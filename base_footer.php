<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Footer for each page.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

if (!isset($noDisplayMenu)) {
	print "\n".str_repeat ( "\t",3 )."<div class='mainheadermenu'>";
	print "\n".str_repeat ( "\t",4 )."<table width='90%' style='border:0'>";
	print "\n".str_repeat ( "\t",5 )."<tr><td class='menuitem'>";
    echo "
                <a class='menuitem' href='". $BASE_urlpath ."/base_ag_main.php?ag_action=list'>". _AGMAINT."</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a class='menuitem' href='". $BASE_urlpath ."/base_maintenance.php'>". _CACHE."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
    if ($Use_Auth_System == 1)
    {
        echo("<a class='menuitem' href='". $BASE_urlpath ."/base_user.php'>". _USERPREF ."</a>&nbsp;&nbsp;|&nbsp;&nbsp;");
        echo("<a class='menuitem' href='". $BASE_urlpath ."/base_logout.php'>". _LOGOUT ."</a>&nbsp;&nbsp;|&nbsp;&nbsp;");
    }
    echo "<a class='menuitem' href='". $BASE_urlpath ."/admin/index.php'>". _ADMIN ."</a>";
	print "\n".str_repeat ( "\t",5 ).'</td></tr>';
	print "\n".str_repeat ( "\t",4 ).'</table>';
	print "\n".str_repeat ( "\t",3 ).'</div>';
}
print "\n".str_repeat ( "\t",3 ).'<div class="mainfootertext">';
print "\n".str_repeat ( "\t",4 ).'<a';
print "\n".str_repeat ( "\t",5 ).'class="largemenuitem"';
print "\n".str_repeat ( "\t",5 ).'href="https://github.com/NathanGibbs3/BASE"';
print "\n".str_repeat ( "\t",5 ).'target="_new"';
print "\n".str_repeat ( "\t",4 ).'>BASE</a>';
if ( !preg_match("/(base_denied|index).php/", $_SERVER['SCRIPT_NAME']) ) {
	print "\n".str_repeat ( "\t",4 ). $BASE_VERSION . _FOOTER;
}else{
	print "\n".str_repeat ( "\t",4 ). _FOOTER;
}
print "\n".str_repeat ( "\t",3 ).'</div>';
?>
