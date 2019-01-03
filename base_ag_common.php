<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: lookup routines for AG information
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net>
** Joel Esler <joelesler@users.sourceforge.net>
********************************************************************************
*/

function GetAGIDbyName($ag_name, $db)
{
  $ag_id = "";
  $sql = "SELECT ag_id FROM acid_ag WHERE ag_name='".$ag_name."'";

  $result = $db->baseExecute($sql, -1, -1, false);

  if ( $db->baseErrorMessage() != "" )
     ErrorMessage(_ERRAGNAMESEARCH);
  else if ( $result->baseRecordCount() < 1 )
     ErrorMessage(_ERRAGNAMEEXIST);
  else
  {
        $myrow = $result->baseFetchRow();
        $ag_id = $myrow[0]; 
        $result->baseFreeRows();
  }

  return $ag_id;
}

function GetAGNameByID($ag_id, $db)
{
  $ag_name = "";
  $sql = "SELECT ag_name FROM acid_ag WHERE ag_id='".$ag_id."'";

  $result = $db->baseExecute($sql, -1, -1, false);

  if ( $db->baseErrorMessage() != "" )
     ErrorMessage(_ERRAGIDSEARCH);
  else if ( $result->baseRecordCount() < 1 )
     ErrorMessage(_ERRAGNAMEEXIST);
  else
  {
        $myrow = $result->baseFetchRow();
        $ag_name = $myrow[0];
        $result->baseFreeRows();
  }

  return $ag_name;
}

function VerifyAGID($ag_id, $db)
{
  $sql = "SELECT ag_id FROM acid_ag WHERE ag_id='".$ag_id."'";
  $result = $db->baseExecute($sql);

  if ( $db->baseErrorMessage() != "" )
  {
     ErrorMessage(_ERRAGLOOKUP);
     return 0;
  }
  else if ( $result->baseRecordCount() < 1 )
     return 0;
  else
  {
     $result->baseFreeRows();
     return 1;
  }
}

function CreateAG($db, $ag_name, $ag_desc)
{
   $sql = "INSERT INTO acid_ag (ag_name, ag_desc) VALUES ('".$ag_name."','".$ag_desc."');";
   $db->baseExecute($sql, -1, -1, false);

   if ( $db->baseErrorMessage() != "" )
      FatalError(_ERRAGINSERT);

   $ag_id = $db->baseInsertID();
   /* The following code is a kludge and can cause errors.  Since it is not possible
    * to determine the last insert ID of the AG, we requery the DB to ascertain the ID
    * by matching on the ag_name and ag_desc.  -- rdd (1/23/2001)
    *
    * Modified code to only run the kludge if the dbtype is postgres.  Created a function
    * to use the actual insertid function if available and return -1 if no -- srh (02/01/2001)
    *
    * Transaction support is neccessary to get this absolutely correct, because using
    * an insert_id might break in a multi-user environment.  -- rdd (02/07/2001)
    */
   if ( $ag_id == -1 )
   {
      $tmp_sql = "SELECT ag_id FROM acid_ag WHERE ag_name='".$ag_name."' AND ".
                 "ag_desc='".$ag_desc."'";
      if ($db->DB_type == "mssql")
         $tmp_sql = "SELECT ag_id FROM acid_ag WHERE ag_name='".$ag_name."' AND ".
                    "ag_desc LIKE '".MssqlKludgeValue($ag_desc)."'";
      $tmp_result = $db->baseExecute($tmp_sql);
      $myrow = $tmp_result->baseFetchRow();
      $ag_id = $myrow[0];
      $tmp_result->baseFreeRows();
   }

   return $ag_id;
}

?>
