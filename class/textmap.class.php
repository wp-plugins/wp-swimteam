<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Text mapping classes.
 *
 * $Id$
 *
 * (c) 2010 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage TextMap
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("seasons.class.php") ;
require_once("swimmeets.class.php") ;

/**
 * Class definition of the swim team text map
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 */
class SwimTeamTextMap
{
    /**
     * Map the opponent swim club id into text for the GDL
     *
     * @return string - opponent text description
     */
    function __mapOpponentSwimClubIdToText($swimclubid)
    {
        //  Handle null id gracefully for non-dual meets
    
        if ($swimclubid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;
    
        $swimclub = new SwimClubProfile() ;
        $swimclub->loadSwimClubBySwimClubId($swimclubid) ;
    
        return $swimclub->getClubOrPoolName() . " " . $swimclub->getTeamName() ;
    }
    
    /**
     * Map the meet id into a text description
     *
     * @return string - meet text description
     */
    function __mapMeetIdToText($meetid)
    {
        //  Handle null id gracefully for non-dual meets
    
        if ($meetid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;
    
        $meet = new SwimMeet() ;
    
        $meet->loadSwimMeetByMeetId($meetid) ;
    
        if ($meet->getMeetType() == WPST_DUAL_MEET)
            $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                $meet->getOpponentSwimClubId()) ;
        else
            $opponent = $meet->getMeetDescription() ;
    
        $meetdate = date("m/d/Y", strtotime($meet->getMeetDate())) ;
    
        return array("date" => $meetdate, "opponent" => $opponent,
            "location" => ucfirst($meet->getLocation())) ;
    }
    
    /**
     * Map the season id into a text description
     *
     * @return string - season text description
     */
    function __mapSeasonIdToText($seasonid)
    {
        //  Handle null id gracefully for non-dual seasons
    
        if ($seasonid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;
    
        $season = new SwimTeamSeason() ;
    
        $season->loadSeasonById($seasonid) ;
    
        $start = date("m/d/Y", strtotime($season->getSeasonStart())) ;
        $end = date("m/d/Y", strtotime($season->getSeasonEnd())) ;
    
        return array("start" => $start, "end" => $end,
            "label" => ucfirst($season->getSeasonLabel())) ;
    }

    /**
     * Map the job id into text for the form
     *
     * @return string - opponent text description
     */
    function __mapJobIdToText($jobid, $description = false)
    {
        //  Handle null id gracefully for non-dual meets

        if ($jobid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;

        $job = new SwimTeamJob() ;
        $job->loadJobByJobId($jobid) ;

        if ($description)
            $text = $job->getJobPosition() . " " . $job->getJobDescription() ;
        else
            $text = $job->getJobPosition() ;

        return $text ;
    }
}   
?>
