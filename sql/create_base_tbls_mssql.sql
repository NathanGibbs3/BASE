-- Copyright (C) 2004 Kevin Johnson
-- Portions Copyright (C) 2000 Carnegie Mellon University
-- Portions Copyright (C) 2001 Iowa National Guard
--
-- Author: Kevin Johnson <kjohnson@secureideas.net
-- Based upon work by Roman Danyliw <roman@danyliw.com>
--      MSSQL by Charles Hand <charlieh@silicondefense.com>
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

-- - Purpose:
--   Creates the MSSQL tables in the Snort database neccessary to support
--   ACID.
-- 
--   TABLE acid_event: cache of signature, IP, port, and classification
--                     information
-- 
--   TABLE acid_ag: stores the description of an Alert Group (AG)
-- 
--   TABLE acid_ag_alert: stores the IDs of the alerts in an Alert Group (AG)
--
--   TABLE acid_ip_cache: caches DNS and whois information

CREATE TABLE acid_event   ( sid                 NUMERIC(10,0) NOT NULL,
                            cid                 NUMERIC(10,0) NOT NULL,     
                            signature           NUMERIC(10,0) NOT NULL,
                            sig_name            VARCHAR(255) NULL,
                            sig_class_id        NUMERIC(10,0) NULL,
                            sig_priority        NUMERIC(10,0) NULL,
                            timestamp              DATETIME NOT NULL,
                            ip_src              NUMERIC(10,0) NULL,
                            ip_dst              NUMERIC(10,0) NULL,
                            ip_proto            NUMERIC(10,0) NULL,
                            layer4_sport        NUMERIC(10,0) NULL,
                            layer4_dport        NUMERIC(10,0) NULL,

                            PRIMARY KEY         (sid,cid));
                            
CREATE INDEX IX_acid_event_signature ON acid_event(signature);
-- CREATE INDEX IX_acid_event_sig_name ON acid_event(sig_name);
CREATE INDEX IX_acid_event_sig_class_id ON acid_event(sig_class_id);
CREATE INDEX IX_acid_event_sig_priority ON acid_event(sig_priority);
CREATE INDEX IX_acid_event_timestamp ON acid_event(timestamp);
CREATE INDEX IX_acid_event_ip_src ON acid_event(ip_src);
CREATE INDEX IX_acid_event_ip_dst ON acid_event(ip_dst);
CREATE INDEX IX_acid_event_ip_proto ON acid_event(ip_proto);
CREATE INDEX IX_acid_event_layer4_sport ON acid_event(layer4_sport);
CREATE INDEX IX_acid_event_layer4_dport ON acid_event(layer4_dport);

 

CREATE TABLE acid_ag      ( ag_id               NUMERIC(10,0) NOT NULL IDENTITY(1,1),
                            ag_name             VARCHAR(40) NULL,
                            ag_desc             TEXT NULL, 
                            ag_ctime            DATETIME NULL,
                            ag_ltime            DATETIME NULL,

                            PRIMARY KEY         (ag_id));
CREATE INDEX IX_acid_ag_ag_id ON acid_ag (ag_id);

CREATE TABLE acid_ag_alert( ag_id               NUMERIC(10,0) NOT NULL,
                            ag_sid              NUMERIC(10,0) NOT NULL,
                            ag_cid              NUMERIC(10,0) NOT NULL, 

                            PRIMARY KEY         (ag_id, ag_sid, ag_cid));
CREATE INDEX    IX_acid_ag_alert_ag_id ON acid_ag_alert (ag_id);
CREATE INDEX    IX_acid_ag_alert_ag_sid_cid ON  acid_ag_alert (ag_sid, ag_cid);

CREATE TABLE acid_ip_cache( ipc_ip                  NUMERIC(10,0) NOT NULL,
                            ipc_fqdn                VARCHAR(50) NULL,
                            ipc_dns_timestamp       DATETIME NULL,
                            ipc_whois               TEXT NULL,
                            ipc_whois_timestamp     DATETIME NULL,

                            PRIMARY KEY         (ipc_ip));
CREATE INDEX    IX_acid_ip_cache_ipc_ip ON acid_ip_cache (ipc_ip) ;

CREATE TABLE    base_roles ( role_id                NUMERIC(10,0) NOT NULL,
                            role_name               VARCHAR(20) NULL,
                            role_desc               VARCHAR(75) NULL,
                            
                            PRIMARY KEY             (role_id) );
                            
CREATE TABLE base_users (   usr_id                  NUMERIC(10,0)   NOT NULL,
                            usr_login               VARCHAR(25)     NOT NULL,
                            usr_pwd                 VARCHAR(32)     NOT NULL,
                            usr_name                VARCHAR(75)     NOT NULL,
                            role_id                 NUMERIC(10,0)   NOT NULL,
                            usr_enabled             NUMERIC(10,0)   NOT NULL,
                            
                            PRIMARY KEY             (usr_id));
                            
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (1, 'Admin', 'Administrator');
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10, 'user', 'Authenticated User');
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10000, 'anonymous', 'Anonymous User');
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (50, 'ag_editor', 'Alert Group Editor');