<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * AgeGroup classes.
 *
 * $Id: agegroups.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage AgeGroups
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'class/db.class.php') ;
require_once(WPST_PATH . 'class/table.class.php') ;
require_once(WPST_PATH . 'include/agegroups.include.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the agegroups
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamAgeGroup extends SwimTeamDBI
{
    /**
     * id property - used for unique database identifier
     */
    var $__id ;

    /**
     * minAge property - the minimum age for the group
     */
    var $__minAge ;
    /**
     * maxAge property - the maximum age for the group
     */
    var $__maxAge ;

    /**
     * gender property - gender of the age group
     */
    var $__gender ;

    /**
     * swimmer id prefix property - swimmer id prefix for the age group
     */
    var $__swimmerlabelprefix ;

    /**
     * type - standard or combined age group
     */
    var $__type ;

    /**
     * registration fee property - registration fee for the age group
     */
    var $__registrationfee ;

    /**
     * Set the age group id
     *
     * @param - int - id of the age group
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the age group id
     *
     * @return - int - id of the age group
     */
    function getId()
    {
        return ($this->__id) ;
    }

    /**
     * Set the minAge of the age group
     *
     * @param - int - minimum age of group
     */
    function setMinAge($minAge)
    {
        $this->__minAge = $minAge ;
    }

    /**
     * Get the minAge of the age group
     *
     * @return - int - minimum age of the group
     */
    function getMinAge()
    {
        return ($this->__minAge) ;
    }

    /**
     * Set the maxAge of the age group
     *
     * @param - int - maximum age of group
     */
    function setMaxAge($maxAge)
    {
        $this->__maxAge = $maxAge ;
    }

    /**
     * Get the maxAge of the age group
     *
     * @return - int - maximum age of the group
     */
    function getMaxAge()
    {
        return ($this->__maxAge) ;
    }

    /**
     * Set the gender of the age group
     *
     * @param - string - gender of the age group
     */
    function setGender($gender)
    {
        $this->__gender = $gender ;
    }

    /**
     * Get the gender of the age group
     *
     * @return - string - gender of the age group
     */
    function getGender()
    {
        return ($this->__gender) ;
    }

    /**
     * Get the gender label of the age group
     *
     * @return - string - gender label of the age group
     */
    function getGenderLabel()
    {
        if ($this->__gender == WPST_GENDER_MALE)
            return get_option(WPST_OPTION_GENDER_LABEL_MALE) ;
        elseif ($this->__gender == WPST_GENDER_FEMALE)
            return get_option(WPST_OPTION_GENDER_LABEL_FEMALE) ;
        elseif ($this->__gender == WPST_GENDER_MIXED)
            return WPST_GENDER_MIXED ;
        else
            return null ;
    }

    /**
     * Set the type of the age group
     *
     * @param - string - type of the age group
     */
    function setType($type)
    {
        $this->__type = $type ;
    }

    /**
     * Get the type of the age group
     *
     * @return - string - type of the age group
     */
    function getType()
    {
        return ($this->__type) ;
    }

    /**
     * Set the registration fee of the age group
     *
     * @param - string - registration fee of the age group
     */
    function setRegistrationFee($fee)
    {
        $this->__registrationfee = $fee ;
    }

    /**
     * Get the registration fee of the age group
     *
     * @return - string - registration fee of the age group
     */
    function getRegistrationFee()
    {
        return ($this->__registrationfee) ;
    }

    /**
     * Set the swimmer id prefix of the age group
     *
     * @param - string - swimmer id prefix of the age group
     */
    function setSwimmerLabelPrefix($prefix)
    {
        $this->__swimmerlabelprefix = $prefix ;
    }

    /**
     * Get the swimmer id prefix of the age group
     *
     * @return - string - swimmer id prefix of the age group
     */
    function getSwimmerLabelPrefix()
    {
        return ($this->__swimmerlabelprefix) ;
    }

    /**
     *
     * Check if a age group already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of age group
     */
    function ageGroupExist()
    {
	    //  Is age group already in the database?

        $query = sprintf('SELECT id FROM %s WHERE type="%s" AND
            minage = "%s" AND maxage="%s" AND gender="%s"',
            WPST_AGE_GROUP_TABLE, $this->getType(), $this->getMinAge(),
            $this->getMaxAge(), $this->getGender()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure age group doesn't exist

        $ageGroupExists = (bool)($this->getQueryCount() > 0) ;

	    return $ageGroupExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of age group
     */
    function ageGroupExistById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

	    //  Is id already in the database?

        $query = sprintf('SELECT id FROM %s WHERE id = "%s"',
            WPST_AGE_GROUP_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     *
     * Check if an age group already exists in the database for the
     * min age, max age, and gender and return a boolean accordingly.
     *
     * @return - boolean - existance of age group
     */
    function ageGroupExistsByMinAgeMaxAgeAndGender()
    {
	    //  Is id already in the database?

        $query = sprintf('SELECT id FROM %s WHERE minage="%s" AND
            maxage="%s" AND gender="%s"', WPST_AGE_GROUP_TABLE,
            $this->getMinAge(), $this->getMaxAge(), $this->getGender()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     *
     * Check if a age group prefix already exists in
     * the database and return a boolean accordingly.
     * Only makes sense when age group prefixes are
     * being used.
     *
     * @param - string - optional prefix
     * @return - boolean - existance of age group
     */
    function ageGroupPrefixInUse($prefix = null)
    {
        //  Make sure prefixes are being used before checking

        $lf = get_option(WPST_DEFAULT_SWIMMER_LABEL_FORMAT) ;

        if (($lf == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            ($lf == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            if (is_null($prefix)) $prefix = $this->getSwimmerLabelPrefix() ;

	        //  Is prefix already in the database?

            $query = sprintf('SELECT id FROM %s WHERE
                swimmerlabelprefix = "%s"', WPST_AGE_GROUP_TABLE, $prefix) ;

            $this->setQuery($query) ;
            $this->runSelectQuery(false) ;

	        //  Make sure id doesn't exist

            $prefixExists = (bool)($this->getQueryCount() > 0) ;
        }
        else
        {
            $prefixExists = false ;
        }

	    return $prefixExists ;
    }

    /**
     * Add a new age group
     */
    function addAgeGroup()
    {
        $success = null ;

        //  Make sure the age group doesn't exist yet

        if (!$this->ageGroupExist())
        {
            //  Construct the insert query
 
            $query = sprintf('INSERT INTO %s SET 
                minage="%s",
                maxage="%s",
                gender="%s",
                swimmerlabelprefix="%s",
                type="%s",
                registrationfee="%s"',
                WPST_AGE_GROUP_TABLE,
                $this->getMinAge(),
                $this->getMaxAge(),
                $this->getGender(),
                $this->getSwimmerLabelPrefix(),
                $this->getType(),
                $this->getRegistrationFee()) ;

            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $success = $this->getInsertId() ;
        }

        return $success ;
    }

    /**
     * Update an age group
     */
    function updateAgeGroup()
    {
        $success = null ;

        //  Make sure the age group doesn't exist yet

        if (!$this->ageGroupExist() || !$this->ageGroupPrefixInUse())
        {
            //  Construct the insert query
 
            $query = sprintf('UPDATE %s SET
                minage="%s",
                maxage="%s",
                gender="%s",
                swimmerlabelprefix="%s",
                type="%s",
                registrationfee="%s"
                WHERE id="%s"',
                WPST_AGE_GROUP_TABLE,
                $this->getMinAge(),
                $this->getMaxAge(),
                $this->getGender(),
                $this->getSwimmerLabelPrefix(),
                $this->getType(),
                $this->getRegistrationFee(),
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
            //$success = $this->getInsertId() ;
        }
        else
        {
            wp_die('Something is wrong, age group not updated.') ;
            $success = false ;
        }

        return $success ;
        //return true ;
    }

    /**
     * Delete an age group
     */
    function deleteAgeGroup()
    {
        $success = null ;

        //  Make sure the age group doesn't exist yet

        if (!$this->ageGroupExist())
        {
            //  Construct the insert query
 
            $query = sprintf('DELETE FROM %s WHERE id="%s"',
                WPST_AGE_GROUP_TABLE, $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->ageGroupExistById() ;

        return $success ;
    }

    /**
     *
     * Load age group record by Id
     *
     * @param - string - optional age group id
     */
    function loadAgeGroupById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setId($id) ;

        //  Make sure it is a legal age group id
        if ($this->ageGroupExistById())
        {
            $query = sprintf('SELECT * FROM %s WHERE id = "%s"',
                WPST_AGE_GROUP_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setId($result['id']) ;
            $this->setMinAge($result['minage']) ;
            $this->setMaxAge($result['maxage']) ;
            $this->setGender($result['gender']) ;
            $this->setSwimmerLabelPrefix($result['swimmerlabelprefix']) ;
            $this->setType($result['type']) ;
            $this->setRegistrationFee($result['registrationfee']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Get age group text
     *
     * @return - string - complete text of age group
     */
    function getAgeGroupText()
    {
        if ($this->getMinAge() == get_option(WPST_OPTION_MIN_AGE))
            $text = sprintf('%s %s & Under', $this->getGender(), $this->getMaxAge()) ;
        else
            $text = sprintf('%s %s-%s', $this->getGender(), $this->getMinAge(), $this->getMaxAge()) ;

        $text = preg_replace('/' . WPST_GENDER_FEMALE . 's?/',
            get_option(WPST_OPTION_GENDER_LABEL_FEMALE) . 's', $text) ;
        $text = preg_replace('/' . WPST_GENDER_MALE . 's?/',
            get_option(WPST_OPTION_GENDER_LABEL_MALE) . 's', $text) ;

        $text = ucfirst($text) ;

        return $text ;
    }

    /**
     * Get age group by age and gender
     *
     * @param - int - age to find age group
     * @param - string - gender
     * @return - int - age group id
     */
    function getAgeGroupIdByAgeAndGender($age, $gender, $setid = false)
    {
        $query = sprintf('SELECT id FROM %s WHERE "%s" >= minage
            AND "%s" <= maxage AND gender="%s" AND type="%s"', WPST_AGE_GROUP_TABLE,
            $age, $age, $gender, WPST_STANDARD) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        $result = $this->getQueryResult() ;

        //  Only return a unique result

        $id = ($this->getQueryCount() == 1) ? $result['id'] : null ;

        if ($setid)
            $this->setId($id) ;

        return $id ;
    }

    /**
     * Get age group by min age, max age and gender
     *
     * @param - int - min age to find age group
     * @param - int - max age to find age group
     * @param - string - gender
     * @return - int - age group id
     */
    function getAgeGroupIdByMinAgeMaxAgeAndGender($minage, $maxage, $gender, $setid = false, $type = WPST_STANDARD)
    {
        $query = sprintf('SELECT id FROM %s WHERE minage="%s"
            AND maxage="%s" AND gender = "%s" AND type="%s"', WPST_AGE_GROUP_TABLE,
            $minage, $maxage, $gender, $type) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        //  Only return a unique result

        if ($this->getQueryCount() == 1)
        {
            $result = $this->getQueryResult() ;
            $id = $result['id'] ;
        }
        else
            $id = null ;

        if ($setid)
            $this->setId($id) ;

        if (!is_null($id))

        return $id ;
    }

    /**
     * Retrieve the Age Group Ids.
     *
     * @return - array - array of age group ids
     */
    function getAgeGroupIds($orderby = null)
    {
        //  Select the records for the age groups

        $query = sprintf('SELECT id FROM %s', WPST_AGE_GROUP_TABLE) ;

        if (!is_null($orderby))
            $query .= ' ORDER BY ' . $orderby ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Build query select clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildSelectClause()
    {
        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        $select_clause = sprintf(WPST_ROSTER_COUNT_COLUMNS, 
            $cutoffdate, $cutoffdate, $cutoffdate, WPST_STANDARD) ;

        return $select_clause ;
    }

    /**
     * Build query where clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildWhereClause($seasonId = null, $type = WPST_STANDARD)
    {
        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        $season = new SwimTeamSeason() ;

        if ($seasonId == null) 
            $seasonId = $season->getActiveSeasonId() ;

        //  On the off chance there isn't an active season
        //  set the season id to an invalid number so the SQL
        //  won't fail.
        
        if ($seasonId == null) $seasonId = -1 ;

        $where_clause = sprintf(WPST_ROSTER_COUNT_WHERE_CLAUSE, $seasonId,
            $cutoffdate, $cutoffdate, $cutoffdate, $cutoffdate, $cutoffdate,
            $cutoffdate, $type) ;

        return $where_clause ;
    }

    /**
     * construct the InfoTable
     *
     */
    function getAgeGroupSummary($seasonId = null)
    {
        //  Count the number of swimmers in each age group
 
        $a = new SwimTeamDBI() ;
        $a->setQuery(sprintf('SELECT %s FROM %s WHERE %s',
            $this->__buildSelectClause(), WPST_ROSTER_COUNT_TABLES,
            $this->__buildWhereClause($seasonId))) ;
        $a->runSelectQuery() ;

        $agc = $a->getQueryResults() ;

        //  Build a complete age group report for all defined
        //  age groups.  The prior query only reports age groups
        //  which contains swimmers - we need to account for the
        //  age groups which are unpopulated as well.
 
        $agegroups = $this->getAgeGroupIds('minage, maxage, gender') ;

        //  Make sure we have some data

        $totals = array(
            'swimmers' => 0
           ,WPST_GENDER_MALE => 0
           ,WPST_GENDER_FEMALE => 0
           ,'agegroups' => array()
        ) ;

        if (count($agegroups) > 0)
        {
            $agegrouptotals = &$totals['agegroups'] ;

            foreach ($agegroups as $agegroup)
            {
                $this->setId($agegroup['id']) ;
                $this->loadAgeGroupById() ;

                //  Only count standard age groups!
                if ($this->getType() == WPST_COMBINED) continue ;

                $agegroupcount = 0 ;

                for ($i = 0 ; $i < count($agc) ; $i++)
                {
                    if ($agc[$i]['agegroup'] == $this->getGender() . ' ' . $this->getMinAge() . ' - ' . $this->getMaxAge())
                    {
                        $agegroupcount = $agc[$i]['agegroupcount'] ;
                        break ;
                    }
                }

                $agegrouptotals[$agegroup['id']] = $agegroupcount ;

                //  Keep a running total of swimmers by gender and total count
                
                $totals[$this->getGender()] += $agegroupcount ;

                $totals['swimmers'] += $agegroupcount ;
            }

        }

        return $totals ;
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
class SwimTeamAgeGroupsGUIDataList extends SwimTeamGUIDataList
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
    function SwimTeamAgeGroupsGUIDataList($title, $width = '100%',
        $default_orderby='minage, gender', $default_reverseorder=FALSE,
        $columns = WPST_AGT_DEFAULT_COLUMNS,
        $tables = WPST_AGT_DEFAULT_TABLES,
        $where_clause = WPST_AGT_DEFAULT_WHERE_CLAUSE)
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
	  	$this->add_header_item('Gender',
	         	    '300', 'gender', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Minimum Age',
	       	    '150', 'minage', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Maximum Age',
	         	    '150', 'maxage', SORTABLE, SEARCHABLE, 'left') ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
	  	    $this->add_header_item('Swimmer Label Prefix',
	         	    '200', 'swimmerlabelprefix', SORTABLE, SEARCHABLE, 'left') ;
        }

	  	$this->add_header_item('Type',
	         	    '150', 'type', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Registration Fee',
	         	    '150', 'registrationfee', SORTABLE, SEARCHABLE, 'left') ;

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
        $na = array('Registration Fee', 'Swimmer Label Prefix') ;

		switch ($col_name)
        {
                /*
            case 'Updated' :
                $obj = strftime('%Y-%m-%d @ %T', (int)$row_data['updated']) ;
                break ;
                */

            case 'Gender' :
                switch ($row_data['gender'])
                {
                    case WPST_GENDER_MALE:
                        $obj = ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) ;
                        break ;

                    case WPST_GENDER_FEMALE:
                        $obj = ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) ;
                        break ;

                    case WPST_GENDER_MIXED:
                        $obj = ucfirst(WPST_GENDER_MIXED) ;
                        break ;

                    default:
                        $obj = 'Error - no gender' ;
                        break ;
                }
                break ;

            case 'Registration Fee' :
                if (($row_data['type'] == WPST_COMBINED) && in_array($col_name, $na))
			        $obj = strtoupper(WPST_NA) ;
                else
                    $obj = get_option(WPST_OPTION_REG_FEE_CURRENCY_LABEL) .  $row_data['registrationfee'] ;
                break ;

            case 'Type' :
                $obj = ucfirst($row_data['type']) ;
                break ;

 		    default:
                if (($row_data['type'] == WPST_COMBINED) && in_array($col_name, $na))
			        $obj = strtoupper(WPST_NA) ;
                else
			        $obj = DefaultGUIDataList::build_column_item($row_data, $col_name);
			    break;
		}
		return $obj;
    }
}

/**
 * GUIDataList class for performaing administration tasks
 * on the various age groups.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamAgeGroupsGUIDataList
 */
class SwimTeamAgeGroupsAdminGUIDataList extends SwimTeamAgeGroupsGUIDataList
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

        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', 'id') ;

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
 * Extended InfoTable Class for presenting SwimTeam
 * age group information as a table extracted from the database.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamAgeGroupInfoTable extends SwimTeamInfoTable
{
    /**
     * construct the InfoTable
     *
     */
    function constructAgeGroupInfoTable()
    {
        //  Alternate the row colors
        $this->set_alt_color_flag(true) ;

        //  Count the number of swimmers in each age group
 
        $a = new SwimTeamAgeGroup() ;
        $summary = $a->getAgeGroupSummary() ;

        //  Make sure we have some data

        if ($summary['swimmers'] > 0)
        {
            $this->add_row(html_b(__('Age Group')), html_b(__('Swimmers'))) ;

            $agegroups = &$summary['agegroups'] ;

            foreach ($agegroups as $key => $value)
            {
                $a->setId($key) ;
                $a->loadAgeGroupById() ;

                $this->add_row(__(ucfirst($a->getGenderLabel() . 's')) . ' ' .
                    $a->getMinAge() . ' - ' . $a->getMaxAge(), sprintf('%s', $value)) ;
            }

            foreach ($summary as $key => $value)
            {
                switch ($key)
                {
                    case 'swimmers' :
                        $this->add_row(html_b(__('Total ' . ucfirst($key))), html_b($value)) ;
                        break ;

                    case WPST_GENDER_MALE:
                        $label = get_option(WPST_OPTION_GENDER_LABEL_MALE) ;
                        if ($label === false) $label = WPST_OPTION_GENDER_LABEL_MALE ;
                        $this->add_row(html_b(__('Total ' . ucfirst($label) . 's')), html_b($value)) ;
                        break ;

                    case WPST_GENDER_FEMALE:
                        $label = get_option(WPST_OPTION_GENDER_LABEL_FEMALE) ;
                        if ($label === false) $label = WPST_OPTION_GENDER_LABEL_FEMALE ;
                        $this->add_row(html_b(__('Total ' . ucfirst($label) . 's')), html_b($value)) ;
                        break ;

                    default:
                        break ;
                }
            }
        }
        else
        {
            $this->add_row(html_b('No age groups defined.')) ;
        }
    }
}
?>
