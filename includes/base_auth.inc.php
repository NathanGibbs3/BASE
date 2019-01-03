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
** Purpose: Creates a user object that will contain the authenticated user
** information.  If the variable $Use_Auth_System is set to 0 (zero) then
** it will be an default object that will return each user request as
** an admin user effectively turning off the authorization system
**
** This file also contains the role object which is used to handle role management
** 
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

class BaseUser
{
    var $db;
    
    function BaseUser()
    {
        // Constructor
        GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password;
        $db = NewBASEDBConnection($DBlib_path, $DBtype);
        $db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password, 1);
        $db->DB->SetFetchMode(ADODB_FETCH_BOTH);
        $this->db = $db;
    }
    
    function Authenticate($user, $pwd)
    {
        /* Accepts a username and a password
             returns a 0 if the username and pwd are correct
             returns a 1 if the password is wrong
             returns a 2 if the user is disabled
             returns a 3 is the username doesn't exist
        */
        $cryptpwd = $this->cryptpassword($pwd);
        if ($user == "")
        {
            // needs to input a user.....   
            return 3;
        }
        
        $sql = "SELECT * from base_users where base_users.usr_login ='" . $user ."';";
        $tmpresult = $this->db->baseExecute($sql);
        
        if ( $this->db->baseErrorMessage() != "" )
           return 3;
        
        if ($tmpresult->baseRecordCount() == 0)
        {
            return 3;
        }
        
        $result = $tmpresult->baseFetchRow();
                
        if (($result['usr_pwd']) == $cryptpwd)
        {
            $this->setRoleCookie($result['usr_pwd'], $user);
            return 0;
        } else
        {
            return 1;
        }
    }
    
    function AuthenticateNoCookie($user, $pwd)
    {
       /*
        * This function is solely used for the stand alone modules!
        * Accepts a username and a password
        * returns "Failed" on failure or role_id on success.
        */
        $cryptpwd = $this->cryptpassword($pwd);
        
        if ($user == "")
        {
            // needs to input a user.....   
            return "Failed";
        }
        
        $sql = "SELECT * from base_users where base_users.usr_login ='" . $user ."';";
        $tmpresult = $this->db->baseExecute($sql);
        
        if ( $this->db->baseErrorMessage() != "" )
           return "Failed";
        
        if ($tmpresult->baseRecordCount() == 0)
        {
            return "Failed";
        }
        
        $result = $tmpresult->baseFetchRow();
                
        if (($result['usr_pwd']) == $cryptpwd)
        {
            return $result['role_id'];
        } else
        {
            return "Failed";
        }
    }
    function hasRole($roleNeeded)
    {
        // Checks which role the user has
        $role = $this->readRoleCookie();
        if (($role > $roleNeeded) || $role == 0)
        {
            // returns unauthorized
            return 0;
        }
        
        return 1;
        
    }
    
    function addUser($user, $role, $password, $name)
    {
        //adds user
        $db = $this->db;
        $sql = "SELECT * FROM base_users WHERE usr_login = '" . $user . "'";
        $exists = $db->baseExecute($sql);
        if ( $exists->baseRecordCount() > 0)
        {
            return "User Already Exists";
        }
        $cryptpassword = $this->cryptpassword($password);
        $sql = "SELECT MAX(usr_id) FROM base_users;";
        $usercount = $db->baseExecute($sql);
        $usercnt = $usercount->baseFetchRow();
        $userid = $usercnt[0] + 1;
        $sql ="INSERT INTO base_users (usr_id, usr_login, usr_pwd, role_id, usr_name, usr_enabled)";
        $sql = $sql . "VALUES (".$userid .", '".$user."','".$cryptpassword."',".$role.",'".$name."', 1);";
        $db->baseExecute($sql, -1, -1, false);
        return _ADDEDSF;
    }
    
    function disableUser($user)
    {
        //disables user
        $db = $this->db;
        $sql = "UPDATE base_users SET usr_enabled = '0' WHERE usr_id = '" . $user . "';";
        $disabled = $db->baseExecute($sql);
        return;
    }
    
    function deleteUser($user)
    {
        //deletes the user
        $db = $this->db;
        $sql = "DELETE FROM base_users WHERE usr_id = '" . $user . "';";
        $deleted = $db->baseExecute($sql);
        return;
    }

    function enableUser($user)
    {
        //enables user
        $db = $this->db;
        $sql = "UPDATE base_users SET usr_enabled = '1' WHERE usr_id = '" . $user . "';";
        $enabled = $db->baseExecute($sql);
        return;
    }
    
    function updateUser($userarray)
    {
        /* This function accepts an array in the following format
          $userarray[0] = $userid
          $userarray[1] = $fullname
          $userarray[2] = $roleid
        */
        $db = $this->db;
        $sql = "UPDATE base_users SET usr_name = '". $userarray[1] ."', role_id = '" . $userarray[2] . "' ";
        $sql = $sql . "WHERE usr_id = '" . $userarray[0] . "'";
        $enabled = $db->baseExecute($sql);
        return;
    }
    
    function changePassword($user, $oldpassword, $newpassword)
    {
        // Changes the user's password
        $db = $this->db;
        $sql = "SELECT usr_pwd from base_users where usr_login = '" . $user ."';";
        $userRS = $db->baseExecute($sql);
        if ( $db->baseErrorMessage() != "" )
        {
            // Generic SQL error
            $error = returnErrorMessage(_NOPWDCHANGE . $db->baseErrorMessage());
            return $error;
        } elseif ($userRS->baseRecordCount() == 0)
        {
            // User doesn't exist... Someone is playing with their cookie
            $error = returnErrorMessage(_NOUSER);
            return $error;
        }
        $row = $userRS->baseFetchRow();
        $cryptoldpasswd = $this->cryptpassword($oldpassword);
        if ($cryptoldpasswd != $row[0])
        {
            // Old password doesn't match record
            $error = returnErrorMessage(_OLDPWD);
            return $error;
        }
        // Finally... lets change the password
        $sql = "UPDATE base_users SET usr_pwd='" . $this->cryptpassword($newpassword);
        $sql = $sql . "' WHERE usr_login='". $user . "';";
        $chngpwd = $db->baseExecute($sql);
        if ( $db->baseErrorMessage() != "" )
        {
            // Generic SQL error
            $error = returnErrorMessage(_PWDCANT. $db->baseErrorMessage());
            return $error;
        }
        
        return _PWDDONE;
    }
    
    function returnUser()
    {
        // returns user login from role cookie
        $cookievalue = $_COOKIE['BASERole'];
        $cookiearr = explode('|', $cookievalue);
        $user = $cookiearr[1];
        return $user;
    }

    function returnUserID($login)
    {
        $db = $this->db;
        $sql = "SELECT usr_id FROM base_users WHERE usr_login = '" . $login . "';";
        $rs = $db->baseExecute($sql);
        $usrid = $rs->baseFetchRow();
        return $usrid[0];
    }
    
    function returnUsers()
    {
        /* returns an array of all users info
         * each array item is formatted as
         * array[] = usr_id|usr_login|role_id|usr_name|usr_enabled
        */
        $userarray = NULL;
        $db = $this->db;
        $sql = "SELECT usr_id, usr_login, role_id, usr_name, usr_enabled ";
        $sql = $sql . "FROM base_users ORDER BY usr_id;";
        $result = $db->baseExecute($sql);
        
        $i = 0;
        while ( ($myrow = $result->baseFetchRow()) && ($i < $result->baseRecordCount()) )
        {
            $userarray[$i] = $myrow[0] . "|" . $myrow[1] . "|" . $myrow[2] . "|" . $myrow[3] . "|" . $myrow[4];
            ++$i;
        }
        $result->baseFreeRows();
        return $userarray;
    }
    
    function returnEditUser($userid)
    {
        /* returns an array of all users info
         * each array item is formatted as
         * array[0] = usr_id|usr_login|role_id|usr_name|usr_enabled
        */
        
        $db = $this->db;
        $sql = "SELECT usr_id, usr_login, role_id, usr_name, usr_enabled ";
        $sql = $sql . "FROM base_users WHERE usr_id = '" . $userid . "';";
        $result = $db->baseExecute($sql);
        
        $myrow = $result->baseFetchRow();
        $result->baseFreeRows();
        return $myrow;
        
    }

    function roleName($roleID)
    {
        // returns rolename for a specified role id
        $db = $this->db;
        $sql = "SELECT role_name FROM base_roles WHERE role_id = '" . $roleID . "';";
        $result = $db->baseExecute($sql);
        $rolename = $result->baseFetchRow();
        return $rolename[0];
    }
    
    function returnRoleNamesDropDown($roleid)
    {
        /* Returns a drop down with all of the role names
          $roleid will cause the one passed to the function to be selected */
        $db = $this->db;
        $sql = "SELECT role_id, role_name FROM base_roles;";
        $result = $db->baseExecute($sql);
        $tmpHTML = "<select name='roleID'>";
        $i = 0;
        while ( ($myrow = $result->baseFetchRow()) && ($i < $result->baseRecordCount()) )
        {
            $selected = ($roleid == $myrow[0]) ? "SELECTED" : "";
            $tmpHTML = $tmpHTML . "<option value='". $myrow[0] ."' ". $selected .">".$myrow[1] . "</option>";
            ++$i;
        }
        $result->baseFreeRows();
        $tmpHTML = $tmpHTML . "</select>";
        return $tmpHTML;

    }
    
    function setRoleCookie($passwd, $user)
    {
        //sets a cookie with the md5 summed passwd embedded
        $hash = md5($passwd . $user . "BASEUserRole");
        $cookievalue = $passwd . "|" . $user . "|";
        setcookie('BASERole', $cookievalue);
    }
    
    function readRoleCookie()
    {
        // reads the roleCookie and returns the role id
        $cookievalue = @$_COOKIE['BASERole'];
        $cookiearr = explode('|', $cookievalue);
        $passwd = $this->db->DB->qstr($cookiearr[0],get_magic_quotes_gpc());
        $user = $this->db->DB->qstr(@$cookiearr[1],get_magic_quotes_gpc());
        $sql = "SELECT role_id FROM base_users where usr_login=$user and usr_pwd=$passwd;";
        $result = $this->db->baseExecute($sql);
        $role = $result->row->fields['role_id'];
        return $role;
    }
    
    function cryptpassword($password)
    {
        // accepts a password and returns the md5 hash of it.
        
        $cryptpwd = md5($password);
        
        return $cryptpwd;
    }
}

class BaseRole
{
    var $db;
    
    function BaseRole()
    {
        // Constructor
        GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password;
        $db = NewBASEDBConnection($DBlib_path, $DBtype);
        $db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password, 1);
        $this->db = $db;
    }
    function addRole($roleid, $rolename, $desc)
    {
        //adds role
        $db = $this->db;
        $sql = "SELECT * FROM base_roles WHERE role_name = '" . $rolename . "'";
        $exists = $db->baseExecute($sql);
        if ( $exists->baseRecordCount() > 0)
        {
            return _ROLEEXIST;
        }
        $sql = "SELECT * FROM base_roles WHERE role_id = '" . $roleid . "'";
        $exists = $db->baseExecute($sql);
        if ( $exists->baseRecordCount() > 0)
        {
            return _ROLEIDEXIST;
        }
        $sql ="INSERT INTO base_roles (role_id, role_name, role_desc)";
        $sql = $sql . "VALUES (".$roleid .", '".$rolename ."','".$desc."');";
        $db->baseExecute($sql, -1, -1, false);
        return _ROLEADDED;
    }
    
    function returnEditRole($roleid)
    {
        /* returns an array of all Role's info
         * each array item is formatted as
         * array[0] = role_id|role_name|role_desc
        */
        
        $db = $this->db;
        $sql = "SELECT role_id, role_name, role_desc ";
        $sql = $sql . "FROM base_roles WHERE role_id = '" . $roleid . "';";
        $result = $db->baseExecute($sql);
        
        $myrow = $result->baseFetchRow();
        $result->baseFreeRows();
        return $myrow;
        
    }
    
    function updateRole($rolearray)
    {
        /* This function accepts an array in the following format
          $rolearray[0] = $roleid
          $rolearray[1] = $role_name
          $rolearray[2] = $role_desc
        */
        $db = $this->db;
        $sql = "UPDATE base_roles SET role_name = '". $rolearray[1] ."', role_desc = '" . $rolearray[2] . "' ";
        $sql = $sql . "WHERE role_id = '" . $rolearray[0] . "'";
        $updated = $db->baseExecute($sql);
        return;
    }
    
    function deleteRole($role)
    {
        //deletes the role
        $db = $this->db;
        $sql = "DELETE FROM base_roles WHERE role_id = '" . $role . "';";
        $deleted = $db->baseExecute($sql);
        return;
    }
    
    function returnRoles()
    {
        /* returns an array of all Roles info
         * each array item is formatted as
         * array[] = role_id|role_name|role_desc
        */
        
        $db = $this->db;
        $sql = "SELECT role_id, role_name, role_desc ";
        $sql = $sql . "FROM base_roles ORDER BY role_id;";
        $result = $db->baseExecute($sql);
        
        $i = 0;
        while ( ($myrow = $result->baseFetchRow()) && ($i < $result->baseRecordCount()) )
        {
            $rolearray[$i] = $myrow[0] . "|" . $myrow[1] . "|" . $myrow[2];
            ++$i;
        }
        $result->baseFreeRows();
        return $rolearray;
    }
}
?>
