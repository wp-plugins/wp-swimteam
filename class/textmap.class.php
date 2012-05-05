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

require_once('seasons.class.php') ;
require_once('swimmeets.class.php') ;

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
    
        return $swimclub->getClubOrPoolName() . ' ' . $swimclub->getTeamName() ;
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
    
        $meetdate = date('m/d/Y', strtotime($meet->getMeetDateAsDate())) ;
    
        return array('date' => $meetdate, 'opponent' => $opponent,
            'location' => ucfirst($meet->getLocation())) ;
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
    
        $start = date('m/d/Y', strtotime($season->getSeasonStart())) ;
        $end = date('m/d/Y', strtotime($season->getSeasonEnd())) ;
    
        return array('start' => $start, 'end' => $end,
            'label' => ucfirst($season->getSeasonLabel())) ;
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
            $text = $job->getJobPosition() . ' ' . $job->getJobDescription() ;
        else
            $text = $job->getJobPosition() ;

        return $text ;
    }

    /**
     * Map the event id into text for the form
     *
     * @param int - event id
     * @return string - opponent text description
     */
    function __mapEventIdToText($eventid)
    {
        require_once('events.class.php') ;
        require_once('agegroups.class.php') ;

        //  Handle null id gracefully for null events

        if ($eventid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;

        $event = new SwimTeamEvent() ;
        $event->loadSwimTeamEventByEventId($eventid) ;

        $text = sprintf('%04s  %s  %s  %s  %s',
            $event->getEventNumber(),
            SwimTeamTextMap::__mapAgeGroupIdToText($event->getAgeGroupId()),
            SwimTeamTextMap::__mapStrokeCodeToText($event->getStroke()),
            $event->getDistance(),
            SwimTeamTextMap::__mapCourseCodeToText($event->getCourse())) ;

        return $text ;
    }

    /**
     * Map the age group id into text for the GDL
     *
     * @return string - season text description
     */
    function __mapAgeGroupIdToText($agegroupid)
    {
        require_once('agegroups.class.php') ;

        $agegroup = new SwimTeamAgeGroup() ;
        $agegroup->loadAgeGroupById($agegroupid) ;

        return $agegroup->getAgeGroupText() ;
    }

    /**
     * Map the season id into text for the GDL
     *
     * @return string - season text description
     */
    function __mapCourseCodeToText($course)
    {
        switch($course)
        {
            case WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_SCM_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_SCY_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_LCM_LABEL ;
                break ;

            case WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE :
                $obj = WPST_SDIF_COURSE_STATUS_CODE_DQ_LABEL ;
                break ;

            default :
                $obj = WPST_UNKNOWN ;
                break ;
        }

        return $obj ;
    }

    /**
     * Map the opponent swim club id into text for the GDL
     *
     * @return string - opponent text description
     */
    function __mapStrokeCodeToText($stroke)
    {
        switch($stroke)
        {
            case WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL ;
                break ;

            case WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE :
                $obj = WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL ;
                break ;

            default :
                $obj = WPST_UNKNOWN ;
                break ;
        }

        return $obj ;
    }

    /**
     * Map the event group id into text for the GDL
     *
     * @return string - season text description
     */
    function __mapEventGroupIdToText($eventgroupid)
    {
        if ($eventgroupid !== WPST_NULL_ID)
        {
            require_once('events.class.php') ;

            $eventgroup = new SwimTeamEventGroup() ;
            
            if ($eventgroup->eventgroupExistById($eventgroupid))
            {
                $eventgroup->loadEventGroupById($eventgroupid) ;
                return $eventgroup->getEventGroupDescription() ;
            }
        }

        return ucwords(WPST_NONE) ;
    }
}   
?>
