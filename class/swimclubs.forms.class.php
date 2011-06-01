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
 * @subpackage SwimClub
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once("swimclubs.class.php") ;
require_once("team.forms.class.php") ;

/**
 * Construct the Add Age Group form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimClubAddForm extends WpSwimTeamTeamProfileForm
{
    /**
     * id property - used to track the age group record
     */

    var $__swimclubid ;

    /**
     * Set the Swim Club Id property
     */
    function setSwimClubId($id)
    {
        $this->__swimclubid = $id ;
    }

    /**
     * Get the Swim Club Id property
     */
    function getSwimClubId()
    {
        return $this->__swimclubid ;
    }

    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        //  Re-use all of the elements from the TeamProfile form

        parent::form_init_elements() ;

        $this->add_hidden_element("_action") ;
        $this->add_hidden_element("swimclubid") ;

        $contactname = new FEText("Contact Name", false, "250px") ;
        $this->add_element($contactname) ;

        $googlemapsurl = new FEUrl("Google Maps URL", false, "250px") ;
        $this->add_element($googlemapsurl) ;

        $mapquesturl = new FEUrl("MapQuest URL", false, "250px") ;
        $this->add_element($mapquesturl) ;

        $notes = new FETextArea("Notes", false, 5, 40, "400px") ;
        $this->add_element($notes) ;

        return ;
    }

    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        $this->set_hidden_element_value("_action", WPST_SWIMCLUBS_ADD_SWIMCLUB) ;
        //  Default pool length based on plugin settings

        $option = get_option(WPST_OPTION_TEAM_POOL_LENGTH) ;

        //  If option isn't stored in the database, use the default
        if ($option)
            $this->set_element_value("Pool Length", $option) ;
        else
            $this->set_element_value("Pool Length", WPST_DEFAULT_POOL_LENGTH) ;

        //  Default pool measurement units based on plugin settings

        $option = get_option(WPST_OPTION_TEAM_POOL_MEASUREMENT_UNITS) ;

        //  If option isn't stored in the database, use the default

        if ($option)
            $this->set_element_value("Units", $option) ;
        else
            $this->set_element_value("Units", WPST_DEFAULT_MEASUREMENT_UNITS) ;
        //  Default pool length based on plugin settings

        $option = get_option(WPST_OPTION_TEAM_POOL_LANES) ;

        //  If option isn't stored in the database, use the default
        if ($option)
            $this->set_element_value("Pool Lanes", $option) ;
        else
            $this->set_element_value("Pool Lanes", WPST_DEFAULT_POOL_LANES) ;

        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        if ($geography == WPST_US_ONLY)
        {
            $this->set_element_value("Country", ucwords(WPST_US_ONLY)) ;
            $this->set_element_value(get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL),
                 get_option(WPST_OPTION_TEAM_STATE_OR_PROVINCE)) ;
        }

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

        $table->add_row(html_td(null, null,
            $this->element_label("Pool Length")), html_td(null, null,
            $this->element_form("Pool Length"), $this->element_form("Units"))) ;

        $table->add_row($this->element_label("Pool Lanes"),
            $this->element_form("Pool Lanes")) ;

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

        $table->add_row($this->element_label("Web Site"),
            $this->element_form("Web Site")) ;

        $table->add_row($this->element_label("Contact Name"),
            $this->element_form("Contact Name")) ;

        $table->add_row($this->element_label("Email Address"),
            $this->element_form("Email Address")) ;

        $table->add_row($this->element_label("Google Maps URL"),
            $this->element_form("Google Maps URL")) ;

        $table->add_row(null, html_span(null, div_font8bold(
            "Google Maps URL not validating?", html_a("http://goo.gl",
            "Try using a Short URL"), "from Google."))) ;

        $table->add_row($this->element_label("MapQuest URL"),
            $this->element_form("MapQuest URL")) ;

        $table->add_row(null, html_span(null, div_font8bold(
            "MapQuest URL not validating?", html_a("http://goo.gl",
            "Try using a Short URL"), "from Google."))) ;

        $table->add_row($this->element_label("Notes"),
            $this->element_form("Notes")) ;

        $this->add_form_block(null, $table) ;
        //return $table ;
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

        if (($p == $s) && ($p != WPST_NULL_STRING))
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
        $sc = new SwimClubProfile() ;
        $sc->setTeamName($this->get_element_value("Team Name")) ;
        $sc->setClubOrPoolName($this->get_element_value("Club or Pool Name")) ;
        $sc->setPoolLength($this->get_element_value("Pool Length")) ;
        $sc->setPoolMeasurementUnits($this->get_element_value("Units")) ;
        $sc->setPoolLanes($this->get_element_value("Pool Lanes")) ;
        $sc->setStreet1($this->get_element_value("Street 1")) ;
        $sc->setStreet2($this->get_element_value("Street 2")) ;
        $sc->setStreet3($this->get_element_value("Street 3")) ;
        $sc->setCity($this->get_element_value("City")) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $sc->setStateOrProvince($this->get_element_value($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $sc->setPostalCode($this->get_element_value($label)) ;

        if (get_option(WPST_OPTION_GEOGRAPHY) == WPST_US_ONLY)
            $sc->setCountry(ucwords(WPST_US_ONLY)) ;
        else
            $sc->setCountry($this->get_element_value("Country")) ;

        $sc->setPrimaryPhone($this->get_element_value("Primary Phone")) ;
        $sc->setSecondaryPhone($this->get_element_value("Secondary Phone")) ;
        $sc->setContactName($this->get_element_value("Contact Name")) ;
        $sc->setEmailAddress($this->get_element_value("Email Address")) ;
        $sc->setWebSite($this->get_element_value("Web Site")) ;
        $sc->setGoogleMapsURL($this->get_element_value("Google Maps URL")) ;
        $sc->setMapQuestURL($this->get_element_value("MapQuest URL")) ;
        $sc->setNotes($this->get_element_value("Notes")) ;

        $success = $sc->addSwimClub() ;

        //  If successful, store the added age group id in so it can be used later.

        if ($success) 
        {
            $sc->setSwimClubId($success) ;
            $this->set_action_message("Swim Club successfully added.") ;
        }
        else
        {
            $this->set_action_message("Swim Club was not successfully added.") ;
        }

        return $success ;
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

/**
 * Construct the Update Swim Club Profile form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSwimClubUpdateForm extends WpSwimTeamSwimClubAddForm
{
    /**
     * This method is called only the first time the form
     * page is hit.  This enables u to query a DB and 
     * pre populate the FormElement objects with data.
     *
     */
    function form_init_data()
    {
        //  Re-use the parent form_init_data()

        parent::form_init_data() ;

        $this->set_hidden_element_value("_action", WPST_SWIMCLUBS_UPDATE_SWIMCLUB) ;

        $sc = new SwimClubProfile() ;
        $sc->loadSwimClubBySwimClubId($this->getSwimClubId()) ;

        $this->set_hidden_element_value("swimclubid", $this->getSwimClubId()) ;

         //  Initialize the form fields
        $this->set_element_value("Team Name", $sc->getTeamName()) ;
        $this->set_element_value("Club or Pool Name", $sc->getClubOrPoolName()) ;
        $this->set_element_value("Pool Length", $sc->getPoolLength()) ;
        $this->set_element_value("Units", $sc->getPoolMeasurementUnits()) ;
        $this->set_element_value("Pool Lanes", $sc->getPoolLanes()) ;

        $this->set_element_value("Street 1", $sc->getStreet1()) ;
        $this->set_element_value("Street 2", $sc->getStreet2()) ;
        $this->set_element_value("Street 3", $sc->getStreet3()) ;
        $this->set_element_value("City", $sc->getCity()) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $this->set_element_value($label, $sc->getStateOrProvince()) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $this->set_element_value($label, $sc->getPostalCode()) ;

        $this->set_element_value("Country", $sc->getCountry()) ;

        $this->set_element_value("Primary Phone", $sc->getPrimaryPhone()) ;
        $this->set_element_value("Secondary Phone", $sc->getSecondaryPhone()) ;
        $this->set_element_value("Contact Name", $sc->getContactName()) ;
        $this->set_element_value("Email Address", $sc->getEmailAddress()) ;
        $this->set_element_value("Web Site", $sc->getWebSite()) ;
        $this->set_element_value("Google Maps URL", $sc->getGoogleMapsURL()) ;
        $this->set_element_value("MapQuest URL", $sc->getMapQuestURL()) ;
        $this->set_element_value("Notes", $sc->getNotes()) ;
    }

    /**
     * This method is called ONLY after ALL validation has
     * passed.  This is the method that allows you to 
     * do something with the data, say insert/update records
     * in the DB.
     */
    function form_action()
    {
        $sc = new SwimClubProfile() ;
        $sc->setSwimClubId($this->get_hidden_element_value("swimclubid")) ;
        $sc->setTeamName($this->get_element_value("Team Name")) ;
        $sc->setClubOrPoolName($this->get_element_value("Club or Pool Name")) ;
        $sc->setPoolLength($this->get_element_value("Pool Length")) ;
        $sc->setPoolMeasurementUnits($this->get_element_value("Units")) ;
        $sc->setPoolLanes($this->get_element_value("Pool Lanes")) ;
        $sc->setStreet1($this->get_element_value("Street 1")) ;
        $sc->setStreet2($this->get_element_value("Street 2")) ;
        $sc->setStreet3($this->get_element_value("Street 3")) ;
        $sc->setCity($this->get_element_value("City")) ;

        $label = get_option(WPST_OPTION_USER_STATE_OR_PROVINCE_LABEL) ;
        $sc->setStateOrProvince($this->get_element_value($label)) ;

        $label = get_option(WPST_OPTION_USER_POSTAL_CODE_LABEL) ;
        $sc->setPostalCode($this->get_element_value($label)) ;

        if (get_option(WPST_OPTION_GEOGRAPHY) == WPST_US_ONLY)
            $sc->setCountry(ucwords(WPST_US_ONLY)) ;
        else
            $sc->setCountry($this->get_element_value("Country")) ;

        $sc->setPrimaryPhone($this->get_element_value("Primary Phone")) ;
        $sc->setSecondaryPhone($this->get_element_value("Secondary Phone")) ;
        $sc->setContactName($this->get_element_value("Contact Name")) ;
        $sc->setEmailAddress($this->get_element_value("Email Address")) ;
        $sc->setWebSite($this->get_element_value("Web Site")) ;
        $sc->setGoogleMapsURL($this->get_element_value("Google Maps URL")) ;
        $sc->setMapQuestURL($this->get_element_value("MapQuest URL")) ;
        $sc->setNotes($this->get_element_value("Notes")) ;

        $success = $sc->updateSwimClub() ;

        if ($success) 
        {
            $sc->setSwimClubId($success) ;
            $this->set_action_message("Swim Club successfully updated.") ;
        }
        else
        {
            $this->set_action_message("Swim Club was not successfully updated.") ;
        }

        return $success ;
    }
}
?>
