package SnortUnified;

#########################################################################################
#  $VERSION = "SnortUnified Parser 1.0 - Copyright (c) 2006 Jason Brvenik";
# 
# A Perl module to make it east to work with snort unified files.
# http://www.snort.org
#
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
# Changes:
# V1.1 - Brvenik - Make it speedy
#########################################################################################
# TODO: in no specific order
#  - Documentation
#  - Print alerts like barnyard does
#  - Print logs like barnyard does
#  - CSV output
#  - XML output
#  - MYSQL output
#  - fuzzy find next valid record for corrupt unifieds
#  - Decode protocols
# 
#########################################################################################
# NOTES:
#########################################################################################

use strict;
require Exporter;
use vars qw($VERSION @ISA @EXPORT @EXPORT_OK %EXPORT_TAGS);

use Socket;
use Fcntl qw(:DEFAULT :flock);
use Net::Packet::Consts qw(:DEFAULT);
use Net::Packet::ETH;
use Net::Packet::IPv4;
use Net::Packet::TCP;
use Net::Packet::UDP;
use Net::Packet::ICMPv4;
use Dumpvalue;

my $linklayer_type = "sll";

my $class_self;

BEGIN {
   $class_self = __PACKAGE__;
   $VERSION = "1.4devel20060831-jl1";
}
my $LICENSE = "GNU GPL see http://www.gnu.org/licenses/gpl.txt for more information.";
sub Version() { "$class_self v$VERSION - Copyright (c) 2006 Jason Brvenik" };
sub License() { Version . "\nLicensed under the $LICENSE" };

# Pollute global namespace
@ISA = qw(Exporter);
@EXPORT = qw(
                Version
                License
                debug
                $debug
                $LOGMAGIC
                $ALERTMAGIC
                $UF
                $ICMP_TYPES
                closeSnortUnified
                openSnortUnified
                readSnortUnifiedRecord
                format_packet_data
                print_packet_data
                print_alert
                format_alert
                print_log
                format_log
                decodeIPOptions
);

@EXPORT_OK = qw(
                 $LOGMAGIC
                 $ALERTMAGIC
                 $UF_Record
                 $alert_fields
                 $log_fields
                 $ETHERNET_TYPE_NAMES
                 $ETHERNET_TYPE_IP
                 $ETHERNET_TYPE_ARP
                 $ETHERNET_TYPE_REVARP
                 $ETHERNET_TYPE_IPV6
                 $ETHERNET_TYPE_IPX
                 $ETHERNET_TYPE_PPPoE_DISC
                 $ETHERNET_TYPE_PPPoE_SESS
                 $ETHERNET_TYPE_8021Q
                 $PKT_FRAG_FLAG
                 $PKT_RB_FLAG
                 $PKT_DF_FLAG
                 $PKT_MF_FLAG
                 get_msg
                 get_snort_sids
                 print_snort_sids
                 get_class
                 get_snort_classifications
                 print_snort_classifications
                 get_priority
                 $IP_PROTO_NAME
                 $TCP_OPT_MAP
                 $ICMP_TYPES
             );

%EXPORT_TAGS = (
               ALL => [@EXPORT, @EXPORT_OK],
               magic_vars => [qw(
                                  $LOGMAGIC 
                                  $ALERTMAGIC
                                )
                             ],
               record_vars => [qw(
                                   $UF_Record 
                                   $alert_fields 
                                   $log_fields
                                 )
                               ],
               ethernet_vars =>[qw(
                                    $ETHERNET_TYPE_NAMES 
                                    $ETHERNET_TYPE_IP 
                                    $ETHERNET_TYPE_ARP 
                                    $ETHERNET_TYPE_REVARP 
                                    $ETHERNET_TYPE_IPV6 
                                    $ETHERNET_TYPE_IPX 
                                    $ETHERNET_TYPE_PPPoE_DISC 
                                    $ETHERNET_TYPE_PPPoE_SESS 
                                    $ETHERNET_TYPE_8021Q
                                  )
                               ],
               pkt_flags => [qw(
                                 $PKT_FRAG_FLAG 
                                 $PKT_RB_FLAG 
                                 $PKT_DF_FLAG 
                                 $PKT_MF_FLAG
                               )
                            ],
               meta_handlers => [qw(
                                     get_msg
                                     get_snort_sids
                                     print_snort_sids
                                     get_class
                                     get_snort_classifications
                                     print_snort_classifications
                                     get_priority
                                   )
                                 ],
               ip_vars => [qw($IP_PROTO_NAMES $IP_OPT_MAP)],
               tcp_vars => [qw($TCP_OPT_MAP)],
               icmp_vars => [qw($ICMP_TYPES)],
);

# > 0 == turn on debugging
our $debug = 0;

our $LOGMAGIC = 0xdead1080;
our $ALERTMAGIC = 0xdead4137;
my $LOGMAGICV = 0xdead1080;
my $LOGMAGICN = 0x8010adde;;
my $ALERTMAGICV = 0xdead4137;
my $ALERTMAGICN = 0x3741adde;

our $ETHERNET_TYPE_IP = 0x0800;
our $ETHERNET_TYPE_ARP = 0x0806;
our $ETHERNET_TYPE_REVARP = 0x8035;
our $ETHERNET_TYPE_IPV6 = 0x86dd;
our $ETHERNET_TYPE_IPX = 0x8137;
our $ETHERNET_TYPE_PPPoE_DISC = 0x8863;
our $ETHERNET_TYPE_PPPoE_SESS = 0x8864;
our $ETHERNET_TYPE_8021Q = 0x8100;

our $ETHERNET_TYPE_NAMES = {
    0x0800 => 'IP',
    0x0806 => 'ARP',
    0x809B => 'APPLETALK',
    0x814C => 'SNMP',
    0x86DD => 'IPv6',
    0x880B => 'PPP',
};

our $IP_PROTO_NAMES = {
    4 => 'IP',
    1 => 'ICMP',
    2 => 'IGMP',
    94 => 'IPIP',
    6 => 'TCP',
    17 => 'UDP',,
};

our $ICMP_TYPES = {
    0 => 'Echo Reply',
    3 => 'Unreachable',
    4 => 'Source Quench',
    5 => 'Redirect',
    8 => 'Echo',
    9 => 'Router Advertisement',
    10 => 'Router Solicit',
    11 => 'Time Exceeded',
    12 => 'Parameter Problem',
    13 => 'Timestamp',
    14 => 'Timestamp Reply',
    15 => 'Information Request',
    16 => 'Information Reply',
    17 => 'Mask Request',
    18 => 'Mask Reply',
};

our $PKT_FRAG_FLAG = 0x00000001;
our $PKT_RB_FLAG   = 0x00000002;
our $PKT_DF_FLAG   = 0x00000004;
our $PKT_MF_FLAG   = 0x00000008;

# Cf. /usr/lib/perl5/vendor_perl/5.10.0/Net/Packet/Consts.pm
# perldoc Net::Packet::Consts
# perldoc -m Net::Packet::Consts
our $IP_PROTO_TCP = Net::Packet::Consts::NP_DESC_IPPROTO_TCP;
our $IP_PROTO_UDP = Net::Packet::Consts::NP_DESC_IPPROTO_UDP;
our $IP_PROTO_ICMP = Net::Packet::Consts::NP_DESC_IPPROTO_ICMPv4;
our $CWR = Net::Packet::Consts::NP_TCP_FLAG_CWR;
our $ECE = Net::Packet::Consts::NP_TCP_FLAG_ECE;
our $URG = Net::Packet::Consts::NP_TCP_FLAG_URG;
our $ACK = Net::Packet::Consts::NP_TCP_FLAG_ACK;
our $PSH = Net::Packet::Consts::NP_TCP_FLAG_PSH;
our $RST = Net::Packet::Consts::NP_TCP_FLAG_RST;
our $SYN = Net::Packet::Consts::NP_TCP_FLAG_SYN;
our $FIN = Net::Packet::Consts::NP_TCP_FLAG_FIN;


our $IP_OPT_MAP = {
     0  =>  { 'name'   => 'End of options list',
              'length' => 1,
            },
     1  =>  { 'name'   => 'NOP',
              'length' => 1,
            },
     2  =>  { 'name'   => 'Security',
              'length' => 11,
            },
     3  =>  { 'name'   => 'Loose Source Route',
              'length' => 0,
            },
     4  =>  { 'name'   => 'Time stamp',
              'length' => 0,
            },
     5  =>  { 'name'   => 'Extended Security',
              'length' => 0,
            },
     6  =>  { 'name'   => 'Commercial Security',
              'length' => undef,
            },
     7  =>  { 'name'   => 'Record Route',
              'length' => 0,
            },
     8  =>  { 'name'   => 'Stream Identifier',
              'length' => 4,
            },
     9  =>  { 'name'   => 'Strict Source Route',
              'length' => 0,
            },
     10 =>  { 'name'   => 'Experimental Measurement',
              'length' => undef,
            },
     11 =>  { 'name'   => 'MTU Probe',
              'length' => 4,
            },
     12 =>  { 'name'   => 'MTU Reply',
              'length' => 4,
            },
     13 =>  { 'name'   => 'Experimental Flow Control',
              'length' => undef,
            },
     14 =>  { 'name'   => 'Expermental Access Control',
              'length' => undef,
            },
     15 =>  { 'name'   => '15',
              'length' => undef,
            },
     16 =>  { 'name'   => 'IMI Traffic Descriptor',
              'length' => undef,
            },
     17 =>  { 'name'   => 'Extended Internet Proto',
              'length' => undef,
            },
     18 =>  { 'name'   => 'Traceroute',
              'length' => 12,
            },
     19 =>  { 'name'   => 'Address Extension',
              'length' => 10,
            },
     20 =>  { 'name'   => 'Router Alert',
              'length' => 4,
            },
     21 =>  { 'name'   => 'Selective Directed Broadcast Mode',
              'length' => 0,
            },
     22 =>  { 'name'   => 'NSAP Addresses',
              'length' => undef,
            },
     23 =>  { 'name'   => 'Dynamic Packet State',
              'length' => undef,
            },
     24 =>  { 'name'   => 'Upstream Multicast Packet',
              'length' => undef,
            },
     25 =>  { 'name'   => '25',
              'length' => undef,
            },
     26 =>  { 'name'   => '26',
              'length' => undef,
            },
     27 =>  { 'name'   => '27',
              'length' => undef,
            },
     28 =>  { 'name'   => '28',
              'length' => undef,
            },
     29 =>  { 'name'   => '29',
              'length' => undef,
            },
     30 =>  { 'name'   => '30',
              'length' => undef,
            },
     31 =>  { 'name'   => '30',
              'length' => undef,
            },
};

our $TCP_OPT_MAP = {
     0  => { 'length' => 1,
             'name'   => 'End of Option List',
           },    
     1  => { 'length' => 1,    
             'name'   => 'No-Operation',
           },
     2  => { 'length' => 4,    
             'name'   => 'Maximum Segment Size',
           },
     3  => { 'length' => 3,    
             'name'   => 'WSOPT - Window Scale',
           },
     4  => { 'length' => 2,    
             'name'   => 'SACK Permitted',
           },
     5  => { 'length' => 0,    
             'name'   => 'SACK',
           },
     6  => { 'length' => 6,    
             'name'   => 'Echo (obsolete)',
           },
     7  => { 'length' => 6,    
             'name'   => 'Echo Reply (obsolete)',
           },
     8  => { 'length' => 10,    
             'name'   => 'TSOPT - Time Stamp Option',
           },
     9  => { 'length' => 2,    
             'name'   => 'Partial Order Connection Permitted',
           },
     10 => { 'length' => 3,    
             'name'   => 'Partial Order Service Profile',
           },
     11 => { 'length' => 6,       
             'name'   => 'CC, Connection Count',
           },
     12 => { 'length' => 6,            
             'name'   => 'CC.NEW',
           },
     13 => { 'length' => 6,
             'name'   => 'CC.ECHO',            
           },
     14 => { 'length' => 3,   
             'name'   => 'TCP Alternate Checksum Request',
           },
     15 => { 'length' => 0,   
             'name'   => 'TCP Alternate Checksum Data',
           },
     16 => { 'length' => undef,            
             'name'   => 'Skeeter',
           },
     17 => { 'length' => undef,            
             'name'   => 'Bubba',
           },
     18 => { 'length' => 3,   
             'name'   => 'Trailer Checksum Option',
           },
     19 => { 'length' => 18,   
             'name'   => 'MD5 Signature Option',
           },
     20 => { 'length' => undef,
             'name'   => 'SCPS Capabilities',
           },
     21	=> { 'length' => undef,	
             'name'   => 'Selective Negative Acknowledgements',
           },
     22	=> { 'length' => undef,
             'name'   => 'Record Boundaries',
           },
     23	=> { 'length' => undef,	
             'name'   => 'Corruption experienced',
           },
     24	=> { 'length' => undef,	
             'name'   => 'SNAP',
           },
     25	=> { 'length' => undef,	
             'name'   => 'Unassigned',
           },
     26 => { 'length' => undef,            
             'name'   => 'TCP Compression Filter',
           },
     253 => { 'length' => 0, 
              'name'   => 'RFC3692-style Experiment 1',
            },
     254 => { 'length' => 0, 
              'name'   => 'RFC3692-style Experiment 2',
            },
};

our $UF = { 
        'FILENAME' => '',
        'TYPE' => '',
        'MAGIC' => '',
        'VERSION_MAJOR' => '',
        'VERSION_MINOR' => '',
        'TIMEZONE' => '',
        'SIG_FLAG' => '',
        'SNAPLEN' => '',
        'LINKTYPE' => '',
        'PACKSTR' => '',
        'FIELDS' => '',
        'RECORDSIZE' => 0,
        'FILESIZE' => 0,
        'FILEMTIME' => 0,
        'FILEPOS' => 0,
        'PATIENCE' => 3,
        'LOCKED' => 0,
        '64BIT' => 0,
};

our $UF_Record = {};

# set up record structure
my $alert32_fields = [
        'sig_gen',
        'sig_id',
        'sig_rev',
        'class',
        'pri',
        'event_id',
        'reference',
        'tv_sec',
        'tv_usec',
        'tv_sec2',
        'tv_usec2',
        'sip',
        'dip',
        'sp',
        'dp',
        'protocol',
        'flags'
];

my $alert64_fields = [
        'sig_gen',
        'sig_id',
        'sig_rev',
        'class',
        'pri',
        'event_id',
        'reference',
        'p1',
        'tv_sec',
        'p1a',
        'tv_usec',
        'p1b',
        'p2',
        'tv_sec2',
        'p2a',
        'tv_usec2',
        'p2b',
        'sip',
        'dip',
        'sp',
        'dp',
        'protocol',
        'flags'
];

our $alert_fields = $alert32_fields;

my $log32_fields = [
        'sig_gen',
        'sig_id',
        'sig_rev',
        'class',
        'pri',
        'event_id',
        'reference',
        'tv_sec',
        'tv_usec',
        'flags',
        'pkt_sec',
        'pkt_usec',
        'caplen',
        'pktlen',
        'pkt',
];

my $log64_fields = [
        'sig_gen',
        'sig_id',
        'sig_rev',
        'class',
        'pri',
        'event_id',
        'reference',
        'p1',
        'tv_sec',
        'p1a',
        'tv_usec',
        'p1b',
        'flags',
        'p2',
        'pkt_sec',
        'p2a',
        'pkt_usec',
        'p2b',
        'caplen',
        'pktlen',
        'pkt',
];

our $log_fields = $log32_fields;

###############################################################
# Close the unified file
###############################################################
sub closeSnortUnified() {
    if ( $UF->{'LOCKED'} ) {
        flock(UFD, LOCK_UN);
    }
    close(UFD);
}

###############################################################
# Open a Snort unified file and return a hash 
# describing the file or undef if we don;t handle it.
# die if we cannot open the file.
###############################################################
sub openSnortUnified($) {
   $UF->{'FILENAME'} = $_[0];
   my $magic = 0;
   die("Cannot open file $UF->{'FILENAME'}\n") unless open(UFD, "<", $UF->{'FILENAME'});
   binmode(UFD);
   # See if we can get an exclusive lock
   # The presumption being that if we can get an exclusive
   # then the file is not actively being written to
   if ( flock(UFD, LOCK_EX | LOCK_NB) ) {
        debug("Got an exclusive lock\n");
       $UF->{'LOCKED'} = 1;
   } else {
       $UF->{'LOCKED'} = 0;
        debug("Did not get an exclusive lock\n");
   }
   
   (undef,undef,undef,undef,undef,undef,undef,$UF->{'FILESIZE'},undef,$UF->{'FILEMTIME'},undef,undef,undef) = stat(UFD);
   $UF->{'FILESIZE'} = (stat(UFD))[7];
   $UF->{'FILEMTIME'} = (stat(UFD))[9];

   read(UFD, $magic, 4);
   $magic = unpack('V', $magic);
  if ( $UF->{'64BIT'} ) {
     debug("Handling unified file with 64bit timevals");
     $log_fields = $log64_fields;
     $alert_fields = $alert64_fields;
     if ( $magic eq $LOGMAGICV ) {
       $UF->{'TYPE'} = 'LOG';
       $UF->{'FIELDS'} = $log_fields;
       $UF->{'RECORDSIZE'} = 20 * 4;
       $UF->{'PACKSTR'} = 'V20';

     } elsif ( $magic eq $LOGMAGICN ) {
       $UF->{'TYPE'} = 'LOG';
       $UF->{'FIELDS'} = $log_fields;
       $UF->{'RECORDSIZE'} = 20 * 4;
       $UF->{'PACKSTR'} = 'N20';

     } elsif ( $magic eq $ALERTMAGICV ) {
       $UF->{'TYPE'} = 'ALERT';
       $UF->{'FIELDS'} = $alert_fields;
       $UF->{'RECORDSIZE'} = (21 * 4) + (2 * 2);
       $UF->{'PACKSTR'} = 'V19v2V2';

     } elsif ( $magic eq $ALERTMAGICN ) {
       $UF->{'TYPE'} = 'ALERT';
       $UF->{'FIELDS'} = $alert_fields;
       $UF->{'RECORDSIZE'} = (21 * 4) + (2 * 2);
       $UF->{'PACKSTR'} = 'N19n2N2';

     } else {
       close(UFD);
       $UF = undef;
       return $UF
     }
  } else { # assume 32bit
     debug("Handling unified file with 32bit timevals");
     $log_fields = $log32_fields;
     $alert_fields = $alert32_fields;
     if ( $magic eq $LOGMAGICV ) {
       debug("LOGMAGICV");
       $UF->{'TYPE'} = 'LOG';
       $UF->{'FIELDS'} = $log_fields;
       $UF->{'RECORDSIZE'} = 14 * 4;
       $UF->{'PACKSTR'} = 'V14';

     } elsif ( $magic eq $LOGMAGICN ) {
       debug("LOGMAGICN");
       $UF->{'TYPE'} = 'LOG';
       $UF->{'FIELDS'} = $log_fields;
       $UF->{'RECORDSIZE'} = 14 * 4;
       $UF->{'PACKSTR'} = 'N14';

     } elsif ( $magic eq $ALERTMAGICV ) {
       debug("ALERTMAGICV");
       $UF->{'TYPE'} = 'ALERT';
       $UF->{'FIELDS'} = $alert_fields;
       $UF->{'RECORDSIZE'} = (15 * 4) + (2 * 2);
       $UF->{'PACKSTR'} = 'V13v2V2';

     } elsif ( $magic eq $ALERTMAGICN ) {
       debug("ALERTMAGICN");
       $UF->{'TYPE'} = 'ALERT';
       $UF->{'FIELDS'} = $alert_fields;
       $UF->{'RECORDSIZE'} = (15 * 4) + (2 * 2);
       $UF->{'PACKSTR'} = 'N13n2N2';

     } else {
       close(UFD);
       $UF = undef;
       return $UF
     }
  }
  readSnortUnifiedHeader($UF);

  return $UF;
}

###############################################################
# Read a record from the unified file and return it
# undef if we read 0 from file
# return @array,$HASH
###############################################################
sub readSnortUnifiedRecord() {
    my $buffer = '';
    my $readsize = 0;
    my $pktsize = 0;
    my $size = 0;
    my $mtime = 0;
    my $fsize;
    my @fields;
    my $i=0;

    $UF_Record = undef;

    # It seems that writing into the array as follows is approximately 3x slower
    # than reading into an array and converting
    # ($a,$b,$c) = ( unpack('NNN',$buffer)
   
    # this should handle partials and active files
    while ( ( $readsize += read(UFD, $buffer, $UF->{'RECORDSIZE'}, $readsize) ) != $UF->{'RECORDSIZE'} ) {
        $fsize = (stat(UFD))[7];
        $mtime = (stat(UFD))[9];
        
        if ( ( $mtime eq $UF->{'FILEMTIME'} ) && 
             ( $fsize eq $UF->{'FILESIZE'} ) && 
             ( $UF->{'FILEPOS'} eq $UF->{'FILESIZE'}) ) {
            # the file is not modified or changed in size and we are at the end
            # so presumably is time to bail
            debug("Checking for end of file in read record\n");
            if ( ! flock(UFD, LOCK_EX | LOCK_NB) && ! $UF->{'LOCKED'} ) {
                # Cannot get an exclusive. Must still be open
                sleep $UF->{'PATIENCE'};
                $UF->{'FILEMTIME'} = $mtime;
                $UF->{'FILESIZE'} = $fsize;
                if ( $UF->{'FILEPOS'} gt $UF->{'FILESIZE'} ) { $UF->{'FILESIZE'} = $UF->{'FILEPOS'}; }
                debug("Couldn't get an exclusive flock in readrecord\n");
                next;
            }
            $UF->{'LOCKED'} = 1;
            # Got the lock time to go.
            return undef;
        } else {
            # Wait some time and try again
            debug("Waiting in readrecord\n");
            sleep $UF->{'PATIENCE'};
            $UF->{'FILEMTIME'} = $mtime;
            $UF->{'FILESIZE'} = $fsize;
            if ( $UF->{'FILEPOS'} gt $UF->{'FILESIZE'} ) { $UF->{'FILESIZE'} = $UF->{'FILEPOS'}; }
       }
    }
    $UF->{'FILEPOS'} += $readsize;

    # if ( read(UFD, $buffer, $UF->{'RECORDSIZE'}) != $UF->{'RECORDSIZE'}) { return undef; }
    @fields = unpack($UF->{'PACKSTR'}, $buffer);
    if ( $debug ) {
        $i = 0;
        foreach my $field (@{$UF->{'FIELDS'}}) {
            debug(sprintf("Field %s is %x\n", $field, @fields[$i++]));
        }
    }
    $i = 0;
    foreach my $field (@{$UF->{'FIELDS'}}) {
        if ( $field eq 'pkt' ) {
        debug(sprintf("FETCHING PACKET OF SIZE %d IN readSnortUnifiedRecord\n", $UF_Record->{'caplen'}));
            # caplen should have been defined by now
            # if not then things are really fsckd up
            if ( ( $pktsize += read(UFD, $UF_Record->{$field}, $UF_Record->{'caplen'}, $pktsize) ) != $UF_Record->{'caplen'} ) {
                while ( ( $pktsize += read(UFD, $UF_Record->{$field}, $UF_Record->{'caplen'}, $pktsize) ) != $UF_Record->{'caplen'} ) {
                    debug(sprintf("READ %d bytes so far as packet ENTERING SLEEP\n", $pktsize));
                    # Wait some time and try again
                    $fsize = (stat(UFD))[7];
                    $mtime = (stat(UFD))[9];
                    if ( ( $mtime eq $UF->{'FILEMTIME'} ) && 
                         ( $fsize eq $UF->{'FILESIZE'} ) && 
                         ( $UF->{'FILEPOS'} eq $UF->{'FILESIZE'}) ) {
                       return undef;
                    } else {
                        debug(sprintf("READ %d bytes as packet\n", $pktsize));
                        $UF->{'FILEMTIME'} = $mtime;
                        $UF->{'FILESIZE'} = $fsize;
                        if ( $UF->{'FILEPOS'} gt $UF->{'FILESIZE'} ) { $UF->{'FILESIZE'} = $UF->{'FILEPOS'}; }
                        sleep $UF->{'PATIENCE'};
                    }
                }
            }
            debug(sprintf("READ %d bytes as packet\n", $pktsize));
            $UF->{'FILEPOS'} += $pktsize;
        } else {
            debug(sprintf("SETTING FIELD %s with data %d long\n", $field, length(@fields[$i])));
            $UF_Record->{$field} = @fields[$i++];
        }
    }

    return $UF_Record;
}

###############################################################
# Populate the header information for the unified file
###############################################################
sub readSnortUnifiedHeader($) {
    my $h = $_[0];
    my $buff;
    my $header = 0;
  
    # Reset at beginning of file
    seek(UFD,0,0);

    if ( $h->{'TYPE'} eq 'LOG' ) {
        $header += read(UFD, $buff, 4);
        $h->{'MAGIC'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 2);
        $h->{'VERSION_MAJOR'} = unpack($h->{'2'}, $buff);
        $header += read(UFD, $buff, 2);
        $h->{'VERSION_MINOR'} = unpack($h->{'2'}, $buff);
        $header += read(UFD, $buff, 4);
        $h->{'TIMEZONE'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 4); 
        $h->{'SIG_FLAG'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 4);
        $h->{'SLAPLEN'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 4);
        $h->{'LINKTYPE'} = unpack($h->{'4'}, $buff);
    } else {
        $header += read(UFD, $buff, 4);
        $h->{'MAGIC'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 4);
        $h->{'VERSION_MAJOR'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 4);
        $h->{'VERSION_MINOR'} = unpack($h->{'4'}, $buff);
        $header += read(UFD, $buff, 4);
        $h->{'TIMEZONE'} = unpack($h->{'4'}, $buff);
    }
    $UF->{'FILEPOS'} = $header;
}

sub print_packet_data($) {
    print format_packet_data($_[0]);
}

sub format_packet_data($) {
    my $data = $_[0];
    my $buff = '';
    my $hex = '';
    my $ascii = '';
    my $len = length($data);
    my $count = 0;
    my $ret = "";

    for (my $i = 0;$i < length($data);$i += 16) {
       $buff = substr($data,$i,16);
       $hex = join(' ',unpack('H2 H2 H2 H2 H2 H2 H2 H2 H2 H2 H2 H2 H2 H2 H2 H2',$buff));
       $ascii = unpack('a16', $buff);
       $ascii =~ tr/A-Za-z0-9;:"'.,<>[]\\|?\/`~!@#$%^&*()_\-+={}/./c;
       $ret = sprintf("%.4X: %-50s%s\n", $count, $hex, $ascii);
       $count += length($buff);
    }
  return $ret;
}

sub get_msg($$$$) {
    my $sids = $_[0];
    my $gen = $_[1];
    my $id = $_[2];
    my $rev = $_[3];

    if ( defined $sids->{$gen}->{$id}->{'msg'} ) {
        if ( defined $sids->{$gen}->{$id}->{$rev}->{'msg'} ) {
            return $sids->{$gen}->{$id}->{$rev}->{'msg'};
        } else {
            return $sids->{$gen}->{$id}->{'msg'};
        }
    } else {
        return "RULE MESSAGE UNKNOWN";
    }
}

sub get_snort_sids($$) {
    my $sidfile = $_[0];
    my $genfile = $_[1];
    my @sid;
    my $sids; 
    my @generator;

    if (! -r $sidfile)
    {
      print "WARNING: \"$sidfile\" does not exist or is not readable. Ignoring. \n";
    }

    if (! -r $genfile)
    {
      print "WARNING: \"$genfile\" does not exist or is not readable. Ignoring. \n";
    }



    return undef unless open(FD, "<", $sidfile);
    while (<FD>) {
        s/#.*//;
        next if /^(\s)*$/;
        chomp;
        @sid = split(/\s\|\|\s/);
        $sids->{1}->{@sid[0]}->{'msg'} = @sid[1];
        $sids->{1}->{@sid[0]}->{'reference'} = @sid[2..$#sid];
    }
    close(FD);

    return $sids unless open(FD, "<", $genfile);
    while (<FD>) {
        s/#.*//;
        next if /^(\s)*$/;
        chomp;
        @generator = split(/\s\|\|\s/);
        $sids->{@generator[0]}->{@generator[1]}->{'msg'} = @generator[2];
    }
    return $sids;
}

sub print_snort_sids($) {
    my $sids = $_[0];

    foreach my $gen (keys %{$sids}) {
      foreach my $sid (keys %{$sids->{$gen}}) {
        print("$gen:$sid || " . get_msg($sids,$gen,$sid,0) );
        foreach my $ref ($sids->{$gen}->{$sid}->{'reference'}) {
            print(" || $ref") if defined $ref;
        }
      print("\n");
      }
    }
}

sub get_class($$) {
    my $class = $_[0];
    my $classid = $_[1];
    
    if ( defined $class->{$classid}->{'name'} ) {
        return $class->{$classid}->{'name'};
    } else {
        return "UNKNOWN";
    }
}

sub get_snort_classifications ($) {
    my $file = $_[0];
    my @classification;
    my $class;
    my $classid = 0;

    if (! -r $file)
    {
      print "WARNING: \"$file\" does not exist or is not readable. Ignoring. \n";
    }

    return undef unless open(FD, "<", $file);
    while (<FD>) {
        s/#.*//;
        next if /^(\s)*$/;
        chomp;
        @classification = split(/:/);
        @classification = split(/,/,@classification[1]);
        $class->{$classid}->{'type'} = @classification[0];
        $class->{$classid}->{'name'} = @classification[1];
        $class->{$classid}->{'priority'} = @classification[2];
        $classid++;
    } 
    close(FD);

    return $class;
}

sub print_snort_classifications($) {
    my $class = $_[0];

    foreach my $key (keys %{$class}) {
       print("Classification ID       : $key\n");
       print("Classification TYPE     : $class->{$key}->{'type'}\n");
       print("Classification NAME     : $class->{$key}->{'name'}\n");
       print("Classification PRIORITY : $class->{$key}->{'priority'}\n");
       print("\n");
    }
}

sub get_priority($$$) {
    my $class = $_[0];
    my $classid = $_[1];
    my $pri = $_[2];
    
    if ( $pri gt 0 ) {
        return $pri; 
    } else {
        if ( $class->{$classid}->{'priority'} gt 0 ) {
            return $class->{$classid}->{'priority'}; 
        } else {
            return 0;
        }
    }
}

sub print_alert($$$) {
    print format_alert($_[0], $_[1], $_[2]);
    print("------------------------------------------------------------------------\n");
}

sub format_alert($$$) {
    my $rec = $_[0];
    my $sids = $_[1];
    my $class = $_[2];
    my $ret = "";
    my $d = Dumpvalue->new();

    my $time = localtime($rec->{'tv_sec'});

    #print "source ip = $rec->{'sip'}\n";
    #$d->dumpValue($rec->{'sip'});
    #die "\n";

    $ret = sprintf("%s {%s} %s:%d -> %s:%d\n" .
            "[**] [%d:%d:%d] %s [**]\n" .
            "[Classification: %s] [Priority: %d]\n", $time,
            $IP_PROTO_NAMES->{$rec->{'protocol'}},
            inet_ntoa(pack('N', $rec->{'sip'})),
            $rec->{'sp'}, inet_ntoa(pack('N', $rec->{'dip'})),
            $rec->{'dp'}, $rec->{'sig_gen'}, $rec->{'sig_id'},
            $rec->{'sig_rev'},
            get_msg($sids,$rec->{'sig_gen'},$rec->{'sig_id'},$rec->{'sig_rev'}),
            get_class($class,$rec->{'class'}),
            get_priority($class,$rec->{'class'},$rec->{'priority'}));
    
    foreach my $ref ($sids->{$rec->{'sig_gen'}}->{$rec->{'sig_id'}}->{'reference'}) {
        if ( defined $ref ) {
            $ret = $ret . sprintf("[Xref => %s]\n", $ref);
        } else {
            $ret = $ret . sprintf("[Xref => None]\n");
        }
    }
    return $ret;
}

sub print_log($$$) {
    print format_log($_[0], $_[1], $_[2]);
    print("=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+\n\n");
}

sub format_log($$$) {
    my $rec = $_[0];
    my $sids = $_[1];
    my $class = $_[2];
    my $eth_obj;
    my $l2_obj;
    my $ip_obj;
    my $tcp_obj;
    my $udp_obj;
    my $icmp_obj;
    my $time = localtime($rec->{'pkt_sec'});
    my $ret = "";

    $ret = sprintf("[**] [%d:%d:%d] %s [**]\n[Classification: %s] [Priority: %d]\n",
            $rec->{'sig_gen'}, $rec->{'sig_id'}, $rec->{'sig_rev'},
            get_msg($sids,$rec->{'sig_gen'},$rec->{'sig_id'},$rec->{'sig_rev'}),
            get_class($class,$rec->{'class'}),
            get_priority($class,$rec->{'class'},$rec->{'priority'}));
   
    foreach my $ref ($sids->{$rec->{'sig_gen'}}->{$rec->{'sig_id'}}->{'reference'}) {
        if ( defined $ref ) {
            $ret = $ret . sprintf("[Xref => %s]\n", $ref);
        } else {
            $ret = $ret . sprintf("[Xref => None]\n");
        }
    }
    
    $ret = $ret . sprintf("Event ID: %lu     Event Reference: %lu\n",
            $rec->{'event_id'}, $rec->{'reference'});

    ## xxx jl
    my $is_ipv4 = 0;
    if ($linklayer_type eq "eth")
    {
       print "Linklayer type is ethernet.\n";
       $l2_obj = new Net::Packet::ETH(raw => $rec->{'pkt'});
       if (($l2_obj->isTypeIpv4))
       {
         $is_ipv4 = 1;
       }
    }
    elsif($linklayer_type eq "sll")
    {
      print "Linklayer type is sll.\n";
      $l2_obj = new Net::Packet::SLL(raw => $rec->{'pkt'});
      if ($l2_obj->isProtocolIpv4)
      {
        $is_ipv4 = 1;
      }
    }


    if ($is_ipv4 == 1)
    {
        print "Yes, IPv4.\n";
        $ip_obj = new Net::Packet::IPv4(raw => $l2_obj->payload);
        
        if (! $ip_obj->isProtocolTcp && ! $ip_obj->isProtocolUdp) {
            $ret = $ret . sprintf("%s %s -> %s", $time, $ip_obj->src, $ip_obj->dst);
        } else {
            if ($ip_obj->isProtocolTcp) {
                $tcp_obj = new Net::Packet::TCP(raw => $ip_obj->payload);
                $ret = $ret . sprintf("%s %s:%d -> %s:%d\n", 
                    $time, 
                    $ip_obj->src,
                    $tcp_obj->src,
                    $ip_obj->dst,
                    $tcp_obj->dst);
            } elsif ($ip_obj->isProtocolUdp) {
                $udp_obj = new Net::Packet::UDP(raw => $ip_obj->payload);
                $ret = $ret . sprintf("%s %s:%d -> %s:%d\n",
                $time,
                $ip_obj->src,
                $udp_obj->src,
                $ip_obj->dst,
                $udp_obj->dst);
            } else {
                # Should never get here
                print("DEBUGME: Why am I here - IP Header Print\n");
            } 
        }
        $ret = $ret . sprintf("%s TTL:%d TOS:0x%X ID:%d IpLen:%d DgmLen:%d",
                $IP_PROTO_NAMES->{$ip_obj->protocol},
                $ip_obj->ttl,
                $ip_obj->tos,
                $ip_obj->id,
                $ip_obj->length - $ip_obj->hlen,
                $ip_obj->length);
        
        if ( $ip_obj->flags & $PKT_RB_FLAG ) {
            $ret = $ret . sprintf(" RB");
        }

        if ($ip_obj->haveFlagDf) {
            $ret = $ret . sprintf(" DF");
        }

        if ($ip_obj->haveFlagMf) {
            $ret = $ret . sprintf(" MF");
        }

        $ret = $ret . sprintf("\n");
        
        if ($ip_obj->getOptionsLength gt 0) {
            my $IPOptions = decodeIPOptions($ip_obj->options);
            foreach my $ipoptkey ( keys %{$IPOptions} ) {
                $ret = $ret . sprintf("IP Option %d : %s\n", $ipoptkey, $IPOptions->{'name'});
                $ret = $ret . format_packet_data($IPOptions->{'data'});
            }
        }

        if ( $ip_obj->flags & 0x00000001 ) {
            $ret = $ret . sprintf("Frag Offset: 0x%X   Frag Size: 0x%X",
                   $ip_obj->offset & 0xFFFF, 
                   $ip_obj->length);
        }

        if ($ip_obj->isProtocolTcp) {
            $ret = $ret . sprintf("%s%s%s%s%s%s%s%s", 
            $tcp_obj->haveFlagCwr ? "1" : "*",
            $tcp_obj->haveFlagEce ? "2" : "*",
            $tcp_obj->haveFlagUrg ? "U" : "*",
            $tcp_obj->haveFlagAck ? "A" : "*",
            $tcp_obj->haveFlagPsh ? "P" : "*",
            $tcp_obj->haveFlagRst ? "R" : "*",
            $tcp_obj->haveFlagSyn ? "S" : "*",
            $tcp_obj->haveFlagFin ? "F" : "*");
            $ret = $ret . sprintf(" Seq: 0x%lX  Ack: 0x%lX  Win: 0x%X  TcpLen: %d",
                   $tcp_obj->seq,
                   $tcp_obj->ack,
                   $tcp_obj->win,
                   $tcp_obj->length);

            if ($tcp_obj->haveFlagUrg gt 0)
            {
                $ret = $ret . sprintf("  UrgPtr: 0x%X", $tcp_obj->urp);
            }
            $ret = $ret . sprintf("\n");
            
            if ($tcp_obj->getOptionsLength gt 0)
            {
                my $TCPOptions = decodeTCPOptions($tcp_obj->options);
                foreach my $tcpoptkey ( keys %{$TCPOptions} ) {
                    $ret = $ret . sprintf("TCP Option %d : %s\n", $tcpoptkey, $TCPOptions->{$tcpoptkey}->{'name'});
                    $ret = $ret . format_packet_data($TCPOptions->{$tcpoptkey}->{'data'});
                }
            }
        } elsif ($ip_obj->isProtocolUdp) {
            $udp_obj = new Net::Packet::UDP(raw => $ip_obj->payload);
            $ret = $ret . sprintf("Len: %d\n", $udp_obj->length); 
        } elsif ($ip_obj->isProtocolIcmpv4) {
            $icmp_obj = new Net::Packet::ICMPv4(raw => $ip_obj->payload);
            $ret = $ret . sprintf("Type:%d  Code:%d  %s\n", $icmp_obj->type, $icmp_obj->code, $ICMP_TYPES->{$icmp_obj->type});
        } else {
            # Should never get here
            print("DEBUGME: Why am I here - TCP/UDP/ICMP Header print\n");
        }
    } else {
        $ret = $ret . sprintf("Layer 3 protocol type %i not decoded.  Raw packet dumped\n",
                $eth_obj->type);
        $ret = $ret . format_packet_data($eth_obj->payload);
    }

    return $ret;
}

sub decodeIPOptions($) {
    my $optdata = $_[0];
    my $opthash;

    if ( length($optdata) gt 0 ) {
        my @bytes = unpack("C*", $optdata);
        my $bytepos = 0;
        my $optionlen = length($optdata);
        my $number;
        my $copy;
        my $class;
        my $name;
        my $length;
        my $data;

        while ( $bytepos < $optionlen ) {
            $number = ( @bytes[$bytepos] & 0x1F );
            $copy = ( @bytes[$bytepos] & 0x80 ) >> 7;
            $class = ( @bytes[$bytepos] & 0x60 ) >> 5;
            $name = $IP_OPT_MAP->{$number}->{'name'};
            if ( $IP_OPT_MAP->{$number}->{'length'} eq 0 ) {
                # Length is actual len for entire option
                $length = @bytes[$bytepos+1];
                debug("IP Option len is $length\n");
                if ( $length le 0 || $length gt ( $optionlen - $bytepos )) {
                    #something odd
                    $length = $optionlen - $bytepos;
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos = $optionlen;
                    debug("IP Option len is $length\n");
                } else {
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos = $length;
                }
            } elsif ( $IP_OPT_MAP->{$number}->{'length'} gt 0 ) {
                $data = substr($optdata, $bytepos, $IP_OPT_MAP->{$number}->{'length'});
                # length is fixed. skip the data we've got all we need
                $bytepos = $bytepos + $IP_OPT_MAP->{$number}->{'length'};
            } elsif ( exists $IP_OPT_MAP->{$number}->{'length'} ) {
                # there is no length or data. it just exists.
                $data = @bytes[$bytepos];;
                $length = 0;
            } else {
                # Treat it as an option with a length
                $length = @bytes[$bytepos+1];
                if ( $length le 0 || $length gt ( $optionlen - $bytepos ) ) {
                    # something odd
                    $length = $optionlen - $bytepos;
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos = $optionlen;
                } else {
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos = $length;
                }
            }
            $opthash->{$number}->{'name'} = $name;
            $opthash->{$number}->{'length'} = $length;
            $opthash->{$number}->{'copy'} = $copy;
            $opthash->{$number}->{'class'} = $class;
            $opthash->{$number}->{'data'} = $data;
            debug("IP Option len is $length\n");
        }
   }
   return $opthash;
}

sub decodeTCPOptions($) {
    my $optdata = $_[0];    
    my $opthash;

    if ( length($optdata) gt 0 ) {
        my @bytes = unpack("C*", $optdata);
        my $bytepos = 0;
        my $optionlen = length($optdata);
        my $number;
        my $name;
        my $length;        
        my $data;

        debug(sprintf("START TCPOPT Option Length is %d\n", $optionlen));
        while ( $bytepos < $optionlen ) {
            $number = @bytes[$bytepos];
        debug(sprintf("TCPOPT OPT Number is %d\n", $number));
            
            $name = exists $TCP_OPT_MAP->{$number}->{'name'}?$TCP_OPT_MAP->{$number}->{'name'}:"UNKNOWN";
        debug(sprintf("TCP OPT Name maps to %s\n", $name));
            if ( $TCP_OPT_MAP->{$number}->{'length'} eq 0 ) {                
                # Length is actual len for entire option
                $length = @bytes[$bytepos+1];
                debug(sprintf("TCP OPT LEN eq 0 Read Length is %d\n", $length));
                if ( $length le 0 || $length gt ( $optionlen - $bytepos )) {
                    # something odd
                    $length = $optionlen - $bytepos;
                    debug(sprintf("TCPOPT len le 0 gt optlen is %d\n", $length));
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos = $optionlen;
                } else {
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos += $length;
                }
            } elsif ( $TCP_OPT_MAP->{$number}->{'length'} gt 0 ) {
                $data = substr($optdata, $bytepos, $TCP_OPT_MAP->{$number}->{'length'});
                # length is fixed. skip the data we've got all we need                
                $length = $TCP_OPT_MAP->{$number}->{'length'};
                debug(sprintf("TCP OPT LEN FIXED  Length is %d\n", $length));
                $bytepos += $length;
            } elsif ( exists $TCP_OPT_MAP->{$number}->{'length'} ) {
                # there is no length or data. it just exists.
                $data = @bytes[$bytepos];
                $length = 0;
                $bytepos += 1;
            } else {
                # Treat it as an option with a length
                $length = @bytes[$bytepos+1];
                debug(sprintf("TCP OPT LEN ELSE CONDITION Option Length is %d\n", $length));
                if ( $length le 0 || $length gt ( $optionlen - $bytepos ) ) {
                    # something odd
                    $length = $optionlen - $bytepos;
                    debug(sprintf("TCP OPT LEN ELSE len le 0 gt optlen Option Length is %d\n", $length));
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos = $optionlen;
                } else {
                    $data = substr($optdata, $bytepos, $length);
                    $bytepos += $length;
                }
            }
            $opthash->{$number}->{'name'} = $name;
            $opthash->{$number}->{'length'} = $length;
            $opthash->{$number}->{'data'} = $data;
            debug(sprintf("TCPOPTLEN FINAL LEN Option Length is %d\n", $length));
        }
   }
   return $opthash;

}

###############################################################
# sub debug() {
# Prints message passed to STDERR wrapped in line markers
#
# Parameters: $msg is the debug message to print
#
# Returns: Nothing
#
# TODO:
###############################################################
sub debug($) {
    return unless $debug;
    my $msg = $_[0];
        my $package = undef;
        my $filename = undef;
        my $line = undef;
        ($package, $filename, $line) = caller();
    print STDERR $filename . ":" . $line . " : " . $msg . "\n";
}


1;
