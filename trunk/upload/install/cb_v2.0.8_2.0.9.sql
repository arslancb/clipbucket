-- PHRASESE UPDATE
UPDATE `{table_prefix}phrases` SET text='Sorry , User Doesn`t Exist' WHERE text='Sorry , User Doesn't Exist' ;
UPDATE `{table_prefix}phrases` SET text='Please Don`t Try To Cheat' WHERE text='Please Don't Try To Cheat' ;
UPDATE `{table_prefix}phrases` SET text='Please Don`t Try To Cross Your Limits' WHERE text='Please Don't Try To Cross Your Limits' ;
UPDATE `{table_prefix}phrases` SET text='Message Doesn`t Exist' WHERE text='Message Doesn't Exist' ;
UPDATE `{table_prefix}phrases` SET text='Sorry, Video Doesn`t Exist' WHERE text='Sorry, Video Doesn't Exist' ;
UPDATE `{table_prefix}phrases` SET text='User doesn`t have any videos' WHERE text='User doesn't have any videos' ;
UPDATE `{table_prefix}phrases` SET text='You don`t have sufficient permissions' WHERE text='You don't have sufficient permissions' ;
UPDATE `{table_prefix}phrases` SET text='Please enter your username and activation code in order to activate your account, 

please check your inbox for the Activation code, if you didn`t get one, please request it by filling the next form' WHERE text='Please enter your username and activation code in order to activate your account, 

please check your inbox for the Activation code, if you didn't get one, please request it by filling the next form' ;
UPDATE `{table_prefix}phrases` SET text='register_as_our_website_member' WHERE text='register_as_our_website_member' ;
UPDATE `{table_prefix}phrases` SET text='Register as a member, it`s free and easy just ' WHERE text='Register as a member, it's free and easy just ' ;
UPDATE `{table_prefix}phrases` SET text='email_wont_display' WHERE text='email_wont_display' ;
UPDATE `{table_prefix}phrases` SET text='Email (Wont` display)' WHERE text='Email (Wont' display)' ;

-- NEW CONFIG
INSERT INTO {tbl_prefix}config (name,value) VALUES ('use_subs','0');

-- ALTER TABLE
ALTER TABLE  {tbl_prefix}video_categories ADD  `parent_id` INT( 255 ) NOT NULL DEFAULT  '0' AFTER  `category_id`;

-- NEW PHRASES
INSERT INTO `{tbl_prefix}phrases` (`lang_iso`, `varname`, `text`) VALUES
('en', '404_error', '404 Error. Requested page not found.'),
('en', '403_error', '403 Error. Sorry, you cannot access this page.'),
('en', 'err_warning', 'Please create your custom %s error page in your styles/template_name/layout folder. <a href="%s">Click Here For Tutorial</a>');