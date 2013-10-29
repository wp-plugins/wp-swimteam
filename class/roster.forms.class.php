<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: roster.forms.class.php 1008 2013-09-28 17:55:11Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Swimmers
 * @version $Revision: 1008 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2013-09-28 13:55:11 -0400 (Sat, 28 Sep 2013) $
 *
 */

require_once('forms.class.php') ;
require_once('swimmers.class.php') ;
require_once('seasons.class.php') ;
require_once('roster.class.php') ;

/**
 * Construct the Register Swimmer form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerRegisterForm extends WpSwimTeamForm
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
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element('swimmerid') ;

        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        //  First Name Field
        $firstName = new FEText('First Name', true, '150px') ;
        $firstName->set_readonly(true) ;
        $this->add_element($firstName) ;

        //  Last Name Field
        $lastName = new FEText('Last Name', true, '150px') ;
        $lastName->set_readonly(true) ;
        $this->add_element($lastName) ;

        //  Season Field
        $season = new FEText('Active Season', true, '150px') ;
        $season->set_readonly(true) ;
        $this->add_element($season) ;

        //  Terms of Use Field

        $option = get_option(WPST_OPTION_REG_TOU_URL) ;

        if (!empty($option))
        {
            $tou = new FECheckBox('Terms of Use',
                'I have read and agree to the Swim Team Terms of Use') ;
            $this->add_element($tou) ;
        }

        //  Billing Policy

        $option = get_option(WPST_OPTION_REG_FEE_URL) ;

        if (!empty($option))
        {
            $billing = new FECheckBox('Billing Policy',
                'I have read and agree to the Swim Team Billing Policy') ;
            $this->add_element($billing) ;
        }

        //  Override Age Checks?  Only certain users can do this.

        if (current_user_can('edit_others_posts'))
        {
            $override = new FECheckBox('Override Age Checks',
                'Override Age Checks') ;
            $this->add_element($override) ;
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
        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->loadSwimmerById($this->getId()) ;

        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        //  Initialize the form fields
        $this->set_hidden_element_value('swimmerid', $this->getId()) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_REGISTER) ;
        $this->set_element_value('First Name', $swimmer->getFirstName()) ;
        $this->set_element_value('Last Name', $swimmer->getLastName()) ;
        $this->set_element_value('Active Season', $season->getSeasonLabel()) ;
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
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('First Name'),
            $this->element_form('First Name')) ;

        $table->add_row($this->element_label('Last Name'),
            $this->element_form('Last Name')) ;

        $table->add_row($this->element_label('Active Season'),
            $this->element_form('Active Season')) ;

        $option = get_option(WPST_OPTION_REG_TOU_URL) ;

        if (!empty($option))
        {
            $tr = html_tr() ;
            $td = html_td() ;

            $td->add($this->element_form('Terms of Use'), 
            html_a($option, html_img(WPST_PLUGIN_URL .
                '/images/icons/paper.png', null, null, null,
                'Swim Team Terms of Use'), null, '_new',
                'Swim Team Terms of Use')) ;
            $td->set_colspan(2) ;
            $tr->add($td) ;
            $table->add_row($tr) ;
        }

        $option = get_option(WPST_OPTION_REG_FEE_URL) ;

        if (!empty($option))
        {
            $tr = html_tr() ;
            $td = html_td() ;

            $td->add($this->element_form('Billing Policy'), 
            html_a($option, html_img(WPST_PLUGIN_URL .
                '/images/icons/paper.png', null, null, null,
                'Swim Team Billing Policy'), null, '_new',
                'Swim Team Billing Policy')) ;
            $td->set_colspan(2) ;
            $tr->add($td) ;
            $table->add_row($tr) ;
        }

        //  Override Age Checks?  Only certain users can do this.

        if (current_user_can('edit_others_posts'))
        {
            $tr = html_tr() ;
            $td = html_td() ;

            $td->add($this->element_form('Override Age Checks')) ;
            $td->set_colspan(2) ;
            $tr->add($td) ;
            $table->add_row($tr) ;
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
        $override = false ;

        //  Make sure swimmer isn't already registered
        //  for the season - can't register twice!
 
        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;

        $swimmer = new SwimTeamSwimmer() ;
        $swimmer->loadSwimmerById($this->get_hidden_element_value('swimmerid')) ;

        //  Override Age Checks?  Only certain users can do this.

        if (current_user_can('edit_others_posts'))
        {
            $override = ($this->get_element_value('Override Age Checks') === WPST_NULL_STRING) ;
        }
 
        //  Three cases to deal with:
        //
        //  1)  Swimmer isn't registered at all
        //  2)  Swimmer is registered but inactive
        //  3)  Swimmer is registered and active
        //  4)  Swimmer is too old
        //  5)  Swimmer is too young
        //
        //  Only the 3rd case fails validation

        $valid = true ;

        if ($roster->isSwimmerRegistered())
        {
            //  Load the complete roster record
            $roster->loadRosterBySeasonIdAndSwimmerId() ;

            //  Already active?  Invalid
            if ($roster->getRosterStatus() == WPST_ACTIVE)
            {
                $valid = false ;
                $this->add_error('Active Season', 'Swimmer is already registered.') ;
            }
        }

        //  Make sure the swimmer's age is within the team setting

        //  Too young?
        if (($swimmer->getAgeGroupAge() < get_option(WPST_OPTION_MIN_AGE)) && !$override)
        {
            $this->add_error('Active Season', 'Swimmer is too young, check date of birth.');
            $valid = false ;
        }

        //  Too old?
        if (($swimmer->getAgeGroupAge() > get_option(WPST_OPTION_MAX_AGE)) && !$override)
        {
            $this->add_error('Active Season', 'Swimmer is too old, check date of birth.');
            $valid = false ;
        }

        //  Terms of Use?
 
        $option = get_option(WPST_OPTION_REG_TOU_URL) ;

        if (!empty($option))
        {

            if (is_null($this->get_element_value('Terms of Use')))
            {
                $valid = false ;
                $this->add_error('Terms of Use', 'You must agree to the Swim Team Terms of Use.') ;
            }
        }

        //  Registration Fee Policy?
 
        $option = get_option(WPST_OPTION_REG_FEE_URL) ;

        if (!empty($option))
        {

            if (is_null($this->get_element_value('Billing Policy')))
            {
                $valid = false ;
                $this->add_error('Billing Policy', 'You must agree to the Swim Team Billing Policy.') ;
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
        //  WP's global userdata, need this to assign the contactId
        //  from the current user.  Not used visibly but if we ever need
        //  to know who registered a swimmer, there is a history.
 
        global $userdata ;

        get_currentuserinfo() ;

        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;
        $roster->setContactId($userdata->ID) ;
        $roster->setRosterStatus(WPST_ROSTER_SWIMMER_ACTIVE) ;

        $success = $roster->registerSwimmer() ;

        //  If successful, store the added roster id in so it can be used later.
        if ($success) 
        {
            $roster->setId($success) ;
            $this->set_action_message('Swimmer successfully registered.') ;
        }
        else
        {
            $this->set_action_message('Swimmer was not successfully registered.') ;
        }

        return $success ;
    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Register' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Register_Cancel() ;
    }
}

/**
 * Construct the Register Swimmer form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerUnregisterForm extends WpSwimTeamSwimmerRegisterForm
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
        $this->set_hidden_element_value('_action', WPST_ACTION_UNREGISTER) ;
    }

    /**
     * This method gets called after the FormElement data has
     * passed the validation.  This enables you to validate the
     * data against some backend mechanism, say a DB.
     *
     */
    function form_backend_validation()
    {
        //  Make sure swimmer is registered for the season.
        //  You can't unregister a swimmer who isn't registered.
 
        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;

        //  Three cases to deal with:
        //
        //  1)  Swimmer isn't registered at all
        //  2)  Swimmer is registered but inactive
        //  3)  Swimmer is registered and active
        //
        //  Only the 3rd case passes validation

        $valid = false ;

        if ($roster->isSwimmerRegistered())
        {
            //  Load the complete roster record
            $roster->loadRosterBySeasonIdAndSwimmerId() ;

            //  Already active?  Invalid
            if ($roster->getRosterStatus() == WPST_ACTIVE)
            {
                $valid = true ;
            }
            else
            {
                $this->add_error('Active Season', 'Swimmer is not registered.') ;
            }
        }
        else
        {
            $this->add_error('Active Season', 'Swimmer is not registered.') ;
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
        //  WP's global userdata, need this to assign the contactId
        //  from the current user.  Not used visibly but if we ever need
        //  to know who registered a swimmer, there is a history.
 
        global $userdata ;

        get_currentuserinfo() ;

        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;
        $roster->loadRosterBySeasonIdAndSwimmerId() ;
        $roster->setRosterStatus(WPST_INACTIVE) ;

        $success = $roster->updateRoster() ;

        $roster->sendConfirmationEmail(WPST_ACTION_UNREGISTER) ;

        //  If successful, store the added roster id in so it can be used later.
        if ($success) 
        {
            $roster->setId($success) ;
            $this->set_action_message('Swimmer successfully unregistered.') ;
        }
        else
        {
            $this->set_action_message('Swimmer was not successfully unregistered.') ;
        }
 
        //  Now that the swimmer has been removed from the roster,
        //  need to update their global status which is presented
        //  as part of the 'My Swimmers' functionality.

        //$swimmer = new SwimTeamSwimmer() ;
        //$swimmer->loadSwimmerById($roster->getSwimmerId()) ;
        //$swimmer->setStatus(WPST_INACTIVE) ;
        //$swimmer->updateSwimmer() ;

        return $success ;
    }

    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Register' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Unregister_Cancel() ;
    }
}

/**
 * Construct the Label Swimmer form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerLabelForm extends WpSwimTeamSwimmerUnregisterForm
{
    function form_init_elements()
    {
        parent::form_init_elements() ;

        //  Season Field

        $swimmerlabel = new FEText('Swimmer Label', true, '150px') ;

        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        if ($season->getSwimmerLabels() == WPST_FROZEN)
            $swimmerlabel->set_readonly(true) ;

        $this->add_element($swimmerlabel) ;
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
        $this->set_hidden_element_value('_action', WPST_ACTION_ASSIGN_LABEL) ;

        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;
        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->getId()) ;

        $roster->loadRosterBySeasonIdAndSwimmerId() ;
        $this->set_element_value('Swimmer Label', $roster->getSwimmerLabel()) ;
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
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('First Name'),
            $this->element_form('First Name')) ;

        $table->add_row($this->element_label('Last Name'),
            $this->element_form('Last Name')) ;

        $table->add_row($this->element_label('Active Season'),
            $this->element_form('Active Season')) ;

        $table->add_row($this->element_label('Swimmer Label'),
            $this->element_form('Swimmer Label')) ;

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
        //  Make sure swimmer is registered for the season.
        //  You can't unregister a swimmer who isn't registered.
 
        $season = new SwimTeamSeason() ;
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;

        //  Three cases to deal with:
        //
        //  1)  Swimmer isn't registered at all
        //  2)  Swimmer is registered but inactive
        //  3)  Swimmer is registered and active
        //
        //  Only the 3rd case passes validation

        $valid = false ;

        if ($roster->isSwimmerRegistered())
        {
            //  Load the complete roster record
            $roster->loadRosterBySeasonIdAndSwimmerId() ;

            //  Already active?  Invalid
            if ($roster->getRosterStatus() == WPST_ACTIVE)
            {
                $roster->setSwimmerLabel($this->get_element_value('Swimmer Label')) ;

                //  Make sure Swimmer Label isn't being used

                if ($roster->isSwimmerLabelAssigned())
                {
                    $this->add_error('Swimmer Label', 'Swimmer Label is already assigned.') ;
                }
                else
                {
                    $valid = true ;
                }
            }
            else
            {
                $this->add_error('Active Season', 'Swimmer is not registered.') ;
            }
        }
        else
        {
            $this->add_error('Active Season', 'Swimmer is not registered.') ;
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
        $season->loadActiveSeason() ;

        $roster = new SwimTeamRoster() ;

        $roster->setSeasonId($season->getActiveSeasonId()) ;
        $roster->setSwimmerId($this->get_hidden_element_value('swimmerid')) ;
        $roster->loadRosterBySeasonIdAndSwimmerId() ;
        $roster->setSwimmerLabel($this->get_element_value('Swimmer Label')) ;

        $success = $roster->updateRoster() ;

        //  If successful, set a message

        if ($success) 
        {
            $roster->setId($success) ;
            $this->set_action_message('Swimmer Label successfully assigned.') ;
        }
        else
        {
            $this->set_action_message('Swimmer Label was not successfully assigned.') ;
        }
 
        return $success ;
    }

    /**
     * Return container on success
     *
     * @return container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Register' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Assign_Cancel() ;
    }
}

/**
 * Construct the Export Roster form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamExportRosterForm extends WpSwimTeamForm
{
    /**
     * exports
     */
    var $__exports = array() ;

    /**
     * action messages
     */
    var $__actionmsgs = array() ;

    /**
     * id property - used to track the swimmer record
     */

    var $__swimmerId ;

    /**
     * Set the Swimmer Id property
     */
    function setSwimmerId($swimmerId)
    {
        $this->__swimmerId = $swimmerId ;
    }

    /**
     * Get the Swimmer Id property
     */
    function getSwimmerId()
    {
        return $this->__swimmerId ;
    }

    /**
     * generated CSV
     */
    var $__csv ;

    /**
     * generated RE1
     */
    var $__re1 ;

    /**
     * generated HY3
     */
    var $__hy3 ;

    /**
     * generated SDIF
     */
    var $__sdif ;

    /**
     * Set up CSV generation
     *
     * @param mixed $csv - CSV report object
     */
    function __initializeReportGeneratorCSV(&$csv)
    {
        $csv->setFirstName(true) ;
        $csv->setMiddleName(true) ;
        $csv->setNickName(true) ;
        $csv->setLastName(true) ;
        $csv->setBirthDate(true) ;
        $csv->setAge(true) ;
        $csv->setAgeGroup(true) ;
        $csv->setGender(true) ;
        $csv->setStatusFilter(true) ;
        $csv->setStatusFilterValue(WPST_ACTIVE) ;
        $csv->setSwimmerLabel(true) ;
        $csv->setResults(true) ;
        $csv->setPrimaryContact(true) ;
        $csv->setSecondaryContact(true) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $csv->setOptionalField($oconst, true) ;
            }
        }
    }

    /**
     * Set up RE1 generation
     *
     * @param mixed $re1 - RE1 report object
     */
    function __initializeReportGeneratorRE1(&$re1)
    {
        $re1->setFirstName(true) ;
        $re1->setMiddleName(false) ;
        $re1->setNickName(false) ;
        $re1->setLastName(true) ;
        $re1->setBirthDate(true) ;
        $re1->setAge(false) ;
        $re1->setAgeGroup(false) ;
        $re1->setGender(true) ;
        $re1->setStatusFilter(true) ;
        $re1->setStatusFilterValue(WPST_ACTIVE) ;
        $re1->setSwimmerLabel(true) ;
        $re1->setResults(false) ;
        $re1->setPrimaryContact(false) ;
        $re1->setSecondaryContact(false) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $re1->setOptionalField($oconst, false) ;
            }
        }
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element('swimmerid') ;
 
        //  This is used to remember the action
        //  which originated from the GUIDataList.
 
        $this->add_hidden_element('_action') ;

        //  Gender
        $gender = new FECheckBoxList('Gender', true, '150px');
        $gender->set_list_data(array(
             ucfirst(WPST_GENDER_MALE) => WPST_GENDER_MALE
            ,ucfirst(WPST_GENDER_FEMALE) => WPST_GENDER_FEMALE
            ,ucfirst(WPST_GENDER_BOTH) => WPST_GENDER_BOTH
        )) ;
        $gender = new FERadioGroup('Gender', array(
             ucfirst(WPST_GENDER_MALE) => WPST_GENDER_MALE
            ,ucfirst(WPST_GENDER_FEMALE) => WPST_GENDER_FEMALE
            ,ucfirst(WPST_GENDER_BOTH) => WPST_GENDER_BOTH
        ), true) ;
        $gender->set_br_flag(true) ;
        $this->add_element($gender) ;

        //  Export Format
        $format = new FECheckBoxList('File Format', true, '150px');
        $format->set_list_data(array(
             ucfirst(WPST_CSV) => WPST_CSV
            ,ucfirst(WPST_SDIF) => WPST_SDIF
            ,ucfirst(WPST_HY3) => WPST_HY3
            ,ucfirst(WPST_RE1) => WPST_RE1
        )) ;
        $format->set_style_attribute('border', '0px') ;
        $format->set_style_attribute('background-color', '#eee') ;
        $this->add_element($format) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_hidden_element_value('swimmerid', $this->getSwimmerId()) ;
        $this->set_hidden_element_value('_action', WPST_ACTION_EXPORT_ROSTER) ;

        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        //  Initialize the form fields
        $this->set_element_value('Gender', $options->getGender()) ;
        //$this->set_element_value('File Format', WPST_CSV) ;
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
        $table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('Gender'),
            $this->element_form('Gender')) ;

        $table->add_row($this->element_label('File Format'),
            $this->element_form('File Format')) ;

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
        $this->__exports = $this->get_element_value('File Format') ;

        $action = $this->get_hidden_element_value('_action') ;
        $actionmsgs = &$this->__actionmsgs ;

        $formats = $this->get_element_value('File Format') ;

        //  Export each format requested

        foreach ($formats as $format)
        {
            switch ($format)
            {
                case WPST_CSV:
                    $this->_form_action_export_csv() ;
                    break ;

                case WPST_RE1:
                    $this->_form_action_export_re1() ;
                    break ;

                case WPST_HY3:
                    $this->_form_action_export_hy3() ;
                    break ;

                case WPST_SDIF:
                    $this->_form_action_export_sdif() ;
                    break ;
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
            $actionmsg = sprintf("No %s actions exectuted.", $action) ;
        }

        $this->set_action_message($actionmsg) ;

        return true ;
    }

    /**
     * Export the CSV data
     *
     */
    function _form_action_export_csv()
    {
        $this->__csv = new SwimTeamSwimmersReportGeneratorCSV() ;
        $csv = &$this->__csv ;
        $actionmsgs = &$this->__actionmsgs ;
        $this->__initializeReportGeneratorCSV($csv) ;

        //  Filter the export based on Gender?

        $gender = $this->get_element_value('Gender') ;

        if ($gender != WPST_GENDER_BOTH)
        {
            $csv->setGenderFilter(true) ;
            $csv->setGenderFilterValue($gender) ;
        }

        $csv->generateReport($this->getSwimmerid(), false) ;
        $csv->generateCSVFile() ;

        //  Build the message that goes back to the user

        $actionmsgs[] = sprintf('Swim Team Roster Exported,
            %s CSV record%s exported.', $csv->getRecordCount(),
            $csv->getRecordCount() == 1 ? '' : 's') ;

        return true ;
    }

    /**
     * Export the RE1 data
     *
     */
    function _form_action_export_re1()
    {
        $this->__re1 = new SwimTeamSwimmersReportGeneratorRE1() ;
        $re1 = &$this->__re1 ;
        $actionmsgs = &$this->__actionmsgs ;
        $this->__initializeReportGeneratorRE1($re1) ;

        //  Filter the export based on Gender?

        $gender = $this->get_element_value('Gender') ;

        if ($gender != WPST_GENDER_BOTH)
        {
            $re1->setGenderFilter(true) ;
            $re1->setGenderFilterValue($gender) ;
        }

        $re1->generateReport($this->getSwimmerid(), false) ;
        $re1->generateRE1File() ;

        //  Build the message that goes back to the user

        $actionmsgs[] = sprintf('Swim Team Roster Exported,
            %s RE1 record%s exported.', $re1->getRecordCount(),
            $re1->getRecordCount() == 1 ? '' : 's') ;

        return true ;
    }

    function _form_action_export_hy3()
    {
        require_once('hy-tek.class.php') ;
        $this->__hy3 = new HY3Roster() ;
        $hy3 = &$this->__hy3 ;
        $actionmsgs = &$this->__actionmsgs ;

        //  Filter the export based on Gender?

        $gender = $this->get_element_value('Gender') ;

        if ($gender != WPST_GENDER_BOTH)
        {
            $hy3->setGender($gender) ;
        }

        $hy3->generateHY3($this->getSwimmerid(), false) ;
        $hy3->generateHY3File() ;

        //  Build the message that goes back to the user

        $actionmsgs[] = sprintf('Swim Team Roster Exported,
            %s HY3 record%s exported.', $hy3->getHY3Count(),
            $hy3->getHY3Count() == 1 ? '' : 's') ;

        return true ;
    }

    function _form_action_export_sdif()
    {
        require_once('sdif.class.php') ;
        $this->__sdif = new SDIFLSCRegistrationPyramid() ;
        $sdif = &$this->__sdif ;
        $actionmsgs = &$this->__actionmsgs ;

        //  Filter the export based on Gender?

        $gender = $this->get_element_value('Gender') ;

        if ($gender != WPST_GENDER_BOTH)
        {
            $sdif->setGender($gender) ;
        }

        $sdif->generateSDIF($this->getSwimmerid()) ;
        $sdif->generateSDIFFile() ;

        //  Build the message that goes back to the user

        $actionmsgs[] = sprintf('Swim Team Roster Exported,
            %s SDIF record%s exported.', $sdif->getSDIFCount(),
            $sdif->getSDIFCount() == 1 ? '' : 's') ;

        return true ;
    }

    /**
     * Build the success action messages
     *
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Register' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Export_Cancel() ;
    }
}

?>
