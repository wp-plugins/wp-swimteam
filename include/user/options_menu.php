<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Options admin page content.
 *
 * $Id: options_menu.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('widgets.class.php') ;

/**
 * Class definition of the Options Page
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see Container
 */
class OptionsTabContainer extends Container
{
    /**
     * Construct the content of the Options Tab Container
     */
    function OptionsTabContainer()
    {
        global $pagenow ;

        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
        //
        //  No matter what the container content is, it must be
        //  enclosed in a DIV with a class of 'wrap' to fit in
        //  the Wordpress admin page structure.
 
        $div = html_div('wrap') ;
        //$div->set_style('background: #ffffff; margin: 0px 10px;') ;

        //  If the wp-SwimTeam database version isn't current,
        //  nag the user as operation of the plugin in unpredicatable.

        $wpst_db_version = get_option(WPST_DB_OPTION) ;

        if ($wpst_db_version != WPST_DB_VERSION)
        {
            $table = html_table(null, '0', '10') ;
            $table->add_row(html_b('Warning:'), sprintf('The wp-SwimTeam
                plugin database version is incorrect (v%s vs. v%s).  Please
                deactivate and reactivate the plugin to ensure proper
                operation and database integrity.',
                $wpst_db_version, WPST_DB_VERSION)) ;
            $div->add(html_div('updated fade', $table)) ;
        }        $div->add(html_h2('Swim Team Options')) ;

        //  Default to Tab #1 if no tab passed as part of URI

        $activetab = (array_key_exists('tab', $_GET)) ? $_GET['tab'] : '1' ;

        //  Build up the tab content
 
        $tab_index = 1 ;
        $tab_content = array() ;

        $tab_content[] = new TabWidgetContent('Overview',
            $tab_index++, 'overview.php', 'OptionsOverviewTabContainer') ;
        $tab_content[] = new TabWidgetContent('Swim Team',
            $tab_index++, 'options.php', 'SwimTeamOptionsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Registration',
            $tab_index++, 'registrationoptions.php', 'SwimTeamOptionsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Team Profile',
            $tab_index++, 'teamprofileoptions.php',
            'TeamProfileOptionsTabContainer') ;
        $tab_content[] = new TabWidgetContent('User Profile',
            $tab_index++, 'userprofileoptions.php',
            'SwimTeamUserProfileOptionsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Swimmer Profile',
            $tab_index++, 'swimmerprofileoptions.php',
            'SwimTeamSwimmerProfileOptionsTabContainer') ;
        $tab_content[] = new TabWidgetContent('SDIF Profile',
            $tab_index++, 'sdifprofile.php',
            'SDIFProfileTabContainer') ;
        $tab_content[] = new TabWidgetContent('Jobs',
            $tab_index++, 'options_jobs.php',
            'JobOptionsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Miscellaneous',
            $tab_index++, 'options_miscellaneous.php',
            'SwimTeamMiscellaneousOptionsTabContainer') ;

        $tabs = new TabControlWidget() ;

        //  Clean up the URL or the tab=N argument
        //  may be appended to the URI mutliple times.

        $url = add_query_arg(array('tab' => false),
            admin_url($pagenow .'?'.$_SERVER['QUERY_STRING'])) ;

        //  Construct the tabs

        foreach ($tab_content as $tc)
        {
            $tabs->add_tab(html_a(add_query_arg('tab', $tc->getIndex(),
                $url), $tc->getLabel()), ($activetab == $tc->getIndex()));
        }

        $div->add($tabs);

        //  Load the tab content

        foreach ($tab_content as $tc)
        {
            if ($tc->getIndex() == $activetab)
            {
                require_once(plugin_dir_path(__FILE__) .  $tc->getIncludeFile()) ;
                $class = $tc->getClassName() ;
                $div->add(new $class()) ;
                break ;
            }
        }

        $this->add($div) ;
    }
}

//  Construct the Container

$c = new OptionsTabContainer() ;
print $c->render();
?>
