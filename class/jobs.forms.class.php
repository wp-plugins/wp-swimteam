<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: jobs.forms.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Jobs
 * @version $Revision: 1065 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 *
 */

require_once(WPST_PATH . 'class/jobs.class.php') ;
require_once(WPST_PATH . 'class/seasons.class.php') ;
require_once(WPST_PATH . 'class/swimmeets.class.php') ;
require_once(WPST_PATH . 'class/forms.class.php') ;
require_once(WPST_PATH . 'class/textmap.class.php') ;

/**
 * Construct the Add Job form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamJobAddForm extends WpSwimTeamForm
{
    /**
     * id property - used to track the job record
     */
    var $__jobid ;

    /**
     * Set the Job Id property
     */
    function setJobId($id)
    {
        $this->__jobid = $id ;
    }

    /**
     * Get the Job Id property
     */
    function getJobId()
    {
        return $this->__jobid ;
    }

    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Define a Swim Team Job.  Settng up a job determines how it can then
            be allocated to a season or one or more swim meets.  A job consists of the following:')) ;
        $ul = html_ul() ;
        $ul->add(html_p(html_b('Position:'), 'The name the job is commonly know by (e.g. Annoucer).')) ;
        $ul->add(html_p(html_b('Description:'), 'Detailed description of the job responsibilities.')) ;
        $ul->add(html_p(html_b('Notes:'), 'Brief notes which can be included on Job Reports.')) ;
        $ul->add(html_p(html_b('Duration:'), 'Rough duration for the job.  Some jobs may be season long,
            other jobs may be for a portion of a swim meet.')) ;
        $ul->add(html_p(html_b('Location:'), 'Location where the job is needed.  Some jobs are only needed
            for home or away meets, other jobs are need for every meet.  Jobs that that location
                independed (e.g. Banquet Coordinator) should be allocated to a season.')) ;
        $ul->add(html_p(html_b('Type:'), 'Identify the job is volunteer position or a paid position.')) ;
        $ul->add(html_p(html_b('Credits:'), 'Determine how many credits a job is worth.  The value of a
            Credit will vary from team to team and depends how each team values or raters the the
            job.  Credits should be whole numbers, the easiest and shortest duration jobs should
            be assigned the least number of credits.')) ;
        $ul->add(html_p(html_b('Status:'), 'Set the status of a job.  Jobs which are set inactive are not
            available to be allocated or signed up for.  A job which is no longer needed should be
            set to inactive.')) ;

        $div->add($ul) ;

        return $div ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_ADD)
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_jobid") ;
        $this->add_hidden_element("_action") ;

        //  Position Field
        $position = new FEText("Position", TRUE, "300px");
        $position->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($position);
		
        //  Description Field
        $description = new FETextArea("Description", TRUE, 4, 60, "400px");
        $description->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($description);
		
        //  Notes Field
        $notes = new FEText("Notes", FALSE, "400px");
        $notes->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($notes);
		
        //  Duration Field
        $duration = new FEListBox("Duration", false, "150px");
        $duration->set_list_data(array(
             ucwords(WPST_JOB_DURATION_FULL_MEET) => WPST_JOB_DURATION_FULL_MEET
            ,ucwords(WPST_JOB_DURATION_PARTIAL_MEET) => WPST_JOB_DURATION_PARTIAL_MEET
            ,ucwords(WPST_JOB_DURATION_FULL_SEASON) => WPST_JOB_DURATION_FULL_SEASON
            ,ucwords(WPST_JOB_DURATION_PARTIAL_SEASON) => WPST_JOB_DURATION_PARTIAL_SEASON
            ,ucwords(WPST_JOB_DURATION_EVENT) => WPST_JOB_DURATION_EVENT
        )) ;
        $duration->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($duration) ;

        //  Type Field
        $type = new FEListBox("Type", false, "150px");
        $type->set_list_data(array(
             ucwords(WPST_JOB_TYPE_VOLUNTEER) => WPST_JOB_TYPE_VOLUNTEER
            ,ucwords(WPST_JOB_TYPE_PAID) => WPST_JOB_TYPE_PAID
        )) ;
        $type->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($type) ;

        //  Location Field
        $location = new FEListBox("Location", false, "150px");
        $location->set_list_data(array(
             ucwords(WPST_HOME) => WPST_HOME
            ,ucwords(WPST_AWAY) => WPST_AWAY
            ,ucwords(WPST_BOTH) => WPST_BOTH
            ,strtoupper(WPST_NA) => WPST_NA
        )) ;
        $location->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($location) ;

        //  Credits Field
        $volunits = new FENumber("Credits", TRUE, "100px");
        $volunits->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($volunits);

        //  Status Field
        $status = new FEListBox("Status", false, "100px");
        $status->set_list_data(array(
             ucwords(WPST_ACTIVE) => WPST_ACTIVE
            ,ucwords(WPST_INACTIVE) => WPST_INACTIVE
        )) ;
        $status->set_readonly($action == WPST_ACTION_DELETE) ;
        $this->add_element($status) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields
        $this->set_hidden_element_value("_action", WPST_ACTION_ADD) ;
        $this->set_element_value("Position", "Position Title") ;
        $this->set_element_value("Description", "Detailed position description.") ;
        $this->set_element_value("Duration", WPST_JOB_DURATION_FULL_MEET) ;
        $this->set_element_value("Location", WPST_BOTH) ;
        $this->set_element_value("Type", WPST_JOB_TYPE_VOLUNTEER) ;
        $this->set_element_value("Credits", 1) ;
        $this->set_element_value("Status", WPST_ACTIVE) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $table = html_table($this->_width,0,4) ;
        $table->set_style("border: 1px solid") ;

        $table->add_row($this->element_label("Position"),
            $this->element_form("Position")) ;

        $table->add_row($this->element_label("Description"),
            $this->element_form("Description")) ;

        $table->add_row($this->element_label("Notes"),
            $this->element_form("Notes")) ;

        $table->add_row($this->element_label("Duration"),
            $this->element_form("Duration")) ;

        $table->add_row($this->element_label("Location"),
            $this->element_form("Location")) ;

        $table->add_row($this->element_label("Type"),
            $this->element_form("Type")) ;

        $table->add_row($this->element_label("Credits"),
            $this->element_form("Credits")) ;

        $table->add_row($this->element_label("Status"),
            $this->element_form("Status")) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;

        //  Need to validate several fields ...

        //  Make sure position is unique

        $job = new SwimTeamJob() ;
        $job->setJobPosition($this->get_element_value("Position")) ;

        if ($job->jobExistByPosition())
        {
            $this->add_error("Position", "Position already exists.");
            $valid = false ;
        }

        //  Make sure units is >= 0
        //
        if ((int)$this->get_element_value("Credits") < 0)
        {
            $this->add_error("Credits", "Credits must be greater than equal to 0.");
            $valid = false ;
        }
        
	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $job = new SwimTeamJob() ;
        $job->setJobPosition($this->get_element_value("Position")) ;
        $job->setJobDescription($this->get_element_value("Description")) ;
        $job->setJobNotes($this->get_element_value("Notes")) ;
        $job->setJobDuration($this->get_element_value("Duration")) ;
        $job->setJobType($this->get_element_value("Type")) ;
        $job->setJobLocation($this->get_element_value("Location")) ;
        $job->setJobCredits($this->get_element_value("Credits")) ;
        $job->setJobStatus($this->get_element_value("Status")) ;

        $success = $job->addJob() ;

        //  If successful, store the added job id in so it can be used later.

        if ($success) 
        {
            $job->setJobId($success) ;
            $this->set_action_message('Job successfully added.') ;
        }
        else if ($job->SwimTeamDBIWordPressDatabaseError())
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message('Job was not successfully added.<br/>' .
               'WordPress Database Error:  ' . $job->wpstdb->last_error) ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message('Job was not successfully added.') ;
        }

        return true ;
    }

    /**
     * Return the status message so it can be
     * displayed by the form processor.
     *
     * @return container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Update Job form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamJobUpdateForm extends WpSwimTeamJobAddForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_hidden_element_value("_jobid", $this->getJobId()) ;
        $this->set_hidden_element_value("_action", WPST_ACTION_UPDATE) ;

        $job = new SwimTeamJob() ;
        $job->loadJobByJobId($this->getJobId()) ;

        //  Initialize the form fields
        $this->set_element_value("Position", $job->getJobPosition()) ;
        $this->set_element_value("Description", $job->getJobDescription()) ;
        $this->set_element_value("Notes", $job->getJobNotes()) ;
        $this->set_element_value("Duration", $job->getJobDuration()) ;
        $this->set_element_value("Type", $job->getJobType()) ;
        $this->set_element_value("Location", $job->getJobLocation()) ;
        $this->set_element_value("Credits", $job->getJobCredits()) ;
        $this->set_element_value("Status", $job->getJobStatus()) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;
        $jobid = $this->get_hidden_element_value("_jobid") ;

        //  Need to validate several fields ...

        //  Make sure position is unique

        $job = new SwimTeamJob() ;
        $job->setJobPosition($this->get_element_value("Position")) ;

        //  Don't want to duplicate positions but also don't
        //  want an update to fail because a position was updated
        //  but the position field didn't change.

        if (($job->jobExistByPosition()) && (!$job->jobExistByPosition($jobid)))
        {
            $this->add_error("Position", "Position already exists.");
            $valid = false ;
        }

        //  Make sure credits is >= 0
        if ((int)$this->get_element_value("Credits") < 0)
        {
            $this->add_error("Credits", "Credits must greater than or equal to 0.");
            $valid = false ;
        }
        
	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $job = new SwimTeamJob() ;
        $job->setJobId($this->get_hidden_element_value("_jobid")) ;
        $job->setJobPosition($this->get_element_value("Position")) ;
        $job->setJobDescription($this->get_element_value("Description")) ;
        $job->setJobNotes($this->get_element_value("Notes")) ;
        $job->setJobDuration($this->get_element_value("Duration")) ;
        $job->setJobType($this->get_element_value("Type")) ;
        $job->setJobLocation($this->get_element_value("Location")) ;
        $job->setJobCredits($this->get_element_value("Credits")) ;
        $job->setJobStatus($this->get_element_value("Status")) ;

        $success = $job->updateJob() ;

        //  If successful, store the added job id in so it can be used later.

        if ($success) 
        {
            $job->setJobId($success) ;
            $this->set_action_message("Job successfully updated.") ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message("Job was not updated.") ;
        }

        return true ;
    }
}

/**
 * Construct the Add Job form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamJobDeleteForm extends WpSwimTeamJobUpdateForm
{
    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Delete a job from the system.  This operation removes the job
            and any job allocations for the position.  All record of a job and any assignments,
            is lost and cannot be recovered.  Be certain before eliminating a job - a better option
            may be to mark the job as Inactive using the Update Job action.')) ;

        return $div ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($action = WPST_ACTION_DELETE)
    {
        parent::form_init_elements($action) ;
        $this->set_hidden_element_value("_action", WPST_ACTION_DELETE) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        parent::form_init_data() ;
        $this->set_hidden_element_value("_action", WPST_ACTION_DELETE) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        //  Make sure position exists

        $job = new SwimTeamJob() ;
        $job->setJobId($this->get_hidden_element_value("_jobid")) ;

        return ($job->jobExistById()) ;

    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $job = new SwimTeamJob() ;
        $job->setJobId($this->get_hidden_element_value("_jobid")) ;

        $success = $job->deleteJob() ;

        //  If successful, store the added job id in so it can be used later.

        if ($success) 
        {
            $job->setJobId($success) ;
            $this->set_action_message("Job successfully deleted.") ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $this->set_action_message("Job was not deleted.") ;
        }

        return true ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Delete" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Delete_Cancel() ;
    }
}

/**
 * Construct the Jobs Allocation form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamJobsAllocateForm extends WpSwimTeamForm
{
    /**
     * action property - used to pass the action to the form processor
     */
    var $__action ;

    /**
     * swimmer id property - used to pass the swimmer id to the form processor
     */
    var $__jobid ;

    /**
     * div to hold the full season selection
     */
    var $__full_season_div ;

    /**
     * div to hold the partial season selection
     */
    var $__partial_season_div ;

    var $__season ;

    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Allocate the job to one or more swim meets and set the number
            of positions available.  A job defined for a Season is automatically allocated
            to all swim meets.  All meets for the season should be defined before the job
            is allocated however a reallocation will account for swim meets added to the
            season.')) ;

        return $div ;
    }

    /**
     * Set the action property
     *
     * @param int $action - action
     */
    function setAction($action)
    {
        $this->__action = $action ;
    }

    /**
     * Get the action property
     *
     * @return int - action
     */
    function getAction()
    {
        return $this->__action ;
    }

    /**
     * Set the swimmer id property
     *
     * @param int $id - swimmer id
     */
    function setJobId($id)
    {
        $this->__jobid = $id ;
    }

    /**
     * Get the swimmer id property
     *
     * @return int - swimmer id
     */
    function getJobId()
    {
        return $this->__jobid ;
    }

    /**
     * Map the job id into text for the form
     *
     * @return string - opponent text description
     */
    function __mapJobIdToText($jobid, $description = false)
    {
        //  Handle null id gracefully for non-dual meets

        if ($jobid == WPST_NULL_ID) return ucwords(WPST_NONE) ;

        $job = new SwimTeamJob() ;
        $job->loadJobByJobId($jobid) ;

        if ($description)
            $text = $job->getJobPosition() . " " . $job->getJobDescription() ;
        else
            $text = $job->getJobPosition() ;

        return $text ;
    }

    /**
     * Map the opponent swim club id into text for the GDL
     *
     * @return string - opponent text description
     */
    function __mapOpponentSwimClubIdToText($swimclubid)
    {
        //  Handle null id gracefully for non-dual meets

        if ($swimclubid == WPST_NULL_ID) return ucwords(WPST_NONE) ;

        $swimclub = new SwimClubProfile() ;
        $swimclub->loadSwimClubBySwimClubId($swimclubid) ;

        return $swimclub->getClubOrPoolName() . " " . $swimclub->getTeamName() ;
    }

    /**
     * Map the meet id into a text description
     *
     * @return string - meet text description
     */
    function __mapMeetIdToText($meetid)
    {
        //  Handle null id gracefully for non-dual meets

        if ($meetid == WPST_NULL_ID) return ucwords(WPST_NONE) ;

        $meet = new SwimMeet() ;

        $meet->loadSwimMeetByMeetId($meetid) ;
    
        if ($meet->getMeetType() == WPST_DUAL_MEET)
            $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                $meet->getOpponentSwimClubId()) ;
        else
            $opponent = $meet->getMeetDescription() ;
    
        $meetdate = date("m/d/Y", strtotime($meet->getMeetDateAsDate())) ;

        return array("date" => $meetdate, "opponent" => $opponent,
            "location" => ucwords($meet->getLocation())) ;
    }

    /**
     * Get the array of swim meet key and value pairs
     *
     * @return mixed - array of swim meet key value pairs
     */
    function _swimmeetSelections($seasonid = null, $location = WPST_BOTH)
    {
        $m = array() ;

        $season = new SwimTeamSeason() ;

        //  Season Id supplied?  If not, use the active season.

        if ($seasonid == null)
            $seasonid = $season->getActiveSeasonId() ;

        //  Find all of the meets in the season

        $meet = new SwimMeet() ;
        $meetIds = $meet->getAllMeetIds(sprintf("seasonid=\"%s\"", $seasonid)) ;

        //  Handle case where no meets have been scheduled yet

        if (!is_null($meetIds))
        {
            foreach ($meetIds as $meetId)
            {
                $meet->loadSwimMeetByMeetId($meetId["meetid"]) ;
    
                if (($location == WPST_BOTH) || ($meet->getLocation() == $location))
                {
                    if ($meet->getMeetType() == WPST_DUAL_MEET)
                        $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
                            $meet->getOpponentSwimClubId()) ;
                    else
                        $opponent = $meet->getMeetDescription() ;
    
                    $meetdate = date("D M j, Y", strtotime($meet->getMeetDateAsDate())) ;

                    $m[sprintf("%s %s (%s)", $meetdate, $opponent,
                        ucwords($meet->getLocation()))] = $meetId["meetid"] ;
                }
            }
        }

        return $m ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element("userid") ;
        $this->add_hidden_element("jobid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

        $this->__season = new FEActiveDIVRadioButtonGroup("Type",
            array(
                ucwords(WPST_SEASON) => WPST_SEASON
               ,ucwords(WPST_SWIMMEET) => WPST_SWIMMEET
            ), true) ;
        $this->__season->set_readonly(true) ;
        $this->add_element($this->__season) ;

        $job = new SwimTeamJob() ;
        $job->loadJobByJobId($this->getJobId()) ;

        $fullseason = new FECheckBoxList("Full Season",
            false, "450px", "120px");
        $fullseason->set_list_data($this->_swimmeetSelections(null, $job->getJobLocation())) ;
        $fullseason->set_disabled(true) ;
        $this->add_element($fullseason) ;

        $swimmeets = new FECheckBoxList("Swim Meets",
            false, "450px", "120px");
        $swimmeets->set_list_data($this->_swimmeetSelections(null, $job->getJobLocation())) ;
        $swimmeets->enable_checkall(true) ;
        $this->add_element($swimmeets) ;

        $quantity = new FENumber("Quantity", TRUE, "50px");
        $this->add_element($quantity);
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields

        $this->set_hidden_element_value("jobid", $this->getJobId()) ;
        $this->set_hidden_element_value("_action", WPST_ACTION_ALLOCATE) ;

        $job = new SwimTeamJob() ;
        $job->loadJobByJobId($this->getJobId()) ;

        if (($job->getJobDuration() == WPST_JOB_DURATION_FULL_SEASON)
            || ($job->getJobDuration() == WPST_JOB_DURATION_PARTIAL_SEASON))
            $this->set_element_value("Type", WPST_SEASON) ;
        else
            $this->set_element_value("Type", WPST_SWIMMEET) ;
        $this->set_element_value("Quantity", 1) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $table = html_table($this->_width, 0, 4) ;
        $table->set_style("border: 1px solid") ;

        $td = html_td() ;
        $td->set_tag_attributes(array("colspan" => "4", "align" => "center")) ;
        $td->add(div_font10bold(SwimTeamTextMap::__mapJobIdToText($this->get_hidden_element_value("jobid"), true))) ;

        $table->add_row($td) ;

        $table->add_row($this->element_label("Type"),
            $this->element_form("Type"), $this->element_label("Quantity"),
            $this->element_form("Quantity")) ;
        $table->add_row(_HTML_SPACE) ;

        //  Initialize the Full Season here instead of in 
        //  the form_init_data() method because it is a disabled
        //  widget and the values won't be preserved if the
        //  form has to be displayed again due to a validation
        //  problem.

        $this->set_element_value("Full Season", $this->_swimmeetSelections()) ;

        //  Build the Magic Divs

        $this->__full_season_div = $this->__season->build_div(0) ;
        $this->__partial_season_div = $this->__season->build_div(1) ;
        $this->__full_season_div->add($this->element_form("Full Season")) ;
        $this->__partial_season_div->add($this->element_form("Swim Meets")) ;
        $season = html_div(null, $this->__full_season_div, $this->__partial_season_div) ;

        $td = html_td() ;
        $td->set_tag_attribute("colspan", "3") ;
        $td->add($season) ;

        $table->add_row("Swim Meets", $td) ;

        $table->add_row(_HTML_SPACE) ;

        $td = html_td() ;
        $td->set_tag_attributes(array("colspan" => "4", "align" => "center")) ;
        $td->add(div_font8bold("This information replaces any existing information on a per swim meet basis.")) ;

        $table->add_row($td) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = true ;

        if ($this->get_element_value("Quantity") < 1)
        {
            $this->add_error("Quantity", "Minimum quantity is one (1) for any job.");
            $valid = false ;
        }

        $swimmeets = $this->get_element_value("Swim Meets") ;

        if (($this->get_element_value("Type") == WPST_SWIMMEET) && empty($swimmeets))
        {
            $this->add_error("Type", "You must select at least one (1) swim meet when selecting Swim Meet.");
            $valid = false ;
        }

	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action($action = WPST_ACTION_ALLOCATE)
    {
        $type = $this->get_element_value("Type") ;

        if ($type == WPST_SEASON)
            return $this->_form_action_season($action) ;
        else
            return $this->_form_action_swimmeet($action) ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function _form_action_swimmeet($action = WPST_ACTION_ALLOCATE)
    {
        $success = true ;
        $actionmsgs = array() ;

        //  Need the WordPress User Id from the global data

        global $userdata ;

        get_currentuserinfo() ;

        //  Use the available Meet Selections for a season
        //  since the element is disabled and won't be passed
        //  through the form processor.

        $meetIds = $this->get_element_value("Swim Meets") ;

        $jobid = $this->get_hidden_element_value("jobid") ;
        $action = $this->get_hidden_element_value("_action") ;

        $ja = new SwimTeamJobAllocation() ;
        $ja->setJobId($jobid) ;

        $season = new SwimTeamSeason() ;
        $swimmeet = new SwimMeet() ;

        $ja->setSeasonId($season->getActiveSeasonId()) ;
        $ja->setJobQuantity($this->get_element_value("Quantity")) ;

        $success = false ;

        //  Loop through the swim meets

        foreach ($meetIds as $meetId)
        {
            $meetdetails = SwimTeamTextMap::__mapMeetIdToText($meetId) ;

            $ja->setMeetId($meetId) ;
            $swimmeet->loadSwimMeetByMeetId($meetId) ;
            $ja->setSeasonId($swimmeet->getSeasonId()) ;

            //  Perform the desired action

            if ($action == WPST_ACTION_ALLOCATE)
            {
                $status = $ja->allocateJob() ;
                $prefix = "" ;
            }
            else if ($action == WPST_ACTION_REALLOCATE)
            {
                $status = $ja->reallocateJob() ;
                $prefix = "re" ;
            }
            else if ($action == WPST_ACTION_DEALLOCATE)
            {
                $status = $ja->deallocateJob() ;
                $prefix = "de" ;
            }
            else
            {
                $status = null ;
                $prefix = "" ;
            }

            //  Create a status message based on the result of the action

            if ($status != null)
            {
                $actionmsgs[] = sprintf("Job \"%s\", quantity %s, %sallocated for swim meet <i>(%s - %s - %s)</i>.",
                    SwimTeamTextMap::__mapJobIdToText($ja->getJobId()),
                    $ja->getJobQuantity(), $prefix, $meetdetails["opponent"],
                    $meetdetails["date"], $meetdetails["location"]) ;
            }
            else
            {
                $actionmsgs[] = sprintf("Job \"%s\", already %sallocated for swim meet <i>(%s - %s - %s)</i>.",
                    SwimTeamTextMap::__mapJobIdToText($ja->getJobId()), $prefix,
                    $meetdetails["opponent"], $meetdetails["date"],
                    $meetdetails["location"]) ;
            }
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf("No %s actions recorded.", $actionlabel) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function _form_action_season($action = WPST_ACTION_ALLOCATE)
    {
        $success = true ;

        //  Need the WordPress User Id from the global data

        global $userdata ;

        get_currentuserinfo() ;

        //  Loop through the swim meets

        $swimmeets = $this->_swimmeetSelections() ;

        $type = $this->get_element_value("Type") ;

        $jobid = $this->get_hidden_element_value("jobid") ;
        $action = $this->get_hidden_element_value("_action") ;

        $ja = new SwimTeamJobAllocation() ;
        $ja->setJobId($jobid) ;

        $season = new SwimTeamSeason() ;
        $ja->setSeasonId($season->getActiveSeasonId()) ;
        $ja->setMeetId(WPST_NULL_ID) ;
        $ja->setJobQuantity($this->get_element_value("Quantity")) ;

        $seasondetails = SwimTeamTextMap::__mapSeasonIdToText($ja->getSeasonId()) ;

        //  Perform the desired action

        if ($action == WPST_ACTION_ALLOCATE)
        {
            $status = $ja->allocateJob() ;
            $prefix = "" ;
        }
        else if ($action == WPST_ACTION_REALLOCATE)
        {
            $status = $ja->reallocateJob() ;
            $prefix = "re" ;
        }
        else if ($action == WPST_ACTION_DEALLOCATE)
        {
            $status = $ja->deallocateJob() ;
            $prefix = "de" ;
        }
        else
        {
            $status = null ;
            $prefix = "" ;
        }

        //  Build status message resulting from action

        if ($status != null)
        {
            $actionmsg = sprintf("Job \"%s\", quantity %s, %sallocated for season  <i>(%s - %s - %s)</i>.",
                SwimTeamTextMap::__mapJobIdToText($ja->getJobId()),
                $ja->getJobQuantity(), $prefix, $seasondetails["label"],
                $seasondetails["start"], $seasondetails["end"]) ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf("Job \"%s\", already %sallocated for season  <i>(%s - %s - %s)</i>.",
                SwimTeamTextMap::__mapJobIdToText($ja->getJobId()), $prefix,
                $seasondetails["label"], $seasondetails["start"],
                $seasondetails["end"]) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * Construct a container with a success message
     * which can be displayed after form processing
     * is complete.
     *
     * @return Container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Jobs Allocation form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamJobsAllocateForm
 */
class WpSwimTeamJobsReallocateForm extends WpSwimTeamJobsAllocateForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        parent::form_init_data() ;
        $this->set_hidden_element_value("_action", WPST_ACTION_REALLOCATE) ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        return parent::form_action(WPST_ACTION_REALLOCATE) ;
    }
}

/**
 * Construct the Jobs Deallocation form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamJobsReallocateForm
 */
class WpSwimTeamJobsDeallocateForm extends WpSwimTeamJobsReallocateForm
{
    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Delete a job allocation from the system.  This operation removes the job
            allocation for the position.  All record of job assignments, is lost and cannot be recovered.
            Be certain before deallocating a job - a better option may be to reallocate the job to
            change the number of positions needed or to set the Job as inactive if it is no longer
            required.')) ;

        return $div ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        parent::form_init_data() ;
        $this->set_hidden_element_value("_action", WPST_ACTION_DEALLOCATE) ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        return parent::form_action(WPST_ACTION_DEALLOCATE) ;
    }
    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function zzzform_action()
    {
        $success = true ;
        $actionmsgs = array() ;

        //  Need the WordPress User Id from the global data

        global $userdata ;

        get_currentuserinfo() ;

        //  Loop through the swim meets

        $swimmeets = $this->_swimmeetSelections() ;

        $type = $this->get_element_value("Type") ;

        //  Use the available Meet Selections for a season
        //  since the element is disabled and won't be passed
        //  through the form processor.

        if ($type == WPST_SWIMMEET)
            $meetIds = $this->get_element_value("Swim Meets") ;
        else
            $meetIds = $this->_swimmeetSelections() ;

        $jobid = $this->get_hidden_element_value("jobid") ;
        $action = $this->get_hidden_element_value("_action") ;

        $ja = new SwimTeamJobAllocation() ;
        $ja->setJobId($jobid) ;

        $season = new SwimTeamSeason() ;
        $ja->setSeasonId($season->getActiveSeasonId()) ;
        $ja->setJobQuantity($this->get_element_value("Quantity")) ;

        //  Loop through the meets, deallocating jobs as specified

        $success = false ;

        foreach ($meetIds as $meetId)
        {
            $meetdetails = SwimTeamTextMap::__mapMeetIdToText($meetId) ;

            $ja->setMeetId($meetId) ;

            if ($ja->deallocateJob() != null)
            {
                $actionmsgs[] = sprintf("Job \"%s\", quantity %s, deallocated for swim meet <i>(%s - %s - %s)</i>.",
                    SwimTeamTextMap::__mapJobIdToText($ja->getJobId()),
                    $ja->getJobQuantity(), $meetdetails["opponent"],
                    $meetdetails["date"], $meetdetails["location"]) ;
            }
            else
            {
                $actionmsgs[] = sprintf("Job \"%s\", has not been allocated to swim meet <i>(%s - %s - %s)</i>.",
                    SwimTeamTextMap::__mapJobIdToText($ja->getJobId()),
                    $meetdetails["opponent"], $meetdetails["date"],
                    $meetdetails["location"]) ;
            }
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf("No %s actions recorded.", $actionlabel) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }
}

/**
 * Construct the Assign Job form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamJobAssignForm extends WpSwimTeamForm
{
    /**
     * mode property - used to set the mode of the form
     */
    var $__mode ;

    /**
     * job id property - used to track the job id
     */
    var $__jobid ;

    /**
     * job allocation id property - used to track the job allocation id
     */
    var $__joballocationid ;

    /**
     * season id property - used to track the season id
     */
    var $__seasonid ;

    /**
     * meet id property - used to track the meet id
     */
    var $__swimmeetid ;

    /**
     * Return form help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add(html_p('Assign a specific instance of a job to a specific user.  Once assigned
            the user&#039;s name will appear on the Job Reports for the relevant swim meet(s).')) ;

        return $div ;
    }

    /**
     * Set the Mode property
     *
     * @param int $mode - mode the form is in
     */
    function setMode($id)
    {
        $this->__mode = $id ;
    }

    /**
     * Get the Mode property
     *
     * @return int $mode - mode the form is in
     */
    function getMode()
    {
        return $this->__mode ;
    }

    /**
     * Set the Job Id property
     *
     * @param int $id - id of the job
     */
    function setJobId($id)
    {
        $this->__jobid = $id ;
    }

    /**
     * Get the Job Id property
     *
     * @return int $id - id of the job
     */
    function getJobId()
    {
        return $this->__jobid ;
    }

    /**
     * Set the Job Allocation Id property
     *
     * @param int $id - id of the job allocation
     */
    function setJobAllocationId($id)
    {
        $this->__joballocationid = $id ;
    }

    /**
     * Get the Job Allocation Id property
     *
     * @return int $id - id of the job allocation
     */
    function getJobAllocationId()
    {
        return $this->__joballocationid ;
    }

    /**
     * Set the Season Id property
     *
     * @param int $id - id of the season
     */
    function setSeasonId($id)
    {
        $this->__seasonid = $id ;
    }

    /**
     * Get the Season Id property
     *
     * @return int $id - id of the season
     */
    function getSeasonId()
    {
        return $this->__seasonid ;
    }

    /**
     * Set the Meet Id property
     *
     * @param int $id - id of the meet
     */
    function setMeetId($id)
    {
        $this->__swimmeetid = $id ;
    }

    /**
     * Get the Meet Id property
     *
     * @return int $id - id of the meet
     */
    function getMeetId()
    {
        return $this->__swimmeetid ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_jobid") ;
        $this->add_hidden_element("_joballocationid") ;
        $this->add_hidden_element("_seasonid") ;
        $this->add_hidden_element("_swimmeetid") ;
        $this->add_hidden_element("_action") ;

        $season = new SwimTeamSeason() ;
        $seasonid = $season->getActiveSeasonId() ;

        //  Need to create a field for each job for the swim meet

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;
        $ja->setSeasonId($seasonid) ;
        $ja->setJobId($this->getJobId()) ;


        $jaids = $ja->getJobAssignmentIdsByJobIdAndSeasonId() ;

        if (empty($jaids)) return ;

        //  Need to filter out the full season jobs

        foreach ($jaids as $jaid)
        {
            global $userdata ;

            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;

            $job->loadJobByJobId($ja->getJobId()) ;

            if (current_user_can('edit_others_posts'))
            {
                $job_element["$label"] =
                    new FEWPUserListBox("Job #$label", false, "200px") ;
            }
            else if (($ja->getUserId() != WPST_NULL_ID) &&
                ($ja->getUserId() != $userdata->ID))
            {
                $job_element["$label"] =
                    new FEWPUserListBox("Job #$label", false, "200px") ;
                $job_element["$label"]->set_readonly(true) ;
            }
            else
            {
                $job_element["$label"] = new FEWPUserListBox("Job #$label",
                    false, "200px", null, true, true) ;
            }

            $this->add_element($job_element["$label"]) ;
        }
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $season = new SwimTeamSeason() ;
        $seasonid = $season->getActiveSeasonId() ;

        //  Initialize the form fields
        $this->set_hidden_element_value("_action", WPST_ACTION_SIGN_UP) ;
        $this->set_hidden_element_value("_jobid", $this->getJobId()) ;
        $this->set_hidden_element_value("_seasonid", $seasonid) ;

        //$job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;
        $ja->setSeasonId($seasonid) ;
        $ja->setJobId($this->getJobId()) ;

        $jaids = $ja->getJobAssignmentIdsByJobIdAndSeasonId() ;

        if (empty($jaids)) return ;

        foreach ($jaids as $jaid)
        {
            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;

            //$job->loadJobByJobId($ja->getJobId()) ;

            $label = &$jaid["jobassignmentid"] ;
            $this->set_element_value("Job #$label", $ja->getUserId()) ;
        }

    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        //  Bring in the text mapping
        require_once(WPST_PATH . 'classtextmap.class.php') ;

        $season = new SwimTeamSeason() ;
        //$seasonid = $season->getActiveSeasonId() ;
        $seasonid = $this->get_hidden_element_value("_seasonid") ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style("border: 1px solid") ;

        $td = html_td() ;
        $td->set_tag_attributes(array("colspan" => "3", "align" => "center")) ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;
        $ja->setSeasonId($seasonid) ;
        $ja->setJobId($this->getJobId()) ;
        $job->loadJobByJobId($this->getJobId()) ;

        $td->add(div_font12bold($job->getJobPosition())) ;
        $table->add_row($td) ;
 
        $jaids = $ja->getJobAssignmentIdsByJobIdAndSeasonId() ;

        //  Need to create a field for each job for the swim meet or season

        if (!empty($jaids))
        {
            $seasondetails = SwimTeamTextMap::__mapSeasonIdToText($ja->getSeasonId(), true) ;
            foreach ($jaids as $jaid)
            {
                $label = &$jaid["jobassignmentid"] ;

                $ja->setJobAssignmentId($label) ;
                $ja->loadJobAssignmentByJobAssignmentId() ;

                $meetdetails = SwimTeamTextMap::__mapMeetIdToText($ja->getMeetId(), true) ;
            
                $job->loadJobByJobId($ja->getJobId()) ;

                if ($ja->getMeetId() == WPST_NULL_ID)
                {
                    $table->add_row(sprintf("%s (%s) - %s - (Job #%s)",
                        $seasondetails["label"], $seasondetails["start"],
                        $seasondetails["end"], $label),
                        /*$this->element_label("Job #$label"),*/
                        $this->element_form("Job #$label")) ;
                }
                else
                {
                    $table->add_row(sprintf("%s (%s) - %s - (Job #%s)",
                        $meetdetails["opponent"], $meetdetails["location"],
                        $meetdetails["date"], $label),
                        /*$this->element_label("Job #$label"),*/
                        $this->element_form("Job #$label")) ;
                }
            }
        }
        else
        {
            $table->add_row("No jobs allocated to this swim meet.") ;
        }

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
	    return true ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $actionmsgs = array() ;
        //  Bring in the text mapping
        require_once(WPST_PATH . 'classtextmap.class.php') ;

        //  Need to assign each job for the swim meet

        $seasonid = $this->get_hidden_element_value("_seasonid") ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;
        $ja->setSeasonId($seasonid) ;
        $ja->setJobId($this->getJobId()) ;
        $job->loadJobByJobId($this->getJobId()) ;

        $jaids = $ja->getJobAssignmentIdsByJobIdAndSeasonId() ;

        //  Loop through the Job Allocation Ids

        foreach ($jaids as $jaid)
        {

            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;
            $job->loadJobByJobId($ja->getJobId()) ;

            $meetdetails = SwimTeamTextMap::__mapMeetIdToText($ja->getMeetId(), true) ;
            $seasondetails = SwimTeamTextMap::__mapSeasonIdToText($ja->getSeasonId(), true) ;

            //  Has the user id for this job assignment id changed?

            if ($ja->getUserId() != $this->get_element_value("Job #$label"))
            {
                $ja->setUserId($this->get_element_value("Job #$label")) ;

                //  Need to account for the use case when a job
                //  us is unassigned by setting the User to None.

                if ($ja->getUserId() == WPST_NULL_ID)
                {
                    $msg = "unsassigned" ;
                }
                else
                {
                    $u = get_userdata($ja->getUserId()) ;
                    $msg = sprintf("assigned to %s %s (%s)", $u->first_name, $u->last_name, $u->user_login) ;
                }

                //  Perform the assignment

                if ($ja->reassignJob() != null)
                {
                    if ($ja->getMeetId() == WPST_NULL_ID)
                        $actionmsgs[] = sprintf("Job \"%s\" %s
                            for swim season <i>(%s - %s - %s)</i>.",
                            $job->getJobPosition(), $msg, $seasondetails["label"],
                            $seasondetails["start"], $seasondetails["end"]) ;
                    else
                        $actionmsgs[] = sprintf("Job \"%s\" %s
                            for swim meet <i>(%s - %s - %s)</i>.",
                            $job->getJobPosition(), $msg, $meetdetails["opponent"],
                            $meetdetails["date"], $meetdetails["location"]) ;
                }
                else
                {
                    if ($ja->getMeetId() == WPST_NULL_ID)
                        $actionmsgs[] = sprintf("Job \"%s\" WAS NOT %s
                            for swim season <i>(%s - %s - %s)</i>.",
                            $job->getJobPosition(), $msg, $seasondetails["label"],
                            $seasondetails["statrt"], $seasondetails["end"]) ;
                    else
                        $actionmsgs[] = sprintf("Job \"%s\" WAS NOT %s
                            for swim meet <i>(%s - %s - %s)</i>.",
                            $job->getJobPosition(), $msg, $meetdetails["opponent"],
                            $meetdetails["date"], $meetdetails["location"]) ;
                }
            }
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf("No %s changes recorded.", WPST_ACTION_ASSIGN_JOBS) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * Return the status message so it can be
     * displayed by the form processor.
     *
     * @return container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Assign Job form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimMeetJobAssignForm extends WpSwimTeamJobAssignForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_jobid") ;
        $this->add_hidden_element("_joballocationid") ;
        $this->add_hidden_element("_seasonid") ;
        $this->add_hidden_element("_swimmeetid") ;
        $this->add_hidden_element("_action") ;

        //  Need to create a field for each job for the swim meet

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $ja->setMeetId($this->getMeetId()) ;

        $jaids = $ja->getJobAssignmentIdsByMeetId() ;

        if (empty($jaids)) return ;

        //  Need to filter out the full season jobs

        foreach ($jaids as $jaid)
        {
            global $userdata ;

            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;

            $job->loadJobByJobId($ja->getJobId()) ;

            if (current_user_can('edit_others_posts'))
            {
                $job_element["$label"] =
                    new FEWPUserListBox("Job #$label", false, "200px") ;
            }
            else if (($ja->getUserId() != WPST_NULL_ID) &&
                ($ja->getUserId() != $userdata->ID))
            {
                $job_element["$label"] =
                    new FEWPUserListBox("Job #$label", false, "200px") ;
                $job_element["$label"]->set_readonly(true) ;
            }
            else
            {
                $job_element["$label"] = new FEWPUserListBox("Job #$label",
                    false, "200px", null, true, true) ;
            }

            $this->add_element($job_element["$label"]) ;
        }
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields
        $this->set_hidden_element_value("_action", WPST_ACTION_JOBS) ;
        $this->set_hidden_element_value("_swimmeetid", $this->getMeetId()) ;
        $this->set_hidden_element_value("_seasonid", $this->getSeasonId()) ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $ja->setMeetId($this->getMeetId()) ;

        $jaids = $ja->getJobAssignmentIdsByMeetId() ;
        
        if (empty($jaids)) return ;

        foreach ($jaids as $jaid)
        {
            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;

            $job->loadJobByJobId($ja->getJobId()) ;

            $label = &$jaid["jobassignmentid"] ;
            $this->set_element_value("Job #$label", $ja->getUserId()) ;
        }

    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        //  Bring in the text mapping
        require_once(WPST_PATH . 'classtextmap.class.php') ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style("border: 1px solid") ;

        $td = html_td() ;
        $td->set_tag_attributes(array("colspan" => "3", "align" => "center")) ;

        $ja->setMeetId($this->getMeetId()) ;

        $jaids = $ja->getJobAssignmentIdsByMeetId() ;

        $meetdetails = SwimTeamTextMap::__mapMeetIdToText($this->getMeetId(), true) ;

        $td->add(div_font12bold(sprintf("%s - %s - %s",
            $meetdetails["opponent"], $meetdetails["date"],
            $meetdetails["location"]))) ;

        $table->add_row($td) ;
 
        //  Need to create a field for each job for the swim meet or season

        if (!empty($jaids))
        {
            foreach ($jaids as $jaid)
            {
                $label = &$jaid["jobassignmentid"] ;

                $ja->setJobAssignmentId($label) ;
                $ja->loadJobAssignmentByJobAssignmentId() ;
            
                $job->loadJobByJobId($ja->getJobId()) ;

                $table->add_row( $job->getJobPosition() . " (Job #$label)",
                    /*$this->element_label("Job #$label"),*/
                    $this->element_form("Job #$label")) ;
            }
        }
        else
        {
            $table->add_row("No jobs allocated to this swim meet.") ;
        }

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $actionmsgs = array() ;
        //  Bring in the text mapping
        require_once(WPST_PATH . 'classtextmap.class.php') ;

        //  Need to assign each job for the swim meet

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $ja->setMeetId($this->getMeetId()) ;

        $jaids = $ja->getJobAssignmentIdsByMeetId() ;

        $meetdetails = SwimTeamTextMap::__mapMeetIdToText($this->getMeetId(), true) ;

        //  Loop through the Job Allocation Ids

        foreach ($jaids as $jaid)
        {

            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;
            $job->loadJobByJobId($ja->getJobId()) ;

            //  Has the user id for this job assignment id changed?

            if ($ja->getUserId() != $this->get_element_value("Job #$label"))
            {
                $ja->setUserId($this->get_element_value("Job #$label")) ;
                $u = get_userdata($ja->getUserId()) ;

                //  Need to account for the use case when a job
                //  us is unassigned by setting the User to None.

                if ($ja->getUserId() == WPST_NULL_ID)
                {
                    $msg = "unsassigned" ;
                }
                else
                {
                    $u = get_userdata($ja->getUserId()) ;
                    $msg = sprintf("assigned to %s %s (%s)", $u->first_name, $u->last_name, $u->user_login) ;
                }

                if ($ja->reassignJob() != null)
                {
                    $actionmsgs[] = sprintf("Job \"%s\" %s for swim meet <i>(%s - %s - %s)</i>.",
                        $job->getJobPosition(), $msg, $meetdetails["opponent"],
                        $meetdetails["date"], $meetdetails["location"]) ;
                }
                else
                {
                    $actionmsgs[] = sprintf("Job \"%s\" WAS NOT assigned to %s
                        %s (%s) for swim meet <i>(%s - %s - %s)</i>.",
                        $job->getJobPosition(), msg, $meetdetails["opponent"],
                        $meetdetails["date"], $meetdetails["location"]) ;
                }
            }
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf("No %s changes recorded.", WPST_ACTION_ASSIGN_JOBS) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }
}
/**
 * Construct the Assign Job form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSeasonJobAssignForm extends WpSwimTeamJobAssignForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_jobid") ;
        $this->add_hidden_element("_joballocationid") ;
        $this->add_hidden_element("_seasonid") ;
        $this->add_hidden_element("_swimmeetid") ;
        $this->add_hidden_element("_action") ;

        //  Need to create a field for each job for the season

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $ja->setSeasonId($this->getSeasonId()) ;

        $jaids = $ja->getJobAssignmentIdsBySeasonId() ;

        if (empty($jaids)) return ;

        foreach ($jaids as $jaid)
        {
            global $userdata ;

            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;

            $job->loadJobByJobId($ja->getJobId()) ;

            if (current_user_can('edit_others_posts'))
            {
                $job_element["$label"] =
                    new FEWPUserListBox("Job #$label", false, "200px") ;
            }
            else if (($ja->getUserId() != WPST_NULL_ID) &&
                ($ja->getUserId() != $userdata->ID))
            {
                $job_element["$label"] =
                    new FEWPUserListBox("Job #$label", false, "200px") ;
                $job_element["$label"]->set_readonly(true) ;
            }
            else
            {
                $job_element["$label"] = new FEWPUserListBox("Job #$label",
                    false, "200px", null, true, true) ;
            }

            $this->add_element($job_element["$label"]) ;
        }
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Initialize the form fields
        $this->set_hidden_element_value("_action", WPST_ACTION_JOBS) ;
        $this->set_hidden_element_value("_swimmeetid", $this->getMeetId()) ;
        $this->set_hidden_element_value("_seasonid", $this->getSeasonId()) ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $ja->setSeasonId($this->getSeasonId()) ;

        $jaids = $ja->getJobAssignmentIdsBySeasonId() ;
        
        if (empty($jaids)) return ;

        foreach ($jaids as $jaid)
        {
            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;

            $job->loadJobByJobId($ja->getJobId()) ;

            $label = &$jaid["jobassignmentid"] ;
            $this->set_element_value("Job #$label", $ja->getUserId()) ;
        }
    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        //  Bring in the text mapping
        require_once(WPST_PATH . 'classtextmap.class.php') ;

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style("border: 1px solid") ;

        $td = html_td() ;
        $td->set_tag_attributes(array("colspan" => "3", "align" => "center")) ;

        $ja->setSeasonId($this->getSeasonId()) ;

        $jaids = $ja->getJobAssignmentIdsBySeasonId() ;

        $seasondetails = SwimTeamTextMap::__mapSeasonIdToText($this->getSeasonId(), true) ;

        $td->add(div_font12bold(sprintf("%s - %s - %s",
            $seasondetails["label"], $seasondetails["start"],
            $seasondetails["end"]))) ;

        $table->add_row($td) ;
 
        //  Need to create a field for each job for the swim meet or season

        if (!empty($jaids))
        {
            foreach ($jaids as $jaid)
            {
                $label = &$jaid["jobassignmentid"] ;

                $ja->setJobAssignmentId($label) ;
                $ja->loadJobAssignmentByJobAssignmentId() ;
            
                $job->loadJobByJobId($ja->getJobId()) ;

                $table->add_row( $job->getJobPosition() . " (Job #$label)",
                    /*$this->element_label("Job #$label"),*/
                    $this->element_form("Job #$label")) ;
            }
        }
        else
        {
            $table->add_row("No jobs allocated to this swim season.") ;
        }

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
	    return true ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $actionmsgs = array() ;
        //  Bring in the text mapping
        require_once(WPST_PATH . 'classtextmap.class.php') ;

        //  Need to assign each job for the swim meet

        $job = new SwimTeamJob() ;
        $ja = new SwimTeamJobAssignment() ;

        $ja->setSeasonId($this->getSeasonId()) ;

        $jaids = $ja->getJobAssignmentIdsBySeasonId() ;

        $seasondetails = SwimTeamTextMap::__mapSeasonIdToText($this->getSeasonId(), true) ;

        //  Loop through the Job Allocation Ids

        foreach ($jaids as $jaid)
        {

            $label = &$jaid["jobassignmentid"] ;

            $ja->setJobAssignmentId($label) ;
            $ja->loadJobAssignmentByJobAssignmentId() ;
            $job->loadJobByJobId($ja->getJobId()) ;

            //  Has the user id for this job assignment id changed?

            if ($ja->getUserId() != $this->get_element_value("Job #$label"))
            {
                $ja->setUserId($this->get_element_value("Job #$label")) ;
                $u = get_userdata($ja->getUserId()) ;

                if ($ja->reassignJob() != null)
                {
                    $actionmsgs[] = sprintf("Job \"%s\" assigned to %s
                        %s (%s) for swim season <i>(%s - %s - %s)</i>.",
                        $job->getJobPosition(), $u->first_name, $u->last_name,
                        $u->user_login, $seasondetails["label"],
                        $seasondetails["start"], $seasondetails["end"]) ;
                }
                else
                {
                    $actionmsgs[] = sprintf("Job \"%s\" WAS NOT assigned to %s
                        %s (%s) for swim season <i>(%s - %s - %s)</i>.",
                        $job->getJobPosition(), $u->first_name, $u->last_name,
                        $u->user_login, $seasondetails["label"],
                        $seasondetails["statrt"], $seasondetails["end"]) ;
                }
            }
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($actionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $this->setErrorActionMessageDivClass() ;
            $actionmsg = sprintf("No %s changes recorded.", WPST_ACTION_ASSIGN_JOBS) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }
}
?>
