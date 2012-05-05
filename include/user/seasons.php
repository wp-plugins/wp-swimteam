<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Seasons admin page content.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once('seasons.class.php') ;
require_once('seasons.forms.class.php') ;
require_once('jobs.forms.class.php') ;
require_once('container.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the jobs
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class SeasonsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return TABLEtag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_ADD)),
            __('Add a swim season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __('Update a swim season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_DELETE)),
            __('Delete a swim season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_JOBS)),
            __('Assign a user to a specific job assigment.')) ;

        return $table ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamSeasonsAdminGUIDataList('Swim Team Seasons',
            '100%', 'season_start', true) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }

    /**
     * Construct the content of the Seasons Tab Container
     */
    function SeasonsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->add(html_br(), html_h3('Swim Team Seasons')) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;
        $actions_allowed_without_seasonid = array(
            WPST_ACTION_ADD
        ) ;

        //  The seasonid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists('seasonid', $scriptargs))
            $seasonid = $scriptargs['seasonid'] ;
        else if (array_key_exists('_seasonid', $scriptargs))
            $seasonid = $scriptargs['_seasonid'] ;
        else if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
            $seasonid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else
            $seasonid = null ;

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of age groups or process an action.
        //  If there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (array_key_exists('_action', $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists('_form_action', $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        if (empty($scriptargs) || is_null($action))
        {
            $gdl = $this->__buildGDL() ;

            $div->add($gdl) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Seasons Action Summary') ;
        }
        else if (is_null($seasonid) && !in_array($action, $actions_allowed_without_seasonid))
        {
            $div->add(html_div('error fade',
                html_h4('You must select a season in order to perform this action.'))) ;
            $div->add($this->__buildGDL()) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Swim Meeets Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_ADD:
                    $form = new WpSwimTeamSeasonAddForm('Add Swim Team Season',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Add Season') ;
                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamSeasonUpdateForm('Update Swim Team Season',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Season') ;
                    break ;

                case WPST_ACTION_DELETE:
                    $form = new WpSwimTeamSeasonDeleteForm('Delete Swim Team Season',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Delete Season') ;
                    break ;

                case WPST_ACTION_OPEN_SEASON:
                    $form = new WpSwimTeamSeasonOpenForm('Open Swim Team Season',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId(seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Open Season') ;
                    break ;

                case WPST_ACTION_CLOSE_SEASON:
                    $form = new WpSwimTeamSeasonCloseForm('Close Swim Team Season',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Close Season') ;
                    break ;

                case WPST_ACTION_LOCK_IDS:
                    $form = new WpSwimTeamLockSwimmerIdsForm('Lock Swimmer Ids',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Lock Swimmer Ids') ;
                    break ;

                case WPST_ACTION_UNLOCK_IDS:
                    $form = new WpSwimTeamUnlockSwimmerIdsForm('Unlock Swimmer Ids',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setId($seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Unlock Swimmer Ids') ;
                    break ;

                case WPST_ACTION_JOBS:
                    $form = new WpSwimTeamSeasonJobAssignForm('Assign Swim Season Jobs',
                        $_SERVER['HTTP_REFERER'], 600) ;
                    $form->setMode(WPST_SEASON) ;
                    $form->setSeasonId($seasonid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Assign Season Jobs') ;
                    break ;

                default:
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
                    $this->setActionSummaryHeader('Seasons Action Summary') ;
                }
                else
                {
	                $div->add(html_br(2), $fp) ;
                }
            }
            else if (isset($c))
            {
                $div->add(html_br(2), $c) ;
                $div->add(SwimTeamGUIButtons::getButton('Return to Seasons')) ;
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
