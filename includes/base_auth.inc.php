<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2023 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: User manangement object.
//                     Role management object.
//                     Access Authoriztion routines.
//                     If the variable $Use_Auth_System = 0 (zero), Access
//                     authorization checks always pass.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

class BaseUser {
	var $db;

	function __construct() { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			// @codeCoverageIgnoreStart
			// Should never execute.
			trigger_error( // Will need to add this message to the TD.
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}

	function BaseUser() { // PHP 4x constructor.
		GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname,
		$alert_host, $alert_port, $alert_user, $alert_password;
		$db = NewBASEDBConnection($DBlib_path, $DBtype);
		$db->baseDBConnect(
			$db_connect_method, $alert_dbname, $alert_host, $alert_port,
			$alert_user, $alert_password, 1
		);
		$db->DB->SetFetchMode(ADODB_FETCH_BOTH);
		$this->db = $db;
	}

	// Core Authentication System.
	// Accepts a username and password.
	// Returns:
	//	0 if the username and pwd are correct.
	//	1 if the password is wrong.
	//	2 if the user is disabled.
	//	3 if the username doesn't exist
	function AuthenticateCore( $user = '', $pwd = '' ){
		GLOBAL $debug_mode, $et;
		$Ret = -1;
		if ( !LoadedString($user) ){ // Input Validation
			$Ret = 3; // Needs User Name, default to nonexistent user.
		}else{
			$db = $this->db;
			$user = filterSql($user,1,$db); // Input sanitazation.
			$pwd  = filterSql($pwd,1,$db);
			$sql = "SELECT * from base_users where base_users.usr_login ='" . $user ."';";
			$rs = $db->baseExecute($sql);
			if (
				$rs != false
				&& $db->baseErrorMessage() == ''
				&& $rs->baseRecordCount() > 0
			){ // Error Check
				$result = $rs->baseFetchRow();
				if ( $result['usr_enabled'] == 0 ){
					$Ret = 2; // User Account Disabled.
				}else{
					if ( $result['usr_pwd'] == $this->cryptpassword($pwd) ){
						$Ret = 0; // Password OK
					}else{
						$Ret = 1; // Password Wrong
					}
				}
				$rs->baseFreeRows();
			}else{
				$Ret = 3;
			}
		}
		// @codeCoverageIgnoreStart
		if ( isset($et) && is_object($et) ){
			// Need to TD this in Issue #11 branch.
			$et->Mark('Authentication Check.');
		}
		// @codeCoverageIgnoreEnd
		return $Ret;
	}
	// Same inputs/returns as AuthenticateCore.
	// Sets the role cookie on success.
	function Authenticate( $user = '', $pwd = '' ){
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = $this->AuthenticateCore( $user, $pwd );
		$tmp = 'Fail';
		if ( $Ret == 0 ){
			$tmp = 'Pass';
			$this->setRoleCookie($this->cryptpassword($pwd), $user);
		}
		KML("$EMPfx$tmp.", 2);
		return $Ret;
	}
	// Same inputs as AuthenticateCore.
	// returns "Failed" on failure or role_id on success.
	function AuthenticateNoCookie( $user = '', $pwd = '' ) {
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = $this->AuthenticateCore( $user, $pwd );
		$tmp = 'Fail';
		if ( $Ret == 0 ){ // Get RoleID
			$db = $this->db;
			$user = filterSql($user,1,$db); // Input sanitazation.
			$pwd  = filterSql($pwd,1,$db);
			$sql = "SELECT role_id FROM base_users where usr_login='" . $user
			. "' AND usr_pwd='".$this->cryptpassword($pwd)."';";
			$rs = $db->baseExecute($sql);
			if (
				$rs != false
				&& $db->baseErrorMessage() == ''
				&& $rs->baseRecordCount() > 0
			){ // Error Check
				$Ret = $rs->baseFetchRow();
				$rs->baseFreeRows();
				if ( isset($Ret[0]) ){
					$tmp = 'Pass';
					$Ret = intval($Ret[0]);
				}
			}else{
				$Ret = 'Failed';
			}
		}else{
			$Ret = 'Failed';
		}
		KML("$EMPfx$tmp.", 2);
		return $Ret;
	}
	// Accepts a username.
	// Returns true if user account is enabled, false otherwise.
	function isActive( $user = '' ){
		$Ret = false;
		if ( LoadedString($user) ){ // Input Validation
			$db = $this->db;
			$user = filterSql($user,1,$db); // Input sanitazation.
			$sql = "SELECT usr_enabled FROM base_users WHERE usr_login ='"
			.$user."';";
			$rs = $db->baseExecute($sql);
			if (
				$rs != false
				&& $db->baseErrorMessage() == ''
				&& $rs->baseRecordCount() > 0
			){ // Error Check
				$Active = $rs->baseFetchRow();
				$rs->baseFreeRows();
				if ( isset($Active[0]) ){
					if ( intval($Active[0]) == 1 ){
						$Ret = true;
					}
				}
			}
		}
		return $Ret;
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
        $sql = "INSERT INTO base_users (usr_id, usr_login, usr_pwd, role_id, usr_name, usr_enabled)";
        $sql .= "VALUES (".$userid .", '".$user."','".$cryptpassword."',".$role.",'".$name."', 1);";
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
	function returnUser(){ // returns user login from role cookie
		$user = '';
		if ( isset($_COOKIE['BASERole']) ){
			$cookievalue = $_COOKIE['BASERole'];
			$cookiearr = explode('|', $cookievalue);
			$user = $cookiearr[1];
		}
		return $user;
	}
	function returnUserID($user){ // Returns uid of user, false on Error.
		$Ret = false;
		if ( LoadedString($user) ){ // Input Validation
			$db = $this->db;
			$sql = "SELECT usr_id FROM base_users WHERE usr_login = '" . $user . "';";
			$rs = $db->baseExecute($sql);
			if (
				$rs != false
				&& $db->baseErrorMessage() == ''
				&& $rs->baseRecordCount() > 0
			){ // Error Check
				$usrid = $rs->baseFetchRow();
				$rs->baseFreeRows();
				if ( isset($usrid[0]) ){
					$Ret = intval($usrid[0]);
				}
			}
		}
		return $Ret;
	}
	function returnUsers(){
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
	function returnEditUser( $userid, $XSS = 1 ){
		// Returns an array of user's info.
		// Each array item is formatted as:
		// array[0] = usr_id|usr_login|role_id|usr_name|usr_enabled
		// Returns false on Error.
		$Ret = false;
		$userid = intval($userid); // Input Validation
		if ( $userid > 0 ){
			if ( !is_int($XSS) ){
				$XSS = 1;
			}
			$db = $this->db;
			$sql = "SELECT usr_id, usr_login, role_id, usr_name, usr_enabled ";
			$sql .= "FROM base_users WHERE usr_id = '" . $userid . "';";
			$result = $db->baseExecute($sql);
			if ( $result != false ){ // Error Check
				$myrow = $result->baseFetchRow();
				$result->baseFreeRows();
				if ( $XSS > 0 ){ // Anti XSS Output Data
					$myrow = XSSPrintSafe($myrow);
				}
				$Ret = $myrow;
			}
		}
		return $Ret;
	}
	function roleName( $roleID, $XSS = 1 ){
	// Returns name of roleID, false on Error.
		$Ret = false;
		$roleID = intval($roleID); // Input Validation
		if ( $roleID > 0 ){
			if ( !is_numeric($XSS) ){
				$XSS = 1;
			}
			$db = $this->db;
			$sql = "SELECT role_name FROM base_roles WHERE role_id = '" . $roleID . "';";
			$result = $db->baseExecute($sql);
			if ( $result != false ){ // Error Check
				$rolename = $result->baseFetchRow();
				$result->baseFreeRows();
				if ( isset($rolename[0]) ){
					$Ret = $rolename[0];
				}
				if ( $XSS > 0 ){ // Anti XSS Output Data
					$Ret = XSSPrintSafe($Ret);
				}
			}
		}
		return $Ret;
	}
	function returnRoleNamesDropDown($roleid){
		// Returns an HTML drop down list with all of the role names.
		// The passed $roleid will be selected if it exists.
		$db = $this->db;
		$sql = "SELECT role_id, role_name FROM base_roles;";
		$result = $db->baseExecute($sql);
		$tmpHTML = NLI("<select name='roleID'>",7);
		$i = 0;
		while (
			($myrow = $result->baseFetchRow())
			&& ($i < $result->baseRecordCount())
		){
			$tmp = "<option value='".$myrow[0]."'";
			$tmp .= chk_select($roleid,$myrow[0]);
			$tmp .= '>'.XSSPrintSafe($myrow[1]).'</option>';
			$tmpHTML .= NLI($tmp,8);
			++$i;
		}
		$result->baseFreeRows();
		$tmpHTML .= NLI('</select>',7);
		return $tmpHTML;
	}

	function setRoleCookie( $passwd, $user ){
		//Sets cookie with the md5 summed passwd embedded.
        $hash = md5($passwd . $user . "BASEUserRole");
        $cookievalue = $passwd . "|" . $user . "|";
		BCS('BASERole', $cookievalue);
	}

	function readRoleCookie(){ // Reads the roleCookie and returns the role id.
		$Ret = 0;
		if ( isset($_COOKIE['BASERole']) ){ // Check cookie sanity
			$cookievalue = $_COOKIE['BASERole'];
			$cookiearr = explode('|', $cookievalue);
			$user = '';
			$pwd = '';
			if ( isset($cookiearr[0]) ){
				$pwd = $cookiearr[0];
			}
			if ( isset($cookiearr[1]) ){
				$user = $cookiearr[1];
			}
			$db = $this->db;
			$user = filterSql($user,1,$db); // Input sanitazation.
			$pwd  = filterSql($pwd,1,$db);
			$sql = "SELECT role_id FROM base_users where usr_login='".$user
			."' AND usr_pwd='".$pwd."';";
			$result = $this->db->baseExecute($sql);
			// Error Check
			if ( $result != false && is_array($result->row->fields) ){
				$Ret = $result->row->fields['role_id'];
			}
		}
		return $Ret;
	}
	// @codeCoverageIgnoreStart
	// Why write a unit test for a builtin function wrapper.
	function cryptpassword( $password ){
		// Returns the md5 hash of supplied password.
		// Security wise this is a bad idea.
		// Opened Issue #79 to track this.
		// https://github.com/NathanGibbs3/BASE/issues/79
		$cryptpwd = md5($password);
		return $cryptpwd;
	}
	// @codeCoverageIgnoreEnd
}

class BaseRole {
	var $db;

	function __construct() { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			// @codeCoverageIgnoreStart
			// Should never execute.
			trigger_error( // Will need to add this message to the TD.
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}
	function BaseRole() { // PHP 4x constructor.
		GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname,
		$alert_host, $alert_port, $alert_user, $alert_password;
		$db = NewBASEDBConnection($DBlib_path, $DBtype);
		$db->baseDBConnect(
			$db_connect_method, $alert_dbname, $alert_host, $alert_port,
			$alert_user, $alert_password, 1
		);
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
	function returnEditRole( $roleid, $XSS = 1 ){
		// Returns an array of Role's info.
		// Each array item is formatted as:
		// array[0] = role_id|role_name|role_desc
		$Ret = false;
		$roleid = intval($roleid); // Input Validation
		if ( $roleid > 0 ){
			if ( !is_numeric($XSS) ){
				$XSS = 1;
			}
			$db = $this->db;
			$sql = "SELECT role_id, role_name, role_desc ";
			$sql .= "FROM base_roles WHERE role_id = '" . $roleid . "';";
			$result = $db->baseExecute($sql);
			if ( $result != false ){ // Error Check
				$myrow = $result->baseFetchRow();
				$result->baseFreeRows();
				if ( $XSS == 1 ){ // Anti XSS Output Data
					$myrow = XSSPrintSafe($myrow);
				}
				$Ret = $myrow;
			}
		}
		return $Ret;
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

// Returns true if the role of current user is authorized, false otherwise.
function ARC( $roleneeded = 1 ){ // AuthorizedRole Core
	GLOBAL $BCR, $Use_Auth_System, $et;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = false;
	$AS = false;
	// @codeCoverageIgnoreStart
	if( isset($BCR) && is_object($BCR) ){
		$AS = $BCR->GetCap('BASE_SSAuth'); // Auth System
		$DBL = $BCR->GetCap('BASE_UIDiag'); // Debug Lvl
	}else{
		$DBL = 0;
		if( intval($Use_Auth_System) == 1 ){
			$AS = true;
		}
	}
	// @codeCoverageIgnoreEnd
	if( !$AS ){ // Auth system off, always pass.
		$Ret = true;
	}else{ // Check account role and enabled flag.
		$BUser = new BaseUser();
		$user = $BUser->returnUser(); // User
		$UAE = $BUser->isActive($user); // User Account Enabled.
		$URN = $BUser->hasRole($roleneeded); // User role needed.
		if ( $URN == 1 && $UAE == true ){ // Authorized
			$Ret = true;
		}
	}
	$tmp = 'Authorization Check'; // Need to TD this in Issue #11 branch.
	kml("$EMPfx$tmp: " . var_export($Ret, true), 3);
	// @codeCoverageIgnoreStart
	if( $DBL > 2 && isset($et) && is_object($et) ){
		$et->Mark("$tmp.");
	}
	// @codeCoverageIgnoreEnd
	return $Ret;
}

// Returns true if the role of current user is authorized.
// Redirect if valid header is given.
function AuthorizedRole( $roleneeded = 1, $header = '' ){
	GLOBAL $BASE_urlpath, $BCR;
	$Ret = ARC($roleneeded);
	if( !$Ret ){ // ARC failed.
		$EMPfx = 'BASE Security Alert ' . __FUNCTION__ . ': ';
		$BUser = new BaseUser();
		$user = $BUser->returnUser(); // User
		$msg = ' user access';
		if ( $user == '' ){
			$msg = "Unauthenticated$msg";
		}else{
			$msg = "Unauthorized$msg: $user";
		}
		kml($EMPfx . $msg);
		if ( $roleneeded >= 10000 ){ // Lock redirect :-)
			kml('Redirect Lock Engaged');
			$header = 'base_denied';
		}
		if ( $header != '' ){
			$ReqRE = "(base_(denied|main)|index)";
			if ( preg_match("/^" . $ReqRE ."$/", $header) ){
				// Redirect to allowed locations only.
				kml('Attempt Redirect');
				HTTP_header("Location: $BASE_urlpath/$header.php");
				kml('Redirect failed');
			}
		}
	}
	return $Ret;
}

// Returns true if the passed value is part of the running script name.
function AuthorizedPage ( $page = '' ){
	GLOBAL $BASE_urlpath;
	$Ret = false;
	$ReqRE = preg_quote("$BASE_urlpath/",'/')."$page\.php";
	if ( preg_match("/^" . $ReqRE ."$/", $_SERVER['SCRIPT_NAME']) ){
		$Ret = true;
	}
	return $Ret;
}

// Returns true if URI is set & matches URL path & running script name.
function AuthorizedURI (){
	GLOBAL $BASE_urlpath;
	$Ret = false;
	if (isset($_SERVER["REQUEST_URI"])){
		$URI = $_SERVER["REQUEST_URI"];
		$ReqRE = preg_quote($BASE_urlpath.$_SERVER['SCRIPT_NAME'],'/');
		if ( preg_match("/^" . $ReqRE ."/", $URI) ){
			$Ret = true;
		}
	}
	return $Ret;
}

// Returns true if Client Host is allowed to connect.
function AuthorizedClient (){
	GLOBAL $AllowedClients;
	$Ret = true; // Fail Open for configs where this isn't set.
	if( LoadedString($AllowedClients) ){
		$IpTmp = $AllowedClients;
		$Ret = false; // Lock the gate.
		$IPF = 0;
		$IPL = 0;
		$IPC = 0;
		$IPV = 0;
		$Snm = netmask ($IpTmp);
		if ( $Snm > 0 ){
			$MaskRE = '\/' . $Snm;
			$IpTmp = preg_replace( '/'. $MaskRE .'$/', '', $IpTmp );
			if( is_ip4 ($IpTmp) ){
				if( $Snm > 32 ){ // Invalid IPv4 Netmask
					$Snm = 32;
				}
				$IPC = NMHC($Snm);
			}elseif( is_ip6 ($IpTmp) ){
				$IPC = NMHC($Snm, true);
			}
		}
		$IPF = ipconvert($IpTmp);
		$IPL = $IPF;
		if( is_ip4 ($IpTmp) ){
			$IPV = 4;
			if( $Snm > 0 ){
				$IPL = $IPL + $IPC;
			}
		}elseif( is_ip6 ($IpTmp) ){
			$IPV = 6;
			if( $Snm > 0 && defined('GMP_VERSION') ){
				$IPL = gmp_strval(gmp_add($IPF, $IPC));
			}
		}
		if( $IPV != 0 && is_key('REMOTE_ADDR', $_SERVER) ){
			$ipcli = $_SERVER['REMOTE_ADDR'];
			$ipcT = ipconvert($ipcli);
			if( $IPV == 4 && is_ip4 ($ipcli) ){
				if( $ipcT >= $IPF && $ipcT <= $IPL ){
					$Ret = true;
				}
			}elseif( $IPV == 6 && is_ip6 ($ipcli) && defined('GMP_VERSION') ){
				if(
					gmp_cmp($ipcT, $IPF) > -1 && gmp_cmp($ipcT, $IPL) < 1
				){
					$Ret = true;
				}
			}
		}
	}
	return $Ret;
}

?>
