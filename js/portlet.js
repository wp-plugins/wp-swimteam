/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 *
 * $Id: portlet.js 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * jQuery Portet support - used to reorder events
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
 */
jQuery(document).ready(
	function()
	{
		// Toggle Single Portlet
		jQuery('a.wpst_toggle').click(function()
			{
				jQuery(this).parent('div').next('div').toggle();
				return false;
			}
		);

		// Invert All Portlets
		jQuery('a#wpst_all_invert').click(function()
			{
				jQuery('div.wpst_portlet_content').toggle();
				return false;
			}
		);

		// Expand All Portlets
		jQuery('a#wpst_all_expand').click(function()
			{
				jQuery('div.wpst_portlet_content:hidden').show();
				return false;
			}
		);

		// Collapse All Portlets
		jQuery('a#wpst_all_collapse').click(function()
			{
				jQuery('div.wpst_portlet_content:visible').hide();
				return false;
			}
		);

		// Open All Portlets
		jQuery('a#wpst_all_open').click(function()
			{
				jQuery('div.wpst_portlet:hidden').show();
				jQuery('a#wpst_all_open:visible').hide();
				jQuery('a#wpst_all_close:hidden').show();
				return false;
			}
		);

		// Close All Portlets
		jQuery('a#wpst_all_close').click(function()
			{
				jQuery('div.wpst_portlet:visible').hide();
				jQuery('a#wpst_all_close:visible').hide();
				jQuery('a#wpst_all_open:hidden').show();
				return false;
			}
		);

		// Controls Drag + Drop
		jQuery('#wpst_columns td').sortable({
				connectWith: 'wpst_portlet',
				helper: 'wpst_sort_placeholder',
				opacity: 0.7,
				tolerance: 'intersect',
                change: function (sorted) {
                    serial = jQuery.SortSerialize('wpst_container');
                    jQuery("div#resultarea").text(jQuery('wpst_columns td').sortable('serialize'));
                alert(serial.hash) ;
                alert('change ...') ;
                    }
            });
    });
                //alert('change ...') ; }
                //    jQuery.post('http://localhost/wp-admin/admin-ajax.php',
                //        {action:'wpst_reorder_events'},
                //        function(str) { alert(str); });
                //
