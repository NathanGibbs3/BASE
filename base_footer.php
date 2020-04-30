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
	// Html Templates
	$Hrst = "<a class='menuitem' href='$BASE_urlpath/"; // Href tag start.
	$HrstTL = $Hrst . 'base_'; // Top Level Pages.
	$Sep = ' | '; // Separator.
	NLIO ("<div class='mainheadermenu'>",2);
	NLIO ("<table width='90%' style='border:0'>",3);
	NLIO ('<tr>',4);
	NLIO ("<td class='menuitem'>",5);
	NLIO ($HrstTL."ag_main.php?ag_action=list'>". _AGMAINT."</a>$Sep",6);
	NLIO ($HrstTL."maintenance.php'>". _CACHE."</a>$Sep",6);
	if ($Use_Auth_System == 1){
		NLIO ($HrstTL."user.php'>". _USERPREF ."</a>$Sep",6);
		NLIO ($HrstTL."logout.php'>". _LOGOUT ."</a>$Sep",6);
	}
	NLIO ($Hrst."admin/index.php'>". _ADMIN .'</a>',6);
	NLIO ('</td>',5);
	NLIO ('</tr>',4);
	NLIO ('</table>',3);
	NLIO ('</div>',2);
	unset($Sep);
	unset($Hrst);
	unset($HrstTL);
}
NLIO ("<div class='mainfootertext'>",2);
NLIO (
	"<a class='largemenuitem' href='https://github.com/NathanGibbs3/BASE' "
	."target='_new'>BASE</a>'"
	,3
);
if ( !preg_match("/(base_denied|index).php/", $_SERVER['SCRIPT_NAME']) ) {
	NLIO ( $BASE_VERSION . _FOOTER,3);
}else{
	NLIO ( _FOOTER,3);
}
NLIO ('</div>',2);
?>
