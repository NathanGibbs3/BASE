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

if (!isset($BASE_path)){ // Issue #5
	$BASE_path = dirname(__FILE__);
	$BASE_path = preg_replace("/\/admin.*/", "", $BASE_path);
}
include("$BASE_path/base_conf.php");
include_once("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");
include_once("$BASE_path/base_stat_common.php");

$et = new EventTiming($debug_time_mode);
if ( isset($_GET['action']) ){
	$Action = filterSql($_GET['action']);
}else{
	$Action = 'Invalid';
}
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
		$Trc = NLI('</tr><tr>',5); // Table row continue.
		// I would like to clean this up later into a display class or set of
		// functions -- Kevin
		if ( preg_match("/((delet|(dis|en)abl)e|edit)user/", $Action) ){
			$userid = filterSql($_GET['userid']);
		}
		if ( preg_match("/(create|list|(edit|update)user)/", $Action) ){
			if ( preg_match("/(create|list|edituser)/", $Action) ){
				$LoginDesc = $UIL->ADA['DescUN'];
				if ( preg_match("/(create|edituser)/", $Action) ){
					$RoleDesc = $UIL->CWA['Role'];
				}
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
				$form = NLI($Fst."add'".$Fct,3);
				$form .= NLI("<table border='1' class='query'>",4);
				$form .= NLI('<tr>',5);
				$form .= NLI("$tdc$LoginDesc:</td>",6);
				$form .= NLI($tdal,6);
				$form .= NLI("<input type='text' name='user'/>",7);
				$form .= NLI('</td>'.$Trc,6);
				$form .= NLI($tdc._FRMFULLNAME.'</td>',6);
				$form .= NLI($tdal,6);
				$form .= NLI("<input type='text' name='fullname'/>",7);
				$form .= NLI('</td>'.$Trc,6);
				$form .= NLI("$tdc$PWDesc:</td>",6);
				$form .= NLI($tdal,6);
				$form .= NLI("<input type='password' name='password'/>",7);
				$form .= NLI('</td>'.$Trc,6);
				$form .= NLI("$tdc$RoleDesc:</td>",6);
				$form .= NLI($tdal,6);
				$form .= $user->returnRoleNamesDropDown($defaultrole);
				$form .= NLI('</td>'.$Trc,6);
				$form .= NLI("<td colspan='2' align='center'>",6);
				$form .= "<input type='submit' name='submit' value='"._SUBMITQUERY."'>";
				$form .= NLI('</td>',6);
				$form .= NLI('</tr>',5);
				$form .= NLI('</table>',4);
				$form .= NLI('</form>',3);
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
				// Anti XSS Output Data
				$userinfo = XSSPrintSafe($user->returnEditUser($userid));
				$uid = $userinfo[0];
				$usn = $userinfo[1];
				$rid = $userinfo[2];
				$ufn = $userinfo[3];
				$tdc = "<td width='25%' align='right'>";
				$tdal = "<td align='left'>";
				$form = NLI($Fst."updateuser'".$Fct,3);
				$form .= NLI("<input type='hidden' name='usr_id' ",4);
				$form .= "value='$uid'/>";
				$form .= NLI("<table border='1' class='query'>",4);
				$form .= NLI('<tr>',5);
				$form .= NLI($tdc._FRMUID.'</td>',6);
				$form .= NLI("$tdal$uid</td>$Trc",6);
				$form .= NLI("$tdc$LoginDesc:</td>",6);
				$form .= NLI("$tdal$usn</td>$Trc",6);
				$form .= NLI($tdc._FRMFULLNAME.'</td>',6);
				$form .= NLI($tdal,6);
				$form .= NLI("<input type='text' name='fullname' ",7);
				$form .= "value='$ufn'/>";
				$form .= NLI('</td>'.$Trc,6);
				$form .= NLI("$tdc$RoleDesc:</td>",6);
				$form .= NLI($tdal,6);
				$form .= $user->returnRoleNamesDropDown($rid);
				$form .= NLI('</td>'.$Trc,6);
				$form .= NLI("<td colspan='2' align='center'>",6);
				$form .= "<input type='submit' name='submit' value='"._UPDATEUSER."'>";
				$form .= NLI('</td>',6);
				$form .= NLI('</tr>',5);
				$form .= NLI('</table>',4);
				$form .= NLI('</form>',3);
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
				$ridesc = $UIL->ADA['DescRI'];
				$asdesc = $UIL->ADA['DescAS'];
				$AcEdit = $UIL->UAA['Edit'];
				$AcDelete = $UIL->UAA['Delete'];
				$uidesc = $UIL->CWA['Id'];
				$ufndesc = $UIL->CWA['Name'];
				$users = $user->returnUsers();
				$thc = "<td class='plfieldhdr'";
				$thcw5 = "$thc width='5%'>";
				$tdac = "<td align='center'>";
				// Styling hack produces table with black border.
				// See https://github.com/NathanGibbs3/BASE/issues/19
				$tmpHTML = "<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH='100%' BGCOLOR='#000000'><TR><TD>";
				// Users Table Display
				$tmpHTML .= NLI("<table cellspacing='0' cellpadding='0' ",2);
				$tmpHTML .= "border='0' width='100%' bgcolor='#FFFFFF'>";
				$tmpHTML .= NLI('<tr>',3);
				$tmpHTML .= NLI("$thcw5$AcEdit</td>",4);
				$tmpHTML .= NLI("$thcw5$AcDelete</td>",4);
				$tmpHTML .= NLI("$thcw5$uidesc</td>",4);
				$tmpHTML .= NLI("$thc>$LoginDesc</td>",4);
				$tmpHTML .= NLI("$thc>$ridesc</td>",4);
				$tmpHTML .= NLI("$thc>$ufndesc</td>",4);
				$tmpHTML .= NLI("$thc>$asdesc</td>",4);
				$tmpHTML .= NLI('</tr>',3);
				if ($users <> "") { // Verify we have a user in the db --Kevin;
					$imgc = NLI('',6);
					$imgc .= "<img border='0' src='".$BASE_urlpath ."/images/";
					$tduma = $tdac.NLI($Hrst,5);
					foreach ($users as $row) { // Iterate users & build table.
						$tmpRow = explode("|", $row);
						// Setup User ID URL param.
						$uuid = "user&amp;userid=".urlencode($tmpRow[0]);
						// Set up enable/disable action URL
						if ($tmpRow[4] == 1) {
							$enabled = $tduma."disable$uuid'>";
							$enabled .= $imgc."greencheck.png' alt='button_greencheck";
						}else{
							$enabled = $tduma."enable$uuid'>";
							$enabled .= $imgc."button_exclamation.png' alt='button_exclamation";
						}
						$enabled .= "'/>";
						$enabled .= NLI('</a>',5).NLI('</td>',4);
						// Anti XSS Output Data
						$uid = htmlspecialchars($tmpRow[0]);
						$usn = htmlspecialchars($tmpRow[1]);
						$rolename = htmlspecialchars($user->roleName($tmpRow[2]));
						$ufn = htmlspecialchars($tmpRow[3]);
						$tmpHTML .= NLI('<tr>',3);
						$tmpHTML .= NLI($tduma."edit$uuid'>",4);
						$tmpHTML .= $imgc."button_edit.png' alt='button_$AcEdit'/>";
						$tmpHTML .= NLI('</a>',5).NLI('</td>',4);
						$tmpHTML .= NLI($tduma."delete$uuid'>",4);
						$tmpHTML .= $imgc."button_delete.png' alt='button_$AcDelete'/>";
						$tmpHTML .= NLI('</a>',5).NLI('</td>',4);
						$tmpHTML .= NLI("$tdac$uid</td>",4);
						$tmpHTML .= NLI($tdac,4);
						if ($tmpRow[2] == 1) { // Display Admin Users in red.
							$tmpHTML .= "<font color='#ff0000'><b>$usn</b></font></td>";
						}else{
							$tmpHTML .= "$usn</td>";
						}
						$tmpHTML .= NLI("$tdac$rolename</td>",4);
						$tmpHTML .= NLI("$tdac$ufn</td>",4);
						$tmpHTML .= NLI($enabled,4);
						$tmpHTML .= NLI('</tr>',3);
					}
				}
				$tmpHTML .= NLI('</table>',2);
				// Closure for styleing hack.
				// See https://github.com/NathanGibbs3/BASE/issues/19
				$tmpHTML .= NLI("</td></tr></table>",1);
				$pagebody = $tmpHTML;
				break;
			default:
				$pagebody = returnErrorMessage('Invalid Action!');
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
