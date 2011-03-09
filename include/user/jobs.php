<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Team Jobs admin page content.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */


require_once("jobs.class.php") ;
require_once("jobs.forms.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the swim clubs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class SwimTeamJobsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return TABLEtag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_USERS_PROFILE_USER)),
            __("Display detailed information about a particular job.")) ;
        $table->add_row(html_b(__(WPST_ACTION_SIGN_UP)),
            __("Assign a user to a specific job assigment.")) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $where_clause = sprintf("jobtype=\"%s\" AND jobstatus=\"%s\"",
            WPST_JOB_TYPE_VOLUNTEER, WPST_ACTIVE) ;

        $gdl = new SwimTeamJobsGUIDataList("Swim Team Jobs",
            "100%", "jobstatus, jobposition", true, WPST_JOBS_DEFAULT_COLUMNS,
            WPST_JOBS_DEFAULT_TABLES, $where_clause) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(false) ;

        return $gdl ;
    }

    /**
     * Construct the content of the Jobs Tab Container
     */
    function SwimTeamJobsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style("clear: both;") ;
        $div->add(html_h3("Swim Team Jobs")) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;

        //  The jobid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . "radio", $scriptargs))
            $jobid = $scriptargs[WPST_DB_PREFIX . "radio"][0] ;
        else if (array_key_exists("_jobid", $scriptargs))
            $jobid = $scriptargs["_jobid"] ;
        else if (array_key_exists("jobid", $scriptargs))
            $jobid = $scriptargs["jobid"] ;
        else
            $jobid = null ;

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of swim clubs or process an action.
        //  If there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (array_key_exists("_action", $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists("_form_action", $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        //  If one of the GDL controls was selected, then
        //  the action maybe confusing the processor.  Flush
        //  any action that doesn't make sense.

        if ($action == WPST_ACTION_SELECT_ACTION) $action = null ;

        if (empty($scriptargs) || is_null($action))
        {
            $gdl = $this->__buildGDL() ;

            $div->add($gdl, html_br(2)) ;

            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Jobs Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_PROFILE:
                    $c = container() ;
                    $profile = new SwimTeamJobProfileInfoTable("Swim Team Job Profile", "500px") ;
                    $profile->setJobId($jobid) ;
                    $profile->constructSwimTeamJobProfile() ;
                    $c->add($profile) ;

                    break ;

                case WPST_ACTION_ADD:
                    $form = new WpSwimTeamJobAddForm("Add Swim Team Job",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamJobUpdateForm("Update Swim Team Job",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_DELETE:
                    $form = new WpSwimTeamJobDeleteForm("Delete Swim Team Job",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_ALLOCATE:
                    $form = new WpSwimTeamJobsAllocateForm("Allocate Swim Team Jobs",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Allocate Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_REALLOCATE:
                    $form = new WpSwimTeamJobsReallocateForm("Reallocate Swim Team Jobs",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Reallocate Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_DEALLOCATE:
                    $form = new WpSwimTeamJobsDeallocateForm("Deallocate Swim Team Jobs",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Deallocate Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_DELETE:
                    $form = new WpSwimTeamJobDeleteForm("Delete Swim Team Job",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
                    break ;

                case WPST_ACTION_SIGN_UP:
                    $form = new WpSwimTeamJobAssignForm("Job Sign Up",
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setJobId($jobid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Sign Up Job Instructions') ;
                    $this->setFormInstructionsContent($form->get_form_help()) ;
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
                //  some statistics about the action that was performed.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $gdl = $this->__buildGDL() ;

                    $div->add($gdl, html_br(2)) ;

	                $div->add(html_br(2), $form->form_success()) ;

                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Jobs Action Summary') ;
                }
                else
                {
	                $div->add(html_br(), $fp) ;
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

/**
 * Class definition of the swim clubs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class SwimTeamAdminJobsTabContainer extends SwimTeamJobsTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        //  Leverage the parent class method

        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __("Add a job or volunteer position.  Use this action to define
            a new job or position so it can be used to help run a swim team.")) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __("Update a job or volunteer position.  Use this
            action to update the information for a job or position.  Set
            a position to <b>Inactive</b> if it is no longer needed.")) ;
        $table->add_row(html_b(__(WPST_ACTION_DELETE)),
            __("Delete a job or volunteer position.  Use this
            action to delete a position from the system.  Deleting a
            job will also delete any job allocations or job assignments
            effectively erasing it from the system.  Set a position to
            Inactive if it is no longer needed.")) ;
        $table->add_row(html_b(__(WPST_ACTION_ALLOCATE)),
            __("Allocate a job to a season or one or more swim meets.  Jobs
            must be allocated before users can be assigned to them.  The
            process of allocating a job determines how many of a specific
            job is required for a season or a meet.")) ;
        $table->add_row(html_b(__(WPST_ACTION_REALLOCATE)),
            __("Change how a job is allocated against a season or meet.  If
            reallocating a job decreases the quantity, existing assigments
            may be deleted depending on whether or not they have been filled
            or not.  When increasing the quantity, new job assigments are
            added to the existing assignments.")) ;
        $table->add_row(html_b(__(WPST_ACTION_DEALLOCATE)),
            __("Remove all allocations of a job from a season or meet.  When
            deallocating a job, any existing job assignments are also removed.")) ;

        return $table ;
    }

    /**
     * Build instructions
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add(html_p('Before a Job can be assigned to a person, the job must be defined
            and allocated to one or more swim meets.  Because some jobs may have more than
            one person assigned to it, creating a new job is a multi step process.')) ;

        $div->add(html_h4('Step 1')) ;
        $div->add(html_p('Define the Job.  This process defines the job title, detailed
            description, and specifies if the job applies to Home meets, Away meets or both
            Home and Away meets.  Select one or more specific swim meets for the Job or
            Select All to allocate the job to all swim meets.')) ;

        $div->add(html_h4('Step 2')) ;
        $div->add(html_p('Allocate the Job.  This process determines which swim meets for
            which the job is required and how many people are required to fill the job.  If
            the number of people required or Meet location changes, the quantity can be
            adjusted by ReAllocating the Job.')) ;

        $div->add(html_h4('Step 3')) ;
        $div->add(html_p('Assign the Job.  This process assigns a specific person to a
            particular job for a specific swim meet.')) ;

        return $div ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamJobsAdminGUIDataList("Swim Team Jobs",
            "100%", "jobstatus, jobposition", false) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }
}
?>
