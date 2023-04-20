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
//            Purpose: Logout -- axe the cookie, redir to index.php.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

include("base_conf.php");
include_once("$BASE_path/base_common.php");
if (!headers_sent()) {
	setcookie('BASERole', '', time()-600000);
	base_header("Location: $BASE_urlpath/index.php");
}
?>
