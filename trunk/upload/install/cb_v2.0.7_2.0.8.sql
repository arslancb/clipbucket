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

-- PHRASESE UPDATE
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