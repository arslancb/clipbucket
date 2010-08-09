-- Language Vars
INSERT INTO `{tbl_prefix}phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(NULL, 'en', 'pending_requests', 'Pending requests'),
(NULL, 'en', 'friend_add_himself_error', 'You cannot add yourself as a friend');

-- New Configs

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'allow_username_spaces', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'use_playlist', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'comments_captcha', 'all');

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'player_logo_file', 'logo.png');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'logo_placement', 'br');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'buffer_time', '3');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'youtube_enabled', 'yes');

-- Contacts Table Alteration

ALTER TABLE `{tbl_prefix}contacts` ADD `request_type` ENUM( 'in', 'out' ) NOT NULL AFTER `contact_group_id` ;

-- Phrases Update
UPDATE `{table_prefix}phrases` SET text='Sorry , User Doesn`t Exist' WHERE text='Sorry , User Doesn’t Exist' ;
UPDATE `{table_prefix}phrases` SET text='Please Don`t Try To Cheat' WHERE text='Please Don’t Try To Cheat' ;
UPDATE `{table_prefix}phrases` SET text='Please Don`t Try To Cross Your Limits' WHERE text='Please Don’t Try To Cross Your Limits' ;
UPDATE `{table_prefix}phrases` SET text='Message Doesn`t Exist' WHERE text='Message Doesn’t Exist' ;
UPDATE `{table_prefix}phrases` SET text='Sorry, Video Doesn`t Exist' WHERE text='Sorry, Video Doesn’t Exist' ;
UPDATE `{table_prefix}phrases` SET text='User doesn`t have any videos' WHERE text='User doesn’t have any videos' ;
UPDATE `{table_prefix}phrases` SET text='You don`t have sufficient permissions' WHERE text='You don’t have sufficient permissions' ;
UPDATE `{table_prefix}phrases` SET text='Please enter your username and activation code in order to activate your account, 

please check your inbox for the Activation code, if you didn`t get one, please request it by filling the next form' WHERE text='Please enter your username and activation code in order to activate your account, 

please check your inbox for the Activation code, if you didn’t get one, please request it by filling the next form' ;
UPDATE `{table_prefix}phrases` SET text='register_as_our_website_member' WHERE text='register_as_our_website_member' ;
UPDATE `{table_prefix}phrases` SET text='Register as a member, it`s free and easy just ' WHERE text='Register as a member, it’s free and easy just ' ;
UPDATE `{table_prefix}phrases` SET text='email_wont_display' WHERE text='email_wont_display' ;
UPDATE `{table_prefix}phrases` SET text='Email (Wont` display)' WHERE text='Email (Wont’ display)' ;

-- UPDATING DATE-FORMAT
UPDATE `{table_prefix}config` SET date_format='Y-m-d' WHERE date_format='m-d-Y';

-- NEW PHRASES
INSERT INTO `{tbl_prefix}phrases` (`lang_iso`, `varname`, `text`) VALUES
('en', 'cant_pm_banned_user', 'You have banned %s. Please unban to send private message.'),
('en', 'cant_pm_user_banned_you', 'You have been banned by %s.'),
('en', 'you_cant_send_pm_yourself', 'You can not send private message to yourself'),
('en', 'you_cant_sub_yourself', 'You can not subscribe to yourself'),
('en', 'view_tp_inactive_user', 'You are still inactive. Please contact group owner if you think a handsome amount of time have passed since you joined.'),
('en', 'view_tp_join', 'To view topics, please join group'),
('en', 'you_not_grp_mem_or_approved', 'You are not group member or are not approved by group owner.'),
('en', 'you_cant_perform_actions_on_grp_own', 'You can not perform such action on your own group.');

-- NEW CONFIG
INSERT INTO {tbl_prefix}config (name,value) VALUES ('use_subs','0');

-- ALTER TABLE
ALTER TABLE  {tbl_prefix}video_categories ADD  `parent_id` INT( 255 ) NOT NULL DEFAULT  '0' AFTER  `category_id`;

-- NEW PHRASES
INSERT INTO `{tbl_prefix}phrases` (`lang_iso`, `varname`, `text`) VALUES
('en', '404_error', '404 Error. Requested page not found.'),
('en', '403_error', '403 Error. Sorry, you cannot access this page.'),
('en', 'err_warning', 'Please create your custom %s error page in your styles/template_name/layout folder. <a href="%s">Click Here For Tutorial</a>');

-- NEW CONFIGS
INSERT INTO {tbl_prefix}config (name,value) VALUES
('use_ffmpeg_vf','no'),
('use_crons','no'),
('mail_type','mail'),
('smtp_host','mail.myserver.com'),
('smtp_user','user@myserver.com'),
('smtp_pass','password'),
('smtp_auth','yes'),
('smtp_port','26');
