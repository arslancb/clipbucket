ALTER TABLE `{tbl_prefix}video` ADD `video_password` VARCHAR( 255 ) NOT NULL AFTER `videokey` ;
ALTER TABLE `{tbl_prefix}video` ADD `video_users` TEXT NOT NULL AFTER `video_password`;
ALTER TABLE `{tbl_prefix}video` ADD `category_parents` TEXT NOT NULL AFTER `category`;

INSERT INTO `{tbl_prefix}config` (`configid` ,`name` ,`value`)VALUES
(NULL , 'comments_per_page', '15'),
(NULL, 'feedsSection', 'yes');
 

INSERT INTO `{tbl_prefix}email_templates` (
`email_template_id` ,
`email_template_name` ,
`email_template_code` ,
`email_template_subject` ,
`email_template` ,
`email_template_allowed_tags`
)
VALUES (
NULL , 'Video Subscription Email', 'video_subscription_email', '{uploader} has uploaded new video on {website_title}', 'Hello {username} You have been notified by {website_title} that {uploader} has uploaded new video Video Title : {video_title} Video Description : {video_description} <a href="{video_link}"> <img src="{video_thumb" border="0" height="90" width="120"><br> click here to watch this video</a> You are notified because you are subscribed to {uploader}, you can manage your subscriptions by going to your account and click on manage subscriptions. {website_title}', ''
);

ALTER TABLE `{tbl_prefix}video` ADD `subscription_email` ENUM( "pending", "sent" ) NOT NULL DEFAULT 'pending' AFTER `last_commented` ;
ALTER TABLE `{tbl_prefix}groups` ADD `group_admins` TEXT NOT NULL AFTER `userid` ;