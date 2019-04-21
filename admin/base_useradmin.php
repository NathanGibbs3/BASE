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
//            Purpose: User management functions (create, disable etc....)
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

include("../base_conf.php");
include("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");
include_once("$BASE_path/base_stat_common.php");

$et = new EventTiming($debug_time_mode);
$Action = filterSql($_GET['action']);

if ( preg_match("/(create|add)/", $Action) || $Use_Auth_System == 1 ){
	$BAStmp = $Use_Auth_System;
	if ($Use_Auth_System == 0) {
		$Use_Auth_System = 1;
	}
	$UIL = new UILang($BASE_Language); // Create UI Language Object.
	$Use_Auth_System = $BAStmp;
	$cs = new CriteriaState("admin/base_useradmin.php");
	$cs->ReadState();
	// Check role out and redirect if needed -- Kevin
	$roleneeded = 1;
	$BUser = new BaseUser();
	if (($BUser->hasRole($roleneeded) == 0) && $Use_Auth_System == 1){
		base_header("Location: ". $BASE_urlpath . "/base_main.php");
	}else{
		$page_title = _USERADMIN;
		// Html Templates
		$Umca = "base_useradmin.php?action="; // User Managemnt Common Action.
		$Fst = "<form action='$Umca"; // Form tag start.
		$Fct = " Method='POST'>"; // Form tag end.
		$Hrst = "<a href='$Umca"; // Href tag start.
		$Trc = "\n".str_repeat("\t",5).'</tr><tr>'; // Table row continue.
		// I would like to clean this up later into a display class or set of functions -- Kevin
		if ( preg_match("/((delet|(dis|en)abl)e|edit)user/", $Action) ){
			$userid = filterSql($_GET['userid']);
		}
		if ( preg_match("/(create|list|(edit|update)user)/", $Action) ){
			if ( preg_match("/(create|list|edituser)/", $Action) ){
				$LoginDesc = $UIL->ADA['DescUN'];
			}
			$user = new BaseUser();
		}else{ // 2 vars for this?! No idea why. Will keep for now. -- Nathan
			$BUser = new BaseUser();
		}
		switch ($Action) {
			case "create"; // Display the new user form.
				$PWDesc = $UIL->ADA['DescPW'];
				$defaultrole = 10;
				$tdc = "<td width='25%' align='right'>";
				$tdal = "<td align='left'>";
				$form = "\n".str_repeat("\t",3).$Fst."add'".$Fct;
				$form .= "\n".str_repeat("\t",4).'<table border=1 ';
				$form .= "class='query'>";
				$form .= "\n".str_repeat("\t",5).'<tr>';
				$form .= "\n".str_repeat("\t",6)."$tdc$LoginDesc:</td>";
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='user'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "\n".str_repeat("\t",6).$tdc._FRMFULLNAME.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='fullname'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "\n".str_repeat("\t",6)."$tdc$PWDesc:</td>";
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='password' ";
				$form .= "name='password'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLE.'</td>';
				// Potential XSS Vector.
				// See https://github.com/NathanGibbs3/BASE/issues/13
				$form .= $tdal.$user->returnRoleNamesDropDown($defaultrole).'</td>'.$Trc;
				$form .= "<td colspan='2' align='center'><input type='submit' name='submit' value='"._SUBMITQUERY."'/></td>";
				$form .= "\n".str_repeat("\t",5).'</tr>';
				$form .= "\n".str_repeat("\t",4).'</table>';
				$form .= "\n".str_repeat("\t",3).'</form>';
				$pagebody = $form;
				break;
			case "add"; // Actually add user to DB.
				$username = filterSql($_POST['user']);
				$role = filterSql($_POST['roleID']);
				$password = filterSql($_POST['password']);
				$name = filterSql($_POST['fullname']);
				$added = $BUser->addUser($username, $role, $password, $name);
				$pagebody = $added;
				break;
			case "edituser"; // Edit user account form.
				// Function returns an array in the folowing format.
				// $userinfo[0] = $uid // id
				// $userinfo[1] = $usn // Name
				// $userinfo[2] = $rid // Role id
				// $userinfo[3] = $ufn // Full Name
				// $userinfo[4] = $uso // Operative
				$userinfo = $user->returnEditUser($userid);
				// Anti XSS Output Data
				$uid = htmlspecialchars($userinfo[0]);
				$usn = htmlspecialchars($userinfo[1]);
				$rid = htmlspecialchars($userinfo[2]);
				$ufn = htmlspecialchars($userinfo[3]);

				$tdc = "<td width='25%' align='right'>";
				$tdal = "<td align='left'>";
				$form = "\n".str_repeat("\t",3).$Fst."updateuser'".$Fct;
				$form .= "\n".str_repeat("\t",4)."<input type='hidden' ";
				$form .= "name='usr_id' value='$uid'/>";
				$form .= "\n".str_repeat("\t",4).'<table border=1 ';
				$form .= "class='query'>";
				$form .= "\n".str_repeat("\t",5).'<tr>';
				$form .= "\n".str_repeat("\t",6).$tdc._FRMUID.'</td>';
				$form .= "\n".str_repeat("\t",6)."$tdal$uid</td>$Trc";
				$form .= "\n".str_repeat("\t",6)."$tdc$LoginDesc:</td>";
				$form .= "\n".str_repeat("\t",6)."$tdal$usn</td>$Trc";
				$form .= "\n".str_repeat("\t",6).$tdc._FRMFULLNAME.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='fullname' value='$ufn'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLE.'</td>';
				// Potential XSS Vector.
				// See https://github.com/NathanGibbs3/BASE/issues/13
				$form .= $tdal.$user->returnRoleNamesDropDown($rid).'</td></tr>';
				$form .= "<tr><td colspan='2' align='center'><input type='submit' name='submit' value='"._UPDATEUSER."'></td>";
				$form .= "\n".str_repeat("\t",5).'</tr>';
				$form .= "\n".str_repeat("\t",4).'</table>';
				$form .= "\n".str_repeat("\t",3).'</form>';
				$pagebody = $form;
				break;
			case "updateuser"; // Updates user account from above form....
				// Setup array in this format for the updateUser function
				// $userarray[0] = $userid
				// $userarray[1] = $fullname
				// $userarray[2] = $roleid
				$userarray = array(filterSql($_POST['usr_id']), filterSql($_POST['fullname']), filterSql($_POST['roleID']),);
				$user->updateUser($userarray);
				base_header("Location: $Umca"."list");
				break;
			case "disableuser"; // Disable user account.
				$BUser->disableUser($userid);
				base_header("Location: $Umca"."list");
				break;
			case "enableuser"; // Enable user account.
				$BUser->enableUser($userid);
				base_header("Location: $Umca"."list");
				break;
			case "deleteuser"; // Delete user account.
				$BUser->deleteUser($userid);
				base_header("Location: $Umca"."list");
				break;
			case "list"; // Generate HTML User Table.
				$AcEdit = $UIL->UAA['Edit'];
				$AcDelete = $UIL->UAA['Delete'];
				$uidesc = $UIL->CPA['Id'];
				$ufndesc = $UIL->CPA['Name'];
				$users = $user->returnUsers();
				$thc = "<td class='plfieldhdr'";
				$thcw5 = "$thc width='5%'>";
				$tdac = "<td align='center'>";
				// Styling hack produces table with black border.
				// See https://github.com/NathanGibbs3/BASE/issues/19
				$tmpHTML = "<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH='100%' BGCOLOR='#000000'><TR><TD>";
				// Users Table Display
				$tmpHTML .= "\n".str_repeat("\t",2).'<table cellspacing=0 ';
				$tmpHTML .= "cellpadding=0 border=0 width='100%' ";
				$tmpHTML .= "bgcolor='#FFFFFF'>";
				$tmpHTML .= "\n".str_repeat("\t",3).'<tr>';
				$tmpHTML .= "\n".str_repeat("\t",4)."$thcw5$AcEdit</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thcw5$AcDelete</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thcw5$uidesc</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thc>$LoginDesc</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thc>"._ROLEID.'</td>';
				$tmpHTML .= "\n".str_repeat("\t",4)."$thc>$ufndesc</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thc>"._ENABLED.'</td>';
				$tmpHTML .= "\n".str_repeat("\t",3).'</tr>';
				if ($users <> "") { // Verify we have a user in the db --Kevin;
					$imgc = "\n".str_repeat("\t",5);
					$imgc .= "<img border='0' src='".$BASE_urlpath ."/images/";
					$tduma = $tdac.$Hrst;
					foreach ($users as $row) { // Iterate users & build table.
						$tmpRow = explode("|", $row);
						// Setup User ID URL param.
						$uuid = "user&amp;userid=".urlencode($tmpRow[0]);
						// Set up enable/disable action URL
						if ($tmpRow[4] == 1) {
							$enabled = $Hrst."disable$uuid'>";
							$enabled .= $imgc."greencheck.png' alt='button_greencheck";
						}else{
							$enabled = $Hrst."enable$uuid'>";
							$enabled .= $imgc."button_exclamation.png' alt='button_exclamation";
						}
						$enabled .= "'/>";
						$enabled .= "\n".str_repeat("\t",4).'</a>';
						// Anti XSS Output Data
						$uid = htmlspecialchars($tmpRow[0]);
						$usn = htmlspecialchars($tmpRow[1]);
						$rolename = htmlspecialchars($user->roleName($tmpRow[2]));
						$ufn = htmlspecialchars($tmpRow[3]);

						$tmpHTML .= "\n".str_repeat("\t",3).'<tr>';
						$tmpHTML .= "\n".str_repeat("\t",4).$tduma."edit$uuid'>";
						$tmpHTML .= $imgc."button_edit.png' alt='button_$AcEdit'/>";
						$tmpHTML .= "\n".str_repeat("\t",4).'</a></td>';
						$tmpHTML .= "\n".str_repeat("\t",4).$tduma."<delete$uuid'>";
						$tmpHTML .= $imgc."button_delete.png' alt='button_$AcDelete'/>";
						$tmpHTML .= "\n".str_repeat("\t",4).'</a></td>';
						$tmpHTML .= "\n".str_repeat("\t",4)."$tdac$uid</td>";
						$tmpHTML .= "\n".str_repeat("\t",4).$tdac;
						if ($tmpRow[2] == 1) { // Display Admin Users in red.
							$tmpHTML .= "<font color='#ff0000'><b>$usn</b></font></td>";
						}else{
							$tmpHTML .= "$usn</td>";
						}
						$tmpHTML .= "\n".str_repeat("\t",4)."$tdac$rolename</td>";
						$tmpHTML .= "\n".str_repeat("\t",4)."$tdac$ufn</td>";
						$tmpHTML .= "\n".str_repeat("\t",4)."$tdac$enabled</td>";
						$tmpHTML .= "\n".str_repeat("\t",3).'</tr>';
					}
				}
				$tmpHTML .= "\n".str_repeat("\t",2).'</table>';
				// Closure for styleing hack.
				// See https://github.com/NathanGibbs3/BASE/issues/19
				$tmpHTML .= "\n".str_repeat("\t",1)."</td></tr></table>";
				$pagebody = $tmpHTML;
				break;
		}
		// Generate Page.
		PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);
		PrintBASEAdminMenuHeader();
		print $pagebody;
		PrintBASEAdminMenuFooter();
		PrintBASESubFooter();
		PageEnd();
	}
}else{
	base_header("Location: ". $BASE_urlpath . "/base_main.php");
}
?>
