<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2023 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Maintenance & configuration page for managing
//                     Alert Groups (AG)
//
//              Input: GET/POST variables
//                     - ag_action:
//                     - ag_id:
//                     - submit:
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

require("base_conf.php");
include_once("$BASE_path/includes/base_constants.inc.php");
include("$BASE_path/includes/base_include.inc.php");
  include_once("$BASE_path/base_db_common.php");
  include_once("$BASE_path/base_qry_common.php");
  include_once("$BASE_path/base_ag_common.php");

AuthorizedRole(10000);
$et = new EventTiming($debug_time_mode);
$UIL = new UILang($BASE_Language); // Create UI Language Object.
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to Alert DB.
$db->baseDBConnect(
	$db_connect_method,$alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);
if ( class_exists('UILang') ){ // Issue 11 backport shim.
	$AcEdit = $UIL->UAA['Edit'];
	$AcDelete = $UIL->UAA['Delete'];
	$AgiDesc = $UIL->CWA['Id'];
	$AgnDesc = $UIL->CWA['Name'];
	$AgdDesc = $UIL->CWA['Desc'];
	$CPSig = $UIL->CWA['Sig'];
	$CPSA = $UIL->CPA['SrcAddr'];
	$CPDA = $UIL->CPA['DstAddr'];
	$CPTs = $UIL->CWA['Ts'];
}else{
	$AcEdit = _EDIT;
	$AcDelete _DELETE;
	$AgiDesc = _ID;
	$AgnDesc = _NAME;
	$AgdDesc = _DESC;
	$CPSig = _SIGNATURE;
	$CPSA = _NBSOURCEADDR;
	$CPDA = _NBDESTADDR;
	$CPTs = _TIMESTAMP;
}

$AdminAuth = AuthorizedRole(50); // AG-Editor
$cs = new CriteriaState("base_ag_main.php");
$cs->ReadState();
  $qs = new QueryState();
  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE, array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY));
  $ag_action = ImportHTTPVar("ag_action", VAR_ALPHA | VAR_USCORE);
  $ag_id = filterSql(ImportHTTPVar("ag_id", VAR_DIGIT));
  $ag_name = filterSql(ImportHTTPVar("ag_name"));
  $ag_desc = filterSql(ImportHTTPVar("ag_desc"));
  $page_title = _AGMAINTTITLE;
  PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);

if ( is_numeric($submit) ){ // A browsing button was clicked.
	if ( $debug_mode > 0 ){
		ErrorMessage("Browsing Clicked ($submit)");
	}
	$qs->MoveView($submit);
	$ag_action = "view";
}
$Sep = ' | ';
// Html Templates
$Umca = "base_ag_main.php?ag_action="; // AG Managemnt Common Action.
$Fct = " Method='POST'>"; // Form tag end.
$Hrst = "<a href='$Umca"; // Href tag start.
$Trc = "\n".str_repeat("\t",4).'</tr><tr>'; // Table row continue.
$Thc = "<td class='plfieldhdr'>"; // Table header Class.
$Tdc = "<td class='plfield'>"; // Table data Class.
NLIO("<div style='margin:auto'>");
NLIO($Hrst."list'>"._LISTALL.'</a>',4);
if ( $AdminAuth ){
	NLIO($Sep.$Hrst."create'>"._CREATE.'</a>',4);
}
NLIO($Sep.$Hrst."view'>"._VIEW.'</a>',4);
if ( $AdminAuth ){
	NLIO($Sep.$Hrst."edit'>$AcEdit</a>",4);
	NLIO($Sep.$Hrst."delete'>$AcDelete</a>",4);
}
NLIO($Sep.$Hrst."clear'>"._CLEAR.'</a>',4);
NLIO('</div>');
NLIO('<hr/>');
NLIO("<form name='PacketForm' action='base_ag_main.php' $Fct");
if ( $debug_mode > 0 ){
	$TK = array ( 'ag_action', 'submit', 'ag_id' );
	$DI = array();
	$DD = array();
	foreach ( $TK as $val ){
		array_push($DD, $val);
		array_push($DI, $$val);
	}
	DDT($DI,$DD,'Request Vars','',25);
}
$qs->AddValidAction("del_alert");
$qs->AddValidAction("email_alert");
$qs->AddValidAction("email_alert2");
$qs->AddValidAction("clear_alert");
  
$qs->AddValidActionOp(_SELECTED);
$qs->AddValidActionOp(_ALLONSCREEN);
$qs->AddValidActionOp(_ENTIREQUERY);

$qs->SetActionSQL("SELECT ag_sid, ag_cid FROM acid_ag_alert WHERE ag_id='".$ag_id."'");
$et->Mark("Initialization");
$qs->RunAction($submit, PAGE_QRY_AG, $db);
$et->Mark("Alert Action");
switch ($ag_action) {
    case "create" :
        echo '<h3>'._CREATEGROUPS.'</h3>';
        break;
    
    case "view" :
        echo '<h3>'._VIEWGROUPS.'</h3>';
        break;
    
    case ("edit" || "save") :
        echo '<h3>'._EDITGROUPS.'</h3>';
        break;
        
    case ("delete" || "delete_confirm") :
        echo '<h3>'._DELETEGROUPS.'</h3>';
        break;
        
    case ("clear" || "clear_confirm") :
        echo '<h3>'._CLEARGROUPS.'</h3>';
        break;
    
    case "list" :
        echo '<h3>'._LISTGROUPS.'</h3>';
        break;
        
    default :
        $ag_action = "list";
}

if ($submit != "") {
    if ($ag_action == "create") {
        $ag_id = CreateAG($db, $ag_name, $ag_desc);
        $ag_action = "view";
    } else if ($ag_action == "save") {
        $sql = "UPDATE acid_ag " .
            "SET ag_name='".$ag_name."', ag_desc='".$ag_desc."' ".
            "WHERE ag_id='".$ag_id."'";
        $db->baseExecute($sql, -1, -1, false);
        if ($db->baseErrorMessage() != "") {
           FatalError(_ERRAGUPDATE);
        }

        $ag_action = "view";
    } else if ($ag_action == "delete_confirm") {
        /* Delete the packet list associated with the AG */
        $sql = "DELETE FROM acid_ag_alert WHERE ag_id='".$ag_id."'";
        $db->baseExecute($sql, -1, -1, false);
        if ($db->baseErrorMessage() != "") {
            FatalError(_ERRAGPACKETLIST." ".$sql);
        }
        
        /* Delete the AG */
        $sql = "DELETE FROM acid_ag WHERE ag_id='".$ag_id."'";
        $db->baseExecute($sql, -1, -1, false);
        if ( $db->baseErrorMessage() != "" ) {
            FatalError(_ERRAGDELETE.$sql);
        }
        
    } else if ($ag_action == "clear_confirm") {
        /* Delete the packet list associated with the AG */
        $sql = "DELETE FROM acid_ag_alert WHERE ag_id='".$ag_id."'";
        $db->baseExecute($sql, -1, -1, false);
        if ($db->baseErrorMessage() != "") {
            FatalError(_ERRAGPACKETLIST." ".$sql);
        }
        
        $ag_action = "view";
    }

    if ($ag_action == "delete_confirm") {
        ErrorMessage("<strong>"._AGDELETE."</strong>");
        $ag_action = "view";
        $ag_name = $ag_desc = "<em>"._AGDELETEINFO."</em>";
    } else {
        /* Re-Query the information to print the AG info out */
        if ($ag_id > 0) {
            $sql = "SELECT ag_id, ag_name, ag_desc FROM acid_ag WHERE ag_id='".$ag_id."'";
        } else {
            $sql = "SELECT ag_id, ag_name, ag_desc FROM acid_ag WHERE ag_name='".$ag_name."'";
        }
     
        $result = $db->baseExecute($sql, -1, -1, false);
        if ($db->baseErrorMessage() != "") {
            ErrorMessage(_ERRAGSEARCHINV);
            $submit = "";
        } else if ( $result->baseRecordCount() < 1 ) {
            ErrorMessage(_ERRAGSEARCHNOTFOUND.$sql);
            $submit = "";
        } else {
            $myrow = $result->baseFetchRow();
            $ag_id = $myrow[0];
            $ag_name = $myrow[1];
            $ag_desc = $myrow[2]; 
        }
    }
}

if ($ag_action == "list") {
    $sql = "SELECT ag_id, ag_name, ag_desc FROM acid_ag";
    $result = $db->baseExecute($sql);
    $num = $result->baseRecordCount();
    if ($num < 1) {
        echo "<div style='margin:auto'><strong>"._NOALERTGOUPS."</strong></div>";
    } else {
        echo '
             <table border="1" style="border-spacing:0;padding:0" width="100%">';
		print "\n".str_repeat("\t",4).'<tr>';
		print "\n".str_repeat("\t",5)."$Thc$AgiDesc</td>";
		print "\n".str_repeat("\t",5)."$Thc$AgnDesc</td>";
		print "\n".str_repeat("\t",5).$Thc._NUMALERTS.'</td>';
		print "\n".str_repeat("\t",5)."$Thc$AgdDesc</td>";
		print "\n".str_repeat("\t",5).$Thc._ACTIONS.'</td>';

		PrintTblNewRow( 0, '');
		$Hrsfx = "&amp;submit=x'>";
        for ($i = 0; $i < $num; $i++) {
            $myrow = $result->baseFetchRow();
			$AOA = urlencode($myrow[0]); // ActOnAG

			// count the number of alerts in the AG.
            $result2 = $db->baseExecute("SELECT count(ag_cid) FROM acid_ag_alert WHERE ag_id='".$myrow[0]."'");
            $myrow2 = $result2->baseFetchRow();
            $num_alerts = $myrow2[0];
            $result2->baseFreeRows();

echo '                    <td class="plfield">
                      <a href="base_ag_main.php?ag_action=view&amp;ag_id='.htmlspecialchars($myrow[0]).'&amp;submit=x">'.htmlspecialchars($myrow[0]).'</a></td>
                      <td class="plfield">'.htmlspecialchars($myrow[1]).'</TD>
                      <td class="plfield">'.$num_alerts.'</TD>
                      <td class="plfield">'.htmlspecialchars($myrow[2]).'</TD>';
			print "\n".str_repeat("\t",6).$Tdc;
			if ( $AdminAuth ){
				NLIO($Hrst."edit&amp;ag_id=".$AOA.$Hrsfx.$AcEdit.'</a>',4);
				NLIO($Sep.$Hrst."delete&amp;ag_id=".$AOA.$Hrsfx.$AcDelete.'</a>',4);
				NLIO($Sep,4);
			}
			NLIO($Hrst."clear&amp;ag_id=".$AOA.$Hrsfx._CLEAR.'</a>',4);
			PrintTblNewRow( 0, '');
		}
		print "\n".str_repeat("\t",4).'</tr>';
		print "\n".str_repeat("\t",3).'</table>';
		$result->baseFreeRows();
	}
}

if ($ag_action != "list") {
	print "\n".str_repeat("\t",3).'<table width="100%" border="2" class="query">';
	print "\n".str_repeat("\t",4).'<tr>';
	print "\n".str_repeat("\t",5).'<td width="10%">';
	print "\n".str_repeat("\t",6)."<strong>$AgiDesc #</strong>";
	print "\n".str_repeat("\t",5).'</td>';
	print "\n".str_repeat("\t",5).'<td>';
    if ($ag_action == "create" && $submit == "") {
        echo '&nbsp;<em> '._NOTASSIGN.' </em>&nbsp';
    } else if ($submit == "") {
        echo '<input type="text" name="ag_id" value="'.htmlspecialchars($ag_id).'">';
    } else if ( ($ag_action == "view" || $ag_action == "edit" || $ag_action == "delete" || $ag_action == "clear") && $submit != "" ) {
        echo '<input type="hidden" name="ag_id" value="'.htmlspecialchars($ag_id).'">';
        echo $ag_id;
    }
	print "\n".str_repeat("\t",5).'</td>';
	print $Trc;
	print "\n".str_repeat("\t",5)."<td valign='top'>";
	print "\n".str_repeat("\t",6)."<strong>$AgnDesc</strong>";
	print "\n".str_repeat("\t",5).'</td>';
	print "\n".str_repeat("\t",5).'<td>';
    if ($ag_action == "create" && $submit == "") {
        echo '<input type="text" name="ag_name" size="40" value="'.htmlspecialchars($ag_name).'">';
    } else if ($submit == "") {
        echo '<select name="ag_name">
              <option value="">{ AG Name }';
        $sql = "select ag_name FROM acid_ag;";
        $result = $db->baseExecute($sql);
        if ($result) {
             while ($myrow = $result->baseFetchRow()) {
                echo '<option value="'.htmlspecialchars($myrow[0]).'">'.htmlspecialchars($myrow[0]);
             }

             $result->baseFreeRows();
        }

        echo '</select>';
    } else if ( $ag_action == "edit" && $submit != "" ) {
        echo '<input type="text" name="ag_name" size="40" value="'.htmlspecialchars($ag_name).'">';
    } else if ( ($ag_action == "view" || $ag_action == "delete" || $ag_action = "clear") && $submit != "" ) {
        echo htmlspecialchars($ag_name);
	}
	print "\n".str_repeat("\t",5).'</td>';
	if ( ($ag_action == "create" && $submit == "")
		|| ((
				$ag_action == "view" || $ag_action == "edit"
				|| $ag_action == "delete" || $ag_action == "clear"
			) && $submit != "" )
	) {
		print $Trc;
		print "\n".str_repeat("\t",5)."<td valign='top'>";
		print "\n".str_repeat("\t",6)."<strong>$AgdDesc</strong>";
		print "\n".str_repeat("\t",5).'</td>';
		print "\n".str_repeat("\t",5).'<td>';
		$agdPS = XSSPrintSafe($ag_desc); // Anti XSS this for display.
		$ACEta = "\n".str_repeat("\t",6); // Action (Create || Edit) TextArea.
		$ACEta .= "<textarea name='ag_desc' cols='70' rows='4'>";
		$ACEta .= "$agdPS</textarea>";
		if ( $submit == "" ){
			if ( $ag_action == "create" ){
				print $ACEta;
			}
		}else{ // $submit has a value.
			if ( $ag_action == "edit" ){
				print $ACEta;
			}else{
				print "\n".str_repeat("\t",6).$agdPS;
			}
		}
		print "\n".str_repeat("\t",5).'</td>';
		print "\n".str_repeat("\t",4).'</tr>';
	}
	print "\n".str_repeat("\t",3).'</table>';
	// Print the Appropriate button.
    if ( $submit == "" || $ag_action == "edit" || $ag_action == "delete" || $ag_action == "clear" ) {
        echo '<div style="margin:auto">';
        if ($ag_action == "create" ) {
            $button_text = _CREATEGROUPS;
        } else if ($ag_action == "view" ) {
            $button_text = _VIEWGROUPS;
        } else if ($ag_action == "edit" && $submit == "") {
            $button_text = _EDITGROUPS;
        } else if ($ag_action == "edit" && $submit != "") {
            $button_text = _SAVECHANGES;  $ag_action = "save";
        } else if ($ag_action == "delete" && $submit == "") {
            $button_text = _DELETEGROUPS;
        } else if ($ag_action == "delete" && $submit != "") {
            $button_text = _CONFIRMDELETE; $ag_action = "delete_confirm";
        } else if ($ag_action == "clear" && $submit == "") {
            $button_text = _CLEARGROUPS;
        } else if ($ag_action == "clear" && $submit != "") {
            $button_text = _CONFIRMCLEAR; $ag_action = "clear_confirm";
        }
        echo '<input type="submit" name="submit" value="'.$button_text.'">';
        echo '</div>';
	}
} // if ($ag_action != "list")
    
echo '<input type="hidden" name="ag_action" value="'.htmlspecialchars($ag_action).'">';
if ( $ag_action == "view" && $submit != "" ) {
	/* Calculate the Number of Alerts */
	$cnt_sql = "SELECT count(ag_sid) FROM acid_ag_alert WHERE ag_id='".$ag_id."'";
	$save_sql = "SELECT acid_event.sid, acid_event.cid, signature, timestamp, ".
         "ip_src, ip_dst, ip_proto ".
         "FROM acid_event ".
         "LEFT JOIN acid_ag_alert ON acid_event.sid=ag_sid AND acid_event.cid=ag_cid ".
         "WHERE acid_event.cid > '0' AND ag_id = '".$ag_id."'";
	$printing_ag = true;
	$ag = $ag_id;
	include("$BASE_path/base_qry_sqlcalls.php");
}
$qs->SaveState();
// Export $ag_id to action_arg, so that Actions work.
ExportHTTPVar($ag_id, "action_arg"); 
print "\n".str_repeat("\t",3).'</form>';
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
