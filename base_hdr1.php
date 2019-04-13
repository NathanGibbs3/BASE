<?php
$title = $UIL->Title . " (BASE)";
if ( !preg_match("/(base_denied|index).php/", $_SERVER['SCRIPT_NAME']) ) {
	$title .= " $BASE_installID";
	if ( isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1 ) {
		$title .= ' -- ARCHIVE';
	}
}
print "\n".str_repeat("\t",2).'<div class="mainheadertitle">'.$title.'</div>';
?>
