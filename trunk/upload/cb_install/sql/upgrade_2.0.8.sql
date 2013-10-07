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
UPDATE `{table_prefix}config` SET date_format='Y-m-d' WHERE date_format='m-d-Y';
