<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Roster Export SDIF page content.
 *
 * $Id: rosterSDIF.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage SDIF
 * @version $Revision: 849 $
 * @lastmodified $Date: 2012-05-09 12:03:20 -0400 (Wed, 09 May 2012) $
 * @lastmodifiedby $Author: mpwalsh8 $
 *
 */


//  Handle either GET or POST requests

$args = array_merge($_POST, $_GET) ;

//  Make sure we have some arguments

if (!empty($args))
{
    //  Make sure we have the right argument

    if (array_key_exists("file", $args))
    {
        $sdif = "" ;

        $file = urldecode($args["file"]) ;

        $fh = fopen($file, "r") or die("Unable to load file, something bad has happened.") ;

        while (!feof($fh))
            $sdif .= fread($fh, 1024) ;

        //  Clean up the temporary file - permissions
        //  may prevent this from succeedeing so use the "@"
        //  to suppress any messages from PHP.

        @unlink($file) ;

        // Tell browser to expect a SD3/SDIF file
 
        header('Content-Type: application/text');
        header("Content-disposition:  attachment; filename=SwimTeamRoster-" .  date("Y-m-d").".sd3") ;
        print $sdif ;
    }
    else
    {
        die("Invalid argument, something bad has happened.") ;
    }
}
else
{
    die("No argument, something bad has happened.") ;
}
?>
