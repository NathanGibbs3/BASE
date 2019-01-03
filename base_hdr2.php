<?php

echo "	<div class='mainheadermenu'>"
. "	<table width='90%' border='0'>"
. "	<tr>"
. "	<td class='menuitem'>"
. "	<a class='menuitem' href='$BASE_urlpath/base_main.php'>"._HOME."</a>&nbsp;&nbsp;|&nbsp;&nbsp;"
. "	<a class='menuitem' href='$BASE_urlpath/base_qry_main.php?new=1'>"._SEARCH."</a>&nbsp;&nbsp;";

if($Use_Auth_System == 1) {
	echo "|&nbsp;&nbsp;<a class='menuitem' href='$BASE_urlpath/base_user.php'>"._USERPREF."</a>";
	echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a class='menuitem' href='$BASE_urlpath/base_logout.php'>"._LOGOUT."</a>";
}

echo "</td>"
. "</tr>"
. "</table></div>";

?>
