<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Plugin menus.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage menus
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

//error_reporting(E_ALL) ;

/**
 * Swim Team page content
 *
 * @param string - content to be displayed within the div.
 *
 */
function swimteam_menu_page_content($content)
{
    printf("<div class=\"wrap\">\n%s\n</div>\n", $content) ;
}

// Hook for adding admin menus
add_action('admin_menu', 'swimteam_add_menu_pages') ;


/**
 * Add pages action for admin hook
 *
 * This function adds all of the plugin admin menus.
 *
 */
function swimteam_add_menu_pages()
{
    global $swimteamPluginHooks ;

    //$swimteamFileName = plugin_basename(__FILE__);
    $swimteamFileName = "swimteam.php" ;

    // Add a top-level menu - the Wordpress Codex notes this is ill-advised
    $swimteamPluginHooks['Swim Team'] = add_menu_page('Swim Team',
        'Swim Team', 'read', $swimteamFileName, 'swimteam_users_page', 'div') ;

    // Add a submenu to the custom top-level menu:
    $swimteamPluginHooks['Manage'] = add_submenu_page($swimteamFileName, 'Manage',
        'Manage', 'edit_others_posts', 'swimteam_manage_page',
        'swimteam_manage_page') ;

    // Add a submenu to the custom top-level menu:
    $swimteamPluginHooks['Reports'] = add_submenu_page($swimteamFileName, 'Reports',
        'Reports', 'read', 'swimteam_reports_page',
        'swimteam_reports_page') ;

    // Add a submenu to the custom top-level menu:
    $swimteamPluginHooks['Options'] = add_submenu_page($swimteamFileName, 'Options',
        'Options', 'manage_options', 'swimteam_options_page',
        'swimteam_options_page') ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_sublevel_tab_test()
{
    swimteam_menu_page_content("<h2>Swim Team Test</h2>") ;
    //require_once("admin/overview.php") ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_sublevel_tab_overview()
{
    //swimteam_menu_page_content("<h2>Swim Team Overview</h2>") ;
    require_once("user/overview.php") ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_manage_jobs()
{
    //swimteam_menu_page_content("<h2>Swim Team Volunteer Roles</h2>") ;
    require_once("admin/jobs.php") ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_manage_age_groups()
{
    //swimteam_menu_page_content("<h2>Swim Team Age Groups</h2>") ;
    require_once("admin/agegroups.php") ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_manage_events()
{
    //swimteam_menu_page_content("<h2>Swim Team Events</h2>") ;
    require_once("admin/events.php") ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_sublevel_tab_2()
{
    swimteam_menu_page_content("<h2>Swim Team Tab 2</h2>") ;
}

/**
 * Build the submenu page content
 *
 */
function swimteam_sublevel_tab_3()
{
    swimteam_menu_page_content("<h2>Swim Team Tab 3</h2>") ;
}

/**
 * Build the Users submenu page content
 *
 */
function swimteam_swimmers()
{
    //swimteam_menu_page_content("<h2>Swim Team Swimmers Page</h2>") ;
    require_once("user/swimmers.php") ;
}

/**
 * Build the Users submenu page content
 *
 */
function swimteam_profile()
{
    //swimteam_menu_page_content("<h2>Swim Team Profile Page</h2>") ;
    require_once("user/profile.php") ;
}

/**
 * Build the Users submenu page content
 *
 */
function swimteam_roster()
{
    //swimteam_menu_page_content("<h2>Swim Team Roster</h2>") ;
    require_once("user/roster.php") ;
}

/**
 * Build the Admin submenu page content
 *
 */
function swimteam_seasons()
{
    swimteam_menu_page_content("<h2>Swim Team Seasons Page</h2>") ;
    //require_once("admin/seasons.php") ;
}

/**
 * Build the User submenu page content
 *
 */
function swimteam_users_page()
{
    //swimteam_menu_page_content( "<h2>Swim Team Users Page</h2>") ;
    require_once("user/users_menu.php") ;
}

/**
 * Build the Manage submenu page content
 *
 */
function swimteam_manage_page()
{
    //swimteam_menu_page_content( "<h2>Swim Team Manage Page</h2>") ;
    require_once("user/manage_menu.php") ;
}

/**
 * Build the Reports submenu page content
 *
 */
function swimteam_reports_page()
{
    //swimteam_menu_page_content( "<h2>Swim Team Reports Page</h2>") ;
    require_once("user/reports_menu.php") ;
}

/**
 * Build the Options submenu page content
 *
 */
function swimteam_options_page()
{
    //swimteam_menu_page_content( "<h2>Swim Team Options Page</h2>") ;
    require_once("user/options_menu.php") ;
}

/**
 * Build the Team Profile submenu page content
 *
 */
function swimteam_team_profile()
{
    //swimteam_menu_page_content( "<h2>Swim Team Manage Seasons</h2>") ;
    require_once("admin/teamprofile.php") ;
}

/**
 * Build the SDIF Profile submenu page content
 *
 */
function swimteam_sdif_profile()
{
    //swimteam_menu_page_content( "<h2>Swim Team SDIF Profile</h2>") ;
    require_once("admin/sdifprofile.php") ;
}

/**
 * Build the All Swimmers submenu page content
 *
 */
function swimteam_all_swimmers()
{
    //swimteam_menu_page_content( "<h2>Swim Team All Swimmers</h2>") ;
    require_once("admin/swimmers.php") ;
}

/**
 * Build the Swim Team Reports submenu page content
 *
 */
function swimteam_report_generator()
{
    //swimteam_menu_page_content( "<h2>Swim Team All Swimmers</h2>") ;
    require_once("admin/reportgen.php") ;
}

/**
 * Build the Manage Swim Clubs page content
 *
 */
function swimteam_manage_swimclubs()
{
    //swimteam_menu_page_content( "<h2>Swim Team Manage Swim Clubs</h2>") ;
    require_once("admin/swimclubs.php") ;
}

/**
 * Build the Manage submenu page content
 *
 */
function swimteam_manage_seasons()
{
    //swimteam_menu_page_content( "<h2>Swim Team Manage Seasons</h2>") ;
    require_once("admin/seasons.php") ;
}

/**
 * Build the Parents & Guardians submenu page content
 *
 */
function swimteam_parents_and_guardians()
{
    //swimteam_menu_page_content( "<h2>Swim Team Parents & Guardians</h2>") ;
    require_once("admin/users.php") ;
}

/**
 * Build the Manage submenu page content
 *
 */
function swimteam_manage_swimmeets()
{
    //swimteam_menu_page_content( "<h2>Swim Team Manage Meets</h2>") ;
    require_once("user/swimmeets.php") ;
}

/**
 * Build the theme (presentation) menu page content
 *
 */
function swimteam_theme_page()
{
    swimteam_menu_page_content( "<h2>Swim Team Theme Page</h2>") ;
}

/**
 * Build the top level menu page content
 *
 */
function swimteam_toplevel_page()
{
    swimteam_menu_page_content( "<h2>Swim Team Top Level Page</h2>") ;
}

/**
 * Build Contextual Help for the Swim Team module
 *
 * This function defines Contextual Help for all pages which appear
 * under the SwimTeam screen.  This takes advantage of the new Contextual
 * Help which appears in WordPress 3.0 and later.  Other modules within
 * the plugin will replace the content of the Contextual Help using jQuery
 * to change the HTML content of the DIV.
 */
function swimteam_plugin_contextual_help($contextual_help, $screen_id, $screen)
{
    //global $swimteamPluginHooks ;

    if ($screen->parent_base == 'swimteam')
    {
        $contextual_help = 'Swim Team Contextual Help goes here.  It is
            <b><i>supposed</i></b> to be replaced with relevant content using jQuery.' ;
    }

    return $contextual_help ;
}


//  Construct the Container

add_filter('contextual_help', 'swimteam_plugin_contextual_help', 10, 3);
?>
