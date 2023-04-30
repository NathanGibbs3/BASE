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
//            Purpose: Page to send people to if they are not logged in
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once("$BASE_path/base_common.php");
include_once("$BASE_path/includes/base_auth.inc.php");
include_once("$BASE_path/includes/base_log_error.inc.php");
include_once("$BASE_path/includes/base_output_html.inc.php");

PrintBASESubHeader();
NLIO('<p>',2);
NLIO('Authentication required.',3); // Needs to be added to TD Files.
NLIO("<a href='index.php'>Login</a>",3);
NLIO('</p>',2);
PrintBASESubFooter();
?>
