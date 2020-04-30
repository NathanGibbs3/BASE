<?php
// Html Template
$Hrst = "<a class='menuitem' href='$BASE_urlpath/base_"; // Href tag start.
$Sep = ' | '; // Separator.
NLIO ("<div class='mainheadermenu'>",2);
NLIO ("<table width='90%' border='0'>",3);
NLIO ('<tr>',4);
NLIO ("<td class='menuitem'>",5);
NLIO ($Hrst."main.php'>"._HOME.'</a>'.$Sep,6);
NLIO ($Hrst."qry_main.php?new=1'>"._SEARCH.'</a>',6);
if($Use_Auth_System == 1) {
	print $Sep;
	NLIO ($Hrst."user.php'>"._USERPREF.'</a>'.$Sep,6);
	NLIO ($Hrst."logout.php'>"._LOGOUT.'</a>',6);
}
NLIO ('</td>',5);
NLIO ('</tr>',4);
NLIO ('</table>',3);
NLIO ('</div>',2);
unset($Hrst);
unset($Sep);
?>
