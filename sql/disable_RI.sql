
-- Copyright (C) 2023 Nathan Gibbs
--
-- Author: Nathan Gibbs
--
-- - Purpose: Remove referential integrity from the database schema on most
--   DB servers.

ALTER TABLE acid_event DROP CONSTRAINT IF EXISTS acid_event_fkey_sid_cid;
ALTER TABLE acid_ag_alert DROP CONSTRAINT IF EXISTS acid_ag_alert_fkey_sid_cid;
ALTER TABLE iphdr DROP CONSTRAINT IF EXISTS iphdr_fkey_sid_cid;
ALTER TABLE tcphdr DROP CONSTRAINT IF EXISTS tcphdr_fkey_sid_cid;
ALTER TABLE udphdr DROP CONSTRAINT IF EXISTS udphdr_fkey_sid_cid;
ALTER TABLE icmphdr DROP CONSTRAINT IF EXISTS icmphdr_fkey_sid_cid;
ALTER TABLE opt DROP CONSTRAINT IF EXISTS opt_fkey_sid_cid;
ALTER TABLE data DROP CONSTRAINT IF EXISTS data_fkey_sid_cid;
