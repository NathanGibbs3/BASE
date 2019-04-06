<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019 Nathan Gibbs
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

require("base_conf.php");
include_once("$BASE_path/includes/base_lang.inc.php");
include_once("$BASE_path/includes/base_output_html.inc.php");
$UIL = new UILang($BASE_Language); // Create UI Language Abstraction Object.
PageStart();
include("$BASE_path/base_hdr1.php");
print "\n".str_repeat ( "\t",2 ).'<p>';
// Needs to be added to TD Files.
print "\n".str_repeat ( "\t",3 ).'Authentication required.';
print "\n".str_repeat ( "\t",3 ).'<a href="index.php">Login</a>';
print "\n".str_repeat ( "\t",2 ).'</p>';
$noDisplayMenu = 1;
include("base_footer.php");
PageEnd();
?>
