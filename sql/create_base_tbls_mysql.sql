# Copyright (C) 2004 Kevin Johnson
# Portions Copyright (C) 2002 Carnegie Mellon University
#
# Author: Kevin Johnson <kjohnson@secureideas.net
# Based upon work by Roman Danyliw <roman@danyliw.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.

# - Purpose:
#   Creates the MySQL tables in the Snort database neccessary to support
#   BASE.
# 
#   TABLE acid_event: cache of signature, IP, port, and classification
#                     information
# 
#   TABLE acid_ag: stores the description of an Alert Group (AG)
# 
#   TABLE acid_ag_alert: stores the IDs of the alerts in an Alert Group (AG)
#
#   TABLE acid_ip_cache: caches DNS and whois information
#
#   TABLE base_roles: Stores the User roles available for the
#                     Authentication System
#
#   TABLE base_users: Stores the user names and passwords

CREATE TABLE acid_event   ( sid                 INT UNSIGNED NOT NULL,
                            cid                 INT UNSIGNED NOT NULL,     
                            signature           INT UNSIGNED NOT NULL,
                            sig_name            VARCHAR(255),
                            sig_class_id        INT UNSIGNED,
                            sig_priority        INT UNSIGNED,
                            timestamp              DATETIME NOT NULL,
                            ip_src              INT UNSIGNED,
                            ip_dst              INT UNSIGNED,
                            ip_proto            INT,
                            layer4_sport        INT UNSIGNED,
                            layer4_dport        INT UNSIGNED,

                            PRIMARY KEY         (sid,cid),
                            INDEX               (signature),
                            INDEX               (sig_name),
                            INDEX               (sig_class_id),
                            INDEX               (sig_priority),
                            INDEX               (timestamp),
                            INDEX               (ip_src),
                            INDEX               (ip_dst),
                            INDEX               (ip_proto),
                            INDEX               (layer4_sport),
                            INDEX               (layer4_dport)
                          );
 

CREATE TABLE acid_ag      ( ag_id               INT           UNSIGNED NOT NULL AUTO_INCREMENT,
                            ag_name             VARCHAR(40),
                            ag_desc             TEXT, 
                            ag_ctime            DATETIME,
                            ag_ltime            DATETIME,

                            PRIMARY KEY         (ag_id),
                            INDEX               (ag_id));

CREATE TABLE acid_ag_alert( ag_id               INT           UNSIGNED NOT NULL,
                            ag_sid              INT           UNSIGNED NOT NULL,
                            ag_cid              INT           UNSIGNED NOT NULL, 

                            PRIMARY KEY         (ag_id, ag_sid, ag_cid),
                            INDEX               (ag_id),
                            INDEX               (ag_sid, ag_cid));

CREATE TABLE acid_ip_cache( ipc_ip                  INT           UNSIGNED NOT NULL,
                            ipc_fqdn                VARCHAR(50),
                            ipc_dns_timestamp       DATETIME,
                            ipc_whois               TEXT,
                            ipc_whois_timestamp     DATETIME,

                            PRIMARY KEY         (ipc_ip),
                            INDEX               (ipc_ip) );

CREATE TABLE `base_roles` ( `role_id`           int(11)         NOT NULL,
                            `role_name`         varchar(20)     NOT NULL,
                            `role_desc`         varchar(75)     NOT NULL,
                            PRIMARY KEY         (`role_id`));

CREATE TABLE `base_users` ( `usr_id`            int(11)         NOT NULL,
                            `usr_login`         varchar(25)     NOT NULL,
                            `usr_pwd`           varchar(32)     NOT NULL,
                            `usr_name`          varchar(75)     NOT NULL,
                            `role_id`           int(11)         NOT NULL,
                            `usr_enabled`       int(11)         NOT NULL,
                            PRIMARY KEY         (`usr_id`),
                            INDEX               (`usr_login`));

INSERT INTO `base_roles` (`role_id`, `role_name`, `role_desc`) VALUES (1, 'Admin', 'Administrator'),
(10, 'User', 'Authenticated User'),
(10000, 'Anonymous', 'Anonymous User'),
(50, 'ag_editor', 'Alert Group Editor');

