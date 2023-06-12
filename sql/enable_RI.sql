
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
--
-- - Purpose:
--   Add referential integrity to the database schema

ALTER TABLE acid_event 
  ADD CONSTRAINT acid_event_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE;

ALTER TABLE acid_ag_alert
  ADD CONSTRAINT acid_ag_alert_fkey_sid_cid
    FOREIGN KEY (ag_sid,ag_cid) REFERENCES event (sid,cid)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE iphdr
  ADD CONSTRAINT iphdr_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE;

ALTER TABLE tcphdr 
  ADD CONSTRAINT tcphdr_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE;

ALTER TABLE udphdr  
  ADD CONSTRAINT udphdr_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE;

ALTER TABLE icmphdr  
  ADD CONSTRAINT icmphdr_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE;

ALTER TABLE opt  
  ADD CONSTRAINT opt_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE;

ALTER TABLE data 
  ADD CONSTRAINT data_fkey_sid_cid 
    FOREIGN KEY (sid,cid) REFERENCES event (sid,cid) 
    ON DELETE CASCADE
    ON UPDATE CASCADE;
