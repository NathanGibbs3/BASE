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
use Net::Pcap;

print License . "\n";

$file = shift;
$UF_Data = {};
$record = {};
$count = 0;
$time = 0;

setSnortConnParam('user', 'snort');
setSnortConnParam('password', 'snort');
setSnortConnParam('interface', 'eth1');
setSnortConnParam('database', 'snorttest');
#setSnortConnParam('hostname', Sys::Hostname::hostname());
setSnortConnParam('hostname', 'localhost');
setSnortConnParam('filter', '');

die unless getSnortDBHandle();

# die unless $UF_Data = openSnortUnified($file);
$pcap = Net::Pcap::open_offline($file, \$err) or die "Can't read '$file': $err\n";
my $linktype = Net::Pcap::datalink($pcap);
if ($linktype == DLT_EN10MB)
{
  $linklayer_type = "eth";
}
elsif ($linktype == DLT_NULL)
{
  $linklayer_type = "null";
}
elsif ($linktype == DLT_RAW)
{
  $linklayer_type = "raw";
}
elsif ($linktype == DLT_LINUX_SLL)
{
  $linklayer_type = "sll";
}
elsif ($linktype == DLT_PPP)
{
  $linklayer_type = "ppp";
}

#print "linktype: \"$linklayer_type\"\n"; 

$sensor_id = getSnortSensorID();

printSnortConnParams();
printSnortSigIdMap();

Net::Pcap::loop($pcap, -1, \&process_packet, $linklayer_type);

# clean up
closeSnortUnified();
closeSnortDBHandle();


sub process_packet {
    my($user_data, $header, $packet) = @_;
    
    if ( $time ne $header->{'tv_sec'} ) {
        $count = 0;
        $time = $header->{'tv_sec'};
    } else {
        $count++;
    }

    my $record = {};
        $record->{'sig_id'} = 9000000;
        $record->{'sig_rev'} = 1;
        $record->{'class'} = 0;
        $record->{'pri'} = 0;
        $record->{'event_id'} = undef;
        $record->{'reference'} = 0;
        $record->{'tv_sec'} = $header->{'tv_sec'};
        $record->{'tv_usec'} = $header->{'tv_usec'};
        $record->{'flags'} = undef;
        $record->{'pkt_sec'} = $header->{'tv_sec'};
        $record->{'pkt_usec'} = $header->{'tv_usec'};
        $record->{'caplen'} = $header->{'caplen'};
        $record->{'pktlen'} = $header->{'len'};
        $record->{'pkt'} = $packet;
        $record->{'linklayer_type'} = $user_data;

        insertSnortLog($record,$sids,$class);
}


