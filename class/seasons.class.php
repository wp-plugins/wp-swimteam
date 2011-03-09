<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Season classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Season
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */


/**
 * IMPORTANT:  There can only be ONE season active at a time.
 *
 * The architecture of the SwimTeam plugin is based on a single
 * season being active.  When a new season is created, all other
 * seasons are tagged as "inactive" and all swimmers are tagged
 * "inactive" until they register for the "active" season.
 *
 */

require_once("db.class.php") ;
require_once("swimteam.include.php") ;
require_once("seasons.include.php") ;
require_once("widgets.class.php") ;
require_once("table.class.php") ;

/**
 * Class definition of the seasons
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamSeason extends SwimTeamDBI
{
    /**
     * id property - used for unique database identifier
     */
    var $__id ;

    /**
     * seasonLabel property - a description of the season
     */
    var $__seasonLabel ;

    /**
     * seasonStart property - the start date of the season
     */
    var $__seasonStart ;

    /**
     * seasonEnd property - the end data of the season
     */
    var $__seasonEnd ;

    /**
     * status property - status of the season
     */
    var $__status ;

    /**
     * swimmer id status property - status of the swimmer labels 
     */
    var $__swimmer_labels ;

    /**
     * Set the season id
     *
     * @param - int - id of the season
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the season id
     *
     * @return - int - id of the season
     */
    function getId()
    {
        return ($this->__id) ;
    }

    /**
     * Set the seasonLabel of the season
     *
     * @param - string - description of the season
     */
    function setSeasonLabel($seasonLabel)
    {
        $this->__seasonLabel = $seasonLabel ;
    }

    /**
     * Get the seasonLabel of the season
     *
     * @return - string - description of the season
     */
    function getSeasonLabel()
    {
        return ($this->__seasonLabel) ;
    }

    /**
     * Set the seasonStart of the season
     *
     * @param - array - start of season date
     */
    function setSeasonStart($seasonStart)
    {
        $this->__seasonStart = $seasonStart ;
    }

    /**
     * Get the start of the season as an array
     *
     * @return - array - start of season date as an array
     */
    function getSeasonStart()
    {
        return ($this->__seasonStart) ;
    }

    /**
     * Get the start of the season
     *
     * @return - array - start of season date as an array
     */
    function getSeasonStartAsArray()
    {
        list($year, $month, $day) = explode('-', $this->__seasonStart) ;
        return array("day" => $day, "month" => $month, "year" => $year) ;
    }

    /**
     * Get the start of the season as a formatted date
     *
     * @return - string - start of season date as a string
     */
    function getSeasonStartAsDate()
    {
        $d = &$this->__seasonStart ;

        return sprintf("%04s-%02s-%02s", $d["year"], $d["month"], $d["day"]) ;
    }

    /**
     * Set the seasonEnd of the season
     *
     * @param - array - end of season date
     */
    function setSeasonEnd($seasonEnd)
    {
        $this->__seasonEnd = $seasonEnd ;
    }

    /**
     * Get the End of the season
     *
     * @return - array - end of season date as an array
     */
    function getSeasonEnd()
    {
        return ($this->__seasonEnd) ;
    }

    /**
     * Get the end of the season as an array
     *
     * @return - array - end of season date as an array
     */
    function getSeasonEndAsArray()
    {
        list($year, $month, $day) = explode('-', $this->__seasonEnd) ;
        return array("day" => $day, "month" => $month, "year" => $year) ;
    }

    /**
     * Get the end of the season as a formatted date
     *
     * @return - string - end of season date as a string
     */
    function getSeasonEndAsDate()
    {
        $d = &$this->__seasonEnd ;

        return sprintf("%04s-%02s-%02s", $d["year"], $d["month"], $d["day"]) ;
    }

    /**
     * Set the status of the season
     *
     * @param - string - status of the season
     */
    function setSeasonStatus($status)
    {
        $this->__status = $status ;
    }

    /**
     * Get the status of the season
     *
     * @return - string - status of the season
     */
    function getSeasonStatus()
    {
        return ($this->__status) ;
    }

    /**
     * Set the status of the swimmer labels
     *
     * @param - string - status of the swimmer labels
     */
    function setSwimmerLabels($status)
    {
        $this->__swimmerlabels = $status ;
    }

    /**
     * Get the status of the swimmer labels
     *
     * @return - string - status of the swimmer labels
     */
    function getSwimmerLabels()
    {
        return ($this->__swimmerlabels) ;
    }

    /**
     *
     * Check if a season already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of season
     */
    function seasonExist()
    {
	    //  Is a similar season already in the database?

        $query = sprintf("SELECT id FROM %s WHERE season_label = \"%s\"
            AND season_start = \"%s\" AND season_end=\"%s\"",
            WPST_SEASONS_TABLE, $this->getSeasonLabel(),
            $this->getSeasonStartAsDate(), $this->getSeasonEndAsDate()) ;

        //  Retain the query result so it can be used later if needed
 
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure season doesn't exist

        $seasonExists = (bool)($this->getQueryCount() > 0) ;

	    return $seasonExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of season
     */
    function seasonExistById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT id FROM %s WHERE id = \"%s\"",
            WPST_SEASONS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new season
     */
    function addSeason()
    {
        $success = null ;

        //  Make sure the season doesn't exist yet

        if (!$this->seasonExist())
        {
            //  Construct the insert query
 
            $query = sprintf("INSERT INTO %s SET
                season_label=\"%s\",
                season_start=\"%s\",
                season_end=\"%s\",
                season_status=\"%s\",
                swimmer_labels=\"%s\"",
                WPST_SEASONS_TABLE,
                $this->getSeasonLabel(),
                $this->getSeasonStartAsDate(),
                $this->getSeasonEndAsDate(),
                $this->getSeasonStatus(),
                $this->getSwimmerLabels()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update an season
     *
     * Update the label, start date, and/or end date but
     * don't update the status, that is done by explicity
     * opening or closing a season.
     */
    function updateSeason()
    {
        $success = null ;

        //  Make sure the season doesn't exist yet

        if ($this->seasonExistById())
        {
            //  Construct the insert query
 
            $query = sprintf("UPDATE %s SET
                season_label=\"%s\",
                season_start=\"%s\",
                season_end=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                $this->getSeasonLabel(),
                $this->getSeasonStartAsDate(),
                $this->getSeasonEndAsDate(),
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }
        else
            wp_die("wp-SwimTeam Error:  Severe error, action aborted.") ;

        //  Open or Close season

        switch ($this->getSeasonStatus())
        {
            case WPST_SEASONS_SEASON_ACTIVE:
                $this->openSeason() ;
                break ;

            case WPST_SEASONS_SEASON_INACTIVE:
                $this->closeSeason() ;
                break ;

            default:
                break ;
        }

        //  Lock or Unlock Swimmer Labels

        switch ($this->getSwimmerLabels())
        {
            case WPST_LOCKED:
                $this->lockSwimmerLabels() ;
                break ;

            case WPST_UNLOCKED:
                $this->unlockSwimmerLabels() ;
                break ;

            case WPST_FROZEN:
                $this->freezeSwimmerLabels() ;
                break ;

            default:
                break ;
        }

        return true ;
    }

    /**
     * Hide a season
     */
    function hideSeason()
    {
        $success = null ;

        //  Make sure the season doesn't exist yet

        if (!$this->seasonExist())
        {
            //  Construct the insert query
 
            $query = sprintf("UPDATE %s SET
                status=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_SEASONS_SEASON_HIDDEN,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }

        return true ;
    }

    /**
     * Open a season
     */
    function openSeason()
    {
        $success = null ;

        //  Make sure the season exists

        if ($this->seasonExistById())
        {
            //  Construct the update query - make all seasons
            //  inactive before making the specified season active.
 
            $query = sprintf("UPDATE %s SET
                season_status=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_SEASONS_SEASON_INACTIVE
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            $query = sprintf("UPDATE %s SET
                season_status=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_SEASONS_SEASON_ACTIVE,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            return true ;
        }
        else
            return false ;
    }

    /**
     * Close a season
     */
    function closeSeason()
    {
        $success = null ;

        //  Make sure the season exists

        if ($this->seasonExistById())
        {
            //  Construct the update query 
 
            $query = sprintf("UPDATE %s SET
                season_status=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_SEASONS_SEASON_INACTIVE,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            return true ;
        }
        else
            return false ;
    }

    /**
     * Delete a season
     *
     * Really need to think about this because deleting a season
     * means deleting all of the meets that go with it.  So if a
     * season has meets (which have results), disallow deleting
     * the season.  It can be "hidden" but can't be deleted.
     *
     */
    function deleteSeason()
    {
        $success = null ;

        //  Make sure the season doesn't exist yet

        if (!$this->seasonExist())
        {
            //  Construct the insert query
 
            $query = sprintf("DELETE FROM %s
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->seasonExistById() ;
        return $success ;
    }

    /**
     * Lock the swimmer labels
     *
     */
    function lockSwimmerLabels()
    {
        $success = null ;

        //  Make sure the season exists

        if ($this->seasonExistById())
        {
            $query = sprintf("UPDATE %s SET
                swimmer_labels=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_LOCKED,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            return true ;
        }
        else
            return false ;
    }

    /**
     * Unlock the swimmer labels
     */
    function unlockSwimmerLabels()
    {
        $success = null ;

        //  Make sure the season exists

        if ($this->seasonExistById())
        {
            $query = sprintf("UPDATE %s SET
                swimmer_labels=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_UNLOCKED,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            return true ;
        }
        else
            return false ;
    }

    /**
     * Lock the swimmer labels
     *
     */
    function freezeSwimmerLabels()
    {
        $success = null ;

        //  Make sure the season exists

        if ($this->seasonExistById())
        {
            $query = sprintf("UPDATE %s SET
                swimmer_labels=\"%s\"
                WHERE id=\"%s\"",
                WPST_SEASONS_TABLE,
                WPST_FROZEN,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            return true ;
        }
        else
            return false ;
    }

    /**
     *
     * Load season record by Id
     *
     * @param - string - optional season id
     */
    function loadSeasonById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setId($id) ;

        //  Make sure it is a legal season id
        if ($this->seasonExistById())
        {
            $query = sprintf("SELECT * FROM %s WHERE id = \"%s\"",
                WPST_SEASONS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setId($result['id']) ;
            $this->setSeasonLabel($result['season_label']) ;
            $this->setSeasonStart($result['season_start']) ;
            $this->setSeasonEnd($result['season_end']) ;
            $this->setSeasonStatus($result['season_status']) ;
            $this->setSwimmerLabels($result['swimmer_labels']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Get active season id
     *
     */

    function getActiveSeasonId()
    {
        $query = sprintf("SELECT id FROM %s WHERE season_status = \"%s\"",
            WPST_SEASONS_TABLE, WPST_SEASONS_SEASON_ACTIVE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure we only have one query result ...

        if ($this->getQueryCount() == 1)
            $id = $this->getQueryResult() ;
        else
            $id = array('id' => null) ;

	    return $id['id'] ;
    }

    /**
     *
     * Load active season record
     *
     * @param - string - optional season id
     */
    function loadActiveSeason()
    {
        $id = $this->getActiveSeasonId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setId($id) ;

        //  Make sure it is a legal season id
        if ($this->seasonExistById())
        {
            $query = sprintf("SELECT * FROM %s WHERE id = \"%s\"",
                WPST_SEASONS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setId($result['id']) ;
            $this->setSeasonLabel($result['season_label']) ;
            $this->setSeasonStart($result['season_start']) ;
            $this->setSeasonEnd($result['season_end']) ;
            $this->setSeasonStatus($result['season_status']) ;
            $this->setSwimmerLabels($result['swimmer_labels']) ;
        }

        return (bool)($this->getQueryCount() == 1) ;
    }

    /**
     * Retrieve all the Season Ids for the seasons.
     * Seasons can be filtered to return a subset of records
     *
     * @param - string - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getAllSeasonIds($filter = null, $orderby = "season_start")
    {
        //  Select the records for the season

        $query = sprintf("SELECT id AS seasonid FROM %s", WPST_SEASONS_TABLE) ;
        if (!is_null($filter) && ($filter != ""))
            $query .= sprintf(" WHERE %s", $filter) ;

        $query .= sprintf(" ORDER BY %s", $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
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
class SwimTeamSeasonsGUIDataList extends SwimTeamGUIDataList
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
    function SwimTeamSeasonsGUIDataList($title, $width = "100%",
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_SEASONS_DEFAULT_COLUMNS,
        $tables = WPST_SEASONS_DEFAULT_TABLES,
        $where_clause = WPST_SEASONS_DEFAULT_WHERE_CLAUSE)
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
		$this->add_header_item("Season",
	       	    "200", "season_label", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Starts",
	         	    "200", "season_start", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Ends",
	         	    "200", "season_end", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Status",
	         	    "200", "season_status", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Swimmer Labels",
	         	    "200", "swimmer_labels", SORTABLE, SEARCHABLE, "left") ;

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
            case "Updated" :
                $obj = strftime("%Y-%m-%d @ %T", (int)$row_data["updated"]) ;
                break ;
                */

            case "Starts" :
                $obj = date("F d, Y", strtotime($row_data["season_start"])) ;
                break ;

            case "Ends" :
                $obj = date("F d, Y", strtotime($row_data["season_end"])) ;
                break ;

            case "Status" :
                $obj = ucfirst($row_data["season_status"]) ;
                break ;

            case "Swimmer Labels" :
                $obj = ucfirst($row_data["swimmer_labels"]) ;
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
 * on the various seasons.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamSeasonsGUIDataList
 */
class SwimTeamSeasonsAdminGUIDataList extends SwimTeamSeasonsGUIDataList
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
        ,WPST_ACTION_JOBS => WPST_ACTION_JOBS
        //,WPST_ACTION_DEFINE_JOBS => WPST_ACTION_DEFINE_JOBS
        //,WPST_ACTION_ASSIGN_JOBS => WPST_ACTION_ASSIGN_JOBS
        //,WPST_ACTION_OPEN => WPST_ACTION_OPEN
        //,WPST_ACTION_CLOSE => WPST_ACTION_CLOSE
        //,WPST_ACTION_LOCK => WPST_ACTION_LOCK
        //,WPST_ACTION_UNLOCK => WPST_ACTION_UNLOCK
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
         "add" => WPST_ACTION_ADD
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

		$this->add_header_item("Id",
	       	    "50", "id", SORTABLE, SEARCHABLE, "left") ;

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', "id") ;

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

        $actions = array() ;

        foreach($this->__normal_actions as $key => $action)
        {
            $actions[$action] = $key ;
        }

        
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
    function actionbar_cell2()
    {
        //  Add an ActionBar button based on the action the page
        //  was called with.

        $c = container() ;

        foreach($this->__normal_actions as $key => $button)
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
?>
