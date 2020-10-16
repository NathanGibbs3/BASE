<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2020 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Role management functions (create, disable etc....)
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

if ( isset($_GET['action']) ){
	$Action = filterSql($_GET['action']);
}else{
	$Action = 'Invalid';
}
if ($Use_Auth_System == 1) {
	AuthorizedRole(1,'base_main');
	$et = new EventTiming($debug_time_mode);
	$cs = new CriteriaState("admin/base_roleadmin.php");
	$cs->ReadState();
	$page_title = _ROLEADMIN;
	// Html Templates
	$Umca = "base_roleadmin.php?action="; // Role Managemnt Common Action.
	$Fst = "<form action='$Umca"; // Form tag start.
	$Fct = " Method='POST'>"; // Form tag end.
	$Hrst = "<a href='$Umca"; // Href tag start.
	$Trc = NLI('</tr><tr>', 5); // Table row continue.
	// I would like to clean this up later into a display class or set of
	// functions -- Kevin
	if ( preg_match("/(delete|edit)role/", $Action) ){
		$roleid = filterSql($_GET['roleid']);
	}
	if ( preg_match("/add/", $Action) ){
		$roleid = filterSql($_POST['roleid']);
	}
	if ( preg_match("/(create|list|(edit|update)role)/", $Action) ){
		$role = new BaseRole();
		if ( class_exists('UILang') ){ // Issue 11 backport shim.
			$hrdesc = $UIL->CWA['Desc']; // Description Header Item.
		}else{
			$hrdesc = _FRMROLEDESC;
		}
		if ( preg_match("/(create|editrole)/", $Action) ){
			if ( class_exists('UILang') ){ // Issue 11 backport shim.
				$RIDesc = $UIL->ADA['DescRI'];
			}else{
				$RIDesc = _FRMROLEID;
			}
		}
	}else{ // 2 vars for this?! No idea why. Will keep for now. -- Nathan
		$BRole = new BaseRole();
	}
	switch ($Action) {
		case "create"; // Display the new Role form.
			$tdc = "<td width='25%' align='right'>";
			$tdal = "<td align='left'>";
			$ipt = "<input type='";
			$form = NLI($Fst."add'".$Fct,3);
			$form .= NLI("<table border=1 class='query'>",4);
			$form .= NLI('<tr>',5);
			$form .= NLI( "$tdc$RIDesc:</td>",6);
			$form .= NLI($tdal,6);
			$form .= NLI($ipt."text' name='roleid'/>",7);
			$form .= NLI("</td>$Trc",6);
			$form .= NLI($tdc._FRMROLENAME.'</td>',6);
			$form .= NLI($tdal,6);
			$form .= NLI($ipt."text' name='rolename'/>",7);
			$form .= NLI("</td>$Trc",6);
			$form .= NLI("$tdc$hrdesc:</td>",6);
			$form .= NLI($tdal,6);
			$form .= NLI($ipt."text' name='desc'>",7);
			$form .= NLI("</td>$Trc",6);
			$form .= NLI("<td colspan='2' align='center'>",6);
			$form .= NLI(
				$ipt."submit' name='submit' value='"._SUBMITQUERY."'/>",
				7
			);
			$form .= NLI('</td>',6);
			$form .= NLI('</tr>',5);
			$form .= NLI('</table>',4);
			$form .= NLI('</form>',3);
			$pagebody = $form;
			break;
		case "add"; // Actually add Role to DB.
			$rolename = filterSql($_POST['rolename']);
			$desc = filterSql($_POST['desc']);
			$added = $BRole->addRole($roleid, $rolename, $desc);
			$pagebody = $added;
			break;
		case "editrole"; // Edit role form.
			// This function accepts an array in the following format
			// $roleinfo[0] = $rid
			// $roleinfo[1] = $rname
			// $roleinfo[2] = $rdesc
			// Anti XSS Output Data
			$roleinfo = XSSPrintSafe($role->returnEditRole($roleid));
			$rid = $roleinfo[0];
			$ron = $roleinfo[1];
			$rod = $roleinfo[2];
			$tdc = "<td width='25%' align='right'>";
			$tdal = "<td align='left'>";
			$ipt = "<input type='";
			$form = NLI($Fst."updaterole'".$Fct,3);
			$form .= NLI($ipt."hidden' name='role_id' value='$rid'/>", 4);
			$form .= NLI("<table border=1 class='query'>",4);
			$form .= NLI('<tr>',5);
			$form .= NLI("$tdc$RIDesc:</td>",6);
			$form .= NLI("$tdal$rid</td>$Trc",6);
			$form .= NLI($tdc._FRMROLENAME.'</td>',6);
			$form .= NLI($tdal,6);
			$form .= NLI($ipt."text' name='role_name' value='$ron' />", 7);
			$form .= NLI("</td>$Trc",6);
			$form .= NLI("$tdc$hrdesc:</td>",6);
			$form .= NLI($tdal,6);
			$form .= NLI($ipt."text' name='desc' value='$rod' />", 7);
			$form .= NLI("</td>$Trc",6);
			$form .= NLI("<td colspan='2' align='center'>",6);
			$form .= NLI(
				$ipt."submit' name='submit' value='"._UPDATEROLE."'/>",
				7
			);
			$form .= NLI('</td>',6);
			$form .= NLI('</tr>',5);
			$form .= NLI('</table>',4);
			$form .= NLI('</form>',3);
			$pagebody = $form;
			break;
		case "updaterole"; // Updates role from above form....
			// Setup array in this format for the updateRole function
			// $rolearray[0] = $roleid
			// $rolearray[1] = $rolename
			// $rolearray[2] = $roledesc
			$rolearray = array(
				filterSql($_POST['role_id']),
				filterSql($_POST['role_name']),
				filterSql($_POST['desc']),
			);
			$role->updateRole($rolearray);
			base_header("Location: $Umca"."list");
			break;
		case "deleterole"; // Deletes role.
			$BRole->deleteRole($roleid);
			base_header("Location: $Umca"."list");
			break;
		case "list"; // Generate HTML Role Table.
			if ( class_exists('UILang') ){ // Issue 11 backport shim.
				$AcEdit = $UIL->UAA['Edit'];
				$AcDelete = $UIL->UAA['Delete'];
				$ridesc = $UIL->CWA['Id'];
				$rname = $UIL->CWA['Name'];
			}else{
				$AcEdit = _EDIT;
				$AcDelete = _DELETE;
				$ridesc = _ID;
				$rname = _NAME;
			}
			$roles = $role->returnRoles();
			$thc = "<td class='plfieldhdr'";
			$thcw5 = "$thc width='5%'>";
			$tdac = "<td align='center'>";
			$imgc = NLI( "<img border='0' src='".$BASE_urlpath ."/images/", 5);
			$imgc .= 'button_';
			$tduma = $tdac.$Hrst;
			// Styling hack produces table with black border.
			// See https://github.com/NathanGibbs3/BASE/issues/19
			$tmpHTML = "<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH='100%' BGCOLOR='#000000'><TR><TD>";
			// Roles Table Display
			$tmpHTML .= NLI("<table cellspacing='0' cellpadding='0' ",2);
			$tmpHTML .= "border='0' width='100%' bgcolor='#FFFFFF'>";
			$tmpHTML .= NLI('<tr>',3);
			$tmpHTML .= NLI("$thcw5$AcEdit</td>",4);
			$tmpHTML .= NLI("$thcw5$AcDelete</td>",4);
			$tmpHTML .= NLI("$thcw5$ridesc</td>",4);
			$tmpHTML .= NLI("$thc>$rname</td>",4);
			$tmpHTML .= NLI("$thc>$hrdesc</td>",4);
			$tmpHTML .= NLI('</tr>',3);
			foreach ( $roles as $row ){ // Iterate roles & build table.
				$tmpRow = explode('|', $row);
				// Setup Role ID URL param.
				$urid = "role&amp;roleid=".urlencode($tmpRow[0]);
				$tmpHTML .= NLI('<tr>',3);
				$tmpHTML .= NLI($tduma."edit$urid'>",4);
				$tmpHTML .= $imgc."edit.png' alt='button_edit'>";
				$tmpHTML .= NLI('</a></td>',4);
				$tmpHTML .= NLI($tduma."delete$urid'>",4);
				$tmpHTML .= $imgc."delete.png' alt='button_delete'>";
				$tmpHTML .= NLI('</a></td>',4);
				// Anti XSS Output Data
				$tmpRow = XSSPrintSafe($tmpRow);
				$tmpHTML .= $tdac.$tmpRow[0].'</td>';
				$tmpHTML .= $tdac.$tmpRow[1].'</td>';
				$tmpHTML .= $tdac.$tmpRow[2].'</td>';
				$tmpHTML .= NLI('</tr>',3);
			}
			$tmpHTML .= NLI('</table>',2);
			// Closure for styleing hack.
			// See https://github.com/NathanGibbs3/BASE/issues/19
			$tmpHTML .= NLI('</td></tr></table>',1);
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
}else{
	base_header("Location: ". $BASE_urlpath . "/base_main.php");
}
?>
