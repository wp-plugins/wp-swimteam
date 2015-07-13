<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Roster Export SDIF page content.
 *
 * $Id$
 *
 * (c) 2012 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package SwimTeam
 * @subpackage Download
 * @version $Revision$
 * @lastmodified $Date$
 * @lastmodifiedby $Author$
 *
 */

//  Handle either GET or POST requests

$args = array_merge($_POST, $_GET) ;

//  Make sure we have some arguments

if (!empty($args))
{
    //  Make sure we have the right argument

    if ((array_key_exists('filename', $args)) &&
        (array_key_exists('contenttype', $args)) &&
        (array_key_exists('wpstnonce', $args)) &&
        ((array_key_exists('file', $args)) ||
        ((array_key_exists('transient', $args) && array_key_exists('abspath', $args)))))
    {
        $txt = '' ;
        $abspath = urldecode($args['abspath']) ;

        $wpl = $abspath . DIRECTORY_SEPARATOR . 'wp-load.php' ;

        //  Make sure wp-load.php exists and is readable!
        if (!is_readable($wpl)) {
            die('Invalid argument(s), unable to load WordPress.') ;
        }

        //  Load WordPress
        require($abspath . DIRECTORY_SEPARATOR . 'wp-load.php') ;

        //  Verify WordPress nonce
        $wpstnonce = urldecode($args['wpstnonce']) ;
        if (!wp_verify_nonce($wpstnonce, 'wpst-nonce') )
            die("Security check failed, permission denied.");

        //  Use transients instead of temporary files for storage?

        if (get_option(WPST_OPTION_USE_TRANSIENTS) === WPST_YES)
        {
            
            $transient = urldecode($args['transient']) ;
            $txt = get_transient($transient) ;
            delete_transient($transient) ;
        }
        else
        {
            $file = urldecode($args['file']) ;
            $fh = fopen($file, 'r') or die('Unable to load file, something bad has happened.') ;

            while (!feof($fh))
                $txt .= fread($fh, 1024) ;

            //  Clean up the temporary file - permissions
            //  may prevent this from succeedeing so use the '@'
            //  to suppress any messages from PHP.

            @unlink($file) ;
        }

        $filename = urldecode($args['filename']) ;
        $contenttype = urldecode($args['contenttype']) ;

        // Tell browser to expect a text file of some sort (usually txt or csv)
 
        header(sprintf('Content-Type: application/%s', $contenttype)) ;
        header(sprintf('Content-disposition:  attachment; filename=%s', $filename)) ;
        print $txt ;
    }
    else
    {
        die('Invalid argument(s), something bad has happened.') ;
    }
}
else
{
    die('No arguments, something bad has happened.') ;
}
?>
