<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Overview admin page content.
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

require_once("seasons.class.php") ;
require_once("agegroups.class.php") ;
require_once("swimmeets.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the overview tab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class OverviewTabContainer extends SwimTeamTabContainer
{
    var $__ch_instructions_header = 'Swim Team Overview' ;

    /**
     * Build Instructions content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add(html_p('The Swim Team Overview provides a summary of relevant
            Swim Team information at a glance.  The current season and roster are
            summarized for easy perusal.')) ;

        return $div ;
    }
    /**
     * Construct the content of the Overview Tab Container
     */
    function OverviewTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.

        $div = html_div() ;
        $div->add(html_br(), html_h3("Swim Team Overview")) ;

        $season = new SwimTeamSeason() ;

        if ($season->loadActiveSeason())
            $div->add(html_h4(sprintf("Active Season is:  %s",
                $season->getSeasonLabel()))) ;
        else
            $div->add(html_h4("No Season Active.")) ;

        //  Age group summary

        $agegroups = new SwimTeamAgeGroupInfoTable("Active Swimmers", "300px") ;
        $agegroups->constructAgeGroupInfoTable() ;
        $agdiv = html_div() ;
        $agdiv->add($agegroups) ;
        //$agdiv->set_tag_attribute("style", "float: left; padding-left: 20px;") ;

        //  Meet summary

        $meetsummary = new SwimMeetScheduleInfoTable("Meet Schedule","500px") ;
        $meetsummary->constructSwimMeetScheduleInfoTable() ;
        $msdiv = html_div() ;
        $msdiv->add($meetsummary) ;
        //$msdiv->set_tag_attribute("style", "float: right; padding-right: 20px;") ;

        $br = html_br() ;
        $br->set_tag_attribute("clear", "both") ;
        $div->add($msdiv, $br, $agdiv, $br) ;

        $div->add(html_br(2), html_h6("wp-SwimTeam plugin v" .
            WPST_VERSION, html_br(), "wp-SwimTeam database v" .
            WPST_DB_VERSION)) ;

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader($this->__ch_instructions_header) ;
        $this->add($this->buildContextualHelp()) ;
    }
}

/**
 * Class definition of the Management overview tab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see OverviewTabContainer
 */
class ManagementOverviewTabContainer extends OverviewTabContainer
{
    var $__ch_instructions_header = 'Swim Team Management Overview' ;

    /**
     * Build Instructions content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add(html_p('The Management section of the Swim Team module allows the
            Swim Team Administrator to set up and manage all aspects of running a
            Swim Team season.  Each tab on the Management page encapsualtes a specific
            area of functionaloty supported by the Swim Team module.')) ;

        return $div ;
    }
}

/**
 * Class definition of the overview tab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see OverviewTabContainer
 */
class ReportsOverviewTabContainer extends OverviewTabContainer
{
    var $__ch_instructions_header = 'Swim Team Reports Overview' ;

    /**
     * Build Instructions content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add(html_p('The Reports section of the Swim Team module allows the
            Swim Team Administrator to generate a number of different reports to
            support the different needs that may arise during the course of a swim
            season.  Reports are configurable to include or exclude a number of
            fields and all reports can be produced as standard web pages or exported
            in CSV format.  CSV is useful for importing the data into tools such as
            Excel')) ;

        return $div ;
    }
}

/**
 * Class definition of the Management overview tab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see OverviewTabContainer
 */
class OptionsOverviewTabContainer extends OverviewTabContainer
{
    var $__ch_instructions_header = 'Swim Team Options Overview' ;

    /**
     * Build Instructions content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add(html_p('The Options section of the Swim Team module allows the
            Swim Team Administrator to set up and manage all of the settings for
            the swim team, swimmers, parents, and a few other miscellaneous options.
            In particular, the definition of user and swimmer optional fields is
            managed from the Options section.')) ;

        return $div ;
    }
}
?>
