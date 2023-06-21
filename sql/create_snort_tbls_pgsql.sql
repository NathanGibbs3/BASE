-- Copyright (C) 2000-2002 Carnegie Mellon University
--
-- Maintainer: Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
--
-- Original Author(s): Jed Pickel <jed@pickel.net>    (2000-2001)
--                     Roman Danyliw <rdd@cert.org>
--                     Todd Schrubb <tls@cert.org>
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program; if not, write to the Free Software
-- Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

CREATE TABLE schema ( vseq        INT4     NOT NULL,
                      ctime       TIMESTAMP with time zone NOT NULL,
                      PRIMARY KEY (vseq));
INSERT INTO schema  (vseq, ctime) VALUES ('107', now());

CREATE TABLE signature ( sig_id       SERIAL NOT NULL,
                         sig_name     TEXT NOT NULL,
                         sig_class_id INT8,
                         sig_priority INT8,
                         sig_rev      INT8,
                         sig_sid      INT8,
                         sig_gid      INT8,
                         PRIMARY KEY (sig_id));
CREATE INDEX sig_name_idx ON signature (sig_name);
CREATE INDEX sig_class_idx ON signature (sig_class_id);

CREATE TABLE sig_reference (sig_id  INT4  NOT NULL,
                            ref_seq INT4  NOT NULL,
                            ref_id  INT4  NOT NULL,
                            PRIMARY KEY(sig_id, ref_seq));

CREATE TABLE reference (  ref_id        SERIAL,
                          ref_system_id INT4 NOT NULL,
                          ref_tag       TEXT NOT NULL,
                          PRIMARY KEY (ref_id));

CREATE TABLE reference_system ( ref_system_id   SERIAL,
                                ref_system_name TEXT,
                                PRIMARY KEY (ref_system_id));

CREATE TABLE sig_class ( sig_class_id        SERIAL,
                         sig_class_name      TEXT NOT NULL,
                         PRIMARY KEY (sig_class_id) );
CREATE INDEX sig_class_name_idx ON sig_class (sig_class_name);

CREATE TABLE event  ( sid 	  INT4 NOT NULL,
                      cid 	  INT8 NOT NULL,
                      signature   INT4 NOT NULL, 
                      timestamp   timestamp with time zone NOT NULL,
                      PRIMARY KEY (sid,cid));
CREATE INDEX signature_idx ON event (signature);
CREATE INDEX timestamp_idx ON event (timestamp);

-- store info about the sensor supplying data
CREATE TABLE sensor ( sid	  SERIAL,
                      hostname    TEXT,
                      interface   TEXT,
                      filter	  TEXT,
                      detail	  INT2,
                      encoding	  INT2,
                      last_cid    INT8 NOT NULL,
                      PRIMARY KEY (sid));

-- All of the fields of an ip header
CREATE TABLE iphdr  ( sid 	  INT4 NOT NULL,
                      cid 	  INT8 NOT NULL,
                      ip_src      INT8 NOT NULL,
                      ip_dst      INT8 NOT NULL,
                      ip_ver      INT2,
                      ip_hlen     INT2,
                      ip_tos  	  INT2,
                      ip_len 	  INT4,
                      ip_id    	  INT4,
                      ip_flags    INT2,
                      ip_off      INT4,
                      ip_ttl   	  INT2,
                      ip_proto 	  INT2 NOT NULL,
                      ip_csum 	  INT4,
                      PRIMARY KEY (sid,cid));
CREATE INDEX ip_src_idx ON iphdr (ip_src);
CREATE INDEX ip_dst_idx ON iphdr (ip_dst);

-- All of the fields of a tcp header
CREATE TABLE tcphdr(  sid 	  INT4 NOT NULL,
                      cid 	  INT8 NOT NULL,
                      tcp_sport   INT4 NOT NULL,
                      tcp_dport   INT4 NOT NULL,
                      tcp_seq     INT8,
                      tcp_ack     INT8,
                      tcp_off     INT2,
                      tcp_res     INT2,
                      tcp_flags   INT2 NOT NULL,
                      tcp_win     INT4,
                      tcp_csum    INT4,
                      tcp_urp     INT4,
                      PRIMARY KEY (sid,cid));
CREATE INDEX tcp_sport_idx ON tcphdr (tcp_sport);
CREATE INDEX tcp_dport_idx ON tcphdr (tcp_dport);
CREATE INDEX tcp_flags_idx ON tcphdr (tcp_flags);

-- All of the fields of a udp header
CREATE TABLE udphdr(  sid 	  INT4 NOT NULL,
                      cid 	  INT8 NOT NULL,
                      udp_sport   INT4 NOT NULL,
                      udp_dport   INT4 NOT NULL,
                      udp_len     INT4,
                      udp_csum    INT4,
                      PRIMARY KEY (sid,cid));
CREATE INDEX udp_sport_idx ON udphdr (udp_sport);
CREATE INDEX udp_dport_idx ON udphdr (udp_dport);

-- All of the fields of an icmp header
CREATE TABLE icmphdr( sid 	  INT4 NOT NULL,
                      cid 	  INT8 NOT NULL,
                      icmp_type   INT2 NOT NULL,
                      icmp_code   INT2 NOT NULL,
                      icmp_csum   INT4, 
                      icmp_id     INT4,
                      icmp_seq    INT4,
                      PRIMARY KEY (sid,cid));
CREATE INDEX icmp_type_idx ON icmphdr (icmp_type);

-- Protocol options
CREATE TABLE opt    ( sid         INT4 NOT NULL,
                      cid         INT8 NOT NULL,
                      optid       INT2 NOT NULL,
                      opt_proto   INT2 NOT NULL,
                      opt_code    INT2 NOT NULL,
                      opt_len     INT4,
                      opt_data    TEXT,
                      PRIMARY KEY (sid,cid,optid));

-- Packet payload
CREATE TABLE data   ( sid          INT4 NOT NULL,
                      cid          INT8 NOT NULL,
                      data_payload TEXT,
                      PRIMARY KEY (sid,cid));

-- encoding is a lookup table for storing encoding types
CREATE TABLE encoding(encoding_type INT2 NOT NULL,
                      encoding_text TEXT NOT NULL,
                      PRIMARY KEY (encoding_type));
INSERT INTO encoding (encoding_type, encoding_text) VALUES (0, 'hex');
INSERT INTO encoding (encoding_type, encoding_text) VALUES (1, 'base64');
INSERT INTO encoding (encoding_type, encoding_text) VALUES (2, 'ascii');

-- detail is a lookup table for storing different detail levels
CREATE TABLE detail  (detail_type INT2 NOT NULL,
                      detail_text TEXT NOT NULL,
                      PRIMARY KEY (detail_type));
INSERT INTO detail (detail_type, detail_text) VALUES (0, 'fast');
INSERT INTO detail (detail_type, detail_text) VALUES (1, 'full');

-- be sure to also use the snortdb-extra tables if you want
-- mappings for tcp flags, protocols, and ports
