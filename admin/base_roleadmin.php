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
//            Purpose: Role management functions (create, disable etc....)
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

if ($Use_Auth_System == 1) {
	// Check role out and redirect if needed -- Kevin
	$roleneeded = 1;
	$BUser = new BaseUser();
	if (($BUser->hasRole($roleneeded) == 0)){
		base_header("Location: ". $BASE_urlpath . "/base_main.php");
	}else{
		$UIL = new UILang($BASE_Language); // Create UI Language Abstraction Object.
		$cs = new CriteriaState("admin/base_roleadmin.php");
		$cs->ReadState();
		$page_title = _ROLEADMIN;
		$AcEdit = $UIL->UAA['Edit'];
		$AcDelete = $UIL->UAA['Delete'];
		// Html Templates
		$Umca = "base_roleadmin.php?action="; // Role Managemnt Common Action.
		$Fst = "<form action='$Umca"; // Form tag start.
		$Fct = " Method='POST'>"; // Form tag end.
		$Hrst = "<a href='$Umca"; // Href tag start.
		$Trc = "\n".str_repeat("\t",5).'</tr><tr>'; // Table row continue.
		// I would like to clean this up later into a display class or set of functions -- Kevin
		if ( preg_match("/(add|(delete|edit)role)/", $Action) ){
			$roleid = filterSql($_GET['roleid']);
		}
		if ( preg_match("/(create|list|(edit|update)role)/", $Action) ){
			$role = new BaseRole();
		}else{ // 2 vars for this?! No idea why. Will keep for now. -- Nathan
			$BRole = new BaseRole();
		}
		switch ($Action) {
			case "create"; // Display the new Role form.
				$tdc = "<td width='25%' align='right'>";
				$tdal = "<td align='left'>";
				$form = "\n".str_repeat("\t",3).$Fst."add'".$Fct;
				$form .= "\n".str_repeat("\t",4).'<table border=1 ';
				$form .= "class='query'>";
				$form .= "\n".str_repeat("\t",5).'<tr>';
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLEID.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='roleid'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLENAME.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='rolename'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLEDESC.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='desc'/>";
				$form .= "\n".str_repeat("\t",6).'</td>'.$Trc;
				$form .= "<td colspan='2' align='center'><input type='submit' name='submit' value='"._SUBMITQUERY."'/></td>";
				$form .= "\n".str_repeat("\t",5).'</tr>';
				$form .= "\n".str_repeat("\t",4).'</table>';
				$form .= "\n".str_repeat("\t",3).'</form>';
				$pagebody = $form;
				break;
			case "add"; // Actually Role to DB.
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
				
				$roleinfo = $role->returnEditRole($roleid);
				// Anti XSS Output Data
				$rid = htmlspecialchars($roleinfo[0]);
				$ron = htmlspecialchars($roleinfo[1]);
				$rod = htmlspecialchars($roleinfo[2]);

				$tdc = "<td width='25%' align='right'>";
				$tdal = "<td align='left'>";
				$form = "\n".str_repeat("\t",3).$Fst."updaterole'".$Fct;
				$form .= "\n".str_repeat("\t",4)."<input type='hidden' ";
				$form .= "name='role_id' value='$rid'/>";
				$form .= "\n".str_repeat("\t",4).'<table border=1 ';
				$form .= "class='query'>";
				$form .= "\n".str_repeat("\t",5).'<tr>';
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLEID.'</td>';
				$form .= "\n".str_repeat("\t",6)."$tdal$rid</td>$Trc";
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLENAME.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='role_name' value='$ron'></td>$Trc";
				$form .= "\n".str_repeat("\t",6).$tdc._FRMROLEDESC.'</td>';
				$form .= "\n".str_repeat("\t",6).$tdal;
				$form .= "\n".str_repeat("\t",7)."<input type='text' ";
				$form .= "name='desc' value='$rod'></td>$Trc";
				$form .= "<td colspan='2' align='center'><input type='submit' name='submit' value='"._UPDATEROLE."'/></td>";
				$form .= "\n".str_repeat("\t",5).'</tr>';
				$form .= "\n".str_repeat("\t",4).'</table>';
				$form .= "\n".str_repeat("\t",3).'</form>';
				$pagebody = $form;
				break;
			case "updaterole"; // Updates role from above form....
				$rolearray = array(filterSql($_POST['role_id']), filterSql($_POST['role_name']), filterSql($_POST['desc']),);
				$role->updateRole($rolearray);
				base_header("Location: $Umca"."list");
				break;
			case "deleterole"; // Deletes role.
				$BRole->deleteRole($roleid);
				base_header("Location: $Umca"."list");
				break;
			case "list"; // Generate HTML Role Table.
				$roles = $role->returnRoles();
				$thc = "<td class='plfieldhdr'";
				$thcw5 = "$thc width='5%'>";
				$tdac = "<td align='center'>";
				$imgc = "\n".str_repeat("\t",5);
				$imgc .= "<img border='0' src='".$BASE_urlpath ."/images/";
				$tduma = $tdac.$Hrst;
				// Styling hack produces table with black border.
				// See https://github.com/NathanGibbs3/BASE/issues/19
				$tmpHTML = "<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH='100%' BGCOLOR='#000000'><TR><TD>";
				// Roles Table Display
				$tmpHTML .= "\n".str_repeat("\t",2).'<table cellspacing=0 ';
				$tmpHTML .= "cellpadding=0 border=0 width='100%' ";
				$tmpHTML .= "bgcolor='#FFFFFF'>";
				$tmpHTML .= "\n".str_repeat("\t",3).'<tr>';
				$tmpHTML .= "\n".str_repeat("\t",4)."$thcw5$AcEdit</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thcw5$AcDelete</td>";
				$tmpHTML .= "\n".str_repeat("\t",4)."$thcw5"._ID.'</td>';
				$tmpHTML .= "\n".str_repeat("\t",4)."$thc>"._NAME.'</td>';
				$tmpHTML .= "\n".str_repeat("\t",4)."$thc>"._DESC.'</td>';
				$tmpHTML .= "\n".str_repeat("\t",3).'</tr>';
				foreach ($roles as $row) { // Iterate roles & build table.
					$tmpRow = explode("|", $row);
					// Setup Role ID URL param.
					$urid = "role&amp;roleid=".urlencode($tmpRow[0]);
					$tmpHTML .= "\n".str_repeat("\t",3).'<tr>';
					$tmpHTML .= "\n".str_repeat("\t",4).$tduma."edit$urid'>";
					$tmpHTML .= $imgc."button_edit.png' alt='button_edit'>";
					$tmpHTML .= "\n".str_repeat("\t",4).'</a></td>';
					$tmpHTML .= "\n".str_repeat("\t",4).$tduma."delete$urid'>";
					$tmpHTML .= $imgc."button_delete.png' alt='button_delete'>";
					$tmpHTML .= "\n".str_repeat("\t",4).'</a></td>';
					// Anti XSS Output Data
					$tmpHTML .= $tdac.htmlspecialchars($tmpRow[0]).'</td>';
					$tmpHTML .= $tdac.htmlspecialchars($tmpRow[1]).'</td>';
					$tmpHTML .= $tdac.htmlspecialchars($tmpRow[2]).'</td>';
					$tmpHTML .= "\n".str_repeat("\t",3).'</tr>';
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
