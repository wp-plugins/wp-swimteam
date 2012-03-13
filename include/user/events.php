<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Events admin page content.
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

require_once('events.class.php') ;
require_once('events.forms.class.php') ;
require_once('container.class.php') ;

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class EventsTabContainer extends SwimTeamTabContainer
{
    /**
     * Event Description
     */
    var $__eventdescription = 'Standard Meet Events' ;

    /**
     * Meet Id
     */
    var $__meetid = WPST_NULL_ID ;

    /**
     * Set the event description
     *
     * @param - string - event description
     */
    function setEventDescription($desc)
    {
        $this->__eventdescription = $desc ;
    }

    /**
     * Get the event description
     *
     * @return - string - event description
     */
    function getEventDescription()
    {
        return ($this->__eventdescription) ;
    }

    /**
     * Set the meet id
     *
     * @param - int - meet id
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id
     *
     * @return - int - meet id
     */
    function getMeetId()
    {
        return ($this->__meetid) ;
    }

    /**
     * Build verbage to explain what can be done
     *
     * @return TABLETag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        //  Can't load events without a specific meet id

        if ($this->getMeetId() != WPST_NULL_ID)
        {
            $table->add_row(html_b(__(WPST_ACTION_EVENTS_LOAD)),
                __('Load the standard events into the swim meet.')) ;
            //$table->add_row(html_b(__(WPST_ACTION_EVENTS_IMPORT)),
            //    __('Import new events from a Hy-tek HYV (.hyv) event file into the swim meet.')
        }

        $table->add_row(html_b(__(WPST_ACTION_EVENTS_ADD)),
            __('Add one or more events.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_IMPORT)),
            __('Import new events from a Hy-tek HYV (.hyv) event file.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_UPDATE)),
            __('Update a single event.  Use this action to correct
            any of the information about an indivual event.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_REORDER)),
            __('Reorder the event list by moving events up or down
            the list.  The events will be renumbered starting at 1.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_DELETE)),
            __('Delete a single event.')) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL($meetid = WPST_NULL_ID)
    {

        $gdl = new SwimMeetEventsGUIDataList($this->getEventDescription(),
            '100%', 'eventnumber', false) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;
        $gdl->set_save_vars(array('swimmeetid' => $meetid)) ;

        return $gdl ;
    }

    /**
     * Construct the content of the Events Tab Container
     *
     * @param meet id - int - optional meet id to load the events from
     */
    function EventsTabContainer($meetid = WPST_NULL_ID, $desc = 'Standard Meet Events')
    {
        $this->setMeetId($meetid) ;
        $this->setEventDescription($desc) ;

        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
 
        if ($meetid == WPST_NULL_ID)
            $div->add(html_br(), html_h3($this->getEventDescription())) ;
        else
            $div->add(html_h3($this->getEventDescription())) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //  The eventid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
            $eventid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else if (array_key_exists('eventid', $scriptargs))
            $eventid = $scriptargs['eventid'] ;
        else
            $eventid = null ;

        //  So, how did we get here?  If $scriptargs is empty
        //  then it wasn't via a form submission.

        //  Show the list of events or process an action.  If
        //  there is no $scriptargs or if there isn't an action
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

        //  Did action originate from the Meets Tab?  If so
        //  null it out so the event action is handled properly.

        if ($action == WPST_ACTION_EVENTS) $action = null ;

        //  Start processing the action

        if (empty($scriptargs) || is_null($action))
        {
            $this->setMeetId($meetid) ;
            $gdl = $this->__buildGDL($this->getMeetId()) ;
            $div->add($gdl) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Events Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_EVENTS_PROFILE:
                    $c = container() ;
                    $profile = new EventProfileInfoTable('Event Profile', '700px') ;
                    $profile->setEventId($eventid) ;
                    $profile->constructEventProfile() ;
                    $c->add($profile) ;

                    break ;

                case WPST_ACTION_EVENTS_LOAD:
                    //  Does the HTTP_REFERER already have the swimmeetid
                    //  parameter?  If so, don't want to add another one to
                    //  the form action.

                    $fa = $_SERVER['HTTP_REFERER'] ;
                    $fa = preg_replace('/&?swimmeetid=[1-9][0-9]*/i', '', $fa) ;
                    
                    $fa .= sprintf('&swimmeetid=%s', $meetid) ;
                    $form = new WpSwimMeetEventLoadForm('Load Events', $fa, '600px') ;
                    $form->setMeetId($meetid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Load Events') ;
                    break ;

                case WPST_ACTION_EVENTS_ADD:
                    //  Does the HTTP_REFERER already have the swimmeetid
                    //  parameter?  If so, don't want to add another one to
                    //  the form action.

                    $fa = $_SERVER['HTTP_REFERER'] ;
                    $fa = preg_replace('/&?swimmeetid=[1-9][0-9]*/i', '', $fa) ;
                    
                    $fa .= sprintf('&swimmeetid=%s', $meetid) ;
                    $form = new WpSwimMeetEventAddForm('Add Event', $fa, 700) ;
                        //$_SERVER['HTTP_REFERER'], 700) ;
                    $form->setMeetId($meetid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Event') ;
                    break ;

                case WPST_ACTION_EVENTS_REORDER:
                    $c = container() ;
                    $ajax = new WpSwimMeetEventReorderAjaxForm($meetid) ;
                    $c->add($ajax) ;
                    break ;

                case WPST_ACTION_EVENTS_IMPORT:
                    $form = new WpSwimMeetEventsImportForm('Import Events',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    //$form->setMeetId($meetid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Import Events') ;
                    break ;

                case WPST_ACTION_EVENTS_UPDATE:
                    $form = new WpSwimMeetEventUpdateForm('Update Event',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setMeetId($meetid) ;
                    $form->setEventId($eventid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Event') ;
                    break ;

                case WPST_ACTION_EVENTS_UPDATE:
                    $form = new WpSwimMeetEventUpdateForm('Update Event',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setMeetId($meetid) ;
                    $form->setEventId($eventid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Event') ;
                    break ;

                case WPST_ACTION_EVENTS_DELETE:
                    $form = new WpSwimMeetEventDeleteForm('Delete Event',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setMeetId($meetid) ;
                    $form->setEventId($eventid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Event') ;
                    break ;

                default:
                    $c = container() ;
                    $c->add(sprintf('Unkown action requested (%s).', $action)) ;
                    break ;
            }

            //  Not all actions are form based ...

            if (isset($form))
            {
                //  Create the form processor

                $fp = new FormProcessor($form) ;
                $fp->set_form_action($_SERVER['PHP_SELF'] .
                    '?' . $_SERVER['QUERY_STRING']) ;

                //  Display the form again even if processing was successful.

                $fp->set_render_form_after_success(false) ;

                //  If the Form Processor was succesful, display
                //  some statistics about the uploaded file.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    get_currentuserinfo() ;

                    $gdl = $this->__buildGDL($this->getMeetId()) ;

                    $div->add($gdl) ;

	                $div->add(html_br(2), $form->form_success()) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Events Action Summary') ;
                }
                else
                {
	                $div->add($fp) ;
                }
            }
            else if (isset($c))
            {
                $div->add($c) ;

                //  Only need to add buttons when managing standard
                //  events, for meet events, the buttons will be handled
                //  by the Swim Meet code.

                if ($meetid == WPST_NULL_ID)
                    $div->add(SwimTeamGUIBackHomeButtons::getButtons()) ;
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
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class AdminEventsTabContainer extends EventsTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        //  Can't load events without a specific meet id

        if ($this->getMeetId() != WPST_NULL_ID)
        {
            $table->add_row(html_b(__(WPST_ACTION_EVENTS_LOAD)),
                __('Load the standard events into the swim meet.')) ;
        }

        $table->add_row(html_b(__(WPST_ACTION_EVENTS_ADD)),
            __('Add one or more events.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_UPDATE)),
            __('Update a single event.  Use this action to correct
            any of the information about an indivual event.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_REORDER)),
            __('Reorder the event list by moving events up or down
            the list.  The events will be renumbered starting at 1.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_DELETE)),
            __('Delete a single event.')) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL($meetid = WPST_NULL_ID)
    {
        $gdl = new SwimMeetEventsAdminGUIDataList('Swim Meet Events',
            '100%', 'eventnumber', false, WPST_EVENTS_DEFAULT_COLUMNS,
            WPST_EVENTS_DEFAULT_TABLES, sprintf('meetid="%s"',
            $this->getMeetId())) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;
        $gdl->set_save_vars(array('swimmeetid' => $meetid)) ;

        return $gdl ;
    }
}
?>
