<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * MyJobs admin page content.
 *
 * $Id$
 *
 * (c) 2011 by Mike Walsh
 *
 * @author Mike Walsh <mike@walshcrew.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once('jobs.class.php') ;
require_once('seasons.class.php') ;
require_once('textmap.class.php') ;
require_once('container.class.php') ;
require_once('container.class.php') ;

/**
 * Class definition of the overview tab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class MyJobsTabContainer extends SwimTeamTabContainer
{
    var $__ch_instructions_header = 'My Swim Team Jobs' ;

    /**
     * Build Instructions content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add(html_p('List the jobs the user has signed up for.')) ;

        return $div ;
    }
    /**
     * Construct the content of the MyJobs Tab Container
     */
    function MyJobsTabContainer()
    {
        global $current_user ;

        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.

        $div = html_div() ;
        $div->add(html_br(), html_h3('My Swim Team Jobs')) ;

        get_currentuserinfo() ;

        $season = new SwimTeamSeason() ;
        $active = $season->getActiveSeasonId() ;
        $seasonlabel = SwimTeamTextMap::__MapSeasonIdToText($active) ;

        $jobs = array() ;
        $jobs[$active] = new SwimTeamUserJobsInfoTable('My Jobs - ' . $seasonlabel['label'], '100%') ;

        $myjobs = &$jobs[$active] ;

        $myjobs->setSeasonId($active) ;
        $myjobs->setUserId($current_user->ID) ;
        $myjobs->constructSwimTeamUserJobsInfoTable() ;

        //  Report credits versus team requirements
        $required = get_option(WPST_OPTION_JOB_CREDITS_REQUIRED) ;
        if ($required === false) $required = 0 ;

        $div->add($myjobs) ;

        //  Summarize credits versus requirements
 
        $div->add(html_h5(sprintf('%s credits assigned / %s credits required.',
            $myjobs->getCredits(), $required))) ;

        if ($myjobs->getCredits() < $required)
        {
            $notice = html_div('error fade',
               html_h4(sprintf('Notice:  You have not met your team Jobs requirement of %s credits.', $required))) ;
            $div->add($notice) ;
        }

        //  Summarize prior seasons if they exist

        $seasonIds = $season->getAllSeasonIds() ;

        $div->add(html_h3('Prior Season Jobs')) ;

        foreach ($seasonIds as $seasonId)
        {
            if ((int)$seasonId['seasonid'] != (int)$active)
            {
                $seasonlabel = SwimTeamTextMap::__MapSeasonIdToText($seasonId['seasonid']) ;
                $jobs[$seasonId['seasonid']] =
                    new SwimTeamUserJobsInfoTable('My Jobs - ' . $seasonlabel['label'], '100%') ;
                $myjobs = &$jobs[$seasonId['seasonid']] ;
                $myjobs->setUserId($current_user->ID) ;
                $myjobs->setSeasonId($seasonId['seasonid']) ;
                $myjobs->constructSwimTeamUserJobsInfoTable() ;
                $div->add($myjobs, html_br()) ;
            }
        }

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader($this->__ch_instructions_header) ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
