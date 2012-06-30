<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: db.class.php 926 2012-06-28 22:24:39Z mpwalsh8 $
 *
 * Form classes.  These classes manage the
 * entry and display of the various forms used
 * by the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for WpSwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage db
 * @version $Revision: 926 $
 * @lastmodified $Date: 2012-06-28 18:24:39 -0400 (Thu, 28 Jun 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

//  Need the DB defintions so everything will work

require_once('db.include.php') ;

//  Build upon the WordPress database class

include_once(ABSPATH . '/wp-config.php') ;
include_once(ABSPATH . '/wp-includes/wp-db.php') ;

/**
 * Class for managing the SwimTeam the database interface.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 *
 */

class SwimTeamDBI
{
    /**
     * Property to store the wpdb handle
     */
    var $wpstdb = null ;

    /**
     * Property to store the query to be exectued.
     */
    var $_query ;

    /**
     * Property to store the results of the query
     * assuming the query submitted was a SELECT query.
     */
    var $_queryResults ;

    /**
     * Property to store the number of rows returned by
     * a select query.
     */
    var $_queryCount ;

    /**
     * Property to store the ID of an INSERT query.
     */
    var $_insertId ;

    /**
     * Property to store the status of the WordPress Query
     */
    var $_wpstWpQuery ;

    /**
     * Mode to fetch query results through the WordPress
     * DB class.  By default, use associative mode which
     * constructs rows indexed by the column headers as
     * opposed to numeric index.
     */
    var $_output = ARRAY_A ;

    /**
     * Get the WordPress Query Status
     *
     * @return boolean - true if WordPress database error
     */
    function SwimTeamDBIWordPressDatabaseError()
    {
        return ($this->_wpstWpQuery === false) ;
    }

    /**
     * Set the DB fetch mode.
     *
     * @param int - mode to fetch data in
     */
    function setOutput($mode = ARRAY_A)
    {
        $this->_output = $mode ;
    }

    /**
     * Get the DB fetch mode.
     *
     * @return int - mode to fetch data in
     */
    function getOutput()
    {
        return $this->_output ;
    }

    /**
     * Set the query string to be executed.
     *
     * @param string - query string
     */
    function setQuery($query)
    {
        $this->_query = $query ;
    }

    /**
     * Get the query string to be executed.
     *
     * @return string - query string
     */
    function getQuery()
    {
        return $this->_query ;
    }

    /**
     * Run an update query
     *
     * @return int - query insert id
     */
    function runInsertQuery()
    {
        global $wpdb ;

        //  Create a database instance

        if ($this->wpstdb == null)
            $this->wpstdb = &$wpdb ;

        //  Execute the query
 
        $this->_wpstWpQuery = $this->wpstdb->query($this->getQuery()) ;

        $this->_insertId = $this->wpstdb->insert_id ;

        return $this->_insertId ;
    }

    /**
     * Run a delete query
     *
     * @return int affected row count
     */
    function runDeleteQuery()
    {
        return $this->runDeleteReplaceOrUpdateQuery() ;
    }

    /**
     * Run a replace query
     *
     * @return int affected row count
     */
    function runReplaceQuery()
    {
        return $this->runDeleteReplaceOrUpdateQuery() ;
    }

    /**
     * Run an update query
     *
     * @return int affected row count
     */
    function runUpdateQuery()
    {
        return $this->runDeleteReplaceOrUpdateQuery() ;
    }

    /**
     * Run a delete, replace, or update query
     *
     * @return int affected row count
     */
    function runDeleteReplaceOrUpdateQuery()
    {
        global $wpdb ;

        //  Create a database instance

        if ($this->wpstdb == null)
            $this->wpstdb = &$wpdb ;

        //  Execute the query
 
        $this->_wpstWpQuery = $this->wpstdb->query($this->getQuery()) ;
        $this->_affectedRows = $this->_wpstWpQuery !== false ? $this->_wpstWpQuery : 0 ;
        //$this->_affectedRows = $this->wpstdb->query($this->getQuery()) ;

        return $this->_affectedRows ;
    }

    /**
     * Execute a SELECT query
     *
     * @param boolean - retrieve the results or simply perform the query
     *
     */
    function runSelectQuery($retrieveResults = true)
    {
        global $wpdb ;

        //  Create a database instance

        if ($this->wpstdb == null)
            $this->wpstdb = &$wpdb ;

        //  Execute the query
 
        if ($retrieveResults)
        {
            $qr = $this->wpstdb->get_results($this->getQuery(), $this->getOutput()) ;

            if (is_null($qr))
                $this->setQueryCount(0) ;
            else if (!is_array($qr))
                $this->setQueryCount(1) ;
            else
                $this->setQueryCount($this->wpstdb->num_rows) ;

            $this->setQueryResults($qr) ;
        }
        else
        {
            $this->_wpstWpQuery = $this->wpstdb->query($this->getQuery()) ;
            $this->setQueryCount($this->_wpstWpQuery !== false ? $this->_wpstWpQuery : 0) ;
        }

        return $this->getQueryCount() ;
    }

    /**
     * Return the Id value of the last Insert
     */
    function getInsertId()
    {
        return $this->_insertId ;
    }

    /**
     * Set the number of rows matched by the last query.
     */
    function setQueryCount($count)
    {
        $this->_queryCount = $count ;
    }

    /**
     * Return the number of rows matched by the last query.
     */
    function getQueryCount()
    {
        return $this->_queryCount ;
    }

    /**
     * Return the result of the last query.  Since the query
     * results are stored in an array, a query which has one
     * result is stored in an array containtining one element
     * which in turn contains the query result.
     *
     * This is a shortcult to return the result of a single row.
     */
    function getQueryResult()
    {
        return $this->_queryResults[0] ;
    }

    /**
     * Set the results of the submitted query.
     */
    function setQueryResults($results)
    {
        $this->_queryResults = $results ;
    }

    /**
     * Return the results of the submitted query.
     */
    function getQueryResults()
    {
        return $this->_queryResults ;
    }

    /**
     * Display the database error condition
     *
     * @param string - the error source
     */
    function dbError($errorSource = "Database Error")
    {
        if (mysql_errno() || mysql_error())      
            trigger_error("MySQL error: " . mysql_errno() .
	        " : " . mysql_error() . "({$errorSource})", E_USER_ERROR) ;
        else 
            trigger_error("Could not connect to SwimTeam Database ({$errorSource})", E_USER_ERROR) ;
    }
}
?>
