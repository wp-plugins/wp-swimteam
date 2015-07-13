<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Roster page content.
 *
 * $Id: roster.php 1084 2015-07-13 14:44:16Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision: 1084 $
 * @lastmodified $Date: 2015-07-13 10:44:16 -0400 (Mon, 13 Jul 2015) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'class/sdif.class.php') ;
require_once(WPST_PATH . 'class/roster.class.php') ;
require_once(WPST_PATH . 'class/roster.forms.class.php') ;
require_once(WPST_PATH . 'class/seasons.class.php') ;
require_once(WPST_PATH . 'class/swimmers.class.php') ;
require_once(WPST_PATH . 'class/swimmers.forms.class.php') ;
require_once(WPST_PATH . 'class/reportgen.class.php') ;
require_once(WPST_PATH . 'class/container.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the roster
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class RosterTabContainer extends SwimTeamTabContainer
{
    /**
     * Return the proper form
     *
     * @return mixed
     */
    function __getForm($label, $action, $width)
    {
        return new WpSwimTeamSwimmerOptInOutForm($label, $action, $width) ;
    }

    /**
     * Build verbage to explain what can be done
     *
     * @return TABLEtag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $table->add_row(html_b(__(WPST_ACTION_PROFILE)),
            __('Display a swimmer\'s profile.  Show the swimmers
            detailed information as it will be displayed on the roster.')) ;
        //$table->add_row(html_b(__(WPST_ACTION_DIRECTORY)),
        //    __('Generate a team roster directory in PDF format.')) ;

        return $table ;
    }

    /**
     * Build query select clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildSelectClause()
    {
        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        $select_clause = sprintf(WPST_ROSTER_COLUMNS, 
            $cutoffdate, $cutoffdate, $cutoffdate) ;

        return $select_clause ;
    }

    /**
     * Build query where clause
     *
     * @return string - where clause for GUIDataList query
     */
    function __buildWhereClause()
    {
        $cutoffdate = sprintf('%s-%02s-%02s', date('Y'), 
            get_option(WPST_OPTION_AGE_CUTOFF_MONTH),
            get_option(WPST_OPTION_AGE_CUTOFF_DAY)) ;

        $season = new SwimTeamSeason() ;
        $seasonId = $season->getActiveSeasonId() ;

        //  On the off chance there isn't an active season
        //  set the season id to an invalid number so the SQL
        //  won't fail.
        
        if ($seasonId == null) $seasonId = -1 ;

        $where_clause = sprintf(WPST_ROSTER_WHERE_CLAUSE, $seasonId,
            $cutoffdate, $cutoffdate, $cutoffdate, $cutoffdate, $cutoffdate,
            $cutoffdate, WPST_STANDARD) ;

        return $where_clause ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamRosterGUIDataList('Swim Team Roster',
            '100%', 'lastname, firstname', false,
            $this->__buildSelectClause(), WPST_ROSTER_TABLES,
            $this->__buildWhereClause()) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        //  Temporarily turn off search - the SQL gets corrupted.
        $gdl->_search_flag = false ;

        return $gdl ;
    }

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
     * Construct the content of the Roster Tab Container
     */
    function RosterTabContainer()
    {
        global $userdata ;

        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.

        $div = html_div() ;
        $div->set_style('clear: both;') ;
        $div->add(html_h3('Swim Team Roster')) ;

        //  This allows passing arguments eithers as a GET or a POST

        $scriptargs = array_merge($_GET, $_POST) ;
        $actions_allowed_without_swimmerid = array(
            WPST_ACTION_DIRECTORY
           ,WPST_ACTION_EXPORT_SDIF
           ,WPST_ACTION_EXPORT_HY3
           ,WPST_ACTION_EXPORT_CSV
           ,WPST_ACTION_EXPORT_MMRE
           ,WPST_ACTION_ASSIGN_LABELS
           ,WPST_ACTION_EXPORT_ROSTER
        ) ;

        //  The swimmerid is the argument which must be
        //  dealt with differently for GET and POST operations

        if (array_key_exists(WPST_DB_PREFIX . 'radio', $scriptargs))
            $swimmerid = $scriptargs[WPST_DB_PREFIX . 'radio'][0] ;
        else if (array_key_exists('swimmerid', $scriptargs) && !empty($scriptargs['swimmerid']))
            $swimmerid = $scriptargs['swimmerid'] ;
        else
            $swimmerid = null ;

        //  So, how did we get here?  If $_POST is empty
        //  then it wasn't via a form submission.

        //  Show the list of swimmers or process an action.  If
        //  there is no $_POST or if there isn't an action
        //  specififed, then simply display the GDL.

        if (array_key_exists('_action', $scriptargs))
            $action = $scriptargs['_action'] ;
        else if (array_key_exists('_form_action', $scriptargs))
            $action = $scriptargs['_form_action'] ;
        else
            $action = null ;

        //  If one of the GDL controls was selected, then
        //  the action maybe confusing the processor.  Flush
        //  any action that doesn't make sense.

        if ($action == WPST_ACTION_SELECT_ACTION) $action = null ;

        //  Is requested action 'Execute'?  If so, need
        //  to get the select action from the drop down
        //  list.

        if ($action == WPST_ACTION_EXECUTE)
        {
            if (array_key_exists('_select_action', $scriptargs))
                $action = $scriptargs['_select_action'] ;
            else
                $action = null ;
        }

        if (empty($scriptargs) || is_null($action))
        {
            $div->add($this->__buildGDL()) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Roster Action Summary') ;
        }
        else if (is_null($swimmerid) && !in_array($action, $actions_allowed_without_swimmerid))
        {
            $div->add(html_div('error fade',
                html_h4('You must select a swimmer in order to perform this action.'))) ;
            $div->add($this->__buildGDL()) ;
            $this->setShowActionSummary() ;
            $this->setActionSummaryHeader('Roster Action Summary') ;
        }
        else  //  Crank up the form processing process
        {
            switch ($action)
            {
                case WPST_ACTION_PROFILE:
                    $c = container() ;
                    $profile = new SwimTeamSwimmerProfileInfoTable('Swimmer Profile', '500px') ;
                    $profile->set_alt_color_flag(true) ;
                    $profile->set_show_cellborders(true) ;
                    $profile->setId($swimmerid) ;
                    $profile->constructSwimmerProfile() ;
                    $c->add($profile) ;

                    break ;

                case WPST_ACTION_UPDATE:
                    $form = new WpSwimTeamSwimmerUpdateForm('Update Swimmer',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Update Swimmer') ;
                    break ;

                case WPST_ACTION_ASSIGN_LABEL:
                    $form = new WpSwimTeamSwimmerLabelForm('Assign Swimmer Label',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Assign Swimmer Label') ;
                    break ;

                case WPST_ACTION_UNREGISTER:
                    $form = new WpSwimTeamSwimmerUnregisterForm('Unregister Swimmer',
                        SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Unregister Swimmer') ;
                    break ;

                 case WPST_ACTION_OPT_IN:
                    $optin = ucwords(get_option(WPST_OPTION_OPT_IN_LABEL)) ;
                    $form = $this->__getForm('Swimmer:  ' .
                        $optin, SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setAction(WPST_ACTION_OPT_IN) ;
                    $form->setSwimmerId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader($optin . ' Swimmer') ;
                    break ;

                 case WPST_ACTION_OPT_OUT:
                    $optout = ucwords(get_option(WPST_OPTION_OPT_OUT_LABEL)) ;
                    $form = $this->__getForm('Swimmer:  ' .
                        $optout, SwimTeamUtils::GetPageURI(), 600) ;
                    $form->setAction(WPST_ACTION_OPT_OUT) ;
                    $form->setSwimmerId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader($optout . ' Swimmer') ;
                    break ;

                case WPST_ACTION_EXPORT_ROSTER:
                    $form = new WpSwimTeamExportRosterForm('Export Roster',
                        SwimTeamUtils::GetPageURI(), 400) ;
                    $form->setSwimmerId($swimmerid) ;
                    $this->setShowFormInstructions() ;
                    $this->setFormInstructionsHeader('Export Roster') ;
                    break ;


                case WPST_ACTION_EXPORT_SDIF:
                    $c = container() ;

                    $sdif = new SDIFLSCRegistrationPyramid() ;
                    $sdif->generateSDIF($swimmerid) ;
                    $sdif->generateSDIFFile() ;

                    $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($sdif->getSDIFFile()),
                        urlencode('SwimTeamRoster-' . date('Y-m-d').'.sd3'), urlencode('txt'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                    $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                    $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                    $c->add($if) ;

                    $c->add(html_div('updated fade',
                        html_h4(sprintf('%s roster records exported in SDIF format.', $sdif->getSDIFCount())))) ;
                    $c->add($this->__buildGDL()) ;

                    break ;

                case WPST_ACTION_EXPORT_HY3:
                    $c = container() ;
                    require_once(WPST_PATH . 'class/hy-tek.class.php') ;

                    $hy3 = new HY3Roster() ;
                    //$hy3->setHy3DebugFlag(true) ;
                    $hy3->generateHY3($swimmerid) ;
                    $hy3->generateHY3File() ;

                    $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($hy3->getHY3File()),
                        urlencode('SwimTeamRoster-' . date('Y-m-d').'.hy3'), urlencode('txt'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                    $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                    $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                    $c->add($if) ;

                    $c->add(html_div('updated fade',
                        html_h4(sprintf('%s roster records exported in HY3 format.', $hy3->getHY3Count())))) ;
                    $c->add($this->__buildGDL()) ;

                    break ;

                case WPST_ACTION_EXPORT_CSV:
                    $c = container() ;

                    $csv = new SwimTeamSwimmersReportGeneratorCSV() ;
                    $this->__initializeReportGeneratorCSV($csv) ;
                    $csv->generateReport($swimmerid) ;
                    $csv->generateCSVFile() ;

                    $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($csv->getCSVFile()),
                        urlencode('SwimTeamRoster-' . date('Y-m-d').'.csv'), urlencode('csv'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                    $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                    $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                    $c->add($if) ;
                    $c->add($csv->getReport()) ;
                    $c->add(html_br(), SwimTeamGUIButtons::getButton('Return to Roster')) ;
                    
                    $div->add(html_div('updated fade',
                        html_h4(sprintf('Swim Team Swimmers Report Generated, %s record%s returned.',
                        $csv->getRecordCount(), $csv->getRecordCount() == 1 ? '' : 's')))) ;                    
                    break ;

                case WPST_ACTION_EXPORT_MMRE:
                    $c = container() ;

                    $re1 = new SwimTeamSwimmersReportGeneratorRE1() ;
                    $this->__initializeReportGeneratorRE1($re1) ;
                    $re1->generateReport($swimmerid) ;
                    $re1->generateRE1File() ;

                    $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($re1->getRE1File()),
                        urlencode('SwimTeamRoster-' . date('Y-m-d').'.re1'), urlencode('txt'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                    $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                    $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                    $c->add($if) ;
                    $c->add($re1->getReport()) ;
                    $c->add(html_br(), SwimTeamGUIButtons::getButton('Return to Roster')) ;

                    $div->add(html_div('updated fade',
                        html_h4(sprintf('Swim Team Swimmers Report Generated, %s record%s exported. in Hy-tek Meet Manager RE1 format.',
                        $re1->getRecordCount(), $re1->getRecordCount() == 1 ? '' : 's')))) ;                    

                    break ;

                case WPST_ACTION_ASSIGN_LABELS:
                    $c = new Container() ;

                    $season = new SwimTeamSeason() ;
                    $season->loadActiveSeason() ;

                    if ($season->getSwimmerLabels() == WPST_UNLOCKED)
                    {
                        $roster = new SwimTeamRoster() ;
                        $roster->setSeasonId($season->getActiveSeasonId()) ;
                        $roster->assignSwimmerLabels() ;
                        $c->add(html_div('updated fade',
                            html_h4('Swimmer Labels assigned.'))) ;
                    }
                    else
                    {
                        $c->add(html_div('error fade',
                            html_h4('Swimmer Labels are locked, no label assignments.'))) ;
                    }


                    $c->add($this->__buildGDL()) ;

                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Roster Action Summary') ;

                    break ;

                default:
                    $div->add(html_h4(html_div('error fade',
                        sprintf('Unsupported action "%s" requested.', $action)))) ;
                    $div->add($this->__buildGDL()) ;
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
                    $c = container() ;
                    
                    //  Did we export something?  If so, need to download the file(s)

                    if ($action == WPST_ACTION_EXPORT_ROSTER)
                    {
                        $exports = &$fp->_form_content->__exports ;

                        //  CSV
                        if (in_array(WPST_CSV, $exports))
                        {
error_log(sprintf('%s::%s', basename(__FILE__), __LINE__)) ;
                            $csv = &$fp->_form_content->__csv ;
error_log(print_r($csv->__csvData, true)) ;
error_log(sprintf('%s::%s', basename(__FILE__), __LINE__)) ;
                            $t = $csv->getExportTransient() ;
                            $v = empty($t) ? null : get_transient($t) ;

                            //  Use transients instead of temporary files for storage?
 
                            if ((get_option(WPST_OPTION_USE_TRANSIENTS) === WPST_YES) && !empty($t) && !empty($v))
                            {
                                $args = sprintf('transient=%s&filename=%s&contenttype=%s&abspath=%s&wpstnonce=%s', urlencode($t),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.csv'), urlencode('csv'), urlencode(ABSPATH), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            elseif (file_exists($csv->getCSVFile()) && filesize($csv->getCSVFile()) > 0)
                            {
error_log(sprintf('%s::%s', basename(__FILE__), __LINE__)) ;
                                $args = sprintf('file=%s&filename=%s&contenttype=%s&abspath=%s&wpstnonce=%s',
                                    urlencode($csv->getCSVFile()), urlencode('SwimTeamRoster-' . date('Y-m-d').'.csv'),
                                    urlencode('csv'), urlencode(ABSPATH), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            else
                            {
                                $c->add(html_div("updated error", html_h4('CSV Export file does not exist, nothing to download.'))) ;
                            }
                        }

                        //  RE1
                        if (in_array(WPST_RE1, $exports))
                        {
                            $re1 = &$fp->_form_content->__re1 ;
                            $t = $re1->getExportTransient() ;
                            $v = empty($t) ? null : get_transient($t) ;

                            //  Use transients instead of temporary files for storage?
 
                            if ((get_option(WPST_OPTION_USE_TRANSIENTS) === WPST_YES) && !empty($t) && !empty($v))
                            {
                                $args = sprintf('transient=%s&filename=%s&contenttype=%s&abspath=%s&wpstnonce=%s', urlencode($t),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.re1'), urlencode('re1'), urlencode(ABSPATH), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            elseif (file_exists($re1->getRE1File()) && filesize($re1->getRE1File()) > 0)
                            {
                                $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($re1->getRE1File()),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.re1'), urlencode('re1'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            else
                            {
                                $c->add(html_div("updated error", html_h4('RE1 export file does not exist, nothing to download.'))) ;
                            }
                        }

                        //  HY3
                        if (in_array(WPST_HY3, $exports))
                        {
                            $hy3 = &$fp->_form_content->__hy3 ;
                            $t = $hy3->getExportTransient() ;
                            $v = empty($t) ? null : get_transient($t) ;

                            //  Use transients instead of temporary files for storage?
 
                            if ((get_option(WPST_OPTION_USE_TRANSIENTS) === WPST_YES) && !empty($t) && !empty($v))
                            {
                                $args = sprintf('transient=%s&filename=%s&contenttype=%s&abspath=%s&wpstnonce=%s', urlencode($t),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.hy3'), urlencode('hy3'), urlencode(ABSPATH), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            elseif (file_exists($hy3->getHY3File()) && filesize($hy3->getHY3File()) > 0)
                            {
                                $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($hy3->getHY3File()),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.hy3'), urlencode('hy3'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            else
                            {
                                $c->add(html_div("updated error", html_h4('HY3 export file does not exist, nothing to download.'))) ;
                            }
                        }

                        //  SDIF
                        if (in_array(WPST_SDIF, $exports))
                        {
                            $sdif = &$fp->_form_content->__sdif ;
                            $t = $sdif->getExportTransient() ;
                            $v = empty($t) ? null : get_transient($t) ;

                            //  Use transients instead of temporary files for storage?
 
                            if ((get_option(WPST_OPTION_USE_TRANSIENTS) === WPST_YES) && !empty($t) && !empty($v))
                            {
                                $args = sprintf('transient=%s&filename=%s&contenttype=%s&abspath=%s&wpstnonce=%s', urlencode($t),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.sd3'), urlencode('sd3'), urlencode(ABSPATH), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            elseif (file_exists($sdif->getSDIFFile()) && filesize($sdif->getSDIFFile()) > 0)
                            {
                                $args = sprintf('file=%s&filename=%s&contenttype=%s&wpstnonce=%s', urlencode($sdif->getSDIFFile()),
                                    urlencode('SwimTeamRoster-' . date('Y-m-d').'.sd3'), urlencode('sd3'), urlencode(wp_create_nonce('wpst-nonce'))) ;

                                $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                                $c->add($if) ;
                            }
                            else
                            {
                                $c->add(html_div("updated error", html_h4('SDIF export file does not exist, nothing to download.'))) ;
                            }
                        }
                    }

                    //  Need to show a different GDL based on whether or
                    //  not the end user has a level of Admin ability.

                    $gdl = $this->__buildGDL() ;

                    $div->add($gdl) ;

error_log(sprintf('%s::%s', basename(__FILE__), __LINE__)) ;
error_log(print_r($c, true)) ;
	                $div->add(html_br(2), $form->form_success(), $c) ;
                    $this->setShowActionSummary() ;
                    $this->setActionSummaryHeader('Roster Action Summary') ;
                }
                else
                {
	                $div->add(html_br(2), $fp) ;
                }
            }
            else if (isset($c))
            {
error_log(sprintf('%s::%s', basename(__FILE__), __LINE__)) ;
                $div->add($c) ;
            }
            else
            {
error_log(sprintf('%s::%s', basename(__FILE__), __LINE__)) ;
                $div->add(html_div('error fade', html_h4('No content to display.'))) ;
            }
        }

        $div->add(html_br()) ;

        $this->add($div) ;
        $this->add($this->buildContextualHelp()) ;
    }
}

/**
 * Class definition of the roster
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see Container
 */
class AdminRosterTabContainer extends RosterTabContainer
{
    /**
     * Build verbage to explain what can be done
     *
     * @return DIVTag
     */
    function __buildActionSummary()
    {
        $table = parent::__buildActionSummary() ;

        $optin = get_option(WPST_OPTION_OPT_IN_LABEL) ;
        $optout = get_option(WPST_OPTION_OPT_OUT_LABEL) ;

        $table->add_row(html_b(__($optin)), __('Explicitly ' .
            strtolower($optin) . ' for a swim meet which requires
            swimmers to commit their intent to swim.')) ;
        $table->add_row(html_b(__($optout)),
            __('Explicitly ' . strtolower($optout) . ' from a swim meet
            which requires swimmers to commit their intent NOT to swim.
            you may ' . strtolower($optout) . ' of the entire meet or
            selected events.  You may also ' . strtolower($optout) .
            ' from an meet or event previously committed to.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UPDATE)),
            __('Update a swimmer\'s information.  Use this action to correct
            any of the information about one or more of swimmers.')) ;
        $table->add_row(html_b(__(WPST_ACTION_UNREGISTER)),
            __('Unregister a swimmer for the current season.  Use this
            action if a swimmer is no longer interested in particpating
            in the current season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_ASSIGN_LABEL)),
            __('Assign swimmer label for the current season.  Use this
            action assign label (aka swimmer number) the swimmer will be 
            assign for the current season.')) ;
        $table->add_row(html_b(__(WPST_ACTION_ASSIGN_LABELS)),
            __('Assign swimmer labels for the current season.  Use this
            action assign labels (aka swimmer numbers) to all swimmers for
            the current season.  Labels are assigned based on swim team
            label configuration')) ;
        $table->add_row(html_b(__(WPST_ACTION_EXPORT_SDIF)),
            html_span(null, __('Export the active roster as a LSC Registration
            Pyramid in SDIF format.  The LSC Registration Pyramid can be
            imported into '), html_a('http://www.winswim.com', 'WinSwim'),
            __(' but is not currently supported by '),
            html_a('http://www.hy-tekltd.com/swim/TMII/index.html',
            'Hy-Tek Team Manager') , __(' at this time.  When no swimmers
            are selected, the entire roster will be exported.'))) ;
        $table->add_row(html_b(__(WPST_ACTION_EXPORT_CSV)),
            __('Export the active roster as a CSV file.  A CSV file can
            be loaded into tools such as Microsoft Excel.  All of the swimmer
            information appears in the file, each field separated by the
            comma "," character.  When no swimmers are selected, the entire
            roster will be exported.')) ;
        $table->add_row(html_b(__(WPST_ACTION_EXPORT_MMRE)),
            html_span(null, __('Export the active roster as a Hy-tek Meet
            Manager Registration file.  The Meet Manager Registration File
            can be imported into '),
            html_a('http://www.hy-tekltd.com/swim/mm/index.html',
            'Hy-Tek Meet Manager'), __(' but is not currently supported by '),
            html_a('http://www.hy-tekltd.com/swim/TMII/index.html',
            'Hy-Tek Team Manager') , __(' at this time.'))) ;

        /*
         */
        return $table ;
    }

    /**
     * Return the proper form
     *
     * @return mixed
     */
    function __getForm($label, $action, $width)
    {
        return new WpSwimTeamSwimmerOptInOutAdminForm($label, $action, $width) ;
    }

    /**
     * Build the GUI DataList used to display the roster
     *
     * @return GUIDataList
     */
    function __buildGDL()
    {
        $gdl = new SwimTeamRosterAdminGUIDataList('Swim Team Roster',
            '100%', 'lastname, firstname', false,
            $this->__buildSelectClause(), WPST_ROSTER_TABLES,
            $this->__buildWhereClause()) ;

        $gdl->set_alternating_row_colors(true) ;
        $gdl->set_show_empty_datalist_actionbar(true) ;

        return $gdl ;
    }
}
?>
