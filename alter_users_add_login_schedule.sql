-- Migration: add login_schedule column to users table
-- Safe for MySQL 5.7+/8.0. Uses TEXT to keep compatibility across versions.

ALTER TABLE `users`
    ADD COLUMN `login_schedule` TEXT NULL AFTER `first_connexion`;

-- Notes:
-- - Store weekly schedule JSON like:
--   {
--     "mon": {"enabled": true,  "start": "08:00", "end": "17:00"},
--     "tue": {"enabled": false, "start": "00:00", "end": "23:59"},
--     ...
--   }
-- - If you prefer JSON type and your MySQL version supports it, you may use:
--     ALTER TABLE `users` ADD COLUMN `login_schedule` JSON NULL AFTER `first_connexion`;
--   But ensure your ORM/DB layer supports JSON type.
