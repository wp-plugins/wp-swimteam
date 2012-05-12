<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Meets classes.
 *
 * $Id: swimmeets.class.php 869 2012-05-12 03:55:47Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Meets
 * @version $Revision: 869 $
 * @lastmodified $Date: 2012-05-11 23:55:47 -0400 (Fri, 11 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */


require_once('db.class.php') ;
require_once('swimteam.include.php') ;
require_once('swimmeets.include.php') ;
require_once('swimclubs.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the meets
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimMeet extends SwimTeamDBI
{
    /**
     * meetid property - used for unique database identifier
     */
    var $__meetid ;

    /**
     * season id property - id of season meet is connected to
     */
    var $__seasonid ;

    /**
     * opponent id property - the Swim Club Id of the opponent
     */
    var $__opponentswimclubid ;

    /**
     * meet type property - type of meet - 'dual', 'time trial', etc.
     */
    var $__meettype ;

    /**
     * participation property - type of participation - 'opt in', 'opt out'.
     */
    var $__participation ;

    /**
     * meet status property - type of meet status - 'open', 'closed'.
     */
    var $__meetstatus ;

    /**
     * meet description property - description of meet
     */
    var $__meetdescription ;

    /**
     * location property - location of meet - 'home' or 'away'
     */
    var $__location ;

    /**
     * meet date property - the date of the meet
     */
    var $__meetdate ;

    /**
     * meet time property - the time of the meet
     */
    var $__meettime ;

    /**
     * team score - the team's score
     */
    var $__teamscore ;

    /**
     * opponent score - the opponent's score
     */
    var $__opponentscore ;

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
     * Set the season id
     *
     * @param - int - id of the season
     */
    function setSeasonId($id)
    {
        $this->__seasonid = $id ;
    }

    /**
     * Get the season id
     *
     * @return - int - id of the season
     */
    function getSeasonId()
    {
        return ($this->__seasonid) ;
    }

    /**
     * Set the opponent's swim club id
     *
     * @param - int - opponent's swim club id
     */
    function setOpponentSwimClubId($id)
    {
        $this->__opponentswimclubid = $id ;
    }

    /**
     * Get the opponent's swim club id
     *
     * @return - int - opponent's swim club id
     */
    function getOpponentSwimClubId()
    {
        return ($this->__opponentswimclubid) ;
    }

    /**
     * Set the type of the meet
     *
     * @param - string - type of the meet
     */
    function setMeetType($type)
    {
        $this->__meettype = $type ;
    }

    /**
     * Get the location of the meet
     *
     * @return - string - location of the meet
     */
    function getMeetType()
    {
        return ($this->__meettype) ;
    }

    /**
     * Set the meet participation
     *
     * @param - string - meet participation
     */
    function setParticipation($participation)
    {
        $this->__participation = $participation ;
    }

    /**
     * Get the meet participation
     *
     * @return - string - meet participation
     */
    function getParticipation()
    {
        return ($this->__participation) ;
    }

    /**
     * Set the meet status
     *
     * @param - string - meet status
     */
    function setMeetStatus($status)
    {
        $this->__meetstatus = $status ;
    }

    /**
     * Get the meet status
     *
     * @return - string - meet status
     */
    function getMeetStatus()
    {
        return ($this->__meetstatus) ;
    }

    /**
     * Set the description of the meet
     *
     * @param - string - description of the meet
     */
    function setMeetDescription($description)
    {
        $this->__meetdescription = $description ;
    }

    /**
     * Get the description of the meet
     *
     * @return - string - description of the meet
     */
    function getMeetDescription()
    {
        return ($this->__meetdescription) ;
    }

    /**
     * Set the location of the meet
     *
     * @param - string - location of the meet
     */
    function setLocation($location)
    {
        $this->__location = $location ;
    }

    /**
     * Get the location of the meet
     *
     * @return - string - location of the meet
     */
    function getLocation()
    {
        return ($this->__location) ;
    }

    /**
     * Set the date of the meet
     *
     * @param - array - date of meet
     */
    function setMeetDate($date)
    {
        if (is_array($date))
            $this->__meetdate = $date ;
        else
            $this->__meetdate = array(
                'year' => substr($date, 0, 4)
               ,'month' => substr($date, 5, 2)
               ,'day' => substr($date, 8, 2)
            ) ;
    }

    /**
     * Get the date of the meet as an array
     *
     * @return - array - date of meet as an array
     */
    function getMeetDate()
    {
        return ($this->__meetdate) ;
    }

    /**
     * Get the date of the meet as a string
     *
     * @return - string - start of meet date as a string
     */
    function getMeetDateAsDate()
    {
        //$d = $this->getMeetDateAsArray() ;
        $d = $this->getMeetDate() ;

        return sprintf('%04s-%02s-%02s', $d['year'], $d['month'], $d['day']) ;
    }

    /**
     * Get the date of the meet as a string in SDIF format
     *
     * @return - string - start of meet date as a string
     */
    function getMeetDateAsMMDDYYYY()
    {
        $d = $this->getMeetDate() ;

        return sprintf('%02s%02s%04s', $d['month'], $d['day'], $d['year']) ;
    }

    /**
     * Get the date of the meet as an array
     *
     * @return - array - date of the meet
     */
    function getMeetDateAsArray()
    {
        list($year, $month, $day) = explode('-', $this->getMeetDate()) ;
        
        return array('month' => $month, 'day' => $day, 'year' => $year) ;
    }

    /**
     * Set the time of the meet
     *
     * @param - array - time of the meet
     */
    function setMeetTime($time)
    {
        $this->__meettime = $time ;
    }

    /**
     * Get the time of the meet
     *
     * @return - array - time of the meet
     */
    function getMeetTime()
    {
        return $this->__meettime ;
    }

    /**
     * Get the time of the meet as an array
     *
     * @return - array - time of the meet
     */
    function getMeetTimeAsArray()
    {
        list($hours, $minutes, $seconds) = explode(':', $this->getMeetTime()) ;
        
        return array('hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds) ;
    }

    /**
     * Set the team's score
     *
     * @param - int - team's score
     */
    function setTeamScore($score)
    {
        $this->__teamscore = $score ;
    }

    /**
     * Get the team's score
     *
     * @return - int - team's score
     */
    function getTeamScore()
    {
        return ($this->__teamscore) ;
    }

    /**
     * Set the opponent's score
     *
     * @param - int - opponent's score
     */
    function setOpponentScore($score)
    {
        $this->__opponentscore = $score ;
    }

    /**
     * Get the opponent's score
     *
     * @return - int - opponent's score
     */
    function getOpponentScore()
    {
        return ($this->__opponentscore) ;
    }

    /**
     *
     * Check if a meet already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of meet
     */
    function getSwimMeetExists()
    {
	    //  Is a similar meet already in the database?

        $query = sprintf('SELECT meetid FROM %s WHERE
            seasonid = "%s" AND
            opponentswimclubid = "%s" AND
            meetdate="%s"',
            WPST_SWIMMEETS_TABLE,
            $this->getSeasonId(),
            $this->getOpponentSwimClubId(),
            $this->getMeetDateAsDate()) ;

        //  Retain the query result so it can be used later if needed
 
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure meet doesn't exist

        $meetExists = (bool)($this->getQueryCount() > 0) ;

	    return $meetExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of meet
     */
    function getSwimMeetExistsByMeetId($meetid = null)
    {
        if (is_null($meetid)) $meetid = $this->getMeetId() ;

	    //  Is id already in the database?

        $query = sprintf('SELECT meetid FROM %s WHERE meetid = "%s"',
            WPST_SWIMMEETS_TABLE, $meetid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new swim meet
     */
    function addSwimMeet()
    {
        $success = null ;

        //  Make sure the meet doesn't exist yet

        if (!$this->getSwimMeetExists())
        {
            //  Construct the insert query
 
            $query = sprintf('INSERT INTO %s SET
                seasonid="%s",
                opponentswimclubid="%s",
                meettype="%s",
                participation="%s",
                meetstatus="%s",
                meetdescription="%s",
                location="%s",
                meetdate="%s",
                meettime="%s",
                teamscore="%s",
                opponentscore="%s"',
                WPST_SWIMMEETS_TABLE,
                $this->getSeasonId(),
                $this->getOpponentSwimClubId(),
                $this->getMeetType(),
                $this->getParticipation(),
                $this->getMeetStatus(),
                $this->getMeetDescription(),
                $this->getLocation(),
                $this->getMeetDateAsDate(),
                $this->getMeetTime(),
                $this->getTeamScore(),
                $this->getOpponentScore()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update a swim meet
     *
     */
    function updateSwimMeet()
    {
        $success = null ;

        //  Make sure the meet exists, can't update something that doesn't!

        if ($this->getSwimMeetExistsByMeetId())
        {
            //  Construct the update query
 
            $query = sprintf('UPDATE %s SET
                seasonid="%s",
                opponentswimclubid="%s",
                meettype="%s",
                participation="%s",
                meetstatus="%s",
                meetdescription="%s",
                location="%s",
                meetdate="%s",
                meettime="%s",
                teamscore="%s",
                opponentscore="%s"
                WHERE meetid="%s"',
                WPST_SWIMMEETS_TABLE,
                $this->getSeasonId(),
                $this->getOpponentSwimClubId(),
                $this->getMeetType(),
                $this->getParticipation(),
                $this->getMeetStatus(),
                $this->getMeetDescription(),
                $this->getLocation(),
                $this->getMeetDateAsDate(),
                $this->getMeetTime(),
                $this->getTeamScore(),
                $this->getOpponentScore(),
                $this->getMeetId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }
        else
        {
            wp_die('Unable to update meet record.') ;
        }

        return true ;
    }

    /**
     * Delete a swim meet
     *
     * Really need to think about this because deleting a meet
     * means deleting all of the results that go with it.  So if a
     * meet has results, disallow deleting the meet.
     *
     */
    function deleteSwimMeet()
    {
        $success = null ;

        //  Make sure the meet doesn't exist yet

        if ($this->getSwimMeetExistsByMeetId())
        {
            //  Construct the insert query
 
            $query = sprintf('DELETE FROM %s
                WHERE id="%s"',
                WPST_SWIMMEETS_TABLE,
                $this->getMeetId()
            ) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->getSwimMeetExistsByMeetId() ;

        return $success ;
    }

    /**
     *
     * Load meet record by Id
     *
     * @param - string - optional meet id
     */
    function loadSwimMeetByMeetId($meetid = null)
    {
        if (is_null($meetid)) $meetid = $this->getMeetId() ;

        //  Dud?
        if (is_null($meetid)) return false ;

        $this->setMeetId($meetid) ;

        //  Make sure it is a legal meet id
        if ($this->getSwimMeetExistsByMeetId())
        {
            $query = sprintf('SELECT * FROM %s WHERE meetid="%s"',
                WPST_SWIMMEETS_TABLE, $meetid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setMeetId($result['meetid']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setOpponentSwimClubId($result['opponentswimclubid']) ;
            $this->setMeetType($result['meettype']) ;
            $this->setParticipation($result['participation']) ;
            $this->setMeetStatus($result['meetstatus']) ;
            $this->setMeetDescription($result['meetdescription']) ;
            $this->setLocation($result['location']) ;
            $this->setMeetDate($result['meetdate']) ;
            $this->setMeetTime($result['meettime']) ;
            $this->setTeamScore($result['teamscore']) ;
            $this->setOpponentScore($result['opponentscore']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Retrieve all the Meet Ids for the seasons.
     * Meets can be filtered to return a subset of records
     *
     * @param - string - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getAllMeetIds($filter = null, $orderby = 'meetdate')
    {
        //  Select the records for the season

        $query = sprintf('SELECT meetid FROM %s', WPST_SWIMMEETS_TABLE) ;
        if (!is_null($filter) && ($filter != ''))
            $query .= sprintf(' WHERE %s', $filter) ;

        $query .= sprintf(' ORDER BY %s', $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }
}


/**
 * Extended GUIDataList Class for presenting SwimTeam
 * information extracted from the database.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamGUIDataList
 */
class SwimMeetsGUIDataList extends SwimTeamGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_DETAILS => WPST_ACTION_DETAILS
        //,WPST_ACTION_JOBS => WPST_ACTION_JOBS
        //,WPST_ACTION_OPT_IN => WPST_ACTION_OPT_IN
        //,WPST_ACTION_OPT_OUT => WPST_ACTION_OPT_OUT
        ,WPST_ACTION_RESULTS => WPST_ACTION_RESULTS
    ) ;

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
    function SwimMeetsGUIDataList($title, $width = '100%',
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_SWIMMEETS_DEFAULT_COLUMNS,
        $tables = WPST_SWIMMEETS_DEFAULT_TABLES,
        $where_clause = WPST_SWIMMEETS_DEFAULT_WHERE_CLAUSE)
    {
        //  Set the properties for this child class
        //$this->setColumns($columns) ;
        //$this->setTables($tables) ;
        //$this->setWhereClause($where_clause) ;

        //  Call the constructor of the parent class
        $this->SwimTeamGUIDataList($title, $width,
            $default_orderby, $default_reverseorder,
            $columns, $tables, $where_clause) ;

        //  These actions can't be part of the property
        //  declaration.

        //  These actions can't be part of the property
        //  declaration.

        if ((current_user_can('edit_posts') || get_option(WPST_OPTION_JOB_SIGN_UP) == WPST_USER))
        {
            $this->__normal_actions[WPST_ACTION_JOBS] = WPST_ACTION_JOBS ;
        }


        $optin = get_option(WPST_OPTION_OPT_IN_LABEL) ;
        $this->__normal_actions[WPST_ACTION_OPT_IN] = $optin ;

        $optout = get_option(WPST_OPTION_OPT_OUT_LABEL) ;
        $this->__normal_actions[WPST_ACTION_OPT_OUT] = $optout ;
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
		$this->add_header_item('Season',
	       	    '200', 'seasonid', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Opponent',
	       	    '300', 'opponentswimclubid', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Date',
	         	'150', 'location', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Location',
	         	'100', 'location', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Participation',
	         	'100', 'participation', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Status',
	         	'100', 'meetstatus', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Score',
	         	'125', 'teamscore', SORTABLE, SEARCHABLE, 'left') ;

        //  Construct the DB query
        $this->_datasource->setup_db_options($this->getColumns(),
            $this->getTables(), $this->getWhereClause()) ;

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', 'meetid') ;
        $this->set_radio_var_name('_swimmeetid', false) ;

        //  we have to be in POST mode, or we could run out
        //  of space in the http request with the saved
        //  checkbox items
        
        $this->set_form_method('POST') ;

        //  set the flag to save the checked items
        //  between pages.
        
        $this->save_checked_items(true) ;
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
            case 'Season' :
                $map = SwimTeamTextMap::__mapSeasonIdToText($row_data['seasonid']) ;
                $obj = $map['label'] ;
                break ;

            case 'Opponent' :
                if ($row_data['meettype'] == WPST_DUAL_MEET)
                    $obj = SwimTeamTextMap::__mapOpponentSwimClubIdToText($row_data['opponentswimclubid']) ;
                else
                    $obj = $row_data['meetdescription'] ;
                break ;

            case 'Date' :
                $obj = date('F d, Y', strtotime($row_data['meetdate'])) ;
                break ;

            case 'Location' :
                $obj = ucfirst($row_data['location']) ;
                break ;

            case 'Status' :
                $obj = ucfirst($row_data['meetstatus']) ;
                break ;

            case 'Participation' :
                if ($row_data['participation'] == WPST_OPT_IN)
                    $obj = ucwords(get_option(WPST_OPTION_OPT_IN_LABEL)) ;
                else if ($row_data['participation'] == WPST_OPT_OUT)
                    $obj = ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL)) ;
                else
                    $obj = ucwords($row_data['participation']) ;
                break ;

            case 'Score' :
                if ($row_data['meettype'] == WPST_DUAL_MEET)
                {
                    $ts = $row_data['teamscore'] ;
                    $os = $row_data['opponentscore'] ;

                    if  ($ts > $os)
                        $obj = sprintf('Win:  %s - %s', $ts, $os) ;
                    else if  ($ts < $os)
                        $obj = sprintf('Loss:  %s - %s', $ts, $os) ;
                    else if ((strtotime('now') > strtotime($row_data['meetdate']))
                        && ($ts == 0) && ($os == 0))
                        $obj = sprintf('Tie:  %s - %s', $ts, $os) ;
                    else if (($ts == 0) && ($os == 0))
                        $obj = 'No Result' ;
                    else
                        $obj = sprintf('Tie:  %s - %s', $ts, $os) ;
                }
                else
                {
                    $obj = 'N/A' ;
                }
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
 * on the various meets.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamMeetsGUIDataList
 */
class SwimMeetsAdminGUIDataList extends SwimMeetsGUIDataList
{
    /**
     * Property to store the requested action
     */
    var $__action ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_DETAILS => WPST_ACTION_DETAILS
        //,WPST_ACTION_RESULTS => WPST_ACTION_RESULTS
        //,WPST_ACTION_SCRATCH_REPORT => WPST_ACTION_SCRATCH_REPORT
        ,WPST_ACTION_ADD => WPST_ACTION_ADD
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        ,WPST_ACTION_DELETE => WPST_ACTION_DELETE
        ,WPST_ACTION_EVENTS_MANAGE => WPST_ACTION_EVENTS_MANAGE
        ,WPST_ACTION_EXPORT_ENTRIES => WPST_ACTION_EXPORT_ENTRIES
        ,WPST_ACTION_JOBS => WPST_ACTION_JOBS
        ,WPST_ACTION_JOB_REMINDERS => WPST_ACTION_JOB_REMINDERS
        //,WPST_ACTION_IMPORT_EVENTS => WPST_ACTION_IMPORT_EVENTS
        //,WPST_ACTION_IMPORT_RESULTS => WPST_ACTION_IMPORT_RESULTS
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
         WPST_ACTION_ADD => WPST_ACTION_ADD
    ) ;

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

	  	$this->add_header_item('Participation',
	         	'100', 'participation', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Id',
	       	    '50', 'meetid', SORTABLE, SEARCHABLE, 'left') ;

    }

}

/**
 * Class definition of a meet info table
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimMeetInfoTable extends SwimTeamInfoTable
{
    /**
     * Property to hold the meet id
     */
    var $__swimmeet_id ;

    /**
     * Set Swim Meet Id
     *
     * @param int - $id - Id of the swim meet
     */
    function setSwimMeetId($id)
    {
        $this->__swimmeet_id = $id ;
    }

    /**
     * Get Swim Meet Id
     *
     * @return int - Id of the swimmeet
     */
    function getSwimMeetId()
    {
        return $this->__swimmeet_id ;
    }

    /**
     * Construct a summary of the active season.
     *
     */
    function constructSwimMeetInfoTable($swimmeetid = null)
    {
        //  Alternate the row colors
        //$this->set_alt_color_flag(true) ;
        //$this->set_column_header($hdr++, 'Date', null, 'left') ;
        //$this->set_column_header($hdr++, 'Opponent', null, 'left') ;
        //$this->set_column_header($hdr++, 'Location', null, 'left') ;
        //$this->set_column_header($hdr++, 'Result', null, 'left') ;

        $meet = new SwimMeet() ;

        if (is_null($swimmeetid)) $swimmeetid = $this->getSwimMeetId() ;

        if (!is_null($swimmeetid))
        {
            $meet->loadSwimMeetByMeetId($swimmeetid) ;
    
            if ($meet->getMeetType() == WPST_DUAL_MEET)
                $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                    $meet->getOpponentSwimClubId()) ;
            else
                $opponent = $meet->getMeetDescription() ;

            $meetdate = date('D M j, Y', strtotime($meet->getMeetDateAsDate())) ;

            $this->add_row(html_b('Date'), $meetdate) ;
            $this->add_row(html_b('Opponent'), $opponent) ;
            $this->add_row(html_b('Location'), ucfirst($meet->getLocation())) ;


            if ($meet->getParticipation() == WPST_OPT_IN)
                $this->add_row(html_b('Participation'), ucwords(get_option(WPST_OPTION_OPT_IN_LABEL))) ;
            else if ($meet->getParticipation() == WPST_OPT_OUT)
                $this->add_row(html_b('Participation'), ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL))) ;
            else
                $this->add_row(html_b('Participation'), ucwords(WPST_CLOSED)) ;

            $this->add_row(html_b('Status'), ucfirst($meet->getMeetStatus())) ;
        }
        else
        {
            $this->add_row('No swim meet details available.') ;
        }
    }
}

/**
 * Class definition of the meet schedule info table
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimMeetScheduleInfoTable extends SwimTeamInfoTable
{
    /**
     * Construct a summary of the active season.
     *
     */
    function constructSwimMeetScheduleInfoTable($seasonid = null, $showstarttime = WPST_NO, $fmt = null)
    {
        $hdr = 0 ;

        //  Alternate the row colors
        $this->set_alt_color_flag(true) ;
        $this->set_column_header($hdr++, 'Date', null, 'left') ;

        if ($showstarttime == WPST_YES)
            $this->set_column_header($hdr++, 'Time', null, 'left') ;

        $this->set_column_header($hdr++, 'Opponent', null, 'left') ;
        $this->set_column_header($hdr++, 'Location', null, 'left') ;
        $this->set_column_header($hdr++, 'Result', null, 'left') ;

        $season = new SwimTeamSeason() ;

        //  Season Id supplied?  If not, use the active season.

        if ($seasonid == null)
            $seasonid = $season->getActiveSeasonId() ;

        //  Find all of the meets in the season

        $meet = new SwimMeet() ;
        $meetIds = $meet->getAllMeetIds(sprintf('seasonid="%s"', $seasonid)) ;

        //  Handle case where no meets have been scheduled yet

        if (is_null($meetIds))
        {
            $td = html_td() ;
            $td->set_tag_attribute('colspan', '4') ;
            $td->set_style('border-top: 1px solid #979797;') ;
            $td->add('No meets scheduled for active season.') ;
            $this->add_row($td) ;
        }
        else
        {
            foreach ($meetIds as $meetId)
            {
                $meet->loadSwimMeetByMeetId($meetId['meetid']) ;
    
                if ($meet->getMeetType() == WPST_DUAL_MEET)
                    $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                        $meet->getOpponentSwimClubId()) ;
                else
                    $opponent = $meet->getMeetDescription() ;
    
                if (empty($fmt))
                    $fmt = get_option(WPST_OPTION_TIME_FORMAT) ;

                $starttime = date(($fmt !== false) ? $fmt : WPST_DEFAULT_TIME_FORMAT,
                    strtotime($meet->getMeetTime())) ;

                //  Determine results - if the score is 0-0 after the
                //  meet date then it is deemed a tie instead of a TBD.
    
                if ($meet->getMeetType() == WPST_DUAL_MEET)
                {
                    $ts = $meet->getTeamScore() ;
                    $os = $meet->getOpponentScore() ;
    
                    if  ($ts > $os)
                        $winloss = sprintf('Win:  %s - %s', $ts, $os) ;
                    else if  ($ts < $os)
                        $winloss = sprintf('Loss:  %s - %s', $ts, $os) ;
                    else if ((strtotime('now') > strtotime($meet->getMeetDateAsDate()))
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
    
                $meetdate = date('D M j, Y', strtotime($meet->getMeetDateAsDate())) ;
                if ($showstarttime == WPST_YES)
                    $this->add_row($meetdate, $starttime, $opponent,
                        ucfirst($meet->getLocation()), $winloss) ;
                else
                    $this->add_row($meetdate, $opponent,
                        ucfirst($meet->getLocation()), $winloss) ;
            }
        }
    }
}

/**
 * Class definition of the swim meet meta data
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimMeetMeta extends SwimTeamDBI
{
    /**
     * smetaid property - used for unique database identifier
     */
    var $__smeta_id ;

    /**
     * user id property - unique id of user responsible for change
     */
    var $__user_id ;

    /**
     * swimmer id property - unique id of swimmer affected by meta data
     */
    var $__swimmer_id ;

    /**
     * swim meet id property - unique id of swim meet affected by meta data
     */
    var $__swimmeet_id ;

    /**
     * stroke code property - unique id of stroke affected by meta data
     */
    var $__stroke_code ;

    /**
     * event id property - unique id of event affected by meta data
     */
    var $__event_id ;

    /**
     * participation property - participation state
     */
    var $__participation ;

    /**
     * meta key property - meta key
     */
    var $__smetakey ;

    /**
     * meta value property - meta value
     */
    var $__smetavalue ;

    /**
     * meta modified property - meta modified
     */
    var $__smetamodified ;

    /**
     * option meta record
     */
    var $__smeta_record ;

    /**
     * Set Swim Meet Meta Id
     *
     * @param int - $id - Id of the meta option
     */
    function setSwimMeetMetaId($id)
    {
        $this->__smeta_id = $id ;
    }

    /**
     * Get Swim Meet Meta Id
     *
     * @return int - Id of the meta option
     */
    function getSwimMeetMetaId()
    {
        return $this->__smeta_id ;
    }

    /**
     * Set Swim Meet User Id
     *
     * @param int - $id - Id of the user option
     */
    function setUserId($id)
    {
        $this->__user_id = $id ;
    }

    /**
     * Get Swim Meet User Id
     *
     * @return int - Id of the user option
     */
    function getUserId()
    {
        return $this->__user_id ;
    }

    /**
     * Set Swim Meet Swimmer Id
     *
     * @param int - $id - Id of the swimmer option
     */
    function setSwimmerId($id)
    {
        $this->__swimmer_id = $id ;
    }

    /**
     * Get Swim Meet Swimmer Id
     *
     * @return int - Id of the swimmer option
     */
    function getSwimmerId()
    {
        return $this->__swimmer_id ;
    }

    /**
     * Set Swim Meet Id
     *
     * @param int - $id - Id of the swim meet
     */
    function setSwimMeetId($id)
    {
        $this->__swimmeet_id = $id ;
    }

    /**
     * Get Swim Meet Id
     *
     * @return int - Id of the swimmeet
     */
    function getSwimMeetId()
    {
        return $this->__swimmeet_id ;
    }

    /**
     * Set Swim Meet Event Id
     *
     * @param int - $id - Id of the event
     */
    function setEventId($id)
    {
        $this->__event_id = $id ;
    }

    /**
     * Get Swim Meet Event Id
     *
     * @return int - Id of the event
     */
    function getEventId()
    {
        return $this->__event_id ;
    }

    /**
     * Set Swim Meet Stroke Code
     *
     * @param int - $code - Code of the stroke
     */
    function setStrokeCode($code)
    {
        $this->__stroke_code = $code ;
    }

    /**
     * Get Swim Meet Stroke Code
     *
     * @return int - Code of the stroke
     */
    function getStrokeCode()
    {
        return $this->__stroke_code ;
    }

    /**
     * Set Participation
     *
     * @param int - $participation - participation
     */
    function setParticipation($participation)
    {
        $this->__participation = $participation ;
    }

    /**
     * Get Participation
     *
     * @return int - participation
     */
    function getParticipation()
    {
        return $this->__participation ;
    }

    /**
     * Set Swim Meet Meta Key
     *
     * @param int - $key - Key of the meta option
     */
    function setSwimMeetMetaKey($key)
    {
        $this->__smetakey = $key ;
    }

    /**
     * Get Swim Meet Meta Key
     *
     * @return int - Key of the meta option
     */
    function getSwimMeetMetaKey()
    {
        return $this->__smetakey ;
    }

    /**
     * Set Swim Meet Meta Value
     *
     * @param int - $value - Value of the meta option
     */
    function setSwimMeetMetaValue($value)
    {
        $this->__smetavalue = $value ;
    }

    /**
     * Get Swim Meet Meta Value
     *
     * @return int - Value of the meta option
     */
    function getSwimMeetMetaValue()
    {
        return $this->__smetavalue ;
    }

    /**
     * Set Swim Meet Meta Modified
     *
     * @param int - $modified - Modified of the meta option
     */
    function setSwimMeetMetaModified($modified)
    {
        $this->__smetamodified = $modified ;
    }

    /**
     * Get Swim Meet Meta Modified
     *
     * @return int - Modified of the meta option
     */
    function getSwimMeetMetaModified()
    {
        return $this->__smetamodified ;
    }

    /**
     * Load Swim Meet Meta
     *
     * @param - string - $query - SQL query string
     */
    function loadSwimMeetMeta($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        // Make sure only one result is returned ...

        if ($this->getQueryCount() == 1)
        {
            $this->__smeta_record = $this->getQueryResult() ;

            //  Short cut to save typing ... 

            $sm = &$this->__smeta_record ;

            $this->setSwimMeetMetaId($sm['smetaid']) ;
            $this->setUserId($sm['userid']) ;
            $this->setSwimmerId($sm['swimmerid']) ;
            $this->setSwimMeetId($sm['swimmeetid']) ;
            $this->setStrokeCode($sm['strokecode']) ;
            $this->setEventId($sm['eventid']) ;
            $this->setParticipation($sm['participation']) ;
            $this->setSwimMeetMetaKey($sm['smetakey']) ;
            $this->setSwimMeetMetaValue($sm['smetavalue']) ;
            $this->setSwimMeetMetaModified($sm['modified']) ;
        }
        else
        {
            $this->setSwimMeetMetaId(null) ;
            $this->setUserId(null) ;
            $this->setSwimmerId(null) ;
            $this->setSwimMeetId(null) ;
            $this->setStrokeCode(null) ;
            $this->setEventId(null) ;
            $this->setParticipation(null) ;
            $this->setSwimMeetMetaKey(null) ;
            $this->setSwimMeetMetaValue(null) ;
            $this->setSwimMeetMetaModified(null) ;
            $this->__smeta_record = null ;
        }

        return ($this->getQueryCount() == 1) ;
    }

    /**
     * Load Swim Meet Meta by Meta Id
     *
     * @param - int - $id - option meta id
     */
    function loadSwimMeetMetaByOMetaId($smetaid = null)
    {
        if (is_null($smetaid)) $smetaid = $this->getSwimMeetMetaId() ;

        if (is_null($smetaid))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Id')) ;
        $query = sprintf('SELECT * FROM %s WHERE smetaid="%s"',
            WPST_SWIMMEETS_META_TABLE, $smetaid) ;

        return $this->loadSwimMeetMeta($query) ;
    }

    /**
     * Load Swim Meet Meta by User Id and Key
     *
     * @param - int - $userid - user id
     * @param - string - $key - option meta key
     */
    function loadSwimMeetMetaByUserIdAndKey($userid, $key)
    {
        $query = sprintf('SELECT * FROM %s WHERE userid="%s" AND smetakey="%s"',
            WPST_SWIMMEETS_META_TABLE, $userid, $key) ;

        return $this->loadSwimMeetMeta($query) ;
    }

    /**
     * Load Swim Meet Meta by Swimmer Id and Key
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $key - option meta key
     */
    function loadSwimMeetMetaBySwimmerIdAndKey($swimmerid, $key)
    {
        $query = sprintf('SELECT * FROM %s WHERE swimmerid="%s" AND smetakey="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $key) ;

        return $this->loadSwimMeetMeta($query) ;
    }

    /**
     * get Swimmer Ids by Meet Id and Participation
     *
     * @param - int - $meetid - meet id
     * @param - string - $participation - participation value
     */
    function getSwimmerIdsByMeetIdAndParticipation($meetid, $participation, $orderby = null)
    {
        if ($orderby == WPST_SORT_BY_NAME)
        {
            $query = sprintf('SELECT DISTINCT %s.swimmerid as
                swimmerid FROM %s, %s WHERE %s.swimmeetid="%s" AND
                %s.participation="%s" AND %s.swimmerid = %s.id
                ORDER BY %s.lastname, %s.firstname',
                WPST_SWIMMEETS_META_TABLE,
                WPST_SWIMMEETS_META_TABLE,
                WPST_SWIMMERS_TABLE,
                WPST_SWIMMEETS_META_TABLE,
                $meetid,
                WPST_SWIMMEETS_META_TABLE,
                $participation,
                WPST_SWIMMEETS_META_TABLE,
                WPST_SWIMMERS_TABLE,
                WPST_SWIMMERS_TABLE,
                WPST_SWIMMERS_TABLE
            ) ;
        }
        else if ($orderby == WPST_SORT_BY_SWIMMER_LABEL)
        {
            $season = new SwimTeamSeason() ;

            $query = sprintf('SELECT DISTINCT %s.swimmerid as
                swimmerid FROM %s, %s WHERE %s.swimmeetid="%s" AND
                %s.participation="%s" AND %s.swimmerid = %s.swimmerid
                AND %s.seasonid="%s" ORDER BY %s.swimmerlabel',
                WPST_SWIMMEETS_META_TABLE,
                WPST_SWIMMEETS_META_TABLE,
                WPST_ROSTER_TABLE,
                WPST_SWIMMEETS_META_TABLE,
                $meetid,
                WPST_SWIMMEETS_META_TABLE,
                $participation,
                WPST_SWIMMEETS_META_TABLE,
                WPST_ROSTER_TABLE,
                WPST_ROSTER_TABLE,
                $season->getActiveSeasonId(),
                WPST_ROSTER_TABLE
            ) ;
        }
        else
        {
            $query = sprintf('SELECT DISTINCT swimmerid FROM %s WHERE
                swimmeetid="%s" AND participation="%s"',
                WPST_SWIMMEETS_META_TABLE, $meetid, $participation) ;
        }

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * get Stroke Codes by Meet Id and Participation
     *
     * @param - int - $meetid - meet id
     * @param - string - $participation - participation value
     */
    function getStrokeCodesBySwimmerIdsAndMeetIdAndParticipation($swimmerid, $meetid, $participation)
    {
        $query = sprintf('SELECT strokecode FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND participation="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $meetid, $participation) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * get Event Ids by Meet Id and Participation
     *
     * @param - int - $meetid - meet id
     * @param - string - $participation - participation value
     */
    function getEventIdsBySwimmerIdsAndMeetIdAndParticipation($swimmerid, $meetid, $participation)
    {
        $query = sprintf('SELECT eventid FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND participation="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $meetid, $participation) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * get Time Stamp by Meet Id, Swimmer Id, and Event Code
     *
     * @param - int - $meetid - meet id
     * @param - int - $swimmerid - swimmer id
     * @param - int - $strokecode - event code
     */
    function getMetaModifiedByMeetIdSwimmerIdAndStrokeCode($meetid, $swimmerid, $strokecode)
    {
        $query = sprintf('SELECT modified FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND strokecode="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $meetid, $strokecode) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResult() ;
    }

    /**
     * get Entered By by Meet Id, Swimmer Id, and Event Code
     *
     * @param - int - $meetid - meet id
     * @param - int - $swimmerid - swimmer id
     * @param - int - $strokecode - event code
     */
    function getMetaEnteredByMeetIdSwimmerIdAndStrokeCode($meetid, $swimmerid, $strokecode)
    {
        $query = sprintf('SELECT userid FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND strokecode="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $meetid, $strokecode) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResult() ;
    }

    /**
     * get Time Stamp by Meet Id, Swimmer Id, and Event Id
     *
     * @param - int - $meetid - meet id
     * @param - int - $swimmerid - swimmer id
     * @param - int - $event id - event id
     */
    function getMetaModifiedByMeetIdSwimmerIdAndEventId($meetid, $swimmerid, $eventid)
    {
        $query = sprintf('SELECT modified FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND eventid="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $meetid, $eventid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResult() ;
    }

    /**
     * get Entered By by Meet Id, Swimmer Id, and Event Id
     *
     * @param - int - $meetid - meet id
     * @param - int - $swimmerid - swimmer id
     * @param - int - $event id - event id
     */
    function getMetaEnteredByMeetIdSwimmerIdAndEventId($meetid, $swimmerid, $eventid)
    {
        $query = sprintf('SELECT userid FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND eventid="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $meetid, $eventid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResult() ;
    }

    /**
     * check if a record already exists
     * by unique id in the user profile table
     *
     * @param - string - $query - SQL query string
     * @return boolean - true if it exists, false otherwise
     */
    function existSwimMeetMeta($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * check if a record already exists
     * by unique id in the user profile table
     *
     * @param - string - $query - SQL query string
     * @param - string - $query - SQL query string
     * @return boolean - true if it exists, false otherwise
     */
    function existSwimMeetMetaByUserIdAndKey($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * Exist Swim Meet Meta by User Id and Key
     *
     * @param - int - $userid - user id
     * @param - string - $key - option meta key
     */
    function existSwimMeetMetaByUserIdAndKey2($userid, $key)
    {
        $query = sprintf('SELECT smetaid FROM %s
            WHERE userid="%s" AND smetakey="%s"',
            WPST_SWIMMEETS_META_TABLE, $userid, $key) ;

        return $this->existSwimMeetMeta($query) ;
    }

    /**
     * Exist Swim Meet Meta by Swimmer Id and Key
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $key - option meta key
     */
    function existSwimMeetMetaBySwimmerIdAndKey($swimmerid, $key)
    {
        $query = sprintf('SELECT smetaid FROM %s
            WHERE swimmerid="%s" AND smetakey="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $key) ;

        return $this->existSwimMeetMeta($query) ;
    }

    /**
     * Exist Swim Meet Meta by Swim Meet Id and Swimmer Id
     *
     * @param - int - $swimmeetid - swim meet id
     * @param - int - $swimmerid - swimmer id
     */
    function existSwimMeetMetaBySwimMeetIdAndSwimmerId($swimmeetid, $swimmerid)
    {
        $query = sprintf('SELECT smetaid FROM %s
            WHERE swimmeetid="%s" AND swimmerid="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmeetid, $swimmerid) ;

        return $this->existSwimMeetMeta($query) ;
    }

    /**
     * Exist Swim Meet Meta by Swim Meet Id and Swimmer Id and Event Code
     *
     * @param - int - $swimmeetid - swim meet id
     * @param - int - $swimmerid - swimmer id
     * @param - int - $strokecode - event code
     */
    function existSwimMeetMetaBySwimMeetIdAndSwimmerIdAndStrokeCode($swimmeetid,
        $swimmerid, $strokecode)
    {
        $query = sprintf('SELECT smetaid FROM %s
            WHERE swimmeetid="%s" AND swimmerid="%s" AND strokecode="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmeetid, $swimmerid, $strokecode) ;

        return $this->existSwimMeetMeta($query) ;
    }

    /**
     * save a user option meta record
     *
     * @return - integer - insert id
     */
    function saveUserSwimMeetMeta()
    {
        $success = false ;

        if (is_null($this->getUserId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Id')) ;
        if (is_null($this->getSwimMeetMetaKey()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Key')) ;
        //  Update or new save?
 
        $update = $this->existSwimMeetMetaByUserIdAndKey($this->getUserId(), $this->getSwimMeetMetaKey()) ;

        if ($update)
            $query = sprintf('UPDATE %s ', WPST_SWIMMEETS_META_TABLE) ;
        else
            $query = sprintf('INSERT INTO %s ', WPST_SWIMMEETS_META_TABLE) ;

        $query .= sprintf('SET 
            userid="%s",
            swimmerid="%s",
            swimmeetid="%s",
            strokecode="%s",
            eventid="%s",
            participation="%s",
            smetakey="%s",
            smetavalue="%s"',
            $this->getUserId(),
            $this->getSwimmerId(),
            $this->getSwimMeetId(),
            $this->getStrokeCode(),
            $this->getEventId(),
            $this->getParticipation(),
            $this->getSwimMeetMetaKey(),
            $this->getSwimMeetMetaValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($update)
        {
            $query .= sprintf(' WHERE userid="%s" AND smetakey="%s"',
                $this->getUserId(), $this->getSwimMeetMetaKey()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->setSwimMeetMetaId($this->getInsertId()) ;
        }

        return $success ;
    }

    /**
     * save a swimmer option meta record
     *
     * @return - integer - insert id
     */
    function saveSwimmerSwimMeetMeta()
    {
        $success = false ;

        if (is_null($this->getSwimmerId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swimmer Id')) ;
        if (is_null($this->getSwimMeetId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swim Meet Id')) ;
        //  Update or new save?
 
        $update = $this->existSwimMeetMetaBySwimMeetIdAndSwimmerIdAndStrokeCode(
            $this->getSwimMeetId(), $this->getSwimmerId(), $this->getStrokeCode()) ;

        if ($update)
            $query = sprintf('UPDATE %s ', WPST_SWIMMEETS_META_TABLE) ;
        else
            $query = sprintf('INSERT INTO %s ', WPST_SWIMMEETS_META_TABLE) ;

        $query .= sprintf('SET 
            userid="%s",
            swimmerid="%s",
            swimmeetid="%s",
            strokecode="%s",
            eventid="%s",
            participation="%s",
            smetakey="%s",
            smetavalue="%s"',
            $this->getUserId(),
            $this->getSwimmerId(),
            $this->getSwimMeetId(),
            $this->getStrokeCode(),
            $this->getEventId(),
            $this->getParticipation(),
            $this->getSwimMeetMetaKey(),
            $this->getSwimMeetMetaValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($update)
        {
            $query .= sprintf(' WHERE swimmeetid="%s" AND swimmerid="%s" AND strokecode="%s"',
                $this->getSwimMeetId(), $this->getSwimmerId(), $this->getStrokeCode()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $this->setSwimMeetMetaId($this->getInsertId()) ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * delete swimmer option meta records for a swim meet
     *
     * @return - integer - affected rows
     */
    function deleteSwimmerSwimMeetMeta()
    {
        if (is_null($this->getSwimmerId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swimmer Id')) ;
        if (is_null($this->getSwimMeetId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swim Meet Id')) ;
        //  Update or new save?
 
        $query = sprintf('DELETE FROM %s ', WPST_SWIMMEETS_META_TABLE) ;

        $query .= sprintf('WHERE 
            swimmerid="%s" AND 
            swimmeetid="%s" AND 
            smetakey="%s" AND 
            smetavalue="%s"',
            $this->getSwimmerId(),
            $this->getSwimMeetId(),
            $this->getSwimMeetMetaKey(),
            $this->getSwimMeetMetaValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        $this->setQuery($query) ;

        $status = $this->runDeleteQuery() ;

        return ($status) ;
    }

    /**
     * Delete Swim Meet Meta data based on a query string
     *
     * @param - string - $query - SQL query string
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMeta($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $status = $this->runDeleteQuery() ;

        return ($status) ;
    }

    /**
     * Delete Swim Meet Meta by User Id - deletes
     * all Swim Meet Meta data associated with a User Id.
     *
     * @param - int - $userid - user id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMetaByUserId($userid)
    {
        $query = sprintf('DELETE FROM %s WHERE userid="%s"',
            WPST_SWIMMEETS_META_TABLE, $userid) ;

        return $this->deleteSwimMeetMeta($query) ;
    }

    /**
     * Delete Swim Meet Meta by User Id and Key
     *
     * @param - int - $userid - user id
     * @param - string - $key - option meta key
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMetaByUserIdAndKey($userid, $key)
    {
        $query = sprintf('DELETE FROM %s
            WHERE userid="%s" AND smetakey="%s"',
            WPST_SWIMMEETS_META_TABLE, $userid, $key) ;

        return $this->deleteSwimMeetMeta($query) ;
    }

    /**
     * Delete Swim Meet Meta by Swimmer Id - deletes
     * all Swim Meet Meta data associated with a Swimmer Id.
     *
     * @param - int - $swimmerid - swimmer id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMetaBySwimmerId($swimmerid)
    {
        $query = sprintf('DELETE FROM %s WHERE swimmerid="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid) ;

        return $this->deleteSwimMeetMeta($query) ;
    }

    /**
     * Delete Swim Meet Meta by Swimmer Id and Key
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $key - option meta key
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMetaBySwimmerIdAndKey($swimmerid, $key)
    {
        $query = sprintf('DELETE FROM %s
            WHERE swimmerid="%s" AND smetakey="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmerid, $key) ;

        return $this->deleteSwimMeetMeta($query) ;
    }

    /**
     * Delete Meet Meta by Swim Meet Id and Swimmer Id - deletes
     * all Swim Meet Meta data associated with a Swim Meet for a
     * Swimmer Id.
     *
     * @param - int - $swimmeetid - swim meet id
     * @param - int - $swimmerid - swimmer id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMetaBySwimMeetIdAndSwimmerId($swimmeetid, $swimmerid)
    {
        $query = sprintf('DELETE FROM %s WHERE swimmeetid="%s" AND swimmerid="%s"',
            WPST_SWIMMEETS_META_TABLE, $swimmeetid, $swimmerid) ;

        return $this->deleteSwimMeetMeta($query) ;
    }

    /**
     * Delete Swim Meet Meta by Event Id - deletes
     * all Swim Meet Meta data associated with a Swimmer Id.
     *
     * @param - int - $eventid - event id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetMetaByEventId($eventid)
    {
        $query = sprintf('DELETE FROM %s WHERE eventid="%s"',
            WPST_SWIMMEETS_META_TABLE, $eventid) ;

        return $this->deleteSwimMeetMeta($query) ;
    }

    /**
     * Send Confirmation E-mail
     *
     * Send an e-mail to the user confirming the action
     * taken (register or unregister) for the swimmer to
     * the user performing the action and the address(es)
     * set up to receive registration e-mail.
     *
     * @param string $action - action to take, register or unregister
     */
    function sendConfirmationEmail($action, $actionmsgs, $mode = WPST_HTML)
    {
        global $userdata ;
        get_currentuserinfo() ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->loadSwimmerById($this->getSwimmerId()) ;

        $c1data = get_userdata($swimmer->getContact1Id()) ;
        $c1email = $c1data->user_email ;

        if ($swimmer->getContact2Id() != WPST_NULL_ID)
        {
            $c2data = get_userdata($swimmer->getContact2Id()) ;
            $c2email = $c1data->user_email ;
        }
        else
        {
            $c2data = null ;
            $c2email = WPST_NULL_STRING ;
        }

        // To send HTML mail, the Content-type header must be set

        if ($mode == WPST_HTML)
        {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        }
        else
        {
            $headers = '' ;
        }

        // Additional headers
        //if (is_null($c2data))
        //    $headers .= sprintf('To: %s %s <%s>', $c1data->user_firstname,
        //        $c1data->user_lastname, $c1data->user_email) . "\r\n" ;
        //else
        //    $headers .= sprintf('To: %s %s <%s>, %s %s<%s>',
        //        $c1data->user_firstname, $c1data->user_lastname, $c1data->user_email,
        //        $c2data->user_firstname, $c2data->user_lastname, $c2data->user_email) . "\r\n" ;

        $headers .= sprintf('From: %s <%s>',
            get_bloginfo('name'), get_bloginfo('admin_email')) . "\r\n" ;

        $headers .= sprintf('Cc: %s', get_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_ADDRESS)) . "\r\n" ;
        $headers .= sprintf('Bcc: %s', get_bloginfo('admin_email')) . "\r\n" ;
        $headers .= sprintf('Reply-To: %s', get_bloginfo('admin_email')) . "\r\n" ;
        $headers .= sprintf('X-Mailer: PHP/%s', phpversion()) ;

        if ($mode == WPST_HTML)
        {
            $htmlhdr = '
                <html>
                <head>
                <title>%s</title>
                </head>
                <body>
                <p>
                %s -
                </p>
                <p>
                A meet %s request has been received for %s.
                </p>
                <ul>
                ' ;

            $htmlftr = '
                </ul>
                <p>
                Thank you,<br/><br/>
                %s
                </p>
                <p>
                Visit <a href="%s">%s</a> for all your swim team news.
                </p>
                </body>
                </html>
                ' ;

            $htmlbody = '' ;

            foreach ($actionmsgs as $actionmsg)
                $htmlbody .= sprintf('<li>%s</li>', $actionmsg) ;

            $message = sprintf($htmlhdr,
                get_bloginfo('url'),
                $c1data->user_firstname,
                $action,
                $swimmer->getFirstName() . ' ' . $swimmer->getLastName()) ;

            $message .= $htmlbody ;

            $message .= sprintf($htmlftr,

                get_bloginfo('name'),
                get_bloginfo('url'),
                get_bloginfo('url')) ;
        }
        else
        {
            $plain = "%s -\r\n\r\n" ;
            $plain .= "A meet %s request has been received for %s.\r\n\r\n" ;

            //  Add each action message to the e-mail body
  
            foreach ($actionmsgs as $actionmsg)
                $plain .= strip_tags($actionmsg) . "\r\n" ;

            $plain .= "\r\n\r\nThank you,\r\n\r\n" ;
            $plain .= "%s\r\n\r\n" ;
            $plain .= "Visit %s for all your swim team news." ;

            $message = sprintf($plain,
                $c1data->user_firstname,
                $action,
                $swimmer->getFirstName() . ' ' . $swimmer->getLastName(),
                //$action,
                get_bloginfo('name'),
                get_bloginfo('url'),
                get_bloginfo('url')) ;
        }

        //$to = sprintf('%s %s <%s>', $c1data->user_firstname,
        //    $c1data->user_lastname, $c1data->user_email) ;
        if (is_null($c2data))
            $to = sprintf('%s %s <%s>', $c1data->user_firstname,
                $c1data->user_lastname, $c1data->user_email) ;
        else
            $to = sprintf('%s %s <%s>, %s %s<%s>',
                $c1data->user_firstname, $c1data->user_lastname, $c1data->user_email,
                $c2data->user_firstname, $c2data->user_lastname, $c2data->user_email) ;

        $subject = sprintf('Swimmer %s for %s',
            $action, $swimmer->getFirstName() . ' ' . $swimmer->getLastName()) ;

        $status = wp_mail($to, $subject, $message, $headers) ;

        return $status ;
    }
}

/**
 * Class definition of the swim meet results data
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimMeetResults extends SwimTeamDBI
{
    /**
     * results id property - used for unique database identifier
     */
    var $__results_id ;

    /**
     * swimmer id property - unique id of swimmer affected by results data
     */
    var $__swimmer_id ;

    /**
     * swim meet id property - unique id of swim meet affected by results data
     */
    var $__swimmeet_id ;

    /**
     * event id property - unique id of event affected by results data
     */
    var $__event_id ;

    /**
     * swim time property - swim time value
     */
    var $__swim_time ;

    /**
     * Set Swim Meet Results Id
     *
     * @param int - $id - Id of the results option
     */
    function setSwimMeetResultsId($id)
    {
        $this->__results_id = $id ;
    }

    /**
     * Get Swim Meet Results Id
     *
     * @return int - Id of the results option
     */
    function getSwimMeetResultsId()
    {
        return $this->__results_id ;
    }

    /**
     * Set Swim Meet Event Id
     *
     * @param int - $id - Id of the event option
     */
    function setEventId($id)
    {
        $this->__event_id = $id ;
    }

    /**
     * Get Swim Meet Event Id
     *
     * @return int - Id of the event option
     */
    function getEventId()
    {
        return $this->__event_id ;
    }

    /**
     * Set Swim Meet Swimmer Id
     *
     * @param int - $id - Id of the swimmer option
     */
    function setSwimmerId($id)
    {
        $this->__swimmer_id = $id ;
    }

    /**
     * Get Swim Meet Swimmer Id
     *
     * @return int - Id of the swimmer option
     */
    function getSwimmerId()
    {
        return $this->__swimmer_id ;
    }

    /**
     * Set Swim Meet Id
     *
     * @param int - $id - Id of the swim meet
     */
    function setSwimMeetId($id)
    {
        $this->__swimmeet_id = $id ;
    }

    /**
     * Get Swim Meet Id
     *
     * @return int - Id of the swimmeet
     */
    function getSwimMeetId()
    {
        return $this->__swimmeet_id ;
    }

    /**
     * Load Swim Meet Results
     *
     * @param - string - $query - SQL query string
     */
    function loadSwimMeetResults($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        // Make sure only one result is returned ...

        if ($this->getQueryCount() == 1)
        {
            $this->__results_record = $this->getQueryResult() ;

            //  Short cut to save typing ... 

            $sm = &$this->__results_record ;

            $this->setSwimMeetResultsId($sm['resultsid']) ;
            $this->setResultsId($sm['resultsid']) ;
            $this->setSwimmerId($sm['swimmerid']) ;
            $this->setSwimMeetId($sm['swimmeetid']) ;
            $this->setEventId($sm['eventid']) ;
            $this->setSwimTime($sm['swimtime']) ;
            $this->setModified($sm['modified']) ;
        }
        else
        {
            $this->setSwimMeetResultsId(null) ;
            $this->setResultsId(null) ;
            $this->setSwimmerId(null) ;
            $this->setSwimMeetId(null) ;
            $this->setEventId(null) ;
            $this->setSwimTime(null) ;
            $this->setModified(null) ;
            $this->__results_record = null ;
        }

        return ($this->getQueryCount() == 1) ;
    }

    /**
     * Load Swim Meet Results by Results Id
     *
     * @param - int - $id - option results id
     */
    function loadSwimMeetResultsByResultsId($resultsid = null)
    {
        if (is_null($resultsid)) $resultsid = $this->getSwimMeetResultsId() ;

        if (is_null($resultsid))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Id')) ;
        $query = sprintf('SELECT * FROM %s WHERE resultsid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $resultsid) ;

        return $this->loadSwimMeetResults($query) ;
    }

    /**
     * Load Swim Meet Results by Event Id and SwimmerId
     *
     * @param - int - $eventid - event id
     * @param - string - $swimmerid - option results swimmerid
     */
    function loadSwimMeetResultsByEventIdAndSwimmerId($eventid, $swimmerid)
    {
        $query = sprintf('SELECT * FROM %s WHERE eventid="%s" AND swimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $eventid, $swimmerid) ;

        return $this->loadSwimMeetResults($query) ;
    }

    /**
     * Load Swim Meet Results by Swimmer Id and SwimmerId
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $swimmerid - option results swimmerid
     */
    function loadSwimMeetResultsBySwimmerIdAndSwimmerId($swimmerid, $swimmerid)
    {
        $query = sprintf('SELECT * FROM %s WHERE swimmerid="%s" AND resultsswimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmerid, $swimmerid) ;

        return $this->loadSwimMeetResults($query) ;
    }

    /**
     * get Swimmer Ids by Meet Id and Participation
     *
     * @param - int - $meetid - meet id
     * @param - string - $participation - participation value
     */
    function getSwimmerIdsByMeetIdAndParticipation($meetid, $participation, $orderby = null)
    {
        if ($orderby == WPST_SORT_BY_NAME)
        {
            $query = sprintf('SELECT DISTINCT %s.swimmerid as
                swimmerid FROM %s, %s WHERE %s.swimmeetid="%s" AND
                %s.participation="%s" AND %s.swimmerid = %s.id
                ORDER BY %s.lastname, %s.firstname',
                WPST_SWIMMEETS_RESULTS_TABLE,
                WPST_SWIMMEETS_RESULTS_TABLE,
                WPST_SWIMMERS_TABLE,
                WPST_SWIMMEETS_RESULTS_TABLE,
                $meetid,
                WPST_SWIMMEETS_RESULTS_TABLE,
                $participation,
                WPST_SWIMMEETS_RESULTS_TABLE,
                WPST_SWIMMERS_TABLE,
                WPST_SWIMMERS_TABLE,
                WPST_SWIMMERS_TABLE
            ) ;
        }
        else if ($orderby == WPST_SORT_BY_SWIMMER_LABEL)
        {
            $season = new SwimTeamSeason() ;

            $query = sprintf('SELECT DISTINCT %s.swimmerid as
                swimmerid FROM %s, %s WHERE %s.swimmeetid="%s" AND
                %s.participation="%s" AND %s.swimmerid = %s.swimmerid
                AND %s.seasonid="%s" ORDER BY %s.swimmerlabel',
                WPST_SWIMMEETS_RESULTS_TABLE,
                WPST_SWIMMEETS_RESULTS_TABLE,
                WPST_ROSTER_TABLE,
                WPST_SWIMMEETS_RESULTS_TABLE,
                $meetid,
                WPST_SWIMMEETS_RESULTS_TABLE,
                $participation,
                WPST_SWIMMEETS_RESULTS_TABLE,
                WPST_ROSTER_TABLE,
                WPST_ROSTER_TABLE,
                $season->getActiveSeasonId(),
                WPST_ROSTER_TABLE
            ) ;
        }
        else
        {
            $query = sprintf('SELECT DISTINCT swimmerid FROM %s WHERE
                swimmeetid="%s" AND participation="%s"',
                WPST_SWIMMEETS_RESULTS_TABLE, $meetid, $participation) ;
        }

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * get Swimmer Ids by Meet Id and Participation
     *
     * @param - int - $meetid - meet id
     * @param - string - $participation - participation value
     */
    function getStrokeCodesBySwimmerIdsAndMeetIdAndParticipation($swimmerid, $meetid, $participation)
    {
        $query = sprintf('SELECT strokecode FROM %s WHERE
            swimmerid="%s" AND swimmeetid="%s" AND participation="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmerid, $meetid, $participation) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * check if a record already exists
     * by unique id in the event profile table
     *
     * @param - string - $query - SQL query string
     * @return boolean - true if it exists, false otherwise
     */
    function existSwimMeetResults($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * check if a record already exists
     * by unique id in the event profile table
     *
     * @param - string - $query - SQL query string
     * @param - string - $query - SQL query string
     * @return boolean - true if it exists, false otherwise
     */
    function existSwimMeetResultsByEventIdAndSwimmerId($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * Exist Swim Meet Results by Event Id and SwimmerId
     *
     * @param - int - $eventid - event id
     * @param - string - $swimmerid - option results swimmerid
     */
    function existSwimMeetResultsByEventIdAndSwimmerId2($eventid, $swimmerid)
    {
        $query = sprintf('SELECT resultsid FROM %s
            WHERE eventid="%s" AND resultsswimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $eventid, $swimmerid) ;

        return $this->existSwimMeetResults($query) ;
    }

    /**
     * Exist Swim Meet Results by Swimmer Id and SwimmerId
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $swimmerid - option results swimmerid
     */
    function existSwimMeetResultsBySwimmerIdAndSwimmerId($swimmerid, $swimmerid)
    {
        $query = sprintf('SELECT resultsid FROM %s
            WHERE swimmerid="%s" AND resultsswimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmerid, $swimmerid) ;

        return $this->existSwimMeetResults($query) ;
    }

    /**
     * Exist Swim Meet Results by Swim Meet Id and Swimmer Id
     *
     * @param - int - $swimmeetid - swim meet id
     * @param - int - $swimmerid - swimmer id
     */
    function existSwimMeetResultsBySwimMeetIdAndSwimmerId($swimmeetid, $swimmerid)
    {
        $query = sprintf('SELECT resultsid FROM %s
            WHERE swimmeetid="%s" AND swimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmeetid, $swimmerid) ;

        return $this->existSwimMeetResults($query) ;
    }

    /**
     * Exist Swim Meet Results by Swim Meet Id and Swimmer Id and Event Code
     *
     * @param - int - $swimmeetid - swim meet id
     * @param - int - $swimmerid - swimmer id
     * @param - int - $strokecode - event code
     */
    function existSwimMeetResultsBySwimMeetIdAndSwimmerIdAndStrokeCode($swimmeetid,
        $swimmerid, $strokecode)
    {
        $query = sprintf('SELECT resultsid FROM %s
            WHERE swimmeetid="%s" AND swimmerid="%s" AND strokecode="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmeetid, $swimmerid, $strokecode) ;

        return $this->existSwimMeetResults($query) ;
    }

    /**
     * save a event option results record
     *
     * @return - integer - insert id
     */
    function saveEventSwimMeetResults()
    {
        $success = false ;

        if (is_null($this->getEventId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Id')) ;
        if (is_null($this->getSwimMeetResultsSwimmerId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null SwimmerId')) ;
        //  Update or new save?
 
        $update = $this->existSwimMeetResultsByEventIdAndSwimmerId($this->getEventId(), $this->getSwimMeetResultsSwimmerId()) ;

        if ($update)
            $query = sprintf('UPDATE %s ', WPST_SWIMMEETS_RESULTS_TABLE) ;
        else
            $query = sprintf('INSERT INTO %s ', WPST_SWIMMEETS_RESULTS_TABLE) ;

        $query .= sprintf('SET 
            eventid="%s",
            swimmerid="%s",
            swimmeetid="%s",
            strokecode="%s",
            eventid="%s",
            participation="%s",
            resultsswimmerid="%s",
            resultsvalue="%s"',
            $this->getEventId(),
            $this->getSwimmerId(),
            $this->getSwimMeetId(),
            $this->getStrokeCode(),
            $this->getEventId(),
            $this->getParticipation(),
            $this->getSwimMeetResultsSwimmerId(),
            $this->getSwimMeetResultsValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($update)
        {
            $query .= sprintf(' WHERE eventid="%s" AND resultsswimmerid="%s"',
                $this->getEventId(), $this->getSwimMeetResultsSwimmerId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->setSwimMeetResultsId($this->getInsertId()) ;
        }

        return $success ;
    }

    /**
     * save a swimmer option results record
     *
     * @return - integer - insert id
     */
    function saveSwimmerSwimMeetResults()
    {
        $success = false ;

        if (is_null($this->getSwimmerId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swimmer Id')) ;
        if (is_null($this->getSwimMeetId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swim Meet Id')) ;
        //  Update or new save?
 
        $update = $this->existSwimMeetResultsBySwimMeetIdAndSwimmerIdAndStrokeCode(
            $this->getSwimMeetId(), $this->getSwimmerId(), $this->getStrokeCode()) ;

        if ($update)
            $query = sprintf('UPDATE %s ', WPST_SWIMMEETS_RESULTS_TABLE) ;
        else
            $query = sprintf('INSERT INTO %s ', WPST_SWIMMEETS_RESULTS_TABLE) ;

        $query .= sprintf('SET 
            eventid="%s",
            swimmerid="%s",
            swimmeetid="%s",
            strokecode="%s",
            eventid="%s",
            participation="%s",
            resultsswimmerid="%s",
            resultsvalue="%s"',
            $this->getEventId(),
            $this->getSwimmerId(),
            $this->getSwimMeetId(),
            $this->getStrokeCode(),
            $this->getEventId(),
            $this->getParticipation(),
            $this->getSwimMeetResultsSwimmerId(),
            $this->getSwimMeetResultsValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($update)
        {
            $query .= sprintf(' WHERE swimmeetid="%s" AND swimmerid="%s" AND strokecode="%s"',
                $this->getSwimMeetId(), $this->getSwimmerId(), $this->getStrokeCode()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $this->setSwimMeetResultsId($this->getInsertId()) ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * delete swimmer option results records for a swim meet
     *
     * @return - integer - affected rows
     */
    function deleteSwimmerSwimMeetResults()
    {
        if (is_null($this->getSwimmerId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swimmer Id')) ;
        if (is_null($this->getSwimMeetId()))
			wp_die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Swim Meet Id')) ;
        //  Update or new save?
 
        $query = sprintf('DELETE FROM %s ', WPST_SWIMMEETS_RESULTS_TABLE) ;

        $query .= sprintf('WHERE 
            swimmerid="%s" AND 
            swimmeetid="%s" AND 
            resultsswimmerid="%s" AND 
            resultsvalue="%s"',
            $this->getSwimmerId(),
            $this->getSwimMeetId(),
            $this->getSwimMeetResultsSwimmerId(),
            $this->getSwimMeetResultsValue()) ;

        //  Query is processed differently for INSERT and UPDATE

        $this->setQuery($query) ;

        $status = $this->runDeleteQuery() ;

        return ($status) ;
    }

    /**
     * Delete Swim Meet Results data based on a query string
     *
     * @param - string - $query - SQL query string
     * @return - int - number of affected rows
     */
    function deleteSwimMeetResults($query = null)
    {
        if (is_null($query))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Query')) ;
        $this->setQuery($query) ;
        $status = $this->runDeleteQuery() ;

        return ($status) ;
    }

    /**
     * Delete Swim Meet Results by Event Id - deletes
     * all Swim Meet Results data associated with a Event Id.
     *
     * @param - int - $eventid - event id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetResultsByEventId($eventid)
    {
        $query = sprintf('DELETE FROM %s WHERE eventid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $eventid) ;

        return $this->deleteSwimMeetResults($query) ;
    }

    /**
     * Delete Swim Meet Results by Event Id and SwimmerId
     *
     * @param - int - $eventid - event id
     * @param - string - $swimmerid - option results swimmerid
     * @return - int - number of affected rows
     */
    function deleteSwimMeetResultsByEventIdAndSwimmerId($eventid, $swimmerid)
    {
        $query = sprintf('DELETE FROM %s
            WHERE eventid="%s" AND resultsswimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $eventid, $swimmerid) ;

        return $this->deleteSwimMeetResults($query) ;
    }

    /**
     * Delete Swim Meet Results by Swimmer Id - deletes
     * all Swim Meet Results data associated with a Swimmer Id.
     *
     * @param - int - $swimmerid - swimmer id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetResultsBySwimmerId($swimmerid)
    {
        $query = sprintf('DELETE FROM %s WHERE swimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmerid) ;

        return $this->deleteSwimMeetResults($query) ;
    }

    /**
     * Delete Swim Meet Results by Swimmer Id and SwimmerId
     *
     * @param - int - $swimmerid - swimmer id
     * @param - string - $swimmerid - option results swimmerid
     * @return - int - number of affected rows
     */
    function deleteSwimMeetResultsBySwimmerIdAndSwimmerId($swimmerid, $swimmerid)
    {
        $query = sprintf('DELETE FROM %s
            WHERE swimmerid="%s" AND resultsswimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmerid, $swimmerid) ;

        return $this->deleteSwimMeetResults($query) ;
    }

    /**
     * Delete Meet Results by Swim Meet Id and Swimmer Id - deletes
     * all Swim Meet Results data associated with a Swim Meet for a
     * Swimmer Id.
     *
     * @param - int - $swimmeetid - swim meet id
     * @param - int - $swimmerid - swimmer id
     * @return - int - number of affected rows
     */
    function deleteSwimMeetResultsBySwimMeetIdAndSwimmerId($swimmeetid, $swimmerid)
    {
        $query = sprintf('DELETE FROM %s WHERE swimmeetid="%s" AND swimmerid="%s"',
            WPST_SWIMMEETS_RESULTS_TABLE, $swimmeetid, $swimmerid) ;

        return $this->deleteSwimMeetResults($query) ;
    }
}

/**
 * Class definition of the swim meet results data
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamResults
 */
class SwimMeetResultsImport extends SwimMeetResults
{
    //var $__
}
?>
