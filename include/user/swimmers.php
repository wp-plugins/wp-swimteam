<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swimmers page content.
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

require_once('swimmers.class.php') ;
require_once('swimmers.forms.class.php') ;
require_once('roster.forms.class.php') ;
require_once('container.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the swimmers
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimmersTabContainer extends SwimTeamTabContainer
{
    /**
     * Container Label Prefix
     */
    var $_tab_prefix = 'My' ;

    /**
     * Return the proper form
     *
     * @return mixed
     */
    function __getForm($label, $action, $width)
    {
        return new WpSwimTeamSwimmerOptInOutForm($label, $action, $width) ;
    }

    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $optin = get_option(WPST_OPTION_OPT_IN_LABEL) ;
        $optout = get_option(WPST_OPTION_OPT_OUT_LABEL) ;

        $table->add_row(html_b(__(WPST_ACTION_PROFILE)),
            __('Display a swimmer\'s profile.  Show the swimmers
            detailed information as it will be displayed on the roster.')) ;
        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __('Add a swimmer to your list of swimmers.  This will add
            a swimmer to the system.  All swimmers must be entered into the
            system before they can be registered for a swim team season.
            A swimmer is only added to the system once - once added, a
            swimmer can be registered for the current season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __('Update a swimmer\'s information.  Use this action to correct
            any of the information about one or more of your swimmers.')) ;
        $table->add_row(html_b(__(WPST_ACTION_REGISTER)),
            __('Register a swimmer for the current season.  Use this
            action if your swimmer would like to participate in the current
            season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UNREGISTER)),
            __('Unregister a swimmer for the current season.  Use this
            action if your swimmer is no longer interested in particpating
            in the current season.')) ;
        $table->add_row(html_b(__($optin)), __('Explicitly ' .
            strtolower($optin) . ' for a swim meet which requires
            swimmers to commit their intent to swim.')) ;
        $table->add_row(html_b(__($optout)),
            __('Explicitly ' . strtolower($optout) . ' from a swim meet
            which requires swimmers to commit their intent NOT to swim.
            you may ' . strtolower($optout) . ' of the entire meet or
            selected events.  You may also ' . strtolower($optout) .
            ' from an meet or event previously committed to.')) ;

        return $table ;
    }

    /**
     * Build query select clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildSelectClause()
    {
        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        $select_clause = sprintf(WPST_SWIMMERS_COLUMNS,
            $cutoffdate, $cutoffdate, $cutoffdate) ;

        return $select_clause ;
    }

    /**
     * Build query where clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildWhereClause()
    {
        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        //  WP's global userdata
        global $userdata ;

        get_currentuserinfo() ;
            
        //  Limit the selection to only swimmers who are
        //  connected to the active user
 
        $where_clause = sprintf('%s.contact1id = "%s" OR
            %s.contact2id = "%s"', WPST_SWIMMERS_TABLE,
            $userdata->ID, WPST_SWIMMERS_TABLE, $userdata->ID) ;

        return $where_clause ;
    }

    /**
     * Build the GUI DataList used to display the swimmers
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamSwimmersGUIDataList($this->_tab_prefix .
            'Swimmers', '100%', 'lastname, firstname', false,
            $this->__buildSelectClause(), WPST_SWIMMERS_DEFAULT_TABLES,
            $this->__buildWhereClause()) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Construct the content of the Swimmers Tab Container
     */
    function SwimmersTabContainer()
    {
        global $userdata ;

        get_currentuserinfo() ;

        //  The container content is either a GUIDataList of 
        //  the swimmers which have been defined OR form processor
        //  content to profile, add, or update swimmers.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.

        $div = html_div() ;
        $div->set_style('clear: both;') ;

        //  If the end user hasn't completed their family profile,
        //  nag the user as operation of the plugin needs the data.

        $user = new SwimTeamUserProfile() ;

        $noprofile = (!$user->userProfileExistsByUserId($userdata->ID)) ;

        if ($noprofile)
        {
            $un = html_i(html_b($userdata->user_login)) ;

            $warning = html_div(null, html_b('Error:')) ;
            $message = html_div(null, sprintf('User profile
                information for username %s has not been entered.',
                $un->render()), html_br(), 'You must select the ',
                html_a(sprintf('%s/wp-admin/admin.php?page=swimteam.php&tab=2',
                get_bloginfo('url')), 'My Profile'), 'tab and complete
                your user profile information before proceding.') ;
            $warning->set_style('display:inline-block;clear:both;float:left;') ;
            $message->set_style('display:inline-block;margin-left:15px;') ;
            $messagebox = html_div('error fade', $warning, $message) ;
            $messagebox->set_style('padding: 10px;') ;
            $div->add($messagebox) ;
        }

        $div->add(html_h3($this->_tab_prefix . ' Swimmers')) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //  The swimmerid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
            $swimmerid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else if (array_key_exists('swimmerid', $scriptargs))
            $swimmerid = $scriptargs['swimmerid'] ;
        else
            $swimmerid = null ;

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of swimmers or process an action.  If
        //  there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

       if (array_key_exists('_action', $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists('_form_action', $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        //  If one of the GDL controls was selected, then
        //  the action maybe confusing the processor.  Flush
        //  any action that doesn't make sense.

        if ($action == WPST_ACTION_SELECT_ACTION) $action = null ;

        //  Is requested action 'Execute'?  If so, need
        //  to get the select action from the drop down
        //  list.

        if ($action == WPST_ACTION_EXECUTE)
        {
            if (array_key_exists('_select_action', $scriptargs))
                $action = $scriptargs['_select_action'] ;
            else
                $action = null ;
        }

        if (empty($scriptargs) || is_null($action))
        {

            $gdl = $this->__buildGDL() ;

            $div->add($gdl, html_div_center(html_h6('Age displayed in
                parentheses is computed relative to the Swim Team age
                group cutoff date.'))) ;

            //  If the user profile is incomplete, eliminate any actions
            if ($noprofile)
            {
                $gdl->disableAllActions() ;
            }

            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader($this->_tab_prefix . ' Swimmers Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_ADD:
                    $form = new WpSwimTeamSwimmerAddForm('Add Swimmer',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Swimmer Profile Instructions') ;
                    break ;

                case WPST_ACTION_PROFILE:
                    $c = container() ;
                    $profile = new SwimTeamSwimmerProfileInfoTable('Swimmer Profile', '500px') ;
                    $profile->set_alt_color_flag(true) ;
                    $profile->set_show_cellborders(true) ;
                    $profile->setId($swimmerid) ;
                    $profile->constructSwimmerProfile() ;
                    $c->add($profile) ;

                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamSwimmerUpdateForm('Update Swimmer',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Swimmer Profile Instructions') ;
                    break ;

                case WPST_ACTION_REGISTER:
                case WPST_ACTION_REGISTER . ' (' . WPST_SEASON . ')':
                    $form = new WpSwimTeamSwimmerRegisterForm('Register Swimmer',
                        $_SERVER['HTTP_REFERER'], 500) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Register Swimmer Instructions') ;
                    break ;

                case WPST_ACTION_UNREGISTER:
                case WPST_ACTION_UNREGISTER . ' (' . WPST_SEASON . ')':
                    $form = new WpSwimTeamSwimmerUnregisterForm('Unregister Swimmer',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Unregister Swimmer Instructions') ;
                    break ;

                case WPST_ACTION_DELETE:
                    $form = new WpSwimTeamSwimmerDeleteForm('Delete Swimmer',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Swimmer Instructions') ;
                    break ;

                case WPST_ACTION_GLOBAL_UPDATE:
                    $form = new WpSwimTeamSwimmerGlobalUpdateForm('Global Swimmer Update',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Global Update Instructions') ;
                    break ;

                 case WPST_ACTION_OPT_IN:
                 case get_option(WPST_OPTION_OPT_IN_LABEL) . ' (' . WPST_SWIMMEET . ')':
                    $optin = ucwords(get_option(WPST_OPTION_OPT_IN_LABEL)) ;
                    $form = $this->__getForm('Swimmer:  ' .
                        $optin, $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setAction(WPST_ACTION_OPT_IN) ;
                    $form->setSwimmerId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader($optin . ' Instructions') ;
                    break ;

                 case WPST_ACTION_OPT_OUT:
                 case get_option(WPST_OPTION_OPT_OUT_LABEL) . ' (' . WPST_SWIMMEET . ')':
                    $optout = ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL)) ;
                    $form = $this->__getForm('Swimmer:  ' .
                        $optout, $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setAction(WPST_ACTION_OPT_OUT) ;
                    $form->setSwimmerId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader($optout . ' Instructions') ;
                    break ;

                default:
                    $div->add(html_h4(sprintf('Unsupported action "%s" requested.', $action))) ;
                    break ;
            }

            //  Not all actions are form based ...

            if (isset($form))
            {
                //  Create the form processor

                $fp = new FormProcessor($form) ;
                $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

                //  Display the form again even if processing was successful.

                $fp->set_render_form_after_success(false) ;

                //  If the Form Processor was succesful, display
                //  some statistics about the uploaded file.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $gdl = $this->__buildGDL() ;

                    $div->add($gdl, html_div_center(html_h6('Age displayed in
                        parentheses is computed relative to the Swim Team age
                        group cutoff date.'))) ;

	                $div->add(html_br(2), $form->form_success()) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader($this->_tab_prefix . ' Swimmers Action Summary') ;
                }
                else
                {
	                $div->add($fp, html_br()) ;
                }
            }
            else if (isset($c))
            {
                $div->add(html_br(2), $c) ;
                $div->add(SwimTeamGUIButtons::getButton('Return to Swimmers')) ;
            }
            else
            {
                $div->add(html_br(2), html_h4('No content to display.')) ;
            }
        }

        $this->add($div) ;
        $this->add($this->buildContextualHelp()) ;
    }
}

/**
 * Class definition of the roster
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class AdminSwimmersTabContainer extends SwimmersTabContainer
{
    /**
     * Container Label Prefix
     */
    var $_tab_prefix = 'All' ;

    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_GLOBAL_UPDATE)),
            __('Change the value of selected fields across all swimmers.')) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamSwimmersAdminGUIDataList($this->_tab_prefix .
            'Swimmers', '100%', 'lastname, firstname', false,
            $this->__buildSelectClause(), WPST_SWIMMERS_DEFAULT_TABLES,
            $this->__buildWhereClause()) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Build query where clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildWhereClause()
    {
        return WPST_SWIMMERS_DEFAULT_WHERE_CLAUSE ;
    }
}
?>
