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
 * @subpackage TeamProfile
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once("team.class.php") ;
require_once("forms.class.php") ;

/**
 * Construct the Add Age Group form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamTeamProfileForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $teamname = new FEText("Team Name", true, "250px") ;
        //$teamname->set_disabled(true) ;
        $this->add_element($teamname) ;

        $cluborpoolname = new FEText("Club or Pool Name", true, "250px") ;
        //$cluborpoolname->set_disabled(true) ;
        $this->add_element($cluborpoolname) ;

        $this->add_hidden_element("UserId") ;

        $street1 = new FEText("Street 1", true, "250px") ;
        $this->add_element($street1) ;
        $street2 = new FEText("Street 2", false, "250px") ;
        $this->add_element($street2) ;
        $street3 = new FEText("Street 3", false, "250px") ;
        $this->add_element($street3) ;

        $city = new FEText("City", true, "200px") ;
        $this->add_element($city) ;

        //  How to handle the portion of the address which is
        //  much different for the US than the rest of the world.
 
        //  Check the options!
        
        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;

        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        if ($geography == WPST_US_ONLY)
            $state = new FEUnitedStates($label, true, "200px") ;
        else
            $state = new FEText($label, true, "250px") ;

        $this->add_element($state) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;

        if ($geography == WPST_US_ONLY)
            $postalcode = new FEZipcode($label, true, "100px") ;
        else
            $postalcode = new FEText($label, true, "200px") ;

        $this->add_element($postalcode) ;

        //  Country is handled - EU has a drop down,
        //  US is fixed and can't be changed, all others
        //  receive a text box.
 
        if ($geography == WPST_EU_ONLY)
            $country = new FEEuropeanUnion("Country", true, "150px") ;
        else
            $country = new FEText("Country", true, "200px") ;

        if ($geography == WPST_US_ONLY)
            $country->set_disabled(true) ;
        $this->add_element($country) ;

        $primaryphone = new FEText("Primary Phone", false, "150px") ;
        $this->add_element($primaryphone) ;

        $secondaryphone = new FEText("Secondary Phone", false, "150px") ;
        $this->add_element($secondaryphone) ;

        $emailaddress = new FEEmail("Email Address", false, "250px") ;
        $this->add_element($emailaddress) ;

        $website = new FEUrl("Web Site", false, "250px") ;
        $this->add_element($website) ;

        $poollength = new FEText("Pool Length", true, "30px") ;
        $this->add_element($poollength) ;

        $poolunits = new FEListBox("Units", true, "150px");
        $poolunits->set_list_data(array(
             ucfirst(WPST_YARDS) => WPST_YARDS
            ,ucfirst(WPST_METERS) => WPST_METERS
        )) ;
        $this->add_element($poolunits) ;

        $poollanes = new FEText("Pool Lanes", true, "30px") ;
        $this->add_element($poollanes) ;
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

        //  Need to pass the WP UserId along to the next step
        $this->set_hidden_element_value("UserId", $userdata->ID) ;

        $p = new SwimTeamProfile() ;
        $p->loadTeamProfile() ;

         //  Initialize the form fields
        $this->set_element_value("Team Name", $p->getTeamName()) ;
        $this->set_element_value("Club or Pool Name", $p->getClubOrPoolName()) ;

        $this->set_element_value("Street 1", $p->getStreet1()) ;
        $this->set_element_value("Street 2", $p->getStreet2()) ;
        $this->set_element_value("Street 3", $p->getStreet3()) ;
        $this->set_element_value("City", $p->getCity()) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $this->set_element_value($label, $p->getStateOrProvince()) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $this->set_element_value($label, $p->getPostalCode()) ;

        $this->set_element_value("Country", $p->getCountry()) ;

        $this->set_element_value("Primary Phone", $p->getPrimaryPhone()) ;
        $this->set_element_value("Secondary Phone", $p->getSecondaryPhone()) ;
        $this->set_element_value("Email Address", $p->getEmailAddress()) ;
        $this->set_element_value("Web Site", $p->getWebSite()) ;
        $this->set_element_value("Pool Length", $p->getPoolLength()) ;
        $this->set_element_value("Units", $p->getPoolMeasurementUnits()) ;
        $this->set_element_value("Pool Lanes", $p->getPoolLanes()) ;
    }

    /**
     * This method is called by the StandardFormContent object
     * to allow you to build the 'blocks' of fields you want to
     * display.  Each form block will live inside a fieldset tag
     * with the a title. 
     *
     * In this example we have 2 form 'blocks'.
     */
    /*
    function form_content()
    {
	    $tabs = new ActiveTab('100%', '500px') ;
	    $tabs->add_tab("Team Profile", $this->_team_profile()) ;
	    $tabs->add_tab("Team2 Profile", $this->_team_profile()) ;
	    $tabs->add_tab("SDIF Profile", $this->_sdif_profile()) ;

        //  In order to get the ActiveTab to work on a form, the
        //  Javascript must be manually added (this is a bug).

        $script = html_script() ;
        $script->add($tabs->get_javascript()) ;

        //  Add the ActiveTab widget and its Javascript to a table.

        $table = html_table($this->_width, 0, 4) ;

        $table->add_row($tabs) ;
        $div = html_div() ;
        $div->add($script, $table) ;

	    $this->add_form_block(null, $div) ;
    }
     */

    /**
     * This is the method that builds the layout of where the
     * FormElements will live.  You can lay it out any way
     * you like.
     *
     */
    //function &_team_profile()
    function form_content()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style("border: 1px solid") ;

        $table->add_row($this->element_label("Team Name"),
            $this->element_form("Team Name")) ;

        $table->add_row($this->element_label("Club or Pool Name"),
            $this->element_form("Club or Pool Name")) ;

        $table->add_row($this->element_label("Street 1"),
            $this->element_form("Street 1")) ;

        $table->add_row($this->element_label("Street 2"),
            $this->element_form("Street 2")) ;

        $table->add_row($this->element_label("Street 3"),
            $this->element_form("Street 3")) ;

        $table->add_row($this->element_label("City"),
            $this->element_form("City")) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;

        $table->add_row($this->element_label($label),
            $this->element_form($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;

        $table->add_row($this->element_label($label),
            $this->element_form($label)) ;

        $table->add_row($this->element_label("Country"),
            $this->element_form("Country")) ;

        $table->add_row($this->element_label("Primary Phone"),
            $this->element_form("Primary Phone")) ;

        $table->add_row($this->element_label("Secondary Phone"),
            $this->element_form("Secondary Phone")) ;

        $table->add_row($this->element_label("Email Address"),
            $this->element_form("Email Address")) ;

        $table->add_row($this->element_label("Web Site"),
            $this->element_form("Web Site")) ;

        $table->add_row(html_td(null, null,
            $this->element_label("Pool Length")), html_td(null, null,
            $this->element_form("Pool Length"), $this->element_form("Units"))) ;

        $table->add_row($this->element_label("Pool Lanes"),
            $this->element_form("Pool Lanes")) ;

        $this->add_form_block(null, $table) ;
        //return $table ;
    }

    function &_sdif_profile()
    {
        $table = html_table($this->_width, 0, 4) ;
        //$table->set_style("border: 1px solid") ;

        $table->add_row($this->element_label("SDIF"),
            $this->element_form("SDIF")) ;

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
        $valid = true ;

        //  Make sure phone numbers are unique

        $p = $this->get_element_value("Primary Phone") ;
        $s = $this->get_element_value("Secondary Phone") ;

        if ($p == $s)
        {
            $this->add_error("Primary Phone", "Primary Phone is the same as the Secondary Phone.") ;
            $this->add_error("Secondary Phone", "Secondary Phone is the same as the Primary Phone.") ;
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
        $p = new SwimTeamProfile() ;
        $p->setTeamName($this->get_element_value("Team Name")) ;
        $p->setClubOrPoolName($this->get_element_value("Club or Pool Name")) ;
        $p->setStreet1($this->get_element_value("Street 1")) ;
        $p->setStreet2($this->get_element_value("Street 2")) ;
        $p->setStreet3($this->get_element_value("Street 3")) ;
        $p->setCity($this->get_element_value("City")) ;
        $p->setCity($this->get_element_value("City")) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $p->setStateOrProvince($this->get_element_value($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $p->setPostalCode($this->get_element_value($label)) ;

        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        if ($geography == WPST_US_ONLY)
            $p->setCountry(ucwords(WPST_US_ONLY)) ;
        else
            $p->setCountry($this->get_element_value("Country")) ;

        $p->setPrimaryPhone($this->get_element_value("Primary Phone")) ;
        $p->setSecondaryPhone($this->get_element_value("Secondary Phone")) ;
        $p->setEmailAddress($this->get_element_value("Email Address")) ;
        $p->setWebSite($this->get_element_value("Web Site")) ;
        $p->setPoolLength($this->get_element_value("Pool Length")) ;
        $p->setPoolMeasurementUnits($this->get_element_value("Units")) ;
        $p->setPoolLanes($this->get_element_value("Pool Lanes")) ;

        $p->updateTeamProfile() ;

        $this->set_action_message("Swim Team profile updated.") ;

        return true ;
    }

    /**
     * Build success container
     *
     * @return container
     */
    function form_success()
    {
        $container = container() ;
        $container->add($this->_action_message) ;

        return $container ;
    }
}
?>
