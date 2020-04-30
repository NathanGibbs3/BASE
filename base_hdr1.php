<?php
$title = _TITLE;
if ( !preg_match("/(base_denied|index).php/", $_SERVER['SCRIPT_NAME']) ) {
	$title .= " $BASE_installID";
	if ( isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1 ) {
		$title .= ' -- ARCHIVE';
	}
}
NLIO('<div class="mainheadertitle">'.$title.'</div>',2);
?>
