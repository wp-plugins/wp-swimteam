<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Plugin Name: SwimTeam
 * Plugin URI: http://www.wp-swimteam.org
 * Description: WordPress plugin to extend Wordpress into a swim team web site.  The wp-SwimTeam plug extends the WP user registration database to include registration of swim team parents, swimmers, and coaches.  Wp-SwimTeam also manages the volunteer jobs to run a swim meet and provides SDIF import/export in order to interface with meet and team management software from Hy-Tek, WinSwim, and Easy Ware.  The jobs and meet events are based on those used by TSA (<a href="http://www.tsanc.org">Tarheel Swimming Association</a>).
 * Version: 1.29.902
 * Last Modified:  2012/06/04 02:59:11
 * Author: Mike Walsh
 * Author URI: http://www.michaelwalsh.org
 * License: GPL
 * 
 *
 * $Id: swimteam.php 894 2012-05-30 01:48:30Z mpwalsh8 $
 *
 * Wp-SwimTeam plugin constants.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @package Wp-SwimTeam
 * @subpackage admin
 * @version $Rev: 894 $
 * @lastmodified $Date: 2012-05-29 21:48:30 -0400 (Tue, 29 May 2012) $
 * @lastmodifiedby $LastChangedBy: mpwalsh8 $
 *
 */

//  Make sure phpHtmlLib is loaded!  wp-SwimTeam won't work without it.

/**
 * phpHtmlLib not installed error
 *
 * This function echos a DIV containing an error message using the
 * "error" and "fade" classes so the error is consisent with other
 * WordPress errors.
 *
 */
function phphtmllib_not_installed_error() {   
	echo '<div id="phphtmllib-not-installed-error" class="error fade"><p>The <b>Swim Team</b> plugin
        requires the <b>phpHtmlLib</b> plugin to be installed and activated.  Please install and activate
        the <b>phpHtmlLib</b> plugin OR deactivate the Swim Team plugin.  As a precaution, Swim Team plugin
        functionality has been disabled.</p></div>' ;
} 

/**
 * phpHtmlLib installed check
 *
 * This function determines if the phpHtmlLib plugin is
 * installed and active.
 *
 * @return boolean
 */
function phphtmllib_plugin_installed_check()
{
    return function_exists('phphtmllib_plugin_installed') ;
}

/**
 * phpHtmlLib installed check
 *
 * This function determines if the phpHtmlLib plugin is
 * installed and active.
 *
 * @return boolean
 */
function swimteam_plugin_dependency_check()
{
    if (phphtmllib_plugin_installed_check() === false)
        add_action('admin_notices', 'phphtmllib_not_installed_error') ;
}

//  Make sure phpHtmlLib in installed and activated before
//  allowing the swim team plugin to be activated.

//register_activation_hook(__FILE__,'swimteam_plugin_dependency_check');

//  Make sure phpHtmlLib in installed and activated or
//  the rest of the plugin will not initialize correctly.

if (phphtmllib_plugin_installed_check() === false)
{
    add_action('admin_notices', 'phphtmllib_not_installed_error');
    return ;
}


require_once('plugininit.include.php') ;
require_once('menus.include.php') ;
require_once('shortcodes.include.php') ;
require_once('db.include.php') ;
require_once('jobs.include.php') ;
require_once('users.include.php') ;
require_once('agegroups.include.php') ;
require_once('seasons.include.php') ;
require_once('swimmers.include.php') ;
require_once('roster.include.php') ;
require_once('swimclubs.include.php') ;
require_once('swimmeets.include.php') ;
require_once('events.include.php') ;
require_once('sdif.include.php') ;
require_once('options.include.php') ;
require_once('results.include.php') ;

/**
 * Add wp_head action
 *
 * This function adds the CSS link and Javascript
 * references required by the SwimTeam plugin.
 *
 */
function swimteam_wp_head()
{
    swimteam_head_css() ;

    //  Initialize Google Map support
    swimteam_google_maps_init() ;
}

/**
 * Add admin_head action
 *
 * This function adds the CSS references
 * required by the SwimTeam plugin.
 *
 */
function swimteam_admin_head()
{
    //  Load CSS
    //swimteam_head_css() ;

    //  Initialize Google Map support
    swimteam_google_maps_init() ;
}

/**
 * Initialize Google Maps support
 *
 * This function adds Google Maps support when
 * enabled and required by the SwimTeam plugin.
 *
 */
function swimteam_google_maps_init()
{
    //  Initialize Google Map support only if enabled
    if (get_option(WPST_OPTION_ENABLE_GOOGLE_MAPS) == WPST_YES)
    {
        $map = new GoogleMapDivtag() ;
        $map->setAPIKey(get_option(WPST_OPTION_GOOGLE_API_KEY)) ;

        $head_js_link = html_script($map->getHeadJSLink()) ;
        $head_js_code = html_script() ;
        $head_js_code->add($map->getHeadJSCode()) ;

        print $head_js_link->render() ;
        print $head_js_code->render() ;
    }
}

/**
 * Add admin_init action
 *
 * This function adds Javascript references
 * required by the SwimTeam plugin.
 *
 */
function swimteam_admin_init()
{
    global $userdata ;

    //  Load plugin Javascript
    wp_enqueue_script('tablednd',
        plugins_url('js/jquery.tablednd.0.6.min.js', __FILE__),
        array('jquery', 'jquery-ui-core', 'jquery-ui-dialog')) ;

    //  Load CSS files
    //  Construct CSS links for phpHtmlLib CSS files

    $css = PHPHTMLLIB_RELPATH . "/css/fonts.css";
    wp_register_style("phphtmllib-fonts", $css, false) ;
    wp_enqueue_style("phphtmllib-fonts") ;

    $css = PHPHTMLLIB_RELPATH . "/css/colors.css";
    wp_register_style("phphtmllib-colors", $css, false) ;
    wp_enqueue_style("phphtmllib-colors") ;

    $css = WPST_PLUGIN_URL . "/css/swimteam-phphtmllib.css";
    wp_register_style("wp-swimteam-phphtmllib-css", $css, false) ;
    wp_enqueue_style("wp-swimteam-phphtmllib-css") ;

    $css = WPST_PLUGIN_URL . "/css/swimteam.css";
    wp_register_style("wp-swimteam-css", $css, false) ;
    wp_enqueue_style("wp-swimteam-css") ;

    //  Class or Fresh color scheme for Dashboard?

    get_currentuserinfo() ;

    $info = get_user_meta($userdata->ID, 'admin_color', true) ;

    switch ($info)
    {
        case 'fresh':
            $css = WPST_PLUGIN_URL . "/css/swimteam-fresh.css";
            wp_register_style("wp-swimteam-fresh-css", $css, false) ;
            wp_enqueue_style("wp-swimteam-fresh-css") ;
            break ;

        case 'classic':
        default:
            $css = WPST_PLUGIN_URL . "/css/swimteam-classic.css";
            wp_register_style("wp-swimteam-classic-css", $css, false) ;
            wp_enqueue_style("wp-swimteam-classic-css") ;
            break ;
    }
}

/**
 * Add CSS action
 *
 * This function generates the CSS 
 * references required by the SwimTeam plugin.
 *
 * This function generates CSS on the fly for the phpHtmlLib
 * widgets employed by the wp-SwimTeam plugin.  It is not called
 * during normal operation of the plugin which is why references
 * to this function are commented out.  During development this
 * can be uncommented and the inline output captured as a CSS
 * file which is loaded using the enqueu process.
 *
 */
function swimteam_head_css()
{
    //  Generate CSS for phpHtmlLib widgets.
    //  CSS for unused widgets is commented
    //  out for performance reasons.

    //$css_container = html_style() ;
    $css_container = new CSSContainer() ;

    //$css_container->add(new FooterNavCSS(true)) ;
    $css_container->add(new InfoTableCSS());
    //$css_container->add(new NavTableCSS()) ;
    //$css_container->add(new TextCSSNavCSS()) ;
    //$css_container->add(new TextNavCSS()) ;
    //$css_container->add(new VerticalCSSNavTableCSS()) ;
    //$css_container->add(new ImageThumbnailWidgetCSS()) ;
    $css_container->add(new ActiveTabCSS()) ;
    //$css_container->add(new RoundTitleTableCSS()) ;
    //$css_container->add(new ButtonPanelCSS()) ;
    //$css_container->add(new TabListCSS()) ;
    //$css_container->add(new TabWidgetCSS()) ;
    $css_container->add(new TabControlCSS()) ;
    //$css_container->add(new ErrorBoxWidgetCSS()) ;
    //$css_container->add(new ProgressWidgetCSS()) ;

    //  GUIDataList CSS isn't included in standard include file
    include_once(PHPHTMLLIB_ABSPATH . "/widgets/data_list/DefaultGUIDataList.inc") ;
    $css_container->add(new DefaultGUIDataListCSS()) ;

    //  Overload some CSS classes
    $css_container->update_all_values("background", "#fff", "#f9f9f9") ;
        //"#fff url('http://localhost/wp-content/plugins/phphtmllib/images/widgets/tabs_bg.gif') repeat-x bottom;",
        //"#f9f9f9 url('http://localhost/wp-content/plugins/phphtmllib/images/widgets/tabs_bg.gif') repeat-x bottom;") ;
    //print $css_container->render() ;

    $style = html_style() ;
    $style->add($css_container->render()) ;

    print $style->render() ;
}

/**
 * swimteam_install()
 *
 * Install the Swim Team plugin.
 *
 */
function swimteam_install()
{
    //  Initialize the database
    swimteam_database_init() ;

    //  Load all of the options which will force
    //  them to be written to the WordPress option
    //  database.

    require_once('options.class.php') ;

    $options = new SwimTeamOptions() ;
    $options->loadOptions() ;
}

/**
 * swimteam_database_init()
 *
 * Setup or upgrade the swim team database tables
 *
 */
function swimteam_database_init()
{
    global $wpdb ;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    //  New install or an upgrade?
 
    $wpst_db_version = get_option(WPST_DB_OPTION) ;

    if (version_compare($wpst_db_version, WPST_DB_VERSION, '!='))
    {
        //  Construct or update the Job table

        $sql = "CREATE TABLE " . WPST_JOBS_TABLE . " (
            jobid INT(11) NOT NULL auto_increment,
            jobposition VARCHAR(100) NOT NULL default '',
            jobdescription TEXT NOT NULL,
            jobnotes TEXT NOT NULL,
            jobduration ENUM('" . WPST_JOB_DURATION_EVENT . "', '". WPST_JOB_DURATION_FULL_MEET . "', '" . WPST_JOB_DURATION_PARTIAL_MEET . "', '" . WPST_JOB_DURATION_FULL_SEASON . "', '" . WPST_JOB_DURATION_PARTIAL_SEASON . "') NOT NULL,
            jobtype ENUM('" . WPST_JOB_TYPE_PAID . "', '" . WPST_JOB_TYPE_VOLUNTEER . "') NOT NULL,
            joblocation ENUM('" . WPST_HOME . "', '" . WPST_AWAY . "', '" . WPST_BOTH . "', '" . WPST_NA . "') NOT NULL,
            jobcredits SMALLINT(5) NOT NULL,
            jobstatus ENUM('" . WPST_ACTIVE . "', '" . WPST_INACTIVE . "') NOT NULL,
            PRIMARY KEY  (jobid)
	    );" ;
      
        dbDelta($sql) ;

        //  The jobs table no longer needs the job quantity coloumn
        //  but it may still exist in some instances of the database.
        //  dbDelta() doesn't reliable drop the column so let's make
        //  sure it goes away!  Should have handled

        if ($wpdb->get_var(sprintf('SHOW COLUMNS FROM %s LIKE "%s"',
            WPST_JOBS_TABLE, 'jobquantity')) == 'jobquantity')
        {
            $wpdb->query(sprintf('ALTER TABLE %s DROP COLUMN `jobquantity`;', WPST_JOBS_TABLE)) ;
        }

        //  Construct or update the Job Allocation table

        $sql = "CREATE TABLE " . WPST_JOB_ALLOCATIONS_TABLE . " (
            joballocationid INT(11) NOT NULL AUTO_INCREMENT,
            jobid INT(11) NOT NULL,
            jobquantity SMALLINT(5) NOT NULL,
            seasonid INT(11) NOT NULL,
            meetid INT(11) NOT NULL,
            KEY jobid (jobid),
            PRIMARY KEY  (joballocationid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Job Assignments table

        $sql = "CREATE TABLE " . WPST_JOB_ASSIGNMENTS_TABLE . " (
            jobassignmentid INT(11) NOT NULL AUTO_INCREMENT,
            joballocationid INT(11) NOT NULL,
            jobid INT(11) NOT NULL,
            userid SMALLINT(5) NOT NULL,
            seasonid INT(11) NOT NULL,
            meetid INT(11) NOT NULL,
            committed DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            KEY jobid (jobid),
            KEY userid (userid),
            PRIMARY KEY  (jobassignmentid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Age Group table

        $sql = "CREATE TABLE " . WPST_AGE_GROUP_TABLE . " (
            id INT(11) NOT NULL AUTO_INCREMENT,
            minage SMALLINT(5) NOT NULL,
            maxage SMALLINT(5) NOT NULL,
            gender ENUM('" . WPST_GENDER_MALE . "', '" . WPST_GENDER_FEMALE . "') NOT NULL,
            swimmerlabelprefix VARCHAR(50) NOT NULL DEFAULT '',
            registrationfee FLOAT NOT NULL DEFAULT 0,
            PRIMARY KEY  (id)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Users table

        $sql = "CREATE TABLE " . WPST_USERS_TABLE . " (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            userid BIGINT(20) NOT NULL,
            street1 VARCHAR(100) NOT NULL DEFAULT '',
            street2 VARCHAR(100) NOT NULL DEFAULT '',
            street3 VARCHAR(100) NOT NULL DEFAULT '',
            city VARCHAR(100) NOT NULL DEFAULT '',
            stateorprovince VARCHAR(100) NOT NULL DEFAULT '',
            postalcode VARCHAR(100) NOT NULL DEFAULT '',
            country VARCHAR(100) NOT NULL DEFAULT '',
            primaryphone VARCHAR(100) NOT NULL DEFAULT '',
            secondaryphone VARCHAR(100) NOT NULL DEFAULT '',
            contactinfo ENUM('" . WPST_PUBLIC . "', '" . WPST_PRIVATE . "') NOT NULL,
            registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            KEY userid (userid),
            PRIMARY KEY  (id)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Seasons table

        $sql = "CREATE TABLE " . WPST_SEASONS_TABLE . " (
            id INT(11) NOT NULL AUTO_INCREMENT,
            season_label VARCHAR(100) NOT NULL DEFAULT '',
            season_start DATE NOT NULL DEFAULT '0000-00-00',
            season_end DATE NOT NULL DEFAULT '0000-00-00',
            season_status ENUM('" . WPST_ACTIVE . "', '" . WPST_INACTIVE . "', '" . WPST_HIDDEN . "') NOT NULL,
            swimmer_labels ENUM('" . WPST_LOCKED . "', '" . WPST_UNLOCKED .  "', '" . WPST_FROZEN . "') NOT NULL,
            PRIMARY KEY  (id)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Swimmers Table

        $sql = "CREATE TABLE " . WPST_SWIMMERS_TABLE . " (
            id INT(11) NOT NULL AUTO_INCREMENT,
            contact1id SMALLINT(5) NOT NULL,
            contact2id SMALLINT(5) NOT NULL,
            wpuserid SMALLINT(5) NOT NULL,
            firstname VARCHAR(100) NOT NULL DEFAULT '',
            middlename VARCHAR(100) NOT NULL DEFAULT '',
            nickname VARCHAR(100) NOT NULL DEFAULT '',
            lastname VARCHAR(100) NOT NULL DEFAULT '',
            birthdate DATE NOT NULL DEFAULT '0000-00-00',
            gender ENUM('" . WPST_GENDER_MALE . "', '" . WPST_GENDER_FEMALE . "') NOT NULL,
            status ENUM('" . WPST_ACTIVE . "', '" . WPST_INACTIVE . "') NOT NULL,
            results ENUM('" . WPST_PUBLIC . "', '" . WPST_PRIVATE . "') NOT NULL,
            PRIMARY KEY  (id)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Roster table

        $sql = "CREATE TABLE " . WPST_ROSTER_TABLE . " (
            id INT(11) NOT NULL AUTO_INCREMENT,
            seasonid INT(11) NOT NULL,
            swimmerid INT(11) NOT NULL,
            contactid INT(11) NOT NULL,
            status ENUM('" . WPST_ACTIVE . "', '" . WPST_INACTIVE . "', '" . WPST_HIDDEN . "') NOT NULL,
            swimmerlabel VARCHAR(50) NOT NULL,
            registered DATE NOT NULL,
            PRIMARY KEY  (id)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Swim Clubs table

        $sql = "CREATE TABLE " . WPST_SWIMCLUBS_TABLE . " (
            swimclubid BIGINT(20) NOT NULL AUTO_INCREMENT,
            teamname VARCHAR(100) NOT NULL DEFAULT '',
            cluborpoolname VARCHAR(100) NOT NULL DEFAULT '',
            poollength INT(11) NOT NULL,
            poolmeasurementunits VARCHAR(100) NOT NULL DEFAULT '',
            poollanes INT(11) NOT NULL,
            street1 VARCHAR(100) NOT NULL DEFAULT '',
            street2 VARCHAR(100) NOT NULL DEFAULT '',
            street3 VARCHAR(100) NOT NULL DEFAULT '',
            city VARCHAR(100) NOT NULL DEFAULT '',
            stateorprovince VARCHAR(100) NOT NULL DEFAULT '',
            postalcode VARCHAR(100) NOT NULL DEFAULT '',
            country VARCHAR(100) NOT NULL DEFAULT '',
            primaryphone VARCHAR(100) NOT NULL DEFAULT '',
            secondaryphone VARCHAR(100) NOT NULL DEFAULT '',
            contactname VARCHAR(100) NOT NULL DEFAULT '',
            contactemail VARCHAR(100) NOT NULL DEFAULT '',
            website VARCHAR(100) NOT NULL DEFAULT '',
            googlemapsurl VARCHAR(255) NOT NULL DEFAULT '',
            mapquesturl VARCHAR(255) NOT NULL DEFAULT '',
            notes TEXT NOT NULL,
            PRIMARY KEY  (swimclubid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Meets table

        //  Strucuture of Swim Meet Meta table changed at v0.83 so
        //  if database is older, change the column for the 'participation'
        //  column as it was incorrectly labeled.

//        if (version_compare($wpst_db_version, '0.84', '<'))
//        {
//            if ($wpdb->get_var(sprintf("SHOW TABLES LIKE \"%s\"",
//                WPST_SWIMMEETS_TABLE)) == WPST_SWIMMEETS_TABLE)
//            {
//	            $wpdb->query("ALTER TABLE " . WPST_SWIMMEETS_TABLE . 
//                    " CHANGE COLUMN `participation` `participation` ENUM('" . WPST_OPT_IN . "', '" . WPST_OPT_OUT . "', '" . WPST_CLOSED . "') NOT NULL NOT NULL AFTER `opponentscore`;") ;
//            }
//        }

        $sql = "CREATE TABLE " . WPST_SWIMMEETS_TABLE . " (
            meetid INT(11) NOT NULL AUTO_INCREMENT,
            seasonid INT(11) NOT NULL,
            opponentswimclubid INT(11),
            meettype ENUM('" . WPST_DUAL_MEET . "', '" . WPST_TIME_TRIAL . "', '" . WPST_INVITATIONAL . "', '" . WPST_RELAY_CARNIVAL . "') NOT NULL,
            meetdescription VARCHAR(100) NOT NULL DEFAULT '',
            location ENUM('" . WPST_HOME . "', '" . WPST_AWAY . "') NOT NULL,
            meetdate DATE NOT NULL DEFAULT '0000-00-00',
            meettime TIME NOT NULL DEFAULT '00:00:00',
            teamscore FLOAT NOT NULL DEFAULT 0,
            opponentscore FLOAT NOT NULL DEFAULT 0,
            participation ENUM('" . WPST_OPT_IN . "', '" . WPST_OPT_OUT . "') NOT NULL,
            meetstatus ENUM('" . WPST_OPEN . "', '" . WPST_CLOSED . "') NOT NULL,
            KEY seasonid (seasonid),
            KEY opponentswimclubid (opponentswimclubid),
            PRIMARY KEY  (meetid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Events table

        $sql = "CREATE TABLE " . WPST_EVENTS_TABLE . " (
            eventid INT(11) NOT NULL AUTO_INCREMENT,
            eventgroupid INT(11) NOT NULL,
            meetid INT(11) NOT NULL,
            agegroupid INT(11) NOT NULL,
            eventnumber INT(11),
            stroke INT(11),
            distance INT(11),
            course ENUM('" . WPST_SDIF_COURSE_STATUS_CODE_SCM_VALUE . "', '" . WPST_SDIF_COURSE_STATUS_CODE_SCY_VALUE . "', '" . WPST_SDIF_COURSE_STATUS_CODE_LCM_VALUE. "', '" . WPST_SDIF_COURSE_STATUS_CODE_DQ_VALUE . "') NOT NULL,
            KEY agegroupid (agegroupid),
            PRIMARY KEY  (eventid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Event Groups table

        $sql = "CREATE TABLE " . WPST_EVENT_GROUPS_TABLE . " (
            eventgroupid INT(11) NOT NULL auto_increment,
            eventgroupdescription VARCHAR(100) NOT NULL default '',
            eventgroupstatus ENUM('" . WPST_ACTIVE . "', '" . WPST_INACTIVE . "') NOT NULL,
            PRIMARY KEY  (eventgroupid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Options Meta table

        $sql = "CREATE TABLE " . WPST_OPTIONS_META_TABLE . " (
            ometaid BIGINT(20) NOT NULL AUTO_INCREMENT,
            userid BIGINT(20) NOT NULL DEFAULT '0',
            swimmerid BIGINT(20) NOT NULL DEFAULT '0',
            ometakey VARCHAR(255) DEFAULT NULL,
            ometavalue LONGTEXT,
            KEY userid (userid),
            KEY swimmerid (swimmerid),
            KEY ometakey (ometakey),
            PRIMARY KEY  (ometaid)
	    );" ;
      
        dbDelta($sql) ;

        //  Construct or update the Swim Meets Meta table

        //  Strucuture of Swim Meet Meta table changed at v0.83 so
        //  if database is older, change the column for the 'eventcode'
        //  column as it was incorrectly labeled.

        if (version_compare($wpst_db_version, '0.83', '<'))
        {
            if ($wpdb->get_var(sprintf("SHOW TABLES LIKE \"%s\"",
                WPST_SWIMMEETS_META_TABLE)) == WPST_SWIMMEETS_META_TABLE)
            {
                if ($wpdb->get_var(sprintf('SHOW COLUMNS FROM %s LIKE "eventcode"', WPST_SWIMMEETS_META_TABLE)))
                {
	                $wpdb->query("ALTER TABLE " . WPST_SWIMMEETS_META_TABLE . 
                        " CHANGE COLUMN `eventcode` `strokecode` BIGINT(20) NOT NULL DEFAULT '0'") ;
                }
            }
        }

        //  SQL to create or alter the table

        $sql = "CREATE TABLE " . WPST_SWIMMEETS_META_TABLE . " (
            smetaid BIGINT(20) NOT NULL AUTO_INCREMENT,
            userid BIGINT(20) NOT NULL DEFAULT '0',
            swimmerid BIGINT(20) NOT NULL DEFAULT '0',
            swimmeetid BIGINT(20) NOT NULL DEFAULT '0',
            strokecode BIGINT(20) NOT NULL DEFAULT '0',
            eventid BIGINT(20) NOT NULL DEFAULT '0',
            participation ENUM('" . WPST_OPT_IN . "', '" . WPST_OPT_OUT . "') NOT NULL,
            smetakey VARCHAR(255) DEFAULT NULL,
            smetavalue LONGTEXT,
            modified TIMESTAMP,
            KEY userid (userid),
            KEY swimmerid (swimmerid),
            KEY swimmeetid (swimmeetid),
            KEY strokecode (strokecode),
            KEY eventid (eventid),
            KEY smetakey (smetakey),
            PRIMARY KEY  (smetaid)
	    );" ;

        dbDelta($sql) ;

        //  Construct or update the Swim Meets Results table

        $sql = "CREATE TABLE " . WPST_RESULTS_TABLE . " (
            resultsid BIGINT(20) NOT NULL AUTO_INCREMENT,
            swimmerid BIGINT(20) NOT NULL DEFAULT '0',
            swimmeetid BIGINT(20) NOT NULL DEFAULT '0',
            eventid BIGINT(20) NOT NULL DEFAULT '0',
            swimtime FLOAT NOT NULL DEFAULT 0,
            modified TIMESTAMP,
            KEY resultsid (resultsid),
            KEY swimmerid (swimmerid),
            KEY swimmeetid (swimmeetid),
            KEY eventid (eventid),
            PRIMARY KEY  (resultsid)
	    );" ;

        dbDelta($sql) ;
 
        //  Strucuture of Jobs table changed at v0.77 so
        //  if database is older, drop the index for the 'id' column.

        if (version_compare($wpst_db_version, '0.77', '<'))
        {
            if ($wpdb->get_var(sprintf("SHOW TABLES LIKE \"%s\"",
                WPST_JOBS_TABLE)) == WPST_JOBS_TABLE)
            {
                if ($wpdb->get_var(sprintf('SHOW COLUMNS FROM %s LIKE "id"', WPST_JOBS_TABLE)))
                {
	                $wpdb->query("ALTER TABLE " . WPST_JOBS_TABLE . 
                        " CHANGE COLUMN `id` `jobid` INT(11) NOT NULL AUTO_INCREMENT FIRST;") ;
                }
            }
        }
        
        //  Strucuture of Swimmers and Users tables changed at v0.81.
        //  If database is older, drop the 'tshirtsize', 'option1',
        //  'option2', 'option3', 'option4', and 'option5' columns
        //  as optional data is now handled via the Meta Table.

        if (version_compare($wpst_db_version, '0.81', '<'))
        {
            $obsolete_columns = array(
                array('table' => WPST_USERS_TABLE, 'column' => 'tshirtsize')
               ,array('table' => WPST_USERS_TABLE, 'column' => 'option1')
               ,array('table' => WPST_USERS_TABLE, 'column' => 'option2')
               ,array('table' => WPST_USERS_TABLE, 'column' => 'option3')
               ,array('table' => WPST_USERS_TABLE, 'column' => 'option4')
               ,array('table' => WPST_USERS_TABLE, 'column' => 'option5')
               ,array('table' => WPST_SWIMMERS_TABLE, 'column' => 'tshirtsize')
               ,array('table' => WPST_SWIMMERS_TABLE, 'column' => 'option1')
               ,array('table' => WPST_SWIMMERS_TABLE, 'column' => 'option2')
               ,array('table' => WPST_SWIMMERS_TABLE, 'column' => 'option3')
               ,array('table' => WPST_SWIMMERS_TABLE, 'column' => 'option4')
               ,array('table' => WPST_SWIMMERS_TABLE, 'column' => 'option5')
               ,array('table' => WPST_JOBS_TABLE, 'column' => 'jobquantity')
            ) ;

            //  Loop through the list of obsolete columns and drop them

            foreach ($obsolete_columns as $column)
            {
                if ($wpdb->get_var(sprintf('SHOW COLUMNS FROM %s LIKE "%s"',
                    $column['table'], $column['column'])) == $column['column'])
                {
                    $wpdb->query(sprintf('ALTER TABLE %s DROP COLUMN `%s`;',
                        $column['table'], $column['column'])) ;
                }
            }
        }

        //  Update the WordPress option which stores the database version

        update_option(WPST_DB_OPTION, WPST_DB_VERSION) ;
    }
}

/**
 * swimteam_plugin_installed()
 *
 * @return - boolean
 */
function swimteam_plugin_installed()
{
    return true ;
}


/**
 * swimteam_sidebar_list()
 *
 * Construct the <li> tags and echo them.  This <li> content
 * is used on the theme's sidebar to provide links into the
 * swimteam plugin.
 *
 */
function swimteam_sidebar_list()
{
    $c = container() ;
    $ul = html_ul() ;
    $ul->add(html_li(html_a("/wp-admin/admin.php?page=swimteam.php",
        __('Overview'), null, null, __('Overview')))) ;
    $ul->add(html_li(html_a("/wp-admin/admin.php?page=swimteam_roster",
        __('Active Roster'), null, null, __('Active Profile')))) ;
    $ul->add(html_li(html_a("/wp-admin/admin.php?page=swimteam_profile",
        __('My Profile'), null, null, __('My Profile')))) ;
    $ul->add(html_li(html_a("/wp-admin/admin.php?page=swimteam_swimmers",
        __('My Swimmers'), null, null, __('My Swimmers')))) ;

    $c->add(html_li(html_h2(__('Swim Team')), $ul)) ;
    $c->set_collapse(true) ;

    print $c->render() ;
}


/**
 * swimteam_uninstall - clean up when the plugin is deactivated
 *
 */
function swimteam_uninstall()
{
}

/**
 * Reorder Events
 *
 * This function is hooked to an Ajax Response which comes
 * through get_option('url') . "/wp-admin/admin-ajax.php".
 *
 * When the Ajax call comes through, a list of Events Ids is
 * expected and the events will be reordered starting at 1
 * based on the order of event ids received.
 *
 */
function swimteam_reorder_events()
{
	//  Need to parse the Ajax response

    parse_str($_POST["wpst_reorder_events"], $eventorder) ;

    //  Load the Events class so the database can be updated

    require_once("events.class.php") ;

    //  Start event numbering at 1

    $eventnumber = 1 ;

    //  Assume success ...

    $success = true ;

    $event = new SwimTeamEvent() ;

    //  Loop through Ajax submitted event ids

    foreach($eventorder["wpst_eventorder"] as $eventId)
    {
        //  The presentation of the event table includes
        //  header rows which have an id which should be
        //  set to 0 and should not exist - skip any events
        //  which don't exist by Id.

        if ($event->getSwimTeamEventExistsByEventId($eventId))
        {
            $event->loadSwimTeamEventByEventId($eventId) ;
            $event->setEventNumber($eventnumber++) ;

            $success &= ($event->updateSwimTeamEvent() != null) ;
        }
    }

    //  Return proper success message

    if ($success) 
    {
        $message = "Events successfully reordered." ;
    }
    else
    {
        $message = "Events were not successfully reordered." ;
    }

    echo $message ;

	exit;
}

/**
 * Hook for adding CSS links and other HEAD stuff
 */
add_action('wp_head', 'swimteam_wp_head');
add_action('admin_head', 'swimteam_admin_head');
add_action('admin_init', 'swimteam_admin_init');
add_action('wp_ajax_wpst_reorder_events', 'swimteam_reorder_events');


/**
 * Hook for adding admin menus
 */
add_action('admin_menu', 'swimteam_add_menu_pages');

/**
 *  Activate the plugin initialization function
 */
register_activation_hook(plugin_basename(__FILE__), 'swimteam_install') ;
register_deactivation_hook(plugin_basename(__FILE__), 'swimteam_uninstall') ;

/**
 * Enhance the plugin page with specific information
 * about the plugin.
 */
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'swimteam_plugin_actlinks') ; 

function swimteam_plugin_actlinks($links)
{ 
    $url = get_bloginfo('url') .
        "/wp-admin/admin.php?page=swimteam_options_page" ;

    // Add a link to this plugin's settings page
    $settings_link = "<a href=\"{$url}\">Options</a>"; 
    array_unshift( $links, $settings_link ); 
    return $links; 
}

/**
 * Add a filter to handle the login redirect action.
 */
//add_filter('loginout', 'wpst_login_redirect');

/**
 * login redirect action
 *
 * @param - string - link to seed the redirect
 */
function wpst_login_redirect2($link)
{
	$currenturl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	if (is_user_logged_in())
    {
        switch (get_option(WPST_OPTION_LOGIN_REDIRECT))
        {
            case WPST_DASHBOARD_PAGE:
                //var_dump(WPST_DASHBOARD_PAGE) ;
                break ;

            case WPST_SWIMTEAM_OVERVIEW_PAGE:
                //var_dump(WPST_SWIMTEAM_OVERVIEW_PAGE) ;
                break ;

            case WPST_HOME_PAGE:
                //var_dump(WPST_HOME_PAGE) ;
                break ;

            case WPST_PREVIOUS_PAGE:
                //var_dump(WPST_DASHBOARD_PAGE) ;
                $link = str_replace( '">', '&amp;redirect_to='
                    . urlencode( $currenturl ) . '">', $link );
                break ;

            default:
                //var_dump("default") ;
                break ;
        }
    }
	else
    {
        //var_dump(basename(__FILE__) . ":" . __LINE__) ;
        $link = str_replace( '">',
            '?redirect_to=' . urlencode( $currenturl ) . '">', $link );
    }

	return $link;
}

/**
 * Redirect upon login
 *
 * @param - string - URL to redirect to
 * @param - string - Requested URL to redirect to
 * @param - int - user Id
 * @return - string - redirected URL
 */
function wpst_login_redirect($redirect_to, $request_redirect_to, $user)
{
    if (is_a($user, 'WP_User') &&
        current_user_can('manage_options') === false)
    {
        switch (get_option(WPST_OPTION_LOGIN_REDIRECT))
        {
            case WPST_SWIMTEAM_OVERVIEW_PAGE:
                $redirect_to .= "admin.php?page=swimteam.php" ;
                break ;

            case WPST_HOME_PAGE:
                $redirect_to = get_bloginfo('url') ;
                break ;

            //case WPST_PREVIOUS_PAGE:
                break ;

            case WPST_DASHBOARD_PAGE:
                break ;

            default:
                break ;
        }

    }

    return $redirect_to;
}
 
// add filter with default priority (10), filter takes (3) parameters
add_filter('login_redirect','wpst_login_redirect', 10, 3);

/**
 * Default to First/Last instead of username for new users
 *
 * @param - $name string - URL to redirect to
 * @return - string - proper display name
 * @see http://lists.automattic.com/pipermail/wp-hackers/2012-May/043066.html
 */
function wpst_default_display_name($name)
{
    if (isset($_POST['display_name']))
        return sanitize_text_field($_POST['display_name']) ;

    if (isset($_POST['first_name']))
    {
        $name = sanitize_text_field($_POST['first_name']) ;
        if (isset($_POST['last_name']))
            $name .= ' '. sanitize_text_field($_POST['last_name']) ;
    }

    return $name ;
}
add_filter('pre_user_display_name','wpst_default_display_name') ;

/**
 *  Build the WordPress Dashboard widget to dispkay an overview of the swim team.
 *
 */
function wpst_dashboard_widget()
{
    require_once('agegroups.class.php') ;

    $br = html_br() ;
    $div = html_div() ;

    $season = new SwimTeamSeason() ;

    if ($season->loadActiveSeason())
        $div->add(html_h4(sprintf('Active Season is:  %s',
            $season->getSeasonLabel())), $br) ;
    else
        $div->add(html_h4('No Season Active.'), $br) ;

    //  Age group summary

    $agegroups = new SwimTeamAgeGroupInfoTable('Active Swimmers', '100%') ;
    $agegroups->constructAgeGroupInfoTable() ;
    $agdiv = html_div() ;
    $agdiv->add($agegroups) ;

    //  Meet summary

    $meetsummary = new SwimMeetScheduleInfoTable('Meet Schedule','100%') ;
    $meetsummary->constructSwimMeetScheduleInfoTable() ;
    $msdiv = html_div() ;
    $msdiv->add($meetsummary) ;

    $br->set_tag_attribute('clear', 'both') ;
    $div->add($msdiv, $br, $br, $agdiv, $br) ;

    $div->add(html_h6('wp-SwimTeam plugin v' .
        WPST_VERSION, $br, 'wp-SwimTeam database v' .
        WPST_DB_VERSION)) ;

    echo $div->render() ;
}

/**
 * Set up the WordPress dashboard widget(s)
 *
 */
function wpst_dashboard_widget_setup()
{
    wp_add_dashboard_widget('dashboard_custom_feed', 'Swim Team Overview', 'wpst_dashboard_widget') ;
}

//  Hook into the Dashboard setup action to add Dashboard widgets
add_action('wp_dashboard_setup', 'wpst_dashboard_widget_setup') ;

?>
