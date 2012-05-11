<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Age Groups admin page content.
 *
 * $Id: userprofileoptions.php 856 2012-05-11 03:04:50Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision: 856 $
 * @lastmodified $Date: 2012-05-10 23:04:50 -0400 (Thu, 10 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('options.class.php') ;
require_once('options.forms.class.php') ;
require_once('container.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the OptionsTab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimTeamUserProfileOptionsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions Content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Set the Options for the End Users.  Set labels for phone contacts
            and set up the Optional Fields.  The number of Optional Fields is controled by
            the setting on the Registrations Options tab.  The Optional Fields can be simple
            text entry fields or specific fields for clothing sizes, yes-no questions, email
            address or URL.  All Optional Fields can be designated as Optional or Required.
            Fields which are designated as &#039;Admin&#039; are not shown to the end user
            but only visible to Administrators.  These sort of fields are useful for tracking
            things such as &#039;Fees Paid&#039;.')) ;

        return $div ;
    }

    /**
     * Construct the content of the Options Tab Container
     *
     */
    function SwimTeamUserProfileOptionsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style('clear: both;') ;

        //  Start building the form

        $form = new WpSwimTeamUserProfileOptionsForm('User Profile Options',
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(true) ;
        $div->add(html_br(), $fp) ;

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader('User Profile Options') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
