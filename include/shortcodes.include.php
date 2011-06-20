<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Plugin shortcodes.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage shortcodes
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once(PHPHTMLLIB_ABSPATH . "/widgets/GoogleMap.inc") ;
require_once("textmap.class.php") ;

/**
 * wpst_flickr_slideshow shortcode handler
 *
 * Build a short code handler to display a Flickr slide show.
 *
 * [wpst_flickr_slideshow userid="id" slideshowid="id"
 *     frameborder="pixels" *     width="pixels" height="pixels"
 *     scrolling="yes|no" align="left|center|right" view="yes|y"]
 *
 * To show this Flickr slide show:
 *
 * http://www.flickr.com/photos/27604893@N04/sets/72157605764227907/show
 *
 * Use this shortcode:
 *
 * [wpst_flickr_slideshow userid="27604893@N04" slideshowid="72157605764227907"]
 *
 * This is the resulting IFRAME tag which is returned to the caller.
 *
 * <iframe align=center src=http://www.flickr.com/slideShow/index.gne?
 *     user_id=27604893@N04&set_id=72157605761943480 frameBorder=0
 *     width=500 scrolling=no height=500></iframe>
 *
 * If the 'view="yes"' or 'view="y"' attribute is include, a linl to the
 * Flickr slideshow will be placed under the IFRAME.
 *     
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_flickr_slideshow_sc_handler($atts)
{
    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
		'userid' => '',
		'slideshowid' => '',
		'frameborder' => 'default 0',
		'width' => '500',
		'height' => '500',
		'scrolling' => 'no',
        'align' => 'center',
        'view' => 'no',
	), $atts)) ;

    //  If either the userid or slideshowid are missing then
    //  we have a problem and can't do anything meaningful.

    if (empty($userid) || empty($slideshowid))
    {
        $c->add(html_br(),
            html_b("wpst_flickr_slideshow::Invalid Shortcode Syntax"),
            html_br(2)) ;

        return $c->render() ;
    }

    $if_src = "http://www.flickr.com/slideShow/index.gne?" .
        sprintf("user_id=%s&set_id=%s frameBorder=%s align=%s",
            $userid, $slideshowid, $frameborder, $align) ;


    $c->add(html_iframe($if_src, $width, $height, $scrolling)) ;

    if (($view == 'yes') || ($view == 'y'))
    {
        $link = "http://www.flickr.com/slideShow/index.gne?" .
        sprintf("user_id=%s&set_id=%s", $userid, $slideshowid) ;

        $c->add(html_br(2), html_a($link, "View this slideshow on Flickr."), html_br(2)) ;
    }

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_flickr_slideshow', 'wpst_flickr_slideshow_sc_handler');

/**
 * wpst_meet_schedule_sc_handler shortcode handler
 *
 * Build a short code handler to display a meet schedule
 *
 * [wpst_meet_schedule seasonid="id"]
 *
 * Use this shortcode:
 *
 * [wpst_meet_schedule seasonid="2"]
 *
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_meet_schedule_sc_handler($atts)
{
    require_once("seasons.class.php") ;
    require_once("swimmeets.class.php") ;

    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
		'seasonid' => '',
	), $atts)) ;

    $meetsummary = new SwimMeetScheduleInfoTable("Meet Schedule","500px") ;

    //  If the season id is empty, use the current season.

    if (empty($seasonid))
        $meetsummary->constructSwimMeetScheduleInfoTable() ;
    else
        $meetsummary->constructSwimMeetScheduleInfoTable($seasonid) ;

    $c->add(html_br(), $meetsummary, html_br(2)) ;

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_meet_schedule', 'wpst_meet_schedule_sc_handler');

/**
 * wpst_club_profile_sc_handler shortcode handler
 *
 * Build a short code handler to display a meet schedule
 *
 * [wpst_club_profile clubid="id" googlemap=y|yes mapquestmap=y|yes]
 *
 * Use this shortcode:
 *
 * [wpst_club_profile clubid="2"]
 *
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_club_profile_sc_handler($atts)
{
    require_once("swimclubs.class.php") ;

    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
		'clubid' => '',
		'googlemap' => 'n',
		'mapquestmap' => 'n',
		'frameborder' => '1',
		'width' => '450px',
		'height' => '300px',
		'control' => 'yes',
		'marker' => 'yes',
        'align' => 'center',
        'link' => 'no',
        'zoom' => '15',
        'infowindow' => 'n'
	), $atts)) ;

    $sc = new SwimClubProfile() ;

    //  If the clubid is missing then we have a
    //  problem and can't do anything meaningful.

    if (empty($clubid))
    {
        $c->add(html_br(),
            html_b("wpst_club_profile::Invalid Shortcode Syntax"),
            html_br(2)) ;

        return $c->render() ;
    }
    else
    {
        if (!$sc->loadSwimClubBySwimClubId($clubid))
        {
            $c->add(html_br(),
                html_b("wpst_club_profile::Invalid Club Id"),
                html_br(2)) ;

            return $c->render() ;
        }
    }

    if ($sc->getWebSite() != WPST_NULL_STRING)
        $info = new SwimTeamInfoTable(html_a($sc->getWebSite(),
            $sc->getClubOrPoolName() . " " .
            $sc->getTeamName()), $width, "center") ;
    else
        $info = new SwimTeamInfoTable($sc->getClubOrPoolName() . " " .
            $sc->getTeamName(), $width, "center") ;

    $info->add_row("Pool Length", $sc->getPoolLength() .
        " " . ucfirst($sc->getPoolMeasurementUnits()) .
        " (" . $sc->getPoolLanes() . " Lanes)") ;

    $address = $sc->getStreet1() ;
    if ($sc->getStreet2() != "")
        $address .= "<br/>" . $sc->getStreet2() ;
    if ($sc->getStreet3() != "")
        $address .= "<br/>" . $sc->getStreet3() ;

    $address .= "<br/>" . $sc->getCity() ;
    $address .= ", " . $sc->getStateOrProvince() ;
    $address .= "<br/>" . $sc->getPostalCode() ;

    if ($sc->getCountry() != WPST_NULL_STRING)
        $address .= "<br/>" . $sc->getCountry() ;

    $info->add_row("Address", $address) ;

    if ($sc->getPrimaryPhone() != WPST_NULL_STRING)
        $info->add_row(get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL),
            $sc->getPrimaryPhone()) ;
    if ($sc->getSecondaryPhone() != WPST_NULL_STRING)
        $info->add_row(get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL),
            $sc->getSecondaryPhone()) ;
    if ($sc->getContactName() != WPST_NULL_STRING)
        $info->add_row("Contact Name", $sc->getContactName()) ;

    if ($sc->getEmailAddress() != WPST_NULL_STRING)
        $info->add_row("Email Address", html_a("mailto:" .
            $sc->getEmailAddress(), $sc->getEmailAddress())) ;

    if ($sc->getNotes() != WPST_NULL_STRING)
        $info->add_row("Notes", nl2br($sc->getNotes())) ;

    $div = html_div() ;
    $div->set_tag_attribute("align", $align) ;

    //  Include Google Map in the output?

    if (strtolower(substr($googlemap, 0, 1)) == 'y')
    {
        $map = new GoogleMapDIVtag() ;
        $map->set_style("border: 3px solid #afb5ff") ;

        $map->setAddress(preg_replace("/<.*?>/", ", ", $address)) ;
        $map->setInfoText($address) ;
        $map->setMapHeight($height) ;
        $map->setMapWidth($width) ;
        $map->setZoomLevel($zoom) ;
        $map->setShowControls(strtolower(substr($control, 0, 1)) == 'y') ;
        $map->setInfoWindowType(strtolower(substr($infowindow, 0, 1)) == 'y'
            ? PHL_GMAPS_INFO_WINDOW_HTML : PHL_GMAPS_INFO_WINDOW_NONE) ;

        $map->setAPIKey(get_option(WPST_OPTION_GOOGLE_API_KEY)) ;
        $map->generateMap() ;
        $div->add($map) ;

        //  Show the link too?

        if (strtolower(substr($link, 0, 1)) == 'y')
        {
            $div->add(html_br(), html_a($sc->getGoogleMapsURL(),
                "View this map on Google Maps."), html_br()) ;
        }

        if (strtolower(substr($mapquestmap, 0, 1)) == 'y')
        {
            $div->add(html_br(), html_a($sc->getMapQuestURL(),
                "View this location on MapQuest."), html_br()) ;
        }
    }

    $c->add($info, html_br(), $div) ;

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_club_profile', 'wpst_club_profile_sc_handler');

/**
 * wpst_google_map_sc_handler shortcode handler
 *
 * Build a short code handler to display a google map
 *
 * [wpst_google_map address="address"]
 *
 * Use this shortcode:
 *
 * [wpst_google_map address="430 St. Andres Lane, Cary, NC, 27511"]
 *
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_google_map_sc_handler($atts)
{
    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
        'address' => '',
		'frameborder' => '1',
		'width' => '450',
		'height' => '300',
		'control' => 'yes',
		'marker' => 'yes',
        'align' => 'center',
        'zoom' => '15',
        'infowindow' => 'n'
	), $atts)) ;

    $div = html_div() ;
    $div->set_tag_attribute("align", $align) ;

    $map = new GoogleMapDIVtag() ;
    $map->set_style("border: 3px solid #afb5ff") ;

    //$map->set_id("map_1") ;
    $map->setAddress($address) ;
    $map->setInfoText($address) ;
    $map->setMapHeight($height) ;
    $map->setMapWidth($width) ;
    $map->setZoomLevel($zoom) ;
    $map->setShowControls(strtolower(substr($control, 0, 1)) == 'y') ;
    $map->setInfoWindowType(strtolower(substr($infowindow, 0, 1)) == 'y'
        ? PHL_GMAPS_INFO_WINDOW_HTML : PHL_GMAPS_INFO_WINDOW_NONE) ;

    $map->setAPIKey(get_option(WPST_OPTION_GOOGLE_API_KEY)) ;
    $map->generateMap() ;
    $div->add($map, html_br()) ;

    $c->add($div) ;

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_google_map', 'wpst_google_map_sc_handler');

/**
 * wpst_meet_report shortcode handler
 *
 * Build a short code handler to display a meet schedule
 *
 * [wpst_meet_report meetid="id"]
 *
 * Optional shortcode attributes
 *   summary=y|yes|n|no
 *   profile=y|yes|n|no
 *   showmap=y|yes|n|no
 *   optinoptout=y|yes|n|no
 *   firstinitial=y|yes|n|no
 *   lastinitial=y|yes|n|no
 *   usenickname=y|yes|n|no
 *   timestamp=y|yes|n|no
 *   sortby=n|name|s|swimmerlabel|l|label|c|chrnological
 *
 *
 * Use this shortcode:
 *
 * [wpst_meet_report meetid="2" optinoptout="y" sortby="name"]
 *
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_meet_report_sc_handler($atts)
{
    require_once("swimmeets.report.class.php") ;

    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
        'meetid' => '',
        'summary' => 'n',
        'profile' => 'n',
        'showmap' => 'n',
        'showmaplinks' => 'n',
        'optinoptout' => 'y',
        'firstinitial' => 'n',
        'lastinitial' => 'n',
        'usenickname' => 'n',
        'timestamp' => 'n',
        'sortby' => 'name'
	), $atts)) ;

    //  If the meetid is missing then we have a
    //  problem and can't do anything meaningful.

    if (empty($meetid))
    {
        $c->add(html_br(),
            html_b("wpst_meet_report::Invalid Shortcode Syntax"),
            html_br(2)) ;

        return $c->render() ;
    }

    //  Set up the meet report

    $meetreport = new SwimMeetReport() ;
    $meetreport->setShortCodeMode(true) ;
    $meetreport->loadSwimMeetByMeetId($meetid) ;

    //  Display the meet summary?
    $meetreport->setMeetSummary(strtolower(substr($summary, 0, 1)) == 'y') ;

    //  Display the opponent profile?
    $meetreport->setOpponentProfile(strtolower(substr($profile, 0, 1)) == 'y') ;

    //  Display the opponent map?
    $meetreport->setShowMap(strtolower(substr($showmap, 0, 1)) == 'y') ;

    //  Display the opponent map links?
    $meetreport->setShowMapLinks(strtolower(substr($showmaplinks, 0, 1)) == 'y') ;

    //  Display the opt-in opt-out report?
    $meetreport->setOptInOptOut(strtolower(substr($optinoptout, 0, 1)) == 'y') ;

    /*
    else if (strtolower(substr($sortby, 0, 1)) == 's')
        $sortby = WPST_SORT_BY_SWIMMER_LABEL ;
    else
        $sortby = WPST_SORT_BY_NAME ;
     */

    switch (strtolower($sortby))
    {
        case 'c':
        case 'chronologically':
            $sortby = WPST_SORT_CHRONOLOGICALLY ;
            break ;

        case 's':
        case 'swimmerlabel':
        case 'l':
        case 'label':
            $sortby = WPST_SORT_BY_SWIMMER_LABEL ;
            break ;

        default:
            $sortby = WPST_SORT_BY_NAME ;
            break ;
    }

    $meetreport->setOptInOptOutSortBy($sortby) ;

    if (strtolower(substr($firstinitial, 0, 1)) == 'y')
        $meetreport->setUseFirstInitial(true) ;

    if (strtolower(substr($lastinitial, 0, 1)) == 'y')
        $meetreport->setUseLastInitial(true) ;

    if (strtolower(substr($usenickname, 0, 1)) == 'y')
        $meetreport->setUseNickname(true) ;

    if (strtolower(substr($timestamp, 0, 1)) == 'y')
        $meetreport->setShowTimeStamp(true) ;

    $meetreport->generateReport() ;

    $c->add(html_br(), $meetreport->getReport(), html_br(2)) ;

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_meet_report', 'wpst_meet_report_sc_handler');

/**
 * wpst_meet_job_assignments shortcode handler
 *
 * Build a short code handler to display meet job assignments
 *
 * [wpst_meet_job_assignments meetid="id"]
 *
 * Optional shortcode attributes
 *   firstinitial=y|yes|n|no
 *   lastinitial=y|yes|n|no
 *   username=y|yes|n|no
 *   email=y|yes|n|no
 *
 *
 * Use this shortcode:
 *
 * [wpst_meet_job_assignments meetid="2" firstinitial="y" email="y"]
 *
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_meet_job_assignments_sc_handler($atts)
{
    require_once("jobs.class.php") ;

    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
        'meetid' => '',
        'firstinitial' => 'n',
        'lastinitial' => 'n',
        'username' => 'n',
        'email' => 'n'
	), $atts)) ;

    //  If the meetid is missing then we have a
    //  problem and can't do anything meaningful.

    if (empty($meetid))
    {
        $c->add(html_br(),
            html_b("wpst_meet_job_assignments::Invalid Shortcode Syntax"),
            html_br(2)) ;

        return $c->render() ;
    }

    //  Set up the job assignment report

    $meet =  SwimTeamTextMap::__mapMeetIdToText($meetid, true) ;
    $title = sprintf("Job Assignments:  %s %s (%s)",
        $meet["opponent"], $meet["date"], $meet["location"]) ;

    $jobassignmentreport = new SwimMeetJobAssignmentInfoTable($title, "100%") ;
    $jobassignmentreport->setMeetId($meetid) ;

    if (strtolower(substr($firstinitial, 0, 1)) == 'y')
        $jobassignmentreport->setShowFirstInitial(true) ;

    if (strtolower(substr($lastinitial, 0, 1)) == 'y')
        $jobassignmentreport->setShowLastInitial(true) ;

    if (strtolower(substr($username, 0, 1)) == 'y')
        $jobassignmentreport->setShowUsername(true) ;

    if (strtolower(substr($email, 0, 1)) == 'y')
        $jobassignmentreport->setShowEmail(true) ;

    $jobassignmentreport->constructSwimMeetJobAssignmentInfoTable() ;

    $c->add(html_br(), $jobassignmentreport, html_br(2)) ;

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_meet_job_assignments', 'wpst_meet_job_assignments_sc_handler');

/**
 * wpst_job_descriptions shortcode handler
 *
 * Build a short code handler to display job descriptions
 *
 * [wpst_job_descriptions]
 *
 * Optional shortcode attributes
 *   inactive=y|yes|n|no
 *
 *
 * Use this shortcode:
 *
 * [wpst_job_descriptions inactive="y"]
 *
 * @param array - shortcode attributes
 * @return string -  HTML code
 */
function wpst_job_descriptions_sc_handler($atts)
{
    require_once("jobs.class.php") ;

    $c = container() ;

    //  Parse the shortcode
 
	extract(shortcode_atts(array(
        'inactive' => 'n'
	), $atts)) ;

    //  If the meetid is missing then we have a
    //  problem and can't do anything meaningful.

    //  Set up the job descriptions report

    $jobdescriptions = new SwimTeamJobDescriptionsInfoTable("Job Descriptions", "100%") ;

    if (strtolower(substr($inactive, 0, 1)) == 'y')
        $jobdescriptions->setShowInactive(true) ;

    $jobdescriptions->constructSwimTeamJobDescriptionsInfoTable() ;

    $c->add(html_br(), $jobdescriptions, html_br(2)) ;

	return $c->render() ;
}

//  Register the shortcode
add_shortcode('wpst_job_descriptions', 'wpst_job_descriptions_sc_handler');
?>
