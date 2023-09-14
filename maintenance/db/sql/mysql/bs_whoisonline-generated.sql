-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: extensions/BlueSpiceWhoIsOnline/maintenance/db/sql/bs_whoisonline.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE /*_*/bs_whoisonline (
  wo_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  wo_user_id INT UNSIGNED NOT NULL,
  wo_user_name VARBINARY(255) DEFAULT '' NOT NULL,
  wo_user_real_name VARBINARY(255) DEFAULT '' NOT NULL,
  wo_page_id INT UNSIGNED NOT NULL,
  wo_page_namespace INT NOT NULL,
  wo_page_title VARBINARY(255) NOT NULL,
  wo_action VARBINARY(32) NOT NULL,
  wo_log_ts BINARY(14) DEFAULT '0' NOT NULL,
  PRIMARY KEY(wo_id)
) /*$wgDBTableOptions*/;