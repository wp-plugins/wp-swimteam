<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TeamProfile classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage TeamProfile
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once('db.class.php') ;
require_once('swimteam.include.php') ;

/**
 * Class definition of the Swim Team Profile
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamProfile extends SwimTeamDBI
{
    /**
     * id property - id
     */
    var $__id ;

    /**
     * team id property - WP team id
     */
    var $__teamid ;

    /**
     * team name property - the name the team is known by
     */
    var $__teamname ;

    /**
     * team club or pool name property - the name of the team's club or pool
     */
    var $__teamcluborpoolname ;

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
     * team email address property
     */
    var $__emailaddress ;

    /**
     * team web site property
     */
    var $__website ;

    /**
     * pool length property
     */
    var $__poollength ;

    /**
     * pool units property
     */
    var $__poolunits ;

    /**
     * pool lanes property
     */
    var $__poollanes ;

    /**
     * coach user id property
     */
    var $__coachuserid ;

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
     * Set the teamid
     *
     * @param - string - teamid
     */
    function setTeamId($teamid)
    {
        $this->__teamid = $teamid ;
    }

    /**
     * Get the teamid
     *
     * @return - string - teamid
     */
    function getTeamId()
    {
        return ($this->__teamid) ;
    }

    /**
     * Set the team name
     *
     * @param - string - team name
     */
    function setTeamName($name)
    {
        $this->__teamname = $name ;
    }

    /**
     * Get the team name
     *
     * @return - string - team name
     */
    function getTeamName()
    {
        return ($this->__teamname) ;
    }

    /**
     * Set the team club or pool name
     *
     * @param - string - team club or pool name
     */
    function setClubOrPoolName($name)
    {
        $this->__teamcluborpoolname = $name ;
    }

    /**
     * Get the team club or pool name
     *
     * @return - string - team club or pool name
     */
    function getClubOrPoolName()
    {
        return ($this->__teamcluborpoolname) ;
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
     * Set the team email address
     *
     * @param - string - team email address
     */
    function setEmailAddress($e)
    {
        $this->__emailaddress = $e;
    }

    /**
     * Get the team email address
     *
     * @return - string - team email address
     */
    function getEmailAddress()
    {
        return ($this->__emailaddress) ;
    }

    /**
     * Set the team web site
     *
     * @param - string - team web site
     */
    function setWebSite($s)
    {
        $this->__website = $s;
    }

    /**
     * Get the team web site
     *
     * @return - string - team web site
     */
    function getWebSite()
    {
        return ($this->__website) ;
    }

    /**
     * Set the pool length
     *
     * @param - string - pool length
     */
    function setPoolLength($l)
    {
        $this->__poollength = $l;
    }

    /**
     * Get the pool length
     *
     * @return - string - pool length
     */
    function getPoolLength()
    {
        return ($this->__poollength) ;
    }

    /**
     * Set the pool measurement units
     *
     * @param - string - pool measurement units
     */
    function setPoolMeasurementUnits($u)
    {
        $this->__poolunits = $u;
    }

    /**
     * Get the pool measurement units
     *
     * @return - string - pool measurement units
     */
    function getPoolMeasurementUnits()
    {
        return ($this->__poolunits) ;
    }

    /**
     * Set the number of pool lanes
     *
     * @param - int - number of pool lanes
     */
    function setPoolLanes($lanes)
    {
        $this->__poollanes = $lanes;
    }

    /**
     * Get the pool measurement units
     *
     * @return - int - number of pool lanes
     */
    function getPoolLanes()
    {
        return ($this->__poollanes) ;
    }

    /**
     * Set the number of pool lanes
     *
     * @param - int - coach user id
     */
    function setCoachUserId($id)
    {
        $this->__coachuserid = $id;
    }

    /**
     * Get the pool measurement units
     *
     * @return - int - coach user id
     */
    function getCoachUserId()
    {
        return ($this->__coachuserid) ;
    }

    /**
     * load Team Profile
     *
     * Load the option values from the Wordpress database.
     * If for some reason, the team profile doesn't exist,
     * use the default values where every possible.
     *
     */
    function loadTeamProfile()
    {
        //  team name
        $option = get_option(WPST_OPTION_TEAM_NAME) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setTeamName($option) ;
        }
        else
        {
            $this->setTeamName(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_NAME, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  club or pool name
        $option = get_option(WPST_OPTION_TEAM_CLUB_OR_POOL_NAME) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setClubOrPoolName($option) ;
        }
        else
        {
            $this->setClubOrPoolName(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_CLUB_OR_POOL_NAME, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }


        //  street 1
        $option = get_option(WPST_OPTION_TEAM_STREET_1) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setStreet1($option) ;
        }
        else
        {
            $this->setStreet1(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_STREET_1, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  street 2
        $option = get_option(WPST_OPTION_TEAM_STREET_2) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setStreet2($option) ;
        }
        else
        {
            $this->setStreet2('') ;
            update_option(WPST_OPTION_TEAM_STREET_2, '') ;
        }

        //  street 3
        $option = get_option(WPST_OPTION_TEAM_STREET_3) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setStreet3($option) ;
        }
        else
        {
            $this->setStreet3('') ;
            update_option(WPST_OPTION_TEAM_STREET_3, '') ;
        }

        //  city
        $option = get_option(WPST_OPTION_TEAM_CITY) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setCity($option) ;
        }
        else
        {
            $this->setCity(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_CITY, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  state or province
        $option = get_option(WPST_OPTION_TEAM_STATE_OR_PROVINCE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setStateOrProvince($option) ;
        }
        else
        {
            $this->setStateOrProvince(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_STATE_OR_PROVINCE, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  postal code
        $option = get_option(WPST_OPTION_TEAM_POSTAL_CODE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setPostalCode($option) ;
        }
        else
        {
            $this->setPostalCode(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_POSTAL_CODE, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  country
        $option = get_option(WPST_OPTION_TEAM_COUNTRY) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setCountry($option) ;
        }
        else
        {
            $this->setCountry(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_COUNTRY, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  primary phone
        $option = get_option(WPST_OPTION_TEAM_PRIMARY_PHONE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setPrimaryPhone($option) ;
        }
        else
        {
            $this->setPrimaryPhone(WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
            update_option(WPST_OPTION_TEAM_PRIMARY_PHONE, WPST_OPTION_TEAM_PROFILE_DEFAULT_VALUE) ;
        }

        //  team email address
        $option = get_option(WPST_OPTION_TEAM_EMAIL_ADDRESS) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setEmailAddress($option) ;
        }
        else
        {
            $this->setEmailAddress('') ;
            update_option(WPST_OPTION_TEAM_EMAIL_ADDRESS, '') ;
        }

        //  team email address
        $option = get_option(WPST_OPTION_TEAM_EMAIL_ADDRESS) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setEmailAddress($option) ;
        }
        else
        {
            $this->setEmailAddress('') ;
            update_option(WPST_OPTION_TEAM_EMAIL_ADDRESS, '') ;
        }

        //  tean web site
        $option = get_option(WPST_OPTION_TEAM_WEB_SITE) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setWebSite($option) ;
        }
        else
        {
            $this->setWebSite('') ;
            update_option(WPST_OPTION_TEAM_SECONDARY_PHONE, '') ;
        }

        //  measurement
        $option = get_option(WPST_OPTION_TEAM_POOL_MEASUREMENT_UNITS) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setPoolMeasurementUnits($option) ;
        }
        else
        {
            $this->setPoolMeasurementUnits(WPST_DEFAULT_MEASUREMENT_UNITS) ;
            update_option(WPST_OPTION_TEAM_POOL_MEASUREMENT_UNITS, WPST_DEFAULT_MEASUREMENT_UNITS) ;
        }

        //  length
        $option = get_option(WPST_OPTION_TEAM_POOL_LENGTH) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setPoolLength($option) ;
        }
        else
        {
            $this->setPoolLength(WPST_DEFAULT_POOL_LENGTH) ;
            update_option(WPST_OPTION_TEAM_POOL_LENGTH, WPST_DEFAULT_POOL_LENGTH) ;
        }

        //  lanes
        $option = get_option(WPST_OPTION_TEAM_POOL_LANES) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setPoolLanes($option) ;
        }
        else
        {
            $this->setPoolLanes(WPST_DEFAULT_POOL_LANES) ;
            update_option(WPST_OPTION_TEAM_POOL_LANES, WPST_DEFAULT_POOL_LANES) ;
        }

        //  coach user id
        $option = get_option(WPST_OPTION_TEAM_COACH_USER_ID) ;

        //  If option isn't stored in the database, use the default
        if ($option)
        {
            $this->setCoachUserId($option) ;
        }
        else
        {
            $this->setCoachUserId(WPST_NULL_ID) ;
            update_option(WPST_OPTION_TEAM_COACH_USER_ID, WPST_NULL_ID) ;
        }
    }

    /**
     * update (save) Team Profile
     *
     * Write the options to the Worpress database
     */
    function updateTeamProfile()
    {
        update_option(WPST_OPTION_TEAM_NAME, $this->getTeamName()) ;
        update_option(WPST_OPTION_TEAM_CLUB_OR_POOL_NAME, $this->getClubOrPoolName()) ;
        update_option(WPST_OPTION_TEAM_STREET_1, $this->getStreet1()) ;
        update_option(WPST_OPTION_TEAM_STREET_2, $this->getStreet2()) ;
        update_option(WPST_OPTION_TEAM_STREET_3, $this->getStreet3()) ;
        update_option(WPST_OPTION_TEAM_CITY, $this->getCity()) ;
        update_option(WPST_OPTION_TEAM_STATE_OR_PROVINCE, $this->getStateOrProvince()) ;
        update_option(WPST_OPTION_TEAM_POSTAL_CODE, $this->getPostalCode()) ;
        update_option(WPST_OPTION_TEAM_COUNTRY, $this->getCountry()) ;
        update_option(WPST_OPTION_TEAM_PRIMARY_PHONE, $this->getPrimaryPhone()) ;
        update_option(WPST_OPTION_TEAM_SECONDARY_PHONE, $this->getSecondaryPhone()) ;
        update_option(WPST_OPTION_TEAM_EMAIL_ADDRESS, $this->getEmailAddress()) ;
        update_option(WPST_OPTION_TEAM_WEB_SITE, $this->getWebSite()) ;
        update_option(WPST_OPTION_TEAM_POOL_LENGTH, $this->getPoolLength()) ;
        update_option(WPST_OPTION_TEAM_POOL_MEASUREMENT_UNITS, $this->getPoolMeasurementUnits()) ;
        update_option(WPST_OPTION_TEAM_POOL_LANES, $this->getPoolLanes()) ;
        update_option(WPST_OPTION_TEAM_COACH_USER_ID, $this->getCoachUserId()) ;
    }
}
?>
