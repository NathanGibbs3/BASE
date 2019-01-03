<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: individual criteria classes
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

class BaseCriteria
{
   var $criteria;
   var $export_name;

   var $db;
   var $cs;

   function BaseCriteria(&$db, &$cs, $name)
   { 
     $this->db =& $db;
     $this->cs =& $cs;

     $this->export_name = $name;
     $this->criteria = NULL;
   }

   function Init()
   { 
   }

   function Import()
   {
     /* imports criteria from POST, GET, or the session */
   }

   function Clear()
   {
     /* clears the criteria */
   }
 
   function Sanitize()
   {
     /* clean/validate the criteria */
   }

   function SanitizeElement()
   {
     /* clean/validate the criteria */
   }
 
   function PrintForm()
   {
     /* prints the HTML form to input the criteria */
   }

   function AddFormItem()
   {
     /* adding another item to the HTML form  */
   }

   function GetFormItemCnt()
   {
     /* returns the number of items in this form element  */
   }   

   function SetFormItemCnt()
   {
     /* sets the number of items in this form element */
   }
 
   function Set($value)
   {
     /* set the value of this criteria */
   }

   function Get()
   {
     /* returns the value of this criteria */
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
     /* generate human-readable description of this criteria */
   }

   function isEmpty()
   {
     /* returns if the criteria is empty */
   }
};

class SingleElementCriteria extends BaseCriteria
{
   function Import()
   {
      $this->criteria = SetSessionVar($this->export_name);

      $_SESSION[$this->export_name] = &$this->criteria;
   }

   function Sanitize()
   {
      $this->SanitizeElement(); 
   }

   function GetFormItemCnt()
   {
      return -1;
   }   
 
   function Set($value)
   {
      $this->criteria = $value;
   }

   function Get()
   {
      return $this->criteria;
   }
   function isEmpty()
   {
      if ( $this->criteria == "" )
         return true;
      else
         return false;
   }
};

class MultipleElementCriteria extends BaseCriteria
{
   var $element_cnt;
   var $criteria_cnt;
   var $valid_field_list = Array();

   function MultipleElementCriteria(&$db, &$cs, $export_name, $element_cnt, $field_list = Array() )
   {
	$tdb =& $db;
	$cs =& $cs;

      $this->BaseCriteria($tdb, $cs, $export_name);

      $this->element_cnt = $element_cnt;
      $this->criteria_cnt = 0;
      $this->valid_field_list = $field_list;
   }

   function Init()
   {
      InitArray($this->criteria, $GLOBALS['MAX_ROWS'], $this->element_cnt, "");
      $this->criteria_cnt = 1; 

      $_SESSION[$this->export_name."_cnt"] = &$this->criteria_cnt;
   }

   function Import()
   {
      $this->criteria = SetSessionVar($this->export_name);
      $this->criteria_cnt = SetSessionVar($this->export_name."_cnt");

      $_SESSION[$this->export_name] = &$this->criteria;
      $_SESSION[$this->export_name."_cnt"] = &$this->criteria_cnt;
   }

   function Sanitize()
   { 
      if ( in_array("criteria", array_keys(get_object_vars($this))) )
      {
         for($i=0; $i < $this->element_cnt; $i++)
         {
            if ( isset($this->criteria[$i]) )
               $this->SanitizeElement($i);
         }
      }
   }

   function SanitizeElement($i)
   {
   }

   function GetFormItemCnt()
   {
      return $this->criteria_cnt;
   }   

   function SetFormItemCnt($value)
   {
      $this->criteria_cnt = $value;
   }

   function AddFormItem(&$submit, $submit_value)
   {
	$this->criteria_cnt =& $this->criteria_cnt;
      AddCriteriaFormRow($submit, $submit_value, $this->criteria_cnt, $this->criteria, $this->element_cnt);
   }
 
   function Set($value)
   {
      $this->criteria = $value;
   }

   function Get()
   {
      return $this->criteria;
   }

   function isEmpty()
   {
      if ( $this->criteria_cnt == 0 )
         return true;
      else
         return false;
   }

   function PrintForm($field_list, $blank_field_string, $add_button_string)
   {
      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
		if (!is_array($this->criteria[$i]))
			$this->criteria = array();

         echo '    <SELECT NAME="'.htmlspecialchars($this->export_name).'['.$i.'][0]">';
         echo '      <OPTION VALUE=" " '.chk_select($this->criteria[$i][0]," ").'>__</OPTION>'; 
         echo '      <OPTION VALUE="(" '.chk_select($this->criteria[$i][0],"(").'>(</OPTION>';
         echo '    </SELECT>';

         echo '    <SELECT NAME="'.htmlspecialchars($this->export_name).'['.$i.'][1]">';
         echo '      <OPTION VALUE=" "      '.chk_select($this->criteria[$i][1]," ").'>'.$blank_field_string.'</OPTION>';
 
         reset($field_list);
         foreach( $field_list as $field_name => $field_human_name )
         {
            echo '   <OPTION VALUE="'.$field_name.'" '.chk_select($this->criteria[$i][1],$field_name).'>'.$field_human_name.'</OPTION>';
         }
         echo '    </SELECT>';

         echo '    <SELECT NAME="'.htmlspecialchars($this->export_name).'['.$i.'][2]">';
         echo '      <OPTION VALUE="="  '.chk_select($this->criteria[$i][2],"="). '>=</OPTION>';
         echo '      <OPTION VALUE="!=" '.chk_select($this->criteria[$i][2],"!=").'>!=</OPTION>';
         echo '      <OPTION VALUE="<"  '.chk_select($this->criteria[$i][2],"<"). '><</OPTION>';
         echo '      <OPTION VALUE="<=" '.chk_select($this->criteria[$i][2],"<=").'><=</OPTION>';
         echo '      <OPTION VALUE=">"  '.chk_select($this->criteria[$i][2],">"). '>></OPTION>';
         echo '      <OPTION VALUE=">=" '.chk_select($this->criteria[$i][2],">=").'>>=</OPTION>';
         echo '    </SELECT>';

         echo '    <INPUT TYPE="text" NAME="'.htmlspecialchars($this->export_name).'['.$i.'][3]" SIZE=5 VALUE="'.htmlspecialchars($this->criteria[$i][3]).'">';

         echo '    <SELECT NAME="'.htmlspecialchars($this->export_name).'['.$i.'][4]">';
         echo '      <OPTION VALUE=" " '.chk_select($this->criteria[$i][4]," ").'>__</OPTION';
         echo '      <OPTION VALUE="(" '.chk_select($this->criteria[$i][4],"(").'>(</OPTION>';
         echo '      <OPTION VALUE=")" '.chk_select($this->criteria[$i][4],")").'>)</OPTION>';
         echo '    </SELECT>';

         echo '    <SELECT NAME="'.htmlspecialchars($this->export_name).'['.$i.'][5]">';
         echo '      <OPTION VALUE=" "   '.chk_select($this->criteria[$i][5]," ").  '>__</OPTION>';
         echo '      <OPTION VALUE="OR" '.chk_select($this->criteria[$i][5],"OR").  '>'._OR.'</OPTION>';
         echo '      <OPTION VALUE="AND" '.chk_select($this->criteria[$i][5],"AND").'>'._AND.'</OPTION>';
         echo '    </SELECT>';
         if ( $i == $this->criteria_cnt-1 )
            echo '    <INPUT TYPE="submit" NAME="submit" VALUE="'.htmlspecialchars($add_button_string).'">';
         echo '<BR>';
      }
   }

   function Compact()
   {
      if ( $this->isEmpty() )
      {
         $this->criteria = "";
         $_SESSION[$this->export_name] = &$this->criteria; 
      }
   }
};

class ProtocolFieldCriteria extends MultipleElementCriteria
{
	function ProtocolFieldCriteria(&$db, &$cs, $export_name, $element_cnt, $field_list = Array() )
	{
		$tdb =& $db;
		$cs =& $cs;

		$this->MultipleElementCriteria($tdb, $cs, $export_name, $element_cnt, $field_list);

	}



   function SanitizeElement($i)
   { 
      // Make a copy of the element array
      $curArr = $this->criteria[$i];
      // Sanitize the element
      $this->criteria[$i][0] = @CleanVariable($curArr[0], VAR_OPAREN);
      $this->criteria[$i][1] = @CleanVariable($curArr[1], "", array_keys($this->valid_field_list));
      $this->criteria[$i][2] = @CleanVariable($curArr[2], "", array("=", "!=", "<", "<=", ">", ">="));
      $this->criteria[$i][3] = @CleanVariable($curArr[3], VAR_DIGIT);
      $this->criteria[$i][4] = @CleanVariable($curArr[4], VAR_OPAREN | VAR_CPAREN);
      $this->criteria[$i][5] = @CleanVariable($curArr[5], "", array("AND", "OR"));
      // Destroy the copy
      unset($curArr);
   }

   function Description($human_fields)
   {
      $tmp = "";
      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
	  if (is_array($this->criteria[$i]))
	      if ($this->criteria[$i][1] != " " && $this->criteria[$i][3] != "" )
		  $tmp = $tmp.$this->criteria[$i][0].$human_fields[($this->criteria[$i][1])].' '.
		      $this->criteria[$i][2].' '.$this->criteria[$i][3].$this->criteria[$i][4].' '.$this->criteria[$i][5];
      }
      if ( $tmp != "" )
         $tmp = $tmp.$this->cs->GetClearCriteriaString($this->export_name); 

      return $tmp;
   }
}




class SignatureCriteria extends SingleElementCriteria
{
/*
 * $sig[4]: stores signature
 *   - [0] : exactly, roughly
 *   - [1] : signature
 *   - [2] : =, !=
 *   - [3] : signature from signature list
 */

   var $sig_type;
   var $criteria = array(0 => '', 1 => '');

   function SignatureCriteria(&$db, &$cs, $export_name)
   {
	$tdb =& $db;
	$cs =& $cs;

      $this->BaseCriteria($tdb, $cs, $export_name);

      $this->sig_type = "";
   }

   function Init()
   {      
      InitArray($this->criteria, 4, 0, "");
      $this->sig_type = "";
   }

   function Import()
   {
      parent::Import();

      $this->sig_type = SetSessionVar("sig_type");

      $_SESSION['sig_type'] = &$this->sig_type;
   }

   function Clear()
   {
   }

   function SanitizeElement()
   {
      if (!isset($this->criteria[0]) || !isset($this->criteria[1])) {
          $this->criteria = array(0 => '', 1 => '');
      }

      $this->criteria[0] = CleanVariable(@$this->criteria[0], "", array(" ", "=", "LIKE"));
      $this->criteria[1] = filterSql(@$this->criteria[1]); /* signature name */
      $this->criteria[2] = CleanVariable(@$this->criteria[2], "", array("=", "!="));
      $this->criteria[3] = filterSql(@$this->criteria[3]); /* signature name from the signature list */
   }

   function PrintForm()
   {
      if (!@is_array($this->criteria))
        $this->criteria = array();

      echo '<SELECT NAME="sig[0]"><OPTION VALUE=" "  '.chk_select(@$this->criteria[0]," "). '>'._DISPSIG;    
      echo '                      <OPTION VALUE="="     '.chk_select(@$this->criteria[0],"="). '>'._SIGEXACTLY;
      echo '                      <OPTION VALUE="LIKE" '.chk_select(@$this->criteria[0],"LIKE").'>'._SIGROUGHLY.'</SELECT>';

      echo '<SELECT NAME="sig[2]"><OPTION VALUE="="  '.chk_select(@$this->criteria[2],"="). '>=';
      echo '                      <OPTION VALUE="!="     '.chk_select(@$this->criteria[2],"!="). '>!=';
      echo '</SELECT>';

      echo '<INPUT TYPE="text" NAME="sig[1]" SIZE=40 VALUE="'.htmlspecialchars(@$this->criteria[1]).'"><BR>';

      if ( $GLOBALS['use_sig_list'] > 0)
      {
         $temp_sql = "SELECT DISTINCT sig_name FROM signature";
         if ($GLOBALS['use_sig_list'] == 1)
         {
            $temp_sql = $temp_sql." WHERE sig_name NOT LIKE '%SPP\_%'";
         }

         $temp_sql = $temp_sql." ORDER BY sig_name";
         $tmp_result = $this->db->baseExecute($temp_sql);
         echo '<SELECT NAME="sig[3]"
                       onChange=\'PacketForm.elements[4].value =
                         this.options[this.selectedIndex].value;return true;\'>
                <OPTION VALUE="null" SELECTED>{ Select Signature from List }';

         if ($tmp_result)
         {
            while ( $myrow = $tmp_result->baseFetchRow() )
               echo '<OPTION VALUE="'.$myrow[0].'">'.$myrow[0];
            $tmp_result->baseFreeRows();
         }
         echo '</SELECT><BR>';
      }
      
   } 

   function ToSQL()
   {
   }

   function Description()
   {
      $tmp = $tmp_human = "";


      // First alternative: signature name is taken from the
      // signature list.  The user has clicked at a drop down menu for this
      if ( 
           (isset($this->criteria[0])) && ($this->criteria[0] != " ") && 
           (isset($this->criteria[3])) && ($this->criteria[3] != "" ) &&
           ($this->criteria[3] != "null") && ($this->criteria[3] != "NULL") &&
           ($this->criteria[3] != NULL)
         )
      {
        if ( $this->criteria[0] == '=' && $this->criteria[2] == '!=' )
           $tmp_human = '!=';
        else if ( $this->criteria[0] == '=' && $this->criteria[2] == '=' )
           $tmp_human = '=';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '!=' )
           $tmp_human = ' '._DOESNTCONTAIN.' ';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '=' )
           $tmp_human = ' '._CONTAINS.' ';

        $tmp = $tmp._SIGNATURE.' '.$tmp_human.' "';
        if ( ($this->db->baseGetDBversion() >= 100) && $this->sig_type == 1 )
          $tmp = $tmp.BuildSigByID($this->criteria[3], $this->db).'" '.$this->cs->GetClearCriteriaString($this->export_name);
        else
          $tmp = $tmp.htmlentities($this->criteria[3]).'"'.$this->cs->GetClearCriteriaString($this->export_name);

        $tmp = $tmp.'<BR>';
      }
      else
      // Second alternative: Signature is taken from a string that
      // has been typed in manually by the user:
      if ( (isset($this->criteria[0])) && ($this->criteria[0] != " ") && 
           (isset($this->criteria[1])) && ($this->criteria[1] != "") )
      {
        if ( $this->criteria[0] == '=' && $this->criteria[2] == '!=' )
           $tmp_human = '!=';
        else if ( $this->criteria[0] == '=' && $this->criteria[2] == '=' )
           $tmp_human = '=';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '!=' )
           $tmp_human = ' '._DOESNTCONTAIN.' ';
        else if ( $this->criteria[0] == 'LIKE' && $this->criteria[2] == '=' )
           $tmp_human = ' '._CONTAINS.' ';

        $tmp = $tmp._SIGNATURE.' '.$tmp_human.' "';
        if ( ($this->db->baseGetDBversion() >= 100) && $this->sig_type == 1 )
          $tmp = $tmp.BuildSigByID($this->criteria[1], $this->db).'" '.$this->cs->GetClearCriteriaString($this->export_name);
        else
          $tmp = $tmp.htmlentities($this->criteria[1]).'"'.$this->cs->GetClearCriteriaString($this->export_name);

        $tmp = $tmp.'<BR>';
      }
      
      return $tmp;
   }
};  /* SignatureCriteria */



class SignatureClassificationCriteria extends SingleElementCriteria
{
   function Init()
   {
     $this->criteria = "";
   }

   function Clear()
   {
    /* clears the criteria */
   }

   function SanitizeElement()
   {
      $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
   }

   function PrintForm()
   {
     if ( $this->db->baseGetDBversion() >= 103 )
     {

        echo '<SELECT NAME="sig_class">
              <OPTION VALUE=" " '.chk_select($this->criteria, " ").'>'._DISPANYCLASS.'
              <OPTION VALUE="null" '.chk_select($this->criteria, "null").'>-'._UNCLASS.'-';

        $temp_sql = "SELECT sig_class_id, sig_class_name FROM sig_class";
        $tmp_result = $this->db->baseExecute($temp_sql);
        if ( $tmp_result )
        {
           while ( $myrow = $tmp_result->baseFetchRow() )
            echo '<OPTION VALUE="'.$myrow[0].'" '.chk_select($this->criteria, $myrow[0]).'>'.
                  $myrow[1];

           $tmp_result->baseFreeRows();
        }
        echo '</SELECT>&nbsp;&nbsp';
     }     
   }

   function ToSQL()
   {
    /* convert this criteria to SQL */
   }

   function Description()
   {
      $tmp = "";

      if ( $this->db->baseGetDBversion() >= 103 )
      {
         if ( $this->criteria != " " && $this->criteria != "" )
         {
            if ( $this->criteria == "null")
               $tmp = $tmp._SIGCLASS.' = '.
                              '<I>'._UNCLASS.'</I><BR>';
            else
               $tmp = $tmp._SIGCLASS.' = '.
                              htmlentities(GetSigClassName($this->criteria, $this->db)).
                              $this->cs->GetClearCriteriaString($this->export_name).'<BR>';
         }
      }

      return $tmp;
   }
};  /* SignatureClassificationCriteria */

class SignaturePriorityCriteria extends SingleElementCriteria
{
   var $criteria = array();
   function Init()
   {
     $this->criteria = "";
   }

   function Clear()
   {
    /* clears the criteria */
   }

   function SanitizeElement()
   {
     if (!isset($this->criteria[0]) || !isset($this->criteria[1])) {
         $this->criteria = array(0 => '', 1 => '');
     }

      $this->criteria[0] = CleanVariable(@$this->criteria[0], "", array("=", "!=", "<", "<=", ">", ">="));
      $this->criteria[1] = CleanVariable(@$this->criteria[1], VAR_DIGIT);
   }

   function PrintForm()
   {
     if ( $this->db->baseGetDBversion() >= 103 )
     {
  		if (!@is_array($this->criteria))                 
			$this->criteria = array();

        echo '<SELECT NAME="sig_priority[0]">
                <OPTION VALUE=" " '.@chk_select($this->criteria[0],"="). '>__</OPTION>
                <OPTION VALUE="=" '.@chk_select($this->criteria[0],"=").'>==</OPTION>
                <OPTION VALUE="!=" '.@chk_select($this->criteria[0],"!=").'>!=</OPTION>
                <OPTION VALUE="<"  '.@chk_select($this->criteria[0],"<"). '><</OPTION>
                <OPTION VALUE=">"  '.@chk_select($this->criteria[0],">").'>></OPTION>
                <OPTION VALUE="<=" '.@chk_select($this->criteria[0],"><="). '><=</OPTION>
                <OPTION VALUE=">=" '.@chk_select($this->criteria[0],">=").'>>=</SELECT>';
 
        echo '<SELECT NAME="sig_priority[1]">
                <OPTION VALUE="" '.@chk_select($this->criteria[1], " ").'>'._DISPANYPRIO.'</OPTION>
 	        <OPTION VALUE="null" '.@chk_select($this->criteria[1], "null").'>-'._UNCLASS.'-</OPTION>';
        $temp_sql = "select DISTINCT sig_priority from signature ORDER BY sig_priority ASC ";
        $tmp_result = $this->db->baseExecute($temp_sql);
        if ( $tmp_result )
        {
           while ( $myrow = $tmp_result->baseFetchRow() )
             echo '<OPTION VALUE="'.$myrow[0].'" '.chk_select(@$this->criteria[1], $myrow[0]).'>'.
                   $myrow[0];
 
            $tmp_result->baseFreeRows();
        }
        echo '</SELECT>&nbsp;&nbsp';
      }     
    }
 
    function ToSQL()
    {
    /* convert this criteria to SQL */
    }
 
    function Description()
    {
       $tmp = "";
       if (!isset($this->criteria[1])) {
           $this->criteria = array(0 => '', 1 => '');
       }
 
       if ( $this->db->baseGetDBversion() >= 103 )
       {
          if ( $this->criteria[1] != " " && $this->criteria[1] != "" )
          {
             if ( $this->criteria[1] == null)
                $tmp = $tmp._SIGPRIO.' = '.
                               '<I>'._NONE.'</I><BR>';
             else
                $tmp = $tmp._SIGPRIO.' '.htmlentities($this->criteria[0])." ".htmlentities($this->criteria[1]).
                       $this->cs->GetClearCriteriaString($this->export_name).'<BR>';
          }
       }
 
       return $tmp;
    }
 };  /* SignaturePriorityCriteria */
 
class AlertGroupCriteria extends SingleElementCriteria
{
   function Init()
   {
      $this->criteria = "";
   }

   function Clear()
   {
    /* clears the criteria */
   }

   function SanitizeElement()
   {
      $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
   }

   function PrintForm()
   {

      echo '<SELECT NAME="ag">
             <OPTION VALUE=" " '.chk_select($this->criteria, " ").'>'._DISPANYAG;

      $temp_sql = "SELECT ag_id, ag_name FROM acid_ag";
      $tmp_result = $this->db->baseExecute($temp_sql);
      if ( $tmp_result )
      {
         while ( $myrow = $tmp_result->baseFetchRow() )
           echo '<OPTION VALUE="'.$myrow[0].'" '.chk_select($this->criteria, $myrow[0]).'>'.
                 '['.$myrow[0].'] '.htmlspecialchars($myrow[1]);

         $tmp_result->baseFreeRows();
      }
      echo '</SELECT>&nbsp;&nbsp;';
   }

   function ToSQL()
   {
    /* convert this criteria to SQL */
   }

   function Description()
   {
      $tmp = "";

      if ( $this->criteria != " " && $this->criteria != "" )
        $tmp = $tmp._ALERTGROUP.' = ['.htmlentities($this->criteria).'] '.GetAGNameByID($this->criteria, $this->db).
                    $this->cs->GetClearCriteriaString($this->export_name).'<BR>';

      return $tmp;
   }
};  /* AlertGroupCriteria */

class SensorCriteria extends SingleElementCriteria
{
   function Init()
   {
     $this->criteria = "";
   }

   function Clear()
   {
     /* clears the criteria */
   }
 
   function SanitizeElement()
   {
      $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
   }
 
   function PrintForm()
   {
			GLOBAL $debug_mode;


      // How many sensors do we have?
      $number_sensors = 0;
      $number_sensors_lst = $this->db->baseExecute("SELECT count(*) FROM sensor");
      $number_sensors_array = $number_sensors_lst->baseFetchRow();
      $number_sensors_lst->baseFreeRows();
      if (!isset($number_sensors_array))
      {
        $mystr = '<BR>' . __FILE__ . '' . __LINE__ . ": \$ERROR: number_sensors_array has not been set at all!<BR>";
        ErrorMessage($mystr);        
        $number_sensors = 0;
      }

      if ($number_sensors_array == NULL || $number_sensors_array == "")
      {
        $number_sensors = 0;
      }
      else
      {
        $number_sensors = $number_sensors_array[0];
      }

      if ($debug_mode > 1)
      {
        echo '$number_sensors = ' . $number_sensors . '<BR><BR>';
      }


      echo '<SELECT NAME="sensor">
             <OPTION VALUE=" " '.chk_select($this->criteria, " ").'>'._DISPANYSENSOR;

      $temp_sql = "SELECT sid, hostname, interface, filter FROM sensor";
      $tmp_result = $this->db->baseExecute($temp_sql);      

      
      for ($n = 0; $n < $number_sensors; $n++)
      {
        $myrow = $tmp_result->baseFetchRow();

        if (!isset($myrow) || $myrow == "" || $myrow == NULL)
        {
          if ($n >= $number_sensors)
          {
            break;
          }
          else
          {
            next;
          }
        }

        echo '<OPTION VALUE="' . $myrow[0] . '" ' .
             chk_select($this->criteria, $myrow[0]) . '>' .
             '[' . $myrow[0] . '] ' .
             GetSensorName($myrow[0], $this->db);
      }
      $tmp_result->baseFreeRows();

      echo '</SELECT>&nbsp;&nbsp';
   }
 
   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
     $tmp = "";

     if ( $this->criteria != " " && $this->criteria != "" )
        $tmp = $tmp._SENSOR.' = ['.htmlentities($this->criteria).'] '.
               GetSensorName($this->criteria, $this->db).
               $this->cs->GetClearCriteriaString($this->export_name).'<BR>';

      return $tmp;
   }
};  /* SensorCriteria */

class TimeCriteria extends MultipleElementCriteria
{
/*
 * $time[MAX][10]: stores the date/time of the packet detection
 *  - [][0] : (                           [][5] : hour  
 *  - [][1] : =, !=, <, <=, >, >=         [][6] : minute
 *  - [][2] : month                       [][7] : second
 *  - [][3] : day                         [][8] : (, )
 *  - [][4] : year                        [][9] : AND, OR
 *
 * $time_cnt : number of rows in the $time[][] structure
 */

   function Clear()
   {
     /* clears the criteria */
   }
 
   function SanitizeElement($i)
   {
      // Make copy of element array.
      $curArr = $this->criteria[$i];
      // Sanitize the element
      $this->criteria[$i][0] = @CleanVariable($curArr[0], VAR_OPAREN);
      $this->criteria[$i][1] = @CleanVariable($curArr[1], "", array("=", "!=", "<", "<=", ">", ">="));
      $this->criteria[$i][2] = @CleanVariable($curArr[2], VAR_DIGIT);
      $this->criteria[$i][3] = @CleanVariable($curArr[3], VAR_DIGIT);
      $this->criteria[$i][4] = @CleanVariable($curArr[4], VAR_DIGIT);
      $this->criteria[$i][5] = @CleanVariable($curArr[5], VAR_DIGIT);
      $this->criteria[$i][6] = @CleanVariable($curArr[6], VAR_DIGIT);
      $this->criteria[$i][7] = @CleanVariable($curArr[7], VAR_DIGIT);
      $this->criteria[$i][8] = @CleanVariable($curArr[8], VAR_OPAREN | VAR_CPAREN);
      $this->criteria[$i][9] = @CleanVariable($curArr[9], "", array("AND", "OR"));
      // Destroy the old copy
      unset($curArr);
   }
 
   function PrintForm()
   {
      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
		if (!@is_array($this->criteria[$i]))
			$this->criteria = array();

         echo '<SELECT NAME="time['.$i.'][0]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][0]," ").'>__'; 
         echo '                               <OPTION VALUE="("  '.chk_select(@$this->criteria[$i][0],"(").'>(</SELECT>';
         echo '<SELECT NAME="time['.$i.'][1]"><OPTION VALUE=" "  '.chk_select(@$this->criteria[$i][1]," "). '>'._DISPTIME;    
         echo '                               <OPTION VALUE="="  '.chk_select(@$this->criteria[$i][1],"="). '>=';
         echo '                               <OPTION VALUE="!=" '.chk_select(@$this->criteria[$i][1],"!=").'>!=';
         echo '                               <OPTION VALUE="<"  '.chk_select(@$this->criteria[$i][1],"<"). '><';
         echo '                               <OPTION VALUE="<=" '.chk_select(@$this->criteria[$i][1],"<=").'><=';
         echo '                               <OPTION VALUE=">"  '.chk_select(@$this->criteria[$i][1],">"). '>>';
         echo '                               <OPTION VALUE=">=" '.chk_select(@$this->criteria[$i][1],">=").'>>=</SELECT>';

         echo '<SELECT NAME="time['.$i.'][2]"><OPTION VALUE=" "  '.chk_select(@$this->criteria[$i][2]," " ).'>'._DISPMONTH;
         echo '                               <OPTION VALUE="01" '.chk_select(@$this->criteria[$i][2],"01").'>'._SHORTJAN;
         echo '                               <OPTION VALUE="02" '.chk_select(@$this->criteria[$i][2],"02").'>'._SHORTFEB;
         echo '                               <OPTION VALUE="03" '.chk_select(@$this->criteria[$i][2],"03").'>'._SHORTMAR;
         echo '                               <OPTION VALUE="04" '.chk_select(@$this->criteria[$i][2],"04").'>'._SHORTAPR;
         echo '                               <OPTION VALUE="05" '.chk_select(@$this->criteria[$i][2],"05").'>'._SHORTMAY;
         echo '                               <OPTION VALUE="06" '.chk_select(@$this->criteria[$i][2],"06").'>'._SHORTJUN;
         echo '                               <OPTION VALUE="07" '.chk_select(@$this->criteria[$i][2],"07").'>'._SHORTJLY;
         echo '                               <OPTION VALUE="08" '.chk_select(@$this->criteria[$i][2],"08").'>'._SHORTAUG;
         echo '                               <OPTION VALUE="09" '.chk_select(@$this->criteria[$i][2],"09").'>'._SHORTSEP;
         echo '                               <OPTION VALUE="10" '.chk_select(@$this->criteria[$i][2],"10").'>'._SHORTOCT;
         echo '                               <OPTION VALUE="11" '.chk_select(@$this->criteria[$i][2],"11").'>'._SHORTNOV;
         echo '                               <OPTION VALUE="12" '.chk_select(@$this->criteria[$i][2],"12").'>'._SHORTDEC.'</SELECT>';
         echo '<INPUT TYPE="text" NAME="time['.$i.'][3]" SIZE=2 VALUE="'.htmlspecialchars(@$this->criteria[$i][3]).'">';
         echo '<SELECT NAME="time['.$i.'][4]">'.dispYearOptions(@$this->criteria[$i][4]).'</SELECT>';

         echo '<INPUT TYPE="text" NAME="time['.$i.'][5]" SIZE=2 VALUE="'.htmlspecialchars(@$this->criteria[$i][5]).'"><B>:</B>';
         echo '<INPUT TYPE="text" NAME="time['.$i.'][6]" SIZE=2 VALUE="'.htmlspecialchars(@$this->criteria[$i][6]).'"><B>:</B>';
         echo '<INPUT TYPE="text" NAME="time['.$i.'][7]" SIZE=2 VALUE="'.htmlspecialchars(@$this->criteria[$i][7]).'">';

         echo '<SELECT NAME="time['.$i.'][8]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][8]," ").'>__';
         echo '                               <OPTION VALUE="(" '.chk_select(@$this->criteria[$i][8],"(").'>(';
         echo '                               <OPTION VALUE=")" '.chk_select(@$this->criteria[$i][8],")").'>)</SELECT>';
         echo '<SELECT NAME="time['.$i.'][9]"><OPTION VALUE=" "   '.chk_select(@$this->criteria[$i][9]," ").  '>__';
         echo '                               <OPTION VALUE="OR" '.chk_select(@$this->criteria[$i][9],"OR").  '>'._OR;
         echo '                               <OPTION VALUE="AND" '.chk_select(@$this->criteria[$i][9],"AND").'>'._AND.'</SELECT>';
       
         if ( $i == $this->criteria_cnt-1 )
            echo '    <INPUT TYPE="submit" NAME="submit" VALUE="'._ADDTIME.'">';
         echo '<BR>';
      }
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
     $tmp = "";
     for ($i = 0; $i < $this->criteria_cnt; $i++)
     {
         if ( isset($this->criteria[$i][1]) && $this->criteria[$i][1] != " " )
         { 
            $tmp = $tmp.'<CODE>'.htmlspecialchars($this->criteria[$i][0]).' time '.htmlspecialchars($this->criteria[$i][1]).' [ ';

            /* date */
            if ( $this->criteria[$i][2] == " " && $this->criteria[$i][3] == "" && $this->criteria[$i][4] == " " )
               $tmp = $tmp." </CODE><I>any date</I><CODE>";
            else
               $tmp = $tmp.(($this->criteria[$i][2] == " ") ? "* / " : $this->criteria[$i][2]." / ").
                           (($this->criteria[$i][3] == "" ) ? "* / " : $this->criteria[$i][3]." / ").
                           (($this->criteria[$i][4] == " ") ? "*  " : $this->criteria[$i][4]." "); 
            $tmp = $tmp.'] [ ';
            /* time */
            if ( $this->criteria[$i][5] == "" && $this->criteria[$i][6] == "" && $this->criteria[$i][7] == "" )
               $tmp = $tmp."</CODE><I>any time</I><CODE>";
            else
               $tmp = $tmp.(($this->criteria[$i][5] == "") ? "* : " : $this->criteria[$i][5]." : ").
                           (($this->criteria[$i][6] == "") ? "* : " : $this->criteria[$i][6]." : ").
                           (($this->criteria[$i][7] == "") ? "*  " : $this->criteria[$i][7]." "); 
            $tmp = $tmp.$this->criteria[$i][8].'] '.$this->criteria[$i][9];
            $tmp = $tmp.'</CODE><BR>';
         }             
     }
     if ( $tmp != "" )
       $tmp = $tmp.$this->cs->GetClearCriteriaString($this->export_name);

     return $tmp;
   }
};  /* TimeCriteria */

class IPAddressCriteria extends MultipleElementCriteria 
{
/*
 * $ip_addr[MAX][10]: stores an ip address parameters/operators row
 *  - [][0] : (                          [][5] : octet3 of address
 *  - [][1] : source, dest               [][6] : octet4 of address
 *  - [][2] : =, !=                      [][7] : network mask
 *  - [][3] : octet1 of address          [][8] : (, )
 *  - [][4] : octet2 of address          [][9] : AND, OR
 *
 * $ip_addr_cnt: number of rows in the $ip_addr[][] structure
 */

   function IPAddressCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::MultipleElementCriteria($tdb, $cs, $export_name, $element_cnt,
                                      array ("ip_src" => _SOURCE,
                                             "ip_dst" => _DEST,
                                             "ip_both" => _SORD));
   }

   function Import()
   {
      parent::Import();      

      /* expand IP into octets */
      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
        if ( (isset ($this->criteria[$i][3])) &&
             (ereg("([0-9]*)\.([0-9]*)\.([0-9]*)\.([0-9]*)", $this->criteria[$i][3])) )
        {
           $tmp_ip_str = $this->criteria[$i][7] = $this->criteria[$i][3];
           $this->criteria[$i][3] = strtok($tmp_ip_str, ".");
           $this->criteria[$i][4] = strtok(".");
           $this->criteria[$i][5] = strtok(".");
           $this->criteria[$i][6] = strtok("/");
           $this->criteria[$i][10] = strtok("");
        }
      } 

      $_SESSION['ip_addr'] = &$this->criteria;
      $_SESSION['ip_addr_cnt'] = &$this->criteria_cnt;
   }

   function Clear()
   {
     /* clears the criteria */
   }
 
   function SanitizeElement()
   { 
	$i = 0;
      // Make copy of old element array
      $curArr = $this->criteria[$i];
      // Sanitize element
      $this->criteria[$i][0] = @CleanVariable($curArr[0], VAR_OPAREN);
      $this->criteria[$i][1] = @CleanVariable($curArr[1], "", array_keys($this->valid_field_list));
      $this->criteria[$i][2] = @CleanVariable($curArr[2], "", array("=", "!=", "<", "<=", ">", ">="));
      $this->criteria[$i][3] = @CleanVariable($curArr[3], VAR_DIGIT);
      $this->criteria[$i][4] = @CleanVariable($curArr[4], VAR_DIGIT);
      $this->criteria[$i][5] = @CleanVariable($curArr[5], VAR_DIGIT);
      $this->criteria[$i][6] = @CleanVariable($curArr[6], VAR_DIGIT);
      $this->criteria[$i][7] = @CleanVariable($curArr[7], VAR_DIGIT | VAR_PERIOD | VAR_FSLASH);
      $this->criteria[$i][8] = @CleanVariable($curArr[8], VAR_OPAREN | VAR_CPAREN);
      $this->criteria[$i][9] = @CleanVariable($curArr[9], "", array("AND", "OR"));
      // Destroy copy
      unset($curArr);
   }
 
   function PrintForm()
   {
      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
		if (!is_array(@$this->criteria[$i]))
			$this->criteria = array();

         echo '    <SELECT NAME="ip_addr['.$i.'][0]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][0]," ").'>__'; 
         echo '                                      <OPTION VALUE="(" '.chk_select(@$this->criteria[$i][0],"(").'>(</SELECT>';
         echo '    <SELECT NAME="ip_addr['.$i.'][1]">
                    <OPTION VALUE=" "      '.chk_select(@$this->criteria[$i][1]," "     ).'>'._DISPADDRESS.'
                    <OPTION VALUE="ip_src" '.chk_select(@$this->criteria[$i][1],"ip_src").'>'._SHORTSOURCE.'
                    <OPTION VALUE="ip_dst" '.chk_select(@$this->criteria[$i][1],"ip_dst").'>'._SHORTDEST.'
                    <OPTION VALUE="ip_both" '.chk_select(@$this->criteria[$i][1],"ip_both").'>'._SHORTSOURCEORDEST.'
                   </SELECT>'; 
         echo '    <SELECT NAME="ip_addr['.$i.'][2]">
                    <OPTION VALUE="="  '.chk_select(@$this->criteria[$i][2],"="). '>=
                    <OPTION VALUE="!=" '.chk_select(@$this->criteria[$i][2],"!=").'>!=
                   </SELECT>';

        if ( $GLOBALS['ip_address_input'] == 2 )
           echo  '    <INPUT TYPE="text" NAME="ip_addr['.$i.'][3]" SIZE=16 VALUE="'.htmlspecialchars(@$this->criteria[$i][7]).'">';
        else
        {
           echo '    <INPUT TYPE="text" NAME="ip_addr['.$i.'][3]" SIZE=3 VALUE="'.htmlspecialchars(@$this->criteria[$i][3]).'"><B>.</B>';
           echo '    <INPUT TYPE="text" NAME="ip_addr['.$i.'][4]" SIZE=3 VALUE="'.htmlspecialchars(@$this->criteria[$i][4]).'"><B>.</B>';
           echo '    <INPUT TYPE="text" NAME="ip_addr['.$i.'][5]" SIZE=3 VALUE="'.htmlspecialchars(@$this->criteria[$i][5]).'"><B>.</B>';
           echo '    <INPUT TYPE="text" NAME="ip_addr['.$i.'][6]" SIZE=3 VALUE="'.htmlspecialchars(@$this->criteria[$i][6]).'"><!--<B>/</B>';
           echo '    <INPUT TYPE="text" NAME="ip_addr['.$i.'][7]" SIZE=3 VALUE="'.htmlspecialchars(@$this->criteria[$i][7]).'">-->'; 
        }
        echo '    <SELECT NAME="ip_addr['.$i.'][8]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][8]," ").'>__';
        echo '                                      <OPTION VALUE="(" '.chk_select(@$this->criteria[$i][8],"(").'>(';
        echo '                                      <OPTION VALUE=")" '.chk_select(@$this->criteria[$i][8],")").'>)</SELECT>';
        echo '    <SELECT NAME="ip_addr['.$i.'][9]"><OPTION VALUE=" "   '.chk_select(@$this->criteria[$i][9]," ").  '>__';
        echo '                                      <OPTION VALUE="OR" '.chk_select(@$this->criteria[$i][9],"OR").  '>'._OR;
        echo '                                      <OPTION VALUE="AND" '.chk_select(@$this->criteria[$i][9],"AND").'>'._AND.'</SELECT>';
        if ( $i == $this->criteria_cnt-1 )
          echo '    <INPUT TYPE="submit" NAME="submit" VALUE="'._ADDADDRESS.'">';
        echo '<BR>';
      }
   }
 
   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      $human_fields["ip_src"] = _SOURCE;
      $human_fields["ip_dst"] = _DEST;
      $human_fields["ip_both"] = _SORD;
      $human_fields[""] = ""; 
      $human_fields["LIKE"] = _CONTAINS;
      $human_fields["="] = "=";  

      $tmp2 = "";

      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
         $tmp = "";
         if ( isset($this->criteria[$i][3]) && $this->criteria[$i][3] != "" )
         {
            $tmp = $tmp.$this->criteria[$i][3];
            if ( $this->criteria[$i][4] != "" )
            {
               $tmp = $tmp.".".$this->criteria[$i][4];
               if ( $this->criteria[$i][5] != "" )
               {
                  $tmp = $tmp.".".$this->criteria[$i][5];
                  if ( $this->criteria[$i][6] != "" )
                  {
                     if ( ($this->criteria[$i][3].".".$this->criteria[$i][4].".".
                        $this->criteria[$i][5].".".$this->criteria[$i][6]) == NULL_IP)
                        $tmp = " unknown ";
                     else
                        $tmp = $tmp.".".$this->criteria[$i][6];
                  }
                  else
                     $tmp = $tmp.'.*';
               }
               else
                  $tmp = $tmp.'.*.*';
            }
            else
               $tmp = $tmp.'.*.*.*';
         }
         /* Make sure that the IP isn't blank */
         if ( $tmp != "" )
         {
            $mask = "";
            if ( $this->criteria[$i][10] != "" )
               $mask = "/".$this->criteria[$i][10];

             $tmp2 = $tmp2.$this->criteria[$i][0].
                     $human_fields[($this->criteria[$i][1])].' '.$this->criteria[$i][2].
                     ' '.$tmp.' '.$this->criteria[$i][8].' '.$this->criteria[$i][9].$mask.
                     $this->cs->GetClearCriteriaString($this->export_name)."<BR>";
         }
      }

      return $tmp2;
   }
};  /* IPAddressCriteria */

class IPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * $ip_field[MAX][6]: stores all other ip fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : TOS, TTL, ID, offset, length [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $ip_field_cnt: number of rows in the $ip_field[][] structure
 */ 

   function IPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array("ip_tos"  => "TOS",
                                          "ip_ttl"  => "TTL",
                                          "ip_id"   => "ID",
                                          "ip_off"  => "offset",
                                          "ip_csum" => "chksum",
                                          "ip_len"  => "length"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDIPFIELD);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      return parent::Description( array_merge( array ( "" => "", 
                                                       "LIKE" => _CONTAINS,
                                                       "=" => "="), $this->valid_field_list ) );  
   }
};

class TCPPortCriteria extends ProtocolFieldCriteria
{
/*
 * $tcp_port[MAX][6]: stores all port parameters/operators row
 *  - [][0] : (                            [][3] : port value
 *  - [][1] : Source Port, Dest Port       [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $tcp_port_cnt: number of rows in the $tcp_port[][] structure
 */ 

   function TCPPortCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("layer4_sport" => _SOURCEPORT,
                                           "layer4_dport" => _DESTPORT));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPPORT, _ADDTCPPORT);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      return parent::Description(array_merge( array("" => "",  
                                                    "=" => "="), $this->valid_field_list) );
   }
};  /* TCPPortCriteria */

class TCPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * TCP Variables
 * =============
 * $tcp_field[MAX][6]: stores all other tcp fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : windows, URP                 [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $tcp_field_cnt: number of rows in the $tcp_field[][] structure
 */

   function TCPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("tcp_win" => "window",  
                                           "tcp_urp" => "urp",
                                           "tcp_seq" => "seq #",
                                           "tcp_ack" => "ack",
                                           "tcp_off" => "offset",
                                           "tcp_res" => "res",
                                           "tcp_csum" => "chksum"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDTCPFIELD);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      return parent::Description(array_merge ( array("" => ""), $this->valid_field_list) );
   }
};  /* TCPFieldCriteria */

class TCPFlagsCriteria extends SingleElementCriteria
{
/*
 * $tcp_flags[7]: stores all other tcp flags parameters/operators row
 *  - [0] : is, contains                   [4] : 8     (RST)
 *  - [1] : 1   (FIN)                      [5] : 16    (ACK)
 *  - [2] : 2   (SYN)                      [6] : 32    (URG)
 *  - [3] : 4   (PUSH)
 */

   function Init()
   {
      InitArray($this->criteria, $GLOBALS['MAX_ROWS'], TCPFLAGS_CFCNT, ""); 
   }

   function Clear()
   {
     /* clears the criteria */
   }
 
   function SanitizeElement()
   {
      $this->criteria = CleanVariable($this->criteria, VAR_DIGIT);
   }
 
   function PrintForm()
   {
       		if (!is_array($this->criteria[0]))
			$this->criteria = array();

      echo '<TD><SELECT NAME="tcp_flags[0]"><OPTION VALUE=" " '.chk_select($this->criteria[0]," ").'>'._DISPFLAGS;
      echo '                              <OPTION VALUE="is" '.chk_select($this->criteria[0],"is").'>'._IS;
      echo '                              <OPTION VALUE="contains" '.chk_select($this->criteria[0],"contains").'>'._CONTAINS.'</SELECT>';
      echo '   <FONT>';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[8]" VALUE="128" '.chk_check($this->criteria[8],"128").'> [RSV1] &nbsp'; 
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[7]" VALUE="64"  '.chk_check($this->criteria[7],"64").'> [RSV0] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[6]" VALUE="32"  '.chk_check($this->criteria[6],"32").'> [URG] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[5]" VALUE="16"  '.chk_check($this->criteria[5],"16").'> [ACK] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[3]" VALUE="8"   '.chk_check($this->criteria[4],"8").'> [PSH] &nbsp'; 
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[4]" VALUE="4"   '.chk_check($this->criteria[3],"4").'> [RST] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[2]" VALUE="2"   '.chk_check($this->criteria[2],"2").'> [SYN] &nbsp';
      echo '    <INPUT TYPE="checkbox" NAME="tcp_flags[1]" VALUE="1"   '.chk_check($this->criteria[1],"1").'> [FIN] &nbsp';
      echo '  </FONT>';
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      $human_fields["1"] = "F";
      $human_fields["2"] = "S";
      $human_fields["4"] = "R";
      $human_fields["8"] = "P";
      $human_fields["16"] = "A";
      $human_fields["32"] = "U";
      $human_fields["64"] = "[R0]";
      $human_fields["128"] = "[R1]";
      $human_fields["LIKE"] = _CONTAINS;
      $human_fields["="] = "="; 

      $tmp = "";

      if ( isset($this->criteria[0]) && ($this->criteria[0] != " ") && ($this->criteria[0] != "") )
      {
         $tmp = $tmp.'flags '.$this->criteria[0].' ';
         for ( $i = 8; $i >=1; $i-- )
            if ( $this->criteria[$i] == "" )
               $tmp = $tmp.'-';
            else
               $tmp = $tmp.$human_fields[($this->criteria[$i])];

         $tmp = $tmp.$this->cs->GetClearCriteriaString("tcp_flags").'<BR>';
      }

      return $tmp;
   }

   function isEmpty()
   {
     if ( strlen($this->criteria) != 0 && ($this->criteria[0] != "") && ($this->criteria[0] != " ") )
        return false;
     else
        return true; 
   }
};  /* TCPFlagCriteria */

class UDPPortCriteria extends ProtocolFieldCriteria
{
/*
 * $udp_port[MAX][6]: stores all port parameters/operators row
 *  - [][0] : (                            [][3] : port value
 *  - [][1] : Source Port, Dest Port       [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $udp_port_cnt: number of rows in the $udp_port[][] structure
 */

   function UDPPortCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt,
                                    array ("layer4_sport" => _SOURCEPORT,
                                           "layer4_dport" => _DESTPORT));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPPORT, _ADDUDPPORT);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      return parent::Description(array_merge( array("" => "",  
                                                    "=" => "="), $this->valid_field_list) );
   }
};  /* UDPPortCriteria */

class UDPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * $udp_field[MAX][6]: stores all other udp fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : length                       [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $udp_field_cnt: number of rows in the $udp_field[][] structure
 */

   function UDPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt, 
                                    array ("udp_len" => "length",
                                           "udp_csum" => "chksum"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDUDPFIELD);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      return parent::Description(array_merge ( array("" => ""), $this->valid_field_list) );
   }
};  /* UDPFieldCriteria */

class ICMPFieldCriteria extends ProtocolFieldCriteria
{
/*
 * $icmp_field[MAX][6]: stores all other icmp fields parameters/operators row
 *  - [][0] : (                            [][3] : field value
 *  - [][1] : code, length                 [][4] : (, )
 *  - [][2] : =, !=, <, <=, >, >=          [][5] : AND, OR
 *
 * $icmp_field_cnt: number of rows in the $icmp_field[][] structure
 */ 

   function ICMPFieldCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::ProtocolFieldCriteria($tdb, $cs, $export_name, $element_cnt, 
                                    array ("icmp_type" => "type",
                                           "icmp_code" => "code",
                                           "icmp_id"   => "id",
                                           "icmp_seq"  => "seq #",
                                           "icmp_csum" => "chksum"));
   }

   function PrintForm()
   {
      parent::PrintForm($this->valid_field_list, _DISPFIELD, _ADDICMPFIELD);
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      return parent::Description(array_merge ( array("" => ""), $this->valid_field_list) );
   }
};  /* ICMPFieldCriteria */

class Layer4Criteria extends SingleElementCriteria
{
   function Init()
   {
      $this->criteria = "";
   }

   function Clear()
   {
     /* clears the criteria */
   }
 
   function SanitizeElement()
   {
      $this->criteria = CleanVariable($this->criteria, "", array("UDP", "TCP", "ICMP", "RawIP"));
   }
 
   function PrintForm()
   {
      if ( $this->criteria != "" )
         echo '<INPUT TYPE="submit" NAME="submit" VALUE="'._NOLAYER4.'"> &nbsp';
      if ( $this->criteria == "TCP" )
         echo '  
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
      else if ( $this->criteria == "UDP" )
         echo '  
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
      else if ( $this->criteria == "ICMP" )
         echo '  
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP">';
      else
         echo '  
           <INPUT TYPE="submit" NAME="submit" VALUE="TCP"> &nbsp
           <INPUT TYPE="submit" NAME="submit" VALUE="UDP">
           <INPUT TYPE="submit" NAME="submit" VALUE="ICMP">';
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      if ( $this->criteria == "TCP" )
         return _QCTCPCRIT;
      else if ( $this->criteria == "UDP" )
         return _QCUDPCRIT;
      else if ( $this->criteria == "ICMP" )
         return _QCICMPCRIT ;
      else
         return _QCLAYER4CRIT;
   }
};  /* Layer4Criteria */

class DataCriteria extends MultipleElementCriteria 
{
/*
 * $data_encode[2]: how the payload should be interpreted and converted
 *  - [0] : encoding type (hex, ascii)
 *  - [1] : conversion type (hex, ascii) 
 *
 * $data[MAX][5]: stores all the payload related parameters/operators row
 *  - [][0] : (                            [][3] : (, )
 *  - [][1] : =, !=                        [][4] : AND, OR
 *  - [][2] : field value
 *
 * $data_cnt: number of rows in the $data[][] structure
 */

   var $data_encode;

   function DataCriteria(&$db, &$cs, $export_name, $element_cnt)
   {
	$tdb =& $db;
	$cs =& $cs;

      parent::MultipleElementCriteria($tdb, $cs, $export_name, $element_cnt,
                                      array ("LIKE" => _HAS,
                                             "NOT LIKE" => _HASNOT ));
      $this->data_encode = array();
   }

   function Init()
   {
      parent::Init();
      InitArray($this->data_encode, 2, 0, "");
   }

   function Import()
   {
      parent::Import();

      $this->data_encode = SetSessionVar("data_encode");

      $_SESSION['data_encode'] = &$this->data_encode;
  }

   function Clear()
   {
     /* clears the criteria */
   }
 
   function SanitizeElement($i)
   {
      $this->data_encode[0] = CleanVariable($this->data_encode[0], "", array("hex", "ascii"));
      $this->data_encode[1] = CleanVariable($this->data_encode[1], "", array("hex", "ascii"));
      // Make a copy of the element array
      $curArr = $this->criteria[$i];
      // Sanitize the array
      $this->criteria[$i][0] = CleanVariable($curArr[0], VAR_OPAREN);
      $this->criteria[$i][1] = CleanVariable($curArr[1], "", array_keys($this->valid_field_list));
      $this->criteria[$i][2] = CleanVariable($curArr[2], VAR_FSLASH | VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER );
      $this->criteria[$i][3] = CleanVariable($curArr[3], VAR_OPAREN | VAR_CPAREN);
      $this->criteria[$i][4] = CleanVariable($curArr[4], "", array("AND", "OR"));
      // Destroy the copy
      unset($curArr);
   }
 
   function PrintForm()
   {
	            if (!is_array(@$this->criteria[0]))  
			$this->criteria = array();

      echo '<B>'._INPUTCRTENC.':</B>';
      echo '<SELECT NAME="data_encode[0]"><OPTION VALUE=" "    '.@chk_select($this->data_encode[0]," ").'>'._DISPENCODING; 
      echo '                              <OPTION VALUE="hex"  '.@chk_select($this->data_encode[0],"hex").'>hex';
      echo '                              <OPTION VALUE="ascii"'.@chk_select($this->data_encode[0],"ascii").'>ascii</SELECT>';
      echo '<B>'._CONVERT2WS.':</B>';
      echo '<SELECT NAME="data_encode[1]"><OPTION VALUE=" "    '.@chk_select(@$this->data_encode[1]," ").'>'._DISPCONVERT2; 
      echo '                              <OPTION VALUE="hex"  '.@chk_select(@$this->data_encode[1],"hex").'>hex';
      echo '                              <OPTION VALUE="ascii"'.@chk_select(@$this->data_encode[1],"ascii").'>ascii</SELECT>';
      echo '<BR>';

      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
         echo '<SELECT NAME="data['.$i.'][0]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][0]," ").'>__'; 
         echo '                               <OPTION VALUE="("  '.chk_select(@$this->criteria[$i][0],"(").'>(</SELECT>';
         echo '<SELECT NAME="data['.$i.'][1]"><OPTION VALUE=" "  '.chk_select(@$this->criteria[$i][1]," "). '>'._DISPPAYLOAD;    
         echo '                               <OPTION VALUE="LIKE"     '.chk_select(@$this->criteria[$i][1],"LIKE"). '>'._HAS;
         echo '                               <OPTION VALUE="NOT LIKE" '.chk_select(@$this->criteria[$i][1],"NOT LIKE").'>'._HASNOT.'</SELECT>';

         echo '<INPUT TYPE="text" NAME="data['.$i.'][2]" SIZE=45 VALUE="'.htmlspecialchars(@$this->criteria[$i][2]).'">';

         echo '<SELECT NAME="data['.$i.'][3]"><OPTION VALUE=" " '.chk_select(@$this->criteria[$i][3]," ").'>__';
         echo '                               <OPTION VALUE="(" '.chk_select(@$this->criteria[$i][3],"(").'>(';
         echo '                               <OPTION VALUE=")" '.chk_select(@$this->criteria[$i][3],")").'>)</SELECT>';
         echo '<SELECT NAME="data['.$i.'][4]"><OPTION VALUE=" "   '.chk_select(@$this->criteria[$i][4]," ").  '>__';
         echo '                               <OPTION VALUE="OR" '.chk_select(@$this->criteria[$i][4],"OR").  '>'._OR;
         echo '                               <OPTION VALUE="AND" '.chk_select(@$this->criteria[$i][4],"AND").'>'._AND.'</SELECT>';

         if ( $i == $this->criteria_cnt-1 )
            echo '    <INPUT TYPE="submit" NAME="submit" VALUE="'._ADDPAYLOAD.'">';
         echo '<BR>';
      }
   }

   function ToSQL()
   {
     /* convert this criteria to SQL */
   }
 
   function Description()
   {
      $human_fields["LIKE"] = _CONTAINS;
      $human_fields["NOT LIKE"] = _DOESNTCONTAIN;
      $human_fields[""] = ""; 

      $tmp = "";

      if ( $this->data_encode[0] != " " && $this->data_encode[1] != " ")
      {
          $tmp = $tmp.' ('._DENCODED.' '.$this->data_encode[0];
          $tmp = $tmp.' => '.$this->data_encode[1];
          $tmp = $tmp.')<BR>';
      }
      else
          $tmp = $tmp.' '._NODENCODED.'<BR>';

      for ( $i = 0; $i < $this->criteria_cnt; $i++ )
      {
         if ($this->criteria[$i][1] != " " && $this->criteria[$i][2] != "" )
            $tmp = $tmp.$this->criteria[$i][0].$human_fields[$this->criteria[$i][1]].' "'.$this->criteria[$i][2].
                             '" '.$this->criteria[$i][3].' '.$this->criteria[$i][4];
      }
       
      if ( $tmp != "" )
         $tmp = $tmp.$this->cs->GetClearCriteriaString($this->export_name);

      return $tmp;
   }
};

?>
