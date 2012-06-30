<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: swimmers.forms.class.php 921 2012-06-28 22:21:32Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Swimmers
 * @version $Revision: 921 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2012-06-28 18:21:32 -0400 (Thu, 28 Jun 2012) $
 *
 */

require_once('forms.class.php') ;
require_once('swimmers.class.php') ;
require_once('seasons.class.php') ;
require_once('swimmeets.class.php') ;
require_once('textmap.class.php') ;

define('CHECKBOX_SUFFIX', ' CheckBox') ;

/**
 * Construct the Add Swimmer form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerAddForm extends WpSwimTeamForm
{
    /**
     * id property - used to track the swimmer record
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
            $g = array(ucfirst(get_option(WPST_OPTION_GENDER_LABEL_MALE)) => WPST_GENDER_MALE
                ,ucfirst(get_option(WPST_OPTION_GENDER_LABEL_FEMALE)) => WPST_GENDER_FEMALE
            ) ;

         return $g ;
    }

    /**
     * Get the array of results key and value pairs
     *
     * @return mixed - array of results key value pairs
     */
    function _resultsSelections()
    {
        //  Gender options and labels are set based on
        //  the plugin options

        $g = array(ucfirst(WPST_PUBLIC) => WPST_PUBLIC
            ,ucfirst(WPST_PRIVATE) => WPST_PRIVATE
            ) ;

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
        global $userdata ;

        get_currentuserinfo() ;

        //  If the class constructing the form is for
        //  the delete operation, the fields are displayed
        //  but are set in the disabled state.
        $disabled_field = (strtoupper(get_class($this))
            == strtoupper('WpSwimTeamSwimmerDeleteForm')) ? true : false ;

        $this->add_hidden_element('swimmerid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        //  First Name Field
        $firstName = new FEText('First Name', !$disabled_field, '150px') ;
        $firstName->set_readonly($disabled_field) ;
        $this->add_element($firstName) ;

        //  Middle Name Field
        $middleName = new FEText('Middle Name', false, '150px') ;
        $middleName->set_readonly($disabled_field) ;
        $this->add_element($middleName) ;

        //  Nick Name Field
        $nickName = new FEText('Nick Name', false, '150px') ;
        $nickName->set_readonly($disabled_field) ;
        $this->add_element($nickName) ;

        //  Last Name Field
        $lastName = new FEText('Last Name', !$disabled_field, '150px') ;
        $lastName->set_readonly($disabled_field) ;
        $this->add_element($lastName) ;

        //  Gender options and labels are set based on
        //  the plugin options

        $gender = new FEListBox('Gender', !$disabled_field, '150px');
        $gender->set_list_data($this->_genderSelections()) ;
        $gender->set_readonly($disabled_field) ;

        $this->add_element($gender) ;
 
        //  Date of Birth field
        $dob = new FEDate('Date of Birth', !$disabled_field, null, null,
            'Fdy', date('Y'), date('Y') - get_option(WPST_OPTION_MAX_AGE) - 1) ;
        $dob->set_readonly($disabled_field) ;

        $this->add_element($dob) ;

        //  Contact1 field

        $contact1 = new FEWPUserListBox('Primary Contact', false, "250px") ;
        $contact1->set_readonly($disabled_field) ;

        $this->add_element($contact1) ;

        //  Contact2 field
        $contact2 = new FEWPUserListBox('Secondary Contact', false, "250px") ;
        $contact2->set_readonly($disabled_field) ;

        $this->add_element($contact2) ;

        //  Swimmer WP Id field
        $swmrWpId = new FEWPUserListBox('Web Site Id', false, "250px") ;
        $swmrWpId->set_readonly($disabled_field) ;

        $this->add_element($swmrWpId) ;

        $results = new FEListBox('Results', !$disabled_field, '150px');
        $results->set_list_data($this->_resultsSelections()) ;
        $results->set_readonly($disabled_field) ;

        $this->add_element($results) ;
 
        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        $oe = array() ;

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if (empty($mode)) $mode = WPST_DISABLED ;

            if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
            {
                switch (get_option($oconst))
                {
                    case WPST_REQUIRED:
                        $oe[$oc] = new FEText($label, !$disabled_field, '250px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_OPTIONAL:
                        $oe[$oc] = new FEText($label, false, '250px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_EMAIL_OPTIONAL:
                        $oe[$oc] = new FEEmail($label, false, '250px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_EMAIL_REQUIRED:
                        $oe[$oc] = new FEEmail($label, !$disabled_field, '250px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_URL_OPTIONAL:
                        $oe[$oc] = new FEUrl($label, false, '250px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_URL_REQUIRED:
                        $oe[$oc] = new FEUrl($label, !$disabled_field, '250px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_YES_NO:
                    case WPST_NO_YES:
                        $oe[$oc] = new FEYesNoListBox($label, !$disabled_field, '75px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_CLOTHING_SIZE:
                        $oe[$oc] = new FEClothingSizeListBox($label, !$disabled_field, '150px') ;
                        $oe[$oc]->set_readonly($disabled_field) ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_DISABLED:
                    case WPST_NULL_STRING:
                    default:
                        break ;
                }
            }
            else
            {
                 $this->add_hidden_element($label) ;
            }
        }

        //  Override age checks?  Only available to admin

        if ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION)
        {
            $override = new FEYesNoListBox('Override Age Range Checks',
                !$disabled_field, '75px', null, WPST_YES, WPST_NO);
            $override->set_readonly($disabled_field) ;
            $this->add_element($override) ;
        }
        else
        {
            $this->add_hidden_element('Override Age Range Checks') ;
        }
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_ACTION_ADD)
    {
        //  WP's global userdata
        global $userdata ;

        get_currentuserinfo() ;

        //  Initialize the form fields
        $this->set_hidden_element_value('_action', $action) ;
        $this->set_element_value('First Name', '') ;
        $this->set_element_value('Middle Name', '') ;
        $this->set_element_value('Nick Name', '') ;
        
        //  Set the last name field to what is stored in the WP profile
        $this->set_element_value('Last Name', $userdata->user_lastname) ;
        
        //  Set the contact fields based on current user
        $this->set_element_value('Primary Contact', $userdata->ID) ;
        $this->set_element_value('Secondary Contact', WPST_NULL_ID) ;
        $this->set_element_value('Web Site Id', WPST_NULL_ID) ;

        $this->set_element_value('Gender', WPST_GENDER_MALE) ;
        $this->set_element_value('Date of Birth', array('year' => date('Y'),
            'month' => date('m'), 'day' => date('d'))) ;
        $this->set_element_value('Results', WPST_PUBLIC) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if (empty($mode)) $mode = WPST_DISABLED ;

            if (($mode == WPST_USER) ||
                ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
            {
                switch (get_option($oconst))
                {
                    case WPST_EMAIL_REQUIRED:
                    case WPST_EMAIL_OPTIONAL:
                    case WPST_URL_REQUIRED:
                    case WPST_URL_OPTIONAL:
                        $this->set_element_value($label, WPST_NULL_STRING) ;
                        break ;

                    case WPST_CLOTHING_SIZE:
                        $this->set_element_value($label, WPST_CLOTHING_SIZE_YL_VALUE) ;
                        break ;

                    case WPST_YES_NO:
                        $this->set_element_value($label, WPST_YES) ;
                        break ;

                    case WPST_NO_YES:
                        $this->set_element_value($label, WPST_NO) ;
                        break ;

                    case WPST_DISABLED:
                    default:
                        break ;
                }
            }
        }
 
        //  Override age checks?  Only available to admin

        if ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION)
            $this->set_element_value('Override Age Range Checks', WPST_NO) ;
        else
            $this->set_hidden_element_value('Override Age Range Checks', WPST_NO) ;
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

        get_currentuserinfo() ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('First Name'),
            $this->element_form('First Name')) ;

        $table->add_row($this->element_label('Middle Name'),
            $this->element_form('Middle Name')) ;

        $table->add_row($this->element_label('Nick Name'),
            $this->element_form('Nick Name')) ;

        $table->add_row($this->element_label('Last Name'),
            $this->element_form('Last Name')) ;

        $table->add_row($this->element_label('Gender'),
            $this->element_form('Gender')) ;

        //$table->add_row($this->element_label('T-Shirt Size'),
            //$this->element_form('T-Shirt Size')) ;

        $table->add_row($this->element_label('Date of Birth'),
            $this->element_form('Date of Birth')) ;

        $table->add_row($this->element_label('Primary Contact'),
            $this->element_form('Primary Contact')) ;

        $table->add_row($this->element_label('Secondary Contact'),
            $this->element_form('Secondary Contact')) ;

        $table->add_row($this->element_label('Web Site Id'),
            $this->element_form('Web Site Id')) ;

        $table->add_row($this->element_label('Results'),
            $this->element_form('Results')) ;

        //  Show optional fields if they are enabled
 
        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

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

        //  Override age checks?  Only available to admin

        if ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION)
        {
            $table->add_row($this->element_label('Override Age Range Checks'),
                $this->element_form('Override Age Range Checks')) ;
        }

        $this->add_form_block(null, $table) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation($exact = false)
    {
        $valid = true ;
        $override = WPST_NO ;

        //  Override age checks?  Only available to admin

        global $userdata ;

        get_currentuserinfo() ;

        if ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION)
        {
            $override = $this->get_element_value('Override Age Range Checks') ;
        }

        $override = ($override == WPST_YES) ;

        //  Need to validate several fields ...

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->setFirstName($this->get_element_value('First Name')) ;
        $swimmer->setMiddleName($this->get_element_value('Middle Name')) ;
        $swimmer->setNickName($this->get_element_value('Nick Name')) ;
        $swimmer->setLastName($this->get_element_value('Last Name')) ;
        $swimmer->setContact1Id($this->get_element_value('Primary Contact')) ;
        $swimmer->setContact2Id($this->get_element_value('Secondary Contact')) ;
        $swimmer->setWPUserId($this->get_element_value('Web Site Id')) ;
        $swimmer->setGender($this->get_element_value('Gender')) ;
        $swimmer->setResults($this->get_element_value('Results')) ;
        $swimmer->setDateOfBirth($this->get_element_value('Date of Birth')) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                $mode = get_option($mconst) ;
                $label = get_option($lconst) ;

                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                    $swimmer->setSwimmerOption($oconst, $this->get_element_value($label)) ;
                else
                    $swimmer->setSwimmerOption($oconst, $this->get_hidden_element_value($label)) ;
            }
        }

        
        if ($swimmer->swimmerExist($exact))
        {
            $this->add_error('First Name', 'Swimmer already exists.');
            $this->add_error('Middle Name', 'Swimmer already exists.');
            $this->add_error('Nick Name', 'Swimmer already exists.');
            $this->add_error('Last Name', 'Swimmer already exists.');
            $this->add_error('Gender', 'Swimmer already exists.');
            $this->add_error('Date of Birth', 'Swimmer already exists.');
            $valid = false ;
        }

        //  Make sure the swimmer's age is within the team setting

        //  Too young?
        if ($swimmer->calculateAdjustedAge() < get_option(WPST_OPTION_MIN_AGE))
        {
            if (!$override)
            {
                $this->add_error('Date of Birth',
                    'Swimmer is too young, check date of birth.');
                $valid = false ;
            }
        }

        //  Too old?
        if ($swimmer->calculateAdjustedAge() > get_option(WPST_OPTION_MAX_AGE))
        {
            if (!$override)
            {
                $this->add_error('Date of Birth',
                    'Swimmer is too old, check date of birth.');
                $valid = false ;
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
        global $userdata ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->setFirstName($this->get_element_value('First Name')) ;
        $swimmer->setMiddleName($this->get_element_value('Middle Name')) ;
        $swimmer->setNickName($this->get_element_value('Nick Name')) ;
        $swimmer->setLastName($this->get_element_value('Last Name')) ;
        $swimmer->setContact1Id($this->get_element_value('Primary Contact')) ;
        $swimmer->setContact2Id($this->get_element_value('Secondary Contact')) ;
        $swimmer->setWPUserId($this->get_element_value('Web Site Id')) ;
        $swimmer->setGender($this->get_element_value('Gender')) ;
        $swimmer->setResults($this->get_element_value('Results')) ;
        $swimmer->setDateOfBirth($this->get_element_value('Date of Birth')) ;
        $swimmer->setStatus(WPST_INACTIVE) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        get_currentuserinfo() ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                $mode = get_option($mconst) ;
                $label = get_option($lconst) ;

                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                    $swimmer->setSwimmerOption($oconst, $this->get_element_value($label)) ;
                else
                    $swimmer->setSwimmerOption($oconst, $this->get_hidden_element_value($label)) ;
            }
        }

        $success = $swimmer->addSwimmer() ;

        //  If successful, store the added swimmer id in so it can be used later.

        if ($success) 
        {
            $swimmer->setId($success) ;

            //  Auto-Register swimmers?

            if (get_option(WPST_OPTION_AUTO_REGISTER) == WPST_YES)
            {
                //  WP's global userdata, need this to assign the contactId
                //  from the current user.  Not used visibly but if we ever need
                //  to know who registered a swimmer, there is a history.
 
                global $userdata ;

                get_currentuserinfo() ;

                $season = new SwimTeamSeason() ;
                $season->loadActiveSeason() ;

                $roster = new SwimTeamRoster() ;

                $roster->setSeasonId($season->getActiveSeasonId()) ;
                $roster->setSwimmerId($swimmer->getId()) ;
                $roster->setContactId($userdata->ID) ;
                $roster->setRosterStatus(WPST_ACTIVE) ;

                list($success, $updated) = $roster->registerSwimmer() ;

                //  If successful, store the added roster
                //  id in so it can be used later.

                if ($success && $updated) 
                {
                    $roster->setId($success) ;
                    $this->set_action_message('Swimmer successfully added and registered for the active season.') ;
                }
                else
                {
                    $this->set_action_message('Swimmer was added but not successfully registered for the current season.') ;
                }
            }
            else
            {
                $this->set_action_message('Swimmer successfully added.') ;
            }
        }
        else
        {
            $this->set_action_message('Swimmer was not successfully added.') ;
        }

        return true ;
    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

/**
 * Construct the Update Swimmer form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimmerAddForm
 */
class WpSwimTeamSwimmerUpdateForm extends WpSwimTeamSwimmerAddForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($action = WPST_ACTION_UPDATE)
    {
        global $userdata ;

        get_currentuserinfo() ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->loadSwimmerById($this->getId()) ;

        //  Initialize the form fields
        $this->set_hidden_element_value('swimmerid', $this->getId()) ;
        $this->set_hidden_element_value('_action', $action) ;
        $this->set_element_value('First Name', $swimmer->getFirstName()) ;
        $this->set_element_value('Middle Name', $swimmer->getMiddleName()) ;
        $this->set_element_value('Nick Name', $swimmer->getNickName()) ;
        $this->set_element_value('Last Name', $swimmer->getLastName()) ;
        $this->set_element_value('Primary Contact', $swimmer->getContact1Id()) ;
        $this->set_element_value('Secondary Contact', $swimmer->getContact2Id()) ;
        $this->set_element_value('Web Site Id', $swimmer->getWPUserId()) ;
        $this->set_element_value('Gender', $swimmer->getGender()) ;
        $this->set_element_value('Results', $swimmer->getResults()) ;
        $this->set_element_value('Date of Birth', $swimmer->getDateOfBirth()) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        get_currentuserinfo() ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                    $this->set_element_value($label, $swimmer->getSwimmerOption($oconst)) ;
                else
                    $this->set_hidden_element_value($label, $swimmer->getSwimmerOption($oconst)) ;
            }
        }
 
        //  Override age checks?  Only available to admin

        if ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION)
            $this->set_element_value('Override Age Range Checks', WPST_NO) ;
        else
            $this->set_hidden_element_value('Override Age Range Checks', WPST_NO) ;
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

        $override = false ;

        //  Override age checks?  Only available to admin

        global $userdata ;

        get_currentuserinfo() ;

        if ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION)
            $override = $this->get_element_value('Override Age Range Checks') ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->setId($this->get_hidden_element_value('swimmerid')) ;

        //  Make sure the swimmer record exists
 
        if ($swimmer->swimmerExistById())
        {
            //  Need to validate several fields ...

            $swimmer->setFirstName($this->get_element_value('First Name')) ;
            $swimmer->setMiddleName($this->get_element_value('Middle Name')) ;
            $swimmer->setNickName($this->get_element_value('Nick Name')) ;
            $swimmer->setLastName($this->get_element_value('Last Name')) ;
            $swimmer->setContact1Id($this->get_element_value('Primary Contact')) ;
            $swimmer->setContact2Id($this->get_element_value('Secondary Contact')) ;
            $swimmer->setWPUserId($this->get_element_value('Web Site Id')) ;
            $swimmer->setGender($this->get_element_value('Gender')) ;
            $swimmer->setResults($this->get_element_value('Results')) ;
            $swimmer->setDateOfBirth($this->get_element_value('Date of Birth')) ;
            //  How many swimmer options does this configuration support?

            $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

            //  Load the swimmer options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
                $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
                $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

                if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                    (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
                {
                    $mode = get_option($mconst) ;
                    $label = get_option($lconst) ;

                    if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                        $swimmer->setSwimmerOption($oconst, $this->get_element_value($label)) ;
                    else
                        $swimmer->setSwimmerOption($oconst, $this->get_hidden_element_value($label)) ;
                }
            }

            //  Need to prevent an update which ends up with duplicate
            //  swimmers.  Unusual situation but it could happen.

            if ($swimmer->swimmerExist(false))
            {
                $qr = $swimmer->getQueryResult() ;

                if ($qr['id'] != $swimmer->getId())
                {
                    $this->add_error('First Name', 'Swimmer already exists.');
                    $this->add_error('Middle Name', 'Swimmer already exists.');
                    $this->add_error('Nick Name', 'Swimmer already exists.');
                    $this->add_error('Last Name', 'Swimmer already exists.');
                    $this->add_error('Gender', 'Swimmer already exists.');
                    $this->add_error('Date of Birth', 'Swimmer already exists.');
                    $valid = false ;
                }
            }

            //  Make sure the swimmer's age is within the team setting

            //  Too young?
            if ($swimmer->calculateAdjustedAge() < get_option(WPST_OPTION_MIN_AGE))
            {
                if (!$override)
                {
                    $this->add_error('Date of Birth',
                        'Swimmer is too young, check date of birth.');
                    $valid = false ;
                }
            }

            //  Too old?
            if ($swimmer->calculateAdjustedAge() > get_option(WPST_OPTION_MAX_AGE))
            {
                if (!$override)
                {
                    $this->add_error('Date of Birth',
                        'Swimmer is too old, check date of birth.');
                    $valid = false ;
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
        global $userdata ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->setId($this->get_hidden_element_value('swimmerid')) ;
        $swimmer->loadSwimmerById() ;

        $swimmer->setFirstName($this->get_element_value('First Name')) ;
        $swimmer->setMiddleName($this->get_element_value('Middle Name')) ;
        $swimmer->setNickName($this->get_element_value('Nick Name')) ;
        $swimmer->setLastName($this->get_element_value('Last Name')) ;
        $swimmer->setContact1Id($this->get_element_value('Primary Contact')) ;
        $swimmer->setContact2Id($this->get_element_value('Secondary Contact')) ;
        $swimmer->setWPUserId($this->get_element_value('Web Site Id')) ;
        $swimmer->setGender($this->get_element_value('Gender')) ;
        $swimmer->setResults($this->get_element_value('Results')) ;
        $swimmer->setDateOfBirth($this->get_element_value('Date of Birth')) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        get_currentuserinfo() ;
        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                $mode = get_option($mconst) ;
                $label = get_option($lconst) ;

                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                    $swimmer->setSwimmerOption($oconst, $this->get_element_value($label)) ;
                else
                    $swimmer->setSwimmerOption($oconst, $this->get_hidden_element_value($label)) ;
            }
        }

        $success = $swimmer->updateSwimmer() ;

        //  If successful, store the added swimmer id in so it can be used later.

        if ($success) 
        {
            $swimmer->setId($success) ;
            $this->set_action_message('Swimmer successfully updated.') ;
        }
        else if ($success === null)
        {
            $this->set_action_message('Swimmer was not successfully updated.') ;
        }
        else
        {
            $this->set_action_message('No changes, swimmer was not updated.') ;
        }

        //  Force success otherwise the form will be displayed again.

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
 * Construct the Update Swimmer form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamSwimmerUpdateForm
 */
class WpSwimTeamSwimmerDeleteForm extends WpSwimTeamSwimmerUpdateForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        parent::form_init_data(WPST_ACTION_DELETE) ;
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
        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->setId($this->get_hidden_element_value('swimmerid')) ;
        $success = $swimmer->deleteSwimmer() ;

        if ($success) 
            $this->set_action_message('Swimmer successfully deleted.') ;
        else
            $this->set_action_message('Swimmer was not successfully deleted.') ;

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

/**
 * Construct the Swimmer OptInOut form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerOptInOutForm extends WpSwimTeamForm
{
    /**
     * action property - used to pass the action to the form processor
     */
    var $__action ;

    /**
     * swimmer id property - used to pass the swimmer id to the form processor
     */
    var $__swimmerid ;

    /**
     * div to hold the full strokes selection
     */
    var $__full_strokes_div ;

    /**
     * div to hold the partial strokes selection
     */
    var $__partial_strokes_div ;

    var $__strokes ;

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
     * Set the swimmer id property
     *
     * @param int $id - swimmer id
     */
    function setSwimmerId($id)
    {
        $this->__swimmerid = $id ;
    }

    /**
     * Get the swimmer id property
     *
     * @return int - swimmer id
     */
    function getSwimmerId()
    {
        return $this->__swimmerid ;
    }

    /**
     * Map the opponent swim club id into text for the GDL
     *
     * @return string - opponent text description
     */
    function __mapOpponentSwimClubIdToText($swimclubid)
    {
        //  Handle null id gracefully for non-dual meets

        if ($swimclubid == WPST_NULL_ID) return ucfirst(WPST_NONE) ;

        $swimclub = new SwimClubProfile() ;
        $swimclub->loadSwimClubBySwimClubId($swimclubid) ;

        return $swimclub->getClubOrPoolName() . ' ' . $swimclub->getTeamName() ;
    }

    /**
     * Get the array of swim meet key and value pairs
     *
     * @return mixed - array of swim meet key value pairs
     */
    function _swimmeetSelections($seasonid = null)
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
                $meet->loadSwimMeetByMeetId($meetId['meetid']) ;
    
                if ($meet->getMeetType() == WPST_DUAL_MEET)
                    $opponent = $this->__mapOpponentSwimClubIdToText(
                        $meet->getOpponentSwimClubId()) ;
                else
                    $opponent = $meet->getMeetDescription() ;
    
                $meetdate = date('D M j, Y', strtotime($meet->getMeetDateAsDate())) ;

                $m[sprintf('%s %s (%s)', $meetdate, $opponent,
                    ucfirst($meet->getLocation()))] = $meetId['meetid'] ;
            }
        }

        return $m ;
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

        $swimmer = new SwimTeamSwimmer() ;

        if ($admin)
            $filter = sprintf("status='%s'", WPST_ACTIVE) ;
        else
            $filter = sprintf("%s.contact1id = '%s' OR %s.contact2id = '%s' AND status='%s'",
            WPST_SWIMMERS_TABLE, $userdata->ID,
            WPST_SWIMMERS_TABLE, $userdata->ID,
            WPST_ACTIVE) ;

        $swimmerIds = $swimmer->getAllSwimmerIds($filter) ;

        if (!empty($swimmerIds))
        {
            foreach ($swimmerIds as $swimmerId)
            {
                $swimmer->loadSwimmerById($swimmerId['swimmerid']) ;
                $s[$swimmer->getFirstName() . ' ' .
                    $swimmer->getLastName()] = $swimmer->getId() ;
            }
        }

        return $s ;
    }

    /**
     * Get the array of stroke key and value pairs
     *
     * @return mixed - array of stroke key value pairs
     */
    function _strokeSelections()
    {
        //  Stroke codes and labels 

        $allstrokes = array(
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

        //  Only show the strokes that are set up for Opt-In/Opt-Out

        $optinoptoutstrokes = get_option(WPST_OPTION_OPT_IN_OPT_OUT_STROKES) ;

        if (empty($optinoptoutstrokes)) $optinoptoutstrokes = $allstrokes ;

        foreach ($allstrokes as $key => $value)
        {
            if (in_array($value, $optinoptoutstrokes))
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
        $this->add_hidden_element('userid') ;
        $this->add_hidden_element('swimmerid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        $swimmeets = new FECheckBoxList('Swim Meets', true, '450px', '120px');
        $swimmeets->set_list_data($this->_swimmeetSelections()) ;
        $swimmeets->enable_checkall(true) ;
        $this->add_element($swimmeets) ;

        $this->__strokes = new FEActiveDIVRadioButtonGroup(
            $this->getActionLabel() . ' Type', array(
                ucwords(WPST_FULL) => WPST_FULL
               ,ucwords(WPST_PARTIAL) => WPST_PARTIAL
            ), true) ;
        $this->__strokes->set_readonly(get_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE) != WPST_BOTH) ;
        $this->add_element($this->__strokes) ;

        $fullstrokes = new FECheckBoxList('Full Strokes',
            false, '200px', '120px');
        $fullstrokes->set_list_data($this->_strokeSelections()) ;
        $fullstrokes->set_disabled(true) ;
        $this->add_element($fullstrokes) ;

        $partialstrokes = new FECheckBoxList('Partial Strokes',
            false, '200px', '120px');
        $partialstrokes->set_list_data($this->_strokeSelections()) ;
        $this->add_element($partialstrokes) ;
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

        $this->set_hidden_element_value('swimmerid', $this->getSwimmerId()) ;
        $this->set_hidden_element_value('_action', $this->getAction()) ;

        if (get_option(WPST_OPTION_OPT_IN_OPT_OUT_MODE) == WPST_PARTIAL)
            $this->set_element_value($this->getActionLabel() . ' Type', WPST_PARTIAL) ;
        else
            $this->set_element_value($this->getActionLabel() . ' Type', WPST_FULL) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $it = new SwimTeamSwimmerProfileInfoTable('Swimmer Details') ;
        $it->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;
        $it->constructSwimmerProfile(true) ;

        $table = html_table($this->_width, 0, 4) ;
        $table->set_style('border: 1px solid') ;

        $td = html_td() ;
        $td->set_tag_attributes(array('rowspan' => '4',
            'valign' => 'middle', 'style' => 'padding-right: 10px;')) ;
        $td->add($it) ;

        $table->add_row(_HTML_SPACE, _HTML_SPACE, $td) ;
        $table->add_row($this->element_label($this->getActionLabel() . ' Type'),
            $this->element_form($this->getActionLabel() . ' Type')) ;
        $table->add_row(_HTML_SPACE) ;

        //  Initialize the Full Strokes here instead of in 
        //  the form_init_data() method because it is a disabled
        //  widget and the values won't be preserved if the
        //  form has to be displayed again due to a validation
        //  problem.

        $this->set_element_value('Full Strokes', $this->_strokeSelections()) ;

        //  Build the Magic Divs

        $this->__full_strokes_div = $this->__strokes->build_div(0) ;
        $this->__partial_strokes_div = $this->__strokes->build_div(1) ;
        $this->__full_strokes_div->add($this->element_form('Full Strokes')) ;
        $this->__partial_strokes_div->add($this->element_form('Partial Strokes')) ;
        $strokes = html_div(null, $this->__full_strokes_div, $this->__partial_strokes_div) ;

        $table->add_row('Strokes', $strokes) ;

        $table->add_row(_HTML_SPACE) ;

        $td = html_td() ;
        $td->set_tag_attributes(array('colspan' => '2',
            'valign' => 'middle', 'style' => 'padding-right: 10px;')) ;
        $td->add($this->element_form('Swim Meets')) ;
        $table->add_row($this->element_label('Swim Meets'), $td) ;
 
        $table->add_row(_HTML_SPACE) ;

        $td = html_td() ;
        $td->set_tag_attributes(array('colspan' => '3', 'align' => 'center')) ;
        $td->add(div_font8bold('This information replaces any existing information on a per swimmer basis.')) ;

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

        $optinoptouttype = $this->get_element_value($this->getActionLabel() . ' Type') ;
        $partialstrokes = $this->get_element_value('Partial Strokes') ;

        if (($optinoptouttype == WPST_PARTIAL) && empty($partialstrokes))
        {
            $this->add_error($this->element_label($this->getActionLabel() . ' Type'), 'You must select at least one (1) stroke.');
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
        $actionmsgs = array() ;

        //  Need the WordPress User Id from the global data

        global $userdata ;

        get_currentuserinfo() ;

        //  Loop through the swimmers

        $strokelabels = $this->_strokeSelections() ;

        $optinoptouttype = $this->get_element_value($this->getActionLabel() . ' Type') ;

        //  Use the available Event Selections for a Full Opt-In
        //  Opt-Out since the element is disabled and won't be passed
        //  through the form processor.

        if ($optinoptouttype == WPST_PARTIAL)
            $strokes = $this->get_element_value('Partial Strokes') ;
        else
            $strokes = $this->_strokeSelections() ;

        $meetIds = $this->get_element_value('Swim Meets') ;

        $swimmerid = $this->get_hidden_element_value('swimmerid') ;
        $action = $this->get_hidden_element_value('_action') ;

        if (strtolower($action) == strtolower(WPST_OPT_IN))
            $actionlabel = get_option(WPST_OPTION_OPT_IN_LABEL) ;
        else if (strtolower($action) == strtolower(WPST_OPT_OUT))
            $actionlabel = get_option(WPST_OPTION_OPT_OUT_LABEL) ;
        else
            $actionalabel = ucwords($action) ;

        $sm = new SwimMeetMeta() ;
        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->loadSwimmerById($swimmerid) ;

        $sm->setSwimmerId($swimmerid) ;
        $sm->setUserId($userdata->ID) ;
        $sm->setParticipation($action) ;
        $sm->setEventId(WPST_NULL_ID) ;

        //  This is wrong - needs to be fixed.
        foreach ($meetIds as $meetId)
        {
            $sm->setSwimMeetId($meetId) ;

            //  Clean up existing data!
            $prior = $sm->deleteSwimmerSwimMeetMeta() ;

            $meetdetails = SwimTeamTextMap::__mapMeetIdToText($meetId) ;

            if ($prior)
            {
                $actionmsgs[] = sprintf('Previous record%s (%s) removed for %s %s <i>(%s - %s - %s)</i>.',
                    ($prior == 1 ? '' : 's'), $prior, 
                    $swimmer->getFirstName(), $swimmer->getLastName(),
                    $meetdetails['opponent'], $meetdetails['date'],
                    $meetdetails['location']) ;
            }

            //  Add or Update meta data for each stroke

            foreach ($strokes as $stroke)
            {
                $sm->setStrokeCode($stroke) ;
                $success &= $sm->saveSwimmerSwimMeetMeta() ;
                $actionmsgs[] = sprintf('%s (%s) recorded for swimmer %s %s <i>(%s - %s - %s)</i>.',
                    $actionlabel, array_search($stroke, $strokelabels),
                    $swimmer->getFirstName(), $swimmer->getLastName(),
                    $meetdetails['opponent'], $meetdetails['date'],
                    $meetdetails['location']) ;
            }
        }

        
        //  Send e-mail confirmation ...
        $sm->sendConfirmationEmail($actionlabel, $actionmsgs,
            get_option(WPST_OPTION_OPT_IN_OPT_OUT_EMAIL_FORMAT)) ;

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
            $actionmsg = sprintf('No %s actions recorded.', $actionlabel) ;
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
 * Construct the Swimmer OptInOut form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerOptInOutAdminForm extends WpSwimTeamSwimmerOptInOutForm
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
 * Construct the Global Swimmer Update form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerGlobalUpdateForm extends WpSwimTeamForm
{
    /**
     * Get the array of results key and value pairs
     *
     * @return mixed - array of results key value pairs
     */
    function _resultsSelections()
    {
        //  Results options and labels are set based on
        //  the plugin options

        $g = array(ucfirst(WPST_PUBLIC) => WPST_PUBLIC
            ,ucfirst(WPST_PRIVATE) => WPST_PRIVATE
            ) ;

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
        global $userdata ;

        get_currentuserinfo() ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        //  Results Field
        $results = new FEListBox('Results', false, '150px');
        $results->set_list_data($this->_resultsSelections()) ;
        $this->add_element($results) ;
        $results_cb = new FECheckbox('Results CheckBox', '') ;
        $this->add_element($results_cb) ;
 
        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        $oe = array() ;

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
            {
                $oe[$oc . CHECKBOX_SUFFIX] = new FECheckbox($label . CHECKBOX_SUFFIX, '') ;
                $this->add_element($oe[$oc . CHECKBOX_SUFFIX]) ;

                switch (get_option($oconst))
                {
                    case WPST_REQUIRED:
                        $oe[$oc] = new FEText($label, false, '250px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_OPTIONAL:
                        $oe[$oc] = new FEText($label, false, '250px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_EMAIL_OPTIONAL:
                        $oe[$oc] = new FEEmail($label, false, '250px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_EMAIL_REQUIRED:
                        $oe[$oc] = new FEEmail($label, false, '250px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_URL_OPTIONAL:
                        $oe[$oc] = new FEUrl($label, false, '250px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_URL_REQUIRED:
                        $oe[$oc] = new FEUrl($label, false, '250px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_YES_NO:
                    case WPST_NO_YES:
                        $oe[$oc] = new FEYesNoListBox($label, false, '75px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_CLOTHING_SIZE:
                        $oe[$oc] = new FEClothingSizeListBox($label, false, '150px') ;
                        $this->add_element($oe[$oc]) ;
                        break ;

                    case WPST_DISABLED:
                    case WPST_NULL_STRING:
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
        //  Initialize the form fields
        $this->set_hidden_element_value('_action', WPST_ACTION_GLOBAL_UPDATE) ;
        $this->set_element_value('Results', WPST_PUBLIC) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;
            $label_cb = $label . CHECKBOX_SUFFIX ;

            if (($mode == WPST_USER) ||
                ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
            {
                $this->set_element_value($label_cb, false) ;
                switch (get_option($oconst))
                {
                    case WPST_URL:
                    case WPST_EMAIL:
                    case WPST_REQUIRED:
                    case WPST_OPTIONAL:
                        $this->set_element_value($label, WPST_NULL_STRING) ;
                        break ;

                    case WPST_CLOTHING_SIZE:
                        $this->set_element_value($label, WPST_CLOTHING_SIZE_YL_VALUE) ;
                        break ;

                    case WPST_YES_NO:
                        $this->set_element_value($label, WPST_YES) ;
                        break ;

                    case WPST_NO_YES:
                        $this->set_element_value($label, WPST_NO) ;
                        break ;

                    case WPST_DISABLED:
                    default:
                        break ;
                }
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

        get_currentuserinfo() ;

        $table = html_table($this->_width,0,4) ;
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('Results CheckBox'),
            $this->element_label('Results'),
            $this->element_form('Results')) ;

        //  Show optional fields if they are enabled
 
        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            $mode = get_option($mconst) ;
            $label = get_option($lconst) ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                if (($mode == WPST_USER) || ((int)$userdata->user_level >= WPST_EDITOR_PERMISSION))
                {
                    $table->add_row($this->element_form($label . CHECKBOX_SUFFIX),
                        $this->element_label($label),
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
        $valid = false ;

        //  Need at least one checkbox selected to do anything ...

        $valid |= ($this->get_element_value('Results' . CHECKBOX_SUFFIX) !== null) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                $mode = get_option($mconst) ;
                $label = get_option($lconst) ;
                $label_cb = $label . CHECKBOX_SUFFIX ;

                $valid |= ($this->get_element_value($label_cb) !== null) ;
            }
        }

        //  If no checkboxes selected, note an error on all fields
        if (!$valid)
        {
            $this->add_error('Results', 'At least one field must be selected for update.');
            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
                $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
                $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

                if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                    (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
                {
                    $mode = get_option($mconst) ;
                    $label = get_option($lconst) ;
                    $label_cb = $label . ' Checkbox' ;

                    $this->add_error($label, 'At least one field must be selected for update.');
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
        $updates = 0 ;
        $meta = new SwimTeamOptionMeta() ;

        //  Need at least one checkbox selected to do anything ...

        //$valid |= ($this->get_element_value('Results' . CHECKBOX_SUFFIX) !== null) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $mconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE') ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if ((get_option($oconst, WPST_DISABLED) != WPST_DISABLED) &&
                (get_option($oconst, WPST_DISABLED) != WPST_NULL_STRING))
            {
                $mode = get_option($mconst) ;
                $label = get_option($lconst) ;
                $label_cb = $label . CHECKBOX_SUFFIX ;

                if ($this->get_element_value($label_cb) !== null)
                {
                    $meta->setOptionMetaKey($oconst) ;
                    $meta->setOptionMetaValue($this->get_element_value($label)) ;
                    $updates += $meta->globalUpdateOptionMetaByKey() ;
                }
            }
        }

        $this->set_action_message(sprintf('Updated %s swimmer records.', $updates)) ;

	    return true ;

    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}

?>
