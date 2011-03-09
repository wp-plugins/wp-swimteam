<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Age Groups admin page content.
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

require_once("options.class.php") ;
require_once("options.forms.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the OptionsTab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SwimTeamMiscellaneousOptionsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions Content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Set the Miscellaneous Options.  The Swim Team module can
            optionally redirect the landing page the end users see when they login
            to the web site.  The default WordPress Dashboard can be overwhelming to
            most users, be setting a redirect, they will view information relevant
            to being a member of a swim team web site.  The Swim Team module supports
            the displaying of Google Maps in posts but in order for the maps to be
            shown correctly, a Google Maps API key is required.')) ;

        return $div ;
    }

    /**
     * Construct the content of the Options Tab Container
     *
     */
    function SwimTeamMiscellaneousOptionsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style("clear: both;") ;

        //  Start building the form

        $form = new WpSwimTeamMiscellaneousOptionsForm("Miscellaneous Options",
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']) ;

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(true) ;

        //  If the Form Processor was succesful, let the user know

        if ($fp->is_action_successful())
        {
	        $div->add(html_br(), $fp) ;
        }
        else
        {
	        $div->add(html_br(), $fp) ;
        }

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader('Miscellaneous Profile Options') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
