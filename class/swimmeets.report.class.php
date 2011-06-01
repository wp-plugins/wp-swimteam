<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Meets classes.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Meets
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */


require_once('forms.class.php') ;
require_once('events.class.php') ;
require_once('seasons.class.php') ;
require_once('swimmeets.class.php') ;
require_once('swimclubs.class.php') ;
require_once('roster.class.php') ;
require_once('jobs.class.php') ;
require_once('textmap.class.php') ;
require_once('print.class.php') ;

/**
 * Class definition of the meets
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimMeet
 */
class SwimMeetReport extends SwimMeet
{
    /**
     * User Id 
     */
    var $__userid = null ;

    /**
     * Meet Summary flag
     */
    var $__meetsummary = false ;

    /**
     * Job Assignments flag
     */
    var $__jobassignments = false ;

    /**
     * Opponent Profile flag
     */
    var $__opponentprofile = false ;

    /**
     * Opt-In / Opt-Out flag
     */
    var $__optinoptout = false ;

    /**
     * Opt-In / Opt-Out sort by key
     */
    var $__optinoptoutsortby = WPST_SORT_BY_NAME ;

    /**
     * Show Timestamp flag
     */
    var $__show_timestamp = false ;

    /**
     * Show Map flag
     */
    var $__show_map = false ;

    /**
     * Show Map Links flag
     */
    var $__show_map_links = false ;

    /**
     * Use Initials flag
     */
    var $__use_first_intial = false ;

    /**
     * Use Nickname flag
     */
    var $__use_nickname = false ;

    /**
     * Active Season Id
     */
    var $__active_season_id = null ;

    /**
     * Report Container Content
     */
    var $__report_content = null ;

    /**
     * Stroke lookup table
     */
    var $__strokecodelut = array(
        WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL
       ,WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL
       ,WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL
       ,WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL
       ,WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL
       ,WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL
       ,WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE =>
            WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL
    ) ;

    /**
     * set user id
     *
     * @param int - id for the user
     */
    function setUserId($id)
    {
        $this->__userid = $id ;
    }

    /**
     * get user id
     *
     * @return int - id for the user
     */
    function getUserId()
    {
        return $this->__userid ;
    }

    /**
     * set active season id
     *
     * @param int - id for the active season
     */
    function setActiveSeasonId($id)
    {
        $this->__active_season_id = $id ;
    }

    /**
     * get active season id
     *
     * @return int - id for the active season
     */
    function getActiveSeasonId()
    {
        return $this->__active_season_id ;
    }

    /**
     * set meet summary flag inclusion
     *
     * @param boolean - flag to turn meet summary inclusion on or off
     */
    function setMeetSummary($flag = true)
    {
        $this->__meetsummary = $flag ;
    }

    /**
     * get meet summary flag inclusion
     *
     * @return boolean - flag to turn meet summary inclusion on or off
     */
    function getMeetSummary()
    {
        return $this->__meetsummary ;
    }

    /**
     * set job assignments flag inclusion
     *
     * @param boolean - flag to turn job assignments inclusion on or off
     */
    function setJobAssignments($flag = true)
    {
        $this->__jobassignments = $flag ;
    }

    /**
     * get job assignments flag inclusion
     *
     * @return boolean - flag to turn job assignments inclusion on or off
     */
    function getJobAssignments()
    {
        return $this->__jobassignments ;
    }

    /**
     * set opponent profile flag inclusion
     *
     * @param boolean - flag to turn opponent profile inclusion on or off
     */
    function setOpponentProfile($flag = true)
    {
        $this->__opponentprofile = $flag ;
    }

    /**
     * get opponent profile flag inclusion
     *
     * @return boolean - flag to turn opponent profile inclusion on or off
     */
    function getOpponentProfile()
    {
        return $this->__opponentprofile ;
    }

    /**
     * set opt-in opt-out flag inclusion
     *
     * @param boolean - flag to turn opt-in opt-out inclusion on or off
     */
    function setOptInOptOut($flag = true)
    {
        $this->__optinoptout = $flag ;
    }

    /**
     * get opt-in opt-out flag inclusion
     *
     * @return boolean - flag to turn opt-in opt-out inclusion on or off
     */
    function getOptInOptOut()
    {
        return $this->__optinoptout ;
    }

    /**
     * set opt-in opt-out sort by key
     *
     * @param string - set opt-in opt-out sort by key
     */
    function setOptInOptOutSortBy($key = WPST_SORT_BY_NAME)
    {
        $this->__optinoptoutsortby = $key ;
    }

    /**
     * get opt-in opt-out sort by key
     *
     * @return string - get opt-in opt-out sort by key
     */
    function getOptInOptOutSortBy()
    {
        return $this->__optinoptoutsortby ;
    }

    /**
     * set show timestamp flag inclusion
     *
     * @param boolean - flag to turn show timestamp inclusion on or off
     */
    function setShowTimeStamp($flag = true)
    {
        $this->__show_timestamp = $flag ;
    }

    /**
     * get show timestamp flag inclusion
     *
     * @return boolean - flag to turn show timestamp inclusion on or off
     */
    function getShowTimeStamp()
    {
        return $this->__show_timestamp ;
    }

    /**
     * set show map flag inclusion
     *
     * @param boolean - flag to turn show map inclusion on or off
     */
    function setShowMap($flag = true)
    {
        $this->__show_map = $flag ;
    }

    /**
     * get show map flag inclusion
     *
     * @return boolean - flag to turn show map inclusion on or off
     */
    function getShowMap()
    {
        return $this->__show_map ;
    }

    /**
     * set show map links flag inclusion
     *
     * @param boolean - flag to turn show map links inclusion on or off
     */
    function setShowMapLinks($flag = true)
    {
        $this->__show_map_links = $flag ;
    }

    /**
     * get show map flag links inclusion
     *
     * @return boolean - flag to turn show map links inclusion on or off
     */
    function getShowMapLinks()
    {
        return $this->__show_map_links ;
    }

    /**
     * set use first initial flag inclusion
     *
     * @param boolean - flag to turn use first initial inclusion on or off
     */
    function setUseFirstInitial($flag = true)
    {
        $this->__use_first_intial = $flag ;
    }

    /**
     * get use first initial flag inclusion
     *
     * @return boolean - flag to turn use first initial inclusion on or off
     */
    function getUseFirstInitial()
    {
        return $this->__use_first_intial ;
    }

    /**
     * set use last initial flag inclusion
     *
     * @param boolean - flag to turn use last initial inclusion on or off
     */
    function setUseLastInitial($flag = true)
    {
        $this->__use_last_intial = $flag ;
    }

    /**
     * get use last initial flag inclusion
     *
     * @return boolean - flag to turn use last initial inclusion on or off
     */
    function getUseLastInitial()
    {
        return $this->__use_last_intial ;
    }

    /**
     * set use nickname flag inclusion
     *
     * @param boolean - flag to turn use nickname inclusion on or off
     */
    function setUseNickname($flag = true)
    {
        $this->__use_nickname = $flag ;
    }

    /**
     * get use nickname flag inclusion
     *
     * @return boolean - flag to turn use nickname inclusion on or off
     */
    function getUseNickname()
    {
        return $this->__use_nickname ;
    }

    /**
     * Generate the report
     *
     */
    function generateReport()
    {
        if (is_null($this->__report_content))
            $this->__report_content = container() ;

        $c = &$this->__report_content ;

        //  Add Meet Summary?

        if ($this->getMeetSummary())
        {
            $summary = new SwimMeetInfoTable('Meet Summary', '100%') ;
            $summary->setSwimMeetId($this->getMeetId()) ;
            $summary->constructSwimMeetInfoTable() ;
            if (empty($c->_content))
                $c->add($summary) ;
            else
                $c->add(html_br(2), $summary) ;
        }
 
        //  Add Job Assignments?

        if ($this->getJobAssignments())
        {
            $jobassignments = new SwimMeetJobAssignmentInfoTable('Job Assignments', '100%') ;
            $jobassignments->setMeetId($this->getMeetId()) ;
            $jobassignments->setShowUsername(true) ;
            $jobassignments->setShowEmail(true) ;
            $jobassignments->setShowPhone(true) ;
            $jobassignments->setShowNotes(true) ;

            if (user_can(get_current_user_id(), 'publish_posts'))
                $jobassignments->constructSwimMeetJobAssignmentInfoTable() ;
            else
                $jobassignments->constructSwimMeetJobAssignmentInfoTable(null, $this->getUserId()) ;

            if (empty($c->_content))
                $c->add($jobassignments) ;
            else
                $c->add(html_br(2), $jobassignments) ;
        }
 
        //  Add Opponent Profile?

        if ($this->getOpponentProfile())
        {
            if ($this->getOpponentSwimClubId() != WPST_NONE)
            {
                $profile = new SwimClubProfileInfoTable('Club Profile', '100%') ;
                $profile->setSwimClubId($this->getOpponentSwimClubId()) ;
                $profile->constructSwimClubProfile(true) ;

                if (empty($c->_content))
                    $c->add($profile) ;
                else
                    $c->add(html_br(2), $profile) ;
            }
            else
            {
                if (empty($c->_content))
                    $c->add(html_h4('No club profile information available.')) ;
                else
                    $c->add(html_br(2), html_h4('No club profile information available.')) ;
            }
        }
 
        //  List the swimmers either registered or scratched?

        if ($this->getOptInOptOut())
        {
            $meta = new SwimMeetMeta() ;
            $swimmerIds = $meta->getSwimmerIdsByMeetIdAndParticipation(
                $this->getMeetId(), $this->getParticipation(),
                $this->getOptInOptOutSortBy()) ;

            $participation = ($this->getParticipation() == WPST_OPT_IN) ?
                get_option(WPST_OPTION_OPT_IN_LABEL) : 
                get_option(WPST_OPTION_OPT_OUT_LABEL) ;

            if ($this->getMeetType() == WPST_DUAL_MEET)
                $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                    $this->getOpponentSwimClubId()) ;
            else
                $opponent = $this->getMeetDescription() ;

            $meetdate = date('D M j, Y', strtotime($this->getMeetDate())) ;
 
            //  Full meet scratches

            $full = new SwimTeamInfoTable(sprintf('Full Meet %s:  %s %s',
                $participation, $opponent, $meetdate), '100%') ;
            $full->set_alt_color_flag(true) ;

            if  ($this->getShowTimeStamp())
                $full->add_row(html_b('Name'), html_b('Swimmer Number'), html_b('Recorded')) ;
            else
                $full->add_row(html_b('Name'), html_b('Swimmer Number')) ;

            $partial = new SwimTeamInfoTable(sprintf('Partial Meet %s:  %s %s',
                $participation, $opponent, $meetdate), '100%') ;
            $partial->set_alt_color_flag(true) ;

            if  ($this->getShowTimeStamp())
                $partial->add_row(html_b('Name'), html_b('Swimmer Number'), html_b('Stroke'), html_b('Recorded')) ;
            else
                $partial->add_row(html_b('Name'), html_b('Swimmer Number'), html_b('Stroke')) ;

            $event = new SwimTeamInfoTable(sprintf('Meet Event %s:  %s %s',
                $participation, $opponent, $meetdate), '100%') ;
            $event->set_alt_color_flag(true) ;

            if  ($this->getShowTimeStamp())
                $event->add_row(html_b('Name'), html_b('Swimmer Number'), html_b('Event'),
                    html_b('Age Group'), html_b('Stroke'), html_b('Distance'),
                    html_b('Recorded')) ;
            else
                $event->add_row(html_b('Name'), html_b('Swimmer Number'), html_b('Event'),
                    html_b('Age Group'), html_b('Stroke'), html_b('Distance')) ;

            //$season = new SwimTeamSeason() ;
            //$season->loadActiveSeason() ;

            $swimmeet = new SwimMeet() ;
            $swimmeet->loadSwimMeetByMeetId($this->getMeetId()) ;

            $roster = new SwimTeamRoster() ;
            //$roster->setSeasonId($season->getActiveSeasonId()) ;
            $roster->setSeasonId($swimmeet->getSeasonId()) ;

            $swimmer = new SwimTeamSwimmer() ;

            $fullrows = 0 ;
            $partialrows = 0 ;
            $eventrows = 0 ;

            if (empty($swimmerIds))
            {
                    $td = html_td(null, null, 'No swimmers found.') ;
                    $td->set_tag_attributes(array('class' => 'contentnovertical', 'colspan' => $this->getShowTimeStamp() ? 3 : 2)) ;
                    $full->add_row($td) ;
                    $td->set_tag_attributes(array('class' => 'contentnovertical', 'colspan' => $this->getShowTimeStamp() ? 4 : 3)) ;
                    $partial->add_row($td) ;
            }
            else
            {
                foreach ($swimmerIds as $swimmerId)
                {
                    $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;

                    if (user_can(get_current_user_id(), 'publish_posts') ||
                        (get_current_user_id() == $swimmer->getContact1Id()) ||
                        (get_current_user_id() == $swimmer->getContact2Id()))
                    {
                        $roster->setSwimmerId($swimmerId['swimmerid']) ;
                        $roster->loadRosterBySeasonIdAndSwimmerId() ;
    
                        $fn = $swimmer->getFirstName() ;
                        $mn = $swimmer->getMiddleName() ;
                        $ln = $swimmer->getLastName() ;
                        $nn = $swimmer->getNickName() ;
    
                        //  Override first name with nickname?
    
                        if ($this->getUseNickname())
                            $name = empty($nn) ? $fn : $nn ;
                        else
                            $name = $fn ;
    
                        //  Use first intial?
    
                        if ($this->getUseFirstInitial())
                            $name = substr($name, 0, 1) . '. ' ;
                        else
                            $name .= ' ' ;
    
                        //  Use last intial?
    
                        if ($this->getUseLastInitial())
                            $name .= substr($ln, 0, 1) . '.' ;
                        else
                            $name .= $ln ;
    
                        // Which mode is the data in?  Stroke or Event?

                        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
                        {
                            $strokecodes = $meta->getStrokeCodesBySwimmerIdsAndMeetIdAndParticipation($swimmerId['swimmerid'],
                                $this->getMeetId(), $this->getParticipation()) ;
        
                            //  Full meet opt-in / opt-out?
        
                            if (count($strokecodes) >= count(get_option(WPST_OPTION_OPT_IN_OPT_OUT_STROKES)))
                            {
                                if ($this->getShowTimeStamp())
                                {
                                    $timestamp = $meta->getMetaModifiedByMeetIdSwimmerIdAndStrokeCode($this->getMeetId(),
                                        $swimmerId['swimmerid'], $strokecodes[0]['strokecode']) ;
                                    $full->add_row($name, $roster->getSwimmerLabel(),
                                        $timestamp['modified']) ;
                                }
                                else
                                {
                                    $full->add_row($name, $roster->getSwimmerLabel()) ;
                                }
        
                                $fullrows++ ;
                            }
                            else
                            {
                                foreach ($strokecodes as $strokecode)
                                {
                                    if ($this->getShowTimeStamp())
                                    {
                                        $timestamp = $meta->getMetaModifiedByMeetIdSwimmerIdAndStrokeCode($this->getMeetId(),
                                            $swimmerId['swimmerid'], $strokecode['strokecode']) ;
                                        $partial->add_row($name, $roster->getSwimmerLabel(),
                                            $this->__strokecodelut[$strokecode['strokecode']],
                                            $timestamp['modified']) ;
                                    }
                                    else
                                    {
                                        $partial->add_row($name, $roster->getSwimmerLabel(),
                                            $this->__strokecodelut[$strokecode['strokecode']]) ;
                                    }
                                }
        
                                $partialrows++ ;
                            }
                        }
                        else
                        {
                            $eventids = $meta->getEventIdsBySwimmerIdsAndMeetIdAndParticipation($swimmerId['swimmerid'],
                            $this->getMeetId(), $this->getParticipation()) ;
        
                            $e = new SwimMeetEvent() ;

                            foreach ($eventids as $eventid)
                            {
                                $e->loadSwimMeetEventByEventId($eventid['eventid']) ;

                                //  If the Event Number is null then we're dealing with data that was
                                //  entered under the stroke model before switching over to the event
                                //  model.  In this scenario, simply display N/A for the details which
                                //  aren't available.

                                if (!is_null($e->getEventNumber()))
                                {
                                    //  Full event details are available

                                    if ($this->getShowTimeStamp())
                                    {
                                        $timestamp = $meta->getMetaModifiedByMeetIdSwimmerIdAndEventId($this->getMeetId(),
                                            $swimmerId['swimmerid'], $eventid['eventid']) ;
                                        $event->add_row($name, $roster->getSwimmerLabel(),
                                            sprintf('%04s', $e->getEventNumber()),
                                            SwimTeamTextMap::__mapAgeGroupIdToText($e->getAgeGroupId()),
                                            SwimTeamTextMap::__mapStrokeCodeToText($e->getStroke()), $e->getDistance() .
                                            ' ' . SwimTeamTextMap::__mapCourseCodeToText($e->getCourse()),
                                            $timestamp['modified']) ;
                                    }
                                    else
                                    {
                                        $event->add_row($name, $roster->getSwimmerLabel(),
                                            sprintf('%04s', $e->getEventNumber()),
                                            SwimTeamTextMap::__mapAgeGroupIdToText($e->getAgeGroupId()),
                                            SwimTeamTextMap::__mapStrokeCodeToText($e->getStroke()), $e->getDistance() .
                                            ' ' . SwimTeamTextMap::__mapCourseCodeToText($e->getCourse())) ;
                                    }
                                }
                                else
                                {
                                    //  Full event details are not available

                                    $na = strtoupper(WPST_NA) ;
                                    if ($this->getShowTimeStamp())
                                    {
                                        $timestamp = $meta->getMetaModifiedByMeetIdSwimmerIdAndEventId($this->getMeetId(),
                                            $swimmerId['swimmerid'], $eventid['eventid']) ;
                                        $event->add_row($name, $roster->getSwimmerLabel(),
                                            $na, $na, $na, $na, $timestamp['modified']) ;
                                    }
                                    else
                                    {
                                        $event->add_row($name, $roster->getSwimmerLabel(), $na, $na, $na, $na) ;
                                    }
                                }
                            }
    
                            $eventrows++ ;
                        }
                    }
                }

                // Which mode is the data in?  Stroke or Event?

                if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
                {
                    if ($fullrows == 0)
                    {
                        $td = html_td(null, null, 'No swimmers found.') ;
                        $td->set_tag_attributes(array('class' => 'contentnovertical', 'colspan' => 3)) ;
                        $full->add_row($td) ;
                    }

                    if ($partialrows == 0)
                    {
                        $td = html_td(null, null, 'No swimmers found.') ;
                        $td->set_tag_attributes(array('class' => 'contentnovertical', 'colspan' => 4)) ;
                        $partial->add_row($td) ;
                    }
                }
                else
                {
                    if ($eventrows == 0)
                    {
                        $td = html_td(null, null, 'No swimmers found.') ;
                        $td->set_tag_attributes(array('class' => 'contentnovertical', 'colspan' => 4)) ;
                        $event->add_row($td) ;
                    }
                }
            }

            // Which mode is the data in?  Stroke or Event?

            if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_USAGE_MODEL) == WPST_STROKE)
            {
                $fullmsg = html_h4(sprintf('%d swimmer(s) found.', $fullrows)) ;
                $partialmsg = html_h4(sprintf('%d swimmer(s) found.', $partialrows)) ;

                if (empty($c->_content))
                    $c->add($full, $fullmsg, html_br(2), $partial, $partialmsg) ;
                else
                    $c->add(html_br(2), $full, $fullmsg, html_br(2), $partial, $partialmsg) ;
            }
            else
            {
                $eventmsg = html_h4(sprintf('%d swimmer(s) found.', $eventrows)) ;

                if (empty($c->_content))
                    $c->add($event, $eventmsg) ;
                else
                    $c->add(html_br(2), $event, $eventmsg) ;
            }
        }

        $sc = null ;

        //  Include Google Map in the output?

        if ($this->getShowMap())
        {
            if ($this->getOpponentSwimClubId() != WPST_NONE)
            {
                $sc = new SwimClubProfile() ;
                $sc->loadSwimClubBySwimClubId($this->getOpponentSwimClubId()) ;

		        $width = '450px' ;
		        $height = '300px' ;
		        $control = 'yes' ;
		        $marker = 'yes' ;
                $align = 'center' ;
                $link = 'no' ;
                $zoom = '15' ;
                $infowindow = 'n' ;

                $address = $sc->getStreet1() ;
                if ($sc->getStreet2() != '')
                    $address .= '<br/>' . $sc->getStreet2() ;
                if ($sc->getStreet3() != '')
                    $address .= '<br/>' . $sc->getStreet3() ;

                $address .= '<br/>' . $sc->getCity() ;
                $address .= ', ' . $sc->getStateOrProvince() ;
                $address .= '<br/>' . $sc->getPostalCode() ;

                if ($sc->getCountry() != WPST_NULL_STRING)
                    $address .= '<br/>' . $sc->getCountry() ;

                $map = new GoogleMapDIVtag() ;
                $map->set_style('border: 3px solid #afb5ff') ;

                $map->setAddress(preg_replace('/<.*?>/', ', ', $address)) ;
                $map->setInfoText($address) ;
                $map->setMapHeight($height) ;
                $map->setMapWidth($width) ;
                $map->setZoomLevel($zoom) ;
                $map->setShowControls(strtolower(substr($control, 0, 1)) == 'y') ;
                $map->setInfoWindowType(strtolower(substr($infowindow, 0, 1)) == 'y'
                    ? PHL_GMAPS_INFO_WINDOW_HTML : PHL_GMAPS_INFO_WINDOW_NONE) ;

                $map->setAPIKey(get_option(WPST_OPTION_GOOGLE_API_KEY)) ;
                $map->generateMap() ;

                if (empty($c->_content))
                    $c->add($map) ;
                else
                    $c->add(html_br(2), $map) ;

            }
            else
            {
                if (empty($c->_content))
                    $c->add(html_h4('No club profile information available to map.')) ;
                else
                    $c->add(html_br(2), html_h4('No club profile information available to map.')) ;
            }
        }

        //  Include Map URLs in the output?

        if ($this->getShowMapLinks())
        {
            if ($this->getOpponentSwimClubId() != WPST_NONE)
            {
                //  Use the loaded swim club profile is maps are shown
                if ($sc == null)
                {
                    $sc = new SwimClubProfile() ;
                    $sc->loadSwimClubBySwimClubId($this->getOpponentSwimClubId()) ;
                }

                //  Show the Google Maps link if it exists
                $url = $sc->getGoogleMapsURL() ;
                if (!empty($url))
                {
                    $c->add(html_br(), html_a($url,
                        'View this map on Google Maps.'), html_br()) ;
                }

                //  Show the MapQuest link if it exists
                $url = $sc->getMapQuestURL() ;
                if (!empty($url))
                {
                    $c->add(html_br(), html_a($url,
                        'View this location on MapQuest.'), html_br()) ;
                }
            }
            else
            {
                if (empty($c->_content))
                    $c->add(html_h4('No club profile information available to map.')) ;
                else
                    $c->add(html_br(2), html_h4('No club profile information available to map.')) ;
            }

        }

        $c->add(html_br(2)) ;
    }

    /**
     * Get report
     *
     * @return mixed - report content
     */
    function getReport()
    {
        return $this->__report_content ;
    }
}

/**
 * Class definition of the printable swim meet report
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimMeetReport
 */
class PrintableSwimMeetReport extends SwimMeetReport
{
    /**
     * Generate the report
     *
     */
    function generateReport()
    {
        if (is_null($this->__report_content))
            $this->__report_content = container() ;

        $c = &$this->__report_content ;

        //  Add the overload CSS so the page is printable.

        $css_container = new CSSContainer() ;
        $css_container->add(new PrintDashboardContentCSS()) ;

        $style = html_style() ;
        $style->add($css_container->render()) ;

        $c->add($style) ;

        parent::generateReport() ;
    }

}

/**
 * Construct the Add SwimMeet form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimMeetsReportForm extends WpSwimTeamForm
{
    /**
     * generated report
     */
    var $__report ;

    /**
     * meet id property - used to track the swim meet
     */

    var $__meetid ;

    /**
     * Set the meet id property
     *
     * @param int - $id - meet id
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id property
     *
     * @return int - $id - meet id
     */
    function getMeetId()
    {
        return $this->__meetid ;
    }

    /**
     * Get the array of swim meet key and value pairs
     *
     * @return mixed - array of swim meet key value pairs
     */
    function _swimmeetSelections($seasonid = null)
    {
        $m = array() ;

        $season = new SwimTeamSeason() ;

        //  Season Id supplied?  If not, use the active season.

        if ($seasonid == null)
            $seasonid = $season->getActiveSeasonId() ;

        //  Find all of the meets in the season

        $meet = new SwimMeet() ;
        $meetIds = $meet->getAllMeetIds(sprintf("seasonid=\"%s\"", $seasonid)) ;

        //  Handle case where no meets have been scheduled yet

        if (!is_null($meetIds))
        {
            foreach ($meetIds as $meetId)
            {
                $meet->loadSwimMeetByMeetId($meetId['meetid']) ;
    
                if ($meet->getMeetType() == WPST_DUAL_MEET)
                    $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                        $meet->getOpponentSwimClubId()) ;
                else
                    $opponent = $meet->getMeetDescription() ;
    
                $meetdate = date('D M j, Y', strtotime($meet->getMeetDate())) ;

                $m[sprintf('%s %s (%s)', $meetdate, $opponent,
                    ucfirst($meet->getLocation()))] = $meetId['meetid'] ;
            }
        }

        return $m ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element('seasonid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;
        $this->add_hidden_element('_userid') ;

        //  Swim  Meet drop down list

        $meets = new FECheckBoxList('Swim Meet', true, '400px', '100px');
        $meets->set_list_data($this->_swimmeetSelections()) ;
        $meets->enable_checkall(true) ;

        $this->add_element($meets) ;

        $summary = new FECheckBox('Meet Summary') ;
        $this->add_element($summary) ;

        $jobs = new FECheckBox('Job Assignments') ;
        $this->add_element($jobs) ;

        $profile = new FECheckBox('Opponent Profile') ;
        $this->add_element($profile) ;

        $showmap = new FECheckBox('Show Map') ;
        $this->add_element($showmap) ;

        $optinoptout = new FECheckBox(get_option(WPST_OPTION_OPT_IN_LABEL) .
            ' / ' . get_option(WPST_OPTION_OPT_OUT_LABEL) . ' List') ;
        $this->add_element($optinoptout) ;

        $sortby = new FERadioGroup('Sort By',
            array(ucfirst(WPST_SORT_BY_NAME) => WPST_SORT_BY_NAME,
            ucfirst(WPST_SORT_BY_SWIMMER_LABEL) => WPST_SORT_BY_SWIMMER_LABEL,
            ucfirst(WPST_SORT_CHRONOLOGICALLY) => WPST_SORT_CHRONOLOGICALLY),
            true, '200px');
        $this->add_element($sortby) ;

        $firstname = new FECheckBox('First Initial Only') ;
        $this->add_element($firstname) ;

        $lastname = new FECheckBox('Last Initial Only') ;
        $this->add_element($lastname) ;

        $nickname = new FECheckBox('Nickname Override') ;
        $this->add_element($nickname) ;

        $output = new FEListBox('Report Format', true, '200px');
        $output->set_list_data(array(
             ucfirst(WPST_GENERATE_STATIC_WEB_PAGE) => WPST_GENERATE_STATIC_WEB_PAGE
            ,ucfirst(WPST_GENERATE_PRINTABLE_WEB_PAGE) => WPST_GENERATE_PRINTABLE_WEB_PAGE
        )) ;
        $this->add_element($output) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields

        $this->set_element_value(get_option(WPST_OPTION_OPT_IN_LABEL) .
            ' / ' . get_option(WPST_OPTION_OPT_OUT_LABEL) . ' List', true) ;
        $this->set_element_value('Sort By', WPST_SORT_BY_NAME) ;
        $this->set_hidden_element_value('_userid', get_current_user_id()) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $table = html_table($this->_width,0,4) ;

        $table->add_row($this->element_label('Swim Meet'),
            $this->element_form('Swim Meet')) ;

        $table->add_row(_HTML_SPACE, html_b(html_br(), 'Report Options')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Meet Summary')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Job Assignments')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Opponent Profile')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Show Map')) ;

        $table->add_row(_HTML_SPACE,
            html_b(html_br(), get_option(WPST_OPTION_OPT_IN_LABEL) .
            ' / ' . get_option(WPST_OPTION_OPT_OUT_LABEL) . ' List Options')) ;

        $table->add_row(_HTML_SPACE, 
            $this->element_form(get_option(WPST_OPTION_OPT_IN_LABEL) .
            ' / ' . get_option(WPST_OPTION_OPT_OUT_LABEL) . ' List')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Sort By')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('First Initial Only')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Last Initial Only')) ;
        $table->add_row(_HTML_SPACE, $this->element_form('Nickname Override')) ;

        $table->add_row(_HTML_SPACE, _HTML_SPACE) ;
        $table->add_row($this->element_label('Report Format'),
            $this->element_form('Report Format')) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;
        
        //printf('%s:%s<br>', basename(__FILE__), __LINE__) ;
	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $success = false ;

        //  Which type of report to generate?
 
        $this->__report = ($this->get_element_value('Report Format')
            == WPST_GENERATE_PRINTABLE_WEB_PAGE)
            ? new PrintableSwimMeetReport() : new SwimMeetReport() ;

        $rpt = &$this->__report ;
        $rpt->setUserId($this->get_hidden_element_value('_userid')) ;

        //  Get the meet ids to report on

        $meets = $this->get_element_value('Swim Meet') ;

        //  Loop through the meets 

        foreach ($meets as $meet)
        {
            $rpt->loadSwimMeetByMeetId($meet) ;

            if (!is_null($this->get_element_value('Meet Summary')))
                $rpt->setMeetSummary(true) ;
        
            if (!is_null($this->get_element_value('Job Assignments')))
                $rpt->setJobAssignments(true) ;
        
            if (!is_null($this->get_element_value('Opponent Profile')))
                $rpt->setOpponentProfile(true) ;
        
            if (!is_null($this->get_element_value('Show Map')))
                $rpt->setShowMap(true) ;
        
            if (!is_null($this->get_element_value(
                get_option(WPST_OPTION_OPT_IN_LABEL) . ' / ' .
                get_option(WPST_OPTION_OPT_OUT_LABEL) . ' List')))
            {
                $rpt->setOptInOptOut(true) ;
                $rpt->setOptInOptOutSortBy($this->get_element_value('Sort By')) ;
                if (!is_null($this->get_element_value('First Initial Only')))
                    $rpt->setUseFirstInitial(true) ;
        
                if (!is_null($this->get_element_value('Last Initial Only')))
                    $rpt->setUseLastInitial(true) ;
        
                if (!is_null($this->get_element_value('Nickname Override')))
                    $rpt->setUseNickName(true) ;

                $rpt->setShowTimeStamp(true) ;
            }

            $rpt->generateReport() ;
        }
        
        $this->set_action_message('Report generated.') ;

        return true ;
    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Generate" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Generate_Cancel() ;
    }
}

?>
