<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * User page content.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("users.class.php") ;
require_once("users.forms.class.php") ;
//require_once("users.csv.class.php") ;
require_once("reportgen.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the user
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class UsersTabContainer extends SwimTeamTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_USERS_PROFILE_USER)),
            __("Display a user\'s profile.  Show the user\'s
            detailed information as it will be displayed on the user.")) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the user
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamUsersGUIDataList("Swim Team Web Site Users",
            "100%", "lastname, firstname", false, WPST_USERS_COLUMNS,
            WPST_USERS_TABLES, WPST_USERS_WHERE_CLAUSE) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Set up CSV generation
     *
     * @param mixed $csv - CSV report object
     */
    function __initializeReportGenerator($csv)
    {
        $csv->setFirstName(true) ;
        $csv->setLastName(true) ;
        $csv->setUsername(true) ;
        $csv->setEmailAddress(true) ;
        $csv->setStreetAddress1(true) ;
        $csv->setStreetAddress2(true) ;
        $csv->setStreetAddress3(true) ;
        $csv->setCity(true) ;
        $csv->setStateOrProvince(true) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $csv->setPostalCode(true) ;

        $csv->setCountry(true) ;
        $csv->setPrimaryPhone(true) ;
        $csv->setSecondaryPhone(true) ;
        $csv->setContactInformation(true) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant("WPST_OPTION_USER_OPTION" . $oc) ;
            $lconst = constant("WPST_OPTION_USER_OPTION" . $oc . "_LABEL") ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $csv->setOptionalField($oconst, true) ;
            }
        }
    }

    /**
     * Generate a report of jobs for a user
     *
     * @param int userid
     * @return mixed div container
     */
    function UserJobReport($userid)
    {
        $div = html_div() ;
        $ud = get_userdata($userid) ;
        $name = $ud->first_name . ' ' . $ud->last_name . ' (' . $ud->user_login . ')' ;

        $season = new SwimTeamSeason() ;
        $active = $season->getActiveSeasonId() ;
        $seasonlabel = SwimTeamTextMap::__MapSeasonIdToText($active) ;

        $jobs = array() ;
        $jobs[$active] = new SwimTeamUserJobsInfoTable('User Jobs - ' . $seasonlabel['label'], '100%') ;

        $userjobs = &$jobs[$active] ;

        $userjobs->setUserId($userid) ;
        $userjobs->setSeasonId($active) ;
        $userjobs->constructSwimTeamUserJobsInfoTable() ;

        //  Report credits versus team requirements
        $required = get_option(WPST_OPTION_JOB_CREDITS_REQUIRED) ;
        if ($required === false) $required = 0 ;

        $div->add(html_h3('Current  Season Jobs - ' . $name)) ;
        $div->add($userjobs) ;

        //  Summarize credits versus requirements
 
        $div->add(html_h5(sprintf('%s credits assigned / %s credits required.',
            $userjobs->getCredits(), $required))) ;

        if ($userjobs->getCredits() < $required)
        {
            $notice = html_div('error fade',
                html_h4(sprintf('Notice:  %s has not met the team Jobs requirement of %s credits.',
                $name, $required))) ;
            $div->add($notice) ;
        }

        //  Summarize prior seasons if they exist

        $seasonIds = $season->getAllSeasonIds() ;

        $div->add(html_h3('Prior Season Jobs - ' . $name)) ;

        foreach ($seasonIds as $seasonId)
        {
            if ((int)$seasonId['seasonid'] != (int)$active)
            {
                $seasonlabel = SwimTeamTextMap::__MapSeasonIdToText($seasonId['seasonid']) ;
                $jobs[$seasonId['seasonid']] =
                    new SwimTeamUserJobsInfoTable('User Jobs - ' . $seasonlabel['label'], '100%') ;
                $userjobs = &$jobs[$seasonId['seasonid']] ;
                $userjobs->setUserId($userid) ;
                $userjobs->setSeasonId($seasonId['seasonid']) ;
                $userjobs->constructSwimTeamUserJobsInfoTable() ;
                $div->add($userjobs, html_br()) ;
            }
        }

        return $div ;
    }


    /**
     * Construct the content of the User Tab Container
     */
    function UsersTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style("clear: both;") ;
        $div->add(html_h3("Parents, Guardians, Coaches, Swimmers, etc.")) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //  The userid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . "radio", $scriptargs))
            $userid = $scriptargs[WPST_DB_PREFIX . "radio"][0] ;
        else if (array_key_exists("userid", $scriptargs))
            $userid = $scriptargs["userid"] ;
        else
            $userid = null ;

        //  Show the list of users or process an action.  If
        //  there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

       if (array_key_exists("_action", $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists("_form_action", $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        //  If one of the GDL controls was selected, then
        //  the action maybe confusing the processor.  Flush
        //  any action that doesn't make sense.

        if ($action == WPST_ACTION_SELECT_ACTION) $action = null ;

        //  Is requested action "Execute"?  If so, need
        //  to get the select action from the drop down
        //  list.

        if ($action == WPST_ACTION_EXECUTE)
        {
            if (array_key_exists("_select_action", $scriptargs))
                $action = $scriptargs['_select_action'] ;
            else
                $action = null ;
        }

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of jobs or process an action.  If
        //  there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (empty($scriptargs) || is_null($action))
        {
            $div->add($this->__buildGDL()) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Users Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_PROFILE:
                    $c = container() ;
                    $profile = new SwimTeamUserProfileInfoTable("Web Site User Profile", "500px") ;
                    $profile->set_alt_color_flag(true) ;
                    $profile->set_show_cellborders(true) ;
                    $profile->setId($userid) ;
                    $profile->buildProfile() ;
                    $c->add($profile) ;
                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamUserProfileForm("Update User",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($userid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update User Profile') ;
                    break ;

                case WPST_ACTION_JOBS:
                    $c = container() ;
                    //$userjobs = new SwimTeamUserJobsInfoTable('User Jobs', '100%') ;
                    //$userjobs->setUserId($userid) ;
                    //$userjobs->constructSwimTeamUserJobsInfoTable() ;
                    //$c->add($userjobs) ;
                    $c->add($this->UserJobReport($userid)) ;
                    break ;

                case WPST_ACTION_EXPORT_CSV:
                    $c = container() ;

                    $csv = new SwimTeamUsersReportGeneratorCSV() ;
                    $this->__initializeReportGenerator($csv) ;
                    $csv->generateReport(true) ;
                    $csv->generateCSVFile() ;
                    $arg = urlencode($csv->getCSVFile()) ;

                    $if = html_iframe(sprintf("%s/include/user/reportgenCSV.php?file=%s", WPST_PLUGIN_URL, $arg)) ;
                    $if->set_tag_attributes(array("width" => 0, "height" => 0)) ;
                    $c->add($if) ;
                    $c->add($csv->getReport(true)) ;
                    
                    $div->add(html_div("updated fade",
                        html_h4(sprintf("Swim Team Users Report
                        Generated, %s record%s returned.",
                        $csv->getRecordCount(),
                        $csv->getRecordCount() == 1 ? "" : "s")))) ;
                    
                    $this->setShowInstructions() ;
                    $this->setInstructionsHeader('Export Users CSV Instructions Summary') ;
                    break ;

                default:
                    $div->add(html_h4(sprintf("Unsupported action \"%s\" requested.", $action))) ;
                    break ;
            }

            //  Not all actions are form based ...

            if (isset($form))
            {
                //  Create the form processor

                $fp = new FormProcessor($form) ;
                //$fp->set_form_action($_SERVER['REQUEST_URI']) ;
                $fp->set_form_action($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']) ;
                //

                //  Display the form again even if processing was successful.

                $fp->set_render_form_after_success(false) ;

                //  If the Form Processor was succesful, display
                //  some statistics about the uploaded file.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $div->add($this->__buildGDL()) ;
	                $div->add(html_br(2), $form->form_success()) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Users Action Summary') ;
                }
                else
                {
	                $div->add($fp, html_br()) ;
                }
            }
            else if (isset($c))
            {
                $div->add($c) ;
                $div->add(SwimTeamGUIBackHomeButtons::getButtons()) ;
            }
            else
            {
                $div->add(html_br(2), html_h4("No content to display.")) ;
            }
        }

        $this->add($div) ;
        $this->add($this->buildContextualHelp()) ;
    }
}

/**
 * Class definition of the user
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class AdminUsersTabContainer extends UsersTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __("Update a user\'s information.  Use this action to correct
            any of the information about one or more of user.")) ;
        $table->add_row(html_b(__(WPST_ACTION_JOBS)),
            __("Report the user\'s Job Assignments.")) ;
        $table->add_row(html_b(__(WPST_ACTION_EXPORT_CSV)),
            __("Export the list of users as a CSV file.  A CSV file can
            be loaded into tools such as Microsoft Excel.  All of the user
            information appears in the file, each field separated by the
            comma \",\" character.")) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the user
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamUsersAdminGUIDataList("Swim Team Web Site Users",
            "100%", "lastname, firstname", false, WPST_USERS_COLUMNS,
            WPST_USERS_TABLES, WPST_USERS_WHERE_CLAUSE) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }
}
?>
