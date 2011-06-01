<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Reports admin page content.
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

require_once("widgets.class.php") ;

/**
 * Class definition of the Reports Page
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class ReportsTabContainer extends Container
{
    /**
     * Construct the content of the Reports Tab Container
     */
    function ReportsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
        //
        //  No matter what the container content is, it must be
        //  enclosed in a DIV with a class of "wrap" to fit in
        //  the Wordpress admin page structure.
 
        $div = html_div("wrap") ;
        //$div->set_style("background: #ffffff; margin: 0px 10px;") ;
 
        //  If the wp-SwimTeam database version isn't current,
        //  nag the user as operation of the plugin in unpredicatable.

        $wpst_db_version = get_option(WPST_DB_OPTION) ;

        if ($wpst_db_version != WPST_DB_VERSION)
        {
            $table = html_table(null, null, "10") ;
            $table->add_row(html_b("Warning:"), sprintf("The wp-SwimTeam
                plugin database version is incorrect (v%s vs. v%s).  Please
                deactivate and reactivate the plugin to ensure proper
                operation and database integrity.",
                $wpst_db_version, WPST_DB_VERSION)) ;
            $div->add(html_div("updated fade", $table)) ;
        }        $div->add(html_h2("Swim Team Reports")) ;

        //  Default to Tab #1 if no tab passed as part of URI

        $activetab = (array_key_exists('tab', $_GET)) ? $_GET['tab'] : "1" ;

        //  Build up the tab content
 
        $tab_index = 1 ;
        $tab_content = array() ;

        $tab_content[] = new TabWidgetContent("Overview",
            $tab_index++, "overview.php", "ReportsOverviewTabContainer") ;
        $tab_content[] = new TabWidgetContent("Swim Meets",
            $tab_index++, "report_swimmeets.php", "ReportSwimMeetsTabContainer") ;
        $tab_content[] = new TabWidgetContent("Job Assignments",
            $tab_index++, "report_jobassignments.php", "ReportJobAssignmentsTabContainer") ;

        //  Users can't access all of the reports ...

        if (user_can(get_current_user_id(), 'publish_posts'))
        {
            $tab_content[] = new TabWidgetContent("Swimmers",
                $tab_index++, "report_swimmers.php", "ReportGeneratorTabContainer") ;
            $tab_content[] = new TabWidgetContent("Users",
                $tab_index++, "report_users.php", "ReportUsersTabContainer") ;
            $tab_content[] = new TabWidgetContent("Job Commitments",
                $tab_index++, "report_jobcommitments.php", "ReportJobCommitmentsTabContainer") ;
        }

        $tabs = new TabControlWidget() ;

        $url = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] ;

        //  Clean up the URL or the tab=N argument
        //  will continue to be appended indefinitely.

        $url = preg_replace('/&?tab=[1-9][0-9]*/i', '', $url) ;

        //  Add the tabs to the widget

        foreach ($tab_content as $tc)
        {
            $tabs->add_tab(html_a(sprintf("%s&tab=%d",
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
                    "/include/user/" . $tc->getIncludeFile()) ;
                $class = $tc->getClassName() ;
                $div->add(new $class()) ;
                break ;
            }
        }

        $this->add($div) ;
    }
}

//  Construct the Container

$c = new ReportsTabContainer() ;
print $c->render();
?>
