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

require_once("agegroups.class.php") ;
require_once("agegroups.forms.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the jobs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class AgeGroupsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_PROFILE)),
            __("Display detailed information about a particular age group.")) ;
        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __("Add a new age group.  Use this action to define a new age
            group in the system.")) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __("Update an age group.  Use this action to update the details
            of an age group in the system.")) ;
        $table->add_row(html_b(__(WPST_ACTION_DELETE)),
            __("Delete an age group.  Use this action to delete an age group
            in the system.")) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamAgeGroupsAdminGUIDataList("Swim Team Age Groups",
            "100%", "minage") ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Construct the content of the AgeGroups Tab Container
     */
    function AgeGroupsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.

        $div = html_div() ;
        $div->set_style("clear: both;") ;
        $div->add(html_h3("Swim Team Age Groups")) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //  The agegroupid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . "radio", $scriptargs))
            $agegroupid = $scriptargs[WPST_DB_PREFIX . "radio"][0] ;
        else if (array_key_exists("agegroupid", $scriptargs))
            $agegroupid = $scriptargs["agegroupid"] ;
        else
            $agegroupid = null ;

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of age groups or process an action.
        //  If there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (array_key_exists("_action", $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists("_form_action", $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        if (empty($scriptargs) || is_null($action))
        {
            $gdl = $this->__buildGDL() ;

            $div->add($gdl) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Age Groups Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_AGT_ADD_AGE_GROUP:
                    $form = new WpSwimTeamAgeGroupAddForm("Add Swim Team Age Group",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Age Group') ;
                    break ;

                case WPST_AGT_UPDATE_AGE_GROUP:
                    $form = new WpSwimTeamAgeGroupUpdateForm("Update Swim Team Age Group",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($agegroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Age Group') ;
                    break ;

                case WPST_AGT_DELETE_AGE_GROUP:
                    $form = new WpSwimTeamAgeGroupDeleteForm("Delete Swim Team Age Group",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($agegroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Age Group') ;
                    break ;

                default:
                    $div->add(html_h4(sprintf("Unsupported action \"%s\" requested.", $action))) ;

                    break ;
            }

            //  Not all actions are form based ...

            if (isset($form))
            {
                //  Create the form processor

                $fp = new FormProcessor($form) ;
                $fp->set_form_action($_SERVER['PHP_SELF'] .
                    "?" . $_SERVER['QUERY_STRING']) ;
                

                //  Display the form again even if processing was successful.

                $fp->set_render_form_after_success(false) ;

                //  If the Form Processor was succesful, display
                //  some statistics about the uploaded file.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $gdl = $this->__buildGDL() ;

                    $div->add($gdl) ;

	                $div->add(html_br(2), $form->form_success()) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Age Groups Action Summary') ;
                }
                else
                {
	                $div->add(html_br(2), $fp) ;
                }
            }
            else if (isset($c))
            {
                $div->add(html_br(2), $c) ;
                $div->add(SwimTeamGUIBackHomeButtons::getButtons()) ;
            }
            else
            {
                $div->add(html_br(2), html_h4("No content to display.")) ;
            }

        }

        $this->add($div) ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
