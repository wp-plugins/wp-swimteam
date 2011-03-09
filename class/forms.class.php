<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id$
 *
 * Form classes.  These classes manage the
 * entry and display of the various forms used
 * by the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for WpSwimTeam.
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage forms
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

/**
 * Include the Form Processing objects
 *
 */
include_once(PHPHTMLLIB_ABSPATH . "/form/includes.inc") ;

/**
 * Build a Clothing Size Select box
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package wp-SwimTeam
 * @subpackage FormProcessing
 * @see FEListBox
 *
 * @copyright LGPL - See LICENCE
 */
class FEClothingSizeListBox extends FEListBox
{
    /**
     * The Constructor
     *
     * @param label string - text label for the element
     * @param bool required - is this a required element
     * @param int required - element width in characters, pixels (px), percentage (%) or elements (em)
     * @param int required - element height in characters, pixels (px), percentage (%) or elements (em)
     *
     */
    function FEClothingSizeListBox($label, $required = TRUE, $width = NULL, $height = NULL)
    {
        $options = array(
             WPST_CLOTHING_SIZE_YS_LABEL => WPST_CLOTHING_SIZE_YS_VALUE
            ,WPST_CLOTHING_SIZE_YM_LABEL => WPST_CLOTHING_SIZE_YM_VALUE
            ,WPST_CLOTHING_SIZE_YL_LABEL => WPST_CLOTHING_SIZE_YL_VALUE
            ,WPST_CLOTHING_SIZE_YXL_LABEL => WPST_CLOTHING_SIZE_YXL_VALUE
            ,WPST_CLOTHING_SIZE_S_LABEL => WPST_CLOTHING_SIZE_S_VALUE
            ,WPST_CLOTHING_SIZE_M_LABEL => WPST_CLOTHING_SIZE_M_VALUE
            ,WPST_CLOTHING_SIZE_L_LABEL => WPST_CLOTHING_SIZE_L_VALUE
            ,WPST_CLOTHING_SIZE_XL_LABEL => WPST_CLOTHING_SIZE_XL_VALUE
            ,WPST_CLOTHING_SIZE_2XL_LABEL => WPST_CLOTHING_SIZE_2XL_VALUE
            ,WPST_CLOTHING_SIZE_3XL_LABEL => WPST_CLOTHING_SIZE_3XL_VALUE
            ,WPST_CLOTHING_SIZE_4XL_LABEL => WPST_CLOTHING_SIZE_4XL_VALUE
            ) ;

        parent::FEListBox($label, $required, $width, $height, $options) ;
    }
}

/**
 * Build a WP User Select list box
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package wp-SwimTeam
 * @subpackage FormProcessing
 * @see FEListBox
 *
 * @copyright LGPL - See LICENCE
 */
class FEWPUserListBox extends FEListBox
{
    /**
     * The Constructor
     *
     * @param label string - text label for the element
     * @param bool required - is this a required element
     * @param int required - element width in characters, pixels (px), percentage (%) or elements (em)
     * @param int required - element height in characters, pixels (px), percentage (%) or elements (em)
     *
     */
    function FEWPUserListBox($label, $required = TRUE, $width = NULL, $height = NULL, $allowNone = TRUE, $currentuseronly = false)
    {
        global $wpdb ;

        $db = new SwimTeamDBI() ;

        //  Retrieve the list of valid Wordpress User unique IDs
 
        if ($currentuseronly)
        {
            global $userdata ;
            get_currentuserinfo() ;

            $db->setQuery(sprintf("SELECT %susers.ID AS id FROM %susers WHERE %susers.ID=\"%s\"",
                $wpdb->prefix, $wpdb->prefix, $wpdb->prefix, $userdata->ID)) ;
        }
        else
            $db->setQuery(sprintf("SELECT %susers.ID AS id FROM %susers",
                $wpdb->prefix, $wpdb->prefix)) ;
        $db->runSelectQuery() ;

        $idList = $db->getQueryResults();

        //  Construct a list of ID building the array
        //  key based on the user's Wordpress meta data.

        $dataList = array() ;

        foreach ($idList as $id)
        {
            $u = get_userdata($id["id"]) ;

            $k = $u->last_name . ", "
                . $u->first_name . " (" . $u->user_login . ")" ;

            $dataList[$k] = $id["id"] ;
        }

        //  Sort the datalist based on the contructed keys
 
        ksort($dataList) ;

        //  Allow a "none" selection?
 
        if ($allowNone)
            $dataList = array_merge(array(__("None") => WPST_NULL_ID), $dataList) ;

        parent::FEListBox($label, $required, $width, $height, $dataList) ;
    }
}

/**
 * WpSwimTeam Form Base Class - extension of StandardFormContent
 *
 * @author Mike Walsh <mike_walsh@mindspirng.com>
 * @access public
 * @see StandardFormContent
 */
class WpSwimTeamSimpleForm extends FormContent
{
    /**
     * Constructor
     *
     * @param string width
     * @param cancel action
     *
     */
    function WpSwimTeamSimpleForm($width = "100%", $cancel_action = null)
    {
        //  Turn of default confirmation

        $this->set_confirm(false) ;
        
        //  Use a 'dagger' character to denote required fields.

        $this->set_required_marker('&#134;');

        //  Turn on the colons for all labels.

	    $this->set_colon_flag(true) ;

        //  Call the parent constructor

        $this->FormContent($width, $cancel_action) ;
    }
}

/**
 * WpSwimTeam Form Base Class - extension of StandardFormContent
 *
 * @author Mike Walsh <mike_walsh@mindspirng.com>
 * @access public
 * @see StandardFormContent
 */
class WpSwimTeamForm extends StandardFormContent
{
    /**
     * Build Form Help
     *
     * @return DIVtag
     */
    function get_form_help()
    {
        $div = html_div() ;
        $div->add('Basic help of this form should be documented here.') ;

        return $div ;
    }

    /**
     * Overload the standard action message function
     * so action messages are displayed in a consistent
     * Wordpress format.
     *
     * @param String message content
     */
    
    function set_action_message($message)
    {
        parent::set_action_message(html_div("updated fade", html_h4($message))) ;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Go" instead of "Save" and not
     * display the cancel button.
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Go()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Go")) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Filter" instead of "Save" and not
     * display the cancel button.
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Filter()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Filter")) ;

        return $div;
    }

    
    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Ok" instead of "Save" and not
     * display the cancel button.
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Ok()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Ok")) ;

        return $div;
    }


    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Login" instead of "Save" and not
     * display the cancel button.
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Login()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Login")) ;

        return $div;
    }


    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Login" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Login_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Login"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Upload" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Upload_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Upload"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Confirm" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Confirm_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Confirm"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Delete" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Delete_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Delete"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Open" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Open_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Open"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Close" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Close_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Close"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Add" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Add_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Add"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Register" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Register_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Register"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Unregister" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Unregister_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Unregister"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Lock" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Lock_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Lock"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Unlock" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Unlock_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Unlock"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Unlock" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Generate_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Generate"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Provide a mechanism to overload form_content_buttons() method
     * to have the button display "Assign" instead of "Save".
     *
     * @return HTMLTag object
     */
    function form_content_buttons_Assign_Cancel()
    {
        $div = new DIVtag(array("style" => "background-color: #eeeeee;".
            "padding-top:5px;padding-bottom:5px", "align"=>"center", "nowrap"),
            $this->add_action("Assign"), _HTML_SPACE, $this->add_cancel()) ;

        return $div;
    }

    /**
     * Constructor
     *
     * @param string - title
     * @param string - cancel page redirect
     * @param string - width of form
     */
    function WpSwimTeamForm($title, $cancel_action = null, $width = "100%")
    {
        parent::StandardFormContent($title, $cancel_action, $width) ;

        //  Turn of default confirmation

        $this->set_confirm(false) ;
        
        //  Use a 'dagger' character to denote required fields.

        $this->set_required_marker('&#134;');

        //  Turn on the colons for all labels.

	    $this->set_colon_flag(true) ;
    }

    //  Overload form_action() due to a bug with form confirmation
    //  as "Save" isn't handled when form confirmation is turned off.

    /**
     * This method handles the form action.
     * 
     * @return boolean TRUE = success
     *                 FALSE = failed.
     */
    function form_action() {
        switch ($this->get_action()) {
        case "Edit":
            return FALSE;
            break;
            
        case "Save":
        case "Login":
        case "Confirm":
        case "Register":
        case "Unregister":
            if ($this->has_confirm())
                return $this->confirm_action();
            else
                return true ;
            break;

        default:
            return FALSE;
            break;
        }
    }
}

/**
 * Construct the Swim Meet Import Results form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimMeetForm
 */
class WpSwimTeamFileUploadForm extends WpSwimTeamForm
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
        $uploadedfile = new FEFile($__uploadFileLabel, true, "400px") ; 
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
        $table = html_table($this->_width,0,4) ;
        $table->set_style("border: 0px solid") ;

        $table->add_row($this->element_label($__uploadFileLabelFilename),
            $this->element_form($__uploadFileLabelFilename)) ;

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
            $this->get_element_value(__uploadFileLabel) .
            "\" successfully uploaded.") ; 
        $file = $this->get_element(__uploadFileLabel) ; 
        $fileInfo = $file->get_file_info() ; 

        $this->set_file_info_table($fileInfo) ; 

        //  Delete the file so we don't keep a lot of stuff around. 

        if (!unlink($fileInfo['tmp_name'])) 
            $this->add_error($__uploadFileLabel, "Unable to remove uploaded file."); 

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
?>
