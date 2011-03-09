<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Team Container
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package SwimTeam
 * @subpackage Container
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

/**
 * Class definition of the swim clubs
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see Container
 */
class SwimTeamTabContainer extends Container
{
    /**
     * Show Action Summary property
     */
    var $__show_action_summary = false ;

    /**
     * Show Action Summary Header property
     */
    var $__action_summary_header = 'Action Summary' ;

    /**
     * Show Instructions property
     */
    var $__show_instructions = false ;

    /**
     * Show Instructions Header property
     */
    var $__instructions_header = 'Operation Summary' ;

    /**
     * Show Form Instructions property
     */
    var $__show_form_instructions = false ;

    /**
     * Show Form Instructions Header property
     */
    var $__form_instructions_header = 'Form Operation Summary' ;

    /**
     * Show Form Instructions Content property
     */
    var $__form_instructions_content = null ;

    /**
     * Set Show Action Summary property
     *
     * @param boolean show or hide Action Summary
     */
    function setShowActionSummary($show = true)
    {
        $this->__show_action_summary = $show ;
    }

    /**
     * Get Show Action Summary property
     *
     * @return boolean show or hide Action Summary
     */
    function getShowActionSummary()
    {
        return $this->__show_action_summary ;
    }

    /**
     * Set Show Action Summary Header property
     *
     * @param string Action Summary Header
     */
    function setActionSummaryHeader($header)
    {
        $this->__action_summary_header = $header ;
    }

    /**
     * Get Action Summary Header property
     *
     * @return string Action Summary Header
     */
    function getActionSummaryHeader()
    {
        return $this->__action_summary_header ;
    }

    /**
     * Set Show Instructions property
     *
     * @param boolean show or hide Instructions
     */
    function setShowInstructions($show = true)
    {
        $this->__show_instructions = $show ;
    }

    /**
     * Get Show Instructions property
     *
     * @return boolean show or hide Instructions
     */
    function getShowInstructions()
    {
        return $this->__show_instructions ;
    }

    /**
     * Set Instructions Header property
     *
     * @param string Instructions Header
     */
    function setInstructionsHeader($header)
    {
        $this->__instructions_header = $header ;
    }

    /**
     * Get Instructions Header property
     *
     * @return string Instructions Header
     */
    function getInstructionsHeader()
    {
        return $this->__instructions_header ;
    }

    /**
     * Set Show Form Instructions property
     *
     * @param boolean show or hide Form Instructions
     */
    function setShowFormInstructions($show = true)
    {
        $this->__show_form_instructions = $show ;
    }

    /**
     * Get Show Form Instructions property
     *
     * @return boolean show or hide Form Instructions
     */
    function getShowFormInstructions()
    {
        return $this->__show_form_instructions ;
    }

    /**
     * Set Form Instructions Header property
     *
     * @param string Form Instructions Header
     */
    function setFormInstructionsHeader($header)
    {
        $this->__form_instructions_header = $header ;
    }

    /**
     * Get Form Instructions Header property
     *
     * @return string Form Instructions Header
     */
    function getFormInstructionsHeader()
    {
        return $this->__form_instructions_header ;
    }

    /**
     * Set Form Instructions Content property
     *
     * @param string Form Instructions Content
     */
    function setFormInstructionsContent($content)
    {
        $this->__form_instructions_content = $content ;
    }

    /**
     * Get Form Instructions Content property
     *
     * @return string Form Instructions Content
     */
    function getFormInstructionsContent()
    {
        return $this->__form_instructions_content ;
    }

    /**
     * Build verbage to explain what can be done
     *
     * @return TABLEtag
     */
    function __buildActionSummary()
    {
        $table = new SwimTeamInfoTable('Actions', '100%', 'center') ;
        $table->set_alt_color_flag(false) ;
        $table->set_show_cellborders(false) ;
        $table->set_cellspacing('10') ;

        return $table ;
    }

    /**
     * Build Instructions
     *
     * @return DIVtag
     */
    function __buildInstructions()
    {
        $div = html_div() ;
        $div->add('Instructions for this function should be documented here.') ;

        return $div ;
    }

    /**
     * Build Form Instructions
     *
     * @return DIVtag
     */
    function __buildFormInstructions()
    {
        $div = $this->getFormInstructionsContent() ;

        if (is_null($div))
        {
            $div = html_div() ;
            $div->add('Instructions for this form should be documented here.') ;
        }

        return $div ;
    }

    /**
     * Build jQuery script to display Action Summary using
     * WordPress contextual help.  The defaultcontent
     * is replaced with task specific content using a
     * simple jQuery script.
     *
     * @return SCRIPTtag
     */
    function buildContextualHelp()
    {
        //  Construct a simple jQuery script to replace the
        //  default content of the Contextual Help.

        $js = '/* <![CDATA[ */
            jQuery(document).ready(function($) {
		        jQuery(\'#contextual-help-wrap\').html(\'%s\');
		    });
	    /* ]]> */' ;

	    $script = html_script() ;

        $contextual_help = new Container() ;

        //  Show Instructions?
 
        if ($this->getShowInstructions())
        {
            $contextual_help->add(html_h3($this->getInstructionsHeader())) ;
            $contextual_help->add($this->__buildInstructions()) ;
        }

        //  Show Form Instructions?
 
        if ($this->getShowFormInstructions())
        {
            $contextual_help->add(html_h3($this->getFormInstructionsHeader())) ;
            $contextual_help->add($this->__buildFormInstructions()) ;
        }

        //  Show Action Summary?

        if ($this->getShowActionSummary())
        {
            $contextual_help->add(html_h3($this->getActionSummaryHeader())) ;
            $contextual_help->add($this->__buildActionSummary()) ;
        }

        //  Clean up all of the newlines and carriage returns - doing
        //  so seems to produce a more reliable jQuery experience!

	    $script->add(sprintf($js, preg_replace("/(\r\n)+|(\n|\r)+/",
	       	"", $contextual_help->render()))) ;

	    return $script ;
    }
}
?>
