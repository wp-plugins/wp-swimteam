<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Events
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once("forms.class.php") ;
require_once("seasons.class.php") ;
require_once("events.class.php") ;
require_once("agegroups.class.php") ;
require_once("portlets.class.php") ;

/**
 * Construct the Add Event form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimMeetEventAddForm extends WpSwimTeamForm
{
    /**
     * event id property
     */
    var $__eventid ;

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
            $agegroup->loadAgeGroupById($agegroupId["id"]) ;
            $s[$agegroup->getAgeGroupText()] = $agegroup->getId() ;
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
        $this->add_hidden_element("eventid") ;
        $this->add_hidden_element("_meetid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

		//  Age Group field

        $agegroup = new FECheckBoxList("Age Group", true, "200px", "200px");
        $agegroup->set_list_data($this->_agegroupSelections()) ;
        $this->add_element($agegroup) ;

		//  Stroke field

        $stroke = new FECheckBoxList("Stroke", true, "200px", "200px");
        $stroke->set_list_data($this->_strokeSelections()) ;
        $this->add_element($stroke) ;

		//  Distance field

        $distance = new FENumber("Distance", true, "50px");
        $this->add_element($distance) ;

		//  Course field

        $course = new FEListBox("Course", true, "150px");
        $course->set_list_data($this->_courseSelections()) ;
        $this->add_element($course) ;

		//  Event Number field

        $eventnumber = new FENumber("Event Number", false, "50px");
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

        $this->set_hidden_element_value("_meetid", $this->getMeetId()) ;
        $this->set_hidden_element_value("_action", WPST_ACTION_EVENTS_ADD) ;
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
        $table->set_style("border: 1px solid") ;

        //$table->add_row($this->element_label("Event Number"),
        //    $this->element_form("Event Number")) ;

        $table->add_row(html_br()) ;

        $table->add_row($this->element_label("Age Group"),
            $this->element_form("Age Group"),
            $this->element_label("Stroke"),
            $this->element_form("Stroke")) ;

        $table->add_row(html_br()) ;

        $table->add_row(html_td(null, null,
            $this->element_label("Distance")), html_td(null, null,
            $this->element_form("Distance"), $this->element_form("Course"))) ;

        //  Handle the form layout slightly differently for ADD actions

        if ($this->get_hidden_element_value("_action") == WPST_ACTION_EVENTS_ADD)
        {
            $table->add_row(html_td(null, null,
                $this->element_label("Event Number")), html_td(null, null,
                $this->element_form("Event Number"),
                div_font8bold("Leave blank when creating multiple events."))) ;
        }
        else
        {
            $table->add_row(html_td(null, null,
                $this->element_label("Event Number")), html_td(null, null,
                $this->element_form("Event Number"))) ;
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

        $event = new SwimMeetEvent() ;

        $strokes = $this->get_element_value("Stroke") ;
        $agegroups = $this->get_element_value("Age Group") ;

        //  Updates are on single items only so to reuse this
        //  code we need to make strokes and age groups into an array.

        if ($this->get_hidden_element_value("_action") == WPST_ACTION_EVENTS_UPDATE)
        {
            $strokes = array($strokes) ;
            $agegroups = array($agegroups) ;
        }

        $event->setCourse($this->get_element_value("Course")) ;
        $event->setDistance($this->get_element_value("Distance")) ;
        $event->setMeetId($this->get_hidden_element_value("_meetid")) ;

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
                    if ($event->getSwimMeetEventExists())
                    {
                        $this->add_error("Age Group", "One or more similar events already exists.");
                        $this->add_error("Stroke", "One or more similar events already exists.");
                        $this->add_error("Distance", "One or more similar events already exists.");
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

        $event = new SwimMeetEvent() ;

        $strokes = $this->get_element_value("Stroke") ;
        $agegroups = $this->get_element_value("Age Group") ;

        $event->setMeetId($this->get_hidden_element_value("_meetid")) ;
        $event->setCourse($this->get_element_value("Course")) ;
        $event->setDistance($this->get_element_value("Distance")) ;

        //  How to handle event number?  It is (a) optional
        //  and (b) ignored when defining multiple events.

        $en = $event->getMaxEventNumber() ;

        if ((count($strokes) == 1) && (count(agegroups) == 1))
        {
            if ($this->get_element_value("Event Number") != WPST_NULL_STRING)
                $en = $this->get_element_value("Event Number") ;
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

                //  By "anding" the successes together,
                //  we can determine if any of them failed.

                $success &= ($event->addSwimMeetEvent() != null) ;
            }
        }

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $this->set_action_message("Event successfully added.") ;
        }
        else
        {
            $this->set_action_message("Event was not successfully added.") ;
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
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimMeetEventAddForm
 */
class WpSwimMeetEventUpdateForm extends WpSwimMeetEventAddForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_EVENTS_UPDATE)
    {
        $this->add_hidden_element("eventid") ;
        $this->add_hidden_element("_meetid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

		//  Age Group field

        $agegroup = new FEListBox("Age Group", true, "175px");
        $agegroup->set_list_data($this->_agegroupSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $agegroup->set_readonly(true) ;

        $this->add_element($agegroup) ;

		//  Stroke field

        $stroke = new FEListBox("Stroke", true, "175px");
        $stroke->set_list_data($this->_strokeSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $stroke->set_readonly(true) ;

        $this->add_element($stroke) ;

		//  Distance field

        $distance = new FENumber("Distance", true, "50px");

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $distance->set_readonly(true) ;

        $this->add_element($distance) ;

		//  Course field

        $course = new FEListBox("Course", true, "150px");
        $course->set_list_data($this->_courseSelections()) ;

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $course->set_readonly(true) ;

        $this->add_element($course) ;

		//  Event Number field

        $eventnumber = new FENumber("Event Number", true, "50px");

        if ($action == WPST_ACTION_EVENTS_DELETE)
            $eventnumber->set_readonly(true) ;

        $this->add_element($eventnumber) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_ACTION_EVENTS_UPDATE)
    {
        $this->set_hidden_element_value("_action", $action) ;
        $this->set_hidden_element_value("_meetid", $this->getMeetId()) ;

        $event = new SwimMeetEvent() ;
        $event->loadSwimMeetEventByEventId($this->getEventId()) ;

        $this->set_hidden_element_value("eventid", $event->getEventId()) ;
        $this->set_element_value("Age Group", $event->getAgeGroupId()) ;
        $this->set_element_value("Event Number", $event->getEventNumber()) ;
        $this->set_element_value("Stroke", $event->getStroke()) ;
        $this->set_element_value("Distance", $event->getDistance()) ;
        $this->set_element_value("Course", $event->getCourse()) ;
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
        $event = new SwimMeetEvent() ;

        $event->setEventId($this->get_hidden_element_value("eventid")) ;
        $event->setAgeGroupId($this->get_element_value("Age Group")) ;
        $event->setEventNumber($this->get_element_value("Event Number")) ;
        $event->setStroke($this->get_element_value("Stroke")) ;
        $event->setDistance($this->get_element_value("Distance")) ;
        $event->setCourse($this->get_element_value("Course")) ;

        $success = $event->updateSwimMeetEvent() ;

        //  If successful, store the updated event id in so it can be used later.

        if ($success) 
        {
            $event->setEventId($success) ;
            $this->set_action_message("Event successfully updated.") ;
        }
        else
        {
            $this->set_action_message("Event was not successfully updated.") ;
        }

        return $success ;
    }
}

/**
 * Construct the Delete Event form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimMeetEventUpdateForm
 */
class WpSwimMeetEventDeleteForm extends WpSwimMeetEventUpdateForm
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
        $event = new SwimMeetEvent() ;
        $event->setEventId($this->get_hidden_element_value("eventid")) ;

        $success = $event->deleteSwimMeetEvent() ;

        if ($success) 
            $this->set_action_message("Event successfully deleted.") ;
        else
            $this->set_action_message("Event was not successfully deleted.") ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Delete" instead of the default "Save".
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
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimMeetEventLoadForm extends WpSwimTeamForm
{
    /**
     * meet id property
     */
    var $__meetid ;

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
     * Map the age group id into text
     *
     * @return string - season text description
     */
    function __mapAgeGroupIdToText($agegroupid)
    {
        $agegroup = new SwimTeamAgeGroup() ;
        $agegroup->loadAgeGroupById($agegroupid) ;

        return $agegroup->getAgeGroupText() ;
    }

    /**
     * Map the course into text
     *
     * @return string - season text description
     */
    function __mapCourseCodeToText($course)
    {
        switch($course)
        {
            case WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL ;
                break ;

            default :
                $obj = WPST_UNKNOWN ;
                break ;
        }

        return $obj ;
    }

    /**
     * Map the stroke code into text
     *
     * @return string - stroke text description
     */
    function __mapStrokeCodeToText($stroke)
    {
        switch($stroke)
        {
            case WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL ;
                break ;

            default :
                $obj = WPST_UNKNOWN ;
                break ;
        }

        return $obj ;
    }

    /**
     * Build the list of events so they can
     * be used in the widget.
     *
     * @return mixed array of event description and id pairs
     */
    function _buildEventList()
    {
        $event = new SwimMeetEvent() ;

        $eventIds = $event->getAllEventIds() ;

        $eventList = array() ;

        foreach ($eventIds as $eventId)
        {
            $event->loadSwimMeetEventByEventId($eventId['eventid']) ;
            $desc = sprintf('%04s:  %s %s %s %s', 
                $event->getEventNumber(),
                $this->__mapAgeGroupIdToText($event->getAgeGroupId()),
                $event->getDistance(),
                $this->__mapCourseCodeToText($event->getCourse()),
                $this->__mapStrokeCodeToText($event->getStroke())) ;

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
        $this->add_hidden_element('_meetid') ;

        $eventlist = new FECheckBoxList('Events', true, '100%', '400px');
        $eventlist->set_list_data($this->_buildEventList()) ;
        $eventlist->enable_checkall(true) ;
        $this->add_element($eventlist) ;

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
        $this->set_hidden_element_value('_meetid', $this->getMeetId()) ;
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

        $table->add_row(html_td(null, null,
            $this->element_label('Events')), html_td(null, null,
            $this->element_form('Events'), html_br(),
            div_font8bold('Note:  Only checked events will be added to the Swim Meet.'))) ;

        $table->add_row(html_br()) ;

        //$table->add_row($this->element_label("First Event Number"),
        //    $this->element_form("First Event Number")) ;

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
        $eventnumber = $this->get_element_value("First Event Number") ;

        if ($eventnumber < 1)
        {
            $this->add_error("Distance", "One or more similar events already exists.");
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

        $event = new SwimMeetEvent() ;

        $eventIds = $this->get_element_value('Events') ;

        //  Handle the odd behavior when all events are removed ...
        //  in this case, all events are passed through the form 
        //  processor as if they are all in the selected list!

        //if (empty($_POST["Events"])) $eventIds = array() ;

        /*
        //  Do any events need to be deleted?  If so, take care of
        //  them before doing the reordering.
 
        //  Loop all event ids

        $allEventIds = $event->getAllEventIds() ;

        foreach ($allEventIds as $eventId)
        {
            if ((empty($eventIds) ||
                !array_search($eventId["eventid"], $eventIds, true)))
            {
                $event->setEventId($eventId["eventid"]) ;
                $event->deleteSwimMeetEvent() ;
            }
        }
         */


        //  Loop through submitted event ids, copying the standard
        //  event as a new event for the selected meet.

        $meetid = $this->get_hidden_element_value("_meetid") ;

        foreach ($eventIds as $eventId)
        {
            //  Load the standard event, change
            //  the meet id and add it as a new event

            $event->loadSwimMeetEventByEventId($eventId) ;
            $event->setMeetId($meetid) ;
            $success = ($event->addSwimMeetEvent() != null) ;

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
            $this->set_action_message("Events successfully loaded.") ;
        }
        else
        {
            $this->set_action_message("Events were not successfully loaded.") ;
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
    function form_content_buttons()
    {
        // Need a work-around?
        if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), "MSIE") != false)
            $workaround = true ;
        else
            $workaround = false ;
        
        //  Need the work around?
 
        if ($workaround)
        {
            $div = new DIVtag( array("style" => "background-color: #eeeeee;".
                "padding-top:5px;padding-bottom:5px", "align" => "center",
                "nowrap")) ;

            /********************************
             * add js onsubmit action if any
             */

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
}

/**
 * Construct the Reorder Event form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see DIVtag
 */
class WpSwimMeetEventReorderAjaxForm extends TABLEtag
{
    var $agegroup ;

    /**
     * Map the age group id into text
     *
     * @return string - season text description
     */
    function __mapAgeGroupIdToText($agegroupid)
    {
        $agegroup = new SwimTeamAgeGroup() ;
        $agegroup->loadAgeGroupById($agegroupid) ;

        return $agegroup->getAgeGroupText() ;
    }

    /**
     * Map the age group id into gender
     *
     * @return int - gender constant
     */
    function __mapAgeGroupIdToGender($agegroupid)
    {
        $agegroup = new SwimTeamAgeGroup() ;
        $agegroup->loadAgeGroupById($agegroupid) ;

        return $agegroup->getAgeGroupGender() ;
    }

    /**
     * Map the age group id into gender label
     *
     * @return string - gender label
     */
    function __mapAgeGroupIdToGenderLabel($agegroupid)
    {
        $agegroup = new SwimTeamAgeGroup() ;
        $agegroup->loadAgeGroupById($agegroupid) ;

        return $agegroup->getAgeGroupGenderLabel() ;
    }

    /**
     * Map the course into text
     *
     * @return string - season text description
     */
    function __mapCourseCodeToText($course)
    {
        switch($course)
        {
            case WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL ;
                break ;

            default :
                $obj = WPST_UNKNOWN ;
                break ;
        }

        return $obj ;
    }

    /**
     * Map the stroke code into text
     *
     * @return string - stroke text description
     */
    function __mapStrokeCodeToText($stroke)
    {
        switch($stroke)
        {
            case WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL ;
                break ;

            default :
                $obj = WPST_UNKNOWN ;
                break ;
        }

        return $obj ;
    }

    /**
     * Constructor - Build the Ajax form content
     *
     * @param meetid - int - meet id to connect events to
     */
    function WpSwimMeetEventReorderAjaxForm($meetid = WPST_NULL_ID)
    {
        $this->agegroup = new SwimTeamAgeGroup() ;

        $this->set_id("wpst_eventorder") ;
        //Portlet::Portlet() ;
        TABLEtag::TABLEtag() ;

        //  Header row needs and Id as well ...

        $head = html_thead() ;
        $tr = html_tr() ;
        $tr->set_id("event-0") ;
        $tr->set_class("nodrop nodrag") ;
        $th = html_th("Event Number") ;
        $th->set_id("eventnumber-0") ;
        $tr->add(html_th("Id"), $th,
            html_th("Age Group"), html_th("Event Description")) ;
        $head->add($tr) ;

        $this->add($head) ;

        $tbody = html_tbody() ;
        $tbody->set_id("wpst_eventorder-2") ;

        //$this->setPortletColumns(2) ;

        //  Loop through events

        $event = new SwimMeetEvent() ;

        $eventIds = $event->getAllEventIdsByMeetId($meetid) ;

        foreach ($eventIds as $eventId)
        {
            $event->loadSwimMeetEventByEventId($eventId["eventid"]) ;

            $this->agegroup->loadAgeGroupById($event->getAgeGroupId()) ;

            $desc = sprintf("%s %s %s", 
                $event->getDistance(),
                $this->__mapCourseCodeToText($event->getCourse()),
                $this->__mapStrokeCodeToText($event->getStroke())) ;

            $tr = html_tr() ;

            if ($this->agegroup->getGender() == WPST_GENDER_MALE)
                $tr->set_class("male") ;
            else
                $tr->set_class("female") ;

            $tr->set_id(sprintf("event-%d", $eventId["eventid"])) ;
            $td = html_td() ;
            $td->set_id(sprintf("eventnumber-%d", $eventId["eventid"])) ;
            $td->add($event->getEventNumber()) ;
            $tr->add($eventId["eventid"], $td, 
                $this->__mapAgeGroupIdToText($event->getAgeGroupId()), $desc) ;
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
            get_option('url') . "/wp-admin/admin-ajax.php")) ;
        $this->add($script) ;

        $msg = html_div() ;
        $msg->set_id("wpst_reorder_events_msg") ;
        $msg->add("&nbsp;") ;
        $this->add($msg) ;
    }
}
?>
