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
require_once('widgets.class.php') ;

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimTeamEventsTabContainer extends SwimTeamTabContainer
{
    /**
     * Event Description
     */
    var $__eventdescription = 'Standard Meet Events' ;

    /**
     * Event Group Id
     */
    var $__eventgroupid = WPST_NULL_ID ;

    /**
     * Swim Meet Id
     */
    var $__swimmeetid = WPST_NULL_ID ;

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
     * Set the event group id
     *
     * @param - int - event group id
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the event group id
     *
     * @return - int - event group id
     */
    function getEventGroupId()
    {
        return ($this->__eventgroupid) ;
    }

    /**
     * Set the swim meet id
     *
     * @param - int - swim meet id
     */
    function setSwimMeetId($id)
    {
        $this->__swimmeetid = $id ;
    }

    /**
     * Get the swim meet id
     *
     * @return - int - swim meet id
     */
    function getSwimMeetId()
    {
        return ($this->__swimmeetid) ;
    }

    /**
     * Build verbage to explain what can be done
     *
     * @return TABLETag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        //  Can't load events without a specific eventgroup id

        if ($this->getEventGroupId() != WPST_NULL_ID)
        {
            $table->add_row(html_b(__(WPST_ACTION_EVENTS_LOAD)),
                __('Load the standard events into the swim eventgroup.')) ;
            //$table->add_row(html_b(__(WPST_ACTION_EVENTS_IMPORT)),
            //    __('Import new events from a Hy-tek HYV (.hyv) event file into the swim eventgroup.')
        }

        $table->add_row(html_b(__(WPST_ACTION_EVENTS_ADD)),
            __('Add one or more events.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_IMPORT)),
            __('Import events from a Hy-tek HYV (.hyv) event file.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_UPDATE)),
            __('Update a single event.  Use this action to correct
            any of the information about an indivual event.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_REORDER)),
            __('Reorder the event list by moving events up or down
            the list.  The events will be renumbered starting at 1.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_DELETE)),
            __('Delete a single event.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EVENTS_DELETE_ALL)),
            __('Delete all events.')) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {

        $gdl = new SwimTeamEventsGUIDataList($this->getEventDescription(),
            '100%', 'eventgroup, eventnumber', false) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Construct the content of the Events Tab Container
     *
     * @param eventgroup id - int - optional eventgroup id to load the events from
     */
    function SwimTeamEventsTabContainer($eventgroupid = WPST_NULL_ID, $desc = 'Standard Meet Events')
    {
        $this->setEventGroupId($eventgroupid) ;
        $this->setEventDescription($desc) ;

        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        //$div->add(sprintf('<h2>%s::%s</h2>', basename(__FILE__), __LINE__)) ;
 
        if ($eventgroupid == WPST_NULL_ID)
            $div->add(html_br(), html_h3($this->getEventDescription())) ;
        else
            $div->add(html_h3($this->getEventDescription())) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //print '<pre>' ;
        //print_r($scriptargs) ;
        //print '</pre>' ;
        //  The eventid is the argument which must be
        //  dealt with differently for GET and POST operations

        //  Event Id passed?
        if (array_key_exists('eventid', $scriptargs))
            $eventid = $scriptargs['eventid'] ;
        else if (array_key_exists('_eventid', $scriptargs))
            $eventid = $scriptargs['_eventid'] ;
        else if (array_key_exists('_eventid', $scriptargs))
            $eventid = is_array($scriptargs['_eventid']) ?
                $scriptargs['_eventid'][0] :  $scriptargs['_eventid'] ;
        //else if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
        //    $eventid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else
            $eventid = null ;

        //  Event Group Id passed?
        if (array_key_exists('eventgroupid', $scriptargs))
            $eventgroupid = $scriptargs['eventgroupid'] ;
        else if (array_key_exists('_eventgroupid', $scriptargs))
            $eventgroupid = is_array($scriptargs['_eventgroupid']) ?
                $scriptargs['_eventgroupid'][0] :  $scriptargs['_eventgroupid'] ;
        else
            $eventgroupid = null ;
        $this->setEventGroupId($eventgroupid) ;

        //  Swim Meet Id passed?
        if (array_key_exists('swimmeetid', $scriptargs))
            $swimmeetid = $scriptargs['swimmeetid'] ;
        else if (array_key_exists('_swimmeetid', $scriptargs))
            $swimmeetid = is_array($scriptargs['_swimmeetid']) ?
                $scriptargs['_swimmeetid'][0] :  $scriptargs['_swimmeetid'] ;
        //else if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
        //    $swimmeetid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else
            $swimmeetid = null ;
        $this->setSwimMeetId($swimmeetid) ;

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

        $this->setAction($action) ;
        //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
        //var_dump($action) ;

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

        //  Did action originate from the Event Groups Tab?  If so
        //  null it out so the event action is handled properly.

        //if ($action == WPST_ACTION_EVENTS_MANAGE) $action = null ;

        //  Start processing the action

        if (empty($scriptargs) || is_null($action) || $action == WPST_ACTION_EVENTS_MANAGE)
        {
            $gdl = $this->__buildGDL() ;
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
                    //  Does the HTTP_REFERER already have the eventgroupid
                    //  parameter?  If so, don't want to add another one to
                    //  the form action.

                    $fa = $_SERVER['HTTP_REFERER'] ;
                    $fa = preg_replace('/&?eventgroupid=[1-9][0-9]*/i', '', $fa) ;
                    
                    $fa .= sprintf('&eventgroupid=%s', $eventgroupid) ;
                    $form = new WpSwimTeamEventLoadForm('Load Events', $fa, '600px') ;
                    //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
                    //var_dump($this->getSwimMeetId()) ;                    
                    $form->setMeetId($this->getSwiMmeetId()) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Load Events') ;
                    break ;

                case WPST_ACTION_EVENTS_ADD:
                    //  Does the HTTP_REFERER already have the eventgroupid
                    //  parameter?  If so, don't want to add another one to
                    //  the form action.

                    $fa = $_SERVER['HTTP_REFERER'] ;
                    $fa = preg_replace('/&?eventgroupid=[1-9][0-9]*/i', '', $fa) ;
                    
                    $fa .= sprintf('&eventgroupid=%s', $eventgroupid) ;
                    $form = new WpSwimTeamEventAddForm('Add Event', $fa, 700) ;
                        //$_SERVER['HTTP_REFERER'], 700) ;
                    $form->setMeetId(WPST_NULL_ID) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Event') ;
                    break ;

                case WPST_ACTION_EVENTS_REORDER:
                    $c = container() ;
                    if ($swimmeetid != WPST_NULL_ID)
                        $ajax = new WpSwimTeamEventReorderBySwimMeetAjaxForm($swimmeetid) ;
                    else
                        $ajax = new WpSwimTeamEventReorderByEventGroupAjaxForm($eventgroupid) ;
                    $c->add($ajax) ;
                    break ;

                case WPST_ACTION_EVENTS_IMPORT:
                    $form = new WpSwimTeamEventsImportForm('Import Events',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setEventGroupId($eventgroupid) ;
                    //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
                    //var_dump($eventgroupid) ;                    
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Import Events') ;
                    break ;

                case WPST_ACTION_EVENTS_UPDATE:
                    $form = new WpSwimTeamEventUpdateForm('Update Event',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $form->setEventId($eventid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Event') ;
                    break ;

                case WPST_ACTION_EVENTS_DELETE:
                    $form = new WpSwimTeamEventDeleteForm('Delete Event',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $form->setEventId($eventid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Event') ;
                    break ;

                case WPST_ACTION_EVENTS_DELETE_ALL:
                    $form = new WpSwimTeamEventDeleteAllForm('Delete All Events',
                        $_SERVER['HTTP_REFERER'], 400) ;
                    $form->setMeetId($swimmeetid) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete All Events') ;
                    break ;

                default:
                    //printf('<h2>%s::%s</h2>', basename(__FILE__), __LINE__) ;
                    $c = container() ;
                    $c->add(sprintf('Unkown action requested (%s).', $action)) ;
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

                    get_currentuserinfo() ;

                    $gdl = $this->__buildGDL() ;

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
                //  events, for eventgroup events, the buttons will be handled
                //  by the Swim Meet code.

                //if ($eventgroupid == WPST_NULL_ID)
                //    $div->add(SwimTeamGUIButtons::getBackHomeButtons()) ;
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
class AdminSwimTeamEventsTabContainer extends SwimTeamEventsTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        //  Can't load events without a specific eventgroup id

        if ($this->getEventGroupId() != WPST_NULL_ID)
        {
            $table->add_row(html_b(__(WPST_ACTION_EVENTS_LOAD)),
                __('Load the standard events into the swim eventgroup.')) ;
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
    function __buildGDL()
    {
        $gdl = new SwimTeamEventsAdminGUIDataList('Swim Team Events',
            '100%', 'eventgroupid,eventnumber', false, WPST_EVENTS_DEFAULT_COLUMNS,
            WPST_EVENTS_DEFAULT_TABLES, sprintf('eventgroupid="%s" AND meetid="%s"',
            $this->getEventGroupId(), WPST_NULL_ID)) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;
        $gdl->set_save_vars(array('_recorded_action' => $this->getAction(), 'eventgroupid' => $this->getEventGroupId())) ;

        return $gdl ;
    }
}

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimMeetTabContainer
 */
class SwimMeetEventsTabContainer extends SwimTeamEventsTabContainer
{
}

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see AdminSwimTeamEventsTabContainer
 */
class AdminSwimMeetEventsTabContainer extends AdminSwimTeamEventsTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        //  Can't load events without a specific swim meet id

        if ($this->getSwimMeetId() != WPST_NULL_ID)
        {
            $table->add_row(html_b(__(WPST_ACTION_EVENTS_LOAD)),
                __('Load events into the swim swim meet.')) ;
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
    function __buildGDL()
    {
        //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
        //var_dump($this->getSwimMeetId()) ;
        $gdl = new SwimMeetEventsAdminGUIDataList('Swim Meet Events',
            '100%', 'eventnumber', false, WPST_EVENTS_DEFAULT_COLUMNS,
            WPST_EVENTS_DEFAULT_TABLES, sprintf('meetid="%s"',
            $this->getSwimMeetId())) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;
        $gdl->set_save_vars(array('_recorded_action' => $this->getAction(), 'swimmeetid' => $this->getSwimMeetId())) ;

        //print '<pre>' ;
        //print_r($gdl) ;
        //print '</pre>' ;
        return $gdl ;
    }
}

?>
