#!/usr/bin/perl

use SnortUnified(qw(:DEFAULT :record_vars));
use Socket;

$file = shift;
$debug = 0;
$UF_Data = {};
$record = {};

$UF_Data = openSnortUnified($file);
die unless $UF_Data;

if ( $UF_Data->{'TYPE'} eq 'LOG' ) {
    @fields = @$log_fields;
} else {
    @fields = @$alert_fields;
}

print("row");
foreach $field ( @fields ) {
    if ( $field ne 'pkt' ) { 
        print("," . $field);
    }
}
print("\n");

$i = 1;
while ( $record = readSnortUnifiedRecord() ) {
    
    print($i++);;
    
    foreach $field ( @fields ) {
        if ( $field ne 'pkt' ) {
            if ($field eq 'sip' || $field eq 'dip' )
            {
              print("," . inet_ntoa(pack("N", $record->{$field})));
            }
            else
            {
              print("," . $record->{$field});
            }
        }
    }
    print("\n");

}

closeSnortUnified();

