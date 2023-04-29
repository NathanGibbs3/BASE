<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: routines to manipulate shared state (session information)
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

// Function: InitArray()
// @doc Defines and initializes a 1 or 2 dimensional array.
//
// @param $a      (in/out) array to initialize
// @param $dim1   number of elements of first dimension
// @param $dim2   number of elements of second dimension
// @param $value  default value
// @return True if array initialized, false if not initialized.
function InitArray(&$a, $dim1 = 1, $dim2 = 0, $value = NULL ){
	if ( !is_int($dim1) || !is_int($dim2) ){
		return false;
	}else{
		$a = array();
		// Are we 2 dimensional?
		if ( $dim2 == 0 ){ // No 1-dim
			for ( $i = 0; $i < $dim1; $i++ ){
				$a[$i] = $value;
			}
		}else{ // Yes 2-dim
			for ( $i = 0; $i < $dim1; $i++ ){
				for ( $j = 0; $j < $dim2; $j++ ){
					$a[$i][$j] = $value;
				}
			}
		}
		return true;
	}
}

// Function: CleanVariable()
// @doc Removes invalid characters/data from a variable based on a specified
//      mask of acceptable data or a list of explicit values.
//      Note: Both mask and explicit list can be used a a time.
//
// @param $item        variable to scrub
// @param $valid_data  mask of valid characters
// @param $exception   array with explicit values to match
// @return a sanitized version of the passed variable.
function CleanVariable( $item, $valid_data = '', $exception = '' ){
	GLOBAL $debug_mode;
	if ( !isset($item) ){ // Is variable set?
		return $item;
	}else{
		// If Array, recursively clean array elements. -- nikns
		if (is_array($item)) {
			foreach ($item as $key => $value){
				$item[$key] = CleanVariable($value, $valid_data, $exception);
			}
			return $item;
		}else{
			if ( $exception != '' ){
				// Is variable value in the exception list?
				if ( in_array($item, $exception) ){ // Exception Hit
					return $item;
				}
				if ( $valid_data == '' ){ // Exception Miss
					return ''; // No Valid Data.
				}
			}
			if ( $valid_data == '' ){
				return $item;
			}else{
				$regex_mask = '';
				if ( is_numeric($valid_data) ){ // Issue #157
					if ( ($valid_data & VAR_DIGIT) > 0 ){
						$regex_mask .= "0-9";
					}
					if ( ($valid_data & VAR_LETTER) > 0 ){
						$regex_mask .= "A-Za-z";
					}
					if ( ($valid_data & VAR_ULETTER) > 0 ){
						$regex_mask .= "A-Z";
					}
					if ( ($valid_data & VAR_LLETTER) > 0 ){
						$regex_mask .= "a-z";
					}
					if ( ($valid_data & VAR_ALPHA) > 0 ){
						$regex_mask .= "0-9A-Za-z";
					}
					if ( ($valid_data & VAR_SPACE) > 0 ){
						$regex_mask .= "\ ";
					}
					if ( ($valid_data & VAR_PERIOD) > 0 ){
						$regex_mask .= "\.";
					}
					if ( ($valid_data & VAR_FSLASH) > 0 ){
						$regex_mask .= "\/";
					}
					if ( ($valid_data & VAR_OPAREN) > 0 ){
						$regex_mask .= "\(";
					}
					if ( ($valid_data & VAR_CPAREN) > 0 ){
						$regex_mask .= "\)";
					}
					if ( ($valid_data & VAR_BOOLEAN) > 0 ){
						$regex_mask .= "=|&|\||!";
					}
					if ( ($valid_data & VAR_OPERATOR) > 0 ){
						$regex_mask .= "\+|\*|\/|=|>|<|&|\||%|!|\^|\(|\)|\-";
					}
					if ( ($valid_data & VAR_USCORE) > 0 ){
						$regex_mask .= "\_";
					}
					if ( ($valid_data & VAR_AT) > 0 ){
						$regex_mask .= "\@";
					}
					// Score (\-) always must be at the end of the RE mask.
					if ( ($valid_data & VAR_PUNC) > 0 ){
						$regex_mask .= "\~\!\#\$\%\^\&\*\_\=\+\:\;\,\.\?\ \(\))\-";
					}
					if ( ($valid_data & VAR_SCORE) > 0 ){
						$regex_mask .= "\-";
					}
				}
				if( $regex_mask != '' ){
					return preg_replace("/[^".$regex_mask."]/", '', $item);
				}else{
					if ( $debug_mode > 0 ){
						ErrorMessage(
							__FUNCTION__ .'(): Invalid Mask', '', 1
						);
					}
					return $item;
				}
			}
		}
	}
}

// Function: SetSessionVar()
// @doc Handles retrieving and updating persistant session (criteria) data.
// This routine handles the details of checking for criteria updates passed
// through POST/GET and resolving this with values that may already have been
// set and stored in the session.
// All criteria variables need invoke this function before they are used for
// the first time to extract their previously stored values, and process
// potential updates to their value.
// Note: Validation of user input is not performed by this routine.
//
// @param $var_name name of the persistant session variable to retrieve
// @return the updated value of the persistant session variable named by
// $var_name
//
function SetSessionVar($var_name){
	GLOBAL $BCR, $debug_mode;
	$UIM = 'Web'; // Default UI Mode.
	if ( isset($BCR) && is_object($BCR) ){
		$UIM = $BCR->GetCap('UIMode');
	}
	if ( isset($_POST[$var_name]) ){
		$msg = 'POST';
		$Ret = $_POST[$var_name];
	}else if ( isset($_GET[$var_name]) ){
		$msg = 'GET';
		$Ret = $_GET[$var_name];
	}elseif ( isset($_SESSION[$var_name]) ){
		$msg = 'SESSION';
		$Ret = $_SESSION[$var_name];
	}else{
		$msg = '';
		// This return value is a contributing factor to Issue(s) #5, #10, #54
		// & #55.
		// Leaving it at the moment, so as not to break things.
		$Ret = '';
	}
	if ( $debug_mode > 0 && $UIM == 'Web' && $msg != '' ){
		$EMPfx = __FUNCTION__ . "(): ";
		ErrorMessage(
			$EMPfx . "Importing $msg var '$var_name'", 'black', 1
		);
		if ( !is_array($Ret) ){ // Vars can contain arrays.
			ErrorMessage(
				$EMPfx . XSSPrintSafe("$var_name: $Ret"), 'black', 1
			);
		}
	}
	return $Ret;
}

/* ***********************************************************************
 * Function: ImportHTTPVar()
 *
 * @doc Handles retrieving temporary state variables needed to present a 
 *      given set of results (e.g., sort order, current record).  The
 *      values of these variables are never persistantly stored.  Rather,
 *      they are passed as HTTP POST and GET parameters.
 *
 *      All temporary variables need invoke this function before they are 
 *      used for the first time to extract their value.
 *
 *      Optionally, sanitization parameters can be set, ala CleanVariable()
 *      syntax to validate the user input.
 *     
 * @param $var_name     name of the temporary state variable to retrieve
 * @param $valid_data   (optional) list of valid character types 
 *                                 (see CleanVariable)
 * @param $exception    (optional) array of explicit values the imported
 *                      variable must be set to
 * 
 * @see CleanVariable
 *
 * @return the sanitized value of the temporary state variable named
 *         by $var_name
 *
 ************************************************************************/
function ImportHTTPVar( $var_name, $valid_data = '', $exception = '' ){
	GLOBAL $BCR, $debug_mode;
	$UIM = 'Web'; // Default UI Mode.
	if ( isset($BCR) && is_object($BCR) ){
		$UIM = $BCR->GetCap('UIMode');
	}
	$msg = '';
	$Ret = '';
	if ( isset($_POST[$var_name]) ){
		$msg = 'POST';
		$Ret = $_POST[$var_name];
	}elseif ( isset($_GET[$var_name]) ){
		$msg = 'GET';
		$Ret = $_GET[$var_name];
	}
	if ( $debug_mode > 0 && $UIM == 'Web' && $msg != '' ){
		$EMPfx = __FUNCTION__ . "(): ";
		ErrorMessage(
			$EMPfx . "Importing $msg var '$var_name'", 'black', 1
		);
		if ( !is_array($Ret) ){ // Vars can contain arrays.
			ErrorMessage(
				$EMPfx . XSSPrintSafe("$var_name: $Ret"),  'black', 1
			);
		}
	}
	$Ret = CleanVariable($Ret, $valid_data, $exception);
	return $Ret;
}

// Function: ExportHTTPVar()
// @doc Handles export of a temporary state variables needed to present a
//      given set of results (e.g., sort order, current record). This routine
//      creates a hidden HTML form variable.
//      Note: User is responsible for generating appropriate HTML form code.
//      Note: Sanitization of input is not performed by this routine.
//      Security Note: Only, temporary variables should make use of this
//                     function. These values are exposed in HTML to the user;
//                     who is free to modify them.
// @param $var_name    Name of the temporary state variable to export
// @param $var_value   Value of the temporary state variable
// @param $tab         Tab stops in output.
// @see ImportHTTPVar
// Returns true if var is exported, false otherwise.
function ExportHTTPVar ( $var_name = '', $var_value = '', $tab = 3 ){
	$Ret = false;
	if ( LoadedString( $var_name ) == true ){ // Input Validation
		if ( !is_int($tab) ){
			$tab = 3;
		}
		print returnExportHTTPVar ( $var_name, $var_value, $tab );
		$Ret = true;
	}
	return $Ret;
}

// Function: filterSql()
// @doc Filters the input string so that it can be safely used in SQL queries.
// @param $item            value of the variable to filter
// @param $force_alert_db  (default 0 - use current db)
// @return a sanitized version of the passed variable.
function filterSql ( $item, $force_alert_db=0, $db = ''){
	GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname,
	$alert_host, $alert_port, $alert_user, $alert_password;
	if ( !isset($item) ){ // Unset Value.
		return $item;
	}else{
		if ( is_array($item) ){ // Array.
			// Recursively convert array elements.
			// Works with both Keyed & NonKeyed arrays.
			foreach ($item as $key => $value) {
				$item[$key] = filterSql( $value, $force_alert_db );
			}
			return $item;
		}else{
			$Dbcf = 0; // DB Object creation Flag.
			if ( is_object($db) && get_class($db) == 'baseCon' ){
				$tdb = $db; // DB Onject passed.
			}else{
				$tdb = NewBASEDBConnection($DBlib_path, $DBtype);
				$Dbcf = 1; // DB Onject created.
				$tdb->baseDBConnect(
					$db_connect_method, $alert_dbname, $alert_host, $alert_port,
					$alert_user, $alert_password, $force_alert_db
				);
			}
			$version = explode('.', phpversion());
			if ( $version[0] > 5 || ($version[0] == 5 && $version[1] > 3) ){
				$Qh = 0;
			}else{ // Figure out quote handling on PHP < 5.4.
				$Qh = get_magic_quotes_runtime();
			}
			$item = $tdb->DB->qstr($item,$Qh);
			if ($Dbcf == 1 ){ // Close it, only if we created it.
				$tdb->baseClose();
			}
			// Cut off first and last character, (quotes added by qstr()).
			$item = substr($item, 1, strlen($item)-2);
			return $item;
		}
	}
}

?>
