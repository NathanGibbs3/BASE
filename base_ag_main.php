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

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_qry_common.php");
include_once("$BASE_path/base_ag_common.php");

AuthorizedRole(10000);
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to Alert DB.
$db->baseDBConnect(
	$db_connect_method,$alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);
if ( class_exists('UILang') ){ // Issue 11 backport shim.
	$CPSig = $UIL->CWA['Sig'];
	$CPSA = $UIL->CPA['SrcAddr'];
	$CPDA = $UIL->CPA['DstAddr'];
	$CPTs = $UIL->CWA['Ts'];
}else{
	$CPSig = _SIGNATURE;
	$CPSA = _NBSOURCEADDR;
	$CPDA = _NBDESTADDR;
	$CPTs = _TIMESTAMP;
}

$AdminAuth = AuthorizedRole(50); // AG-Editor
$cs = new CriteriaState("base_ag_main.php");
$cs->ReadState();
$qs = new QueryState();
$submit = ImportHTTPVar(
	'submit', VAR_ALPHA | VAR_SPACE,
	array(_SELECTED, _ALLONSCREEN, _ENTIREQUERY)
);
$ag_action = ImportHTTPVar("ag_action", VAR_ALPHA | VAR_USCORE);
$ag_id = filterSql(ImportHTTPVar("ag_id", VAR_DIGIT));
$ag_name = filterSql(ImportHTTPVar("ag_name"));
$ag_desc = filterSql(ImportHTTPVar("ag_desc"));
$page_title = _AGMAINTTITLE;
PrintBASESubHeader(
	$page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages
);
if( $debug_mode > 0 ){ // Dump debugging info on the shared state.
	PrintCriteriaState();
}
if ( is_numeric($submit) ){ // A browsing button was clicked.
	if ( $debug_mode > 0 ){
		ErrorMessage("Browsing Clicked ($submit)");
	}
	$qs->MoveView($submit);
	$ag_action = "view";
}
$Hrst = "<a href='base_ag_main.php?ag_action=";
$Sep = ' | ';
NLIO("<div style='margin:auto'>");
NLIO($Hrst."list'>"._LISTALL.'</a>',4);
if ( $AdminAuth ){
	NLIO($Sep.$Hrst."create'>"._CREATE.'</a>',4);
}
NLIO($Sep.$Hrst."view'>"._VIEW.'</a>',4);
if ( $AdminAuth ){
	NLIO($Sep.$Hrst."edit'>"._EDIT.'</a>',4);
	NLIO($Sep.$Hrst."delete'>"._DELETE.'</a>',4);
}
NLIO($Sep.$Hrst."clear'>"._CLEAR.'</a>',4);
NLIO('</div>');
NLIO('<hr/>');
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

$AgaDesc = array(
	'create' => _CREATEGROUPS,
	'view' => _VIEWGROUPS,
	'edit' => _EDITGROUPS,
	'save' => _EDITGROUPS,
	'delete' => _DELETEGROUPS,
	'delete_confirm' => _DELETEGROUPS,
	'clear' => _CLEARGROUPS,
	'clear_confirm' => _CLEARGROUPS,
	'list' => _LISTGROUPS,
);
if( is_key($ag_action, $AgaDesc) ){
	$AcDesc = $AgaDesc[$ag_action];
}else{
	$AcDesc = $AgaDesc['list']; // Something strange is going on, lock it down.
	$ag_action = "list";
}
NLIO("<h3>$AcDesc</h3>", 2);
NLIO("<form name='PacketForm' action='base_ag_main.php' method='post'>", 2);

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
             <table border="1" style="border-spacing:0;padding:0" width="100%">
             <tr>
               <td class="plfieldhdr">'._ID.'</td>
               <td class="plfieldhdr">'._NAME.'</td>
               <td class="plfieldhdr">'._NUMALERTS.'</td>
               <td class="plfieldhdr">'._DESC.'</td>
               <td class="plfieldhdr">'._ACTIONS;

		PrintTblNewRow( 0, '');
		$Hrsfx = "&amp;submit=x'>";
        for ($i = 0; $i < $num; $i++) {
            $myrow = $result->baseFetchRow();
			$AOA = urlencode($myrow[0]); // ActOnAG

            /* count the number of alerts in the AG */
            $result2 = $db->baseExecute("SELECT count(ag_cid) FROM acid_ag_alert WHERE ag_id='".$myrow[0]."'");
            $myrow2 = $result2->baseFetchRow();
            $num_alerts = $myrow2[0];
            $result2->baseFreeRows();

echo '                    <td class="plfield">
                      <a href="base_ag_main.php?ag_action=view&amp;ag_id='.htmlspecialchars($myrow[0]).'&amp;submit=x">'.htmlspecialchars($myrow[0]).'</a></td>
                      <td class="plfield">'.htmlspecialchars($myrow[1]).'</TD>
                      <td class="plfield">'.$num_alerts.'</TD>
                      <td class="plfield">'.htmlspecialchars($myrow[2]).'</TD>
                      <td class="plfield">';

			if ( $AdminAuth ){
				NLIO($Hrst."edit&amp;ag_id=".$AOA.$Hrsfx._EDIT.'</a>',4);
				NLIO($Sep.$Hrst."delete&amp;ag_id=".$AOA.$Hrsfx._DELETE.'</a>',4);
				NLIO($Sep,4);
			}
			NLIO($Hrst."clear&amp;ag_id=".$AOA.$Hrsfx._CLEAR.'</a>',4);
			PrintTblNewRow( 0, '');
		}
        echo '</table>';
        $result->baseFreeRows();
	}
}

if ($ag_action != "list") {
    echo '<table width="100%" border="2" class="query">
          <tr>
           <td width="10%"><strong>ID #</strong></td>
           <td>';

    if ($ag_action == "create" && $submit == "") {
        echo '&nbsp;<em> '._NOTASSIGN.' </em>&nbsp';
    } else if ($submit == "") {
        echo '<input type="text" name="ag_id" value="'.htmlspecialchars($ag_id).'">';
    } else if ( ($ag_action == "view" || $ag_action == "edit" || $ag_action == "delete" || $ag_action == "clear") && $submit != "" ) {
        echo '<input type="hidden" name="ag_id" value="'.htmlspecialchars($ag_id).'">';
        echo $ag_id;
    }

    echo ' </td>
         <tr>
           <td valign="top"><strong>'._NAME.'</strong></td>
           <td>';

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

    echo ' </td>';
     
    if ( ($ag_action == "create" && $submit == "") || (($ag_action == "view" || $ag_action == "edit" || $ag_action == "delete" || $ag_action == "clear") && $submit != "" )) {
        echo '
        <tr>
         <td valign="top"><strong>'._DESC.'</strong></td>
         <td>';

        if ( $ag_action == "create" && $submit == "" ) {
            echo '<textarea name="ag_desc" cols="70" rows=4>'.htmlspecialchars($ag_desc).'</textarea>';
        } else if ( $ag_action == "edit" && $submit != "" ) {
            echo '<textarea name="ag_desc" cols="70" rows=4>'.htmlspecialchars($ag_desc).'</textarea>';
        } else if ( ($ag_action == "view" || $ag_action == "delete" ||$ag_action == "clear") && $submit != "" ) {
            echo(htmlspecialchars($ag_desc));
        }

        echo '
             </td>
           </tr>';
    }

    echo '</table>';
    /* Print the Appropriate button */
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
// Export action_arg = current AG ID, so that Actions work.
ExportHTTPVar($ag_id, "action_arg");
NLIO('</form>',2);
$et->Mark("Get Query Elements");
PrintBASESubFooter();
?>
