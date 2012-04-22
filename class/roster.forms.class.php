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
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage Swimmers
 * @version $Revision$
 * @lastmodified $Author$
 * @lastmodifiedby $Date$
 *
 */

require_once('forms.class.php') ;
require_once('swimmers.class.php') ;
require_once('seasons.class.php') ;
require_once('roster.class.php') ;

/**
 * Construct the Register Swimmer form
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
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
 * @author Mike Walsh <mike_walsh@mindspring.com>
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
 * @author Mike Walsh <mike_walsh@mindspring.com>
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
?>
