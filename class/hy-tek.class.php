<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * TeamProfile classes.
 *
 * $Id: hy3.class.php 863 2012-05-11 18:46:18Z mpwalsh8 $
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage TeamProfile
 * @version $Revision: 863 $
 * @lastmodified $Date: 2012-05-11 14:46:18 -0400 (Fri, 11 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('db.include.php') ;
require_once('swimteam.include.php') ;
require_once('hy-tek.include.php') ;
require_once('users.class.php') ;
require_once('team.class.php') ;
require_once('seasons.class.php') ;
require_once('roster.class.php') ;
require_once('swimclubs.class.php') ;

/**
 * Class definition of the HY3 team profile
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SDIFProfile
 */
class HY3Profile extends SDIFProfile
{
    /**
     * Load HY3 Profile
     *
     */
    function loadHY3Profile()
    {
        parent::loadSDIFProfile() ;
    }
}

/**
 * Class definition of the HY3 LSC Registration export
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3Profile
 */
class HY3BaseRecord extends HY3Profile
{
    /**
     * hy3 data
     */
    var $__hy3Data ;

    /**
     * hy3 File
     */
    var $__hy3File ;

    /**
     * hy3 record count
     */
    var $__hy3Count ;

    /**
     * hy3 debug flag
     */
    var $__hy3DebugFlag = false ;

    /**
     * Get HY3 Debug Flag
     *
     * @return boolean - state of HY3 debug flag
     */
    function getHY3DebugFlag()
    {
        return $this->__hy3DebugFlag ;
    }

    /**
     * Set HY3 Debug Flag
     *
     * @return boolean - state of HY3 debug flag
     */
    function setHY3DebugFlag($flag = true)
    {
        $this->__hy3DebugFlag = $flag ;
    }

    /**
     * Set HY3 record count
     *
     * @return int - count of HY3 records
     */
    function setHY3Count($count)
    {
        $this->__hy3Count  = $count ;
    }

    /**
     * Get HY3 record count
     *
     * @return int - count of HY3 records
     */
    function getHY3Count()
    {
        return $this->__hy3Count ;
    }

    /**
     * Get HY3 file name
     *
     * @return string - HY3 file name
     */
    function getHY3File()
    {
        return $this->__hy3File ;
    }

    /**
     * Set HY3 file name
     *
     * @param string - HY3 file name
     */
    function setHY3File($f)
    {
        $this->__hy3File = $f ;
    }

    /**
     * Write the HY3 data to a file which can be sent to the browser
     *
     */
    function generateHY3File()
    {
        //  Generate a temporary file to hold the data
 
        $this->setHY3File(tempnam('', 'SD3')) ;

        //  Write the HY3 data to the file

        $f = fopen($this->getHY3File(), 'w') ;

        foreach ($this->__hy3Data as $hy3)
            fwrite($f, $hy3 . WPST_HY3_RECORD_TERMINATOR) ;

        fclose($f) ;
    }
}

/**
 * Class definition of the HY3 LSC Registration export
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3BaseRecord
 */
class HY3Roster extends HY3BaseRecord
{
    /**
     * Consturctor
     *
     */
    function HY3Roster()
    {
        parent::loadHY3Profile() ;
    }

    /**
     * GenerateHY3 - generate the HY3 content for the team roster.
     *
     * @return string - HY3 content
     */
    function generateHY3()
    {
        global $current_user ;

        //  Need some information from the current user

        $user = new SwimTeamUserProfile() ;
        $user->loadUserProfileByUserId($current_user->ID) ;

        // Shortcut to access property
        $hy3 = &$this->__hy3Data ;

        $hy3 = array() ;

        //  Need to keep track of unique swimmers and various
        //  HY3 record counts in order to terminate HY3 file.

        $unique_swimmers = array() ;
        $hy3_counters = array('b' => 0, 'c' => 0, 'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0) ;

        //  Add debug stuff?  The debug stuff invalidates the HY3
        //  but is useful for ensuring all of the information is in
        //  the proper column.
 
        if ($this->getHY3DebugFlag())
        {
            $hy3[] = WPST_HY3_COLUMN_DEBUG1 ;
            $hy3[] = WPST_HY3_COLUMN_DEBUG2 ;
            $hy3[] = WPST_HY3_COLUMN_DEBUG3 ;
        }

        //  Need Team Profile information from database
 
        $swimteam = new SwimTeamProfile() ;
        $swimteam->loadTeamProfile() ;

        /**
         * Build the A1 record
         */
        $a1 = new HY3A1Record() ;
        $a1->setFileCode(WPST_HY3_FTC_MEET_TEAM_ROSTER_VALUE) ;
        $a1->setFileDescription('Swim Team Roster') ;
        $a1->setSoftwareVendor(WPST_HY3_SOFTWARE_NAME) ;
        $a1->setSoftwareName(WPST_HY3_SOFTWARE_VERSION) ;
        $a1->setFileCreationDate(date('mdY'))  ;
        $a1->setFileCreationTime(date('g:i A'))  ;
        $a1->setTeamName($swimteam->getTeamName()) ;
        
        $hy3[] = $a1->GenerateRecord() ;

        /**
         * Build the C1 record
         */
        $c1 = new HY3C1Record() ;
        $c1->setTeamNameAbrv($this->getTeamCode()) ;
        $c1->setTeamFullName($swimteam->getClubOrPoolName()) ;
        $c1->setTeamName($swimteam->getTeamName()) ;
        $c1->setTeamLSC($this->getLSCCode()) ;
        $c1->setTeamType(WPST_HY3_TTC_AGE_GROUP_VALUE) ;

        $hy3[] = $c1->GenerateRecord() ;
        $hy3_counters['c']++ ;

        $c2 = new HY3C2Record() ;
        $c2->setTeamAddress1($swimteam->getStreet1()) ;
        $c2->setTeamAddress2($swimteam->getStreet2()) ;
        $c2->setTeamCity($swimteam->getCity()) ;
        $c2->setTeamState($swimteam->getStateOrProvince()) ;
        $c2->setTeamPostalCode($swimteam->getPostalCode()) ;
        $c2->setTeamCountryCode($this->getCountryCode()) ;
        $c2->setTeamRegistrationCode(WPST_HY3_TRC_USA_SWIMMING_VALUE) ;

        $hy3[] = $c2->GenerateRecord() ;
        $hy3_counters['c']++ ;

        $c3 = new HY3C3Record() ;
        $c3->setPhoneNumber($swimteam->getPrimaryPhone()) ;
        $c3->setTeamSecondaryPhone($swimteam->getSecondaryPhone()) ;
        $c3->setTeamFax(WPST_HY3_UNUSED) ;
        $c3->setTeamEmail($swimteam->getEmailAddress()) ;

        $hy3[] = $c3->GenerateRecord() ;
        $hy3_counters['c']++ ;

        //  Need a bunch of data!

        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;

        //  Load information from the database
        //  to get the list of potential swimmers

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $swimmerIds = $roster->getSwimmerIds() ;

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact1 = new SwimTeamUserProfile() ;
        $contact2 = new SwimTeamUserProfile() ;
        $contact3 = new SwimTeamUserProfile() ;

        //  Need records for the Hy-Tek roster - there are a bunch!
        //  Hy-Tek team manager defines a number of fields which somewhat
        //  contradict each other - Primary Contact, Secondary Contact
        //  Mother's Name, Father's Name, etc.  We'll make our best guess
        //  at populating as much data as possible with assumptions.  In
        //  particular, the wp-SwimTeam Contact 1 is assumed to be the
        //  swimmer's Mother and the wp-SwimTeam Contact 2 is assumed to
        //  be the swimmer's Father.

        $d1 = new HY3D1Record() ;
        $d2 = new HY3D2Record() ;
        $d3 = new HY3D3Record() ;
        $d4 = new HY3D4Record() ;
        $d5 = new HY3D5Record() ;
        $d6 = new HY3D6Record() ;
        $d7 = new HY3D7Record() ;
        $d8 = new HY3D8Record() ;
        $d9 = new HY3D9Record() ;
        $da = new HY3DARecord() ;
        $db = new HY3DBRecord() ;
        $dc = new HY3DCRecord() ;
        $dd = new HY3DDRecord() ;
        $de = new HY3DERecord() ;
        $df = new HY3DFRecord() ;

        //  Loop through roster

        foreach ($swimmerIds as $key => &$swimmerId)
        {
            $roster->setSwimmerId($swimmerId['swimmerid']) ;
            $roster->loadRosterBySeasonIdAndSwimmerId() ;
            $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
            $contact1->loadUserProfileByUserId($swimmer->getContact1Id()) ;
            $contact2->loadUserProfileByUserId($swimmer->getContact2Id()) ;
            $contact3->loadUserProfileByUserId($swimmer->getWpUserId()) ;
                    
            //  Initialize D1 record fields which are swimmer based
            $d1->setAthleteLastName($swimmer->getLastName()) ;
            $d1->setAthleteFirstName($swimmer->getFirstName()) ;
            $d1->setAthleteMiddleInitial(substr($swimmer->getMiddleName(), 0, 1)) ;

            if ($swimmer->getNickname() != '')
                $d1->setAthleteNickname($swimmer->getNickname()) ;
            else
                $d1->setAthleteNickname($swimmer->getFirstName()) ;

            $d1->setAthleteBirthDate($swimmer->getDateOfBirthAsMMDDYYYY(), true) ;

            //  How should the Swimmer Id appear in the HY3 file?
            if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID)
                $d1->setAthleteId($swimmer->getId()) ;
            if ($this->getSwimmerIdFormat() == WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL)
                $d1->setAthleteId($roster->getSwimmerLabel()) ;
            else
                $d1->setAthleteId($swimmer->getUSSNumber()) ;

            if ($this->getUseAgeGroupAge() == WPST_NO)
                $d1->setAthleteAge($swimmer->getAge()) ;
            else
                $d1->setAthleteAge($swimmer->getAgeGroupAge()) ;

            $d1->setGender(ucwords($swimmer->getGender())) ;
            $d1->setRegistrationCountry($this->getCountryCode()) ;

            //  Fill in the unused fields
            $d1->setDatabaseId1(WPST_HY3_UNUSED) ;
            $d1->setDatabaseId2(WPST_HY3_UNUSED) ;
            $d1->setAthleteSchoolYear(WPST_HY3_UNUSED) ;
            $d1->setGroup(WPST_HY3_UNUSED) ;
            $d1->setSubgroup(WPST_HY3_UNUSED) ;
            $d1->setInactive(WPST_HY3_UNUSED) ;
            $d1->setWmGroup(WPST_HY3_UNUSED) ;
            $d1->setWmSubgroup(WPST_HY3_UNUSED) ;
    
            //  Build D2 record
            $d2->setPrimaryContactAddress1($contact1->getStreet1()) ;
            $d2->setPrimaryContactAddress2($contact1->getStreet2()) ;
            $d2->setPrimaryContactCity($contact1->getCity()) ;
            $d2->setPrimaryContactState($contact1->getStateOrProvince()) ;
            $d2->setPrimaryContactPostalCode($contact1->getPostalCode()) ;
            $d2->setPrimaryContactCountry($contact1->getCountry()) ;

            //  Build D3 record
            $d3->setFathersOfficePhoneNumber($contact2->getSecondaryPhone()) ;
            $d3->setPrimaryContactHomePhoneNumber($contact1->getPrimaryPhone()) ;
            $d3->setPrimaryContactFaxNumber($contact1->getSecondaryPhone()) ;
            $d3->setFathersEmailAddress($contact2->getEmailAddress()) ;
    
            //  Build D4 record
            $d4->setSecondaryContactMailto($swimmer->getFirstAndLastNames() .
                ' c/o ' . $contact2->getFullName()) ;
            $d4->setSecondaryContactAddress1($contact2->getStreet1()) ;
            $d4->setSecondaryContactCity($contact2->getCity()) ;
            $d4->setSecondaryContactState($contact2->getStateOrProvince()) ;
            $d4->setSecondaryContactPostalCode($contact2->getPostalCode()) ;
            $d4->setSecondaryContactCountry($contact2->getCountry()) ;

            //  Build D5 record
            $d5->setPrimaryContactMailto($swimmer->getFirstAndLastNames() .
                ' c/o ' . $contact1->getFullName()) ;
            $d5->setSecondaryContactAddress2($contact2->getStreet2()) ;
            $d5->setRegistrationDate($roster->getRegistrationDateAsMMDDYYYY()) ;
            $d5->setPrimaryContactCity($contact1->getCity()) ;
            $d5->setSecondaryContactCity($contact2->getCity()) ;

            //  Build D6 record
            $d6->setDoctorsName(WPST_HY3_UNUSED) ;
            $d6->setDoctorsPhoneNumber(WPST_HY3_UNUSED) ;
            $d6->setEmergencyContactName(WPST_HY3_UNUSED) ;
            $d6->setEmergencyContactPhoneNumber(WPST_HY3_UNUSED) ;

            //  Build D7 record
            $d7->setDoctorsName(WPST_HY3_UNUSED) ;
            $d7->setSecondaryContactParent1OfficePhoneNumber($contact1->getSecondaryPhone()) ;
            $d7->setSecondaryContactHomePhoneNumber($contact2->getPrimaryPhone()) ;
            $d7->setSecondaryContactFaxNumber($contact2->getSecondaryPhone()) ;
            $d7->setSecondaryContactParent1EmailAddress($contact1->getEmailAddress()) ;
            $d7->setFathersEmailAddress($contact2->getEmailAddress()) ;

            //  Build D8 record
            $d8->setMedicalConditionDescription(WPST_HY3_UNUSED) ;

            //  Build D9 record
            $d9->setMedicationDescription(WPST_HY3_UNUSED) ;

            //  Build DA record
            $da->setCustomField1Name(WPST_HY3_UNUSED) ;
            $da->setCustomField1Value(WPST_HY3_UNUSED) ;
            $da->setCustomField2Name(WPST_HY3_UNUSED) ;
            $da->setCustomField2Value(WPST_HY3_UNUSED) ;
            $da->setCustomField3Name(WPST_HY3_UNUSED) ;
            $da->setCustomField3Value(WPST_HY3_UNUSED) ;

            //  The Hy-tek HY3 file supports 3 custom fields so if swimmer
            //  fileds are enabled, we'll map up to three of theme into the
            //  Hy-tek custom fields.

            $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

            $ometa = new SwimTeamOptionMeta() ;
            $ometa->setSwimmerId($swimmerId['swimmerid']) ;

            //  Load the swimmer options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
                $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
                if (get_option($oconst) != WPST_DISABLED)
                {
                    $label = get_option($lconst) ;
                    $ometa->loadOptionMetaBySwimmerIdAndKey($swimmerId['swimmerid'], $oconst) ;

                    switch ($oc)
                    {
                        case 1:
                            $da->setCustomField1Name($label) ;
                            $da->setCustomField1Value($ometa->getOptionMetaValue()) ;
                            break ;

                        case 2:
                            $da->setCustomField2Name($label) ;
                            $da->setCustomField2Value($ometa->getOptionMetaValue()) ;
                            break ;

                        case 3:
                            $da->setCustomField3Name($label) ;
                            $da->setCustomField3Value($ometa->getOptionMetaValue()) ;
                            break ;

                        default:
                            break ;
                    }
                }
            }

            //  Build DB record
            $db->setFathersLastName($contact2->getLastName()) ;
            $db->setFathersFirstName($contact2->getFirstName()) ;
            $db->setMothersFirstName($contact1->getFirstName()) ;
            $db->setSecondaryContactLastName($contact2->getLastName()) ;
            $db->setSecondaryContactParent1Name($contact1->getFullName()) ;
            $db->setSecondaryContactParent2Name($contact2->getFullName()) ;

            //  Build DD record
            $dd->setFathersCellPhoneNumber($contact2->getSecondaryPhone()) ;
            $dd->setMothersOfficePhoneNumber($contact1->getSecondaryPhone()) ;
            $dd->setMothersCellPhoneNumber($contact1->getSecondaryPhone()) ;
            $dd->setSecondaryContactParent1CellPhoneNumber($contact1->getSecondaryPhone()) ;
            $dd->setSecondaryContactParent2OfficePhoneNumber($contact2->getSecondaryPhone()) ;
            $dd->setSecondaryContactParent2CellPhoneNumber($contact2->getSecondaryPhone()) ;

            //  Build DE record
            $de->setMothersEmailAddress($contact1->getEmailAddress()) ;
            $de->setSecondaryContactParent2EmailAddress($contact2->getEmailAddress()) ;
            $de->setMothersLastName($contact1->getLastName()) ;
 
            //  Build DF record
            $df->setAthletesMiddleName($swimmer->getMiddleName()) ;
            $df->setAthletesCellPhoneNumber($contact3->getPrimaryPhone()) ;
            $df->setAthletesEmailAddress($contact3->getEmailAddress()) ;

            if ($this->getHY3DebugFlag())
            {
                $hy3[] = WPST_HY3_COLUMN_DEBUG1 ;
                $hy3[] = WPST_HY3_COLUMN_DEBUG2 ;
                $hy3[] = WPST_HY3_COLUMN_DEBUG3 ;
            }
    
            $hy3[] = $d1->GenerateRecord() ;
            $hy3[] = $d2->GenerateRecord() ;
            $hy3[] = $d3->GenerateRecord() ;
            $hy3[] = $d4->GenerateRecord() ;
            $hy3[] = $d5->GenerateRecord() ;
            $hy3[] = $d6->GenerateRecord() ;
            $hy3[] = $d7->GenerateRecord() ;
            $hy3[] = $d8->GenerateRecord() ;
            $hy3[] = $d9->GenerateRecord() ;
            $hy3[] = $da->GenerateRecord() ;
            $hy3[] = $db->GenerateRecord() ;
            $hy3[] = $dc->GenerateRecord() ;
            $hy3[] = $dd->GenerateRecord() ;
            $hy3[] = $de->GenerateRecord() ;
            $hy3[] = $df->GenerateRecord() ;

            $hy3_counters['d']++ ;

            //  Update the various counters
    
            //  Track uninque swimmers
            if (!in_array($swimmer->getId(), $unique_swimmers))
                $unique_swimmers[] = $swimmer->getId() ;
        }

        //  Record the count of entries created

        //$this->setHY3Count($hy3_counters['d'] + $hy3_counters['e'] + $hy3_counters['f']) ;
        $this->setHY3Count(count($unique_swimmers)) ;
    }
}

/**
 * Class definition of the HY3 Meet Entries export
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3BaseRecord
 */
class HY3MeetEntries extends HY3BaseRecord
{
    /**
     * Swim Meet Id
     */
    var $_swimmeetid ;

    /**
     * Zero Time Mode property
     */
    var $_zerotimemode ;

    /**
     * Set the Swim Meet Id
     *
     * @param int $id swim meet id
     */
    function setSwimMeetId($id)
    {
        $this->_swimmeetid = $id ;
    }

    /**
     * Get the Swim Meet Id
     *
     * @return int swim meet id
     */
    function getSwimMeetId()
    {
        return $this->_swimmeetid ;
    }

    /**
     * Set the Zero Time Mode to specify
     * how zero times (0.00) should be handled
     *
     * @param string $mode mode setting
     */
    function setZeroTimeMode($mode = WPST_HYTEK_USE_BLANKS)
    {
        $this->_zerotimemode = $mode ;
    }

    /**
     * Get the Zero Time Mode to specify
     * how zero times (0.00) should be handled
     *
     * @return string $mode mode setting
     */
    function getZeroTimeMode()
    {
        return $this->_zerotimemode ;
    }

    /**
     * Consturctor
     *
     */
    function HY3MeetEntries()
    {
        parent::loadHY3Profile() ;
    }

    /**
     * GenerateHY3 - generate the HY3 content for the team roster.
     *
     * @return string - HY3 content
     */
    function generateHY3($eventIds = array())
    {
        global $current_user ;

        //  Need some information from the current user

        $user = new SwimTeamUserProfile() ;
        $user->loadUserProfileByUserId($current_user->ID) ;

        // Shortcut to access property
        $hy3 = &$this->__hy3Data ;

        $hy3 = array() ;

        //  Add debug stuff?  The debug stuff invalidates the HY3
        //  but is useful for ensuring all of the information is in
        //  the proper column.
 
        if ($this->getHY3DebugFlag())
        {
            $hy3[] = WPST_HYTEK_COLUMN_DEBUG1 ;
            $hy3[] = WPST_HYTEK_COLUMN_DEBUG2 ;
        }

        //  Need to keep track of unique swimmers and various
        //  HY3 record counts in order to terminate HY3 file.

        $unique_swimmers = array() ;
        $hy3_counters = array('b' => 0, 'c' => 0, 'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0) ;

        //  We'll need a bunch of data from the database ...

        $swimmeet = new SwimMeet() ;
        $meta = new SwimMeetMeta() ;
        $event = new SwimMeetEvent() ;
        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;
        $swimclub = new SwimClubProfile() ;
        $swimteam = new SwimTeamProfile() ;

        //  Need Team Profile information from database
        //  along with swim meet and opponent club details.
 
        $swimteam->loadTeamProfile() ;
        $swimmeet->loadSwimMeetByMeetId($this->getSwimMeetId()) ;
        $swimclub->loadSwimClubBySwimClubId($swimmeet->getOpponentSwimClubId()) ;

        /**
         * Build the A0 record
         */
        $a0 = new HY3A0Record() ;
        $a0->setOrgCode($this->getOrgCode()) ;
        $a0->setHY3VersionNumber(WPST_HYTEK_VERSION) ;
        $a0->setFileCode(WPST_HYTEK_FTT_CODE_MEET_REGISTRATIONS_VALUE) ;
        $a0->setSoftwareName(WPST_HYTEK_SOFTWARE_NAME) ;
        $a0->setSoftwareVersion(WPST_HYTEK_SOFTWARE_VERSION) ;
        $a0->setContactName($user->GetFullName()) ;
        $a0->setContactPhone($user->GetPrimaryPhone()) ;
        $a0->setFileCreationOrUpdate(date('mdY')) ;
        $a0->setSubmittedByLSC($this->getLSCCOde()) ;
        
        $hy3[] = $a0->GenerateRecord() ;

        /**
         * Build the B1 record
         */
        $b1 = new HY3B1Record() ;
        $b1->setOrgCode($this->getOrgCode()) ;
        $b1->setTeamCode($this->getTeamCode()) ;
        $b1->setMeetName(sprintf('%s vs %s', $swimteam->getTeamName(), $swimclub->getTeamName())) ;

        //  Meet address depends on meet location, 'Home' or 'Away'

        if ($swimmeet->getLocation() == WPST_HOME)
            $st = &$swimteam ;
        else if ($swimmeet->getLocation() == WPST_AWAY)
            $st = &$swimclub ;
        else
            $st = &$swimteam ;

        $b1->setMeetAddress1($st->getStreet1()) ;
        $b1->setMeetAddress2($st->getStreet2()) ;
        $b1->setMeetCity($st->getCity()) ;
        $b1->setMeetState($st->getStateOrProvince()) ;
        $b1->setMeetPostalCode($st->getPostalCode()) ;
        $b1->setMeetCountryCode($this->getCountryCode()) ;

        switch ($swimmeet->getMeetType())
        {
            case WPST_DUAL_MEET:
                $b1->setMeetCode(WPST_HYTEK_MEET_TYPE_DUAL_VALUE) ;
                break ;

            case WPST_INVITATIONAL:
                $b1->setMeetCode(WPST_HYTEK_MEET_TYPE_INVITATIONAL_VALUE) ;
                break ;

            case WPST_TIME_TRIAL:
                $b1->setMeetCode(WPST_HYTEK_MEET_TYPE_TIME_TRIALS_VALUE) ;
                break ;

            default:
                $b1->setMeetCode(WPST_HYTEK_MEET_TYPE_OPEN_VALUE) ;
                break ;
        }

        $b1->setMeetStart($swimmeet->getMeetDateAsMMDDYYYY()) ;
        $b1->setMeetEnd($swimmeet->getMeetDateAsMMDDYYYY()) ;
        $b1->setPoolAltitude(0) ;
        $b1->setCourseCode(HY3CodeTableMappings::GetCourseCode(
            $st->getPoolMeasurementUnits(), $st->getPoolLength())) ;

        $hy3[] = $b1->GenerateRecord() ;
        $hy3_counters['b']++ ;

        /**
         * Build the B2 record
         */
        $b2 = new HY3B2Record() ;
        $b2->setOrgCode($this->getOrgCode()) ;
        $b2->setTeamCode($this->getTeamCode()) ;
        $b2->setMeetName($user->getFullName()) ;
        $b2->setMeetAddress1($st->getStreet1()) ;
        $b2->setMeetAddress2($st->getStreet2()) ;
        $b2->setMeetCity($st->getCity()) ;
        $b2->setMeetState($st->getStateOrProvince()) ;
        $b2->setMeetPostalCode($st->getPostalCode()) ;
        $b2->setMeetCountryCode($this->getCountryCode()) ;
        $b2->setMeetHostPhone($user->getPrimaryPhone()) ;

        $hy3[] = $b2->GenerateRecord() ;
        $hy3_counters['b']++ ;

        /**
         * Build the C1 record
         */
        $c1 = new HY3C1Record() ;
        $c1->setOrgCode($this->getOrgCode()) ;
        $c1->setTeamCode($this->getTeamCode()) ;
        $c1->setTeamName($swimteam->getClubOrPoolName()) ;
        $c1->setTeamNameAbrv($swimteam->getTeamName()) ;
        $c1->setTeamAddress1($swimteam->getStreet1()) ;
        $c1->setTeamAddress2($swimteam->getStreet2()) ;
        $c1->setTeamCity($swimteam->getCity()) ;
        $c1->setTeamState($swimteam->getStateOrProvince()) ;
        $c1->setTeamPostalCode($swimteam->getPostalCode()) ;
        $c1->setTeamCountryCode($this->getCountryCode()) ;
        $c1->setRegionCode($this->getRegionCode()) ;

        $hy3[] = $c1->GenerateRecord() ;
        $hy3_counters['c']++ ;

        /**
         * Build the C2 record.  We'll actually build this twice, initially
         * with placeholder values in the count fields and then it will be
         * rebuilt later once all of the counts are known.
         */
        $c2 = new HY3C2Record() ;
        $c2->setOrgCode($this->getOrgCode()) ;
        $c2->setTeamCode($this->getTeamCode()) ;
        if ($swimteam->getCoachUserId() !== WPST_NULL_ID)
        {
            $coach = new SwimTeamUserProfile() ;
            $coach->loadUserProfileByUserId($swimteam->getCoachUserId()) ;
            $c2->setCoachName($coach->getFullName()) ;
            $c2->setCoachPhone($coach->getPrimaryPhone()) ;
        }
        else
        {
            $c2->setCoachName(WPST_NULL_STRING) ;
            $c2->setCoachPhone(WPST_NULL_STRING) ;
        }

        $c2->setNumberOfIndividualEntries(0) ;
        $c2->setNumberOfAthletes(0) ;
        $c2->setNumberOfRelayEntries(0) ;
        $c2->setNumberOfRelaySwimmers(0) ;
        $c2->setNumberOfSplitRecords(0) ;
        $c2->setTeamNameAbrv($swimteam->getTeamName()) ;
        $c2->setTeamCode5(WPST_NULL_STRING) ;

        $hy3[] = $c2->GenerateRecord() ;
        $hy3_counters['c']++ ;

        //  Load information from the database
        //  to get the list of potential swimmers

        $roster->setSeasonId($swimmeet->getSeasonId()) ;
        $swimmerIds = $roster->getSwimmerIds() ;

        //  Need structures for swimmers and their primary contact
 
        $swimmer = new SwimTeamSwimmer() ;
        $contact = new SwimTeamUserProfile() ;

        //  If no events are specified, use all events in the swim meet

        if (empty($eventIds))
        {
            $meetEventIds = $event->getAllEventIdsByMeetId($this->getSwimMeetId(), 'eventid') ;
            //  Clean up the array of Meet Event Ids

            foreach ($meetEventIds as $key => $value)
                $eventIds[] = $value['eventid'] ;

            unset($meetEventIds) ;
        }

        //  Initialize the D0 and D3 records, some of it is static for all entries
        $d0 = new HY3D0Record() ;
        $d3 = new HY3D3Record() ;

        $d0->setOrgCode($this->getOrgCode()) ;
        $d0->setAttachCode(WPST_HYTEK_ATTACHED_CODE_ATTACHED_VALUE) ;

        //  May want to add a citizenship field for each swimmer but
        //  for now, we'll use the country code for the swim team itself
        $d0->setCitizenCode($this->getCountryCode()) ;


        //  The rest of the fields are left empty
        $d0->setSwimDate($swimmeet->getMeetDateAsMMDDYYYY()) ;
        $d0->setSeedTime(0.0) ;
        $d0->setSeedCourseCode(WPST_NULL_STRING) ;
        $d0->setPrelimTime(0.0) ;
        $d0->setPrelimCourseCode(WPST_NULL_STRING) ;
        $d0->setSwimOffTime(0.0) ;
        $d0->setSwimOffCourseCode(WPST_NULL_STRING) ;
        $d0->setFinalsTime(0.0) ;
        $d0->setFinalsCourseCode(WPST_NULL_STRING) ;
        $d0->setPrelimHeatNumber(0) ;
        $d0->setPrelimLaneNumber(0) ;
        $d0->setFinalsHeatNumber(0) ;
        $d0->setFinalsLaneNumber(0) ;
        $d0->setPrelimPlaceRanking(WPST_NULL_STRING) ;
        $d0->setFinalsPlaceRanking(WPST_NULL_STRING) ;
        $d0->setFinalsPoints(WPST_NULL_STRING) ;
        $d0->setEventTimeClassCode(WPST_NULL_STRING) ;
        $d0->setSwimmerFlightStatus(WPST_NULL_STRING) ;

        //  Initialize the E0 and F0 records, some of it is static for all entries
        $e0 = new HY3E0Record() ;
        $f0 = new HY3F0Record() ;

        $e0->setOrgCode($this->getOrgCode()) ;
        $e0->setTeamCode($this->getTeamCode()) ;
        $e0->setRelayTeamName('A') ;

        $f0->setOrgCode($this->getOrgCode()) ;
        $f0->setTeamCode($this->getTeamCode()) ;
        $f0->setRelayTeamName('A') ;
        $f0->setPrelimLegOrderCode(WPST_HYTEK_RELAY_CODE_NOT_SWIMMING_VALUE) ;
        $f0->setSwimOffLegOrderCode(WPST_HYTEK_RELAY_CODE_NOT_SWIMMING_VALUE) ;
        $f0->setFinalsLegOrderCode(WPST_HYTEK_RELAY_CODE_ALTERNAME_VALUE) ;

        //  The rest of the fields are left empty
        $e0->setSwimDate($swimmeet->getMeetDateAsMMDDYYYY(), false) ;
        $e0->setSeedTime(0.0) ;
        $e0->setSeedCourseCode(WPST_NULL_STRING) ;
        $e0->setPrelimTime(0.0) ;
        $e0->setPrelimCourseCode(WPST_NULL_STRING) ;
        $e0->setSwimOffTime(0.0) ;
        $e0->setSwimOffCourseCode(WPST_NULL_STRING) ;
        $e0->setFinalsTime(0.0) ;
        $e0->setFinalsCourseCode(WPST_NULL_STRING) ;
        $e0->setPrelimHeatNumber(0) ;
        $e0->setPrelimLaneNumber(0) ;
        $e0->setFinalsHeatNumber(0) ;
        $e0->setFinalsLaneNumber(0) ;
        $e0->setPrelimPlaceRanking(WPST_NULL_STRING) ;
        $e0->setFinalsPlaceRanking(WPST_NULL_STRING) ;
        $e0->setFinalsPoints(WPST_NULL_STRING) ;
        $e0->setEventTimeClassCode(WPST_NULL_STRING) ;

        //  Loop through events

        foreach ($eventIds as $eventId)
        {
            //  Debug code, take this out!!!
            //if ($eventId['eventid'] != 1411) continue ;
//            if ((($eventId['eventid'] < 1462) || ($eventId['eventid'] > 1465)) &&
//                (($eventId['eventid'] < 1474) || ($eventId['eventid'] > 1476))) continue ;

            //$event->loadSwimMeetEventByEventId($eventId['eventid']) ;
            $event->loadSwimMeetEventByEventId($eventId) ;

            // Get all swimmers eligible for the age group

            $swimmerIds = $roster->getAllSwimmerIdsByAgeGroupId($event->getAgeGroupId()) ;

            //  Need to tidy up the list of swimmers based on meet participation
            //  Is the meet set up as opt-in or opt-out?  Adjust the list accordingly.

            //foreach ($swimmerIds as $swimmerId)
            foreach ($swimmerIds as $key => &$swimmerId)
            {
                //printf('<h4>Swimmer Id:  %s<br>Event Number %s</h4>', $swimmerId['swimmerid'], $event->getEventNumber()) ;
                //  Has swimmer entered or scratched this event?
                //  Which mode is the data ingetDateOfBirthAsMMDDYYYY?  Stroke or Event?

                if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
                {
                    printf('<h5>Stroke Mode</h5>') ;
                    $optinoptout = $meta->getStrokeCodesBySwimmerIdsAndMeetIdAndParticipation($swimmerId['swimmerid'],
                        $swimmeet->getMeetId(), $swimmeet->getParticipation()) ;

                    if (!empty($optinoptout)) $optinoptout = $optinoptout[0] ;

                    $swimming = ($swimmeet->getParticipation() == WPST_OPT_IN) ?
                        in_array($event->getStroke(), $optinoptout) :
                        !in_array($event->getStroke(), $optinoptout) ;
                }
                else
                {
                    $optinoptout = $meta->getEventIdsBySwimmerIdsAndMeetIdAndParticipation($swimmerId['swimmerid'],
                        $swimmeet->getMeetId(), $swimmeet->getParticipation()) ;

                    if (!empty($optinoptout)) $optinoptout = $optinoptout[0] ;

                    $swimming = ($swimmeet->getParticipation() == WPST_OPT_IN) ?
                        in_array($event->getEventId(), $optinoptout) :
                        !in_array($event->getEventId(), $optinoptout) ;
                }

                //  If swimmer isn't swimming, drop them from the list!

                if (!$swimming) unset($swimmerIds[$key]) ;
            }

            //  Now we know the swimmers and the events, time to generate records!

            //  Individual or Relay event?

            if (($event->getStroke() == WPST_HYTEK_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE) ||
                ($event->getStroke() == WPST_HYTEK_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE))
            {
                //  Intialize the E0 record fields that are event based
                $e0->setEventGender($event->getGender()) ;
                $e0->setEventDistance($event->getDistance()) ;
                $e0->setCourseCode($event->getCourse()) ;
                $e0->setStrokeCode($event->getStroke()) ;
                $e0->setEventNumber($event->getEventNumber()) ;
                $e0->setEventAgeCode($event->getMinAge(), $event->getMaxAge()) ;

                $f0count = 0 ;
                $totalage = 0 ;
                $f0hy3 = array() ;

                foreach ($swimmerIds as $key => &$swimmerId)
                {
                    $roster->setSwimmerId($swimmerId['swimmerid']) ;
                    $roster->loadRosterBySeasonIdAndSwimmerId() ;
                    $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
                    
                    //  Initialize F0 record fields which are swimmer based
                    $f0->setSwimmerName($swimmer->getLastCommaFirstNames($this->getUseNickName())) ;
                    $f0->setPreferredFirstName($swimmer->getFirstName($this->getUseNickName())) ;
                    $f0->setBirthDate($swimmer->getDateOfBirthAsMMDDYYYY(), false) ;

                    //  How should the Swimmer Id appear in the HY3 file?
                    if ($this->getSwimmerIdFormat() == WPST_HYTEK_SWIMMER_ID_FORMAT_WPST_ID)
                        $f0->setUSS($swimmer->getId()) ;
                    if ($this->getSwimmerIdFormat() == WPST_HYTEK_SWIMMER_ID_FORMAT_SWIMMER_LABEL)
                        $f0->setUSS($roster->getSwimmerLabel()) ;
                    else
                        $f0->setUSS($swimmer->getUSSNumber()) ;

                    $f0->setUSSNew() ;
    
                    if ($this->getUseAgeGroupAge() == WPST_NO)
                    {
                        $totalage += $swimmer->getAge() ;
                        $f0->setAgeOrClass($swimmer->getAge()) ;
                    }
                    else
                    {
                        $totalage += $swimmer->getAgeGroupAge() ;
                        $f0->setAgeOrClass($swimmer->getAgeGroupAge()) ;
                    }

                    $f0->setGender($swimmer->getGender()) ;
    
                    if ($this->getHY3DebugFlag())
                    {
                        $hy3[] = WPST_HYTEK_COLUMN_DEBUG1 ;
                        $hy3[] = WPST_HYTEK_COLUMN_DEBUG2 ;
                    }
    
                    $f0hy3[] = $f0->GenerateRecord($this->getZeroTimeMode()) ;
    
                    //  Update the various counters
                    $f0count++ ;
                    $hy3_counters['f']++ ;
    
                    //  Track uninque swimmers
                    if (!in_array($swimmer->getId(), $unique_swimmers))
                        $unique_swimmers[] = $swimmer->getId() ;
                }

                //  All of the F0 records have been generated,
                //  need to update the E0 records then add them 
                //  all to the pile of HY3 records.

                if ($f0count > 0)
                {
                    $e0->setTotalAgeOfAthletes($totalage) ;
                    $e0->setNumberOfF0Records($f0count) ;

                    $hy3[] = $e0->GenerateRecord($this->getZeroTimeMode()) ;

                    //  Append all of the F0 records

                    foreach ($f0hy3 as $key => $value)
                        $hy3[] = $value ;
                }
            }
            else
            {
                //  Intiialize the D0 record fields that are event based
                $d0->setEventGender($event->getGender()) ;
                $d0->setEventDistance($event->getDistance()) ;
                $d0->setCourseCode($event->getCourse()) ;
                $d0->setStrokeCode($event->getStroke()) ;
                $d0->setEventNumber($event->getEventNumber()) ;
                $d0->setEventAgeCode($event->getMinAge(), $event->getMaxAge()) ;

                foreach ($swimmerIds as $key => &$swimmerId)
                {
                    $roster->setSwimmerId($swimmerId['swimmerid']) ;
                    $roster->loadRosterBySeasonIdAndSwimmerId() ;
                    $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
                    
                    //  Initialize D0 record fields which are swimmer based
                    $d0->setSwimmerName($swimmer->getLastCommaFirstNames($this->getUseNickName())) ;
                    $d0->setBirthDate($swimmer->getDateOfBirthAsMMDDYYYY(), true) ;
                    $d3->setPreferredFirstName($swimmer->getFirstName($this->getUseNickName())) ;

                    //  How should the Swimmer Id appear in the HY3 file?
                    if ($this->getSwimmerIdFormat() == WPST_HYTEK_SWIMMER_ID_FORMAT_WPST_ID)
                    {
                        $d0->setUSS($swimmer->getId()) ;
                        $d3->setUSS($swimmer->getId()) ;
                    }
                    if ($this->getSwimmerIdFormat() == WPST_HYTEK_SWIMMER_ID_FORMAT_SWIMMER_LABEL)
                    {
                        $d0->setUSS($roster->getSwimmerLabel()) ;
                        $d3->setUSS($roster->getSwimmerLabel()) ;
                    }
                    else
                    {
                        $d0->setUSS($swimmer->getUSSNumber()) ;
                        $d3->setUSS($swimmer->getUSSNumber()) ;
                    }
    
                    if ($this->getUseAgeGroupAge() == WPST_NO)
                        $d0->setAgeOrClass($swimmer->getAge()) ;
                    else
                        $d0->setAgeOrClass($swimmer->getAgeGroupAge()) ;
                    $d0->setGender($swimmer->getGender()) ;
    
                    if ($this->getHY3DebugFlag())
                    {
                        $hy3[] = WPST_HYTEK_COLUMN_DEBUG1 ;
                        $hy3[] = WPST_HYTEK_COLUMN_DEBUG2 ;
                    }
    
                    //  Generate HY3 and update the various counters

                    $hy3[] = $d0->GenerateRecord($this->getZeroTimeMode()) ;
                    $hy3_counters['d']++ ;

                    $hy3[] = $d3->GenerateRecord() ;
                    $hy3_counters['d']++ ;
    
                    //  Track uninque swimmers
                    if (!in_array($swimmer->getId(), $unique_swimmers))
                        $unique_swimmers[] = $swimmer->getId() ;
                }
            }
        }


        //  Construct the Z0 file termination record
 
        $z0 = new HY3Z0Record() ;
        $z0->setOrgCode($this->getOrgCode()) ;
        $z0->setFileCode(WPST_HYTEK_FTT_CODE_MEET_REGISTRATIONS_VALUE) ;
        $z0->setNotes(sprintf('Created:  %s', date('Y-m-d @ H:i'))) ;
        $z0->setBRecordCount($hy3_counters['b']) ;
        $z0->setMeetCount(1) ;
        $z0->setCRecordCount($hy3_counters['c']) ;
        $z0->setTeamCount(1) ;
        $z0->setDRecordCount($hy3_counters['d']) ;
        $z0->setSwimmerCount(count($unique_swimmers)) ;
        $z0->setERecordCount($hy3_counters['e']) ;
        $z0->setFRecordCount($hy3_counters['f']) ;
        $z0->setGRecordCount($hy3_counters['g']) ;
        $z0->setBatchNumber(1) ;
        $z0->setNewMemberCount(0) ;
        $z0->setRenewMemberCount(0) ;
        $z0->setChangeMemberCount(0) ;
        $z0->setDeleteMemberCount(0) ;

        $hy3[] = $z0->GenerateRecord() ;

        //  Need to go back and "update" the C2 record now that the 
        //  number of entries and types of records are now known.

        $c2->setNumberOfIndividualEntries($hy3_counters['d']) ;
        $c2->setNumberOfAthletes(count($unique_swimmers)) ;
        $c2->setNumberOfRelayEntries($hy3_counters['e']) ;
        $c2->setNumberOfRelaySwimmers($hy3_counters['f']) ;
        $c2->setNumberOfSplitRecords($hy3_counters['g']) ;

        //  Scan through the HY3 records and update the C2 record

        foreach ($hy3 as $key => $value)
            if (substr($value, 0, 2) == 'C2')
                $hy3[$key] = $c2->GenerateRecord() ;

        //  Record the count of entries created

        $this->setHY3Count($hy3_counters['d'] + $hy3_counters['e'] + $hy3_counters['f']) ;
    }
}

/**
 * HY3 record base class
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamDBI
 */
class HY3Record extends SDIFRecord
{
    /**
     * HY3 record storage
     */
    var $_hy3_record ;

    /**
     * HY3 record type
     */
    var $_hy3_record_type ;

    /**
     * hy3 checksum
     */
    var $__hy3_checksum = '' ;

    /**
     * Get HY3 Checksum
     *
     * @return string - calculated HY3 checksum as a 2 character string
     */
    function getHY3Checksum()
    {
        return $this->__hy3_checksum ;
    }

    /**
     * Set HY3 Checksum
     *
     * @param string - HY3 record
     */
    function setHY3Checksum($chksm)
    {
        $this->__hy3_checksum = $chksm ;
    }

    /**
     * Calculate HY3 Checksum
     *
     * Adapted from Troy Delano's example PHP code.  Credit to Joe
     * Hance for decipering the goofy Hy-tek checksum.  Joe is the man!
     *
     * @param string - HY3 record
     */
    function CalculateHy3Checksum($hy3)
    {
        // Ensure the string is 128 bytes in length and padded with whitespace

        $hy3 = str_pad($hy3, 128, ' ', STR_PAD_RIGHT) ;

        $sumEvn = 0 ;
        $sumOdd = 0 ;

        //  Loop through 128 characters, two at a time

        for ($i =0 ; $i < 64 ; $i++)
        {
            $sumEvn = $sumEvn + ord($hy3{(2*$i)}) ;
            $sumOdd = $sumOdd + (2 * ord($hy3{(2 * $i) +1 })) ;
        }

        //  Calculate the checksum and save the ones and tens digits

        $chksum = (floor(($sumEvn + $sumOdd)/21)) + 205 ;
        $ones = $chksum/1 % 10 ;
        $tens = $chksum/10 % 10 ;

        return sprintf(WPST_HY3_CHECKSUM_RECORD, $hy3, $ones, $tens) ;
    }
}

/**
 * HY3 A1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3Record
 */
class HY3A1Record extends HY3Record
{
    /**
     * File Description
     */
    var $_file_description ;

    /**
     * Software Vendor
     */
    var $_software_vendor ;

    /**
     * Software Name
     */
    var $_software_name ;

    /**
     * Team Name
     */
    var $_contact_name ;

    /**
     * File Creation Date
     */
    var $_file_creation_date ;

    /**
     * File Creation Time
     */
    var $_file_creation_time ;

    /**
     * Set File Description
     *
     * @param string file description
     */
    function setFileDescription($txt)
    {
        $this->_file_description = $txt ;
    }

    /**
     * Get File Description
     *
     * @return string file description
     */
    function getFileDescription()
    {
        return $this->_file_description ;
    }

    /**
     * Set Software Vendor
     *
     * @param string software vendor
     */
    function setSoftwareVendor($txt)
    {
        $this->_software_vendor = $txt ;
    }

    /**
     * Get Software Vendor
     *
     * @return string software vendor
     */
    function getSoftwareVendor()
    {
        return $this->_software_vendor ;
    }

    /**
     * Set Software Name
     *
     * @param string software name
     */
    function setSoftwareName($txt)
    {
        $this->_software_name = $txt ;
    }

    /**
     * Get Software Name
     *
     * @return string software name
     */
    function getSoftwareName()
    {
        return $this->_software_name ;
    }

    /**
     * Set Team Name
     *
     * @param string $txt team name
     */
    function setTeamName($txt)
    {
        $this->_team_name = $txt ;
    }

    /**
     * Get Team Name
     *
     * @return string team name
     */
    function getTeamName()
    {
        return $this->_team_name ;
    }

    /**
     * Set File Creation Date
     *
     * @param string file date
     */
    function setFileCreationDate($date)
    {
        $this->_file_creation_date = $date ;
    }

    /**
     * Get File Creation Date
     *
     * @return string file date
     */
    function getFileCreationDate()
    {
        return $this->_file_creation_date ;
    }

    /**
     * Set File Creation Time
     *
     * @param string file time
     */
    function setFileCreationTime($time)
    {
        $this->_file_creation_time = $time ;
    }

    /**
     * Get File Creation Time
     *
     * @return string file time
     */
    function getFileCreationTime()
    {
        return $this->_file_creation_time ;
    }

    /**
     * Parse an A1 HY3 record
     *
     * @return void
     */
    function ParseRecord()
    {
        $c = container() ;
        if (WPST_DEBUG)
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1, WPST_HYTEK_COLUMN_DEBUG2,
                WPST_HYTEK_COLUMN_DEBUG3, $this->_hy3_record)) ;

        //  Extract the data from the HY3 record by substring position

        $this->setFileCode(trim(substr($this->_hy3_record, 2, 2))) ;
        $this->setFileDescription(trim(substr($this->_hy3_record, 4, 24))) ;
        $this->setSoftwareVendor(trim(substr($this->_hy3_record, 29, 16))) ;
        $this->setSoftwareName(trim(substr($this->_hy3_record, 44, 14))) ;
        $this->setFileCreationDate(trim(substr($this->_hy3_record, 58, 8))) ;
        $this->setFileCreationTime(trim(substr($this->_hy3_record, 67, 8))) ;
        $this->setTeamName(trim(substr($this->_hy3_record, 75, 52))) ;
    }

    /**
     * Generate an A1 HY3 record
     *
     * @return string - A1 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_A1_RECORD,
            $this->getFileCode(),
            $this->getFileDescription(),
            $this->getSoftwareVendor(),
            $this->getSoftwareName(),
            $this->getFileCreationDate(),
            WPST_HY3_UNUSED,
            $this->getFileCreationTime(),
            $this->getTeamName(),
            WPST_HY3_NO_VALUE
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 Bx record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3Record
 */
class HY3BxRecord extends HY3Record
{
    /**
     * Meet Name
     */
    var $_meet_name ;

    /**
     * Meet Address Line 1
     */
    var $_meet_address_1 ;

    /**
     * Meet Address Line 2
     */
    var $_meet_address_2 ;

    /**
     * Meet City
     */
    var $_meet_city ;

    /**
     * Meet State
     */
    var $_meet_state ;

    /**
     * Meet Postal Code
     */
    var $_meet_postal_code ;

    /**
     * Meet Country Code
     */
    var $_meet_country_code ;

    /**
     * Meet Phone
     */
    var $_meet_host_phone ;

    /**
     * Meet Code
     */
    var $_meet_code ;

    /**
     * Meet Start
     */
    var $_meet_start ;

    /**
     * Meet Start for database
     */
    var $_meet_start_db ;

    /**
     * Meet End
     */
    var $_meet_end ;

    /**
     * Meet End for database
     */
    var $_meet_end_db ;

    /**
     * Pool Altitude
     */
    var $_pool_altitude ;

    /**
     * Set Meet Name
     *
     * @param string meet name
     */
    function setMeetName($txt)
    {
        $this->_meet_name = $txt ;
    }

    /**
     * Get Meet Name
     *
     * @return string meet name
     */
    function getMeetName()
    {
        return $this->_meet_name ;
    }

    /**
     * Set Meet Address 1
     *
     * @param string meet address 1
     */
    function setMeetAddress1($txt)
    {
        $this->_meet_address_1 = $txt ;
    }

    /**
     * Get Meet Address 1
     *
     * @return string meet address 1
     */
    function getMeetAddress1()
    {
        return $this->_meet_address_1 ;
    }

    /**
     * Set Meet Address 2
     *
     * @param string meet address 2
     */
    function setMeetAddress2($txt)
    {
        $this->_meet_address_2 = $txt ;
    }

    /**
     * Get Meet Address 2
     *
     * @return string meet address 2
     */
    function getMeetAddress2()
    {
        return $this->_meet_address_2 ;
    }

    /**
     * Set Meet City
     *
     * @param string meet city
     */
    function setMeetCity($txt)
    {
        $this->_meet_city = $txt ;
    }

    /**
     * Get Meet City
     *
     * @return string meet city
     */
    function getMeetCity()
    {
        return $this->_meet_city ;
    }

    /**
     * Set Meet State
     *
     * @param string meet state
     */
    function setMeetState($txt)
    {
        $this->_meet_state = $txt ;
    }

    /**
     * Get Meet State
     *
     * @return string meet state
     */
    function getMeetState()
    {
        return $this->_meet_state ;
    }

    /**
     * Set Meet Postal Code
     *
     * @param string meet postal code
     */
    function setMeetPostalCode($txt)
    {
        $this->_meet_postal_code = $txt ;
    }

    /**
     * Get Meet Postal Code
     *
     * @return string meet postal code
     */
    function getMeetPostalCode()
    {
        return $this->_meet_postal_code ;
    }

    /**
     * Set Meet Country Code
     *
     * @param string meet country code
     */
    function setMeetCountryCode($txt)
    {
        $this->_meet_country_code = $txt ;
    }

    /**
     * Get Meet Country Code
     *
     * @return string meet country code
     */
    function getMeetCountryCode()
    {
        return $this->_meet_country_code ;
    }

    /**
     * Set Meet Host Phone
     *
     * @param string meet host phone
     */
    function setMeetHostPhone($txt)
    {
        $this->_meet_host_phone = $txt ;
    }

    /**
     * Get Meet Host Phone
     *
     * @return string meet host phone
     */
    function getMeetHostPhone()
    {
        return $this->_meet_host_phone ;
    }

    /**
     * Set Meet Code
     *
     * @param string meet code
     */
    function setMeetCode($txt)
    {
        $this->_meet_code = $txt ;
    }

    /**
     * Get Meet Code
     *
     * @return string meet code
     */
    function getMeetCode()
    {
        return $this->_meet_code ;
    }

    /**
     * Set Meet Start
     *
     * @param string meet start
     * @param boolean date provided in database format
     */
    function setMeetStart($txt, $db = false)
    {
        if ($db)
        {
            $this->_meet_start_db = $txt ;
 
            //  The meet start date is stored in YYYY-MM-DD in the database but
            //  HY3 B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_meet_start_db ;

            $this->_meet_start = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_meet_start = $txt ;
 
            //  The meet start date is stored in MMDDYYYY format in the HY3 B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_meet_start ;

            $this->_meet_start_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Meet Start
     *
     * @param boolean date returned in database format
     * @return string meet start
     */
    function getMeetStart($db = true)
    {
        if ($db)
            return $this->_meet_start_db ;
        else
            return $this->_meet_start ;
    }

    /**
     * Set Meet End
     *
     * @param string meet end
     * @param boolean date provided in database format
     */
    function setMeetEnd($txt, $db = false)
    {
        if ($db)
        {
            $this->_meet_end_db = $txt ;
 
            //  The meet end date is stored in YYYY-MM-DD in the database but
            //  HY3 B1 record expects it in MMDDYYYY format so the dates are
            //  reformatted appropriately.

            $date = &$this->_meet_end_db ;

            $this->_meet_end = sprintf('%02s%02s%04s',
                substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) ;
        }
        else
        {
            $this->_meet_end = $txt ;
 
            //  The meet end date is stored in MMDDYYYY format in the HY3 B1
            //  record.  The database needs dates in YYYY-MM-DD format so the
            //  dates are reformatted appropriately.

            $date = &$this->_meet_end ;

            $this->_meet_end_db = sprintf('%04s-%02s-%02s',
                substr($date, 4, 4), substr($date, 0, 2), substr($date, 2, 2)) ;
        }
    }

    /**
     * Get Meet End
     *
     * @param boolean date returned in database format
     * @return string meet end
     */
    function getMeetEnd($db = true)
    {
        if ($db)
            return $this->_meet_end_db ;
        else
            return $this->_meet_end ;
    }

    /**
     * Set Pool Altitude
     *
     * @param string pool altitude
     */
    function setPoolAltitude($txt)
    {
        $this->_pool_altitude = $txt ;
    }

    /**
     * Get Pool Altitude
     *
     * @return string pool altitude
     */
    function getPoolAltitude()
    {
        return $this->_pool_altitude ;
    }
}

/**
 * HY3 B1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3BxRecord
 */
class HY3B1Record extends HY3BxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        $c = container() ;
        if (WPST_DEBUG)
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1,
                WPST_HYTEK_COLUMN_DEBUG2, $this->_hy3_record)) ;
        //print $c->render() ;

        //  This doesn't work right and I am not sure why ...
        //  it ends reading data from the wrong character position.

        //$success = sscanf($this->_hy3_record, WPST_HYTEK_B1_RECORD,
        //    $this->_org_code,
        //    $this->_future_use_1,
        //    $this->_meet_name,
        //    $this->_meet_address_1,
        //    $this->_meet_address_2,
        //    $this->_meet_city,
        //    $this->_meet_state,
        //    $this->_meet_postal_code,
        //    $this->_meet_country_code,
        //    $this->_meet_code,
        //    $this->_meet_start,
        //    $this->_meet_end,
        //    $this->_pool_altitude,
        //    $this->_future_use_2,
        //    $this->_course_code,
        //    $this->_future_use_3) ;
        /**
         * Build the B1 record
         */

        //  Extract the data from the HY3 record by substring position

        $this->setOrgCode(trim(substr($this->_hy3_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_hy3_record, 3, 8))) ;
        $this->setMeetName(trim(substr($this->_hy3_record, 11, 30))) ;
        $this->setMeetAddress1(trim(substr($this->_hy3_record, 41, 22))) ;
        $this->setMeetAddress2(trim(substr($this->_hy3_record, 63, 22))) ;
        $this->setMeetCity(trim(substr($this->_hy3_record, 85, 20))) ;
        $this->setMeetState(trim(substr($this->_hy3_record, 105, 2))) ;
        $this->setMeetPostalCode(trim(substr($this->_hy3_record, 107, 10))) ;
        $this->setMeetCountryCode(trim(substr($this->_hy3_record, 117, 3))) ;
        $this->setMeetCode(trim(substr($this->_hy3_record, 120, 1))) ;
        $this->setMeetStart(trim(substr($this->_hy3_record, 121, 8)), false) ;
        $this->setMeetEnd(trim(substr($this->_hy3_record, 129, 8)), false) ;
        $this->setPoolAltitude(trim(substr($this->_hy3_record, 137, 4))) ;
        $this->setFutureUse2(trim(substr($this->_hy3_record, 141, 8))) ;
        $this->setCourseCode(trim(substr($this->_hy3_record, 149, 1))) ;
        $this->setFutureUse3(trim(substr($this->_hy3_record, 150, 10))) ;
    }

    /**
     * Generate Record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_HYTEK_B1_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getMeetName(),
            $this->getMeetAddress1(),
            $this->getMeetAddress2(),
            $this->getMeetCity(),
            $this->getMeetState(),
            $this->getMeetPostalCode(),
            $this->getMeetCountryCode(),
            $this->getMeetCode(),
            $this->getMeetStart(false),
            $this->getMeetEnd(false),
            $this->getPoolAltitude(),
            $this->getFutureUse2(),
            $this->getCourseCode(),
            $this->getFutureUse3()
        ) ;
    }
}

/**
 * HY3 B2 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3BxRecord
 */
class HY3B2Record extends HY3BxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        $c = container() ;
        if (WPST_DEBUG)
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1,
                WPST_HYTEK_COLUMN_DEBUG2, $this->_hy3_record)) ;
        //print $c->render() ;

        //  This doesn't work right and I am not sure why ...
        //  it ends reading data from the wrong character position.

        //$success = sscanf($this->_hy3_record, WPST_HYTEK_B1_RECORD,
        //    $this->_org_code,
        //    $this->_future_use_1,
        //    $this->_meet_name,
        //    $this->_meet_address_1,
        //    $this->_meet_address_2,
        //    $this->_meet_city,
        //    $this->_meet_state,
        //    $this->_meet_postal_code,
        //    $this->_meet_country_code,
        //    $this->_meet_code,
        //    $this->_meet_start,
        //    $this->_meet_end,
        //    $this->_pool_altitude,
        //    $this->_future_use_2,
        //    $this->_course_code,
        //    $this->_future_use_3) ;

        //  Extract the data from the HY3 record by substring position

        $this->setOrgCode(trim(substr($this->_hy3_record, 2, 1))) ;
        $this->setFutureUse1(trim(substr($this->_hy3_record, 3, 8))) ;
        $this->setMeetName(trim(substr($this->_hy3_record, 11, 30))) ;
        $this->setMeetAddress1(trim(substr($this->_hy3_record, 41, 22))) ;
        $this->setMeetAddress2(trim(substr($this->_hy3_record, 63, 22))) ;
        $this->setMeetCity(trim(substr($this->_hy3_record, 85, 20))) ;
        $this->setMeetState(trim(substr($this->_hy3_record, 105, 2))) ;
        $this->setMeetPostalCode(trim(substr($this->_hy3_record, 107, 10))) ;
        $this->setMeetCountryCode(trim(substr($this->_hy3_record, 117, 3))) ;
        $this->setMeetHostPhone(trim(substr($this->_hy3_record, 120, 1))) ;
        $this->setFutureUse2(trim(substr($this->_hy3_record, 141, 8))) ;
    }

    /**
     * Generate Record
     */
    function GenerateRecord()
    {
        return sprintf(WPST_HYTEK_B2_RECORD,
            $this->getOrgCode(),
            $this->getFutureUse1(),
            $this->getMeetName(),
            $this->getMeetAddress1(),
            $this->getMeetAddress2(),
            $this->getMeetCity(),
            $this->getMeetState(),
            $this->getMeetPostalCode(),
            $this->getMeetCountryCode(),
            $this->getMeetHostPhone(),
            $this->getFutureUse2()
        ) ;
    }
}

/**
 * HY3 C1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3Record
 */
class HY3C1Record extends HY3Record
{
    /**
     * Team Name Abbreviation
     */
    var $_team_name_abrv ;

    /**
     * Team Full Name
     */
    var $_team_full_name ;

    /**
     * Team Name
     */
    var $_team_name ;

    /**
     * Team LSC
     */
    var $_team_lsc ;

    /**
     * Team Type
     */
    var $_team_type ;

    /**
     * Set Team Name Abbreviation
     *
     * @param string team name abreviation
     */
    function setTeamNameAbrv($txt)
    {
        $this->_team_name_abrv = $txt ;
    }

    /**
     * Get Team Name Abreviation
     *
     * @return string team name abreviation
     */
    function getTeamNameAbrv()
    {
        return $this->_team_name_abrv ;
    }

    /**
     * Set Team Full Name
     *
     * @param string team full name
     */
    function setTeamFullName($txt)
    {
        $this->_team_full_name = $txt ;
    }

    /**
     * Get Team Full Name
     *
     * @return string team name
     */
    function getTeamFullName()
    {
        return $this->_team_full_name ;
    }

    /**
     * Set Team Name
     *
     * @param string team full name
     */
    function setTeamName($txt)
    {
        $this->_team_name_full = $txt ;
    }

    /**
     * Get Team Name
     *
     * @return string team name
     */
    function getTeamName()
    {
        return $this->_team_name ;
    }

    /**
     * Set Team LSC
     *
     * @param string team full lsc
     */
    function setTeamLSC($txt)
    {
        $this->_team_lsc = $txt ;
    }

    /**
     * Get Team LSC
     *
     * @return string team lsc
     */
    function getTeamLSC()
    {
        return $this->_team_lsc ;
    }

    /**
     * Set Team Type
     *
     * @param string team full type
     */
    function setTeamType($txt)
    {
        $this->_team_type = $txt ;
    }

    /**
     * Get Team Type
     *
     * @return string team type
     */
    function getTeamType()
    {
        return $this->_team_type ;
    }

    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1,
                WPST_HYTEK_COLUMN_DEBUG2, $this->_hy3_record)) ;
            //print $c->render() ;
        }

        //  Extract the data from the HY3 record by substring position

        $this->setTeamNameAbrv(trim(substr($this->_hy3_record, 2, 5))) ;
        $this->setTeamFullName(trim(substr($this->_hy3_record, 7, 30))) ;
        $this->setTeamName(trim(substr($this->_hy3_record, 37, 16))) ;
        $this->setTeamLSC(trim(substr($this->_hy3_record, 51, 2))) ;
        $this->setTeamType(trim(substr($this->_hy3_record, 119, 3))) ;
    }

    /**
     * Generate Record
     *
     * @return string - C1 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_C1_RECORD,
            $this->getTeamNameAbrv(),
            $this->getTeamFullName(),
            $this->getTeamName(),
            $this->getTeamLSC(),
            WPST_HY3_UNUSED,
            $this->getTeamType(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 C2 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3Record
 */
class HY3C2Record extends HY3Record
{
    /**
     * Team Address Line 1
     */
    var $_team_address_1 ;

    /**
     * Team Address Line 2
     */
    var $_team_address_2 ;

    /**
     * Team City
     */
    var $_team_city ;

    /**
     * Team State
     */
    var $_team_state ;

    /**
     * Team Postal Code
     */
    var $_team_postal_code ;

    /**
     * Team Country Code
     */
    var $_team_country_code ;

    /**
     * Team Registration Code
     */
    var $_team_registration_code ;

    /**
     * Set Team Name
     *
     * @param string team name
     */
    function setTeamName($txt)
    {
        $this->_team_name = $txt ;
    }

    /**
     * Get Team Name
     *
     * @return string team name
     */
    function getTeamName()
    {
        return $this->_team_name ;
    }

    /**
     * Set Team Address 1
     *
     * @param string team address 1
     */
    function setTeamAddress1($txt)
    {
        $this->_team_address_1 = $txt ;
    }

    /**
     * Get Team Address 1
     *
     * @return string team address 1
     */
    function getTeamAddress1()
    {
        return $this->_team_address_1 ;
    }

    /**
     * Set Team Address 2
     *
     * @param string team address 2
     */
    function setTeamAddress2($txt)
    {
        $this->_team_address_2 = $txt ;
    }

    /**
     * Get Team Address 2
     *
     * @return string team address 2
     */
    function getTeamAddress2()
    {
        return $this->_team_address_2 ;
    }

    /**
     * Set Team City
     *
     * @param string team city
     */
    function setTeamCity($txt)
    {
        $this->_team_city = $txt ;
    }

    /**
     * Get Team City
     *
     * @return string team city
     */
    function getTeamCity()
    {
        return $this->_team_city ;
    }

    /**
     * Set Team State
     *
     * @param string team state
     */
    function setTeamState($txt)
    {
        $this->_team_state = $txt ;
    }

    /**
     * Get Team State
     *
     * @return string team state
     */
    function getTeamState()
    {
        return $this->_team_state ;
    }

    /**
     * Set Team Postal Code
     *
     * @param string team postal code
     */
    function setTeamPostalCode($txt)
    {
        $this->_team_postal_code = $txt ;
    }

    /**
     * Get Team Postal Code
     *
     * @return string team postal code
     */
    function getTeamPostalCode()
    {
        return $this->_team_postal_code ;
    }

    /**
     * Set Team Country Code
     *
     * @param string team country code
     */
    function setTeamCountryCode($txt)
    {
        $this->_team_country_code = $txt ;
    }

    /**
     * Get Team Country Code
     *
     * @return string team country code
     */
    function getTeamCountryCode()
    {
        return $this->_team_country_code ;
    }

    /**
     * Set Team Registration Code
     *
     * @param string team registration code
     */
    function setTeamRegistrationCode($txt)
    {
        $this->_team_registration_code = $txt ;
    }

    /**
     * Get Team Registration Code
     *
     * @return string team registration code
     */
    function getTeamRegistrationCode()
    {
        return $this->_team_registration_code ;
    }

    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1,
                WPST_HYTEK_COLUMN_DEBUG2, $this->_hy3_record)) ;
            //print $c->render() ;
        }

        //  Extract the data from the HY3 record by substring position

        $this->setTeamAddress1(trim(substr($this->_hy3_record, 2, 30))) ;
        $this->setTeamAddress2(trim(substr($this->_hy3_record, 32, 30))) ;
        $this->setTeamCity(trim(substr($this->_hy3_record, 62, 30))) ;
        $this->setTeamState(trim(substr($this->_hy3_record, 92, 2))) ;
        $this->setTeamPostalCode(trim(substr($this->_hy3_record, 94, 10))) ;
        $this->setTeamCountryCode(trim(substr($this->_hy3_record, 104, 3))) ;
        $this->setTeamRegistrationCode(trim(substr($this->_hy3_record, 108, 4))) ;
        $this->setChecksum(trim(substr($this->_hy3_record, 150, 128))) ;
    }

    /**
     * Generate Record
     *
     * @return string - C1 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_C2_RECORD,
            $this->getTeamAddress1(),
            $this->getTeamAddress2(),
            $this->getTeamCity(),
            $this->getTeamState(),
            $this->getTeamPostalCode(),
            $this->getTeamCountryCode(),
            WPST_HY3_UNUSED,
            $this->getTeamRegistrationCode(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 C3 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3C1Record
 */
class HY3C3Record extends HY3C1Record
{
    /**
     * Team Secondary Phone
     */
    var $_team_secondary_phone ;

    /**
     * Team Fax
     */
    var $_team_fax ;

    /**
     * Team Email
     */
    var $_team_email ;

    /**
     * Set Team Secondary Phone
     *
     * @param string team secondary phone
     */
    function setTeamSecondaryPhone($txt)
    {
        $this->_team_secondary_phone = $txt ;
    }

    /**
     * Get TeamSecondary Phone
     *
     * @return string team secondary phone
     */
    function getTeamSecondaryPhone()
    {
        return $this->_team_secondary_phone ;
    }

    /**
     * Set Team Fax
     *
     * @param string team fax
     */
    function setTeamFax($txt)
    {
        $this->_team_fax = $txt ;
    }

    /**
     * Get TeamFax
     *
     * @return string team fax
     */
    function getTeamFax()
    {
        return $this->_team_fax ;
    }

    /**
     * Set Team Email
     *
     * @param string team email
     */
    function setTeamEmail($txt)
    {
        $this->_team_email = $txt ;
    }

    /**
     * Get TeamEmail
     *
     * @return string team email
     */
    function getTeamEmail()
    {
        return $this->_team_email ;
    }

    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1,
                WPST_HYTEK_COLUMN_DEBUG2, $this->_hy3_record)) ;
            //print $c->render() ;
        }

        //  Extract the data from the HY3 record by substring position

        $this->setPhoneNumber(trim(substr($this->_hy3_record, 32, 30))) ;
        $this->setTeamSecondaryPhone(trim(substr($this->_hy3_record, 52, 20))) ;
        $this->setTeamFax(trim(substr($this->_hy3_record, 72, 20))) ;
        $this->setTeamEmail(trim(substr($this->_hy3_record, 92, 36))) ;
        $this->setChecksum(trim(substr($this->_hy3_record, 150, 128))) ;
    }

    /**
     * Generate Record
     *
     * @return string - C3 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_C3_RECORD,
            WPST_HY3_UNUSED,
            $this->getPhoneNumber(),
            $this->getTeamSecondaryPhone(),
            $this->getTeamFax(),
            $this->getTeamEmail(),
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 Dx record - base record for all Dx records
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3Record
 */
class HY3DxRecord extends HY3Record
{
    /**
     *   Gender property
     */
    var $__gender ;

    /**
     *   Database Id1 property
     */
    var $__database_id1 ;

    /**
     *   Athlete Last Name property
     */
    var $__athlete_last_name ;

    /**
     *   Athlete First Name property
     */
    var $__athlete_first_name ;

    /**
     *   Athlete Nickname property
     */
    var $__athlete_nickname ;

    /**
     *   Athlete Middle Initial property
     */
    var $__athlete_middle_initial ;

    /**
     *   Athlete Id property
     */
    var $__athlete_id ;

    /**
     *   Database Id2 property
     */
    var $__database_id2 ;

    /**
     *   Athlete Birth Date property
     */
    var $__athlete_birth_date ;

    /**
     *   Athlete Age property
     */
    var $__athlete_age ;

    /**
     *   Athlete School Year property
     */
    var $__athlete_school_year ;

    /**
     *   Group property
     */
    var $__group ;

    /**
     *   Subgroup property
     */
    var $__subgroup ;

    /**
     *   Inactive property
     */
    var $__inactive ;

    /**
     *   Registration Country property
     */
    var $__registration_country ;

    /**
     *   Wm Group property
     */
    var $__wm_group ;

    /**
     *   Wm Subgroup property
     */
    var $__wm_subgroup ;

    /**
     *   Primary Contact Address1 property
     */
    var $__primary_contact_address1 ;

    /**
     *   Primary Contact Address2 property
     */
    var $__primary_contact_address2 ;

    /**
     *   Primary Contact City property
     */
    var $__primary_contact_city ;

    /**
     *   Primary Contact State property
     */
    var $__primary_contact_state ;

    /**
     *   Primary Contact Postal Code property
     */
    var $__primary_contact_postal_code ;

    /**
     *   Primary Contact Country property
     */
    var $__primary_contact_country ;

    /**
     *   Fathers Office Phone Number property
     */
    var $__fathers_office_phone_number ;

    /**
     *   Primary Contact Home Phone Number property
     */
    var $__primary_contact_home_phone_number ;

    /**
     *   Primary Contact Fax Number property
     */
    var $__primary_contact_fax_number ;

    /**
     *   Fathers Email Address property
     */
    var $__fathers_email_address ;

    /**
     *   Secondary Contact Mailto property
     */
    var $__secondary_contact_mailto ;

    /**
     *   Secondary Contact Address1 property
     */
    var $__secondary_contact_address1 ;

    /**
     *   Secondary Contact City property
     */
    var $__secondary_contact_city ;

    /**
     *   Secondary Contact State property
     */
    var $__secondary_contact_state ;

    /**
     *   Secondary Contact Postal Code property
     */
    var $__secondary_contact_postal_code ;

    /**
     *   Secondary Contact Country property
     */
    var $__secondary_contact_country ;

    /**
     *   Primary Contact Mailto property
     */
    var $__primary_contact_mailto ;

    /**
     *   Secondary Contact Address2 property
     */
    var $__secondary_contact_address2 ;

    /**
     *   Registration Date property
     */
    var $__registration_date ;

    /**
     *   Doctors Name property
     */
    var $__doctors_name ;

    /**
     *   Doctors Phone Number property
     */
    var $__doctors_phone_number ;

    /**
     *   Emergency Contact Name property
     */
    var $__emergency_contact_name ;

    /**
     *   Emergency Contact Phone Number property
     */
    var $__emergency_contact_phone_number ;

    /**
     *   Secondary Contact Parent1 Office Phone Number property
     */
    var $__secondary_contact_parent1_office_phone_number ;

    /**
     *   Secondary Contact Home Phone Number property
     */
    var $__secondary_contact_home_phone_number ;

    /**
     *   Secondary Contact Fax Number property
     */
    var $__secondary_contact_fax_number ;

    /**
     *   Secondary Contact Parent1 Email Address property
     */
    var $__secondary_contact_parent1_email_address ;

    /**
     *   Medical Condition Description property
     */
    var $__medical_condition_description ;

    /**
     *   Medication Description property
     */
    var $__medication_description ;

    /**
     *   Custom Field 1 Name property
     */
    var $__custom_field_1_name ;

    /**
     *   Custom Field 1 Value property
     */
    var $__custom_field_1_value ;

    /**
     *   Custom Field 2 Name property
     */
    var $__custom_field_2_name ;

    /**
     *   Custom Field 2 Value property
     */
    var $__custom_field_2_value ;

    /**
     *   Custom Field 3 Name property
     */
    var $__custom_field_3_name ;

    /**
     *   Custom Field 3 Value property
     */
    var $__custom_field_3_value ;

    /**
     *   Fathers Last Name property
     */
    var $__fathers_last_name ;

    /**
     *   Fathers First Name property
     */
    var $__fathers_first_name ;

    /**
     *   Mothers First Name property
     */
    var $__mothers_first_name ;

    /**
     *   Secondary Contact Last Name property
     */
    var $__secondary_contact_last_name ;

    /**
     *   Secondary Contact Parent1 Name property
     */
    var $__secondary_contact_parent1_name ;

    /**
     *   Secondary Contact Parent2 Name property
     */
    var $__secondary_contact_parent2_name ;

    /**
     *   Fathers Cell Phone Number property
     */
    var $__fathers_cell_phone_number ;

    /**
     *   Mothers Office Phone Number property
     */
    var $__mothers_office_phone_number ;

    /**
     *   Mothers Cell Phone Number property
     */
    var $__mothers_cell_phone_number ;

    /**
     *   Secondary Contact Parent1 Cell Phone Number property
     */
    var $__secondary_contact_parent1_cell_phone_number ;

    /**
     *   Secondary Contact Parent2 Office Phone Number property
     */
    var $__secondary_contact_parent2_office_phone_number ;

    /**
     *   Secondary Contact Parent2 Cell Phone Number property
     */
    var $__secondary_contact_parent2_cell_phone_number ;

    /**
     *   Mothers Email Address property
     */
    var $__mothers_email_address ;

    /**
     *   Secondary Contact Parent2 Email Address property
     */
    var $__secondary_contact_parent2_email_address ;

    /**
     *   Mothers Last Name property
     */
    var $__mothers_last_name ;

    /**
     *   Athletes Middle Name property
     */
    var $__athletes_middle_name ;

    /**
     *   Athletes Cell Phone Number property
     */
    var $__athletes_cell_phone_number ;

    /**
     *   Athletes Email Address property
     */
    var $__athletes_email_address ;

    /**
     *  Set Gender property
     *
     *  @param string -  gender
     */
    function setGender($value = null)
    {
        $this->__gender = $value ;
    }

    /**
     *  Get Gender property
     *
     *  @return string -  gender
     */
    function getGender()
    {
        return $this->__gender ;
    }

    /**
     *  Set Database Id1 property
     *
     *  @param string -  database id1
     */
    function setDatabaseId1($value = null)
    {
        $this->__database_id1 = $value ;
    }

    /**
     *  Get Database Id1 property
     *
     *  @return string -  database id1
     */
    function getDatabaseId1()
    {
        return $this->__database_id1 ;
    }

    /**
     *  Set Athlete Last Name property
     *
     *  @param string -  athlete last name
     */
    function setAthleteLastName($value = null)
    {
        $this->__athlete_last_name = $value ;
    }

    /**
     *  Get Athlete Last Name property
     *
     *  @return string -  athlete last name
     */
    function getAthleteLastName()
    {
        return $this->__athlete_last_name ;
    }

    /**
     *  Set Athlete First Name property
     *
     *  @param string -  athlete first name
     */
    function setAthleteFirstName($value = null)
    {
        $this->__athlete_first_name = $value ;
    }

    /**
     *  Get Athlete First Name property
     *
     *  @return string -  athlete first name
     */
    function getAthleteFirstName()
    {
        return $this->__athlete_first_name ;
    }

    /**
     *  Set Athlete Nickname property
     *
     *  @param string -  athlete nickname
     */
    function setAthleteNickname($value = null)
    {
        $this->__athlete_nickname = $value ;
    }

    /**
     *  Get Athlete Nickname property
     *
     *  @return string -  athlete nickname
     */
    function getAthleteNickname()
    {
        return $this->__athlete_nickname ;
    }

    /**
     *  Set Athlete Middle Initial property
     *
     *  @param string -  athlete middle initial
     */
    function setAthleteMiddleInitial($value = null)
    {
        $this->__athlete_middle_initial = $value ;
    }

    /**
     *  Get Athlete Middle Initial property
     *
     *  @return string -  athlete middle initial
     */
    function getAthleteMiddleInitial()
    {
        return $this->__athlete_middle_initial ;
    }

    /**
     *  Set Athlete Id property
     *
     *  @param string -  athlete id
     */
    function setAthleteId($value = null)
    {
        $this->__athlete_id = $value ;
    }

    /**
     *  Get Athlete Id property
     *
     *  @return string -  athlete id
     */
    function getAthleteId()
    {
        return $this->__athlete_id ;
    }

    /**
     *  Set Database Id2 property
     *
     *  @param string -  database id2
     */
    function setDatabaseId2($value = null)
    {
        $this->__database_id2 = $value ;
    }

    /**
     *  Get Database Id2 property
     *
     *  @return string -  database id2
     */
    function getDatabaseId2()
    {
        return $this->__database_id2 ;
    }

    /**
     *  Set Athlete Birth Date property
     *
     *  @param string -  athlete birth date
     */
    function setAthleteBirthDate($value = null)
    {
        $this->__athlete_birth_date = $value ;
    }

    /**
     *  Get Athlete Birth Date property
     *
     *  @return string -  athlete birth date
     */
    function getAthleteBirthDate()
    {
        return $this->__athlete_birth_date ;
    }

    /**
     *  Set Athlete Age property
     *
     *  @param string -  athlete age
     */
    function setAthleteAge($value = null)
    {
        $this->__athlete_age = $value ;
    }

    /**
     *  Get Athlete Age property
     *
     *  @return string -  athlete age
     */
    function getAthleteAge()
    {
        return $this->__athlete_age ;
    }

    /**
     *  Set Athlete School Year property
     *
     *  @param string -  athlete school year
     */
    function setAthleteSchoolYear($value = null)
    {
        $this->__athlete_school_year = $value ;
    }

    /**
     *  Get Athlete School Year property
     *
     *  @return string -  athlete school year
     */
    function getAthleteSchoolYear()
    {
        return $this->__athlete_school_year ;
    }

    /**
     *  Set Group property
     *
     *  @param string -  group
     */
    function setGroup($value = null)
    {
        $this->__group = $value ;
    }

    /**
     *  Get Group property
     *
     *  @return string -  group
     */
    function getGroup()
    {
        return $this->__group ;
    }

    /**
     *  Set Subgroup property
     *
     *  @param string -  subgroup
     */
    function setSubgroup($value = null)
    {
        $this->__subgroup = $value ;
    }

    /**
     *  Get Subgroup property
     *
     *  @return string -  subgroup
     */
    function getSubgroup()
    {
        return $this->__subgroup ;
    }

    /**
     *  Set Inactive property
     *
     *  @param string -  inactive
     */
    function setInactive($value = null)
    {
        $this->__inactive = $value ;
    }

    /**
     *  Get Inactive property
     *
     *  @return string -  inactive
     */
    function getInactive()
    {
        return $this->__inactive ;
    }

    /**
     *  Set Registration Country property
     *
     *  @param string -  registration country
     */
    function setRegistrationCountry($value = null)
    {
        $this->__registration_country = $value ;
    }

    /**
     *  Get Registration Country property
     *
     *  @return string -  registration country
     */
    function getRegistrationCountry()
    {
        return $this->__registration_country ;
    }

    /**
     *  Set Wm Group property
     *
     *  @param string -  wm group
     */
    function setWmGroup($value = null)
    {
        $this->__wm_group = $value ;
    }

    /**
     *  Get Wm Group property
     *
     *  @return string -  wm group
     */
    function getWmGroup()
    {
        return $this->__wm_group ;
    }

    /**
     *  Set Wm Subgroup property
     *
     *  @param string -  wm subgroup
     */
    function setWmSubgroup($value = null)
    {
        $this->__wm_subgroup = $value ;
    }

    /**
     *  Get Wm Subgroup property
     *
     *  @return string -  wm subgroup
     */
    function getWmSubgroup()
    {
        return $this->__wm_subgroup ;
    }

    /**
     *  Set Primary Contact Address1 property
     *
     *  @param string -  primary contact address1
     */
    function setPrimaryContactAddress1($value = null)
    {
        $this->__primary_contact_address1 = $value ;
    }

    /**
     *  Get Primary Contact Address1 property
     *
     *  @return string -  primary contact address1
     */
    function getPrimaryContactAddress1()
    {
        return $this->__primary_contact_address1 ;
    }

    /**
     *  Set Primary Contact Address2 property
     *
     *  @param string -  primary contact address2
     */
    function setPrimaryContactAddress2($value = null)
    {
        $this->__primary_contact_address2 = $value ;
    }

    /**
     *  Get Primary Contact Address2 property
     *
     *  @return string -  primary contact address2
     */
    function getPrimaryContactAddress2()
    {
        return $this->__primary_contact_address2 ;
    }

    /**
     *  Set Primary Contact City property
     *
     *  @param string -  primary contact city
     */
    function setPrimaryContactCity($value = null)
    {
        $this->__primary_contact_city = $value ;
    }

    /**
     *  Get Primary Contact City property
     *
     *  @return string -  primary contact city
     */
    function getPrimaryContactCity()
    {
        return $this->__primary_contact_city ;
    }

    /**
     *  Set Primary Contact State property
     *
     *  @param string -  primary contact state
     */
    function setPrimaryContactState($value = null)
    {
        $this->__primary_contact_state = $value ;
    }

    /**
     *  Get Primary Contact State property
     *
     *  @return string -  primary contact state
     */
    function getPrimaryContactState()
    {
        return $this->__primary_contact_state ;
    }

    /**
     *  Set Primary Contact Postal Code property
     *
     *  @param string -  primary contact postal code
     */
    function setPrimaryContactPostalCode($value = null)
    {
        $this->__primary_contact_postal_code = $value ;
    }

    /**
     *  Get Primary Contact Postal Code property
     *
     *  @return string -  primary contact postal code
     */
    function getPrimaryContactPostalCode()
    {
        return $this->__primary_contact_postal_code ;
    }

    /**
     *  Set Primary Contact Country property
     *
     *  @param string -  primary contact country
     */
    function setPrimaryContactCountry($value = null)
    {
        $this->__primary_contact_country = $value ;
    }

    /**
     *  Get Primary Contact Country property
     *
     *  @return string -  primary contact country
     */
    function getPrimaryContactCountry()
    {
        return $this->__primary_contact_country ;
    }

    /**
     *  Set Fathers Office Phone Number property
     *
     *  @param string -  fathers office phone number
     */
    function setFathersOfficePhoneNumber($value = null)
    {
        $this->__fathers_office_phone_number = $value ;
    }

    /**
     *  Get Fathers Office Phone Number property
     *
     *  @return string -  fathers office phone number
     */
    function getFathersOfficePhoneNumber()
    {
        return $this->__fathers_office_phone_number ;
    }

    /**
     *  Set Primary Contact Home Phone Number property
     *
     *  @param string -  primary contact home phone number
     */
    function setPrimaryContactHomePhoneNumber($value = null)
    {
        $this->__primary_contact_home_phone_number = $value ;
    }

    /**
     *  Get Primary Contact Home Phone Number property
     *
     *  @return string -  primary contact home phone number
     */
    function getPrimaryContactHomePhoneNumber()
    {
        return $this->__primary_contact_home_phone_number ;
    }

    /**
     *  Set Primary Contact Fax Number property
     *
     *  @param string -  primary contact fax number
     */
    function setPrimaryContactFaxNumber($value = null)
    {
        $this->__primary_contact_fax_number = $value ;
    }

    /**
     *  Get Primary Contact Fax Number property
     *
     *  @return string -  primary contact fax number
     */
    function getPrimaryContactFaxNumber()
    {
        return $this->__primary_contact_fax_number ;
    }

    /**
     *  Set Fathers Email Address property
     *
     *  @param string -  fathers email address
     */
    function setFathersEmailAddress($value = null)
    {
        $this->__fathers_email_address = $value ;
    }

    /**
     *  Get Fathers Email Address property
     *
     *  @return string -  fathers email address
     */
    function getFathersEmailAddress()
    {
        return $this->__fathers_email_address ;
    }

    /**
     *  Set Secondary Contact Mailto property
     *
     *  @param string -  secondary contact mailto
     */
    function setSecondaryContactMailto($value = null)
    {
        $this->__secondary_contact_mailto = $value ;
    }

    /**
     *  Get Secondary Contact Mailto property
     *
     *  @return string -  secondary contact mailto
     */
    function getSecondaryContactMailto()
    {
        return $this->__secondary_contact_mailto ;
    }

    /**
     *  Set Secondary Contact Address1 property
     *
     *  @param string -  secondary contact address1
     */
    function setSecondaryContactAddress1($value = null)
    {
        $this->__secondary_contact_address1 = $value ;
    }

    /**
     *  Get Secondary Contact Address1 property
     *
     *  @return string -  secondary contact address1
     */
    function getSecondaryContactAddress1()
    {
        return $this->__secondary_contact_address1 ;
    }

    /**
     *  Set Secondary Contact City property
     *
     *  @param string -  secondary contact city
     */
    function setSecondaryContactCity($value = null)
    {
        $this->__secondary_contact_city = $value ;
    }

    /**
     *  Get Secondary Contact City property
     *
     *  @return string -  secondary contact city
     */
    function getSecondaryContactCity()
    {
        return $this->__secondary_contact_city ;
    }

    /**
     *  Set Secondary Contact State property
     *
     *  @param string -  secondary contact state
     */
    function setSecondaryContactState($value = null)
    {
        $this->__secondary_contact_state = $value ;
    }

    /**
     *  Get Secondary Contact State property
     *
     *  @return string -  secondary contact state
     */
    function getSecondaryContactState()
    {
        return $this->__secondary_contact_state ;
    }

    /**
     *  Set Secondary Contact Postal Code property
     *
     *  @param string -  secondary contact postal code
     */
    function setSecondaryContactPostalCode($value = null)
    {
        $this->__secondary_contact_postal_code = $value ;
    }

    /**
     *  Get Secondary Contact Postal Code property
     *
     *  @return string -  secondary contact postal code
     */
    function getSecondaryContactPostalCode()
    {
        return $this->__secondary_contact_postal_code ;
    }

    /**
     *  Set Secondary Contact Country property
     *
     *  @param string -  secondary contact country
     */
    function setSecondaryContactCountry($value = null)
    {
        $this->__secondary_contact_country = $value ;
    }

    /**
     *  Get Secondary Contact Country property
     *
     *  @return string -  secondary contact country
     */
    function getSecondaryContactCountry()
    {
        return $this->__secondary_contact_country ;
    }

    /**
     *  Set Primary Contact Mailto property
     *
     *  @param string -  primary contact mailto
     */
    function setPrimaryContactMailto($value = null)
    {
        $this->__primary_contact_mailto = $value ;
    }

    /**
     *  Get Primary Contact Mailto property
     *
     *  @return string -  primary contact mailto
     */
    function getPrimaryContactMailto()
    {
        return $this->__primary_contact_mailto ;
    }

    /**
     *  Set Secondary Contact Address2 property
     *
     *  @param string -  secondary contact address2
     */
    function setSecondaryContactAddress2($value = null)
    {
        $this->__secondary_contact_address2 = $value ;
    }

    /**
     *  Get Secondary Contact Address2 property
     *
     *  @return string -  secondary contact address2
     */
    function getSecondaryContactAddress2()
    {
        return $this->__secondary_contact_address2 ;
    }

    /**
     *  Set Registration Date property
     *
     *  @param string -  registration date
     */
    function setRegistrationDate($value = null)
    {
        $this->__registration_date = $value ;
    }

    /**
     *  Get Registration Date property
     *
     *  @return string -  registration date
     */
    function getRegistrationDate()
    {
        return $this->__registration_date ;
    }

    /**
     *  Set Doctors Name property
     *
     *  @param string -  doctors name
     */
    function setDoctorsName($value = null)
    {
        $this->__doctors_name = $value ;
    }

    /**
     *  Get Doctors Name property
     *
     *  @return string -  doctors name
     */
    function getDoctorsName()
    {
        return $this->__doctors_name ;
    }

    /**
     *  Set Doctors Phone Number property
     *
     *  @param string -  doctors phone number
     */
    function setDoctorsPhoneNumber($value = null)
    {
        $this->__doctors_phone_number = $value ;
    }

    /**
     *  Get Doctors Phone Number property
     *
     *  @return string -  doctors phone number
     */
    function getDoctorsPhoneNumber()
    {
        return $this->__doctors_phone_number ;
    }

    /**
     *  Set Emergency Contact Name property
     *
     *  @param string -  emergency contact name
     */
    function setEmergencyContactName($value = null)
    {
        $this->__emergency_contact_name = $value ;
    }

    /**
     *  Get Emergency Contact Name property
     *
     *  @return string -  emergency contact name
     */
    function getEmergencyContactName()
    {
        return $this->__emergency_contact_name ;
    }

    /**
     *  Set Emergency Contact Phone Number property
     *
     *  @param string -  emergency contact phone number
     */
    function setEmergencyContactPhoneNumber($value = null)
    {
        $this->__emergency_contact_phone_number = $value ;
    }

    /**
     *  Get Emergency Contact Phone Number property
     *
     *  @return string -  emergency contact phone number
     */
    function getEmergencyContactPhoneNumber()
    {
        return $this->__emergency_contact_phone_number ;
    }

    /**
     *  Set Secondary Contact Parent1 Office Phone Number property
     *
     *  @param string -  secondary contact parent1 office phone number
     */
    function setSecondaryContactParent1OfficePhoneNumber($value = null)
    {
        $this->__secondary_contact_parent1_office_phone_number = $value ;
    }

    /**
     *  Get Secondary Contact Parent1 Office Phone Number property
     *
     *  @return string -  secondary contact parent1 office phone number
     */
    function getSecondaryContactParent1OfficePhoneNumber()
    {
        return $this->__secondary_contact_parent1_office_phone_number ;
    }

    /**
     *  Set Secondary Contact Home Phone Number property
     *
     *  @param string -  secondary contact home phone number
     */
    function setSecondaryContactHomePhoneNumber($value = null)
    {
        $this->__secondary_contact_home_phone_number = $value ;
    }

    /**
     *  Get Secondary Contact Home Phone Number property
     *
     *  @return string -  secondary contact home phone number
     */
    function getSecondaryContactHomePhoneNumber()
    {
        return $this->__secondary_contact_home_phone_number ;
    }

    /**
     *  Set Secondary Contact Fax Number property
     *
     *  @param string -  secondary contact fax number
     */
    function setSecondaryContactFaxNumber($value = null)
    {
        $this->__secondary_contact_fax_number = $value ;
    }

    /**
     *  Get Secondary Contact Fax Number property
     *
     *  @return string -  secondary contact fax number
     */
    function getSecondaryContactFaxNumber()
    {
        return $this->__secondary_contact_fax_number ;
    }

    /**
     *  Set Secondary Contact Parent1 Email Address property
     *
     *  @param string -  secondary contact parent1 email address
     */
    function setSecondaryContactParent1EmailAddress($value = null)
    {
        $this->__secondary_contact_parent1_email_address = $value ;
    }

    /**
     *  Get Secondary Contact Parent1 Email Address property
     *
     *  @return string -  secondary contact parent1 email address
     */
    function getSecondaryContactParent1EmailAddress()
    {
        return $this->__secondary_contact_parent1_email_address ;
    }

    /**
     *  Set Medical Condition Description property
     *
     *  @param string -  medical condition description
     */
    function setMedicalConditionDescription($value = null)
    {
        $this->__medical_condition_description = $value ;
    }

    /**
     *  Get Medical Condition Description property
     *
     *  @return string -  medical condition description
     */
    function getMedicalConditionDescription()
    {
        return $this->__medical_condition_description ;
    }

    /**
     *  Set Medication Description property
     *
     *  @param string -  medication description
     */
    function setMedicationDescription($value = null)
    {
        $this->__medication_description = $value ;
    }

    /**
     *  Get Medication Description property
     *
     *  @return string -  medication description
     */
    function getMedicationDescription()
    {
        return $this->__medication_description ;
    }

    /**
     *  Set Custom Field 1 Name property
     *
     *  @param string -  custom field 1 name
     */
    function setCustomField1Name($value = null)
    {
        $this->__custom_field_1_name = $value ;
    }

    /**
     *  Get Custom Field 1 Name property
     *
     *  @return string -  custom field 1 name
     */
    function getCustomField1Name()
    {
        return $this->__custom_field_1_name ;
    }

    /**
     *  Set Custom Field 1 Value property
     *
     *  @param string -  custom field 1 value
     */
    function setCustomField1Value($value = null)
    {
        $this->__custom_field_1_value = $value ;
    }

    /**
     *  Get Custom Field 1 Value property
     *
     *  @return string -  custom field 1 value
     */
    function getCustomField1Value()
    {
        return $this->__custom_field_1_value ;
    }

    /**
     *  Set Custom Field 2 Name property
     *
     *  @param string -  custom field 2 name
     */
    function setCustomField2Name($value = null)
    {
        $this->__custom_field_2_name = $value ;
    }

    /**
     *  Get Custom Field 2 Name property
     *
     *  @return string -  custom field 2 name
     */
    function getCustomField2Name()
    {
        return $this->__custom_field_2_name ;
    }

    /**
     *  Set Custom Field 2 Value property
     *
     *  @param string -  custom field 2 value
     */
    function setCustomField2Value($value = null)
    {
        $this->__custom_field_2_value = $value ;
    }

    /**
     *  Get Custom Field 2 Value property
     *
     *  @return string -  custom field 2 value
     */
    function getCustomField2Value()
    {
        return $this->__custom_field_2_value ;
    }

    /**
     *  Set Custom Field 3 Name property
     *
     *  @param string -  custom field 3 name
     */
    function setCustomField3Name($value = null)
    {
        $this->__custom_field_3_name = $value ;
    }

    /**
     *  Get Custom Field 3 Name property
     *
     *  @return string -  custom field 3 name
     */
    function getCustomField3Name()
    {
        return $this->__custom_field_3_name ;
    }

    /**
     *  Set Custom Field 3 Value property
     *
     *  @param string -  custom field 3 value
     */
    function setCustomField3Value($value = null)
    {
        $this->__custom_field_3_value = $value ;
    }

    /**
     *  Get Custom Field 3 Value property
     *
     *  @return string -  custom field 3 value
     */
    function getCustomField3Value()
    {
        return $this->__custom_field_3_value ;
    }

    /**
     *  Set Fathers Last Name property
     *
     *  @param string -  fathers last name
     */
    function setFathersLastName($value = null)
    {
        $this->__fathers_last_name = $value ;
    }

    /**
     *  Get Fathers Last Name property
     *
     *  @return string -  fathers last name
     */
    function getFathersLastName()
    {
        return $this->__fathers_last_name ;
    }

    /**
     *  Set Fathers First Name property
     *
     *  @param string -  fathers first name
     */
    function setFathersFirstName($value = null)
    {
        $this->__fathers_first_name = $value ;
    }

    /**
     *  Get Fathers First Name property
     *
     *  @return string -  fathers first name
     */
    function getFathersFirstName()
    {
        return $this->__fathers_first_name ;
    }

    /**
     *  Set Mothers First Name property
     *
     *  @param string -  mothers first name
     */
    function setMothersFirstName($value = null)
    {
        $this->__mothers_first_name = $value ;
    }

    /**
     *  Get Mothers First Name property
     *
     *  @return string -  mothers first name
     */
    function getMothersFirstName()
    {
        return $this->__mothers_first_name ;
    }

    /**
     *  Set Secondary Contact Last Name property
     *
     *  @param string -  secondary contact last name
     */
    function setSecondaryContactLastName($value = null)
    {
        $this->__secondary_contact_last_name = $value ;
    }

    /**
     *  Get Secondary Contact Last Name property
     *
     *  @return string -  secondary contact last name
     */
    function getSecondaryContactLastName()
    {
        return $this->__secondary_contact_last_name ;
    }

    /**
     *  Set Secondary Contact Parent1 Name property
     *
     *  @param string -  secondary contact parent1 name
     */
    function setSecondaryContactParent1Name($value = null)
    {
        $this->__secondary_contact_parent1_name = $value ;
    }

    /**
     *  Get Secondary Contact Parent1 Name property
     *
     *  @return string -  secondary contact parent1 name
     */
    function getSecondaryContactParent1Name()
    {
        return $this->__secondary_contact_parent1_name ;
    }

    /**
     *  Set Secondary Contact Parent2 Name property
     *
     *  @param string -  secondary contact parent2 name
     */
    function setSecondaryContactParent2Name($value = null)
    {
        $this->__secondary_contact_parent2_name = $value ;
    }

    /**
     *  Get Secondary Contact Parent2 Name property
     *
     *  @return string -  secondary contact parent2 name
     */
    function getSecondaryContactParent2Name()
    {
        return $this->__secondary_contact_parent2_name ;
    }

    /**
     *  Set Fathers Cell Phone Number property
     *
     *  @param string -  fathers cell phone number
     */
    function setFathersCellPhoneNumber($value = null)
    {
        $this->__fathers_cell_phone_number = $value ;
    }

    /**
     *  Get Fathers Cell Phone Number property
     *
     *  @return string -  fathers cell phone number
     */
    function getFathersCellPhoneNumber()
    {
        return $this->__fathers_cell_phone_number ;
    }

    /**
     *  Set Mothers Office Phone Number property
     *
     *  @param string -  mothers office phone number
     */
    function setMothersOfficePhoneNumber($value = null)
    {
        $this->__mothers_office_phone_number = $value ;
    }

    /**
     *  Get Mothers Office Phone Number property
     *
     *  @return string -  mothers office phone number
     */
    function getMothersOfficePhoneNumber()
    {
        return $this->__mothers_office_phone_number ;
    }

    /**
     *  Set Mothers Cell Phone Number property
     *
     *  @param string -  mothers cell phone number
     */
    function setMothersCellPhoneNumber($value = null)
    {
        $this->__mothers_cell_phone_number = $value ;
    }

    /**
     *  Get Mothers Cell Phone Number property
     *
     *  @return string -  mothers cell phone number
     */
    function getMothersCellPhoneNumber()
    {
        return $this->__mothers_cell_phone_number ;
    }

    /**
     *  Set Secondary Contact Parent1 Cell Phone Number property
     *
     *  @param string -  secondary contact parent1 cell phone number
     */
    function setSecondaryContactParent1CellPhoneNumber($value = null)
    {
        $this->__secondary_contact_parent1_cell_phone_number = $value ;
    }

    /**
     *  Get Secondary Contact Parent1 Cell Phone Number property
     *
     *  @return string -  secondary contact parent1 cell phone number
     */
    function getSecondaryContactParent1CellPhoneNumber()
    {
        return $this->__secondary_contact_parent1_cell_phone_number ;
    }

    /**
     *  Set Secondary Contact Parent2 Office Phone Number property
     *
     *  @param string -  secondary contact parent2 office phone number
     */
    function setSecondaryContactParent2OfficePhoneNumber($value = null)
    {
        $this->__secondary_contact_parent2_office_phone_number = $value ;
    }

    /**
     *  Get Secondary Contact Parent2 Office Phone Number property
     *
     *  @return string -  secondary contact parent2 office phone number
     */
    function getSecondaryContactParent2OfficePhoneNumber()
    {
        return $this->__secondary_contact_parent2_office_phone_number ;
    }

    /**
     *  Set Secondary Contact Parent2 Cell Phone Number property
     *
     *  @param string -  secondary contact parent2 cell phone number
     */
    function setSecondaryContactParent2CellPhoneNumber($value = null)
    {
        $this->__secondary_contact_parent2_cell_phone_number = $value ;
    }

    /**
     *  Get Secondary Contact Parent2 Cell Phone Number property
     *
     *  @return string -  secondary contact parent2 cell phone number
     */
    function getSecondaryContactParent2CellPhoneNumber()
    {
        return $this->__secondary_contact_parent2_cell_phone_number ;
    }

    /**
     *  Set Mothers Email Address property
     *
     *  @param string -  mothers email address
     */
    function setMothersEmailAddress($value = null)
    {
        $this->__mothers_email_address = $value ;
    }

    /**
     *  Get Mothers Email Address property
     *
     *  @return string -  mothers email address
     */
    function getMothersEmailAddress()
    {
        return $this->__mothers_email_address ;
    }

    /**
     *  Set Secondary Contact Parent2 Email Address property
     *
     *  @param string -  secondary contact parent2 email address
     */
    function setSecondaryContactParent2EmailAddress($value = null)
    {
        $this->__secondary_contact_parent2_email_address = $value ;
    }

    /**
     *  Get Secondary Contact Parent2 Email Address property
     *
     *  @return string -  secondary contact parent2 email address
     */
    function getSecondaryContactParent2EmailAddress()
    {
        return $this->__secondary_contact_parent2_email_address ;
    }

    /**
     *  Set Mothers Last Name property
     *
     *  @param string -  mothers last name
     */
    function setMothersLastName($value = null)
    {
        $this->__mothers_last_name = $value ;
    }

    /**
     *  Get Mothers Last Name property
     *
     *  @return string -  mothers last name
     */
    function getMothersLastName()
    {
        return $this->__mothers_last_name ;
    }

    /**
     *  Set Athletes Middle Name property
     *
     *  @param string -  athletes middle name
     */
    function setAthletesMiddleName($value = null)
    {
        $this->__athletes_middle_name = $value ;
    }

    /**
     *  Get Athletes Middle Name property
     *
     *  @return string -  athletes middle name
     */
    function getAthletesMiddleName()
    {
        return $this->__athletes_middle_name ;
    }

    /**
     *  Set Athletes Cell Phone Number property
     *
     *  @param string -  athletes cell phone number
     */
    function setAthletesCellPhoneNumber($value = null)
    {
        $this->__athletes_cell_phone_number = $value ;
    }

    /**
     *  Get Athletes Cell Phone Number property
     *
     *  @return string -  athletes cell phone number
     */
    function getAthletesCellPhoneNumber()
    {
        return $this->__athletes_cell_phone_number ;
    }

    /**
     *  Set Athletes Email Address property
     *
     *  @param string -  athletes email address
     */
    function setAthletesEmailAddress($value = null)
    {
        $this->__athletes_email_address = $value ;
    }

    /**
     *  Get Athletes Email Address property
     *
     *  @return string -  athletes email address
     */
    function getAthletesEmailAddress()
    {
        return $this->__athletes_email_address ;
    }

}

/**
 * HY3 D1 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D1Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        if (WPST_DEBUG)
        {
            $c = container() ;
            $c->add(html_pre(WPST_HYTEK_COLUMN_DEBUG1,
                WPST_HYTEK_COLUMN_DEBUG2, $this->_hy3_record)) ;
            //print $c->render() ;
        }

        //  Extract the data from the HY3 record by substring position

        $this->setAthleteLastName(trim(substr($this->_hy3_record, 8, 20))) ;
        $this->setAthleteFirstName(trim(substr($this->_hy3_record, 28, 20))) ;
        $this->setAthleteNickname(trim(substr($this->_hy3_record, 48, 20))) ;
        $this->setAthleteMiddleInitial(trim(substr($this->_hy3_record, 68, 1))) ;
        $this->setAthleteId(trim(substr($this->_hy3_record, 69, 14))) ;
        $this->setAthleteBirthDate(trim(substr($this->_hy3_record, 88, 8))) ;
        $this->setAthleteAge(trim(substr($this->_hy3_record, 97, 2))) ;
        $this->setChecksum(trim(substr($this->_hy3_record, 150, 128))) ;
    }

    /**
     * Generate Record
     *
     * @return string - D1 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D1_RECORD,
            $this->getGender(),
            $this->getDatabaseId1(),
            $this->getAthleteLastName(),
            $this->getAthleteFirstName(),
            $this->getAthleteNickname(),
            $this->getAthleteMiddleInitial(),
            $this->getAthleteId(),
            $this->getDatabaseId2(),
            $this->getAthleteBirthDate(),
            WPST_HY3_UNUSED,
            $this->getAthleteAge(),
            $this->getAthleteSchoolYear(),
            WPST_HY3_UNUSED,
            $this->getGroup(),
            $this->getSubgroup(),
            $this->getInactive(),
            $this->getRegistrationCountry(),
            WPST_HY3_UNUSED,
            $this->getWmGroup(),
            $this->getWmSubgroup(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D2 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D2Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D2 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D2_RECORD,
            $this->getPrimaryContactAddress1(),
            $this->getPrimaryContactAddress2(),
            $this->getPrimaryContactCity(),
            WPST_HY3_UNUSED,
            $this->getPrimaryContactState(),
            $this->getPrimaryContactPostalCode(),
            $this->getPrimaryContactCountry(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D3 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D3Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D3 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D3_RECORD,
            WPST_HY3_UNUSED,
            $this->getFathersOfficePhoneNumber(),
            $this->getPrimaryContactHomePhoneNumber(),
            $this->getPrimaryContactFaxNumber(),
            $this->getFathersEmailAddress(),
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D4 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D4Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D4 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D4_RECORD,
            $this->getSecondaryContactMailto(),
            $this->getSecondaryContactAddress1(),
            $this->getSecondaryContactCity(),
            WPST_HY3_UNUSED,
            $this->getSecondaryContactState(),
            $this->getSecondaryContactPostalCode(),
            $this->getSecondaryContactCountry(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D5 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D5Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D5 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D5_RECORD,
            $this->getPrimaryContactMailto(),
            $this->getSecondaryContactAddress2(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED,
            $this->getRegistrationDate(),
            WPST_HY3_UNUSED,
            substr(sprintf('%-30.30s', $this->getPrimaryContactCity()), 20, 10),
            substr(sprintf('%-30.30s', $this->getSecondaryContactCity()), 20, 10),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D6 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D6Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D6 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D6_RECORD,
            $this->getDoctorsName(),
            $this->getDoctorsPhoneNumber(),
            $this->getEmergencyContactName(),
            $this->getEmergencyContactPhoneNumber(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D7 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D7Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D7 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D7_RECORD,
            $this->getSecondaryContactParent1OfficePhoneNumber(),
            $this->getSecondaryContactHomePhoneNumber(),
            $this->getSecondaryContactFaxNumber(),
            $this->getSecondaryContactParent1EmailAddress(),
            substr(sprintf('%-30.30s', $this->getPrimaryContactCity()), 36, 14),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D8 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D8Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D8 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D8_RECORD,
            $this->getMedicalConditionDescription(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 D9 record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3D9Record extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - D9 HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_D9_RECORD,
            $this->getMedicationDescription(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 DA record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3DARecord extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - DA HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_DA_RECORD,
            $this->getCustomField1Name(),
            $this->getCustomField1Value(),
            $this->getCustomField2Name(),
            $this->getCustomField2Value(),
            $this->getCustomField3Name(),
            $this->getCustomField3Value(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 DB record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3DBRecord extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - DB HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_DB_RECORD,
            $this->getFathersLastName(),
            $this->getFathersFirstName(),
            $this->getMothersFirstName(),
            $this->getSecondaryContactLastName(),
            $this->getSecondaryContactParent1Name(),
            $this->getSecondaryContactParent2Name(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 DC record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3DCRecord extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - DC HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_DC_RECORD,
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 DD record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3DDRecord extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - DD HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_DD_RECORD,
            $this->getFathersCellPhoneNumber(),
            $this->getMothersOfficePhoneNumber(),
            $this->getMothersCellPhoneNumber(),
            $this->getSecondaryContactParent1CellPhoneNumber(),
            $this->getSecondaryContactParent2OfficePhoneNumber(),
            $this->getSecondaryContactParent2CellPhoneNumber(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 DE record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3DERecord extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - DE HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_DE_RECORD,
            $this->getMothersEmailAddress(),
            $this->getSecondaryContactParent2EmailAddress(),
            substr(sprintf('%-50.50s', $this->getMothersEmailAddress()), 36, 14),
            $this->getMothersLastName(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 DF record
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see HY3DxRecord
 */
class HY3DFRecord extends HY3DxRecord
{
    /**
     * Parse Record
     */
    function ParseRecord()
    {
        wp_die('This funtion has not been implemented.') ;
    }

    /**
     * Generate Record
     *
     * @return string - DF HY3 record
     */
    function GenerateRecord()
    {
        $hy3 = sprintf(WPST_HY3_DF_RECORD,
            $this->getAthletesMiddleName(),
            $this->getAthletesCellPhoneNumber(),
            $this->getAthletesEmailAddress(),
            WPST_HY3_UNUSED,
            WPST_HY3_UNUSED
        ) ;

        return $this->CalculateHy3Checksum($hy3) ;
    }
}

/**
 * HY3 Code Tables
 *
 * The HY3 specification defines 26 tables that map code
 * values into some sort of textual reprsentation.  Some of
 * the mappings are very simple, for example, gender, others
 * are more complex, for example, country codes.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 */
class HY3CodeTables
{
    /**
     * Return the Gender Code text based on the supplied
     * gender code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string gender code
     * @param boolean optional invalid mapping
     * @return string gender code description
     */
    function GetGenderCode($code, $invalid = true)
    {
        $WPST_HYTEK_GENDER_CODES = array(
            WPST_HYTEK_SWIMMER_SEX_CODE_MALE_VALUE => WPST_HYTEK_SWIMMER_SEX_CODE_MALE_LABEL
           ,WPST_HYTEK_SWIMMER_SEX_CODE_FEMALE_VALUE => WPST_HYTEK_SWIMMER_SEX_CODE_FEMALE_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_GENDER_CODES))
            return $WPST_HYTEK_GENDER_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Event Gender Code text based on the supplied
     * event gender code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string event gender code
     * @param boolean optional invalid mapping
     * @return string event gender code description
     */
    function GetEventGenderCode($code, $invalid = true)
    {
        $WPST_HYTEK_EVENT_GENDER_CODES = array(
            WPST_HYTEK_EVENT_SEX_CODE_MALE_VALUE => WPST_HYTEK_EVENT_SEX_CODE_MALE_LABEL
           ,WPST_HYTEK_EVENT_SEX_CODE_FEMALE_VALUE => WPST_HYTEK_EVENT_SEX_CODE_FEMALE_LABEL
           ,WPST_HYTEK_EVENT_SEX_CODE_MIXED_VALUE => WPST_HYTEK_EVENT_SEX_CODE_MIXED_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_EVENT_GENDER_CODES))
            return $WPST_HYTEK_EVENT_GENDER_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Attach Code text based on the supplied
     * attached code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string attached code
     * @param boolean optional invalid mapping
     * @return string attached code description
     */
    function GetAttachedCode($code, $invalid = true)
    {
        $WPST_HYTEK_ATTACHED_CODES = array(
            WPST_HYTEK_ATTACHED_CODE_ATTACHED_VALUE => WPST_HYTEK_ATTACHED_CODE_ATTACHED_LABEL
           ,WPST_HYTEK_ATTACHED_CODE_UNATTACHED_VALUE => WPST_HYTEK_ATTACHED_CODE_UNATTACHED_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_ATTACHED_CODES))
            return $WPST_HYTEK_ATTACHED_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Citizen Code text based on the supplied
     * citizen code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string citizen code
     * @param boolean optional invalid mapping
     * @return string citizen code description
     */
    function GetCitizenCode($code, $invalid = true)
    {
        $WPST_HYTEK_CITIZEN_CODES = array(
            WPST_HYTEK_CITIZENSHIP_CODE_DUAL_VALUE => WPST_HYTEK_CITIZENSHIP_CODE_DUAL_LABEL
           ,WPST_HYTEK_CITIZENSHIP_CODE_FOREIGN_VALUE => WPST_HYTEK_CITIZENSHIP_CODE_FOREIGN_LABEL
        ) ;

        //  The citizen code can also come from the list of
        //  Country codes so look there first!

        $cc = HY3CodeTables::GetCountryCode($code) ;

        if ($cc != '')
            return $cc ;
        else if (array_key_exists($code, $WPST_HYTEK_CITIZEN_CODES))
            return $WPST_HYTEK_CITIZEN_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Org Code text based on the supplied
     * org code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string org code
     * @param boolean optional invalid mapping
     * @return string org code description
     */
    function GetOrgCode($code, $invalid = true)
    {
        $WPST_HYTEK_ORG_CODES = array(
            WPST_HYTEK_ORG_CODE_USS_VALUE => WPST_HYTEK_ORG_CODE_USS_LABEL
           ,WPST_HYTEK_ORG_CODE_MASTERS_VALUE => WPST_HYTEK_ORG_CODE_MASTERS_LABEL
           ,WPST_HYTEK_ORG_CODE_NCAA_VALUE => WPST_HYTEK_ORG_CODE_NCAA_LABEL
           ,WPST_HYTEK_ORG_CODE_NCAA_DIV_I_VALUE => WPST_HYTEK_ORG_CODE_NCAA_DIV_I_LABEL
           ,WPST_HYTEK_ORG_CODE_NCAA_DIV_II_VALUE => WPST_HYTEK_ORG_CODE_NCAA_DIV_II_LABEL
           ,WPST_HYTEK_ORG_CODE_NCAA_DIV_III_VALUE => WPST_HYTEK_ORG_CODE_NCAA_DIV_III_LABEL
           ,WPST_HYTEK_ORG_CODE_YMCA_VALUE => WPST_HYTEK_ORG_CODE_YMCA_LABEL
           ,WPST_HYTEK_ORG_CODE_FINA_VALUE => WPST_HYTEK_ORG_CODE_FINA_LABEL
           ,WPST_HYTEK_ORG_CODE_HIGH_SCHOOL_VALUE => WPST_HYTEK_ORG_CODE_HIGH_SCHOOL_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_ORG_CODES))
            return $WPST_HYTEK_ORG_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Course Code text based on the supplied
     * course code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string course code
     * @param boolean optional invalid mapping
     * @return string course code description
     */
    function GetCourseCode($code, $alt = false, $invalid = true)
    {
        if ($alt)
            $WPST_HYTEK_COURSE_CODES = array(
                WPST_HYTEK_COURSE_STATUS_CODE_SCM_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCM_ALT_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_SCY_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCY_ALT_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_LCM_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_LCM_ALT_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_DQ_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_DQ_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_SCM_ALT_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCM_ALT_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_SCY_ALT_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCY_ALT_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_LCM_ALT_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_LCM_ALT_LABEL
            ) ;

        else
            $WPST_HYTEK_COURSE_CODES = array(
                WPST_HYTEK_COURSE_STATUS_CODE_SCM_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCM_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_SCY_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCY_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_LCM_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_LCM_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_DQ_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_DQ_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_SCM_ALT_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCM_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_SCY_ALT_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_SCY_LABEL
               ,WPST_HYTEK_COURSE_STATUS_CODE_LCM_ALT_VALUE => WPST_HYTEK_COURSE_STATUS_CODE_LCM_LABEL
            ) ;

        if (array_key_exists($code, $WPST_HYTEK_COURSE_CODES))
            return $WPST_HYTEK_COURSE_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Stroke Code text based on the supplied
     * stroke code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string stroke code
     * @param boolean optional invalid mapping
     * @return string stroke code description
     */
    function GetStrokeCode($code, $invalid = true)
    {
        $WPST_HYTEK_EVENT_STROKE_CODES = array(
            WPST_HYTEK_EVENT_STROKE_CODE_FREESTYLE_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_FREESTYLE_LABEL
           ,WPST_HYTEK_EVENT_STROKE_CODE_BACKSTROKE_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_BACKSTROKE_LABEL
           ,WPST_HYTEK_EVENT_STROKE_CODE_BREASTSTROKE_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_BREASTSTROKE_LABEL
           ,WPST_HYTEK_EVENT_STROKE_CODE_BUTTERFLY_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_BUTTERFLY_LABEL
           ,WPST_HYTEK_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL
           ,WPST_HYTEK_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL
           ,WPST_HYTEK_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE => WPST_HYTEK_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_EVENT_STROKE_CODES))
            return $WPST_HYTEK_EVENT_STROKE_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Region Code text based on the supplied
     * region code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string region code
     * @param boolean optional invalid mapping
     * @return string region code description
     */
    function GetRegionCode($code, $invalid = true)
    {
        $WPST_HYTEK_REGION_CODES = array(
            WPST_HYTEK_REGION_CODE_REGION_1_VALUE => WPST_HYTEK_REGION_CODE_REGION_1_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_2_VALUE => WPST_HYTEK_REGION_CODE_REGION_2_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_3_VALUE => WPST_HYTEK_REGION_CODE_REGION_3_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_4_VALUE => WPST_HYTEK_REGION_CODE_REGION_4_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_5_VALUE => WPST_HYTEK_REGION_CODE_REGION_5_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_6_VALUE => WPST_HYTEK_REGION_CODE_REGION_6_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_7_VALUE => WPST_HYTEK_REGION_CODE_REGION_7_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_8_VALUE => WPST_HYTEK_REGION_CODE_REGION_8_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_9_VALUE => WPST_HYTEK_REGION_CODE_REGION_9_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_10_VALUE => WPST_HYTEK_REGION_CODE_REGION_10_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_11_VALUE => WPST_HYTEK_REGION_CODE_REGION_11_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_12_VALUE => WPST_HYTEK_REGION_CODE_REGION_12_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_13_VALUE => WPST_HYTEK_REGION_CODE_REGION_13_LABEL
           ,WPST_HYTEK_REGION_CODE_REGION_14_VALUE => WPST_HYTEK_REGION_CODE_REGION_14_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_REGION_CODES))
            return $WPST_HYTEK_REGION_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Meet Code text based on the supplied
     * meet code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string meet code
     * @param boolean optional invalid mapping
     * @return string meet code description
     */
    function GetMeetCode($code, $invalid = true)
    {
        $WPST_HYTEK_MEET_CODES = array(
            WPST_HYTEK_MEET_TYPE_INVITATIONAL_VALUE => WPST_HYTEK_MEET_TYPE_INVITATIONAL_LABEL
           ,WPST_HYTEK_MEET_TYPE_REGIONAL_VALUE => WPST_HYTEK_MEET_TYPE_REGIONAL_LABEL
           ,WPST_HYTEK_MEET_TYPE_LSC_CHAMPIONSHIP_VALUE => WPST_HYTEK_MEET_TYPE_LSC_CHAMPIONSHIP_LABEL
           ,WPST_HYTEK_MEET_TYPE_ZONE_VALUE => WPST_HYTEK_MEET_TYPE_ZONE_LABEL
           ,WPST_HYTEK_MEET_TYPE_ZONE_CHAMPIONSHIP_VALUE => WPST_HYTEK_MEET_TYPE_ZONE_CHAMPIONSHIP_LABEL
           ,WPST_HYTEK_MEET_TYPE_NATIONAL_CHAMPIONSHIP_VALUE => WPST_HYTEK_MEET_TYPE_NATIONAL_CHAMPIONSHIP_LABEL
           ,WPST_HYTEK_MEET_TYPE_JUNIORS_VALUE => WPST_HYTEK_MEET_TYPE_JUNIORS_LABEL
           ,WPST_HYTEK_MEET_TYPE_SENIORS_VALUE => WPST_HYTEK_MEET_TYPE_SENIORS_LABEL
           ,WPST_HYTEK_MEET_TYPE_DUAL_VALUE => WPST_HYTEK_MEET_TYPE_DUAL_LABEL
           ,WPST_HYTEK_MEET_TYPE_TIME_TRIALS_VALUE => WPST_HYTEK_MEET_TYPE_TIME_TRIALS_LABEL
           ,WPST_HYTEK_MEET_TYPE_INTERNATIONAL_VALUE => WPST_HYTEK_MEET_TYPE_INTERNATIONAL_LABEL
           ,WPST_HYTEK_MEET_TYPE_OPEN_VALUE => WPST_HYTEK_MEET_TYPE_OPEN_LABEL
           ,WPST_HYTEK_MEET_TYPE_LEAGUE_VALUE => WPST_HYTEK_MEET_TYPE_LEAGUE_LABEL
        ) ;

        if (array_key_exists($code, $WPST_HYTEK_MEET_CODES))
            return $WPST_HYTEK_MEET_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }

    /**
     * Return the Country Code text based on the supplied
     * country code.  Return either 'Invalid' or an empty
     * string when the code cannot be mapped.
     *
     * @param string country code
     * @param boolean optional invalid mapping
     * @return string country code description
     */
    function GetCountryCode($code, $invalid = true)
    {
		$WPST_HYTEK_COUNTRY_CODES = array(
		    WPST_HYTEK_COUNTRY_CODE_AFGHANISTAN_VALUE => WPST_HYTEK_COUNTRY_CODE_AFGHANISTAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ALBANIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ALBANIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ALGERIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ALGERIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_AMERICAN_SAMOA_VALUE => WPST_HYTEK_COUNTRY_CODE_AMERICAN_SAMOA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ANDORRA_VALUE => WPST_HYTEK_COUNTRY_CODE_ANDORRA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ANGOLA_VALUE => WPST_HYTEK_COUNTRY_CODE_ANGOLA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ANTIGUA_VALUE => WPST_HYTEK_COUNTRY_CODE_ANTIGUA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_VALUE => WPST_HYTEK_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_VALUE => WPST_HYTEK_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ARGENTINA_VALUE => WPST_HYTEK_COUNTRY_CODE_ARGENTINA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ARMENIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ARMENIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ARUBA_VALUE => WPST_HYTEK_COUNTRY_CODE_ARUBA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_AUSTRALIA_VALUE => WPST_HYTEK_COUNTRY_CODE_AUSTRALIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_AUSTRIA_VALUE => WPST_HYTEK_COUNTRY_CODE_AUSTRIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_AZERBAIJAN_VALUE => WPST_HYTEK_COUNTRY_CODE_AZERBAIJAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BAHAMAS_VALUE => WPST_HYTEK_COUNTRY_CODE_BAHAMAS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BAHRAIN_VALUE => WPST_HYTEK_COUNTRY_CODE_BAHRAIN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BANGLADESH_VALUE => WPST_HYTEK_COUNTRY_CODE_BANGLADESH_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BARBADOS_VALUE => WPST_HYTEK_COUNTRY_CODE_BARBADOS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BELARUS_VALUE => WPST_HYTEK_COUNTRY_CODE_BELARUS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BELGIUM_VALUE => WPST_HYTEK_COUNTRY_CODE_BELGIUM_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BELIZE_VALUE => WPST_HYTEK_COUNTRY_CODE_BELIZE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BENIN_VALUE => WPST_HYTEK_COUNTRY_CODE_BENIN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BERMUDA_VALUE => WPST_HYTEK_COUNTRY_CODE_BERMUDA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BHUTAN_VALUE => WPST_HYTEK_COUNTRY_CODE_BHUTAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BOLIVIA_VALUE => WPST_HYTEK_COUNTRY_CODE_BOLIVIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BOTSWANA_VALUE => WPST_HYTEK_COUNTRY_CODE_BOTSWANA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BRAZIL_VALUE => WPST_HYTEK_COUNTRY_CODE_BRAZIL_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_VALUE => WPST_HYTEK_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BRUNEI_VALUE => WPST_HYTEK_COUNTRY_CODE_BRUNEI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BULGARIA_VALUE => WPST_HYTEK_COUNTRY_CODE_BULGARIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_BURKINA_FASO_VALUE => WPST_HYTEK_COUNTRY_CODE_BURKINA_FASO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CAMEROON_VALUE => WPST_HYTEK_COUNTRY_CODE_CAMEROON_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CANADA_VALUE => WPST_HYTEK_COUNTRY_CODE_CANADA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CAYMAN_ISLANDS_VALUE => WPST_HYTEK_COUNTRY_CODE_CAYMAN_ISLANDS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_VALUE => WPST_HYTEK_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CHAD_VALUE => WPST_HYTEK_COUNTRY_CODE_CHAD_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CHILE_VALUE => WPST_HYTEK_COUNTRY_CODE_CHILE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CHINESE_TAIPEI_VALUE => WPST_HYTEK_COUNTRY_CODE_CHINESE_TAIPEI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_COLUMBIA_VALUE => WPST_HYTEK_COUNTRY_CODE_COLUMBIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_COOK_ISLANDS_VALUE => WPST_HYTEK_COUNTRY_CODE_COOK_ISLANDS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_COSTA_RICA_VALUE => WPST_HYTEK_COUNTRY_CODE_COSTA_RICA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CROATIA_VALUE => WPST_HYTEK_COUNTRY_CODE_CROATIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CUBA_VALUE => WPST_HYTEK_COUNTRY_CODE_CUBA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CYPRUS_VALUE => WPST_HYTEK_COUNTRY_CODE_CYPRUS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_CZECHOSLOVAKIA_VALUE => WPST_HYTEK_COUNTRY_CODE_CZECHOSLOVAKIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_VALUE => WPST_HYTEK_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_DENMARK_VALUE => WPST_HYTEK_COUNTRY_CODE_DENMARK_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_DJIBOUTI_VALUE => WPST_HYTEK_COUNTRY_CODE_DJIBOUTI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_DOMINICAN_REPUBLIC_VALUE => WPST_HYTEK_COUNTRY_CODE_DOMINICAN_REPUBLIC_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ECUADOR_VALUE => WPST_HYTEK_COUNTRY_CODE_ECUADOR_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_EL_SALVADOR_VALUE => WPST_HYTEK_COUNTRY_CODE_EL_SALVADOR_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_EQUATORIAL_GUINEA_VALUE => WPST_HYTEK_COUNTRY_CODE_EQUATORIAL_GUINEA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ESTONIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ESTONIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ETHIOPIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ETHIOPIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_FIJI_VALUE => WPST_HYTEK_COUNTRY_CODE_FIJI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_FINLAND_VALUE => WPST_HYTEK_COUNTRY_CODE_FINLAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_FRANCE_VALUE => WPST_HYTEK_COUNTRY_CODE_FRANCE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GABON_VALUE => WPST_HYTEK_COUNTRY_CODE_GABON_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GAMBIA_VALUE => WPST_HYTEK_COUNTRY_CODE_GAMBIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GEORGIA_VALUE => WPST_HYTEK_COUNTRY_CODE_GEORGIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GERMANY_VALUE => WPST_HYTEK_COUNTRY_CODE_GERMANY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GHANA_VALUE => WPST_HYTEK_COUNTRY_CODE_GHANA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GREAT_BRITAIN_VALUE => WPST_HYTEK_COUNTRY_CODE_GREAT_BRITAIN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GREECE_VALUE => WPST_HYTEK_COUNTRY_CODE_GREECE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GRENADA_VALUE => WPST_HYTEK_COUNTRY_CODE_GRENADA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GUAM_VALUE => WPST_HYTEK_COUNTRY_CODE_GUAM_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GUATEMALA_VALUE => WPST_HYTEK_COUNTRY_CODE_GUATEMALA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GUINEA_VALUE => WPST_HYTEK_COUNTRY_CODE_GUINEA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_GUYANA_VALUE => WPST_HYTEK_COUNTRY_CODE_GUYANA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_HAITI_VALUE => WPST_HYTEK_COUNTRY_CODE_HAITI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_HONDURAS_VALUE => WPST_HYTEK_COUNTRY_CODE_HONDURAS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_HONG_KONG_VALUE => WPST_HYTEK_COUNTRY_CODE_HONG_KONG_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_HUNGARY_VALUE => WPST_HYTEK_COUNTRY_CODE_HUNGARY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ICELAND_VALUE => WPST_HYTEK_COUNTRY_CODE_ICELAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_INDIA_VALUE => WPST_HYTEK_COUNTRY_CODE_INDIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_INDONESIA_VALUE => WPST_HYTEK_COUNTRY_CODE_INDONESIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_IRAQ_VALUE => WPST_HYTEK_COUNTRY_CODE_IRAQ_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_IRELAND_VALUE => WPST_HYTEK_COUNTRY_CODE_IRELAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_VALUE => WPST_HYTEK_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ISRAEL_VALUE => WPST_HYTEK_COUNTRY_CODE_ISRAEL_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ITALY_VALUE => WPST_HYTEK_COUNTRY_CODE_ITALY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_IVORY_COAST_VALUE => WPST_HYTEK_COUNTRY_CODE_IVORY_COAST_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_JAMAICA_VALUE => WPST_HYTEK_COUNTRY_CODE_JAMAICA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_JAPAN_VALUE => WPST_HYTEK_COUNTRY_CODE_JAPAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_JORDAN_VALUE => WPST_HYTEK_COUNTRY_CODE_JORDAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_KAZAKHSTAN_VALUE => WPST_HYTEK_COUNTRY_CODE_KAZAKHSTAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_KENYA_VALUE => WPST_HYTEK_COUNTRY_CODE_KENYA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_KOREA_SOUTH_VALUE => WPST_HYTEK_COUNTRY_CODE_KOREA_SOUTH_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_KUWAIT_VALUE => WPST_HYTEK_COUNTRY_CODE_KUWAIT_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_KYRGHYZSTAN_VALUE => WPST_HYTEK_COUNTRY_CODE_KYRGHYZSTAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LAOS_VALUE => WPST_HYTEK_COUNTRY_CODE_LAOS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LATVIA_VALUE => WPST_HYTEK_COUNTRY_CODE_LATVIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LEBANON_VALUE => WPST_HYTEK_COUNTRY_CODE_LEBANON_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LESOTHO_VALUE => WPST_HYTEK_COUNTRY_CODE_LESOTHO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LIBERIA_VALUE => WPST_HYTEK_COUNTRY_CODE_LIBERIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LIBYA_VALUE => WPST_HYTEK_COUNTRY_CODE_LIBYA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LIECHTENSTEIN_VALUE => WPST_HYTEK_COUNTRY_CODE_LIECHTENSTEIN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LITHUANIA_VALUE => WPST_HYTEK_COUNTRY_CODE_LITHUANIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_LUXEMBOURG_VALUE => WPST_HYTEK_COUNTRY_CODE_LUXEMBOURG_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MADAGASCAR_VALUE => WPST_HYTEK_COUNTRY_CODE_MADAGASCAR_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MALAWI_VALUE => WPST_HYTEK_COUNTRY_CODE_MALAWI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MALAYSIA_VALUE => WPST_HYTEK_COUNTRY_CODE_MALAYSIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MALDIVES_VALUE => WPST_HYTEK_COUNTRY_CODE_MALDIVES_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MALI_VALUE => WPST_HYTEK_COUNTRY_CODE_MALI_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MALTA_VALUE => WPST_HYTEK_COUNTRY_CODE_MALTA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MAURITANIA_VALUE => WPST_HYTEK_COUNTRY_CODE_MAURITANIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MAURITIUS_VALUE => WPST_HYTEK_COUNTRY_CODE_MAURITIUS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MEXICO_VALUE => WPST_HYTEK_COUNTRY_CODE_MEXICO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MOLDOVA_VALUE => WPST_HYTEK_COUNTRY_CODE_MOLDOVA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MONACO_VALUE => WPST_HYTEK_COUNTRY_CODE_MONACO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MONGOLIA_VALUE => WPST_HYTEK_COUNTRY_CODE_MONGOLIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MOROCCO_VALUE => WPST_HYTEK_COUNTRY_CODE_MOROCCO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_MOZAMBIQUE_VALUE => WPST_HYTEK_COUNTRY_CODE_MOZAMBIQUE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NAMIBIA_VALUE => WPST_HYTEK_COUNTRY_CODE_NAMIBIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NEPAL_VALUE => WPST_HYTEK_COUNTRY_CODE_NEPAL_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NEW_ZEALAND_VALUE => WPST_HYTEK_COUNTRY_CODE_NEW_ZEALAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NICARAGUA_VALUE => WPST_HYTEK_COUNTRY_CODE_NICARAGUA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NIGER_VALUE => WPST_HYTEK_COUNTRY_CODE_NIGER_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NIGERIA_VALUE => WPST_HYTEK_COUNTRY_CODE_NIGERIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_NORWAY_VALUE => WPST_HYTEK_COUNTRY_CODE_NORWAY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_OMAN_VALUE => WPST_HYTEK_COUNTRY_CODE_OMAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PAKISTAN_VALUE => WPST_HYTEK_COUNTRY_CODE_PAKISTAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PANAMA_VALUE => WPST_HYTEK_COUNTRY_CODE_PANAMA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PAPAU_NEW_GUINEA_VALUE => WPST_HYTEK_COUNTRY_CODE_PAPAU_NEW_GUINEA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PARAGUAY_VALUE => WPST_HYTEK_COUNTRY_CODE_PARAGUAY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_VALUE => WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_VALUE => WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PERU_VALUE => WPST_HYTEK_COUNTRY_CODE_PERU_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PHILIPPINES_VALUE => WPST_HYTEK_COUNTRY_CODE_PHILIPPINES_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_POLAND_VALUE => WPST_HYTEK_COUNTRY_CODE_POLAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PORTUGAL_VALUE => WPST_HYTEK_COUNTRY_CODE_PORTUGAL_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_PUERTO_RICO_VALUE => WPST_HYTEK_COUNTRY_CODE_PUERTO_RICO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_QATAR_VALUE => WPST_HYTEK_COUNTRY_CODE_QATAR_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ROMANIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ROMANIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_RUSSIA_VALUE => WPST_HYTEK_COUNTRY_CODE_RUSSIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_RWANDA_VALUE => WPST_HYTEK_COUNTRY_CODE_RWANDA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SAN_MARINO_VALUE => WPST_HYTEK_COUNTRY_CODE_SAN_MARINO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SAUDI_ARABIA_VALUE => WPST_HYTEK_COUNTRY_CODE_SAUDI_ARABIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SENEGAL_VALUE => WPST_HYTEK_COUNTRY_CODE_SENEGAL_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SEYCHELLES_VALUE => WPST_HYTEK_COUNTRY_CODE_SEYCHELLES_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SIERRA_LEONE_VALUE => WPST_HYTEK_COUNTRY_CODE_SIERRA_LEONE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SINGAPORE_VALUE => WPST_HYTEK_COUNTRY_CODE_SINGAPORE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SLOVENIA_VALUE => WPST_HYTEK_COUNTRY_CODE_SLOVENIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SOLOMON_ISLANDS_VALUE => WPST_HYTEK_COUNTRY_CODE_SOLOMON_ISLANDS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SOMALIA_VALUE => WPST_HYTEK_COUNTRY_CODE_SOMALIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SOUTH_AFRICA_VALUE => WPST_HYTEK_COUNTRY_CODE_SOUTH_AFRICA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SPAIN_VALUE => WPST_HYTEK_COUNTRY_CODE_SPAIN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SRI_LANKA_VALUE => WPST_HYTEK_COUNTRY_CODE_SRI_LANKA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_VALUE => WPST_HYTEK_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SUDAN_VALUE => WPST_HYTEK_COUNTRY_CODE_SUDAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SURINAM_VALUE => WPST_HYTEK_COUNTRY_CODE_SURINAM_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SWAZILAND_VALUE => WPST_HYTEK_COUNTRY_CODE_SWAZILAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SWEDEN_VALUE => WPST_HYTEK_COUNTRY_CODE_SWEDEN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SWITZERLAND_VALUE => WPST_HYTEK_COUNTRY_CODE_SWITZERLAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_SYRIA_VALUE => WPST_HYTEK_COUNTRY_CODE_SYRIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TADJIKISTAN_VALUE => WPST_HYTEK_COUNTRY_CODE_TADJIKISTAN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TANZANIA_VALUE => WPST_HYTEK_COUNTRY_CODE_TANZANIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_THAILAND_VALUE => WPST_HYTEK_COUNTRY_CODE_THAILAND_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_THE_NETHERLANDS_VALUE => WPST_HYTEK_COUNTRY_CODE_THE_NETHERLANDS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TOGO_VALUE => WPST_HYTEK_COUNTRY_CODE_TOGO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TONGA_VALUE => WPST_HYTEK_COUNTRY_CODE_TONGA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_VALUE => WPST_HYTEK_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TUNISIA_VALUE => WPST_HYTEK_COUNTRY_CODE_TUNISIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_TURKEY_VALUE => WPST_HYTEK_COUNTRY_CODE_TURKEY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_UGANDA_VALUE => WPST_HYTEK_COUNTRY_CODE_UGANDA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_UKRAINE_VALUE => WPST_HYTEK_COUNTRY_CODE_UKRAINE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_UNION_OF_MYANMAR_VALUE => WPST_HYTEK_COUNTRY_CODE_UNION_OF_MYANMAR_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_UNITED_ARAB_EMIRATES_VALUE => WPST_HYTEK_COUNTRY_CODE_UNITED_ARAB_EMIRATES_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE => WPST_HYTEK_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_URUGUAY_VALUE => WPST_HYTEK_COUNTRY_CODE_URUGUAY_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_VANUATU_VALUE => WPST_HYTEK_COUNTRY_CODE_VANUATU_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_VENEZUELA_VALUE => WPST_HYTEK_COUNTRY_CODE_VENEZUELA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_VIETNAM_VALUE => WPST_HYTEK_COUNTRY_CODE_VIETNAM_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_VIRGIN_ISLANDS_VALUE => WPST_HYTEK_COUNTRY_CODE_VIRGIN_ISLANDS_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_WESTERN_SAMOA_VALUE => WPST_HYTEK_COUNTRY_CODE_WESTERN_SAMOA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_YEMEN_VALUE => WPST_HYTEK_COUNTRY_CODE_YEMEN_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_YUGOSLAVIA_VALUE => WPST_HYTEK_COUNTRY_CODE_YUGOSLAVIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ZAIRE_VALUE => WPST_HYTEK_COUNTRY_CODE_ZAIRE_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ZAMBIA_VALUE => WPST_HYTEK_COUNTRY_CODE_ZAMBIA_LABEL
		   ,WPST_HYTEK_COUNTRY_CODE_ZIMBABWE_VALUE => WPST_HYTEK_COUNTRY_CODE_ZIMBABWE_LABEL
		) ;

        if (array_key_exists($code, $WPST_HYTEK_COUNTRY_CODES))
            return $WPST_HYTEK_COUNTRY_CODES[$code] ;
        else if ($invalid)
            return 'Invalid' ;
        else
            return '' ;
    }
}

/**
 * HY3 Code Tables
 *
 * The HY3 specification defines 26 tables that map code
 * values into some sort of textual reprsentation.  Some of
 * the mappings are very simple, for example, gender, others
 * are more complex, for example, country codes.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 */
class HY3CodeTableMappings
{
    /**
     * Return an array of file format codes and their mappings
     *
     * @return array file format code mappings
     */
    function GetFileFormat()
    {
        $WPST_FILE_FORMAT = array(
            WPST_HYTEK_FILE_FORMAT_HYTEK_LABEL => WPST_HYTEK_FILE_FORMAT_HYTEK_VALUE
           ,WPST_HYTEK_FILE_FORMAT_CL2_LABEL => WPST_HYTEK_FILE_FORMAT_CL2_VALUE
        ) ;

        return $WPST_FILE_FORMAT ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return string course code description
     */
    function GetCourseCode($units = WPST_METERS, $length = 25)
    {
        if (($units == WPST_YARDS) && ($length == 25))
            return WPST_HYTEK_COURSE_STATUS_CODE_SCY_VALUE ;
        else if (($units == WPST_METERS) && ($length == 25))
            return WPST_HYTEK_COURSE_STATUS_CODE_SCM_VALUE ;
        else if (($units == WPST_METERS) && ($length == 50))
            return WPST_HYTEK_COURSE_STATUS_CODE_LCM_VALUE  ;
        else
            return WPST_HYTEK_COURSE_STATUS_CODE_DQ_VALUE ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return string course code description
     */
    function GetCountryCode($country)
    {
        $codes = HY3CodeTableMappings::GetCountryCodes() ;

        if (array_key_exists($country, $codes))
            return $codes[$country] ;
        else
            return WPST_NA ;
    }

    /**
     * Return an array of zero time codes and their mappings
     *
     * @return array zero time code mappings
     */
    function GetZeroTimeMode()
    {
        $WPST_ZERO_TIME_MODES = array(
            WPST_HYTEK_USE_BLANKS_LABEL => WPST_HYTEK_USE_BLANKS_VALUE
           ,WPST_HYTEK_USE_ZEROS_LABEL => WPST_HYTEK_USE_ZEROS_VALUE
           ,WPST_HYTEK_USE_NT_LABEL => WPST_HYTEK_USE_NT_VALUE
        ) ;

        return $WPST_ZERO_TIME_MODES ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return array org code mappings
     */
    function GetOrgCodes()
    {
        $WPST_HYTEK_ORG_CODES = array(
            'Select Organization' => WPST_NULL_STRING
           ,WPST_HYTEK_ORG_CODE_USS_LABEL => WPST_HYTEK_ORG_CODE_USS_VALUE
           ,WPST_HYTEK_ORG_CODE_MASTERS_LABEL => WPST_HYTEK_ORG_CODE_MASTERS_VALUE
           ,WPST_HYTEK_ORG_CODE_NCAA_LABEL => WPST_HYTEK_ORG_CODE_NCAA_VALUE
           ,WPST_HYTEK_ORG_CODE_NCAA_DIV_I_LABEL => WPST_HYTEK_ORG_CODE_NCAA_DIV_I_VALUE
           ,WPST_HYTEK_ORG_CODE_NCAA_DIV_II_LABEL => WPST_HYTEK_ORG_CODE_NCAA_DIV_II_VALUE
           ,WPST_HYTEK_ORG_CODE_NCAA_DIV_III_LABEL => WPST_HYTEK_ORG_CODE_NCAA_DIV_III_VALUE
           ,WPST_HYTEK_ORG_CODE_YMCA_LABEL => WPST_HYTEK_ORG_CODE_YMCA_VALUE
           ,WPST_HYTEK_ORG_CODE_FINA_LABEL => WPST_HYTEK_ORG_CODE_FINA_VALUE
           ,WPST_HYTEK_ORG_CODE_HIGH_SCHOOL_LABEL => WPST_HYTEK_ORG_CODE_HIGH_SCHOOL_VALUE
        ) ;

        return $WPST_HYTEK_ORG_CODES ;
    }

    /**
     * Return an array of course codes and their mappings
     *
     * @return string course code description
     */
    function GetCourseCodes($dq = false)
    {
        $WPST_HYTEK_COURSE_CODES = array(
            'Select Course' => WPST_NULL_STRING
           ,WPST_HYTEK_COURSE_STATUS_CODE_SCM_LABEL => WPST_HYTEK_COURSE_STATUS_CODE_SCM_VALUE
           ,WPST_HYTEK_COURSE_STATUS_CODE_SCY_LABEL => WPST_HYTEK_COURSE_STATUS_CODE_SCY_VALUE
           ,WPST_HYTEK_COURSE_STATUS_CODE_LCM_LABEL => WPST_HYTEK_COURSE_STATUS_CODE_LCM_VALUE
        ) ;

        //  Include the DQ option?  Not included by default.

        if ($dq)
            $WPST_HYTEK_COURSE_CODES[
                WPST_HYTEK_COURSE_STATUS_CODE_DQ_LABEL] = WPST_HYTEK_COURSE_STATUS_CODE_DQ_VALUE ;
 
        return $WPST_HYTEK_COURSE_CODES ;
    }

    /**
     * Return an array of region codes and their mappings
     *
     * @return string region code description
     */
    function GetRegionCodes()
    {
        $WPST_HYTEK_REGION_CODES = array(
            'Select Region' => WPST_NULL_STRING
           ,WPST_HYTEK_REGION_CODE_REGION_1_LABEL => WPST_HYTEK_REGION_CODE_REGION_1_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_2_LABEL => WPST_HYTEK_REGION_CODE_REGION_2_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_3_LABEL => WPST_HYTEK_REGION_CODE_REGION_3_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_4_LABEL => WPST_HYTEK_REGION_CODE_REGION_4_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_5_LABEL => WPST_HYTEK_REGION_CODE_REGION_5_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_6_LABEL => WPST_HYTEK_REGION_CODE_REGION_6_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_7_LABEL => WPST_HYTEK_REGION_CODE_REGION_7_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_8_LABEL => WPST_HYTEK_REGION_CODE_REGION_8_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_9_LABEL => WPST_HYTEK_REGION_CODE_REGION_9_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_10_LABEL => WPST_HYTEK_REGION_CODE_REGION_10_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_11_LABEL => WPST_HYTEK_REGION_CODE_REGION_11_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_12_LABEL => WPST_HYTEK_REGION_CODE_REGION_12_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_13_LABEL => WPST_HYTEK_REGION_CODE_REGION_13_VALUE
           ,WPST_HYTEK_REGION_CODE_REGION_14_LABEL => WPST_HYTEK_REGION_CODE_REGION_14_VALUE
        ) ;

        return $WPST_HYTEK_REGION_CODES ;
    }

    /**
     * Return an array of meet codes and their mappings
     *
     * @return string meet code description
     */
    function GetMeetCodes()
    {
        $WPST_HYTEK_MEET_CODES = array(
            'Select Meet' => WPST_NULL_STRING
           ,WPST_HYTEK_MEET_TYPE_INVITATIONAL_LABEL => WPST_HYTEK_MEET_TYPE_INVITATIONAL_VALUE
           ,WPST_HYTEK_MEET_TYPE_REGIONAL_LABEL => WPST_HYTEK_MEET_TYPE_REGIONAL_VALUE
           ,WPST_HYTEK_MEET_TYPE_LSC_CHAMPIONSHIP_LABEL => WPST_HYTEK_MEET_TYPE_LSC_CHAMPIONSHIP_VALUE
           ,WPST_HYTEK_MEET_TYPE_ZONE_LABEL => WPST_HYTEK_MEET_TYPE_ZONE_VALUE
           ,WPST_HYTEK_MEET_TYPE_ZONE_CHAMPIONSHIP_LABEL => WPST_HYTEK_MEET_TYPE_ZONE_CHAMPIONSHIP_VALUE
           ,WPST_HYTEK_MEET_TYPE_NATIONAL_CHAMPIONSHIP_LABEL => WPST_HYTEK_MEET_TYPE_NATIONAL_CHAMPIONSHIP_VALUE
           ,WPST_HYTEK_MEET_TYPE_JUNIORS_LABEL => WPST_HYTEK_MEET_TYPE_JUNIORS_VALUE
           ,WPST_HYTEK_MEET_TYPE_SENIORS_LABEL => WPST_HYTEK_MEET_TYPE_SENIORS_VALUE
           ,WPST_HYTEK_MEET_TYPE_DUAL_LABEL => WPST_HYTEK_MEET_TYPE_DUAL_VALUE
           ,WPST_HYTEK_MEET_TYPE_TIME_TRIALS_LABEL => WPST_HYTEK_MEET_TYPE_TIME_TRIALS_VALUE
           ,WPST_HYTEK_MEET_TYPE_INTERNATIONAL_LABEL => WPST_HYTEK_MEET_TYPE_INTERNATIONAL_VALUE
           ,WPST_HYTEK_MEET_TYPE_OPEN_LABEL => WPST_HYTEK_MEET_TYPE_OPEN_VALUE
           ,WPST_HYTEK_MEET_TYPE_LEAGUE_LABEL => WPST_HYTEK_MEET_TYPE_LEAGUE_VALUE
        ) ;

        return $WPST_HYTEK_MEET_CODES ;
    }

    /**
     * Return an array of country codes and their mappings
     *
     * @return array country code description mappings
     */
    function GetCountryCodes()
    {
		$WPST_HYTEK_COUNTRY_CODES = array(
            'Select Country' => WPST_NULL_STRING
		   ,WPST_HYTEK_COUNTRY_CODE_AFGHANISTAN_LABEL => WPST_HYTEK_COUNTRY_CODE_AFGHANISTAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ALBANIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ALBANIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ALGERIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ALGERIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_AMERICAN_SAMOA_LABEL => WPST_HYTEK_COUNTRY_CODE_AMERICAN_SAMOA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ANDORRA_LABEL => WPST_HYTEK_COUNTRY_CODE_ANDORRA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ANGOLA_LABEL => WPST_HYTEK_COUNTRY_CODE_ANGOLA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ANTIGUA_LABEL => WPST_HYTEK_COUNTRY_CODE_ANTIGUA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_LABEL => WPST_HYTEK_COUNTRY_CODE_ANTILLES_NETHERLANDS_DUTCH_WEST_INDIES_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_LABEL => WPST_HYTEK_COUNTRY_CODE_ARAB_REPUBLIC_OF_EGYPT_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ARGENTINA_LABEL => WPST_HYTEK_COUNTRY_CODE_ARGENTINA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ARMENIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ARMENIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ARUBA_LABEL => WPST_HYTEK_COUNTRY_CODE_ARUBA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_AUSTRALIA_LABEL => WPST_HYTEK_COUNTRY_CODE_AUSTRALIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_AUSTRIA_LABEL => WPST_HYTEK_COUNTRY_CODE_AUSTRIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_AZERBAIJAN_LABEL => WPST_HYTEK_COUNTRY_CODE_AZERBAIJAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BAHAMAS_LABEL => WPST_HYTEK_COUNTRY_CODE_BAHAMAS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BAHRAIN_LABEL => WPST_HYTEK_COUNTRY_CODE_BAHRAIN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BANGLADESH_LABEL => WPST_HYTEK_COUNTRY_CODE_BANGLADESH_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BARBADOS_LABEL => WPST_HYTEK_COUNTRY_CODE_BARBADOS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BELARUS_LABEL => WPST_HYTEK_COUNTRY_CODE_BELARUS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BELGIUM_LABEL => WPST_HYTEK_COUNTRY_CODE_BELGIUM_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BELIZE_LABEL => WPST_HYTEK_COUNTRY_CODE_BELIZE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BENIN_LABEL => WPST_HYTEK_COUNTRY_CODE_BENIN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BERMUDA_LABEL => WPST_HYTEK_COUNTRY_CODE_BERMUDA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BHUTAN_LABEL => WPST_HYTEK_COUNTRY_CODE_BHUTAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BOLIVIA_LABEL => WPST_HYTEK_COUNTRY_CODE_BOLIVIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BOTSWANA_LABEL => WPST_HYTEK_COUNTRY_CODE_BOTSWANA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BRAZIL_LABEL => WPST_HYTEK_COUNTRY_CODE_BRAZIL_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_LABEL => WPST_HYTEK_COUNTRY_CODE_BRITISH_VIRGIN_ISLANDS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BRUNEI_LABEL => WPST_HYTEK_COUNTRY_CODE_BRUNEI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BULGARIA_LABEL => WPST_HYTEK_COUNTRY_CODE_BULGARIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_BURKINA_FASO_LABEL => WPST_HYTEK_COUNTRY_CODE_BURKINA_FASO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CAMEROON_LABEL => WPST_HYTEK_COUNTRY_CODE_CAMEROON_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CANADA_LABEL => WPST_HYTEK_COUNTRY_CODE_CANADA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CAYMAN_ISLANDS_LABEL => WPST_HYTEK_COUNTRY_CODE_CAYMAN_ISLANDS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_LABEL => WPST_HYTEK_COUNTRY_CODE_CENTRAL_AFRICAN_REPUBLIC_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CHAD_LABEL => WPST_HYTEK_COUNTRY_CODE_CHAD_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CHILE_LABEL => WPST_HYTEK_COUNTRY_CODE_CHILE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CHINESE_TAIPEI_LABEL => WPST_HYTEK_COUNTRY_CODE_CHINESE_TAIPEI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_COLUMBIA_LABEL => WPST_HYTEK_COUNTRY_CODE_COLUMBIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_COOK_ISLANDS_LABEL => WPST_HYTEK_COUNTRY_CODE_COOK_ISLANDS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_COSTA_RICA_LABEL => WPST_HYTEK_COUNTRY_CODE_COSTA_RICA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CROATIA_LABEL => WPST_HYTEK_COUNTRY_CODE_CROATIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CUBA_LABEL => WPST_HYTEK_COUNTRY_CODE_CUBA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CYPRUS_LABEL => WPST_HYTEK_COUNTRY_CODE_CYPRUS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_CZECHOSLOVAKIA_LABEL => WPST_HYTEK_COUNTRY_CODE_CZECHOSLOVAKIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_LABEL => WPST_HYTEK_COUNTRY_CODE_DEMOCRATIC_PEOPLES_REP_OF_KOREA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_DENMARK_LABEL => WPST_HYTEK_COUNTRY_CODE_DENMARK_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_DJIBOUTI_LABEL => WPST_HYTEK_COUNTRY_CODE_DJIBOUTI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_DOMINICAN_REPUBLIC_LABEL => WPST_HYTEK_COUNTRY_CODE_DOMINICAN_REPUBLIC_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ECUADOR_LABEL => WPST_HYTEK_COUNTRY_CODE_ECUADOR_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_EL_SALVADOR_LABEL => WPST_HYTEK_COUNTRY_CODE_EL_SALVADOR_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_EQUATORIAL_GUINEA_LABEL => WPST_HYTEK_COUNTRY_CODE_EQUATORIAL_GUINEA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ESTONIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ESTONIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ETHIOPIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ETHIOPIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_FIJI_LABEL => WPST_HYTEK_COUNTRY_CODE_FIJI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_FINLAND_LABEL => WPST_HYTEK_COUNTRY_CODE_FINLAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_FRANCE_LABEL => WPST_HYTEK_COUNTRY_CODE_FRANCE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GABON_LABEL => WPST_HYTEK_COUNTRY_CODE_GABON_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GAMBIA_LABEL => WPST_HYTEK_COUNTRY_CODE_GAMBIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GEORGIA_LABEL => WPST_HYTEK_COUNTRY_CODE_GEORGIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GERMANY_LABEL => WPST_HYTEK_COUNTRY_CODE_GERMANY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GHANA_LABEL => WPST_HYTEK_COUNTRY_CODE_GHANA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GREAT_BRITAIN_LABEL => WPST_HYTEK_COUNTRY_CODE_GREAT_BRITAIN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GREECE_LABEL => WPST_HYTEK_COUNTRY_CODE_GREECE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GRENADA_LABEL => WPST_HYTEK_COUNTRY_CODE_GRENADA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GUAM_LABEL => WPST_HYTEK_COUNTRY_CODE_GUAM_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GUATEMALA_LABEL => WPST_HYTEK_COUNTRY_CODE_GUATEMALA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GUINEA_LABEL => WPST_HYTEK_COUNTRY_CODE_GUINEA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_GUYANA_LABEL => WPST_HYTEK_COUNTRY_CODE_GUYANA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_HAITI_LABEL => WPST_HYTEK_COUNTRY_CODE_HAITI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_HONDURAS_LABEL => WPST_HYTEK_COUNTRY_CODE_HONDURAS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_HONG_KONG_LABEL => WPST_HYTEK_COUNTRY_CODE_HONG_KONG_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_HUNGARY_LABEL => WPST_HYTEK_COUNTRY_CODE_HUNGARY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ICELAND_LABEL => WPST_HYTEK_COUNTRY_CODE_ICELAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_INDIA_LABEL => WPST_HYTEK_COUNTRY_CODE_INDIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_INDONESIA_LABEL => WPST_HYTEK_COUNTRY_CODE_INDONESIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_IRAQ_LABEL => WPST_HYTEK_COUNTRY_CODE_IRAQ_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_IRELAND_LABEL => WPST_HYTEK_COUNTRY_CODE_IRELAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_LABEL => WPST_HYTEK_COUNTRY_CODE_ISLAMIC_REP_OF_IRAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ISRAEL_LABEL => WPST_HYTEK_COUNTRY_CODE_ISRAEL_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ITALY_LABEL => WPST_HYTEK_COUNTRY_CODE_ITALY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_IVORY_COAST_LABEL => WPST_HYTEK_COUNTRY_CODE_IVORY_COAST_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_JAMAICA_LABEL => WPST_HYTEK_COUNTRY_CODE_JAMAICA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_JAPAN_LABEL => WPST_HYTEK_COUNTRY_CODE_JAPAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_JORDAN_LABEL => WPST_HYTEK_COUNTRY_CODE_JORDAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_KAZAKHSTAN_LABEL => WPST_HYTEK_COUNTRY_CODE_KAZAKHSTAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_KENYA_LABEL => WPST_HYTEK_COUNTRY_CODE_KENYA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_KOREA_SOUTH_LABEL => WPST_HYTEK_COUNTRY_CODE_KOREA_SOUTH_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_KUWAIT_LABEL => WPST_HYTEK_COUNTRY_CODE_KUWAIT_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_KYRGHYZSTAN_LABEL => WPST_HYTEK_COUNTRY_CODE_KYRGHYZSTAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LAOS_LABEL => WPST_HYTEK_COUNTRY_CODE_LAOS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LATVIA_LABEL => WPST_HYTEK_COUNTRY_CODE_LATVIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LEBANON_LABEL => WPST_HYTEK_COUNTRY_CODE_LEBANON_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LESOTHO_LABEL => WPST_HYTEK_COUNTRY_CODE_LESOTHO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LIBERIA_LABEL => WPST_HYTEK_COUNTRY_CODE_LIBERIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LIBYA_LABEL => WPST_HYTEK_COUNTRY_CODE_LIBYA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LIECHTENSTEIN_LABEL => WPST_HYTEK_COUNTRY_CODE_LIECHTENSTEIN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LITHUANIA_LABEL => WPST_HYTEK_COUNTRY_CODE_LITHUANIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_LUXEMBOURG_LABEL => WPST_HYTEK_COUNTRY_CODE_LUXEMBOURG_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MADAGASCAR_LABEL => WPST_HYTEK_COUNTRY_CODE_MADAGASCAR_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MALAWI_LABEL => WPST_HYTEK_COUNTRY_CODE_MALAWI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MALAYSIA_LABEL => WPST_HYTEK_COUNTRY_CODE_MALAYSIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MALDIVES_LABEL => WPST_HYTEK_COUNTRY_CODE_MALDIVES_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MALI_LABEL => WPST_HYTEK_COUNTRY_CODE_MALI_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MALTA_LABEL => WPST_HYTEK_COUNTRY_CODE_MALTA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MAURITANIA_LABEL => WPST_HYTEK_COUNTRY_CODE_MAURITANIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MAURITIUS_LABEL => WPST_HYTEK_COUNTRY_CODE_MAURITIUS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MEXICO_LABEL => WPST_HYTEK_COUNTRY_CODE_MEXICO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MOLDOVA_LABEL => WPST_HYTEK_COUNTRY_CODE_MOLDOVA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MONACO_LABEL => WPST_HYTEK_COUNTRY_CODE_MONACO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MONGOLIA_LABEL => WPST_HYTEK_COUNTRY_CODE_MONGOLIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MOROCCO_LABEL => WPST_HYTEK_COUNTRY_CODE_MOROCCO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_MOZAMBIQUE_LABEL => WPST_HYTEK_COUNTRY_CODE_MOZAMBIQUE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NAMIBIA_LABEL => WPST_HYTEK_COUNTRY_CODE_NAMIBIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NEPAL_LABEL => WPST_HYTEK_COUNTRY_CODE_NEPAL_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NEW_ZEALAND_LABEL => WPST_HYTEK_COUNTRY_CODE_NEW_ZEALAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NICARAGUA_LABEL => WPST_HYTEK_COUNTRY_CODE_NICARAGUA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NIGER_LABEL => WPST_HYTEK_COUNTRY_CODE_NIGER_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NIGERIA_LABEL => WPST_HYTEK_COUNTRY_CODE_NIGERIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_NORWAY_LABEL => WPST_HYTEK_COUNTRY_CODE_NORWAY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_OMAN_LABEL => WPST_HYTEK_COUNTRY_CODE_OMAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PAKISTAN_LABEL => WPST_HYTEK_COUNTRY_CODE_PAKISTAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PANAMA_LABEL => WPST_HYTEK_COUNTRY_CODE_PANAMA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PAPAU_NEW_GUINEA_LABEL => WPST_HYTEK_COUNTRY_CODE_PAPAU_NEW_GUINEA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PARAGUAY_LABEL => WPST_HYTEK_COUNTRY_CODE_PARAGUAY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_LABEL => WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CHINA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_LABEL => WPST_HYTEK_COUNTRY_CODE_PEOPLES_REP_OF_CONGO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PERU_LABEL => WPST_HYTEK_COUNTRY_CODE_PERU_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PHILIPPINES_LABEL => WPST_HYTEK_COUNTRY_CODE_PHILIPPINES_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_POLAND_LABEL => WPST_HYTEK_COUNTRY_CODE_POLAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PORTUGAL_LABEL => WPST_HYTEK_COUNTRY_CODE_PORTUGAL_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_PUERTO_RICO_LABEL => WPST_HYTEK_COUNTRY_CODE_PUERTO_RICO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_QATAR_LABEL => WPST_HYTEK_COUNTRY_CODE_QATAR_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ROMANIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ROMANIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_RUSSIA_LABEL => WPST_HYTEK_COUNTRY_CODE_RUSSIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_RWANDA_LABEL => WPST_HYTEK_COUNTRY_CODE_RWANDA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SAN_MARINO_LABEL => WPST_HYTEK_COUNTRY_CODE_SAN_MARINO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SAUDI_ARABIA_LABEL => WPST_HYTEK_COUNTRY_CODE_SAUDI_ARABIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SENEGAL_LABEL => WPST_HYTEK_COUNTRY_CODE_SENEGAL_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SEYCHELLES_LABEL => WPST_HYTEK_COUNTRY_CODE_SEYCHELLES_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SIERRA_LEONE_LABEL => WPST_HYTEK_COUNTRY_CODE_SIERRA_LEONE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SINGAPORE_LABEL => WPST_HYTEK_COUNTRY_CODE_SINGAPORE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SLOVENIA_LABEL => WPST_HYTEK_COUNTRY_CODE_SLOVENIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SOLOMON_ISLANDS_LABEL => WPST_HYTEK_COUNTRY_CODE_SOLOMON_ISLANDS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SOMALIA_LABEL => WPST_HYTEK_COUNTRY_CODE_SOMALIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SOUTH_AFRICA_LABEL => WPST_HYTEK_COUNTRY_CODE_SOUTH_AFRICA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SPAIN_LABEL => WPST_HYTEK_COUNTRY_CODE_SPAIN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SRI_LANKA_LABEL => WPST_HYTEK_COUNTRY_CODE_SRI_LANKA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_LABEL => WPST_HYTEK_COUNTRY_CODE_ST_VINCENT_AND_THE_GRENADINES_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SUDAN_LABEL => WPST_HYTEK_COUNTRY_CODE_SUDAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SURINAM_LABEL => WPST_HYTEK_COUNTRY_CODE_SURINAM_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SWAZILAND_LABEL => WPST_HYTEK_COUNTRY_CODE_SWAZILAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SWEDEN_LABEL => WPST_HYTEK_COUNTRY_CODE_SWEDEN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SWITZERLAND_LABEL => WPST_HYTEK_COUNTRY_CODE_SWITZERLAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_SYRIA_LABEL => WPST_HYTEK_COUNTRY_CODE_SYRIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TADJIKISTAN_LABEL => WPST_HYTEK_COUNTRY_CODE_TADJIKISTAN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TANZANIA_LABEL => WPST_HYTEK_COUNTRY_CODE_TANZANIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_THAILAND_LABEL => WPST_HYTEK_COUNTRY_CODE_THAILAND_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_THE_NETHERLANDS_LABEL => WPST_HYTEK_COUNTRY_CODE_THE_NETHERLANDS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TOGO_LABEL => WPST_HYTEK_COUNTRY_CODE_TOGO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TONGA_LABEL => WPST_HYTEK_COUNTRY_CODE_TONGA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_LABEL => WPST_HYTEK_COUNTRY_CODE_TRINIDAD_AND_TOBAGO_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TUNISIA_LABEL => WPST_HYTEK_COUNTRY_CODE_TUNISIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_TURKEY_LABEL => WPST_HYTEK_COUNTRY_CODE_TURKEY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_UGANDA_LABEL => WPST_HYTEK_COUNTRY_CODE_UGANDA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_UKRAINE_LABEL => WPST_HYTEK_COUNTRY_CODE_UKRAINE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_UNION_OF_MYANMAR_LABEL => WPST_HYTEK_COUNTRY_CODE_UNION_OF_MYANMAR_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_UNITED_ARAB_EMIRATES_LABEL => WPST_HYTEK_COUNTRY_CODE_UNITED_ARAB_EMIRATES_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_LABEL => WPST_HYTEK_COUNTRY_CODE_UNITED_STATES_OF_AMERICA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_URUGUAY_LABEL => WPST_HYTEK_COUNTRY_CODE_URUGUAY_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_VANUATU_LABEL => WPST_HYTEK_COUNTRY_CODE_VANUATU_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_VENEZUELA_LABEL => WPST_HYTEK_COUNTRY_CODE_VENEZUELA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_VIETNAM_LABEL => WPST_HYTEK_COUNTRY_CODE_VIETNAM_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_VIRGIN_ISLANDS_LABEL => WPST_HYTEK_COUNTRY_CODE_VIRGIN_ISLANDS_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_WESTERN_SAMOA_LABEL => WPST_HYTEK_COUNTRY_CODE_WESTERN_SAMOA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_YEMEN_LABEL => WPST_HYTEK_COUNTRY_CODE_YEMEN_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_YUGOSLAVIA_LABEL => WPST_HYTEK_COUNTRY_CODE_YUGOSLAVIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ZAIRE_LABEL => WPST_HYTEK_COUNTRY_CODE_ZAIRE_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ZAMBIA_LABEL => WPST_HYTEK_COUNTRY_CODE_ZAMBIA_VALUE
		   ,WPST_HYTEK_COUNTRY_CODE_ZIMBABWE_LABEL => WPST_HYTEK_COUNTRY_CODE_ZIMBABWE_VALUE
		) ;

        return $WPST_HYTEK_COUNTRY_CODES ;
    }
}
?>
