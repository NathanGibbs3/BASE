<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Purpose: Displays the actual .GIF/.PNG/.TIFF image
**          of the chart
**
** Input GET/POST variables
**   - width: chart width
**   - height: chart width
**   - pmargin0-3: plot margins
**   - title: chart title
**   - xaxis_label: x-axis label
**   - yaxis_label: y-axis label
**   - xdata[][]: data and label array for the x-axis and the y-axis
**   - yaxis_scale: (boolean) 0: linear; 1: logarithmic
**   - rotate_xaxis_lbl: (boolean) rotate X-axis labels 90 degrees
**   - style: [bar|line|pie] chooses the style of the chart
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

  include ("base_conf.php");
  include ("$BASE_path/includes/base_constants.inc.php");
  include ("$BASE_path/includes/base_state_common.inc.php");
  include ("$BASE_path/base_graph_common.php");
  require_once('Image/Graph.php');
  
  

  // One more time: A workaround for the inability of PEAR::Image_Canvas-0.3.1
  // to deal with strings as x-axis labels in a proper way in the case
  // of a logarithmic y-axis.
  function replace_numbers($value)
  {
    GLOBAL $xdata, $debug_mode;

    if ($debug_mode > 1)
    {
      error_log(__FILE__ . ":" . __LINE__ . ": \$value = \"$value\"");
    }

    $str = $xdata[$value][0];
    return $str;
  }


  $xdata = $_SESSION['xdata'];
  $width = ImportHTTPVar("width", VAR_DIGIT);
  $height = ImportHTTPVar("height", VAR_DIGIT);
  $pmargin0 = ImportHTTPVar("pmargin0", VAR_DIGIT);
  $pmargin1 = ImportHTTPVar("pmargin1", VAR_DIGIT);
  $pmargin2 = ImportHTTPVar("pmargin2", VAR_DIGIT);
  $pmargin3 = ImportHTTPVar("pmargin3", VAR_DIGIT);
  $title = ImportHTTPVar("title", VAR_ALPHA | VAR_SPACE);
  $xaxis_label = ImportHTTPVar("xaxis_label", VAR_ALPHA | VAR_SPACE);
  $yaxis_label = ImportHTTPVar("yaxis_label", VAR_ALPHA | VAR_SPACE);
  $yaxis_scale = ImportHTTPVar("yaxis_scale", VAR_DIGIT);
  $xaxis_grid = ImportHTTPVar("xaxis_grid", VAR_DIGIT);
  $yaxis_grid = ImportHTTPVar("yaxis_grid", VAR_DIGIT);
  $rotate_xaxis_lbl = ImportHTTPVar("rotate_xaxis_lbl", VAR_DIGIT);
  $style = ImportHTTPVar("style", VAR_ALPHA);
  $chart_type = ImportHTTPVar("chart_type", VAR_DIGIT);
  // Do not disturb the generation of the png by whaffling to the screen
  $old_display_error_type = ini_get('display_errors');
  if (! empty($old_display_error_type))
  {
    ini_set("display_errors", "0");
  } 


  // Using the world map requires quite some memory. 100 MB should be
  // more than enough, I would have thought after some tests.  However,
  // even this amount of memory can be insufficient under certain
  // circumstances, which are not quite clear to me (bugs in PEAR::Image::
  // Graph? Or ::Canvas???).  So, let's try and ask for 256 MB:
  ini_set("memory_limit", "256M"); 


  if ($debug_mode > 1)
  {
    error_log(__FILE__ . ":" . __LINE__ . ": count(\$xdata) = " . count($xdata));
  }


  if ($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP || $chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP)
  {
    // Number of alerts spread over a worldmap: width and height
    // MUST be constant. At least as of Image_Graph-0.7.2
    // Otherwise the coordinates file must be regenerated. And this
    // is NOT possible during runtime (as of version 0.7.2)
    $Graph =& Image_Graph::factory('graph', array(1800, 913));
    //$Graph =& Image_Graph::factory('graph', array(600, 300));
  }
  elseif (($yaxis_scale == 1) && ($style != 'pie'))
  {
    // the old form of instantiation does not seem to work
    // any more with PEAR::Image_Canvas-0.3.1 with logarithmic
    // y-axes. So factory-method is required.
    $Graph =& Image_Graph::factory('graph', array($width, $height));
  }
  else
  { 
    // Create the graph area, legends on bottom -- Alejandro
    $Graph =& new Image_Graph(array('driver'=>'gd', 
                                    'width'=>$width,
                                    'height'=>$height));
  }

  
  if ($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP || $chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP)
  // then a worldmap is to be drawn.
  {
    $Graph->add(
      Image_Graph::vertical(
        Image_Graph::factory('title', array($title, 35)),
        Image_Graph::vertical(
          // create the plotarea
          $Plotarea = Image_Graph::factory('Image_Graph_Plotarea_Map', 'world_map6'),
          $Legend = Image_Graph::factory('legend'), // legend does not work, yet.
          90
        ),
        10
      )
    );
  }
  elseif ($yaxis_scale == 1)
  // then a logarithmic y axis has been requested
  {
    if ($style == "pie")
    // in this case we ignore logarithm
    {
      $Graph->add(
        Image_Graph::vertical(
          Image_Graph::factory('title', array($title, 16)),
          Image_Graph::horizontal(
            $Plotarea = Image_Graph::factory('plotarea'),
            $Legend = Image_Graph::factory('legend'),
            80
          ),
          10
        )
      );
    }
    else
    // bar, line
    {
      $Graph->add(
        Image_Graph::vertical(
          Image_Graph::factory('title', array($title, 16)),
          Image_Graph::vertical(
            $Plotarea = Image_Graph::factory('plotarea' , array('axis', 'axis_log')),
            $Legend = Image_Graph::factory('legend'),
            80 // 85 
          ),
          10
        )
      );
    }
  }
  else
  // linear y-axis 
  {
    if ($style == "pie")
    {
      $Graph->add(
        Image_Graph::vertical(
          Image_Graph::factory('title', array($title, 16)),
          Image_Graph::horizontal(
            $Plotarea = Image_Graph::factory('plotarea'),
            $Legend = Image_Graph::factory('legend'),
            80  // 85
          ),
          10
        )
      );
    }
    else
    // bar, line
    {
      $Graph->add(
        Image_Graph::vertical(
          Image_Graph::factory('title', array($title, 16)),
          Image_Graph::vertical(
            $Plotarea = Image_Graph::factory('plotarea'),
            $Legend = Image_Graph::factory('legend'),
            85
          ),
          10
        )
      );
    }
  }
  
  
  $rv = ini_get("safe_mode");
  if ($rv != 1)
  // normal mode
  {
		GLOBAL $graph_font_name, $debug_mode;


    /* Say, $graph_font_name is being set to "DejaVuSans".  This means, that the
       IMAGE_CANVAS_SYSTEM_FONT_PATH constant in Canvas.php must be set
       to the directory, where "DejaVuSans.ttf" can be found.
       For example: vim Canvas.php

        define('IMAGE_CANVAS_SYSTEM_FONT_PATH', '/usr/share/fonts/dejavu/');
    */

		

		if (!isset($graph_font_name))
		{
			// "Image_Graph_Font" used to be a fail-safe font name.  But even this
			// does not seem to work, any more, for php >= 5.3.
    	$graph_font_name = "";	
    }

		if ($debug_mode > 0)
		{
			error_log(__FILE__ . ":" . __LINE__ . ": \$graph_font_name = \"$graph_font_name\"");
		}

		$Font =& $Graph->addNew('font', $graph_font_name);

		
    if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
      // worldmap
    {
      $Font->setSize(8);
    }
    else
      // all the other chart types
    {
      $Font->setSize(8);
    }
    $Graph->setFont($Font);
  }
else
// safe_mode
{
  $Font =& $Graph->addNew('Image_Graph_Font');
  $Font->setSize(8); // has no effect!
  error_log(__FILE__ . ":" . __LINE__ . ": WARNING: safe_mode: Falling back to default font without the possibility to adjust any font sizes."); 
}


// Configure plotarea
if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
{
  //  PHP Fatal error:  Allowed memory size of 104857600 bytes exhausted (tried to allocate 37 bytes) in /usr/share/pear/Image/Canvas.php on line 179
  //  ini_set("memory_limit", "100M");
  //  $Legend->setPlotarea($Plotarea);
}
elseif($style == "pie") {
  $Legend->setPlotarea($Plotarea);
}
else
{
  $Plotarea->setAxisPadding(30, 'top');
  $Plotarea->setAxisPadding(30, 'bottom');
  $Plotarea->setAxisPadding(10, 'left');
  $Plotarea->setAxisPadding(10, 'right');
}

$AxisX =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
$AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);


if (($style != "pie") && ($chart_type != CHARTTYPE_SRC_COUNTRY_ON_MAP) && ($chart_type != CHARTTYPE_DST_COUNTRY_ON_MAP))
{
  // Arrows
  $AxisX->showArrow();
  $AxisY->showArrow();

  // Grid lines for y-axis requested?
  if ($yaxis_grid == 1)
  {
    $GridY =& $Plotarea->addNew('bar_grid', IMAGE_GRAPH_AXIS_Y);
    $GridY->setFillStyle(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'white', 'lightgrey')));
  }


  // Grid lines for x-axis requested?
  if ($xaxis_grid == 1)
  {
    $Plotarea->addNew('line_grid', true, IMAGE_GRAPH_AXIS_X);
  }
}

// Create the dataset -- Alejandro
$Dataset =& Image_Graph::factory('dataset'); 
  for ($i = 0; $i < count($xdata); $i++) {
    if ($debug_mode > 1)
    {
      error_log(__FILE__ . ":" . __LINE__ . ": " . $i . ": \"" . $xdata[$i][0] . "\" - " . $xdata[$i][1]);
    }

    if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
    {
      $tmp = $xdata[$i][0];
      $tmp_lower = strtolower($tmp);
      if ($debug_mode > 1)
      {
        error_log("to be looked up: '$tmp', '$tmp_lower' ###");
      }

      // special case '"I0" => "private network (rfc 1918)"' and
      // '"** (private network) " => "private network (rfc 1918)"'
      if (ereg("rfc 1918", $tmp, $substring) || (ereg("[*][*] \(private network\) ", $tmp_lower, $substring)))
      {
        $Dataset->addPoint("private network (rfc 1918)", $xdata[$i][1]);
      }
      // special case '?? (Not Found) ' => 'unknown'
      elseif(ereg("[?][?][ \t]+\(Not Found\)[ \t]*", $tmp, $substring))
      {
        $Dataset->addPoint("unknown", $xdata[$i][1]);
      }
      // anything inside parentheses, following a 2-letter TLD:
      elseif (ereg("^[-a-zA-Z0-9]{2}[ \t]\((.+)\)[ \t]*$", $tmp, $substring))
      {
        $Dataset->addPoint($substring[1], $xdata[$i][1]);
      }
      // anything after two-letter top level domain names and after one space or tab:
      elseif (ereg("[ \t]*[-a-zA-Z0-9]{2}[ \t]([-a-zA-Z0-9]+[-a-zA-Z0-9 ]*)", $tmp, $substring))
      {
        $Dataset->addPoint($substring[1], $xdata[$i][1]);
      }
      // two-letter top level domain names right at the beginning:
      elseif (ereg("[ \t]*([-a-zA-Z0-9]{2})[ \t]", $tmp_lower, $substring))
      {
        $Dataset->addPoint($substring[1], $xdata[$i][1]);
      }
      else
      {
        $Dataset->addPoint($tmp, $xdata[$i][1]);
      }
    }
    elseif (($yaxis_scale == 1) && ($style != 'pie'))
    // Logarithmic y-axis with PEAR::Image_Canvas-0.3.1 seems to be buggy:
    // It does not work with strings as x-axis labels. So a workaround
    // is necessary - once again.
    {
      $Dataset->addPoint($i, $xdata[$i][1]);
    }
    else
    {
      $Dataset->addPoint($xdata[$i][0], $xdata[$i][1]);
    }
  }
  $number_elements = $i;

  if ($debug_mode > 1)
  {
    error_log("number_elements = $number_elements");
  }

  // Design plot: Should it be a bar, line or a pie chart?
  if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
  {
    $Plot =& $Plotarea->addNew('Image_Graph_Plot_Dot', array(&$Dataset));
  }
  elseif ($style == "line")
  // then we correct this style and replace it by "area":
  {
    $Plot =& $Plotarea->addNew('area', $Dataset);
  }
  else
  {
    $Plot =& $Plotarea->addNew($style, $Dataset);  
  }

  
  // Labelling of the axes?
  if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
  {
    // Well, nothing to do here.
  }
  elseif ( $style == "pie" ) {
    // We don't need any axes
    $Plotarea->hideAxis();
    $Plot->explode(10);
  } else {

    if ($chart_type == CHARTTYPE_CLASSIFICATION)
    {
      $ArrayData =& Image_Graph::factory('Image_Graph_DataPreprocessor_Array',$xdata);

    }
    else
    {
      $ArrayData =& Image_Graph::factory('Image_Graph_DataPreprocessor_Array',$xdata[0]);
    }


    // Prepare x-axis labels
    $AxisX->setDataPreprocessor($ArrayData);    
     

    if ($debug_mode > 1)
    {
      error_log(__FILE__ . ":" . __LINE__ . ": \$yaxis_scale = \"$yaxis_scale\"");
    }

    // Part of that workaround for PEAR::Image_Canvas being unable to
    // deal with strings as x-axis lables in a proper way
    // xxx jl: Hmmm. What has $yaxis_scale to do with AxisX??? Dead code, anyway.
    if ($yaxis_scale == 1)
    {
      $AxisX->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Function', 'replace_numbers'));
    }


    // Should they be rotated by 90 degress?
    if ($rotate_xaxis_lbl == 1)
    {  
      // affects x-axis title and labels:
      $AxisX->setFontAngle('vertical');

      // x-axis title
      if ((isset($xaxis_label)) && (strlen($xaxis_label) > 0))
      {
        $AxisX->setTitle($xaxis_label, array('angle' => 0, 'size' => 10));
      }

      // x-axis labels:
      // Workaround according to 
      // http://pear.php.net/bugs/bug.php?id=8675
      $AxisX->setLabelOption('showOffset', true);

      switch($chart_type)
      {
        case CHARTTYPE_HOUR:
        {
          // For time labels:
          $AxisX->setLabelOption('offset', 200);
          break;
        } 
        case CHARTTYPE_DAY:
        case CHARTTYPE_WEEK:
        {
          // For days:
          $AxisX->setLabelOption('offset', 60);
          break;
        }
        case CHARTTYPE_MONTH:
        case CHARTTYPE_YEAR:
        {
          // For months:
          $AxisX->setLabelOption('offset', 40);
          break;
        }
        case CHARTTYPE_SRC_IP:
        case CHARTTYPE_DST_IP:
        {
          // for ip addresses:
          $AxisX->setLabelOption('offset', 90);
          break;
        }
        case CHARTTYPE_DST_UDP_PORT:
        case CHARTTYPE_DST_TCP_PORT:
        case CHARTTYPE_SRC_UDP_PORT:
        case CHARTTYPE_SRC_TCP_PORT:
        {
          // for port numbers
          $AxisX->setLabelOption('offset', 25);
          break;
        }
        case CHARTTYPE_CLASSIFICATION:
        {
          // for classifications
          $AxisX->setLabelOption('offset', 210);
          break;
        }
        case CHARTTYPE_SENSOR:
        {
          // for host names of sensors
          $AxisX->setLabelOption('offset', 90);
          break;
        }
        case CHARTTYPE_SRC_COUNTRY:
        case CHARTTYPE_SRC_COUNTRY_ON_MAP:
        case CHARTTYPE_DST_COUNTRY:
        case CHARTTYPE_DST_COUNTRY_ON_MAP:
        {
          // 2-letter contry name plus complete country name
          $AxisX->setLabelOption('offset', 110);
          break;
        }
        case CHARTTYPE_UNIQUE_SIGNATURE:
        {
          // for signature names vs. num of alerts
          $AxisX->setLabelOption('offset', 400);
          break;
        }
        default:
        {        
          $AxisX->setLabelOption('offset', 70);
        }
      }
    }
    else
    {
      // x-axis title if no rotation is required
      if ((isset($xaxis_label)) && (strlen($xaxis_label) > 0))
      {
        $AxisX->setTitle($xaxis_label, array('size' => 10));
      }
    }


    // Prepare y-axis title
    if ((isset($yaxis_label)) && (strlen($yaxis_label) > 0))
    {
      $AxisY->setTitle($yaxis_label, array('angle' => 90, 'size' => 10));
    }
  }


  // Set markers (small rectangular labels inside the plot)
  if ($chart_type == 15 || $chart_type == 17)
  {
    $Marker =& $Plot->setMarker(Image_Graph::factory('Image_Graph_Marker_Bubble'));
    $ValueMarker =& Image_Graph::factory('Image_Graph_Marker_Value', IMAGE_GRAPH_VALUE_X);
    // Image_Graph_Marker_Pointing_Angular or Image_Graph_Marker_Pointing_Radial? Both of them are not perfect.
    $Marker->setSecondaryMarker(Image_Graph::factory('Image_Graph_Marker_Pointing_Radial', array(40, &$ValueMarker)));
  }
  else
  {
    $Marker =& $Plot->addNew('Image_Graph_Marker_Value', IMAGE_GRAPH_PCT_Y_TOTAL);
    $PointingMarker =& $Plot->addNew('Image_Graph_Marker_Pointing_Angular', array(20, &$Marker));
    $Plot->setMarker($PointingMarker);    
    $Marker->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Formatted', '%0.1f%%'));
  }

  // background of the whole drawing board:  
  $Graph->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'silver@0.5', 'white')));

  $Graph->setBorderColor('black');
  $Graph->setPadding(10);

  // background of the plotarea only:
  if (($chart_type != 15) && ($chart_type != 17))
  {
    $Plotarea->setBackgroundColor('white');
  }
  else
  // worldmap:
  {
    $Plotarea->setFillColor('white');
    $FillArray2 =& Image_Graph::factory('Image_Graph_Fill_Array');
    $FillArray2->addColor('white');
    $Plotarea->setFillStyle($FillArray2);
  }
  $Plotarea->setBorderColor('black');
  $Plotarea->setPadding(20);
  $Plotarea->showShadow();


  // and now all the filling tasks (gradients and the like) of the plot:
  if (($chart_type == 15) || ($chart_type == 17))
  {
    // set a line color
    $Plot->setLineColor('gray');

    // set a standard fill style
    $FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
    $Marker->setFillStyle($FillArray);
    $FillArray->addColor('orange@0.5');
    $FillArray->addColor('green@0.5');
    $FillArray->addColor('blue@0.5');
    $FillArray->addColor('yellow@0.5');
    $FillArray->addColor('red@0.5');
    $FillArray->addColor('black@0.5');
  }
  elseif ($style == "bar")
  {
    $Plot->setFillStyle(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'white', 'red')));
  }
  elseif ($style == "line")
  {
    $Plot->setFillStyle(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'orange', 'lightyellow')));
  }
  elseif ($style == "pie")
  // colours are each time determined randomly:
  // TODO:
  // While each colour name is taken only once rather than twice
  // or multiple times, the colours for two different colour names
  // may appear on the screen as if they were identical. Some names
  // may also simply be aliases. This can only be solved by removing 
  // the corresponding colour names from this list:
  {
    $mycolors = "aliceblue aquamarine azure beige bisque black blanchedalmond blue blueviolet brown burlywood cadetblue chocolate coral cornflowerblue cornsilk crimson cyan darkcyan darkgoldenrod darkgray darkgreen darkkhaki darkmagenta darkolivegreen darkorange darkorchid darkred darksalmon darkseagreen darkslateblue darkslategray darkviolet deeppink deepskyblue dimgray dodgerblue firebrick forestgreen fuchsia gainsboro gold goldenrod gray green greenyellow honeydew hotpink indianred indigo khaki lavender lawngreen lemonchiffon lightblue lightcoral lightcyan lightgoldenrodyellow lightgreen lightgrey lightpink lightsalmon lightseagreen lightslategray lightsteelblue lightyellow lime limegreen linen magenta maroon mediumaquamarine mediumorchid mediumpurple mediumseagreen mediumslateblue mediumspringgreen mediumturquoise mediumvioletred mistyrose navy oldlace olive olivedrab orange orangered orchid palegoldenrod palegreen paleturquoise palevioletred papayawhip peru pink powderblue purple red rosybrown royalblue saddlebrown salmon sandybrown seagreen sienna silver skyblue slateblue slategray springgreen steelblue tan teal thistle tomato violet wheat white yellow yellowgreen";
    // removed: 
    // darkblue,
    // plum,
    // chartreuse, 
    // antiquewhite, blanchedalmond, navajowhite, moccasin, peachpuff
    // aqua, darkturquoise, lavenderblush, turquoise 
    // lightskyblue, mediumturquoise, paleturquoise
    // mediumblue, midnightblue
    // floralwhite, ghostwhite, ivory, mintcream, snow, whitesmoke, seashell
    $color_array = explode(" ", $mycolors);
    $num_colors = count($color_array);
    shuffle($color_array);
    $FillArray =& Image_Graph::factory('Image_Graph_Fill_Array');
    for ($n = 0, $array_index = 0; 
         $n < $number_elements; 
         $n++, $array_index++)
    {
      if ($array_index >= $num_colors)
      // then restart from the beginning
      {
        $array_index = 0;
      }
      $color_to_use = $color_array[$array_index];
      $FillArray->addNew('gradient', array(IMAGE_GRAPH_GRAD_RADIAL, 'white', $color_to_use));
    }

    // If there are a lot elements, we need some more space at the bottom:
    // (not really a good solution)
    /*
    if ($number_elements >= 10)
    {
      $Graph->setPadding(90);
    }
    */

    $Plot->setFillStyle($FillArray);
    $Plot->Radius = 2;
  }
  else
  {
    error_log("$style is an unsupported chart style");
  }



  // Show time! -- Alejandro
  if (version_compare(PHP_VERSION, "5.0.0", "<"))
  {
    $rv =& $Graph->done();
    if (PEAR::isError($rv)) 
    {
      error_log(__FILE__ . ":" . __LINE__ . ": ERROR: \$Graph->done() has failed.");
    }
  }
  else
  {
    try
    {
      // $error = 'Always throw this error (1)';
      // throw new Exception($error);

      $rv =& $Graph->done();
      if (PEAR::isError($rv)) 
      {
        error_log(__FILE__ . ":" . __LINE__ . ": ERROR: \$Graph->done() has failed.");
      }
    }
    catch (Exception $exc1)
    {
			$error = $exc1->getMessage();

      // Write the error message to apache's error log
			if (isset($error))
			{
      	error_log(__FILE__ . ":" . __LINE__ . ": ERROR: \$Graph->done() has failed: \"" . $error . "\"");
			}
			else
			{
				error_log(__FILE__ . ":" . __LINE__ . ": ERROR: \$Graph->done() has failed.");
			}


      // and try and write the error message in form of a png to the screen
      try 
      {
        // $error = 'Always throw this error (2)';
        // throw new Exception($error);

        $im = @imagecreate(600, 80);

        if (isset($im))
        {
          $background_color = imagecolorallocate($im, 255, 255, 255);
          $text_color = imagecolorallocate($im, 233, 14, 91);


          imagestring($im, 5, 10, 10, __FILE__ . ":" . __LINE__ . ":", $text_color);
          imagestring($im, 5, 10, 30, "Graph->done() has failed:", $text_color);
 
					if (isset($error))
					{
          	imagestring($im, 5, 10, 50, $error , $text_color);
					}
					else
					{
						imagestring($im, 5, 10, 50, "Unknown error." , $text_color);
					}


          imagepng($im);
          imagedestroy($im);
        }
      }
      catch (Exception $exc2)
      {
				$error = $exc2->getMessage();

				if (isset($error))
				{
        	error_log(__FILE__ . ":" . __LINE__ . ": ERROR: Creating the error png has ALSO failed: \"" . $error . "\"");
				}
				else
				{
					error_log(__FILE__ . ":" . __LINE__ . ": ERROR: Creating the error png has ALSO failed.");
				}
      } // try - catch
    } // try - catch 
  } // if (version_compare(PHP_VERSION, "5.0.0", "<"))


  if ($debug_mode > 0)
  {
    $peak_memory = number_format(memory_get_peak_usage(TRUE));
    error_log(__FILE__ . ":" . __LINE__ . ": peak_memory = $peak_memory bytes");
  }

  // Now, that the png has been drawn, we can allow the
  // old value, again.
  if (! empty($old_display_error_type))
  {
    ini_set("display_errors", $old_display_error_type);
  }
?>
