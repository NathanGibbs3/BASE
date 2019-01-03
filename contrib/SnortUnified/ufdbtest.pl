#!/usr/bin/perl

#########################################################################################
# Copyright (c) 2006 Jason Brvenik
# A Perl module to make it east to work with snort unified files.
# http://www.snort.org
# 
#########################################################################################
# 
#
# The intellectual property rights in this program are owned by 
# Mr. Jason Brvenik.  This program may be copied, distributed and/or 
# modified only in accordance with the terms and conditions of 
# Version 2 of the GNU General Public License (dated June 1991).  By 
# accessing, using, modifying and/or copying this program, you are 
# agreeing to be bound by the terms and conditions of Version 2 of 
# the GNU General Public License (dated June 1991).
#
# This program is distributed in the hope that it will be useful, but 
# WITHOUT ANY WARRANTY; without even the implied warranty of 
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
# See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License 
# along with this program; if not, write to the 
# Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, 
# Boston, MA  02110-1301  USA 
# 
# 
#
#########################################################################################

use SnortUnified qw(:DEFAULT :meta_handlers);
use SnortUnified::Database qw(:ALL);
use Sys::Hostname;

print License . "\n";

$file = shift;
$UF_Data = {};
$record = {};

$sids = get_snort_sids("/tmp/sid-msg.map",
                       "/tmp/gen-msg.map_DISABLED"); # old format!
$class = get_snort_classifications("/tmp/classification.config");

# If you want to see them
# print_snort_sids($sids);

# If you want to see them
# print_snort_classifications($class);

setSnortConnParam('user', 'snort');
setSnortConnParam('password', 'snort');
setSnortConnParam('interface', 'eth1');
setSnortConnParam('database', 'snorttest');
#setSnortConnParam('hostname', Sys::Hostname::hostname());
setSnortConnParam('hostname', 'localhost');
setSnortConnParam('filter', '');

die unless getSnortDBHandle();

die unless $UF_Data = openSnortUnified($file);

my $sensor_id = getSnortSensorID();

# print("Sensor ID is " . $sensor_id . "\n");

printSnortConnParams();
printSnortSigIdMap();

while ( $record = readSnortUnifiedRecord() ) {
    
    if ( $UF_Data->{'TYPE'} eq 'LOG' ) {
        print_log($record,$sids,$class);
        insertSnortLog($record,$sids,$class);
    } else {
        print_alert($record,$sids,$class);
        insertSnortAlert($record,$sids,$class);
    }

}

# clean up
closeSnortUnified();
closeSnortDBHandle();


