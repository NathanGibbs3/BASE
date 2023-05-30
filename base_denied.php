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
//            Purpose: Landing page for unauthenticated users.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once(BASE_IPath . 'base_log_error.inc.php');
include_once(BASE_IPath . 'base_output_html.inc.php');
include_once(BASE_IPath . 'base_state_common.inc.php');

$tmp = 'Authentication required: '; // Need to TD this.
PrintBASESubHeader();
NLIO("<div class='errorMsg'>", 3);
printIcon('auth', $tmp, 4, 'lg');
NLIO("<div style='line-height: 64px;'>", 4);
NLIO($tmp, 5);
NLIO("<a href='index.php'>Login</a>", 5);
NLIO("</div>", 4);
NLIO('</div>', 3);
PrintBASESubFooter();
?>
