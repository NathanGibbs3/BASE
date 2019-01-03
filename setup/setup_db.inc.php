<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Setup step 4, create the database stuff
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net>
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

function CreateBASEAG($db) {
     global $debug_mode;
     
     $tblBaseAG_present = $db->baseTableExists("acid_ag");
     $tblBaseAGAlert_present = $db->baseTableExists("acid_ag_alert");
     $tblBaseIPCache_present = $db->baseTableExists("acid_ip_cache");
     $tblBaseEvent_present = $db->baseTableExists("acid_event");
     $tblBaseRoles_present = $db->baseTableExists("base_roles");
     $tblBaseUsers_present = $db->baseTableExists("base_users");

     if ( $debug_mode > 0 ) {
       echo "\$submit = $submit <BR>
              database: $db->DB_name <BR>
              table acid_ag present? $tblBaseAG_present <BR>  
              table acid_ag_alert present? $tblBaseAGAlert_present <BR>
              table acid_ip_cache present? $tblBaseIPCache_present <BR>
              table acid_event present? $tblBaseEvent_present <BR>
              table base_roles present? $tblBaseRoles_present <BR>
              table base_users present? $tblBaseUsers_present <BR>";
     }

     if ( !$tblBaseAG_present ) {
        if ( $db->DB_type == "mysql" ) {
           $sql = 'CREATE TABLE acid_ag ( ag_id               INT           UNSIGNED NOT NULL AUTO_INCREMENT,
                                          ag_name             VARCHAR(40),
                                          ag_desc             TEXT,
                                          ag_ctime            DATETIME,
                                          ag_ltime            DATETIME,
                                          PRIMARY KEY         (ag_id),
                                          INDEX               (ag_id))';
        } else if ($db->DB_type == "postgres") {
           $sql = 'CREATE TABLE acid_ag ( ag_id               SERIAL NOT NULL,
                                          ag_name             TEXT,
                                          ag_desc             TEXT,
                                          ag_ctime            TIMESTAMP,
                                          ag_ltime            TIMESTAMP,
                                          PRIMARY KEY         (ag_id))';
        } else if ($db->DB_type == "mssql") {
           /* Microsoft recommends specifying NULL if NULL is permitted */
           /* Otherwise it will unpredictably change the nullability. */
           $sql = 'CREATE TABLE acid_ag ( ag_id              NUMERIC(10,0) IDENTITY(1,1) NOT NULL,
                                          ag_name            VARCHAR(40) NULL,
                                          ag_desc            TEXT NULL,
                                          ag_ctime           DATETIME NULL,
                                          ag_ltime           DATETIME NULL,
                                          PRIMARY KEY        (ag_id))';
        } else if ($db->DB_type == "oci8") {
           $sql = 'CREATE TABLE acid_ag ( ag_id               INT NOT NULL,
                                          ag_name             VARCHAR2(40),
                                          ag_desc             BLOB,
                                          ag_ctime            DATE,
                                          ag_ltime            DATE,
                                          PRIMARY KEY         (ag_id))';
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE table 'acid_ag': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created 'acid_ag'");

           $sql = 'CREATE SEQUENCE snort.seq_acid_ag_id START WITH 1 INCREMENT BY 1';
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE sequence 'snort.seq_acid_ag_id': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created sequence 'snort.seq_acid_ag_id'");

           $sql = 'CREATE or replace TRIGGER tr_acid_ag_id
                   BEFORE INSERT ON acid_ag
                   FOR EACH ROW
                   BEGIN
                   SELECT snort.seq_acid_ag_id.nextval INTO :new.ag_id FROM dual;
                   END;/';
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE trigger 'tr_acid_ag_id': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created trigger 'tr_acid_ag_id'");
	}

        /* Run query to create table 'acid_ag' */
        if( $db->DB_type != "oci8" ) {
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE table 'acid_ag': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created 'acid_ag'");
        }
        $tblBaseAG_present = $db->baseTableExists("acid_ag");
     }

     if ( !$tblBaseAGAlert_present ) {
        if ( $db->DB_type == "mysql" ) {
           $sql = 'CREATE TABLE acid_ag_alert( ag_id               INT           UNSIGNED NOT NULL,
                                               ag_sid              INT           UNSIGNED NOT NULL,
                                               ag_cid              INT           UNSIGNED NOT NULL,
                                               PRIMARY KEY         (ag_id, ag_sid, ag_cid),
                                               INDEX               (ag_id),
                                               INDEX               (ag_sid, ag_cid))';
        } else if ($db->DB_type == "postgres") {
           $sql = 'CREATE TABLE acid_ag_alert ( ag_id               INT8 NOT NULL,
                                                ag_sid              INT4 NOT NULL,
                                                ag_cid              INT8 NOT NULL,
                                                PRIMARY KEY         (ag_id, ag_sid, ag_cid) );
                   CREATE INDEX acid_ag_alert_aid_idx ON acid_ag_alert (ag_id);
                   CREATE INDEX acid_ag_alert_id_idx ON acid_ag_alert (ag_sid, ag_cid);';
        } else if ($db->DB_type == "mssql") {
           /* Microsoft recommends specifying NULL if NULL is permitted */
           /* Otherwise it will unpredictably change the nullability. */
           $sql = 'CREATE TABLE acid_ag_alert ( ag_id        NUMERIC(10,0) NOT NULL,
                                                ag_sid       NUMERIC(10,0) NOT NULL,
                                                ag_cid       NUMERIC(10,0) NOT NULL,
                                                PRIMARY KEY  (ag_id, ag_sid, ag_cid) )';
        } else if( $db->DB_type == "oci8" ) {
           $sql = 'CREATE TABLE acid_ag_alert ( ag_id  INT NOT NULL,
                                                ag_sid INT NOT NULL,
                                                ag_cid INT NOT NULL,
                                                PRIMARY KEY (ag_id, ag_sid, ag_cid) )';
        }

        $db->baseExecute($sql, -1, -1, false);
        if ( $db->baseErrorMessage() != "" )
           ErrorMessage("Unable to CREATE table 'acid_ag_alert': ".$db->baseErrorMessage());
        else
           ErrorMessage("Successfully created 'acid_ag_alert'");
        $tblBaseAGAlert_present = $db->baseTableExists("acid_ag_alert");
     }

     if ( !$tblBaseIPCache_present ) {
        if ( $db->DB_type == "mysql" ) {
           $sql = 'CREATE TABLE acid_ip_cache( ipc_ip                  INT UNSIGNED NOT NULL,
                                               ipc_fqdn                VARCHAR(50),
                                               ipc_dns_timestamp       DATETIME,
                                               ipc_whois               TEXT,
                                               ipc_whois_timestamp     DATETIME,
                                               PRIMARY KEY         (ipc_ip),
                                               INDEX               (ipc_ip))';
        } else if ($db->DB_type == "postgres") {
           $sql = 'CREATE TABLE acid_ip_cache( ipc_ip                  INT8 NOT NULL,
                                               ipc_fqdn                TEXT,
                                               ipc_dns_timestamp       TIMESTAMP,
                                               ipc_whois               TEXT,
                                               ipc_whois_timestamp     TIMESTAMP,
                                               PRIMARY KEY         (ipc_ip) );';
        } else if ($db->DB_type == "mssql") {
           /* Microsoft recommends specifying NULL if NULL is permitted */
           /* Otherwise it will unpredictably change the nullability. */
           $sql = 'CREATE TABLE acid_ip_cache ( ipc_ip   NUMERIC(10,0) NOT NULL,
                                                ipc_fqdn  VARCHAR(50) NULL,
                                                ipc_dns_timestamp  DATETIME NULL,
                                                ipc_whois      TEXT NULL,
                                                ipc_whois_timestamp  DATETIME NULL,
                                                PRIMARY KEY     (ipc_ip) )';
        } else if ($db->DB_type == "oci8") {
           $sql = 'CREATE TABLE acid_ip_cache( ipc_ip              INT NOT NULL,
                                               ipc_fqdn            VARCHAR2(50),
                                               ipc_dns_timestamp   DATE,
                                               ipc_whois           BLOB,
                                               ipc_whois_timestamp DATE,
                                               PRIMARY KEY         (ipc_ip) )';
        }

        $db->baseExecute($sql, -1, -1, false);
        if ( $db->baseErrorMessage() != "" )
           ErrorMessage("Unable to CREATE table 'acid_ip_cache': ".$db->baseErrorMessage());
        else
           ErrorMessage("Successfully created 'acid_ip_cache'");
        $tblBaseIPCache_present = $db->baseTableExists("acid_ip_cache");
     }

     if ( !$tblBaseEvent_present ) {
           if ( $db->DB_type == "mysql" ) {  
              if ( $db->baseGetDBversion() < 100 )
                 $sig_ddl = "signature      VARCHAR(255) NOT NULL,";
              else
                 $sig_ddl = "signature      INT UNSIGNED NOT NULL,";
              $sql = 'CREATE TABLE acid_event ( sid                 INT UNSIGNED NOT NULL,
                                                cid                 INT UNSIGNED NOT NULL,'.
                                                $sig_ddl.'     
                                                sig_name            VARCHAR(255),
                                                sig_class_id        INT UNSIGNED,
                                                sig_priority        INT UNSIGNED,
                                                timestamp           DATETIME NOT NULL,
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
                                                INDEX               (layer4_dport) )';
           
           } else if ($db->DB_type == "postgres") {
              if ( $db->baseGetDBversion() < 100 )
                 $sig_ddl = "signature      TEXT NOT NULL,";
              else
                 $sig_ddl = "signature      INT8 NOT NULL,";

              $sql = 'CREATE TABLE acid_event ( sid                 INT8 NOT NULL,
                                                cid                 INT8 NOT NULL,'.
                                                $sig_ddl.'
                                                sig_name            TEXT,
                                                sig_class_id        INT8,
                                                sig_priority        INT8,
                                                timestamp              TIMESTAMP NOT NULL,
                                                ip_src              INT8,
                                                ip_dst              INT8,
                                                ip_proto            INT4,
                                                layer4_sport        INT4,
                                                layer4_dport        INT4,
                                                PRIMARY KEY         (sid,cid) );
                      CREATE INDEX acid_event_signature ON acid_event (signature);
                      CREATE INDEX acid_event_sig_name ON acid_event (sig_name);
                      CREATE INDEX acid_event_sig_class_id ON acid_event (sig_class_id);
                      CREATE INDEX acid_event_sig_priority ON acid_event (sig_priority);
                      CREATE INDEX acid_event_timestamp ON acid_event (timestamp);
                      CREATE INDEX acid_event_ip_src ON acid_event (ip_src);
                      CREATE INDEX acid_event_ip_dst ON acid_event (ip_dst); 
                      CREATE INDEX acid_event_ip_proto ON acid_event (ip_proto);
                      CREATE INDEX acid_event_layer4_sport ON acid_event (layer4_sport);
                      CREATE INDEX acid_event_layer4_dport ON acid_event (layer4_dport);';
           } else if ($db->DB_type == "mssql") {
           /* Microsoft recommends specifying NULL if NULL is permitted */
           /* Otherwise it will unpredictably change the nullability. */
              if ( $db->baseGetDBversion() < 100 )
                 $sig_ddl = "signature      TEXT NOT NULL,";
              else
                 $sig_ddl = "signature      NUMERIC(10,0) NOT NULL,";
              $sql = 'CREATE TABLE acid_event ( sid                 NUMERIC(10,0) NOT NULL,
                                                cid                 NUMERIC(10,0) NOT NULL,'.
                                                $sig_ddl.'
                                                sig_name            VARCHAR(255) NULL,
                                                sig_class_id        NUMERIC(10,0) NULL,
                                                sig_priority        NUMERIC(10,0) NULL,
                                                timestamp           DATETIME NOT NULL,
                                                ip_src              NUMERIC(10,0) NULL,
                                                ip_dst              NUMERIC(10,0) NULL,
                                                ip_proto            NUMERIC(10,0) NULL,
                                                layer4_sport        NUMERIC(10,0) NULL,
                                                layer4_dport        NUMERIC(10,0) NULL,
                                                PRIMARY KEY         (sid,cid))';
           } else if ($db->DB_type == "oci8") {
              $sql = 'CREATE TABLE acid_event ( sid                 INT NOT NULL,
                                                cid                 INT NOT NULL,
                                                signature           INT NOT NULL,
                                                sig_name            VARCHAR2(255),
                                                sig_class_id        INT,
                                                sig_priority        INT,
                                                timestamp           DATE NOT NULL,
                                                ip_src              INT,
                                                ip_dst              INT,
                                                ip_proto            INT,
                                                layer4_sport        INT,
                                                layer4_dport        INT,
                                                PRIMARY KEY         (sid,cid))';
	   }

           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE table 'acid_event': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created 'acid_event'");
           $tblBaseEvent_present = $db->baseTableExists("acid_event");
     }

     if ($db->DB_type == "mssql") {
           /* Sheesh! If you create the indexes at the same time you create the */
           /*   tables, you get Attention and a disconnect (no error message). */
           /*   If you create the indexes here, it works. Go figure. */
           $sql = 'CREATE INDEX acid_ag_ag_id_idx ON acid_ag (ag_id)
                   CREATE INDEX acid_ag_alert_aid_idx ON acid_ag_alert (ag_id)
                   CREATE INDEX acid_ag_alert_id_idx ON acid_ag_alert (ag_sid, ag_cid)        
                   CREATE INDEX acid_event_sig_class_id ON acid_event (sig_class_id)
                   CREATE INDEX acid_event_sig_priority ON acid_event (sig_priority)
                   CREATE INDEX acid_event_timestamp ON acid_event (timestamp)
                   CREATE INDEX acid_event_ip_src ON acid_event (ip_src)
                   CREATE INDEX acid_event_ip_dst ON acid_event (ip_dst)
                   CREATE INDEX acid_event_ip_proto ON acid_event (ip_proto)
                   CREATE INDEX acid_event_layer4_sport ON acid_event (layer4_sport)
                   CREATE INDEX acid_event_layer4_dport ON acid_event (layer4_dport)';
           $db->baseExecute($sql, -1, -1, false);
           if ($db->baseErrorMessage() != "")
              ErrorMessage("Unable to CREATE MSSQL BASE table indexes : ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created MSSQL BASE table indexes");
     }
      
     /* Added for base_roles and base_users -- Kevin */
     if ( !$tblBaseRoles_present ) {
           if ( $db->DB_type == "mysql" ) {
              $sql = 'CREATE TABLE base_roles ( role_id           int(11)         NOT NULL,
                                                role_name         varchar(20)     NOT NULL,
                                                role_desc         varchar(75)     NOT NULL,
                                                PRIMARY KEY         (role_id));';
           } else if ($db->DB_type == "postgres") {
              $sql = 'CREATE TABLE base_roles ( role_id             INT8 NOT NULL,
                                                role_name           TEXT,
                                                role_desc           TEXT,
                                                PRIMARY KEY         (role_id) );';
           } else if ($db->DB_type == "mssql") {
           /* Microsoft recommends specifying NULL if NULL is permitted */
           /* Otherwise it will unpredictably change the nullability. */
              $sql = 'CREATE TABLE base_roles ( role_id   NUMERIC(10,0) NOT NULL,
                                                role_name  VARCHAR(20) NULL,
                                                role_desc  VARCHAR(75) NULL,
                                                PRIMARY KEY     (role_id) );';
           } else if ($db->DB_type == "oci8") {
              $sql = 'CREATE TABLE base_roles ( role_id           int          NOT NULL,
                                                role_name         varchar2(20) NOT NULL,
                                                role_desc         varchar2(75) NOT NULL,
                                                PRIMARY KEY       (role_id))';
           }

           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE table 'base_roles': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created 'base_roles'");
         
	   /* INSERT into base_roles - PostgreSQL cannot do multiple row */
           // Administrator
           $sql = "INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (1, 'Admin', 'Administrator');";
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to INSERT roles: ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully INSERTED Admin role");

           // Authenticated User
           $sql = "INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10, 'user', 'Authenticated User');";
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to INSERT roles: ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully INSERTED Authenticated User role");

           // Anonymous User
           $sql = "INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (10000, 'anonymous', 'Anonymous User');";
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to INSERT roles: ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully INSERTED Anonymous User role");

           // Alert Group Editor
           $sql = "INSERT INTO base_roles (role_id, role_name, role_desc) VALUES (50, 'ag_editor', 'Alert Group Editor');";
           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to INSERT roles: ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully INSERTED Alert Group Editor role");
           $tblBaseRoles_present = $db->baseTableExists("base_roles");
     }

     if ( !$tblBaseUsers_present ) {
           if ( $db->DB_type == "mysql" ) {
              $sql = 'CREATE TABLE base_users ( usr_id            int(11)          NOT NULL,
                                                usr_login         varchar(25)      NOT NULL,
                                                usr_pwd           varchar(32)      NOT NULL,
                                                usr_name          varchar(75)      NOT NULL,
                                                role_id           int(11)          NOT NULL,
                                                usr_enabled       int(11)          NOT NULL,
                                                PRIMARY KEY         (usr_id),
                                                INDEX               (usr_login));';
           } else if ($db->DB_type == "postgres") {
              $sql = 'CREATE TABLE base_users ( usr_id            INT8         NOT NULL,
                                                usr_login         TEXT         NOT NULL,
                                                usr_pwd           TEXT         NOT NULL,
                                                usr_name          TEXT         NOT NULL,
                                                role_id           INT8         NOT NULL,
                                                usr_enabled       INT8         NOT NULL,
                                                PRIMARY KEY       (usr_id));
                                                CREATE INDEX base_users_usr_login ON base_users (usr_login);';
           } else if ($db->DB_type == "mssql") {
              // I did not create the usr_login index yet -- Kevin
              $sql = 'CREATE TABLE base_users ( usr_id NUMERIC(10,0) NOT NULL,
                                                usr_login         VARCHAR(25)     NOT NULL,
                                                usr_pwd           VARCHAR(32)     NOT NULL,
                                                usr_name          VARCHAR(75)     NOT NULL,
                                                role_id           NUMERIC(10,0)   NOT NULL,
                                                usr_enabled       NUMERIC(10,0)   NOT NULL,
                                                PRIMARY KEY       (usr_id));';
           } else if ($db->DB_type == "oci8") {
              $sql = 'CREATE TABLE base_users ( usr_id            int             NOT NULL,
                                                usr_login         varchar2(25)    NOT NULL,
                                                usr_pwd           varchar2(32)    NOT NULL,
                                                usr_name          varchar2(75)    NOT NULL,
                                                role_id           number          NOT NULL,
                                                usr_enabled       number          NOT NULL,
                                                PRIMARY KEY       (usr_id))';
           }

           $db->baseExecute($sql, -1, -1, false);
           if ( $db->baseErrorMessage() != "" )
              ErrorMessage("Unable to CREATE table 'base_users': ".$db->baseErrorMessage());
           else
              ErrorMessage("Successfully created 'base_users'");
           $tblBaseUsers_present = $db->baseTableExists("base_users");
     }

     if ( $tblBaseAG_present && $tblBaseAGAlert_present && $tblBaseIPCache_present 
          && $tblBaseEvent_present && $tblBaseRoles_present && $tblBaseUsers_present )
        return 1;
     else
        return 0;
}

?>
