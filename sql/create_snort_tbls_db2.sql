-- Maintainer and author: Vlatko Kosturjak <kost at linux dot hr>
--
-- Usage:
-- db2 create database snort
-- db2 connect to snort
-- db2 -tvf create_db2
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

CREATE TABLE schema ( vseq        INT     NOT NULL,
                      ctime       TIMESTAMP NOT NULL,
                      PRIMARY KEY (vseq));
INSERT INTO schema  (vseq, ctime) VALUES (107, current timestamp);

CREATE TABLE signature ( sig_id       INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1),
                         sig_name     VARCHAR(255) NOT NULL,
                         sig_class_id BIGINT,
                         sig_priority BIGINT,
                         sig_rev      BIGINT,
                         sig_sid      BIGINT,
			 sig_gid      BIGINT,
                         PRIMARY KEY (sig_id));
CREATE INDEX sig_name_idx ON signature (sig_name);
CREATE INDEX sig_class_idx ON signature (sig_class_id);

CREATE TABLE sig_reference (sig_id  INT  NOT NULL,
                            ref_seq INT  NOT NULL,
                            ref_id  INT  NOT NULL,
                            PRIMARY KEY(sig_id, ref_seq));

CREATE TABLE reference (  ref_id        INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1),
                          ref_system_id INT NOT NULL,
                          ref_tag       VARCHAR(300) NOT NULL,
                          PRIMARY KEY (ref_id));

CREATE TABLE reference_system ( ref_system_id   INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1),
                                ref_system_name VARCHAR(20),
                                PRIMARY KEY (ref_system_id));

CREATE TABLE sig_class ( sig_class_id        INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1),
                         sig_class_name      VARCHAR(60) NOT NULL,
                         PRIMARY KEY (sig_class_id) );
CREATE INDEX sig_class_id_idx ON sig_class (sig_class_id);
CREATE INDEX sig_class_name_idx ON sig_class (sig_class_name);

CREATE TABLE event  ( sid 	  INT NOT NULL,
                      cid 	  BIGINT NOT NULL,
                      signature   INT NOT NULL, 
                      timestamp   timestamp NOT NULL,
                      PRIMARY KEY (sid,cid));
CREATE INDEX signature_idx ON event (signature);
CREATE INDEX timestamp_idx ON event (timestamp);

-- store info about the sensor supplying data
CREATE TABLE sensor ( sid	  INTEGER NOT NULL GENERATED ALWAYS AS IDENTITY (START WITH 1, INCREMENT BY 1),
                      hostname    VARCHAR(300),
                      interface   VARCHAR(300),
                      filter	  VARCHAR(300),
                      detail	  SMALLINT,
                      encoding	  SMALLINT,
                      last_cid    BIGINT NOT NULL,
                      PRIMARY KEY (sid));

-- All of the fields of an ip header
CREATE TABLE iphdr  ( sid 	  INT NOT NULL,
                      cid 	  BIGINT NOT NULL,
                      ip_src      BIGINT NOT NULL,
                      ip_dst      BIGINT NOT NULL,
                      ip_ver      SMALLINT,
                      ip_hlen     SMALLINT,
                      ip_tos  	  SMALLINT,
                      ip_len 	  INT,
                      ip_id    	  INT,
                      ip_flags    SMALLINT,
                      ip_off      INT,
                      ip_ttl   	  SMALLINT,
                      ip_proto 	  SMALLINT NOT NULL,
                      ip_csum 	  INT,
                      PRIMARY KEY (sid,cid));
CREATE INDEX ip_src_idx ON iphdr (ip_src);
CREATE INDEX ip_dst_idx ON iphdr (ip_dst);

-- All of the fields of a tcp header
CREATE TABLE tcphdr(  sid 	  INT NOT NULL,
                      cid 	  BIGINT NOT NULL,
                      tcp_sport   INT NOT NULL,
                      tcp_dport   INT NOT NULL,
                      tcp_seq     BIGINT,
                      tcp_ack     BIGINT,
                      tcp_off     SMALLINT,
                      tcp_res     SMALLINT,
                      tcp_flags   SMALLINT NOT NULL,
                      tcp_win     INT,
                      tcp_csum    INT,
                      tcp_urp     INT,
                      PRIMARY KEY (sid,cid));
CREATE INDEX tcp_sport_idx ON tcphdr (tcp_sport);
CREATE INDEX tcp_dport_idx ON tcphdr (tcp_dport);
CREATE INDEX tcp_flags_idx ON tcphdr (tcp_flags);

-- All of the fields of a udp header
CREATE TABLE udphdr(  sid 	  INT NOT NULL,
                      cid 	  BIGINT NOT NULL,
                      udp_sport   INT NOT NULL,
                      udp_dport   INT NOT NULL,
                      udp_len     INT,
                      udp_csum    INT,
                      PRIMARY KEY (sid,cid));
CREATE INDEX udp_sport_idx ON udphdr (udp_sport);
CREATE INDEX udp_dport_idx ON udphdr (udp_dport);

-- All of the fields of an icmp header
CREATE TABLE icmphdr( sid 	  INT NOT NULL,
                      cid 	  BIGINT NOT NULL,
                      icmp_type   SMALLINT NOT NULL,
                      icmp_code   SMALLINT NOT NULL,
                      icmp_csum   INT, 
                      icmp_id     INT,
                      icmp_seq    INT,
                      PRIMARY KEY (sid,cid));
CREATE INDEX icmp_type_idx ON icmphdr (icmp_type);

-- Protocol options
CREATE TABLE opt    ( sid         INT NOT NULL,
                      cid         BIGINT NOT NULL,
                      optid       SMALLINT NOT NULL,
                      opt_proto   SMALLINT NOT NULL,
                      opt_code    SMALLINT NOT NULL,
                      opt_len     INT,
                      opt_data    VARCHAR(300),
                      PRIMARY KEY (sid,cid,optid));

-- Packet payload
CREATE TABLE data   ( sid          INT NOT NULL,
                      cid          BIGINT NOT NULL,
                      data_payload VARCHAR(300),
                      PRIMARY KEY (sid,cid));

-- encoding is a lookup table for storing encoding types
CREATE TABLE encoding(encoding_type SMALLINT NOT NULL,
                      encoding_text VARCHAR(300) NOT NULL,
                      PRIMARY KEY (encoding_type));
INSERT INTO encoding (encoding_type, encoding_text) VALUES (0, 'hex');
INSERT INTO encoding (encoding_type, encoding_text) VALUES (1, 'base64');
INSERT INTO encoding (encoding_type, encoding_text) VALUES (2, 'ascii');

-- detail is a lookup table for storing different detail levels
CREATE TABLE detail  (detail_type SMALLINT NOT NULL,
                      detail_text VARCHAR(300) NOT NULL,
                      PRIMARY KEY (detail_type));
INSERT INTO detail (detail_type, detail_text) VALUES (0, 'fast');
INSERT INTO detail (detail_type, detail_text) VALUES (1, 'full');

-- be sure to also use the snortdb-extra tables if you want
-- mappings for tcp flags, protocols, and ports
