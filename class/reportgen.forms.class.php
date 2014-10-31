<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: reportgen.forms.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Reports
 * @version $Revision: 1065 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 *
 */

require_once(WPST_PATH . 'class/forms.class.php') ;
require_once(WPST_PATH . 'class/reportgen.class.php') ;

define('FEFILTER', ' Filter') ;
define('FEFILTERLB', FEFILTER . ' Listbox') ;

/**
 * Construct the Report Generator form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamUsersReportGeneratorForm extends WpSwimTeamForm
{
    /**
     * generated report
     */
    var $__report ;

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements($filters = true)
    {
        $internalid = new FECheckBox('Internal Id') ;
        $this->add_element($internalid) ;

        $firstname = new FECheckBox('First Name') ;
        $this->add_element($firstname) ;

        $lastname = new FECheckBox('Last Name') ;
        $this->add_element($lastname) ;

        $username = new FECheckBox('Username') ;
        $this->add_element($username) ;

        $emailaddress = new FECheckBox('E-mail Address') ;
        $this->add_element($emailaddress) ;

        $address1 = new FECheckBox('Street Address 1') ;
        $this->add_element($address1) ;

        $address2 = new FECheckBox('Street Address 2') ;
        $this->add_element($address2) ;

        $address3 = new FECheckBox('Street Address 3') ;
        $this->add_element($address3) ;

        $city = new FECheckBox('City') ;
        $this->add_element($city) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $stateorprovince = new FECheckBox($label) ;
        $this->add_element($stateorprovince) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $postalcode = new FECheckBox($label) ;
        $this->add_element($postalcode) ;

        $country = new FECheckBox('Country') ;
        $this->add_element($country) ;

        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $primaryphone = new FECheckBox($label) ;
        $this->add_element($primaryphone) ;

        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $secondaryphone = new FECheckBox($label) ;
        $this->add_element($secondaryphone) ;

        $contactinfo = new FECheckBox('Contact Information') ;
        $this->add_element($contactinfo) ;

        //$tshirtsize = new FECheckBox('T-Shirt Size') ;
        //$this->add_element($tshirtsize) ;

        //$swimmerlabel = new FECheckBox('Swimmer Label') ;
        //$this->add_element($swimmerlabel) ;

        $websitreid = new FECheckBox('Web Site Id') ;
        $this->add_element($websitreid) ;

        $send_to = new FEListBox('Report', true, '200px');
        $send_to->set_list_data(array(
             ucfirst(WPST_GENERATE_STATIC_WEB_PAGE) => WPST_GENERATE_STATIC_WEB_PAGE
            ,ucfirst(WPST_GENERATE_CSV) => WPST_GENERATE_CSV
        )) ;
        $this->add_element($send_to) ;

        //  Sometimes we don't want to have filters

        if ($filters)
        {
            $contactinfofilter = new FECheckBox('Contact Information' . FEFILTER) ;
            $this->add_element($contactinfofilter) ;

            $contactinfofilterlb = new FEListBox('Contact Information' . FEFILTERLB, true, '100px');
            $contactinfofilterlb->set_list_data(array(
                 ucfirst(WPST_PUBLIC) => WPST_PUBLIC
                ,ucfirst(WPST_PRIVATE) => WPST_PRIVATE
            )) ;
            $this->add_element($contactinfofilterlb) ;
        }

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        $of = array($options) ;
        $offcb = array($options) ;
        $offyn = array($options) ;

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;

            if (get_option($oconst) != WPST_DISABLED)
            {
                $of[$oc] = new FECheckBox(get_option($lconst)) ;
                $this->add_element($of[$oc]) ;

                if ($filters)
                {
                    // Field is enabled, can it be a filter?
 
                    if ((get_option($oconst) == WPST_YES_NO) ||
                        (get_option($oconst) == WPST_NO_YES))
                    {
                        $offcb[$oc] = new FECheckBox(get_option($lconst) . FEFILTER) ;
                        $offyn[$oc] = new FEYesNoListBox(get_option($lconst) . FEFILTERLB, false, '75px') ;
                        $this->add_element($offcb[$oc]) ;
                        $this->add_element($offyn[$oc]) ;
                    }
                }
            }
        }
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data($filters = true)
    {
        $this->set_element_value('Internal Id', false) ;
        $this->set_element_value('First Name', true) ;
        $this->set_element_value('Last Name', true) ;
        $this->set_element_value('Username', false) ;
        $this->set_element_value('E-mail Address', true) ;
        $this->set_element_value('Street Address 1', false) ;
        $this->set_element_value('Street Address 2', false) ;
        $this->set_element_value('Street Address 3', false) ;
        $this->set_element_value('City', false) ;
        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $this->set_element_value($label, false) ;
        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $this->set_element_value($label, false) ;
        $this->set_element_value('Country', false) ;
        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $this->set_element_value($label, false) ;
        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        $this->set_element_value($label, false) ;
        $this->set_element_value('Contact Information', false) ;

        if ($filters)
        {
            $this->set_element_value('Contact Information' . FEFILTER, false) ;
            $this->set_element_value('Contact Information' . FEFILTERLB, WPST_PUBLIC) ;
            //  How many user options does this configuration support?

            $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

            if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

            //  Load the user options

            for ($oc = 1 ; $oc <= $options ; $oc++)
            {
                $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
                $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
                switch (get_option($oconst))
                {
                    case WPST_YES_NO:
                        $this->set_element_value(get_option($lconst) . FEFILTER, false) ;
                        $this->set_element_value(get_option($lconst) . FEFILTERLB, WPST_YES) ;
                        break ;

                    case WPST_NO_YES:
                        $this->set_element_value(get_option($lconst) . FEFILTER, false) ;
                        $this->set_element_value(get_option($lconst) . FEFILTERLB, WPST_NO) ;
                        break ;

                    default:
                        break ;
                }
            }

        }
        $this->set_element_value('Report', WPST_GENERATE_STATIC_WEB_PAGE) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        //array_walk(debug_backtrace(),create_function('$a,$b','print '{$a[\'function\']}()('.basename($a[\'file\']).':{$a[\'line\']}); ';'));
        $this->add_form_block('Contact Fields', $this->_contact_options()) ;
        $this->add_form_block('Report Filters', $this->_report_filters()) ;
        $this->add_form_block('Report Output', $this->_send_report_to()) ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_contact_options()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('First Name'),
            $this->element_form('Last Name')) ;

        $table->add_row($this->element_form('E-mail Address'),
            $this->element_form('Username')) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $table->add_row($this->element_form('Street Address 1'),
            $this->element_form($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $table->add_row($this->element_form('Street Address 2'),
            $this->element_form($label)) ;

        $table->add_row($this->element_form('Street Address 3'),
            $this->element_form('Country')) ;

        $plabel = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        $slabel = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;

        $table->add_row($this->element_form($plabel),
            $this->element_form($slabel)) ;

        $table->add_row($this->element_form('Internal Id'),
            _HTML_SPACE) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //$ometa = new SwimTeamOptionMeta() ;
        //$ometa->setUserId($this->getId()) ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $table->add_row($this->element_form(get_option($lconst))) ;
            }
        }

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_send_report_to()
    {
        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($this->element_form('Report')) ;

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_report_filters()
    {
        error_reporting(E_ALL) ;
        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($this->element_form('Contact Information' . FEFILTER),
            $this->element_form('Contact Information' . FEFILTERLB)) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;

        //  Load the user options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
            if ((get_option($oconst) == WPST_YES_NO) ||
                (get_option($oconst) == WPST_NO_YES))
            {

                $table->add_row(
                    $this->element_form(get_option($lconst) . FEFILTER),
                    $this->element_form(get_option($lconst) . FEFILTERLB)) ;
            }
        }

        return $table ;
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
     * Set the report options based on form state
     *
     */
    function __set_report_options($filters = true)
    {
        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        if (!is_null($this->get_element_value('Internal Id')))
            $rpt->setinternalId(true) ;

        if (!is_null($this->get_element_value('First Name')))
            $rpt->setFirstName(true) ;

        if (!is_null($this->get_element_value('Last Name')))
            $rpt->setLastName(true) ;

        if (!is_null($this->get_element_value('Username')))
            $rpt->setUsername(true) ;

        if (!is_null($this->get_element_value('E-mail Address')))
            $rpt->setEmailAddress(true) ;

        if (!is_null($this->get_element_value('Street Address 1')))
            $rpt->setStreetAddress1(true) ;

        if (!is_null($this->get_element_value('Street Address 2')))
            $rpt->setStreetAddress2(true) ;

        if (!is_null($this->get_element_value('Street Address 3')))
            $rpt->setStreetAddress3(true) ;

        if (!is_null($this->get_element_value('City')))
            $rpt->setCity(true) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        if (!is_null($this->get_element_value($label)))
            $rpt->setStateOrProvince(true) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        if (!is_null($this->get_element_value($label)))
            $rpt->setPostalCode(true) ;

        if (!is_null($this->get_element_value('Country')))
            $rpt->setCountry(true) ;

        $label = get_option(WPST_OPTION_USER_PRIMARY_PHONE_LABEL) ;
        if (!is_null($this->get_element_value($label)))
            $rpt->setPrimaryPhone(true) ;

        $label = get_option(WPST_OPTION_USER_SECONDARY_PHONE_LABEL) ;
        if (!is_null($this->get_element_value($label)))
            $rpt->setSecondaryPhone(true) ;

        if (!is_null($this->get_element_value('Contact Information')))
            $rpt->setContactInformation(true) ;

        //  How many user options does this configuration support?

        $options = get_option(WPST_OPTION_USER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_USER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_USER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_USER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if (!is_null($this->get_element_value(get_option($lconst))))
                    $rpt->setOptionalField($oconst, true) ;

                //  Handle the possible filter

                if ($filters)
                {
                    if (get_option($oconst) == WPST_YES_NO)
                    {
                        if (!is_null($this->get_element_value(get_option($lconst) . FEFILTER)))
                        {
                            $rpt->setOptionalFieldFilter($oconst, true) ;
                            $rpt->setOptionalFieldFilterValue($oconst,
                                $this->get_element_value(get_option($lconst) . FEFILTERLB)) ;
                        }
                    }
                }
            }
        }

        //  Filters
 
        if ($filters)
        {
            if (!is_null($this->get_element_value('Contact Information' . FEFILTER)))
            {
                $rpt->setGenderFilter(true) ;
                $rpt->setGenderFilterValue($this->get_element_value('Contact Information' . FEFILTERLB)) ;
            }
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
        if ($this->get_element_value('Report') == WPST_GENERATE_STATIC_WEB_PAGE)
        {
            $csv = false ;
            $this->__report = new SwimTeamUsersReportGenerator() ;
            
        }
        else if ($this->get_element_value('Report') == WPST_GENERATE_CSV)
        {
            $csv = true ;
            $this->__report = new SwimTeamUsersReportGeneratorExportCSV() ;
        }
        else
        {
            return false ;
        }

        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        //  Set up the report based on form options

        $this->__set_report_options() ;

        //  Generate the report

        $rpt->setReportTitle('Swim Team Users Report') ;
        $rpt->generateReport() ;

        //  In CSV mode, also force the HTML report to be generated

        if ($csv) $rpt->generateReport(true) ;
        
        //  Build the message that goes back to the user

        $this->set_action_message(sprintf('Swim Team Users Report Generated,
            %s record%s returned.', $rpt->getRecordCount(),
            $rpt->getRecordCount() == 1 ? '' : 's')) ;

        return true ;
    }

    /**
     * container to hold success message
     *
     * @return container
     */
    function form_success()
    {
        $c = container() ;

        $c->add($this->_action_message) ;

        return $c ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display 'Generate' instead of the default 'Save'.
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Generate_Cancel() ;
    }
}

/**
 * Construct the Report Generator form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamUsersReportGeneratorForm
 */
class WpSwimTeamJobAssignmentsReportGeneratorForm extends WpSwimTeamUsersReportGeneratorForm
{
    /**
     * meet id property - used to track the swim meet
     */

    var $__meetid ;

    /**
     * Set the meet id property
     *
     * @param int - $id - meet id
     */
    function setMeetId($id)
    {
        $this->__meetid = $id ;
    }

    /**
     * Get the meet id property
     *
     * @return int - $id - meet id
     */
    function getMeetId()
    {
        return $this->__meetid ;
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
        $meetIds = $meet->getAllMeetIds(sprintf('seasonid="%s"', $seasonid)) ;

        //  Handle case where no meets have been scheduled yet

        if (!is_null($meetIds))
        {
            foreach ($meetIds as $meetId)
            {
                $meet->loadSwimMeetByMeetId($meetId['meetid']) ;
    
                if ($meet->getMeetType() == WPST_DUAL_MEET)
                    $opponent = SwimTeamTextMap::__mapOpponentSwimClubIdToText(
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
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  Pick up the form elements from the parent form
        parent::form_init_elements(false) ;

        //  Swim  Meet check box list

        $meets = new FECheckBoxList('Swim Meet', true, '400px', '100px');
        $meets->set_list_data($this->_swimmeetSelections()) ;
        $meets->enable_checkall(true) ;

        $this->add_element($meets) ;

        $jobposition = new FECheckBox('Position') ;
        $this->add_element($jobposition) ;

        $jobdescription = new FECheckBox('Description') ;
        $this->add_element($jobdescription) ;

        $jobduration = new FECheckBox('Duration') ;
        $this->add_element($jobduration) ;

        $jobtype = new FECheckBox('Type') ;
        $this->add_element($jobtype) ;

        $jobcredits = new FECheckBox('Credits') ;
        $this->add_element($jobcredits) ;

        $jobnotes = new FECheckBox('Notes') ;
        $this->add_element($jobnotes) ;

        $jobdurationfilter = new FECheckBox('Duration' . FEFILTER) ;
        $jobdurationfilter->set_disabled(true) ;
        $this->add_element($jobdurationfilter) ;

        $jobdurationfilterlb = new FEListBox('Duration' . FEFILTERLB, true, '100px');
        $jobdurationfilterlb->set_list_data(array(
             ucwords(WPST_JOB_DURATION_FULL_MEET) => WPST_JOB_DURATION_FULL_MEET
            ,ucwords(WPST_JOB_DURATION_PARTIAL_MEET) => WPST_JOB_DURATION_PARTIAL_MEET
            ,ucwords(WPST_JOB_DURATION_FULL_SEASON) => WPST_JOB_DURATION_FULL_SEASON
            ,ucwords(WPST_JOB_DURATION_PARTIAL_SEASON) => WPST_JOB_DURATION_PARTIAL_SEASON
            ,ucwords(WPST_JOB_DURATION_EVENT) => WPST_JOB_DURATION_EVENT
        )) ;
        $this->add_element($jobdurationfilterlb) ;

        $jobtypefilter = new FECheckBox('Type' . FEFILTER) ;
        $jobtypefilter->set_disabled(true) ;
        $this->add_element($jobtypefilter) ;

        $jobtypefilterlb = new FEListBox('Type' . FEFILTERLB, true, '100px');
        $jobtypefilterlb->set_list_data(array(
             ucwords(WPST_JOB_TYPE_VOLUNTEER) => WPST_JOB_TYPE_VOLUNTEER
            ,ucwords(WPST_JOB_TYPE_PAID) => WPST_JOB_TYPE_PAID
        )) ;
        $this->add_element($jobtypefilterlb) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Pick up the form initialization from the parent form
        parent::form_init_data(false) ;

        $this->set_element_value('Position', true) ;
        $this->set_element_value('Description', false) ;
        $this->set_element_value('Duration', true) ;
        $this->set_element_value('Type', false) ;
        $this->set_element_value('Credits', false) ;
        $this->set_element_value('Notes', true) ;
        $this->set_element_value('Duration' . FEFILTER, false) ;
        $this->set_element_value('Duration' . FEFILTERLB, WPST_JOB_DURATION_FULL_MEET) ;
        $this->set_element_value('Type' . FEFILTER, false) ;
        $this->set_element_value('Type' . FEFILTERLB, WPST_JOB_TYPE_VOLUNTEER) ;
    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $this->add_form_block('Swim Meets', $this->_swim_meet_options()) ;
        $this->add_form_block('Job Assignment Fields', $this->_job_assignment_options()) ;
        $this->add_form_block('Contact Fields', $this->_contact_options()) ;
        $this->add_form_block('Report Filters', $this->_report_filters()) ;
        $this->add_form_block('Report Output', $this->_send_report_to()) ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_swim_meet_options()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('Swim Meet')) ;

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_job_assignment_options()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('Position'),
            $this->element_form('Description')) ;

        $table->add_row($this->element_form('Duration'),
            $this->element_form('Type')) ;

        $table->add_row($this->element_form('Credits'),
            $this->element_form('Notes')) ;

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_report_filters()
    {
        //$table = parent::_report_filters() ;
        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($this->element_form('Duration' . FEFILTER),
            $this->element_form('Duration' . FEFILTERLB)) ;

        $table->add_row($this->element_form('Type' . FEFILTER),
            $this->element_form('Type' . FEFILTERLB)) ;

        return $table ;
    }

    /**
     * Set the report options based on form state
     *
     */
    function __set_report_options()
    {
        parent::__set_report_options(false) ;

        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        if (!is_null($this->get_element_value('Position')))
            $rpt->setJobPosition(true) ;

        if (!is_null($this->get_element_value('Description')))
            $rpt->setJobDescription(true) ;

        if (!is_null($this->get_element_value('Duration')))
            $rpt->setJobDuration(true) ;

        if (!is_null($this->get_element_value('Type')))
            $rpt->setJobType(true) ;

        if (!is_null($this->get_element_value('Credits')))
            $rpt->setJobCredits(true) ;

        if (!is_null($this->get_element_value('Notes')))
            $rpt->setJobNotes(true) ;

        $rpt->setSwimMeetIds($this->get_element_value('Swim Meet')) ;

        //  Filters
 
        if (!is_null($this->get_element_value('Duration' . FEFILTER)))
        {
            $rpt->setJobDurationFilter(true) ;
            $rpt->setJobDurationFilterValue($this->get_element_value('Duration' . FEFILTERLB)) ;
        }
 
        if (!is_null($this->get_element_value('Type' . FEFILTER)))
        {
            $rpt->setJobTypeFilter(true) ;
            $rpt->setJobTypeFilterValue($this->get_element_value('Type' . FEFILTERLB)) ;
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
        if ($this->get_element_value('Report') == WPST_GENERATE_STATIC_WEB_PAGE)
        {
            $csv = false ;
            $this->__report = new SwimTeamJobAssignmentsReportGenerator() ;
            
        }
        elseif ($this->get_element_value('Report') == WPST_GENERATE_CSV)
        {
            $csv = true ;
            $this->__report = new SwimTeamJobAssignmentsReportGeneratorExportCSV() ;
        }
        else
        {
            return false ;
        }

        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        //  Set up the report based on form options

        $this->__set_report_options() ;

        //  Generate the report

        $rpt->setReportTitle('Swim Team Job Assignments Report') ;
        $rpt->generateReport() ;
        
        $this->set_action_message(sprintf('Swim Team Job Assignments Report Generated,
            %s record%s returned.', $rpt->getRecordCount(),
            $rpt->getRecordCount() == 1 ? '' : 's')) ;

        return true ;
    }

}

/**
 * Construct the Report Generator form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamUsersReportGeneratorForm
 */
class WpSwimTeamJobCommitmentsReportGeneratorForm extends WpSwimTeamUsersReportGeneratorForm
{
    /**
     * season id property - used to track the swim season
     */

    var $__seasonid ;

    /**
     * Set the season id property
     *
     * @param int - $id - season id
     */
    function setMeetId($id)
    {
        $this->__seasonid = $id ;
    }

    /**
     * Get the season id property
     *
     * @return int - $id - season id
     */
    function getMeetId()
    {
        return $this->__seasonid ;
    }

    /**
     * Get the array of swim season key and value pairs
     *
     * @return mixed - array of swim season key value pairs
     */
    function _swimseasonSelections($seasonid = null)
    {
        $s = array() ;

        $season = new SwimTeamSeason() ;

        //  Season Id supplied?  If not, use the active season.

        if ($seasonid == null)
            $seasonid = $season->getActiveSeasonId() ;

        //  Find all of the seasons in the season

        $season = new SwimTeamSeason() ;
        //$seasonIds = $season->getAllSeasonIds(sprintf('seasonid="%s"', $seasonid)) ;
        $seasonIds = $season->getAllSeasonIds() ;

        //  Handle case where no seasons have been scheduled yet

        if (!is_null($seasonIds))
        {
            foreach ($seasonIds as $seasonId)
            {
                $season->loadSeasonById($seasonId['seasonid']) ;
    
                $seasonstart = date('M j, Y', strtotime($season->getSeasonStart())) ;
                $seasonend = date('M j, Y', strtotime($season->getSeasonEnd())) ;

                $s[sprintf('%s [%s - %s] (%s)', $season->getSeasonLabel(), $seasonstart,
                    $seasonend, $season->getSeasonStatus())] = $seasonId['seasonid'] ;
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
        //  Pick up the form elements from the parent form
        parent::form_init_elements(false) ;

        //  Swim  Meet check box list

        //$seasons = new FECheckBoxList('Swim Seasons', true, '100%', '100px');
        $seasons = new FEListBox('Swim Seasons', true, '100%', '100px');
        $seasons->set_list_data($this->_swimseasonSelections()) ;
        //$seasons->enable_checkall(true) ;

        $this->add_element($seasons) ;

        //$jobposition = new FECheckBox('Position') ;
        //$this->add_element($jobposition) ;

        //$jobdescription = new FECheckBox('Description') ;
        //$this->add_element($jobdescription) ;

        //$jobduration = new FECheckBox('Duration') ;
        //$this->add_element($jobduration) ;

        //$jobtype = new FECheckBox('Type') ;
        //$this->add_element($jobtype) ;

        //$jobcredits = new FECheckBox('Credits') ;
        //$this->add_element($jobcredits) ;

        //$jobnotes = new FECheckBox('Notes') ;
        //$this->add_element($jobnotes) ;

        //$jobdurationfilter = new FECheckBox('Duration' . FEFILTER) ;
        //$jobdurationfilter->set_disabled(true) ;
        //$this->add_element($jobdurationfilter) ;

        //$jobdurationfilterlb = new FEListBox('Duration' . FEFILTERLB, true, '100px');
        //$jobdurationfilterlb->set_list_data(array(
             //ucwords(WPST_JOB_DURATION_FULL_MEET) => WPST_JOB_DURATION_FULL_MEET
            //,ucwords(WPST_JOB_DURATION_PARTIAL_MEET) => WPST_JOB_DURATION_PARTIAL_MEET
            //,ucwords(WPST_JOB_DURATION_FULL_SEASON) => WPST_JOB_DURATION_FULL_SEASON
            //,ucwords(WPST_JOB_DURATION_PARTIAL_SEASON) => WPST_JOB_DURATION_PARTIAL_SEASON
            //,ucwords(WPST_JOB_DURATION_EVENT) => WPST_JOB_DURATION_EVENT
        //)) ;
        //$this->add_element($jobdurationfilterlb) ;

        //$jobtypefilter = new FECheckBox('Type' . FEFILTER) ;
        //$jobtypefilter->set_disabled(true) ;
        //$this->add_element($jobtypefilter) ;

        //$jobtypefilterlb = new FEListBox('Type' . FEFILTERLB, true, '100px');
        //$jobtypefilterlb->set_list_data(array(
             //ucwords(WPST_JOB_TYPE_VOLUNTEER) => WPST_JOB_TYPE_VOLUNTEER
            //,ucwords(WPST_JOB_TYPE_PAID) => WPST_JOB_TYPE_PAID
        //)) ;
        //$this->add_element($jobtypefilterlb) ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Pick up the form initialization from the parent form
        parent::form_init_data(false) ;

        //$this->set_element_value('Position', true) ;
        //$this->set_element_value('Description', false) ;
        //$this->set_element_value('Duration', true) ;
        //$this->set_element_value('Type', false) ;
        //$this->set_element_value('Credits', false) ;
        //$this->set_element_value('Notes', true) ;
        //$this->set_element_value('Duration' . FEFILTER, false) ;
        //$this->set_element_value('Duration' . FEFILTERLB, WPST_JOB_DURATION_FULL_MEET) ;
        //$this->set_element_value('Type' . FEFILTER, false) ;
        //$this->set_element_value('Type' . FEFILTERLB, WPST_JOB_TYPE_VOLUNTEER) ;
    }

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $this->add_form_block('Swim Seasons', $this->_swim_meet_options()) ;
        //$this->add_form_block('Job Commitment Fields', $this->_job_commitment_options()) ;
        $this->add_form_block('Contact Fields', $this->_contact_options()) ;
        //$this->add_form_block('Report Filters', $this->_report_filters()) ;
        $this->add_form_block('Report Output', $this->_send_report_to()) ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_swim_meet_options()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('Swim Seasons')) ;

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_job_commitment_options()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('Position'),
            $this->element_form('Description')) ;

        $table->add_row($this->element_form('Duration'),
            $this->element_form('Type')) ;

        $table->add_row($this->element_form('Credits'),
            $this->element_form('Notes')) ;

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_report_filters()
    {
        //$table = parent::_report_filters() ;
        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($this->element_form('Duration' . FEFILTER),
            $this->element_form('Duration' . FEFILTERLB)) ;

        $table->add_row($this->element_form('Type' . FEFILTER),
            $this->element_form('Type' . FEFILTERLB)) ;

        return $table ;
    }

    /**
     * Set the report options based on form state
     *
     */
    function __set_report_options()
    {
        parent::__set_report_options(false) ;

        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        //if (!is_null($this->get_element_value('Position')))
            //$rpt->setJobPosition(true) ;

        //if (!is_null($this->get_element_value('Description')))
            //$rpt->setJobDescription(true) ;

        //if (!is_null($this->get_element_value('Duration')))
            //$rpt->setJobDuration(true) ;

        //if (!is_null($this->get_element_value('Type')))
            //$rpt->setJobType(true) ;

        //if (!is_null($this->get_element_value('Credits')))
            //$rpt->setJobCredits(true) ;

        //if (!is_null($this->get_element_value('Notes')))
            //$rpt->setJobNotes(true) ;

        $rpt->setSeasonId($this->get_element_value('Swim Seasons')) ;

        //  Filters
 
        //if (!is_null($this->get_element_value('Duration' . FEFILTER)))
        //{
            //$rpt->setJobDurationFilter(true) ;
            //$rpt->setJobDurationFilterValue($this->get_element_value('Duration' . FEFILTERLB)) ;
        //}
 
        //if (!is_null($this->get_element_value('Type' . FEFILTER)))
        //{
            //$rpt->setJobTypeFilter(true) ;
            //$rpt->setJobTypeFilterValue($this->get_element_value('Type' . FEFILTERLB)) ;
        //}
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $seasonid = $this->get_element_value('Swim Seasons') ;

        if ($this->get_element_value('Report') == WPST_GENERATE_STATIC_WEB_PAGE)
        {
            $csv = false ;
            $this->__report = new SwimTeamJobCommitmentsReportGenerator() ;
            
        }
        else if ($this->get_element_value('Report') == WPST_GENERATE_CSV)
        {
            $csv = true ;
            $this->__report = new SwimTeamJobCommitmentsReportGeneratorExportCSV() ;
        }
        else
        {
            return false ;
        }

        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        //  Set up the report based on form options

        $this->__set_report_options() ;

        //  Generate the report

        $rpt->CalculateCredits($seasonid) ;

        $rpt->setReportTitle('Swim Team Job Commitments Report') ;
        $rpt->generateReport() ;
        
        $this->set_action_message(sprintf('Swim Team Job Commitments Report Generated,
            %s record%s returned.', $rpt->getRecordCount(),
            $rpt->getRecordCount() == 1 ? '' : 's')) ;

        return true ;
    }

}

/**
 * Construct the Report Generator form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimmersReportGeneratorForm extends WpSwimTeamForm
{
    /**
     * generated report
     */
    var $__report ;

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $firstname = new FECheckBox('First Name') ;
        $this->add_element($firstname) ;

        $middlename = new FECheckBox('Middle Name') ;
        $this->add_element($middlename) ;

        $nickname = new FECheckBox('Nickname') ;
        $this->add_element($nickname) ;

        $lastname = new FECheckBox('Last Name') ;
        $this->add_element($lastname) ;

        $gender = new FECheckBox('Gender') ;
        $this->add_element($gender) ;

        $birthdate = new FECheckBox('Birth Date') ;
        $this->add_element($birthdate) ;

        $age = new FECheckBox('Age') ;
        $this->add_element($age) ;

        $agegroup = new FECheckBox('Age Group') ;
        $this->add_element($agegroup) ;

        $primarycontact = new FECheckBox('Primary Contact') ;
        //$primarycontact->set_disabled(true) ;
        $this->add_element($primarycontact) ;

        $primarycontactdetail = new FECheckBox('Primary Contact Detail') ;
        //$primarycontactdetail->set_disabled(true) ;
        $this->add_element($primarycontactdetail) ;

        $secondarycontact = new FECheckBox('Secondary Contact') ;
        //$secondarycontact->set_disabled(true) ;
        $this->add_element($secondarycontact) ;

        $secondarycontactdetail = new FECheckBox('Secondary Contact Detail') ;
        //$secondarycontactdetail->set_disabled(true) ;
        $this->add_element($secondarycontactdetail) ;

        $status = new FECheckBox('Status') ;
        $this->add_element($status) ;

        $results = new FECheckBox('Results') ;
        $this->add_element($results) ;

        $swimmerlabel = new FECheckBox('Swimmer Label') ;
        $this->add_element($swimmerlabel) ;

        $websitreid = new FECheckBox('Web Site Id') ;
        $this->add_element($websitreid) ;

        $nicknameoverride = new FECheckBox('Nickname Override') ;
        $this->add_element($nicknameoverride) ;

        $internalid = new FECheckBox('Internal Id') ;
        $this->add_element($internalid) ;

        $genderfilter = new FECheckBox('Gender' . FEFILTER) ;
        $this->add_element($genderfilter) ;

        $genderfilterlb = new FEListBox('Gender' . FEFILTERLB, true, '100px');
        $genderfilterlb->set_list_data(array(
             ucfirst(WPST_GENDER_MALE) => WPST_GENDER_MALE
            ,ucfirst(WPST_GENDER_FEMALE) => WPST_GENDER_FEMALE
        )) ;
        $this->add_element($genderfilterlb) ;

        $statusfilter = new FECheckBox('Status' . FEFILTER) ;
        $this->add_element($statusfilter) ;

        $statusfilterlb = new FEListBox('Status' . FEFILTERLB, true, '100px');
        $statusfilterlb->set_list_data(array(
             ucfirst(WPST_ACTIVE) => WPST_ACTIVE
            ,ucfirst(WPST_INACTIVE) => WPST_INACTIVE
        )) ;
        $this->add_element($statusfilterlb) ;

        $resultsfilter = new FECheckBox('Results' . FEFILTER) ;
        $this->add_element($resultsfilter) ;

        $resultsfilterlb = new FEListBox('Results' . FEFILTERLB, true, '100px');
        $resultsfilterlb->set_list_data(array(
             ucfirst(WPST_PUBLIC) => WPST_PUBLIC
            ,ucfirst(WPST_PRIVATE) => WPST_PRIVATE
        )) ;
        $this->add_element($resultsfilterlb) ;

        $send_to = new FEListBox('Report', true, '200px');
        $send_to->set_list_data(array(
             ucfirst(WPST_GENERATE_STATIC_WEB_PAGE) => WPST_GENERATE_STATIC_WEB_PAGE
            //,ucfirst(WPST_GENERATE_DYNAMIC_WEB_PAGE) => WPST_GENERATE_DYNAMIC_WEB_PAGE
            ,ucfirst(WPST_GENERATE_CSV) => WPST_GENERATE_CSV
        )) ;
        $this->add_element($send_to) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        $of = array($options) ;
        $offcb = array($options) ;
        $offyn = array($options) ;

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;

            if (get_option($oconst) != WPST_DISABLED)
            {
                $of[$oc] = new FECheckBox(get_option($lconst)) ;
                $this->add_element($of[$oc]) ;

                // Field is enabled, can it be a filter?
 
                if (get_option($oconst) == WPST_YES_NO)
                {
                    $offcb[$oc] = new FECheckBox(get_option($lconst) . FEFILTER) ;
                    $offyn[$oc] = new FEYesNoListBox(get_option($lconst) . FEFILTERLB, false, '75px') ;
                    $this->add_element($offcb[$oc]) ;
                    $this->add_element($offyn[$oc]) ;
                }
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
        $this->set_element_value('First Name', true) ;
        $this->set_element_value('Middle Name', false) ;
        $this->set_element_value('Nickname', false) ;
        $this->set_element_value('Last Name', true) ;
        $this->set_element_value('Gender', true) ;
        $this->set_element_value('Birth Date', true) ;
        $this->set_element_value('Age', true) ;
        $this->set_element_value('Age Group', true) ;
        $this->set_element_value('Primary Contact', false) ;
        $this->set_element_value('Secondary Contact', false) ;
        $this->set_element_value('Primary Contact Detail', false) ;
        $this->set_element_value('Secondary Contact Detail', false) ;
        $this->set_element_value('Results', false) ;
        $this->set_element_value('Status', false) ;
        $this->set_element_value('Swimmer Label', true) ;
        $this->set_element_value('Web Site Id', false) ;
        $this->set_element_value('Nickname Override', false) ;
        $this->set_element_value('Internal Id', false) ;
        $this->set_element_value('Results' . FEFILTER, false) ;
        $this->set_element_value('Status' . FEFILTER, true) ;
        $this->set_element_value('Gender' . FEFILTERLB, WPST_GENDER_BOTH) ;
        $this->set_element_value('Status' . FEFILTERLB, WPST_ACTIVE) ;
        $this->set_element_value('Results' . FEFILTERLB, WPST_PUBLIC) ;
        $this->set_element_value('Report', WPST_GENERATE_STATIC_WEB_PAGE) ;
    }


    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    function form_content()
    {
        $this->add_form_block('Contact Fields', $this->_report_options()) ;
        $this->add_form_block('Report Filters', $this->_report_filters()) ;
        $this->add_form_block('Report Output', $this->_send_report_to()) ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_report_options()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style('border: 1px solid') ;

        $table->add_row($this->element_form('First Name'),
            $this->element_form('Middle Name')) ;

        $table->add_row($this->element_form('Last Name'),
            $this->element_form('Nickname')) ;

        $table->add_row($this->element_form('Birth Date'),
            $this->element_form('Gender')) ;

        $table->add_row($this->element_form('Age'),
            $this->element_form('Age Group')) ;

        $table->add_row($this->element_form('Primary Contact'),
            $this->element_form('Primary Contact Detail')) ;

        $table->add_row($this->element_form('Secondary Contact'),
            $this->element_form('Secondary Contact Detail')) ;

        $table->add_row($this->element_form('Status'),
            $this->element_form('Results')) ;

        $table->add_row($this->element_form('Swimmer Label'),
            $this->element_form('Web Site Id')) ;

        $table->add_row($this->element_form('Nickname Override'),
            $this->element_form('Internal Id')) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                $table->add_row($this->element_form(get_option($lconst))) ;
            }
        }

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_send_report_to()
    {
        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($this->element_form('Report')) ;

        return $table ;
    }

    /**
     * This is the method that builds the layout of
     * the Swim Team plugin options.
     *
     */
    function &_report_filters()
    {
        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($this->element_form('Gender' . FEFILTER),
            $this->element_form('Gender' . FEFILTERLB)) ;

        $table->add_row($this->element_form('Status' . FEFILTER),
            $this->element_form('Status' . FEFILTERLB)) ;

        $table->add_row($this->element_form('Results' . FEFILTER),
            $this->element_form('Results' . FEFILTERLB)) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;

        //  Load the swimmer options

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) == WPST_YES_NO)
            {
                $table->add_row(
                    $this->element_form(get_option($lconst) . FEFILTER),
                    $this->element_form(get_option($lconst) . FEFILTERLB)) ;
            }
        }

        return $table ;
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
        if ($this->get_element_value('Report') == WPST_GENERATE_STATIC_WEB_PAGE)
        {
            $csv = false ;
            $this->__report = new SwimTeamSwimmersReportGenerator() ;
            //$this->__report = new SwimTeamInfoTable('Swim Team Report', '800px') ;
            
        }
        else if ($this->get_element_value('Report') == WPST_GENERATE_CSV)
        {
            $csv = true ;
            $this->__report = new SwimTeamSwimmersReportGeneratorExportCSV() ;
        }
        else
        {
            return false ;
        }

        //  $rpt is a shortcut to the class property

        $rpt = &$this->__report ;

        if (!is_null($this->get_element_value('First Name')))
            $rpt->setFirstName(true) ;

        if (!is_null($this->get_element_value('Middle Name')))
            $rpt->setMiddleName(true) ;

        if (!is_null($this->get_element_value('Nickname')))
            $rpt->setNickName(true) ;

        if (!is_null($this->get_element_value('Last Name')))
            $rpt->setLastName(true) ;

        if (!is_null($this->get_element_value('Birth Date')))
            $rpt->setBirthDate(true) ;

        if (!is_null($this->get_element_value('Age')))
            $rpt->setAge(true) ;

        if (!is_null($this->get_element_value('Age Group')))
            $rpt->setAgeGroup(true) ;

        if (!is_null($this->get_element_value('Gender')))
            $rpt->setGender(true) ;

        if (!is_null($this->get_element_value('Primary Contact')))
            $rpt->setPrimaryContact(true) ;

        if (!is_null($this->get_element_value('Primary Contact Detail')))
            $rpt->setPrimaryContactDetail(true) ;

        if (!is_null($this->get_element_value('Secondary Contact')))
            $rpt->setSecondaryContact(true) ;

        if (!is_null($this->get_element_value('Secondary Contact Detail')))
            $rpt->setSecondaryContactDetail(true) ;

        if (!is_null($this->get_element_value('Status')))
            $rpt->setStatus(true) ;

        if (!is_null($this->get_element_value('Results')))
            $rpt->setResults(true) ;

        if (!is_null($this->get_element_value('Swimmer Label')))
            $rpt->setSwimmerLabel(true) ;

        if (!is_null($this->get_element_value('Web Site Id')))
            $rpt->setWebSiteId(true) ;

        if (!is_null($this->get_element_value('Nickname Override')))
            $rpt->setNickNameOverride(true) ;

        if (!is_null($this->get_element_value('Internal Id')))
            $rpt->setInternalId(true) ;

        //  How many swimmer options does this configuration support?

        $options = get_option(WPST_OPTION_SWIMMER_OPTION_COUNT) ;

        if ($options === false) $options = WPST_DEFAULT_SWIMMER_OPTION_COUNT ;
            
        //  Handle the optional fields

        for ($oc = 1 ; $oc <= $options ; $oc++)
        {
            $oconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc) ;
            $lconst = constant('WPST_OPTION_SWIMMER_OPTION' . $oc . '_LABEL') ;
                
            if (get_option($oconst) != WPST_DISABLED)
            {
                if (!is_null($this->get_element_value(get_option($lconst))))
                    $rpt->setOptionalField($oconst, true) ;

                //  Handle the possible filter
                if (get_option($oconst) == WPST_YES_NO)
                {
                    if (!is_null($this->get_element_value(get_option($lconst) . FEFILTER)))
                    {
                        $rpt->setOptionalFieldFilter($oconst, true) ;
                        $rpt->setOptionalFieldFilterValue($oconst,
                            $this->get_element_value(get_option($lconst) . FEFILTERLB)) ;
                    }
                }
            }
        }

        //  Filters
 
        if (!is_null($this->get_element_value('Gender' . FEFILTER)))
        {
            $rpt->setGenderFilter(true) ;
            $rpt->setGenderFilterValue($this->get_element_value('Gender' . FEFILTERLB)) ;
        }

        if (!is_null($this->get_element_value('Status' . FEFILTER)))
        {
            $rpt->setStatusFilter(true) ;
            $rpt->setStatusFilterValue($this->get_element_value('Status' . FEFILTERLB)) ;
        }

        if (!is_null($this->get_element_value('Results' . FEFILTER)))
        {
            $rpt->setResultsFilter(true) ;
            $rpt->setResultsFilterValue($this->get_element_value('Results' . FEFILTERLB)) ;
        }

        $rpt->generateReport() ;
        
        $this->set_action_message(sprintf('Swim Team Report Generated,
            %s record%s returned.', $rpt->getRecordCount(),
            $rpt->getRecordCount() == 1 ? '' : 's')) ;

        return true ;
    }

    /**
     * container to hold success message
     *
     * @return container
     */
    function form_success()
    {
        $c = container() ;
        $c->add($this->_action_message) ;

        return $c ;
    }

    /**
     * Overload form_content_buttons() method to have the
     * button display "Generate" instead of the default "Save".
     *
     */
    function form_content_buttons()
    {
        return $this->form_content_buttons_Generate_Cancel() ;
    }
}
?>
