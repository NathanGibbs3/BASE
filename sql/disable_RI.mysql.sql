
-- Copyright (C) 2023 Nathan Gibbs
--
-- Author: Nathan Gibbs
--
-- - Purpose: Remove referential integrity from the database schema on
--   MySQL/MariaDB < 8.0.19

ALTER TABLE acid_event DROP FOREIGN KEY IF EXISTS acid_event_fkey_sid_cid;
ALTER TABLE acid_ag_alert DROP FOREIGN KEY IF EXISTS acid_ag_alert_fkey_sid_cid;
ALTER TABLE iphdr DROP FOREIGN KEY IF EXISTS iphdr_fkey_sid_cid;
ALTER TABLE tcphdr DROP FOREIGN KEY IF EXISTS tcphdr_fkey_sid_cid;
ALTER TABLE udphdr DROP FOREIGN KEY IF EXISTS udphdr_fkey_sid_cid;
ALTER TABLE icmphdr DROP FOREIGN KEY IF EXISTS icmphdr_fkey_sid_cid;
ALTER TABLE opt DROP FOREIGN KEY IF EXISTS opt_fkey_sid_cid;
ALTER TABLE data DROP FOREIGN KEY IF EXISTS data_fkey_sid_cid;
