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

INSERT INTO `base_roles` (`role_id`, `role_name`, `role_desc`) VALUES (1, 'Admin', 'Administrator');
INSERT INTO `base_roles` (`role_id`, `role_name`, `role_desc`) VALUES (10, 'user', 'Authenticated User');
INSERT INTO `base_roles` (`role_id`, `role_name`, `role_desc`) VALUES (10000, 'anonymous', 'Anonymous User');
INSERT INTO `base_roles` (`role_id`, `role_name`, `role_desc`) VALUES (50, 'ag_editor', 'Alert Group Editor');
