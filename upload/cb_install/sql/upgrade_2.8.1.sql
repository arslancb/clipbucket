ALTER TABLE `{tbl_prefix}video` ADD  `thumbs_version` varchar(5)  NOT NULL DEFAULT  "2.6";
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('index_recent','6');
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('index_featured','2');

INSERT INTO `{tbl_prefix}config` (`name`, `value`) VALUES ('clientid', 'your_client_id_here');

INSERT INTO `{tbl_prefix}config` (`name`, `value`) VALUES ('secretId', 'your_client_secret_here');
ALTER TABLE `{tbl_prefix}collection_categories` ADD `parent_id` int DEFAULT 1;
INSERT INTO `{tbl_prefix}config` (`name`, `value`) VALUES ('youtube_api_key', 'key_here');
/*Indexing of following tables*/
/*Author: Sikander Ali 	*/

/*Cb_collection*/
ALTER TABLE `{tbl_prefix}collections` ADD INDEX(`userid`);
ALTER TABLE `{tbl_prefix}collections` ADD INDEX(`featured`);
/*Editor Pick*/
ALTER TABLE `{tbl_prefix}editors_picks` ADD INDEX(`videoid`);
/*Favourites*/
ALTER TABLE `{tbl_prefix}favorites` ADD INDEX(`userid`);
/*Cb_Photos*/
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`userid`);
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`collection_id`);
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`featured`);
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`last_viewed`);
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`rating`);
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`total_comments`);
ALTER TABLE `{tbl_prefix}photos` ADD INDEX(`last_viewed`);

/*Cb_videos*/
ALTER TABLE `{tbl_prefix}video` ADD INDEX(`userid`);
ALTER TABLE `{tbl_prefix}video` ADD INDEX(`featured`);
ALTER TABLE `{tbl_prefix}video` ADD INDEX(`last_viewed`);
ALTER TABLE `{tbl_prefix}video` ADD INDEX(`rating`);
ALTER TABLE `{tbl_prefix}video` ADD INDEX(`comments_count`);
ALTER TABLE `{tbl_prefix}video` ADD INDEX(`last_viewed`);

UPDATE `{tbl_prefix}config` SET value = 'cb_28' WHERE name = 'template_dir';
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('stay_mp4','');
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('youtube_api_key','');
ALTER TABLE `{tbl_prefix}video` ADD `video_files` text(33) NOT NULL;
