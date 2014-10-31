<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: sdif.forms.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Plugin initialization.  This code will ensure that the
 * include_path is correct for phpHtmlLib, PEAR, and the local
 * site class and include files.
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage SDIFProfile
 * @version $Revision: 1065 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 *
 */

require_once(WPST_PATH . 'include/sdif.include.php') ;
require_once(WPST_PATH . 'class/sdif.class.php') ;
require_once(WPST_PATH . 'class/forms.class.php') ;

/**
 * Construct the Add Age Group form
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see WpSwimTeamForm
 */
class WpSwimTeamSDIFProfileForm extends WpSwimTeamForm
{
    /**
     * This method gets called EVERY time the object is
     * created.  It is used to build all of the 
     * FormElement objects used in this Form.
     *
     */
    function form_init_elements()
    {
        $this->add_hidden_element("UserId") ;
        $geography = get_option(WPST_OPTION_GEOGRAPHY) ;

        $orgcode = new FEListBox("Org Code", true, "200px");
        $orgcode->set_list_data(array(
            WPST_SDIF_ORG_CODE_USS_LABEL => WPST_SDIF_ORG_CODE_USS_VALUE
           ,WPST_SDIF_ORG_CODE_MASTERS_LABEL => WPST_SDIF_ORG_CODE_MASTERS_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_LABEL => WPST_SDIF_ORG_CODE_NCAA_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_I_LABEL => WPST_SDIF_ORG_CODE_NCAA_DIV_I_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_II_LABEL => WPST_SDIF_ORG_CODE_NCAA_DIV_II_VALUE
           ,WPST_SDIF_ORG_CODE_NCAA_DIV_III_LABEL => WPST_SDIF_ORG_CODE_NCAA_DIV_III_VALUE
           ,WPST_SDIF_ORG_CODE_YMCA_LABEL => WPST_SDIF_ORG_CODE_YMCA_VALUE
           ,WPST_SDIF_ORG_CODE_FINA_LABEL => WPST_SDIF_ORG_CODE_FINA_VALUE
           ,WPST_SDIF_ORG_CODE_HIGH_SCHOOL_LABEL => WPST_SDIF_ORG_CODE_HIGH_SCHOOL_VALUE
        )) ;
        $this->add_element($orgcode) ;

        $teamcode = new FEText("Team Code", true, "200px") ;
        $this->add_element($teamcode) ;

        $lsccode = new FEListBox("LSC Code", true, "200px");
        $lsccode->set_list_data(array(
            WPST_SDIF_LSC_CODE_ADIRONDACK_LABEL => WPST_SDIF_LSC_CODE_ADIRONDACK_VALUE
           ,WPST_SDIF_LSC_CODE_ALASKA_LABEL => WPST_SDIF_LSC_CODE_ALASKA_VALUE
           ,WPST_SDIF_LSC_CODE_ALLEGHENY_MOUNTAIN_LABEL => WPST_SDIF_LSC_CODE_ALLEGHENY_MOUNTAIN_VALUE
           ,WPST_SDIF_LSC_CODE_ARKANSAS_LABEL => WPST_SDIF_LSC_CODE_ARKANSAS_VALUE
           ,WPST_SDIF_LSC_CODE_ARIZONA_LABEL => WPST_SDIF_LSC_CODE_ARIZONA_VALUE
           ,WPST_SDIF_LSC_CODE_BORDER_LABEL => WPST_SDIF_LSC_CODE_BORDER_VALUE
           ,WPST_SDIF_LSC_CODE_CENTRAL_CALIFORNIA_LABEL => WPST_SDIF_LSC_CODE_CENTRAL_CALIFORNIA_VALUE
           ,WPST_SDIF_LSC_CODE_COLORADO_LABEL => WPST_SDIF_LSC_CODE_COLORADO_VALUE
           ,WPST_SDIF_LSC_CODE_CONNECTICUT_LABEL => WPST_SDIF_LSC_CODE_CONNECTICUT_VALUE
           ,WPST_SDIF_LSC_CODE_FLORIDA_GOLD_COAST_LABEL => WPST_SDIF_LSC_CODE_FLORIDA_GOLD_COAST_VALUE
           ,WPST_SDIF_LSC_CODE_FLORIDA_LABEL => WPST_SDIF_LSC_CODE_FLORIDA_VALUE
           ,WPST_SDIF_LSC_CODE_GEORGIA_LABEL => WPST_SDIF_LSC_CODE_GEORGIA_VALUE
           ,WPST_SDIF_LSC_CODE_GULF_LABEL => WPST_SDIF_LSC_CODE_GULF_VALUE
           ,WPST_SDIF_LSC_CODE_HAWAII_LABEL => WPST_SDIF_LSC_CODE_HAWAII_VALUE
           ,WPST_SDIF_LSC_CODE_IOWA_LABEL => WPST_SDIF_LSC_CODE_IOWA_VALUE
           ,WPST_SDIF_LSC_CODE_INLAND_EMPIRE_LABEL => WPST_SDIF_LSC_CODE_INLAND_EMPIRE_VALUE
           ,WPST_SDIF_LSC_CODE_ILLINOIS_LABEL => WPST_SDIF_LSC_CODE_ILLINOIS_VALUE
           ,WPST_SDIF_LSC_CODE_INDIANA_LABEL => WPST_SDIF_LSC_CODE_INDIANA_VALUE
           ,WPST_SDIF_LSC_CODE_KENTUCKY_LABEL => WPST_SDIF_LSC_CODE_KENTUCKY_VALUE
           ,WPST_SDIF_LSC_CODE_LOUISIANA_LABEL => WPST_SDIF_LSC_CODE_LOUISIANA_VALUE
           ,WPST_SDIF_LSC_CODE_LAKE_ERIE_LABEL => WPST_SDIF_LSC_CODE_LAKE_ERIE_VALUE
           ,WPST_SDIF_LSC_CODE_MIDDLE_ATLANTIC_LABEL => WPST_SDIF_LSC_CODE_MIDDLE_ATLANTIC_VALUE
           ,WPST_SDIF_LSC_CODE_MARYLAND_LABEL => WPST_SDIF_LSC_CODE_MARYLAND_VALUE
           ,WPST_SDIF_LSC_CODE_MAINE_LABEL => WPST_SDIF_LSC_CODE_MAINE_VALUE
           ,WPST_SDIF_LSC_CODE_MINNESOTA_LABEL => WPST_SDIF_LSC_CODE_MINNESOTA_VALUE
           ,WPST_SDIF_LSC_CODE_MICHIGAN_LABEL => WPST_SDIF_LSC_CODE_MICHIGAN_VALUE
           ,WPST_SDIF_LSC_CODE_METROPOLITAN_LABEL => WPST_SDIF_LSC_CODE_METROPOLITAN_VALUE
           ,WPST_SDIF_LSC_CODE_MISSISSIPPI_LABEL => WPST_SDIF_LSC_CODE_MISSISSIPPI_VALUE
           ,WPST_SDIF_LSC_CODE_MONTANA_LABEL => WPST_SDIF_LSC_CODE_MONTANA_VALUE
           ,WPST_SDIF_LSC_CODE_MISSOURI_VALLEY_LABEL => WPST_SDIF_LSC_CODE_MISSOURI_VALLEY_VALUE
           ,WPST_SDIF_LSC_CODE_MIDWESTERN_LABEL => WPST_SDIF_LSC_CODE_MIDWESTERN_VALUE
           ,WPST_SDIF_LSC_CODE_NORTH_CAROLINA_LABEL => WPST_SDIF_LSC_CODE_NORTH_CAROLINA_VALUE
           ,WPST_SDIF_LSC_CODE_NORTH_DAKOTA_LABEL => WPST_SDIF_LSC_CODE_NORTH_DAKOTA_VALUE
           ,WPST_SDIF_LSC_CODE_NEW_ENGLAND_LABEL => WPST_SDIF_LSC_CODE_NEW_ENGLAND_VALUE
           ,WPST_SDIF_LSC_CODE_NIAGARA_LABEL => WPST_SDIF_LSC_CODE_NIAGARA_VALUE
           ,WPST_SDIF_LSC_CODE_NEW_JERSEY_LABEL => WPST_SDIF_LSC_CODE_NEW_JERSEY_VALUE
           ,WPST_SDIF_LSC_CODE_NEW_MEXICO_LABEL => WPST_SDIF_LSC_CODE_NEW_MEXICO_VALUE
           ,WPST_SDIF_LSC_CODE_NORTH_TEXAS_LABEL => WPST_SDIF_LSC_CODE_NORTH_TEXAS_VALUE
           ,WPST_SDIF_LSC_CODE_OHIO_LABEL => WPST_SDIF_LSC_CODE_OHIO_VALUE
           ,WPST_SDIF_LSC_CODE_OKLAHOMA_LABEL => WPST_SDIF_LSC_CODE_OKLAHOMA_VALUE
           ,WPST_SDIF_LSC_CODE_OREGON_LABEL => WPST_SDIF_LSC_CODE_OREGON_VALUE
           ,WPST_SDIF_LSC_CODE_OZARK_LABEL => WPST_SDIF_LSC_CODE_OZARK_VALUE
           ,WPST_SDIF_LSC_CODE_PACIFIC_LABEL => WPST_SDIF_LSC_CODE_PACIFIC_VALUE
           ,WPST_SDIF_LSC_CODE_PACIFIC_NORTHWEST_LABEL => WPST_SDIF_LSC_CODE_PACIFIC_NORTHWEST_VALUE
           ,WPST_SDIF_LSC_CODE_POTOMAC_VALLEY_LABEL => WPST_SDIF_LSC_CODE_POTOMAC_VALLEY_VALUE
           ,WPST_SDIF_LSC_CODE_SOUTH_CAROLINA_LABEL => WPST_SDIF_LSC_CODE_SOUTH_CAROLINA_VALUE
           ,WPST_SDIF_LSC_CODE_SOUTH_DAKOTA_LABEL => WPST_SDIF_LSC_CODE_SOUTH_DAKOTA_VALUE
           ,WPST_SDIF_LSC_CODE_SOUTHEASTERN_LABEL => WPST_SDIF_LSC_CODE_SOUTHEASTERN_VALUE
           ,WPST_SDIF_LSC_CODE_SOUTHERN_CALIFORNIA_LABEL => WPST_SDIF_LSC_CODE_SOUTHERN_CALIFORNIA_VALUE
           ,WPST_SDIF_LSC_CODE_SAN_DIEGO_IMPERIAL_LABEL => WPST_SDIF_LSC_CODE_SAN_DIEGO_IMPERIAL_VALUE
           ,WPST_SDIF_LSC_CODE_WEST_TEXAS_LABEL => WPST_SDIF_LSC_CODE_WEST_TEXAS_VALUE
           ,WPST_SDIF_LSC_CODE_SIERRA_NEVADA_LABEL => WPST_SDIF_LSC_CODE_SIERRA_NEVADA_VALUE
           ,WPST_SDIF_LSC_CODE_SNAKE_RIVER_LABEL => WPST_SDIF_LSC_CODE_SNAKE_RIVER_VALUE
           ,WPST_SDIF_LSC_CODE_SOUTH_TEXAS_LABEL => WPST_SDIF_LSC_CODE_SOUTH_TEXAS_VALUE
           ,WPST_SDIF_LSC_CODE_UTAH_LABEL => WPST_SDIF_LSC_CODE_UTAH_VALUE
           ,WPST_SDIF_LSC_CODE_VIRGINIA_LABEL => WPST_SDIF_LSC_CODE_VIRGINIA_VALUE
           ,WPST_SDIF_LSC_CODE_WISCONSIN_LABEL => WPST_SDIF_LSC_CODE_WISCONSIN_VALUE
           ,WPST_SDIF_LSC_CODE_WEST_VIRGINIA_LABEL => WPST_SDIF_LSC_CODE_WEST_VIRGINIA_VALUE
           ,WPST_SDIF_LSC_CODE_WYOMING_LABEL => WPST_SDIF_LSC_CODE_WYOMING_VALUE
            
        )) ;
        $this->add_element($lsccode) ;

        $countrycode = new FEListBox("Country Code", true, "200px");
        $countrycode->set_list_data(SDIFCodeTableMappings::GetCountryCodes()) ;
        $countrycode->set_readonly($geography == WPST_US_ONLY) ;

        $this->add_element($countrycode) ;

        $regioncode = new FEListBox("Region Code", true, "200px");
        $regioncode->set_list_data(array(
             WPST_SDIF_REGION_CODE_REGION_1_LABEL => WPST_SDIF_REGION_CODE_REGION_1_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_2_LABEL => WPST_SDIF_REGION_CODE_REGION_2_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_3_LABEL => WPST_SDIF_REGION_CODE_REGION_3_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_4_LABEL => WPST_SDIF_REGION_CODE_REGION_4_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_5_LABEL => WPST_SDIF_REGION_CODE_REGION_5_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_6_LABEL => WPST_SDIF_REGION_CODE_REGION_6_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_7_LABEL => WPST_SDIF_REGION_CODE_REGION_7_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_8_LABEL => WPST_SDIF_REGION_CODE_REGION_8_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_9_LABEL => WPST_SDIF_REGION_CODE_REGION_9_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_10_LABEL => WPST_SDIF_REGION_CODE_REGION_10_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_11_LABEL => WPST_SDIF_REGION_CODE_REGION_11_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_12_LABEL => WPST_SDIF_REGION_CODE_REGION_12_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_13_LABEL => WPST_SDIF_REGION_CODE_REGION_13_VALUE
            ,WPST_SDIF_REGION_CODE_REGION_14_LABEL => WPST_SDIF_REGION_CODE_REGION_14_VALUE
        )) ;
        $this->add_element($regioncode) ;

        $swimmeridformat = new FEListBox("Swimmer Id Format", true, "200px");
        $swimmeridformat->set_list_data(array(
             WPST_SDIF_SWIMMER_ID_FORMAT_USA_SWIMMING => WPST_SDIF_SWIMMER_ID_FORMAT_USA_SWIMMING
            ,WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL => WPST_SDIF_SWIMMER_ID_FORMAT_SWIMMER_LABEL
            ,WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID => WPST_SDIF_SWIMMER_ID_FORMAT_WPST_ID
        )) ;
        $this->add_element($swimmeridformat) ;

        $usenickname = new FEYesNoListBox("Override Firstname with Nickname",
            true, "100px", null, WPST_YES, WPST_NO) ;
        $this->add_element($usenickname) ;

        $useagegroupage = new FEYesNoListBox("Use Age Group Age",
            true, "100px", null, WPST_YES, WPST_NO) ;
        $this->add_element($useagegroupage) ;
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

        $p = new SDIFProfile() ;
        $p->loadSDIFProfile() ;
        
        //  Initialize the form fields
        $this->set_element_value("Org Code", $p->getOrgCode()) ;
        $this->set_element_value("Team Code", $p->getTeamCode()) ;
        $this->set_element_value("LSC Code", $p->getLSCCode()) ;
        $this->set_element_value("Country Code", $p->getCountryCode()) ;
        $this->set_element_value("Region Code", $p->getRegionCode()) ;
        $this->set_element_value("Swimmer Id Format", $p->getSwimmerIdFormat()) ;
        $this->set_element_value("Override Firstname with Nickname", $p->getUseNickName()) ;
        $this->set_element_value("Use Age Group Age", $p->getUseAgeGroupAge()) ;
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

        $table->add_row($this->element_label("Org Code"),
            $this->element_form("Org Code")) ;

        $table->add_row($this->element_label("Team Code"),
            $this->element_form("Team Code")) ;

        $table->add_row($this->element_label("LSC Code"),
            $this->element_form("LSC Code")) ;

        $table->add_row($this->element_label("Country Code"),
            $this->element_form("Country Code")) ;

        $table->add_row($this->element_label("Region Code"),
            $this->element_form("Region Code")) ;

        $table->add_row($this->element_label("Swimmer Id Format"),
            $this->element_form("Swimmer Id Format")) ;

        $table->add_row($this->element_label("Override Firstname with Nickname"),
            $this->element_form("Override Firstname with Nickname")) ;

        $table->add_row($this->element_label("Use Age Group Age"),
            $this->element_form("Use Age Group Age")) ;

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

        //  First two characters of Team Code must
        //  match the LSC code

        $o = $this->get_element_value("Org Code") ;
        $t = $this->get_element_value("Team Code") ;
        $l = $this->get_element_value("LSC Code") ;
        $c = $this->get_element_value("Country Code") ;
        $r = $this->get_element_value("Region Code") ;
        $f = $this->get_element_value("Swimmer Id Format") ;

        if (substr($t, 0, 2) != $l)
        {
            $this->add_error("Team Code", "First two characters of Team Code must match LSC Code.") ;
            $valid = false ;
        }
        
        if ($c != $c)
        {
            $this->add_error("Country Code", "Country Code is inconsistent with team profile.") ;
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
        $p = new SDIFProfile() ;

        $p->setOrgCode($this->get_element_value("Org Code")) ;
        $p->setTeamCode($this->get_element_value("Team Code")) ;
        $p->setLSCCode($this->get_element_value("LSC Code")) ;
        $p->setCountryCode($this->get_element_value("Country Code")) ;
        $p->setRegionCode($this->get_element_value("Region Code")) ;
        $p->setSwimmerIdFormat($this->get_element_value("Swimmer Id Format")) ;
        $p->setUseNickName($this->get_element_value("Override Firstname with Nickname")) ;
        $p->setUseAgeGroupAge($this->get_element_value("Use Age Group Age")) ;

        $p->updateSDIFProfile() ;

        $this->set_action_message('Swim Team SDIF profile updated.') ;

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
