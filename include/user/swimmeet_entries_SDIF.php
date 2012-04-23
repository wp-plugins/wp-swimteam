<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Swim Meet Entries Export SDIF page content.
 *
 * $Id$
 *
 * (c) 2012 by Mike Walsh
 *
 * @author Mike Walsh <mpwalsh8@gmail.com>
 * @package swimteam
 * @subpackage SDIF
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

    if (array_key_exists('file', $args))
    {
        $sdif = '' ;

        $file = urldecode($args['file']) ;

        $fh = fopen($file, 'r') or die('Unable to load file, something bad has happened.') ;

        while (!feof($fh))
            $sdif .= fread($fh, 1024) ;

        //  Clean up the temporary file - permissions
        //  may prevent this from succeedeing so use the '@'
        //  to suppress any messages from PHP.

        @unlink($file) ;

        // Tell browser to expect a SD3/SDIF file
 
        header('Content-Type: application/text');
        header('Content-disposition:  attachment; filename=SwimMeetEntries-' .  date('Y-m-d').'.sd3') ;
        print $sdif ;
    }
    else
    {
        die('Invalid argument, something bad has happened.') ;
    }
}
else
{
    die('No argument, something bad has happened.') ;
}
?>
