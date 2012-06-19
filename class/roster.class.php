<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Season classes.
 *
 * $Id: roster.class.php 910 2012-06-13 17:14:48Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Roster
 * @version $Revision: 910 $
 * @lastmodified $Date: 2012-06-13 13:14:48 -0400 (Wed, 13 Jun 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
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
require_once("roster.include.php") ;
require_once("swimmers.class.php") ;
require_once("widgets.class.php") ;

/**
 * Class definition of the seasons
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamDBI
 */
class SwimTeamRoster extends SwimTeamDBI
{
    /**
     * id property - used for unique database identifier
     */
    var $__id ;

    /**
     * season Id property - the Id of the season record
     */
    var $__seasonId ;

    /**
     * swimmer Id property - the Id of the swimmer record
     */
    var $__swimmerId ;

    /**
     * contact Id property - the id of the swimmer's contact
     */
    var $__contactId ;

    /**
     * roster status property - roster status of the swimmer
     */
    var $__rosterStatus ;

    /**
     * swimmer number property - swimmer number of the swimmer
     */
    var $__swimmerlabel = WPST_NULL_STRING ;

    /**
     * registration date property - date swimmer was registered
     */
    var $__registration_date ;

    /**
     * Set the roster entry id
     *
     * @param - int - id of the roster entry
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the roster entry id
     *
     * @return - int - id of the roster entry
     */
    function getId()
    {
        return ($this->__id) ;
    }

    /**
     * Set the id of the season
     *
     * @param - string - description of the season
     */
    function setSeasonId($seasonId)
    {
        $this->__seasonId = $seasonId ;
    }

    /**
     * Get the id of the season
     *
     * @return - int - id of the season
     */
    function getSeasonId()
    {
        return ($this->__seasonId) ;
    }

    /**
     * Get the id of the active season
     *
     * @return - int - id of the season
     */
    function getActiveSeasonId()
    {
        $season = new SwimTeamSeason() ;

        return $season->getActiveSeasonId() ;
    }

    /**
     * Set the id of the swimmer
     *
     * @param - int - id of the swimmer
     */
    function setSwimmerId($swimmerId)
    {
        $this->__swimmerId = $swimmerId ;
    }

    /**
     * Get the id of the swimmer
     *
     * @return - int - id of the swimmer
     */
    function getSwimmerId()
    {
        return ($this->__swimmerId) ;
    }

    /**
     * Set the id of the contact who registered the swimmer.
     *
     * @param - int - id of the contact
     */
    function setContactId($contactId)
    {
        $this->__contactId = $contactId ;
    }

    /**
     * Get the id of the contact who registered the swimmer.
     *
     * @return - int - id of the contact
     */
    function getContactId()
    {
        return ($this->__contactId) ;
    }

    /**
     * Set the roster status of the swimmer
     *
     * @param - string - roster status of the swimmer
     */
    function setRosterStatus($status)
    {
        $this->__rosterStatus = $status ;
    }

    /**
     * Get the roster status of the swimmer
     *
     * @return - string - roster status of the swimmer
     */
    function getRosterStatus()
    {
        return ($this->__rosterStatus) ;
    }

    /**
     * Set the swimmer number of the swimmer
     *
     * @param - string - swimmer number of the swimmer
     */
    function setSwimmerLabel($number)
    {
        $this->__swimmerlabel = $number ;
    }

    /**
     * Get the swimmer number of the swimmer
     *
     * @return - string - swimmer number of the swimmer
     */
    function getSwimmerLabel()
    {
        return ($this->__swimmerlabel) ;
    }

    /**
     * Set the registration date of birth
     *
     * @param - array - registration date of birth
     */
    function setRegistrationDateAsDate($date)
    {
        $this->__registration_date = array('year' => date('Y', strtotime($date)),
            'month' => date('m', strtotime($date)), 'day' => date('d', strtotime($date))) ;
    }

    /**
     * Get the registration date of birth
     *
     * @return - array - registration date as an array
     */
    function getRegistrationDate()
    {
        return ($this->__registration_date) ;
    }

    /**
     * Get the registration date as MMDDYYYY
     *
     * @return - string - registration date as MMDDYYYY
     */
    function getRegistrationDateAsMMDDYYYY()
    {
        $d = &$this->__registration_date ;

        return sprintf('%02s%02s%04s', $d['month'], $d['day'], $d['year']) ;
    }

    /**
     *
     * Check if a swimmer is already registered in the database
     * for a season and return a boolean accordingly.
     *
     * @return - boolean - existance of registered swimmer
     */
    function isSwimmerRegistered()
    {
	    //  Is the swimmer already in the database?

        $query = sprintf("SELECT seasonid, swimmerid FROM %s WHERE
            seasonid = \"%s\" AND swimmerid = \"%s\" AND status=\"%s\"",
            WPST_ROSTER_TABLE, $this->getSeasonId(), $this->getSwimmerId(),
            WPST_ACTIVE) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure season doesn't exist

        $swimmerRegistered = (bool)($this->getQueryCount() > 0) ;

	    return $swimmerRegistered ;
    }

    /**
     *
     * Check if a swimmer label is already assigned in the
     * database for a season and return a boolean accordingly.
     *
     * @return - boolean - existance of registered swimmer
     */
    function isSwimmerLabelAssigned()
    {
	    //  Is the swimmer label already assigned?

        $query = sprintf("SELECT seasonid,
            swimmerlabel
            FROM %s WHERE
            seasonid = \"%s\" AND
            swimmerlabel = \"%s\"",
            WPST_ROSTER_TABLE,
            $this->getSeasonId(),
            $this->getSwimmerLabel()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure season doesn't exist

        $labelassigned = (bool)($this->getQueryCount() > 0) ;

	    return $labelassigned ;
    }

    /**
     *
     * Check if a id already exists in the database
     * and return a boolean accordingly.
     *
     * @param - string - optional id
     * @return - boolean - existance of season
     */
    function isSwimmerRegisteredById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

	    //  Is id already in the database?

        $query = sprintf("SELECT id FROM %s WHERE id = \"%s\"",
            WPST_ROSTER_TABLE, $id) ;

        $this->setQuery($query) ;
        $this->runSelectQuery(false) ;

	    //  Make sure id doesn't exist

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Register swimmer for a season
     */
    function registerSwimmer()
    {
        $success = null ;

        //  Make sure the season roster entry doesn't exist yet

        if (!$this->isSwimmerRegistered())
        {
            //  Construct the insert query
 
            $query = sprintf("INSERT INTO %s SET
                seasonid=\"%s\",
                swimmerid=\"%s\",
                contactid=\"%s\",
                status=\"%s\",
                swimmerlabel=\"%s\",
                registered=NOW()",
                WPST_ROSTER_TABLE,
                $this->getSeasonId(),
                $this->getSwimmerId(),
                $this->getContactId(),
                WPST_ACTIVE,
                $this->getSwimmerLabel()) ;

            $this->setQuery($query) ;
            $success = $this->runInsertQuery() ;
        }
        else
        {
            //  Construct the update query
 
            $query = sprintf("UPDATE %s SET
                status = \"%s\"
                WHERE swimmerid = \"%s\" AND
                seasonid = \"%s\"",
                WPST_ROSTER_TABLE,
                WPST_ACTIVE,
                $this->getSwimmerId(),
                $this->getSeasonId()) ;

            $this->setQuery($query) ;
            $success = $this->runUpdateQuery() ;
        }

        //  Send out e-mail regarding registration

        if ($success)
        {
            $this->sendConfirmationEmail(WPST_ACTION_REGISTER) ;

            //  Now that the swimmer has been added to the roster,
            //  need to update their global status which is presented
            //  as part of the "My Swimmers" functionality.

            //$swimmer = new SwimTeamSwimmer() ;
            //$swimmer->loadSwimmerById($this->getSwimmerId()) ;
            //$swimmer->setStatus(WPST_ACTIVE) ;
            //$update = $swimmer->updateSwimmer() ;
            $update = true ;
        }
        else
        {
            $update = false ;
        }

        return array($success, $update) ;
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
    function sendConfirmationEmail($action, $mode = WPST_HTML)
    {
        global $userdata ;
        get_currentuserinfo() ;

        $regprefix = get_option(WPST_OPTION_REG_PREFIX_LABEL) ;
        if ($regprefix === false) $regprefix = WPST_DEFAULT_REG_PREFIX_LABEL ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->loadSwimmerById($this->getSwimmerId()) ;

        $c1data = get_userdata($swimmer->getContact1Id()) ;
        //$c1email = $c1data->user_email ;

        if ($swimmer->getContact2Id() != WPST_NULL_ID)
        {
            $c2data = get_userdata($swimmer->getContact2Id()) ;
            //$c2email = $c2data->user_email ;
        }
        else
        {
            $c2data = null ;
            //$c2email = WPST_NULL_STRING ;
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
        //    $headers .= sprintf("To: %s %s <%s>", $c1data->user_firstname,
        //        $c1data->user_lastname, $c1data->user_email) . "\r\n" ;
        //else
        //    $headers .= sprintf("To: %s %s <%s>, %s %s<%s>",
        //        $c1data->user_firstname, $c1data->user_lastname, $c1data->user_email,
        //        $c2data->user_firstname, $c2data->user_lastname, $c2data->user_email) . "\r\n" ;

        $headers .= sprintf("From: %s <%s>",
            get_bloginfo('name'), get_bloginfo('admin_email')) . "\r\n" ;

        $headers .= sprintf("Cc: %s", get_option(WPST_OPTION_REG_EMAIL)) . "\r\n" ;
        $headers .= sprintf("Bcc: %s", get_bloginfo('admin_email')) . "\r\n" ;
        $headers .= sprintf("Reply-To: %s", get_bloginfo('admin_email')) . "\r\n" ;
        $headers .= sprintf("X-Mailer: PHP/%s", phpversion()) ;

        if ($mode == WPST_HTML)
        {
            $html = '
                <html>
                <head>
                <title>%s</title>
                </head>
                <body>
                <p>
                %s -
                </p>
                <p>
                You have successfully %sregistered %s %s swim team.
                </p>
                <h3>Swimmer Information</h3>
                Name:  %s<br/>
                Birthdate:  %s<br/>
                Age:  %s<br/>
                Age Group:  %s<br/>
                <h3>Parent Contact Information</h3>
                <p>%s</p>
                <p>
                <h3>Important Swim Team Links</h3>
                <ul>
                <li>Swim Team Web Site:  <a href="%s">%s</a></li>
                <li>Swim Team Terms of Use:  <a href="%s">%s</a></li>
                <li>Swim Team Billing Policy:  <a href="%s">%s</a></li>
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

            $profile = new SwimTeamUserProfileInfoTable("") ;
            //$profile->setId($userdata->ID) ;
            $profile->setId($swimmer->getContact1Id()) ;
            $profile->buildProfile() ;

            $message = sprintf($html,
                get_bloginfo('url'),
                $c1data->user_firstname,
                ($action == WPST_ACTION_REGISTER) ? $regprefix : "un",
                $swimmer->getFirstName() . " " . $swimmer->getLastName(),
                ($action == WPST_ACTION_REGISTER) ? "for" : "from",
                $swimmer->getFirstName() . " " . $swimmer->getLastName(),
                $swimmer->getDateOfBirthAsDate(),
                $swimmer->getAge(),
                $swimmer->getAgeGroupText(),
                $profile->render(),
                get_bloginfo('url'),
                get_bloginfo('url'),
                get_option(WPST_OPTION_REG_TOU_URL),
                get_option(WPST_OPTION_REG_TOU_URL),
                get_option(WPST_OPTION_REG_FEE_URL),
                get_option(WPST_OPTION_REG_FEE_URL),
                get_bloginfo('name'),
                get_bloginfo('url'),
                get_bloginfo('url')) ;
        }
        else
        {
            $plain = "%s -\r\n\r\n" ;
            $plain .= "You have successfully %sregistered %s %s swim team.\r\n\r\n" ;
            $plain .= "Important Swim Team Links\r\n" ;
            $plain .= "  - Swim Team Web Site:  %s\r\n" ;
            $plain .= "  - Swim Team Terms of Use:  %s\r\n" ;
            $plain .= "  - Swim Team Billing Policy:  %s\r\n\r\n" ;
            $plain .= "Thank you,\r\n\r\n" ;
            $plain .= "%s\r\n\r\n" ;
            $plain .= "Visit %s for all your swim team news." ;

            $message = sprintf($plain,
                $c1data->user_firstname,
                ($action == WPST_ACTION_REGISTER) ? $regprefix : "un",
                $swimmer->getFirstName() . " " . $swimmer->getLastName(),
                ($action == WPST_ACTION_REGISTER) ? "for" : "from",
                get_bloginfo('url'),
                get_option(WPST_OPTION_REG_TOU_URL),
                get_option(WPST_OPTION_REG_FEE_URL),
                get_bloginfo('name'),
                get_bloginfo('url')) ;
        }

        //$to = sprintf("%s %s <%s>", $c1data->user_firstname,
        //    $c1data->user_lastname, $c1data->user_email) ;
        if (is_null($c2data))
            $to = sprintf("%s %s <%s>", $c1data->user_firstname,
                $c1data->user_lastname, $c1data->user_email) . "\r\n" ;
        else
            $to = sprintf("%s %s <%s>, %s %s<%s>",
                $c1data->user_firstname, $c1data->user_lastname, $c1data->user_email,
                $c2data->user_firstname, $c2data->user_lastname, $c2data->user_email) . "\r\n" ;

        $subject = sprintf("Swimmer %sregistration for %s",
            ($action == WPST_ACTION_REGISTER) ? $regprefix : "un",
            $swimmer->getFirstName() . " " . $swimmer->getLastName()) ;

        $status = wp_mail($to, $subject, $message, $headers) ;

        return $status ;
    }

    /**
     * Update a roster
     *
     * Update the roster - the only thing which should
     * change is the status of the swimmer as from time
     * to time, swimmers leave the team for one reason
     * or another.
     */
    function updateRoster()
    {
        $success = null ;

        //  Make sure the season doesn't exist yet

        if ($this->isSwimmerRegistered())
        {
            //  Construct the insert query
 
            $query = sprintf("UPDATE %s SET
                seasonid=\"%s\",
                swimmerid=\"%s\",
                contactid=\"%s\",
                status=\"%s\",
                swimmerlabel=\"%s\"
                WHERE id=\"%s\"",
                WPST_ROSTER_TABLE,
                $this->getSeasonId(),
                $this->getSwimmerId(),
                $this->getContactId(),
                $this->getRosterStatus(),
                $this->getSwimmerLabel(),
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }
        else
            wp_error("Huh?  Shouldn't be here.") ;

        return true ;
    }

    /**
     * Hide a season
     */
    function hideSeason()
    {
        $success = null ;

        //  Make sure the season doesn't exist yet

        if (!$this->isSwimmerRegistered())
        {
            //  Construct the insert query
 
            $query = sprintf("UPDATE %s SET
                status=\"%s\"
                WHERE id=\"%s\"",
                WPST_ROSTER_TABLE,
                WPST_ROSTER_SWIMMER_HIDDEN,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;
        }

        return true ;
    }

    /**
     * Register a swimmer
     */
    function registerSwimmer2()
    {
        $success = null ;
        die("registerSwimmer();") ;

        //  Make sure the season exists

        if ($this->isSwimmerRegisteredById())
        {
            //  Construct the update query - make all seasons
            //  inactive before making the specified season active.
 
            $query = sprintf("UPDATE %s SET
                season_status=\"%s\"",
                WPST_ROSTER_TABLE,
                WPST_ROSTER_SWIMMER_INACTIVE
            ) ;

            $this->setQuery($query) ;
            $this->runUpdateQuery() ;

            $query = sprintf("UPDATE %s SET
                season_status=\"%s\"
                WHERE id=\"%s\"",
                WPST_ROSTER_TABLE,
                WPST_ROSTER_SWIMMER_ACTIVE,
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

        if ($this->isSwimmerRegisteredById())
        {
            //  Construct the update query 
 
            $query = sprintf("UPDATE %s SET
                season_status=\"%s\"
                WHERE id=\"%s\"",
                WPST_ROSTER_TABLE,
                WPST_ROSTER_SWIMMER_INACTIVE,
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

        if (!$this->isSwimmerRegistered())
        {
            //  Construct the insert query
 
            $query = sprintf("DELETE FROM %s
                WHERE id=\"%s\"",
                WPST_ROSTER_TABLE,
                $this->getId()
            ) ;

            $this->setQuery($query) ;
            $this->runDeleteQuery() ;
        }

        $success = !$this->isSwimmerRegisteredById() ;
        return $success ;
    }

    /**
     *
     * Load roster record by Id
     *
     * @param - string - optional roster id
     */
    function loadRosterById($id = null)
    {
        if (is_null($id)) $id = $this->getId() ;

        //  Dud?
        if (is_null($id)) return false ;

        $this->setId($id) ;

        //  Make sure it is a legal season id
        if ($this->isSwimmerRegisteredById())
        {
            $query = sprintf("SELECT * FROM %s WHERE id = \"%s\"",
                WPST_ROSTER_TABLE, $id) ;

            $this->setQuery($query) ;
            $this->runSelectQuery() ;

            $result = $this->getQueryResult() ;

            $this->setId($result['id']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setSwimmerId($result['swimmerid']) ;
            $this->setContactId($result['contactid']) ;
            $this->setRosterStatus($result['status']) ;
            $this->setSwimmerLabel($result['swimmerlabel']) ;
            $this->setRegistrationDateAsDate($result['registered']) ;
        }

        $idExists = (bool)($this->getQueryCount() > 0) ;

	    return $idExists ;
    }

    /**
     * Load roster record by season and swimmer id
     *
     * @param - int - optional roster id
     */
    function loadRosterBySeasonIdAndSwimmerId()
    {
        $query = sprintf("SELECT * FROM %s WHERE swimmerid = \"%s\" AND
            seasonid = \"%s\"", WPST_ROSTER_TABLE, $this->getSwimmerId(),
            $this->getSeasonId()) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        $idExists = (bool)($this->getQueryCount() > 0) ;

        //  Make sure it is a legal season id
        if ($idExists)
        {
            $result = $this->getQueryResult() ;

            $this->setId($result['id']) ;
            $this->setSeasonId($result['seasonid']) ;
            $this->setSwimmerId($result['swimmerid']) ;
            $this->setContactId($result['contactid']) ;
            $this->setRosterStatus($result['status']) ;
            $this->setSwimmerLabel($result['swimmerlabel']) ;
            $this->setRegistrationDateAsDate($result['registered']) ;
        }

	    return $idExists ;
    }

    /**
     * Retrieve the Swimmer Ids for the swimmers based
     * on a Season Id.  Swimmers can be restricted to only
     * those who are/were active.
     *
     * @param - boolean - optional to restrict query to active swimmers
     * @return - array - array of swimmers ids
     */
    function getSwimmerIds($active_only = true, $orderbybirthdate = false, $orderbyname = true)
    {
        //  Make sure we don't have a null season id, query will fail.
        if ($this->getSeasonId() == null) die("No Season Id.") ;

        //  Select the records for the season

        $query = sprintf("SELECT swimmerid FROM %s, %s
            WHERE %s.seasonid = \"%s\" AND %s.swimmerid = %s.id",
            WPST_ROSTER_TABLE,
            WPST_SWIMMERS_TABLE,
            WPST_ROSTER_TABLE,
            $this->getSeasonId(),
            WPST_ROSTER_TABLE,
            WPST_SWIMMERS_TABLE,
            WPST_ROSTER_TABLE,
            WPST_SWIMMERS_TABLE
        ) ;

        //  Only active swimmers?
      
        if ($active_only)
            $query .= sprintf(" AND %s.status = \"%s\"",
                WPST_ROSTER_TABLE, WPST_ACTIVE) ;

        if ($orderbybirthdate)
            $orderby = sprintf(" ORDER BY %s.birthdate DESC",
                WPST_SWIMMERS_TABLE) ;
        else if ($orderbyname)
            $orderby = sprintf(" ORDER BY %s.lastname, %s.firstname",
                WPST_SWIMMERS_TABLE, WPST_SWIMMERS_TABLE) ;
        else
            $orderby = sprintf("%s.id", WPST_SWIMMERS_TABLE) ;

        $query .= $orderby ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Retrieve the Swimmer Ids for the swimmers based
     * on a Season Id.  Swimmers can be restricted to only
     * those who are/were active.
     *
     * @param - boolean - optional to restrict query to active swimmers
     * @return - array - array of swimmers ids
     */
    function getAllSwimmerIdsByAgeGroupId($agegroupid)
    {
        //  Make sure we don't have a null season id, query will fail.
        if ($this->getSeasonId() == null) die('No Season Id.') ;

        //  Select the roster records that match the age group id

        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        $select_clause = sprintf(WPST_ROSTER_COLUMNS, 
            $cutoffdate, $cutoffdate, $cutoffdate) ;

        $where_clause = sprintf(WPST_ROSTER_WHERE_CLAUSE,
            $this->getSeasonId(), $cutoffdate, $cutoffdate,
            $cutoffdate, $cutoffdate, $cutoffdate, $cutoffdate) ;

        $query = sprintf('SELECT %s FROM %s WHERE %s AND %s.id="%s"',
            $select_clause, WPST_ROSTER_TABLES, $where_clause,
            WPST_AGE_GROUP_TABLE, $agegroupid) ;

        $this->setQuery($query) ;
        $this->runSelectQuery() ;

        return $this->getQueryResults() ;
    }

    /**
     * Assign the swimmer labels
     *
     */
    function assignSwimmerLabels()
    {
        //  How are labels assigned?  Lots of options ...
        //  If labels are numerically assigned by age group
        //  then we need to keep track of the swimmers within
        //  the age group so the labels are sequenced correctly.
        //  All other options are pretty straight forward.

        switch (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT))
        {
            case WPST_SIMPLE_NUMERIC:
                $iv = get_option(WPST_OPTION_SWIMMER_LABEL_INITIAL_VALUE) ;

                //  If option isn't stored in the database, use the default
                if ($iv === false)
                    $iv = WPST_DEFAULT_SWIMMER_LABEL_INITIAL_VALUE ;

                $num = $iv ;

                break ;

            case WPST_AGE_GROUP_PREFIX_NUMERIC:
            case WPST_AGE_GROUP_PREFIX_WPST_ID:
                $iv = get_option(WPST_OPTION_SWIMMER_LABEL_INITIAL_VALUE) ;

                //  If option isn't stored in the database, use the default
                if ($iv === false)
                    $iv = WPST_DEFAULT_SWIMMER_LABEL_INITIAL_VALUE ;

                $num = array() ;
                $prefix = array() ;

                //  Need to construct the prefixs

                $a = new SwimTeamAgeGroup() ;
                $agegroups = $a->getAgeGroupIds() ;

                foreach ($agegroups as $agegroup)
                {
                    $a->loadAgeGroupById($agegroup["id"]) ;
                    $prefix[$a->getId()]["num"] = $iv ;
                    $prefix[$a->getId()]["prefix"] = $a->getSwimmerLabelPrefix() ;
                }

                break ;

            default:
                break ;
        }

        //  Get all of the active swimmer ids, labels
        //  are assigned on a per seasson basis so when
        //  assigning, only assign based on the active
        //  season.

        $swimmerIds = $this->getSwimmerIds(true) ;

        //  Loop through the swimmer ids

        foreach ($swimmerIds as $swimmerId)
        {
            $this->setSwimmerId($swimmerId["swimmerid"]) ;
            $this->loadRosterBySeasonIdAndSwimmerId() ;

            //  Swimmer loaded, how should the label be assigned?


            switch (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT))
            {
                case WPST_SIMPLE_NUMERIC:
                    $this->setSwimmerLabel(sprintf(
                        get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE),
                        $num++)) ;
                    $this->updateRoster() ;
                    break ;

                case WPST_AGE_GROUP_PREFIX_NUMERIC:
                    $s = new SwimTeamSwimmer() ;
                    $s->loadSwimmerById($swimmerId["swimmerid"]) ;

                    $p = $a->getAgeGroupIdByAgeAndGender(
                        $s->getAgeGroupAge(), $s->getGender()) ;

                    $this->setSwimmerLabel(sprintf(
                        get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE), 
                        $prefix[$p]["prefix"], $prefix[$p]["num"]++)) ;

                    $this->updateRoster() ;

                    break ;

                case WPST_USA_SWIMMING:
                    $s = new SwimTeamSwimmer() ;
                    $s->loadSwimmerById($swimmerId["swimmerid"]) ;

                    $this->setSwimmerLabel(sprintf(
                        get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE),
                        $s->getUSSNumber())) ;
                    $this->updateRoster() ;
                    break ;

                case WPST_WPST_ID:
                    $this->setSwimmerLabel(sprintf(
                        get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE),
                        $swimmerId["swimmerid"])) ;
                    $this->setSwimmerLabel($swimmerId["swimmerid"]) ;
                    $this->updateRoster() ;

                    break ;

                case WPST_AGE_GROUP_PREFIX_WPST_ID:
                    $s = new SwimTeamSwimmer() ;
                    $s->loadSwimmerById($swimmerId["swimmerid"]) ;

                    $p = $a->getAgeGroupIdByAgeAndGender(
                        $s->getAgeGroupAge(), $s->getGender()) ;

                    $this->setSwimmerLabel(sprintf(
                        get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT_CODE), 
                        $prefix[$p]["prefix"], $swimmerId["swimmerid"])) ;

                    $this->updateRoster() ;

                    break ;

                case WPST_CUSTOM:
                    wp_die("Custom") ;
                    break ;

                default:
                    wp_die("Label assignment method not specified.") ;
                    break ;

            }
        }
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
class SwimTeamRosterGUIDataList extends SwimTeamGUIDataList
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
        //,"update" => WPST_ACTION_UPDATE
        //,"unregister" => WPST_ACTION_UNREGISTER
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
    function SwimTeamRosterGUIDataList($title, $width = "100%",
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_ROSTER_DEFAULT_COLUMNS,
        $tables = WPST_ROSTER_DEFAULT_TABLES,
        $where_clause = WPST_ROSTER_DEFAULT_WHERE_CLAUSE)
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
		$this->add_header_item("First Name",
	       	    "200", "firstname", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Last Name",
	         	    "200", "lastname", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Age",
	         	    "100", "age", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Age Group",
	         	    "300", "agegroup", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Swimmer Label",
	         	    "200", "swimmerlabel", SORTABLE, SEARCHABLE, "left") ;

	  	$this->add_header_item("Registered",
	         	    "200", "registered", SORTABLE, SEARCHABLE, "left") ;

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

	    $this->add_action_column('radio', 'FIRST', "swimmerid") ;

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
            case "Registered" :
                $obj = $row_data["registered"] ;
                break ;

            case "First Name":
                if ($row_data["nickname"] != "")
                    $obj = $row_data["nickname"] ;
                else
                    $obj = $row_data["firstname"] ;
                break ;

            case "Starts" :
                $obj = date("F d, Y", strtotime($row_data["season_start"])) ;
                break ;

            case "Ends" :
                $obj = date("F d, Y", strtotime($row_data["season_end"])) ;
                break ;

            case "Age" :
                $obj = $row_data["age"] . " (" . $row_data["agegroupage"] . ")" ;
                break ;

            case "Age Group" :
                $obj = $row_data["agegroup"] ;
                
                $obj = preg_replace("/" . WPST_GENDER_FEMALE . "s?/",
                    get_option(WPST_OPTION_GENDER_LABEL_FEMALE) . "s", $obj) ;
                $obj = preg_replace("/" . WPST_GENDER_MALE . "s?/",
                    get_option(WPST_OPTION_GENDER_LABEL_MALE) . "s", $obj) ;

                $obj = ucfirst($obj) ;
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
 * @see SwimTeamRosterGUIDataList
 */
class SwimTeamRosterAdminGUIDataList extends SwimTeamRosterGUIDataList
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
        //,WPST_ACTION_DIRECTORY => WPST_ACTION_DIRECTORY
        ,WPST_ACTION_UPDATE => WPST_ACTION_UPDATE
        ,WPST_ACTION_UNREGISTER => WPST_ACTION_UNREGISTER
        ,WPST_ACTION_ASSIGN_LABEL => WPST_ACTION_ASSIGN_LABEL
        ,WPST_ACTION_ASSIGN_LABELS => WPST_ACTION_ASSIGN_LABELS
        ,WPST_ACTION_EXPORT_SDIF => WPST_ACTION_EXPORT_SDIF
        ,WPST_ACTION_EXPORT_CSV => WPST_ACTION_EXPORT_CSV
        ,WPST_ACTION_EXPORT_HY3 => WPST_ACTION_EXPORT_HY3
        ,WPST_ACTION_EXPORT_MMRE => WPST_ACTION_EXPORT_MMRE
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
    function SwimTeamRosterAdminGUIDataList($title, $width = "100%",
        $default_orderby='', $default_reverseorder=FALSE,
        $columns = WPST_ROSTER_DEFAULT_COLUMNS,
        $tables = WPST_ROSTER_DEFAULT_TABLES,
        $where_clause = WPST_ROSTER_DEFAULT_WHERE_CLAUSE)
    {
        //  Call the constructor of the parent class
        $this->SwimTeamRosterGUIDataList($title, $width,
            $default_orderby, $default_reverseorder,
            $columns, $tables, $where_clause) ;

        //  These actions can't be part of the property
        //  declaration.  Opt-In and Opt-Out are only enabled
        //  for the Roster GDL when Opt-In/Opt-Out is in
        //  stroke mode!

        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
        {
            $optin = get_option(WPST_OPTION_OPT_IN_LABEL) ;
            $this->__normal_actions[WPST_ACTION_OPT_IN] = $optin ;

            $optout = get_option(WPST_OPTION_OPT_OUT_LABEL) ;
            $this->__normal_actions[WPST_ACTION_OPT_OUT] = $optout ;
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
        //  make use of the parent class user_setup()
        //  function to set up the display of the fields

        parent::user_setup() ;
    }

}
?>
