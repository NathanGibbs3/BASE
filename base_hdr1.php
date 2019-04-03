<?php
$title = $UIL->Title . " (BASE) $BASE_installID";
if ( isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1 ) {
	$title .= ' -- ARCHIVE';
}
print "\n".str_repeat ( "\t",2 ).'<div class="mainheadertitle">';
print "\n".str_repeat ( "\t",3 ).'&nbsp;'.$title;
print "\n".str_repeat ( "\t",2 ).'</div>';
?>
