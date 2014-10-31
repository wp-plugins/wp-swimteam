<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: options.forms.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Options
 * @version $Revision: 1065 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 *
 */

require_once(WPST_PATH . 'class/forms.class.php') ;
require_once(WPST_PATH . 'class/options.class.php') ;

/**
 * Construct the Options Settings form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamOptionsForm extends WpSwimTeamForm
{
    /**
     * div to hold the stroke model opt-in/opt-out selection
     */
    var $__stroke_model_div ;

    /**
     * div to hold the event model opt-in/opt-out selection
     */
    var $__event_model_div ;

    /**
     * div to hold the stroke model opt-in/opt-out model selection
     */
    var $__opt_in_opt_out_model_div ;

    /**
     * Get the array of event key and value pairs
     *
     * @return mixed - array of event key value pairs
     */
    function _strokeSelections()
    {
        //  Event codes and labels 

        $s = array(
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
        $gender = new FEListBox('Gender', true, '150px');
        $gender->set_list_data(array(
             ucfirst(WPST_GENDER_MALE) => WPST_GENDER_MALE
            ,ucfirst(WPST_GENDER_FEMALE) => WPST_GENDER_FEMALE
            ,ucfirst(WPST_GENDER_BOTH) => WPST_GENDER_BOTH
        )) ;
        $this->add_element($gender) ;

        $agelist = array() ;
        for ($i = WPST_AGE_MIN ; $i <= WPST_AGE_MAX ; $i++)
            $agelist[] = $i ;

        //  Minimum Age Field
        $minage = new FEListBox('Minimum Age', true, '100ox') ;
        $minage->set_list_data($agelist) ;
        $this->add_element($minage);
		
        //  Maximum Age Field
        $maxage = new FEListBox('Maximum Age', true, '100px') ;
        $maxage->set_list_data($agelist) ;
        $this->add_element($maxage);
		
        $cutoffmonth = new FEMonths('Age Cutoff Month', true, '150px') ;
        $this->add_element($cutoffmonth);

        $cutoffday = new FEDays('Age Cutoff Day', true, '100px') ;
        $this->add_element($cutoffday);

        $optinlabel = new FERegEx('Opt-In Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($optinlabel) ;

        $optoutlabel = new FERegEx('Opt-Out Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($optoutlabel) ;

        $optinoptoutemail = new FEEmailMany('Opt-In Opt-Out E-mail Address', true, '300px');
        $this->add_element($optinoptoutemail) ;

        $optinoptoutemailformat = new FEListBox('Opt-In Opt-Out E-mail Format', true, '100px');
        $optinoptoutemailformat->set_list_data(array(
             ucwords(WPST_HTML) => WPST_HTML
            ,ucwords(WPST_TEXT) => WPST_TEXT
        )) ;
        $this->add_element($optinoptoutemailformat) ;

        //  Build the Active DIV Radio Button Group
        $this->__opt_in_opt_out_model_div = new FEActiveDIVRadioButtonGroup(
            'Opt-In Opt-Out Usage Model', array(
                ucwords(WPST_STROKE) => WPST_STROKE
               ,ucwords(WPST_EVENT) => WPST_EVENT
            ), true) ;
        $this->add_element($this->__opt_in_opt_out_model_div) ;        

        $optinoptoutmode = new FEListBox('Opt-In Opt-Out Stroke Mode', true, '100px');
        $optinoptoutmode->set_list_data(array(
             ucwords(WPST_BOTH) => WPST_BOTH
            ,ucwords(WPST_FULL) => WPST_FULL
            ,ucwords(WPST_PARTIAL) => WPST_PARTIAL
        )) ;
        $this->add_element($optinoptoutmode) ;

        $strokes = new FECheckBoxList('Opt-In Opt-Out Strokes', true, '200px', '120px');
        $strokes->set_list_data($this->_strokeSelections()) ;
        $this->add_element($strokes) ;

        $geography = new FEListBox('Geography', true, '150px');
        $geography->set_list_data(array(
             ucwords(WPST_US_ONLY) => WPST_US_ONLY
            ,ucwords(WPST_EU_ONLY) => WPST_EU_ONLY
            ,ucwords(WPST_INTERNATIONAL) => WPST_INTERNATIONAL
        )) ;
        $this->add_element($geography) ;

        $stateorprovincelabel = new FERegEx('State or Province Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($stateorprovincelabel) ;

        $postalcodelabel = new FERegEx('Postal Code Label', true, '200px',
            null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($postalcodelabel) ;

        /*
        $measurementunits = new FEListBox('Measurement Units', true, '150px');
        $measurementunits->set_list_data(array(
             ucfirst(WPST_YARDS) => WPST_YARDS
            ,ucfirst(WPST_METERS) => WPST_METERS
        )) ;
        $this->add_element($measurementunits) ;
        */
}

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        //  Initialize the form fields
        $this->set_element_value('Gender', $options->getGender()) ;
        $this->set_element_value('Minimum Age', $options->getMinAge()) ;
        $this->set_element_value('Maximum Age', $options->getMaxAge()) ;
        $this->set_element_value('Age Cutoff Month', $options->getAgeCutoffMonth()) ;
        $this->set_element_value('Age Cutoff Day', $options->getAgeCutoffDay()) ;
        $this->set_element_value('Opt-In Label', $options->getOptInLabel()) ;
        $this->set_element_value('Opt-Out Label', $options->getOptOutLabel()) ;
        $this->set_element_value('Opt-In Opt-Out E-mail Address', $options->getOptInOptOutEmailAddress()) ;
        $this->set_element_value('Opt-In Opt-Out E-mail Format', $options->getOptInOptOutEmailFormat()) ;
        $this->set_element_value('Opt-In Opt-Out Usage Model', $options->getOptInOptOutUsageModel()) ;
        $this->set_element_value('Opt-In Opt-Out Stroke Mode', $options->getOptInOptOutMode()) ;
        $this->set_element_value('Opt-In Opt-Out Strokes', $options->getOptInOptOutStrokes()) ;
        /*
        $this->set_element_value('Opt-In Opt-Out Strokes', array(
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_VALUE,
            WPST_SDIF_EVENT_STROKE_CODE_BACKSTROKE_VALUE,
            WPST_SDIF_EVENT_STROKE_CODE_BREASTSTROKE_VALUE,
            WPST_SDIF_EVENT_STROKE_CODE_BUTTERFLY_VALUE,
            WPST_SDIF_EVENT_STROKE_CODE_INDIVIDUAL_MEDLEY_VALUE,
            WPST_SDIF_EVENT_STROKE_CODE_FREESTYLE_RELAY_VALUE,
            WPST_SDIF_EVENT_STROKE_CODE_MEDLEY_RELAY_VALUE)) ;
         */
        $this->set_element_value('Geography', $options->getGeography()) ;
        $this->set_element_value('State or Province Label', $options->getStateOrProvinceLabel()) ;
        $this->set_element_value('Postal Code Label', $options->getPostalCodeLabel()) ;
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

        $stable = html_table('100%', 0, 0) ;
 
        //$stable->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('Gender'),
            $this->element_form('Gender')) ;

        $table->add_row($this->element_label('Minimum Age'),
            $this->element_form('Minimum Age')) ;

        $table->add_row($this->element_label('Maximum Age'),
            $this->element_form('Maximum Age')) ;

        $table->add_row($this->element_label('Age Cutoff Month'),
            $this->element_form('Age Cutoff Month')) ;

        $table->add_row($this->element_label('Age Cutoff Day'),
            $this->element_form('Age Cutoff Day')) ;

        $table->add_row($this->element_label('Opt-In Label'),
            $this->element_form('Opt-In Label')) ;

        $table->add_row($this->element_label('Opt-Out Label'),
            $this->element_form('Opt-Out Label')) ;

        $table->add_row($this->element_label('Opt-In Opt-Out E-mail Address'),
            $this->element_form('Opt-In Opt-Out E-mail Address')) ;

        $table->add_row($this->element_label('Opt-In Opt-Out E-mail Format'),
            $this->element_form('Opt-In Opt-Out E-mail Format')) ;

        $table->add_row($this->element_label('Opt-In Opt-Out Usage Model'),
            $this->element_form('Opt-In Opt-Out Usage Model')) ;

        //  Build the Magic Divs to show the strokes only in stroke mode

        $this->__stroke_model_div = $this->__opt_in_opt_out_model_div->build_div(0) ;
        $this->__event_model_div = $this->__opt_in_opt_out_model_div->build_div(1) ;

        $tdl = html_td() ;
        $tdr = html_td() ;
        $tdl->add($this->element_label('Opt-In Opt-Out Stroke Mode')) ;
        $tdr->add($this->element_form('Opt-In Opt-Out Stroke Mode')) ;
        $tdr->set_style('padding: 3px 0px;width: 359px') ;
        $stable->add_row($tdl, $tdr) ;
        $tdl = html_td() ;
        $tdr = html_td() ;
        $tdl->add($this->element_label('Opt-In Opt-Out Strokes')) ;
        $tdr->add($this->element_form('Opt-In Opt-Out Strokes')) ;
        $tdr->set_style('padding: 3px 0px;width: 359px') ;
        $stable->add_row($tdl, $tdr) ;

        $this->__stroke_model_div->add($stable) ;

        $this->__event_model_div->add(null) ;

        $model = html_div(null, $this->__stroke_model_div, $this->__event_model_div) ;

        $td = html_td() ;
        $td->set_tag_attributes(array('colspan' => '2',
            'valign' => 'middle', 'style' => 'padding-top: 5px; padding-right: 0px;')) ;
        $td->add($model) ;

        $table->add_row($td) ;

        $table->add_row($this->element_label('Geography'),
            $this->element_form('Geography')) ;

        $table->add_row($this->element_label('State or Province Label'),
            $this->element_form('State or Province Label')) ;

        $table->add_row($this->element_label('Postal Code Label'),
            $this->element_form('Postal Code Label')) ;

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

        $options = new SwimTeamOptions() ;
        $options->setMinAge($this->get_element_value('Minimum Age')) ;
        $options->setMaxAge($this->get_element_value('Maximum Age')) ;

        //  Make sure min and max age make sense

        if ($options->getMinAge() >= $options->getMaxAge())
        {
            $this->add_error('Minimum Age', 'Minimum age must be less than Maximum age.') ;
            $this->add_error('Maximum Age', 'Maximum age must be greater than Maximum age.') ;
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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;
        $options->setGender($this->get_element_value('Gender')) ;
        $options->setMinAge($this->get_element_value('Minimum Age')) ;
        $options->setMaxAge($this->get_element_value('Maximum Age')) ;
        $options->setAgeCutoffMonth($this->get_element_value('Age Cutoff Month')) ;
        $options->setAgeCutoffDay($this->get_element_value('Age Cutoff Day')) ;

        $options->setOptInLabel($this->get_element_value('Opt-In Label')) ;
        $options->setOptOutLabel($this->get_element_value('Opt-Out Label')) ;
        $options->setOptInOptOutEmailAddress($this->get_element_value('Opt-In Opt-Out E-mail Address')) ;
        $options->setOptInOptOutEmailFormat($this->get_element_value('Opt-In Opt-Out E-mail Format')) ;
        $options->setOptInOptOutUsageModel($this->get_element_value('Opt-In Opt-Out Usage Model')) ;
        $options->setOptInOptOutMode($this->get_element_value('Opt-In Opt-Out Stroke Mode')) ;
        $options->setOptInOptOutStrokes($this->get_element_value('Opt-In Opt-Out Strokes')) ;
        $options->setGeography($this->get_element_value('Geography')) ;
        $options->setStateOrProvinceLabel($this->get_element_value('State or Province Label')) ;
        $options->setPostalCodeLabel($this->get_element_value('Postal Code Label')) ;
        $options->updateOptions() ;

        $this->set_action_message('Swim Team options updated.') ;

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
 * Construct the Registration Options Settings form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamRegistrationOptionsForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $autoregister = new FEYesNoListBox('Auto-Register New Swimmers', true, '75px') ;
        $this->add_element($autoregister) ;

        $regsystem = new FEListBox('Registration System', true, '100px');
        $regsystem->set_list_data(array(
             ucwords(WPST_OPEN) => WPST_OPEN
            ,ucwords(WPST_CLOSED) => WPST_CLOSED
        )) ;
        $this->add_element($regsystem) ;

        $regprefixlabel = new FEText('Registration Prefix Label', false, '75px');
        $this->add_element($regprefixlabel) ;

        $regfeelabel = new FERegEx('Registration Fee Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($regfeelabel) ;

        $currencylabel = new FEText('Currency Label', true, '75px');
        $this->add_element($currencylabel) ;

        $defaultregfee = new FENumberPrice('Registration Fee', true, '100px');
        $this->add_element($defaultregfee) ;

        $regemail = new FEEmailMany('Registration E-mail Address', true, '300px');
        $this->add_element($regemail) ;

        $regemailformat = new FEListBox('Registration E-mail Format', true, '100px');
        $regemailformat->set_list_data(array(
             ucwords(WPST_HTML) => WPST_HTML
            ,ucwords(WPST_TEXT) => WPST_TEXT
        )) ;
        $this->add_element($regemailformat) ;

        $regtouurl = new FEUrl('Registration Terms of Use URL', false, '300px');
        $this->add_element($regtouurl) ;

        $regfeeurl = new FEUrl('Registration Fee Policy URL', false, '300px');
        $this->add_element($regfeeurl) ;

        $useroptionalfields = new FENumberInRange('User Optional Fields', true, '100px');
        $this->add_element($useroptionalfields) ;

        $swimmeroptionalfields = new FENumberInRange('Swimmer Optional Fields', true, '100px');
        $this->add_element($swimmeroptionalfields) ;

        //$emailswimmeroptionalfields = new FEYesNoListBox('Email Swimmer Optional Fields', true, '75px') ;
        //$this->add_element($emailswimmeroptionalfields) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        //  Initialize the form fields
        $this->set_element_value('Auto-Register New Swimmers', $options->getAutoRegister()) ;
        $this->set_element_value('Registration System', $options->getRegistrationSystem()) ;
        $this->set_element_value('Registration Prefix Label', $options->getRegistrationPrefixLabel()) ;
        $this->set_element_value('Registration Fee Label', $options->getRegistrationFeeLabel()) ;
        $this->set_element_value('Currency Label', $options->getRegistrationFeeCurrencyLabel()) ;
        $this->set_element_value('Registration Fee', $options->getRegistrationFee()) ;
        $this->set_element_value('Registration E-mail Address', $options->getRegistrationEmail()) ;
        $this->set_element_value('Registration E-mail Format', $options->getRegistrationEmailFormat()) ;
        $this->set_element_value('Registration Terms of Use URL', $options->getRegistrationTermsOfUseURL()) ;
        $this->set_element_value('Registration Fee Policy URL', $options->getRegistrationFeePolicyURL()) ;
        $this->set_element_value('User Optional Fields', $options->getUserOptionalFields()) ;
        $this->set_element_value('Swimmer Optional Fields', $options->getSwimmerOptionalFields()) ;
        //$this->set_element_value('Email Swimmer Optional Fields', $options->getEmailSwimmerOptionalFields()) ;
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
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('Auto-Register New Swimmers'),
            $this->element_form('Auto-Register New Swimmers')) ;

        $table->add_row($this->element_label('Registration System'),
            $this->element_form('Registration System')) ;

        $table->add_row($this->element_label('Registration Prefix Label'),
            $this->element_form('Registration Prefix Label')) ;

        $table->add_row($this->element_label('Registration Fee Label'),
            $this->element_form('Registration Fee Label')) ;

        $table->add_row($this->element_label('Currency Label'),
            $this->element_form('Currency Label')) ;

        $table->add_row($this->element_label('Registration Fee'),
            $this->element_form('Registration Fee')) ;

        $table->add_row($this->element_label('Registration E-mail Address'),
            $this->element_form('Registration E-mail Address')) ;

        $table->add_row($this->element_label('Registration E-mail Format'),
            $this->element_form('Registration E-mail Format')) ;

        $table->add_row($this->element_label('Registration Terms of Use URL'),
            $this->element_form('Registration Terms of Use URL')) ;

        $table->add_row($this->element_label('Registration Fee Policy URL'),
            $this->element_form('Registration Fee Policy URL')) ;

        $table->add_row($this->element_label('User Optional Fields'),
            $this->element_form('User Optional Fields')) ;

        $table->add_row($this->element_label('Swimmer Optional Fields'),
            $this->element_form('Swimmer Optional Fields')) ;

        //$table->add_row($this->element_label('Email Swimmer Optional Fields'),
            //$this->element_form('Email Swimmer Optional Fields')) ;

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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;
        $options->setAutoRegister($this->get_element_value('Auto-Register New Swimmers')) ;
        $options->setRegistrationSystem($this->get_element_value('Registration System')) ;
        $options->setRegistrationPrefixLabel($this->get_element_value('Registration Prefix Label')) ;
        $options->setRegistrationFeeLabel($this->get_element_value('Registration Fee Label')) ;
        $options->setRegistrationFeeCurrencyLabel($this->get_element_value('Currency Label')) ;
        $options->setRegistrationFee($this->get_element_value('Registration Fee')) ;
        $options->setRegistrationEmail($this->get_element_value('Registration E-mail Address')) ;
        $options->setRegistrationEmailFormat($this->get_element_value('Registration E-mail Format')) ;
        $options->setRegistrationTermsOfUseURL($this->get_element_value('Registration Terms of Use URL')) ;
        $options->setRegistrationFeePolicyURL($this->get_element_value('Registration Fee Policy URL')) ;
        $options->setUserOptionalFields($this->get_element_value('User Optional Fields')) ;
        $options->setSwimmerOptionalFields($this->get_element_value('Swimmer Optional Fields')) ;
        //$options->setEmailSwimmerOptionalFields($this->get_element_value('Email Swimmer Optional Fields')) ;
        $options->updateOptions() ;

        $this->set_action_message('Swim Team options updated.') ; 

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
 * Construct the User Profile Options form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamUserProfileOptionsForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $primaryphonelabel = new FERegEx('Primary Phone Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($primaryphonelabel) ;

        $secondaryphonelabel = new FERegEx('Secondary Phone Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($secondaryphonelabel) ;


        //  User optional fields
        //  How many user options does this configuration support?

        $options_count = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options_count ; $oc++)
        {
            $user_option{$oc} = new FEListBox('User Option #' .
                $oc, true, '200px');
            $user_option{$oc}->set_list_data(array(
                 ucfirst(WPST_REQUIRED) => WPST_REQUIRED
                ,ucfirst(WPST_OPTIONAL) => WPST_OPTIONAL
                ,ucwords(WPST_YES_NO) => WPST_YES_NO
                ,ucwords(WPST_NO_YES) => WPST_NO_YES
                ,ucwords(WPST_CLOTHING_SIZE) => WPST_CLOTHING_SIZE
                ,ucwords(WPST_EMAIL_REQUIRED) => WPST_EMAIL_REQUIRED
                ,ucwords(WPST_URL_REQUIRED) => WPST_URL_REQUIRED
                ,ucwords(WPST_EMAIL_OPTIONAL) => WPST_EMAIL_OPTIONAL
                ,ucwords(WPST_URL_OPTIONAL) => WPST_URL_OPTIONAL
                ,ucfirst(WPST_DISABLED) => WPST_DISABLED
            )) ;
            $this->add_element($user_option{$oc}) ;

            $user_option_label{$oc} = new FERegEx('User Option #' .
                $oc . ' Label', true, '200px',
                null, '/[a-zA-Z]+/', 'Label must start with a letter.');
            $this->add_element($user_option_label{$oc}) ;

            $user_option_mode{$oc} = new FEListBox('User Option #' .
                $oc . ' Mode', true, '100px') ;
            $user_option_mode{$oc}->set_list_data(array(
                 ucfirst(WPST_USER) => WPST_USER
                ,ucfirst(WPST_ADMIN) => WPST_ADMIN
            )) ;
            $this->add_element($user_option_mode{$oc}) ;
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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        $this->set_element_value('Primary Phone Label', $options->getPrimaryPhoneLabel()) ;
        $this->set_element_value('Secondary Phone Label', $options->getSecondaryPhoneLabel()) ;

        //  Initialize the form fields
        //  How many user options does this configuration support?

        $options_count = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options_count ; $oc++)
        {
            $this->set_element_value('User Option #' . $oc,
                $options->getSwimTeamOption(constant('WPST_OPTION_USER_OPTION' . $oc))) ;
            $this->set_element_value('User Option #' . $oc . ' Label',
                $options->getSwimTeamOption(constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL'))) ;
            $this->set_element_value('User Option #' . $oc . ' Mode',
                $options->getSwimTeamOption(constant('WPST_OPTION_USER_OPTION' . $oc . '_MODE'))) ;
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

        $table->add_row($this->element_label('Primary Phone Label'),
            $this->element_form('Primary Phone Label'), '&nbsp;') ;

        $table->add_row($this->element_label('Secondary Phone Label'),
            $this->element_form('Secondary Phone Label'), '&nbsp;') ;

        $table->add_row('&nbsp;', '&nbsp;', '&nbsp;') ;

        //  How many user options does this configuration support?

        $options_count = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_USER_OPTION_COUNT ;

        if ($options_count > 0)
        {
            $table->add_row(html_b('Option Label'),
                html_b('Option Type'), html_b('Option Mode')) ;

            //  Load the user options

            for ($oc = 1 ; $oc <= $options_count ; $oc++)
            {
                $table->add_row(
                    $this->element_form('User Option #' . $oc . ' Label'),
                    $this->element_form('User Option #' . $oc),
                    $this->element_form('User Option #' . $oc . ' Mode')
                ) ;
            }

            $td = html_td(null, null, div_font8bold('Admin Mode:  Field is only visible to Adminstrative users.')) ;
            $td->set_tag_attributes(array('colspan' => 3, 'align' => 'center')) ;
            $table->add(html_tr(null, $td)) ;
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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        $options->setPrimaryPhoneLabel($this->get_element_value('Primary Phone Label')) ;
        $options->setSecondaryPhoneLabel($this->get_element_value('Secondary Phone Label')) ;
        //  How many user options does this configuration support?

        $options_count = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Store the user options

        for ($oc = 1 ; $oc <= $options_count ; $oc++)
        {
            $options->setSwimTeamOption(constant('WPST_OPTION_USER_OPTION' .
                $oc), $this->get_element_value('User Option #' . $oc)) ;
            $options->setSwimTeamOption(constant('WPST_OPTION_USER_OPTION' .
                $oc . '_LABEL'), $this->get_element_value('User Option #' .
                $oc . ' Label')) ;
            $options->setSwimTeamOption(constant('WPST_OPTION_USER_OPTION' .
                $oc . '_MODE'), $this->get_element_value('User Option #' .
                $oc . ' Mode')) ;
        }

        $options->updateOptions() ;

        $this->set_action_message('User Profile options updated.') ;

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
 * Construct the Swimmer Profile Options form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmerProfileOptionsForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $genderlabelmale = new FERegEx('Male Gender Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($genderlabelmale) ;

        $genderlabelfemale = new FERegEx('Female Gender Label', true,
            '200px', null, '/[a-zA-Z]+/', 'Label must start with a letter.');
        $this->add_element($genderlabelfemale) ;

        $swimmerlabelformat = new FEListBox('Swimmer Labels', true, '250px');
        $swimmerlabelformat->set_list_data(array(
             WPST_USA_SWIMMING => WPST_USA_SWIMMING
            ,WPST_SIMPLE_NUMERIC => WPST_SIMPLE_NUMERIC
            ,WPST_AGE_GROUP_PREFIX_NUMERIC => WPST_AGE_GROUP_PREFIX_NUMERIC
            ,WPST_WPST_ID => WPST_WPST_ID
            ,WPST_AGE_GROUP_PREFIX_WPST_ID => WPST_AGE_GROUP_PREFIX_WPST_ID
            ,WPST_CUSTOM => WPST_CUSTOM
        )) ;
        $this->add_element($swimmerlabelformat) ;

        $swimmerlabelformatcode = new FEText('Swimmer Label Format', true, '100px');
        $this->add_element($swimmerlabelformatcode) ;

        $swimmerlabelinitialvalue = new FENumberInRange('Swimmer Label Initial Value', false, '100px', null, 0, 1000) ;
        $this->add_element($swimmerlabelinitialvalue) ;

        //  Swimmer optional fields
        //  How many swimmer options does this configuration support?

        $options_count = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options_count ; $oc++)
        {
            $swimmer_option{$oc} = new FEListBox('Swimmer Option #' .
                $oc, true, '200px');
            $swimmer_option{$oc}->set_list_data(array(
                 ucfirst(WPST_REQUIRED) => WPST_REQUIRED
                ,ucfirst(WPST_OPTIONAL) => WPST_OPTIONAL
                ,ucwords(WPST_YES_NO) => WPST_YES_NO
                ,ucwords(WPST_NO_YES) => WPST_NO_YES
                ,ucwords(WPST_CLOTHING_SIZE) => WPST_CLOTHING_SIZE
                ,ucwords(WPST_EMAIL_REQUIRED) => WPST_EMAIL_REQUIRED
                ,ucwords(WPST_URL_REQUIRED) => WPST_URL_REQUIRED
                ,ucwords(WPST_EMAIL_OPTIONAL) => WPST_EMAIL_OPTIONAL
                ,ucwords(WPST_URL_OPTIONAL) => WPST_URL_OPTIONAL
                ,ucfirst(WPST_DISABLED) => WPST_DISABLED
            )) ;
            $this->add_element($swimmer_option{$oc}) ;

            $swimmer_option_label{$oc} = new FERegEx('Swimmer Option #' .
                $oc . ' Label', true, '200px',
                null, '/[a-zA-Z]+/', 'Label must start with a letter.');
            
            $this->add_element($swimmer_option_label{$oc}) ;

            $swimmer_option_mode{$oc} = new FEListBox('Swimmer Option #' .
                $oc . ' Mode', true, '100px') ;
            $swimmer_option_mode{$oc}->set_list_data(array(
                 ucfirst(WPST_USER) => WPST_USER
                ,ucfirst(WPST_ADMIN) => WPST_ADMIN
            )) ;
            $this->add_element($swimmer_option_mode{$oc}) ;
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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        $this->set_element_value('Male Gender Label', $options->getGenderLabelMale()) ;
        $this->set_element_value('Female Gender Label', $options->getGenderLabelFemale()) ;
        $this->set_element_value('Swimmer Labels', $options->getSwimmerLabelFormat()) ;
        $this->set_element_value('Swimmer Label Format', $options->getSwimmerLabelFormatCode()) ;
        $this->set_element_value('Swimmer Label Initial Value', $options->getSwimmerLabelInitialValue()) ;

        //  Initialize the form fields
        //  How many swimmer options does this configuration support?

        $options_count = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options_count ; $oc++)
        {
            $this->set_element_value('Swimmer Option #' . $oc,
                $options->getSwimTeamOption(constant('WPST_OPTION_SWIMMER_OPTION' . $oc))) ;
            $this->set_element_value('Swimmer Option #' . $oc . ' Label',
                $options->getSwimTeamOption(constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL'))) ;
            $this->set_element_value('Swimmer Option #' . $oc . ' Mode',
                $options->getSwimTeamOption(constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE'))) ;
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

        $table->add_row($this->element_label('Male Gender Label'),
            $this->element_form('Male Gender Label')) ;

        $table->add_row($this->element_label('Female Gender Label'),
            $this->element_form('Female Gender Label')) ;

        $table->add_row($this->element_label('Swimmer Labels'),
            $this->element_form('Swimmer Labels')) ;

        $table->add_row($this->element_label('Swimmer Label Format'),
            $this->element_form('Swimmer Label Format')) ;

        $table->add_row($this->element_label('Swimmer Label Initial Value'),
            $this->element_form('Swimmer Label Initial Value')) ;

        $table->add_row('&nbsp;', '&nbsp;', '&nbsp;') ;

        //  How many swimmer options does this configuration support?

        $options_count = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        if ($options_count > 0)
        {
            $table->add(html_tr(null, html_th('Option Label'),
                html_th('Option Type'), html_th('Option Mode'))) ;

            //  Load the swimmer options
    
            for ($oc = 1 ; $oc <= $options_count ; $oc++)
            {
                $table->add_row(
                    $this->element_form('Swimmer Option #' . $oc . ' Label'),
                    $this->element_form('Swimmer Option #' . $oc),
                    $this->element_form('Swimmer Option #' . $oc . ' Mode')
                ) ;
            }

            $td = html_td(null, null, div_font8bold('Admin Mode:  Field is only visible to Adminstrative users.')) ;
            $td->set_tag_attributes(array('colspan' => 3, 'align' => 'center')) ;
            $table->add(html_tr(null, $td)) ;
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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        $options->setGenderLabelMale($this->get_element_value('Male Gender Label')) ;
        $options->setGenderLabelFemale($this->get_element_value('Female Gender Label')) ;
        $options->setSwimmerLabelFormat($this->get_element_value('Swimmer Labels')) ;
        $options->setSwimmerLabelFormatCode($this->get_element_value('Swimmer Label Format')) ;
        $options->setSwimmerLabelInitialValue($this->get_element_value('Swimmer Label Initial Value')) ;

        //  How many swimmer options does this configuration support?

        $options_count = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options_count === false)
            $options_count = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Store the swimmer options

        for ($oc = 1 ; $oc <= $options_count ; $oc++)
        {
            $options->setSwimTeamOption(constant('WPST_OPTION_SWIMMER_OPTION' . $oc), 
                $this->get_element_value('Swimmer Option #' . $oc)) ;
            $options->setSwimTeamOption(constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL'),
                $this->get_element_value('Swimmer Option #' . $oc . ' Label')) ;
            $options->setSwimTeamOption(constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_MODE'),
                $this->get_element_value('Swimmer Option #' . $oc . ' Mode')) ;
        }
        $options->updateOptions() ;

        $this->set_action_message('Swimmer Profile options updated.') ;

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
 * Construct the Googple Maps Options Settings form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamGoogleMapsOptionsForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $googlemapsapikey = new FETextArea('Google API Key', false, 3, 60, '300px') ;
        $this->add_element($googlemapsapikey) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        //  Initialize the form fields
        $this->set_element_value('Google API Key', $options->getGoogleAPIKey()) ;
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

        $table->add_row($this->element_label('Google API Key'),
            $this->element_form('Google API Key')) ;

        $table->add_row(null, html_span(null,
            'Don\'t have a Google Maps API Key?', html_br(), 
            html_a('http://code.google.com/apis/maps/signup.html',
            'Sign up'), 'for a free API key with Google Maps.')) ;

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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;
        $options->setGoogleAPIKey($this->get_element_value('Google API Key')) ;
        $options->updateOptions() ;

        $this->set_action_message('Swim Team Google Maps options updated.') ;

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
 * Construct the Job Options Settings form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamJobOptionsForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $jobsignup = new FEListBox('Job Sign Up Mode', true, '100px');
        $jobsignup->set_list_data(array(
             ucwords(WPST_USER) => WPST_USER
            ,ucwords(WPST_ADMIN) => WPST_ADMIN
        )) ;
        $this->add_element($jobsignup) ;

        $jobcredits = new FENumberInRange('Job Credits', true, '100px', null, 0, 500) ;
        $this->add_element($jobcredits) ;

        $jobcreditsrequired = new FENumberInRange('Job Credits Required', true, '100px', null, 0, 500) ;
        $this->add_element($jobcreditsrequired) ;

        $regemail = new FEEmailMany('Job E-mail Address', true, '300px');
        $this->add_element($regemail) ;

        $regemailformat = new FEListBox('Job E-mail Format', true, '100px');
        $regemailformat->set_list_data(array(
             ucwords(WPST_HTML) => WPST_HTML
            ,ucwords(WPST_TEXT) => WPST_TEXT
        )) ;
        $this->add_element($regemailformat) ;

        $regtouurl = new FEUrl('Job Expectations URL', false, '300px');
        $this->add_element($regtouurl) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        //  Initialize the form fields
        $this->set_element_value('Job Sign Up Mode', $options->getJobSignUp()) ;
        $this->set_element_value('Job Credits', $options->getJobCredits()) ;
        $this->set_element_value('Job Credits Required', $options->getJobCreditsRequired()) ;
        $this->set_element_value('Job E-mail Address', $options->getJobEmailAddress()) ;
        $this->set_element_value('Job E-mail Format', $options->getJobEmailFormat()) ;
        $this->set_element_value('Job Expectations URL', $options->getJobExpectationsURL()) ;
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
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_label('Job Sign Up Mode'),
            $this->element_form('Job Sign Up Mode')) ;

        $table->add_row($this->element_label('Job Credits'),
            $this->element_form('Job Credits')) ;

        $table->add_row($this->element_label('Job Credits Required'),
            $this->element_form('Job Credits Required')) ;

        $table->add_row($this->element_label('Job E-mail Address'),
            $this->element_form('Job E-mail Address')) ;

        $table->add_row($this->element_label('Job E-mail Format'),
            $this->element_form('Job E-mail Format')) ;

        $table->add_row($this->element_label('Job Expectations URL'),
            $this->element_form('Job Expectations URL')) ;

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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;
        $options->setJobSignUp($this->get_element_value('Job Sign Up Mode')) ;
        $options->setJobCredits($this->get_element_value('Job Credits')) ;
        $options->setJobCreditsRequired($this->get_element_value('Job Credits Required')) ;
        $options->setJobEmailAddress($this->get_element_value('Job E-mail Address')) ;
        $options->setJobEmailFormat($this->get_element_value('Job E-mail Format')) ;
        $options->setJobExpectationsURL($this->get_element_value('Job Expectations URL')) ;
        $options->updateOptions() ;

        $this->set_action_message('Swim Team options updated.') ;

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
 * Construct the Googple Maps Options Settings form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamMiscellaneousOptionsForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $usetransients = new FEYesNoListBox('Use Transients for Temporary Storage', true, '75px') ;
        $this->add_element($usetransients) ;

        $enableverbosemessges = new FEYesNoListBox('Enable Verbose Messages', true, '75px') ;
        $this->add_element($enableverbosemessges) ;

        $gdlrows = new FENumberInRange('Rows to Display', true, '100px', null, 5, 200) ;
        $this->add_element($gdlrows) ;

        $timeformat = new FEText('Time Format', true, '100px') ;
        $this->add_element($timeformat) ;

        $enablegooglemaps = new FEYesNoListBox('Enable Google Maps', true, '75px') ;
        $this->add_element($enablegooglemaps) ;

        $googlemapsapikey = new FETextArea('Google API Key', false, 3, 60, '300px') ;
        $this->add_element($googlemapsapikey) ;

        $redirect = new FERadioGroup('Login Redirect', array(
            ucwords(WPST_NONE) => WPST_NONE,
            ucwords(WPST_DASHBOARD_PAGE) => WPST_DASHBOARD_PAGE,
            ucwords(WPST_SWIMTEAM_OVERVIEW_PAGE) => WPST_SWIMTEAM_OVERVIEW_PAGE,
            ucwords(WPST_HOME_PAGE) => WPST_HOME_PAGE,
            //ucwords(WPST_PREVIOUS_PAGE) => WPST_PREVIOUS_PAGE
            ), true, '200px');
        $redirect->set_br_flag(true) ;
        $this->add_element($redirect) ;

    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;

        //  Initialize the form fields
        $this->set_element_value('Use Transients for Temporary Storage', $options->getUseTransients()) ;
        $this->set_element_value('Enable Verbose Messages', $options->getEnableVerboseMessages()) ;
        $this->set_element_value('Rows to Display', $options->getGDLRowsToDisplay()) ;
        $this->set_element_value('Time Format', $options->getTimeFormat()) ;
        $this->set_element_value('Enable Google Maps', $options->getEnableGoogleMaps()) ;
        $this->set_element_value('Google API Key', $options->getGoogleAPIKey()) ;
        $this->set_element_value('Login Redirect', WPST_NONE) ;
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

        $table->add_row($this->element_label('Use Transients for Temporary Storage'),
            $this->element_form('Use Transients for Temporary Storage')) ;

        $table->add_row($this->element_label('Enable Verbose Messages'),
            $this->element_form('Enable Verbose Messages')) ;

        $table->add_row($this->element_label('Rows to Display'),
            $this->element_form('Rows to Display')) ;

        $table->add_row($this->element_label('Time Format'),
            html_span(null, $this->element_form('Time Format'),
            html_a('http://php.net/manual/en/function.date.php', 'Help'))) ;

        $table->add_row(null, html_span(null,
            'Frequently wp-SwimTeam presents information in', html_br(),
            'a table format.  This setting determines how many', html_br(),
            'rows should be shown on a page at one time.')) ;

        $table->add_row(_HTML_SPACE, _HTML_SPACE) ;

        $table->add_row($this->element_label('Enable Google Maps'),
            $this->element_form('Enable Google Maps')) ;

        $table->add_row( $this->element_label('Google API Key'),
            $this->element_form('Google API Key')) ;

        //$table->add_row(_HTML_SPACE, _HTML_SPACE) ;

        $table->add_row(null, html_span(null,
            'Don\'t have a Google Maps API Key?', html_br(), 
            html_a('http://code.google.com/apis/maps/signup.html',
            'Sign up'), 'for a free API key with Google.')) ;

        $table->add_row(_HTML_SPACE, _HTML_SPACE) ;

        $table->add_row($this->element_label('Login Redirect'),
            $this->element_form('Login Redirect')) ;

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
        $options = new SwimTeamOptions() ;
        $options->loadOptions() ;
        $options->setUseTransients($this->get_element_value('Use Transients for Temporary Storage')) ;
        $options->setEnableVerboseMessages($this->get_element_value('Enable Verbose Messages')) ;
        $options->setGDLRowsToDisplay($this->get_element_value('Rows to Display')) ;
        $options->setTimeFormat($this->get_element_value('Time Format')) ;
        $options->setEnableGoogleMaps($this->get_element_value('Enable Google Maps')) ;
        $options->setGoogleAPIKey($this->get_element_value('Google API Key')) ;
        $options->setLoginRedirectAction($this->get_element_value('Login Redirect')) ;
        $options->updateOptions() ;

        $this->set_action_message('Swim Team Miscellaneous options updated.') ;

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
