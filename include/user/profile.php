<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * User Profile page content.
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

require_once("users.class.php") ;
require_once("users.forms.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the UserProfileTab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class UserProfileTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions Content
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Set the Options for the End User:  Name, address, phone
            and e-mail contact information, and the user&#039;s privacy setting.')) ;

        return $div ;
    }

    /**
     * Construct the content of the UserProfile Tab Container
     *
     */
    function UserProfileTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style("clear: both;") ;
        $div->add(html_h3("Swim Team User Profile")) ;

        //  Start building the form

        $u = wp_get_current_user() ;

        $form = new WpSwimTeamUserProfileForm("Swim Team User Profile",
            $_SERVER['HTTP_REFERER'], 600) ;

        $form->setId($u->ID) ;
        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action($_SERVER['PHP_SELF'] .
            "?" . $_SERVER['QUERY_STRING']) ;

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(false) ;

        //  If the Form Processor was succesful, let the user know

        if ($fp->is_action_successful())
        {
            if (!is_null($form->getId()))
            {
                $profile = new SwimTeamUserProfileInfoTable("Your Profile", "500px") ;
                $profile->setId($form->getId()) ;
                $profile->buildProfile() ;

	            $div->add($profile, $fp, html_br()) ;
            }
            else
            {
                $div->add("No profile selected.") ;
            }
        }
        else
        {
	        $div->add($fp, html_br()) ;
        }

        $this->add($div) ;
        $this->setShowActionSummary(false) ;
        $this->setInstructionsHeader('My Profile Options') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
