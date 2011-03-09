<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Meets Report page content.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage reports
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once("swimmeets.report.class.php") ;
require_once("container.class.php") ;

/**
 * Class definition of the ReportGeneratorTab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see SwimTeamTabContainer
 */
class ReportSwimMeetsTabContainer extends SwimTeamTabContainer
{
    /**
     * Build Instructions
     *
     */
    function __buildInstructions()
    {
        $div = html_div() ;

        $div->add('Generate a report for one or more swim meets.  Choose which options to
            include in the report and specify if the report should be produced as a web page
            or a CSV file.  A CSV file can be saved and loaded into tools like Excel.  The
            report can optionally include Job Assignments, Opt-In / Opt-Out lists, and meet
            details.') ;

        return $div ;
    }

    /**
     * Construct the content of the ReportGenerator Tab Container
     *
     */
    function ReportSwimMeetsTabContainer()
    {
        //  The container content is either a GUIDataList of 
        //  the jobs which have been defined OR form processor
        //  content to add, delete, or update jobs.  Wbich type
        //  of content the container holds is dependent on how
        //  the page was reached.
 
        $div = html_div() ;
        $div->set_style("clear: both;") ;

        //  Start building the form

        $form = new WpSwimTeamSwimMeetsReportForm("Swim Team Swim Meet Report",
            $_SERVER['HTTP_REFERER'], 600) ;

        //  Create the form processor

        $fp = new FormProcessor($form) ;
        //$fp->set_form_action($_SERVER['REQUEST_URI']) ;
        $fp->set_form_action($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']) ;
        //

        //  Display the form again even if processing was successful.

        $fp->set_render_form_after_success(false) ;

        //  If the Form Processor was succesful, let the user know

        if ($fp->is_action_successful())
        {
            $mode = $fp->_form_content->get_element_value("Report Format") ;

            $c = container() ;

            $rpt = &$fp->_form_content->__report ;

            if ($mode == WPST_GENERATE_PDF)
            {
                $rpt->generatePDFFile() ;

                $arg = urlencode($rpt->getPDFFile()) ;

                $if = html_iframe(sprintf("%s/include/user/reportgenPDF.php?file=%s", WPST_PLUGIN_URL, $arg)) ;
                $if->set_tag_attributes(array("width" => 0, "height" => 0)) ;
                $c->add($if) ;


                $fp->set_render_form_after_success(true) ;

	            $div->add($fp, html_br(), $c) ;
            }
            else
            {
                $c->add($rpt->getReport()) ;
                
                $fp->set_render_form_after_success(false) ;

                $div->add($fp, html_br(), $c) ;
                $buttons = SwimTeamGUIBackHomeButtons::getButtons() ;
                $buttons->set_id("BackHomeButtons") ;
                $div->add($buttons) ;
            }
        }
        else
        {
	        $div->add($fp, html_br()) ;
        }

        $this->add($div) ;
        $this->setShowInstructions() ;
        $this->setInstructionsHeader('Report Swim Meets') ;
        $this->add($this->buildContextualHelp()) ;
    }
}
?>
