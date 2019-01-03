-- Copyright (C) 2004 Kevin Johnson
-- Portions Copyright (C) 2002 Carnegie Mellon University
--
-- Author: Kevin Johnson <kjohnson@secureideas.net
-- Based upon work by Roman Danyliw <roman@danyliw.com>
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
--
-- - Purpose:
--   Creates the PostgreSQL tables in the Snort database neccessary to support
--   ACID.
--  
--   TABLE acid_event: cache of signature, IP, port, and classification
--                     information
--   TABLE acid_ag: stores the description of an Alert Group (AG)
-- 
--   TABLE acid_ag_alert: stores the IDs of the alerts in an Alert Group (AG)
--
--   TABLE acid_ip_cache: caches DNS and whois information

CREATE TABLE acid_event   ( sid                 INT8 NOT NULL,
                            cid                 INT8 NOT NULL,
                            signature           INT8 NOT NULL,
                            sig_name            TEXT,
                            sig_class_id        INT8,
                            sig_priority        INT8,
                            timestamp              TIMESTAMP NOT NULL,
                            ip_src              INT8,
                            ip_dst              INT8,
                            ip_proto            INT4,
                            layer4_sport        INT4,
                            layer4_dport        INT4,
                            PRIMARY KEY         (sid,cid)
                          );

CREATE INDEX acid_event_signature ON acid_event (signature);
CREATE INDEX acid_event_sig_name ON acid_event (sig_name);
CREATE INDEX acid_event_sig_class_id ON acid_event (sig_class_id);
CREATE INDEX acid_event_sig_priority ON acid_event (sig_priority);
CREATE INDEX acid_event_timestamp ON acid_event (timestamp);
CREATE INDEX acid_event_ip_src ON acid_event (ip_src);
CREATE INDEX acid_event_ip_dst ON acid_event (ip_dst);
CREATE INDEX acid_event_ip_proto ON acid_event (ip_proto);
CREATE INDEX acid_event_layer4_sport ON acid_event (layer4_sport);
CREATE INDEX acid_event_layer4_dport ON acid_event (layer4_dport);

CREATE TABLE acid_ag      ( ag_id               SERIAL NOT NULL,
                            ag_name             TEXT,
                            ag_desc             TEXT, 
                            ag_ctime            TIMESTAMP,
                            ag_ltime            TIMESTAMP,

                            PRIMARY KEY         (ag_id) );

CREATE TABLE acid_ag_alert( ag_id               INT8 NOT NULL,
                            ag_sid              INT4 NOT NULL,
                            ag_cid              INT8 NOT NULL, 

                            PRIMARY KEY         (ag_id, ag_sid, ag_cid) );

CREATE INDEX acid_ag_alert_aid_idx ON acid_ag_alert (ag_id);
CREATE INDEX acid_ag_alert_id_idx ON acid_ag_alert (ag_sid, ag_cid);

CREATE TABLE acid_ip_cache( ipc_ip                  INT8 NOT NULL,
                            ipc_fqdn                TEXT,
                            ipc_dns_timestamp       TIMESTAMP,
                            ipc_whois               TEXT,
                            ipc_whois_timestamp     TIMESTAMP,

                            PRIMARY KEY         (ipc_ip) );

CREATE TABLE base_roles(    role_id                  INT8 NOT NULL,
                            role_name                TEXT,
                            role_desc                TEXT,
                            PRIMARY KEY         (role_id) );
                            
CREATE TABLE base_users (   usr_id            INT8         NOT NULL,
                            usr_login         TEXT     NOT NULL,
                            usr_pwd           TEXT     NOT NULL,
                            usr_name          TEXT     NOT NULL,
                            role_id           INT8         NOT NULL,
                            usr_enabled       INT8         NOT NULL,
                            
                            PRIMARY KEY         (usr_id) );

CREATE INDEX base_users_usr_login ON base_users (usr_login);

INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (1, 'Admin', 'Administrator');
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10, 'user', 'Authenticated User');
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10000, 'anonymous', 'Anonymous User');
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (50, 'ag_editor', 'Alert Group Editor');
