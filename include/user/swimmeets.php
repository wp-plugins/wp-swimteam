<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Meets admin page content.
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

require_once('swimteam.include.php') ;
require_once('swimmeets.class.php') ;
require_once('swimmeets.forms.class.php') ;
require_once('events.class.php') ;
require_once('jobs.forms.class.php') ;
require_once('container.class.php') ;
require_once('textmap.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the swim meets
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimMeetsTabContainer extends SwimTeamTabContainer
{
    /**
     * Return the proper form
     *
     * @return mixed
     */
    function __getForm($label, $action, $width)
    {
        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_EVENT)
            return new WpSwimTeamSwimMeetEventOptInOutForm($label, $action, $width) ;
        else
            return new WpSwimTeamSwimMeetOptInOutForm($label, $action, $width) ;
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

        $table->add_row(html_b(__(WPST_ACTION_DETAILS)),
            __('Display a swim meet\'s detailed information - date, time,
            type, location, etc.')) ;
        $table->add_row(html_b(__(WPST_ACTION_JOBS)),
            __('Assign a user to a specific job assigment.')) ;
        $table->add_row(html_b(__(WPST_ACTION_RESULTS)),
            __('Display detailed results for a swim meet.')) ;
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
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL($swimmeerid = WPST_NULL_ID)
    {
        $gdl = new SwimMeetsGUIDataList('Swim Meets', '100%', 'meetdate', true) ;
        $gdl->set_alternating_row_colors(true) ;
        //$gdl->set_save_vars(array('swimmeetid' => $swimmeetid)) ;

        return $gdl ;
    }

    /**
     * Construct the content of the SwimMeets Tab Container
     */
    function SwimMeetsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style('clear: both;') ;
        $div->add(html_h3('Swim Meets')) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_POST, $_GET) ;
        $actions_allowed_without_swimmeetid = array(
            WPST_ACTION_ADD
        ) ;

        //printf('<h3>%s::%s</h3>', basename(__FILE__), __LINE__) ;
        //print '<pre>' ;
        //print_r($scriptargs) ;
        //print '</pre>' ;
        //  The swimmeetid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists('swimmeetid', $scriptargs))
            $swimmeetid = $scriptargs['swimmeetid'] ;
        else if (array_key_exists('_swimmeetid', $scriptargs))
            $swimmeetid = is_array($scriptargs['_swimmeetid']) ?
                $scriptargs['_swimmeetid'][0] :  $scriptargs['_swimmeetid'] ;
        //else if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
        //    $swimmeetid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else
            $swimmeetid = null ;

        //printf('<h3>%s::%s</h3>', basename(__FILE__), __LINE__) ;
        //var_dump($swimmeetid) ;
        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of swim meets or process an action.
        //  If there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (array_key_exists('_action', $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists('_form_action', $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        //  If one of the GDL controls was selected, then
        //  the action maybe confusing the processor.  Flush
        //  any action that doesn't make sense.  Look for the
        //  recorded action when this happens.

        if ($action == WPST_ACTION_SELECT_ACTION)
        {
            //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
            if (array_key_exists('_recorded_action', $scriptargs))
                $action = $scriptargs['_recorded_action'] ;
            else
                $action = null ;
        }

        //  Need to map opt-in and opt-out labels into the proper action

        if ($action == get_option(WPST_OPTION_OPT_IN_LABEL))
            $action = WPST_ACTION_OPT_IN ;

        if ($action == get_option(WPST_OPTION_OPT_OUT_LABEL))
            $action = WPST_ACTION_OPT_OUT ;

        //  Process the action

        if (empty($scriptargs) || is_null($action))
        {
            $div->add($gdl = $this->__buildGDL($swimmeetid)) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Swim Meeets Action Summary') ;
        }
        else if (is_null($swimmeetid) && !in_array($action, $actions_allowed_without_swimmeetid))
        {
            $div->add(html_div('error fade',
                html_h4('You must select a swim meet in order to perform this action.'))) ;
            $div->add($this->__buildGDL($swimmeetid)) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Swim Meeets Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_DETAILS:
                    $c = container() ;
                    $profile = new SwimMeetInfoTable('Swim Meet Details', '500px') ;
                    $profile->setSwimMeetId($swimmeetid) ;
                    $profile->constructSwimMeetInfoTable() ;
                    $c->add($profile) ;

                    break ;

                case WPST_ACTION_ADD:
                    $form = new WpSwimTeamSwimMeetAddForm('Add Swim Meet',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Swim Meeets') ;
                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamSwimMeetUpdateForm('Update Swim Meet',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setMeetId($swimmeetid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Swim Meeets') ;
                    break ;

                case WPST_ACTION_JOBS:
                    $form = new WpSwimTeamSwimMeetJobAssignForm('Assign Swim Meet Jobs',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setMode(WPST_SWIMMEET) ;
                    $form->setMeetId($swimmeetid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Assign Swim Meeet Jobs') ;
                    break ;

                case WPST_ACTION_JOB_REMINDERS:
                    $form = new WpSwimTeamSwimMeetJobRemindersForm('Send Job Reminders for Swim Meet',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setMeetId($swimmeetid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Send Job Reminder Emails') ;
                    break ;

                case WPST_ACTION_EXPORT_ENTRIES:
                    $swimmeet = SwimTeamTextMap::__mapMeetIdToText($swimmeetid) ;
                    //  Does the meet have events to load results against?
                   
                    $event = new SwimMeetEvent() ;
                    $event->setMeetId($swimmeetid) ;
                    $eventIds = $event->getAllEventIdsByMeetId($swimmeetid) ;

                    if (!empty($eventIds))
                    {
                        $form = new WpSwimTeamSwimMeetExportEntriesForm('Export Meet Entries:  ' .
                            sprintf('%s vs %s on %s', $swimmeet['location'], $swimmeet['opponent'],
                            $swimmeet['date']), SwimTeamUtils::GetPageURI(), 600) ;
                        $form->setMeetId($swimmeetid) ;
                    }
                    else
                    {
                        $div->add(html_div('error fade', html_h4('Swim meet (' .
                            sprintf('%s vs %s on %s', $swimmeet['location'], $swimmeet['opponent'],
                            $swimmeet['date']) .  ') does not have any events to export entries for.
                            Set up meet events before exporting entries.'))) ;

                        $c = Container() ;
                        $c->add($this->__buildGDL($swimmeetid)) ;
                        $this->setShowActionSummary() ;
                        $this->setActionSummaryHeader('Swim Meeets Action Summary') ;
                    }
                    break ;

                case WPST_ACTION_IMPORT_RESULTS:

                    //  Does the meet have events to load results against?
                   
                    $event = new SwimMeetEvent() ;
                    $event->setMeetId($swimmeetid) ;
                    $eventIds = $event->getAllEventIdsByMeetId($swimmeetid) ;

                    if (!empty($eventIds))
                    {
                        $form = new WpSwimTeamSwimMeetImportResultsForm('Import Swim Meet Results',
                            SwimTeamUtils::GetPageURI(), 600) ;
                        $form->setMeetId($swimmeetid) ;
                    }
                    else
                    {
                        //$c = container() ;
                        $msg = html_div('updated fade', html_h4('Swim meet does not have any events to import results against.  Set up meet events before importing results.')) ;
                        $div->add($msg) ;

                        $div->add($this->__buildGDL($swimmeetid)) ;
                        $this->setShowActionSummary() ;
                        $this->setActionSummaryHeader('Swim Meeets Action Summary') ;
                    }
                    break ;

                case WPST_ACTION_OPT_IN:
                    $optin = ucwords(get_option(WPST_OPTION_OPT_IN_LABEL)) ;
                    //$form = new WpSwimTeamSwimMeetOptInOutForm('Swim Meet:  ' .
                    //    $optin, SwimTeamUtils::GetPageURI(), 600) ;
                    $form = $this->__getForm('Swim Meet:  ' .
                        $optin, SwimTeamUtils::GetPageURI(), 700) ;
                    $form->setAction(WPST_ACTION_OPT_IN) ;
                    $form->setMeetId($swimmeetid) ;
                    break ;

                case WPST_ACTION_OPT_OUT:
                    $optout = ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL)) ;
                    //$form = new WpSwimTeamSwimMeetOptInOutForm('Swim Meet:  ' .
                        //$optout, SwimTeamUtils::GetPageURI(), 600) ;
                    $form = $this->__getForm('Swim Meet:  ' .
                        $optout, SwimTeamUtils::GetPageURI(), 700) ;
                    $form->setAction(WPST_ACTION_OPT_OUT) ;
                    $form->setMeetId($swimmeetid) ;
                    break ;

                case WPST_ACTION_DELETE:
                    /*
                    $form = new WpSwimTeamSwimMeetDeleteForm('Delete Swim Meet',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setSwimMeetId($swimmeetid) ;
                    break ;
                     */

                case WPST_ACTION_EVENTS_MANAGE:
                case WPST_ACTION_EVENTS_LOAD:
                case WPST_ACTION_EVENTS_ADD:
                case WPST_ACTION_EVENTS_UPDATE:
                case WPST_ACTION_EVENTS_REORDER:
                case WPST_ACTION_EVENTS_DELETE:
                case WPST_ACTION_EVENTS_DELETE_ALL:

                    $swimmeet = new SwimMeet() ;
                    $swimmeet->loadSwimMeetByMeetId($swimmeetid) ;
                    

                    //  Handle meets without an opponent ...

                    if ($swimmeet->getMeetType() === WPST_DUAL_MEET)
                    {
                        $opponent = new SwimClubProfile() ;
                        $opponent->loadSwimClubBySwimClubId($swimmeet->getOpponentSwimClubId()) ;
                    
                        $desc = sprintf('%s vs %s %s', $swimmeet->getMeetDateAsDate(),
                            $opponent->getClubOrPoolName(), $opponent->getTeamName()) ;
                    }
                    else
                    {
                        $desc = $swimmeet->getMeetDescription() ;

                        if (!empty($desc))
                            $desc = sprintf('%s %s', $swimmeet->getMeetDate(), $desc) ;
                    else
                        $desc = sprintf('%s %s', $swimmeet->getMeetDate(), ucwords($swimmeet->getMeetType())) ;
                    }

                    //  Leverage the Events tab management code

                    require_once('events.php') ;
                    //printf('<h3>%s::%s</h3>', basename(__FILE__), __LINE__) ;
                    //var_dump($swimmeetid) ;
                    $c = new AdminSwimMeetEventsTabContainer($swimmeetid, $desc) ;

                    break ;

                case WPST_ACTION_EXPORT_ENTRIES:
                    //  Does the meet have events to load results against?
                   
                    $c = container() ;

                    $event = new SwimMeetEvent() ;
                    $event->setMeetId($swimmeetid) ;
                    $eventIds = $event->getAllEventIdsByMeetId($swimmeetid) ;

                    if (!empty($eventIds))
                    {
                        require_once('sdif.class.php') ;
                        //$form = new WpSwimTeamSwimMeetExportEntriesForm('Export Entries Swim Meet',
                        //    SwimTeamUtils::GetPageURI(), 600) ;
                        //$form->setMeetId($swimmeetid) ;

                        $sdif = new SDIFMeetEntriesPyramid() ;
                        $sdif->setSwimMeetId($swimmeetid) ;
                        $sdif->setSDIFDebugFlag(false) ;
                        $sdif->setZeroTimeMode(WPST_SDIF_USE_BLANKS) ;
                        $sdif->generateSDIF($swimmeetid) ;
                        $sdif->generateSDIFFile() ;

                        $arg = urlencode($sdif->getSDIFFile()) ;

                        $if = html_iframe(sprintf('%s/include/user/swimmeet_entries_SDIF.php?file=%s', WPST_PLUGIN_URL, $arg)) ;
                        $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                        $c->add($if) ;

                        $msg = html_div('updated fade',
                            html_h4(sprintf('%s meet entries exported in SDIF format.', $sdif->getSDIFCount()))) ;
                        $c->add($msg) ;
                    }
                    else
                    {
                        $swimmeet = SwimTeamTextMap::__mapMeetIdToText($swimmeetid) ;
                        $msg = html_div('error fade', html_h4('Swim meet (' .
                            sprintf('%s vs %s on %s', $swimmeet['location'], $swimmeet['opponent'],
                            $swimmeet['date']) .  ') does not have any events to export entries for.
                            Set up meet events before exporting entries.')) ;
                        $c->add($msg) ;
                    }

                    $gdl = $this->__buildGDL($swimmeetid) ;

                    $div->add($gdl) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Swim Meeets Action Summary') ;

                    break ;

                case WPST_ACTION_RESULTS:
                case WPST_ACTION_EXPORT_RESULTS:
                case WPST_ACTION_SCRATCH_REPORT:
                case WPST_ACTION_DETAILS:
                    $c = container() ;
                    $c->add(html_h3(sprintf('Requested action (%s) has not been implemented yet.', $action))) ;
                    $c->add(html_br(2)) ;
                    break ;

                default:
                    $c = container() ;
                    $c->add(html_h3(sprintf('Unkown action requested (%s).', $action))) ;
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
                    if ($action == WPST_ACTION_IMPORT_RESULTS)
                    {
                        $c = container() ;
                        $c->add($form->get_file_info_table()) ;
                        //$div->add($c, html_br(),
                        //    SwimTeamGUIButtons::getBackHomeButtons()) ;
                    }
                    else if ($action == WPST_ACTION_EXPORT_ENTRIES)
                    {
                        $div->add($this->__buildGDL($swimmeetid)) ;
                        $this->setShowActionSummary() ;
                        $this->setActionSummaryHeader('Swim Meeets Action Summary') ;

                        $c = container() ;
                        $if = html_iframe(sprintf('%s/include/user/swimmeet_entries_SDIF.php?file=%s',
                            WPST_PLUGIN_URL, $form->getSDIFFile())) ;
                        $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                        $c->add($if) ;
                        $div->add($c) ;
                    }
                    else
                    {
                        //  Need to show a different GDL based on whether or
                        //  not the end user has a level of Admin ability.

                        $div->add($this->__buildGDL($swimmeetid)) ;
                        $this->setShowActionSummary() ;
                        $this->setActionSummaryHeader('Swim Meeets Action Summary') ;
                    }

	                $div->add(html_br(2), $form->form_success()) ;
                }
                else
                {
	                $div->add($fp, html_br()) ;
                }
            }
            else if (isset($c))
            {
                $div->add($c) ;
                //$div->add($c, html_br(), SwimTeamGUIButtons::getButton('Return to Swim Meets')) ;
            }
            else
            {
                $div->add(html_div('error fade', html_h4('No content to display.'))) ;
            }
        }

        $this->add($div) ;
        $this->add($this->buildContextualHelp()) ;
    }
}
/**
 * Class definition of the jobs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class AdminSwimMeetsTabContainer extends SwimMeetsTabContainer
{
    /**
     * Return the proper form
     *
     * @return mixed
     */
    function __getForm($label, $action, $width)
    {
        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_EVENT)
            return new WpSwimTeamSwimMeetEventOptInOutAdminForm($label, $action, $width) ;
        else
            return new WpSwimTeamSwimMeetOptInOutAdminForm($label, $action, $width) ;
    }

    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $optin = ucwords(get_option(WPST_OPTION_OPT_IN_LABEL)) ;
        $optout = ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL)) ;

        $table->add_row(html_b(__(WPST_ACTION_RESULTS)),
            __('Display a swim meet\'s results.')) ;
        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __('Add a swim meet information.  Use this action to
            add swim meet to the system.  Swim meets must be in the system
            before they can be used for seeding, results, etc.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __('Update a swim meet information.  Use this action to
            correct any of the information about a swim meet and to enter
            scores.')) ;
        $table->add_row(html_b(__(WPST_ACTION_DELETE)),
            __('Delete a swim meet.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS)),
            __('Manage the events for a swim meet.')) ;
        $table->add_row(html_b(__(WPST_ACTION_IMPORT_RESULTS)),
            __('Import the results for a swim meet.')) ;
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
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL($swimmeetid = WPST_NULL_ID)
    {
        $gdl = new SwimMeetsAdminGUIDataList('Swim Meets',
            '100%', 'meetdate', true) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;
        //$gdl->set_save_vars(array('swimmeetid' => $swimmeetid)) ;

        return $gdl ;
    }
}
?>
