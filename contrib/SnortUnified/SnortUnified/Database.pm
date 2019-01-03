package SnortUnified::Database;

#########################################################################################
#  $VERSION = "SnortUnified to MySql 1.0 - Copyright (c) 2006 Jason Brvenik";
# 
# A Perl module to insert snort data from a unified file into a mysql database.
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
#########################################################################################
# Changes:
#########################################################################################
# TODO: in no specific order
#  - Documentation
# 
#########################################################################################
# NOTES:
#########################################################################################
# NOTE to self:
# 
# I've chosen to use internal globals so that routines can be selectively overridden
# in the future if needed without a lot of parameter passing.
# 
# EG: You could use this method to choose a routine to map into
# instead of the default.
# *{getSnortSensorID} = sub { SomeDatabaseGetSnortSensorID();};
#
# This will be useful _if_ there is a quirk somewhere that DBI does not handle
# and there is no easy way to fix it in teh existing routine.
#########################################################################################

use strict;
require Exporter;
use vars qw($VERSION @ISA @EXPORT @EXPORT_OK %EXPORT_TAGS);

use DBI;
use POSIX qw(strftime); 
use Net::Packet::Consts qw(:DEFAULT);
use Net::Packet::ETH;
use Net::Packet::IPv4;
use Net::Packet::TCP;
use Net::Packet::UDP;
use Net::Packet::ICMPv4;
use Socket;
use Dumpvalue;

# Cf. /usr/lib/perl5/vendor_perl/5.10.0/Net/Packet/Consts.pm
# perldoc Net::Packet::Consts
# perldoc -m Net::Packet::Consts
our $ETH_TYPE_IP = Net::Packet::Consts::NP_ETH_TYPE_IPv4;
our $IP_PROTO_TCP = Net::Packet::Consts::NP_DESC_IPPROTO_TCP;
our $IP_PROTO_UDP = Net::Packet::Consts::NP_DESC_IPPROTO_UDP;
our $IP_PROTO_ICMP = Net::Packet::Consts::NP_DESC_IPPROTO_ICMPv4;

my $class_self;

BEGIN {
   $class_self = __PACKAGE__;
   $VERSION = "1.3devel-jl1";
}
my $DBLICENSE = "GNU GPL see http://www.gnu.org/licenses/gpl.txt for more information.";
sub DBVersion() { "$class_self v$VERSION - Copyright (c) 2006 Jason Brvenik" };
sub DBLicense() { DBVersion . "\nLicensed under the $DBLICENSE" };

# Pollute global namespace
@ISA = qw(Exporter);
@EXPORT = qw(
                DBLicense
                DBVersion
                getSnortDBHandle
                closeSnortDBHandle
                setSnortConnParam
                printSnortConnParams
                getSnortSensorID
                insertSnortAlert
                insertSnortLog
);

@EXPORT_OK = qw(
                $DB_INFO
                $SIG_ID_MAP
                printSnortSigIdMap
                $DBH
             );

%EXPORT_TAGS = (
               ALL => [@EXPORT, @EXPORT_OK],
);


our $DBH = undef;
our $SIG_ID_MAP = undef;
our $CLASS_ID_MAP = undef;
our $DB_INFO = { 
                'type'           => 'mysql',
                'host'           => 'localhost',
                'port'           => 3306,
                'database'       => 'snort',
                'user'           => 'snortuser',
                'password'       => 'snortpass',
                'connstr'        => 'DBI:mysql:database=snort;host=localhost;port=3306',
                'sensor_id'      => 0,
                'linktype'       => 0,
                'interface'      => '',
                'hostname'       => 'localhost',
                'filter'         => '',
                'payload'        => 1,
                'event_id'       => 0,
              };

my $REQUIRED_SCHEMA = 106;
my $SIG_MAP_H = undef;
my $CLASS_MAP_H = undef;
my $REF_MAP_H = undef;
my $EVENT_INS_H = undef;
my $IPH_INS_H = undef;
my $TCP_INS_H = undef;
my $UDP_INS_H = undef;
my $ICMP_INS_H = undef;
my $IPHDR_INS_H = undef;
my $TCPHDR_INS_H = undef;
my $UDPHDR_INS_H = undef;
my $ICMPHDR_INS_FULL_H = undef;
my $ICMPHDR_INS_H = undef;
my $REFERENCE_INS_H = undef;
my $PAYLOAD_INS_H = undef;
my $PAYLOAD_WITH_FLOP_INS_H = undef;
my $IP_OPTIONS_INS_H = undef;
my $TCP_OPTIONS_INS_H = undef;


sub setSnortConnParam($$) {
    my $parm = $_[0];
    my $val = $_[1];

    $DB_INFO->{$parm} = $val;
    if ( $DB_INFO->{'type'} eq 'mysql' ) {
        $DB_INFO->{'connstr'} = "DBI:" . $DB_INFO->{'type'} . 
            ":database=" . $DB_INFO->{'database'} . 
            ";host=" . $DB_INFO->{'host'} .
            ";port=" . $DB_INFO->{'port'} .
            ";";
    } else {
        print("Database " . $DB_INFO->{'database'} . " not supported\n");
    }

}

sub printSnortConnParams() {
    my $sid;
    my $rev;
    foreach my $key ( keys %{$DB_INFO} ) {
        print("$key     \t: " . $DB_INFO->{$key} . "\n");
    }
}

sub printSnortSigIdMap() {
    print("Dumping sid->sig map\n");
    foreach my $key (sort keys %{$SIG_ID_MAP}) {
        print("$key\t: " . $SIG_ID_MAP->{$key} . "\n");
    }
}

sub printSnortClassIdMap() {
    print("Dumping class->id map\n");
    foreach my $key ( sort keys %{$CLASS_ID_MAP} ) {
        print("$CLASS_ID_MAP->{$key}:$key\n");
    }
}

sub getSnortDBHandle() {

    my $schema = 0;

    $DBH = DBI->connect($DB_INFO->{'connstr'}, $DB_INFO->{'user'}, $DB_INFO->{'password'}) or die "ERROR: Connecting with the database has failed. Exiting."; 
   
    ($schema) = $DBH->selectrow_array("SELECT max(vseq) from `schema`");
    
    if ( $schema lt $REQUIRED_SCHEMA ) { 
        print("Schema Version \"" . $schema . "\" too old\n"); 
        return 0;
    } else {
        return 1;
    }
}

sub closeSnortDBHandle() {

    $DBH->disconnect();
}

sub getSnortSensorID() {

    my $sid = 0;
    my $qh;

    $qh = $DBH->prepare("SELECT sid FROM sensor WHERE " . 
                        "hostname=? AND " . 
                        "interface=? AND " .
                        "filter=? AND " . 
                        "detail=? AND " .
                        "encoding='0'");

    $qh->execute($DB_INFO->{'hostname'}, 
                 $DB_INFO->{'interface'}, 
                 $DB_INFO->{'filter'}, 
                 $DB_INFO->{'payload'}) || print("error " . $qh->errstr . "\n");;

    ($sid) = $qh->fetchrow_array();
    
    if ( !defined $sid ) {
        $qh = $DBH->prepare("INSERT INTO sensor(hostname,interface,filter," .
                            "detail,encoding,last_cid) VALUES (?,?,?,?,'0','0')");

        $qh->execute($DB_INFO->{'hostname'}, 
                     $DB_INFO->{'interface'}, 
                     $DB_INFO->{'filter'}, 
                     $DB_INFO->{'payload'}) || print("error " . $qh->errstr . "\n");

        $qh = $DBH->prepare("SELECT sid FROM sensor WHERE " . 
                            "hostname=? AND " . 
                            "interface=? AND " . 
                            "filter=? AND " . 
                            "detail=? AND " .
                            "encoding='0'");

        $qh->execute($DB_INFO->{'hostname'}, 
                     $DB_INFO->{'interface'}, 
                     $DB_INFO->{'filter'}, 
                     $DB_INFO->{'payload'}) || print("error " . $qh->errstr . "\n");

        ($sid) = $qh->fetchrow_array();
    }

    $DB_INFO->{'sensor_id'} = $sid;

    # get the next event ID for this sensor
    ($DB_INFO->{'event_id'}) = $DBH->selectrow_array("SELECT max(cid) FROM " . 
                                                     "event WHERE sid=" . $sid);
    $DB_INFO->{'event_id'}++;

    # build the sig_id map and cache it for use
    # XXX - Note: This differs from barnyard somewhat...
    # barnyard uses the rule text in combination with the sid:rev
    # I'm using gen:sid:rev ignoring the message text as that will be pulled 
    # from the sidmap at the time of insert

    $qh = $DBH->prepare("SELECT sig_gid,sig_sid,sig_rev,sig_id,sig_name FROM signature");
    $qh->execute;

    my $sidref;
    while ( $sidref = $qh->fetchrow_hashref() ) {
        my $gensid = $sidref->{'sig_gid'}.":".$sidref->{'sig_sid'};
        $SIG_ID_MAP->{$gensid}->{'id'} = $sidref->{'sig_id'};
        $SIG_ID_MAP->{$gensid}->{'msg'} = $sidref->{'sig_name'};
        $SIG_ID_MAP->{$gensid}->{'gid'} = $sidref->{'sig_gid'};
        $SIG_ID_MAP->{$gensid}->{'sid'} = $sidref->{'sig_sid'};
    }

    # Build the classification map
    $qh = $DBH->prepare("SELECT sig_class_id, sig_class_name FROM sig_class");
    $qh->execute;
    my $classref;
    while ( $classref = $qh->fetchrow_hashref() ) {
        $CLASS_ID_MAP->{$classref->{'sig_class_name'}} = $classref->{'sig_class_id'};
    }

    return $sid;
}

sub getSigID($$) {
    my $record = $_[0];
    my $sids = $_[1];
    my $sidref;
    my $gensid = "$record->{'sig_gen'}:$record->{'sig_id'}";
    my $msg = $sids->{$record->{'sig_gen'}}->{$record->{'sig_id'}}->{'msg'};

    # sometimes we get events for things that were not updated
    # in sid-msg.map 
    # Most commonly this is for local rules
    # Using UNKNOWN for the sig message in this case
    $msg = defined $msg?$msg:"UNKNOWN";

    if ( defined $SIG_ID_MAP->{$gensid}->{'id'} ) {
        return $SIG_ID_MAP->{$gensid}->{'id'};
    }

    # in case someone slipped it in on us
    my $qh = $DBH->prepare("SELECT sig_gid,sig_sid,sig_rev,sig_id,sig_name FROM signature " .
                           "WHERE sig_gid=? AND sig_sid=?");
    $qh->execute($record->{'sig_gen'}, $record->{'sig_id'});
    $sidref = $qh->fetchrow_hashref();

    if ( !defined $sidref ) {
        if ( !defined $SIG_MAP_H ) {
            $SIG_MAP_H = $DBH->prepare("INSERT INTO " . 
               "signature(sig_name, sig_class_id, sig_priority, sig_rev, sig_sid, sig_gid) " . 
               "VALUES(?, ?, ?, ?, ?, ?)");
        }

        $SIG_MAP_H->execute($msg,
                   $record->{'class'},
                   $record->{'pri'},
                   $record->{'sig_rev'},
                   $record->{'sig_id'},
                   $record->{'sig_gen'});

        $qh->execute($record->{'sig_gen'}, $record->{'sig_id'});
        $sidref = $qh->fetchrow_hashref();

        $SIG_ID_MAP->{$gensid}->{'id'} = $sidref->{'sig_id'};
        $SIG_ID_MAP->{$gensid}->{'msg'} = $sidref->{'sig_name'};
        $SIG_ID_MAP->{$gensid}->{'gid'} = $sidref->{'sig_gid'};
        $SIG_ID_MAP->{$gensid}->{'sid'} = $sidref->{'sig_sid'};
        
        # XXX - Need to add reference handling
    } else {
        $SIG_ID_MAP->{$gensid}->{'id'} = $sidref->{'sig_id'};
        $SIG_ID_MAP->{$gensid}->{'msg'} = $sidref->{'sig_name'};
        $SIG_ID_MAP->{$gensid}->{'gid'} = $sidref->{'sig_gid'};
        $SIG_ID_MAP->{$gensid}->{'sid'} = $sidref->{'sig_sid'};
    }

    return $SIG_ID_MAP->{$gensid}->{'id'};
}

sub getClassID($$) {
    my $record = $_[0];
    my $class = $_[1];
    my $classid;
    my $msg = $class->{$record->{'class'}}->{'name'};

    $msg = defined $msg?$msg:"UNKNOWN";

    if ( defined $CLASS_ID_MAP->{$msg} ) {
        return $CLASS_ID_MAP->{$msg};
    }

    # In case someone else slipped it in on us
    my $qh = $DBH->prepare("SELECT sig_class_id FROM sig_class where sig_class_name=?");
    $qh->execute($msg);
   
    ($classid) = $qh->fetchrow_array();
    if ( !defined $classid ) {
        if ( !defined $CLASS_MAP_H ) {
            $CLASS_MAP_H = $DBH->prepare("INSERT INTO sig_class(sig_class_name) VALUES(?)");
        }
        $CLASS_MAP_H->execute($msg);
        $qh->execute($msg);
        ($classid) = $qh->fetchrow_array();
        $CLASS_ID_MAP->{$msg} = $classid;
    }
    
    return $CLASS_ID_MAP->{$msg};
}

sub getReferenceID() {
    my $record = $_[0];

}

sub check_handles() {
    if ( !defined $EVENT_INS_H ) {
        $EVENT_INS_H = $DBH->prepare("INSERT INTO " .
                                     "event(sid, cid, signature, timestamp) " .
                                     "VALUES(?, ?, ?, ?)");
    }

    if ( !defined $IPH_INS_H ) {
        $IPH_INS_H = $DBH->prepare("INSERT INTO " .
                                   "iphdr(sid, cid, ip_src, ip_dst, ip_proto) " .
                                   "VALUES(?, ?, ?, ?, ?)");
    }

    if ( !defined $TCP_INS_H ) {
        $TCP_INS_H = $DBH->prepare("INSERT INTO " .
                                   "tcphdr (sid, cid, tcp_sport, tcp_dport, tcp_flags) " .
                                   "VALUES(?, ?, ?, ?, 0)");
    }

    if ( !defined $UDP_INS_H ) {
        $UDP_INS_H = $DBH->prepare("INSERT INTO " .
                                   "udphdr (sid, cid, udp_sport, udp_dport) " .
                                   "VALUES(?, ?, ?, ?)");
    }

    if ( !defined $ICMP_INS_H ) {
        $ICMP_INS_H = $DBH->prepare("INSERT INTO " .
                                    "icmphdr (sid, cid, icmp_type, icmp_code) " .
                                    "VALUES(?, ?, ?, ?)");
    }

    if ( !defined $IPHDR_INS_H ) {
        $IPHDR_INS_H = $DBH->prepare("INSERT INTO iphdr(sid, cid, ip_src, ip_dst, ip_proto, " .
                                 "ip_ver, ip_hlen, ip_tos, ip_len, ip_id, ip_flags, ip_off, ".
                                 "ip_ttl, ip_csum) " .
                                 "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    }
    
    if ( !defined $TCPHDR_INS_H ) {
        $TCPHDR_INS_H = $DBH->prepare("INSERT INTO tcphdr(sid, cid, tcp_sport, tcp_dport, " . 
                                 "tcp_seq, tcp_ack, tcp_off, tcp_res, tcp_flags, tcp_win, " . 
                                 "tcp_csum, tcp_urp) " . 
                                 "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    }
 
    if ( !defined $UDPHDR_INS_H ) {
        $UDPHDR_INS_H = $DBH->prepare("INSERT INTO " . 
                                 "udphdr(sid, cid, udp_sport, udp_dport, udp_len, udp_csum) " .
                                 "VALUES(?, ?, ?, ?, ?, ?)");

    }
    
    if ( !defined $ICMPHDR_INS_FULL_H ) {
        $ICMPHDR_INS_FULL_H = $DBH->prepare("INSERT INTO " . 
                                 "icmphdr(sid, cid, icmp_type, icmp_code, icmp_csum, icmp_id, " . 
                                 "icmp_seq) " . 
                                 "VALUES(?, ?, ?, ?, ?, ?, ?)");
    }

    if ( !defined $ICMPHDR_INS_H ) {
        $ICMPHDR_INS_H = $DBH->prepare("INSERT INTO " . 
                                 "icmphdr(sid, cid, icmp_type, icmp_code, icmp_csum) " . 
                                 "VALUES(?, ?, ?, ?, ?)");
    }

    if ( !defined $PAYLOAD_INS_H ) {
        $PAYLOAD_INS_H = $DBH->prepare("INSERT INTO " .
                                       "data(sid, cid, data_payload)" .
                                       "VALUES(?, ?, ?)");
    }

    if ( !defined $PAYLOAD_WITH_FLOP_INS_H) {
        $PAYLOAD_WITH_FLOP_INS_H = $DBH->prepare("INSERT INTO " .
                                                  "data(sid, cid, data_payload, data_header, pcap_header)" .
                                                  "VALUES(?, ?, ?, ?, ?)");
    }

    if ( !defined $IP_OPTIONS_INS_H) {
        $IP_OPTIONS_INS_H = $DBH->prepare("INSERT INTO " .
                                          "opt (sid, cid, optid, opt_proto, opt_code, opt_len, opt_data) " .
                                          "VALUES(?, ?, ?, ?, ?, ?, ?)"
        );
    }
 
    if ( !defined $TCP_OPTIONS_INS_H) {
        $TCP_OPTIONS_INS_H = $DBH->prepare("INSERT INTO " .
                                           "opt (sid, cid, optid, opt_proto, opt_code, opt_len, opt_data) " . 
                                           "VALUES(?, ?, ?, ?, ?, ?, ?)");
    }
};


sub snort_hexify($)
{
  my $payload_orig = $_[0];
  my $rv = "";

  if ($payload_orig eq "") {return ""};


  for (my $i = 0; $i < length($payload_orig); $i++)
  {
    my $char = substr($payload_orig, $i, 1);
    my $ord_hex = sprintf("%02X", ord($char));
    my $first_nibble = substr($ord_hex, 0, 1);
    my $second_nibble = substr($ord_hex, 1, 1);
    $rv = $rv . $first_nibble . $second_nibble;
  }

  return $rv;
}



sub insertSnortAlert($$$) {
    my $record = $_[0]; # hash with the actual data
    my $sids = $_[1];   # Hash of sids
    my $class = $_[2];  # Hash of classifications
    my $sigid;
    my $classid;
    my $timestamp = strftime("%Y-%m-%d %H:%M:%S", localtime($record->{'tv_sec'}));
    my $gensid = "$record->{'sig_gen'}:$record->{'sig_id'}";
    
    check_handles();

    $sigid = getSigID($record, $sids);
    $classid = getClassID($record, $class);
    

    $EVENT_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'}, 
                 $sigid, $timestamp);

    $IPH_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                 $record->{'sip'}, $record->{'dip'}, $record->{'protocol'});

    if ( $record->{'protocol'} eq $IP_PROTO_TCP ) {
        $TCP_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                 $record->{'sp'}, $record->{'dp'});

    } elsif ( $record->{'protocol'} eq $IP_PROTO_UDP ) {
        $UDP_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                 $record->{'sp'}, $record->{'dp'});

    } elsif ( $record->{'protocol'} eq $IP_PROTO_ICMP ) {
        $ICMP_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                 $record->{'sp'}, $record->{'dp'});
    }
    
    # Increment the event ID
    $DB_INFO->{'event_id'}++;
}

sub insertSnortLog($$$) {
    my $record = $_[0]; # hash with the actual data
    my $sids = $_[1];   # Hash of sids
    my $class = $_[2];  # Hash of classifications
    my $sigid;
    my $classid;
    my $timestamp = strftime("%Y-%m-%d %H:%M:%S", localtime($record->{'tv_sec'}));
    my $gensid = "$record->{'sig_gen'}:$record->{'sig_id'}"; 
    my $dump_obj;
    my $l2_obj;
    my $ip_obj;
    my $tcp_obj;
    my $udp_obj;
    my $icmp_obj;

    check_handles();

    $sigid = getSigID($record, $sids) || 0;
    $classid = getClassID($record, $class) || 0;

    $EVENT_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                 $sigid, $timestamp);

    # old: man /media/sda3/usr/share/man/man3/NetPacket::Ethernet.3pm
    # new: man Net::Packet::ETH ==
    #      man /usr/share/man/man3/Net::Packet::ETH.3pm.gz
    # new: man Net::Packet::Layer2 ==
    #      man /usr/share/man/man3/Net::Packet::Layer2.3pm.gz
    #      man Net::Packet::Frame
    #      man Net::Packet::Dump
    # http://search.cpan.org/CPAN/authors/id/G/GO/GOMOR/Net-Packet-3.26.tar.gz
    # /usr/local/src/Net-Packet-3.26/examples

    my $is_ipv4 = 0;
    my $l2_encap = "";
    my $l2_encap2 = "";
    my $linklayer_type = $record->{'linklayer_type'};
    if ($linklayer_type eq "eth")
    {
      #print "Linklayer type is ethernet.\n";
      $l2_obj = new Net::Packet::ETH(raw => $record->{'pkt'});
      if (($l2_obj->isTypeIpv4))
      {
        $is_ipv4 = 1;
      }
      else
      {
        $l2_encap = $l2_obj->type;
        $l2_encap2 = $l2_obj->encapsulate;
      }
    }
    elsif($linklayer_type eq "null")
    {
      #print "Linklayer type is \"null\".\n";
      $l2_obj = new Net::Packet::NULL(raw => $record->{'pkt'});
      if ($l2_obj->isTypeIpv4)
      {
        $is_ipv4 = 1;
      }
      else
      {
        $l2_encap = $l2_obj->type;
        $l2_encap2 = $l2_obj->encapsulate;
      }
    }
    elsif($linklayer_type eq "raw")
    {
      print "Linklayer type is raw.\n";
      print "WARNING: I cannot deal with a RAW packet.\n";
      die "Exiting.\n";
    }
    elsif($linklayer_type eq "sll")
    {
      #print "Linklayer type is sll.\n";
      $l2_obj = new Net::Packet::SLL(raw => $record->{'pkt'});
      if ($l2_obj->isProtocolIpv4)
      {
        $is_ipv4 = 1;
      }
      else
      {
        $l2_encap = $l2_obj->protocol;
        $l2_encap2 = $l2_obj->encapsulate;
      }
    }
    elsif($linklayer_type == "ppp")
    {
      #print "Linklayer type is ppp.\n";
      $l2_obj = new Net::Packet::PPP(raw => $record->{'pkg'}); 
      if ($l2_obj->isProtocolIpv4)
      {
        $is_ipv4 = 1;
      }
      else
      {
        $l2_encap = $l2_obj->protocol;
        $l2_encap2 = $l2_obj->encapsulate;
      }
    }
    else
    {
      print "WARNING: Unknown linklayer type! Simply trying DLT_EN10MB... and failing, presumably...\n";
      $l2_obj = new Net::Packet::ETH(raw => $record->{'pkt'});
      if (($l2_obj->isTypeIpv4))
      {
        $is_ipv4 = 1;
      }
      else
      {
        $l2_encap = $l2_obj->type;
        $l2_encap2 = $l2_obj->encapsulate;
      }
    }


    if ($is_ipv4 == 1)
    {
        #print "Layer 3 is IPv4.\n";
        $ip_obj = new Net::Packet::IPv4(raw => $l2_obj->payload);
        #print $ip_obj->src . " -> " . $ip_obj->dst . "\n";
        #print "Id: "       . $ip_obj->id . "\n";
        #print "Protocol: " . $ip_obj->protocol . "\n";
        #print "hlen: "     . $ip_obj->hlen . "\n";
        #print "tos: "      . $ip_obj->tos . "\n";
        #print "length: "   . $ip_obj->length . "\n";
        #print "id: "       . $ip_obj->id . "\n";
        #print "flags: "    . $ip_obj->flags . "\n";
        #print "offset: "   . $ip_obj->offset . "\n";
        #print "ttl: "      . $ip_obj->ttl . "\n";
        #print "checksum: ";
        #printf("0x%02x", $ip_obj->checksum);
        #print " = "        . $ip_obj->checksum . "\n";


        $IPHDR_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                              unpack("N", inet_aton($ip_obj->src)), unpack("N", inet_aton($ip_obj->dst)),
                              $ip_obj->protocol, $ip_obj->version,
                              $ip_obj->hlen, $ip_obj->tos,
                              $ip_obj->length, $ip_obj->id,
                              $ip_obj->flags, $ip_obj->offset,
                              $ip_obj->ttl, $ip_obj->checksum);

        my $ip_options_length = $ip_obj->getOptionsLength;
        if ($ip_options_length > 0)
        {
          #print "There are IP options. Length: " . $ip_options_length . " bytes.\n";
          #print "Options: \"" . $ip_obj->options . "\"\n";
          #my $d = Dumpvalue->new();
          #$d->dumpValue($ip_obj->options);
          #print "\n";

          my $opt_id = 0;

          for (my $i = 0; $i < $ip_options_length; $i++)
          {
            my $opt_len = 0;
            my $opt_data = "";
            my $opt_code = ord(substr($ip_obj->options, $i, 1));

            if ($opt_code == 1)
            {
              # snort seems to consider the length of NOP to be 0 bytes.
              $opt_len = 0;
              $opt_data = ''; 
            }
            elsif ($opt_code > 1)
            {
              $opt_len = ord(substr($ip_obj->options, $i + 1, 1)) - 2;
              $opt_data = snort_hexify(substr($ip_obj->options, $i + 2, $opt_len));
              $i += $opt_len + 2; 
              $IP_OPTIONS_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                         $opt_id, Net::Packet::Consts::NP_DESC_IPPROTO_IP, $opt_code, $opt_len, $opt_data 
              );

              $opt_id += 1;
            }
          }
        }



        if ($ip_obj->isProtocolTcp) {
            #print "Layer 4 is TCP.\n\n\n";
            $tcp_obj = new Net::Packet::TCP(raw => $ip_obj->payload);
            $TCPHDR_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                   $tcp_obj->src, $tcp_obj->dst,
                                   $tcp_obj->seq, $tcp_obj->ack,
                                   (($tcp_obj->x2 & 0xf0) >> 4),
                                   ($tcp_obj->x2 & 0x0f),
                                   $tcp_obj->flags, $tcp_obj->win,
                                   $tcp_obj->checksum, $tcp_obj->urp);



            my $tcp_options_length = $tcp_obj->getOptionsLength;
            if ($tcp_options_length > 0)
            {
              #print "There are TCP options. Length: " . $tcp_options_length . " bytes.\n";
              #print "Options: \"";
              #my $d = Dumpvalue->new();
              #$d->dumpValue($tcp_obj->options);
              #print "\n";
              
              my $opt_id = 0;

              for (my $i = 0; $i < $tcp_options_length; $i++)
              {
                my $opt_len = 0;
                my $opt_data = "";
                my $opt_code = ord(substr($tcp_obj->options, $i, 1));


                if ($opt_code == 1)
                {                  
                  #print "$i: NOP";
                  # snort seems to consider the length of NOP to be 0 bytes.
                  $opt_len = 0;
                  $opt_data = '';
                }
                elsif ($opt_code > 1)
                {
                  #print "$i: Type 2. ";

                  #if ($opt_code == 8)
                  #{
                  #  print "TS";
                  #}

                  # snort seems to consider the length of TS to be 
                  # all of the 10 bytes minus 1 byte $opt_code and
                  # minus 1 byte for $opt_len, i.e. 8 bytes rather
                  # than 10 bytes.
                  $opt_len = ord(substr($tcp_obj->options, $i + 1, 1)) - 2;
                  $opt_data = snort_hexify(substr($tcp_obj->options, $i + 2 , $opt_len));
                  $i += $opt_len + 2;
                }
                #else
                #{
                #  print "$i: \"" . $opt_code . "\", ";
                #}
                $TCP_OPTIONS_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'}, 
                                            $opt_id, Net::Packet::Consts::NP_DESC_IPPROTO_TCP, $opt_code, $opt_len, $opt_data
                );
                $opt_id += 1;
                #print "\n";
              }
            }



            if (defined($tcp_obj->payload) && $tcp_obj->payload ne "")
            {
              $PAYLOAD_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                      snort_hexify($tcp_obj->payload));
            }
        } elsif ($ip_obj->isProtocolUdp) {
            #print "Layer 4 is UDP.\n\n\n";
            $udp_obj = new Net::Packet::UDP(raw => $ip_obj->payload);
            $UDPHDR_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                   $udp_obj->src, $udp_obj->dst,
                                   $udp_obj->length, $udp_obj->checksum);

            
            if (defined($udp_obj->payload) && $udp_obj->payload ne "")
            {
              $PAYLOAD_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                      snort_hexify($udp_obj->payload));
            }
              
        } elsif ($ip_obj->isProtocolIcmpv4) {
            #print "Layer 4 is ICMPv4.\n\n\n";
            $icmp_obj = new Net::Packet::ICMPv4(raw => $ip_obj->payload);
            if ( $icmp_obj->isTypeEchoReply || $icmp_obj->isTypeEchoRequest ||
                 $icmp_obj->isTypeTimestampRequest || $icmp_obj->isTypeTimestampReply ||
                 $icmp_obj->isTypeInformationRequest || $icmp_obj->isTypeInformationReply ) {
                 $ICMPHDR_INS_FULL_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                   $icmp_obj->type, $icmp_obj->code,
                                   $icmp_obj->checksum, 
                                   unpack('n', substr($icmp_obj->data, 0, 2)),
                                   unpack('n', substr($icmp_obj->data, 2, 2)));
            } else {
                 $ICMPHDR_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                   $icmp_obj->type, $icmp_obj->code,
                                   $icmp_obj->checksum);
            }

            if (defined($icmp_obj->payload) && $icmp_obj->payload ne "")
            {
              $PAYLOAD_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                      snort_hexify($icmp_obj->payload));
            }
        } else {
            print("DEBUGME: Why am I here - insertSnortLog\n");
            print("WARNING: Layer 4 protocol is neither TCP nor UDP nor ICMP! Treating everything from layer 4 up as payload.\n");
            if (defined($ip_obj->payload) && $ip_obj->payload ne "")
            {
              $PAYLOAD_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                snort_hexify($ip_obj->payload));
            }
        }
    }
    else
    {
      print("WARNING: Not IPv4! ");
      print("Type: " . $l2_encap . " (" . $l2_encap2 . ").\n");
      if ($l2_encap == "17664")
      {
        print("Does this happen to be a file, that has been generated by snort_inline (snort in inline mode)? If so, you could convert it as follows, so that at least tcpdump and tshark could read it: \n");
        print("editcap -T rawip  /tmp/snort_inline.raw.pcap /tmp/snort_inline.pcap");
      }
      print("Treating everything from layer 3 up as payload.\n");
      if (defined($l2_obj->payload) && $l2_obj->payload ne "")
      {
        $PAYLOAD_INS_H->execute($DB_INFO->{'sensor_id'}, $DB_INFO->{'event_id'},
                                snort_hexify($l2_obj->payload));
      }
    }

    $DB_INFO->{'event_id'}++;
}
1
