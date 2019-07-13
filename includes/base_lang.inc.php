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
	var $Caps;
	var $ILocale;

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
		GLOBAL $BASE_path, $BASE_installID, $Use_Auth_System, $debug_mode;
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
		// Init Spacing & Capitalization.
		if ( isset($UI_Spacing) ) { // New TDF
			if ( !is_int($UI_Spacing) ) {
				$UI_Spacing = 1; // Default to 1 on invalid data.
			}
			$this->Spacing = $UI_Spacing;
			$this->Caps = $UI_Spacing;
		}else{ // Legacy TDF. Spacing Information does not exist.
			if ( preg_match("/(chi|japa)nese/", $UILang) ){
				$this->Spacing = 0;
				$this->Caps = 0;
			}else{
				$this->Spacing = 1;
				$this->Caps = 1;
			}
		}
		// Auto Generate Long/Short Month Names
		if ( !isset($UI_ILC) ){ // Legecy TDF
			// Compatibility Shimming
			$UI_IRC = '';
			if ( preg_match("/portuguese/", $this->Lang) ){
				$UI_ILC = 'pt';
				if ( preg_match("/portuguese-PT/", $this->Lang) ){
					$UI_IRC = 'PT';
				}else{
					$UI_IRC = 'BR';
				}
			}elseif (preg_match("/chinese/", $this->Lang) ){
				$UI_ILC = 'zh';
			}else{
				switch ($this->Lang) {
					case 'czech':
						$UI_ILC = 'cs';
						break;
					case 'danish':
						$UI_ILC = 'da';
						break;
					case 'english':
						$UI_ILC = 'en';
						break;
					case 'finnish':
						$UI_ILC = 'fi';
						break;
					case 'french':
						$UI_ILC = 'fr';
						break;
					case 'german':
						$UI_ILC = 'de';
						break;
					case 'indonesian':
						$UI_ILC = 'id';
						break;
					case 'italian':
						$UI_ILC = 'it';
						break;
					case 'japanese':
						$UI_ILC = 'ja';
						break;
					case 'norwegian':
						$UI_ILC = 'no';
						break;
					case 'polish':
						$UI_ILC = 'pl';
						break;
					case 'russian':
						$UI_ILC = 'ru';
						break;
					case 'spanish':
						$UI_ILC = 'es';
						break;
					case 'swedish':
						$UI_ILC = 'sv';
						break;
					case 'turkish':
						$UI_ILC = 'tr';
						break;
					default:
						$UI_ILC = 'en';
				}
			}
		}else{ // New TDF
			if ( strlen($UI_ILC) != 2 ) {
				$UI_ILC = 'en'; // Default to english on invalid data.
			}
			if ( isset($UI_IRC) ) {
				if ( strlen($UI_IRC) != 2 ) {
					$UI_IRC = 'US'; // Default to US on invalid data.
				}
			}else{
				$UI_IRC = '';
			}
		}
		$UI_ILC = strtolower($UI_ILC);
		$UI_IRC = strtoupper($UI_IRC);
		if ( isset($UI_Charset) ){ // Var New TDF
			$tcs = $UI_Charset;
		}elseif (defined('_CHARSET')) { // Const Legacy TDF
			$tcs = _CHARSET;
		}else{ // Default to UTF-8
			$tcs = 'utf-8';
		}
		$this->SetUICharset($tcs); // UI Content-Type charset.
		$loc = $UI_ILC.'_';
		if ( $UI_IRC != '' ){ // Add Region Code.;
			$loc .= "$UI_IRC.";
		}
		$loc .= $tcs;
		$this->ILocale = $loc; // Auto Generated Locale :-)
		if ( phpversion('intl') ){ // Is Intl available?
			// Set up Month format strings via Intl.
			$MfS = new IntlDateFormatter(
				$loc, IntlDateFormatter::NONE, IntlDateFormatter::NONE, NULL,
				NULL, "LLL"
			);
			$MfL = new IntlDateFormatter(
				$loc, IntlDateFormatter::NONE, IntlDateFormatter::NONE, NULL,
				NULL, "LLLL"
			);
			if ($MfS && $MfL){
				$Mgf = 'datefmt_format';
			}else{ // Not Auto Generating TD.
				$Mgf = NULL;
			}
		}else{
			$tmplocale = setlocale(LC_TIME, "0"); // Snapshot Locale.
			if ( setlocale(LC_TIME,$loc) ){
				// Set up Month format strings via Locale.
				$MfS = '%b';
				$MfL = '%B';
				$Mgf = 'strftime';
			}else{ // Not Auto Generating TD.
				$Mgf = NULL;
			}
			setlocale(LC_TIME, $tmplocale); // Put Locale back.
		}
		if ( isset($Mgf) ){
			for ($i = 1; $i < 13; $i++){
				$tts = mktime(0, 0, 0, $i);
				$this->SetUICWItem("ML$i",$Mgf($MfL,$tts)); // Set Long Month
				$this->SetUICWItem("MS$i",$Mgf($MfS,$tts)); // Set Short Month
			}
		}else{ // Fall back to TDF.
			$MLI = array (
				'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY',
				'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
			);
			for ($i = 1; $i < 13; $i++){
				$Idx = $i - 1;
				$MLk = 'ML'.$i;
				$MLv = 'UI_CW_'.$MLk;
				$MLc = '_'.$MLI[$Idx];
				print "Set CWA[$MLk] ";
				if ( isset($$MLv) ) { // Var New TDF
					print "Var: $$MLv Value: ".$$MLv."\n";
					$this->SetUICWItem($MLk,$$MLv);
				}elseif (defined($MLc)) { // Const Legacy TDF
					print "Const: $MLc Value: ".constant($MLc)."\n";
					$this->SetUICWItem($MLk,$MLc);
				}else{
					print "Error:\n";
					print "VarName: $MLv\n";
					print "Var: $$MLv Value: ".$$MLv."\n";
					print "Const: $MLc Value: ".constant($MLc)."\n";
					$this->SetUICWItem($MLk);
				}
			}
		}
		// Init Misc Items.
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
		if ( isset($UI_Title) ) { // Var New TDF
			$this->SetUITitle($UI_Title);
		}elseif (defined('_TITLE')) { // Const Legacy TDF
			$this->SetUITitle(_TITLE);
		}else{
			$this->SetUITitle();
		}
		// Init Universal Actions
		if ( isset($UI_CW_Edit) ) { // Var New TDF
			$this->SetUIUAItem('Edit',$UI_CW_Edit);
		}elseif (defined('_EDIT')) { // Const Legacy TDF
			$this->SetUIUAItem('Edit',_EDIT);
		}else{
			$this->SetUIUAItem('Edit');
		}
		if ( isset($UI_CW_Delete) ) { // Var New TDF
			$this->SetUIUAItem('Delete',$UI_CW_Delete);
		}elseif (defined('_DELETE')) { // Const Legacy TDF
			$this->SetUIUAItem('Delete',_DELETE);
		}else{
			$this->SetUIUAItem('Delete');
		}
		// Init Common Words
		if ( isset($UI_CW_Role) ) { // Var New TDF
			$this->SetUICWItem('Role',$UI_CW_Role);
		}elseif (defined('_FRMROLE')) { // Const Legacy TDF
			$tmp = preg_replace("/:$/","",_FRMROLE); // Strip trailing :.
			$this->SetUICWItem('Role',$tmp);
		}else{
			$this->SetUICWItem('Role');
		}
		if ( isset($UI_CW_Src) ) { // Var New TDF
			$this->SetUICWItem('Src',$UI_CW_Src);
		}elseif (defined('_SOURCE')) { // Const Legacy TDF
			$this->SetUICWItem('Src',_SOURCE);
		}else{
			$this->SetUICWItem('Src');
		}
		if ( isset($UI_CW_Dst) ) { // Var New TDF
			$this->SetUICWItem('Dst',$UI_CW_Dst);
		}elseif (defined('_DEST')) { // Const Legacy TDF
			$this->SetUICWItem('Dst',_DEST);
		}else{
			$this->SetUICWItem('Dst');
		}
		if ( isset($UI_CW_Id) ) { // Var New TDF
			$this->SetUICWItem('Id',$UI_CW_Id);
		}elseif (defined('_ID')) { // Const Legacy TDF
			$this->SetUICWItem('Id',_ID);
		}else{
			$this->SetUICWItem('Id');
		}
		if ( isset($UI_CW_Name) ) { // Var New TDF
			$this->SetUICWItem('Name',$UI_CW_Name);
		}elseif (defined('_NAME')) { // Const Legacy TDF
			$this->SetUICWItem('Name',_NAME);
		}else{
			$this->SetUICWItem('Name');
		}
		if ( isset($UI_CW_Int) ) { // Var New TDF
			$this->SetUICWItem('Int',$UI_CW_Int);
		}elseif (defined('_INTERFACE')) { // Const Legacy TDF
			$this->SetUICWItem('Int',_INTERFACE);
		}else{
			$this->SetUICWItem('Int');
		}
		if ( isset($UI_CW_Filter) ) { // Var New TDF
			$this->SetUICWItem('Filter',$UI_CW_Filter);
		}elseif (defined('_FILTER')) { // Const Legacy TDF
			$this->SetUICWItem('Filter',_FILTER);
		}else{
			$this->SetUICWItem('Filter');
		}
		if ( isset($UI_CW_Desc) ) { // Var New TDF
			$this->SetUICWItem('Desc',$UI_CW_Desc);
		}elseif (defined('_DESC')) { // Const Legacy TDF
			$this->SetUICWItem('Desc',_DESC);
		}else{
			$this->SetUICWItem('Desc');
		}
		if ( isset($UI_CW_SucDesc) ) { // Var New TDF
			$this->SetUICWItem('SucDesc',$UI_CW_SucDesc);
		}elseif (defined('_SUCCESS')) { // Const Legacy TDF
			$this->SetUICWItem('SucDesc',_SUCCESS);
		}else{
			$this->SetUICWItem('SucDesc');
		}
		if ( isset($UI_CW_Sensor) ) { // Var New TDF
			$this->SetUICWItem('Sensor',$UI_CW_Sensor);
		}elseif (defined('_SUCCESS')) { // Const Legacy TDF
			$this->SetUICWItem('Sensor',_SENSOR);
		}else{
			$this->SetUICWItem('Sensor');
		}
		if ( isset($UI_CW_Sig) ) { // Var New TDF
			$this->SetUICWItem('Sig',$UI_CW_Sig);
		}elseif (defined('_SIGNATURE')) { // Const Legacy TDF
			$this->SetUICWItem('Sig',_SIGNATURE);
		}else{
			$this->SetUICWItem('Sig');
		}
		if ( isset($UI_CW_Ts) ) { // Var New TDF
			$this->SetUICWItem('Ts',$UI_CW_Ts);
		}elseif (defined('_TIMESTAMP')) { // Const Legacy TDF
			$this->SetUICWItem('Ts',_TIMESTAMP);
		}else{
			$this->SetUICWItem('Ts');
		}
		if ( isset($UI_CW_Addr) ) { // Var New TDF
			$this->SetUICWItem('Addr',$UI_CW_Addr);
		}elseif (defined('_ADDRESS')) { // Const Legacy TDF
			$this->SetUICWItem('Addr',_ADDRESS);
		}else{
			$this->SetUICWItem('Addr');
		}
		if ( isset($UI_CW_Layer) ) { // Var New TDF
			$this->SetUICWItem('Layer',$UI_CW_Layer);
		}else{ // No Legacy TD Equivalent
			$this->SetUICWItem('Layer','');
		}
		if ( isset($UI_CW_Proto) ) { // Var New TDF
			$this->SetUICWItem('Proto',$UI_CW_Proto);
		}elseif (defined('_SIPLPROTO')) { // Const Legacy TDF
			$this->SetUICWItem('Proto',_SIPLPROTO);
		}else{
			$this->SetUICWItem('Proto');
		}
		if ( isset($UI_CW_Pri) ) { // Var New TDF
			$this->SetUICWItem('Pri',$UI_CW_Pri);
		}elseif (defined('_PRIORITY')) { // Const Legacy TDF
			$this->SetUICWItem('Pri',_PRIORITY);
		}else{
			$this->SetUICWItem('Pri');
		}
		if ( isset($UI_CW_Event) ) { // Var New TDF
			$this->SetUICWItem('Event',$UI_CW_Event);
		}else{ // No Legacy TD Equivalent
			$this->SetUICWItem('Event','');
		}
		if ( isset($UI_CW_Type) ) { // Var New TDF
			$this->SetUICWItem('Type',$UI_CW_Type);
		}elseif (defined('_TYPE')) { // Const Legacy TDF
			$this->SetUICWItem('Type',_TYPE);
		}else{
			$this->SetUICWItem('Type');
		}
		if ( isset($UI_CW_Last) ) { // Var New TDF
			$this->SetUICWItem('Last',$UI_CW_Last);
		}elseif (defined('_LAST')) { // Const Legacy TDF
			$this->SetUICWItem('Last',_LAST);
		}else{
			$this->SetUICWItem('Last');
		}
		// Init Common Phrases
		if ( isset($UI_CP_SrcName) ) { // Var New TDF
			$this->SetUICPItem('SrcName',$this->Phrase($UI_CP_SrcName));
		}elseif (defined('_SOURCENAME')) { // Const Legacy TDF
			$this->SetUICPItem('SrcName',_SOURCENAME);
		}else{
			$this->SetUICPItem('SrcName');
		}
		if ( isset($UI_CP_DstName) ) { // Var New TDF
			$this->SetUICPItem('DstName',$this->Phrase($UI_CP_DstName));
		}elseif (defined('_DESTNAME')) { // Const Legacy TDF
			$this->SetUICPItem('DstName',_DESTNAME);
		}else{
			$this->SetUICPItem('DstName');
		}
		if ( isset($UI_CP_SrcDst) ) { // Var New TDF
			$this->SetUICPItem('SrcDst',$this->Phrase($UI_CP_SrcDst));
		}elseif (defined('_SORD')) { // Const Legacy TDF
			$this->SetUICPItem('SrcDst',_SORD);
		}else{
			$this->SetUICPItem('SrcDst');
		}
		if ( isset($UI_CP_SrcAddr) ) { // Var New TDF
			$this->SetUICPItem('SrcAddr',$this->Phrase($UI_CP_SrcAddr,1));
		}elseif (defined('_NBSOURCEADDR')) { // Const Legacy TDF
			$this->SetUICPItem('SrcAddr',_NBSOURCEADDR);
		}else{
			$this->SetUICPItem('SrcAddr');
		}
		if ( isset($UI_CP_DstAddr) ) { // Var New TDF
			$this->SetUICPItem('DstAddr',$this->Phrase($UI_CP_DstAddr,1));
		}elseif (defined('_NBDESTADDR')) { // Const Legacy TDF
			$this->SetUICPItem('DstAddr',_NBDESTADDR);
		}else{
			$this->SetUICPItem('DstAddr');
		}
		if ( isset($UI_CP_L4P) ) { // Var New TDF
			$this->SetUICPItem('L4P',$this->Phrase($UI_CP_L4P,1));
		}elseif (defined('_NBLAYER4')) { // Const Legacy TDF
			$this->SetUICPItem('L4P',_NBLAYER4);
		}else{
			$this->SetUICPItem('L4P');
		}
		if ( isset($UI_CP_ET) ) { // Var New TDF
			$this->SetUICPItem('ET',$this->Phrase($UI_CP_ET));
		}elseif (defined('_EVENTTYPE')) { // Const Legacy TDF
			$this->SetUICPItem('ET',_EVENTTYPE);
		}else{
			$this->SetUICPItem('ET');
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
			if ( isset($UI_CW_Pw) ) { // Var New TDF
				$this->SetUIADItem('DescPW',$UI_CW_Pw);
			}elseif (defined('_FRMPWD')) { // Const Legacy TDF
				$this->SetUIADItem('DescPW',_FRMPWD);
			}else{
				$this->SetUIADItem('DescPW');
			}
			if ( isset($UI_AD_RID) ) { // Var New TDF
				$this->SetUIADItem('DescRI',$this->Phrase($UI_AD_RID));
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
		// $this->CPA Anti XXSed via Phrase() during construction.
		// Necessary for &nbsp; support.
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
		// Fallback options if TD doesn't work.
		// Auto generated Locale from class Init.
		array_push( $tmp, $this->ILocale );
		array_push( $tmp, ""); // Locale from Environemnt.
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
	// Set UI Content-Type charset from TD.
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
			'Src', 'Dst', 'Id', 'Name', 'Int', 'Filter', 'Desc', 'SucDesc',
			'Sensor', 'Sig', 'Ts', 'Role', 'Addr', 'Layer', 'Proto', 'Pri',
			'Event', 'Type', 'ML1', 'ML2', 'ML3', 'ML4', 'ML5', 'ML6', 'ML7',
			'ML8', 'ML9', 'ML10', 'ML11', 'ML12', 'MS1', 'MS2', 'MS3', 'MS4',
			'MS5', 'MS6', 'MS7', 'MS8', 'MS9', 'MS10', 'MS11', 'MS12', 'Last'
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
			'SrcName', 'DstName', 'SrcDst', 'SrcAddr', 'DstAddr', 'L4P', 'ET'
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
	// Phrase Construction.
	function Phrase($Words = array(), $Nbs = 0 ) {
		if ( !is_array($Words) ) {
			$Words = array($Words);
		}
		// Anti XSS the Data.
		$Words = XSSPrintSafe($Words);
		if ($this->Spacing == 1){
			if ($Nbs == 1){
				$glue = '&nbsp;';
			}else{
				$glue = ' ';
			}
		}else{
			$glue = '';
		}
		return implode($glue, $Words);
	}
}
?>
