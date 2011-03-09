<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Roster Export RE1 page content.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage RE1
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

    if (array_key_exists("file", $args))
    {
        $re1 = "" ;

        $file = urldecode($args["file"]) ;

        $fh = fopen($file, "r") or die("Unable to load file, something bad has happened.") ;

        while (!feof($fh))
            $re1 .= fread($fh, 1024) ;

        //  Clean up the temporary file - permissions
        //  may prevent this from succeedeing so use the "@"
        //  to suppress any messages from PHP.

        @unlink($file) ;

        // Tell browser to expect a RE1 text file
 
        header('Content-Type: application/text');
        header("Content-disposition:  attachment; filename=SwimTeamRoster-" .  date("Y-m-d").".re1") ;
        print $re1 ;
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
