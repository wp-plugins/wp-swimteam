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
 * @subpackage UserProfiles
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once('users.class.php') ;
require_once('forms.class.php') ;

/**
 * Construct the Add Age Group form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamUserProfileForm extends WpSwimTeamForm
{
    /**
     * Property to store the user Id during form processing
     */
    var $__id ;

    /**
     * Get the user id
     *
     * @return int - user id
     */
    function getId()
    {
        return $this->__id ;
    }

    /**
     * Set the user id
     *
     * @param int - user id
     */
    function setId($id)
    {
        $this->__id = $id ;
    }

    /**
     * Get the array of results key and value pairs
     *
     * @return mixed - array of results key value pairs
     */
    function _contactinfoSelections()
    {
        $i = array(ucfirst(WPST_PUBLIC) => WPST_PUBLIC
            ,ucfirst(WPST_PRIVATE) => WPST_PRIVATE
            ) ;

         return $i ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        global $userdata ;

        $this->add_hidden_element('_action') ;
        $this->add_hidden_element('UserId') ;

        $firstname = new FEText('First Name', true, '200px') ;
        $this->add_element($firstname) ;

        $lastname = new FEText('Last Name', true, '200px') ;
        $this->add_element($lastname) ;

        $emailaddress = new FEText('E-Mail Address', true, '300px') ;
        $this->add_element($emailaddress) ;

        $street1 = new FEText('Street 1', true, '250px') ;
        $this->add_element($street1) ;
        $street2 = new FEText('Street 2', false, '250px') ;
        $this->add_element($street2) ;
        $street3 = new FEText('Street 3', false, '250px') ;
        $this->add_element($street3) ;

        $city = new FEText('City', true, '200px') ;
        $this->add_element($city) ;

        //  How to handle the portion of the address which is
        //  much different for the US than the rest of the world.
 
        //  Check the options!
        
        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        if (empty($label))
            $label = WPST_DEFAULT_STATE_OR_PROVINCE_LABEL ;

        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        if ($geography == WPST_US_ONLY)
            $state = new FEUnitedStates($label, true, '200px') ;
        else
            $state = new FEText($label, true, '250px') ;

        $this->add_element($state) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        if (empty($label))
            $label = WPST_DEFAULT_POSTAL_CODE_LABEL ;

        if ($geography == WPST_US_ONLY)
            $postalcode = new FEZipcode($label, true, '75px') ;
        else
            $postalcode = new FEText($label, true, '200px') ;

        $this->add_element($postalcode) ;

        //  Country is handled - EU has a drop down,
        //  US is fixed and can't be changed, all others
        //  receive a text box.
 
        if ($geography == WPST_EU_ONLY)
            $country = new FEEuropeanUnion('Country', true, '150px') ;
        else
            $country = new FEText('Country', true, '200px') ;

        if ($geography == WPST_US_ONLY)
            $country->set_disabled(true) ;
        $this->add_element($country) ;

        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $primaryphone = new FEText($label, true, '150px') ;
        $this->add_element($primaryphone) ;

        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $secondaryphone = new FEText($label, false, '150px') ;
        $this->add_element($secondaryphone) ;

        $contactinfo = new FEListBox('Contact Information', true, '150px');
        $contactinfo->set_list_data($this->_contactinfoSelections()) ;

        $this->add_element($contactinfo) ;
 
        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        get_currentuserinfo() ;

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
            {
                //printf('%s -->  Mode:  %s  User Level %s  Permission %s<br>',
                    //__LINE__, $mode, (int)$userdata->user_level, WPST_EDITOR_PERMISSION) ;
                switch (get_option($oconst))
                {
                    case WPST_REQUIRED:
                        $this->add_element(new FEText($label, true, '250px')) ;
                        break ;

                    case WPST_OPTIONAL:
                        $this->add_element(new FEText($label, false, '250px')) ;
                        break ;

                    case WPST_EMAIL_OPTIONAL:
                        $this->add_element(new FEEmail($label, false, '250px')) ;
                        break ;

                    case WPST_EMAIL_REQUIRED:
                        $this->add_element(new FEEmail($label, true, '250px')) ;
                        break ;

                    case WPST_URL_OPTIONAL:
                        $this->add_element(new FEUrl($label, false, '250px')) ;
                        break ;

                    case WPST_URL_REQUIRED:
                        $this->add_element(new FEUrl($label, true, '250px')) ;
                        break ;

                    case WPST_YES_NO:
                    case WPST_NO_YES:
                        $this->add_element(new FEYesNoListBox($label, false, '75px')) ;
                        break ;

                    case WPST_CLOTHING_SIZE:
                        $this->add_element(new FEClothingSizeListBox($label, false, '150px')) ;
                        break ;

                    case WPST_DISABLED:
                    default:
                        break ;
                }
            }
            else
            {
                 $this->add_hidden_element($label) ;
            }
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
        global $userdata ;

        $userinfo = get_userdata($this->getId()) ;

        //  Set the first name field to what is stored in the WP profile
        $this->set_element_value('First Name', $userinfo->user_firstname) ;

        //  Set the last name field to what is stored in the WP profile
        $this->set_element_value('Last Name', $userinfo->user_lastname) ;

        //  Set the email address field to what is stored in the WP profile
        $this->set_element_value('E-Mail Address', $userinfo->user_email) ;

        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        if ($geography == WPST_US_ONLY)
            $this->set_element_value('Country', ucwords(WPST_US_ONLY)) ;

        //  Need to pass the WP UserId along to the next step
        $this->set_hidden_element_value('UserId', $this->getId()) ;

        //  Need to set the action so the next step knows
        //  what to do when called from a GUIDataList.
        //
        $this->set_hidden_element_value('_action', WPST_USERS_UPDATE_USER) ;

        $u = new SwimTeamUserProfile() ;

        if ($u->userProfileExistsByUserId($this->getId()))
        {
        //printf('%s::%s<br>', basename(__FILE__), __LINE__) ;
            $u->setUserId($this->getId()) ;
            $u->loadUserProfileByUserId() ;

            //  Initialize the form fields
            $this->set_element_value('Street 1', $u->getStreet1()) ;
            $this->set_element_value('Street 2', $u->getStreet2()) ;
            $this->set_element_value('Street 3', $u->getStreet3()) ;
            $this->set_element_value('City', $u->getCity()) ;

            $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
            if (empty($label))
                $label = WPST_DEFAULT_STATE_OR_PROVINCE_LABEL ;
            $this->set_element_value($label, $u->getStateOrProvince()) ;

            $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
            if (empty($label))
                $label = WPST_DEFAULT_POSTAL_CODE_LABEL ;
            $this->set_element_value($label, $u->getPostalCode()) ;

            $this->set_element_value('Country', $u->getCountry()) ;

            //printf('%s::%s<br>', basename(__FILE__), __LINE__) ;
            $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
            $this->set_element_value($label, $u->getPrimaryPhone()) ;
            //printf('%s::%s<br>', basename(__FILE__), __LINE__) ;
            $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
            $this->set_element_value($label, $u->getSecondaryPhone()) ;
            //printf('%s::%s<br>', basename(__FILE__), __LINE__) ;
            $this->set_element_value('Contact Information', $u->getContactInfo()) ;

        }

        //  Run through the user option fields regardless of whether the
        //  user is in the database or not to ensure proper the field are
        //  initialized.
 
        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        get_currentuserinfo() ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                if (($mode == WPST_USER) ||
                    ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                    $this->set_element_value($label,
                        $u->getUserOption($oconst)) ;
                else
                    $this->set_hidden_element_value($label,
                        $u->getUserOption($oconst)) ;
            }
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
        global $userdata ;

        $table = html_table($this->_width, 0, 4) ;
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('First Name'),
            $this->element_form('First Name')) ;

        $table->add_row($this->element_label('Last Name'),
            $this->element_form('Last Name')) ;

        $table->add_row($this->element_label('E-Mail Address'),
            $this->element_form('E-Mail Address')) ;

        $table->add_row($this->element_label('Street 1'),
            $this->element_form('Street 1')) ;

        $table->add_row($this->element_label('Street 2'),
            $this->element_form('Street 2')) ;

        $table->add_row($this->element_label('Street 3'),
            $this->element_form('Street 3')) ;

        $table->add_row($this->element_label('City'),
            $this->element_form('City')) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        if (empty($label))
            $label = WPST_DEFAULT_STATE_OR_PROVINCE_LABEL ;

        $table->add_row($this->element_label($label),
            $this->element_form($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        if (empty($label))
            $label = WPST_DEFAULT_POSTAL_CODE_LABEL ;

        $table->add_row($this->element_label($label),
            $this->element_form($label)) ;

        $table->add_row($this->element_label('Country'),
            $this->element_form('Country')) ;

        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $table->add_row($this->element_label($label),
            $this->element_form($label)) ;

        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $table->add_row($this->element_label($label),
            $this->element_form($label)) ;

        $table->add_row($this->element_label('Contact Information'),
            $this->element_form('Contact Information')) ;

        //  Show optional fields if they are enabled
 
        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        get_currentuserinfo() ;

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                {
                    $table->add_row($this->element_label($label),
                        $this->element_form($label)) ;
                }
            }
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
        $valid = true ;

        //  Make sure phone numbers are unique

        $plabel = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $p = $this->get_element_value($plabel) ;
        $slabel = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $s = $this->get_element_value($slabel) ;

        if ($p == $s)
        {
            $this->add_error($plabel, sprintf('%s is the same as the %s.', $plabel, $slabel)) ;
            $this->add_error($slabel, sprintf('%s is the same as the %s.', $slabel, $plabel)) ;
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
        global $userdata ;

        $u = new SwimTeamUserProfile() ;
        $u->setUserId($this->get_hidden_element_value('UserId')) ;
        $u->setStreet1($this->get_element_value('Street 1')) ;
        $u->setStreet2($this->get_element_value('Street 2')) ;
        $u->setStreet3($this->get_element_value('Street 3')) ;
        $u->setCity($this->get_element_value('City')) ;
        $u->setCity($this->get_element_value('City')) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        if (empty($label))
            $label = WPST_DEFAULT_STATE_OR_PROVINCE_LABEL ;
        $u->setStateOrProvince($this->get_element_value($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        if (empty($label))
            $label = WPST_DEFAULT_POSTAL_CODE_LABEL ;
        $u->setPostalCode($this->get_element_value($label)) ;

        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        if ($geography == WPST_US_ONLY)
            $u->setCountry(ucwords(WPST_US_ONLY)) ;
        else
            $u->setCountry($this->get_element_value('Country')) ;

        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $u->setPrimaryPhone($this->get_element_value($label)) ;
        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $u->setSecondaryPhone($this->get_element_value($label)) ;
        $u->setContactInfo($this->get_element_value('Contact Information')) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if (empty($options)) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        get_currentuserinfo() ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                $mode = get_option($mconst) ;
                $label = get_option($lconst) ;

                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                    $u->setUserOption($oconst, $this->get_element_value($label)) ;
                else
                    $u->setUserOption($oconst, $this->get_hidden_element_value($label)) ;
            }
        }

        $success = $u->saveUserProfile() ;

        //  Update the User Meta Data table with the first and last names
        $first = $this->get_element_value('First Name') ; 
        $last = $this->get_element_value('Last Name') ; 
        $success |= update_user_meta($u->getUserId(), 'first_name', $first) ;
        $success |= update_user_meta($u->getUserId(), 'last_name', $last) ;

        //  Update the Display Name in the WordPress user table
        $ID = $u->getUserId() ;
        $display_name = $first . ' ' . $last ;
        $user_email = $this->get_element_value('E-Mail Address') ; 
        $userdata = compact('ID', 'display_name', 'user_email') ;
        $success |= wp_update_user($userdata) ;

        //  If successful, store the added swimmer id in so it can be used later.
        if ($success) 
        {
            //$this->setId($success) ;
            $this->set_action_message('Swim Team profile successfully updated.') ;
        }
        else if ($success === null)
        {
            $this->set_action_message('Swim Team profile was not successfully updated.') ;
        }
        else
        {
            $this->set_action_message('No changes, Swim Team profile was not updated.') ;
        }

        //  Force success otherwise the form will be displayed again.

        return true ;
    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;
        //$container->add(html_h4($this->_action_message)) ;

        return $container ;
    }
}
?>
