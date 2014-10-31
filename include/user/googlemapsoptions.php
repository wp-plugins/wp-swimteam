<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Age Groups admin page content.
 *
 * $Id: googlemapsoptions.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
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

require_once(WPST_PATH . 'class/options.class.php') ;
require_once(WPST_PATH . 'class/options.forms.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the OptionsTab
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see Container
 */
class SwimTeamGoogleMapsOptionsTabContainer extends Container
{
    /**
     * Construct the content of the Options Tab Container
     */
    function SwimTeamGoogleMapsOptionsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style('clear: both;') ;

        //  Start building the form

        $form = new WpSwimTeamGoogleMapsOptionsForm('Google Maps Options',
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

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

        $this->add($div, html_br()) ;
    }
}
?>
