<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Registration Options page content.
 *
 * $Id: registrationoptions.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage options
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'class/options.class.php') ;
require_once(WPST_PATH . 'class/options.forms.class.php') ;
require_once(WPST_PATH . 'class/container.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the OptionsTab
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimTeamOptionsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions Content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Set the Options which determine how registration is presented
            to end users.'), html_ul(html_li(' Registration can be Open or Closed.  When closed, the
            Registration option is not presented to users.  Auto-Register will automatically
            register new swimmers for the active season when enabled.'),  html_li('There are
            settings for URLs to Terms of Use and Registration Fee Policies, how e-mail is
            formatted and the number of optional fields for Users and Swimmers.'),
            html_li('Optional fields can be used to collect information that is of interest to
            the team that isn&#039;t part of the default user or swimmer profile.'), html_li('The
            registration prefix label can be used to "prefix" the word', html_b('registration'),
            'in the confirmation e-mail which is sent out.  This is useful when the registration
            system is used for a pre-registration process.'))) ;

        return $div ;
    }

    /**
     * Construct the content of the Options Tab Container
     *
     */
    function SwimTeamOptionsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.

        $this->setShowInstructions() ;
        $this->setInstructionsHeader('Swim Team Options') ;
        $this->add($this->buildContextualHelp()) ;

        $div = html_div() ;
        $div->set_style('clear: both;') ;

        //  Start building the form

        $form = new WpSwimTeamRegistrationOptionsForm('Registration Options',
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(true) ;
        $div->add(html_br(), $fp) ;

        $this->add($div) ;
        $this->setShowActionSummary(false) ;
        $this->setInstructionsHeader('Registration Options') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
