<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: agegroups.forms.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage AgeGroups
 * @version $Revision: 1065 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 *
 */

require_once(WPST_PATH . 'class/agegroups.class.php') ;
require_once(WPST_PATH . 'class/forms.class.php') ;

/**
 * Construct the Add Age Group form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
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
     * div to hold the standard age group options
     */
    var $__standard_div ;

    /**
     * div to hold the combined age group options
     */
    var $__combined_div ;

    /**
     * div to hold the toggle of group types
     */
    var $__type_div ;

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
    function _genderSelections($mixed = false)
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

            if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
                (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
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

        //  Support mixed genders?
        if ($mixed) $g[ucfirst(WPST_GENDER_MIXED)] = WPST_GENDER_MIXED ;

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
            == strtoupper('WpSwimTeamAgeGroupDeleteForm')) ? true : false ;

        $this->add_hidden_element('agegroupid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        //  Set up an age range
        $agelist = array() ;
        for ($i = get_option(WPST_OPTION_MIN_AGE) ; $i <= get_option(WPST_OPTION_MAX_AGE) ; $i++)
            $agelist[$i] = $i ;

        //  Minimum Age Field
        $minage_s = new FEListBox('Standard Minimum Age', !$disabled_field, '75px');
        $minage_s->set_list_data($agelist) ;
        $minage_s->set_disabled($disabled_field) ;
        $this->add_element($minage_s);
		
        //  Maximum Age Field
        $maxage_s = new FEListBox('Standard Maximum Age', !$disabled_field, '75px');
        $maxage_s->set_list_data($agelist) ;
        $maxage_s->set_disabled($disabled_field) ;
        $this->add_element($maxage_s);
		
        //  Minimum Age Field
        $minage_c = new FEListBox('Combined Minimum Age', !$disabled_field, '75px');
        $minage_c->set_list_data($agelist) ;
        $minage_c->set_disabled($disabled_field) ;
        $this->add_element($minage_c);
		
        //  Maximum Age Field
        $maxage_c = new FEListBox('Combined Maximum Age', !$disabled_field, '75px');
        $maxage_c->set_list_data($agelist) ;
        $maxage_c->set_disabled($disabled_field) ;
        $this->add_element($maxage_c);
		
        //  Set up the ActiveDiv to toggle inputs based on type of age group
        $this->__type_div = new FEActiveDIVRadioButtonGroup(
            'Type', array(
                ucwords(WPST_STANDARD) => WPST_STANDARD
               ,ucwords(WPST_COMBINED) => WPST_COMBINED
            ), true) ;
        $this->__type_div->set_br_flag(false) ;
        $this->__type_div->set_disabled($disabled_field) ;
        $this->add_element($this->__type_div) ;

        //  Gender options and labels are set based on
        //  the plugin options

        $gender_s = new FEListBox('Standard Gender', !$disabled_field, '100px');
        $gender_s->set_list_data($this->_genderSelections(false)) ;
        $gender_s->set_disabled($disabled_field) ;

        $this->add_element($gender_s) ;

        $gender_c = new FEListBox('Combined Gender', !$disabled_field, '100px');
        $gender_c->set_list_data($this->_genderSelections(true)) ;
        $gender_c->set_disabled($disabled_field) ;

        $this->add_element($gender_c) ;

        //  Need to handle swimmer id when alpha-numeric
        //  swimmer ids are enabled.

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $swimmeridprefix = new FEText('Standard Age Group Swimmer Label Prefix', false, '75px') ;
            $swimmeridprefix->set_disabled($disabled_field) ;

            $this->add_element($swimmeridprefix) ;
        }
        else
        {
            $this->add_hidden_element('Standard Age Group Swimmer Label Prefix') ;
        }

        $this->add_hidden_element('Combined Age Group Swimmer Label Prefix') ;
 
        //  Handle Registration Fees
        $regfee = new FENumberPrice('Standard Registration Fee', !$disabled_field, '100px');
        $regfee->set_disabled($disabled_field) ;
        $this->add_element($regfee) ;
 
        $this->add_hidden_element('Combined Registration Fee') ;
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
        $this->set_hidden_element_value('_action', WPST_ACTION_ADD) ;
        $this->set_element_value('Standard Minimum Age', get_option(WPST_OPTION_MIN_AGE)) ;
        $this->set_element_value('Standard Maximum Age', get_option(WPST_OPTION_MAX_AGE)) ;
        $this->set_element_value('Combined Minimum Age', get_option(WPST_OPTION_MIN_AGE)) ;
        $this->set_element_value('Combined Maximum Age', get_option(WPST_OPTION_MAX_AGE)) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $this->set_element_value('Standard Gender', WPST_GENDER_MALE) ;
            $this->set_element_value('Combined Gender', WPST_GENDER_MALE) ;
        }
        else
        {
            $this->set_element_value('Standard Gender', WPST_GENDER_BOTH) ;
            $this->set_element_value('Combined Gender', WPST_GENDER_BOTH) ;
        }


        $this->set_element_value('Type', WPST_STANDARD) ;
        $this->set_element_value('Standard Registration Fee', get_option(WPST_OPTION_REG_FEE_AMOUNT)) ;
        $this->set_hidden_element_value('Combined Registration Fee', get_option(WPST_OPTION_REG_FEE_AMOUNT)) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $this->set_element_value('Standard Age Group Swimmer Label Prefix', WPST_NULL_STRING) ;
            //$this->set_element_value('Combined Age Group Swimmer Label Prefix', WPST_NULL_STRING) ;
            $this->set_hidden_element_value('Combined Age Group Swimmer Label Prefix', WPST_NULL_STRING) ;
        }
        else
        {
            $this->set_hidden_element_value('Standard Age Group Swimmer Label Prefix', WPST_NULL_STRING) ;
            $this->set_hidden_element_value('Combined Age Group Swimmer Label Prefix', WPST_NULL_STRING) ;
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
        $table = html_table($this->_width, 0, 4) ;
        $table->set_style('border: 1px solid') ;

        //  Build the Magic Divs

        $this->__standard_div = $this->__type_div->build_div(0) ;
        $stable = html_table($this->_width, 0, 4) ;
        $stable->add_row(sprintf('%s Minimum Age',
            $this->get_required_marker()), $this->element_form('Standard Minimum Age')) ;
        $stable->add_row(sprintf('%s Maximum Age',
            $this->get_required_marker()), $this->element_form('Standard Maximum Age')) ;
        $stable->add_row(sprintf('%s Gender',
            $this->get_required_marker()), $this->element_form('Standard Gender')) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $stable->add_row(sprintf('%s Swimmer Label Prefix',
                $this->get_required_marker()), $this->element_form('Standard Age Group Swimmer Label Prefix')) ;
        }

        $stable->add_row(sprintf('%s Standard Registration Fee',
            $this->get_required_marker()), $this->element_form('Standard Registration Fee')) ;
        $this->__standard_div->add($stable) ;

        $this->__combined_div = $this->__type_div->build_div(1) ;
        $ctable = html_table($this->_width, 0, 4) ;
        $ctable->add_row(sprintf('%s Minimum Age',
            $this->get_required_marker()), $this->element_form('Combined Minimum Age')) ;
        $ctable->add_row(sprintf('%s Maximum Age',
            $this->get_required_marker()), $this->element_form('Combined Maximum Age')) ;
        $ctable->add_row(sprintf('%s Gender',
            $this->get_required_marker()), $this->element_form('Combined Gender')) ;
        $this->__combined_div->add($ctable) ;

        $type = html_div(null, $this->__standard_div, $this->__combined_div) ;

        $table->add_row($this->element_label('Type'), $this->element_form('Type')) ;
        $td = html_td() ;
        $td->add($type) ;
        $td->set_tag_attribute('colspan', '2') ;
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

        //  Need to validate several fields ...

        //  Make sure position is unique

        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->setId($this->get_hidden_element_value('agegroupid')) ;

        $type = $this->get_element_value('Type') ;
        $ageGroup->setType($type) ;

        if ($type == WPST_STANDARD)
        {
            $ageGroup->setMinAge($this->get_element_value('Standard Minimum Age')) ;
            $ageGroup->setMaxAge($this->get_element_value('Standard Maximum Age')) ;
            $ageGroup->setGender($this->get_element_value('Standard Gender')) ;
            $ageGroup->setRegistrationFee($this->get_element_value('Standard Registration Fee')) ;
        }
        else
        {
            $ageGroup->setMinAge($this->get_element_value('Combined Minimum Age')) ;
            $ageGroup->setMaxAge($this->get_element_value('Combined Maximum Age')) ;
            $ageGroup->setGender($this->get_element_value('Combined Gender')) ;
            $ageGroup->setRegistrationFee($this->get_hidden_element_value('Combined Registration Fee')) ;
        }

        if ($type == WPST_COMBINED)
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value('Combined Age Group Swimmer Label Prefix')) ;
        }
        elseif ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_element_value('Standard Age Group Swimmer Label Prefix')) ;
        }
        else
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value('Standard Age Group Swimmer Label Prefix')) ;
        }

        //  Make sure the age group isn't in use - need to handle
        //  updates to existing age groups so decision isn't simple.

        if ($ageGroup->ageGroupExist())
        {
            $qr = $ageGroup->getQueryResult() ;
            if (is_null($ageGroup->getId()) || ($ageGroup->getId() != $qr['id']))
            {
                $this->add_error(ucwords($type) . ' Minimum Age', 'Age Group already exists.');
                $this->add_error(ucwords($type) . ' Maximum Age', 'Age Group already exists.');
                $this->add_error(ucwords($type) . ' Gender', 'Age Group already exists.');
                $valid = false ;
            }
        }

        if (((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID)) && $ageGroup->ageGroupPrefixInUse())
        {
            $this->add_error(ucwords($type) . ' Age Group Swimmer Label Prefix', 'Age Group Swimmer Label Prefix is already in use.');
            $valid = false ;
        }

        //  Make sure quantity is > 0

        if ($this->get_element_value(ucwords($type) . ' Minimum Age') > $this->get_element_value(ucwords($type) . ' Maximum Age'))
        {
            $this->add_error(ucwords($type) . ' Minimum Age', 'Minimum age must be less than or equal to Maximum age.') ;
            $this->add_error(ucwords($type) . ' Maximum Age', 'Maximum age must be greater than or equal to Maximum age.') ;
            $valid = false ;
        }
        
	    return $valid ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.administrator
     */
    function form_action()
    {
        $ageGroup = new SwimTeamAgeGroup() ;
        $ageGroup->setId($this->get_hidden_element_value('agegroupid')) ;

        $type = $this->get_element_value('Type') ;
        $ageGroup->setType($type) ;

        if ($type == WPST_STANDARD)
        {
            $gender = $this->get_element_value('Standard Gender') ;
            $ageGroup->setMinAge($this->get_element_value('Standard Minimum Age')) ;
            $ageGroup->setMaxAge($this->get_element_value('Standard Maximum Age')) ;
            $ageGroup->setGender($gender) ;
            $ageGroup->setRegistrationFee($this->get_element_value('Standard Registration Fee')) ;
        }
        else
        {
            $gender = $this->get_element_value('Combined Gender') ;
            $ageGroup->setMinAge($this->get_element_value('Combined Minimum Age')) ;
            $ageGroup->setMaxAge($this->get_element_value('Combined Maximum Age')) ;
            $ageGroup->setGender($gender) ;
            $ageGroup->setRegistrationFee($this->get_hidden_element_value('Combined Registration Fee')) ;
        }

        if ($type == WPST_COMBINED)
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value('Combined Age Group Swimmer Label Prefix')) ;
        }
        elseif ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_element_value('Standard Age Group Swimmer Label Prefix')) ;
        }
        else
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value('Standard Age Group Swimmer Label Prefix')) ;
        }

        if ($gender == WPST_GENDER_BOTH)
        {
            $ageGroup->setGender(WPST_GENDER_MALE) ;
            $success = $ageGroup->addAgeGroup() ;
            $ageGroup->setGender(WPST_GENDER_FEMALE) ;
            $success |= $ageGroup->addAgeGroup() ;
        }
        else
        {
            //$ageGroup->setGender($this->get_element_value('Gender')) ;
            $success = $ageGroup->addAgeGroup() ;
        }

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $ageGroup->setId($success) ;
            $this->set_action_message('Age Group successfully added.') ;
        }
        else
        {
            $this->set_action_message('Age Group was not successfully added.') ;
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
 * @author Mike Walsh <mpwalsh8@gmail.com>
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
                ,ucfirst(WPST_GENDER_MIXED) => WPST_GENDER_MIXED
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
        $this->set_hidden_element_value('agegroupid', $this->getId()) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_UPDATE) ;
        $this->set_element_value('Standard Minimum Age', $ageGroup->getMinAge()) ;
        $this->set_element_value('Standard Maximum Age', $ageGroup->getMaxAge()) ;
        $this->set_element_value('Combined Minimum Age', $ageGroup->getMinAge()) ;
        $this->set_element_value('Combined Maximum Age', $ageGroup->getMaxAge()) ;
        $this->set_element_value('Type', $ageGroup->getType()) ;
        $this->set_element_value('Standard Gender', $ageGroup->getGender()) ;
        $this->set_element_value('Combined Gender', $ageGroup->getGender()) ;
        $this->set_element_value('Standard Registration Fee', $ageGroup->getRegistrationFee()) ;
        $this->set_hidden_element_value('Combined Registration Fee', $ageGroup->getRegistrationFee()) ;

        if ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $this->set_element_value('Standard Age Group Swimmer Label Prefix',
                $ageGroup->getSwimmerLabelPrefix()) ;
        }
        else
        {
            $this->set_hidden_element_value('Standard Age Group Swimmer Label Prefix',
                WPST_NULL_STRING) ;
        }

        $this->set_hidden_element_value('Combined Age Group Swimmer Label Prefix', WPST_NULL_STRING) ;
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
        $ageGroup->setId($this->get_hidden_element_value('agegroupid')) ;

        $type = $this->get_element_value('Type') ;
        $ageGroup->setType($type) ;

        if ($type == WPST_STANDARD)
        {
            $gender = $this->get_element_value('Standard Gender') ;
            $ageGroup->setMinAge($this->get_element_value('Standard Minimum Age')) ;
            $ageGroup->setMaxAge($this->get_element_value('Standard Maximum Age')) ;
            $ageGroup->setGender($gender) ;
            $ageGroup->setRegistrationFee($this->get_element_value('Standard Registration Fee')) ;
        }
        else
        {
            $gender = $this->get_element_value('Combined Gender') ;
            $ageGroup->setMinAge($this->get_element_value('Combined Minimum Age')) ;
            $ageGroup->setMaxAge($this->get_element_value('Combined Maximum Age')) ;
            $ageGroup->setGender($gender) ;
            $ageGroup->setRegistrationFee($this->get_hidden_element_value('Combined Registration Fee')) ;
        }

        if ($type == WPST_COMBINED)
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value('Combined Age Group Swimmer Label Prefix')) ;
        }
        elseif ((get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_NUMERIC) ||
            (get_option(WPST_OPTION_SWIMMER_LABEL_FORMAT) == WPST_AGE_GROUP_PREFIX_WPST_ID))
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_element_value('Standard Age Group Swimmer Label Prefix')) ;
        }
        else
        {
            $ageGroup->setSwimmerLabelPrefix($this->get_hidden_element_value('Standard Age Group Swimmer Label Prefix')) ;
        }
        $success = $ageGroup->updateAgeGroup() ;

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $ageGroup->setId($success) ;
            $this->set_action_message('Age Group successfully updated.') ;
        }
        else
        {
            $this->set_action_message('Age Group was not updated.') ;
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
 * @author Mike Walsh <mpwalsh8@gmail.com>
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
        $this->set_hidden_element_value('agegroupid', $this->getId()) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_DELETE) ;
        $this->set_element_value('Standard Minimum Age', $ageGroup->getMinAge()) ;
        $this->set_element_value('Standard Maximum Age', $ageGroup->getMaxAge()) ;
        $this->set_element_value('Combined Minimum Age', $ageGroup->getMinAge()) ;
        $this->set_element_value('Combined Maximum Age', $ageGroup->getMaxAge()) ;
        $this->set_element_value('Standard Gender', $ageGroup->getGender()) ;
        $this->set_element_value('Combined Gender', $ageGroup->getGender()) ;
        $this->set_element_value('Type', $ageGroup->getType()) ;
        $this->set_element_value('Standard Registration Fee', $ageGroup->getRegistrationFee()) ;
        $this->set_hidden_element_value('Combined Registration Fee', $ageGroup->getRegistrationFee()) ;
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
        $ageGroup->setId($this->get_hidden_element_value('agegroupid')) ;
        $success = $ageGroup->deleteAgeGroup() ;

        if ($success) 
            $this->set_action_message('Age Group successfully deleted.') ;
        else
            $this->set_action_message('Age Group was not successfully deleted.') ;

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
