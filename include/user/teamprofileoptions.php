<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Team Profile page content.
 *
 * $Id: teamprofileoptions.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'class/team.class.php') ;
require_once(WPST_PATH . 'class/team.forms.class.php') ;
require_once(WPST_PATH . 'class/container.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the TeamProfileTab
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class TeamProfileOptionsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions Content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Set the Options for the Swim Team:  Team Name, Club or Pool Name,
            address, phone and e-mail contact information, and the constraints of the pool.')) ;

        return $div ;
    }

    /**
     * Construct the content of the TeamProfile Tab Container
     *
     */
    function TeamProfileOptionsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style('clear: both;') ;
        //$div->add(html_h2('Swim Team Profile')) ;
        //$div->add(html_br()) ;

        //  Start building the form

        $form = new WpSwimTeamTeamProfileForm('Swim Team Profile',
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(true) ;
        $div->add(html_br(), $fp) ;

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader('Team Profile Options') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
