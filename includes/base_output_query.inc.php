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
//            Purpose: Manages the output of Query results.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

class QueryResultsOutput {
	var $qroHeader = NULL;
	var $url = '';
	var $JavaScript = NULL;

	function __construct($uri) { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			// @codeCoverageIgnoreStart
			// Should never execute.
			trigger_error( // Will need to add this message to the TD.
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}
	function QueryResultsOutput($uri) { // PHP 4x constructor.
		GLOBAL $BASE_path, $BASE_urlpath, $debug_mode;
		$this->url = $uri;
		$sc = DIRECTORY_SEPARATOR;
		$file = __FILE__;
		$ReqRE = preg_quote("$BASE_path$sc" . "includes$sc", '/');
		$file = preg_replace("/^" . $ReqRE ."/", '', $file);
		$file = preg_replace("/".preg_quote('.php','/')."$/", '', $file);
		$file = $sc . "js$sc$file" . '.js';
		$tf = "$BASE_path$file";
		$file = "$BASE_urlpath$file";
		if ( ChkAccess($tf) == 1 ){
			$this->JavaScript = $file;
		}
		if( $debug_mode > 1 ){
			$tmp = '';
			$cc = '';
			if ( is_null($this->JavaScript) ){
				$tmp = 'not ';
			}else{
				$cc = 'black';
			}
			ErrorMessage("Resource $tmp"."available JavaScript: $file", $cc,1);
		}
	}
	function AddTitle(
		$title, $asc_sort = " ", $asc_sort_sql1 = "", $asc_sort_sql2 = "",
		$desc_sort = " ", $desc_sort_sql1 = "", $desc_sort_sql2 = "",
		$align = 'center'
	){
		$align = strtolower($align);
		$hal = array( 'left', 'center', 'right' );
		if ( !in_array($align, $hal) ){
			$align = 'center';
		}
		$tc = CleanVariable($title, VAR_LETTER | VAR_USCORE);
		$this->qroHeader[$title] = array(
			$tc."_$asc_sort"  => array( $asc_sort_sql1, $asc_sort_sql2 ),
			$tc."_$desc_sort" => array( $desc_sort_sql1, $desc_sort_sql2 ),
			"$title-InternalProperty-align" => $align
		);
	}
	function GetSortSQL( $sort, $sort_order ){
		GLOBAL $debug_mode;
		$Ret = NULL; // $sort is not a valid sort type of any header.
		if ( !is_null($this->qroHeader) ){ // Issue #108 Check
			foreach ( $this->qroHeader as $title ){ // Issue #153
				if ( in_array($sort, array_keys($title)) ){
					$Ret = $title[$sort];
					break;
				}
			}
			if ( $debug_mode > 0 ){
				print "<pre>  FUNC: ".__FUNCTION__."()\n";
				print "  SORT: $sort\n";
				print " ORDER: $sort_order\n";
				print "RETURN: ";
				print_r($Ret);
				print "</pre>\n";
			}
		}
		return $Ret;
	}
	function PrintHeader(){
		$file = $this->JavaScript;
		if ( !is_null($file) ){
			NLIO("<script type='text/javascript' src='$file'></script>",3);
		}
		NLIO('<!-- Query Results Title Bar -->',3);
		PrintFramedBoxHeader('','black');
		if ( is_null($this->qroHeader) ){ // Issue #108 Check
			$tdpfx = "<td class='plfieldhdr'>";
			NLIO( $tdpfx.'NULL Header.</td>', 5 );
		}else{
			$hal = array( 'left', 'center', 'right' );
			foreach ( $this->qroHeader as $title ){ // Issue #153
				$sort_keys = array_keys($title);
				$align = '';
				foreach ( $sort_keys as $val ){
					if ( preg_match("/-InternalProperty-align$/", $val) ){
						$tt = preg_replace(
							"/-InternalProperty-align$/", '', $val
						);
						$align = $title["$tt-InternalProperty-align"];
						break;
					}
				}
				$align = strtolower($align);
				if ( !in_array($align, $hal) ){
					$align = 'center';
				}
				$tdpfx = "<td class='plfieldhdr' style='text-align:$align;'>";
				$print_title = '';
				$pfx = '';
				$sfx = '';
				if ( count($sort_keys) == 3 ){
					$tmp = "<a href='".$this->url."&amp;sort_order=";
					$pfx = $tmp.$sort_keys[0]."'>&lt;</a>&nbsp;";
					$sfx = "&nbsp;$tmp".$sort_keys[1]."'>&gt;</a>";
				}
				$print_title = $pfx.$tt.$sfx;
				NLIO( $tdpfx, 5 );
				NLIO($print_title, 6 );
				NLIO( '</td>', 5 );
			}
		}
		NLIO('</tr>',4);
		NLIO('<!-- Query Results Table -->',4);
	}
	function PrintFooter(){
		NLIO('</table>',3);
	}
	function DumpQROHeader(){ // This code is not used anywhere.
    echo "<B>"._QUERYRESULTSHEADER."</B>
          <PRE>";
    print_r($this->qroHeader);
    echo "</PRE>";
	}
}
function qroReturnSelectALLCheck(){
	return "<input type=checkbox value='Select All' ".
	"onClick='if (this.checked) SelectAll(); ".
	"if (!this.checked) UnselectAll();'/>";
}
function qroPrintEntryHeader($prio=1, $color=0) {
	GLOBAL $priority_colors;
	$msg = '<tr bgcolor="#';
	if($color != 1) { // Row colors alternating.
		if ( $prio % 2 == 0 ){
			$tmp = 'DDDDDD'; // Light Gray
		}else{
			$tmp = 'FFFFFF'; // White
		}
	}else{ // Row colors by alert priority.
		$prio --; // Fix Issue #60
		$tmp ='';
		// Fix Issue #59
		if(
			is_key('priority_colors', $GLOBALS)
			&& is_key($prio, $priority_colors)
		){
			$tmp = $priority_colors[$prio];
		}
		// Fix Issue #57
		// Expect 6 digit hex color code.
		// Default to Dark Gray ( $prio=4 ), if something's odd.
		if( !preg_match('/^[0-9A-F]{6}$/i', $tmp) ){
			$tmp = '999999';
		}
	}
	$msg .= $tmp . '">';
	print $msg;
}
function qroPrintEntry( $value, $halign = 'center', $valign = 'top' ){
	$halign = strtolower($halign);
	$valign = strtolower($valign);
	$hal = array( 'left', 'center', 'right' );
	$val = array( 'top', 'bottom' );
	if ( !in_array($halign, $hal) ){
		$halign = 'center';
	}
	if ( !in_array($valign, $val) ){
		$valign = 'top';
	}
	NLIO (
		"<td style='text-align: $halign; vertical-align: $valign; ".
		"padding-left: 15px; padding-right: 15px'>",3
	);
	NLIO ($value,4);
	NLIO ('</td>',3);
}
function qroPrintEntryFooter(){
	NLIO ('</tr>',2);
}
?>
