<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Clubs admin page content.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("swimclubs.class.php") ;
require_once("swimclubs.forms.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the swim clubs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimClubsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_PROFILE)),
            __("Display a swim club\'s profile - address, phone number,
            web site, notes, etc.")) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamSwimClubsGUIDataList("Swim Clubs",
            "100%", "cluborpoolname, teamname", true) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(false) ;

        return $gdl ;
    }

    /**
     * Construct the content of the SwimClubs Tab Container
     */
    function SwimClubsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style("clear: both;") ;
        $div->add(html_h3("Swim Clubs")) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //  The swimclubid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . "radio", $scriptargs))
            $swimclubid = $scriptargs[WPST_DB_PREFIX . "radio"][0] ;
        else if (array_key_exists("swimclubid", $scriptargs))
            $swimclubid = $scriptargs["swimclubid"] ;
        else
            $swimclubid = null ;

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of swim clubs or process an action.
        //  If there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

       if (array_key_exists("_action", $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists("_form_action", $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        if (empty($scriptargs) || is_null($action))
        {
            $gdl = $this->__buildGDL() ;

            $div->add($gdl) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Swim Clubs Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_SWIMCLUBS_PROFILE_SWIMCLUB:
                    $c = container() ;
                    $profile = new SwimClubProfileInfoTable("Swim Club Profile", "700px") ;
                    $profile->setSwimClubId($swimclubid) ;
                    $profile->constructSwimClubProfile() ;
                    $c->add($profile) ;

                    break ;

                case WPST_SWIMCLUBS_ADD_SWIMCLUB:
                    $form = new WpSwimTeamSwimClubAddForm("Add Swim Club",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Swim Club') ;
                    break ;

                case WPST_SWIMCLUBS_UPDATE_SWIMCLUB:
                    $form = new WpSwimTeamSwimClubUpdateForm("Update Swim Club",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setSwimClubId($swimclubid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Swim Club') ;
                    break ;

                case WPST_SWIMCLUBS_DELETE_SWIMCLUB:
                    $form = new WpSwimTeamSwimClubDeleteForm("Delete Swim Club",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setSwimClubId($swimclubid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Swim Club') ;
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
                $fp->set_form_action($_SERVER['PHP_SELF'] .
                    "?" . $_SERVER['QUERY_STRING']) ;

                //  Display the form again even if processing was successful.

                $fp->set_render_form_after_success(false) ;

                //  If the Form Processor was succesful, display
                //  some statistics about the uploaded file.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $gdl = $this->__buildGDL() ;

                    $div->add($gdl) ;

	                $div->add(html_br(2), $form->form_success()) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Swim Clubs Action Summary') ;
                }
                else
                {
	                $div->add($fp, html_br()) ;
                }
            }
            else if (isset($c))
            {
                $div->add(html_br(2), $c) ;
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
 * Class definition of the swim clubs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class AdminSwimClubsTabContainer extends SwimClubsTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_PROFILE)),
            __("Display a swim club\'s profile - address, phone number,
            web site, notes, etc.")) ;
        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __("Add a swim club\'s information.  Use this action to
            add swim club to the system.  Swim clubs must be in the syetm
            before they can be used as a meet opponent.")) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __("Update a swim club\'s information.  Use this action to
            correct any of the information about a swim club.")) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamSwimClubsAdminGUIDataList("Swim Clubs",
            "100%", "cluborpoolname, teamname", true) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }
}
?>
