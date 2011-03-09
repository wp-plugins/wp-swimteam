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
 * @subpackage SwimMeets
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once("forms.class.php") ;
require_once("seasons.class.php") ;
require_once("swimmeets.class.php") ;
require_once("swimclubs.class.php") ;
require_once("swimmers.class.php") ;
require_once("sdif.include.php") ;

/**
 * Construct the base SwimMeet form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimMeetForm extends WpSwimTeamForm
{
    /**
     * meet id property - used to track the age group record
     */

    var $__meetid ;

    /**
     * Set the meet id property
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id property
     */
    function getMeetId()
    {
        return $this->__meetid ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element("meetid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;
    }
}

/**
 * Construct the Swim Meet Import Results form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimMeetForm
 */
class WpSwimTeamSwimMeetFileUploadForm extends WpSwimTeamSwimMeetForm
{
    /**
     * File Info Table property
     */
    var $__fileInfoTable ; 

    /**
     * Upload File Label property
     */
    var $__uploadFileLabel = "Filename" ;

    /** 
     * This method returns the InfoTable widget. 
     */ 
    function get_file_info_table()
    { 
        return $this->__fileInfoTable ; 
    } 

    /** 
     * This method creates an InfoTable widget which 
     * is used to display information regarding the  
     * uploaded file. 
     */ 
    function set_file_info_table($fileInfo)
    { 
        $it = new InfoTable("File Upload Summary", 400) ; 

        $lines = file($fileInfo['tmp_name']) ; 

        $it->add_row("Filename", $fileInfo['name']) ; 
        $it->add_row("Temporary Filename", $fileInfo['tmp_name']) ; 
        $it->add_row("File Size", filesize($fileInfo['tmp_name'])) ; 
        $it->add_row("Lines", count($lines)) ; 

        unset($lines) ; 

        $this->__fileInfoTable = &$it ; 
    } 

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $uploadedfile = new FEFile($this->__uploadFileLabel, true, "100%") ; 
        $uploadedfile->set_max_size(10240000000) ; 
        $uploadedfile->set_temp_dir(ini_get('upload_tmp_dir')) ; 

        $this->add_element($uploadedfile) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        //$table = html_table($this->_width,0,4) ;
        $table = html_table("100%", 2,4) ;
        $table->set_style("border: 3px solid") ;

        $table->add_row($this->element_label($this->__uploadFileLabel),
            $this->element_form($this->__uploadFileLabel)) ;

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
        $success = true ;

        $this->set_action_message("File \"" . 
            $this->get_element_value($this->__uploadFileLabel) .
            "\" successfully uploaded.") ; 
        $file = $this->get_element($this->__uploadFileLabel) ; 
        $fileInfo = $file->get_file_info() ; 

        $this->set_file_info_table($fileInfo) ; 

        //  Delete the file so we don't keep a lot of stuff around. 

        if (!unlink($fileInfo['tmp_name'])) 
            $this->add_error($this->__uploadFileLabel, "Unable to remove uploaded file."); 

        $this->set_action_message("File uploaded.") ;

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Upload" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Upload_Cancel() ;
    }
}


/**
 * Construct the Add SwimMeet form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimMeetAddForm extends WpSwimTeamSwimMeetForm
{
    /**
     * Get the array of location key and value pairs
     *
     * @return mixed - array of location key value pairs
     */
    function _locationSelections()
    {
        //  Location options and labels 

        $s = array(
            ucfirst(WPST_HOME) => WPST_HOME
           ,ucfirst(WPST_AWAY) => WPST_AWAY
        ) ;

         return $s ;
    }

    /**
     * Get the array of meet type key and value pairs
     *
     * @return mixed - array of meet type key value pairs
     */
    function _meettypeSelections()
    {
        //  Meet Type options and labels 

        $s = array(
            ucwords(WPST_DUAL_MEET) => WPST_DUAL_MEET
           ,ucwords(WPST_TIME_TRIAL) => WPST_TIME_TRIAL
           ,ucwords(WPST_INVITATIONAL) => WPST_INVITATIONAL
           ,ucwords(WPST_RELAY_CARNIVAL) => WPST_RELAY_CARNIVAL
        ) ;

        return $s ;
    }

    /**
     * Get the array of participation key and value pairs
     *
     * @return mixed - array of participation key value pairs
     */
    function _participationSelections()
    {
        //  Meet Type options and labels 

        $s = array(
            ucwords(get_option(WPST_OPTION_OPT_IN_LABEL)) => WPST_OPT_IN
           ,ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL)) => WPST_OPT_OUT
        ) ;

        return $s ;
    }

    /**
     * Get the array of season key and value pairs
     *
     * @return mixed - array of season key value pairs
     */
    function _seasonSelections()
    {
        //  Season options and labels 

        $s = array() ;

        $season = new SwimTeamSeason() ;
        $seasonIds = $season->getAllSeasonIds() ;

        if (!is_null($seasonIds))
        {
            foreach ($seasonIds as $seasonId)
            {
                $season->loadSeasonById($seasonId["seasonid"]) ;
                $s[$season->getSeasonLabel()] = $season->getId() ;
            }
        }

        return $s ;
    }

    /**
     * Get the array of opponent swim club key and value pairs
     *
     * @return mixed - array of opponent swim club key value pairs
     */
    function _opponentSelections()
    {
        //  Swim Club options and labels, seed "None" as an
        //  option as some meet types don't have an opponent.

        $s = array(ucfirst(WPST_NONE) => WPST_NULL_ID) ;

        $swimclub = new SwimClubProfile() ;
        $swimclubIds = $swimclub->getAllSwimClubIds() ;

        //  Make sure we have swim clubs to build a list of!
        if ($swimclubIds != null)
        {
            foreach ($swimclubIds as $swimclubId)
            {
                $swimclub->loadSwimClubBySwimClubId($swimclubId["swimclubid"]) ;

                $opponent = $swimclub->getClubOrPoolName() .
                    " " . $swimclub->getTeamName() ;
                $s[$opponent] = $swimclub->getSwimClubId() ;
            }
        }

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
        $this->add_hidden_element("meetid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

		//  Season field

        $season = new FEListBox("Season", true, "200px");
        $season->set_list_data($this->_seasonSelections()) ;
        $this->add_element($season) ;

		//  Opponent field

        $opponent = new FEListBox("Opponent", true, "300px");
        $opponent->set_list_data($this->_opponentSelections()) ;
        $this->add_element($opponent) ;

		//  Description field

        $description = new FEText("Description", false, "300px");
        $this->add_element($description) ;

		//  Meet Type field

        $meettype = new FEListBox("Meet Type", true, "150px");
        $meettype->set_list_data($this->_meettypeSelections()) ;
        $this->add_element($meettype) ;

		//  Participation field

        $participation = new FEListBox("Participation", true, "150px");
        $participation->set_list_data($this->_participationSelections()) ;
        $this->add_element($participation) ;

        //  Date Field

        $meetdate = new FEDate("Date", true, null, null,
                "Fdy", date("Y") - 3, date("Y") + 7) ;
        $this->add_element($meetdate);

        $hours = new FEHoursListBox("Time", true) ;
        $this->add_element($hours) ;
		
        $minutes = new FEMinutesListBox("Minutes", true) ;
        $this->add_element($minutes) ;
		
        $location = new FEListBox("Location", true, "100px");
        $location->set_list_data($this->_locationSelections()) ;
        $this->add_element($location) ;

		//  Team Score field

        $teamscore = new FENumberFloat("Team Score", true, "50px");
        $this->add_element($teamscore) ;

		//  Opponent Score field

        $opponentscore = new FENumberFloat("Opponent Score", true, "50px");
        $this->add_element($opponentscore) ;
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

        $season = new SwimTeamSeason() ;

        $this->set_element_value("Season", $season->getActiveSeasonId()) ;
        $this->set_element_value("Opponent", WPST_NULL_ID) ;
        $this->set_element_value("Meet Type", WPST_DUAL_MEET) ;
        $this->set_element_value("Participation", WPST_OPT_OUT) ;
        $this->set_element_value("Location", WPST_HOME) ;
        $this->set_element_value("Date", array("year" => date("Y"),
            "month" => date("m"), "day" => date("d"))) ;
        //$this->set_element_value("Time", date("H")) ;
        //$this->set_element_value("Minutes", date("i")) ;
        $this->set_element_value("Time", "17") ;
        $this->set_element_value("Minutes", "0") ;
        $this->set_element_value("Team Score", "0") ;
        $this->set_element_value("Opponent Score", "0") ;
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

        $table->add_row($this->element_label("Season"),
            $this->element_form("Season")) ;

        $table->add_row($this->element_label("Opponent"),
            $this->element_form("Opponent")) ;

        $table->add_row($this->element_label("Meet Type"),
            $this->element_form("Meet Type")) ;

        $table->add_row($this->element_label("Participation"),
            $this->element_form("Participation")) ;

        $table->add_row(html_td(null, null,
            $this->element_label("Description")), html_td(null, null,
            $this->element_form("Description"),
            div_font8bold("Optional description for non-dual meets."))) ;

        $table->add_row($this->element_label("Location"),
            $this->element_form("Location")) ;

        $table->add_row($this->element_label("Date"),
            $this->element_form("Date")) ;

        $table->add_row(html_td(null, null,
            $this->element_label("Time")), html_td(null, null,
            $this->element_form("Time"), ":", $this->element_form("Minutes"),
            div_font8bold("Time is in 24 hour HH:MM format"))) ;

        $table->add_row($this->element_label("Team Score"),
            $this->element_form("Team Score")) ;

        $table->add_row($this->element_label("Opponent Score"),
            $this->element_form("Opponent Score")) ;

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation($checkexists = true)
    {
        $valid = true ;

        //  Make sure swim meet is unique

        $meet = new SwimMeet() ;

        $meet->setSeasonId($this->get_element_value("Season")) ;
        $meet->setOpponentSwimClubId($this->get_element_value("Opponent")) ;
        $meet->setMeetType($this->get_element_value("Meet Type")) ;
        $meet->setParticipation($this->get_element_value("Participation")) ;
        $meet->setMeetDescription($this->get_element_value("Description")) ;
        $meet->setLocation($this->get_element_value("Location")) ;
        $meet->setMeetDate($this->get_element_value("Date")) ;

        $time = $this->get_element_value("Time") . ":" .
            $this->get_element_value("Minutes") . ":00" ;
        $meet->setMeetTime($time) ;

        $meet->setTeamScore($this->get_element_value("Team Score")) ;
        $meet->setOpponentScore($this->get_element_value("Opponent Score")) ;

        //  Check existance?
 
        if ($checkexists)
        {
            if ($meet->getSwimMeetExists())
            {
                $this->add_error("Season", "Similar swim meet already exists.");
                $this->add_error("Opponent", "Similar swim meet already exists.");
                $this->add_error("Date", "Similar swim meet already exists.");
                $valid = false ;
            }
        }

        //  Make sure dates are reasonable - is it during the season?
        
        $season = new SwimTeamSeason() ;
        $season->loadSeasonById($meet->getSeasonId()) ;

        $s = $season->getSeasonStartAsArray() ;
        $st = strtotime(sprintf("%04s-%02s-%02s", $s["year"], $s["month"], $s["day"])) ;

        $e = $season->getSeasonEndAsArray() ;
        $et = strtotime(sprintf("%04s-%02s-%02s", $e["year"], $e["month"], $e["day"])) ;

        $d = $meet->getMeetDate() ;
        $dt = strtotime(sprintf("%04s-%02s-%02s", $d["year"], $d["month"], $d["day"])) ;
 
        //  Date before season start or after season end?

        if (($dt < $st) || ($dt > $et))
        {
            $this->add_error("Date", "Date occurs outside of season.") ;
            $valid = false ;
        }

        //  Dual meet with no opponent?

        if (($meet->getMeetType() == WPST_DUAL_MEET) &&
            ($meet->getOpponentSwimClubId() == WPST_NONE))
        {
            $this->add_error("Opponent", "No opponent selected for dual meet.") ;
            $valid = false ;
        }

        //  Non-dual meet?  If so, need a description

        if (($meet->getMeetType() != WPST_DUAL_MEET) &&
            ($meet->getMeetDescription() == WPST_NULL_STRING))
        {
            $this->add_error("Description", sprintf("Description required for %s.", ucwords($meet->getMeetType()))) ;
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
        $meet = new SwimMeet() ;

        $meet->setSeasonId($this->get_element_value("Season")) ;
        $meet->setOpponentSwimClubId($this->get_element_value("Opponent")) ;
        $meet->setMeetType($this->get_element_value("Meet Type")) ;
        $meet->setParticipation($this->get_element_value("Participation")) ;
        $meet->setMeetDescription($this->get_element_value("Description")) ;
        $meet->setLocation($this->get_element_value("Location")) ;
        $meet->setMeetDate($this->get_element_value("Date")) ;

        $time = $this->get_element_value("Time") . ":" .
            sprintf("%02s", $this->get_element_value("Minutes")) . ":00" ;
        $meet->setMeetTime($time) ;

        $meet->setTeamScore($this->get_element_value("Team Score")) ;
        $meet->setOpponentScore($this->get_element_value("Opponent Score")) ;

        $success = $meet->addSwimMeet() ;

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $meet->setMeetId($success) ;
            $this->set_action_message("Swim Meet successfully added.") ;
        }
        else
        {
            $this->set_action_message("Swim Meet was not successfully added.") ;
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
 * Construct the Update SwimMeet form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimMeetAddForm
 */
class WpSwimTeamSwimMeetUpdateForm extends WpSwimTeamSwimMeetAddForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_hidden_element_value("_action", WPST_ACTION_UPDATE) ;

        $meet = new SwimMeet() ;
        $meet->loadSwimMeetByMeetId($this->getMeetId()) ;

        $this->set_hidden_element_value("meetid", $meet->getMeetId()) ;
        $this->set_element_value("Season", $meet->getSeasonId()) ;
        $this->set_element_value("Opponent", $meet->getOpponentSwimClubId()) ;
        $this->set_element_value("Meet Type", $meet->getMeetType()) ;
        $this->set_element_value("Participation", $meet->getParticipation()) ;
        $this->set_element_value("Description", $meet->getMeetDescription()) ;
        $this->set_element_value("Location", $meet->getLocation()) ;
        $this->set_element_value("Date", $meet->getMeetDate())  ;

        $time = $meet->getMeetTimeAsArray() ;
        $this->set_element_value("Time", $time["hours"]) ;
        $this->set_element_value("Minutes", $time["minutes"]) ;

        $this->set_element_value("Team Score", $meet->getTeamScore()) ;
        $this->set_element_value("Opponent Score", $meet->getOpponentScore()) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        $valid = parent::form_backend_validation(false) ;

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
        $meet = new SwimMeet() ;

        $meet->setMeetId($this->get_hidden_element_value("meetid")) ;
        $meet->setSeasonId($this->get_element_value("Season")) ;
        $meet->setOpponentSwimClubId($this->get_element_value("Opponent")) ;
        $meet->setMeetType($this->get_element_value("Meet Type")) ;
        $meet->setParticipation($this->get_element_value("Participation")) ;
        $meet->setMeetDescription($this->get_element_value("Description")) ;
        $meet->setLocation($this->get_element_value("Location")) ;
        $meet->setMeetDate($this->get_element_value("Date")) ;

        $time = $this->get_element_value("Time") . ":" .
            sprintf("%02s", $this->get_element_value("Minutes")) . ":00" ;
        $meet->setMeetTime($time) ;

        $meet->setTeamScore($this->get_element_value("Team Score")) ;
        $meet->setOpponentScore($this->get_element_value("Opponent Score")) ;

        $success = $meet->updateSwimMeet() ;

        //  If successful, store the updated meet id in so it can be used later.

        if ($success) 
        {
            $meet->setMeetId($success) ;
            $this->set_action_message("Swim Meet successfully updated.") ;
        }
        else
        {
            $this->set_action_message("Swim Meet was not successfully updated.") ;
        }

        return $success ;
    }
}

/**
 * Construct the Delete SwimMeet form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimMeetUpdateForm
 */
class WpSwimTeamSwimMeetDeleteForm extends WpSwimTeamSwimMeetUpdateForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_MEETS_DELETE_MEET)
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
        $meet = new SwimTeamSwimMeet() ;
        $meet->setId($this->get_hidden_element_value("meetid")) ;
        $success = $meet->deleteSwimMeet() ;

        if ($success) 
            $this->set_action_message("SwimMeet successfully deleted.") ;
        else
            $this->set_action_message("SwimMeet was not successfully deleted.") ;

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
 * Construct the SwimMeet OptInOut form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimMeetOptInOutForm extends WpSwimTeamForm
{
    /**
     * action property - used to pass the action to the form processor
     */
    var $__action ;

    /**
     * meet id property - used to pass the meet id to the form processor
     */
    var $__meetid ;

    /**
     * div to hold the full events selection
     */
    var $__full_events_div ;

    /**
     * div to hold the partial events selection
     */
    var $__partial_events_div ;

    var $__events ;

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
     * Get the label for the action property
     *
     * @return string - action label
     */
    function getActionLabel()
    {
        $action = $this->getAction() ;

        if (strtolower($action) == strtolower(WPST_OPT_IN))
            $actionlabel = get_option(WPST_OPTION_OPT_IN_LABEL) ;
        else if (strtolower($action) == strtolower(WPST_OPT_OUT))
            $actionlabel = get_option(WPST_OPTION_OPT_OUT_LABEL) ;
        else
            $actionalabel = ucwords($action) ;

        return $actionlabel ;
    }

    /**
     * Set the meet id property
     *
     * @param int $id - meet id
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id property
     *
     * @return int - meet id
     */
    function getMeetId()
    {
        return $this->__meetid ;
    }

    /**
     * Get the array of swimmer key and value pairs
     *
     * @return mixed - array of swimmer key value pairs
     */
    function _swimmerSelections($admin = false)
    {
        global $userdata ;

        get_currentuserinfo() ;

        //  AgeGroup options and labels 

        $s = array() ;

        $season = new SwimTeamSeason() ;
        $swimmer = new SwimTeamSwimmer() ;

        if ($admin)
            $filter = sprintf("%s.seasonid='%s' AND %s.swimmerid=%s.id AND %s.status='%s'",
                WPST_ROSTER_TABLE, $season->getActiveSeasonId(),
                WPST_ROSTER_TABLE, WPST_SWIMMERS_TABLE,
                WPST_ROSTER_TABLE, WPST_ACTIVE) ;
        else
            $filter = sprintf("(%s.contact1id = '%s' OR %s.contact2id = '%s') AND %s.seasonid='%s' AND %s.swimmerid=%s.id AND %s.status='%s'",
                WPST_SWIMMERS_TABLE, $userdata->ID,
                WPST_SWIMMERS_TABLE, $userdata->ID,
                WPST_ROSTER_TABLE, $season->getActiveSeasonId(),
                WPST_ROSTER_TABLE, WPST_SWIMMERS_TABLE,
                WPST_ROSTER_TABLE, WPST_ACTIVE) ;

        $swimmerIds = $swimmer->getAllSwimmerIds($filter) ;

        if (!empty($swimmerIds))
        {
            foreach ($swimmerIds as $swimmerId)
            {
                $swimmer->loadSwimmerById($swimmerId["swimmerid"]) ;
                $s[$swimmer->getFirstName() . " " .
                    $swimmer->getLastName()] = $swimmer->getId() ;
            }
        }

        return $s ;
    }

    /**
     * Get the array of event key and value pairs
     *
     * @return mixed - array of event key value pairs
     */
    function _eventSelections()
    {
        //  Event codes and labels 

        $allevents = array(
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE
           ,WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_LABEL =>
            WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE
        ) ;

        //  Only show the events that are set up for Opt-In/Opt-Out

        $optinoptoutevents = get_option(WPST_OPTION_OPT_IN_OPT_OUT_EVENTS) ;

        if (empty($optinoptoutevents)) $optinoptoutevents = $allevents ;

        foreach ($allevents as $key => $value)
        {
            if (in_array($value, $optinoptoutevents))
                $s[$key] = $value ;
        }

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
        $this->add_hidden_element("userid") ;
        $this->add_hidden_element("meetid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

        $swimmers = new FECheckBoxList("Swimmers", true, "200px", "120px");
        $swimmers->set_list_data($this->_swimmerSelections()) ;
        $this->add_element($swimmers) ;

        $this->__events = new FEActiveDIVRadioButtonGroup(
            $this->getActionLabel() . " Type", array(
                ucwords(WPST_FULL) => WPST_FULL
               ,ucwords(WPST_PARTIAL) => WPST_PARTIAL
            ), true) ;
        $this->__events->set_readonly(get_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE) != WPST_BOTH) ;
        $this->add_element($this->__events) ;

        $fullevents = new FECheckBoxList("Full Events",
            false, "200px", "120px");
        $fullevents->set_list_data($this->_eventSelections()) ;
        $fullevents->set_disabled(true) ;
        $this->add_element($fullevents) ;

        $partialevents = new FECheckBoxList("Partial Events",
            false, "200px", "120px");
        $partialevents->set_list_data($this->_eventSelections()) ;
        $this->add_element($partialevents) ;
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

        $this->set_hidden_element_value("meetid", $this->getMeetId()) ;
        $this->set_hidden_element_value("_action", $this->getAction()) ;

        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE) == WPST_PARTIAL)
            $this->set_element_value($this->getActionLabel() . " Type", WPST_PARTIAL) ;
        else
            $this->set_element_value($this->getActionLabel() . " Type", WPST_FULL) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $it = new SwimMeetInfoTable("Swim Meet Details") ;
        $it->setSwimMeetId($this->get_hidden_element_value("meetid")) ;
        $it->constructSwimMeetInfoTable() ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style("border: 1px solid") ;

        $td = html_td() ;
        $td->set_tag_attributes(array("rowspan" => "2",
            "valign" => "middle", "style" => "padding-right: 10px;")) ;
        $td->add($it) ;

        $table->add_row($this->element_label("Swimmers"),
            $this->element_form("Swimmers"), $td) ;
 
        $table->add_row(_HTML_SPACE, _HTML_SPACE) ;
        $table->add_row($this->element_label($this->getActionLabel() . " Type"),
            $this->element_form($this->getActionLabel() . " Type")) ;
        $table->add_row(_HTML_SPACE, _HTML_SPACE) ;

        //  Initialize the Full Events here instead of in 
        //  the form_init_data() method because it is a disabled
        //  widget and the values won't be preserved if the
        //  form has to be displayed again due to a validation
        //  problem.

        $this->set_element_value("Full Events", $this->_eventSelections()) ;

        //  Build the Magic Divs

        $this->__full_events_div = $this->__events->build_div(0) ;
        $this->__partial_events_div = $this->__events->build_div(1) ;
        $this->__full_events_div->add($this->element_form("Full Events")) ;
        $this->__partial_events_div->add($this->element_form("Partial Events")) ;
        $events = html_div(null, $this->__full_events_div, $this->__partial_events_div) ;

        $table->add_row("Events", $events) ;

        $td = html_td() ;
        $td->set_tag_attributes(array("colspan" => "3", "align" => "center")) ;
        $td->add(div_font8bold("This information replaces any existing information on a per swimmer basis.")) ;

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

        $optinoptouttype = $this->get_element_value($this->getActionLabel() . " Type") ;
        $partialevents = $this->get_element_value("Partial Events") ;

        if (($optinoptouttype == WPST_PARTIAL) && empty($partialevents))
        {
            $this->add_error($this->element_label($this->getActionLabel() . " Type"), "You must select at least one (1) event.");
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
        $success = true ;
        $allactionmsgs = array() ;

        //  Need the WordPress User Id from the global data

        global $userdata ;

        get_currentuserinfo() ;

        //  Loop through the swimmers

        $eventlabels = $this->_eventSelections() ;

        $optinoptouttype = $this->get_element_value($this->getActionLabel() . " Type") ;

        //  Use the available Event Selections for a Full Opt-In
        //  Opt-Out since the element is disabled and won't be passed
        //  through the form processor.

        if ($optinoptouttype == WPST_PARTIAL)
            $events = $this->get_element_value("Partial Events") ;
        else
            $events = $this->_eventSelections() ;

        $swimmerIds = $this->get_element_value("Swimmers") ;

        $meetid = $this->get_hidden_element_value("meetid") ;
        $action = $this->get_hidden_element_value("_action") ;

        if (strtolower($action) == strtolower(WPST_OPT_IN))
            $actionlabel = get_option(WPST_OPTION_OPT_IN_LABEL) ;
        else if (strtolower($action) == strtolower(WPST_OPT_OUT))
            $actionlabel = get_option(WPST_OPTION_OPT_OUT_LABEL) ;
        else
            $actionalabel = ucwords($action) ;

        $sm = new SwimMeetMeta() ;
        $swimmer = new SwimTeamSwimmer() ;

        $sm->setSwimMeetId($meetid) ;
        $sm->setUserId($userdata->ID) ;
        $sm->setParticipation($action) ;

        $meetdetails = SwimTeamTextMap::__MapMeetIdToText($meetid) ;

        foreach ($swimmerIds as $swimmerId)
        {
            $actionmsgs = array() ;
            $sm->setSwimmerId($swimmerId) ;
            $swimmer->setSwimmerId($swimmerId) ;
            $swimmer->loadSwimmerById($swimmerId) ;

            //  Clean up existing data!
            $prior = $sm->deleteSwimmerSwimMeetMeta() ;

            if ($prior)
            {
                $actionmsgs[] = sprintf("Previous record%s (%s) removed for swimmer %s %s <i>(%s - %s - %s)</i>.",
                    ($prior == 1 ? "" : "s"), $prior, 
                    $swimmer->getFirstName(), $swimmer->getLastName(),
                    $meetdetails["opponent"], $meetdetails["date"],
                    $meetdetails["location"]) ;
            }

            //  Add or Update meta data for each stroke

            foreach ($events as $event)
            {
                $sm->setEventCode($event) ;
                $success &= $sm->saveSwimmerSwimMeetMeta() ;
                $actionmsgs[] = sprintf("%s (%s) recorded for swimmer %s %s <i>(%s - %s - %s)</i>.",
                    $actionlabel, array_search($event, $eventlabels),
                    $swimmer->getFirstName(), $swimmer->getLastName(),
                    $meetdetails["opponent"], $meetdetails["date"],
                    $meetdetails["location"]) ;
            }

            //  Send e-mail confirmation ...
            $sm->sendConfirmationEmail($actionlabel, $actionmsgs,
                get_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_FORMAT)) ;

            $allactionmsgs = array_merge($allactionmsgs, $actionmsgs) ;
        }

        //  Construct action message

        if (!empty($actionmsgs))
        {
            $c = container() ;

            foreach($allactionmsgs as $actionmsg)
            {
                $c->add($actionmsg, html_br()) ;
            }

            $actionmsg = $c->render() ;
        }
        else
        {
            $actionmsg = sprintf("No %s actions recorded.", $actionlabel) ;
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
 * Construct the SwimMeet OptInOut form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimMeetOptInOutAdminForm extends WpSwimTeamSwimMeetOptInOutForm
{
    /**
     * Get the array of swimmer key and value pairs
     *
     * @return mixed - array of swimmer key value pairs
     */
    function _swimmerSelections()
    {
        return parent::_swimmerSelections(true) ;
    }
}

/**
 * Construct the Swim Meet Import Results form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimMeetForm
 */
class WpSwimTeamSwimMeetImportResultsForm extends WpSwimTeamSwimMeetForm
{
    var $__fileInfoTable ; 

    /** 
     * This method returns the InfoTable widget. 
     */ 
    function get_file_info_table()
    { 
        return $this->__fileInfoTable ; 
    } 

    /** 
     * This method creates an InfoTable widget which 
     * is used to display information regarding the  
     * uploaded file. 
     */ 
    function set_file_info_table($fileInfo)
    { 
        $it = new InfoTable("File Upload Summary", 400) ; 

        $lines = file($fileInfo['tmp_name']) ; 

        $it->add_row("Filename", $fileInfo['name']) ; 
        $it->add_row("Temporary Filename", $fileInfo['tmp_name']) ; 
        $it->add_row("File Size", filesize($fileInfo['tmp_name'])) ; 
        $it->add_row("Lines", count($lines)) ; 

        unset($lines) ; 

        $this->__fileInfoTable = &$it ; 
    } 

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element("meetid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

        $resultsfile = new FEFile("SDIF Filename", true, "400px") ; 
        $resultsfile->set_max_size(10240000000) ; 
        $resultsfile->set_temp_dir(ini_get('upload_tmp_dir')) ; 

        $this->add_element($resultsfile) ;

        //  Options on how to load results

        $match = new FERadioGroup("Match Swimmers", array(
            //ucwords(WPST_NONE) => WPST_NONE,
            ucwords(WPST_MATCH_SWIMMER_ID) => WPST_MATCH_SWIMMER_ID,
            ucwords(WPST_MATCH_SWIMMER_NAME) => WPST_MATCH_SWIMMER_NAME,
            ucwords(WPST_MATCH_SWIMMER_NAME_AND_ID) => WPST_MATCH_SWIMMER_NAME_AND_ID,
            //ucwords(WPST_PREVIOUS_PAGE) => WPST_PREVIOUS_PAGE
            ), true, "200px");
        $match->set_br_flag(true) ;
        $this->add_element($match) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_hidden_element_value("meetid", $this->getMeetId()) ;
        $this->set_hidden_element_value("_action", WPST_ACTION_IMPORT_RESULTS) ;
        $this->set_element_value("Match Swimmers", WPST_MATCH_SWIMMER_ID) ;
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
        $table->set_style("border: 0px solid") ;

        $table->add_row($this->element_label("SDIF Filename"),
            $this->element_form("SDIF Filename")) ;

        $table->add_row(_HTML_SPACE, _HTML_SPACE) ;
        
        $table->add_row($this->element_label("Match Swimmers"),
            $this->element_form("Match Swimmers")) ;

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
        //   Need to make sure file contains meet results.
        //
        //   What is a results file?
        //
        //   -  1 A0 record
        //   -  1 B1 record
        //   -  1 B2 record (optional)
        //   -  1 or more C1 records
        //   -  1 or more C2 records
        //   -  0 or more D0 records
        //   -  0 or more D3 records
        //   -  0 or more G0 records
        //   -  0 or more E0 records
        //   -  0 or more F0 records
        //   -  0 or more G0 records
        //   -  1 Z0 record
        //
        //  A results file can contain results for more than
        //  one team - so what to do if that happens?

        $legal_records = array("A0" => 1, "B1" => 1, "B2" => 0,
            "C1" => 1, "C2" => 0, "D0" => 0, "D3" => 0, "G0" => 0,
            "E0" => 0, "F0" => 0, "Z0" => 1) ;
 
        $record_counts = array("A0" => 0, "B1" => 0, "B2" => 0,
            "C1" => 0, "C2" => 0, "D0" => 0, "D3" => 0, "G0" => 0,
            "E0" => 0, "F0" => 0, "Z0" => 0) ;
 
        $file = $this->get_element("SDIF Filename") ; 
        $fileInfo = $file->get_file_info() ; 

        $lines = file($fileInfo['tmp_name']) ; 

        //  Scan the records to make sure there isn't something odd in the file

        $line_number = 1 ;

        foreach ($lines as $line)
        {
            if (trim($line) == WPST_NULL_STRING) continue ;

            $record_type = substr($line, 0, 2) ;

            if (!array_key_exists($record_type, $legal_records))
            {
                $this->add_error("SDIF File", sprintf("Invalid record \"%s\" encountered in SDIF file on line %s.", $record_type, $line_number)) ;
                return false ;
            }
            else
            {
                $record_counts[$record_type]++ ;
            }

            $line_number++ ;
        }

        //  Got this far, the file has the right records in it, do
        //  the counts make sense?
        
        foreach ($record_counts as $record_type => $record_count)
        {
            if ($record_count < $legal_records[$record_type])
            {
                $this->add_error("SDIF File", sprintf("Missing required \"%s\" record(s) in SDIF file.", $record_type)) ;
                return false ;
            }
        }

        unset($lines) ; 

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
        $meet = new SwimMeet() ;

        $meet->setMeetId($this->get_hidden_element_value("meetid")) ;

        $this->set_action_message("Results File \"" . 
            $this->get_element_value("SDIF Filename") .
            "\" successfully uploaded.") ; 
        $file = $this->get_element("SDIF Filename") ; 
        $fileInfo = $file->get_file_info() ; 

        $this->set_file_info_table($fileInfo) ; 

        $results = new SwimMeetResults() ;
        $results->setMeetId($meet->getMeetId()) ;

        //  Delete the file so we don't keep a lot of stuff around. 

        if (!unlink($fileInfo['tmp_name'])) 
            $this->add_error("CSV Filename", "Unable to remove uploaded results file."); 

        //$success = $meet->importSwimMeetResults() ;
        $success = true ;

        //  If successful, store the updated meet id in so it can be used later.

        if ($success) 
        {
            $meet->setMeetId($success) ;
            $this->set_action_message("Swim Meet results successfully imported.") ;
        }
        else
        {
            $this->set_action_message("Swim Meet results were not imported.") ;
        }

        return $success ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Upload" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Upload_Cancel() ;
    }
}

/**
 * Construct the Swim Meet Import Results form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamFileUploadForm
 */
class WpSwimTeamSwimMeetImportEventsForm extends WpSwimTeamSwimMeetFileUploadForm
{
}
?>
