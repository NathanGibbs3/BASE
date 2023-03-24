<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: renders the HTML form to gather search criteria
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

if ( isset($sort_order) ){ // Issue #5
if ( $submit == "TCP" )        {  $cs->criteria['layer4']->Set("TCP");   }
if ( $submit == "UDP" )        {  $cs->criteria['layer4']->Set("UDP");   }
if ( $submit == "ICMP" )       {  $cs->criteria['layer4']->Set("ICMP");  }
if ( $submit == _NOLAYER4 )    {  $cs->criteria['layer4']->Set("");      }

if ( $submit == _ADDTIME && $cs->criteria['time']->GetFormItemCnt() < $MAX_ROWS)        
   $cs->criteria['time']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDADDRESS && $cs->criteria['ip_addr']->GetFormItemCnt() < $MAX_ROWS)     
   $cs->criteria['ip_addr']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDIPFIELD && $cs->criteria['ip_field']->GetFormItemCnt() < $MAX_ROWS)    
   $cs->criteria['ip_field']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDTCPPORT && $cs->criteria['tcp_port']->GetFormItemCnt() < $MAX_ROWS)    
   $cs->criteria['tcp_port']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDTCPFIELD && $cs->criteria['tcp_field']->GetFormItemCnt() < $MAX_ROWS)   
   $cs->criteria['tcp_field']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDUDPPORT && $cs->criteria['udp_port']->GetFormItemCnt() < $MAX_ROWS)    
   $cs->criteria['udp_port']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDUDPFIELD && $cs->criteria['udp_field']->GetFormItemCnt() < $MAX_ROWS)
   $cs->criteria['udp_field']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDICMPFIELD && $cs->criteria['icmp_field']->GetFormItemCnt() < $MAX_ROWS)
   $cs->criteria['icmp_field']->AddFormItem($submit, $cs->criteria['layer4']->Get());
if ( $submit == _ADDPAYLOAD && $cs->criteria['data']->GetFormItemCnt() < $MAX_ROWS)
   $cs->criteria['data']->AddFormItem($submit, $cs->criteria['layer4']->Get());

echo '

<!-- ************ Meta Criteria ******************** -->
<TABLE WIDTH="100%" BORDER=0>
  <TR>
      <TD WIDTH="40%" CLASS="metatitle"><B>'._QCMETACRIT.'</B></TD>
      <TD></TD></TR>
</TABLE>

<TABLE WIDTH="100%" border=2 class="query">
  <TR>
      <TD COLSPAN=2>
           <B>'._SENSOR.': </B>';
$cs->criteria['sensor']->PrintForm('','','');
     echo '<B>'._ALERTGROUP.': </B>';
$cs->criteria['ag']->PrintForm('','','');
     echo '</TD>';

     echo '<TR>
            <TD><B>'._SIGNATURE.': </B></TD>
           <TD>';  
$cs->criteria['sig']->PrintForm('','','');
if ( $db->baseGetDBversion() >= 103 ) {
	echo '<B>'._CHRTCLASS.': </B>';
	$cs->criteria['sig_class']->PrintForm('','','');
	echo '<B>'._PRIORITY.': </B>';
	$cs->criteria['sig_priority']->PrintForm('','','');
}
     echo '</TD></TR>';    

echo '<TR>
      <TD><B>'._ALERTTIME.':</B></TD>
      <TD>';
$cs->criteria['time']->PrintForm('','','');
        echo '
</TABLE>
<ul id="zMenu">';

  echo '
<p>    </p>
<li> <a href="#">'._QCIPCRIT.'</a>
<ul>      
<!-- ************ IP Criteria ******************** -->
<P>
<TABLE WIDTH="90%" BORDER=0>
  <TR>
      <TD WIDTH="40%" CLASS="iptitle"><B>'._QCIPCRIT.'</B></TD>
      <TD></TD></TR>
</TABLE>

<TABLE WIDTH="90%" BORDER=2 class="query">';
      echo '<TR><TD VALIGN=TOP><B>'._ADDRESS.':</B>';  
      echo '    <TD>';
$cs->criteria['ip_addr']->PrintForm('','','');
      echo '<TR><TD><B>'._MISC.':</B>';
      echo '    <TD>';
$cs->criteria['ip_field']->PrintForm('','','');
   echo '
   <TR><TD><B>Layer-4:</B>
       <TD>';
$cs->criteria['layer4']->PrintForm('','','');
echo '
   </TABLE>
      </ul>
<p>  </p>
</li>';

if ( $cs->criteria['layer4']->Get() == "TCP" )
{
  echo '
    <p></p>
<li> <a href="#">'._QCTCPCRIT.'</a>
      <ul>
<!-- ************ TCP Criteria ******************** -->
<P>
<TABLE WIDTH="90%" BORDER=0>
  <TR>
      <TD WIDTH="40%" CLASS="layer4title"><B>'._QCTCPCRIT.'</B></TD>
      <TD></TD>
</TABLE>

<TABLE WIDTH="90%" BORDER=2 class="query">';

      echo '<TR><TD><B>'._PORT.':</B>';
      echo '    <TD>';
	$cs->criteria['tcp_port']->PrintForm('','','');
  echo '
  <TR>
      <TD VALIGN=TOP><B>'._FLAGS.':</B>';
	$cs->criteria['tcp_flags']->PrintForm('','','');
      echo '<TR><TD><B>'._MISC.':</B>';
      echo '    <TD>';
	$cs->criteria['tcp_field']->PrintForm('','','');
  echo'
</TABLE>         
</ul>
<p>  </p>
</li>';
}

if ( $cs->criteria['layer4']->Get() == "UDP" )
{
  echo '
      <p></p>
<li> <a href="#">'._QCUDPCRIT.'</a>
      <ul>
<!-- ************ UDP Criteria ******************** -->
<P>
<TABLE WIDTH="90%" BORDER=0>
  <TR>
      <TD WIDTH="40%" CLASS="layer4title"><B>'._QCUDPCRIT.'</B></TD>
      <TD></TD>
</TABLE>

<TABLE WIDTH="100%" BORDER=2 class="query">';

      echo '<TR><TD><B>'._PORT.':</B>';
      echo '    <TD>';
	$cs->criteria['udp_port']->PrintForm('','','');
      echo '<TR><TD><B>'._MISC.':</B>';
      echo '    <TD>';
	$cs->criteria['udp_field']->PrintForm('','','');
  echo'
</TABLE>
</ul>
<p>
  </p>
</li>';
}


if ( $cs->criteria['layer4']->Get() == "ICMP" )
{
  echo  '
        <p></p>
<li> <a href="#">'._QCICMPCRIT.'</a>
      <ul>
<!-- ************ ICMP Criteria ******************** -->
<P>
<TABLE WIDTH="90%" BORDER=0>
  <TR>
      <TD WIDTH="40%" CLASS="layer4title"><B>'._QCICMPCRIT.'</B></TD>
      <TD></TD>
</TABLE>


<TABLE WIDTH="100%" BORDER=2 class="query">';

      echo '<TR><TD><B>'._MISC.':</B>';
      echo '    <TD>';
	$cs->criteria['icmp_field']->PrintForm('','','');
   echo '
</TABLE>
</ul>
<p>  </p>
</li>'; 
}

echo '
      <p></p>
<li> <a href="#">'._QCPAYCRIT.'</a>
<ul>
<!-- ************ Payload Criteria ******************** -->
<P>
<TABLE WIDTH="90%" BORDER=0>
  <TR>
      <TD WIDTH="40%" CLASS="payloadtitle"><B>'._QCPAYCRIT.'</B></TD>
      <TD></TD></TR>
</TABLE>

<TABLE WIDTH="90%" BORDER=2 class="query">
  <TR>
      <TD>';
	$cs->criteria['data']->PrintForm('','','');
        echo '
</TABLE>
</ul>
<p>  </p>
</li></ul>';

	ExportHTTPVar('new',1,3);
	$SOFIPfx = "<input type='radio' name='sort_order' value='";
	NLIO("<div style='margin-left: 25%; margin-right: 25%;'>",3);
	PrintFramedBoxHeader(_QFRMSORTORDER,'',1,4);
	NLIO(
		$SOFIPfx . "none'"
		.chk_check($sort_order, 'none').'/>'. _QFRMSORTNONE, 7
	);
	NLIO(
		"$SOFIPfx$CPTs" . "_time_a'"
		.chk_check($sort_order, $CPTs . '_time_a').'/>'._QFRMTIMEA, 7
	);
	NLIO(
		"$SOFIPfx$CPTs" . "_time_d'"
		.chk_check($sort_order, $CPTs . '_time_d').'/>'._QFRMTIMED, 7
	);
	NLIO(
		"$SOFIPfx$CPSig" . "_sig_d'"
		.chk_check($sort_order, $CPSig . '_sig_d').'/>'._QFRMSIG, 7
	);
	NLIO(
		$SOFIPfx . CleanVariable($CPSA, VAR_LETTER | VAR_USCORE) . "_sip_a'"
		.chk_check($sort_order, $CPSA . '_sip_a').'/>'._QFRMSIP, 7
	);
	NLIO(
		$SOFIPfx . CleanVariable($CPDA, VAR_LETTER | VAR_USCORE) . "_dip_a'"
		.chk_check($sort_order, $CPDA . '_dip_a').'/>'._QFRMDIP, 7
	);
	PrintTblNewRow(1,'center',6);
	NLIO("<input type='submit' name='submit' value='"._QUERYDB."'/>",7);
	printFramedBoxFooter(1,4);
	NLIO('</div>',3);
 echo '
 <!-- ************ JavaScript for Hiding Details ******************** -->
 <script type="text/javascript">
// <![CDATA[
function loopElements(el,level){
	for(var i=0;i<el.childNodes.length;i++){
		//just want LI nodes:
		if(el.childNodes[i] && el.childNodes[i]["tagName"] && el.childNodes[i].tagName.toLowerCase() == "li"){
			//give LI node a className
			el.childNodes[i].className = "zMenu"+level
			//Look for the A and if it has child elements (another UL tag)
			childs = el.childNodes[i].childNodes
			for(var j=0;j<childs.length;j++){
				temp = childs[j]
				if(temp && temp["tagName"]){
					if(temp.tagName.toLowerCase() == "a"){
						//found the A tag - set class
						temp.className = "zMenu"+level
						//adding click event
						temp.onclick=showHide;
					}else if(temp.tagName.toLowerCase() == "ul"){
						//Hide sublevels
						temp.style.display = "';
if (isset($show_expanded_query) && $show_expanded_query == 1){
	print '"';
}else{
	print 'none"';
}
 echo '
						//Set class
						temp.className= "zMenu"+level
						//Recursive - calling self with new found element - go all the way through 
						loopElements(temp,level +1) 
					}
				}
			}	
		}
	}
}

var menu = document.getElementById("zMenu") //get menu div
menu.className="zMenu"+0 //Set class to top level
loopElements(menu,0) //function call

function showHide(){
	//from the LI tag check for UL tags:
	el = this.parentNode
	//Loop for UL tags:
	for(var i=0;i<el.childNodes.length;i++){
		temp = el.childNodes[i]
		if(temp && temp["tagName"] && temp.tagName.toLowerCase() == "ul"){
			//Check status:
			if(temp.style.display=="none"){
				temp.style.display = ""
			}else{
				temp.style.display = "none"	
			}
		}
	}
	return false
}
// ]]>
</script>
';
}
?>
