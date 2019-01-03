#!/usr/bin/perl

use SnortUnified(qw(:DEFAULT :record_vars :meta_handlers));
use XML::Writer;
use Socket;

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

my $xml = new XML::Writer();

$xml->xmlDecl();
$xml->comment("Generated from a unified " . $UF_Data->{'TYPE'} . " file " . $file);
$xml->startTag("SnortData");
print("\n");
while ( $record = readSnortUnifiedRecord() ) {
    
    print("\t");
    $xml->startTag('Event');
    $xml->characters($record->{'event_id'});
    print("\n");

    foreach $field ( @fields ) {
      if ($field ne 'pkt') {
        print("\t\t");
        $xml->startTag($field);
        $xml->characters($record->{$field});
        $xml->endTag($field);
        print("\n");
        if ( $field eq 'tv_sec' || $field eq 'tv_sec2' ) {
            print("\t\t");
            $xml->startTag($field . "_h");
            $xml->characters(scalar localtime($record->{$field}));
            $xml->endTag($field . "_h");
            print("\n");
        }
        if ( $field eq 'sip' || $field eq 'dip' ) {
            print("\t\t");
            $xml->startTag($field . "_h");
            $xml->characters(inet_ntoa(pack('N', $record->{$field})));
            $xml->endTag($field . "_h");
            print("\n");
        }
      }
    }
    print("\t");
    $xml->endTag('Event');
    print("\n");
    
}
$xml->endTag("SnortData");

$xml->end();
closeSnortUnified();

