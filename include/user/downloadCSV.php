<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Export CSV page content.
 *
 * $Id: downloadCSV.php 849 2012-05-09 16:03:20Z mpwalsh8 $
 *
 * (c) 2008 by Mike Walsh
 *
 * @author Mike Walsh <mike_walsh@mindspring.com>
 * @package swimteam
 * @subpackage admin
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
        header("Content-disposition:  attachment; filename=SwimTeamCSVData-" .  date("Y-m-d").".csv") ;
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
