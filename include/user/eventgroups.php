<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Event Groups admin pevent content.
 *
 * $Id: eventgroups.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @packevent swimteam
 * @subpackevent admin
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once('events.class.php') ;
require_once('events.forms.class.php') ;
require_once('container.class.php') ;
require_once('textmap.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the jobs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class EventGroupsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build verbevent to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_PROFILE)),
            __('Display detailed information about a particular event group.')) ;
        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __('Add a new event group.  Use this action to define a new event
            group in the system.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __('Update an event group.  Use this action to update the details
            of an event group in the system.')) ;
        $table->add_row(html_b(__(WPST_ACTION_DELETE)),
            __('Delete an event group.  Use this action to delete an event group
            in the system.')) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamEventGroupsAdminGUIDataList('Swim Team Event Groups',
            '100%', 'eventgroupdescription') ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Construct the content of the EventGroups Tab Container
     */
    function EventGroupsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the pevent was reached.

        $div = html_div() ;
        //$div->add(sprintf('<h2>%s::%s</h2>', basename(__FILE__), __LINE__)) ;
        

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;
        $actions_allowed_without_eventgroupid = array(
            WPST_ACTION_ADD
        ) ;

        //print '<pre>' ;
        //print_r($scriptargs) ;
        //print '</pre>' ;
        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  The eventgroupid is the argument which must be
        //  dealt with differently for GET and POST operations

        //  Event Group Id passed?
        if (array_key_exists('eventgroupid', $scriptargs))
            $eventgroupid = $scriptargs['eventgroupid'] ;
        else if (array_key_exists('_eventgroupid', $scriptargs))
            $eventgroupid = is_array($scriptargs['_eventgroupid']) ?
                $scriptargs['_eventgroupid'][0] :  $scriptargs['_eventgroupid'] ;
        else
            $eventgroupid = null ;

        //  Show the list of swim meets or process an action.
        //  If there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (array_key_exists('_action', $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists('_form_action', $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        //  If one of the GDL controls was selected, then
        //  the action maybe confusing the processor.  Flush
        //  any action that doesn't make sense.  Look for the
        //  recorded action when this happens.

        if ($action == WPST_ACTION_SELECT_ACTION)
        {
            //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
            if (array_key_exists('_recorded_action', $scriptargs))
                $action = $scriptargs['_recorded_action'] ;
            else
                $action = null ;
        }

        if (empty($scriptargs) || is_null($action))
        {
            $div->set_style('clear: both;') ;
            $div->add(html_h3('Swim Team Event Groups')) ;

            $div->add($this->__buildGDL()) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Event Groups Action Summary') ;
        }
        else if (is_null($eventgroupid) && !in_array($action, $actions_allowed_without_eventgroupid))
        {
            $div->add(html_div('error fade',
                html_h4('You must select an event group in order to perform this action.'))) ;
            $div->add($this->__buildGDL()) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Event Groups Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_ADD:
                    $form = new WpSwimTeamEventGroupAddForm('Add Swim Team Event Group',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Event Group') ;
                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamEventGroupUpdateForm('Update Swim Team Event Group',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Event Group') ;
                    break ;

                case WPST_ACTION_DELETE:
                    $form = new WpSwimTeamEventGroupDeleteForm('Delete Swim Team Event Group',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setEventGroupId($eventgroupid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Event Group') ;
                    break ;

                case WPST_ACTION_EVENTS_REPORT:
                //case WPST_ACTION_PROFILE:
                    $c = container() ;
                    $profile = new SwimTeamEventGroupInfoTable(
                        SwimTeamTextMap::__mapEventGroupIdToText($eventgroupid), '700px') ;
                    $profile->constructSwimTeamEventGroupeInfoTable($eventgroupid) ;
                    $c->add($profile) ;

                    break ;

                case WPST_ACTION_MANAGE:
                case WPST_ACTION_EVENTS_LOAD:
                case WPST_ACTION_EVENTS_ADD:
                case WPST_ACTION_EVENTS_UPDATE:
                case WPST_ACTION_EVENTS_REORDER:
                case WPST_ACTION_EVENTS_MANAGE:
                case WPST_ACTION_EVENTS_IMPORT:
                case WPST_ACTION_EVENTS_PROFILE:
                case WPST_ACTION_EVENTS_DELETE:
                case WPST_ACTION_EVENTS_DELETE_ALL:

                    /*
                    $swimmeet = new SwimMeet() ;
                    $swimmeet->loadSwimMeetByMeetId($swimmeetid) ;
                    

                    //  Handle meets without an opponent ...

                    if ($swimmeet->getMeetType() === WPST_DUAL_MEET)
                    {
                        $opponent = new SwimClubProfile() ;
                        $opponent->loadSwimClubBySwimClubId($swimmeet->getOpponentSwimClubId()) ;
                    
                        $desc = sprintf("%s vs %s %s", $swimmeet->getMeetDate(),
                            $opponent->getClubOrPoolName(), $opponent->getTeamName()) ;
                    }
                    else
                    {
                        $desc = $swimmeet->getMeetDescription() ;

                        if (!empty($desc))
                            $desc = sprintf("%s %s", $swimmeet->getMeetDate(), $desc) ;
                    else
                        $desc = sprintf("%s %s", $swimmeet->getMeetDate(), ucwords($swimmeet->getMeetType())) ;
                    }
                     */

                    //  Leverage the Events tab management code

                    //printf('<h3>%s::%s<h3>', basename(__FILE__), __LINE__) ;
                    //var_dump($eventgroupid) ;
                    require_once('events.php') ;
                    $c = new AdminSwimTeamEventsTabContainer($eventgroupid,
                       SwimTeamTextMap::__mapEventGroupIdToText($eventgroupid)) ;

                    break ;

                    /*
                case WPST_ACTION_EVENTS_REORDER:
                    $c = container() ;
                    $ajax = new WpSwimTeamEventReorderByEventGroupAjaxForm($eventgroupid) ;
                    $c->add($ajax) ;
                    break ;
                     */

                default:
                    //printf('<h2>%s::%s</h2>', basename(__FILE__), __LINE__) ;
                    $div->add(html_h4(sprintf('Unsupported action "%s" requested.', $action))) ;

                    break ;
            }

            //  Not all actions are form based ...

            if (isset($form))
            {
                //  Create the form processor

                $fp = new FormProcessor($form) ;
                $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

                //  Display the form again even if processing was successful.

                $fp->set_render_form_after_success(false) ;

                //  If the Form Processor was succesful, display
                //  some statistics about the uploaded file.

                if ($fp->is_action_successful())
                {
                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $gdl = $this->__buildGDL() ;

                    $div->add($gdl) ;

	                $div->add(html_br(2), $form->form_success()) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Event Groups Action Summary') ;
                }
                else
                {
	                $div->add(html_br(2), $fp) ;
                }
            }
            else if (isset($c))
            {
                $div->add(html_br(2), $c) ;
                $div->add(SwimTeamGUIButtons::getButton('Return to Event Groups')) ;
            }
            else
            {
                $div->add(html_br(2), html_h4('No content to display.')) ;
            }

        }

        $this->add($div) ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
