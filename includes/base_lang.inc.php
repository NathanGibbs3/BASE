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
//            Purpose: UI Language abstraction layer.
//
//          Author(s): Nathan Gibbs

// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

class UILang{
	var $TDF;
	var $Lang;
	var $Locale;
	var $Timefmt;
	var $Charset;
	var $Title;
	var $ADA;
	var $CPA;
	var $CWA;
	var $UAA;
	var $Spacing;

	function __construct($UILang) { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			// @codeCoverageIgnoreStart
			// Should never execute.
			trigger_error(
				// Will need to add this message to the TD.
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}
	function UILang($UILang) { // PHP 4x constructor.
		GLOBAL $BASE_path, $BASE_installID, $Use_Auth_System;
		$TDF = "$BASE_path/languages/$UILang.lang.php";
		if (!file_exists($TDF)) { // TD file for lang not found.
			trigger_error(
				"No TD found for Language: $UILang. Default to english.\n"
			);
			$UILang = 'english'; // Default to english TD file.
			$TDF = "$BASE_path/languages/$UILang.lang.php";
		}
		include_once($TDF); // Include Translation Data.
		$this->Lang = $UILang;
		$this->TDF = $TDF;
		if ( isset($UI_Spacing) ) { // New TDF
			if ( !is_int($UI_Spacing) ) {
				$UI_Spacing = 1; // Default to 1 on invalid data.
			}
			$this->Spacing = $UI_Spacing;
		}else{ // Legacy TDF. Spacing Information does not exist.
			if ( preg_match("/(chi|japa)nese/", $UILang) ){
				$this->Spacing = 0;
			}else{
				$this->Spacing = 1;
			}
		}
		if ( isset($UI_Locales) ) { // Var New TDF
			if ( is_array($UI_Locales) ) {
				$this->Locale = $UI_Locales;
			}else{ // Invalid format of Locale in TDF.
				$this->Locale = 'Invalid';
			}
		}elseif (
			defined('_LOCALESTR1')
			&& defined('_LOCALESTR2')
			&& defined('_LOCALESTR3')
		) { // Const Legacy TDF
			$this->Locale = array ( _LOCALESTR1, _LOCALESTR2, _LOCALESTR3 );
		}else{
			$this->Locale = NULL;
		}
		if ( isset($UI_Timefmt) ) { // Var New TDF
			$this->SetUITimefmt($UI_Timefmt);
		}elseif (defined('_STRFTIMEFORMAT')) { // Const Legacy TDF
			$this->SetUITimefmt(_STRFTIMEFORMAT);
		}else{
			$this->SetUITimefmt();
		}
		if ( isset($UI_Charset) ) { // Var New TDF
			$this->SetUICharset($UI_Charset);
		}elseif (defined('_CHARSET')) { // Const Legacy TDF
			$this->SetUICharset(_CHARSET);
		}else{
			$this->SetUICharset();
		}
		if ( isset($UI_Title) ) { // Var New TDF
			$this->SetUITitle($UI_Title);
		}elseif (defined('_TITLE')) { // Const Legacy TDF
			$this->SetUITitle(_TITLE);
		}else{
			$this->SetUITitle();
		}
		// Init Universal Actions
		if ( isset($UI_UA_Edit) ) { // Var New TDF
			$this->SetUIUAItem('Edit',$UI_UA_Edit);
		}elseif (defined('_EDIT')) { // Const Legacy TDF
			$this->SetUIUAItem('Edit',_EDIT);
		}else{
			$this->SetUIUAItem('Edit');
		}
		if ( isset($UI_UA_Delete) ) { // Var New TDF
			$this->SetUIUAItem('Delete',$UI_UA_Delete);
		}elseif (defined('_DELETE')) { // Const Legacy TDF
			$this->SetUIUAItem('Delete',_DELETE);
		}else{
			$this->SetUIUAItem('Delete');
		}
		// Init Common Phrases
		if ( isset($UI_CP_SrcDesc) ) { // Var New TDF
			$this->SetUICPItem('SrcDesc',$UI_CP_SrcDesc);
		}elseif (defined('_SOURCE')) { // Const Legacy TDF
			$this->SetUICPItem('SrcDesc',_SOURCE);
		}else{
			$this->SetUICPItem('SrcDesc');
		}
		if ( isset($UI_CP_SrcName) ) { // Var New TDF
			$this->SetUICPItem('SrcName',$UI_CP_SrcName);
		}elseif (defined('_SOURCENAME')) { // Const Legacy TDF
			$this->SetUICPItem('SrcName',_SOURCENAME);
		}else{
			$this->SetUICPItem('SrcName');
		}
		if ( isset($UI_CP_DstDesc) ) { // Var New TDF
			$this->SetUICPItem('DstDesc',$UI_CP_DstDesc);
		}elseif (defined('_DEST')) { // Const Legacy TDF
			$this->SetUICPItem('DstDesc',_DEST);
		}else{
			$this->SetUICPItem('DstDesc');
		}
		if ( isset($UI_CP_DstName) ) { // Var New TDF
			$this->SetUICPItem('DstName',$UI_CP_DstName);
		}elseif (defined('_DESTNAME')) { // Const Legacy TDF
			$this->SetUICPItem('DstName',_DESTNAME);
		}else{
			$this->SetUICPItem('DstName');
		}
		if ( isset($UI_CP_SrcDst) ) { // Var New TDF
			$this->SetUICPItem('SrcDst',$UI_CP_SrcDst);
		}elseif (defined('_SORD')) { // Const Legacy TDF
			$this->SetUICPItem('SrcDst',_SORD);
		}else{
			$this->SetUICPItem('SrcDst');
		}
		if ( isset($UI_CP_Id) ) { // Var New TDF
			$this->SetUICPItem('Id',$UI_CP_Id);
		}elseif (defined('_ID')) { // Const Legacy TDF
			$this->SetUICPItem('Id',_ID);
		}else{
			$this->SetUICPItem('Id');
		}
		if ( isset($UI_CP_Name) ) { // Var New TDF
			$this->SetUICPItem('Name',$UI_CP_Name);
		}elseif (defined('_NAME')) { // Const Legacy TDF
			$this->SetUICPItem('Name',_NAME);
		}else{
			$this->SetUICPItem('Name');
		}
		if ( isset($UI_CP_Int) ) { // Var New TDF
			$this->SetUICPItem('Int',$UI_CP_Int);
		}elseif (defined('_INTERFACE')) { // Const Legacy TDF
			$this->SetUICPItem('Int',_INTERFACE);
		}else{
			$this->SetUICPItem('Int');
		}
		if ( isset($UI_CP_Filter) ) { // Var New TDF
			$this->SetUICPItem('Filter',$UI_CP_Filter);
		}elseif (defined('_FILTER')) { // Const Legacy TDF
			$this->SetUICPItem('Filter',_FILTER);
		}else{
			$this->SetUICPItem('Filter');
		}
		if ( isset($UI_CP_Desc) ) { // Var New TDF
			$this->SetUICPItem('Desc',$UI_CP_Desc);
		}elseif (defined('_DESC')) { // Const Legacy TDF
			$this->SetUICPItem('Desc',_DESC);
		}else{
			$this->SetUICPItem('Desc');
		}
		if ( isset($UI_CP_SucDesc) ) { // Var New TDF
			$this->SetUICPItem('SucDesc',$UI_CP_SucDesc);
		}elseif (defined('_SUCCESS')) { // Const Legacy TDF
			$this->SetUICPItem('SucDesc',_SUCCESS);
		}else{
			$this->SetUICPItem('SucDesc');
		}
		if ( isset($UI_CP_Sensor) ) { // Var New TDF
			$this->SetUICPItem('Sensor',$UI_CP_Sensor);
		}elseif (defined('_SUCCESS')) { // Const Legacy TDF
			$this->SetUICPItem('Sensor',_SENSOR);
		}else{
			$this->SetUICPItem('Sensor');
		}
		if ( isset($UI_CP_Sig) ) { // Var New TDF
			$this->SetUICPItem('Sig',$UI_CP_Sig);
		}elseif (defined('_SIGNATURE')) { // Const Legacy TDF
			$this->SetUICPItem('Sig',_SIGNATURE);
		}else{
			$this->SetUICPItem('Sig');
		}
		// Init Authentication Data if Auth Sys is enabled.
		if ($Use_Auth_System == 1) {
			if ( isset($UI_AD_UND) ) { // Var New TDF
				$this->SetUIADItem('DescUN',$UI_AD_UND);
			}elseif (defined('_FRMLOGIN')) { // Const Legacy TDF
				$this->SetUIADItem('DescUN',_FRMLOGIN);
			}else{
				$this->SetUIADItem('DescUN');
			}
			if ( isset($UI_AD_PWD) ) { // Var New TDF
				$this->SetUIADItem('DescPW',$UI_AD_PWD);
			}elseif (defined('_FRMPWD')) { // Const Legacy TDF
				$this->SetUIADItem('DescPW',_FRMPWD);
			}else{
				$this->SetUIADItem('DescPW');
			}
			if ( isset($UI_AD_RID) ) { // Var New TDF
				$this->SetUIADItem('DescRI',$UI_AD_RID);
			}elseif (defined('_FRMPWD')) { // Const Legacy TDF
				$this->SetUIADItem('DescRI',_ROLEID);
			}else{
				$this->SetUIADItem('DescRI');
			}
			if ( isset($UI_AD_ASD) ) { // Var New TDF
				$this->SetUIADItem('DescAS',$UI_AD_ASD);
			}elseif (defined('_ENABLED')) { // Const Legacy TDF
				$this->SetUIADItem('DescAS',_ENABLED);
			}else{
				$this->SetUIADItem('DescAS');
			}
		}else{
			$this->ADA = NULL;
		}
		// This Line is here until we migrate some TD into CWA.
		$this->CWA = array();
		// Check for unset/NULL TD, replace with default placeholder text.
		$this->Timefmt = $this->BlankProps('Timefmt',$this->Timefmt);
		$this->Charset = $this->BlankProps('Charset',$this->Charset);
		$this->Title = $this->BlankProps('Title',$this->Title);
		if ($Use_Auth_System == 1) {
			$this->ADA = $this->BlankProps('ADA',$this->ADA);
		}
		$this->CWA = $this->BlankProps('CWA',$this->CWA);
		$this->CPA = $this->BlankProps('CPA',$this->CPA);
		$this->UAA = $this->BlankProps('UAA',$this->UAA);
		// Anti XSS the Translation Data.
		$this->Locale = XSSPrintSafe($this->Locale);
		$this->Timefmt = XSSPrintSafe($this->Timefmt);
		$this->Charset = XSSPrintSafe($this->Charset);
		$this->Title = XSSPrintSafe($this->Title);
		$this->ADA = XSSPrintSafe($this->ADA);
		$this->CWA = XSSPrintSafe($this->CWA);
		$this->CPA = XSSPrintSafe($this->CPA);
		$this->UAA = XSSPrintSafe($this->UAA);
	}
	// Notify about & Init unset/NULL TD Items.
	// Set Items will pass through unchanged.
	function BlankProps($Property, $Item) {
		if ( !isset($Item) ){ // Is Unset/NULL.
			$Value = "Missing TD Item: $Property.\n";
			trigger_error($Value); // Notify.
			return $Value;
		}else{
			if ( is_array($Item) ){ // Is Array.
				foreach ($Item as $key => $value) {
					$Item[$key] = $this->BlankProps($Property."[$key]", $value);
				}
				return $Item;
			}else{ // Is Variable.
				return $Item;
			}
		}
	}
	// Sets locale from translation data or defaults to system locale.
	function SetUILocale() {
		if ( is_array($this->Locale) ) {
			$tmp = $this->Locale;
		}else{
			$tmp = array();
		}
		array_push( $tmp, "");
		$Ret = setlocale (LC_TIME, $tmp);
		if ($Ret != FALSE) {
			$this->Locale = setlocale(LC_TIME, "0");
		}else{
			// @codeCoverageIgnoreStart
			// This code path is implementation dependent.
			$this->Locale = NULL;
			// @codeCoverageIgnoreEnd
		}
		return $Ret;
	}
	// Sets Time format from translation data.
	function SetUITimeFmt($Value = NULL) {
		$this->Timefmt = $Value;
	}
	// Sets HTML Content-Type charset from translation data.
	function SetUICharset($Value = NULL) {
		$this->Charset = $Value;
	}
	// Sets HTML Common Page Title from translation data.
	function SetUITitle($Value = NULL) {
		$this->Title = $Value;
	}
	// Sets Authentication Data Item from translation data.
	function SetUIADItem($Item,$Value = NULL) {
		GLOBAL $Use_Auth_System;
		if ($Use_Auth_System == 1) { // Run only if Auth Sys is enabled.
			$Items = array ( 'DescUN', 'DescPW', 'DescRI', 'DescAS' );
			if (in_array($Item, $Items)) {
				$this->ADA[$Item] = $Value;
			}else{
				// Will need to add this message to the TD.
				trigger_error("Invalid AD Set Request for: $Item.\n");
			}
		}
	}
	// Sets Common Word Item from translation data.
	function SetUICWItem($Item,$Value = NULL) {
		$Items = array (
		);
		if (in_array($Item, $Items)) {
			$this->CWA[$Item] = $Value;
		}else{
			// Will need to add this message to the TD.
			trigger_error("Invalid CW Set Request for: $Item.\n");
		}
	}
	// Sets Common Phrase Item from translation data.
	function SetUICPItem($Item,$Value = NULL) {
		$Items = array (
			'SrcDesc', 'SrcName', 'DstDesc', 'DstName', 'SrcDst', 'Id', 'Name',
			'Int', 'Filter', 'Desc', 'SucDesc', 'Sensor', 'Sig'
		);
		if (in_array($Item, $Items)) {
			$this->CPA[$Item] = $Value;
		}else{
			// Will need to add this message to the TD.
			trigger_error("Invalid CP Set Request for: $Item.\n");
		}
	}
	// Sets Universal Action Item from translation data.
	function SetUIUAItem($Item,$Value = NULL) {
		$Items = array ( 'Edit', 'Delete' );
		if (in_array($Item, $Items)) {
			$this->UAA[$Item] = $Value;
		}else{
			// Will need to add this message to the TD.
			trigger_error("Invalid UA Set Request for: $Item.\n");
		}
	}
}
?>
