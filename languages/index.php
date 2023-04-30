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
//            Purpose: File to prevent access to this directory directly.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$sc = DIRECTORY_SEPARATOR;
require_once("..$sc" . "includes$sc" . 'base_krnl.php');
HTTP_header("Location: $BASE_urlpath/base_main.php");
?>
