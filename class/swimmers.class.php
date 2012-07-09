<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swimmer classes.
 *
 * $Id: swimmers.class.php 956 2012-07-07 04:38:51Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Swimmers
 * @version $Revision: 956 $
 * @lastmodified $Date: 2012-07-07 00:38:51 -0400 (Sat, 07 Jul 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('db.class.php') ;
require_once('swimmers.include.php') ;
require_once('users.class.php') ;
require_once('agegroups.class.php') ;
require_once('table.class.php') ;
require_once('widgets.class.php') ;
require_once('options.class.php') ;

/**
 * Class definition of the agegroups
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamSwimmer extends SwimTeamDBI
{
    /**
     * id property - used for unique database identifier
     */
    var $__id ;

    /**
     * primary contact id property - id of primary contact
     */
    var $__contact1Id ;

    /**
     * secondary contact id property - id of secondary contact
     */
    var $__contact2Id ;

    /**
     * wp user id property - the WP user id of the swimmer
     */
    var $__wpUserId ;

    /**
     * first name property - first name of the swimmer
     */
    var $__firstName ;

    /**
     * middle name property - nick name of the swimmer
     */
    var $__middleName ;

    /**
     * nick name property - nick name of the swimmer
     */
    var $__nickName ;

    /**
     * last name property - last name of the swimmer
     */
    var $__lastName ;

    /**
     * date of birth property - birth date
     */
    var $__dateOfBirth ;

    /**
     * age property - age of the swimmer
     */
    var $__age ;

    /**
     * age group age property - age of the swimmer relative to age group
     */
    var $__agegroupage ;

    /**
     * gender property - gender of the swimmer
     */
    var $__gender ;

    /**
     * status property - status of the swimmer
     */
    var $__status ;

    /**
     * results property - results of the swimmer
     */
    var $__results ;

    /**
     * swimmer option field property
     */
    var $__swimmer_option = array() ;

    /**
     * age group object
     */
    var $__agegroup = null ;

    /**
     * Set the swimmer id
     *
     * @param - int - id of the swimmer
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the swimmer id
     *
     * @return - int - id of the swimmer
     */
    function getId()
    {
        return ($this->__id) ;
    }

    /**
     * Set the swimmer id
     *
     * @param - int - id of the swimmer
     */
    function setSwimmerId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the swimmer id
     *
     * @return - int - id of the swimmer
     */
    function getSwimmerId()
    {
        return ($this->__id) ;
    }

    /**
     * Set the contact1Id of the swimmer
     *
     * @param - int - minimum age of group
     */
    function setContact1Id($contact1Id)
    {
        $this->__contact1Id = $contact1Id ;
    }

    /**
     * Get the contact1Id of the swimmer
     *
     * @return - int - minimum age of the group
     */
    function getContact1Id()
    {
        return ($this->__contact1Id) ;
    }

    /**
     * Set the contact2Id of the swimmer
     *
     * @param - int - maximum age of group
     */
    function setContact2Id($contact2Id)
    {
        $this->__contact2Id = $contact2Id ;
    }

    /**
     * Get the contact2Id of the swimmer
     *
     * @return - int - maximum age of the group
     */
    function getContact2Id()
    {
        return ($this->__contact2Id) ;
    }

    /**
     * Set the WP user id of the swimmer
     *
     * @param - int - swimmer's WP user id
     */
    function setWPUserId($id)
    {
        $this->__wpUserId = $id ;
    }

    /**
     * Get the WP user id of the swimmer
     *
     * @return - int - swimmer's WP user id
     */
    function getWPUserId()
    {
        return ($this->__wpUserId) ;
    }

    /**
     * Set the gender of the swimmer
     *
     * @param - string - gender of the swimmer
     */
    function setGender($gender)
    {
        $this->__gender = $gender ;
    }

    /**
     * Get the gender of the swimmer
     *
     * @return - string - gender of the swimmer
     */
    function getGender()
    {
        return ($this->__gender) ;
    }

    /**
     * Set the swimmer option
     *
     * @param - string - $option swim team option
     * @param - string - $value - value of option
     */
    function setSwimmerOption($option, $value)
    {
        $this->__swimmer_option[$option] = $value ;
    }

    /**
     * Get the swimmer option
     *
     * @param - string - swimmer option
     * @return - string - value of swimmer option
     */
    function getSwimmerOption($option)
    {
        //  In case option has never been used before
        //  return a null string if the array key does
        //  not exist.

        if ((!array_key_exists($option, $this->__swimmer_option)) ||
            (is_null($this->__swimmer_option[$option])))
        {
            switch (get_option($option))
            {
                case WPST_URL_REQUIRED:
                case WPST_URL_OPTIONAL:
                case WPST_EMAIL_OPTIONAL:
                case WPST_EMAIL_REQUIRED:
                    $this->__swimmer_option[$option] = WPST_NULL_STRING ;
                    break ;

                case WPST_YES_NO:
                    $this->__swimmer_option[$option] = WPST_YES ;
                    break ;

                case WPST_NO_YES:
                    $this->__swimmer_option[$option] = WPST_NO ;
                    break ;

                case WPST_DISABLED:
                default:
                    $this->__swimmer_option[$option] = WPST_NULL_STRING ;
                    break ;
            }
        }

        return ($this->__swimmer_option[$option]) ;
    }

    /**
     * Set the status of the swimmer
     *
     * @param - string - status of the swimmer
     */
    function setStatus($status)
    {
        $this->__status = $status ;
    }

    /**
     * Get the status of the swimmer
     *
     * @return - string - status of the swimmer
     */
    function getStatus()
    {
        $status = WPST_INACTIVE ;

        //  Status is based on the active season roster

        $roster = new SwimTeamRoster() ;
        $roster->setSeasonId($roster->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->getSwimmerId()) ;
        $roster->loadRosterBySeasonIdAndSwimmerId() ;

        $status = $roster->getRosterStatus() ;

        if (empty($status))
        {
            $status = WPST_INACTIVE ;
        }

        return $status ;
    }

    /**
     * Set the results of the swimmer
     *
     * @param - string - results of the swimmer
     */
    function setResults($results)
    {
        $this->__results = $results ;
    }

    /**
     * Get the results of the swimmer
     *
     * @return - string - results of the swimmer
     */
    function getResults()
    {
        return ($this->__results) ;
    }

    /**
     * Set the first name of the swimmer
     *
     * @param - string - first name of the swimmer
     */
    function setFirstName($firstName)
    {
        $this->__firstName = $firstName ;
    }

    /**
     * Get the first name of the swimmer
     *
     * @return - string - first name of the swimmer
     */
    function getFirstName()
    {
        return ($this->__firstName) ;
    }

    /**
     * Set the middle name of the swimmer
     *
     * @param - string - middle name of the swimmer
     */
    function setMiddleName($middleName)
    {
        $this->__middleName = $middleName ;
    }

    /**
     * Get the middle name of the swimmer
     *
     * @return - string - middle name of the swimmer
     */
    function getMiddleName()
    {
        return ($this->__middleName) ;
    }

    /**
     * Get the middle initial of the swimmer
     *
     * @return - string - middle initial of the swimmer
     */
    function getMiddleInitial()
    {
        return substr($this->getMiddleName(), 0, 1) ;
    }

    /**
     * Set the nick name of the swimmer
     *
     * @param - string - nick name of the swimmer
     */
    function setNickName($nickName)
    {
        $this->__nickName = $nickName ;
    }

    /**
     * Get the nick name of the swimmer
     *
     * @return - string - nick name of the swimmer
     */
    function getNickName()
    {
        return ($this->__nickName) ;
    }

    /**
     * Set the last name of the swimmer
     *
     * @param - string - last name of the swimmer
     */
    function setLastName($lastName)
    {
        $this->__lastName = $lastName ;
    }

    /**
     * Get the last name of the swimmer
     *
     * @return - string - last name of the swimmer
     */
    function getLastName()
    {
        return ($this->__lastName) ;
    }

    /**
     * Get the first and last names of the swimmer
     *
     * @return - string - first and last names of the swimmer
     */
    function getFirstAndLastNames($useNickName = WPST_NO)
    {
        if ($useNickName == WPST_YES)
        {
            if ($this->getNickName() == WPST_NULL_STRING)
                $first = $this->getFirstName() ;
            else
                $first = $this->getNickName() ;
        }
        else
        {
            $first = $this->getFirstName() ;
        }

        return ($first . ' ' . $this->getLastName()) ;
    }

    /**
     * Get the first and last names of the swimmer
     *
     * @return - string - first and last names of the swimmer
     */
    function getLastCommaFirstNames($useNickName = WPST_NO)
    {
        $first = $this->getFirstName() ;

        if (($useNickName == WPST_YES) &&
            ($this->getNickName() != WPST_NULL_STRING))
                $first = $this->getNickName() ;

        return ($this->getLastName() . ', ' . $first) ;
    }

    /**
     * Set the date of birth
     *
     * @param - array - start of season date
     */
    function setDateOfBirth($dob)
    {
        $this->__dateOfBirth = $dob ;
    }

    /**
     * Set the date of birth
     *
     * @param - array - date of birth
     */
    function setDateOfBirthAsDate($dob)
    {
        $this->__dateOfBirth = array('year' => date('Y', strtotime($dob)),
           'month' => date('m', strtotime($dob)), 'day' => date('d', strtotime($dob))) ;
    }

    /**
     * Get the date of birth
     *
     * @return - array - date of birth as an array
     */
    function getDateOfBirth()
    {
        return ($this->__dateOfBirth) ;
    }

    /**
     * Get the date of birth
     *
     * @return - string - start of season date as a string
     */
    function getDateOfBirthAsDate()
    {
        $d = &$this->__dateOfBirth ;

        return sprintf('%04s-%02s-%02s', $d['year'], $d['month'], $d['day']) ;
    }

    /**
     * Get the date of birth as MMDDYYYY
     *
     * @return - string - date of birth as MMDDYYYY
     */
    function getDateOfBirthAsMMDDYYYY()
    {
        $d = &$this->__dateOfBirth ;

        return sprintf('%02s%02s%04s', $d['month'], $d['day'], $d['year']) ;
    }

    /**
     * Get the date of birth as MMDDYY
     *
     * @return - string - date of birth as MMDDYY
     */
    function getDateOfBirthAsMMDDYY($delimiter = '')
    {
        $d = &$this->__dateOfBirth ;

        return sprintf('%02s%s%02s%s%02s', $d['month'],
            $delimiter, $d['day'], $delimiter, substr($d['year'], 2, 2)) ;
    }

    /**
     * Get the date of birth as YYYYMMDD
     *
     * @parameter - string - optional delimiter
     * @return - string - date of birth as YYYYMMDD
     */
    function getDateOfBirthAsYYYYMMDD($delimiter = '')
    {
        $d = &$this->__dateOfBirth ;

        return sprintf('%04s%s%02s%s%02s', $d['year'],
            $delimiter, $d['month'], $delimiter, $d['day']) ;
    }

    /**
     * Set the age
     *
     * @param - int - swimmer age
     */
    function setAge($age)
    {
        $this->__age = $age ;
    }

    /**
     * Get the age
     *
     * @return - int - age of swimmer
     */
    function getAge()
    {
        return ($this->__age) ;
    }

    /**
     * Set the age group relative age
     *
     * @param - int - swimmer age relative to age group
     */
    function setAgeGroupAge($agegroupage)
    {
        $this->__agegroupage = $agegroupage ;
    }

    /**
     * Get the age group relative age
     *
     * @return - int - age of swimmer relative to age group
     */
    function getAgeGroupAge()
    {
        return ($this->__agegroupage) ;
    }

    /**
     * Get the age group relative age
     *
     * @return - int - age of swimmer relative to age group
     */
    function getAgeGroupText()
    {
        // Determine the age groyup 

        if (is_null($this->__agegroup))
            $this->__agegroup = new SwimTeamAgeGroup() ;

        $agegroup = &$this->__agegroup ;

        $id = $agegroup->getAgeGroupIdByAgeAndGender($this->getAgeGroupAge(),
            $this->getGender(), true) ;

        if (is_null($id))
        {
            $text = WPST_NONE ;
        }
        else if ($agegroup->ageGroupExistById())
        {
            $agegroup->loadAgeGroupById() ;
            $text = $agegroup->getAgeGroupText() ;
        }
        else
            $text = WPST_NONE ;

        return $text ;
    }

    /**
     * Calculate the swimmer age
     *
     * @param - string - optional date to base calculation on
     * @return - int - age of swimmer
     */
    function calculateAge($birthdate = null)
    {
        if (is_null($birthdate))
            list($year, $month, $day) = explode('-', $this->getDateOfBirthAsDate()) ;
        else
            list($year, $month, $day) = explode('-', $birthdate) ;

        $year_diff = date('Y') - $year ;

        if (date('m') < $month || (date('m') == $month && date('d') < $day))
            $year_diff-- ;

        return $year_diff ;
    }

    /**
     * Calculate swimmer age adjusted for cutoff
     *
     * @param - string - optional date to base calculation on
     * @return - int - relative age of swimmer
     */
    function calculateAdjustedAge($birthdate = null)
    {
        if (is_null($birthdate))
            list($year, $month, $day) = explode('-', $this->getDateOfBirthAsDate()) ;
        else
            list($year, $month, $day) = explode('-', $birthdate) ;

        /*
        $year_diff = date('Y') - $year ;

        if (date('m') < $month || (get_option(WPST_OPTION_AGE_CUTOFF_MONTH) == $month && get_option(WPST_OPTION_AGE_CUTOFF_DAY) < $day))
            $year_diff-- ;
         */

        $adjustedage = date('Y') - $year -
            (get_option(WPST_OPTION_AGE_CUTOFF_MONTH) < $month) -
            ((get_option(WPST_OPTION_AGE_CUTOFF_MONTH) == $month) &
            (get_option(WPST_OPTION_AGE_CUTOFF_DAY) <= $day)) ;

        return $adjustedage ;
    }

    /**
     * Get the USS number
     *
     * @return - string - USS number
     */
    function getUSSNumber()
    {
        $d = &$this->__dateOfBirth ;

        $uss = strtoupper(sprintf('%02s%02s%02s%-3s%1s%-4s',
            substr($d['year'], 2, 2), $d['month'], $d['day'],
            substr($this->getFirstName(), 0, 3),
            substr($this->getMiddleName(), 0, 1),
            substr($this->getLastName(),0, 4))) ;

        //  Replace and space characters with an asterisk

        $uss = preg_replace('/ /', '*', $uss) ;

        return $uss ;
    }

    /**
     * Get the old format USS number
     *
     * @return - string - old format USS number
     */
    function getOldFormatUSSNumber($lsc = '')
    {
        $d = &$this->__dateOfBirth ;

        $uss = strtoupper(sprintf('%1s%02s%1s%1s%1s%02s%2s%2s',
            $lsc, substr($d['year'], 2, 2),
            substr($this->getFirstName(), 0, 1),
            substr($this->getMiddleName(), 0, 1),
            substr($this->getLastName(),0, 1),
            $d['month'],
            $d['day'],
            substr($d['year'], 2, 2))) ;

        //  Replace and space characters with an asterisk

        $uss = preg_replace('/ /', '*', $uss) ;

        return $uss ;
    }

    /**
     *
     * Check if a swimmer already exists in the database
     * and return a boolean accordingly.
     *
     * @return - boolean - existance of swimmer
     */
    function swimmerExist($exact = false)
    {
        //  Is swimmer already in the database?  Swimmer existance is
        //  somewhat loose, match either contact with the current user
        //  and first name, last name and gender.  Unlikely two swimmers
        //  will have same parents and names but different birthday.

        if ($exact)
        {
            $query = sprintf('SELECT id FROM %s
                WHERE contact1id = "%s"
                AND contact2id="%s"
                AND wpuserid="%s"
                AND firstname="%s" AND
                middlename="%s" AND
                nickname="%s" AND
                lastname="%s" AND
                gender="%s" AND
                birthdate="%s" AND
                results="%s"',
                WPST_SWIMMERS_TABLE,
                $this->getContact1Id(),
                $this->getContact2Id(),
                $this->getWPUserId(),
                $this->getFirstName(),
                $this->getMiddleName(),
                $this->getNickName(),
                $this->getLastName(),
                $this->getGender(),
                $this->getDateOfBirthAsDate(),
                $this->getResults()
            ) ;
        }
        else
        {
            $query = sprintf('SELECT id FROM %s WHERE (contact1id = "%s" OR
                contact2id="%s") AND (firstname="%s" AND lastname="%s"
                AND gender="%s")', WPST_SWIMMERS_TABLE,
                $this->getContact1Id(), $this->getContact2Id(),
                $this->getFirstName(), $this->getLastName(),
                $this->getGender()) ;
        }

        //  Keep the query result around so it can be used if needed
 
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure swimmer doesn't exist

        $swimmerExists = (bool)($this->getQueryCount() > 0) ;

	    return $swimmerExists ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of swimmer
     */
    function swimmerExistById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

	    //  Is id already in the database?

        $query = sprintf('SELECT id FROM %s WHERE id = "%s"',
            WPST_SWIMMERS_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Add a new swimmer
     */
    function addSwimmer()
    {
        $success = false ;

        //  Make sure the swimmer doesn't exist yet

        if (!$this->swimmerExist())
        {
            //  Construct the insert query
 
            $query = sprintf('INSERT INTO %s SET
                firstname="%s",
                middlename="%s",
                nickname="%s",
                lastname="%s",
                contact1id="%s",
                contact2id="%s",
                wpuserid="%s",
                gender="%s",
                results="%s",
                birthdate="%s",
                status="%s"',
                WPST_SWIMMERS_TABLE,
                $this->getFirstName(),
                $this->getMiddleName(),
                $this->getNickName(),
                $this->getLastName(),
                $this->getContact1Id(),
                $this->getContact2Id(),
                $this->getWPUserId(),
                $this->getGender(),
                $this->getResults(),
                $this->getDateOfBirthAsDate(),
                $this->getStatus()
            ) ;

            $this->setQuery($query) ;
            $success = $this->runInsertQuery() ;

            //  Save the swimmer option data
            if ($success)
            {
                $this->setId($success) ;
                $metasuccess = $this->saveSwimmerOptionMeta() ;
            }
        }

        return $success ;
    }

    /**
     * Update an swimmer
     */
    function updateSwimmer()
    {
        $success = false ;

        //  Make sure the swimmer doesn't exist yet

        if ($this->swimmerExistById())
        {
            //  Construct the insert query
 
            $query = sprintf('UPDATE %s SET
                firstname="%s",
                middlename="%s",
                nickname="%s",
                lastname="%s",
                contact1id="%s",
                contact2id="%s",
                wpuserid="%s",
                gender="%s",
                results="%s",
                birthdate="%s",
                status="%s"
                WHERE id="%s"',
                WPST_SWIMMERS_TABLE,
                $this->getFirstName(),
                $this->getMiddleName(),
                $this->getNickName(),
                $this->getLastName(),
                $this->getContact1Id(),
                $this->getContact2Id(),
                $this->getWPUserId(),
                $this->getGender(),
                $this->getResults(),
                $this->getDateOfBirthAsDate(),
                $this->getStatus(),
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
            $success |= $this->saveSwimmerOptionMeta() ;
        }

        return $success ;
    }

    /**
     * Save Swimmer Option Meta
     *
     */
    function saveSwimmerOptionMeta()
    {
        $success = false ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        $ometa = new SwimTeamOptionMeta() ;
        $ometa->setSwimmerId($this->getId()) ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $ometa->setOptionMetaKey($oconst) ;
                $ometa->setOptionMetaValue($this->getSwimmerOption($oconst)) ;
                $success |= $ometa->saveSwimmerOptionMeta() ;
            }
        }

        return $success ;
    }

    /**
     * Delete an swimmer
     */
    function deleteSwimmer()
    {
        $success = null ;

        //  Make sure the swimmer doesn't exist yet

        if (!$this->swimmerExist())
        {
            //  Construct the delete query
 
            $query = sprintf('DELETE FROM %s
                WHERE id="%s"',
                WPST_SWIMMERS_TABLE,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $success = $this->runDeleteQuery() ;

            //  Clean up the option meta data
            $ometa = new SwimTeamOptionMeta() ;
            $ometa->deleteOptionMetaBySwimmerId($this->getId()) ;
        }

        return $success ;
    }

    /**
     * Load swimmer record by Id
     *
     * @param - string - optional swimmer id
     */
    function loadSwimmerById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setId($id) ;

        //  Make sure it is a legal swimmer id
        if ($this->swimmerExistById())
        {
            $cutoffdate = $this->__cutoffDate() ;

            $query = sprintf('SELECT %s FROM %s WHERE id = "%s"',
                sprintf(WPST_SWIMMERS_COLUMNS, $cutoffdate, $cutoffdate,
                $cutoffdate), WPST_SWIMMERS_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setId($result['id']) ;
            $this->setContact1Id($result['contact1id']) ;
            $this->setContact2Id($result['contact2id']) ;
            $this->setWPUserId($result['wpuserid']) ;
            $this->setFirstName($result['firstname']) ;
            $this->setMiddleName($result['middlename']) ;
            $this->setNickName($result['nickname']) ;
            $this->setLastName($result['lastname']) ;
            $this->setGender($result['gender']) ;
            $this->setResults($result['results']) ;
            $this->setStatus($result['status']) ;
            $this->setDateOfBirthAsDate($result['birthdate']) ;
            $this->setAge($result['age']) ;
            $this->setAgeGroupAge($result['agegroupage']) ;

            //  How many swimmer options does this configuration support?

            $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

            $ometa = new SwimTeamOptionMeta() ;
            $ometa->setSwimmerId($this->getId()) ;

            //  Load the swimmer options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;

                if (get_option($oconst) != WPST_DISABLED)
                {
                    $ometa->loadOptionMetaBySwimmerIdAndKey($this->getId(), $oconst) ;
                    $this->setSwimmerOption($oconst, $ometa->getOptionMetaValue()) ;
                }
            }
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Retrieve all the Swimmer Ids for the swimmers based
     * on Swimmers can be filtered to only return active or
     * inactive.
     *
     * @param - string - optional filter to restrict query
     * @return - array - array of swimmers ids
     */
    function getAllSwimmerIds($filter = null, $orderby = 'lastname', $joins = null)
    {
        //  Select the records for the season

        $options_count = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        //  If the options count is zero or non-existant, don't reference
        //  the meta table because it will result in an empty set being returned.

        //if (($options_count === false) || ((int)$options_count === 0))
        //{
        //    $query = sprintf('SELECT DISTINCT %s.id AS swimmerid FROM %s, %s, %s',
        //        WPST_SWIMMERS_TABLE, WPST_SWIMMERS_TABLE,
        //        WPST_ROSTER_TABLE, WPST_SEASONS_TABLE) ;
        //}
        //else
        //{
        //    $query = sprintf('SELECT DISTINCT %s.id AS swimmerid FROM %s, %s, %s, %s',
        //        WPST_SWIMMERS_TABLE, WPST_SWIMMERS_TABLE, WPST_ROSTER_TABLE,
        //        WPST_SEASONS_TABLE, WPST_OPTIONS_META_TABLE) ;
        //}

        if ($joins == null)
            $query = sprintf('SELECT DISTINCT s.id AS swimmerid FROM %s s
                LEFT JOIN %s m ON (m.swimmerid = s.id)', WPST_SWIMMERS_TABLE, WPST_OPTIONS_META_TABLE) ;
        else
            $query = sprintf('SELECT DISTINCT s.id AS swimmerid FROM %s s %s
                LEFT JOIN %s m ON (m.swimmerid = s.id)', WPST_SWIMMERS_TABLE, $joins, WPST_OPTIONS_META_TABLE) ;

        //  Build the filters

        if (!is_null($filter) && ($filter != ''))
            $query .= sprintf(' WHERE %s', $filter) ;

        $query .= sprintf(' ORDER BY %s', $orderby) ;

        
        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Build cutoff date string
     *
     * @return string - cutoff date string
     */
    function __cutoffDate()
    {
        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        return $cutoffdate ;
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
class SwimTeamSwimmersGUIDataList extends SwimTeamGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        ,WPST_ACTION_ADD => WPST_ACTION_ADD
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
     * Disable all actions - need to do this when user's profile
     * hasn't been completed to prevent swimmer additions which
     * would end up without address and contact information.
     *
     */
    function disableAllActions()
    {
        $this->__normal_actions = array() ;
        $this->__empty_actions = array() ;
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
    function SwimTeamSwimmersGUIDataList($title, $width = '100%',
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_SWIMMERS_DEFAULT_COLUMNS,
        $tables = WPST_SWIMMERS_DEFAULT_TABLES,
        $where_clause = WPST_SWIMMERS_DEFAULT_WHERE_CLAUSE)
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

        if ((current_user_can('edit_posts') || get_option(WPST_OPTION_REGISTRATION_SYSTEM) == WPST_OPEN))
        {
            $this->__normal_actions[WPST_ACTION_REGISTER] = WPST_ACTION_REGISTER . ' (' . WPST_SEASON . ')' ;
            $this->__normal_actions[WPST_ACTION_UNREGISTER] = WPST_ACTION_UNREGISTER . ' (' . WPST_SEASON . ')' ;
        }

        //  If Opt-In/Opt-Out usage model is set to Stroke, then allow the actions.

        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
        {
            $optin = get_option(WPST_OPTION_OPT_IN_LABEL) ;
            $this->__normal_actions[WPST_ACTION_OPT_IN] = $optin . ' (' . WPST_SWIMMEET . ')' ;

            $optout = get_option(WPST_OPTION_OPT_OUT_LABEL) ;
            $this->__normal_actions[WPST_ACTION_OPT_OUT] = $optout . ' (' . WPST_SWIMMEET . ')' ;
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
	  	$this->add_header_item('First Name',
	        '300', 'firstname', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Last Name',
	        '300', 'lastname', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Gender',
	        '150', 'gender', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Date of Birth',
	        '250', 'birthdate', SORTABLE, SEARCHABLE, 'left') ;

	  	$this->add_header_item('Age',
	        '150', 'age', SORTABLE, NOT_SEARCHABLE, 'left') ;

	  	$this->add_header_item('Results',
	        '150', 'results', SORTABLE, SEARCHABLE, 'left') ;

		$this->add_header_item('Status',
	       	'150', 'status', false, SEARCHABLE, 'left') ;
	       	//'150', 'status', SORTABLE, SEARCHABLE, 'left') ;

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

	    $this->add_action_column('radio', 'FIRST', 'id') ;

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
        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->setSwimmerId($row_data['id']) ;

		switch ($col_name)
        {
            case 'First Name' :
                if ($row_data['middlename'] != '')
                    $obj = $row_data['firstname'] . ' ' .
                        substr($row_data['middlename'], 0, 1) . '.' ;
                else
                    $obj = $row_data['firstname'] ;
                break ;

            case 'Gender' :
                $obj = __(ucfirst($row_data['gender'])) ;
                break ;

            case 'Results' :
                $obj = __(ucfirst($row_data['results'])) ;
                break ;

            case 'Status' :
                //$obj = __(ucfirst($row_data['status'])) ;
                //$row_data['status'] = $swimmer->getStatus() ;
                $obj = ucfirst($swimmer->getStatus()) ;
                //$obj = __(ucfirst($row_data['status'])) ;
                break ;

            case 'Age' :
                $obj = $row_data['age'] . ' (' . $row_data['agegroupage'] . ')' ;
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
 * @see SwimTeamSwimmersGUIDataList
 */
class SwimTeamSwimmersAdminGUIDataList extends SwimTeamSwimmersGUIDataList
{
    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array(
         WPST_ACTION_PROFILE => WPST_ACTION_PROFILE
        ,WPST_ACTION_ADD => WPST_ACTION_ADD
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        ,WPST_ACTION_DELETE => WPST_ACTION_DELETE
        ,WPST_ACTION_GLOBAL_UPDATE => WPST_ACTION_GLOBAL_UPDATE
    ) ;
}

/**
 * Extended InfoTable Class for presenting Swimmer
 * information as a table extracted from the database.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamInfoTable
 */
class SwimTeamSwimmerProfileInfoTable extends SwimTeamInfoTable
{
    /**
     * id property, used to query user information
     */
    var $__swimmerid = null ;

    /**
     * Set the id
     *
     * @param int - the id of the user profile to query
     */
    function setId($id)
    {
        $this->__swimmerid = $id ;
    }

    /**
     * Get the id
     *
     * @return int - the id of the swimmer profile to query
     */
    function getId()
    {
        return $this->__swimmerid ;
    }

    /**
     * Set the swimmer id
     *
     * @param int - the id of the user profile to query
     */
    function setSwimmerId($id)
    {
        $this->__swimmerid = $id ;
    }

    /**
     * Get the swimmer id
     *
     * @return int - the id of the swimmer profile to query
     */
    function getSwimmerId()
    {
        return $this->__swimmerid ;
    }

    /**
     * Build the InfoTable
     *
     */
    function constructSwimmerProfile($brief = false)
    {
        $this->set_alt_color_flag(true) ;
        $this->set_show_cellborders(true) ;

        if (is_null($this->getId()))
        {
            $this->add_row('No swimmer data.') ;
        }
        else
        {
            $s = new SwimTeamSwimmer() ;
            $s->loadSwimmerById($this->getSwimmerId()) ;

            if ($s->getNickName() == WPST_NULL_STRING)
                $this->add_row('Name',
                    $s->getFirstName() . ' ' . $s->getLastName()) ;
            else
                $this->add_row('Name',
                    $s->getNickName() . ' ' . $s->getLastName()) ;

            $this->add_row('Age', $s->getAge() . ' (' . $s->getAgeGroupAge() . ')') ;
            $this->add_row('Age Group', $s->getAgeGroupText()) ;

            if ($brief) return ;

            //  Only show birthdate to swimmer's parent/guardian
            //  or any non-regular user (coaches, swim team admin, etc.)

            //  WP's global userdata
            global $userdata ;

            get_currentuserinfo() ;

            if (($userdata->ID == $s->getContact1Id()) ||
                ($userdata->ID == $s->getCOntact2Id()) ||
                ($userdata->user_level > 0))
            {
                $this->add_row('Birth Date', $s->getDateOfBirthAsDate()) ;
            }

            $this->add_row(html_b('Primary Contact Information'), '&nbsp;') ;
            $this->__swimmerContactInfo($s->getContact1Id()) ;

            $this->add_row(html_b('Secondary Contact Information'), '&nbsp;') ;
            $this->__swimmerContactInfo($s->getContact2Id()) ;

            $this->add_row(html_b('Web Site User Id'), '&nbsp;') ;
            $this->__swimmerContactInfo($s->getWPUserId()) ;
 
            //  Report Optional Fields

            //  How many swimmer options does this configuration support?

            $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

            $ometa = new SwimTeamOptionMeta() ;
            $ometa->setSwimmerId($this->getId()) ;

            //  Load the swimmer options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
                $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
                if (get_option($oconst) != WPST_DISABLED)
                {
                    $label = get_option($lconst) ;
                    $ometa->loadOptionMetaBySwimmerIdAndKey($this->getId(), $oconst) ;
                    $this->add_row($label, $ometa->getOptionMetaValue()) ;
                }
            }
        }
    }

    /**
     * Add contact information based on a user id
     *
     * @param - int - user id
     */
    function __swimmerContactInfo($id)
    {
        if (($id != WPST_NULL_ID) && ($id != null))
        {
            //  Show the parent's contact information
            $p = new SwimTeamUserProfile() ;
            
            if ($p->loadUserProfileByUserId($id))
            {
                if ($p->getContactInfo() == WPST_PUBLIC)
                {
                    $this->add_row('Name', $p->getFirstName() . ' ' . $p->getLastName()) ;

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

                    $phone = $p->getPrimaryPhone() ;
                    if ($p->getSecondaryPhone() != '')
                        $phone .= '<br/>' . $p->getSecondaryPhone() ;
                    $this->add_row('Phone', $phone) ;
                    $this->add_row('E-mail', html_a('mailto:' .
                        $p->getEmailAddress(), $p->getEmailAddress())) ;
                }
                else
                {
                    $this->add_row('Name',
                        $p->getFirstName() . ' ' . $p->getLastName()) ;
                    $this->add_row('Contact information is not public.',
                        '&nbsp;') ;
                }
            }
            else
            {
                //  No contact record in the swim team user table
                //  bluff with whatever can be pulled from WP user table

                //$this->add_row('No contact information available.', '&nbsp;') ;
                $u = get_userdata($id) ;

                $this->add_row('Name',
                    $u->user_firstname . ' ' . $u->user_lastname) ;
                $this->add_row('E-mail', html_a('mailto:' .
                    $u->user_email, $u->user_email)) ;
            }
        }
        else if ($id == null)
        {
            $this->add_row('No contact information available.', '&nbsp;') ;
        }
        else
        {
            $this->add_row('None', '&nbsp;') ;
        }
    }
}
?>
