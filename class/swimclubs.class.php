<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Clubs profile classes.
 *
 * $Id: swimclubs.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage SwimClubs
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'class/db.class.php') ;
require_once(WPST_PATH . 'include/swimteam.include.php') ;
require_once(WPST_PATH . 'class/table.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;
require_once(WPST_PATH . 'class/team.class.php') ;

/**
 * Class definition of the Swim Team Profile
 *
 * Swim Clubs build on the team profile information since virtually
 * the exact information is needed for each swim club.  Unlike the team
 * profile, the swim club data is stored in a tables as opposed to the
 * WordPress options table.
 *
 * The properties, methods, and even the GUI can be reused from the
 * team profile classes.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamProfile
 */
class SwimClubProfile extends SwimTeamProfile
{
    /**
     * swim club id property
     */
    var $__swimclubid ;

    /**
     * contact name property
     */
    var $__contactname ;

    /**
     * Google Maps URL property
     */
    var $__googlemapsurl ;

    /**
     * MapQuest URL property
     */
    var $__mapquesturl ;

    /**
     * Notes property
     */
    var $__notes ;

    /**
     * Set the swim club id
     *
     * @param - int - id of the swim club
     */
    function setSwimClubId($id)
    {
        $this->__swimclubid = $id ;
    }

    /**
     * Get the swim club id
     *
     * @return - int - id of the swim club
     */
    function getSwimClubId()
    {
        return ($this->__swimclubid) ;
    }

    /**
     * Set the swim club contact name
     *
     * @param - string - contact name of the swim club
     */
    function setContactName($name)
    {
        $this->__contactname = $name ;
    }

    /**
     * Get the swim club contact name
     *
     * @return - string - contact name of the swim club
     */
    function getContactName()
    {
        return ($this->__contactname) ;
    }

    /**
     * Set the swim club Googple Maps URL
     *
     * @param - string - Google Maps URL of the swim club
     */
    function setGoogleMapsURL($url)
    {
        $this->__googlemapsurl = $url ;
    }

    /**
     * Get the swim club Google Maps URL
     *
     * @return - string - Google Maps URL of the swim club
     */
    function getGoogleMapsURL()
    {
        return ($this->__googlemapsurl) ;
    }

    /**
     * Set the swim club Googple Maps URL
     *
     * @param - string - MapQuest URL of the swim club
     */
    function setMapQuestURL($url)
    {
        $this->__mapquesturl = $url ;
    }

    /**
     * Get the swim club Google Maps URL
     *
     * @return - string - MapQuest URL of the swim club
     */
    function getMapQuestURL()
    {
        return ($this->__mapquesturl) ;
    }

    /**
     * Set the swim club notes
     *
     * @param - string - notes for the swim club
     */
    function setNotes($notes)
    {
        $this->__notes = $notes ;
    }

    /**
     * Get the swim club notes
     *
     * @return - string - notes for the swim club
     */
    function getNotes()
    {
        return ($this->__notes) ;
    }

    /**
     * Check if a swimclub already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of swimclub
     */
    function getSwimClubExists()
    {
	    //  Is a similar swimclub already in the database?

        $query = sprintf('SELECT swimclubid FROM %s WHERE
            teamname = "%s" AND cluborpoolname = "%s"',
            WPST_SWIMCLUBS_TABLE, $this->getTeamName(),
            $this->getClubOrPoolName()) ;

        //  Retain the query result so it can be used later if needed
 
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure swimclub doesn't exist

        $swimclubExists = (bool)($this->getQueryCount() > 0) ;

	    return $swimclubExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of swimclub
     */
    function getSwimClubExistsById($id = null)
    {
        if (is_null($id)) $id = $this->getSwimClubId() ;

	    //  Is id already in the database?

        $query = sprintf('SELECT swimclubid FROM %s WHERE swimclubid = "%s"',
            WPST_SWIMCLUBS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new swimclub
     */
    function addSwimClub()
    {
        $success = null ;

        //  Make sure the swimclub doesn't exist yet

        if (!$this->getSwimclubExists())
        {
            //  Construct the insert query
 
            $query = sprintf('INSERT INTO %s SET
                teamname="%s",
                cluborpoolname="%s",
                poollength="%s",
                poolmeasurementunits="%s",
                poollanes="%s",
                street1="%s",
                street2="%s",
                street3="%s",
                city="%s",
                stateorprovince="%s",
                postalcode="%s",
                country="%s",
                primaryphone="%s",
                secondaryphone="%s",
                contactname="%s",
                contactemail="%s",
                website="%s",
                googlemapsurl="%s",
                mapquesturl="%s",
                notes="%s"',
                WPST_SWIMCLUBS_TABLE,
                $this->getTeamName(),
                $this->getClubOrPoolName(),
                $this->getPoolLength(),
                $this->getPoolMeasurementUnits(),
                $this->getPoolLanes(),
                $this->getStreet1(),
                $this->getStreet2(),
                $this->getStreet3(),
                $this->getCity(),
                $this->getStateOrProvince(),
                $this->getPostalCode(),
                $this->getCountry(),
                $this->getPrimaryPhone(),
                $this->getSecondaryPhone(),
                $this->getContactName(),
                $this->getEmailAddress(),
                $this->getWebSite(),
                $this->getGoogleMapsURL(),
                $this->getMapQuestURL(),
                $this->getNotes()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update a swim club
     *
     * Update the label, start date, and/or end date but
     * don't update the status, that is done by explicity
     * opening or closing a swimclub.
     */
    function updateSwimClub()
    {
        $success = null ;

        //  Make sure the swimclub doesn't exist yet

        if ($this->getSwimClubExistsById())
        {
            //  Construct the insert query
 
            $query = sprintf('UPDATE %s SET
                teamname="%s",
                cluborpoolname="%s",
                poollength="%s",
                poolmeasurementunits="%s",
                poollanes="%s",
                street1="%s",
                street2="%s",
                street3="%s",
                city="%s",
                stateorprovince="%s",
                postalcode="%s",
                country="%s",
                primaryphone="%s",
                secondaryphone="%s",
                contactname="%s",
                contactemail="%s",
                website="%s",
                googlemapsurl="%s",
                mapquesturl="%s",
                notes="%s"
                WHERE swimclubid="%s"',
                WPST_SWIMCLUBS_TABLE,
                $this->getTeamName(),
                $this->getClubOrPoolName(),
                $this->getPoolLength(),
                $this->getPoolMeasurementUnits(),
                $this->getPoolLanes(),
                $this->getStreet1(),
                $this->getStreet2(),
                $this->getStreet3(),
                $this->getCity(),
                $this->getStateOrProvince(),
                $this->getPostalCode(),
                $this->getCountry(),
                $this->getPrimaryPhone(),
                $this->getSecondaryPhone(),
                $this->getContactName(),
                $this->getEmailAddress(),
                $this->getWebSite(),
                $this->getGoogleMapsURL(),
                $this->getMapQuestURL(),
                $this->getNotes(),
                $this->getSwimClubId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }
        else
        {
            wp_die('Serious database update error encountered.') ;
        }

        return true ;
    }

    /**
     * Delete a swimclub
     *
     * Really need to think about this because deleting a swimclub
     * means deleting all of the meets that go with it.  So if a
     * swimclub has meets (which have results), disallow deleting
     * the swimclub.  It can be 'hidden' but can't be deleted.
     *
     */
    function deleteSwimClub()
    {
        $success = null ;

        //  Make sure the swimclub doesn't exist yet

        if (!$this->getSwimClubExists())
        {
            //  Construct the insert query
 
            $query = sprintf('DELETE FROM %s
                WHERE id="%s"',
                WPST_SWIMCLUBS_TABLE,
                $this->getSwimClubId()
            ) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->getSwimClubExistsById() ;

        return $success ;
    }

    /**
     *
     * Load swimclub record by Id
     *
     * @param - string - optional swimclub id
     */
    function loadSwimClubBySwimClubId($swimclubid = null)
    {
        if (is_null($swimclubid)) $swimclubid = $this->getSwimClubId() ;

        //  Dud?
        if (is_null($swimclubid)) return false ;

        $this->setSwimClubId($swimclubid) ;

        //  Make sure it is a legal swimclub id
        if ($this->getSwimClubExistsById())
        {
            $query = sprintf('SELECT * FROM %s WHERE swimclubid = "%s"',
                WPST_SWIMCLUBS_TABLE, $swimclubid) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setSwimClubId($result['swimclubid']) ;
            $this->setTeamName($result['teamname']) ;
            $this->setClubOrPoolName($result['cluborpoolname']) ;
            $this->setPoolLength($result['poollength']) ;
            $this->setPoolMeasurementUnits($result['poolmeasurementunits']) ;
            $this->setPoolLanes($result['poollanes']) ;
            $this->setStreet1($result['street1']) ;
            $this->setStreet2($result['street2']) ;
            $this->setStreet3($result['street3']) ;
            $this->setCity($result['city']) ;
            $this->setStateOrProvince($result['stateorprovince']) ;
            $this->setPostalCode($result['postalcode']) ;
            $this->setCountry($result['country']) ;
            $this->setPrimaryPhone($result['primaryphone']) ;
            $this->setSecondaryPhone($result['secondaryphone']) ;
            $this->setContactName($result['contactname']) ;
            $this->setEmailAddress($result['contactemail']) ;
            $this->setWebSite($result['website']) ;
            $this->setGoogleMapsURL($result['googlemapsurl']) ;
            $this->setMapQuestURL($result['mapquesturl']) ;
            $this->setNotes($result['notes']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Retrieve all the Swim Club Ids for the swim clubs.
     * Swim Clubs can be filtered to return a subset of records
     *
     * @param - string - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getAllSwimClubIds($filter = null, $orderby = 'cluborpoolname')
    {
        //  Select the records for the season

        $query = sprintf('SELECT swimclubid FROM %s', WPST_SWIMCLUBS_TABLE) ;
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
class SwimTeamSwimClubsGUIDataList extends SwimTeamGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
    ) ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array(
         WPST_ACTION_ADD => WPST_ACTION_ADD
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
    function SwimTeamSwimClubsGUIDataList($title, $width = '100%',
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_SWIMCLUBS_DEFAULT_COLUMNS,
        $tables = WPST_SWIMCLUBS_DEFAULT_TABLES,
        $where_clause = WPST_SWIMCLUBS_DEFAULT_WHERE_CLAUSE)
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
		//  Add the columns in the display that you want to view.  The API is :
		//  Title, width, DB column name, field SORTABLE?, field SEARCHABLE?, align
		$this->add_header_item('Club or Pool',
	       	    '200', 'cluborpoolname', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Team Name',
	         	    '150', 'teamname', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('City',
	         	    '150', 'city', SORTABLE, SEARCHABLE, 'left') ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
	  	$this->add_header_item($label,
	         	    '150', 'stateorprovince', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Web Site',
	         	    '200', 'website', SORTABLE, SEARCHABLE, 'left') ;

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

	    $this->add_action_column('radio', 'FIRST', 'swimclubid') ;

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
            case 'Updated' :
                $obj = strftime('%Y-%m-%d @ %T', (int)$row_data['updated']) ;
                break ;
                */

            case 'Web Site' :
                $obj = html_a($row_data['website'], $row_data['website']) ;
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
 * on the various swimclubs.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamSwimClubsGUIDataList
 */
class SwimTeamSwimClubsAdminGUIDataList extends SwimTeamSwimClubsGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        ,WPST_ACTION_ADD => WPST_ACTION_ADD
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        //,WPST_ACTION_DELETE => WPST_ACTION_DELETE
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

		$this->add_header_item('Id',
	       	    '50', 'swimclubid', SORTABLE, SEARCHABLE, 'left') ;

    }
}

/**
 * Extended InfoTable Class for presenting Swimmer
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimClubProfileInfoTable extends SwimTeamInfoTable
{
    /**
     * swim club id property, used to query user information
     */
    var $__swimclubid = null ;

    /**
     * Set the swim club id
     *
     * @param int - the swim club id of the club profile to query
     */
    function setSwimClubId($id)
    {
        $this->__swimclubid = $id ;
    }

    /**
     * Get the swim club id
     *
     * @return int - the swim club id of the club profile to query
     */
    function getSwimClubId()
    {
        return $this->__swimclubid ;
    }

    /**
     * Build the InfoTable
     *
     */
    function constructSwimClubProfile($brief = false)
    {
        $this->set_alt_color_flag(true) ;
        $this->set_show_cellborders(true) ;

        if (is_null($this->getSwimClubId()))
        {
            $this->add_row('No swim club profile data.') ;
        }
        else
        {
            $sc = new SwimClubProfile() ;
            $sc->loadSwimClubBySwimClubId($this->getSwimClubId()) ;

            $this->add_row('Team Name', $sc->getTeamName()) ;
            $this->add_row('Club or Pool Name', $sc->getClubOrPoolName()) ;
            $this->add_row('Pool Length', $sc->getPoolLength() .
                ' ' . ucfirst($sc->getPoolMeasurementUnits()) .
                ' (' . $sc->getPoolLanes() . ' Lanes)') ;

            $address = $sc->getStreet1() ;
            if ($sc->getStreet2() != '')
                $address .= '<br/>' . $sc->getStreet2() ;
            if ($sc->getStreet3() != '')
                $address .= '<br/>' . $sc->getStreet3() ;

            $address .= '<br/>' . $sc->getCity() ;
            $address .= ', ' . $sc->getStateOrProvince() ;
            $address .= '<br/>' . $sc->getPostalCode() ;
            $address .= '<br/>' . $sc->getCountry() ;

            $this->add_row('Address', $address) ;

            $this->add_row('Primary Phone', $sc->getPrimaryPhone()) ;

            //  Brief profile?

            if ($brief) return ;

            $this->add_row('Secondary Phone', $sc->getSecondaryPhone()) ;
            $this->add_row('Contact Name', $sc->getContactName()) ;

            if ($sc->getEmailAddress() != WPST_NULL_STRING)
                $this->add_row('Email Address', html_a('mailto:' .
                    $sc->getEmailAddress(), $sc->getEmailAddress())) ;
            else
                $this->add_row('Email Address', WPST_NULL_STRING) ;

            if ($sc->getWebSite() != WPST_NULL_STRING)
                $this->add_row('Web Site',
                    html_a($sc->getWebSite(), $sc->getWebSite())) ;
            else
                $this->add_row('Web Site', WPST_NULL_STRING) ;

            if ($sc->getGoogleMapsURL() != WPST_NULL_STRING)
                $this->add_row('Google Maps URL',
                    html_a($sc->getGoogleMapsURL(), $sc->getGoogleMapsURL())) ;
            else
                $this->add_row('Google Maps URL', WPST_NULL_STRING) ;

            if ($sc->getMapQuestURL() != WPST_NULL_STRING)
                $this->add_row('MapQuest URL',
                    html_a($sc->getMapQuestURL(), $sc->getMapQuestURL())) ;
            else
                $this->add_row('MapQuest URL', WPST_NULL_STRING) ;

            if ($sc->getNotes() != WPST_NULL_STRING)
                $this->add_row('Notes', nl2br($sc->getNotes())) ;
            else
                $this->add_row('Notes', WPST_NULL_STRING) ;

            //  Only display the short code to users who can post

            global $userdata;
            get_currentuserinfo();

            if ($userdata->user_level > 0)
            { 
                $this->add_row('Short Codes',
                    sprintf('[wpst_club_profile clubid=%s [googlemap=\'y|yes|n|no\'] [mapquestmap=\'y|yes|n|no] [links=\'y|yes|n|no\']]',                   $sc->getSwimClubId())) ;
            }
        }
    }
}
?>
