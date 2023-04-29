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
** Purpose: User Preferences page
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_stat_common.php");

AuthorizedRole(10000);
$page_body='';
$cs = new CriteriaState("base_user.php");
$cs->ReadState();
$userprefs = new BaseUserPrefs();
$userobj = new BaseUser();
$username = $userobj->returnUser();
$page_title = _BASEUSERTITLE;
PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);

$Action = 'display'; // Default Action.
if ( isset($_GET['action']) ){
	$Action = filterSql($_GET['action']);
}
// This is where the processing of this page happens.
switch ( $Action ){
	case "change": //call auth.inc
        if (($_POST['newpasswd1'] == $_POST['newpasswd2']) && ($_POST['newpasswd1'] != ""))
        {
          $pwdresponse = $userobj->changePassword($username, filterSql($_POST['oldpasswd']), filterSql($_POST['newpasswd1']));
          $page_body = $pwdresponse;
          break;
        } else
        {
          $page_body = _BASEUSERERRPWD;
        }
      
      case "changepassword":
        $form = "<form action='base_user.php?action=change' Method='POST'>";
        $form = $form . "<table border=1 class='query'>";
        $form = $form . "<tr><td width='25%' align='right'>"._BASEUSEROLDPWD."</td>";
        $form = $form . "<td align='left'><input type='password' name='oldpasswd'></td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._BASEUSERNEWPWD."</td>";
        $form = $form . "<td align='left'><input type='password' name='newpasswd1'></td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._BASEUSERNEWPWDAGAIN."</td>";
        $form = $form . "<td align='left'><input type='password' name='newpasswd2'></td></tr>";
        $form = $form . "<tr><td colspan='2' align='center'><input type='submit' name='submit'></td>";
        $form = $form . "</tr></table>";
        $page_body = $page_body . $form;
        break;
      
      case "display":
        $user = new BaseUser();
        $userlogin = $user->returnUser();
        $userid = $user->returnUserID($userlogin);
			$userinfo = $user->returnEditUser($userid); // Anti XSS by default.

//			TODO: Need to build a generic table builder that works like DDT.
//			$DD = array(_FRMUID, _FRMLOGIN, _FRMFULLNAME, _FRMROLE);
//			$DI = array(
//				$userinfo[0], $userinfo[1], $userinfo[3],
//				$user->roleName($userinfo[2])
//			);
//			DDT($DI, $DD, _USERPREF, '', 12, 1);

        $form = "<table border=1 class='query'>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMUID."</td>";
        $form = $form . "<td align='left'>". $userinfo[0] ."</td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMLOGIN."</td>";
        $form = $form . "<td align='left'>". $userinfo[1] ."</td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMFULLNAME."</td>";
        $form = $form . "<td align='left'>". $userinfo[3] ."</td></tr>";
        $form = $form . "<tr><td width='25%' align='right'>"._FRMROLE."</td>";
        $form = $form . "<td align='left'>" . $user->roleName($userinfo[2]) ."</td></tr>";
        $form = $form . "</tr></table>";
       
        $page_body = $form;
        break;
        
      default:
        $page_body = $page_body . " ";
    }

// Design barrowed from PrintBASEAdminMenuHeader();
$menu = NLI("<div>",2);
$menu .= NLI("<div class='mainheadermenu' style='float: left; width: 15%;'>",3);
$menu .= NLI(_USERPREF ."<hr/>",4);
// Html Templates
$Hrst = "<a href='base_user.php?action="; // Href tag start.
$Hrsp = " class='menuitem'>"; // Href tag end.
$menu .= NLI($Hrst . "changepassword'" . $Hrsp . _CHNGPWD . "</a><br>",4);
$menu .= NLI($Hrst . "display'" . $Hrsp . _DISPLAYU . "</a><br>",4);
$menu .= NLI("</div>",3);
$menu .= NLI("<div style='padding-left: 10px; width: auto;'>",3);
print $menu;
print $page_body;
PrintBASEAdminMenuFooter();
PrintBASESubFooter();
?>
