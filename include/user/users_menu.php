<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Users admin page content.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once('widgets.class.php') ;

/**
 * Class definition of the Users Page
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class UsersMenuTabContainer extends Container
{
    /**
     * Construct the content of the Users Tab Container
     */
    function UsersMenuTabContainer()
    {
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

        //  If the wp-SwimTeam database version isn't current,
        //  nag the user as operation of the plugin in unpredicatable.

        $wpst_db_version = get_option(WPST_DB_OPTION) ;

        if ($wpst_db_version != WPST_DB_VERSION)
        {
            $table = html_table(null, '0', '10') ;
            $table->add_row(html_b('Warning:'), sprintf('The wp-SwimTeam
                plugin database version is incorrect (v%s vs. v%s).  Please
                notify the web site administrator.',
                $wpst_db_version, WPST_DB_VERSION)) ;
            $div->add(html_div('updated fade', $table)) ;
        }        $div->add(html_h2('Swim Team Users')) ;

        //  Default to Tab #1 if no tab passed as part of URI

        $activetab = (array_key_exists('tab', $_GET)) ? $_GET['tab'] : '1' ;

        //  Build up the tab content
 
        $tab_index = 1 ;
        $tab_content = array() ;

        $tab_content[] = new TabWidgetContent('Overview',
            $tab_index++, 'overview.php', 'OverviewTabContainer') ;
        $tab_content[] = new TabWidgetContent('My Profile',
            $tab_index++, 'profile.php', 'UserProfileTabContainer') ;
        $tab_content[] = new TabWidgetContent('My Swimmers',
            $tab_index++, 'swimmers.php', 'SwimmersTabContainer') ;
        $tab_content[] = new TabWidgetContent('My Jobs',
            $tab_index++, 'myjobs.php', 'MyJobsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Team Roster',
            $tab_index++, 'roster.php', 'RosterTabContainer') ;
        $tab_content[] = new TabWidgetContent('Swim Meets',
            $tab_index++, 'swimmeets.php', 'SwimMeetsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Jobs',
            $tab_index++, 'jobs.php', 'SwimTeamJobsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Swim Clubs',
            $tab_index++, 'swimclubs.php', 'SwimClubsTabContainer') ;
        $tab_content[] = new TabWidgetContent('Users',
            $tab_index++, 'users.php', 'UsersTabContainer') ;

        $tabs = new TabControlWidget() ;

        $url = SwimTeamUtils::GetPageURI() ;

        //  Clean up the URL or the tab=N argument
        //  will continue to be appended indefinitely.

        $url = preg_replace('/&?tab=[1-9][0-9]*/i', '', $url) ;

        //  Add the tabs to the widget

        foreach ($tab_content as $tc)
        {
            $tabs->add_tab(html_a(sprintf('%s&tab=%d',
                $url, $tc->getIndex()), $tc->getLabel()),
                ($activetab == $tc->getIndex()));
        }

        $div->add($tabs);

        //  Load the tab content

        foreach ($tab_content as $tc)
        {
            if ($tc->getIndex() == $activetab)
            {
                require_once(WPST_PATH .
                    '/include/user/' . $tc->getIncludeFile()) ;
                $class = $tc->getClassName() ;
                $div->add(new $class()) ;
                break ;
            }
        }

       $this->add($div) ;
    }
}

//  Construct the Container


$c = new UsersMenuTabContainer() ;
print $c->render();
?>
