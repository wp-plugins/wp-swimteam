<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Age Groups admin page content.
 *
 * $Id: report_swimmers.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(WPST_PATH . 'class/reportgen.class.php') ;
require_once(WPST_PATH . 'class/reportgen.forms.class.php') ;
require_once(WPST_PATH . 'class/container.class.php') ;
require_once(WPST_PATH . 'class/widgets.class.php') ;

/**
 * Class definition of the ReportGeneratorTab
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class ReportGeneratorTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions
     *
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add('Generate a report of the Swimmers registered with the web site.
            Choose which options to include in the report and specify if the report
            should be produced as a web page or a CSV file.  A CSV file can be saved
            and loaded into tools like Excel.  The report can be filtered to contain
            a subset of the swimmers based on their gender, status, and/or results
            settings.') ;

        return $div ;
    }

    /**
     * Construct the content of the ReportGenerator Tab Container
     *
     */
    function ReportGeneratorTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style('clear: both;') ;

        //  Start building the form

        $form = new WpSwimTeamSwimmersReportGeneratorForm('Swim Team Swimmer Report Generator',
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        $fp->set_form_action(SwimTeamUtils::GetPageURI()) ;

        //  Display the form again even if processing was successful.


        //  If the Form Processor was succesful, let the user know

        if ($fp->is_action_successful())
        {
            $mode = $fp->_form_content->get_element_value('Report') ;

            $c = container() ;

            $rpt = &$fp->_form_content->__report ;

            if ($mode == WPST_GENERATE_CSV)
            {
                $rpt->generateCSVFile() ;
                $t = $rpt->getExportTransient() ;
                $v = empty($t) ? null : get_transient($t) ;

                //  Use transients instead of temporary files for storage?
 
                if ((get_option(WPST_OPTION_USE_TRANSIENTS) === WPST_YES) && !empty($t) && !empty($v))
                {
                    $args = sprintf('transient=%s&filename=%s&contenttype=%s&abspath=%s', urlencode($t),
                        urlencode('SwimTeamReport-' . date('Y-m-d').'.csv'), urlencode('csv'), urlencode(ABSPATH)) ;

                    $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                    $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                    $c->add($if) ;
                }
                elseif (file_exists($rpt->getCSVFile()) && filesize($rpt->getCSVFile()) > 0)
                {
                    $args = sprintf('file=%s&filename=%s&contenttype=%s', urlencode($rpt->getCSVFile()),
                        urlencode('SwimTeamReport-' . date('Y-m-d').'.csv'), urlencode('csv')) ;

                    $if = html_iframe(sprintf('%s?%s', plugins_url('download.php', __FILE__), $args)) ;
                    $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                    $c->add($if) ;
                }
                else
                {
                    $c->add(html_div("updated error", html_h4('CSV Export file does not exist, nothing to download.'))) ;
                }

                $c->add($rpt->getReport(true)) ;

                $fp->set_render_form_after_success(false) ;

	            $div->add($fp, html_br(), $c) ;
            }
            else
            {
                $c->add($rpt->getReport()) ;

                $fp->set_render_form_after_success(false) ;

	            $div->add($fp, html_br(), $c) ;
            }
            
            $div->add(SwimTeamGUIButtons::getButton('Return to Report Generator')) ;
        }
        else
        {
	        $div->add($fp, html_br()) ;
        }

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader('Report Swimmers') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
