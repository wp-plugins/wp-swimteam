<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * UserProfile classes.
 *
 * $Id: users.class.php 1078 2015-05-15 18:18:25Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage UserProfile
 * @version $Revision: 1078 $
 * @lastmodified $Date: 2015-05-15 14:18:25 -0400 (Fri, 15 May 2015) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'include/users.include.php') ;
require_once(WPST_PATH . 'class/db.class.php') ;
require_once(WPST_PATH . 'include/swimteam.include.php') ;
require_once(WPST_PATH . 'class/table.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;
require_once(WPST_PATH . 'class/options.class.php') ;

/**
 * Class definition of the agegroups
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamUserProfile extends SwimTeamDBI
{
    /**
     * id property - id
     */
    var $__id ;

    /**
     * user id property - WP user id
     */
    var $__userid ;

    /**
     * street1 property - street address field 1
     */
    var $__street1 ;

    /**
     * street2 property - street address field 2
     */
    var $__street2 ;

    /**
     * street3 property - street address field 3
     */
    var $__street3 ;

    /**
     * city property
     */
    var $__city ;

    /**
     * stateorprovince property
     */
    var $__stateorprovince ;

    /**
     * postal code property
     */
    var $__postalcode ;

    /**
     * country property
     */
    var $__country ;

    /**
     * primary phone property
     */
    var $__primaryphone ;

    /**
     * secondary phone property
     */
    var $__secondaryphone ;

    /**
     * contact infomation property
     */
    var $__contactinfo ;

    /**
     * user option field property
     */
    var $__user_option = array() ;

    /**
     * user profile record - used when reading data from database
     */
    var $__userProfileRecord ;

    /**
     * Set the id
     *
     * @param - string - id
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the id
     *
     * @return - string - id
     */
    function getId()
    {
        return ($this->__id) ;
    }

    /**
     * Set the userid
     *
     * @param - string - userid
     */
    function setUserId($userid)
    {
        $this->__userid = $userid ;
    }

    /**
     * Get the userid
     *
     * @return - string - userid
     */
    function getUserId()
    {
        return ($this->__userid) ;
    }

    /**
     * Set the street1 property
     *
     * @param - string - street address 1
     */
    function setStreet1($street1)
    {
        $this->__street1 = $street1 ;
    }

    /**
     * Get the street1 property
     *
     * @return - string - street address 1
     */
    function getStreet1()
    {
        return ($this->__street1) ;
    }

    /**
     * Set the street2 property
     *
     * @param - string - street address 2
     */
    function setStreet2($street2)
    {
        $this->__street2 = $street2 ;
    }

    /**
     * Get the street2 property
     *
     * @return - string - street address 2
     */
    function getStreet2()
    {
        return ($this->__street2) ;
    }

    /**
     * Set the street3 property
     *
     * @param - string - street address 3
     */
    function setStreet3($street3)
    {
        $this->__street3 = $street3 ;
    }

    /**
     * Get the street3 property
     *
     * @return - string - street address 3
     */
    function getStreet3()
    {
        return ($this->__street3) ;
    }

    /**
     * Get the full (concatenated) street address
     *
     * @return - string - concatenated street address
     */
    function getFullStreetAddress()
    {
        return $this->__street1 . ' ' .  $this->__street2 . ' ' . $this->__street3 ;
    }

    /**
     * Set the city property
     *
     * @param - string - city
     */
    function setCity($city)
    {
        $this->__city = $city ;
    }

    /**
     * Get the city property
     *
     * @return - string - city
     */
    function getCity()
    {
        return ($this->__city) ;
    }

    /**
     * Set the stateorprovince property
     *
     * @param - string - stateorprovince
     */
    function setStateOrProvince($stateorprovince)
    {
        $this->__stateorprovince = $stateorprovince ;
    }

    /**
     * Get the stateorprovince property
     *
     * @return - string - stateorprovince
     */
    function getStateOrProvince()
    {
        return ($this->__stateorprovince) ;
    }

    /**
     * Set the postal code property
     *
     * @param - string - postal code
     */
    function setPostalCode($postalcode)
    {
        $this->__postalcode = $postalcode ;
    }

    /**
     * Get the postal code property
     *
     * @return - string - postal code
     */
    function getPostalCode()
    {
        return ($this->__postalcode) ;
    }

    /**
     * Set the country property
     *
     * @param - string - country
     */
    function setCountry($country)
    {
        $this->__country = $country ;
    }

    /**
     * Get the country property
     *
     * @return - string - country
     */
    function getCountry()
    {
        return ($this->__country) ;
    }

    /**
     * Set the primary phone property
     *
     * @param - string - primary phone
     */
    function setPrimaryPhone($p)
    {
        $this->__primaryphone = $p;
    }

    /**
     * Get the primary phone property
     *
     * @return - string - primary phone
     */
    function getPrimaryPhone()
    {
        return ($this->__primaryphone) ;
    }

    /**
     * Set the secondary phone property
     *
     * @param - string - secondary phone
     */
    function setSecondaryPhone($p)
    {
        $this->__secondaryphone = $p;
    }

    /**
     * Get the secondary phone property
     *
     * @return - string - secondary phone
     */
    function getSecondaryPhone()
    {
        return ($this->__secondaryphone) ;
    }

    /**
     * Set the contact information of the user
     *
     * @param - string - contact infortmation of the user
     */
    function setContactInfo($contactinfo)
    {
        $this->__contactinfo = $contactinfo ;
    }

    /**
     * Get the contact information of the user
     *
     * @return - string - contact information of the user
     */
    function getContactInfo()
    {
        return ($this->__contactinfo) ;
    }

    /**
     * Set the user option
     *
     * @param - string - $option swim team option
     * @param - string - $value - value of option
     */
    function setUserOption($option, $value)
    {
        $this->__user_option[$option] = $value ;
    }

    /**
     * Get the user option
     *
     * @param - string - user option
     * @return - string - value of user option
     */
    function getUserOption($option)
    {
        //  In case option has never been used before
        //  return a null string if the array key does
        //  not exist.

        if ((!array_key_exists($option, $this->__user_option)) ||
            (is_null($this->__user_option[$option])))
        {
            switch (get_option($option))
            {
                case WPST_URL_REQUIRED:
                case WPST_URL_OPTIONAL:
                case WPST_EMAIL_REQUIRED:
                case WPST_EMAIL_OPTIONAL:
                case WPST_REQUIRED:
                case WPST_OPTIONAL:
                    $this->__user_option[$option] = WPST_NULL_STRING ;
                    break ;

                case WPST_YES_NO:
                    $this->__user_option[$option] = WPST_YES ;
                    break ;

                case WPST_NO_YES:
                    $this->__user_option[$option] = WPST_NO ;
                    break ;

                case WPST_DISABLED:
                default:
                    $this->__user_option[$option] = WPST_NULL_STRING ;
                    break ;
            }
        }

        return ($this->__user_option[$option]) ;
    }

    /**
     * Get first name - stored in the WP database tables
     *
     * @return string - first name
     */
    function getFirstName()
    {
        $u = get_userdata($this->getUserId()) ;

        return ($u !== false) ? $u->first_name : null ;
    }

    /**
     * Get last name - stored in the WP database tables
     *
     * @return string - last name
     */
    function getLastName()
    {
        $u = get_userdata($this->getUserId()) ;

        return ($u !== false) ? $u->last_name : null ;
    }

    /**
     * Get user name - stored in the WP database tables
     *
     * @return string - user name
     */
    function getUserName()
    {
        $u = get_userdata($this->getUserId()) ;

        return ($u !== false) ? $u->user_login : null ;
    }

    /**
     * Get full name - stored in the WP database tables
     *
     * @return string - full name
     */
    function getFullName()
    {
        $u = get_userdata($this->getUserId()) ;

        return ($u !== false) ? $u->first_name . ' ' . $u->last_name : null ;
    }

    /**
     * Get name e-mail address stored in the WP database tables
     *
     * @return string - e-mail address
     */
    function getEmailAddress()
    {
        $u = get_userdata($this->getUserId()) ;

        return ($u !== false) ? $u->user_email : null ;
    }

    /**
     * check if a record already exists
     * by unique id in the user profile table
     *
     * @return boolean - true if it exists, false otherwise
     */
    function userProfileExistsById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

        if (is_null($id)) return false ;

        $query = sprintf('SELECT id FROM %s WHERE id="%s"',
            WPST_USERS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * check if a record already exists
     * by WP user id in the user profile table
     *
     * @return boolean - true if it exists, false otherwise
     */
    function userProfileExistsByUserId($userid = null)
    {
        if (is_null($userid)) $userid = $this->getUserId() ;

        $query = sprintf('SELECT userid FROM %s WHERE userid="%s"',
            WPST_USERS_TABLE, $userid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

        return (bool)($this->getQueryCount()) ;
    }

    /**
     * load a user profile record by the userid
     *
     * @param - integer - option user id
     */
    function loadUserProfileByUserId($userid = null)
    {
        if (is_null($userid)) $userid = $this->getUserId() ;

        if (is_null($userid))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Id')) ;
        $query = sprintf('SELECT * FROM %s WHERE userid = "%s"',
            WPST_USERS_TABLE, $userid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        //  Make sure we only selected one record

        if ($this->getQueryCount() == 1)
        {
            $this->__userProfileRecord = $this->getQueryResult() ;

            //  Short cut to save typing ... 

            $u = &$this->__userProfileRecord ;

            $this->setId($u['id']) ;
            $this->setUserId($u['userid']) ;
            $this->setStreet1($u['street1']) ;
            $this->setStreet2($u['street2']) ;
            $this->setStreet3($u['street3']) ;
            $this->setCity($u['city']) ;
            $this->setStateOrProvince($u['stateorprovince']) ;
            $this->setPostalCode($u['postalcode']) ;
            $this->setCountry($u['country']) ;
            $this->setPrimaryPhone($u['primaryphone']) ;
            $this->setSecondaryPhone($u['secondaryphone']) ;
            $this->setContactInfo($u['contactinfo']) ;

            //  How many user options does this configuration support?

            $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

            $ometa = new SwimTeamOptionMeta() ;
            $ometa->setUserId($this->getUserId()) ;

            //  Load the user options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;

                if (get_option($oconst) != WPST_DISABLED)
                {
                    $ometa->loadOptionMetaByUserIdAndKey($this->getUserId(), $oconst) ;
                    $this->setUserOption($oconst, $ometa->getOptionMetaValue()) ;
                }
            }
        }
        else
        {
            $this->setId(null) ;
            $this->setUserId($userid) ;
            $this->setStreet1(null) ;
            $this->setStreet2(null) ;
            $this->setStreet3(null) ;
            $this->setCity(null) ;
            $this->setStateOrProvince(null) ;
            $this->setPostalCode(null) ;
            $this->setCountry(null) ;
            $this->setPrimaryPhone(null) ;
            $this->setSecondaryPhone(null) ;
            $this->setContactInfo(null) ;
            $this->__userProfileRecord = null ;
        }

        return ($this->getQueryCount() == 1) ;
    }

    /**
     * save a user profile record by the userid
     *
     * @return - integer - insert id
     */
    function saveUserProfile()
    {
        $success = false ;

        if (is_null($this->getUserId()))
			die(sprintf('%s(%s):  %s', basename(__FILE__), __LINE__, 'Null Id')) ;
        //  Update or new save?
 
        if ($this->userProfileExistsByUserId())
            $query = sprintf('UPDATE %s ', WPST_USERS_TABLE) ;
        else
            $query = sprintf('INSERT INTO %s ', WPST_USERS_TABLE) ;

        $query .= sprintf('SET 
            userid="%s",
            street1="%s",
            street2="%s",
            street3="%s",
            city="%s",
            stateorprovince="%s",
            postalcode="%s",
            country="%s",
            primaryphone="%s",
            secondaryphone="%s",
            contactinfo="%s"',
            $this->getUserId(),
            $this->getStreet1(),
            $this->getStreet2(),
            $this->getStreet3(),
            $this->getCity(),
            $this->getStateOrProvince(),
            $this->getPostalCode(),
            $this->getCountry(),
            $this->getPrimaryPhone(),
            $this->getSecondaryPhone(),
            $this->getContactInfo()) ;

        //  Query is processed differently for INSERT and UPDATE

        if ($this->userProfileExistsByUserId())
        {
            $query .= sprintf(' WHERE userid="%s"', $this->getUserId()) ;

            $this->setQuery($query) ;
            $success |= $this->runUpdateQuery() ;
        }
        else
        {
            $this->setQuery($query) ;
            $this->runInsertQuery() ;
            $this->setId($this->getInsertId()) ;
            $success |= $this->getInsertId() ;
        }
 
        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        $ometa = new SwimTeamOptionMeta() ;
        $ometa->setUserId($this->getUserId()) ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $ometa->setOptionMetaKey($oconst) ;
                $ometa->setOptionMetaValue($this->getUserOption($oconst)) ;
                $success |= $ometa->saveUserOptionMeta() ;
            }
        }

        return $success ;
    }

    /**
     * Retrieve the User Ids for the web site users.
     *
     * @return - array - array of user ids
     */
    function getUserIds($orderbyusername = false,
        $orderbylastcommafirst = true, $filter = '')
    {
        //  Select the records for the season

        $options_count = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        //  If the options count is zero or non-existant, don't reference
        //  the meta table because it will result in an empty set being returned.

        //if (($options_count === false) || ((int)$options_count === 0))
        //{
        //    $query = sprintf('SELECT DISTINCT u.ID as userid FROM %s', WPST_USERS_TABLES) ;
        //}
        //else
        //{
        //    $query = sprintf('SELECT DISTINCT u.ID as userid FROM %s, %s',
        //        WPST_OPTIONS_META_TABLE, WPST_USERS_TABLES) ;
        //}

        //  Need to handle WordPress Multi-site
        //  Get information about this blog's users into an array.
        $bloguserids = get_users(array('blog_id' => get_current_blog_id(), 'fields' => 'ID') );
        //error_log(print_r($bloguserids, true)) ;

        $query = sprintf('SELECT DISTINCT u.id AS userid FROM %susers u
            LEFT JOIN %susermeta m1 ON (m1.user_id = u.ID AND m1.meta_key = \'first_name\')
            LEFT JOIN %susermeta m2 ON (m2.user_id = u.ID AND m2.meta_key = \'last_name\')
            LEFT JOIN %s m ON (m.userid = u.id)', WP_DB_BASE_PREFIX, WP_DB_BASE_PREFIX,
                WP_DB_BASE_PREFIX, WPST_OPTIONS_META_TABLE) ;

        //  Filter?

        if (!empty($filter))
            $query .= sprintf(' WHERE u.id IN (%s) AND %s', join(',', $bloguserids), $filter) ;
            //$query .= sprintf(' WHERE %s', $filter) ;
        else
            $query .= sprintf(' WHERE u.id IN (%s)', join(',', $bloguserids)) ;

        //  Custom ordering?
      
        if ($orderbyusername)
            $orderby = ' ORDER BY user_login' ;
        else if ($orderbylastcommafirst)
            //$orderby = ' ORDER BY lastname, firstname' ;
            $orderby = ' ORDER BY m2.meta_value, m1.meta_value' ;
        else
            $orderby = ' ORDER BY userid' ;

        $query .= $orderby ;

        error_log(print_r($query, true)) ;

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
class SwimTeamUsersGUIDataList extends SwimTeamGUIDataList
{
    /**
     * Property to store the requested action
     */
    var $__action ;

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
    function SwimTeamUsersGUIDataList($title, $width = '100%',
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_USERS_DEFAULT_COLUMNS,
        $tables = WPST_USERS_DEFAULT_TABLES,
        $where_clause = WPST_USERS_DEFAULT_WHERE_CLAUSE)
    {
        //  Set the properties for this child class
        //$this->setColumns($columns) ;
        //$this->setTables($tables) ;
        //$this->setWhereClause($where_clause) ;

        //  Running in multi-site mode?  If so, need to limit
        //  list of users to those allowed to access the blog.

        if (is_multisite())
        {
            $blog_users = get_users(array('blogid' => get_current_blog_id(), 'fields' => 'ID')) ;
            //printf("<pre>%s</pre>", print_r($blog_users, true)) ;

            foreach ($blog_users as $blog_user)
                $where_clause .= sprintf('%su.ID="%s"',
                    empty($where_clause) ? '' : ' OR ', $blog_user) ;
        }

        //  Call the constructor of the parent class
        $this->SwimTeamGUIDataList($title, $width,
            $default_orderby, $default_reverseorder,
            $columns, $tables, $where_clause) ;

        //printf("<pre>%s</pre>", print_r($title, true)) ;
        //printf("<pre>%s</pre>", print_r($width, true)) ;
        //printf("<pre>%s</pre>", print_r($default_orderby, true)) ;
        //printf("<pre>%s</pre>", print_r($default_reverseorder, true)) ;
        //printf("<pre>%s</pre>", print_r($columns, true)) ;
        //printf("<pre>%s</pre>", print_r($tables, true)) ;
        //printf("<pre>%s</pre>", print_r($where_clause, true)) ;
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
	  	//$this->add_header_item('ID',
	    //     	    '200', 'id', SORTABLE, SEARCHABLE, 'left') ;

  	  	$this->add_header_item('First Name',
	         	    '200', 'm1.meta_value', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Last Name',
	         	    '200', 'm2.meta_value', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Username',
	         	    '200', 'user_login', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Swimmers',
	         	    '200', 'swimmers', SORTABLE, NOT_SEARCHABLE, 'left') ;
 
        //  Construct the DB query
        $this->_datasource->setup_db_options($this->getColumns(),
            $this->getTables(), $this->getWhereClause()) ;
        //  Need this because of our complex query for users and their swimmers
        $this->_datasource->set_count_column('DISTINCT u.ID') ;

        $this->_collapsable_search = true ;
        //  turn on the 'collapsable' search block.
        //  The word 'Search' in the output will be clickable,
        //  and hide/show the search box.

        $this->_collapsable_search = true ;

        //  lets add an action column of checkboxes,
        //  and allow us to save the checked items between pages.
	    //  Use the last field for the check box action.

        //  The unique item is the second column.

	    $this->add_action_column('radio', 'FIRST', 'userid') ;

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
        //  Need to the user data from the Wordpress user profile

		switch ($col_name)
        {
            case 'First Name' :
                $obj = $row_data['firstname'] ;

                if (empty($obj))
                    $obj = get_user_meta($row_data['userid'], 'first_name') ;

                if (is_array($obj) && !empty($obj)) $obj = $obj[0] ;

                if (empty($obj))
                    $obj = strtoupper(WPST_NA) ;

                break ;

            case 'Last Name' :
                $obj = $row_data['lastname'] ;

                if (empty($obj))
                    $obj = get_user_meta($row_data['userid'], 'last_name') ;

                if (is_array($obj) && !empty($obj)) $obj = $obj[0] ;

                if (empty($obj))
                    $obj = strtoupper(WPST_NA) ;

                break ;

            case 'Username' :
                $obj = $row_data['user_login'] ;

                if (empty($obj))
                {
                    $u = get_userdata($row_data['userid']) ;
                    $obj = $u->user_login ;
                }

                if (empty($obj))
                    $obj = strtoupper(WPST_NA) ;

                break ;

            case 'Swimmers' :
                if ($row_data['swimmers'] == 'no')
                    $obj = __(ucfirst(WPST_NO)) ;
                else
                    $obj = __(ucfirst(WPST_YES)) ;
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
 * on the various swimmers.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamUsersGUIDataList
 */
class SwimTeamUsersAdminGUIDataList extends SwimTeamUsersGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        ,WPST_ACTION_JOBS => WPST_ACTION_JOBS
        ,WPST_ACTION_EXPORT_CSV => WPST_ACTION_EXPORT_CSV
    ) ;
}

/**
 * Extended InfoTable Class for presenting SwimTeam
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamUserProfileInfoTable extends SwimTeamInfoTable
{
    /**
     * id property, used to query user information
     */
    var $__id = null ;

    /**
     * Set the id
     *
     * @param int - the id of the user profile to query
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the id
     *
     * @return int - the id of the user profile to query
     */
    function getId()
    {
        return $this->__id ;
    }

    /**
     * Build the InfoTable
     *
     */
    function buildProfile()
    {
        if (is_null($this->getId()))
        {
            $this->add_row('No data.') ;
        }
        else
        {
            $u = get_userdata($this->getId()) ;
            $this->add_row('Name', $u->first_name . ' ' . $u->last_name) ;
            $this->add_row('Username', $u->user_login) ;

            $p = new SwimTeamUserProfile() ;
            $p->loadUserProfileByUserId($this->getId()) ;

            $this->add_row(html_b('Contact Information'), '&nbsp;') ;

            $address = $p->getStreet1() ;
            if ($p->getStreet2() != '')
                $address .= '<br/>' . $p->getStreet2() ;
            if ($p->getStreet3() != '')
                $address .= '<br/>' . $p->getStreet3() ;

            $address .= '<br/>' . $p->getCity() ;
            $address .= ', ' . $p->getStateOrProvince() ;
            $address .= '<br/>' . $p->getPostalCode() ;
            $address .= '<br/>' . $p->getCountry() ;

            $this->add_row('Address', $address) ;

            $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
            $this->add_row($label, $p->getPrimaryPhone()) ;

            $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
            if ($p->getSecondaryPhone() != '')
                $this->add_row($label, $p->getSecondaryPhone()) ;
            else
                $this->add_row($label, __('N/A')) ;

            $this->add_row('E-mail', $u->user_email) ;

            //  Report Optional Fields

            //  How many user options does this configuration support?

            $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

            $ometa = new SwimTeamOptionMeta() ;
            $ometa->setUserId($this->getId()) ;

            //  Load the user options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
                $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
                if (get_option($oconst) != WPST_DISABLED)
                {
                    $label = get_option($lconst) ;
                    $ometa->loadOptionMetaByUserIdAndKey($this->getId(), $oconst) ;
                    $this->add_row($label, $ometa->getOptionMetaValue()) ;
                }
            }
        }
    }
}
?>
