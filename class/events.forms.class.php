<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: events.forms.class.php 921 2012-06-28 22:21:32Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @package Wp-SwimTeam
 * @subpackage Events
 * @version $Revision: 921 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2012-06-28 18:21:32 -0400 (Thu, 28 Jun 2012) $
 *
 */

require_once('forms.class.php') ;
require_once('seasons.class.php') ;
require_once('events.class.php') ;
require_once('agegroups.class.php') ;
require_once('portlets.class.php') ;

/**
 * Construct the Add Event form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamEventAddForm extends WpSwimTeamForm
{
    /**
     * event id property
     */
    var $__eventid ;

    /**
     * event group id property
     */
    var $__eventgroupid ;

    /**
     * meet id property
     */
    var $__meetid ;

    /**
     * Set the event id property
     */
    function setEventId($id)
    {
        $this->__eventid = $id ;
    }

    /**
     * Get the event id property
     */
    function getEventId()
    {
        return $this->__eventid ;
    }

    /**
     * Set the event group id property
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the event group id property
     */
    function getEventGroupId()
    {
        return $this->__eventgroupid ;
    }

    /**
     * Set the meet id property
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id property
     */
    function getMeetId()
    {
        return $this->__meetid ;
    }

    /**
     * Get the array of coarse key and value pairs
     *
     * @return mixed - array of coarse key value pairs
     */
    function _courseSelections()
    {
        //  Course options and labels 

        $s = array(
            WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL => WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE
           ,WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL => WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE
           ,WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL => WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE
           ,WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL => WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE
        ) ;

         return $s ;
    }

    /**
     * Get the array of event type key and value pairs
     *
     * @return mixed - array of event type key value pairs
     */
    function _strokeSelections()
    {
        //  Stroke options and labels 

        $s = array(
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL => WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL => WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL => WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL => WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL => WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL => WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL => WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE
        ) ;

         return $s ;
    }

    /**
     * Get the array of agegroup key and value pairs
     *
     * @return mixed - array of agegroup key value pairs
     */
    function _agegroupSelections()
    {
        //  AgeGroup options and labels 

        $s = array() ;

        $agegroup = new SwimTeamAgeGroup() ;
        $agegroupIds = $agegroup->getAgeGroupIds() ;

        foreach ($agegroupIds as $agegroupId)
        {
            $agegroup->loadAgeGroupById($agegroupId['id']) ;
            $s[$agegroup->getAgeGroupText()] = $agegroup->getId() ;
        }

        return $s ;
    }

    /**
     * Get the array of eventgroup key and value pairs
     *
     * @return mixed - array of eventgroup key value pairs
     */
    function _eventgroupSelections()
    {
        //  EventGroup options and labels 

        $s = array() ;
        //$s = array(ucwords(WPST_NONE) => WPST_NULL_ID) ;

        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroupIds = $eventgroup->getEventGroupIds() ;

        foreach ($eventgroupIds as $eventgroupId)
        {
            $eventgroup->loadEventGroupById($eventgroupId['eventgroupid']) ;
            $s[$eventgroup->getEventGroupDescription()] = $eventgroup->getEventGroupId() ;
        }

        return $s ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_EVENTS_ADD)
    {
        $this->add_hidden_element('eventid') ;
        $this->add_hidden_element('_swimmeetid') ;
        $this->add_hidden_element('_eventgroupid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

		//  Event Group field

        $eventgroup = new FEListBox('Event Group', true, '300px');
        $eventgroup->set_list_data($this->_eventgroupSelections()) ;
        $this->add_element($eventgroup) ;

		//  Age Group field

        $agegroup = new FECheckBoxList('Age Group', true, '200px', '200px');
        $agegroup->set_list_data($this->_agegroupSelections()) ;
        $this->add_element($agegroup) ;

		//  Stroke field

        $stroke = new FECheckBoxList('Stroke', true, '200px', '200px');
        $stroke->set_list_data($this->_strokeSelections()) ;
        $this->add_element($stroke) ;

		//  Distance field

        $distance = new FENumber('Distance', true, '50px');
        $this->add_element($distance) ;

		//  Course field

        $course = new FEListBox('Course', true, '150px');
        $course->set_list_data($this->_courseSelections()) ;
        $this->add_element($course) ;

		//  Event Number field

        $eventnumber = new FENumber('Event Number', false, '50px');
        $this->add_element($eventnumber) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields

        $this->set_hidden_element_value('_swimmeetid', $this->getMeetId()) ;
        $this->set_hidden_element_value('_eventgroupid', $this->getEventGroupId()) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_EVENTS_ADD) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $table = html_table($this->_width,0,4) ;
        $table->set_style('border: 1px solid') ;

        //$table->add_row($this->element_label('Event Number'),
        //    $this->element_form('Event Number')) ;

        $table->add_row(html_br()) ;

        $table->add_row(html_td(null, null,
            $this->element_label('Event Group')), html_td(null, null,
            $this->element_form('Event Group'))) ;

        $table->add_row(html_br()) ;

        $table->add_row($this->element_label('Age Group'),
            $this->element_form('Age Group'),
            $this->element_label('Stroke'),
            $this->element_form('Stroke')) ;

        $table->add_row(html_br()) ;

        $table->add_row(html_td(null, null,
            $this->element_label('Distance')), html_td(null, null,
            $this->element_form('Distance'), $this->element_form('Course'))) ;

        //  Handle the form layout slightly differently for ADD actions

        if ($this->get_hidden_element_value('_action') == WPST_ACTION_EVENTS_ADD)
        {
            $table->add_row(html_td(null, null,
                $this->element_label('Event Number')), html_td(null, null,
                $this->element_form('Event Number'),
                div_font8bold('Leave blank when creating multiple events.'))) ;
        }
        else
        {
            $table->add_row(html_td(null, null,
                $this->element_label('Event Number')), html_td(null, null,
                $this->element_form('Event Number'))) ;
        }

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation($checkexists = true)
    {
        $valid = true ;

        //  Make sure swim event is unique.  Since multiple
        //  events can be defined at one time, need to loop
        //  through combination of age groups and strokes.

        $event = new SwimTeamEvent() ;

        $strokes = $this->get_element_value('Stroke') ;
        $agegroups = $this->get_element_value('Age Group') ;

        //  Updates are on single items only so to reuse this
        //  code we need to make strokes and age groups into an array.

        if ($this->get_hidden_element_value('_action') == WPST_ACTION_EVENTS_UPDATE)
        {
            $strokes = array($strokes) ;
            $agegroups = array($agegroups) ;
        }

        $event->setCourse($this->get_element_value('Course')) ;
        $event->setDistance($this->get_element_value('Distance')) ;
        $event->setEventGroupId($this->get_element_value('Event Group')) ;
        $event->setMeetId($this->get_hidden_element_value('_swimmeetid')) ;
        $event->setEventGroupId($this->get_hidden_element_value('_eventgroupid')) ;

        //  Loop through age groups

        foreach ($agegroups as $agegroup)
        {
            
            //  Loop through strokes

            foreach ($strokes as $stroke)
            {
                $event->setStroke($stroke) ;
                $event->setAgeGroupId($agegroup) ;

                //  Check existance?
 
                if ($checkexists)
                {
                    if ($event->getSwimTeamEventExists())
                    {
                        $this->add_error('Event Group', 'One or more similar events already exists.');
                        $this->add_error('Age Group', 'One or more similar events already exists.');
                        $this->add_error('Stroke', 'One or more similar events already exists.');
                        $this->add_error('Distance', 'One or more similar events already exists.');
                        $valid = false ;
                    }
                }
            }
        }

	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        //  Assume success ...

        $success = true ;

        //  Make sure swim event is unique.  Since multiple
        //  events can be defined at one time, need to loop
        //  through combination of age groups and strokes.

        $event = new SwimTeamEvent() ;

        $strokes = $this->get_element_value('Stroke') ;
        $agegroups = $this->get_element_value('Age Group') ;

        $event->setMeetId($this->get_hidden_element_value('_swimmeetid')) ;
        $event->setCourse($this->get_element_value('Course')) ;
        $event->setDistance($this->get_element_value('Distance')) ;
        $event->setEventGroupId($this->get_element_value('Event Group')) ;

        //  How to handle event number?  It is (a) optional
        //  and (b) ignored when defining multiple events.

        $en = $event->getMaxEventNumber() ;

        if ((count($strokes) == 1) && (count($agegroups) == 1))
        {
            if ($this->get_element_value('Event Number') != WPST_NULL_STRING)
                $en = $this->get_element_value('Event Number') ;
        }

        //  Loop through age groups

        foreach ($agegroups as $agegroup)
        {
            //  Loop through strokes

            foreach ($strokes as $stroke)
            {
                $event->setStroke($stroke) ;
                $event->setAgeGroupId($agegroup) ;
                $event->setEventNumber(++$en) ;

                //  By 'anding' the successes together,
                //  we can determine if any of them failed.

                $success &= ($event->addSwimTeamEvent() != null) ;
            }
        }

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $this->set_action_message('Event(s) successfully added.') ;
        }
        else
        {
            $this->set_action_message('Event(s) not successfully added.') ;
        }

        return $success ;
    }

    /**
     * Construct a container with a success message
     * which can be displayed after form processing
     * is complete.
     *
     * @return Container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Update Event form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamEventAddForm
 */
class WpSwimTeamEventUpdateForm extends WpSwimTeamEventAddForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_EVENTS_UPDATE)
    {
        $this->add_hidden_element('eventid') ;
        $this->add_hidden_element('_swimmeetid') ;
        $this->add_hidden_element('_eventgroupid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

		//  Age Group field

        $agegroup = new FEListBox('Age Group', true, '175px');
        $agegroup->set_list_data($this->_agegroupSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $agegroup->set_readonly(true) ;

        $this->add_element($agegroup) ;

		//  Stroke field

        $stroke = new FEListBox('Stroke', true, '175px');
        $stroke->set_list_data($this->_strokeSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $stroke->set_readonly(true) ;

        $this->add_element($stroke) ;

		//  Distance field

        $distance = new FENumber('Distance', true, '50px');

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $distance->set_readonly(true) ;

        $this->add_element($distance) ;

		//  Course field

        $course = new FEListBox('Course', true, '150px');
        $course->set_list_data($this->_courseSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $course->set_readonly(true) ;

        $this->add_element($course) ;

		//  Event Number field

        $eventnumber = new FENumber('Event Number', true, '50px');

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $eventnumber->set_readonly(true) ;

        $this->add_element($eventnumber) ;

		//  Event Group field

        $eventgroup = new FEListBox('Event Group', true, '175px');
        $eventgroup->set_list_data($this->_eventgroupSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $eventgroup->set_readonly(true) ;

        $this->add_element($eventgroup) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_ACTION_EVENTS_UPDATE)
    {
        $this->set_hidden_element_value('_action', $action) ;
        $this->set_hidden_element_value('_swimmeetid', $this->getMeetId()) ;
        $this->set_hidden_element_value('_eventgroupid', $this->getEventGroupId()) ;

        $event = new SwimTeamEvent() ;
        $event->loadSwimTeamEventByEventId($this->getEventId()) ;

        $this->set_hidden_element_value('eventid', $event->getEventId()) ;
        $this->set_element_value('Age Group', $event->getAgeGroupId()) ;
        $this->set_element_value('Event Group', $event->getEventGroupId()) ;
        $this->set_element_value('Event Number', $event->getEventNumber()) ;
        $this->set_element_value('Stroke', $event->getStroke()) ;
        $this->set_element_value('Distance', $event->getDistance()) ;
        $this->set_element_value('Course', $event->getCourse()) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = parent::form_backend_validation() ;

	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $event = new SwimTeamEvent() ;

        $event->setEventId($this->get_hidden_element_value('eventid')) ;
        $event->setAgeGroupId($this->get_element_value('Age Group')) ;
        $event->setEventGroupId($this->get_element_value('Event Group')) ;
        $event->setEventNumber($this->get_element_value('Event Number')) ;
        $event->setStroke($this->get_element_value('Stroke')) ;
        $event->setDistance($this->get_element_value('Distance')) ;
        $event->setCourse($this->get_element_value('Course')) ;

        $success = $event->updateSwimTeamEvent() ;

        //  If successful, store the updated event id in so it can be used later.

        if ($success) 
        {
            $event->setEventId($success) ;
            $this->set_action_message('Event successfully updated.') ;
        }
        else
        {
            $this->set_action_message('Event was not successfully updated.') ;
        }

        return $success ;
    }
}

/**
 * Construct the Delete Event form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamEventUpdateForm
 */
class WpSwimTeamEventDeleteForm extends WpSwimTeamEventUpdateForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_EVENTS_DELETE)
    {
        parent::form_init_elements($action) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_ACTION_EVENTS_DELETE)
    {
        parent::form_init_data($action) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
     */
    function form_backend_validation()
    {
        return true ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $event = new SwimTeamEvent() ;
        $event->setEventId($this->get_hidden_element_value('eventid')) ;

        $success = $event->deleteSwimTeamEvent() ;

        if ($success) 
            $this->set_action_message('Event successfully deleted.') ;
        else
            $this->set_action_message('Event was not successfully deleted.') ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Delete' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Delete_Cancel() ;
    }
}

/**
 * Construct the Delete Event form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamEventUpdateForm
 */
class WpSwimTeamEventDeleteAllForm extends WpSwimTeamEventDeleteForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        //  Need to remember the Group Id we're working on
        $this->add_hidden_element('_eventgroupid') ;
        
        //  Need to remember the Meet Id we're working on
        $this->add_hidden_element('_swimmeetid') ;
        
        $confirm = new FECheckBox('Confirm - Delete All Events') ;
        $this->add_element($confirm) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_element_value('Confirm - Delete All Events', false) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_EVENTS_DELETE_ALL) ;
        $this->set_hidden_element_value('_swimmeetid', $this->getMeetId()) ;
        $this->set_hidden_element_value('_eventgroupid', $this->getEventGroupId()) ;
    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $meetid = $this->get_hidden_element_value('_swimmeetid') ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style('border: 1px solid') ;
        $table->add_row(html_br()) ;

        if ($meetid != WPST_NULL_ID)
        {
            $desc = SwimTeamTextMap::__mapMeetIdToText($meetid) ;
            $table->add_row(div_font10bold('Delete all events from Swim Meet:', html_br(),
                sprintf('%s vs %s on %s', ucwords($desc['location']), $desc['opponent'], $desc['date']))) ;
        }
        else
        {
            $desc = SwimTeamTextMap::__mapEventGroupIdToText($this->get_hidden_element_value('_eventgroupid')) ;
            $table->add_row(div_font10bold('Delete all events from Event Group:  ' .$desc)) ;
        }

        $table->add_row(html_br()) ;
        $table->add_row($this->element_form('Confirm - Delete All Events')) ;
        $table->add_row(html_br()) ;
        $table->add_row(div_font8bold('Note:  This action cannot be undone.')) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
     */
    function form_backend_validation()
    {
        return !is_null($this->get_element_value('Confirm - Delete All Events')) ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $actionmsgs = array() ;

        $event = new SwimTeamEvent() ;
        $meetid = $this->get_hidden_element_value('_swimmeetid') ;
        $eventgroupid = $this->get_hidden_element_value('_eventgroupid') ;
        $event->setMeetId($meetid) ;
        $event->setEventGroupId($eventgroupid) ;

        //  Deleting events from a meet?
        //  Tailor the message get the event ids based on either group or meet

        if ($meetid != WPST_NULL_ID)
        {
            $desc = SwimTeamTextMap::__mapMeetIdToText($meetid) ;
            $desc = sprintf('%s swim meet vs %s on %s', ucwords($desc['location']), $desc['opponent'], $desc['date']) ;
            $eventIds = $event->getAllEventIdsByMeetId($meetid) ;
        }
        else
        {
            //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
            $desc = SwimTeamTextMap::__mapEventGroupIdToText($this->get_hidden_element_value('_eventgroupid')) ;
            $desc = sprintf('"%s" event group.', $desc) ;
            $eventIds = $event->getAllEventIdsByEventGroupId($eventgroupid) ;
        }

        //  Process all of the events ids

        foreach ($eventIds as $eventId)
        {
            $event->setEventId($eventId['eventid']) ;
            $event->loadSwimTeamEventByEventId() ;

            if ($event->deleteSwimTeamEvent())
                $actionmsgs[] = sprintf('Event Number %s was deleted from %s.',
                    $event->getEventNumber(), $desc) ;
            else
                $actionmsgs[] = sprintf('Event Number %s was not deleted from %s.',
                    $event->getEventNumber(), $desc) ;
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            //  What sort of message(s) do we want to show ...
            if (get_option(WPST_OPTION_ENABLE_VERBOSE_MESSAGES) == WPST_YES)
            {
                foreach($actionmsgs as $actionmsg)
                {
                    $c->add($actionmsg, html_br()) ;
                }
            }
            else
            {
                $c->add(sprintf('%d events deleted from %s.', count($actionmsgs), $desc), html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf('No events deleted.') ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Delete' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Delete_Cancel() ;
    }
}

/**
 * Construct the Event Load form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamEventLoadForm extends WpSwimTeamForm
{
    /**
     * meet id property
     */
    var $__meetid ;

    /**
     * event group id property
     */
    var $__eventgroupid ;

    /**
     * Set the meet id property
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id property
     */
    function getMeetId()
    {
        return $this->__meetid ;
    }

    /**
     * Set the eventgroup id property
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the eventgroup id property
     */
    function getEventGroupId()
    {
        return $this->__eventgroupid ;
    }

    /**
     * Get the array of eventgroup key and value pairs
     *
     * @return mixed - array of eventgroup key value pairs
     */
    function _eventgroupSelections()
    {
        //  EventGroup options and labels 

        $s = array() ;
        //$s = array(ucwords(WPST_NONE) => WPST_NULL_ID) ;

        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroupIds = $eventgroup->getEventGroupIds() ;

        foreach ($eventgroupIds as $eventgroupId)
        {
            $eventgroup->loadEventGroupById($eventgroupId['eventgroupid']) ;
            $ec = $eventgroup->getEventGroupCount() ;
            $s[$eventgroup->getEventGroupDescription() .
                ' (' . $ec['eventcount'] . ' events)' ] = $eventgroup->getEventGroupId() ;
        }

        return $s ;
    }

    /**
     * Build the list of events so they can
     * be used in the widget.
     *
     * @return mixed array of event description and id pairs
     */
    function _buildEventList()
    {
        $event = new SwimTeamEvent() ;

        $eventIds = $event->getAllEventIds() ;

        $eventList = array() ;

        foreach ($eventIds as $eventId)
        {
            $event->loadSwimTeamEventByEventId($eventId['eventid']) ;
            $desc = sprintf('%04s:  %s %s %s %s', 
                $event->getEventNumber(),
                SwimTeamTextMap::__mapAgeGroupIdToText($event->getAgeGroupId()),
                $event->getDistance(),
                SwimTeamTextMap::__mapCourseCodeToText($event->getCourse()),
                SwimTeamTextMap::__mapStrokeCodeToText($event->getStroke())) ;

            $eventList[$desc] = $event->getEventId() ;
        }

        return $eventList ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_EVENTS_LOAD)
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;
        $this->add_hidden_element('_swimmeetid') ;

        $eventgroup = new FEListBox('Event Group', true, '300px');
        $eventgroup->set_list_data($this->_eventgroupSelections()) ;
        $this->add_element($eventgroup) ;

        //$eventlist = new FECheckBoxList('Events', true, '100%', '400px');
        //$eventlist->set_list_data($this->_buildEventList()) ;
        //$eventlist->enable_checkall(true) ;
        //$this->add_element($eventlist) ;

 		//  Event Number field

        //$eventnumber = new FENumber('First Event Number', true, '50px');
        //$this->add_element($eventnumber) ;
}

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields

        $this->set_hidden_element_value('_action', WPST_ACTION_EVENTS_LOAD) ;

        //if (is_null($this->getMeetId())) wp_die(basename(__FILE__) . '::' . __LINE__) ;
        $this->set_hidden_element_value('_swimmeetid', $this->getMeetId()) ;
        //$this->set_element_value('First Event Number', '1') ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $table = html_table($this->_width,0,4) ;
        $table->set_style('border: 1px solid') ;

        //$table->add_row(html_td(null, null,
        //    $this->element_label('Events')), html_td(null, null,
        //    $this->element_form('Events'), html_br(),
        //    div_font8bold('Note:  Only checked events will be added to the Swim Meet.'))) ;

        $table->add_row(html_br()) ;

        $table->add_row(html_td(null, null,
            $this->element_label('Event Group')), html_td(null, null,
            $this->element_form('Event Group'))) ;

        $table->add_row(html_br()) ;

        //$table->add_row($this->element_label('First Event Number'),
        //    $this->element_form('First Event Number')) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;

        /*
        $eventnumber = $this->get_element_value('First Event Number') ;

        if ($eventnumber < 1)
        {
            $this->add_error('Distance', 'One or more similar events already exists.');
            $valid = false ;
        }
         */

	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $actionmsgs = array() ;

        //  Assume success ...

        $success = true ;
        $actionmsgcount = array('good' => 0, 'bad' => '0') ;

        $event = new SwimTeamEvent() ;

        $meetid = $this->get_hidden_element_value('_swimmeetid') ;
        $desc = SwimTeamTextMap::__mapMeetIdToText($meetid) ;
        $desc = sprintf('%s swim meet vs %s on %s', ucwords($desc['location']), $desc['opponent'], $desc['date']) ;
        $eventgroupid = $this->get_element_value('Event Group') ;

        $eventIds = $event->getAllEventIdsByEventGroupIdAndMeetId($eventgroupid, WPST_NULL_ID) ;

        //  Loop through submitted event ids, copying the standard
        //  event as a new event for the selected meet.

        foreach ($eventIds as $eventId)
        {
            //  Load the base event, change
            //  the meet id and add it as a new event

            $event->loadSwimTeamEventByEventId($eventId['eventid']) ;
            $event->setMeetId($meetid) ;
            $success = ($event->addSwimTeamEvent() != null) ;

            if ($success)
            {
                $actionmsgcount['good']++ ;
                $actionmsgs[] = sprintf('Event %d added to %s.', $event->getEventNumber(), $desc) ;
            }
            else
            {
                $actionmsgcount['bad']++ ;
                $actionmsgs[] = sprintf('Event %d was not added to %s.', $event->getEventNumber(), $desc) ;
            }
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            //  What sort of message(s) do we want to show ...
            if (get_option(WPST_OPTION_ENABLE_VERBOSE_MESSAGES) == WPST_YES)
            {
                foreach($actionmsgs as $actionmsg)
                {
                    $c->add($actionmsg, html_br()) ;
                }
            }
            else
            {
                $c->add(sprintf('%d events loaded into %s.', $actionmsgcount['good'], $desc), html_br()) ;
                $c->add(sprintf('%d events were not loaded into %s.', $actionmsgcount['bad'], $desc), html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = 'No events loaded.' ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action2()
    {
        $actionmsgs = array() ;

        //  Assume success ...

        $success = true ;

        $event = new SwimTeamEvent() ;

        $eventIds = $this->get_element_value('Events') ;

        //  Handle the odd behavior when all events are removed ...
        //  in this case, all events are passed through the form 
        //  processor as if they are all in the selected list!

        //if (empty($_POST['Events'])) $eventIds = array() ;

        /*
        //  Do any events need to be deleted?  If so, take care of
        //  them before doing the reordering.
 
        //  Loop all event ids

        $allEventIds = $event->getAllEventIds() ;

        foreach ($allEventIds as $eventId)
        {
            if ((empty($eventIds) ||
                !array_search($eventId['eventid'], $eventIds, true)))
            {
                $event->setEventId($eventId['eventid']) ;
                $event->deleteSwimTeamEvent() ;
            }
        }
         */


        //  Loop through submitted event ids, copying the standard
        //  event as a new event for the selected meet.

        $meetid = $this->get_hidden_element_value('_swimmeetid') ;

        foreach ($eventIds as $eventId)
        {
            //  Load the standard event, change
            //  the meet id and add it as a new event

            $event->loadSwimTeamEventByEventId($eventId) ;
            $event->setMeetId($meetid) ;
            $success = ($event->addSwimTeamEvent() != null) ;

            if ($success)
                $actionmsgs[] = sprintf('Event added to swim meet:  %s',
                    SwimTeamTextMap::__mapEventIdToText($eventId)) ;
            else
                $actionmsgs[] = sprintf('Event not added to swim meet:  %s',
                    SwimTeamTextMap::__mapEventIdToText($eventId)) ;

        }

        if (0)
        {
        if ($success) 
        {
            $this->set_action_message('Events successfully loaded.') ;
        }
        else
        {
            $this->set_action_message('Events were not successfully loaded.') ;
        }

        return $success ;
        }
        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $actionmsg = sprintf('No events loaded.') ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * Construct a container with a success message
     * which can be displayed after form processing
     * is complete.
     *
     * @return Container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }

    /**
     * form content buttons
     *
     * There is a problem wuth IE and the FEComboListBox
     * widget where the items in the right hand box don't
     * get passed correctly because of an add Javascript
     * incompatibility.  This code works around that problem
     * but should have been incorporated into phpHtmlLib but
     * hasn't been.
     *
     * This solution appeared in the phpHtmlLib forums.
     *
     * @return mixed - DIV container containing buttons.
     */
    /*
    function form_content_buttons()
    {
        // Need a work-around?
        if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'MSIE') != false)
            $workaround = true ;
        else
            $workaround = false ;
        
        //  Need the work around?
 
        if ($workaround)
        {
            $div = new DIVtag( array('style' => 'background-color: #eeeeee;'.
                'padding-top:5px;padding-bottom:5px', 'align' => 'center',
                'nowrap')) ;

            //********************************
            //* add js onsubmit action if any
            //

            foreach($this->_elements as $k => $v)
            {
                $e = &$this->get_element($k) ;
                if ($e->_has_form_on_submit)
                    $onclick .= $e->form_tag_onsubmit() ;
            }
 
            //*******************************

            //if(!$this->is_read_only())
            $div->add($this->add_action('Save', false, $onclick)) ;
            $div->add(_HTML_SPACE, $this->add_cancel()) ;

            return $div ;
        }
        else
        {
            return parent::form_content_buttons() ;
        }
    }
             */

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Delete' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Action_Cancel(WPST_ACTION_EVENTS_LOAD) ;
    }
}

/**
 * Construct the Event Load form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamEventsImportForm extends WpSwimTeamFileUploadForm
{
    /**
     * Age Group - used to connect event to an age group
     */
    var $__age_group  = null ;

    /**
     * Validation Error Message
     */
    var $__error_message ;

    /**
     * id property - used to track the eventgroup record
     */
    var $__eventgroupid ;

    /**
     * Set the Event Group Id property
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the Event Group Id property
     */
    function getEventGroupId()
    {
        return $this->__eventgroupid ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        parent::form_init_elements() ;

        //  How to handle duplicate event numbers?
        $dupes = new FERadioGroup("Duplicate Event Numbers", array(
            //ucwords(WPST_NONE) => WPST_NONE,
            ucwords(WPST_ACTION_IGNORE) => WPST_ACTION_IGNORE,
            ucwords(WPST_ACTION_REPLACE) => WPST_ACTION_REPLACE,
            ), true, "200px");
        $dupes->set_br_flag(true) ;
        $this->add_element($dupes) ;

        //  Hidden field to hold the event group id
        $this->add_hidden_element('_eventgroupid') ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_element_value('Duplicate Event Numbers', WPST_ACTION_IGNORE) ;
        $this->set_hidden_element_value('_eventgroupid', $this->getEventGroupId()) ;
    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        //$table = html_table($this->_width,0,4) ;
        $table = html_table('100%', 0,4) ;
        //$table->set_style('border: 3px solid red;') ;

        $table->add_row($this->element_label($this->__uploadFileLabel),
            $this->element_form($this->__uploadFileLabel)) ;

        $table->add_row($this->element_label('Duplicate Event Numbers'),
            $this->element_form('Duplicate Event Numbers')) ;

        $this->add_form_block(null, $table) ;
    }
        
    /**
     * Validate Event File Header Record
     *
     * @param string - header record
     * @return boolean - valid (true) or invalid (false)
     */
    function validateEventFileHeaderRecord($record)
    {
        $err = &$this->__error_message ;

        $err = '' ;

        //  Hy-tek Meet Event (.hyv) File Format
        //
        //  More details at:
        //
        //  https://docs.google.com/spreadsheet/pub?key=0AgBHWDGsX0PUdG9PNzVqaDNPMWpsdjVIbTBJMlFWYUE&output=pdf
        //  
        //  Header record - first line of file
        //
        //  Field	Content
        //  1	    Meet Description
        //  2	    Start Date
        //  3	    End Date
        //  4	    Age Up Date	18 and up (Masters)
        //  5	    Course Code
        //  6	    Location/Pool
        //  7	    Unknown
        //  8	    Software Vendor
        //  9	    Sofware Version
        //  10	    Unknown
        //  11	    Unknown
        //

        $fields = explode(';', $record) ;

        //  Validate number of fields, should only be 11
        if (count($fields) != 11)
        {
            $err = sprintf('Error:  Invalid number of fields in Header Record, found %d, should have 11', count($fields)) ;
            return false ;
        }

        //  Validate start date - Field 2
        preg_match("/(?P<month>[0-9]{2})\/(?P<day>[0-9]{2})\/(?P<year>[0-9]{4})/", $fields[1], $sd);
        if (!checkdate((int)$sd['month'], (int)$sd['day'], (int)$sd['year']))
        {
            $err = sprintf('Error:  Invalid start date (%s) found in Header Record.', $fields[1]) ;
            return false ;
        }

        //  Validate end date - Field 3
        preg_match("/(?P<month>[0-9]{2})\/(?P<day>[0-9]{2})\/(?P<year>[0-9]{4})/", $fields[2], $sd);
        if (!checkdate((int)$sd['month'], (int)$sd['day'], (int)$sd['year']))
        {
            $err = sprintf('Error:  Invalid end date (%s) found in Header Record.', $fields[2]) ;
            return false ;
        }

        //  Validate age up date - Field 4
        preg_match("/(?P<month>[0-9]{2})\/(?P<day>[0-9]{2})\/(?P<year>[0-9]{4})/", $fields[3], $sd);
        if (!checkdate((int)$sd['month'], (int)$sd['day'], (int)$sd['year']))
        {
            $err = sprintf('Error:  Invalid age up date (%s) found in Header Record.', $fields[3]) ;
            return false ;
        }

        //  Validate course code
        if (array_search($fields[4], array('S', 'L', 'Y'), true) === false)
        {
            $err = sprintf('Error:  Invalid course code in Header Record, found \'%s\', should be \'S\', \'L\', or \'Y\'', $fields[4]) ;
            return false ;
        }

        return true ;
    }

    /**
     * Validate Event File Header Record
     *
     * @param string - header record
     * @return boolean - valid (true) or invalid (false)
     */
    function validateEventFileEventRecord($record)
    {
        $ag = &$this->__age_group ;
        $err = &$this->__error_message ;

        $err = '' ;

        if (is_null($ag)) $ag = new SwimTeamAgeGroup() ;

        //  Hy-tek Meet Event (.hyv) File Format
        //
        //  More details at:
        //
        //  https://docs.google.com/spreadsheet/pub?key=0AgBHWDGsX0PUdG9PNzVqaDNPMWpsdjVIbTBJMlFWYUE&output=pdf
        //  
        //  Event Record
        //
        //  Field	Content
        //  1	    Event Number
        //  2	    Event Classifcation
        //  3	    Gender
        //  4	    Event Type
        //  5	    Minimum Age
        //  6	    Maximum Age
        //  7	    Distance
        //  8	    Event Code
        //  9	    Unknown
        //  10	    Qualifying Time
        //  11	    Unknown
        //  12	    Event Fee
        //  13	    Unknown
        //  14	    Unknown
        //  15	    Unknown
        //  16	    Unknown
        //  17	    Unknown
        //  18	    Unknown
        //

        $fields = explode(';', $record) ;

        //  Validate number of fields, should only be 18
        if (count($fields) != 18)
        {
            $err = sprintf('Error:  Invalid number of fields in Header Record, found %d, should have 18', count($fields)) ;
            return false ;
        }

        //  Validate event number - Field 1
        if (!preg_match('/^\d+$/', $fields[0]))
        {
            $err = sprintf('Error:  Invalid event number (%s) found in Event Record.', $fields[0]) ;
            return false ;
        }

        //  Validate Classification - Field 2
        if (array_search($fields[1], array('F', 'P', 'S'), true) === false)
        {
            $err = sprintf('Error:  Invalid classification in Event Record, found \'%s\', should be \'F\', \'P\', or \'S\'', $fields[1]) ;
            return false ;
        }

        //  Validate Gender - Field 3
        if (array_search($fields[2], array('M', 'F'), true) === false)
        {
            $err = sprintf('Error:  Invalid gender in Event Record, found \'%s\', should be \'M\', or \'F\'', $fields[2]) ;
            return false ;
        }
        else
        {
            $gender = ($fields[2] == 'M') ? WPST_GENDER_MALE : WPST_GENDER_FEMALE ;
        }

        //  Validate Event Type - Field 4
        if (array_search($fields[3], array('I', 'R'), true) === false)
        {
            $err = sprintf('Error:  Invalid event type in Event Record, found \'%s\', should be \'I\', or \'R\'', $fields[3]) ;
            return false ;
        }

        //  Validate minimum age - Field 5
        preg_match("/(?P<minage>\d+$)/", $fields[4], $age);
        if (!array_key_exists('minage', $age))
        {
            $err = sprintf('Error:  Invalid minimum age (%s) found in Event Record.', $fields[4]) ;
            return false ;
        }
        else if ((int)$age['minage'] < get_option(WPST_OPTION_MIN_AGE))
        {
            $err = sprintf('Error:  Minimum age (%s) found in Event Record is less than Swim Team minimum age (%s).',
                $fields[4], get_option(WPST_OPTION_MIN_AGE)) ;
            return false ;
        }
        else
        {
            $minage = $age['minage'] ;
        }

        //  Validate maximum age - Field 6
        preg_match("/(?P<maxage>\d+$)/", $fields[5], $age);
        if (!array_key_exists('maxage', $age))
        {
            $err = sprintf('Error:  Invalid maximum age (%s) found in Event Record.', $fields[5]) ;
            return false ;
        }
        else if ((int)$age['maxage'] > get_option(WPST_OPTION_MAX_AGE))
        {
            $err = sprintf('Error:  Maximum age (%s) found in Event Record is greater than Swim Team maximum age (%s).',
                $fields[5], get_option(WPST_OPTION_MAX_AGE)) ;
            return false ;
        }
        else
        {
            $maxage = $age['maxage'] ;
        }

        //  Validate minimum and maximum ages make sense
        if ($minage >= $maxage)
        {
            $err = sprintf('Error:  Invalid Event Record, minimum age (%s) must be less than maximum age (%s).', $fields[4], $fields[5]) ;
            return false ;
        }

        //  Validate the age range matches a defined age group

        $ag->setMinAge($minage) ;
        $ag->setMaxAge($maxage) ;
        $ag->setGender($gender) ;

        if (!$ag->ageGroupExistsByMinAgeMaxAgeAndGender())
        {
            $err = sprintf('Error:  Age range (%s-%s) in Event Record does not match any \'%s\' age groups.',
                $fields[4], $fields[5], ucwords($gender)) ;
            return false ;
        }

        return true ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        //  A Hy-tek Events file contains one header record and N event records

        $file = $this->get_element('Filename') ; 
        $fileInfo = $file->get_file_info() ; 

        $lines = file($fileInfo['tmp_name']) ; 

        //  Scan the records to make sure there isn't something odd in the file

        $line_number = 1 ;

        foreach ($lines as $line)
        {
            if (trim($line) == WPST_NULL_STRING) continue ;

            if ($line_number == 1)
            {
                //printf('<h3>%s::%s</h3>', basename(__FILE__), __LINE__) ;
                if (!$this->validateEventFileHeaderRecord($line))
                {
                    $this->add_error('Hy-tek Event File', sprintf('Invalid header record encountered in Hy-tek Event File on line %s.', $line_number)) ;
                    $this->add_error('Hy-tek Event File', $this->__error_message) ;
                    return false ;
                }
            }
            else
            {
                //printf('<h3>%s::%s</h3>', basename(__FILE__), __LINE__) ;
                if (!$this->validateEventFileEventRecord($line))
                {
                    $this->add_error('Hy-tek Event File', sprintf('Invalid event record encountered in hy-tek event file on line %s.', $line_number)) ;
                    $this->add_error('Hy-tek Event File', $this->__error_message) ;
                    return false ;
                }
            }

            $line_number++ ;
        }

        //  got this far, the file has the right records in it, do the counts make sense?
        
        if ($line_number <= 1)
        {
            $this->add_error('Hy-tek Event File', 'No event records found in file.') ;
            return false ;
        }

        unset($lines) ; 

	    return true ;
    }

    /**
     * this method is called only after all validation has
     * passed.  this is the method that allows you to 
     * do something with the data, say insert/update records
     * in the db.
     */
    function form_action()
    {
        //  Assume success ...
        $success = true ;
        $actionmsgs = array() ;

        $ag = &$this->__age_group ;
        $err = &$this->__error_message ;

        $err = '' ;

        if (is_null($ag)) $ag = new SwimTeamAgeGroup() ;

        $file = $this->get_element('Filename') ; 
        $fileInfo = $file->get_file_info() ; 

        $lines = file($fileInfo['tmp_name']) ; 

        $line_number = 1 ;

        //  Make sure swim event is unique.  Since multiple
        //  events can be defined at one time, need to loop
        //  through combination of age groups and strokes.

        $event = new SwimTeamEvent() ;
        $event->setMeetId(WPST_NULL_ID) ;
        $this->setEventGroupId($this->get_hidden_element_value('_eventgroupid')) ;
        $event->setEventGroupId($this->get_hidden_element_value('_eventgroupid')) ;
        
        //  Hy-tek Meet Event (.hyv) File Format
        //
        //  More details at:
        //
        //  https://docs.google.com/spreadsheet/pub?key=0AgBHWDGsX0PUdG9PNzVqaDNPMWpsdjVIbTBJMlFWYUE&output=pdf
        //  
        //  Event Record
        //
        //  Field	Content
        //  1	    Event Number
        //  2	    Event Classifcation
        //  3	    Gender
        //  4	    Event Type
        //  5	    Minimum Age
        //  6	    Maximum Age
        //  7	    Distance
        //  8	    Event Code
        //  9	    Unknown
        //  10	    Qualifying Time
        //  11	    Unknown
        //  12	    Event Fee
        //  13	    Unknown
        //  14	    Unknown
        //  15	    Unknown
        //  16	    Unknown
        //  17	    Unknown
        //  18	    Unknown
        //

        foreach ($lines as $line)
        {
            $fields = explode(';', $line) ;

            //  Skip any empty lines
            if (trim($line) == WPST_NULL_STRING) continue ;

            //  The only thing needed from the header is the course code

            if ($line_number == 1)
            {
                $event->setCourse($fields[4]) ;
            }
            else
            {
                $gender = ($fields[2] == 'M') ? WPST_GENDER_MALE : WPST_GENDER_FEMALE ;
                $agegroupid = $ag->getAgeGroupIdByMinAgeMaxAgeAndGender($fields[4], $fields[5], $gender) ;

                //  Hy-tek "sort of" uses the SDIF stroke codes correctly.  The codes are
                //  correct for individual events but not for relays so you need to look at
                //  the Event Type (Field 4) with the stroke code.

                $stroke = $fields[7] ;

                if ($fields[3] == 'R')
                {
                    //  Fix the freestyle relay
                    if ($stroke == WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE)
                        $stroke = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE ;
                    
                    //  Fix the medley relay
                    if ($stroke == WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE)
                        $stroke = WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE ;
                }

                $event->setStroke($stroke) ;
                $event->setAgeGroupId($agegroupid) ;
                $event->setEventNumber($fields[0]) ;
                $event->setDistance($fields[6]) ;
           
                //  Check to see if the event already exists

                if ($event->getSwimTeamEventExistsByEventNumberAndGroupId(null, null, true))
                {
                    if ($this->get_element_value('Duplicate Event Numbers') == WPST_ACTION_REPLACE)
                    {
                        if ($event->updateSwimTeamEvent())
                            $actionmsgs[] = sprintf('Event %s on line %d updated.',
                                SwimTeamTextMap::__mapEventIdToText($event->getEventId()), $line_number) ;
                        else
                            $actionmsgs[] = sprintf('Event %s on line %d was not updated.',
                                SwimTeamTextMap::__mapEventIdToText($event->getEventId()), $line_number) ;
                    }
                    else
                    {
                        $actionmsgs[] = sprintf('Event Number %s on line %d will be ignored, duplicate event number.',
                            $fields[0], $line_number) ;
                    }
                }
                else
                {
                    if ($event->addSwimTeamEvent() != null)
                        $actionmsgs[] = sprintf('Event %s on line %d added.',
                            SwimTeamTextMap::__mapEventIdToText($event->getEventId()), $line_number) ;
                    else
                    {
                        $actionmsgs[] = sprintf('Event Number %s on line %d was not added.', $fields[0], $line_number) ;
                    }
                }
            }

            $line_number++ ;
        }

        unset($lines) ;

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = 'Nothing events imported.' ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * overload form_content_buttons() method to have the
     * button display 'upload' instead of the default 'save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_upload_cancel(WPST_ACTION_EVENTS_IMPORT) ;
    }
}

/**
 * Construct the Add Event Group Form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamEventGroupAddForm extends WpSwimTeamForm
{
    /**
     * id property - used to track the eventgroup record
     */
    var $__eventgroupid ;

    /**
     * Set the Event Group Id property
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the Event Group Id property
     */
    function getEventGroupId()
    {
        return $this->__eventgroupid ;
    }

    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Define a Swim Team Event Group.  Settng up a eventgroup determines how it can then
            be allocated to a season or one or more swim meets.  A eventgroup consists of the following:')) ;
        $ul = html_ul() ;
        $ul->add(html_p(html_b('Description:'), 'Detailed description of the Event Group.')) ;
        $ul->add(html_p(html_b('Status:'), 'Set the status of a Event Group.  Event Groups which
            are set inactive are not available to be used for assigning events to a swim meet.  A
            event group which is no longer needed should be set to inactive.')) ;

        $div->add($ul) ;

        return $div ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_ADD)
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_eventgroupid') ;
        $this->add_hidden_element('_action') ;

        //  Description Field
        $description = new FEText('Description', TRUE, '300px');
        $description->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($description);
		
        //  Status Field
        $status = new FEListBox('Status', false, '100px');
        $status->set_list_data(array(
             ucwords(WPST_ACTIVE) => WPST_ACTIVE
            ,ucwords(WPST_INACTIVE) => WPST_INACTIVE
        )) ;
        $status->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($status) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields
        $this->set_hidden_element_value('_action', WPST_ACTION_ADD) ;
        $this->set_element_value('Description', 'Event Group description.') ;
        $this->set_element_value('Status', WPST_ACTIVE) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $table = html_table($this->_width,0,4) ;
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('Description'),
            $this->element_form('Description')) ;

        $table->add_row($this->element_label('Status'),
            $this->element_form('Status')) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;

        //  Need to validate several fields ...

        //  Make sure position is unique

        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->setEventGroupDescription($this->get_element_value('Description')) ;

        if ($eventgroup->eventgroupExistByDescription())
        {
            $this->add_error('Description', 'Description already exists.');
            $valid = false ;
        }

	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->setEventGroupDescription($this->get_element_value('Description')) ;
        $eventgroup->setEventGroupStatus($this->get_element_value('Status')) ;
        $success = $eventgroup->addEventGroup() ;

        //  If successful, store the added eventgroup id in so it can be used later.

        if ($success) 
        {
            $eventgroup->setEventGroupId($success) ;
            $this->set_action_message('Event Group successfully added.') ;
        }
        else if ($eventgroup->SwimTeamDBIWordPressDatabaseError())
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message('Event Group was not successfully added.<br/>' .
               'WordPress Database Error:  ' . $eventgroup->wpstdb->last_error) ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message('Event Group was not successfully added.') ;
        }

        return true ;
    }

    /**
     * Return the status message so it can be
     * displayed by the form processor.
     *
     * @return container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Update Event Group form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamEventGroupAddForm
 */
class WpSwimTeamEventGroupUpdateForm extends WpSwimTeamEventGroupAddForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_hidden_element_value('_eventgroupid', $this->getEventGroupId()) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_UPDATE) ;

        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->loadEventGroupById($this->getEventGroupId()) ;

        //  Initialize the form fields
        $this->set_element_value('Description', $eventgroup->getEventGroupDescription()) ;
        $this->set_element_value('Status', $eventgroup->getEventGroupStatus()) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;
        $eventgroupid = $this->get_hidden_element_value('_eventgroupid') ;

        //  Need to validate several fields ...

        //  Make sure description is unique

        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->setEventGroupDescription($this->get_element_value('Description')) ;

        if (($eventgroup->eventgroupExistByDescription())
            && (!$eventgroup->eventgroupExistByDescription($eventgroupid)))
        {
            $this->add_error('Description', 'Description already exists.');
            $valid = false ;
        }

	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->setEventGroupId($this->get_hidden_element_value('_eventgroupid')) ;
        $eventgroup->setEventGroupDescription($this->get_element_value('Description')) ;
        $eventgroup->setEventGroupStatus($this->get_element_value('Status')) ;

        $success = $eventgroup->updateEventGroup() ;

        //  If successful, store the added eventgroup id in so it can be used later.

        if ($success) 
        {
            $eventgroup->setEventGroupId($success) ;
            $this->set_action_message('Event Group successfully updated.') ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message('Event Group was not updated.') ;
        }

        return true ;
    }
}

/**
 * Construct the Delete Event Group form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamEventGroupDeleteForm extends WpSwimTeamEventGroupUpdateForm
{
    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Delete an event group from the system.  This operation removes the
            event group and any event group allocations for the position.  All record of a
            event group and any assignments, is lost and cannot be recovered.  Be certain
            before eliminating a event group - a better option may be to mark the event group
            as Inactive using the Update Event Group action.')) ;

        return $div ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_DELETE)
    {
        parent::form_init_elements($action) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_DELETE) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        parent::form_init_data() ;
        $this->set_hidden_element_value('_action', WPST_ACTION_DELETE) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        //  Make sure position exists

        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->setEventGroupId($this->get_hidden_element_value('_eventgroupid')) ;

        return ($eventgroup->eventgroupExistById()) ;

    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $eventgroup = new SwimTeamEventGroup() ;
        $eventgroup->setEventGroupId($this->get_hidden_element_value('_eventgroupid')) ;

        $success = $eventgroup->deleteEventGroup() ;

        //  If successful, store the added eventgroup id in so it can be used later.

        if ($success) 
        {
            $eventgroup->setEventGroupId($success) ;
            $this->set_action_message('Event Group successfully deleted.') ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message('Event Group was not deleted.') ;
        }

        return true ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Delete' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Delete_Cancel() ;
    }
}

/**
 * Construct the Reorder Event form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see DIVtag
 */
class WpSwimTeamEventReorderAjaxForm extends TABLEtag
{
    /**
     * Property to store the event
     */
    var $__event ;

    /**
     * Property to store the event ids
     */
    var $__eventIds ;

    /**
     * Property to store the age group
     */
    var $__agegroup ;

    /**
     * Constructor - Build the Ajax form content
     *
     * @param meetid - int - meet id to connect events to
     */
    //function WpSwimTeamEventReorderAjaxForm($meetid = WPST_NULL_ID, $eventgroupid = WPST_NULL_ID)
    function WpSwimTeamEventReorderAjaxForm()
    {
        $this->__agegroup = new SwimTeamAgeGroup() ;

        $this->set_id('wpst_eventorder') ;
        //Portlet::Portlet() ;
        TABLEtag::TABLEtag() ;

        //  Header row needs and Id as well ...

        $head = html_thead() ;
        $tr = html_tr() ;
        $tr->set_id('event-0') ;
        $tr->set_class('nodrop nodrag') ;
        $th = html_th('Event Number') ;
        $th->set_id('eventnumber-0') ;
        $tr->add(html_th('Id'), html_th('Event Group'), $th,
            html_th('Age Group'), html_th('Event Description')) ;
        $head->add($tr) ;

        $this->add($head) ;

        $tbody = html_tbody() ;
        $tbody->set_id('wpst_eventorder-2') ;

        //$this->setPortletColumns(2) ;

        //  Loop through events

        foreach ($this->__eventIds as $eventId)
        {
            $this->__event->loadSwimTeamEventByEventId($eventId['eventid']) ;

            $this->__agegroup->loadAgeGroupById($this->__event->getAgeGroupId()) ;

            $desc = sprintf('%s %s %s', 
                $this->__event->getDistance(),
                SwimTeamTextMap::__mapCourseCodeToText($this->__event->getCourse()),
                SwimTeamTextMap::__mapStrokeCodeToText($this->__event->getStroke())) ;

            $tr = html_tr() ;

            if ($this->__agegroup->getGender() == WPST_GENDER_MALE)
                $tr->set_class('male') ;
            else
                $tr->set_class('female') ;

            //$tr->set_id(sprintf('event-%d-%d', $event->getEventGroupId(), $eventId['eventid'])) ;
            $tr->set_id(sprintf('event-%d', $eventId['eventid'])) ;
            $td = html_td() ;
            //$td->set_id(sprintf('eventnumber-%d-%d', $event->getEventGroupId(), $eventId['eventid'])) ;
            $td->set_id(sprintf('eventnumber-%d', $eventId['eventid'])) ;
            $td->add($this->__event->getEventNumber()) ;
            $tr->add($eventId['eventid'],
                SwimTeamTextMap::__mapEventGroupIdToText($this->__event->getEventGroupId()), $td,
                SwimTeamTextMap::__mapAgeGroupIdToText($this->__event->getAgeGroupId()), $desc) ;
            $tbody->add($tr) ;
        }

        $this->add($tbody) ;

        //  Add a Save button

        $js = "/* <![CDATA[ */
            jQuery(document).ready(function() {
                // Initialise the table
                jQuery(\"#wpst_eventorder\").tableDnD() ;

                jQuery(\"#wpst_eventorder\").tableDnD({
                    onDrop: function(table, row) {
                        re = new RegExp(\"/[^\-]*$/\") ;
                        var rows = table.tBodies[0].rows ;
                        for (var i = 0 ; i < rows.length ; i++) {
                            var nodrag = jQuery(rows[i]).hasClass(\"nodrag\") ;
                            if (nodrag == false) {
                                jQuery(\"#eventnumber-\" +
                                    rows[i].id.match(table.tableDnDConfig.serializeRegexp)[0]).html(i+1) ;
                            }
                        }
                        serial = jQuery.tableDnD.serialize() ;
                        jQuery.post(\"%s\", {action:\"wpst_reorder_events\",
                            \"wpst_reorder_events\": serial}, function(str) {
                            jQuery(\"#wpst_reorder_events_msg\").html(str) ;
	                    });
                    }
                });
            });
            /* ]]> */" ;

        $script = html_script() ;
        $script->add(sprintf($js, 
            get_option('url') . '/wp-admin/admin-ajax.php')) ;
        $this->add($script) ;

        $msg = html_div() ;
        $msg->set_id('wpst_reorder_events_msg') ;
        $msg->add('&nbsp;') ;
        $this->add($msg) ;
    }
}

/**
 * Construct the Reorder Event by Swim Meet form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamEventReorderAjaxForm
 */
class WpSwimTeamEventReorderBySwimMeetAjaxForm extends WpSwimTeamEventReorderAjaxForm
{
    /**
     * Constructor - Build the Ajax form content
     *
     * @param meetid - int - meet id to connect events to
     */
    function WpSwimTeamEventReorderBySwimMeetAjaxForm($meetid = WPST_NULL_ID)
    {
        $this->__event = new SwimTeamEvent() ;

        $this->__eventIds = $this->__event->getAllEventIdsByMeetId($meetid) ;

        parent::WpSwimTeamEventReorderAjaxForm() ;
    }
}

/**
 * Construct the Reorder Event by Event Group form
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see WpSwimTeamEventReorderAjaxForm
 */
class WpSwimTeamEventReorderByEventGroupAjaxForm extends WpSwimTeamEventReorderAjaxForm
{
    /**
     * Constructor - Build the Ajax form content
     *
     * @param meetid - int - meet id to connect events to
     */
    function WpSwimTeamEventReorderByEventGroupAjaxForm($eventgroupid = WPST_NULL_ID)
    {
        $this->__event = new SwimTeamEvent() ;

        $this->__eventIds = $this->__event->getAllEventIdsByEventGroupIdAndMeetId($eventgroupid, WPST_NULL_ID) ;

        parent::WpSwimTeamEventReorderAjaxForm() ;
    }
}
?>
