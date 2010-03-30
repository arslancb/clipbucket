-- Please Put all the DB changes here
-- Also add those changes that have been made and not been added

ALTER TABLE `video` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `video_comments` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `channel_comments` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `groups` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `group_members` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `group_posts` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `group_topics` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `group_videos` ADD `userid` INT NOT NULL AFTER `username` 

ALTER TABLE `messages` ADD `inbox_user_id` INT NOT NULL AFTER `sender` ,
ADD `outbox_user_id` INT NOT NULL AFTER `inbox_user_id` ,
ADD `sender_id` INT NOT NULL AFTER `outbox_user_id` ,
ADD `reciever_id` INT NOT NULL AFTER `sender_id` 

ALTER TABLE `subscriptions` ADD `subscriber_id` INT NOT NULL AFTER `subscribed_user` ,
ADD `userid` INT NOT NULL AFTER `subscriber_id` 

ALTER TABLE  `plugins` ADD  `plugin_folder` TEXT NOT NULL AFTER  `plugin_file` ;

ALTER TABLE `user_permissions` ADD `input_type` ENUM( 'text', 'radio', 'select', 'textarea' ) NOT NULL DEFAULT 'radio';