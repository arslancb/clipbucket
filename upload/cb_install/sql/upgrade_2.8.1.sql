ALTER TABLE `{tbl_prefix}video` ADD  `thumbs_version` varchar(5)  NOT NULL DEFAULT  "2.6";
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('index_recent','6')
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('index_featured','2')


/*Indexing of following tables*/
/*Author: Sikander Ali 	*/

/*Cb_collection*/
ALTER TABLE `{tbl_prefix}cb_collections` ADD INDEX(`userid`);
ALTER TABLE `{tbl_prefix}cb_collections` ADD INDEX(`featured`);
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
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`userid`);
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`collection_id`);
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`featured`);
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`last_viewed`);
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`rating`);
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`total_comments`);
ALTER TABLE `{tbl_prefix}videos` ADD INDEX(`last_viewed`);

UPDATE `{tbl_prefix}config` SET value = 'cb_28' WHERE name = 'template_dir';