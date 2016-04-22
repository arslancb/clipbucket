UPDATE `{table_prefix}phrases` SET text='register_as_our_website_member' WHERE text='register_as_our_website_member' ;
UPDATE `{table_prefix}phrases` SET text='Register as a member, it`s free and easy just ' WHERE text='Register as a member, it's free and easy just ' ;
UPDATE `{table_prefix}phrases` SET text='email_wont_display' WHERE text='email_wont_display' ;
UPDATE `{table_prefix}phrases` SET text='Email (Wont` display)' WHERE text='Email (Wont' display)' ;

INSERT INTO {tbl_prefix}config (name,value) VALUES ('use_subs','0');

ALTER TABLE  {tbl_prefix}video_categories ADD  `parent_id` INT( 255 ) NOT NULL DEFAULT  '0' AFTER  `category_id`;