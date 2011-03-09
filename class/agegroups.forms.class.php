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
 * @subpackage AgeGroups
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once("agegroups.class.php") ;
require_once("forms.class.php") ;

/**
 * Construct the Add Age Group form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamAgeGroupAddForm extends WpSwimTeamForm
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
     * Get the array of gender key and value pairs
     *
     * @return mixed - array of gender key value pairs
     */
    function _genderSelections()
    {
        //  Gender options and labels are set based on
        //  the plugin options

        if (get_option(WPST_OPTION_GENDER) == WPST_GENDER_MALE)
            $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) => WPST_GENDER_MALE) ;
        else if (get_option(WPST_OPTION_GENDER) == WPST_GENDER_FEMALE)
            $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) => WPST_GENDER_FEMALE) ;
        else
        {
            //  Can't use the "both" short cut when using alpha-numeric
            //  age group prefixes as it would end up with both genders
            //  having the same swimmer id prefix and that would be bad.

            if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
                WPST_AGE_GROUP_PREFIX_NUMERIC) ||
                (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
                WPST_AGE_GROUP_PREFIX_WPST_ID))
            {
                $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) => WPST_GENDER_MALE
                    ,ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) => WPST_GENDER_FEMALE
                ) ;
            }
            else
            {
                $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) => WPST_GENDER_MALE
                    ,ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) => WPST_GENDER_FEMALE
                    ,ucfirst(WPST_GENDER_BOTH) => WPST_GENDER_BOTH
                ) ;
            }
        }

         return $g ;
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
        $disabled_field = (strtoupper(get_class($this))
            == strtoupper("WpSwimTeamAgeGroupDeleteForm")) ? true : false ;

        $this->add_hidden_element("agegroupid") ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element("_action") ;

        //  Set up an age range
        $agelist = array() ;
        for ($i = get_option(WPST_OPTION_MIN_AGE) ; $i <= get_option(WPST_OPTION_MAX_AGE) ; $i++)
            $agelist[$i] = $i ;

        //  Minimum Age Field
        $minage = new FEListBox("Minimum Age", !$disabled_field, "75px");
        $minage->set_list_data($agelist) ;
        $minage->set_disabled($disabled_field) ;

        $this->add_element($minage);
		
        //  Maximum Age Field
        $maxage = new FEListBox("Maximum Age", !$disabled_field, "75px");
        $maxage->set_list_data($agelist) ;
        $maxage->set_disabled($disabled_field) ;
        $this->add_element($maxage);
		
        //  Gender options and labels are set based on
        //  the plugin options

        $gender = new FEListBox("Gender", !$disabled_field, "100px");
        $gender->set_list_data($this->_genderSelections()) ;
        $gender->set_disabled($disabled_field) ;

        $this->add_element($gender) ;

        //  Need to handle swimmer id when alpha-numeric
        //  swimmer ids are enabled.

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $swimmeridprefix = new FEText("Age Group Swimmer Label Prefix",
                !$disabled_field, "75px") ;
            $swimmeridprefix->set_disabled($disabled_field) ;

            $this->add_element($swimmeridprefix) ;
        }
        else
        {
            $this->add_hidden_element("Age Group Swimmer Label Prefix") ;
        }
 
        $regfee = new FENumberPrice("Registration Fee", !$disabled_field, "100px");
        $this->add_element($regfee) ;
        $regfee->set_disabled($disabled_field) ;
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
        $this->set_hidden_element_value("_action", WPST_AGT_ADD_AGE_GROUP) ;
        $this->set_element_value("Minimum Age", WPST_AGT_MIN_AGE) ;
        $this->set_element_value("Maximum Age", WPST_AGT_MAX_AGE) ;
        $this->set_element_value("Gender", WPST_AGT_GENDER_BOTH) ;
        $this->set_element_value("Registration Fee", get_option(WPST_OPTION_REG_FEE_AMOUNT)) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
            $this->set_element_value("Age Group Swimmer Label Prefix", WPST_NULL_STRING) ;
        else
            $this->set_hidden_element_value("Age Group Swimmer Label Prefix", WPST_NULL_STRING) ;
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

        $table->add_row($this->element_label("Minimum Age"),
            $this->element_form("Minimum Age")) ;

        $table->add_row($this->element_label("Maximum Age"),
            $this->element_form("Maximum Age")) ;

        $table->add_row($this->element_label("Gender"),
            $this->element_form("Gender")) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $table->add_row($this->element_label("Age Group Swimmer Label Prefix"),
                $this->element_form("Age Group Swimmer Label Prefix")) ;
        }

        $table->add_row($this->element_label("Registration Fee"),
            $this->element_form("Registration Fee")) ;

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

        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->setId($this->get_hidden_element_value("agegroupid")) ;
        $ageGroup->setMinAge($this->get_element_value("Minimum Age")) ;
        $ageGroup->setMaxAge($this->get_element_value("Maximum Age")) ;
        $ageGroup->setGender($this->get_element_value("Gender")) ;
        $ageGroup->setRegistrationFee($this->get_element_value("Registration Fee")) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_element_value("Age Group Swimmer Label Prefix")) ;
        }
        else
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value("Age Group Swimmer Label Prefix")) ;
        }

        //  Make sure the age group isn't in use - need to handle
        //  updates to existing age groups so decision isn't simple.

        if ($ageGroup->ageGroupExist())
        {
            $qr = $ageGroup->getQueryResult() ;
            if (is_null($ageGroup->getId()) || ($ageGroup->getId() != $qr["id"]))
            {
                $this->add_error("Minimum Age", "Age Group already exists.");
                $this->add_error("Maximum Age", "Age Group already exists.");
                $this->add_error("Gender", "Age Group already exists.");
                $valid = false ;
            }
        }

        if (((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID)) && $ageGroup->ageGroupPrefixInUse())
        {
            $this->add_error("Age Group Swimmer Label Prefix", "Age Group Swimmer Label Prefix is already in use.");
            $valid = false ;
        }

        //  Make sure quantity is > 0

        if ($this->get_element_value("Minimum Age") >= $this->get_element_value("Maximum Age"))
        {
            $this->add_error("Minimum Age", "Minimum age must be less than Maximum age.") ;
            $this->add_error("Maximum Age", "Maximum age must be greater than Maximum age.") ;
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
        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->setMinAge($this->get_element_value("Minimum Age")) ;
        $ageGroup->setMaxAge($this->get_element_value("Maximum Age")) ;
        $ageGroup->setRegistrationFee($this->get_element_value("Registration Fee")) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
            $ageGroup->setSwimmerLabelPrefix($this->get_element_value("Age Group Swimmer Label Prefix")) ;
        else
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value("Age Group Swimmer Label Prefix")) ;

        $gender = $this->get_element_value("Gender") ;

        if ($gender == WPST_GENDER_BOTH)
        {
            $ageGroup->setGender(WPST_GENDER_MALE) ;
            $success = $ageGroup->addAgeGroup() ;
            $ageGroup->setGender(WPST_GENDER_FEMALE) ;
            $success |= $ageGroup->addAgeGroup() ;
        }
        else
        {
            $ageGroup->setGender($this->get_element_value("Gender")) ;
            $success = $ageGroup->addAgeGroup() ;
        }

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $ageGroup->setId($success) ;
            $this->set_action_message("Age Group successfully added.") ;
        }
        else
        {
            $this->set_action_message("Age Group was not successfully added.") ;
        }

        return $success ;
    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Update AgeGroup form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamAgeGroupAddForm
 */
class WpSwimTeamAgeGroupUpdateForm extends WpSwimTeamAgeGroupAddForm
{
    /**
     * Get the array of gender key and value pairs
     *
     * @return mixed - array of gender key value pairs
     */
    function _genderSelections()
    {
        //  Gender options and labels are set based on
        //  the plugin options

        if (get_option(WPST_OPTION_GENDER) == WPST_GENDER_MALE)
            $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) => WPST_GENDER_MALE) ;
        else if (get_option(WPST_OPTION_GENDER) == WPST_GENDER_FEMALE)
            $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) => WPST_GENDER_FEMALE) ;
        else
            $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) => WPST_GENDER_MALE
                ,ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) => WPST_GENDER_FEMALE
            ) ;

         return $g ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->loadAgeGroupById($this->getId()) ;

        //  Initialize the form fields
        $this->set_hidden_element_value("agegroupid", $this->getId()) ;
        $this->set_hidden_element_value("_action", WPST_AGT_UPDATE_AGE_GROUP) ;
        $this->set_element_value("Minimum Age", $ageGroup->getMinAge()) ;
        $this->set_element_value("Maximum Age", $ageGroup->getMaxAge()) ;
        $this->set_element_value("Gender", $ageGroup->getGender()) ;
        $this->set_element_value("Registration Fee", $ageGroup->getRegistrationFee()) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $this->set_element_value("Age Group Swimmer Label Prefix",
                $ageGroup->getSwimmerLabelPrefix()) ;
        }
        else
        {
            $this->set_hidden_element_value("Age Group Swimmer Label Prefix",
                WPST_NULL_STRING) ;
        }
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->setId($this->get_hidden_element_value("agegroupid")) ;
        $ageGroup->setMinAge($this->get_element_value("Minimum Age")) ;
        $ageGroup->setMaxAge($this->get_element_value("Maximum Age")) ;
        $ageGroup->setGender($this->get_element_value("Gender")) ;
        $ageGroup->setRegistrationFee($this->get_element_value("Registration Fee")) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) ==
            WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_element_value("Age Group Swimmer Label Prefix")) ;
        }
        else
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value("Age Group Swimmer Label Prefix")) ;
        }

        $success = $ageGroup->updateAgeGroup() ;

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $ageGroup->setId($success) ;
            $this->set_action_message("Age Group successfully updated.") ;
        }
        else
        {
            $this->set_action_message("Age Group was not updated.") ;
        }

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
 * Construct the Update AgeGroup form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamAgeGroupUpdateForm
 */
class WpSwimTeamAgeGroupDeleteForm extends WpSwimTeamAgeGroupUpdateForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->loadAgeGroupById($this->getId()) ;

        //  Initialize the form fields
        $this->set_hidden_element_value("agegroupid", $this->getId()) ;
        $this->set_hidden_element_value("_action", WPST_AGT_DELETE_AGE_GROUP) ;
        $this->set_element_value("Minimum Age", $ageGroup->getMinAge()) ;
        $this->set_element_value("Maximum Age", $ageGroup->getMaxAge()) ;
        $this->set_element_value("Gender", $ageGroup->getGender()) ;
        $this->set_element_value("Registration Fee", $ageGroup->getRegistrationFee()) ;
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
        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->setId($this->get_hidden_element_value("agegroupid")) ;
        $success = $ageGroup->deleteAgeGroup() ;

        if ($success) 
            $this->set_action_message("Age Group successfully deleted.") ;
        else
            $this->set_action_message("Age Group was not successfully deleted.") ;

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
?>
