ALTER TABLE `video` ADD `refer_url` TEXT NOT NULL AFTER `embed_code` 
ALTER TABLE `video` ADD `downloads` BIGINT( 255 ) NOT NULL AFTER `refer_url`
ALTER TABLE `users` ADD `total_downloads` BIGINT( 255 ) NOT NULL AFTER `welcome_email_sent`

ALTER TABLE `video_categories` ADD `category_order` INT( 5 ) NOT NULL DEFAULT '1' AFTER `category_name` 
ALTER TABLE `user_categories` ADD `category_order` INT( 5 ) NOT NULL DEFAULT '1' AFTER `category_name` 
ALTER TABLE `group_categories` ADD `category_order` INT( 5 ) NOT NULL DEFAULT '1' AFTER `category_name` 

ALTER TABLE `comments` ADD `type_owner_id` INT( 255 ) NOT NULL AFTER `type_id` 
ALTER TABLE `users` ADD `total_subscriptions` BIGINT( 255 ) NOT NULL AFTER `subscribers` 

INSERT INTO `config` (`configid` ,`name` ,`value`)
VALUES (NULL , 'anonymous_id', '1'),
(NULL , 'date_format', 'd-m-Y'),
( NULL , 'default_time_zone', '5'),
(NULL , 'autoplay_video', 'yes');