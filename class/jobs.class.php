<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Job classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Jobs
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("db.class.php") ;
require_once("jobs.include.php") ;
require_once("users.class.php") ;
require_once("table.class.php") ;
require_once("widgets.class.php") ;

/**
 * Class definition of the jobs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamJob extends SwimTeamDBI
{
    /**
     * job id property - used for unique database identifier
     */
    var $__jobid ;

    /**
     * position property - the position of the job
     */
    var $__jobposition ;

    /**
     * description property - desription of the job
     */
    var $__jobdescription ;

    /**
     * duration property - duration of the job
     */
    var $__jobduration ;

    /**
     * type property - type of job (paid, unpaid, volunteer, etc.)
     */
    var $__jobtype ;

    /**
     * location property - location of job (home, away, both)
     */
    var $__joblocation ;

    /**
     * quantify property - number of times job needs to be filled
     */
    var $__jobquantity ;

    /**
     * volunteer units property - value to correlate one
     * position against another.
     */
    var $__jobcredits ;

    /**
     * status property - status of job (active, inactive)
     */
    var $__jobstatus ;

    /**
     * notes property - notes for the job
     */
    var $__jobnotes ;

    /**
     * Set the job id
     *
     * @param - int - id of the job
     */
    function setJobId($id)
    {
        $this->__jobid = $id ;
    }

    /**
     * Get the job id
     *
     * @return - int - id of the job
     */
    function getJobId()
    {
        return ($this->__jobid) ;
    }

    /**
     * Set the position of the job
     *
     * @param - string - position of the job
     */
    function setJobPosition($position)
    {
        $this->__jobposition = $position ;
    }

    /**
     * Get the position of the job
     *
     * @return - string - position of the job record
     */
    function getJobPosition()
    {
        return ($this->__jobposition) ;
    }

    /**
     * Set the description of the job
     *
     * @param - string - description of the job
     */
    function setJobDescription($description)
    {
        $this->__jobdescription = $description ;
    }

    /**
     * Get the description of the job
     *
     * @return - string - description of the job record
     */
    function getJobDescription()
    {
        return ($this->__jobdescription) ;
    }

    /**
     * Set the job duration (season, event, etc.)
     *
     * @param - int - duration of the job
     */
    function setJobDuration($duration)
    {
        $this->__jobduration = $duration ;
    }

    /**
     * Get the job duration
     *
     * @return - int - duration of the job
     */
    function getJobDuration()
    {
        return ($this->__jobduration) ;
    }

    /**
     * Set the job type
     *
     * @param - int - type of the job
     */
    function setJobType($type)
    {
        $this->__jobtype = $type ;
    }

    /**
     * Get the job type
     *
     * @return - int - type of the job
     */
    function getJobType()
    {
        return ($this->__jobtype) ;
    }

    /**
     * Set the job location
     *
     * @param - int - location of the job
     */
    function setJobLocation($location)
    {
        $this->__joblocation = $location ;
    }

    /**
     * Get the job location
     *
     * @return - int - location of the job
     */
    function getJobLocation()
    {
        return ($this->__joblocation) ;
    }

    /**
     * Set the job quantity
     *
     * @param - int - quantity of the job
     */
    function setJobQuantity($quantity)
    {
        $this->__jobquantity = $quantity ;
    }

    /**
     * Get the job quantity
     *
     * @return - int - quantity of the job
     */
    function getJobQuantity()
    {
        return ($this->__jobquantity) ;
    }

    /**
     * Set the job volunteer units
     *
     * @param - int - volunteer units for the job
     */
    function setJobCredits($units)
    {
        $this->__jobcredits = $units ;
    }

    /**
     * Get the job quantity
     *
     * @return - int - volunteer units for the job
     */
    function getJobCredits()
    {
        return ($this->__jobcredits) ;
    }

    /**
     * Set the job status
     *
     * @param - int - status of the job
     */
    function setJobStatus($status)
    {
        $this->__jobstatus = $status ;
    }

    /**
     * Get the job status
     *
     * @return - int - status of the job
     */
    function getJobStatus()
    {
        return ($this->__jobstatus) ;
    }

    /**
     * Set the job notes
     *
     * @param - string - notes for the job
     */
    function setJobNotes($notes)
    {
        $this->__jobnotes = $notes ;
    }

    /**
     * Get the job notes
     *
     * @return - string - notes for the job
     */
    function getJobNotes()
    {
        return ($this->__jobnotes) ;
    }

    /**
     *
     * Check if a position already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional job id
     * @return - boolean - existance of position
     */
    function jobExistByPosition($jobid = null)
    {
        if (is_null($jobid))
            $query = sprintf("SELECT jobposition FROM %s WHERE
                jobposition=\"%s\"", WPST_JOBS_TABLE, $this->getJobPosition()) ;
        else
            $query = sprintf("SELECT jobposition FROM %s WHERE
            jobid=\"%s\" AND jobposition=\"%s\"", WPST_JOBS_TABLE,
            $jobid, $this->getJobPosition()) ;

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
    function jobExistById($id = null)
    {
        if (is_null($id)) $id = $this->getJobId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT jobid FROM %s WHERE jobid = \"%s\"",
            WPST_JOBS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new job
     */
    function addJob()
    {
        $success = null ;

        //  Make sure the job doesn't exist yet

        if (!$this->jobExistByPosition())
        {
            //  Construct the insert query
 
            $query = sprintf("INSERT INTO %s SET
                jobposition=\"%s\",
                jobdescription=\"%s\",
                jobnotes=\"%s\",
                jobduration=\"%s\",
                jobtype=\"%s\",
                joblocation=\"%s\",
                jobcredits=\"%s\",
                jobstatus=\"%s\"",
                WPST_JOBS_TABLE,
                $this->getJobPosition(),
                $this->getJobDescription(),
                $this->getJobNotes(),
                $this->getJobDuration(),
                $this->getJobType(),
                $this->getJobLocation(),
                $this->getJobCredits(),
                $this->getJobStatus()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update a new job
     */
    function updateJob()
    {
        //  Make sure the job does exist

        if ($this->jobExistById())
        {
            //  Construct the insert query
 
            $query = sprintf("UPDATE %s SET
                jobposition=\"%s\",
                jobdescription=\"%s\",
                jobnotes=\"%s\",
                jobduration=\"%s\",
                jobtype=\"%s\",
                joblocation=\"%s\",
                jobcredits=\"%s\",
                jobstatus=\"%s\"
                WHERE jobid=\"%s\"",
                WPST_JOBS_TABLE,
                $this->getJobPosition(),
                $this->getJobDescription(),
                $this->getJobNotes(),
                $this->getJobDuration(),
                $this->getJobType(),
                $this->getJobLocation(),
                $this->getJobCredits(),
                $this->getJobStatus(),
                $this->getJobId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            wp_die("Unable to update job record.") ;
        }

        return $success ;
    }

    /**
     * delete job
     *
     * @return int - success, number of rows affected
     */
    function deleteJob()
    {
        //  Make sure the job does exist

        if ($this->jobExistById())
        {
            //  Need the full record before deleting it

            $this->loadJobByJobId() ;

            //  Before deleting the allocation record, need
            //  to delete all of the assignment records which
            //  are connected to it.

            $ja = new SwimTeamJobAllocation() ;

            $jaids = $ja->getAllJobAllocationIdsByJobId($this->getJobId()) ;

            if (empty($jaids)) $jaids = array() ;
 
            //  Remove any existing job allocations

            foreach ($jaids as $jaid)
            {
                $ja->setJobAllocationId($jaid["joballocationid"]) ;
                $ja->loadJobAllocationByJobAllocationId($jaid["joballocationid"]) ;
                $ja->deallocateJob() ;
            }

            //  Construct the delete query and update the allocation
 
            $query = sprintf("DELETE FROM %s WHERE jobid=\"%s\"",
                WPST_JOBS_TABLE, $this->getJobId()) ;

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
     * Load a job record by Id
     *
     * @param - string - optional position
     */
    function loadJobByJobId($id = null)
    {
        if (is_null($id)) $id = $this->getJobId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setJobId($id) ;

        //  Make sure it is a legal job id
        if ($this->jobExistById())
        {
            $query = sprintf("SELECT * FROM %s WHERE jobid = \"%s\"",
                WPST_JOBS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobId($result['jobid']) ;
            $this->setJobPosition($result['jobposition']) ;
            $this->setJobDescription($result['jobdescription']) ;
            $this->setJobNotes($result['jobnotes']) ;
            $this->setJobDuration($result['jobduration']) ;
            $this->setJobType($result['jobtype']) ;
            $this->setJobLocation($result['joblocation']) ;
            $this->setJobCredits($result['jobcredits']) ;
            $this->setJobStatus($result['jobstatus']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Retrieve all the Job Ids.
     *
     * @param - string - optional filter to restrict query
     * @param - string - optional order by to order query results
     * @return - array - array of assignment ids
     */
    function getAllJobIds($filter = null, $orderby = null)
    {
        //  Select the records for the season

        $query = sprintf("SELECT %s.jobid FROM %s",
                WPST_JOBS_TABLE, WPST_JOBS_TABLE) ;

        if (!is_null($filter) && ($filter != ""))
            $query .= sprintf(" WHERE %s", $filter) ;

        if (is_null($orderby) || ($orderby == ""))
            $orderby = sprintf("%s.%s", WPST_JOBS_TABLE, "jobposition") ;

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
class SwimTeamJobsGUIDataList extends SwimTeamGUIDataList
{
    /**
     * Property to store the requested action
     */
    var $__action ;

    /**
     * Property to store the possible actions - used to build buttons
     */
    var $__actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        //,WPST_ACTION_SIGN_UP => WPST_ACTION_SIGN_UP
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
    function SwimTeamJobsGUIDataList($title, $width = "100%",
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_JOBS_DEFAULT_COLUMNS,
        $tables = WPST_JOBS_DEFAULT_TABLES,
        $where_clause = WPST_JOBS_DEFAULT_WHERE_CLAUSE)
    {
        //  Set the properties for this child class
        //$this->setColumns($columns) ;
        //$this->setTables($tables) ;
        //$this->setWhereClause($where_clause) ;

        //  Call the constructor of the parent class
        $this->SwimTeamGUIDataList($title, $width,
            $default_orderby, $default_reverseorder,
            $columns, $tables, $where_clause) ;

        if ((current_user_can('edit_posts') || get_option(WPST_OPTION_JOB_SIGN_UP) == WPST_USER))
        {
            $this->__actions[WPST_ACTION_SIGN_UP] = WPST_ACTION_SIGN_UP ;
        }

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
		$this->add_header_item("Position",
	       	    "225", "jobposition", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Location",
	         	    "75", "joblocation", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Duration",
	         	    "100", "jobduration", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Credits",
	         	    "75", "jobcredits", SORTABLE, SEARCHABLE, "left") ;

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

	    $this->add_action_column('radio', 'FIRST', "jobid") ;

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
                /*
            case "Updated" :
                $obj = strftime("%Y-%m-%d @ %T", (int)$row_data["updated"]) ;
                break ;
                */

            case "Type" :
                $obj = ucwords($row_data["jobtype"]) ;
                break ;

            case "Location" :
                if ($row_data["joblocation"] == WPST_NA)
                    $obj = strtoupper($row_data["joblocation"]) ;
                else
                    $obj = ucwords($row_data["joblocation"]) ;
                break ;

            case "Duration" :
                $obj = ucwords($row_data["jobduration"]) ;
                break ;

            case "Status" :
                $obj = ucwords($row_data["jobstatus"]) ;
                break ;

            case "Credits" :
                if ($row_data["jobtype"] == WPST_JOB_TYPE_PAID)
                    $obj = __(strtoupper(WPST_NA)) ;
                else
                    $obj = $row_data["jobcredits"] ;
                break ;

		    default:
			    $obj = DefaultGUIDataList::build_column_item($row_data, $col_name);
			    break;
		}
		return $obj;
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

        foreach($this->__actions as $key => $button)
        {
            //$b = $this->action_button($button[0], $_SERVER['REQUEST_URI']) ;

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

/**
 * GUIDataList class for performaing administration tasks
 * on the various jobs.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamJobsGUIDataList
 */
class SwimTeamJobsAdminGUIDataList extends SwimTeamJobsGUIDataList
{
    /**
     * Property to store the possible actions - used to build buttons
     */
    var $__actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        ,WPST_ACTION_SIGN_UP => WPST_ACTION_SIGN_UP
        ,WPST_ACTION_ADD => WPST_ACTION_ADD
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        ,WPST_ACTION_DELETE => WPST_ACTION_DELETE
        ,WPST_ACTION_ALLOCATE => WPST_ACTION_ALLOCATE
        ,WPST_ACTION_REALLOCATE => WPST_ACTION_REALLOCATE
        ,WPST_ACTION_DEALLOCATE => WPST_ACTION_DEALLOCATE
        //,WPST_ACTION_DELETE => WPST_ACTION_DELETE
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
    function SwimTeamJobsAdminGUIDataList($title, $width = "100%",
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_JOBS_DEFAULT_COLUMNS,
        $tables = WPST_JOBS_DEFAULT_TABLES,
        $where_clause = WPST_JOBS_DEFAULT_WHERE_CLAUSE)
    {
        parent::SwimTeamJobsGUIDataList($title, $width, $default_orderby,
            $default_reverseorder, $columns, $tables, $where_clause) ;

        //  These actions can't be part of the property
        //  declaration.

        /*
        $this->__actions[WPST_ACTION_ALLOCATE_JOBS_SEASON] = WPST_ACTION_ALLOCATE . " (" . WPST_SEASON . ")" ;
        $this->__actions[WPST_ACTION_ALLOCATE_JOBS_SWIMMEET] = WPST_ACTION_ALLOCATE . " (" . WPST_SWIMMEET . ")" ;
        $this->__actions[WPST_ACTION_ASSIGN_JOBS_SEASON] = WPST_ACTION_ASSIGN . " (" . WPST_SEASON . ")" ;
        $this->__actions[WPST_ACTION_ASSIGN_JOBS_SWIMMEET] = WPST_ACTION_ASSIGN . " (" . WPST_SWIMMEET . ")" ;
         */
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
        parent::user_setup() ;

	  	$this->add_header_item("Status",
	         	    "100", "jobstatus", SORTABLE, SEARCHABLE, "left") ;
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

        foreach($this->__actions as $key => $action)
        {
            $actions[$action] = $key ;
        }
        
        $lb = $this->action_select("_action", $actions,
            "", false, array("style" => "width: 150px; margin-right: 10px;"),
            $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']) ;

        //$b = $this->action_button(WPST_ACTION_EXECUTE) ;
        //$b->set_tag_attribute("type", "submit") ;
        //$c->add($lb, _HTML_SPACE, $b) ;
        $c->add($lb) ;

        return $c ;
    }
}

/**
 * Extended InfoTable Class for presenting SwimTeam
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamJobProfileInfoTable extends SwimTeamInfoTable
{
    /**
     * id property, used to query job information
     */
    var $__jobid = null ;

    /**
     * Set the job id
     *
     * @param int - the id of the job profile to query
     */
    function setJobId($id)
    {
        $this->__jobid = $id ;
    }

    /**
     * Get the job id
     *
     * @return int - the id of the job profile to query
     */
    function getJobId()
    {
        return $this->__jobid ;
    }

    /**
     * Build the InfoTable
     *
     */
    function constructSwimTeamJobProfile($jobid = null)
    {
        if (is_null($jobid)) $jobid = $this->getJobId() ;

        if (is_null($jobid))
        {
            $this->add_row("No data.") ;
        }
        else
        {
            //$this->set_alt_color_flag(true) ;
            //$this->set_show_cellborders(true) ;

            $job = new SwimTeamJob() ;
            $job->loadJobByJobId($jobid) ;

            $this->add_row("Position", $job->getJobPosition()) ;
            $this->add_row("Description", $job->getJobDescription()) ;
            $this->add_row("Duration", ucwords($job->getJobDuration())) ;
            $this->add_row("Type", ucwords($job->getJobType())) ;
            $this->add_row("Location", ucwords($job->getJobLocation())) ;
            $this->add_row("Credits", $job->getJobCredits()) ;
            $this->add_row("Status", ucwords($job->getJobStatus())) ;
        }
    }
}

/**
 * Class definition of the jobs allocation
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamJobAllocation extends SwimTeamJob
{
    /**
     * job allocation id property - used for unique database identifier
     */
    var $__joballocationid ;

    /**
     * quantify property - number of times job needs to be filled
     */
    var $__jobquantity ;

    /**
     * season id property - record id of the season referenced
     */
    var $__seasonid ;

    /**
     * meet id property - record id of the meet referenced
     */
    var $__meetid ;

    /**
     * Set the job allocation id
     *
     * @param - int - id of the job allocation
     */
    function setJobAllocationId($id)
    {
        $this->__joballocationid = $id ;
    }

    /**
     * Get the job allocation id
     *
     * @return - int - id of the job allocation
     */
    function getJobAllocationId()
    {
        return ($this->__joballocationid) ;
    }

    /**
     * Set the job quantity
     *
     * @param - int - quantity of the job
     */
    function setJobQuantity($quantity)
    {
        $this->__jobquantity = $quantity ;
    }

    /**
     * Get the job quantity
     *
     * @return - int - quantity of the job
     */
    function getJobQuantity()
    {
        return ($this->__jobquantity) ;
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
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of position
     */
    function jobAllocationExistById($id = null)
    {
        if (is_null($id)) $id = $this->getJobAllocationId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT joballocationid FROM %s WHERE joballocationid=\"%s\"",
            WPST_JOB_ALLOCATIONS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of position
     */
    function jobAllocationExistByJobIdAndMeetId()
    {
	    //  Is id already in the database?

        $query = sprintf("SELECT joballocationid FROM %s WHERE jobid=\"%s\"
            AND meetid=\"%s\"", WPST_JOB_ALLOCATIONS_TABLE, $this->getJobId(),
            $this->getMeetId()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of position
     */
    function jobAllocationExistByJobIdSeasonIdAndMeetId()
    {
	    //  Is id already in the database?

        $query = sprintf("SELECT joballocationid FROM %s WHERE jobid=\"%s\"
            AND seasonid=\"%s\" AND meetid=\"%s\"", WPST_JOB_ALLOCATIONS_TABLE,
            $this->getJobId(), $this->getSeasonId(), $this->getMeetId()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new job allocation
     *
     */
    function allocateJob()
    {
        $success = null ;

        //  Make sure the job allocation doesn't exist yet

        if (!$this->jobAllocationExistByJobIdSeasonIdAndMeetId())
        {
            //  Construct the insert query
 
            $query = sprintf("INSERT INTO %s SET
                jobid=\"%s\",
                jobquantity=\"%s\",
                seasonid=\"%s\",
                meetid=\"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE,
                $this->getJobId(),
                $this->getJobQuantity(),
                $this->getSeasonId(),
                $this->getMeetId()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;

            //  Now that the job has been allocated, need to
            //  add inial entries to the Job Assignment table
            //  that are not assgined to a user.

            if ($success)
            {
                $ja = new SwimTeamJobAssignment($this) ;
                $ja->setJobAllocationId($success) ;

                for ($i = 0 ; $i < $this->getJobQuantity() ; $i++)
                {
                    $ja->assignJob(true) ;
                }
            }
        }

        return $success ;
    }

    /**
     * job reallocation
     *
     * @return int - success, number of rows affected
     */
    function reallocateJob()
    {
        //  Make sure the job does exist

        if ($this->jobAllocationExistByJobIdSeasonIdAndMeetId())
        {
            //  Construct the update query
 
            $query = sprintf("UPDATE %s
                SET jobquantity=\"%s\"
                WHERE jobid=\"%s\"
                AND seasonid=\"%s\"
                AND meetid=\"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE,
                $this->getJobQuantity(),
                $this->getJobId(),
                $this->getSeasonId(),
                $this->getMeetId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;

            //  If successful, assignment records need to
            //  adjusted accordingly - added or deleted depending
            //  on quantity change.

            if ($success)
            {
                $ja = new SwimTeamJobAssignment($this) ;
                $ja->loadJobAssignmentByJobIdSeasonIdAndMeetId() ;

                $jaids = $ja->getAllJobAssignmentIdsByJobAllocationId() ;

                $desired = $this->getJobQuantity() ;

                //  Need to add some?  Remove some?  Do nothing?

                if (count($jaids) < $desired)
                {
                    for ($i = 0 ; $i < $desired - count($jaids) ; $i++)
                    {
                        $ja->assignJob(true) ;
                    }
                }
                else if (count($jaids) > $desired)
                {
                    //  Reverse the order of assignment ids,
                    //  remove oldest to newest.

                    rsort($jaids) ;

                    $i = 0 ;

                    foreach ($jaids as $jaid)
                    {
                        if ($i++ < (count($jaids) - $desired))
                        {
                            $ja->setJobAssignmentId($jaid["jobassignmentid"]) ;
                            $ja->deleteJobAssignment() ;
                        }
                        else
                            continue ;
                    }
                }
                else
                {
                    //  No adjustments needed.
                }
            }
        }
        else
        {
            $success = false ;
        }

        return $success ;
    }

    /**
     * job deallocation
     *
     * @return int - success, number of rows affected
     */
    function deallocateJob()
    {
        //  Make sure the job does exist

        if ($this->jobAllocationExistByJobIdSeasonIdAndMeetId())
        {
            //  Need the full record before deleting it

            $this->loadJobAllocationByJobIdSeasonIdAndMeetId() ;

            //  Before deleting the allocation record, need
            //  to delete all of the assignment records which
            //  are connected to it.

            $ja = new SwimTeamJobAssignment($this) ;
            $ja->loadJobAssignmentByJobIdSeasonIdAndMeetId() ;

            $jaids = $ja->getAllJobAssignmentIdsByJobAllocationId() ;

            //  Remove any existing job assignments

            foreach ($jaids as $jaid)
            {
                $ja->setJobAssignmentId($jaid["jobassignmentid"]) ;
                $ja->deleteJobAssignment() ;
            }

            //  Construct the delete query and update the allocation
 
            $query = sprintf("DELETE FROM %s
                WHERE joballocationid=\"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE,
                $this->getJobAllocationId()) ;

            $this->setQuery($query) ;
            $success = $this->runDeleteQuery() ;
        }
        /*
        else if ($this->jobAllocationExistById())
        {
            die("here") ;
            //  Need the full record before deleting it

            $this->loadJobAllocationByJobAllocationId() ;

            //  Before deleting the allocation record, need
            //  to delete all of the assignment records which
            //  are connected to it.

            $ja = new SwimTeamJobAssignment($this) ;
            $ja->loadJobAssignmentByJobIdSeasonIdAndMeetId() ;

            $jaids = $ja->getAllJobAssignmentIdsByJobAllocationId() ;

            //  Remove any existing job assignments

            foreach ($jaids as $jaid)
            {
                $ja->setJobAssignmentId($jaid["jobassignmentid"]) ;
                $ja->deleteJobAssignment() ;
            }

            //  Construct the delete query and update the allocation
 
            $query = sprintf("DELETE FROM %s
                WHERE joballocationid=\"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE,
                $this->getJobAllocationId()) ;

            $this->setQuery($query) ;
            $success = $this->runDeleteQuery() ;
        }
         */
        else
        {
            $success = false ;
        }

        return $success ;
    }

    /**
     * Load a job record by Id
     *
     * @param - string - optional position
     */
    function loadJobAllocationByJobAllocationId($id = null)
    {
        if (is_null($id)) $id = $this->getJobAllocationId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setJobAllocationId($id) ;

        //  Make sure it is a legal job id
        if ($this->jobAllocationExistById())
        {
            $query = sprintf("SELECT * FROM %s WHERE joballocationid = \"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobAllocationId($result['joballocationid']) ;
            $this->setJobId($result['jobid']) ;
            $this->setJobQuantity($result['jobquantity']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setMeetId($result['meetid']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Load a job allocation record by job id and meet id
     *
     * @param - int - optional job id
     * @param - int - optional meet id
     */
    function loadJobAllocationByJobIdAndMeetId($jobid = null, $meetid = null)
    {
        if (is_null($jobid)) $jobid = $this->getJobId() ;
        if (is_null($meetid)) $meetid = $this->getMeetId() ;

        //  Dud?
        if (is_null($jobid) || is_null($meetid)) return false ;

        $this->setJobId($jobid) ;
        $this->setMeetId($meetid) ;

        //  Make sure it is a legal job id
        if ($this->jobAllocationExistByJobIdAndMeetId())
        {
            $query = sprintf("SELECT * FROM %s WHERE jobid=\"%s\" AND meetid=\"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE, $jobid, $meetid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobAllocationId($result['joballocationid']) ;
            $this->setJobId($result['jobid']) ;
            $this->setJobQuantity($result['jobquantity']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setMeetId($result['meetid']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Load a job allocation record by job id and meet id
     *
     * @param - int - optional job id
     * @param - int - optional season id
     * @param - int - optional meet id
     */
    function loadJobAllocationByJobIdSeasonIdAndMeetId($jobid = null, $seasonid = null, $meetid = null)
    {
        if (is_null($jobid)) $jobid = $this->getJobId() ;
        if (is_null($seasonid)) $seasonid = $this->getSeasonId() ;
        if (is_null($meetid)) $meetid = $this->getMeetId() ;

        //  Dud?
        if (is_null($jobid) || is_null($seasonid) || is_null($meetid)) return false ;

        $this->setJobId($jobid) ;
        $this->setSeasonId($seasonid) ;
        $this->setMeetId($meetid) ;

        //  Make sure it is a legal job id
        if ($this->jobAllocationExistByJobIdSeasonIdAndMeetId())
        {
            $query = sprintf("SELECT * FROM %s WHERE
                jobid=\"%s\" AND seasonid = \"%s\" AND meetid=\"%s\"",
                WPST_JOB_ALLOCATIONS_TABLE, $jobid, $seasonid, $meetid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobAllocationId($result['joballocationid']) ;
            $this->setJobId($result['jobid']) ;
            $this->setJobQuantity($result['jobquantity']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setMeetId($result['meetid']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Get Job Ids by Meet Id
     *
     * @param int - $id - swim meet id
     * @return mixed - array of job ids
     */
    function getJobIdsByMeetId($id = null)
    {
        if (is_null($id)) $id = $this->getMeetId() ;

        //  Select the records for the meet

        $query = sprintf("SELECT %s.jobid FROM %s, %s WHERE %s.meetid=\"%s\"
            AND %s.jobid = %s.jobid ORDER BY %s.jobposition",
            WPST_JOB_ALLOCATIONS_TABLE, WPST_JOBS_TABLE,
            WPST_JOB_ALLOCATIONS_TABLE, WPST_JOB_ALLOCATIONS_TABLE, $id,
            WPST_JOB_ALLOCATIONS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Job Allocation Ids.  Can optionally based
     * be filtered to only return a subset of the allocation ids.
     *
     * @param - string - optional filter to restrict query
     * @param - string - optional order by to order query results
     * @return - array - array of allocation ids
     */
    function getAllJobAllocationIds($filter = null, $orderby = null)
    {
        //  Select the records for the season

        $query = sprintf("SELECT %s.joballocationid FROM %s",
                WPST_JOB_ALLOCATIONS_TABLE, WPST_JOB_ALLOCATIONS_TABLE) ;

        if (!is_null($filter) && ($filter != ""))
            $query .= sprintf(" WHERE %s", $filter) ;

        if (is_null($orderby) || ($orderby == ""))
            $orderby = sprintf("%s.%s", WPST_JOB_ALLOCATIONS_TABLE, "joballocationid") ;

        $query .= sprintf(" ORDER BY %s", $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Job Allocation Ids.  Can optionally based
     * be filtered to only return a subset of the allocation ids.
     *
     * @param - string - optional filter to restrict query
     * @param - string - optional order by to order query results
     * @return - array - array of allocation ids
     */
    function getAllJobAllocationIdsByJobId($id = null)
    {
        if (is_null($id)) $id = $this->getJobId() ;

        if (is_null($id)) wp_die("Null Job Id") ;

        $filter = sprintf("%s.%s = \"%s\"",
            WPST_JOB_ALLOCATIONS_TABLE, "jobid", $id) ;

        return $this->getAllJobAllocationIds($filter) ;
    }
}

/**
 * Class definition of the jobs allocation
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamJobAssignment extends SwimTeamJobAllocation
{
    /**
     * job assignment id property - used for unique database identifier
     */
    var $__jobassignmentid ;

    /**
     * userid property - WP user id of person assigned to a job
     */
    var $__userid ;

    /**
     * committed property - time stamp when job was comitted to
     */
    var $__committed ;

    /**
     * Constructor
     *
     * If the constructor is passed an optional parent instance
     * it will set all of the properties based on the parent instance.
     *
     * @param - mixed - optional parent class to prepopulate new class
     */
    function SwimTeamJobAssignment($parent = null)
    {
        if (!is_null($parent))
        {
            if (strtolower(get_class($parent))
                == strtolower(get_parent_class($this)))
            {
                $this->setUserId(WPST_NULL_ID) ;
                $this->setJobId($parent->getJobId()) ;
                $this->setJobAllocationId($parent->getJobAllocationId()) ;
                $this->setJobQuantity($parent->getJobQuantity()) ;
                $this->setSeasonId($parent->getSeasonId()) ;
                $this->setMeetId($parent->getMeetId()) ;
            }
        }
    }

    /**
     * Set the job assignment id
     *
     * @param - int - id of the job assignment
     */
    function setJobAssignmentId($id)
    {
        $this->__jobassignmentid = $id ;
    }

    /**
     * Get the job assignment id
     *
     * @return - int - id of the job assignment
     */
    function getJobAssignmentId()
    {
        return ($this->__jobassignmentid) ;
    }

    /**
     * Set the user id
     *
     * @param - int - id of the user
     */
    function setUserId($id)
    {
        $this->__userid = $id ;
    }

    /**
     * Get the user id
     *
     * @return - int - id of the user
     */
    function getUserId()
    {
        return ($this->__userid) ;
    }

    /**
     * Set the commitment
     *
     * @param - mixed - commitment
     */
    function setCommitment($commitment)
    {
        $this->__commitment = $commitment ;
    }

    /**
     * Get the commitment
     *
     * @return - mixed - commitment
     */
    function getCommitment()
    {
        return ($this->__commitment) ;
    }

    /**
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of position
     */
    function jobAssignmentExistByJobAssignmentId($id = null)
    {
        if (is_null($id)) $id = $this->getJobAssignmentId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT jobassignmentid FROM %s WHERE jobassignmentid=\"%s\"",
            WPST_JOB_ASSIGNMENTS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of position
     */
    function jobAssignmentExistByJobAllocationId($id = null)
    {
        if (is_null($id)) $id = $this->getJobAllocationId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT jobassignmentid FROM %s WHERE joballocationid=\"%s\"",
            WPST_JOB_ASSIGNMENTS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of position
     */
    function jobAssignmentExistByJobIdAndMeetId()
    {
	    //  Is id already in the database?

        $query = sprintf("SELECT jobassignmentid FROM %s WHERE jobid=\"%s\"
            AND meetid=\"%s\"", WPST_JOB_ASSIGNMENTS_TABLE, $this->getJobId(),
            $this->getMeetId()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of position
     */
    function jobAssignmentExistByJobIdSeasonIdAndMeetId()
    {
	    //  Is id already in the database?

        $query = sprintf("SELECT jobassignmentid FROM %s WHERE jobid=\"%s\"
            AND seasonid=\"%s\" AND meetid=\"%s\"", WPST_JOB_ASSIGNMENTS_TABLE,
            $this->getJobId(), $this->getSeasonId(), $this->getMeetId()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new job assignment
     *
     * @param - boolean - optional boolean to override checking
     */
    function assignJob($preload = false)
    {
        $success = null ;

        //  Make sure the job assignment doesn't exist yet

        if (!$this->jobAssignmentExistByJobIdSeasonIdAndMeetId() || $preload)
        {
            //  Construct the insert query
 
            $query = sprintf("INSERT INTO %s SET
                jobid=\"%s\",
                userid=\"%s\",
                seasonid=\"%s\",
                meetid=\"%s\",
                committed=\"%s\",
                joballocationid=\"%s\"",
                WPST_JOB_ASSIGNMENTS_TABLE,
                $this->getJobId(),
                $this->getUserId(),
                $this->getSeasonId(),
                $this->getMeetId(),
                date("Y-m-d H:m"),
                $this->getJobAllocationId()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * job reassignment
     *
     * @param int - optional user id to assign job to
     * @return int - success, number of rows affected
     */
    function reassignJob($userid = null)
    {
        //  Account for user id overrides.

        if ($userid == null) $userid = $this->getUserId() ;

        //  Make sure the job does exist

        if ($this->jobAssignmentExistByJobAssignmentId())
        {
            $this->loadJobAssignmentByJobAssignmentId() ;
            $olduserid = $this->getUserId() ;

            //  Construct the update query
 
            $query = sprintf("UPDATE %s
                SET userid=\"%s\"
                WHERE jobassignmentid=\"%s\"", WPST_JOB_ASSIGNMENTS_TABLE,
                $userid, $this->getJobAssignmentId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;

            //  Send confirmation e-mails
 
            if ($userid != WPST_NULL_ID)
                $this->sendConfirmationEmail($userid, true) ;

            if ($olduserid != WPST_NULL_ID)
                $this->sendConfirmationEmail($olduserid, false) ;
        }
        else
        {
            $success = false ;
        }

        return $success ;
    }

    /**
     * job unassignment
     *
     * @return int - success, number of rows affected
     */
    function unassignJob()
    {
        return $this->reassignJob(WPST_NULL_ID) ;
    }

    /**
     * delete job assignment
     *
     * @return int - success, number of rows affected
     */
    function deleteJobAssignment()
    {
        //  Make sure the job does exist

        if ($this->jobAssignmentExistByJobAssignmentId())
        {
            //  Construct the update query
 
            $query = sprintf("DELETE FROM %s
                WHERE jobassignmentid=\"%s\"",
                WPST_JOB_ASSIGNMENTS_TABLE,
                $this->getJobAssignmentId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }
        else
        {
            $success = false ;
        }

        return $success ;
    }

    /**
     * Load a job record by Id
     *
     * @param - string - optional position
     */
    function loadJobAssignmentByJobAssignmentId($id = null)
    {
        if (is_null($id)) $id = $this->getJobAssignmentId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setJobAssignmentId($id) ;

        //  Make sure it is a legal job id
        if ($this->jobAssignmentExistByJobAssignmentId())
        {
            $query = sprintf("SELECT * FROM %s WHERE jobassignmentid = \"%s\"",
                WPST_JOB_ASSIGNMENTS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobAssignmentId($result['jobassignmentid']) ;
            $this->setJobId($result['jobid']) ;
            //$this->setJobQuantity($result['jobquantity']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setMeetId($result['meetid']) ;
            $this->setUserId($result['userid']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Load a job assignment record by job id and meet id
     *
     * @param - int - optional job id
     * @param - int - optional meet id
     */
    function loadJobAssignmentByJobIdAndMeetId($jobid = null, $meetid = null)
    {
        if (is_null($jobid)) $jobid = $this->getJobId() ;
        if (is_null($meetid)) $meetid = $this->getMeetId() ;

        //  Dud?
        if (is_null($jobid) || is_null($meetid)) return false ;

        $this->setJobId($jobid) ;
        $this->setMeetId($meetid) ;

        //  Make sure it is a legal job id
        if ($this->jobAssignmentExistByJobIdAndMeetId())
        {
            $query = sprintf("SELECT * FROM %s WHERE jobid=\"%s\" AND meetid=\"%s\"",
                WPST_JOB_ASSIGNMENTS_TABLE, $jobid, $meetid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobAssignmentId($result['jobassignmentid']) ;
            $this->setJobId($result['jobid']) ;
            $this->setJobQuantity($result['jobquantity']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setMeetId($result['meetid']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Load a job assignment record by job id and meet id
     *
     * @param - int - optional job id
     * @param - int - optional season id
     * @param - int - optional meet id
     */
    function loadJobAssignmentByJobIdSeasonIdAndMeetId($jobid = null,
        $seasonid = null, $meetid = null)
    {
        if (is_null($jobid)) $jobid = $this->getJobId() ;
        if (is_null($seasonid)) $seasonid = $this->getSeasonId() ;
        if (is_null($meetid)) $meetid = $this->getMeetId() ;

        //  Dud?
        if (is_null($jobid) || is_null($meetid) || is_null($seasonid)) return false ;

        $this->setJobId($jobid) ;
        $this->setSeasonId($seasonid) ;
        $this->setMeetId($meetid) ;

        //  Make sure it is a legal job id
        if ($this->jobAssignmentExistByJobIdSeasonIdAndMeetId())
        {
            $query = sprintf("SELECT * FROM %s WHERE
                jobid=\"%s\" AND seasonid=\"%s\" AND meetid=\"%s\"",
                WPST_JOB_ASSIGNMENTS_TABLE, $jobid, $seasonid, $meetid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setJobAssignmentId($result['jobassignmentid']) ;
            $this->setJobId($result['jobid']) ;
            $this->setCommitment($result['committed']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setMeetId($result['meetid']) ;
            $this->setJobAllocationId($result['joballocationid']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Get Job Ids by Meet Id
     *
     * @param int - $id - swim meet id
     * @return mixed - array of job ids
     */
    function getJobIdsByMeetId($id = null)
    {
        if (is_null($id)) $id = $this->getMeetId() ;

        //  Select the records for the meet

        $query = sprintf("SELECT %s.jobid FROM %s, %s WHERE %s.meetid=\"%s\"
            AND %s.jobid = %s.jobid ORDER BY %s.jobposition",
            WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
            WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
            WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Get Job Assignment Ids by Meet Id
     *
     * @param int - $id - swim meet id
     * @return mixed - array of job assignment ids
     */
    function getJobAssignmentIdsByMeetId($id = null, $fullseasonjobs = false)
    {
        if (is_null($id)) $id = $this->getMeetId() ;

        //  Select the records for the meet

        if ($fullseasonjobs)
            $query = sprintf("SELECT %s.jobassignmentid FROM %s, %s WHERE
                %s.meetid=\"%s\" AND %s.jobid = %s.jobid
                ORDER BY %s.jobposition, %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE) ;
        else
            $query = sprintf("SELECT %s.jobassignmentid FROM %s, %s WHERE
                %s.meetid=\"%s\" AND %s.jobid = %s.jobid AND
                %s.jobduration != \"%s\" ORDER BY %s.jobposition,
                %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_DURATION_FULL_SEASON, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Get Job Assignment Ids by User Id
     *
     * @param int - $id - user id
     * @return mixed - array of job assignment ids
     */
    function getJobAssignmentIdsByUserId($id = null, $fullseasonjobs = false)
    {
        if (is_null($id)) $id = $this->getUserId() ;

        //  Select the records for the meet

        if ($fullseasonjobs)
            $query = sprintf("SELECT DISTINCT %s.jobassignmentid FROM %s, %s WHERE
                %s.userid=\"%s\" AND %s.jobid = %s.jobid
                ORDER BY %s.jobposition, %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE) ;
        else
            $query = sprintf("SELECT DISTINCT %s.jobassignmentid FROM %s, %s WHERE
                %s.userid=\"%s\" AND %s.jobid = %s.jobid AND
                %s.jobduration != \"%s\" ORDER BY %s.jobposition,
                %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_DURATION_FULL_SEASON, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

     /**
     * Get Job Assignment Ids by Job Id and Season Id
     *
     * @param int - $id - swim meet id
     * @return mixed - array of job assignment ids
     */
    function getJobAssignmentIdsByJobIdAndSeasonId($jobid = null, $seasonid = null)
    {
        if (is_null($jobid)) $jobid = $this->getJobId() ;

        if (is_null($seasonid)) $seasonid = $this->getSeasonId() ;

        if (is_null($jobid) || is_null($seasonid)) wp_die("Invalid query.") ;

        $filter = sprintf("%s.seasonid = \"%s\" AND %s.jobid = \"%s\"",
            WPST_JOB_ASSIGNMENTS_TABLE, $seasonid,
            WPST_JOB_ASSIGNMENTS_TABLE, $jobid) ;

        return $this->getAllJobAssignmentIds($filter) ;
    }

    /**
     * Get Job Assignment Ids by Season Id
     *
     * @param int - $id - season id
     * @return mixed - array of job assignment ids
     */
    function getJobAssignmentIdsBySeasonId($id = null, $fullseason = true)
    {
        if (is_null($id)) $id = $this->getSeasonId() ;

        //  Select the records for the meet

        if ($fullseason)
            $query = sprintf("SELECT %s.jobassignmentid FROM %s, %s WHERE
                %s.seasonid=\"%s\" AND %s.jobid=%s.jobid AND
                %s.meetid=\"%s\" ORDER BY %s.jobposition,
                %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_NULL_ID, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE) ;
        else
            $query = sprintf("SELECT %s.jobassignmentid FROM %s, %s WHERE
                %s.seasonid=\"%s\" AND %s.jobid = %s.jobid
                ORDER BY %s.jobposition, %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $id,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Get Job Assignment Ids by Season Id and User Id
     *
     * @param int - $seasonid - season id
     * @param int - $userid - user id
     * @return mixed - array of job assignment ids
     */
    function getJobAssignmentIdsBySeasonIdAndUserId($seasonid = null, $userid = null, $fullseason = true)
    {
        if (is_null($userid)) $userid = $this->getUserId() ;
        if (is_null($seasonid)) $seasonid = $this->getSeasonId() ;

        //  Select the records for the meet

        if ($fullseason)
            $query = sprintf("SELECT DISTINCT %s.jobassignmentid FROM %s, %s WHERE
                %s.seasonid=\"%s\" AND %s.jobid=%s.jobid AND
                %s.meetid=\"%s\" AND %s.userid=\"%s\" ORDER BY %s.jobposition,
                %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $seasonid,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_NULL_ID, WPST_JOB_ASSIGNMENTS_TABLE,
                $userid, WPST_JOBS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE) ;
        else
            $query = sprintf("SELECT DISTINCT %s.jobassignmentid FROM %s, %s WHERE
                %s.seasonid=\"%s\" AND %s.jobid = %s.jobid AND %s.userid=\"%s\"
                ORDER BY %s.jobposition, %s.jobassignmentid",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE, $seasonid,
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOBS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE,
                $userid, WPST_JOBS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Job Assignment Ids.  Can optionally based
     * be filtered to only return a subset of the assignment ids.
     *
     * @param - string - optional filter to restrict query
     * @param - string - optional order by to order query results
     * @return - array - array of assignment ids
     */
    function getAllJobAssignmentIds($filter = null, $orderby = null)
    {
        //  Select the records for the season

        $query = sprintf("SELECT %s.jobassignmentid FROM %s",
                WPST_JOB_ASSIGNMENTS_TABLE, WPST_JOB_ASSIGNMENTS_TABLE) ;

        if (!is_null($filter) && ($filter != ""))
            $query .= sprintf(" WHERE %s", $filter) ;

        if (is_null($orderby) || ($orderby == ""))
            $orderby = sprintf("%s.%s", WPST_JOB_ASSIGNMENTS_TABLE, "jobassignmentid") ;

        $query .= sprintf(" ORDER BY %s", $orderby) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve all the Job Assignment Ids.  Can optionally based
     * be filtered to only return a subset of the assignment ids.
     *
     * @param - string - optional filter to restrict query
     * @param - string - optional order by to order query results
     * @return - array - array of assignment ids
     */
    function getAllJobAssignmentIdsByJobAllocationId($id = null)
    {
        if (is_null($id)) $id = $this->getJobAllocationId() ;

        if (is_null($id)) wp_die("Null Job Allocation Id") ;

        $filter = sprintf("%s.%s = \"%s\"",
            WPST_JOB_ASSIGNMENTS_TABLE, "joballocationid", $id) ;

        return $this->getAllJobAssignmentIds($filter) ;
    }

    /**
     * Send Confirmation E-mail
     *
     * Send an e-mail to the user confirming the action
     * taken (assign or unassign) for the job assignment to
     * the user performing the action and the address(es)
     * set up to receive e-mail.
     *
     * @param string $action - action to take, assign or unassign
     */
    function sendConfirmationEmail($userid, $signup = true) 
    {
        $mode = get_option(WPST_OPTION_JOB_EMAIL_FORMAT) ;
        $from = get_option(WPST_OPTION_JOB_EMAIL_ADDRESS) ;

        global $userdata ;
        get_currentuserinfo() ;

        $action = ($signup) ? __('sign up') : __('withdrawal') ;

        $meetdetails = SwimTeamTextMap::__mapMeetIdToText($this->getMeetId()) ;
        $jobdetails = SwimTeamTextMap::__mapJobIdToText($this->getJobId()) ;
        $seasondetails = SwimTeamTextMap::__mapSeasonIdToText($this->getSeasonId(), true) ;

        $u = get_userdata($userid) ;
    
        if ($this->getMeetId() == WPST_NULL_ID)
        {
            $actionmsgs[] = sprintf('Name:  %s %s (%s)', $u->first_name, $u->last_name, $u->user_login) ;
            $actionmsgs[] = sprintf('Job:  %s', $jobdetails) ;
            $actionmsgs[] = sprintf('Swim Season:  %s - %s - %s',
                $seasondetails["label"], $seasondetails["start"], $seasondetails["end"]) ;
        }
        else
        {
            $actionmsgs[] = sprintf('Name:  %s %s (%s)', $u->first_name, $u->last_name, $u->user_login) ;
            $actionmsgs[] = sprintf('Job:  %s', $jobdetails) ;
            $actionmsgs[] = sprintf('Swim Meet:  %s - %s - %s',
                $meetdetails["opponent"], $meetdetails["date"], $meetdetails["location"]) ;
        }

        $c1data = get_userdata($userid) ;
        $c1email = $c1data->user_email ;

        // To send HTML mail, the Content-type header must be set

        if ($mode == WPST_HTML)
        {
            $headers  = 'MIME-Version: 1.0' . "\r\n" ;
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n" ;
        }
        else
        {
            $headers = '' ;
        }

        // Additional headers
        //$headers .= sprintf('To: "%s %s" <%s>', $c1data->user_firstname,
        //    $c1data->user_lastname, $c1data->user_email) . "\r\n" ;

        $headers .= sprintf('From: %s <%s>', get_bloginfo('name'), $from) . "\r\n" ;
        $headers .= sprintf('Cc: %s', $from) . "\r\n" ;
        $headers .= sprintf('Bcc: %s', get_bloginfo('admin_email')) . "\r\n" ;
        $headers .= sprintf('Reply-To: %s', $from) . "\r\n" ;
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
                A job %s request has been received for %s:
                </p>
                <ul>
                ' ;

            $htmlftr = '
                </ul>
                <p>
                View all <a href="%s">Job Descriptions and Expectations</a>.
                </p>
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
                $htmlbody .= sprintf("<li>%s</li>", $actionmsg) ;

            $message = sprintf($htmlhdr,
                get_bloginfo('url'),
                $c1data->user_firstname,
                $action,
                $c1data->user_firstname . " " . $c1data->user_lastname) ;

            $message .= $htmlbody ;

            $message .= sprintf($htmlftr,
                get_option(WPST_OPTION_JOB_EXPECTATIONS_URL),
                get_bloginfo('name'),
                get_bloginfo('url'),
                get_bloginfo('url')) ;
        }
        else
        {
            $plain = "%s -\r\n\r\n" ;
            $plain .= "A job %s request has been received for %s:\r\n\r\n" ;

            //  Add each action message to the e-mail body
  
            foreach ($actionmsgs as $actionmsg)
                $plain .= strip_tags($actionmsg) . "\r\n" ;

            $plain .= "\r\n\r\nView job descriptions and expectations:  %s\r\n\r\n" ;
            $plain .= "\r\n\r\nThank you,\r\n\r\n" ;
            $plain .= "%s\r\n\r\n" ;
            $plain .= "Visit %s for all your swim team news." ;

            $message = sprintf($plain,
                $c1data->user_firstname,
                $action,
                $c1data->user_firstname . " " . $c1data->user_lastname,
                get_option(WPST_OPTION_JOB_EXPECTATIONS_URL),
                get_bloginfo('name'),
                get_bloginfo('url'),
                get_bloginfo('url')) ;
        }

        $to = sprintf("%s %s <%s>", $c1data->user_firstname,
            $c1data->user_lastname, $c1data->user_email) ;

        $subject = sprintf("Job %s for %s",
            $action, $c1data->user_firstname . " " . $c1data->user_lastname) ;

        $status = wp_mail($to, $subject, $message, $headers) ;

        return $status ;
    }
}

/**
 * Extended InfoTable Class for presenting SwimTeam
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimMeetJobAssignmentInfoTable extends SwimTeamInfoTable
{
    /**
     * id property, used to query job information
     */
    var $__meetid = null ;

    /**
     * show first initial property
     */
    var $__show_first_initial = false ;

    /**
     * show last initial property
     */
    var $__show_last_initial = false ;

    /**
     * show username property
     */
    var $__show_username = false ;

    /**
     * show email property
     */
    var $__show_email = false ;

    /**
     * show phone property
     */
    var $__show_phone = false ;

    /**
     * show notes property
     */
    var $__show_notes = false ;

    /**
     * Set the meet id
     *
     * @param int - the id of the meet profile to query
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id
     *
     * @return int - the id of the meet profile to query
     */
    function getMeetId()
    {
        return $this->__meetid ;
    }

    /**
     * set first initial flag
     *
     * @param - boolean - flag to show only first initial
     */
    function setShowFirstInitial($flag = false)
    {
        $this->__show_first_initial = $flag ;
    }

    /**
     * get first initial flag
     *
     * @return - boolean - flag to show only first initial
     */
    function getShowFirstInitial()
    {
        return $this->__show_first_initial ;
    }

    /**
     * set last initial flag
     *
     * @param - boolean - flag to show only last initial
     */
    function setShowLastInitial($flag = false)
    {
        $this->__show_last_initial = $flag ;
    }

    /**
     * get last initial flag
     *
     * @return - boolean - flag to show only last initial
     */
    function getShowLastInitial()
    {
        return $this->__show_last_initial ;
    }

    /**
     * set show username
     *wpst_meet_jobassignments_sc_handler
     * @param - boolean - flag to show only show username
     */
    function setShowUsername($flag = false)
    {
        $this->__show_username = $flag ;
    }

    /**
     * get show username flag
     *
     * @return - boolean - flag to show only show username
     */
    function getShowUsername()
    {
        return $this->__show_username ;
    }

    /**
     * set show email flag
     *
     * @param - boolean - flag to show only show email
     */
    function setShowEmail($flag = false)
    {
        $this->__show_email = $flag ;
    }

    /**
     * get show email flag
     *
     * @return - boolean - flag to show only show email
     */
    function getShowEmail()
    {
        return $this->__show_email ;
    }

    /**
     * set show phone flag
     *
     * @param - boolean - flag to show only show phone
     */
    function setShowPhone($flag = false)
    {
        $this->__show_phone = $flag ;
    }

    /**
     * get show phone flag
     *
     * @return - boolean - flag to show only show phone
     */
    function getShowPhone()
    {
        return $this->__show_phone ;
    }

    /**
     * set show notes flag
     *
     * @param - boolean - flag to show only show notes
     */
    function setShowNotes($flag = false)
    {
        $this->__show_notes = $flag ;
    }

    /**
     * get show notes flag
     *
     * @return - boolean - flag to show only show notes
     */
    function getShowNotes()
    {
        return $this->__show_notes ;
    }

    /**
     * Build the InfoTable
     *
     */
    function constructSwimMeetJobAssignmentInfoTable($meetid = null, $userid = null)
    {
        //  Need swim meet classes to build the table

        require_once("swimmeets.class.php") ;

        if (is_null($meetid)) $meetid = $this->getMeetId() ;

        if (is_null($meetid))
        {
            $this->add_row("No data.") ;
        }
        else
        {
            $user = new SwimTeamUserProfile() ;

            $this->set_alt_color_flag(true) ;
            $this->set_show_cellborders(true) ;

            $job = new SwimTeamJob() ;
            $ja = new SwimTeamJobAssignment() ;

            $ja->setMeetId($this->getMeetId()) ;

            $swimmeet = new SwimMeet() ;
            $swimmeet->loadSwimMeetByMeetId($this->getMeetId()) ;

            //  Get season long job ids
            $jaids = $ja->getJobAssignmentIdsBySeasonId($swimmeet->getSeasonId()) ;

            if (is_null($jaids)) $jaids = array() ;

            //  Merge with meet job ids
            $jaids = array_merge($jaids, $ja->getJobAssignmentIdsByMeetId(null, true)) ;

            if (empty($jaids))
            {
                $jaids = array() ;
                $this->add_row("No job assignments.") ;
            }
            else
            {
                //  Construct the header
                $row = array(html_b("Position"), html_b("Name")) ;

                if ($this->getShowEmail())
                    $row[] = html_b("Email Address") ;

                if ($this->getShowPhone())
                    $row[] = html_b("Phone Number(s)") ;

                if ($this->getShowNotes())
                    $row[] = html_b("Notes") ;

                call_user_func_array(array(&$this, 'add_row'), $row) ;

                //  Add job assignments

                foreach ($jaids as $jaid)
                {
                    $row = array() ;
                    $key = &$jaid["jobassignmentid"] ;

                    $ja->loadJobAssignmentByJobAssignmentId($key) ;
                    $job->loadJobByJobId($ja->getJobId()) ;

                    if (($userid == null) || ($ja->getUserId() == $userid))
                    {
                        //  Job Position
                        $row[] = html_b($job->getJobPosition()) ;
    
                        //  Name fields, handle initials if necessary
    
                        $u = get_userdata($ja->getUserId()) ;
    
                        //  Only report the data when the userid is null or
                        //  when the user id matches.  Id will be null for admin
                        //  users, set to a specific id for regular users.
     
                        if ($ja->getUserId() != WPST_NULL_ID)
                        {
                            $name = ($this->getShowFirstInitial() ?
                                substr($u->first_name, 0, 1) . "." : $u->first_name) ;
    
                            $name .= " " . ($this->getShowLastInitial() ?
                                substr($u->last_name, 0, 1) . "." : $u->last_name) ;
    
                            if ($this->getShowUsername())
                                $name .= " (" . $u->user_login . ")" ;
    
                            $row[] = $name ;
                        }
                        else
                        {
                           $row[] = __("None") ;
                        }
    
                        if ($this->getShowEmail())
                        {
                            if ($ja->getUserId() != WPST_NULL_ID)
                                $row[] = html_a(sprintf("mailto:%s",
                                    $u->user_email), $u->user_email) ;
                            else
                                $row[] = _HTML_SPACE ;
                        }
    
                        if ($this->getShowPhone())
                        {
                            if ($ja->getUserId() != WPST_NULL_ID)
                            {
                                $user->loadUserProfileByUserId($ja->getUserId()) ;
                                $row[] = $user->getPrimaryPhone() .
                                    " / " .  $user->getSecondaryPhone() ;
                            }
                            else
                                $row[] = _HTML_SPACE ;
                        }
    
                        //  Job Notes

                        if ($this->getShowNotes())
                            $row[] = $job->getJobNotes() ;
        
                        call_user_func_array(array(&$this, 'add_row'), $row) ;
                    }
                }
            }
        }
    }
}

/**
 * Extended InfoTable Class for presenting SwimTeam
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamJobDescriptionsInfoTable extends SwimTeamInfoTable
{
    /**
     * show inactive property
     */
    var $__show_inactive = false ;

    /**
     * set show inactive flag
     *
     * @param - boolean - flag to show only show inactive
     */
    function setShowInactive($flag = false)
    {
        $this->__show_inactive = $flag ;
    }

    /**
     * get show phone flag
     *
     * @return - boolean - flag to show show inactive
     */
    function getShowInactive()
    {
        return $this->__show_inactive ;
    }

    /**
     * Build the InfoTable
     *
     */
    function constructSwimTeamJobDescriptionsInfoTable()
    {
        $job = new SwimTeamJob() ;

        $jobids = $job->getAllJobIds() ;

        if (empty($jobids))
        {
            $this->add_row("No jobs defined.") ;
        }
        else
        {
            $this->set_alt_color_flag(true) ;
            $this->set_show_cellborders(true) ;

            $attrs = array("width" => "25%", "align" => "right",
                "valign" => "top", "style" => "padding-right: 5px") ;

            //  Add the job descriptions, one per row.
 
            foreach ($jobids as $jobid)
            {
                $job->loadJobByJobId($jobid["jobid"]) ;

                $table = html_table() ;
                $table->set_tag_attributes(array("style" => "padding: 7px")) ;

                $th = html_th("Position:") ;
                $th->set_tag_attributes($attrs) ;
                $table->add_row($th,
                    html_td(null, null, $job->getJobPosition())) ;

                $th = html_th("Description:") ;
                $th->set_tag_attributes($attrs) ;
                $table->add_row($th,
                   html_td(null, null, $job->getJobDescription())) ;

                $th = html_th("Duration:") ;
                $th->set_tag_attributes($attrs) ;
                $table->add_row($th,
                   html_td(null, null, ucwords($job->getJobDuration()))) ;

                $th = html_th("Type:") ;
                $th->set_tag_attributes($attrs) ;
                $table->add_row($th,
                   html_td(null, null, ucwords($job->getJobType()))) ;

                $th = html_th("Location:") ;
                $th->set_tag_attributes($attrs) ;
                if ($job->getJobLocation() == WPST_BOTH)
                    $table->add_row($th, html_td(null, null,
                        ucwords(WPST_HOME) . " and " . ucwords(WPST_AWAY))) ;
                else
                    $table->add_row($th,
                       html_td(null, null, ucwords($job->getJobLocation()))) ;

                $th = html_th("Credits:") ;
                $th->set_tag_attributes($attrs) ;
                $table->add_row($th,
                   html_td(null, null, $job->getJobCredits())) ;

                $th = html_th("Status:") ;
                $th->set_tag_attributes($attrs) ;
                $table->add_row($th,
                   html_td(null, null, ucwords($job->getJobStatus()))) ;

                $this->add_row($table) ;
            }
        }
    }
}

/**
 * Extended InfoTable Class for presenting SwimTeam
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamUserJobsInfoTable extends SwimTeamInfoTable
{
    /**
     * Credits property
     */
    var $__credits ;

    /**
     * User Id property
     */
    var $__userid ;

    /**
     * Season Id property
     */
    var $__seasonid ;

    /**
     * Set the credits
     *
     * @param - int - credits
     */
    function setCredits($credits)
    {
        $this->__credits = $credits ;
    }

    /**
     * Get the credits
     *
     * @return - int - credits
     */
    function getCredits()
    {
        return ($this->__credits) ;
    }

    /**
     * Set the user id
     *
     * @param - int - id of the user
     */
    function setUserId($id)
    {
        $this->__userid = $id ;
    }

    /**
     * Get the user id
     *
     * @return - int - id of the user
     */
    function getUserId()
    {
        return ($this->__userid) ;
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
     * Build the InfoTable
     *
     */
    function constructSwimTeamUserJobsInfoTable()
    {
        $this->setCredits(0) ;

        require_once('textmap.class.php') ;

        $this->set_alt_color_flag(true) ;
        $this->set_show_cellborders(true) ;

        $attrs = array("width" => "25%", "align" => "right",
            "valign" => "top", "style" => "padding-right: 5px") ;

        $this->add_column_header('Date', '10%', 'left') ;
        $this->add_column_header('Position', '40%', 'left') ;
        $this->add_column_header('Credits', '10%', 'left') ;
        $this->add_column_header('Opponent', '30%', 'left') ;
        $this->add_column_header('Location', '10%', 'left') ;

        //  Get all of the Job assignmnts for the specficied user.

        $ja = new SwimTeamJobAssignment() ;
        $ja->setUserId($this->getUserId()) ;
        $ja->setSeasonId($this->getSeasonId()) ;
        $jaids = $ja->getJobAssignmentIdsBySeasonIdAndUserId(null, null, false) ;

        //  Loop through the Job assignment ids

        foreach ($jaids as $jaid)
        {
            $ja->loadJobAssignmentByJobAssignmentId($jaid['jobassignmentid']) ;
            $ja->loadJobByJobId($ja->getJobId()) ;
            $season = SwimTeamTextMap::__mapSeasonIdToText($ja->getSeasonId()) ;

            //  Is the job a full season job?

            if ($ja->getMeetId() == WPST_NULL_ID)
            {
                $meet['date'] = ucwords(WPST_FULL . ' ' . WPST_SEASON) ;
                $meet['opponent'] = strtoupper(WPST_NA) ;
                $meet['location'] = strtoupper(WPST_NA) ;
            }
            else
            {
                $meet = SwimTeamTextMap::__mapMeetIdToText($ja->getMeetId()) ;
            }

            $this->add_row($meet['date'], $ja->getJobPosition(),
                $ja->getJobCredits(), $meet['opponent'], $meet['location']) ;

            $this->__credits += $ja->getJobCredits() ;
        }
    }
}
?>
