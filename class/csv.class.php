<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * CSV classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Roster
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("roster.class.php") ;

/**
 * Class definition of the seasons
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamRoster
 */
class SwimTeamRosterCSV extends SwimTeamRoster
{
    /**
     * csv data
     */
    var $__csvData ;

    /**
     * csv File
     */
    var $__csvFile ;

    /**
     * csv record count
     */
    var $__csvCount ;

    /**
     * Get CSV record count
     *
     * @return int - count of CSV records
     */
    function getCSVCount()
    {
        return $this->__csvCount ;
    }

    /**
     * Get CSV file name
     *
     * @return string - CSV file name
     */
    function getCSVFile()
    {
        return $this->__csvFile ;
    }

    /**
     * Set CSV file name
     *
     * @param string - CSV file name
     */
    function setCSVFile($f)
    {
        $this->__csvFile = $f ;
    }

    /**
     * Build the CSV stream
     *
     * @param - boolean - optional to export query to active swimmers only
     */
    function generateCSV($active_only = true)
    {
        $csv = &$this->__csvData ;

        $season = new SwimTeamSeason() ;
        $roster = new SwimTeamRoster() ;

        $this->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSeasonId($season->getActiveSeasonId()) ;

        $csv = "" ;
        $swimmer = new SwimTeamSwimmer() ;

        $csv .= "\"First Name\"," ;
        $csv .= "\"Middle Name\"," ;
        $csv .= "\"Nick Name\"," ;
        $csv .= "\"Last Name\"," ;
        $csv .= "\"Gender\"," ;
        $csv .= "\"T-Shirt Size\"," ;
        $csv .= "\"Results\"," ;
        $csv .= "\"Status\"," ;
        $csv .= "\"Birth Date\"," ;
        $csv .= "\"Real Age\"," ;
        $csv .= "\"Adjusted Age\"," ;
        $csv .= "\"Age Group\"" ;
        $csv .= "\"Swimmer Label\"" ;

        //  Handle the optional fields

        $option = get_option(WPST_OPTION_SWIMMER_OPTION1) ;

        if ($option != WPST_DISABLED)
        {
            $label = get_option(WPST_OPTION_SWIMMER_OPTION1_LABEL) ;
            $csv .= sprintf(",\"%s\"", $label) ;
        }

        $option = get_option(WPST_OPTION_SWIMMER_OPTION2) ;

        if ($option != WPST_DISABLED)
        {
            $label = get_option(WPST_OPTION_SWIMMER_OPTION2_LABEL) ;
            $csv .= sprintf(",\"%s\"", $label) ;
        }

        $option = get_option(WPST_OPTION_SWIMMER_OPTION3) ;

        if ($option != WPST_DISABLED)
        {
            $label = get_option(WPST_OPTION_SWIMMER_OPTION3_LABEL) ;
            $csv .= sprintf(",\"%s\"", $label) ;
        }

        $option = get_option(WPST_OPTION_SWIMMER_OPTION4) ;

        if ($option != WPST_DISABLED)
        {
            $label = get_option(WPST_OPTION_SWIMMER_OPTION4_LABEL) ;
            $csv .= sprintf(",\"%s\"", $label) ;
        }

        $option = get_option(WPST_OPTION_SWIMMER_OPTION5) ;

        if ($option != WPST_DISABLED)
        {
            $label = get_option(WPST_OPTION_SWIMMER_OPTION5_LABEL) ;
            $csv .= sprintf(",\"%s\"", $label) ;
        }

        $csv .= "\r\n" ;


        $swimmerIds = $this->getSwimmerIds($active_only) ;

        //  Loop through the active swimmers

        $this->__csvCount = 0 ;

        foreach ($swimmerIds as $swimmerId)
        {
            $this->__csvCount++ ;

            $swimmer->loadSwimmerById($swimmerId["swimmerid"]) ;
            $roster->setSwimmerId($swimmerId["swimmerid"]) ;
            $roster->loadRosterBySeasonIdAndSwimmerId() ;

            $csv .= sprintf("\"%s\",", $swimmer->getFirstName()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getMiddleName()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getNickName()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getLastName()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getGender()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getTShirtSize()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getResults()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getStatus()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getDateOfBirthAsDate()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getAge()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getAgeGroupAge()) ;
            $csv .= sprintf("\"%s\",", $swimmer->getAgeGroupText()) ;
            $csv .= sprintf("\"%s\"", $roster->getSwimmerLabel()) ;

            //  Handle the optional fields

            $option = get_option(WPST_OPTION_SWIMMER_OPTION1) ;

            if ($option != WPST_DISABLED)
            {
                $csv .= sprintf(",\"%s\"", $swimmer->getSwimmerOption1()) ;
            }

            $csv .= "\r\n" ;
        }
    }

    /**
     * Write the CSV data to a file which can be sent to the browser
     *
     */
    function generateCSVFile()
    {
        //  Generate a temporary file to hold the data
 
        $this->setCSVFile(tempnam(ABSPATH .
            "/" . get_option('upload_path'), "CSV")) ;

        $this->setCSVFile(tempnam('', "CSV")) ;

        //  Write the CSV data to the file

        $f = fopen($this->getCSVFile(), "w") ;
        fwrite($f, $this->__csvData) ;
        fclose($f) ;
    }
}
?>
