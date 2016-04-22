INSERT INTO `{tbl_prefix}config` (
`name` ,
`value`
)
VALUES
('default_country_iso2', 'PK'),
('channel_comments', '1'),
('max_profile_pic_size', '25'),
('max_profile_pic_height', ''),
('max_profile_pic_width', '140'),
('gravatars', 'yes'),
('picture_url', 'yes'),
('picture_upload', 'yes'),
('background_url', 'yes'),
('background_upload', 'yes'),
('max_bg_size', '25'),
('max_bg_width', ''),
('max_bg_height', '600'),
('background_color', 'yes'),
('send_comment_notification', 'no'),
('approve_video_notification', 'yes'),
('keep_mp4_as_is', 'yes'),
('hq_output', 'yes'),
('grp_categories', '3'),
('grps_items_search_page', '24'),
('grp_thumb_height', '140'),
('grp_thumb_width', '140'),
('grp_max_title', '20'),
('grp_max_desc', '500');
ALTER TABLE `{tbl_prefix}languages` ADD `language_active` ENUM( "yes", "no" ) NOT NULL DEFAULT 'yes' AFTER `language_regex` ;