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
** Purpose: mandatory include files for any queries
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');
$sc = DIRECTORY_SEPARATOR;
include_once("$BASE_path$sc" . "includes$sc" . 'base_output_html.inc.php');
include_once("$BASE_path$sc" . "includes$sc" . "base_state_common.inc.php");
include_once("$BASE_path/includes/base_user.inc.php");
include_once("$BASE_path/includes/base_state_query.inc.php");
include_once("$BASE_path/includes/base_state_criteria.inc.php");
include_once("$BASE_path/includes/base_output_query.inc.php");
include_once("$BASE_path/includes/base_log_error.inc.php");
include_once("$BASE_path/includes/base_action.inc.php");
include_once("$BASE_path/includes/base_cache.inc.php");
include_once("$BASE_path/includes/base_net.inc.php");
include_once("$BASE_path/includes/base_signature.inc.php");
?>
