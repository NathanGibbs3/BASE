#!/usr/bin/perl

use SnortUnified(qw(:DEFAULT :record_vars :meta_handlers));
use Sys::Syslog;

$file = shift;
$debug = 0;
$UF_Data = {};
$record = {};
$prepend = "Snort Alert:";

$sids = get_snort_sids("/tmp/sid-msg.map",
                       "/tmp/gen-msg.map_DISABLED"); # old format!
$class = get_snort_classifications("/tmp/classification.config");

$UF_Data = openSnortUnified($file);
die unless $UF_Data;

if ( $UF_Data->{'TYPE'} eq 'LOG' ) {
    @fields = @$log_fields;
} else {
    @fields = @$alert_fields;
}


openlog($prepend, 'cons,pid', 'local0');
syslog('info', "$0: Processing file $file");

while ( $record = readSnortUnifiedRecord() ) {
    
    syslog('info', format_alert($record, $sids, $class));
    
}

closelog();
closeSnortUnified();

