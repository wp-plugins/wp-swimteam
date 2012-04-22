<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Age Groups admin page content.
 *
 * $Id$
 *
 * (c) 2007 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

require_once('reportgen.class.php') ;
require_once('reportgen.forms.class.php') ;
require_once('container.class.php') ;
require_once('widgets.class.php') ;

/**
 * Class definition of the ReportGeneratorTab
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
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
                $arg = urlencode($rpt->getCSVFile()) ;

                $if = html_iframe(sprintf('%s/include/user/reportgenCSV.php?file=%s', WPST_PLUGIN_URL, $arg)) ;
                $if->set_tag_attributes(array('width' => 0, 'height' => 0)) ;
                $c->add($if) ;

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
