<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: widgets.class.php 1065 2014-09-22 13:04:25Z mpwalsh8 $
 *
 * Widget classes.  These classes create and/or extend
 * phpHtmlLib based widgets used by the Wp-SwimTeam plugin.
 *
 * (c) 2007 by Mike Walsh for WpSwimTeam.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package Wp-SwimTeam
 * @subpackage widget
 * @version $Revision: 1065 $
 * @lastmodified $Date: 2014-09-22 09:04:25 -0400 (Mon, 22 Sep 2014) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */

require_once(PHPHTMLLIB_ABSPATH . '/widgets/data_list/includes.inc') ;
require_once(PHPHTMLLIB_ABSPATH . '/widgets/data_list/WordPressSQLDataListSource.inc') ;

require_once(WPST_PATH . 'include/db.include.php') ;

/**
 * Class definition for the tab content
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access private
 */
class TabWidgetContent
{
    /**
     * Tab Label
     */
    var $_label ;

    /**
     * Tab Index
     */
    var $_index ;

    /**
     * Tab Include File
     */
    var $_include_file ;

    /**
     * Tab Class Name
     */
    var $_class_name ;

    /**
     * Class Constructor
     *
     * @return void
     */
    function TabWidgetContent($label, $index, $include_file, $class_name)
    {
        $this->setLabel($label) ;
        $this->setIndex($index) ;
        $this->setIncludeFile($include_file) ;
        $this->setClassName($class_name) ;
    }

    /**
     * Set Tab Label
     *
     * @param string - tab label
     */
    function setLabel($label)
    {
        $this->_label = $label ;
    }

    /**
     * Get Tab Label
     *
     * @return string - tab label
     */
    function getLabel()
    {
        return $this->_label ;
    }

    /**
     * Set Tab Index
     *
     * @param string - tab index
     */
    function setIndex($index)
    {
        $this->_index = $index ;
    }

    /**
     * Get Tab Index
     *
     * @return string - tab index
     */
    function getIndex()
    {
        return $this->_index ;
    }

    /**
     * Set Tab IncludeFile
     *
     * @param string - tab include file
     */
    function setIncludeFile($include_file)
    {
        $this->_include_file = $include_file ;
    }

    /**
     * Get Tab Include File
     *
     * @return string - tab include file
     */
    function getIncludeFile()
    {
        return $this->_include_file ;
    }

    /**
     * Set Tab Class Name
     *
     * @param string - tab class name
     */
    function setClassName($class_name)
    {
        $this->_class_name = $class_name ;
    }

    /**
     * Get Tab Class Name
     *
     * @return string - tab class name
     */
    function getClassName()
    {
        return $this->_class_name ;
    }
}

/**
 * Class to construct Javscript Back and Home buttons
 *
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see SPANtag
 */
class SwimTeamGUIButtons extends SPANtag
{
    /**
     * Get Back and Home buttons
     *
     * @param string - optional URI to embed in the javascript.
     * @return object - HTML span containing Back and Home buttons.
     */
    function getBackHomeButtons($uri = null)
    {
        if (is_null($uri))
            $uri = SwimTeamUtils::GetPageURI() ;

        $back = html_button('button', 'Back') ;
        $back->set_tag_attribute('onclick',
            'javascript:window.history.back() ;') ;
        $back->set_tag_attribute('style', 'margin: 10px;') ;

        $home = html_button('button', 'Home') ;
        $home->set_tag_attribute('onclick',
            'javascript:document.location=\'' . $uri . '\' ;') ;
        $home->set_tag_attribute('style', 'margin: 10px;') ;

        return html_span(null, $back, $home) ;
    }

    /**
     * Get Done button
     *
     * @param string - optional URI to embed in the javascript.
     * @return object - HTML span containing Back and Home buttons.
     */
    function getDoneButton($uri = null)
    {
        if (is_null($uri))
            $uri = SwimTeamUtils::GetPageURI() ;

        $done = html_button('button', 'Done') ;
        $done->set_tag_attribute('onclick',
            'javascript:document.location=\'' . $uri . '\' ;') ;
        $done->set_tag_attribute('style', 'margin: 10px;') ;

        return html_span(null, $done) ;
    }

    /**
     * Get 'Custom' button
     *
     * @param string - text to display on the button
     * @param string - optional URI to embed in the javascript.
     * @return object - HTML span containing Back and Home buttons.
     */
    function getButton($text, $uri = null)
    {
        if (is_null($uri))
            $uri = SwimTeamUtils::GetPageURI() ;

        $button = html_button('button', $text) ;
        $button->set_tag_attribute('onclick',
            'javascript:document.location=\'' . $uri . '\' ;') ;
        $button->set_tag_attribute('style', 'margin: 10px;') ;

        return html_span(null, $button) ;
    }
}

/**
 * Extended GUIDataList Class for presenting SwimTeam
 * information extracted from the database.
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access public
 * @see DefaultGUIDataList
 */
class SwimTeamGUIDataList extends DefaultGUIDataList
{
	// change the # of rows to display to 15 from 10
	var $_default_rows_per_page = 15 ;

    /**
     * Class properties to drive the GUIDataList
     */

    var $__columns ;
    var $__tables ;
    var $__where_clause ;

    /**
     * Property to store the requested action
     */
    var $__action = null ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__normal_actions = array() ;

    /**
     * Property to store the possible actions - used to build action buttons
     */
    var $__empty_actions = array() ;

    /**
     * Get admin action
     *
     * @return string - action to take
     */
    function getAdminAction()
    {
        return $this->__action ;
    }

    /**
     * Set radio var
     *
     * @param string - action to take
     */
    function set_radio_var_name($radioVarName, $useglobalprefix = true)
    {
        $this->_vars['radioVar'] = ($useglobalprefix) ?
            $this->get_global_prefix() . $radioVarName : $radioVarName ;
    }

    /**
     * Set admin action
     *
     * @param string - action to take
     */
    function setAdminAction($action)
    {
        $this->__action = $action ;
    }

    /**
     * The constructor
     *
     * @param string - the title of the data list
     * @param string - the overall width
     * @param string - the column to use as the default sorting order
     * @param boolean - sort the default column in reverse order?
     * @param string - columns to query return from database
     * @param string - tables to query from database
     * @param string - where clause for database query
     */
    function SwimTeamGUIDataList($title, $width = '100%',
        $default_orderby = '', $default_reverseorder = false,
        $columns, $tables, $where_clause)
    {
        //  Set the properties for this child class
        $this->setColumns($columns) ;
        $this->setTables($tables) ;
        $this->setWhereClause($where_clause) ;

        //  Call the constructor of the parent class
        parent::DefaultGUIDataList($title, $width,
            $default_orderby, $default_reverseorder) ;

        //  Alternate row colors
        $this->set_alternating_row_colors(true) ;

        //  Set the number of rows to display based on the configuration
        $rows_to_display = get_option(WPST_OPTION_GDL_ROWS_TO_DISPLAY) ;
        $this->_default_rows_per_page = ($rows_to_display !== false) ?
            $rows_to_display : WPST_DEFAULT_GDL_ROWS_TO_DISPLAY ;
    }

    /**
     * Return columns which GUIDataList is sourced from
     *
     * @return string - the comma separated column list
     *
     */
    function getColumns()
    {
        return $this->__columns ;
    }

    /**
     * Set columns which GUIDataList is sourced from
     *
     * @param string - $columns - the comma separated column list
     *
     */
    function setColumns($columns)
    {
        $this->__columns = $columns ;
    }

    /**
     * Return tables which GUIDataList is sourced from
     *
     * @return string - the comma separated table list
     *
     */
    function getTables()
    {
        return $this->__tables ;
    }

    /**
     * Set table(s) which GUIDataList is sourced from
     *
     * @param string - $tables - the comma separated table list
     *
     */
    function setTables($tables)
    {
        $this->__tables = $tables ;
    }

    /**
     * Return the WHERE CLAUSE which GUIDataList is sourced from
     *
     * @return string - the WHERE CLAUSE
     *
     */
    function getWhereClause()
    {
        return $this->__where_clause ;
    }

    /**
     * Set the WHERE CLAUSE which GUIDataList is sourced from
     *
     * @param string - $where_clause - the WHERE CLAUSE
     *
     */
    function setWhereClause($where_clause)
    {
        $this->__where_clause = $where_clause ;
    }

	/**
	 * This function is called automatically by
	 * the DataList constructor.  It must be
	 * extended by the child class to actually
	 * set the DataListSource object.
	 *
	 * 
	 */
    function get_data_source()
    {
		//build the PEAR DB object and connect
		//to the database.

        $db = new wpdb(WPST_DB_USERNAME,
            WPST_DB_PASSWORD, WPST_DB_NAME, WPST_DB_HOSTNAME);

		//  Create the DataListSource object
		//  and pass in the WordPress DB object
		$source = new WordPressSQLDataListSource($db) ;
		//$source = new PEARSQLDataListSource($wpdb->dbh) ;

		//  Set the DataListSource for this DataList
		//  Every DataList needs a Source for it's data.
		$this->set_data_source($source) ;

		//  Set the prefix for all the internal query string 
		//  variables.  You really only need to change this
		//  if you have more then 1 DataList object per page.
		$this->set_global_prefix(WPST_DB_PREFIX) ;
	}

	/**
     * This method is used to setup the optons
	 * for the DataList object's display. 
	 * Which columns to show, their respective 
	 * source column name, width, etc. etc.
	 *
     * The constructor automatically calls 
	 * this function.
	 *
     */
    function user_setup()
    {
        user_error('SwimTeamGUIDataList::user_setup() - child class ' .
            'must override this to set the the database table.') ;
	}

    /**
     * This is the basic function for letting us
     * do a mapping between the column name in
     * the header, to the value found in the DataListSource.
     *
     * NOTE: this function can be overridden so that you can
     *       return whatever you want for any given column.  
     *
     * @param array - $row_data - the entire data for the row
     * @param string - $col_name - the name of the column header
     *                             for this row to render.
     * @return mixed - either a HTMLTag object, or raw text.
     */
	function build_column_item($row_data, $col_name)
    {
		switch ($col_name)
        {
                /*
            case 'Updated' :
                $obj = strftime('%Y-%m-%d @ %T', (int)$row_data['updated']) ;
                break ;
                */

		    default:
			    $obj = DefaultGUIDataList::build_column_item($row_data, $col_name);
			    break;
		}
		return $obj;
	}

    /**
     * Action Bar - build a set of Action Bar buttons
     *
     * @return container - container holding action bar content
     */
    function actionbar_cell($actions = null)
    {
        //  Add an ActionBar button based on the action the page
        //  was called with.

        $sa = array() ;
        $c = container() ;

        if (is_null($actions)) $actions = $this->__normal_actions ;

        foreach($actions as $key => $action)
            $sa[$action] = $key ;

        $lb = $this->action_select('_action', $sa, '', false,
            array('style' => 'width: 150px; margin-right: 10px;'),
            SwimTeamUtils::GetPageURI()) ;

        $c->add($lb) ;

        return $c ;
    }

    /**
     * Action Bar - build a set of Action Bar buttons
     *
     * @return container - container holding action bar content
     */
    function empty_datalist_actionbar_cell()
    {
        return $this->actionbar_cell($this->__empty_actions) ;
    }

    /**
     * This function overloads the DataList class function
     * supplied with phpHtmlLib.  As delivered, the build_base_url()
     * function incorrectly builds a page in certain instances in the
     * Wordpress Admin chain.  By using REQUEST_URI instead of PHP_SELF
     * the correct URL is constructed and the widget works as expected.
     *
     * This builds the base url used
     * by the column headers as well
     * as the page tool links.
     *
     * it basically builds:
     * $_SELF?$_GET - not anymore, now it builds $_SELF?$_QUERY_STRING
     *
     * @return string
     */
    function build_base_url()
    {
        //$url = $_SERVER['PHP_SELF'].'?';
        //$uri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ;
        //$uri = SwimTeamUtils::GetPageQuery() ;
        $uri = SwimTeamUtils::GetPageURI() ;
        $url = SwimTeamUtils::GetPageURL() . '?' ;

        //  On a POST, return the URI instead of
        //  constructing a page url.
        if ( $this->get_form_method() == 'POST' ) {
            return $uri;
        }

        $vars = array_merge($_POST, $_GET);
        //request method independant access to
        //browser variable
        if ( count($vars) ) {
            //walk through all of the get vars
            //and add them to the url to save them.
            foreach($vars as $name => $value) {

                if ( $name != $this->_vars['offsetVar'] &&
                     $name != $this->_vars['orderbyVar'] &&
                     $name != $this->_vars['reverseorderVar'] &&
                     $name != $this->_vars['search_valueVar']
                   ) {
                    if ( is_array($value) ) {
                        $url .= $name.'[]='.implode('&'.$name.'[]=',$value).'&';
                    } else {
                        $url .= $name.'='.urlencode(stripslashes($value)).'&';
                    }
                }
            }
        }

        return $url;
    }
}

/**
 * Class definition for useful static methods
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @access private
 */
class SwimTeamUtils
{
    /**
     * Get Page URL
     *
     * @return string
     */
    function GetPageURL()
    {
        global $pagenow ;

        return get_admin_url() . $pagenow  ;
    }

    /**
     * Get Page URI
     *
     * @return string
     */
    function GetPageURI()
    {
        global $pagenow ;

        return admin_url($pagenow .'?'. $_SERVER['QUERY_STRING']) ;
    }
}
?>
