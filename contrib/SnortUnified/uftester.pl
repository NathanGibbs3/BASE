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
#########################################################################################

use SnortUnified qw(:DEFAULT :meta_handlers);
use Socket;

print License . "\n";

$file = shift;
$debug = 1;
$UF_Data = {};
$record = {};

$sids = get_snort_sids("/tmp/sid-msg.map",
                       "/tmp/gen-msg.map_DISABLED");
$class = get_snort_classifications("/tmp/classification.config");

# If you want to see them
#print_snort_sids($sids);

# If you want to see them
#print_snort_classifications($class);

# printf("Opening File %s\n", $file);

$UF_Data = openSnortUnified($file);
die unless $UF_Data;

# printf("File Type is %s\n", $UF_Data->{'TYPE'});
# printf("File Magic is 0x%.8x\n", $UF_Data->{'MAGIC'});
# printf("VERSION_MAJOR 0x%.8x\n",  $UF_Data->{'VERSION_MAJOR'});
# printf("VERSION_MINOR 0x%.8x\n",  $UF_Data->{'VERSION_MINOR'});
# if ( $UF_Data->{'TYPE'} eq 'LOG' ) {
#     printf("TIMEZONE 0x%.8x\n",  $UF_Data->{'TIMEZONE'});
#     printf("SIG_FLAG 0x%.8x\n",  $UF_Data->{'SIG_FLAG'});
#     printf("SNAPLEN 0x%.8x\n",  $UF_Data->{'SNAPLEN'});
#     printf("LINKTYPE 0x%.8x\n",  $UF_Data->{'LINKTYPE'});
# }


while ( $record = readSnortUnifiedRecord() ) {
    
    if ( $UF_Data->{'TYPE'} eq 'LOG' ) {
        print_log($record,$sids,$class);
    } else {
        print_alert($record,$sids,$class);
    }

}

closeSnortUnified();

