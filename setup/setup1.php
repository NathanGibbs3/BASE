<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: This file is the first step in the setup program
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
session_start();

define( "_BASE_INC", 1 );
include("../includes/base_setup.inc.php");
include("../includes/base_state_common.inc.php");

if (file_exists('../base_conf.php'))
	die ("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");

$errorMsg = '';

/* build array of languages */
$i = 0;
if ($handle = opendir('../languages')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != ".." && $file != "CVS" && $file != "index.php") {
           $filename = explode(".", $file);
           $languages[$i] = $filename[0];
           $i++;
           
       }
   }
   closedir($handle);
}

if (@$_GET['action'] == "check")
{
    // form has been submitted.  Check answers.
    $_SESSION['language'] = ImportHTTPVar("language", "", $languages);
    
    //Check path to ADODB
    $adodbexists = file_exists($_POST['adodbpath'] . "/adodb.inc.php");
    if ($adodbexists != 1){
        $errorMsg = $errorMsg . "<br>The Path to ADODB does not appear to be correct!<br>";
        $errorMsg = $errorMsg . "Please correct.";
        $error = 1;
    } else {
        $_SESSION['adodbpath'] = realpath($_POST['adodbpath']);
        $error = 0;
    }
    
    if ($error != 1)
    {
        header("Location: setup2.php");
	die (__FILE__ . ":" . __LINE__ . ": ERROR: This line should not have been reached.<BR>\n");
    }
    
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- Basic Analysis and Security Engine (BASE) -->
<HTML>

<HEAD>
  <META HTTP-EQUIV="pragma" CONTENT="no-cache">
  <TITLE>Basic Analysis and Security Engine (BASE)</TITLE>
  <LINK rel="stylesheet" type="text/css" HREF="../styles/base_style.css">
</HEAD>
<BODY>
<TABLE WIDTH="100%" BORDER=0 CELLSPACING=0 CELLPADDING=5>
    <TR>
      <TD class="mainheader"> &nbsp </TD>
      <TD class="mainheadertitle">
         Basic Analysis and Security Engine (BASE) Setup Program
      </TD>
    </TR>
</TABLE>
<br>
<P>
<?php echo("<div class='errorMsg' align='center'>".$errorMsg."</div>"); ?>
<form action=setup1.php?action=check method="POST">
<center><table width="50%" border=1 class ="query">
<tr><td colspan=2 align="center" class="setupTitle">Step 1 of 5</td><tr>
<tr><td class="setupKey" width="50%">Pick a Language:</td><td class="setupValue"><select name="language">
<?php
    $langCount = count($languages);
    for ($y = 0; $y < $langCount; $y++) {
        /* If there is language saved from session then make it selected. 
         * If there was no session language - make 'english' selected.
         */        
        if (array_key_exists('language', $_SESSION)) 
        {
            if ( 
                 ($languages[$y] == $_SESSION['language']) || 
                 ($_SESSION['language'] == '' && $languages[$y] == 'english')
               )           
            {
              echo("<OPTION name='".$languages[$y]."' SELECTED>".$languages[$y]);
            }
            else
            {
              echo("<OPTION name='".$languages[$y]."'>".$languages[$y]);
            }
        }
        else
        {
            if ($languages[$y] == 'english')
            {
               echo("<OPTION name='".$languages[$y]."' SELECTED>".$languages[$y]);
            }
            else
            {
              echo("<OPTION name='".$languages[$y]."'>".$languages[$y]);
            }
        }
    }
?>
</select>
[<a href="../help/base_setup_help.php#language" onClick="javascript:window.open('../help/base_setup_help.php#language','helpscreen','width=300,height=300');">?</a>]
</td></tr>
<tr><td class="setupKey">
Path to ADODB:
</td><td class="setupValue">
<input type="text" name="adodbpath" value="<?php echo(@$_POST['adodbpath']); ?>">
[<a href="../help/base_setup_help.php#adodb" onClick="javascript:window.open('../help/base_setup_help.php#adodb','helpscreen','width=300,height=300');">?</a>]
</td></tr>
<tr><td colspan=2 align="center"><input type="submit" value="Continue"></td></tr>
</table></center></form>
</BODY>
</HTML>
