<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: portlets.class.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * jQuery Portet widget - used to reorder events
 * and heats.  This code was adapted from the jQuery
 * portet example: 
 *
 * http://sonspring.com/journal/jquery-portlets
 *
 * By in the large the original code is used as it
 * was published in the article but the classes have
 * all been changed and the jQuery references have
 * been changed to work in the WordPress context.
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package Wp-SwimTeam
 * @subpackage Events
 * @version $Revision: 849 $
 * @lastmodified $Author: mpwalsh8 $
 * @lastmodifiedby $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @see http://sonspring.com/journal/jquery-portlets
 *
 */

define('ORDER_PORTLET_BY_ROW', 'row') ;
define('ORDER_PORTLET_BY_COLUMN', 'column') ;

/**
 * Class definition of a Portet
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see DIVtag
 */
class Portlet extends DIVtag
{
    /**
     * property to set number of columns, default to 3
     */
    var $__portletcolumns = 3 ;

    /**
     * property to set portlet ordering, default to row
     */
    var $__portletordering = ORDER_PORTLET_BY_ROW ;

    /**
     * portlets property - container to hold the portlets
     */
    var $__portlets = null ;

    /**
     * Set the portlet columns
     *
     * @param int - number of columns
     */
    function setPortletColumns($cols)
    {
        $this->__portletcolumns = $cols ;
    }

    /**
     * Get the portlet columns
     *
     * @return int - number of columns
     */
    function getPortletColumns()
    {
        return $this->__portletcolumns ;
    }

    /**
     * Set the portlet ordering
     *
     * @param string - portlet ordering
     */
    function setPortletOrdering($ordering = ORDER_PORTLET_BY_ROW)
    {
        switch (stringtolower($ordering))
        {
            case ORDER_PORTLET_BY_COLUMN:
                $ordering = ORDER_PORTLET_BY_COLUMN ;
                break ;
            default:
                $ordering = ORDER_PORTLET_BY_ROW ;
                break ;
        }

        $this->__portletordering = $ordering ;
    }

    /**
     * Get the portlet ordering
     *
     * @return string - portlet ordering
     */
    function getPortletOrdering()
    {
        return $this->__portletordering ;
    }

    /**
     * add portlet
     *
     * @param int - id number for the portlet
     * @param string - short description - text for the topper
     * @param string - long description - text for the portlet
     */
    function addPortlet($id, $shortdesc, $longdesc)
    {
        $div = html_div("wpst_portlet") ;
        $div->set_id(sprintf("wpst_portlet_id_%d", $id)) ;

        $topper = html_div("wpst_portlet_topper") ;
        $topper->add(html_a("#", "Toggle", "wpst_toggle"), $shortdesc) ;
        $topper->set_collapse(true) ;

        $content = html_div("wpst_portlet_content") ;
        $content->add(html_p($longdesc)) ;

        $div->add($topper, $content) ;

        $this->__portlets[] = $div ;
    }

    /**
     * Constructor
     *
     */
    function Portlet()
    {
        $this->__portlets = array() ;
        DIVtag::DIVtag() ;
    }

    /**
     * Render the Portlet
     *
     * This method overloads the render() method and builds
     * up the Portlet DIV before actually rendering it.
     *
     */
    function render()
    {
        $pcols = $this->getPortletColumns() ;

        switch ($pcols)
        {
            case 1:
                $css = new OneColumnPortletCSS() ;
                break ;

            case 2:
                $css = new TwoColumnPortletCSS() ;
                break ;

            case 3:
                $css = new ThreeColumnPortletCSS() ;
                break ;

            case 4:
                $css = new FourColumnPortletCSS() ;
                break ;

            default:
                $this->setPortletColumns(3) ;
                $pcols = 3 ;
                $css = new ThreeColumnPortletCSS() ;
                break ;
        }

        $style = html_style() ;
        $style->add($css) ;
        $this->add($style) ;

        $this->set_id("wpst_container") ;
        $hdr = html_div() ;
        $hdr->set_id("wpst_header") ;
        $span = html_span() ;
        $span->set_id("wpst_controls") ;
        $a = html_a("#", "[ + ]", null, null, "Open") ;
        $a->set_id("wpst_all_open") ;
        $span->add($a) ;
        $a = html_a("#", "[ x ]", null, null, "Close") ;
        $a->set_id("wpst_all_close") ;
        $span->add($a) ;
        $hdr->add($span) ;
        $a = html_a("#", "Expand", null, null, "Expand") ;
        $a->set_id("wpst_all_expand") ;
        $hdr->add($a) ;
        $a = html_a("#", "Collapse", null, null, "Collapse") ;
        $a->set_id("wpst_all_collapse") ;
        $hdr->add($a) ;
        $a = html_a("#", "Invert", null, null, "Invert") ;
        $a->set_id("wpst_all_invert") ;
        $hdr->add($a) ;
        $this->add($hdr) ;

        $table = html_table() ;
        $table->set_id("wpst_columns") ;
        $tr = html_tr() ;

        //  How many columns?  Need a TD for each column

        $td = array() ;

        for ($i = 0 ; $i < $pcols ; $i++)
        {
            $td[$i] = html_td() ;
            $td[$i]->set_id(sprintf("wpst_portlet_col_id_%d", $i)) ;
        }

        //  Add the portlets which were previously queued up
        //  Adding them by column is harder than adding them
        //  by row because you the number of rows is unknown.

        if ($this->getPortletOrdering() == ORDER_PORTLET_BY_COLUMN)
        {
            $p = count($this->__portlets) ;

            //  Portlets may not fit exactly ...

            $rows = floor(0.5 + $p / $pcols) ;

            for ($i = 0, $j = 0, $r = 0 ; $i < count($this->__portlets) ; $i++)
            {
                $td[$j]->add($this->__portlets[$i]) ;

                //  Filled a column?  If so, increment and start again

                if (++$r == $rows)
                {
                    $j++ ;
                    $r = 0 ;
                }
            }
        }
        else
        {
            for ($i = 0, $j = 0 ; $i < count($this->__portlets) ; $i++)
            {
                $td[$j]->add($this->__portlets[$i]) ;
                $j = ($j == $pcols - 1) ? 0 : $j + 1 ;
            }
        }

        //  Add the TD tags to the TR

        for ($i = 0 ; $i < $pcols ; $i++)
            $tr->add($td[$i]) ;

        $table->add($tr) ;
        
        $this->add($table) ;

        //  Now really render the DIVtag
 
        return parent::render() ;
    }
}

/**
 * Class definition of Portet CSS
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see CSSBuilder
 */
class PortletCSS extends CSSBuilder
{
    /**
     * Define the CSS entries
     *
     */
    function user_setup()
    {
        // #wpst_all_open
        $this->add_entry("#wpst_all_open", null,
            array(
                "display" => "none"
            )
        ) ;

        // #wpst_container
        $this->add_entry("#wpst_container", null,
            array(
                "margin" => "0 auto"
               ,"width" => "750px"
            )
        ) ;

        // #wpst_controls
        $this->add_entry("#wpst_controls", null,
            array(
                "float" => "right"
            )
        ) ;

        // #wpst_footer
        $this->add_entry("#wpst_footer", null,
            array(
                "clear" => "both"
               ,"color" => "#443"
               ,"border-top" => "1px dashed #999"
               ,"font-weight" => "bold"
               ,"letter-spacing" => "1px"
               ,"margin" => "0 5px"
               ,"padding" => "5px 0"
               ,"text-align" => "center"
            )
        ) ;

        // #wpst_footer a
        $this->add_entry("#wpst_footer", "a",
            array(
                "color" => "#443"
            )
        ) ;

        // #wpst_header
        $this->add_entry("#wpst_header", null,
            array(
                "background" => "#999"
               ,"border-color" => "#fff #665 #665 #fff"
               ,"border-style" => "solid"
               ,"border-width" => "1px"
               ,"color" => "#fff"
               ,"font-weight" => "bold"
               ,"margin" => "0 0 10px"
               ,"padding" => "5px 10px"
            )
        ) ;

        // #wpst_header a
        $this->add_entry("#wpst_header", "a",
            array(
                "color" => "#fff"
            )
        ) ;

        // #wpst_logo
        $this->add_entry("#wpst_logo", null,
            array(
                "display" => "block"
               ,"margin" => "0 auto"
               ,"width" => "258px"
            )
        ) ;

        // #wpst_logo img
        $this->add_entry("#wpst_logo", "img",
            array(
                "border-top" => "5px solid #999"
               ,"padding" => "10px 0 5px"
            )
        ) ;

        // #wpst_header:hover img
        $this->add_entry("#wpst_header:hover", "img",
            array(
                "border-color" => "#543"
            )
        ) ;

        // #wpst_columns
        $this->add_entry("#wpst_columns", "td",
            array(
                "padding" => "0 5px"
               ,"vertical-align" => "top"
               //,"width" => "240px"
            )
        ) ;

        // .wpst_portlet
        $this->add_entry(".wpst_portlet", null,
            array(
                "background" => "#eee"
               ,"border-color" => "#fff #665 #665 #fff"
               ,"border-style" => "solid"
               ,"border-width" => "1px"
               ,"cursor" => "move"
               ,"margin" => "0 0 10px"
               ,"width" => "238px"
            )
        ) ;

        // .wpst_portlet_topper
        $this->add_entry(".wpst_portlet_topper", null,
            array(
                "background-color" => "#ccc"
               ,"padding" => "5px 10px"
            )
        ) ;

        // .wpst_portlet_topper a
        $this->add_entry(".wpst_portlet_topper", "a",
            array(
                "color" => "#443"
               ,"font-weight" => "bold"
            )
        ) ;

        // .wpst_portlet_content
        $this->add_entry(".wpst_portlet_content", null,
            array(
                "border-top" => "1px solid #999"
               ,"padding" => "10px"
            )
        ) ;

        // .wpst_portlet_content p
        $this->add_entry(".wpst_portlet_content", "p",
            array(
                "line-height" => "150%"
               ,"text-align" => "justify"
               ,"font" => "11px Arial, sans-serif"
            )
        ) ;

        // .wpst_portlet_bottom
        $this->add_entry(".wpst_portlet_bottom", null,
            array(
                "background" => "#ccc"
               ,"font-size" => "1px"
               ,"line-height" => "1px"
               ,"overflow" => "hidden"
               ,"height" => "5px"
            )
        ) ;

        // .wpst_sort_placeholder
        $this->add_entry(".wpst_sort_placeholder", null,
            array(
                "background" => "#bba"
            )
        ) ;

        // .wpst_toggle
        $this->add_entry(".wpst_toggle", null,
            array(
                "float" => "right"
            )
        ) ;

    }   
}

/**
 * Class definition of One Column Portet CSS
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see PortletCSS
 */
class OneColumnPortletCSS extends PortletCSS
{
    /**
     * Define the CSS entries
     *
     */
    function user_setup()
    {
        //  Start with the parent entries, then change
        //  the one needed to arrange the columns correctly.

        parent::user_setup() ;

        // #wpst_columns
//        $this->add_entry("#wpst_columns", "td",
//            array(
//                "padding" => "0 5px"
//               ,"vertical-align" => "top"
//               ,"width" => "720px"
//            )
//        ) ;

        // .wpst_portlet
        $this->add_entry(".wpst_portlet", null,
            array(
                "background" => "#eee"
               ,"border-color" => "#fff #665 #665 #fff"
               ,"border-style" => "solid"
               ,"border-width" => "1px"
               ,"cursor" => "move"
               ,"margin" => "0 0 10px"
               ,"width" => "740px"
            )
        ) ;
    }
}

/**
 * Class definition of Two Column Portet CSS
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see PortletCSS
 */
class TwoColumnPortletCSS extends PortletCSS
{
    /**
     * Define the CSS entries
     *
     */
    function user_setup()
    {
        //  Start with the parent entries, then change
        //  the one needed to arrange the columns correctly.

        parent::user_setup() ;

//        // #wpst_columns
//        $this->add_entry("#wpst_columns", "td",
//            array(
//                "padding" => "0 5px"
//               ,"vertical-align" => "top"
//               ,"width" => "3050px"
//            )
//        ) ;

        // .wpst_portlet
        $this->add_entry(".wpst_portlet", null,
            array(
                "background" => "#eee"
               ,"border-color" => "#fff #665 #665 #fff"
               ,"border-style" => "solid"
               ,"border-width" => "1px"
               ,"cursor" => "move"
               ,"margin" => "0 0 10px"
               ,"width" => "360px"
            )
        ) ;
    }
}

/**
 * Class definition of Three Column Portet CSS
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see PortletCSS
 */
class ThreeColumnPortletCSS extends PortletCSS
{
    /**
     * Define the CSS entries
     *
     */
    function user_setup()
    {
        //  Start with the parent entries, then change
        //  the one needed to arrange the columns correctly.

        parent::user_setup() ;

        //  Default is 3 columns, no need to do anything else ...
    }
}

/**
 * Class definition of One Column Portet CSS
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @access public
 * @see PortletCSS
 */
class FourColumnPortletCSS extends PortletCSS
{
    /**
     * Define the CSS entries
     *
     */
    function user_setup()
    {
        //  Start with the parent entries, then change
        //  the one needed to arrange the columns correctly.

        parent::user_setup() ;

//        // #wpst_columns
//        $this->add_entry("#wpst_columns", "td",
//            array(
//                "padding" => "0 5px"
//               ,"vertical-align" => "top"
//               ,"width" => "240px"
//            )
//        ) ;

        // .wpst_portlet
        $this->add_entry(".wpst_portlet", null,
            array(
                "background" => "#eee"
               ,"border-color" => "#fff #665 #665 #fff"
               ,"border-style" => "solid"
               ,"border-width" => "1px"
               ,"cursor" => "move"
               ,"margin" => "0 0 10px"
               ,"width" => "170px"
            )
        ) ;
    }
}
?>
