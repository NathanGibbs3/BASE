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