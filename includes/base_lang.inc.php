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
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}
	function UILang($UILang) { // PHP 4x constructor.
		GLOBAL $BASE_path, $BASE_installID;
		$TDF = "$BASE_path/languages/$UILang.lang.php";
		if (!file_exists($TDF)) {
//			trigger_error(
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
			}else{
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
	}
	// Sets locale from translation data or defaults to system locale.
	function SetUILocale() {
		if ( is_array($this->Locale) ) { // Var Based
			$Ret = setlocale (LC_TIME, $this->Locale, "");
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
	// ets HTML Common Page Title from translation data.
	function SetUITitle($UI_Title) {
		if ( isset($UI_Title) ) { // Var Based
			$this->Title = $UI_Title;
		}else{ // Const based
			$this->Title = _TITLE;
		}
	}
}
?>
