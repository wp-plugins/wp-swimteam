<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Seasons
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once("seasons.class.php") ;
require_once("forms.class.php") ;

/**
 * Construct the Add Season form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSeasonAddForm extends WpSwimTeamForm
{
    /**
     * id property - used to track the age group record
     */

    var $__id ;

    /**
     * Set the Id property
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the Id property
     */
    function getId()
    {
        return $this->__id ;
    }

    /**
     * Get the array of status key and value pairs
     *
     * @return mixed - array of status key value pairs
     */
    function _statusSelections()
    {
        //  Status options and labels are set based on
        //  the plugin options

        $s = array(
            ucfirst(WPST_ACTIVE) => WPST_ACTIVE
           ,ucfirst(WPST_INACTIVE) => WPST_INACTIVE
        ) ;

         return $s ;
    }

    /**
     * Get the array of swimmer id status key and value pairs
     *
     * @return mixed - array of swimmer id status key value pairs
     */
    function _publicSwimmerIdSelections()
    {
        //  Status options and labels are set based on
        //  the plugin options

        $s = array(
            ucfirst(WPST_UNLOCKED) => WPST_UNLOCKED
           ,ucfirst(WPST_LOCKED) => WPST_LOCKED
           ,ucfirst(WPST_FROZEN) => WPST_FROZEN
        ) ;

         return $s ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  If the class constructing the form is for
        //  the delete operation, the fields are displayed
        //  but are set in the disabled state.
        //$disabled_field = (strtoupper(get_class($this))
            //== strtoupper("WpSwimTeamSeasonDeleteForm")) ? true : false ;


        switch (strtoupper(get_class($this)))
        {
            case strtoupper("WpSwimTeamSeasonOpenForm"):
            case strtoupper("WpSwimTeamSeasonCloseForm"):
            case strtoupper("WpSwimTeamSeasonDeleteForm"):
                $disabled_field = true ;
                break ;

            default:
                $disabled_field = false ;
                break ;
        }

        $this->add_hidden_element("seasonid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

        $description = new FEText("Description", !$disabled_field, "250px") ;
        $description->set_readonly($disabled_field) ;
        $this->add_element($description) ;


        //  Start Field

        if ($disabled_field)
            $season_start = new FEText("Start", !$disabled_field, "150px") ;
        else
            $season_start = new FEDate("Start", !$disabled_field, null, null,
                "Fdy", date("Y") - 3, date("Y") + 7) ;

        $season_start->set_readonly($disabled_field) ;


        $this->add_element($season_start);
		
        //  End Field
        if ($disabled_field)
            $season_end = new FEText("End", !$disabled_field, "150px") ;
        else
            $season_end = new FEDate("End", !$disabled_field, null, null,
                "Fdy", date("Y") - 3, date("Y") + 7) ;
        $season_end->set_readonly($disabled_field) ;

        $this->add_element($season_end);
		
        //  Status options and labels are set based on
        //  the plugin options

        $status = new FEListBox("Status", !$disabled_field, "150px");
        $status->set_list_data($this->_statusSelections()) ;
        $status->set_readonly($disabled_field) ;

        //  Disable status field on an Add operation - all seasons
        //  start out inactive so two seasons don't end up active
        //  simultaneously.

        if (!$disabled_field)
            $status->set_readonly(strtoupper(get_class($this))
            == strtoupper("WpSwimTeamSeasonAddForm")) ;

        $this->add_element($status) ;

        //  Status options and labels are set based on
        //  the plugin options

        $swimmerlabels = new FEListBox("Swimmer Labels", !$disabled_field, "150px");
        $swimmerlabels->set_list_data($this->_publicSwimmerIdSelections()) ;
        $swimmerlabels->set_readonly($disabled_field) ;

        $this->add_element($swimmerlabels) ;
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
        $this->set_element_value("Start", array("year" => date("Y"),
            "month" => date("m"), "day" => date("d"))) ;
        $this->set_element_value("End", array("year" => date("Y"),
            "month" => date("m"), "day" => date("d"))) ;
        $this->set_element_value("Status", WPST_SEASONS_SEASON_INACTIVE) ;
        $this->set_element_value("Swimmer Labels", WPST_UNLOCKED) ;
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

        $table->add_row($this->element_label("Description"),
            $this->element_form("Description")) ;

        $table->add_row($this->element_label("Start"),
            $this->element_form("Start")) ;

        $table->add_row($this->element_label("End"),
            $this->element_form("End")) ;

        $table->add_row($this->element_label("Status"),
            $this->element_form("Status")) ;

        $table->add_row($this->element_label("Swimmer Labels"),
            $this->element_form("Swimmer Labels")) ;

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

        $season = new SwimTeamSeason() ;
        $season->setSeasonLabel($this->get_element_value("Description")) ;
        $season->setSeasonStart($this->get_element_value("Start")) ;
        $season->setSeasonEnd($this->get_element_value("End")) ;
        $season->setSeasonStatus($this->get_element_value("Status")) ;
        $season->setSwimmerLabels($this->get_element_value("Swimmer Labels")) ;

        if ($season->seasonExist())
        {
            $this->add_error("Description", "Season already exists.");
            $this->add_error("Start", "Season already exists.");
            $this->add_error("End", "Season already exists.");
            $this->add_error("Status", "Season already exists.");
            $valid = false ;
        }

        //  Make sure dates are reasonable
        
        $d = $this->get_element_value("Start") ;
        $startTime = strtotime(sprintf("%04s-%02s-%02s", $d["year"], $d["month"], $d["day"])) ;
        $d = $this->get_element_value("End") ;
        $endTime = strtotime(sprintf("%04s-%02s-%02s", $d["year"], $d["month"], $d["day"])) ;

 
        if ($startTime == $endTime)
        {
            $this->add_error("Start", "Start date and End date are the same.") ;
            $this->add_error("End", " Start date and End date are the same.") ;
            $valid = false ;
        }
        
        if ($startTime > $endTime)
        {
            $this->add_error("Start", "Start date occurs after End date.") ;
            $this->add_error("End", " End date occurs before Start date.") ;
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
        $season = new SwimTeamSeason() ;
        $season->setSeasonLabel($this->get_element_value("Description")) ;
        $season->setSeasonStart($this->get_element_value("Start")) ;
        $season->setSeasonEnd($this->get_element_value("End")) ;
        $season->setSwimmerLabels($this->get_element_value("Swimmer Labels")) ;

        //  Seasons always start inactive ...
        $season->setSeasonStatus(WPST_INACTIVE) ;
        //$season->setSeasonStatus($this->get_element_value("Status")) ;

        $success = $season->addSeason() ;

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $season->setId($success) ;
            $this->set_action_message("Season successfully added.") ;
        }
        else
        {
            $this->set_action_message("Season was not successfully added.") ;
        }

        return $success ;
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
 * Construct the Update Season form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSeasonAddForm
 */
class WpSwimTeamSeasonUpdateForm extends WpSwimTeamSeasonAddForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_SEASONS_UPDATE_SEASON)
    {
        $season = new SwimTeamSeason() ;
        $season->loadSeasonById($this->getId()) ;

        //  Initialize the form fields
        $this->set_hidden_element_value("seasonid", $this->getId()) ;
        $this->set_hidden_element_value("_action", $action) ;
        $this->set_element_value("Description", $season->getSeasonLabel()) ;
        $this->set_element_value("Start", $season->getSeasonStart()) ;
        $this->set_element_value("End", $season->getSeasonEnd()) ;
        $this->set_element_value("Status", $season->getSeasonStatus()) ;
        $this->set_element_value("Swimmer Labels", $season->getSwimmerLabels()) ;
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

        $season = new SwimTeamSeason() ;
        $season->setSeasonLabel($this->get_element_value("Description")) ;
        $season->setSeasonStart($this->get_element_value("Start")) ;
        $season->setSeasonEnd($this->get_element_value("End")) ;
        $season->setSeasonStatus($this->get_element_value("Status")) ;
        $season->setSwimmerLabels($this->get_element_value("Swimmer Labels")) ;

        //  The existance check retains the id result of the query
        //  (if it found one) so it can be used for additional checking.

        if ($season->seasonExist())
        {
            //  A similar season exists - is it the same one we're updating?

            $qr = $season->getQueryResult() ;

            if ($qr["id"] != $this->get_hidden_element_value("seasonid"))
            {
                $this->add_error("Description", "Similar season already exists.");
                $this->add_error("Start", "Similar season already exists.");
                $this->add_error("End", "Similar season already exists.");
                $valid = false ;
            }
            else
            {
                //  Make sure dates are reasonable
        
                $d = $this->get_element_value("Start") ;
                $startTime = strtotime(sprintf("%04s-%02s-%02s",
                    $d["year"], $d["month"], $d["day"])) ;
                $d = $this->get_element_value("End") ;
                $endTime = strtotime(sprintf("%04s-%02s-%02s",
                    $d["year"], $d["month"], $d["day"])) ;
 
                if ($startTime == $endTime)
                {
                    $this->add_error("Start", "Start date and End date are the same.") ;
                    $this->add_error("End", " Start date and End date are the same.") ;
                    $valid = false ;
                }
                else if ($startTime > $endTime)
                {
                    $this->add_error("Start", "Start date occurs after End date.") ;
                    $this->add_error("End", " End date occurs before Start date.") ;
                    $valid = false ;
                }
                else
                {
                    //  Check to make sure we aren't doing something
                    //  which is already done like opening a season
                    //  which is already open or unlocking swimmer
                    //  ids which are already unlocked.
        
                    $oldSeason = new SwimTeamSeason() ;
                    $oldSeason->setId($this->get_hidden_element_value("seasonid")) ;
                    $oldSeason->loadSeasonById() ;

                    //  No change to either season status or swimmer ids?

                    if (($oldSeason->getSeasonStatus() == $season->getSeasonStatus()) && ($oldSeason->getSwimmerLabels() == $season->getSwimmerLabels()))
                    {
                        switch ($season->getSeasonStatus())
                        {
                            case WPST_SEASONS_SEASON_ACTIVE:
                                $this->add_error("Status", "Season is already open.") ;
                                break ;

                            case WPST_SEASONS_SEASON_INACTIVE:
                                $this->add_error("Status", "Season is already closed.") ;
                                break ;

                            default:
                                $this->add_error("Status", "No change to season status.") ;
                                break ;
                        }

                        switch ($season->getSwimmerLabels())
                        {
                            case WPST_LOCKED:
                                $this->add_error("Swimmer Labels", "Swimmer Labels are already locked.") ;
                                break ;

                            case WPST_UNLOCKED:
                                $this->add_error("Swimmer Labels", "Swimmer Labels are already unlocked.") ;
                                break ;

                            case WPST_FROZEN:
                                $this->add_error("Swimmer Labels", "Swimmer Labels are already frozen.") ;
                                break ;

                            default:
                                $this->add_error("Swimmer Labels", "No change to swimmer Ids status.") ;
                                break ;
                        }

                        $valid = false ;
                    }
                }
            }
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
        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $season->setSeasonLabel($this->get_element_value("Description")) ;
        $season->setSeasonStart($this->get_element_value("Start")) ;
        $season->setSeasonEnd($this->get_element_value("End")) ;
        $season->setSeasonStatus($this->get_element_value("Status")) ;
        $season->setSwimmerLabels($this->get_element_value("Swimmer Labels")) ;
        $success = $season->updateSeason() ;

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $season->setId($success) ;
            $this->set_action_message("Season successfully updated.") ;
        }
        else
        {
            $this->set_action_message("Season was not successfully updated.") ;
        }

        return $success ;
    }
}

/**
 * Construct the Delete Season form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSeasonUpdateForm
 */
class WpSwimTeamSeasonDeleteForm extends WpSwimTeamSeasonUpdateForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_SEASONS_DELETE_SEASON)
    {
        parent::form_init_data($action) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
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
        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $success = $season->deleteSeason() ;

        if ($success) 
            $this->set_action_message("Season successfully deleted.") ;
        else
            $this->set_action_message("Season was not successfully deleted.") ;

        return $success ;
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
 * Construct the Open Season form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSeasonDeleteForm
 */
class WpSwimTeamSeasonOpenForm extends WpSwimTeamSeasonDeleteForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_SEASONS_OPEN_SEASON)
    {
        parent::form_init_data($action) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
     */
    function form_backend_validation()
    {
        $valid = true ;

        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $season->loadSeasonById() ;
 
        //  Don't want to open a season which is alreay opened
 
        if ($season->getSeasonStatus() == WPST_SEASONS_SEASON_ACTIVE)
        {
            $valid = false ;
            $this->add_error("Status", "Season is already open.") ;
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
        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $success = $season->openSeason() ;

        if ($success) 
            $this->set_action_message("Season successfully opened.") ;
        else
            $this->set_action_message("Season was not successfully opened.") ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Delete" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Open_Cancel() ;
    }
}

/**
 * Construct the Close Season form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSeasonDeleteForm
 */
class WpSwimTeamSeasonCloseForm extends WpSwimTeamSeasonDeleteForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_SEASONS_CLOSE_SEASON)
    {
        parent::form_init_data($action) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
     */
    function form_backend_validation()
    {
        $valid = true ;

        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $season->loadSeasonById() ;
 
        //  Don't want to close a season which is alreay closed
 
        if ($season->getSeasonStatus() == WPST_SEASONS_SEASON_INACTIVE)
        {
            $valid = false ;
            $this->add_error("Status", "Season is already closed.") ;
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
        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $success = $season->closeSeason() ;

        if ($success) 
            $this->set_action_message("Season successfully closed.") ;
        else
            $this->set_action_message("Season was not successfully closed.") ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Delete" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Close_Cancel() ;
    }
}

/**
 * Construct the Lock Swimmer Labels form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSeasonDeleteForm
 */
class WpSwimTeamLockSwimmerIdsForm extends WpSwimTeamSeasonDeleteForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_SEASONS_LOCK_IDS)
    {
        parent::form_init_data($action) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
     */
    function form_backend_validation()
    {
        $valid = true ;

        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $season->loadSeasonById() ;
 
        //  Don't want to open a season which is alreay opened
 
        if ($season->getSwimmerLabels() == WPST_LOCKED)
        {
            $valid = false ;
            $this->add_error("Swimmer Labels", "Swimmer Labels are already locked.") ;
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
        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $success = $season->lockSwimmerIds() ;

        if ($success) 
            $this->set_action_message("Swimmer Labels successfully locked.") ;
        else
            $this->set_action_message("Swimmer Labels were not successfully locked.") ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Lock" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Lock_Cancel() ;
    }
}

/**
 * Construct the Unlock Swimmer Labels form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSeasonDeleteForm
 */
class WpSwimTeamUnlockSwimmerIdsForm extends WpSwimTeamSeasonDeleteForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_SEASONS_UNLOCK_IDS)
    {
        parent::form_init_data($action) ;
    }

    /**
     * Validate the form elements.  In this case, there is
     * no need to validate anything because it is a delete
     * operation and the form elements are disabled and
     * not passed to the form processor.
     *
     * @return boolean
     */
    function form_backend_validation()
    {
        $valid = true ;

        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $season->loadSeasonById() ;
 
        //  Don't want to unlock swimmers labels which are already unlocked!
 
        if ($season->getSwimmerLabels() == WPST_UNLOCKED)
        {
            $valid = false ;
            $this->add_error("Swimmer Labels", "Swimmer Labels are already unlocked.") ;
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
        $season = new SwimTeamSeason() ;
        $season->setId($this->get_hidden_element_value("seasonid")) ;
        $success = $season->unlockSwimmerIds() ;

        if ($success) 
            $this->set_action_message("Swimmer Labels successfully unlocked.") ;
        else
            $this->set_action_message("Swimmer Labels were not successfully unlocked.") ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Unlock" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Unlock_Cancel() ;
    }
}

?>
