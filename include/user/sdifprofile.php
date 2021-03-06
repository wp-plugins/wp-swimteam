<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * SDIF Profile page content.
 *
 * $Id: sdifprofile.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
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

require_once(WPST_PATH . 'class/sdif.class.php') ;
require_once(WPST_PATH . 'class/sdif.forms.class.php') ;
require_once(WPST_PATH . 'class/container.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the SDIFProfileTab
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SDIFProfileTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions Content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Set the SDIF Options for the Swim Team.  SDIF, Swim Data Interchange
            Format, is the defacto standard for exchanging data between swim teams and swim
            organizations.  The SDIF standard, while old, remains the only viable solution for
            exchanging swim data.  The',  html_a('http://www.usaswimming.org/_Rainbow/Documents/521e8fae-ce81-4c73-a51a-3653a1304a30/Standard%20Data%20Interchange%20Format.doc', 'SDIF Specification v3.0'),
            'was created by USA Swimming in 1998.  The SDIF Options are used in the generation of
            SDIF files.  The SDIF specification document on the USA Swimming web site is very
            poorly formatted.  A much better version of the specification can be found on the ',
            html_a('http://www.winswim.com/ftp/Standard%20Data%20Interchange%20Format.pdf',
            'WinSwim web site'), '.')) ;

        return $div ;
    }

    /**
     * Construct the content of the SDIFProfile Tab Container
     *
     */
    function SDIFProfileTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style('clear: both;') ;
        //$div->add(html_h2('Swim Team SDIF Profile')) ;

        //  Start building the form

        $form = new WpSwimTeamSDIFProfileForm('Swim SDIF Team Profile',
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(true) ;
        $div->add(html_br(), $fp) ;

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader('SDIF Profile Options') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
