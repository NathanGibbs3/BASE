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
** Purpose: Class to handle parsing variables into HTML template files for  
** output.
**
********************************************************************************
** Authors:
********************************************************************************
** Chris Shepherd <chsh@cogeco.ca> 
**
********************************************************************************
** Usage: 
** Inside the template, use the {} braces to denote either variable or special 
** function insertion. It will be placed as page friendly (strip trailing 
** newlines, etc) as possible. The name of the variable inside the braces should
** be passed into the array as an argument to the Template::get function. It 
** should directly correspond to the array key. 
** Example:
**  In the template:
**    <A HREF="{LINKURL}>{LINKTEXT}</A>
**
**  In the PHP file:
**    $array = array(
**      'LINKURL' => "http://www.google.ca",
**      'LINKTEXT' => "Google"
**    );
**    echo Template::get("link.htm", $array);
**
** The $_SERVER array keypairs are added to this array.
**
** You can also use functions in the templates:
**    <A HREF="{LINKURL}>{LINKTEXT}</A>
**    Source for this: 
**    {tl:Template::getRaw("link.htm");}
**
** When paired with the PHP example above, it will display the source of link.htm
** after parsing it. This can be used for any defined function, and is intended
** for situations where we are doing output inside a loop (based on database 
** queries, for example).
*/

//  Define template class constants.
define("TL_BASEPATH", 
  dirname(__FILE__)."/templates");      // Template directory. Template names will be 
                                        // appended to this.
define("TL_DEFAULT", "default");        // Default template name. This is for use with
                                        // "theming".
define("TL_VAROPEN", "{");              // Template variable open
define("TL_VARCLOSE", "}");             // Template variable close

//  Define Template filename constants.
define("TL_INFO", "info.php");          // Template information (Name, Version, etc)

// detect if the file_get_contents function exists. If it doesn't, use our own version.
if (! function_exists('file_get_contents'))
{
  // See if PEAR::PHP_Compat is installed
  @include 'PHP/Compat/Function/file_get_contents.php';
  // If it still doesn't exist, load our own.
  if (! function_exists('file_get_contents'))
  {
    function file_get_contents($filename)
    {
      if (file_exists($filename))
      {
        $buf = file($filename);
        $output = "";
        for ($i = 0; $i < sizeof($buf); $i++)
        {
          $output .= $buf[$i];
        }
        return $output;
      }
    }
  }
}

if (is_null($template)) 
{
  $template = TL_DEFAULT;
}  
$TPATH = TL_BASEPATH."/".$template;
// Detect if the directory exists;
if (!is_dir($TPATH)) {
  // Directory doesn't exist, set to default.
  $TPATH = TL_BASEPATH."/".TL_DEFAULT;
}   
class Template {
        
  // Returns a 'non-standard' template, ie: one which is not needed on all pages.
  function get($file, $vars=null)
  {
    return Template::parseTemplate($file, $vars);
  }
    
  // Returns the raw 'non-standard' template that is passed.
  function getRaw($file, $vars=null) 
  {
    return file_get_contents($TPATH."/$file");
  }  
  
  // Parses the template as a string, and returns
  // the string result.
  function parseTemplate($file, $vars=null) 
  {
    global $TPATH;
    // get contents of the $template file
    $template = file_get_contents($TPATH."/".$file);
    if (is_array($vars)) 
    {
      $vars = array_merge($vars, $_SERVER);
    } else {
      $vars = $_SERVER;
    }
    $start = 0;
    $end = 0;
    $retstring = "";
    $varname = "";
    $found = false;
    //echo htmlspecialchars($template);
    $size = strlen($template);
    for ($i=0; $i<=$size; $i++) 
    {
      $char = substr($template, $i, 1);
      if ($found == false) 
      {
        if ($char === '{') 
        {
          $found = true;
          $varname = "";
        } else {
          $retstring .= $char;
        }
      } else {
        if ($char === '}') 
        {
          $found = false;
          // now determine if we should call a function, or 
          // use a variable name
          if (preg_match("/^TL:/i", $varname))
          {
            // If matched, this should use the contents of a function
            $fcall = preg_replace("/^TL:/i", "", $varname);
            eval("\$tmp = $fcall");
            $retstring .= $tmp;
          } else {
            $retstring .= $vars[$varname];
          }
        } else {
          $varname .= $char; 
        }
      }
    }
    return $retstring;
  }
}
?>
