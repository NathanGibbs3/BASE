<?php

include_once ("base_conf.php");
include_once ("$BASE_path/includes/base_constants.inc.php");
include_once ("$BASE_path/includes/base_include.inc.php");

$rv = false;

if ( base_array_key_exists('external_sig_link',$GLOBALS) ){
	$dir = $GLOBALS['external_sig_link']['local_rules_dir'][0];
}else{
	$dir = 'rules/';
}
if ( isset($_GET['sid']) ){
	$sid = $_GET['sid'];
}
$ODir = XSSPrintSafe($dir);
$OSid = XSSPrintSafe($sid);
if ( $debug_mode > 0 ){
	ErrorMessage($ODir,'black',1);
	ErrorMessage($OSid,'black',1);
}

function print_element($item, $key)
{
	echo "<BR><BR>\n\n-------\n" . htmlspecialchars($item) . "\n--------\n\n<BR><BR>";
}

function pcre_grep_file( $file, $sid ){
	if ( !LoadedString($file) ){
		ErrorMessage($EMPfx ."file is empty.", 0, 1);
		return FALSE;
	}
	if ( !LoadedString($sid) ){
		ErrorMessage($EMPfx ."sid is empty.", 0, 1);
		return FALSE;
	}

	// This pattern per se does work for rules which stretch over several lines.
	// However, it crashes php: Segmentation fault.
	$pattern = "/^(?:[ \t]*)(?:alert|log|drop)(?:.|\n)*sid:[ \t]*$sid(?:[ \t]*);(?:.|\n)*?[^\\\]$/ims";

	$lines = file_get_contents($file);
	$rv = preg_match($pattern, $lines, $matches);

	print_r($matches);
	#array_walk($matches, 'print_element');

	return $rv;
}

function pcre_grep_file_poor($file, $key, $sid){
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ERROR: ';
	$rv = FALSE;
	if ( !LoadedString($file) ){
		ErrorMessage($EMPfx ."file is empty.", 0, 1);
		return FALSE;
	}
	if ( !LoadedString($sid) ){
		ErrorMessage($EMPfx ."sid is empty.", 0, 1);
		return FALSE;
	}
	$OFile = XSSPrintSafe($file);
	$tmp = ChkAccess($file);
	if ( $tmp != 1 ){
		$EMsg = $EMPfx . '"' . $OFile . '" not ';
		if ( $tmp == -1 ){
			$EMsg .= 'found';
		}elseif ( $tmp == -2 ){
			$EMsg .= 'readable';
		}
		$$EMsg .= '. Ignoring this file.';
		ErrorMessage($EMsg, 0, 1);
		return FALSE;
	}

	$pattern = "/^(?:[ \t]*)(?:alert|log|drop).*?sid:[ \t]*$sid(?:[ \t]*);.*$/i";
	$return_value = false;

	$lines_array = file($file);

	if ( $debug_mode > 0 ){
		echo "file = \"$OFile\", pattern = \"" . htmlspecialchars($pattern) . "\"\n<BR>";
	}
	foreach ( $lines_array as $val ){ // Issue #153
		$rv = preg_match($pattern, $val, $matches);
		if ( $rv ){
			echo "<TH ALIGN=LEFT>$OFile:</TH>\n";
			echo "<TR>\n";
			foreach ( $matches as $rule ){ // Issue #153
				echo '<td>'. XSSPrintSafe($rule) .'</td>';
			}
			echo "</TR>\n";
			$return_value = true;
		}
	}
	return $return_value;
}

function search_dir($dir, $sid){
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ERROR: ';
	$sc = DIRECTORY_SEPARATOR;
	$rv = FALSE;
	if ( !LoadedString($dir) ){
		ErrorMessage($EMPfx ."dir is empty.", 0, 1);
		return FALSE;
	}
	if ( !LoadedString($sid) ){
		ErrorMessage($EMPfx ."sid is empty.", 0, 1);
		return FALSE;
	}
	if ( $debug_mode > 1 ){
		echo "In front of glob, with \$dir = " . htmlspecialchars($dir) . "\n<BR>";
	}
	foreach ( glob($dir . $sc . "*") as $filename ){
		if ( $debug_mode > 0 ){
			echo "filename = " . htmlspecialchars($filename) . "\n";
		}
		if ( ChkAccess($filename,'d') == 1 ){
			search_dir($filename, $sid);
		}else{
			$tmp = ChkAccess($filename);
			if ( $tmp == 1 ){
				if ( pcre_grep_file_poor($filename, "", $sid) ){
					$rv = true;
					if ( $debug_mode > 0 ){
						echo "Found\n<BR>";
					}
					break;
				}
			}else{
				$EMsg = $EMPfx . '"' . XSSPrintSafe($filename) . '" not ';
				if ( $tmp == -1 ){
					$EMsg .= 'found';
				}elseif ( $tmp == -2 ){
					$EMsg .= 'readable';
				}
				$$EMsg .= '. Ignoring this file.';
				ErrorMessage($EMsg, 0, 1);
			}
		}
	}
	return $rv;
}

############# main() ##############
AuthorizedRole(10000);
PrintBASESubHeader('Local Rule Lookup');
if (file_exists($dir))
{
	if (is_executable($dir))
	{
		if ( is_readable($dir) ){
			echo "<H1>sid: $OSid</H1>\n";
			if ( $debug_mode > 0 ){
				ErrorMessage('Calling search_dir()...',0,1);
			}
			echo "<TABLE>\n";
			$rv = search_dir($dir, $sid);
			echo "</TABLE>\n";
			if ( $rv ){
				if ( $debug_mode ){
					echo "Ok. Found.\n<BR>";
				}
			}else{
				ErrorMessage("Sig Not found: \"sig: $OSid\" in directory \"$ODir\"."
				, 0, 1);
			}
		}else{
			echo "ERROR: Directory $ODir can not be searched. It must also be readable for the user the web server is running as. However, this is not required by the web server per se, but by the glob() command of php.\n<BR>";
		}
	}else{
		echo "ERROR: Directory \"$ODir\" can not be searched. It must be executable (required by the web server).\n<BR>";
	}
}else{
	echo "ERROR: Directory \"$ODir\" does not exist.\n<BR>";
}
PrintBASESubFooter();
?>
