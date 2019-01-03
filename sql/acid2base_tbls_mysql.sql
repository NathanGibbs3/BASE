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
(10, 'user', 'Authenticated User'),
(10000, 'anonymous', 'Anonymous User'),
(50, 'ag_editor', 'Alert Group Editor');

