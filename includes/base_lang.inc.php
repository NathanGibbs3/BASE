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
	var $UAA;

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
		if (!file_exists($TDF)) {
//			trigger_error(
				// Will need to add this message to the TD.
//				"No TD found for Language: $UILang. Default to english"
//			);
			// Default to english if language is not supported.
			$TDF = "$BASE_path/languages/english.lang.php";
		}
		// Include Translation Data
		include_once($TDF);
		$this->Lang = $UILang;
		$this->TDF = $TDF;
		// Store Locales
		if ( isset($UI_Locales) ) {
			if ( is_array($UI_Locales) ) {
				$this->Locale = $UI_Locales;
			}else{ // Invalid format of Locale in TDF.
				$this->Locale = NULL;
			}
		}else{
			$this->Locale = NULL;
		}
		if ( isset($UI_Timefmt) ) {
			$this->SetUITimefmt($UI_Timefmt);
		}else{
			$this->SetUITimefmt(NULL);
		}
		if ( isset($UI_Charset) ) {
			$this->SetUICharset($UI_Charset);
		}else{
			$this->SetUICharset(NULL);
		}
		if ( isset($UI_Title) ) {
			$this->SetUITitle($UI_Title);
		}else{
			$this->SetUITitle(NULL);
		}
		// Will not execute if Auth Sys is disabled.
		if ($Use_Auth_System == 1) {
			$this->ADA = array();
			if ( isset($UI_ADUN) ) {
				$this->SetUIADItem('DescUN',$UI_ADUN);
			}else{
				$this->SetUIADItem('DescUN',NULL);
			}
			if ( isset($UI_ADPW) ) {
				$this->SetUIADItem('DescPW',$UI_ADPW);
			}else{
				$this->SetUIADItem('DescPW',NULL);
			}
		}else{
			$this->ADA = NULL;
		}
		if ( isset($UI_UA_Edit) ) {
			$this->SetUIUAItem('Edit',$UI_UA_Edit);
		}else{
			$this->SetUIUAItem('Edit',NULL);
		}
		if ( isset($UI_UA_Delete) ) {
			$this->SetUIUAItem('Delete',$UI_UA_Delete);
		}else{
			$this->SetUIUAItem('Delete',NULL);
		}
		if ( isset($UI_CP_SrcDesc) ) {
			$this->SetUICPItem('SrcDesc',$UI_CP_SrcDesc);
		}else{
			$this->SetUICPItem('SrcDesc',NULL);
		}
		if ( isset($UI_CP_SrcName) ) {
			$this->SetUICPItem('SrcName',$UI_CP_SrcName);
		}else{
			$this->SetUICPItem('SrcName',NULL);
		}
		if ( isset($UI_CP_DstDesc) ) {
			$this->SetUICPItem('DstDesc',$UI_CP_DstDesc);
		}else{
			$this->SetUICPItem('DstDesc',NULL);
		}
		if ( isset($UI_CP_DstName) ) {
			$this->SetUICPItem('DstName',$UI_CP_DstName);
		}else{
			$this->SetUICPItem('DstName',NULL);
		}
		if ( isset($UI_CP_SrcDst) ) {
			$this->SetUICPItem('SrcDst',$UI_CP_SrcDst);
		}else{
			$this->SetUICPItem('SrcDst',NULL);
		}
		if ( isset($UI_CP_Id) ) {
			$this->SetUICPItem('Id',$UI_CP_Id);
		}else{
			$this->SetUICPItem('Id',NULL);
		}
	}
	// Sets locale from translation data or defaults to system locale.
	function SetUILocale() {
		if ( is_array($this->Locale) ) { // Var Based
			array_push( $this->Locale, "");
			$Ret = setlocale (LC_TIME, $this->Locale);
		}else{ // Const based
			$Ret = setlocale (LC_TIME, _LOCALESTR1, _LOCALESTR2, _LOCALESTR3, "");
		}
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
	function SetUITimeFmt($UI_Timefmt) {
		if ( isset($UI_Timefmt) ) { // Var Based
			$this->Timefmt = $UI_Timefmt;
		}else{ // Const based
			$this->Timefmt = _STRFTIMEFORMAT;
		}
	}
	// Sets HTML Content-Type charset from translation data.
	function SetUICharset($UI_Charset) {
		if ( isset($UI_Charset) ) { // Var Based
			$this->Charset = $UI_Charset;
		}else{ // Const based
			$this->Charset = _CHARSET;
		}
	}
	// Sets HTML Common Page Title from translation data.
	function SetUITitle($UI_Title) {
		if ( isset($UI_Title) ) { // Var Based
			$this->Title = $UI_Title;
		}else{ // Const based
			$this->Title = _TITLE;
		}
	}
	// Sets Authentication Data Item from translation data.
	function SetUIADItem($Item,$Value) {
		GLOBAL $Use_Auth_System;
		if ($Use_Auth_System == 1) { // Run only if Auth Sys is enabled.
			$Items = array ( 'DescUN', 'DescPW' );
			if (in_array($Item, $Items)) {
				if ( isset($Value) ) { // Var Based
					$this->ADA[$Item] = $Value;
				}else{ // Const based
					switch ($Item) {
						case 'DescUN';
							$this->ADA[$Item] = _FRMLOGIN;
							break;
						case 'DescPW';
							$this->ADA[$Item] = _FRMPWD;
							break;
						// @codeCoverageIgnoreStart
						// Should never execute.
						default;
							// Will need to add this message to the TD.
							trigger_error(
								"Invalid AD Set Request for: $Item.\n"
							);
						// @codeCoverageIgnoreEnd
					}
				}
			}else{
				// Will need to add this message to the TD.
				trigger_error("Invalid AD Set Request for: $Item.\n");
			}
		}
	}
	// Sets Common Phrase Item from translation data.
	function SetUICPItem($Item,$Value) {
		$Items = array (
			'SrcDesc', 'SrcName', 'DstDesc', 'DstName', 'SrcDst', 'Id'
		);
		if (in_array($Item, $Items)) {
			if ( isset($Value) ) { // Var Based
				$this->CPA[$Item] = $Value;
			}else{ // Const based
				switch ($Item) {
					case 'SrcDesc';
						$this->CPA[$Item] = _SOURCE;
						break;
					case 'SrcName';
						$this->CPA[$Item] = _SOURCENAME;
						break;
					case 'DstDesc';
						$this->CPA[$Item] = _DEST;
						break;
					case 'DstName';
						$this->CPA[$Item] = _DESTNAME;
						break;
					case 'SrcDst';
						$this->CPA[$Item] = _SORD;
						break;
					case 'Id';
						$this->CPA[$Item] = _ID;
						break;
					// @codeCoverageIgnoreStart
					// Should never execute.
					default;
						// Will need to add this message to the TD.
						trigger_error("Invalid CP Set Request for: $Item.\n");
					// @codeCoverageIgnoreEnd
				}
			}
		}else{
			// Will need to add this message to the TD.
			trigger_error("Invalid CP Set Request for: $Item.\n");
		}
	}
	// Sets Universal Action Item from translation data.
	function SetUIUAItem($Item,$Value) {
		$Items = array ( 'Edit', 'Delete' );
		if (in_array($Item, $Items)) {
			if ( isset($Value) ) { // Var Based
				$this->UAA[$Item] = $Value;
			}else{ // Const based
				switch ($Item) {
					case 'Edit';
						$this->UAA[$Item] = _EDIT;
						break;
					case 'Delete';
						$this->UAA[$Item] = _DELETE;
						break;
					// @codeCoverageIgnoreStart
					// Should never execute.
					default;
						// Will need to add this message to the TD.
						trigger_error("Invalid UA Set Request for: $Item.\n");
					// @codeCoverageIgnoreEnd
				}
			}
		}else{
			// Will need to add this message to the TD.
			trigger_error("Invalid UA Set Request for: $Item.\n");
		}
	}
}
?>
