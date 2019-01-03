<?php


include_once ("base_conf.php");
include_once ("$BASE_path/includes/base_constants.inc.php");
include_once ("$BASE_path/includes/base_include.inc.php");


$rv = false;


// $dir = @$_GET['dir'];
$dir = $GLOBALS['external_sig_link']['local_rules_dir'][0];
$sid = @$_GET['sid'];

if ($debug_mode > 0)
{
	echo "dir = \"$dir\"<BR>\n";
	echo "sid = \"$sid\"<BR>\n";
}



function print_element($item, $key)
{
	echo "<BR><BR>\n\n-------\n" . htmlspecialchars($item) . "\n--------\n\n<BR><BR>";
}



function pcre_grep_file($file, $sid)
{
	if ($file == "")
	{
		echo "ERROR: file is empty.";
		return FALSE;
	}

	if ($sid == "")
	{
		echo "ERROR: sid is empty.";
		return FALSE;
	}



	# This pattern per se does work for rules which stretch over several lines.
	# However, it crashes php: Segmentation fault.
	$pattern = "/^(?:[ \t]*)(?:alert|log|drop)(?:.|\n)*sid:[ \t]*$sid(?:[ \t]*);(?:.|\n)*?[^\\\]$/ims";


	$lines = file_get_contents($file);
	$rv = preg_match($pattern, $lines, $matches);

	print_r($matches);
	#array_walk($matches, 'print_element');

	return $rv;
}



function pcre_grep_file_poor($file, $key, $sid)
{
	GLOBAL $debug_mode;
	$rv = FALSE;


	if ($file == "")
	{
		echo "ERROR: file is empty.";
		return FALSE;
	}

	if ($sid == "")
	{
		echo "ERROR: sid is empty.";
		return FALSE;
	}

	if (!is_readable($file))
	{
		echo "ERROR: \"" . htmlspecialchars($file) . "\" is not readable. Ignoring this file.\n<BR>";
		return FALSE;
	}



	$pattern = "/^(?:[ \t]*)(?:alert|log|drop).*?sid:[ \t]*$sid(?:[ \t]*);.*$/i";
	$return_value = false;

	$lines_array = file($file);

	if ($debug_mode > 0)
	{
		echo "file = \"" . htmlspecialchars($file) . "\", pattern = \"" . htmlspecialchars($pattern) . "\"\n<BR>";
	}

	while (list($key, $val) = each($lines_array)) 
	{

		$rv = preg_match($pattern, $val, $matches);
		if ($rv)
		{
			echo "<TH ALIGN=LEFT>" . htmlspecialchars($file) . ":</TH>\n";
			echo "<TR><TD>\n";
			while (list($k, $rule) = each($matches))
			{
				echo htmlspecialchars($rule);
				echo "\n";
			}
			echo "</TD></TR>\n";
			$return_value = true;
		}
	}


	return $return_value;
}



function search_dir($dir, $sid)
{
	GLOBAL $debug_mode;
	$rv = FALSE;


	if ($dir == "")
	{
		echo "ERROR: dir is empty.\n";
		return FALSE;
	}

	if ($sid == "")
	{
		echo "ERROR: sid is empty.\n";
		return FALSE;
	}

	if ($debug_mode > 1)
	{
		echo "In front of glob, with \$dir = " . htmlspecialchars($dir) . "\n<BR>";
	}


	foreach (glob($dir . DIRECTORY_SEPARATOR . "*") as $filename)
	{
		if ($debug_mode > 0)
		{
			echo "filename = " . htmlspecialchars($filename) . "\n";
		}

		if (filetype($filename) == "dir")
		{
			search_dir($filename, $sid);
		}
		else
		{
			if (is_readable($filename))
			{
				if (pcre_grep_file_poor($filename, "", $sid))
				{
					$rv = true;	

					if ($debug_mode > 0)
					{
						echo "Found\n<BR>";
					}
				}
			}
			else
			{
				echo "ERROR: \"" . htmlspecialchars($dir) ."/" . htmlspecialchars($filename) . "\" is not readable. Ignoring this file.";
			}
		}
	}

	return $rv;
}


############# main() ##############
echo "<HTML>\n";
echo "<TITLE></TITLE>\n";
echo "<BODY>\n";

if (file_exists($dir))
{
	if (is_executable($dir))
	{
		if (is_readable($dir))
		{
			echo "<H1>sid:" . htmlspecialchars($sid) . ";</H1>\n";

			if ($debug_mode > 0)
			{
				echo "Calling search_dir()...\n<BR>";
			}

			echo "<TABLE>\n";
			$rv = search_dir($dir, $sid);
			echo "</TABLE>\n";

			if ($rv)
			{
				if ($debug_mode)
				{
					echo "Ok. Found.\n<BR>";
				}
			}
			else
			{
				echo "ERROR: Could not find \"sig:" . htmlspecialchars($sid) . ";\" in directory \"". htmlspecialchars($dir) . "\".\n<BR>";
			}
		}
		else
		{
			echo "ERROR: Directory " . htmlspecialchars($dir) . " can not be searched. It must also be readable for the user the web server is running as. However, this is not required by the web server per se, but by the glob() command of php.\n<BR>";
		}
	}
	else
	{
		echo "ERROR: Directory \"" . htmlspecialchars($dir) . "\" can not be searched. It must be executable (required by the web server).\n<BR>";
	}
}
else
{
	echo "ERROR: Directory \"" . htmlspecialchars($dir) . "\" does not exist.\n<BR>";
}


echo "</BODY>";
echo "</HTML>";


?>
