<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Events classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Events
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("db.class.php") ;
require_once("swimteam.include.php") ;
require_once("events.include.php") ;
require_once("swimclubs.class.php") ;
require_once("widgets.class.php") ;

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimMeetEvent extends SwimTeamDBI
{
    /**
     * event id property - used for unique database identifier
     */
    var $__eventid ;

    /**
     * meet id property - id of meet event is connected to
     */
    var $__meetid = WPST_NULL_ID ;

    /**
     * age group id property - id of age group event is connected to
     */
    var $__agegroupid ;

    /**
     * event number property - used for event sequencing
     */
    var $__eventnumber ;

    /**
     * stroke property - the stroke for the event.
     *
     * The value of the stroke property comes from
     * the SDIF standard.
     */
    var $__stroke ;

    /**
     * distance property - the distance for the event.
     *
     * The value of the distance property comes from
     * the SDIF standard.
     */
    var $__distance ;

    /**
     * course property - the course for the event.
     *
     * The value of the course property comes from
     * the SDIF standard.
     */
    var $__course ;

    /**
     * Set the event id
     *
     * @param - int - id of the event
     */
    function setEventId($id)
    {
        $this->__eventid = $id ;
    }

    /**
     * Get the event id
     *
     * @return - int - id of the event
     */
    function getEventId()
    {
        return ($this->__eventid) ;
    }

    /**
     * Set the meet id
     *
     * @param - int - id of the meet
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id
     *
     * @return - int - id of the meet
     */
    function getMeetId()
    {
        return ($this->__meetid) ;
    }

    /**
     * Set the agegroup id
     *
     * @param - int - id of the agegroup
     */
    function setAgeGroupId($id)
    {
        $this->__agegroupid = $id ;
    }

    /**
     * Get the agegroup id
     *
     * @return - int - id of the agegroup
     */
    function getAgeGroupId()
    {
        return ($this->__agegroupid) ;
    }

    /**
     * Set the event number
     *
     * @param - int - event number
     */
    function setEventNumber($type)
    {
        $this->__eventnumber = $type ;
    }

    /**
     * Get the event number
     *
     * @return - int - event number
     */
    function getEventNumber()
    {
        return ($this->__eventnumber) ;
    }

    /**
     * Set the stroke
     *
     * @param - int - stroke
     */
    function setStroke($stroke)
    {
        $this->__stroke = $stroke ;
    }

    /**
     * Get the stroke
     *
     * @return - int - stroke
     */
    function getStroke()
    {
        return ($this->__stroke) ;
    }

    /**
     * Set the distance of the event
     *
     * @param - int - distance of the event
     */
    function setDistance($distance)
    {
        $this->__distance = $distance ;
    }

    /**
     * Get the distance of the event
     *
     * @return - int - distance of the event
     */
    function getDistance()
    {
        return ($this->__distance) ;
    }

    /**
     * Set the course of the event
     *
     * @param - int - course of the event
     */
    function setCourse($course)
    {
        $this->__course = $course ;
    }

    /**
     * Get the course of the event
     *
     * @return - int - course of the event
     */
    function getCourse()
    {
        return ($this->__course) ;
    }

    /**
     *
     * Check if an event already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of event
     */
    function getSwimMeetEventExists($eventnumber = false)
    {
	    //  Is a similar event already in the database?

        if ($eventnumber)
        {
            $query = sprintf("SELECT eventid FROM %s WHERE
                meetid = \"%s\" AND
                agegroupid = \"%s\" AND
                eventnumber = \"%s\" AND
                stroke = \"%s\" AND
                distance = \"%s\" AND
                course=\"%s\"",
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventNumber(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse()) ;
        }
        else
        {
            $query = sprintf("SELECT eventid FROM %s WHERE
                meetid = \"%s\" AND
                agegroupid = \"%s\" AND
                stroke = \"%s\" AND
                distance = \"%s\" AND
                course=\"%s\"",
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse()) ;
        }

        //  Retain the query result so it can be used later if needed
 
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure event doesn't exist

        $eventExists = (bool)($this->getQueryCount() > 0) ;

	    return $eventExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of event
     */
    function getSwimMeetEventExistsByEventId($eventid = null)
    {
        if (is_null($eventid)) $eventid = $this->getEventId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT eventid FROM %s WHERE eventid = \"%s\"",
            WPST_EVENTS_TABLE, $eventid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new swim event
     */
    function addSwimMeetEvent()
    {
        $success = null ;

        //  Make sure the event doesn't exist yet

        if (!$this->getSwimMeetEventExists())
        {
            //  Construct the insert query
 
            $query = sprintf("INSERT INTO %s SET
                meetid=\"%s\",
                agegroupid=\"%s\",
                eventnumber=\"%s\",
                stroke=\"%s\",
                distance=\"%s\",
                course=\"%s\"",
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventNumber(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update a swim event
     *
     */
    function updateSwimMeetEvent()
    {
        $success = null ;

        //  Make sure the event exists, can't update something that doesn't!

        if ($this->getSwimMeetEventExistsByEventId())
        {
            //  Construct the update query
 
            $query = sprintf("UPDATE %s SET
                meetid=\"%s\",
                agegroupid=\"%s\",
                eventnumber=\"%s\",
                stroke=\"%s\",
                distance=\"%s\",
                course=\"%s\"
                WHERE eventid=\"%s\"",
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventNumber(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse(),
                $this->getEventId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }
        else
        {
            wp_die("Unable to update event record.") ;
        }

        return true ;
    }

    /**
     * Delete a swim event
     *
     * Really need to think about this because deleting a event
     * means deleting all of the results that go with it.  So if a
     * event has results, disallow deleting the event.
     *
     */
    function deleteSwimMeetEvent()
    {
        $success = null ;

        //  Make sure the event doesn't exist yet

        if ($this->getSwimMeetEventExistsByEventId())
        {
            //  Construct the insert query
 
            $query = sprintf("DELETE FROM %s
                WHERE eventid=\"%s\"",
                WPST_EVENTS_TABLE,
                $this->getEventId()
            ) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->getSwimMeetEventExistsByEventId() ;

        return $success ;
    }

    /**
     *
     * Load event record by Id
     *
     * @param - string - optional event id
     */
    function loadSwimMeetEventByEventId($eventid = null)
    {
        if (is_null($eventid)) $eventid = $this->getEventId() ;

        //  Dud?
        if (is_null($eventid)) return false ;

        $this->setEventId($eventid) ;

        //  Make sure it is a legal event id
        if ($this->getSwimMeetEventExistsByEventId($eventid))
        {
            $query = sprintf("SELECT * FROM %s WHERE eventid=\"%s\"",
                WPST_EVENTS_TABLE, $eventid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setEventId($result['eventid']) ;
            $this->setMeetId($result['meetid']) ;
            $this->setAgeGroupId($result['agegroupid']) ;
            $this->setEventNumber($result['eventnumber']) ;
            $this->setStroke($result['stroke']) ;
            $this->setDistance($result['distance']) ;
            $this->setCourse($result['course']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Retrieve all the Event Ids for the seasons.
     * Events can be filtered to return a subset of records
     *
     * @param - string - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getAllEventIds($filter = null, $orderby = "eventnumber")
    {
        //  Select the records for the season

        $query = sprintf("SELECT eventid FROM %s", WPST_EVENTS_TABLE) ;
        if (!is_null($filter) && ($filter != ""))
            $query .= sprintf(" WHERE %s", $filter) ;

        $query .= sprintf(" ORDER BY %s", $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Event Ids for a meet
     *
     * @param - optional - meet id, default to the standard events
     * @return - array - array of swimmers ids
     */
    function getAllEventIdsByMeetId($meetid = WPST_NULL_ID, $orderby = "eventnumber")
    {
        $filter = sprintf("meetid=\"%s\"", $meetid) ;

        return $this->getAllEventIds($filter, $orderby) ;
    }

    /**
     * Retrieve all maximum event number.
     *
     * @return - int - maximum event number
     */
    function getMaxEventNumber()
    {
        //  Select the records for the season

        $query = sprintf("SELECT MAX(eventnumber) as maxeventnumber FROM %s", WPST_EVENTS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        $qr = $this->getQueryResult() ;
        return $qr["maxeventnumber"] ;
    }
}

/**
 * Extended GUIDataList Class for presenting SwimTeam
 * information extracted from the database.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamGUIDataList
 */
class SwimMeetEventsGUIDataList extends SwimTeamGUIDataList
{
    /**
     * The constructor
     *
     * @param string - the title of the data list
     * @param string - the overall width
     * @param string - the column to use as the default sorting order
     * @param boolean - sort the default column in reverse order?
     * @param string - columns to query return from database
     * @param string - tables to query from database
     * @param string - where clause for database query
     */
    function SwimMeetEventsGUIDataList($title, $width = "100%",
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_EVENTS_DEFAULT_COLUMNS,
        $tables = WPST_EVENTS_DEFAULT_TABLES,
        $where_clause = WPST_EVENTS_DEFAULT_WHERE_CLAUSE)
    {
        //  Set the properties for this child class
        //$this->setColumns($columns) ;
        //$this->setTables($tables) ;
        //$this->setWhereClause($where_clause) ;

        //  Call the constructor of the parent class
        $this->SwimTeamGUIDataList($title, $width,
            $default_orderby, $default_reverseorder,
            $columns, $tables, $where_clause) ;
    }

    /**
     * This method is used to setup the options
	 * for the DataList object's display
	 * Which columns to show, their respective 
	 * source column name, width, etc. etc.
	 *
     * The constructor automatically calls 
	 * this function.
	 *
     */
	function user_setup()
    {
		//add the columns in the display that you want to view.
		//The API is :
		//Title, width, DB column name, field SORTABLE?, field SEARCHABLE?, align
		$this->add_header_item("ID",
	       	    "50", "eventid", SORTABLE, SEARCHABLE, "left") ;

		$this->add_header_item("Event",
	       	    "100", "eventnumber", SORTABLE, SEARCHABLE, "left") ;

		$this->add_header_item("Age Group",
	       	    "150", "agegroupid", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Distance",
	         	"100", "distance", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Course",
	         	"150", "course", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Stroke",
	         	"150", "stroke", SORTABLE, SEARCHABLE, "left") ;

        //  Construct the DB query
        $this->_datasource->setup_db_options($this->getColumns(),
            $this->getTables(), $this->getWhereClause()) ;

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;
	}

    /**
     * This is the basic function for letting us
     * do a mapping between the column name in
     * the header, to the value found in the DataListSource.
     *
     * NOTE: this function can be overridden so that you can
     *       return whatever you want for any given column.  
     *
     * @param array - $row_data - the entire data for the row
     * @param string - $col_name - the name of the column header
     *                             for this row to render.
     * @return mixed - either a HTMLTag object, or raw text.
     */
	function build_column_item($row_data, $col_name)
    {
		switch ($col_name)
        {
            case "Course" :
                $obj = $this->__mapCourseCodeToText($row_data["course"]) ;
                break ;

            case "Age Group" :
                $obj = $this->__mapAgeGroupIdToText($row_data["agegroupid"]) ;
                break ;

            case "Stroke" :
                $obj = $this->__mapStrokeCodeToText($row_data["stroke"]) ;
                break ;

		    default:
			    $obj = DefaultGUIDataList::build_column_item($row_data, $col_name);
			    break;
		}
		return $obj;
    }

    /**
     * Map the age group id into text for the GDL
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
     * Map the season id into text for the GDL
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
     * Map the opponent swim club id into text for the GDL
     *
     * @return string - opponent text description
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
}

/**
 * GUIDataList class for performaing administration tasks
 * on the various events.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamEventsGUIDataList
 */
class SwimMeetEventsAdminGUIDataList extends SwimMeetEventsGUIDataList
{
    /**
     * Property to store the requested action
     */
    var $__action ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_EVENTS_LOAD => WPST_ACTION_EVENTS_LOAD
        ,WPST_ACTION_EVENTS_ADD => WPST_ACTION_EVENTS_ADD
        ,WPST_ACTION_EVENTS_UPDATE => WPST_ACTION_EVENTS_UPDATE
        ,WPST_ACTION_EVENTS_REORDER => WPST_ACTION_EVENTS_REORDER
        ,WPST_ACTION_EVENTS_DELETE => WPST_ACTION_EVENTS_DELETE
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
         WPST_ACTION_EVENTS_LOAD => WPST_ACTION_EVENTS_LOAD
        ,WPST_ACTION_EVENTS_ADD => WPST_ACTION_EVENTS_ADD
    ) ;

    /**
     * Get admin action
     *
     * @return string - action to take
     */
    function getAdminAction()
    {
        return $this->__action ;
    }

    /**
     * Set admin action
     *
     * @param string - action to take
     */
    function setAdminAction($action)
    {
        $this->__action = $action ;
    }

    /**
     * This method is used to setup the options
	 * for the DataList object's display
	 * Which columns to show, their respective 
	 * source column name, width, etc. etc.
	 *
     * The constructor automatically calls 
	 * this function.
	 *
     */
    function user_setup()
    {
        //  make use of the parent class user_setup()
        //  function to set up the display of the fields

        parent::user_setup() ;

		//$this->add_header_item("Id",
	    //   	    "50", "eventid", SORTABLE, SEARCHABLE, "left") ;

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', "eventid") ;

        //  we have to be in POST mode, or we could run out
        //  of space in the http request with the saved
        //  checkbox items
        
        $this->set_form_method('POST') ;

        //  set the flag to save the checked items
        //  between pages.
        
        $this->save_checked_items(true) ;
    }

    /**
     * Action Bar - build a set of Action Bar buttons
     *
     * @return container - container holding action bar content
     */
    function actionbar_cell()
    {
        //  Add an ActionBar button based on the action the page
        //  was called with.

        $c = container() ;

        //foreach($this->__normal_actions as $key => $button)
        //{
            //$b = $this->action_button($button, $_SERVER['REQUEST_URI']) ;

            /**
             * The above line is commented out because it doesn't work
             * under Safari.  For some reason Safari doesn't pass the value
             * argument of the submit button via Javascript.  The below line
             * will work as long as the intended target is the same as
             * what is specified in the FORM's action tag.
             */

        //    $b = $this->action_button($button) ;
        //    $b->set_tag_attribute("type", "submit") ;
        //    $c->add($b) ;
        //}

        foreach($this->__normal_actions as $key => $action)
            $actions[$action] = $action ;

        $lb = $this->action_select("_action", $actions,
            "", false, array("style" => "width: 150px; margin-right: 10px;"),
            $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']) ;

        $c->add($lb) ;

        return $c ;
    }

    /**
     * Action Bar - build a set of Action Bar buttons
     *
     * @return container - container holding action bar content
     */
    function empty_datalist_actionbar_cell()
    {
        //  Add an ActionBar button based on the action the page
        //  was called with.

        $c = container() ;

        foreach($this->__empty_actions as $key => $button)
        {
            //$b = $this->action_button($button, $_SERVER['REQUEST_URI']) ;

            /**
             * The above line is commented out because it doesn't work
             * under Safari.  For some reason Safari doesn't pass the value
             * argument of the submit button via Javascript.  The below line
             * will work as long as the intended target is the same as
             * what is specified in the FORM's action tag.
             */

            $b = $this->action_button($button) ;
            $b->set_tag_attribute("type", "submit") ;
            $c->add($b) ;
        }

        return $c ;
    }
}

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimMeetEventScheduleInfoTable extends SwimTeamInfoTable
{
    /**
     * Map the opponent swim club id into text for the GDL
     *
     * @return string - opponent text description
     */
    function __mapOpponentSwimClubIdToText($swimclubid)
    {
        //  Handle null id gracefully for non-dual events

        if ($swimclubid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;

        $swimclub = new SwimClubProfile() ;
        $swimclub->loadSwimClubBySwimClubId($swimclubid) ;

        return $swimclub->getClubOrPoolName() . " " . $swimclub->getTeamName() ;
    }

    /**
     * Construct a summary of the active season.
     *
     */
    function constructSwimMeetEventScheduleInfoTable($seasonid = null)
    {
        $hdr = 0 ;

        //  Alternate the row colors
        $this->set_alt_color_flag(true) ;
        $this->set_column_header($hdr++, "Date", null, "left") ;
        $this->set_column_header($hdr++, "Opponent", null, "left") ;
        $this->set_column_header($hdr++, "Location", null, "left") ;
        $this->set_column_header($hdr++, "Result", null, "left") ;

        $season = new SwimTeamSeason() ;

        //  Season Id supplied?  If not, use the active season.

        if ($seasonid == null)
            $seasonid = $season->getActiveSeasonId() ;

        //  Find all of the events in the season

        $event = new SwimMeetEvent() ;
        $eventIds = $event->getAllEventIds(sprintf("seasonid=\"%s\"", $seasonid)) ;

        foreach ($eventIds as $eventId)
        {
            $event->loadSwimMeetEventByEventId($eventId["eventid"]) ;

            if ($event->getEventType() == WPST_DUAL_MEET)
                $opponent = $this->__mapOpponentSwimClubIdToText(
                    $event->getOpponentSwimClubId()) ;
            else
                $opponent = $event->getEventDescription() ;

            //  Determine results - if the score is 0-0 after the
            //  event date then it is deemed a tie instead of a TBD.

            if ($event->getEventType() == WPST_DUAL_MEET)
            {
                $ts = $event->getTeamScore() ;
                $os = $event->getOpponentScore() ;

                if  ($ts > $os)
                    $winloss = sprintf("Win:  %s - %s", $ts, $os) ;
                else if  ($ts < $os)
                    $winloss = sprintf("Loss:  %s - %s", $ts, $os) ;
                else if ((strtotime("now") > strtotime($event->getEventDate()))
                    && ($ts == 0) && ($os == 0))
                    $winloss = sprintf("Tie:  %s - %s", $ts, $os) ;
                else if (($ts == 0) && ($os == 0))
                    $winloss = "TBD" ;
                else
                    $winloss = sprintf("Tie:  %s - %s", $ts, $os) ;
            }
            else
            {
                $winloss = "N/A" ;
            }

            $eventdate = date("D M j, Y", strtotime($event->getEventDate())) ;
            $this->add_row($eventdate, $opponent,
                ucfirst($event->getLocation()), $winloss) ;
        }
    }
}
?>
