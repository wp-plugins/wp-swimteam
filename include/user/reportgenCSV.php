<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Report Generator CSV page content.
 *
 * $Id$
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
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
        $csv = "" ;

        $file = urldecode($args["file"]) ;

        $fh = fopen($file, "r") or die("Unable to load file, something bad has happened.") ;

        while (!feof($fh))
            $csv .= fread($fh, 1024) ;

        //  Clean up the temporary file - permissions
        //  may prevent this from succeedeing so use the "@"
        //  to suppress any messages from PHP.

        @unlink($file) ;

        // Tell browser to expect a CSV file
 
        header('Content-Type: application/csv');
        header("Content-disposition:  attachment; filename=SwimTeamReport-" .  date("Y-m-d").".csv") ;
        print $csv ;
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
