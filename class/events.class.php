<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Events classes.
 *
 * $Id: events.class.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Events
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('db.class.php') ;
require_once('swimteam.include.php') ;
require_once('events.include.php') ;
require_once('swimclubs.class.php') ;
require_once('widgets.class.php') ;
require_once('textmap.class.php') ;

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamEvent extends SwimTeamDBI
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
     * event group id property - id of event group event is connected to
     */
    var $__eventgroupid ;

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
     * min age property - minimum age for the event.
     *
     * The value of the min age property comes from
     * age group record for this event.
     */
    var $__minage ;

    /**
     * max age property - maximum age for the event.
     *
     * The value of the max age property comes from
     * age group record for this event.
     */
    var $__maxage ;

    /**
     * gender property - gender for the event.
     *
     * The value of the course property comes from
     * the SDIF standard.
     */
    var $__gender ;

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
     * Set the eventgroup id
     *
     * @param - int - id of the eventgroup
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the eventgroup id
     *
     * @return - int - id of the eventgroup
     */
    function getEventGroupId()
    {
        return ($this->__eventgroupid) ;
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
     * Set the minage of the event
     *
     * @param - int - minage of the event
     */
    function setMinAge($minage)
    {
        $this->__minage = $minage ;
    }

    /**
     * Get the minage of the event
     *
     * @return - int - minage of the event
     */
    function getMinAge()
    {
        return ($this->__minage) ;
    }

    /**
     * Set the maxage of the event
     *
     * @param - int - maxage of the event
     */
    function setMaxAge($maxage)
    {
        $this->__maxage = $maxage ;
    }

    /**
     * Get the maxage of the event
     *
     * @return - int - maxage of the event
     */
    function getMaxAge()
    {
        return ($this->__maxage) ;
    }

    /**
     * Set the gender of the event
     *
     * @param - int - gender of the event
     */
    function setGender($gender)
    {
        //  Need to do some "parsing" to make sure the value
        //  is stored as the "SDIF" value and not what WPST uses.
 
        if ($gender == WPST_GENDER_MALE)
            $this->__gender = WPST_SDIF_SWIMMER_SEX_CODE_MALE_VALUE ;
        else if ($gender == WPST_GENDER_FEMALE)
            $this->__gender = WPST_SDIF_SWIMMER_SEX_CODE_FEMALE_VALUE ;
        else
            $this->__gender = $gender ;
    }

    /**
     * Get the gender of the event
     *
     * @return - int - gender of the event
     */
    function getGender()
    {
        return ($this->__gender) ;
    }

    /**
     *
     * Check if an event already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of event
     */
    function getSwimTeamEventExists($eventnumber = false, $seteventid = false)
    {
	    //  Is a similar event already in the database?

        if ($eventnumber)
        {
            $query = sprintf('SELECT eventid FROM %s WHERE
                meetid = "%s" AND
                agegroupid = "%s" AND
                eventgroupid = "%s" AND
                eventnumber = "%s" AND
                stroke = "%s" AND
                distance = "%s" AND
                course="%s"',
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventGroupId(),
                $this->getEventNumber(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse()) ;
        }
        else
        {
            $query = sprintf('SELECT eventid FROM %s WHERE
                meetid = "%s" AND
                agegroupid = "%s" AND
                eventgroupid = "%s" AND
                stroke = "%s" AND
                distance = "%s" AND
                course="%s"',
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventGroupId(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse()) ;
        }

        //  Retain the query result so it can be used later if needed
 
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure event doesn't exist

        $eventExists = (bool)($this->getQueryCount() > 0) ;

        //  Save the Event Id?
        if ($eventExists && $seteventid)
        {
            $result = $this->getQueryResult() ;
            $this->setEventId($result['eventid']) ;
        }

	    return $eventExists ;
    }

    /**
     *
     * Check if a event already exists in the database
     * based on the Event Id and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of event
     */
    function getSwimTeamEventExistsByEventId($eventid = null, $seteventid = false)
    {
        if (is_null($eventid)) $eventid = $this->getEventId() ;

	    //  Is id already in the database?

        $query = sprintf('SELECT eventid FROM %s WHERE eventid = "%s"',
            WPST_EVENTS_TABLE, $eventid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $eventIdExists = (bool)($this->getQueryCount() > 0) ;

        //  Save the Event Id?
        if ($eventIdExists && $seteventid)
        {
            $result = $this->getQueryResult() ;
            $this->setEventId($result['eventid']) ;
        }

	    return $eventIdExists ;
    }

    /**
     *
     * Check if a event already exists in the database
     * based on the Event Number and return a boolean accordingly.
     *
     * @param - string - optional event number
     * @param - boolean - optional flag to set the event id
     * @return - boolean - existance of event
     */
    function getSwimTeamEventExistsByEventNumberAndGroupId($eventnumber = null,
        $eventgroupid = null, $seteventid = false)
    {
        if (is_null($eventnumber)) $eventnumber = $this->getEventNumber() ;
        if (is_null($eventgroupid)) $eventgroupid = $this->getEventGroupId() ;

	    //  Is Event Number already in the database?

        $query = sprintf('SELECT eventid FROM %s WHERE eventnumber="%s"
            AND eventgroupid="%s"', WPST_EVENTS_TABLE, $eventnumber, $eventgroupid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure id doesn't exist

        $eventIdExists = (bool)($this->getQueryCount() > 0) ;

        //  Save the Event Id?
        if ($eventIdExists && $seteventid)
        {
            $result = $this->getQueryResult() ;
            $this->setEventId($result['eventid']) ;
        }

	    return $eventIdExists ;
    }

    /**
     * Add a new swim event
     */
    function addSwimTeamEvent()
    {
        $success = null ;

        //  Make sure the event doesn't exist yet

        if (!$this->getSwimTeamEventExists())
        {
            //  Construct the insert query
 
            $query = sprintf('INSERT INTO %s SET
                meetid="%s",
                agegroupid="%s",
                eventgroupid="%s",
                eventnumber="%s",
                stroke="%s",
                distance="%s",
                course="%s"',
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventGroupId(),
                $this->getEventNumber(),
                $this->getStroke(),
                $this->getDistance(),
                $this->getCourse()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
            $this->setEventId($this->getInsertId()) ;
        }

        return $success ;
    }

    /**
     * Update a swim event
     *
     */
    function updateSwimTeamEvent()
    {
        $success = null ;

        //  Make sure the event exists, can't update something that doesn't!

        if ($this->getSwimTeamEventExistsByEventId())
        {
            //  Construct the update query
 
            $query = sprintf('UPDATE %s SET
                meetid="%s",
                agegroupid="%s",
                eventgroupid="%s",
                eventnumber="%s",
                stroke="%s",
                distance="%s",
                course="%s"
                WHERE eventid="%s"',
                WPST_EVENTS_TABLE,
                $this->getMeetId(),
                $this->getAgeGroupId(),
                $this->getEventGroupId(),
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
            wp_die('Unable to update event record.') ;
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
    function deleteSwimTeamEvent()
    {
        $success = null ;

        //  Make sure the event exists yet

        if ($this->getSwimTeamEventExistsByEventId())
        {
            //  Before deleting the event, need to make sure any registrations
            //  associated with it are deleted as well to prevent orphan registrations.
 
            $sm = new SwimMeetMeta() ;
            $sm->deleteSwimMeetMetaByEventId($this->getEventId()) ;

            //  Construct the delete query
 
            $query = sprintf('DELETE FROM %s WHERE eventid="%s"',
                WPST_EVENTS_TABLE, $this->getEventId()) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->getSwimTeamEventExistsByEventId() ;

        return $success ;
    }

    /**
     *
     * Load event record by Id
     *
     * @param - string - optional event id
     */
    function loadSwimTeamEventByEventId($eventid = null)
    {
        if (is_null($eventid)) $eventid = $this->getEventId() ;

        //  Dud?
        if (is_null($eventid)) return false ;

        $this->setEventId($eventid) ;

        //  Make sure it is a legal event id
        if ($this->getSwimTeamEventExistsByEventId($eventid))
        {
            $where_clause = sprintf('%s AND %s.eventid="%s"',
                WPST_EXTENDED_EVENTS_WHERE_CLAUSE, WPST_EVENTS_TABLE, $eventid) ;
            $query = sprintf('SELECT %s FROM %s WHERE %s',
                WPST_EXTENDED_EVENTS_COLUMNS, WPST_EXTENDED_EVENTS_TABLES, $where_clause) ;

            //$query = sprintf('SELECT * FROM %s WHERE eventid="%s"',
            //    WPST_EVENTS_TABLE, $eventid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            //print '<pre>' ;
            //print_r($result) ;
            //print '</pre>' ;

            $this->setEventId($result['eventid']) ;
            $this->setMeetId($result['meetid']) ;
            $this->setAgeGroupId($result['agegroupid']) ;
            $this->setEventGroupId($result['eventgroupid']) ;
            $this->setEventNumber($result['eventnumber']) ;
            $this->setStroke($result['stroke']) ;
            $this->setDistance($result['distance']) ;
            $this->setCourse($result['course']) ;
            $this->setMinAge($result['minage']) ;
            $this->setMaxAge($result['maxage']) ;
            $this->setGender($result['gender']) ;
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
    function getAllEventIds($filter = null, $orderby = 'eventnumber')
    {
        //  Select the records for the season

        $query = sprintf('SELECT eventid FROM %s', WPST_EVENTS_TABLE) ;
        if (!is_null($filter) && ($filter != ''))
            $query .= sprintf(' WHERE %s', $filter) ;

        $query .= sprintf(' ORDER BY %s', $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
        //var_dump($query) ;
        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Event Ids for a meet
     *
     * @param - optional - meet id, default to the standard events
     * @return - array - array of swimmers ids
     */
    function getAllEventIdsByMeetId($meetid = WPST_NULL_ID, $orderby = 'eventnumber')
    {
        $filter = sprintf('meetid="%s"', $meetid) ;

        return $this->getAllEventIds($filter, $orderby) ;
    }

    /**
     * Retrieve all the Event Ids for an event group
     *
     * @param - optional - event group id, default to no group id
     * @return - array - array of swimmers ids
     */
    function getAllEventIdsByEventGroupId($eventgroupid = WPST_NULL_ID, $orderby = 'eventnumber')
    {
        $filter = sprintf('eventgroupid="%s"', $eventgroupid) ;

        return $this->getAllEventIds($filter, $orderby) ;
    }

    /**
     * Retrieve all the Event Ids for an event group
     *
     * @param - optional - event group id, default to no group id
     * @return - array - array of swimmers ids
     */
    function getAllEventIdsByEventGroupIdAndMeetId($eventgroupid = WPST_NULL_ID, $meetid = WPST_NULL_ID, $orderby = 'eventnumber')
    {
        $filter = sprintf('eventgroupid="%s" AND meetid="%s"', $eventgroupid, $meetid) ;

        return $this->getAllEventIds($filter, $orderby) ;
    }

    /**
     * Retrieve all the Event Ids for an event group
     *
     * @param - optional - event group id, default to no group id
     * @return - array - array of swimmers ids
     */
    function getAllEventIdsByEventGroupIdAndEventNumber($eventgroupid = WPST_NULL_ID, $eventnumber = WPST_NULL_ID, $orderby = 'eventnumber')
    {
        $filter = sprintf('eventgroupid="%s" AND eventnumber="%s"', $eventgroupid, $eventnumber) ;

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

        $query = sprintf('SELECT MAX(eventnumber) as maxeventnumber FROM %s', WPST_EVENTS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        $qr = $this->getQueryResult() ;
        return $qr['maxeventnumber'] ;
    }
}

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimMeetEvent extends SwimTeamEvent
{
    /**
     *
     * Load event record by Id
     *
     * @param - string - optional event id
     */
    function loadSwimMeetEventByEventId($eventid = null)
    {
        return parent::loadSwimTeamEventByEventId($eventid) ;
    }
}


/**
 * Class definition of the event groups
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamEventGroup extends SwimTeamDBI
{
    /**
     * eventgroup id property - used for unique database identifier
     */
    var $__eventgroupid ;

    /**
     * description property - desription of the eventgroup
     */
    var $__eventgroupdescription ;

    /**
     * status property - status of eventgroup (active, inactive)
     */
    var $__eventgroupstatus ;

    /**
     * Set the eventgroup id
     *
     * @param - int - id of the eventgroup
     */
    function setEventGroupId($id)
    {
        $this->__eventgroupid = $id ;
    }

    /**
     * Get the eventgroup id
     *
     * @return - int - id of the eventgroup
     */
    function getEventGroupId()
    {
        return ($this->__eventgroupid) ;
    }

    /**
     * Set the description of the eventgroup
     *
     * @param - string - description of the eventgroup
     */
    function setEventGroupDescription($description)
    {
        $this->__eventgroupdescription = $description ;
    }

    /**
     * Get the description of the eventgroup
     *
     * @return - string - description of the eventgroup record
     */
    function getEventGroupDescription()
    {
        return ($this->__eventgroupdescription) ;
    }

    /**
     * Set the eventgroup status
     *
     * @param - int - status of the eventgroup
     */
    function setEventGroupStatus($status)
    {
        $this->__eventgroupstatus = $status ;
    }

    /**
     * Get the eventgroup status
     *
     * @return - int - status of the eventgroup
     */
    function getEventGroupStatus()
    {
        return ($this->__eventgroupstatus) ;
    }

    /**
     *
     * Check if a position already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional eventgroup id
     * @return - boolean - existance of position
     */
    function eventgroupExistByDescription($eventgroupid = null)
    {
        if (is_null($eventgroupid))
            $query = sprintf('SELECT eventgroupdescription FROM %s WHERE
                eventgroupdescription="%s"', WPST_EVENT_GROUPS_TABLE,
                $this->getEventGroupDescription()) ;
        else
            $query = sprintf('SELECT eventgroupdescription FROM %s WHERE
                eventgroupid="%s" AND eventgroupdescription="%s"',
                WPST_EVENT_GROUPS_TABLE, $eventgroupid, $this->getEventGroupDescription()) ;

	    //  Is position already in the database?

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure position doesn't exist

        $positionExists = (bool)($this->getQueryCount() > 0) ;

	    return $positionExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of position
     */
    function eventgroupExistById($id = null)
    {
        if (is_null($id)) $id = $this->getEventGroupId() ;

	    //  Is id already in the database?

        $query = sprintf('SELECT eventgroupid FROM %s WHERE eventgroupid = "%s"',
            WPST_EVENT_GROUPS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new eventgroup
     */
    function addEventGroup()
    {
        $success = null ;

        //  Make sure the eventgroup doesn't exist yet

        if (!$this->eventgroupExistByDescription())
        {
            //  Construct the insert query
 
            $query = sprintf('INSERT INTO %s SET
                eventgroupdescription="%s",
                eventgroupstatus="%s"',
                WPST_EVENT_GROUPS_TABLE,
                $this->getEventGroupDescription(),
                $this->getEventGroupStatus()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update a new eventgroup
     */
    function updateEventGroup()
    {
        //  Make sure the eventgroup does exist

        if ($this->eventgroupExistById())
        {
            //  Construct the insert query
 
            $query = sprintf('UPDATE %s SET
                eventgroupdescription="%s",
                eventgroupstatus="%s"
                WHERE eventgroupid="%s"',
                WPST_EVENT_GROUPS_TABLE,
                $this->getEventGroupDescription(),
                $this->getEventGroupStatus(),
                $this->getEventGroupId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            wp_die('Unable to update eventgroup record.') ;
        }

        return $success ;
    }

    /**
     * delete eventgroup
     *
     * @return int - success, number of rows affected
     */
    function deleteEventGroup()
    {
        //  Make sure the eventgroup does exist

        if ($this->eventgroupExistById())
        {
            //  Need the full record before deleting it

            $this->loadEventGroupByEventGroupId() ;

            //  Before deleting the allocation record, need
            //  to delete all of the event records which are
            //  connected to it.

            $event = new SwimTeamEvent() ;

            $eventids = $event->getAllEventIdsByEventGroupId($this->getEventGroupId()) ;

            if (empty($eventids)) $eventids = array() ;
 
            //  Remove any existing event group assignment, set it back to the default

            foreach ($eventids as $eventid)
            {
                $event->loadEventById($eventid) ;
                $event->setEventGroupId(WPST_NONE) ;
                $event->updateEvent() ;
            }

            //  Construct the delete query and update the allocation
 
            $query = sprintf('DELETE FROM %s WHERE eventgroupid="%s"',
                WPST_EVENT_GROUPS_TABLE, $this->getEventGroupId()) ;

            $this->setQuery($query) ;
            $success = $this->runDeleteQuery() ;
        }
        else
        {
            $success = false ;
        }

        return $success ;
    }

    /**
     *
     * Load a eventgroup record by Id
     *
     * @param - string - optional position
     */
    function loadEventGroupById($id = null)
    {
        if (is_null($id)) $id = $this->getEventGroupId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setEventGroupId($id) ;

        //  Make sure it is a legal eventgroup id
        if ($this->eventgroupExistById())
        {
            $query = sprintf('SELECT * FROM %s WHERE eventgroupid ="%s"',
                WPST_EVENT_GROUPS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setEventGroupId($result['eventgroupid']) ;
            $this->setEventGroupDescription($result['eventgroupdescription']) ;
            $this->setEventGroupStatus($result['eventgroupstatus']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Retrieve all the EventGroup Ids.
     *
     * @param - string - optional filter to restrict query
     * @param - string - optional order by to order query results
     * @return - array - array of assignment ids
     */
    function getAllEventGroupIds($filter = null, $orderby = null)
    {
        //  Select the records for the season

        $query = sprintf('SELECT %s.eventgroupid FROM %s',
                WPST_EVENT_GROUPS_TABLE, WPST_EVENT_GROUPS_TABLE) ;

        if (!is_null($filter) && ($filter != ''))
            $query .= sprintf(' WHERE %s', $filter) ;

        if (is_null($orderby) || ($orderby == ''))
            $orderby = sprintf('%s.%s', WPST_EVENT_GROUPS_TABLE, 'eventgroupposition') ;

        $query .= sprintf(' ORDER BY %s', $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Event Group Ids for the seasons.
     * Events can be filtered to return a subset of records
     *
     * @param - string - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getEventGroupIds($filter = null, $orderby = 'eventgroupid')
    {
        //  Select the records for the season

        $query = sprintf('SELECT eventgroupid FROM %s', WPST_EVENT_GROUPS_TABLE) ;
        if (!is_null($filter) && ($filter != ''))
            $query .= sprintf(' WHERE %s', $filter) ;

        $query .= sprintf(' ORDER BY %s', $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Get the count of Events for the Event Group
     *
     * @param - int - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getEventGroupCount($eventgroupid = null)
    {
        if (is_null($eventgroupid)) $eventgroupid = $this->getEventGroupId() ;

        //  Select the count of records for the Event Group in
        //  the Events ignoring the ones assigned to a real meet.

        $query = sprintf('SELECT COUNT(eventid) AS eventcount FROM
            %s WHERE eventgroupid="%s" AND meetid="%s"', WPST_EVENTS_TABLE,
            $eventgroupid, WPST_NULL_ID) ;
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResult() ;
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
class SwimTeamEventsGUIDataList extends SwimTeamGUIDataList
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
    function SwimTeamEventsGUIDataList($title, $width = '100%',
        $default_orderby = '', $default_reverseorder = false,
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

		$this->add_header_item('Event',
	       	    '50', 'eventnumber', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Age Group',
	       	    '125', 'agegroupid', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Distance',
	         	'75', 'distance', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Course',
	         	'150', 'course', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Stroke',
	         	'150', 'stroke', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Group',
	         	'175', 'eventgroupid', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('ID',
	       	    '30', 'eventid', SORTABLE, SEARCHABLE, 'left') ;

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
            //case 'Event' :
            //    $obj = $row_data['eventgroupid'] . '-' . $row_data['eventnumber'] ;
            //    break ;

            case 'Course' :
                $obj = SwimTeamTextMap::__mapCourseCodeToText($row_data['course']) ;
                break ;

            case 'Age Group' :
                $obj = SwimTeamTextMap::__mapAgeGroupIdToText($row_data['agegroupid']) ;
                break ;

            case 'Stroke' :
                $obj = SwimTeamTextMap::__mapStrokeCodeToText($row_data['stroke']) ;
                break ;

            case 'Group' :
                $obj = SwimTeamTextMap::__mapEventGroupIdToText($row_data['eventgroupid']) ;
                break ;

		    default:
			    $obj = DefaultGUIDataList::build_column_item($row_data, $col_name);
			    break;
		}
		return $obj;
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
class SwimTeamEventsAdminGUIDataList extends SwimTeamEventsGUIDataList
{
    /**
     * Property to store the requested action
     */
    var $__action ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_EVENTS_ADD => WPST_ACTION_EVENTS_ADD
        ,WPST_ACTION_EVENTS_UPDATE => WPST_ACTION_EVENTS_UPDATE
        ,WPST_ACTION_EVENTS_IMPORT => WPST_ACTION_EVENTS_IMPORT
        //,WPST_ACTION_EVENTS_LOAD => WPST_ACTION_EVENTS_LOAD
        ,WPST_ACTION_EVENTS_REORDER => WPST_ACTION_EVENTS_REORDER
        ,WPST_ACTION_EVENTS_DELETE => WPST_ACTION_EVENTS_DELETE
        ,WPST_ACTION_EVENTS_DELETE_ALL => WPST_ACTION_EVENTS_DELETE_ALL
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
         WPST_ACTION_EVENTS_ADD => WPST_ACTION_EVENTS_ADD
        ,WPST_ACTION_EVENTS_IMPORT => WPST_ACTION_EVENTS_IMPORT
        ,//WPST_ACTION_EVENTS_LOAD => WPST_ACTION_EVENTS_LOAD
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

		//$this->add_header_item('Id',
	    //   	    '50', 'eventid', SORTABLE, SEARCHABLE, 'left') ;

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', 'eventid') ;
        $this->set_radio_var_name('_eventid', false) ;

        //  we have to be in POST mode, or we could run out
        //  of space in the http request with the saved
        //  checkbox items
        
        $this->set_form_method('POST') ;

        //  set the flag to save the checked items
        //  between pages.
        
        $this->save_checked_items(true) ;
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
class SwimTeamEventGroupsGUIDataList extends SwimTeamGUIDataList
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
    function SwimTeamEventGroupsGUIDataList($title, $width = '100%',
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_EVENT_GROUPS_WITH_EVENT_COUNT_COLUMNS,
        $tables = WPST_EVENT_GROUPS_DEFAULT_TABLES,
        $where_clause = WPST_EVENT_GROUPS_DEFAULT_WHERE_CLAUSE)
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

		$this->add_header_item('Event Group',
	       	    '200', 'eventgroupdescription', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Event Count',
	       	    '50', 'eventcount', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Status',
	       	    '50', 'eventgroupstatus', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('ID',
	       	    '15', 'eventgroupid', SORTABLE, SEARCHABLE, 'left') ;

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
            /*
            case 'Event Group' :
                $obj = SwimTeamTextMap::__mapEventGroupIdToText($row_data['eventgroupid']) ;
                break ;
             */

            case 'Status' :
                $obj = ucwords($row_data['eventgroupstatus']) ;
                break ;

		    default:
			    $obj = DefaultGUIDataList::build_column_item($row_data, $col_name);
			    break;
		}
		return $obj;
    }
}

/**
 * GUIDataList class for performaing administration tasks
 * on the various events.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamEventGroupsGUIDataList
 */
class SwimTeamEventGroupsAdminGUIDataList extends SwimTeamEventGroupsGUIDataList
{
    /**
     * Property to store the requested action
     */
    var $__action ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_ADD => WPST_ACTION_ADD
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        ,WPST_ACTION_DELETE => WPST_ACTION_DELETE
        //,WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        //,WPST_ACTION_EVENTS => WPST_ACTION_EVENTS
        //,WPST_ACTION_MANAGE => WPST_ACTION_MANAGE
        ,WPST_ACTION_EVENTS_REPORT => WPST_ACTION_EVENTS_REPORT
        ,WPST_ACTION_EVENTS_MANAGE => WPST_ACTION_EVENTS_MANAGE
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
         WPST_ACTION_ADD => WPST_ACTION_ADD
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

		//$this->add_header_item('Id',
	    //   	    '50', 'eventid', SORTABLE, SEARCHABLE, 'left') ;

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', 'eventgroupid') ;
        $this->set_radio_var_name('_eventgroupid', false) ;

        //  we have to be in POST mode, or we could run out
        //  of space in the http request with the saved
        //  checkbox items
        
        $this->set_form_method('POST') ;

        //  set the flag to save the checked items
        //  between pages.
        
        $this->save_checked_items(true) ;
    }
}

/**
 * GUIDataList class for performaing administration tasks
 * on the various events.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamEventsAdminGUIDataList
 * @since v1.19
 */
class SwimMeetEventsGUIDataList extends SwimTeamEventsGUIDataList
{
}

/**
 * GUIDataList class for performaing administration tasks
 * on the various events.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamEventsAdminGUIDataList
 * @since v1.19
 */
class SwimMeetEventsAdminGUIDataList extends SwimTeamEventsAdminGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
        //WPST_ACTION_EVENTS_ADD => WPST_ACTION_EVENTS_ADD
        //,WPST_ACTION_EVENTS_UPDATE => WPST_ACTION_EVENTS_UPDATE
        //,WPST_ACTION_EVENTS_IMPORT => WPST_ACTION_EVENTS_IMPORT
         WPST_ACTION_EVENTS_LOAD => WPST_ACTION_EVENTS_LOAD
        ,WPST_ACTION_EVENTS_REORDER => WPST_ACTION_EVENTS_REORDER
        ,WPST_ACTION_EVENTS_DELETE => WPST_ACTION_EVENTS_DELETE
        ,WPST_ACTION_EVENTS_DELETE_ALL => WPST_ACTION_EVENTS_DELETE_ALL
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
        // WPST_ACTION_EVENTS_ADD => WPST_ACTION_EVENTS_ADD
        //,WPST_ACTION_EVENTS_IMPORT => WPST_ACTION_EVENTS_IMPORT
        WPST_ACTION_EVENTS_LOAD => WPST_ACTION_EVENTS_LOAD
    ) ;
}

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamEventGroupInfoTable extends SwimTeamInfoTable
{
    /**
     * Construct a summary of the active season.
     *
     */
    function constructSwimTeamEventGroupeInfoTable($eventgroupid)
    {
        $hdr = 0 ;

        //  Alternate the row colors
        $this->set_alt_color_flag(true) ;
        $this->set_column_header($hdr++, 'Event Number', null, 'left') ;
        $this->set_column_header($hdr++, 'Age Group', null, 'left') ;
        $this->set_column_header($hdr++, 'Description', null, 'left') ;

        //  Find all of the events in the season

        $event = new SwimTeamEvent() ;
        $agegroup = new SwimTeamAgeGroup() ;
        
        $eventIds = $event->getAllEventIdsByEventGroupId($eventgroupid) ;

        //  Looop through events ids
        foreach ($eventIds as $eventId)
        {
            $event->loadSwimTeamEventByEventId($eventId['eventid']) ;

            $agegroup->loadAgeGroupById($event->getAgeGroupId()) ;

            $desc = sprintf('%s %s %s', 
                $event->getDistance(),
                SwimTeamTextMap::__mapCourseCodeToText($event->getCourse()),
                SwimTeamTextMap::__mapStrokeCodeToText($event->getStroke())) ;

            $this->add_row( $event->getEventNumber(),
                SwimTeamTextMap::__mapAgeGroupIdToText($event->getAgeGroupId()), $desc) ;
        }
    }
}

/**
 * Class definition of the events
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamEventScheduleInfoTable extends SwimTeamInfoTable
{
    /**
     * Construct a summary of the active season.
     *
     */
    function constructSwimTeamEventScheduleInfoTable($seasonid = null)
    {
        $hdr = 0 ;

        //  Alternate the row colors
        $this->set_alt_color_flag(true) ;
        $this->set_column_header($hdr++, 'Date', null, 'left') ;
        $this->set_column_header($hdr++, 'Opponent', null, 'left') ;
        $this->set_column_header($hdr++, 'Location', null, 'left') ;
        $this->set_column_header($hdr++, 'Result', null, 'left') ;

        $season = new SwimTeamSeason() ;

        //  Season Id supplied?  If not, use the active season.

        if ($seasonid == null)
            $seasonid = $season->getActiveSeasonId() ;

        //  Find all of the events in the season

        $event = new SwimTeamEvent() ;
        $eventIds = $event->getAllEventIds(sprintf('seasonid="%s"', $seasonid)) ;

        foreach ($eventIds as $eventId)
        {
            $event->loadSwimTeamEventByEventId($eventId['eventid']) ;

            if ($event->getEventType() == WPST_DUAL_MEET)
                $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
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
                    $winloss = sprintf('Win:  %s - %s', $ts, $os) ;
                else if  ($ts < $os)
                    $winloss = sprintf('Loss:  %s - %s', $ts, $os) ;
                else if ((strtotime('now') > strtotime($event->getEventDate()))
                    && ($ts == 0) && ($os == 0))
                    $winloss = sprintf('Tie:  %s - %s', $ts, $os) ;
                else if (($ts == 0) && ($os == 0))
                    $winloss = 'TBD' ;
                else
                    $winloss = sprintf('Tie:  %s - %s', $ts, $os) ;
            }
            else
            {
                $winloss = 'N/A' ;
            }

            $eventdate = date('D M j, Y', strtotime($event->getEventDate())) ;
            $this->add_row($eventdate, $opponent,
                ucfirst($event->getLocation()), $winloss) ;
        }
    }
}
?>
