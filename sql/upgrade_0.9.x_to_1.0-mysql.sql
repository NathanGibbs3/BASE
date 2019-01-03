-- This file is for people who are upgrading from a 0.9.x version of BASE
-- It will fix a size problem in the base_users table.
-- It is only necessary for users of MySQL since we did not have version of
-- the SQL files for the other databases until 1.0

ALTER TABLE `base_users` CHANGE `usr_pwd` `usr_pwd` VARCHAR( 32 ) NOT NULL 