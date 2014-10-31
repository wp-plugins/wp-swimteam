<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: print.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Print CSS classes
 *
 * (c) 2009 by Mike Walsh for WpSwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage print
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

/**
 * This class defines the css used by the 
 * Print
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package wp-swimteam 
 */
class PrintDashboardContentCSS extends CSSBuilder
{
    /**
	 * This function is used to construct the css name
     * declaration string.  IT IS A BAND AID because 
     * phpHtmlLib can't handle CSS class names with the
     * hyphen character.
     *
     * The limitiation on this BAND AID is each CSS class
     * name, selector, ID, etc. must defined separately.
     * You can't do something like this:
     *
	 * #foo div,span { }
	 * 
	 * @param string the name-extends string
	 * @return the css name declaration
	 */
    function _build_name($class)
    {
		return substr($class, 0, strlen($class) - 1) . " {\n" ;
    }

    function user_setup()
    {
        $this->add_entry("#wphead", null, array("display" => "none")) ;
        $this->add_entry("#footer", null, array("display" => "none")) ;
        $this->add_entry("#adminmenu", null, array("display" => "none")) ;
        $this->add_entry("#screen-meta", null, array("display" => "none")) ;
        $this->add_entry("#update-nag", null, array("display" => "none")) ;
        $this->add_entry(".updated", null, array("display" => "none")) ;
        $this->add_entry(".fade", null, array("display" => "none")) ;
        $this->add_entry(".tabs", null, array("display" => "none")) ;
        $this->add_entry("#BackHomeButtons", null, array("display" => "none")) ;

        $this->add_entry(".wrap", null, array("position" => "absolute",
            "top" => "200", "left" => "0", "width" => "8in")) ;
    }   
}
?>
