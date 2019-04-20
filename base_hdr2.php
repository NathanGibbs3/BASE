<?php
// Html Template
$Hrst = "<a class='menuitem' href='$BASE_urlpath/base_"; // Href tag start.
print "\n".str_repeat("\t",2)."<div class='mainheadermenu'>";
print "\n".str_repeat("\t",3)."<table width='90%' border='0'>";
print "\n".str_repeat("\t",4).'<tr>';
print "\n".str_repeat("\t",5)."<td class='menuitem'>";
print "\n".str_repeat("\t",6).$Hrst."main.php'>"._HOME."</a>";
print "\n".str_repeat("\t",6)."| $Hrst"."qry_main.php?new=1'>"._SEARCH."</a>";
if($Use_Auth_System == 1) {
	print "\n".str_repeat("\t",6)."| $Hrst"."user.php'>"._USERPREF."</a>";
	print "\n".str_repeat("\t",6)."| $Hrst"."logout.php'>"._LOGOUT."</a>";
}
unset($Hrst);
print "\n".str_repeat("\t",5).'</td>';
print "\n".str_repeat("\t",4).'</tr>';
print "\n".str_repeat("\t",3).'</table>';
print "\n".str_repeat("\t",2).'</div>';
?>
