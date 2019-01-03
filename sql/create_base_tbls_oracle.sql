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

-- - Purpose:
--   Creates the Oracle tables in the Snort database neccessary to support
--   BASE.
-- 
--   TABLE acid_event: cache of signature, IP, port, and classification
--                     information
-- 
--   TABLE acid_ag: stores the description of an Alert Group (AG)
-- 
--   TABLE acid_ag_alert: stores the IDs of the alerts in an Alert Group (AG)
--
--   TABLE acid_ip_cache: caches DNS and whois information
--
--   TABLE base_roles: Stores the User roles available for the
--                     Authentication System
--
--   TABLE base_users: Stores the user names and passwords

drop table acid_event; 
CREATE TABLE acid_event ( sid INT NOT NULL, 
cid INT NOT NULL,  
signature INT NOT NULL, 
sig_name VARCHAR2(255), 
sig_class_id INT, 
sig_priority INT, 
timestamp DATE NOT NULL, 
ip_src INT, 
ip_dst INT, 
ip_proto INT, 
layer4_sport INT, 
layer4_dport INT, 
PRIMARY KEY (sid,cid) 
); 
commit work; 
 
create INDEX idx_acid_1 on acid_event(signature); 
create INDEX idx_acid_2 on acid_event(sig_name); 
create INDEX idx_acid_3 on acid_event(sig_class_id); 
create INDEX idx_acid_4 on acid_event(sig_priority); 
create INDEX idx_acid_5 on acid_event("timestamp"); 
create INDEX idx_acid_6 on acid_event(ip_src); 
create INDEX idx_acid_7 on acid_event(ip_dst); 
create INDEX idx_acid_8 on acid_event(ip_proto); 
create INDEX idx_acid_9 on acid_event(layer4_sport); 
create INDEX idx_acid_10 on acid_event(layer4_dport); 
commit work; 
 
drop table acid_ag; 
CREATE TABLE acid_ag ( ag_id INT NOT NULL, 
ag_name VARCHAR2(40), 
ag_desc BLOB,  
ag_ctime DATE, 
ag_ltime DATE, 
PRIMARY KEY (ag_id)); 
commit work; 
 
drop sequence seq_acid_ag_id; 
CREATE SEQUENCE snort.seq_acid_ag_id START WITH 1 INCREMENT BY 1; 
 
CREATE or replace TRIGGER tr_acid_ag_id 
BEFORE INSERT ON acid_ag 
FOR EACH ROW 
BEGIN 
SELECT snort.seq_acid_ag_id.nextval INTO :new.ag_id FROM dual; 
END; 
/ 
 
drop table acid_ag_alert; 
CREATE TABLE acid_ag_alert( ag_id INT NOT NULL, 
ag_sid INT NOT NULL, 
ag_cid INT NOT NULL,  
PRIMARY KEY (ag_id, ag_sid, ag_cid) 
); 
create INDEX idx_acid_12 on acid_ag_alert(ag_id); 
create INDEX idx_acid_13 on acid_ag_alert(ag_sid,ag_cid); 
commit work; 
 
drop table acid_ip_cache; 
CREATE TABLE acid_ip_cache( ipc_ip INT NOT NULL, 
ipc_fqdn VARCHAR2(50), 
ipc_dns_timestamp DATE, 
ipc_whois BLOB, 
ipc_whois_timestamp DATE, 
PRIMARY KEY (ipc_ip) ); 
commit work; 
 
drop table snort.base_roles; 
CREATE TABLE base_roles ( role_id int NOT NULL, 
role_name varchar2(20) NOT NULL, 
role_desc varchar2(75) NOT NULL, 
PRIMARY KEY (role_id)); 
commit work; 
 
drop table base_users; 
CREATE TABLE base_users ( usr_id int NOT NULL, 
usr_login varchar2(25) NOT NULL, 
usr_pwd varchar2(32) NOT NULL, 
usr_name varchar2(75) NOT NULL, 
role_id number NOT NULL, 
usr_enabled number NOT NULL, 
PRIMARY KEY (usr_id) 
); 
create INDEX idx_acid_15 on base_users(usr_login); 
commit work; 
 
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES ( 1, 'Admin', 'Administrator' ); 
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES ( 10, 'User', 'Authenticated User' ); 
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10000, 'Anonymous', 'Anonymous User' ); 
INSERT INTO base_roles (role_id, role_name, role_desc) VALUES ( 50, 'ag_editor', 'Alert Group Editor' ); 
 
commit work;