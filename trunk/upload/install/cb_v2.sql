-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2010 at 01:19 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `svn_clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}action_log`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}action_log` (
  `action_id` int(255) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(60) CHARACTER SET latin1 NOT NULL,
  `action_username` varchar(60) CHARACTER SET latin1 NOT NULL,
  `action_userid` int(30) NOT NULL,
  `action_useremail` varchar(200) CHARACTER SET latin1 NOT NULL,
  `action_userlevel` int(11) NOT NULL,
  `action_ip` varchar(15) CHARACTER SET latin1 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action_success` enum('yes','no') CHARACTER SET latin1 NOT NULL,
  `action_details` text CHARACTER SET latin1 NOT NULL,
  `action_link` text NOT NULL,
  `action_obj_id` int(255) NOT NULL,
  `action_done_id` int(255) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}action_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}admin_notes`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}admin_notes` (
  `note_id` int(225) NOT NULL AUTO_INCREMENT,
  `note` text CHARACTER SET ucs2 NOT NULL,
  `date_added` datetime NOT NULL,
  `userid` int(225) NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}admin_notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}ads_data`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}ads_data` (
  `ad_id` int(50) NOT NULL AUTO_INCREMENT,
  `ad_name` mediumtext NOT NULL,
  `ad_code` mediumtext NOT NULL,
  `ad_placement` varchar(50) NOT NULL DEFAULT '',
  `ad_category` int(11) NOT NULL DEFAULT '0',
  `ad_status` enum('0','1') NOT NULL DEFAULT '0',
  `ad_impressions` bigint(255) NOT NULL DEFAULT '0',
  `last_viewed` datetime NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `{tbl_prefix}ads_data`
--

INSERT INTO `{tbl_prefix}ads_data` (`ad_id`, `ad_name`, `ad_code`, `ad_placement`, `ad_category`, `ad_status`, `ad_impressions`, `last_viewed`, `date_added`) VALUES
(1, 'Adbox 300 x 250', '&lt;div style=\\''color:#0066cc; font-size:10px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:300px; height:250px; line-height:250px; border:1px solid #ccc\\'' align=\\&quot;center\\&quot;&gt;\r\n	Ad Box 300x250\r\n&lt;/div&gt;', 'ad_300x250', 0, '0', 233, '0000-00-00 00:00:00', '2010-02-13 00:00:00'),
(2, 'Adbox 160x600', '&lt;div style=\\''color:#0066cc; font-size:10px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:160px; height:600px;  border:1px solid #ccc; line-height:600px\\'' align=\\&quot;center\\&quot;&gt;\r\n	Ad Box 160x600\r\n&lt;/div&gt;', 'ad_160x600', 0, '1', 114, '0000-00-00 00:00:00', '2010-02-13 00:00:00'),
(3, 'Adbox 468x60', '&lt;div style=\\''color:#0066cc; font-size:10px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:468px; height:60px;  border:1px solid #ccc; line-height:60px\\'' align=\\&quot;center\\&quot;&gt;\r\n	Ad Box 468x60\r\n&lt;/div&gt;', 'ad_468x60', 0, '1', 543, '0000-00-00 00:00:00', '2010-02-13 00:00:00'),
(4, 'Adbox 728x90', '&lt;div style=\\''color:#0066cc; font-size:10px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:728px; height:90px;  border:1px solid #ccc; line-height:90px\\'' align=\\&quot;center\\&quot;&gt;\r\n	Ad Box 728x90\r\n&lt;/div&gt;', 'ad_728x90', 0, '1', 0, '0000-00-00 00:00:00', '2010-02-13 00:00:00'),
(5, 'Adbox 120x600', '&lt;div style=\\''color:#0066cc; font-size:10px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:120px; height:600px;  border:1px solid #ccc; line-height:600px\\'' align=\\&quot;center\\&quot;&gt;\r\n	Ad Box 120x600\r\n&lt;/div&gt;', 'ad_120x600', 0, '1', 0, '0000-00-00 00:00:00', '2010-02-13 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}ads_placements`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}ads_placements` (
  `placement_id` int(20) NOT NULL AUTO_INCREMENT,
  `placement` varchar(26) NOT NULL,
  `placement_name` varchar(50) NOT NULL,
  `disable` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`placement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `{tbl_prefix}ads_placements`
--

INSERT INTO `{tbl_prefix}ads_placements` (`placement_id`, `placement`, `placement_name`, `disable`) VALUES
(1, 'ad_160x600', 'Wide Skyscrapper 160 x 600', 'yes'),
(2, 'ad_468x60', 'Banner 468 x 60', 'yes'),
(3, 'ad_300x250', 'Medium Rectangle 300 x 250', 'yes'),
(4, 'ad_728x90', 'Leader Board 728 x 90', 'yes'),
(7, 'ad_120x600', 'Skyscrapper 120 x 600', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}comments`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}comments` (
  `comment_id` int(60) NOT NULL AUTO_INCREMENT,
  `type` varchar(3) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(60) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `parent_id` int(60) NOT NULL,
  `type_id` int(225) NOT NULL,
  `type_owner_id` int(255) NOT NULL,
  `vote` varchar(225) NOT NULL,
  `voters` text NOT NULL,
  `spam_votes` bigint(20) NOT NULL,
  `spam_voters` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment_ip` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}config`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}config` (
  `configid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`configid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=155 ;

--
-- Dumping data for table `{tbl_prefix}config`
--

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES
(1, 'site_title', 'ClipBucket v2'),
(2, 'site_slogan', 'A way to broadcast yourself'),
(3, 'baseurl', 'http://domain.tld'),
(4, 'basedir', '/path/goes/here'),
(5, 'template_dir', 'cbv2new'),
(6, 'player_file', 'hdflvplayer.plug.php'),
(7, 'closed', '0'),
(8, 'closed_msg', 'We Are Updating Our Website, Please Visit us after few hours.'),
(9, 'description', 'Clip Bucket is an ultimate Video Sharing script'),
(10, 'keywords', 'clip bucket video sharing website script'),
(11, 'ffmpegpath', '/usr/bin/ffmpeg'),
(12, 'flvtool2path', '/usr/bin/flvtool2'),
(13, 'mp4boxpath', '/usr/bin/MP4Box'),
(14, 'vbrate', '512000'),
(15, 'srate', '22050'),
(16, 'r_height', '300'),
(17, 'r_width', '400'),
(18, 'resize', 'no'),
(19, 'mencoderpath', ''),
(20, 'keep_original', '1'),
(21, 'activation', ''),
(22, 'mplayerpath', ''),
(23, 'email_verification', '1'),
(24, 'allow_registeration', '1'),
(25, 'php_path', '/usr/bin/local/php'),
(26, 'videos_list_per_page', '25'),
(27, 'channels_list_per_page', '25'),
(28, 'videos_list_per_tab', '1'),
(29, 'channels_list_per_tab', '1'),
(30, 'video_comments', '1'),
(31, 'video_rating', '1'),
(32, 'comment_rating', '1'),
(33, 'video_download', '1'),
(34, 'video_embed', '1'),
(35, 'groups_list_per_page', '25'),
(36, 'seo', 'yes'),
(37, 'admin_pages', '60'),
(38, 'search_list_per_page', '25'),
(39, 'recently_viewed_limit', '10'),
(40, 'max_upload_size', '1000'),
(41, 'sbrate', '128000'),
(42, 'thumb_width', '120'),
(43, 'thumb_height', '90'),
(45, 'user_comment_opt1', ''),
(44, 'ffmpeg_type', ''),
(46, 'user_comment_opt2', ''),
(47, 'user_comment_opt3', ''),
(48, 'user_comment_opt4', ''),
(49, 'user_rate_opt1', ''),
(50, 'captcha_type', '0'),
(51, 'allow_upload', 'yes'),
(52, 'allowed_types', 'wmv,avi,divx,3gp,mov,mpeg,mpg,xvid,flv,asf,rm,dat,mp4'),
(53, 'version', '2.0.1'),
(54, 'version_type', 'Alpha'),
(55, 'allow_template_change', '1'),
(56, 'allow_language_change', '1'),
(57, 'default_site_lang', ''),
(58, 'video_require_login', ''),
(59, 'audio_codec', 'libmp3lame'),
(60, 'con_modules_type', ''),
(61, 'remoteUpload', ''),
(62, 'embedUpload', ''),
(63, 'player_div_id', ''),
(64, 'code_dev', ' (Powered by ClipBucket)'),
(65, 'sys_os', ''),
(66, 'debug_level', ''),
(67, 'enable_troubleshooter', '1'),
(68, 'vrate', '30'),
(69, 'num_thumbs', '3'),
(70, 'big_thumb_width', '320'),
(71, 'big_thumb_height', '240'),
(72, 'user_max_chr', '15'),
(73, 'disallowed_usernames', 'shit, asshole, fucker'),
(74, 'min_age_reg', '0'),
(75, 'max_comment_chr', '800'),
(76, 'user_comment_own', ''),
(77, 'anonym_comments', 'yes'),
(78, 'player_dir', 'hdflvplayer'),
(79, 'player_width', '655'),
(80, 'player_height', '308'),
(81, 'default_country_iso2', 'PK'),
(82, 'channel_player_width', '600'),
(83, 'channel_player_height', '281'),
(84, 'videos_items_grp_page', '12'),
(85, 'videos_items_hme_page', '20'),
(86, 'videos_items_columns', '9'),
(87, 'videos_items_ufav_page', '25'),
(88, 'videos_items_uvid_page', '25'),
(89, 'videos_items_search_page', '30'),
(90, 'videos_item_channel_page', '10'),
(91, 'users_items_subscriptions', '5'),
(92, 'users_items_subscibers', '5'),
(93, 'users_items_contacts_channel', '5'),
(94, 'users_items_search_page', '12'),
(95, 'users_items_group_page', '15'),
(96, 'cbhash', 'PGRpdiBhbGlnbj0iY2VudGVyIj48IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPg0KUG93ZXJlZCBieSA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tLyI+Q2xpcEJ1Y2tldDwvYT4gJXMgfCA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tL2Fyc2xhbi1oYXNzYW4iPkFyc2xhbiBIYXNzYW48L2E+DQo8IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPjwvZGl2Pg=='),
(97, 'min_video_title', '4'),
(98, 'max_video_title', '60'),
(99, 'min_video_desc', '5'),
(100, 'max_video_desc', '300'),
(101, 'video_categories', '4'),
(102, 'min_video_tags', '3'),
(103, 'max_video_tags', '30'),
(104, 'video_codec', 'flv'),
(105, 'date_released', '01-05-2010'),
(106, 'date_installed', '01-05-2010'),
(107, 'date_updated', '2010-01-09 18:36:16'),
(108, 'support_email', 'support@website.tld'),
(109, 'website_email', 'email@website.td'),
(110, 'welcome_email', 'welcome@website.tld'),
(112, 'anonymous_id', '8'),
(113, 'date_format', 'm-d-Y'),
(114, 'default_time_zone', '5'),
(115, 'autoplay_video', 'yes'),
(116, 'default_country_iso2', 'PK'),
(117, 'channel_comments', '1'),
(118, 'max_profile_pic_size', '2500'),
(119, 'max_profile_pic_height', ''),
(120, 'max_profile_pic_width', '140'),
(121, 'gravatars', 'yes'),
(122, 'picture_url', 'yes'),
(123, 'picture_upload', 'yes'),
(124, 'background_url', 'yes'),
(125, 'background_upload', 'yes'),
(126, 'max_bg_size', '2500'),
(127, 'max_bg_width', '600'),
(128, 'max_bg_height', ''),
(129, 'background_color', 'yes'),
(130, 'send_comment_notification', 'yes'),
(131, 'approve_video_notification', 'yes'),
(132, 'keep_mp4_as_is', 'yes'),
(133, 'hq_output', 'yes'),
(134, 'grp_categories', '3'),
(136, 'grps_items_search_page', '25'),
(137, 'grp_thumb_height', '140'),
(138, 'grp_thumb_width', '140'),
(139, 'grp_max_title', '20'),
(140, 'grp_max_desc', '500'),
(141, 'quick_conv', 'yes'),
(142, 'server_friendly_conversion', ''),
(143, 'max_conversion', '2'),
(144, 'max_time_wait', '7200'),
(145, 'allow_unicode_usernames', 'yes'),
(146, 'min_username', '3'),
(147, 'max_username', '15'),
(154, 'youtube_enabled', 'yes'),
(148, 'allow_username_spaces', 'yes'),
(149, 'use_playlist', 'yes'),
(150, 'comments_captcha', 'guests'),
(151, 'player_logo_file', 'logo.jpg'),
(152, 'logo_placement', 'br'),
(153, 'buffer_time', '3');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}contacts`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}contacts` (
  `contact_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(225) NOT NULL,
  `contact_userid` int(225) NOT NULL,
  `confirmed` enum('yes','no') NOT NULL DEFAULT 'no',
  `contact_group_id` int(225) NOT NULL,
  `request_type` enum('in','out') NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}conversion_queue`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}conversion_queue` (
  `cqueue_id` int(11) NOT NULL AUTO_INCREMENT,
  `cqueue_name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `cqueue_ext` varchar(5) CHARACTER SET latin1 NOT NULL,
  `cqueue_tmp_ext` varchar(3) CHARACTER SET latin1 NOT NULL,
  `cqueue_conversion` enum('yes','no','p') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_started` varchar(32) NOT NULL,
  `time_completed` varchar(32) NOT NULL,
  PRIMARY KEY (`cqueue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}conversion_queue`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}countries`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}countries` (
  `country_id` int(80) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) CHARACTER SET latin1 NOT NULL,
  `name` varchar(80) CHARACTER SET latin1 NOT NULL,
  `name_en` varchar(80) CHARACTER SET latin1 NOT NULL,
  `iso3` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

--
-- Dumping data for table `{tbl_prefix}countries`
--

INSERT INTO `{tbl_prefix}countries` (`country_id`, `iso2`, `name`, `name_en`, `iso3`, `numcode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152),
(44, 'CN', 'CHINA', 'China', 'CHN', 156),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188),
(53, 'CI', 'COTE D''IVOIRE', 'Cote D''Ivoire', 'CIV', 384),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352),
(99, 'IN', 'INDIA', 'India', 'IND', 356),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE''S REPUBLIC OF', 'Korea, Democratic People''s Republic of', 'PRK', 408),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417),
(116, 'LA', 'LAO PEOPLE''S DEMOCRATIC REPUBLIC', 'Lao People''s Democratic Republic', 'LAO', 418),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600),
(168, 'PE', 'PERU', 'Peru', 'PER', 604),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716);

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}editors_picks`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}editors_picks` (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(225) NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `{tbl_prefix}editors_picks`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}email_settings`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}email_settings` (
  `email_settings_id` int(25) NOT NULL AUTO_INCREMENT,
  `email_settings_name` varchar(60) NOT NULL,
  `email_settings_value` mediumtext NOT NULL,
  `email_settings_headers` mediumtext NOT NULL,
  PRIMARY KEY (`email_settings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `{tbl_prefix}email_settings`
--

INSERT INTO `{tbl_prefix}email_settings` (`email_settings_id`, `email_settings_name`, `email_settings_value`, `email_settings_headers`) VALUES
(1, 'website_email', 'email@example.com', ''),
(2, 'support_email', 'support@example.com', ''),
(3, 'welcome_email', 'no-reply@example.com', ''),
(4, 'email_verification_template', 'Hello $username,\r\nThank For Joining Us, Your Account Details are\r\n\r\nUsername     : $username\r\nPassword     : $password\r\nEmail        : $email\r\nDate Joined  : $cur_date\r\n\r\nYour Account Is Inactive Please Activate it by using following link \r\n\r\n<a href=$baseurl/activation.php?username=$username&avcode=$avcode>Click Here</a>\r\n\r\n$baseurl/activation.php?username=$username&avcode=$avcode\r\n\r\n====================\r\nRegards\r\n$title', '$uname''s Account Activation'),
(5, 'welcome_message_template', 'Hello $username, Welcome to $title.\r\nYou are now our member, you can now\r\n\r\n-> Upload Videos\r\n-> Share Videos\r\n-> Make Friends and Send Messeges\r\n-> Now You Have Your Own Channel\r\n\r\nTo Access Your Account Please <a href=$baseurl>Click Here</a> and login\r\n\r\nThank You For Joining Us,\r\nRegards\r\n$title Team', 'Welcome $username to $title'),
(6, 'activate_request_template', 'Hello $username,\r\n\r\nYour Activation Code is : $avcode\r\n<a href=$baseurl/activation.php>Click Here</a> To Goto Activation Page\r\n\r\nDirect Activation\r\n==========================================\r\n<a href=$baseurl/activation.php?username=$username&avcode=$avcode>Click Here</a> or Copy & Paste the following link in your browser\r\n$baseurl/activation.php?username=$username&avcode=$avcode\r\n', '$username''s  Account Activation'),
(7, 'share_video_template', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	width: 120px;\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">$title</span>share video</td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">$username wants to share Video With You<div id="videoThumb"><a href="$baseurl/watch_video.php?v=$videokey">$videothumb<br>\r\n    watch video</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Video Description</span><br>\r\n      <span class="text">$videodes</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Messege</span><br>\r\n      <span class="text">$messege\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">$username</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights 2007 $title</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '$username Want To Share A Video With You'),
(8, 'share_picture_template', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">$title</span>share Picture </td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">$username wants to share Picture With You\r\n      <div id="videoThumb"><a href="$baseurl/view_picture.php?picid=$picid">$picture<br>\r\n    View Picture</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Picture Description</span><br>\r\n      <span class="text">$picdes</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Messege</span><br>\r\n      <span class="text">$messege\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">$username</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights 2007 $title</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '$username Want To Share A  Picture With You');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}email_templates`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `email_template_code` varchar(225) CHARACTER SET latin1 NOT NULL,
  `email_template_subject` mediumtext CHARACTER SET latin1 NOT NULL,
  `email_template` text CHARACTER SET latin1 NOT NULL,
  `email_template_allowed_tags` mediumtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`email_template_id`),
  UNIQUE KEY `email_template_code` (`email_template_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `{tbl_prefix}email_templates`
--

INSERT INTO `{tbl_prefix}email_templates` (`email_template_id`, `email_template_name`, `email_template_code`, `email_template_subject`, `email_template`, `email_template_allowed_tags`) VALUES
(1, 'Share Video Template', 'share_video_template', '[{website_title}] - {username} wants to share a video with you', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	width: 120px;\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\n#videoThumb img{border:0px}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">{website_title}</span>share video</td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">{username} wants to share Video With You\r\n      <div id="videoThumb"><a href="{video_link}"><img src="{video_thumb}"><br>\r\n    watch video</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Video Description</span><br>\r\n      <span class="text">{video_description}</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Message</span><br>\r\n      <span class="text">{user_message}\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">{username}</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights {date_year} {website_title}</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '{website_title},{'),
(2, 'Email Verification Template', 'email_verify_template', '[{website_title}] - Account activation email', 'Hello {username},\r\nThank you for joining {website_title}, one last step is required in order to activate your account\r\n\r\n<a href=''{baseurl}/activation.php?av_username={username}&avcode={avcode}''>Click Here</a>\r\n{baseurl}/activation.php?av_username={username}&avcode={avcode}\r\n\r\nEmail           : {email}\r\nUsername        : {username}\r\nActivation code : {avcode}\r\n\r\nif above given is not working , please go here and activate it\r\n<a href=''{baseurl}/activation.php''>{baseurl}/activation.php</a>\r\n\r\n====================\r\nRegards\r\n{website_title}', ''),
(3, 'Private Message Notification', 'pm_email_message', '[{website_title}] - {sender} has sent you a private message', '{sender} has sent you a private message, \r\n\r\n{subject}\r\n"{content}"\r\n\r\nclick here to view your inbox <a href="{baseurl}/private_message.php?mode=inbox&mid={msg_id}">{baseurl}/private_message.php?mode=inbox&mid={msg_id}</a>\r\n\r\n{website_title}', ''),
(4, 'Acitvation code request template', 'avcode_request_template', '[{website_title}] - Account activation code request', 'Hello {username},\r\n\r\nYour Activation Code is : {avcode}\r\n<a href=''{baseurl}/activation.php?av_username={username}&avcode={avcode}''>Click Here</a> To goto Activation Page\r\n\r\nDirect Activation\r\n==========================================\r\nClick Here or Copy & Paste the following link in your browser\r\n{baseurl}/activation.php?av_username={username}&avcode={avcode}\r\n\r\nif above given links are not working, please go here and activate it\r\n\r\nEmail           : {email}\r\nUsername        : {username}\r\nActivation code : {avcode}\r\n\r\nif above given is not working , please go here and activate it\r\n<a href=''{baseurl}/activation.php''>{baseurl}/activation.php</a>\r\n\r\n----------------\r\nRegards\r\n{website_title}', 'username,email,avcode,doj'),
(5, 'Welcome Message Template', 'welcome_message_template', 'Welcome {username} to {website_title}', 'Hello {username},\r\nThanks for joining at {website_title}!, you are now part of our community and we hope you will enjoy your stay\r\n\r\nAll the best,\r\n{website_title}', 'username,email'),
(6, 'Password Reset Request', 'password_reset_request', '[{website_title}] - Password reset confirmation', 'Dear {username}\r\nyou have requested a password reset, please follow the link in order to reset your password\r\n<a href="{baseurl}/forgot.php?mode=reset_pass&user={userid}&avcode={avcode}">{baseurl}/forgot.php?mode=reset_pass&user={userid}&avcode={avcode}</a>\r\n\r\n-----------------------------------------\r\nIF YOU HAVE NOT REQUESTED A PASSWORD RESTE - PLEASE IGNORE THIS MESSAGE\r\n-----------------------------------------\r\nRegards\r\n{website_title}', 'username,userid,avcode'),
(7, 'Passwor Reset Details', 'password_reset_details', '[{website_title}] - Password reset details', 'Dear {username}\r\nyour password has been reset\r\nyour new password is : {password}\r\n\r\n<a href="{login_link}">click here to login to website</a>\r\n<{login_link}>\r\n\r\n---------------\r\nRegards\r\n{website_title}', 'username,password'),
(8, 'Forgot username request', 'forgot_username_request', '[{website_title}] - your {website_title} username', 'Hello,\r\nyour {website_title} username is : {username}\r\n\r\n--------------\r\nRegards\r\n{website_title}', '{username}'),
(9, 'Friend Request Email', 'friend_request_email', '[{website_title}] {username} add you as friend', 'Hi {reciever},\r\n{sender} added you as a friend on {website_title}. We need to confirm that you know {sender} in order for you to be friends on {website_title}.\r\n\r\n<a href="{sender_link}">View profile of {sender}</a> \r\n<a href="{request_link}">lick here to respond to friendship request</a>\r\n\r\nThanks,\r\n{website_title} Team', 'reciever,sender,sender_link,request_link'),
(10, 'Friend Confirmation Email', 'friend_confirmation_email', '[{website_title}] - {sender} has confirmed you as a friend', 'Hi {reciever},\r\n{sender} confirmed you as a friend on {website_title}.\r\n\r\n<a href="{sender_link}">View {sender} profile</a>\r\n\r\nThanks,\r\nThe {website_title} Team', 'sender,reciever,sender_link'),
(11, 'Group Invitation', 'group_invitation', '[{website_title}] {sender} has invited you to join group &#8220;{group_name}&#8221;', '{sender} invited you to join the {website_title} group "{group_name}".\r\n\r\n{group_description}\r\n\r\nTo see more details and confirm this group invitation, follow the link below:\r\n<a href="{group_url}">{group_url}</a>\r\n\r\nThanks,\r\n{website_title}', 'sender,reciever,group_name,group_url'),
(12, 'Contact Form', 'contact_form', '[{website_title} - Contact] {reason} from {name}', 'Name : {name}\r\nEmail : {email}\r\nReason : {reason}\r\n\r\nMessage:\r\n{message}\r\n\r\n===============\r\nIp : {ip_address}\r\ndate : {now}', ''),
(13, 'Video Acitvation Email', 'video_activation_email', '[{website_title}] - Your video has been activated', 'Hell {username},\r\nYour video has been reviewed and activated by one of our staff, thanks for uploading this video. You can view this video here.\r\n{video_link}\r\n\r\nThanks\r\n{website_title} Team', ''),
(14, 'User Comment Email', 'user_comment_email', '[{website_title}] {username} made comment on your {obj}', '{username} has commented on your {obj}\r\n"{comment}"\r\n\r\n<a href="{obj_link}">{obj_link}</a>\r\n\r\n{website_title} team', '');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}favorites`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}favorites` (
  `favorite_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) CHARACTER SET latin1 NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}favorites`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}flags`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}flags` (
  `flag_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) CHARACTER SET latin1 NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `flag_type` bigint(25) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`flag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}flags`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}groups`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}groups` (
  `group_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_name` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(255) NOT NULL,
  `group_description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_tags` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_url` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_privacy` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `video_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `post_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  `featured` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `total_views` bigint(225) NOT NULL,
  `total_videos` int(225) NOT NULL,
  `total_members` int(225) NOT NULL,
  `total_topics` int(225) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `{tbl_prefix}group_categories`
--

INSERT INTO `{tbl_prefix}group_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(3, 'Uncategorized', 1, '', '2010-04-28 13:17:46', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_invitations`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_invitations` (
  `invitation_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `invited` int(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}group_invitations`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_members`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_members` (
  `group_mid` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`group_mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}group_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_posts`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}group_posts`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_topics`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_topics` (
  `topic_id` int(225) NOT NULL AUTO_INCREMENT,
  `topic_title` text NOT NULL,
  `userid` int(225) NOT NULL,
  `group_id` int(225) NOT NULL,
  `topic_post` text NOT NULL,
  `date_added` datetime NOT NULL,
  `last_poster` int(225) NOT NULL,
  `last_post_time` datetime NOT NULL,
  `total_views` bigint(225) NOT NULL,
  `total_replies` bigint(225) NOT NULL,
  `topic_icon` varchar(225) NOT NULL,
  `approved` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}group_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}group_videos`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_videos` (
  `group_video_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(255) NOT NULL,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}group_videos`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}hd_smart`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}hd_smart` (
  `hd_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `hd_config_name` varchar(225) NOT NULL,
  `hd_config_value` text NOT NULL,
  `hd_config_description` text NOT NULL,
  PRIMARY KEY (`hd_config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `{tbl_prefix}hd_smart`
--

INSERT INTO `{tbl_prefix}hd_smart` (`hd_config_id`, `hd_config_name`, `hd_config_value`, `hd_config_description`) VALUES
(1, 'auto_play', 'yes', ''),
(2, 'logo_placement', 'BR', ''),
(3, 'hd_skin', 'skin_black.swf', ''),
(4, 'custom_variables', '[]', ''),
(5, 'license', '', ''),
(6, 'embed_visible', 'true', '');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}languages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}languages` (
  `language_id` int(9) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `language_regex` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `language_default` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `{tbl_prefix}languages`
--

INSERT INTO `{tbl_prefix}languages` (`language_id`, `language_code`, `language_name`, `language_regex`, `language_active`, `language_default`) VALUES
(5, 'en', 'English', '/^en/i', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}messages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}messages` (
  `message_id` int(225) NOT NULL AUTO_INCREMENT,
  `message_from` int(20) NOT NULL,
  `message_to` varchar(200) NOT NULL,
  `message_content` mediumtext NOT NULL,
  `message_type` enum('pm','notification') NOT NULL DEFAULT 'pm',
  `message_attachments` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_subject` mediumtext NOT NULL,
  `message_status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `reply_to` int(225) NOT NULL DEFAULT '0',
  `message_box` enum('in','out') NOT NULL DEFAULT 'in',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}modules`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}modules` (
  `module_id` int(25) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(25) NOT NULL,
  `module_file` varchar(60) NOT NULL,
  `active` varchar(5) NOT NULL,
  `module_include_file` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}modules`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}pages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(225) NOT NULL,
  `page_title` varchar(225) NOT NULL,
  `page_content` text NOT NULL,
  `userid` int(225) NOT NULL,
  `active` enum('yes','no') NOT NULL,
  `delete_able` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `{tbl_prefix}pages`
--

INSERT INTO `{tbl_prefix}pages` (`page_id`, `page_name`, `page_title`, `page_content`, `userid`, `active`, `delete_able`, `date_added`) VALUES
(1, 'About us', 'About us', '<div style="margin: auto; width: 98%;"><font style="font-weight: bold;" size="4">About Us</font><hr noshade="noshade" size="1">\r\n  \r\n  \r\n    <p><span style="font-weight: bold;">ClipBucket </span>is one of the world''s best video sites . We specialize in short-form\r\noriginal content - from new, emerging talents and established Hollywood\r\nheavyweights alike. We''re committed to delivering an exceptional\r\nentertainment experience, and we do so by engaging and empowering our\r\naudience every step of the way.</p>\r\n      <p>Everyone can Watch Videos\r\non <span style="font-weight: bold;">ClipBucket</span>. People can see first-hand accounts of current events, find\r\nvideos about their hobbies and interests, and discover the\r\nquirky and unusual. As more people capture special moments on\r\nvideo,<span style="font-weight: bold;">ClipBucket </span>is empowering them to become the broadcasters of\r\ntomorrow.</p>\r\n      <p><span style="font-weight: bold;">ClipBucket </span>not only a video sharing website but\r\nalso has social network features, you can make friends,\r\nand send them videos and private messages. <span style="font-weight: bold;">ClipBucket </span><span style="font-weight: bold;"></span> also has built in\r\nrating system and comment system so that people can discuss on there\r\ninterested videos, not only comment but also, people can rate Comments.</p></div>', 1, 'yes', 'no', '2010-01-01 08:47:56'),
(2, 'Privacy Policy', 'Privacy Policy', '<h1>ClipBucket Privacy Notice - YT Version\r\n</h1>\r\n<h2>Personal Information</h2>\r\n<ul>\r\n  <li><strong>Browsing ClipBucket</strong> You can watch videos on ClipBucket without having a ClipBucket Account or a  PHPBucket Account. You also can contact us through the ClipBucket Help Center  or by emailing us directly without having to register for an account.</li>\r\n  <li><strong>Your ClipBucket Account.</strong> For some activities on ClipBucket, like uploading videos, posting  comments, flagging videos, or watching restricted videos, you need a  ClipBucket or PHPBucket Account. We ask for some personal information when  you create an account, including your email address and a password,  which is used to protect your account from unauthorized access. A  PHPBucket Account, additionally, allows you to access other PHPBucket  services that require registration.</li>\r\n  <li><strong>Usage Information.</strong> When you use ClipBucket, we may record information about your usage of the  site, such as the channels, groups and favorites you subscribe to,  which other users you communicate with, the videos you watch, the  frequency and size of data transfers, and information you display about  yourself as well as information you click on in ClipBucket (including UI  elements, settings). If you are logged in, we may associate that  information with your ClipBucket Account. In order to ensure the quality  of our service to you, we may place a tag (also called a "web beacon")  in HTML-based customer support emails or other communications with you  in order to confirm delivery.</li>\r\n  <li><strong>Content Uploaded to Site.</strong> Any personal information or video content that you voluntarily disclose  online (e.g., video comments, your profile page) may be collected and  used by others. If you download the ClipBucket Uploader, your copy  includes a unique application number. This number, and information  about your installation of the Uploader (version number, language) will  be sent to ClipBucket when the Uploader automatically checks for updates  and will be used to update your version of the Uploader.</li>\r\n</ul>\r\n<h2>Uses</h2>\r\n<ul>\r\n  <li>If  you submit personal information to ClipBucket, we may use that information  to operate, maintain, and improve the features and functionality of  ClipBucket, and to process any flagging activity or other communication  you send to us.</li>\r\n  <li>We do not use your  email address or other personal information to send commercial or  marketing messages without your consent. We may use your email address  without further consent for non-marketing or administrative purposes  (such as notifying you of major ClipBucket changes or for customer service  purposes). You also can choose how often ClipBucket sends you email  updates in your ClipBucket Account settings page.</li>\r\n  <li>We  use cookies, web beacons, and log file information to: (a) store  information so that you will not have to re-enter it during your visit  or the next time you visit ClipBucket; (b) provide custom, personalized  content and information; (c) monitor the effectiveness of our marketing  campaigns; (d) monitor aggregate metrics such as total number of  visitors and pages viewed; and (e) track your entries, submissions, and  status in promotions, sweepstakes, and contests.</li>\r\n</ul>\r\n<h2>Information That is Publicly Available</h2>\r\n<ul>\r\n  <li>When  you create a ClipBucket Account, some information about your ClipBucket  Account and your account activity will be provided to other users of  ClipBucket. This may include the date you opened your ClipBucket Account, the  date you last logged into your ClipBucket Account, your age (if you choose  to make it public), the country and the number of videos you have  watched.</li>\r\n  <li>Your ClipBucket Account name,  not your email address, is displayed to other users when you engage in  certain activities on ClipBucket, such as when you upload videos or send  messages through ClipBucket. Other users can contact you by leaving a  message or comment on the site.</li>\r\n  <li>Any  videos that you submit to ClipBucket may be redistributed through the  internet and other media channels, and may be viewed by other ClipBucket  users or the general public. </li>\r\n  <li>You  may also choose to add personal information which may include your  name, gender, profile picture or other details, that will be visible to  other users on your ClipBucket Account channel page. If you choose to add  certain features to your ClipBucket Account channel page, then these  features and your activity associated with these features will be  displayed to other users and may be aggregated and shared with your  friends or other users. Such shared activity may include your favorite  videos, videos you rated and videos that you have uploaded.</li>\r\n</ul>\r\n<h2>Your Choices</h2>\r\n<ul>\r\n  <li>If  you have a ClipBucket Account, you may update or correct your personal  profile information, email preferences and privacy settings at any time  by visiting your account profile page. </li>\r\n  <li>You  may control the information that is available to other users and your  confirmed friends at any time by editing your ClipBucket Account and the  features that are included on your channel page. If you have enabled  Active Sharing, other users may see that you, as identified by your  account name, not your email address, are watching the same video.</li>\r\n  <li>You  may, of course, decline to submit personal information through ClipBucket,  in which case you can still view videos and explore ClipBucket, but  ClipBucket may not be able to provide certain services to you. Some  advanced ClipBucket features may use other PHPBucket services like PHPBucket  Checkout or AdSense. The privacy notices of those services govern the  use of your personal information associated with them.</li>\r\n</ul>\r\n', 1, 'no', 'no', '2010-01-01 08:52:46'),
(3, 'Terms of Serivce', 'Terms of Service', 'Write your own terms of service...', 1, 'yes', 'no', '2010-01-01 08:53:57'),
(4, 'Help', 'Help', '<span style="font-weight: bold;">How to use ClipBucket</span><br><ol><li>Articles will be written pretty soon</li></ol>', 1, 'yes', 'no', '2010-01-01 09:17:36'),
(5, '403 Error', '403 Forbidden', '<h2>403 Access Denied</h2>\r\nSorry, you cannot access this page...', 1, 'yes', 'no', '0000-00-00 00:00:00'),
(6, '404 Error', '404 Not Found', '<h2>404 Not Found</h2>\r\nwe are unable to find requested URL on server..', 1, 'yes', 'no', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}phrases`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}phrases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_iso` varchar(5) NOT NULL DEFAULT 'en',
  `varname` varchar(250) NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4471 ;

--
-- Dumping data for table `{tbl_prefix}phrases`
--

INSERT INTO `{tbl_prefix}phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(3338, 'en', 'ad_name_error', 'Please enter a name for the Advertisment'),
(3339, 'en', 'ad_code_error', 'Error : Please enter a code for the Advertisement'),
(3340, 'en', 'ad_exists_error1', 'Advertisement does not exist'),
(3341, 'en', 'ad_exists_error2', 'Error : Advertisement with this name already exist'),
(3342, 'en', 'ad_add_msg', 'Advertisment was added succesfully'),
(3343, 'en', 'ad_msg', 'Ad Has Been '),
(3344, 'en', 'ad_update_msg', 'Advertisment has been Updated'),
(3345, 'en', 'ad_del_msg', 'Advertisement has been Deleted'),
(3346, 'en', 'ad_deactive', 'Deactivated'),
(3347, 'en', 'ad_active', 'Activated'),
(3348, 'en', 'ad_placment_delete_msg', 'Placement has been Removed'),
(3349, 'en', 'ad_placement_err1', 'Placement already exists'),
(3350, 'en', 'ad_placement_err2', 'Please Enter a name for the Placement'),
(3351, 'en', 'ad_placement_err3', 'Please Enter a Code for the Placement'),
(3352, 'en', 'ad_placement_msg', 'Placement has been Added'),
(3353, 'en', 'cat_img_error', 'Please Upload JPEG, GIF or PNG image only'),
(3354, 'en', 'cat_exist_error', 'Category doesn&#8217;t exist'),
(3355, 'en', 'cat_add_msg', 'Category has been added successfully'),
(3356, 'en', 'cat_update_msg', 'Category has been updated'),
(3357, 'en', 'grp_err', 'Group Doesn&#8217;t Exist'),
(3358, 'en', 'grp_fr_msg', 'Group has been set as featured'),
(3359, 'en', 'grp_fr_msg1', 'Selected Groups Have Been Removed From The Featured List'),
(3360, 'en', 'grp_ac_msg', 'Selected Groups Have Been Activated'),
(3361, 'en', 'grp_dac_msg', 'Selected Groups Have Been Dectivated'),
(3362, 'en', 'grp_del_msg', 'Group has been deleted'),
(3363, 'en', 'editor_pic_up', 'Video Has Been Moved Up'),
(3364, 'en', 'editor_pic_down', 'Video Has Been Moved Down'),
(3365, 'en', 'plugin_install_msg', 'Plugin has been installed'),
(3366, 'en', 'plugin_no_file_err', 'No file was found'),
(3367, 'en', 'plugin_file_detail_err', 'Unknown plugin details found'),
(3368, 'en', 'plugin_installed_err', 'Plugin already installed'),
(3369, 'en', 'plugin_no_install_err', 'Plugin is not installed'),
(3370, 'en', 'grp_name_error', 'Please enter group name'),
(3371, 'en', 'grp_name_error1', 'Group Name Already Exists'),
(3372, 'en', 'grp_des_error', 'Please Enter A Little Description For The Group'),
(3373, 'en', 'grp_tags_error', 'Please Enter Tags For The Group'),
(3374, 'en', 'grp_url_error', 'Please enter valid url for the Group'),
(3375, 'en', 'grp_url_error1', 'Please enter Valid URL name'),
(3376, 'en', 'grp_url_error2', 'Group URL Already Exists, Please Choose a Different URL'),
(3377, 'en', 'grp_tpc_error', 'Please enter a topic to add'),
(3378, 'en', 'grp_comment_error', 'You must enter a comment'),
(3379, 'en', 'grp_join_error', 'You have already joined this group'),
(3380, 'en', 'grp_prvt_error', 'This Group Is Private, Please Login to View this Group'),
(3381, 'en', 'grp_inact_error', 'This Group Is Inactive, Please Contact Administrator for the problem'),
(3382, 'en', 'grp_join_error1', 'You Have Not Joined This Group Yet'),
(3383, 'en', 'grp_exist_error', 'Sorry, Group Doesn&#8217;t Exist'),
(3384, 'en', 'grp_tpc_error1', 'This Topic is not approved by the Group Owner'),
(3385, 'en', 'grp_cat_error', 'Please Select A Category For Your group'),
(3386, 'en', 'grp_tpc_error2', 'Please enter a topic to add'),
(3387, 'en', 'grp_tpc_error3', 'Your Topic Requires Approval From The Owner Of This Group'),
(3388, 'en', 'grp_tpc_msg', 'Topic has been added'),
(3389, 'en', 'grp_comment_msg', 'Comment has been added'),
(3390, 'en', 'grp_vdo_msg', 'Videos Deleted'),
(3391, 'en', 'grp_vdo_msg1', 'Videos Added Successfully'),
(3392, 'en', 'grp_vdo_msg2', 'Videos Have Been Approved'),
(3393, 'en', 'grp_mem_msg', 'Member Has Been Deleted'),
(3394, 'en', 'grp_mem_msg1', 'Member Has Been Approved'),
(3395, 'en', 'grp_inv_msg', 'Your Invitation Has Been Sent'),
(3396, 'en', 'grp_tpc_msg1', 'Topic has been deleted'),
(3397, 'en', 'grp_tpc_msg2', 'Topic Has Been Approved'),
(3398, 'en', 'grp_fr_msg2', 'Group has been removed from featured list'),
(3399, 'en', 'grp_inv_msg1', 'Has Invited You To Join '),
(3400, 'en', 'grp_av_msg', 'Group has been activated'),
(3401, 'en', 'grp_da_msg', 'Group has been deactivated'),
(3402, 'en', 'grp_post_msg', 'Post Has Been Deleted'),
(3403, 'en', 'grp_update_msg', 'Group has been updated'),
(3404, 'en', 'grp_owner_err', 'Only Owner Can Add Videos To This Group'),
(3405, 'en', 'grp_owner_err1', 'You are not owner of this group'),
(3406, 'en', 'grp_owner_err2', 'You are the owner of this group. You cannot leave your group.'),
(3407, 'en', 'grp_prvt_err1', 'This group is private, you need invitiation from its owner in order to join'),
(3408, 'en', 'grp_rmv_msg', 'Selected Groups Have Been Removed From Your Account'),
(3409, 'en', 'grp_tpc_err4', 'Sorry, Topic Doesn&#8217;t Exist'),
(3410, 'en', 'grp_title_topic', 'Groups - Topic - '),
(3411, 'en', 'grp_add_title', '- Add Video'),
(3412, 'en', 'usr_sadmin_err', 'You Cannot Set SuperAdmin Username as Blank'),
(3413, 'en', 'usr_cpass_err', 'Confirm Password Doesn&#8217;t Match'),
(3414, 'en', 'usr_pass_err', 'Old password is incorrect'),
(3415, 'en', 'usr_email_err', 'Please Provide A Valid Email Address'),
(3416, 'en', 'usr_cpass_err1', 'Confirm password is incorrect'),
(3417, 'en', 'usr_pass_err1', 'Password is Incorrect'),
(3418, 'en', 'usr_cmt_err', 'You Must Login First To Comment'),
(3419, 'en', 'usr_cmt_err1', 'Please Type Something In the Comment Box'),
(3420, 'en', 'usr_cmt_err2', 'You cannot comment on your video'),
(3421, 'en', 'usr_cmt_err3', 'You Have Already Posted a Comment on this channel.'),
(3422, 'en', 'usr_cmt_err4', 'Comment Has Been Added'),
(3423, 'en', 'usr_cmt_del_msg', 'Comment Has Been Deleted'),
(3424, 'en', 'usr_cmt_del_err', 'An Error Occured While deleting a Comment'),
(3425, 'en', 'usr_cnt_err', 'You Cannot Add Yourself as a Contact'),
(3426, 'en', 'usr_cnt_err1', 'You Have Already Added This User To Your Contact List'),
(3427, 'en', 'usr_sub_err', 'You are already subscribed to %s'),
(3428, 'en', 'usr_exist_err', 'User Doesnt Exist'),
(3429, 'en', 'usr_ccode_err', 'You Have Entered Wrong Confirmation Code'),
(3430, 'en', 'usr_exist_err1', 'Sorry, No User Exists With This Email'),
(3431, 'en', 'usr_exist_err2', 'Sorry , User Doesnâ€™t Exist'),
(3432, 'en', 'usr_uname_err', 'Username is empty'),
(3433, 'en', 'usr_uname_err2', 'Username already exists'),
(3434, 'en', 'usr_pass_err2', 'Password Is Empty'),
(3435, 'en', 'usr_email_err1', 'Email is Empty'),
(3436, 'en', 'usr_email_err2', 'Please Enter A Valid Email Address'),
(3437, 'en', 'usr_email_err3', 'Email Address Is Already In Use'),
(3438, 'en', 'usr_pcode_err', 'Postal Codes Only Contain Numbers'),
(3439, 'en', 'usr_fname_err', 'First Name Is Empty'),
(3440, 'en', 'usr_lname_err', 'Last Name Is Empty'),
(3441, 'en', 'usr_uname_err3', 'Username Contains Unallowed Characters'),
(3442, 'en', 'usr_pass_err3', 'Passwords MisMatched'),
(3443, 'en', 'usr_dob_err', 'Please Select Date Of Birth'),
(3444, 'en', 'usr_ament_err', 'Sorry, you need to agree to the terms of use and privacy policy to create an account'),
(3445, 'en', 'usr_reg_err', 'Sorry, Registrations Are Temporarily Not Allowed, Please Try Again Later'),
(3446, 'en', 'usr_ban_err', 'User account is banned, please contact website administrator'),
(3447, 'en', 'usr_login_err', 'Username and Password Didn&#8217;t Match'),
(3448, 'en', 'usr_sadmin_msg', 'Super Admin Has Been Updated'),
(3449, 'en', 'usr_pass_msg', 'Your Password Has Been Changed'),
(3450, 'en', 'usr_cnt_msg', 'This User Has Been Added To Your Contact List'),
(3451, 'en', 'usr_sub_msg', 'You are now subsribed to %s'),
(3452, 'en', 'usr_uname_email_msg', 'We Have Sent you an Email containing Your Username, Please Check It'),
(3453, 'en', 'usr_rpass_email_msg', 'An Email Has Been Sent To You. Please Follow the Instructions there to Reset Your Password'),
(3454, 'en', 'usr_pass_email_msg', 'Password has been changed successfully'),
(3455, 'en', 'usr_email_msg', 'Email Settings Has Been Updated'),
(3456, 'en', 'usr_del_msg', 'User has been deleted'),
(3457, 'en', 'usr_dels_msg', 'Selected Users Have Been Deleted'),
(3458, 'en', 'usr_ac_msg', 'User has been activated'),
(3459, 'en', 'usr_dac_msg', 'User has been deactivated'),
(3460, 'en', 'usr_mem_ac', 'Selected Members Have Been Activated'),
(3461, 'en', 'usr_mems_ac', 'Selected Members Have Been Deactivated'),
(3462, 'en', 'usr_fr_msg', 'User Has Been Made a Featured Member'),
(3463, 'en', 'usr_ufr_msg', 'User Has Been Unfeatured'),
(3464, 'en', 'usr_frs_msg', 'Selected Users Have Been Set As Featured'),
(3465, 'en', 'usr_ufrs_msg', 'Selected Users Have Been Removed From The Featured List'),
(3466, 'en', 'usr_uban_msg', 'User Has Been Banned'),
(3467, 'en', 'usr_uuban_msg', 'User Has Been Unbanned'),
(3468, 'en', 'usr_ubans_msg', 'Selected Members Have Been Banned'),
(3469, 'en', 'usr_uubans_msg', 'Selected Members Have Been Unbanned'),
(3470, 'en', 'usr_pass_reset_conf', 'Password Reset Confirmation'),
(3471, 'en', 'usr_dear_user', 'Dear User'),
(3472, 'en', 'usr_pass_reset_msg', 'You Requested A Password Reset, Follow The Link To Reset Your Password'),
(3473, 'en', 'usr_rpass_msg', 'Password Has Been Reset'),
(3474, 'en', 'usr_rpass_req_msg', 'You Requested A Password Reset, Here is your new password : '),
(3475, 'en', 'usr_uname_req_msg', 'You Requested to Recover Your Username, Here is your username: '),
(3476, 'en', 'usr_uname_recovery', 'Username Recovery Email'),
(3477, 'en', 'usr_add_succ_msg', 'User Has Been Added'),
(3478, 'en', 'usr_upd_succ_msg', 'User has been updated'),
(3479, 'en', 'usr_activation_msg', 'Your account has been activated. Now you can login to your account and upload videos'),
(3480, 'en', 'usr_activation_err', 'This user is already activated'),
(3481, 'en', 'usr_activation_em_msg', 'We have sent you an email containing your activation code, please check your mail box'),
(3482, 'en', 'usr_activation_em_err', 'Email Doesn&#8217;t Exist or a User With This Email is already Activated'),
(3483, 'en', 'usr_no_msg_del_err', 'No Message Was Selected To Delete'),
(3484, 'en', 'usr_sel_msg_del_msg', 'Selected Messages Have Been Deleted'),
(3485, 'en', 'usr_pof_upd_msg', 'Profile has been updated'),
(3486, 'en', 'usr_arr_no_ans', 'no answer'),
(3487, 'en', 'usr_arr_elementary', 'Elementary'),
(3488, 'en', 'usr_arr_hi_school', 'High School'),
(3489, 'en', 'usr_arr_some_colg', 'Some College'),
(3490, 'en', 'usr_arr_assoc_deg', 'Associates Degree'),
(3491, 'en', 'usr_arr_bach_deg', 'Bachelor&#8217;s Degree'),
(3492, 'en', 'usr_arr_mast_deg', 'Master&#8217;s Degree'),
(3493, 'en', 'usr_arr_phd', 'Ph.D.'),
(3494, 'en', 'usr_arr_post_doc', 'Postdoctoral'),
(3495, 'en', 'usr_arr_single', 'Single'),
(3496, 'en', 'usr_arr_married', 'Married'),
(3497, 'en', 'usr_arr_comitted', 'Comitted'),
(3498, 'en', 'usr_arr_open_marriage', 'Open Marriage'),
(3499, 'en', 'usr_arr_open_relate', 'Open Relationship'),
(3500, 'en', 'title_crt_new_msg', 'Compose New Message'),
(3501, 'en', 'title_forgot', 'Forgot Something? Find it now !'),
(3502, 'en', 'title_inbox', ' - Inbox'),
(3503, 'en', 'title_sent', ' - Sent Folder'),
(3504, 'en', 'title_usr_contact', '&#8217;s Contact List'),
(3505, 'en', 'title_usr_fav_vids', '%&#8217;s Favorite Videos'),
(3506, 'en', 'title_view_channel', '&#8217;s Channel'),
(3507, 'en', 'title_edit_video', 'Edit Video - '),
(3508, 'en', 'vdo_title_err', 'Please Enter Video Title'),
(3509, 'en', 'vdo_des_err', 'Please Enter Video Description'),
(3510, 'en', 'vdo_tags_err', 'Please Enter Tags For The Video'),
(3511, 'en', 'vdo_cat_err', 'Please Choose Atleast 1 Category'),
(3512, 'en', 'vdo_cat_err1', 'You Can Only Choose Up to 3 Categories'),
(3513, 'en', 'vdo_sub_email_msg', ' and therefore this message is sent to you automatically that '),
(3514, 'en', 'vdo_has_upload_nv', 'Has Uploaded New Video'),
(3515, 'en', 'vdo_del_selected', 'Selected Videos Have Been Deleted'),
(3516, 'en', 'vdo_cheat_msg', 'Please Donâ€™t Try To Cheat'),
(3517, 'en', 'vdo_limits_warn_msg', 'Please Donâ€™t Try To Cross Your Limits'),
(3518, 'en', 'vdo_cmt_del_msg', 'Comment Has Been Deleted'),
(3519, 'en', 'vdo_iac_msg', 'Video Is Inactive - Please Contact Admin For Details'),
(3520, 'en', 'vdo_is_in_process', 'Video Is Being Processed - Please Contact Administrator for further details'),
(3521, 'en', 'vdo_upload_allow_err', 'Uploading Is Not Allowed By Website Owner'),
(3522, 'en', 'vdo_download_allow_err', 'Video Downloading Is Not Allowed'),
(3523, 'en', 'vdo_edit_owner_err', 'You Are Not Video Owner'),
(3524, 'en', 'vdo_embed_code_wrong', 'Embed Code Was Wrong'),
(3525, 'en', 'vdo_seconds_err', 'Wrong Value Entered For Seconds Field'),
(3526, 'en', 'vdo_mins_err', 'Wrong Value Entered For Minutes Field'),
(3527, 'en', 'vdo_thumb_up_err', 'Error In Uploading Thumb'),
(3528, 'en', 'class_error_occured', 'Sorry, An Error Occured'),
(3529, 'en', 'class_cat_del_msg', 'Category has been deleted'),
(3530, 'en', 'class_vdo_del_msg', 'Video has been deleted'),
(3531, 'en', 'class_vdo_fr_msg', 'Video has been marked as &#8220;Featured Video&#8221;'),
(3532, 'en', 'class_fr_msg1', 'Video has been removed from &#8220;Featured Videos&#8221;'),
(3533, 'en', 'class_vdo_act_msg', 'Video has been activated'),
(3534, 'en', 'class_vdo_act_msg1', 'Vidoe has been deactivated'),
(3535, 'en', 'class_vdo_update_msg', 'Video details have been updated'),
(3536, 'en', 'class_comment_err', 'You Must Login Before Postings Comments'),
(3537, 'en', 'class_comment_err1', 'Please Type Something In The Comment Box'),
(3538, 'en', 'class_comment_err2', 'You Cannot Post a Comment on  Your Own Video'),
(3539, 'en', 'class_comment_err3', 'You Have Already Posted a Comment, Please Wait for the others.'),
(3540, 'en', 'class_comment_err4', 'You Have Already Replied To That a Comment, Please Wait for the others.'),
(3541, 'en', 'class_comment_err5', 'You Cannot Post a Reply To Yourself'),
(3542, 'en', 'class_comment_msg', 'Comment Has Been Added'),
(3543, 'en', 'class_comment_err6', 'Please login to rate comment'),
(3544, 'en', 'class_comment_err7', 'You have already rated this comment'),
(3545, 'en', 'class_vdo_fav_err', 'This Video is Already Added To Your Favorites'),
(3546, 'en', 'class_vdo_fav_msg', 'This Video Has Been Added To Your Favorites'),
(3547, 'en', 'class_vdo_flag_err', 'You Have Already Flagged This Video'),
(3548, 'en', 'class_vdo_flag_msg', 'This Video Has Been Flagged As Inappropriate'),
(3549, 'en', 'class_vdo_flag_rm', 'Flag(s) Has/Have Been Removed'),
(3550, 'en', 'class_send_msg_err', 'Please Enter a Username or Select any User to Send Message'),
(3551, 'en', 'class_invalid_user', 'Invalid Username'),
(3552, 'en', 'class_subj_err', 'Message subject was empty'),
(3553, 'en', 'class_msg_err', 'Please Type Something In Message Box'),
(3554, 'en', 'class_sent_you_msg', 'Sent You A Message'),
(3555, 'en', 'class_sent_prvt_msg', 'Sent You A Private Message on '),
(3556, 'en', 'class_click_inbox', 'Please Click here To View Your Inbox'),
(3557, 'en', 'class_click_login', 'Click Here To Login'),
(3558, 'en', 'class_email_notify', 'Email Notification'),
(3559, 'en', 'class_msg_has_sent_to', 'Message Has Been Sent To '),
(3560, 'en', 'class_inbox_del_msg', 'Message Has Been Delete From Inbox '),
(3561, 'en', 'class_sent_del_msg', 'Message Has Been Delete From Sent Folder'),
(3562, 'en', 'class_msg_exist_err', 'Message Doesnâ€™t Exist'),
(3563, 'en', 'class_vdo_del_err', 'Video does not exist'),
(3564, 'en', 'class_unsub_msg', 'You have been unsubscribed sucessfully'),
(3565, 'en', 'class_sub_exist_err', 'Subscription Does Not Exist'),
(3566, 'en', 'class_vdo_rm_fav_msg', 'Video Has Been Removed From Favourites'),
(3567, 'en', 'class_vdo_fav_err1', 'This Video Is Not In Your Favourites List'),
(3568, 'en', 'class_cont_del_msg', 'Contact Has Been Deleted'),
(3569, 'en', 'class_cot_err', 'Sorry, This Contact Is Not In Your Contact List'),
(3570, 'en', 'class_vdo_ep_add_msg', 'Video Has Been Added To Editor&#8217;s Pick'),
(3571, 'en', 'class_vdo_ep_err', 'Video Is Already In The Editor&#8217;s Pick'),
(3572, 'en', 'class_vdo_ep_err1', 'You Have Already Picked 10 Videos Please Delete Alteast One to Add More'),
(3573, 'en', 'class_vdo_ep_msg', 'Video Has Been Removed From Editor&#8217;s Pick'),
(3574, 'en', 'class_vdo_exist_err', 'Sorry, Video Doesnâ€™t Exist'),
(3575, 'en', 'class_img_gif_err', 'Please Upload Gif Image Only'),
(3576, 'en', 'class_img_png_err', 'Please Upload Png Image Only'),
(3577, 'en', 'class_img_jpg_err', 'Please Upload Jpg Image Only'),
(3578, 'en', 'class_logo_msg', 'Logo Has Been Changed. Please Clear Cache If You Are Not Able To See the Changed Logo'),
(3579, 'en', 'com_forgot_username', 'Forgot Username | Password'),
(3580, 'en', 'com_join_now', 'Join Now'),
(3581, 'en', 'com_my_account', 'My Account'),
(3582, 'en', 'com_manage_vids', 'Manage Videos'),
(3583, 'en', 'com_view_channel', 'View My Channel'),
(3584, 'en', 'com_my_inbox', 'My Inbox'),
(3585, 'en', 'com_welcome', 'Welcome'),
(3586, 'en', 'com_top_mem', 'Top Members '),
(3587, 'en', 'com_vidz', 'Videos'),
(3588, 'en', 'com_sign_up_now', 'Sign Up Now !'),
(3589, 'en', 'com_my_videos', 'My Videos'),
(3590, 'en', 'com_my_channel', 'My Channel'),
(3591, 'en', 'com_my_subs', 'My Subscriptions'),
(3592, 'en', 'com_user_no_contacts', 'User Does Not Have Any Contact'),
(3593, 'en', 'com_user_no_vides', 'User Does Not Have Any Favourite Video'),
(3594, 'en', 'com_user_no_vid_com', 'User Has No Video Comments'),
(3595, 'en', 'com_view_all_contacts', 'View All Contacts of'),
(3596, 'en', 'com_view_fav_all_videos', 'View All Favourite Videos Of'),
(3597, 'en', 'com_login_success_msg', 'You Have Been Successfully Logged In.'),
(3598, 'en', 'com_logout_success_msg', 'You Have Been Successfully Logged Out.'),
(3599, 'en', 'com_not_redirecting', 'You are now Redirecting .'),
(3600, 'en', 'com_not_redirecting_msg', 'if your are not redirecting'),
(3601, 'en', 'com_manage_contacts', 'Manage Contacts '),
(3602, 'en', 'com_send_message', 'Send Message'),
(3603, 'en', 'com_manage_fav', 'Manage Favorites '),
(3604, 'en', 'com_manage_subs', 'Manage Subscriptions'),
(3605, 'en', 'com_subscribe_to', 'Subscribe to %s&#8217;s channel'),
(3606, 'en', 'com_total_subs', 'Total Subscribtions'),
(3607, 'en', 'com_total_vids', 'Total Videos'),
(3608, 'en', 'com_date_subscribed', 'Date Subscribed'),
(3609, 'en', 'com_search_results', 'Search Results'),
(3610, 'en', 'com_advance_results', 'Advanced Search'),
(3611, 'en', 'com_search_results_in', 'Search Results In'),
(3612, 'en', 'videos_being_watched', 'Recently Viewed...'),
(3613, 'en', 'latest_added_videos', 'Recent Additions'),
(3614, 'en', 'most_viewed', 'Most Viewed'),
(3615, 'en', 'recently_added', 'Recently Added'),
(3616, 'en', 'featured', 'Featured'),
(3617, 'en', 'highest_rated', 'Highest Rated'),
(3618, 'en', 'most_discussed', 'Most Discussed'),
(3619, 'en', 'style_change', 'Style Change'),
(3620, 'en', 'rss_feed_latest_title', 'RSS Feed for Most Recent Videos'),
(3621, 'en', 'rss_feed_featured_title', 'RSS Feed for Featured Videos'),
(3622, 'en', 'rss_feed_most_viewed_title', 'RSS Feed for Most Popular Videos'),
(3623, 'en', 'lang_folder', 'en'),
(3624, 'en', 'reg_closed', 'Registration Closed'),
(3625, 'en', 'reg_for', 'Registration for'),
(3626, 'en', 'is_currently_closed', 'is currently closed'),
(3627, 'en', 'about_us', 'About Us'),
(3628, 'en', 'account', 'Account'),
(3629, 'en', 'added', 'Added'),
(3630, 'en', 'advertisements', 'Advertisements'),
(3631, 'en', 'all', 'All'),
(3632, 'en', 'active', 'Active'),
(3633, 'en', 'activate', 'Activate'),
(3634, 'en', 'age', 'Age'),
(3635, 'en', 'approve', 'Approve'),
(3636, 'en', 'approved', 'Approved'),
(3637, 'en', 'approval', 'Approval'),
(3638, 'en', 'books', 'Books'),
(3639, 'en', 'browse', 'Browse'),
(3640, 'en', 'by', 'by'),
(3641, 'en', 'cancel', 'Cancel'),
(3642, 'en', 'categories', 'Categories'),
(3643, 'en', 'category', 'Category'),
(3644, 'en', 'channels', 'channels'),
(3645, 'en', 'check_all', 'Check All'),
(3646, 'en', 'click_here', 'Click Here'),
(3647, 'en', 'comments', 'Comments'),
(3648, 'en', 'community', 'Community'),
(3649, 'en', 'companies', 'Companies'),
(3650, 'en', 'contacts', 'Contacts'),
(3651, 'en', 'contact_us', 'Contact Us'),
(3652, 'en', 'country', 'Country'),
(3653, 'en', 'created', 'Created'),
(3654, 'en', 'date', 'Date'),
(3655, 'en', 'date_added', 'Date Added'),
(3656, 'en', 'date_joined', 'Date Joined'),
(3657, 'en', 'dear', 'Dear'),
(3658, 'en', 'delete', 'Delete'),
(3659, 'en', 'delete_selected', 'Delete Selected'),
(3660, 'en', 'des_title', 'Description:'),
(3661, 'en', 'duration', 'Duration'),
(3662, 'en', 'education', 'Education'),
(3663, 'en', 'email', 'email'),
(3664, 'en', 'embed', 'Embed'),
(3665, 'en', 'embed_code', 'Embed Code'),
(3666, 'en', 'favourite', 'Favorite'),
(3667, 'en', 'favourited', 'Favorited'),
(3668, 'en', 'favourites', 'Favorites'),
(3669, 'en', 'female', 'Female'),
(3670, 'en', 'filter', 'Filter'),
(3671, 'en', 'forgot', 'Forgot'),
(3672, 'en', 'friends', 'Friends'),
(3673, 'en', 'from', 'From'),
(3674, 'en', 'gender', 'Gender'),
(3675, 'en', 'groups', 'Groups'),
(3676, 'en', 'hello', 'Hello'),
(3677, 'en', 'help', 'Help'),
(3678, 'en', 'hi', 'Hi'),
(3679, 'en', 'hobbies', 'Hobbies'),
(3680, 'en', 'Home', 'Home'),
(3681, 'en', 'inbox', 'Inbox'),
(3682, 'en', 'interests', 'Interests'),
(3683, 'en', 'join_now', 'Join Now'),
(3684, 'en', 'joined', 'Joined'),
(3685, 'en', 'join', 'Join'),
(3686, 'en', 'keywords', 'Keywords'),
(3687, 'en', 'latest', 'Latest'),
(3688, 'en', 'leave', 'Leave'),
(3689, 'en', 'location', 'Location'),
(3690, 'en', 'login', 'Login'),
(3691, 'en', 'logout', 'Logout'),
(3692, 'en', 'male', 'Male'),
(3693, 'en', 'members', 'Members'),
(3694, 'en', 'messages', 'Messages'),
(3695, 'en', 'message', 'Message'),
(3696, 'en', 'minutes', 'minutes'),
(3697, 'en', 'most_members', 'Most Members'),
(3698, 'en', 'most_recent', 'Most Recent'),
(3699, 'en', 'most_videos', 'Most Videos'),
(3700, 'en', 'music', 'Music'),
(3701, 'en', 'my_account', 'My Account'),
(3702, 'en', 'next', 'Next'),
(3703, 'en', 'no', 'No'),
(3704, 'en', 'no_user_exists', 'No User Exists'),
(3705, 'en', 'no_video_exists', 'No Video Exists'),
(3706, 'en', 'occupations', 'Occupations'),
(3707, 'en', 'optional', 'optional'),
(3708, 'en', 'owner', 'Owner'),
(3709, 'en', 'password', 'password'),
(3710, 'en', 'please', 'Please'),
(3711, 'en', 'privacy', 'Privacy'),
(3712, 'en', 'privacy_policy', 'Privacy Policy'),
(3713, 'en', 'random', 'Random'),
(3714, 'en', 'rate', 'Rate'),
(3715, 'en', 'request', 'Request'),
(3716, 'en', 'related', 'Related'),
(3717, 'en', 'reply', 'Reply'),
(3718, 'en', 'results', 'Results'),
(3719, 'en', 'relationship', 'Relationship'),
(3720, 'en', 'seconds', 'seconds'),
(3721, 'en', 'select', 'Select'),
(3722, 'en', 'send', 'Send'),
(3723, 'en', 'sent', 'Sent'),
(3724, 'en', 'signup', 'Signup'),
(3725, 'en', 'subject', 'Subject'),
(3726, 'en', 'tags', 'Tags'),
(3727, 'en', 'times', 'Times'),
(3728, 'en', 'to', 'To'),
(3729, 'en', 'type', 'Type'),
(3730, 'en', 'update', 'Update'),
(3731, 'en', 'upload', 'Upload'),
(3732, 'en', 'url', 'Url'),
(3733, 'en', 'verification', 'Verification'),
(3734, 'en', 'videos', 'Videos'),
(3735, 'en', 'viewing', 'Viewing'),
(3736, 'en', 'welcome', 'Welcome'),
(3737, 'en', 'website', 'Website'),
(3738, 'en', 'yes', 'Yes'),
(3739, 'en', 'of', 'of'),
(3740, 'en', 'on', 'on'),
(3741, 'en', 'previous', 'Previous'),
(3742, 'en', 'rating', 'Rating'),
(3743, 'en', 'ratings', 'Ratings'),
(3744, 'en', 'remote_upload', 'Remote Upload'),
(3745, 'en', 'remove', 'Remove'),
(3746, 'en', 'search', 'Search'),
(3747, 'en', 'services', 'Services'),
(3748, 'en', 'show_all', 'Show All'),
(3749, 'en', 'signupup', 'Sign Up'),
(3750, 'en', 'sort_by', 'Sort'),
(3751, 'en', 'subscriptions', 'Subscriptions'),
(3752, 'en', 'subscribers', 'Subscribers'),
(3753, 'en', 'tag_title', 'Tags'),
(3754, 'en', 'time', 'time'),
(3755, 'en', 'top', 'Top'),
(3756, 'en', 'tos_title', 'Terms of Use'),
(3757, 'en', 'username', 'Username'),
(3758, 'en', 'views', 'Views'),
(3759, 'en', 'proccession_wait', 'Processing, Please Wait'),
(3760, 'en', 'mostly_viewed', 'Most Viewed'),
(3761, 'en', 'most_comments', 'Most Comments'),
(3762, 'en', 'group', 'Group'),
(3763, 'en', 'not_logged_in', 'You are not logged in or you do not have permission to access this page. This could be due to one of several reasons:'),
(3764, 'en', 'fill_auth_form', 'You are not logged in. Fill in the form below and try again.'),
(3765, 'en', 'insufficient_privileges', 'You may not have sufficient privileges to access this page.'),
(3766, 'en', 'admin_disabled_you', 'The site administrator may have disabled your account, or it may be awaiting activation.'),
(3767, 'en', 'Recover_Password', 'Recover Password'),
(3768, 'en', 'Submit', 'Submit'),
(3769, 'en', 'Reset_Fields', 'Reset Fields'),
(3770, 'en', 'admin_reg_req', 'The administrator may have required you to register before you can view this page.'),
(3771, 'en', 'lang_change', 'Language Change'),
(3772, 'en', 'lang_changed', 'Your language has been changed'),
(3773, 'en', 'lang_choice', 'Language'),
(3774, 'en', 'if_not_redir', 'Click here to continue if you are not automatically redirected.'),
(3775, 'en', 'style_changed', 'Your style has been changed'),
(3776, 'en', 'style_choice', 'Style'),
(3777, 'en', 'vdo_edit_vdo', 'Edit Video'),
(3778, 'en', 'vdo_stills', 'Video Stills'),
(3779, 'en', 'vdo_watch_video', 'Watch Video'),
(3780, 'en', 'vdo_video_details', 'Video Details'),
(3781, 'en', 'vdo_title', 'Title'),
(3782, 'en', 'vdo_desc', 'Description'),
(3783, 'en', 'vdo_cat', 'Video Category'),
(3784, 'en', 'vdo_cat_msg', 'You May Select Up To %s Categories'),
(3785, 'en', 'vdo_tags_msg', 'Tags are separated by commas ie Arslan Hassan, Awsome, ClipBucket'),
(3786, 'en', 'vdo_br_opt', 'Broadcast Options'),
(3787, 'en', 'vdo_br_opt1', 'Public - Share your video with Everyone! (Recommended)'),
(3788, 'en', 'vdo_br_opt2', 'Private - Viewable by you and your friends only.'),
(3789, 'en', 'vdo_date_loc', 'Date And Location'),
(3790, 'en', 'vdo_date_rec', 'Date Recorded'),
(3791, 'en', 'vdo_for_date', 'format MM / DD / YYYY '),
(3792, 'en', 'vdo_add_eg', 'e.g London Greenland, Sialkot Mubarak Pura'),
(3793, 'en', 'vdo_share_opt', 'Sharing Options'),
(3794, 'en', 'vdo_allow_comm', 'Allow Comments '),
(3795, 'en', 'vdo_dallow_comm', 'Do Not Allow Comments'),
(3796, 'en', 'vdo_comm_vote', 'Comments Voting'),
(3797, 'en', 'vdo_allow_com_vote', 'Allow Voting on Comments'),
(3798, 'en', 'vdo_dallow_com_vote', 'Do Not Allow on Comments'),
(3799, 'en', 'vdo_allow_rating', 'Yes, Allow Rating on this video'),
(3800, 'en', 'vdo_dallow_ratig', 'No, Do Not Allow Rating on this video'),
(3801, 'en', 'vdo_embedding', 'Embedding'),
(3802, 'en', 'vdo_embed_opt1', 'Yes, People can play this video on other websites'),
(3803, 'en', 'vdo_embed_opt2', 'No, People cannot play this video on other websites'),
(3804, 'en', 'vdo_update_title', 'Update'),
(3805, 'en', 'vdo_inactive_msg', 'Your Account is Inactive. Please Activate it to Upload Videos, To Activate your account Please'),
(3806, 'en', 'vdo_click_here', 'Click Here'),
(3807, 'en', 'vdo_continue_upload', 'Continue to Upload'),
(3808, 'en', 'vdo_upload_step1', 'Video Upload'),
(3809, 'en', 'vdo_upload_step2', 'Video Step %s/2'),
(3810, 'en', 'vdo_upload_step3', '(Step 2/2)'),
(3811, 'en', 'vdo_select_vdo', 'Select a video to upload.'),
(3812, 'en', 'vdo_enter_remote_url', 'Enter Url Of The Video.'),
(3813, 'en', 'vdo_enter_embed_code_msg', 'Enter Embed Video Code from other websites ie Youtube or Metacafe.'),
(3814, 'en', 'vdo_enter_embed_code', 'Enter Embed Code'),
(3815, 'en', 'vdo_enter_druation', 'Enter Duration'),
(3816, 'en', 'vdo_select_vdo_thumb', 'Select Video Thumb'),
(3817, 'en', 'vdo_having_trouble', 'Having Trouble?'),
(3818, 'en', 'vdo_if_having_problem', 'if you are having problems with the uploader'),
(3819, 'en', 'vdo_clic_to_manage_all', 'Click Here To Manage All Videos'),
(3820, 'en', 'vdo_manage_vdeos', 'Manage Videos '),
(3821, 'en', 'vdo_status', 'Status'),
(3822, 'en', 'vdo_rawfile', 'RawFile'),
(3823, 'en', 'vdo_video_upload_complete', 'Video Upload - Upload Complete'),
(3824, 'en', 'vdo_thanks_you_upload_complete_1', 'Thank you! Your upload is complete'),
(3825, 'en', 'vdo_thanks_you_upload_complete_2', 'This video will be available in'),
(3826, 'en', 'vdo_after_it_has_process', 'after it has finished processing.'),
(3827, 'en', 'vdo_embed_this_video_on_web', 'Embed this video on your website.'),
(3828, 'en', 'vdo_copy_and_paste_the_code', 'Copy and paste the code below to embed this video.'),
(3829, 'en', 'vdo_upload_another_video', 'Upload Another Video'),
(3830, 'en', 'vdo_goto_my_videos', 'Goto My Videos'),
(3831, 'en', 'vdo_sperate_emails_by', 'seperate emails by commas'),
(3832, 'en', 'vdo_personal_msg', 'Personal Message'),
(3833, 'en', 'vdo_related_tags', 'Related Tags'),
(3834, 'en', 'vdo_reply_to_this', 'Reply To This '),
(3835, 'en', 'vdo_add_reply', 'Add Reply'),
(3836, 'en', 'vdo_share_video', 'Share Video'),
(3837, 'en', 'vdo_about_this_video', 'About This Video'),
(3838, 'en', 'vdo_post_to_a_services', 'Post to an Aggregating Service'),
(3839, 'en', 'vdo_commentary', 'Commentary'),
(3840, 'en', 'vdo_post_a_comment', 'Post A Comment'),
(3841, 'en', 'grp_add_vdo_msg', 'Add Videos To Group '),
(3842, 'en', 'grp_no_vdo_msg', 'You Don&#8217;t Have Any Video'),
(3843, 'en', 'grp_add_to', 'Add To Group'),
(3844, 'en', 'grp_add_vdos', 'Add Videos'),
(3845, 'en', 'grp_name_title', 'Group name'),
(3846, 'en', 'grp_tag_title', 'Tags:'),
(3847, 'en', 'grp_des_title', 'Description:'),
(3848, 'en', 'grp_tags_msg', 'Enter one or more tags, separated by spaces.'),
(3849, 'en', 'grp_tags_msg1', 'Enter one or more tags, separated by spaces. Tags  are keywords used to describe your group so it can be easily found by  other users. For example, if you have a group for surfers, you might  tag it: surfing, beach, waves.'),
(3850, 'en', 'grp_url_title', 'Choose a unique group name URL:'),
(3851, 'en', 'grp_url_msg', 'Enter 3-18 characters with no spaces (such as &#8220;skateboarding skates&#8221;), that will become part of your group&#8217;s web address. Please note, the group name URL you pick is permanent and can&#8217;t be changed.'),
(3852, 'en', 'grp_cat_tile', 'Group Category:'),
(3853, 'en', 'grp_vdo_uploads', 'Video Uploads:'),
(3854, 'en', 'grp_forum_posting', 'Forum Posting:'),
(3855, 'en', 'grp_join_opt1', 'Public, anyone can join.'),
(3856, 'en', 'grp_join_opt2', 'Protected, requires founder approval to join.'),
(3857, 'en', 'grp_join_opt3', 'Private, by founder invite only, only members can view group details.'),
(3858, 'en', 'grp_vdo_opt1', 'Post videos immediately.'),
(3859, 'en', 'grp_vdo_opt2', 'Founder approval required before video is available.'),
(3860, 'en', 'grp_vdo_opt3', 'Only Founder can add new videos.'),
(3861, 'en', 'grp_post_opt1', 'Post topics immediately.'),
(3862, 'en', 'grp_post_opt2', 'Founder approval required before topic is available.'),
(3863, 'en', 'grp_post_opt3', 'Only Founder can create a new topic.'),
(3864, 'en', 'grp_crt_grp', 'Create Group'),
(3865, 'en', 'grp_thumb_title', 'Group Thumb'),
(3866, 'en', 'grp_upl_thumb', 'Upload Group Thumb'),
(3867, 'en', 'grp_must_be', 'Must Be'),
(3868, 'en', 'grp_90x90', '90  x 90 Ratio Will Give Best Quality'),
(3869, 'en', 'grp_thumb_warn', 'Do Not Upload Vulgar or Copyrighted Material'),
(3870, 'en', 'grp_del_confirm', 'Are You Sure You Want To Delete This Group'),
(3871, 'en', 'grp_del_success', 'You Have Successfully Deleted'),
(3872, 'en', 'grp_click_go_grps', 'Click Here To Go To Groups'),
(3873, 'en', 'grp_edit_grp_title', 'Edit Group'),
(3874, 'en', 'grp_manage_vdos', 'Manage Videos'),
(3875, 'en', 'grp_manage_mems', 'Manage Members'),
(3876, 'en', 'grp_del_group_title', 'Delete Group'),
(3877, 'en', 'grp_add_vdos_title', 'Add Videos'),
(3878, 'en', 'grp_join_grp_title', 'Join Group'),
(3879, 'en', 'grp_leave_group_title', 'Leave Group'),
(3880, 'en', 'grp_invite_grp_title', 'Invite Members'),
(3881, 'en', 'grp_view_mems', 'View Members'),
(3882, 'en', 'grp_view_vdos', 'View Videos'),
(3883, 'en', 'grp_create_grp_title', 'Create A New Group'),
(3884, 'en', 'grp_most_members', 'Most Members'),
(3885, 'en', 'grp_most_discussed', 'Most Discussed'),
(3886, 'en', 'grp_invite_msg', 'Invite Users To This Group'),
(3887, 'en', 'grp_invite_msg1', 'Has Invited You To Join'),
(3888, 'en', 'grp_invite_msg2', 'Enter Emails or Usernames (seperate by commas)'),
(3889, 'en', 'grp_url_title1', 'Group url'),
(3890, 'en', 'grp_invite_msg3', 'Send Invitation'),
(3891, 'en', 'grp_join_confirm_msg', 'Are You Sure You Want To Join This Group'),
(3892, 'en', 'grp_join_msg_succ', 'You have successfully joined group'),
(3893, 'en', 'grp_click_here_to_go', 'Click Here To Go To'),
(3894, 'en', 'grp_leave_confirm', 'Are You Sure You Want To Leave This Group'),
(3895, 'en', 'grp_leave_succ_msg', 'You have left the group'),
(3896, 'en', 'grp_manage_members_title', 'Manage Members '),
(3897, 'en', 'grp_for_approval', 'For Approval'),
(3898, 'en', 'grp_rm_videos', 'Remove Videos'),
(3899, 'en', 'grp_rm_mems', 'Remove Members'),
(3900, 'en', 'grp_groups_title', 'Manage Groups'),
(3901, 'en', 'grp_remove_group', 'Remove Group'),
(3902, 'en', 'grp_bo_grp_found', 'No Group Found'),
(3903, 'en', 'grp_joined_groups', 'Joined Groups'),
(3904, 'en', 'grp_owned_groups', 'Owned Groups'),
(3905, 'en', 'grp_edit_this_grp', 'Edit This Group'),
(3906, 'en', 'grp_topics_title', 'Topics'),
(3907, 'en', 'grp_topic_title', 'Topic'),
(3908, 'en', 'grp_posts_title', 'Posts'),
(3909, 'en', 'grp_discus_title', 'Discussions'),
(3910, 'en', 'grp_author_title', 'Author'),
(3911, 'en', 'grp_replies_title', 'Replies'),
(3912, 'en', 'grp_last_post_title', 'Last Post '),
(3913, 'en', 'grp_viewl_all_videos', 'View All Videos of This Group'),
(3914, 'en', 'grp_add_new_topic', 'Add New Topic'),
(3915, 'en', 'grp_attach_video', 'Attach Video '),
(3916, 'en', 'grp_add_topic', 'Add Topic'),
(3917, 'en', 'grp_please_login', 'Please login to post topics'),
(3918, 'en', 'grp_please_join', 'Please Join This Group To Post Topics'),
(3919, 'en', 'grp_inactive_account', 'Your Account Is Inactive And Requires Activation From The Group Owner'),
(3920, 'en', 'grp_about_this_grp', 'About This Group '),
(3921, 'en', 'grp_no_vdo_err', 'This Group Has No Vidoes'),
(3922, 'en', 'grp_posted_by', 'Posted by'),
(3923, 'en', 'grp_add_new_comment', 'Add New Comment'),
(3924, 'en', 'grp_add_comment', 'Add Comment'),
(3925, 'en', 'grp_pls_login_comment', 'Please Login To Post Comments'),
(3926, 'en', 'grp_pls_join_comment', 'Please Join This Group To Post Comments'),
(3927, 'en', 'usr_activation_title', 'User Activation'),
(3928, 'en', 'usr_actiavation_msg', 'Enter Your Username and Activation Code that has been sent to your email.'),
(3929, 'en', 'usr_actiavation_msg1', 'Request Activation Code'),
(3930, 'en', 'usr_activation_code_tl', 'Activation Code'),
(3931, 'en', 'usr_compose_msg', 'Compose Message'),
(3932, 'en', 'usr_inbox_title', 'Inbox'),
(3933, 'en', 'usr_sent_title', 'Sent'),
(3934, 'en', 'usr_to_title', 'To: (Enter Username)'),
(3935, 'en', 'usr_or_select_frm_list', 'or select from contact list'),
(3936, 'en', 'usr_attach_video', 'Attach Video'),
(3937, 'en', 'user_attached_video', 'Attached Video'),
(3938, 'en', 'usr_send_message', 'Send Message'),
(3939, 'en', 'user_no_message', 'No Message'),
(3940, 'en', 'user_delete_message_msg', 'Delete This Message'),
(3941, 'en', 'user_forgot_message', 'Forgot password'),
(3942, 'en', 'user_forgot_message_2', 'Dont Worry, recover it now'),
(3943, 'en', 'user_pass_reset_msg', 'Password Reset'),
(3944, 'en', 'user_pass_forgot_msg', 'if you have forgot your password, please enter you username and verification code in the box, and password reset instructions will be sent to your mail box.'),
(3945, 'en', 'user_veri_code', 'Verification Code'),
(3946, 'en', 'user_reocover_user', 'Recover Username'),
(3947, 'en', 'user_user_forgot_msg', 'Forgot Username?'),
(3948, 'en', 'user_recover', 'Recover'),
(3949, 'en', 'user_reset', 'Reset'),
(3950, 'en', 'user_inactive_msg', 'Your Account is Inactive. Please Activate it , To Activate your account Please'),
(3951, 'en', 'user_dashboard', 'Dash Board'),
(3952, 'en', 'user_manage_prof_chnnl', 'Manage Profile &amp; Channel'),
(3953, 'en', 'user_manage_friends', 'Manage Friends &amp; Contacts'),
(3954, 'en', 'user_prof_channel', 'Profile/Channel'),
(3955, 'en', 'user_message_box', 'Message Box'),
(3956, 'en', 'user_new_messages', 'New Messages'),
(3957, 'en', 'user_goto_inbox', 'Go to Inbox'),
(3958, 'en', 'user_goto_sentbox', 'Go to Sent Box'),
(3959, 'en', 'user_compose_new', 'Compose New Messages'),
(3960, 'en', 'user_total_subs_users', 'Total Subscribed Users'),
(3961, 'en', 'user_you_have', 'You Have'),
(3962, 'en', 'user_fav_videos', 'Favorite Videos'),
(3963, 'en', 'user_your_vids_watched', 'Your Videos Watched'),
(3964, 'en', 'user_times', 'Times'),
(3965, 'en', 'user_you_have_watched', 'You Have Watched'),
(3966, 'en', 'user_channel_profiles', 'Channel &amp; Profile'),
(3967, 'en', 'user_channel_views', 'Channel Views'),
(3968, 'en', 'user_channel_comm', 'Channel Comments '),
(3969, 'en', 'user_manage_prof', 'Manage Profile / Channel'),
(3970, 'en', 'user_you_created', 'You Have Created'),
(3971, 'en', 'user_you_joined', 'You Have Joined'),
(3972, 'en', 'user_create_group', 'Create New Group'),
(3973, 'en', 'user_manage_my_account', 'Manage My Account '),
(3974, 'en', 'user_manage_my_videos', 'Manage My Videos'),
(3975, 'en', 'user_manage_my_channel', 'Manage My Channel'),
(3976, 'en', 'user_sent_box', 'My sent items'),
(3977, 'en', 'user_manage_channel', 'Manage Channel'),
(3978, 'en', 'user_manage_my_contacts', 'Manage My Contacts'),
(3979, 'en', 'user_manage_contacts', 'Manage Contacts'),
(3980, 'en', 'user_manage_favourites', 'Manage Favourite Videos'),
(3981, 'en', 'user_mem_login', 'Members Login'),
(3982, 'en', 'user_already_have', 'Please Login Here if You Already have an account of'),
(3983, 'en', 'user_forgot_username', 'Forgot Username'),
(3984, 'en', 'user_forgot_password', 'Forgot Password'),
(3985, 'en', 'user_create_your', 'Create Your '),
(3986, 'en', 'user_all_fields_req', 'All Fields Are Required'),
(3987, 'en', 'user_valid_email_addr', 'Valid Email Address'),
(3988, 'en', 'user_allowed_format', 'Letters A-Z or a-z , Numbers 0-9 and Underscores _'),
(3989, 'en', 'user_confirm_pass', 'Confirm Password'),
(3990, 'en', 'user_reg_msg_0', 'Register as '),
(3991, 'en', 'user_reg_msg_1', 'member, its free and easy just fill out the form below'),
(3992, 'en', 'user_date_of_birth', 'Date Of Birth'),
(3993, 'en', 'user_enter_text_as_img', 'Enter Text As Seen In The Image'),
(3994, 'en', 'user_refresh_img', 'Refresh Image'),
(3995, 'en', 'user_i_agree_to_the', 'I Agree to  <a href="%s" target="_blank">Terms of Service</a> and <a href="%s" target="_blank" >Privacy Policy'),
(3996, 'en', 'user_thanks_for_reg', 'Thank You For Registering on '),
(3997, 'en', 'user_email_has_sent', 'An email has been sent to your inbox containing Your Account'),
(3998, 'en', 'user_and_activation', '&amp; Activation'),
(3999, 'en', 'user_details_you_now', 'Details. You may now do the following things on our network'),
(4000, 'en', 'user_upload_share_vds', 'Upload, Share Videos'),
(4001, 'en', 'user_make_friends', 'Make Friends'),
(4002, 'en', 'user_send_messages', 'Send Messages'),
(4003, 'en', 'user_grow_your_network', 'Grow Your Networks by Inviting more Friends'),
(4004, 'en', 'user_rate_comment', 'Rate and Comment Videos'),
(4005, 'en', 'user_make_customize', 'Make and Customize Your Channel'),
(4006, 'en', 'user_to_upload_vid', 'To Upload Video, You Need to Activate your account first, activation details has been sent to your email account, it may take sometimes to reach your inbox'),
(4007, 'en', 'user_click_to_login', 'Click here To Login To Your Account'),
(4008, 'en', 'user_view_my_channel', 'View My Channel'),
(4009, 'en', 'user_change_pass', 'Change Password'),
(4010, 'en', 'user_email_settings', 'Email Settings'),
(4011, 'en', 'user_profile_settings', 'Profile Settings'),
(4012, 'en', 'user_usr_prof_chnl_edit', 'User Profile &amp; Channel Edit'),
(4013, 'en', 'user_personal_info', 'Personal Information'),
(4014, 'en', 'user_fname', 'First Name'),
(4015, 'en', 'user_lname', 'Last Name'),
(4016, 'en', 'user_gender', 'Gender'),
(4017, 'en', 'user_relat_status', 'Relationship Status'),
(4018, 'en', 'user_display_age', 'Display Age'),
(4019, 'en', 'user_about_me', 'About Me'),
(4020, 'en', 'user_website_url', 'Website Url'),
(4021, 'en', 'user_eg_website', 'e.g www.cafepixie.com'),
(4022, 'en', 'user_prof_info', 'Professional Information'),
(4023, 'en', 'user_education', 'Education'),
(4024, 'en', 'user_school_colleges', 'Schools / Colleges'),
(4025, 'en', 'user_occupations', 'Occupation(s)'),
(4026, 'en', 'user_companies', 'Companies'),
(4027, 'en', 'user_sperate_by_commas', 'seperate with commas'),
(4028, 'en', 'user_interests_hobbies', 'Interests and Hobbies'),
(4029, 'en', 'user_fav_movs_shows', 'Favorite Movies &amp; Shows'),
(4030, 'en', 'user_fav_music', 'Favorite Music'),
(4031, 'en', 'user_fav_books', 'Favorite Books'),
(4032, 'en', 'user_user_avatar', 'User Avatar'),
(4033, 'en', 'user_upload_avatar', 'Upload Avatar'),
(4034, 'en', 'user_channel_info', 'Channel Info'),
(4035, 'en', 'user_channel_title', 'Channel Title'),
(4036, 'en', 'user_channel_description', 'Channel Description'),
(4037, 'en', 'user_channel_permission', 'Channel Permissions'),
(4038, 'en', 'user_allow_comments_msg', 'users can comment'),
(4039, 'en', 'user_dallow_comments_msg', 'users cannot comment'),
(4040, 'en', 'user_allow_rating', 'Allow Rating'),
(4041, 'en', 'user_dallow_rating', 'Do Not Allow Rating'),
(4042, 'en', 'user_allow_rating_msg1', 'users can rate'),
(4043, 'en', 'user_dallow_rating_msg1', 'users cannot rate'),
(4044, 'en', 'user_channel_feature_vid', 'Channel Featured Video'),
(4045, 'en', 'user_select_vid_for_fr', 'Select Video To set as Featured'),
(4046, 'en', 'user_chane_channel_bg', 'Change Channel Background'),
(4047, 'en', 'user_remove_bg', 'Remove Background'),
(4048, 'en', 'user_currently_you_d_have_pic', 'Currently You Don&#8217;t Have a Background Picture'),
(4049, 'en', 'user_change_email', 'Change Email'),
(4050, 'en', 'user_email_address', 'Email Address'),
(4051, 'en', 'user_new_email', 'New Email'),
(4052, 'en', 'user_notify_me', 'Notify Me When User Sends Me A Message'),
(4053, 'en', 'user_old_pass', 'Old Password'),
(4054, 'en', 'user_new_pass', 'New Password'),
(4055, 'en', 'user_c_new_pass', 'Confirm New Password'),
(4056, 'en', 'user_doesnt_exist', 'User Doesn&#8217;t Exist'),
(4057, 'en', 'user_do_not_have_contact', 'User Does Not Have Any Contacts'),
(4058, 'en', 'user_no_fav_video_exist', 'User does not have any Favorite Videos selected'),
(4059, 'en', 'user_have_no_vide', 'User doesnâ€™t have any videos'),
(4060, 'en', 'user_s_channel', '%s&#8217;s Channel '),
(4061, 'en', 'user_last_login', 'Last Login'),
(4062, 'en', 'user_send_message', 'Send Message'),
(4063, 'en', 'user_add_contact', 'Add Contact'),
(4064, 'en', 'user_dob', 'DoB'),
(4065, 'en', 'user_movies_shows', 'Movies &amp; Shows'),
(4066, 'en', 'user_add_comment', 'Add Comment '),
(4067, 'en', 'user_view_all_comments', 'View All Comments'),
(4068, 'en', 'user_no_fr_video', 'User Has Not Selected Any Video To Set As Featured'),
(4069, 'en', 'user_view_all_video_of', 'View All Videos of '),
(4070, 'en', 'menu_home', 'Home'),
(4071, 'en', 'menu_videos', 'Videos'),
(4072, 'en', 'menu_upload', 'Upload'),
(4073, 'en', 'menu_signup', 'SignUp'),
(4074, 'en', 'menu_account', 'Account'),
(4075, 'en', 'menu_groups', 'Groups'),
(4076, 'en', 'menu_channels', 'Channels'),
(4077, 'en', 'menu_community', 'Community'),
(4078, 'en', 'menu_inbox', 'Inbox'),
(4079, 'en', 'vdo_cat_err2', 'You cannot select more than %d categories'),
(4080, 'en', 'user_subscribe_message', 'Hello %subscriber%\nYou Have Subscribed To %user% and therefore this message is sent to you automatically, because %user% Has Uploaded a New Video\n\n%website_title%'),
(4081, 'en', 'user_subscribe_subject', '%user% has uploaded a new video'),
(4082, 'en', 'you_already_logged', 'You are already logged in'),
(4083, 'en', 'you_not_logged_in', 'You are not logged in'),
(4084, 'en', 'invalid_user', 'Invalid User'),
(4085, 'en', 'vdo_cat_err3', 'Please select at least 1 category'),
(4086, 'en', 'embed_code_invalid_err', 'Invalid video embed code'),
(4087, 'en', 'invalid_duration', 'Invalid duration'),
(4088, 'en', 'vid_thumb_changed', 'Video default thumb has been changed'),
(4089, 'en', 'vid_thumb_change_err', 'Video thumbnail was not found'),
(4090, 'en', 'upload_vid_thumbs_msg', 'All video thumbs have been uploaded'),
(4091, 'en', 'video_thumb_delete_msg', 'Video thumb has been deleted'),
(4092, 'en', 'video_thumb_delete_err', 'Could not delete video thumb'),
(4093, 'en', 'no_comment_del_perm', 'You dont have permission to delete this comment'),
(4094, 'en', 'my_text_context', 'My test context'),
(4095, 'en', 'user_contains_disallow_err', 'Username contains disallowed characters'),
(4096, 'en', 'add_cat_erro', 'Category already exists'),
(4097, 'en', 'add_cat_no_name_err', 'Please enter a name for the category'),
(4098, 'en', 'cat_default_err', 'Default cannot be deleted, please choose another category as &#8220;default&#8221; and then delete this one'),
(4099, 'en', 'pic_upload_vali_err', 'Please upload valid JPG, GIF or PNG image'),
(4100, 'en', 'cat_dir_make_err', 'Unable to create the category thumb directory'),
(4101, 'en', 'cat_set_default_ok', 'Category has been set as default'),
(4102, 'en', 'vid_thumb_removed_msg', 'Video thumbs have been removed'),
(4103, 'en', 'vid_files_removed_msg', 'Video files have been removed'),
(4104, 'en', 'vid_log_delete_msg', 'Video log has been deleted'),
(4105, 'en', 'vdo_multi_del_erro', 'Videos has have been deleted'),
(4106, 'en', 'add_fav_message', 'This %s has been added to your favorites'),
(4107, 'en', 'obj_not_exists', '%s does not exist'),
(4108, 'en', 'already_fav_message', 'This %s is already added to your favorites'),
(4109, 'en', 'obj_report_msg', 'this %s has been reported'),
(4110, 'en', 'obj_report_err', 'You have already reported this %s'),
(4111, 'en', 'user_no_exist_wid_username', '&#8216;%s&#8217; does not exist'),
(4112, 'en', 'share_video_no_user_err', 'Please enter usernames or emails to send this %s'),
(4113, 'en', 'uploaded', 'Uploaded'),
(4114, 'en', 'today', 'Today'),
(4115, 'en', 'yesterday', 'Yesterday'),
(4116, 'en', 'thisweek', 'This Week'),
(4117, 'en', 'lastweek', 'Last Week'),
(4118, 'en', 'thismonth', 'This Month'),
(4119, 'en', 'lastmonth', 'Last Month'),
(4120, 'en', 'thisyear', 'This Year'),
(4121, 'en', 'lastyear', 'Last Year'),
(4122, 'en', 'favorites', 'Favorites'),
(4123, 'en', 'alltime', 'All Time'),
(4124, 'en', 'insufficient_privileges_loggin', 'You cannot access this page Click Here to Login or Register'),
(4125, 'en', 'profile_title', 'Profile Title'),
(4126, 'en', 'show_dob', 'Show Date of Birth'),
(4127, 'en', 'profile_tags', 'Profile Tags'),
(4128, 'en', 'profile_desc', 'Profile Description'),
(4129, 'en', 'online_status', 'User Status'),
(4130, 'en', 'show_profile', 'Show Profile'),
(4131, 'en', 'allow_ratings', 'Allow Profile Ratings'),
(4132, 'en', 'postal_code', 'Postal Code'),
(4133, 'en', 'temp_file_load_err', 'Unable to load tempalte file &#8216;%s&#8217; in directory &#8216;%s&#8217;'),
(4134, 'en', 'no_date_provided', 'No date provided'),
(4135, 'en', 'second', 'second'),
(4136, 'en', 'minute', 'minute'),
(4137, 'en', 'bad_date', 'Never'),
(4138, 'en', 'users_videos', '%s&#8217;s Videos'),
(4139, 'en', 'please_login_subscribe', 'Please login to Subsribe %s'),
(4140, 'en', 'users_subscribers', '%s&#8217;s Subscribers'),
(4141, 'en', 'user_no_subscribers', '%s has no subsribers'),
(4142, 'en', 'user_subscriptions', '%s&#8217;s Subscriptions'),
(4143, 'en', 'user_no_subscriptions', '%s has no subscriptions'),
(4144, 'en', 'usr_avatar_bg_update', 'User avatar and background have been updated'),
(4145, 'en', 'user_email_confirm_email_err', 'Confirm email mismatched'),
(4146, 'en', 'email_change_msg', 'Email has been changed successfully'),
(4147, 'en', 'no_edit_video', 'You cannot edit this video'),
(4148, 'en', 'confirm_del_video', 'Are you sure you want to delete this video ?'),
(4149, 'en', 'remove_fav_video_confirm', 'Are you sure you want to remove this video from your favorites ?'),
(4150, 'en', 'fav_remove_msg', '%s has been removed from your favorites'),
(4151, 'en', 'unknown_favorite', 'Unknown favorite %s'),
(4152, 'en', 'vdo_multi_del_fav_msg', 'Videos have been removed from your favorites'),
(4153, 'en', 'unknown_sender', 'Unknown Sender'),
(4154, 'en', 'please_enter_message', 'Please enter something for message'),
(4155, 'en', 'unknown_reciever', 'Unknown reciever'),
(4156, 'en', 'no_pm_exist', 'Private message does not exist'),
(4157, 'en', 'pm_sent_success', 'Private message has been sent successfully'),
(4158, 'en', 'msg_delete_inbox', 'Message has been deleted from inbox'),
(4159, 'en', 'msg_delete_outbox', 'Message has been deleted from your outbox'),
(4160, 'en', 'private_messags_deleted', 'Private messages have been deleted'),
(4161, 'en', 'ban_users', 'Ban Users'),
(4162, 'en', 'spe_users_by_comma', 'separate usernames by comma'),
(4163, 'en', 'user_ban_msg', 'Users have been banned successfully'),
(4164, 'en', 'no_user_ban_msg', 'No user is banned from your account!'),
(4165, 'en', 'thnx_sharing_msg', 'Thanks for sharing this %s'),
(4166, 'en', 'no_own_commen_rate', 'You cannot rate your own comment'),
(4167, 'en', 'no_comment_exists', 'Comment does not exist'),
(4168, 'en', 'thanks_rating_comment', 'Thanks for rating comment'),
(4169, 'en', 'please_login_create_playlist', 'Please login to creat playlists'),
(4170, 'en', 'play_list_with_this_name_arlready_exists', 'Playlist with name &#8216;%s&#8217; already exists'),
(4171, 'en', 'please_enter_playlist_name', 'Please enter playlist name'),
(4172, 'en', 'new_playlist_created', 'New playlist has been created'),
(4173, 'en', 'playlist_not_exist', 'Playlist does not exist'),
(4174, 'en', 'playlist_item_not_exist', 'Playlist item does not exist'),
(4175, 'en', 'playlist_item_delete', 'Playlist item has been deleted'),
(4176, 'en', 'play_list_updated', 'Playlist has been updated'),
(4177, 'en', 'you_dont_hv_permission_del_playlist', 'You do not have permission to delete the playlist'),
(4178, 'en', 'playlist_delete_msg', 'Playlist has been deleted'),
(4179, 'en', 'playlist_name', 'Playlist Name'),
(4180, 'en', 'add_new_playlist', 'Add Playlist'),
(4181, 'en', 'this_thing_added_playlist', 'This %s has been added to playlist'),
(4182, 'en', 'this_already_exist_in_pl', 'This %s already exists in your playlist'),
(4183, 'en', 'edit_playlist', 'Edit Playlist'),
(4184, 'en', 'remove_playlist_item_confirm', 'Are you sure you want to remove this from your playlist'),
(4185, 'en', 'remove_playlist_confirm', 'Are you sure you want to delete this playlist?'),
(4186, 'en', 'avcode_incorrect', 'Activation code is incorrect'),
(4187, 'en', 'group_join_login_err', 'Please login in order to join this group'),
(4188, 'en', 'manage_playlist', 'Manage playlist'),
(4189, 'en', 'my_notifications', 'My notifications');
INSERT INTO `{tbl_prefix}phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(4190, 'en', 'users_contacts', '%s&#8217;s contacts'),
(4191, 'en', 'type_flags_removed', '%s flags have been removed'),
(4192, 'en', 'terms_of_serivce', 'Terms of services'),
(4193, 'en', 'users', 'Users'),
(4194, 'en', 'login_to_mark_as_spam', 'Please login to mark as spam'),
(4195, 'en', 'no_own_commen_spam', 'You cannot mark your own comment as spam'),
(4196, 'en', 'already_spammed_comment', 'You have already marked this comment as spam'),
(4197, 'en', 'spam_comment_ok', 'Comment has been marked as spam'),
(4198, 'en', 'arslan_hassan', 'Arslan Hassan'),
(4199, 'en', 'you_not_allowed_add_grp_vids', 'You are not member of this group so cannot add videos'),
(4200, 'en', 'sel_vids_updated', 'Selected videos have been updated'),
(4201, 'en', 'unable_find_download_file', 'Unable to find download file'),
(4202, 'en', 'you_cant_edit_group', 'You cannot edit this group'),
(4203, 'en', 'you_cant_invite_mems', 'You cannot invite members'),
(4204, 'en', 'you_cant_moderate_group', 'You cannot moderate this group'),
(4205, 'en', 'page_doesnt_exist', 'Page does not exist'),
(4206, 'en', 'pelase_select_img_file_for_vdo', 'Please select image file for video thumb'),
(4207, 'en', 'new_mem_added', 'New member has been added'),
(4208, 'en', 'this_vdo_not_working', 'This video might not work properly'),
(4209, 'en', 'email_template_not_exist', 'Email template does not exist'),
(4210, 'en', 'email_subj_empty', 'Email subject was empty'),
(4211, 'en', 'email_msg_empty', 'Email message was empty'),
(4212, 'en', 'email_tpl_has_updated', 'Email Template has been updated'),
(4213, 'en', 'page_name_empty', 'Page name was empty'),
(4214, 'en', 'page_title_empty', 'Page title was empty'),
(4215, 'en', 'page_content_empty', 'Page content was empty'),
(4216, 'en', 'new_page_added_successfully', 'New page has been added successfully'),
(4217, 'en', 'page_updated', 'Page has been updated'),
(4218, 'en', 'page_deleted', 'Page has been deleted successfully'),
(4219, 'en', 'page_activated', 'Page has been activated'),
(4220, 'en', 'page_deactivated', 'Page has been deactivated'),
(4221, 'en', 'you_cant_delete_this_page', 'You cannot delete this page'),
(4222, 'en', 'ad_placement_err4', 'Placement does not exist'),
(4223, 'en', 'grp_details_updated', 'Group details have been updated'),
(4224, 'en', 'you_cant_del_topic', 'You cannot delete this topic'),
(4225, 'en', 'you_cant_del_user_topics', 'You cannot delete user topics'),
(4226, 'en', 'topics_deleted', 'Topics have been deleted'),
(4227, 'en', 'you_cant_delete_grp_topics', 'You cannot delete group topics'),
(4228, 'en', 'you_not_allowed_post_topics', 'You are not allowed to post topics'),
(4229, 'en', 'you_cant_add_this_vdo', 'You cannot add this video'),
(4230, 'en', 'video_added', 'Video has been added'),
(4231, 'en', 'you_cant_del_this_vdo', 'You cannot remove this video'),
(4232, 'en', 'video_removed', 'Video has been removed'),
(4233, 'en', 'user_not_grp_mem', 'User is not group member'),
(4234, 'en', 'user_already_group_mem', 'User has already joined this group'),
(4235, 'en', 'invitations_sent', 'Invitations have been sent'),
(4236, 'en', 'you_not_grp_mem', 'You are not a member of this group'),
(4237, 'en', 'you_cant_delete_this_grp', 'You cannot delete this group'),
(4238, 'en', 'grp_deleted', 'Group has been deleted'),
(4239, 'en', 'you_cant_del_grp_mems', 'You cannot delete group members'),
(4240, 'en', 'mems_deleted', 'Members have been deleted'),
(4241, 'en', 'you_cant_del_grp_vdos', 'You cannot delete group videos'),
(4242, 'en', 'thnx_for_voting', 'Thanks for voting'),
(4243, 'en', 'you_hv_already_rated_vdo', 'You have already rated this video'),
(4244, 'en', 'please_login_to_rate', 'Please login to rate'),
(4245, 'en', 'you_not_subscribed', 'You are not subscribed'),
(4246, 'en', 'you_cant_delete_this_user', 'You cannot delete this user&#8221;'),
(4247, 'en', 'you_dont_hv_perms', 'You donâ€™t have sufficient permissions'),
(4248, 'en', 'user_subs_hv_been_removed', 'User subscriptions have been removed'),
(4249, 'en', 'user_subsers_hv_removed', 'User subscribers have been removed'),
(4250, 'en', 'you_already_sent_frend_request', 'You have already sent friend request'),
(4251, 'en', 'friend_added', 'Friend has been added'),
(4252, 'en', 'friend_request_sent', 'Friend request has been sent'),
(4253, 'en', 'friend_confirm_error', 'Either the user has not requested your friend request or you have already confirmed it'),
(4254, 'en', 'friend_confirmed', 'Friend has been confirmed'),
(4255, 'en', 'friend_request_not_found', 'No friend request found'),
(4256, 'en', 'you_cant_confirm_this_request', 'You cannot confirm this request'),
(4257, 'en', 'friend_request_already_confirmed', 'Friend request is already confirmed'),
(4258, 'en', 'user_no_in_contact_list', 'User is not in your contact list'),
(4259, 'en', 'user_removed_from_contact_list', 'User has been removed from your contact list'),
(4260, 'en', 'cant_find_level', 'Cannot find level'),
(4261, 'en', 'please_enter_level_name', 'Please enter level name'),
(4262, 'en', 'level_updated', 'Level has been updated'),
(4263, 'en', 'level_del_sucess', 'User level has been deleted, all users of this level has been transfered to %s'),
(4264, 'en', 'level_not_deleteable', 'This level is not deletable'),
(4265, 'en', 'pass_mismatched', 'Passwords Mismatched'),
(4266, 'en', 'user_blocked', 'User has been blocked'),
(4267, 'en', 'user_already_blocked', 'User is already blocked'),
(4268, 'en', 'you_cant_del_user', 'You cannot block this user'),
(4269, 'en', 'user_vids_hv_deleted', 'User videos have been deleted'),
(4270, 'en', 'user_contacts_hv_removed', 'User contacts have been removed'),
(4271, 'en', 'all_user_inbox_deleted', 'All User inbox messages have been deleted'),
(4272, 'en', 'all_user_sent_messages_deleted', 'All user sent messages have been deleted'),
(4273, 'en', 'pelase_enter_something_for_comment', 'Please enter something as comment'),
(4274, 'en', 'please_enter_your_name', 'Please enter your name'),
(4275, 'en', 'please_enter_your_email', 'Please enter your email'),
(4276, 'en', 'template_activated', 'Template has been activated'),
(4277, 'en', 'error_occured_changing_template', 'An error occured while changing the template'),
(4278, 'en', 'phrase_code_empty', 'Phrase code was empty'),
(4279, 'en', 'phrase_text_empty', 'Phrase text was empty'),
(4280, 'en', 'language_does_not_exist', 'Language does not exist'),
(4281, 'en', 'name_has_been_added', '%s has been added'),
(4282, 'en', 'name_already_exists', '&#8216;%s&#8217; already exist'),
(4283, 'en', 'lang_doesnt_exist', 'language does not exist'),
(4284, 'en', 'no_file_was_selected', 'No file was selected'),
(4285, 'en', 'err_reading_file_content', 'Error reading file content'),
(4286, 'en', 'cant_find_lang_name', 'Cant find language name'),
(4287, 'en', 'cant_find_lang_code', 'Cant find language code'),
(4288, 'en', 'no_phrases_found', 'No phrases were found'),
(4289, 'en', 'language_already_exists', 'Language already exists'),
(4290, 'en', 'lang_added', 'Language has been added successfully'),
(4291, 'en', 'error_while_upload_file', 'Error occured while uploading language file'),
(4292, 'en', 'default_lang_del_error', 'This is the default language, please select another language as &#8220;default&#8221; and then delete this pack'),
(4293, 'en', 'lang_deleted', 'Language pack has been deleted'),
(4294, 'en', 'lang_name_empty', 'Language name was empty'),
(4295, 'en', 'lang_code_empty', 'Language code was empty'),
(4296, 'en', 'lang_regex_empty', 'Language regular expression was empty'),
(4297, 'en', 'lang_code_already_exist', 'Language code already exists'),
(4298, 'en', 'lang_updated', 'Language has been updated'),
(4299, 'en', 'player_activated', 'Player has been activated'),
(4300, 'en', 'error_occured_while_activating_player', 'An error occured while activating player'),
(4301, 'en', 'plugin_has_been_s', 'Plugin has been %s'),
(4302, 'en', 'plugin_uninstalled', 'Plugin has been Uninstalled'),
(4303, 'en', 'perm_code_empty', 'Permission code is empty'),
(4304, 'en', 'perm_name_empty', 'Permission name is empty'),
(4305, 'en', 'perm_already_exist', 'Permission already exists'),
(4306, 'en', 'perm_type_not_valid', 'Permission type is not valid'),
(4307, 'en', 'perm_added', 'New Permission has been added'),
(4308, 'en', 'perm_deleted', 'Permission has been deleted'),
(4309, 'en', 'perm_doesnt_exist', 'Permission does not exist'),
(4310, 'en', 'acitvation_html_message', 'Please enter your username and activation code in order to activate your account, please check your inbox for the Activation code, if you didnâ€™t get one, please request it by filling the next form'),
(4311, 'en', 'acitvation_html_message2', 'Please enter your email address to request your activation code'),
(4312, 'en', 'admin_panel', 'Admin Panel'),
(4313, 'en', 'moderate_videos', 'Moderate Videos'),
(4314, 'en', 'moderate_users', 'Moderate Users'),
(4315, 'en', 'revert_back_to_admin', 'Revert back to admin'),
(4316, 'en', 'more_options', 'More Options'),
(4317, 'en', 'downloading_string', 'Downloading %s ...'),
(4318, 'en', 'download_redirect_msg', '<a href="%s">click here if you don''t redirect automatically</a> - <a href="%s"> Click Here to Go Back to Video Page</a>'),
(4319, 'en', 'account_details', 'Account Details'),
(4320, 'en', 'profile_details', 'Profile Details'),
(4321, 'en', 'update_profile', 'Update Profile'),
(4322, 'en', 'please_select_img_file', 'Please select image file'),
(4323, 'en', 'or', 'or'),
(4324, 'en', 'pelase_enter_image_url', 'Please Enter Image URL'),
(4325, 'en', 'user_bg', 'Channel Background'),
(4326, 'en', 'user_bg_img', 'Channel Background Image'),
(4327, 'en', 'please_enter_bg_color', 'Please Enter Background Color'),
(4328, 'en', 'bg_repeat_type', 'Background Repeat Type (if using image as a background)'),
(4329, 'en', 'fix_bg', 'Fix Background'),
(4330, 'en', 'delete_this_img', 'Delete this image'),
(4331, 'en', 'current_email', 'Current Email'),
(4332, 'en', 'confirm_new_email', 'Confirm New Email'),
(4333, 'en', 'no_subs_found', 'No subscription found'),
(4334, 'en', 'video_info_all_fields_req', 'Video Information - All fields are required'),
(4335, 'en', 'update_group', 'Update Group'),
(4336, 'en', 'default', 'Default'),
(4337, 'en', 'grp_info_all_fields_req', 'Group Information - All Fields Are Required'),
(4338, 'en', 'date_recorded_location', 'Date recorded &amp; Location'),
(4339, 'en', 'update_video', 'Update Video'),
(4340, 'en', 'click_here_to_recover_user', 'Click here to recover username'),
(4341, 'en', 'click_here_reset_pass', 'Click here to reset password'),
(4342, 'en', 'remember_me', 'Remember Me'),
(4343, 'en', 'howdy_user', 'Howdy %s'),
(4344, 'en', 'notifications', 'Notifications'),
(4345, 'en', 'playlists', 'Playlists'),
(4346, 'en', 'friend_requests', 'Friend Requests'),
(4347, 'en', 'after_meny_guest_msg', 'Welcome Guest ! Please <a href="%s">Login</a> or <a href="%s">Register</a>'),
(4348, 'en', 'being_watched', 'Being Watched'),
(4349, 'en', 'change_style_of_listing', 'Change Style of Listing'),
(4350, 'en', 'website_members', '%s Members'),
(4351, 'en', 'guest_homeright_msg', 'Watch, Upload, Share and more'),
(4352, 'en', 'reg_for_free', 'Register for free'),
(4353, 'en', 'rand_vids', 'Random Videos'),
(4354, 'en', 't_10_users', 'Top 10 Users'),
(4355, 'en', 'pending', 'Pending'),
(4356, 'en', 'confirm', 'Confirm'),
(4357, 'en', 'no_contacts', 'No Contacts'),
(4358, 'en', 'you_dont_hv_any_grp', 'You do not have any groups'),
(4359, 'en', 'leave_groups', 'Leave Groups'),
(4360, 'en', 'manage_grp_mems', 'Manage Group Members'),
(4361, 'en', 'pending_mems', 'Pending Members'),
(4362, 'en', 'active_mems', 'Active Members'),
(4363, 'en', 'disapprove', 'Disapprove'),
(4364, 'en', 'manage_grp_vids', 'Manage Group Videos'),
(4365, 'en', 'pending_vids', 'Pending Videos'),
(4366, 'en', 'no_pending_vids', 'No Pending Videos'),
(4367, 'en', 'no_active_videos', 'No Active Videos'),
(4368, 'en', 'active_videos', 'Active Videos'),
(4369, 'en', 'manage_playlists', 'Manage Playlists'),
(4370, 'en', 'total_items', 'Total Items'),
(4371, 'en', 'play_now', 'PLAY NOW'),
(4372, 'en', 'no_video_in_playlist', 'This playlist has no video'),
(4373, 'en', 'view', 'View'),
(4374, 'en', 'you_dont_hv_fav_vids', 'You do not have any favorite videos'),
(4375, 'en', 'private_messages', 'Private Messages'),
(4376, 'en', 'new_private_msg', 'New private message'),
(4377, 'en', 'search_for_s', 'Search For %s'),
(4378, 'en', 'signup_success_usr_ok', '<h2 style="margin: 5px 5px 8px; font-family: Arial,Verdana,sans-serif;">Just One More Step</h2>     	<p style="margin: 0px 5px; line-height: 18px; font-size: 11px;">Your are just one step behind from becoming an official memeber of our website.  Please check your email, we have sent you a confirmation email which contains a confirmation link from our website, Please click it to complete your registration.</p>'),
(4379, 'en', 'signup_success_usr_emailverify', '<h2 style="font-family:Arial,Verdana,sans-serif; margin:5px 5px 8px;">Welcome To our community</h2>\r\n    	<p style="margin:0px 5px; line-height:18px; font-size:11px;">Your email has been confirmed, Please <strong><a href="%s">click here to login</a></strong> and continue as our registered member.</p>'),
(4380, 'en', 'if_you_already_hv_account', 'if you already have an account, please login here '),
(4381, 'en', 'signup_message_under_login', ' <p>Our website is the home for video online:</p>\r\n          \r\n            <ul><li><strong>Watch</strong> millions  of videos</li><li><strong>Share favorites</strong> with friends and family</li>\r\n            <li><strong>Connect with other users</strong> who share your interests</li><li><strong>Upload your videos</strong> to a worldwide audience\r\n\r\n</li></ul>'),
(4382, 'en', 'new_mems_signup_here', 'New Members Signup Here'),
(4383, 'en', 'register_as_our_website_member', 'Register as a member, itâ€™s free and easy just '),
(4384, 'en', 'video_complete_msg', '<h2>Video Upload Has Been Completed</h2>\r\n<span class="header1">Thank you! Your upload is complete.</span><br>\r\n<span class="tips">This video will be available in <a href="%s"><strong>My Videos</strong></a> after it has finished processing.</span>  \r\n<div class="upload_link_button" align="center">\r\n    <ul>\r\n        <li><a href="%s" >Upload Another Video</a></li>\r\n        <li><a href="%s" >Go to My Videos</a></li>\r\n    </ul>\r\n<div class=''clearfix''></div>\r\n</div>\r\n'),
(4385, 'en', 'upload_right_guide', ' <div>\r\n            <div>\r\n              <p>\r\n                <strong>\r\n                <strong>Important:</strong>\r\n                Do not upload any TV shows, music videos, music concerts, or  commercials without permission unless they consist entirely of content  you created yourself.</strong></p>\r\n                <p>The \r\n                <a href="#">Copyright Tips page</a> and the \r\n                <a href="#">Community Guidelines</a> can help you determine whether your video infringes someone else''s copyright.</p>\r\n                <p>By clicking "Upload Video", you are representing that this video does not violate Our website''s \r\n                <a id="terms-of-use-link" href="#">Terms of Use</a> \r\n                and that you own all copyrights in this video or have authorization to upload it.</p>\r\n            </div>\r\n        </div>'),
(4386, 'en', 'report_this_user', 'Report This User'),
(4387, 'en', 'add_to_favs', 'Add to Favorites'),
(4388, 'en', 'report_this', 'Report this!'),
(4389, 'en', 'share_this', 'Share This'),
(4390, 'en', 'add_to_playlist', 'Add to Playlist'),
(4391, 'en', 'view_profile', 'View Profile'),
(4392, 'en', 'subscribe', 'Subscribe'),
(4393, 'en', 'uploaded_by_s', 'Uploaded by %s'),
(4394, 'en', 'more', 'More'),
(4395, 'en', 'link_this_video', 'Link This Video'),
(4396, 'en', 'click_to_download_video', 'Click Here To Download This Video'),
(4397, 'en', 'name', 'Name'),
(4398, 'en', 'email_wont_display', 'Email (Wontâ€™ display)'),
(4399, 'en', 'please_login_to_comment', 'Please login to comment'),
(4400, 'en', 'marked_as_spam_comment_by_user', 'Marked as spam, commented by <em>%s</em>'),
(4401, 'en', 'spam', 'Spam'),
(4402, 'en', 'user_commented_time', '<a href="%s">%s</a> commented %s'),
(4403, 'en', 'no_comments', 'No Comments'),
(4404, 'en', 'view_video', 'View Video'),
(4405, 'en', 'topic_icon', 'Topic Icon'),
(4406, 'en', 'group_options', 'Group option'),
(4407, 'en', 'info', 'Info'),
(4408, 'en', 'basic_info', 'Basic Info'),
(4409, 'en', 'group_owner', 'Group Owner'),
(4410, 'en', 'total_mems', 'Total Members'),
(4411, 'en', 'total_topics', 'Total Topics'),
(4412, 'en', 'grp_url', 'Group URL'),
(4413, 'en', 'more_details', 'More Details<'),
(4414, 'en', 'view_all_mems', 'View All Members'),
(4415, 'en', 'view_all_vids', 'View All Videos'),
(4416, 'en', 'topic_title', 'Topic Title'),
(4417, 'en', 'last_reply', 'Last Reply'),
(4418, 'en', 'topic_by_user', '<a href="%s">%s</a></span> by <a href="%s">%s</a>'),
(4419, 'en', 'no_topics', 'No Topics'),
(4420, 'en', 'last_post_time_by_user', '%s<br />\r\nby <a href="%s">%s'),
(4421, 'en', 'profile_views', 'Profile views'),
(4422, 'en', 'last_logged_in', 'Last logged in'),
(4423, 'en', 'last_active', 'Last active'),
(4424, 'en', 'total_logins', 'Total logins'),
(4425, 'en', 'total_videos_watched', 'Total videos watched'),
(4426, 'en', 'view_group', 'View Group'),
(4427, 'en', 'you_dont_hv_any_pm', 'No messages to display'),
(4428, 'en', 'date_sent', 'Date sent'),
(4429, 'en', 'show_hide', 'show - hide'),
(4430, 'en', 'quicklists', 'Quicklists'),
(4431, 'en', 'are_you_sure_rm_grp', 'Are you sure you want to remove this group ?'),
(4432, 'en', 'are_you_sure_del_grp', 'Are you sure you want to delete this group?'),
(4433, 'en', 'change_avatar', 'Change Avatar'),
(4434, 'en', 'change_bg', 'Change Background'),
(4435, 'en', 'uploaded_videos', 'Uploaded Videos'),
(4436, 'en', 'video_playlists', 'Video Playlists'),
(4437, 'en', 'add_contact_list', 'Add Contact List'),
(4438, 'en', 'topic_post', 'Topic Post'),
(4439, 'en', 'invite', 'Invite'),
(4440, 'en', 'time_ago', '%s %s ago'),
(4441, 'en', 'from_now', '%s %s from now'),
(4442, 'en', 'lang_has_been_activated', 'Language has been activated'),
(4443, 'en', 'lang_has_been_deactivated', 'Language has been deactivated'),
(4444, 'en', 'lang_default_no_actions', 'Language is default so you cannot perform actions on it'),
(4445, 'en', 'private_video_error', 'This video is private, only uploader friends can view this video'),
(4446, 'en', 'email_send_confirm', 'An email has been sent to our web administrator, we will respond you soon'),
(4447, 'en', 'name_was_empty', 'Name was empty'),
(4448, 'en', 'invalid_email', 'Invalid Email'),
(4449, 'en', 'pelase_enter_reason', 'Please enter reason for contact'),
(4450, 'en', 'please_enter_something_for_message', 'Please enter something in message box'),
(4451, 'en', 'comm_disabled_for_vid', 'Comments Disabled For This Video'),
(4452, 'en', 'coments_disabled_profile', 'Comments disabled for this profile'),
(4453, 'en', 'file_size_exceeds', 'File size exceeds ''%s kbs'''),
(4454, 'en', 'vid_rate_disabled', 'Video rating is disabled'),
(4455, 'en', 'chane_lang', '- Change Language -'),
(4456, 'en', 'recent', 'Recent'),
(4457, 'en', 'viewed', 'Viewed'),
(4458, 'en', 'top_rated', 'Top Rated'),
(4459, 'en', 'commented', 'Commented'),
(4460, 'en', 'searching_keyword_in_obj', 'Searchin ''%s'' in %s'),
(4461, 'en', 'no_results_found', 'No results found'),
(4462, 'en', 'please_enter_val_bw_min_max', 'Please enter ''%s'' value between ''%s'' and ''%s'''),
(4463, 'en', 'no_new_subs_video', 'No new videos found in subscriptions'),
(4464, 'en', 'inapp_content', 'Inappropriate Content'),
(4465, 'en', 'copyright_infring', 'Copyright infringement'),
(4466, 'en', 'sexual_content', 'Sexual Content'),
(4467, 'en', 'violence_replusive_content', 'Violence or repulsive content'),
(4468, 'en', 'disturbing', 'Disturbing'),
(4469, 'en', 'other', 'Other'),
(4470, 'en', 'pending_requests', 'Pending requests'),
(4471, 'en', 'friend_add_himself_error', 'You cannot add yourself as a friend');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}playlists`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}playlists` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `userid` int(11) NOT NULL,
  `playlist_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}playlists`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}playlist_items`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}playlist_items` (
  `playlist_item_id` int(225) NOT NULL AUTO_INCREMENT,
  `object_id` int(225) NOT NULL,
  `playlist_id` int(225) NOT NULL,
  `playlist_item_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}playlist_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}plugins`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}plugins` (
  `plugin_id` int(255) NOT NULL AUTO_INCREMENT,
  `plugin_file` text NOT NULL,
  `plugin_folder` text NOT NULL,
  `plugin_version` float NOT NULL,
  `plugin_license_type` varchar(10) NOT NULL DEFAULT 'GPL',
  `plugin_license_key` varchar(5) NOT NULL,
  `plugin_license_code` text NOT NULL,
  `plugin_active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`plugin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `{tbl_prefix}plugins`
--

INSERT INTO `{tbl_prefix}plugins` (`plugin_id`, `plugin_file`, `plugin_folder`, `plugin_version`, `plugin_license_type`, `plugin_license_key`, `plugin_license_code`, `plugin_active`) VALUES
(1, 'embed_video_mod.php', 'embed_video_mod', 0, '', '', '', 'yes'),
(5, 'tester_plugin.php', '', 0, '', '', '', 'yes'),
(12, 'editors_picks.php', 'editors_pick', 0, '', '', '', 'yes'),
(8, 'comment_censor.php', '', 0, '', '', '', 'yes'),
(9, '{tbl_prefix}bbcode.php', '{tbl_prefix}bbcodes', 0, '', '', '', 'yes'),
(10, '{tbl_prefix}modules.php', '{tbl_prefix}modules', 0, '', '', '', 'yes'),
(15, 'signup_captcha.php', 'signup_captcha', 0, '', '', '', 'yes'),
(14, 'date_picker.php', 'date_picker', 0, '', '', '', 'yes'),
(36, 'clipbucket_helper.php', 'clipbucket_helper', 0, '', '', '', 'yes'),
(41, 'hd_player_smart.php', 'hdplayersmart', 0, '', '', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}plugin_config`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}plugin_config` (
  `plugin_config_id` int(223) NOT NULL AUTO_INCREMENT,
  `plugin_id_code` varchar(25) CHARACTER SET latin1 NOT NULL,
  `plugin_config_name` text CHARACTER SET latin1 NOT NULL,
  `plugin_config_value` text CHARACTER SET latin1 NOT NULL,
  `player_type` enum('built-in','plugin') CHARACTER SET latin1 NOT NULL DEFAULT 'built-in',
  `player_admin_file` text CHARACTER SET latin1 NOT NULL,
  `player_include_file` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`plugin_config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}plugin_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}sessions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_user` int(11) NOT NULL,
  `session_string` varchar(60) NOT NULL,
  `session_value` varchar(32) NOT NULL,
  `session_date` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}stats`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}stats` (
  `stat_id` int(255) NOT NULL AUTO_INCREMENT,
  `date_added` date NOT NULL,
  `video_stats` text NOT NULL,
  `user_stats` text NOT NULL,
  `group_stats` text NOT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}stats`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}subscriptions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}subscriptions` (
  `subscription_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `subscribed_to` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}subscriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}template`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}template` (
  `template_id` int(20) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(25) NOT NULL,
  `template_dir` varchar(30) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}template`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}users`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}users` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` int(20) NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `username` text NOT NULL,
  `user_session_key` varchar(32) NOT NULL,
  `user_session_code` int(5) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `usr_status` enum('Ok','ToActivate') NOT NULL DEFAULT 'ToActivate',
  `msg_notify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar` varchar(225) NOT NULL DEFAULT '',
  `avatar_url` text NOT NULL,
  `sex` enum('male','female') NOT NULL DEFAULT 'male',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `country` varchar(20) NOT NULL DEFAULT 'PK',
  `level` int(6) NOT NULL DEFAULT '2',
  `avcode` mediumtext NOT NULL,
  `doj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_visits` bigint(20) NOT NULL DEFAULT '0',
  `session` varchar(32) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `signup_ip` varchar(15) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `featured` enum('No','Yes') NOT NULL DEFAULT 'No',
  `featured_date` datetime NOT NULL,
  `profile_hits` bigint(20) DEFAULT '0',
  `total_watched` bigint(20) NOT NULL DEFAULT '0',
  `total_videos` bigint(20) NOT NULL,
  `total_comments` bigint(20) NOT NULL,
  `comments_count` bigint(20) NOT NULL,
  `ban_status` enum('yes','no') NOT NULL DEFAULT 'no',
  `upload` varchar(20) NOT NULL DEFAULT '1',
  `subscribers` varchar(25) NOT NULL DEFAULT '0',
  `total_subscriptions` bigint(255) NOT NULL,
  `background` mediumtext NOT NULL,
  `background_color` varchar(25) NOT NULL,
  `background_url` text NOT NULL,
  `background_repeat` enum('no-repeat','repeat','repeat-x','repeat-y') NOT NULL DEFAULT 'repeat',
  `background_attachement` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_groups` bigint(20) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rating` bigint(25) NOT NULL,
  `rated_by` text NOT NULL,
  `banned_users` text NOT NULL,
  `welcome_email_sent` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_downloads` bigint(255) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `ind_status_doj` (`doj`),
  KEY `ind_status_id` (`userid`),
  KEY `ind_hits_doj` (`profile_hits`,`doj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `{tbl_prefix}users`
--

INSERT INTO `{tbl_prefix}users` (`userid`, `category`, `featured_video`, `username`, `user_session_key`, `user_session_code`, `password`, `email`, `usr_status`, `msg_notify`, `avatar`, `avatar_url`, `sex`, `dob`, `country`, `level`, `avcode`, `doj`, `last_logged`, `num_visits`, `session`, `ip`, `signup_ip`, `time_zone`, `featured`, `featured_date`, `profile_hits`, `total_watched`, `total_videos`, `total_comments`, `comments_count`, `ban_status`, `upload`, `subscribers`, `total_subscriptions`, `background`, `background_color`, `background_url`, `background_repeat`, `background_attachement`, `total_groups`, `last_active`, `rating`, `rated_by`, `banned_users`, `welcome_email_sent`, `total_downloads`) VALUES
(1, 2, '', 'admin', '777750fea4d3bd585bf47dc1873619fc', 10192, '38d8e594a1ddbd29fdba0de385d4fefa', 'admind@localhost.com', 'Ok', 'yes', '1.jpg', '', 'male', '1989-10-14', 'PK', 1, '', '0000-00-00 00:00:00', '2010-04-28 11:14:17', 27, 'pub6e7fq5oj76vakuov2j03hm1', '127.0.0.1', '', 0, 'No', '2009-12-03 15:14:20', 0, 0, 0, 0, 1, 'no', '0', '0', 2, '1.jpg', '#53baff', '', 'repeat', '', 0, '2010-04-28 13:18:25', 1, '0', '', 'yes', 0);

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `{tbl_prefix}user_categories`
--

INSERT INTO `{tbl_prefix}user_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Basic User', 1, '', '2009-12-03 12:18:15', '', 'yes'),
(2, 'Gurus', 1, '', '2009-12-03 12:18:21', '', 'no'),
(3, 'Comedian', 1, '', '2009-12-03 12:18:25', '', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_levels`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_levels` (
  `user_level_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_level_active` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  `user_level_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `user_level_is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `{tbl_prefix}user_levels`
--

INSERT INTO `{tbl_prefix}user_levels` (`user_level_id`, `user_level_active`, `user_level_name`, `user_level_is_default`) VALUES
(4, 'yes', 'Guest', 'yes'),
(2, 'yes', 'Registered User', 'yes'),
(3, 'yes', 'Inactive User', 'yes'),
(1, 'yes', 'Administrator', 'yes'),
(5, 'yes', 'Global Moderator', 'yes'),
(6, 'yes', 'Anonymous', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_levels_permissions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_levels_permissions` (
  `user_level_permission_id` int(22) NOT NULL AUTO_INCREMENT,
  `user_level_id` int(22) NOT NULL,
  `admin_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_video_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_channel` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_group` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_videos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `video_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `member_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `ad_manager_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `manage_template_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `group_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `web_config_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `view_channels` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_groups` enum('yes','no') NOT NULL DEFAULT 'yes',
  `playlist_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_channel_bg` enum('yes','no') NOT NULL DEFAULT 'yes',
  `private_msg_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `edit_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `download_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `admin_del_access` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_permission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `{tbl_prefix}user_levels_permissions`
--

INSERT INTO `{tbl_prefix}user_levels_permissions` (`user_level_permission_id`, `user_level_id`, `admin_access`, `allow_video_upload`, `view_video`, `view_channel`, `view_group`, `view_videos`, `avatar_upload`, `video_moderation`, `member_moderation`, `ad_manager_access`, `manage_template_access`, `group_moderation`, `web_config_access`, `view_channels`, `view_groups`, `playlist_access`, `allow_channel_bg`, `private_msg_access`, `edit_video`, `download_video`, `admin_del_access`) VALUES
(1, 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(2, 2, 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(3, 3, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(4, 4, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(5, 5, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(6, 6, 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_permissions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_permissions` (
  `permission_id` int(225) NOT NULL AUTO_INCREMENT,
  `permission_type` int(225) NOT NULL,
  `permission_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `permission_code` varchar(225) CHARACTER SET latin1 NOT NULL,
  `permission_desc` mediumtext CHARACTER SET latin1 NOT NULL,
  `permission_default` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `{tbl_prefix}user_permissions`
--

INSERT INTO `{tbl_prefix}user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES
(12, 3, 'Admin Access', 'admin_access', 'User can access admin panel', 'no'),
(13, 1, 'View Video', 'view_video', 'User can view videos', 'yes'),
(11, 2, 'Allow Video Upload', 'allow_video_upload', 'Allow user to upload videos', 'yes'),
(14, 1, 'View Channel', 'view_channel', 'User Can View Channel', 'yes'),
(15, 1, 'View Group', 'view_group', 'User Can View Group', 'yes'),
(16, 1, 'View Videos Page', 'view_videos', 'User Can view videos page', 'yes'),
(17, 2, 'Allow Avatar Upload', 'avatar_upload', 'User can upload video', 'yes'),
(19, 3, 'Video Moderation', 'video_moderation', 'User Can Moderate Videos', 'no'),
(20, 3, 'Member Moderation', 'member_moderation', 'User Can Moderate Members', 'no'),
(21, 3, 'Advertisment Manager', 'ad_manager_access', 'User can change advertisment', 'no'),
(22, 3, 'Manage Templates', 'manage_template_access', 'User can manage website templates', 'no'),
(23, 3, 'Groups Moderation', 'group_moderation', 'User can moderate group', 'no'),
(24, 3, 'Website Configurations', 'web_config_access', 'User can change website settings', 'no'),
(25, 1, 'View channels', 'view_channels', 'User can channels', 'yes'),
(26, 1, 'View Groups', 'view_groups', 'User can view groups', 'yes'),
(28, 4, 'Playlist Access', 'playlist_access', 'User can access playlists', 'yes'),
(29, 2, 'Allow Channel Background', 'allow_channel_bg', 'Allow user to change channel background', 'yes'),
(30, 4, 'Private Messages', 'private_msg_access', 'User can use private messaging system', 'yes'),
(31, 4, 'Edit Video', 'edit_video', 'User can edit video', 'yes'),
(32, 4, 'Download Video', 'download_video', 'User can download videos', 'yes'),
(33, 3, 'Admin Delete Access', 'admin_del_access', 'User can delete comments if has admin access', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_permission_types`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_permission_types` (
  `user_permission_type_id` int(225) NOT NULL AUTO_INCREMENT,
  `user_permission_type_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `user_permission_type_desc` mediumtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`user_permission_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `{tbl_prefix}user_permission_types`
--

INSERT INTO `{tbl_prefix}user_permission_types` (`user_permission_type_id`, `user_permission_type_name`, `user_permission_type_desc`) VALUES
(1, 'Viewing Permission', ''),
(2, 'Uploading Permission', ''),
(3, 'Administrator Permission', ''),
(4, 'General Permission', '');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}user_profile`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_profile` (
  `user_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `profile_title` mediumtext NOT NULL,
  `profile_desc` mediumtext NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(225) NOT NULL DEFAULT 'no_avatar.jpg',
  `show_dob` enum('no','yes') DEFAULT 'no',
  `postal_code` varchar(20) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `profile_tags` mediumtext,
  `web_url` varchar(200) NOT NULL DEFAULT '',
  `hometown` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `online_status` enum('online','offline','custom') NOT NULL DEFAULT 'online',
  `show_profile` enum('all','members','friends') NOT NULL DEFAULT 'all',
  `allow_comments` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `allow_ratings` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `content_filter` enum('Nothing','On','Off') NOT NULL DEFAULT 'Nothing',
  `icon_id` bigint(20) NOT NULL DEFAULT '0',
  `browse_criteria` mediumtext,
  `about_me` mediumtext NOT NULL,
  `education` varchar(3) DEFAULT NULL,
  `schools` mediumtext NOT NULL,
  `occupation` mediumtext NOT NULL,
  `companies` mediumtext NOT NULL,
  `relation_status` varchar(15) DEFAULT NULL,
  `hobbies` mediumtext NOT NULL,
  `fav_movies` mediumtext NOT NULL,
  `fav_music` mediumtext NOT NULL,
  `fav_books` mediumtext NOT NULL,
  `background` mediumtext NOT NULL,
  `profile_video` int(255) NOT NULL,
  PRIMARY KEY (`user_profile_id`),
  KEY `ind_status_id` (`userid`),
  FULLTEXT KEY `profile_tags` (`profile_tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `{tbl_prefix}user_profile`
--

INSERT INTO `{tbl_prefix}user_profile` (`user_profile_id`, `userid`, `profile_title`, `profile_desc`, `featured_video`, `first_name`, `last_name`, `avatar`, `show_dob`, `postal_code`, `time_zone`, `profile_tags`, `web_url`, `hometown`, `city`, `online_status`, `show_profile`, `allow_comments`, `allow_ratings`, `content_filter`, `icon_id`, `browse_criteria`, `about_me`, `education`, `schools`, `occupation`, `companies`, `relation_status`, `hobbies`, `fav_movies`, `fav_music`, `fav_books`, `background`, `profile_video`) VALUES
(1, 1, '', '', '', '', '', 'no_avatar.jpg', 'yes', '', 0, '', '', '', '', 'online', 'all', 'Yes', 'Yes', 'Nothing', 0, NULL, '', 'no ', '', '', '', 'no answer', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}validation_re`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}validation_re` (
  `re_id` int(25) NOT NULL AUTO_INCREMENT,
  `re_name` varchar(60) NOT NULL,
  `re_code` varchar(60) NOT NULL,
  `re_syntax` text NOT NULL,
  PRIMARY KEY (`re_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `{tbl_prefix}validation_re`
--

INSERT INTO `{tbl_prefix}validation_re` (`re_id`, `re_name`, `re_code`, `re_syntax`) VALUES
(1, 'Username', 'username', '^^[a-zA-Z0-9_]+$'),
(2, 'Email', 'email', '^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,10})$'),
(3, 'Field Text', 'field_text', '^^[_a-z0-9-]+$');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video` (
  `videoid` bigint(20) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `username` text NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(225) NOT NULL,
  `flv` mediumtext NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `description` mediumtext NOT NULL,
  `tags` mediumtext NOT NULL,
  `category` varchar(20) NOT NULL DEFAULT '0',
  `broadcast` varchar(10) NOT NULL DEFAULT '',
  `location` mediumtext,
  `datecreated` date DEFAULT NULL,
  `country` mediumtext,
  `allow_embedding` char(3) NOT NULL DEFAULT '',
  `rating` int(15) NOT NULL DEFAULT '0',
  `rated_by` varchar(20) NOT NULL DEFAULT '0',
  `voter_ids` mediumtext NOT NULL,
  `allow_comments` char(3) NOT NULL DEFAULT '',
  `comment_voting` char(3) NOT NULL DEFAULT '',
  `comments_count` int(15) NOT NULL DEFAULT '0',
  `featured` char(3) NOT NULL DEFAULT 'no',
  `featured_date` datetime NOT NULL,
  `featured_description` mediumtext NOT NULL,
  `allow_rating` char(3) NOT NULL DEFAULT '',
  `active` char(3) NOT NULL DEFAULT '0',
  `favourite_count` varchar(15) NOT NULL DEFAULT '0',
  `playlist_count` varchar(15) NOT NULL DEFAULT '0',
  `views` bigint(22) NOT NULL DEFAULT '0',
  `last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `flagged` varchar(3) NOT NULL DEFAULT 'no',
  `duration` varchar(20) NOT NULL DEFAULT '00',
  `status` enum('Successful','Processing','Failed') NOT NULL DEFAULT 'Processing',
  `flv_file_url` text,
  `default_thumb` int(3) NOT NULL DEFAULT '1',
  `embed_code` text NOT NULL,
  `refer_url` text NOT NULL,
  `downloads` bigint(255) NOT NULL,
  `uploader_ip` varchar(20) NOT NULL,
  `mass_embed_status` enum('no','pending','approved') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`videoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}video`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `{tbl_prefix}video_categories`
--

INSERT INTO `{tbl_prefix}video_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 1, '', '2010-04-28 13:17:23', '', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_favourites`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_favourites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `videoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}video_favourites`
--


-- --------------------------------------------------------

--
-- Table structure for table `{tbl_prefix}video_files`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL,
  `file_conversion_log` text CHARACTER SET latin1 NOT NULL,
  `encoder` char(16) CHARACTER SET latin1 NOT NULL,
  `command_used` text CHARACTER SET latin1 NOT NULL,
  `src_path` text CHARACTER SET latin1 NOT NULL,
  `src_name` char(64) CHARACTER SET latin1 NOT NULL,
  `src_ext` char(8) CHARACTER SET latin1 NOT NULL,
  `src_format` char(32) CHARACTER SET latin1 NOT NULL,
  `src_duration` char(10) CHARACTER SET latin1 NOT NULL,
  `src_size` char(10) CHARACTER SET latin1 NOT NULL,
  `src_bitrate` char(6) CHARACTER SET latin1 NOT NULL,
  `src_video_width` char(5) CHARACTER SET latin1 NOT NULL,
  `src_video_height` char(5) CHARACTER SET latin1 NOT NULL,
  `src_video_wh_ratio` char(10) CHARACTER SET latin1 NOT NULL,
  `src_video_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `src_video_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_video_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_video_color` char(16) CHARACTER SET latin1 NOT NULL,
  `src_audio_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `src_audio_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_audio_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `src_audio_channels` char(16) CHARACTER SET latin1 NOT NULL,
  `output_path` text CHARACTER SET latin1 NOT NULL,
  `output_format` char(32) CHARACTER SET latin1 NOT NULL,
  `output_duration` char(10) CHARACTER SET latin1 NOT NULL,
  `output_size` char(10) CHARACTER SET latin1 NOT NULL,
  `output_bitrate` char(6) CHARACTER SET latin1 NOT NULL,
  `output_video_width` char(5) CHARACTER SET latin1 NOT NULL,
  `output_video_height` char(5) CHARACTER SET latin1 NOT NULL,
  `output_video_wh_ratio` char(10) CHARACTER SET latin1 NOT NULL,
  `output_video_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `output_video_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_video_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_video_color` char(16) CHARACTER SET latin1 NOT NULL,
  `output_audio_codec` char(16) CHARACTER SET latin1 NOT NULL,
  `output_audio_bitrate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_audio_rate` char(10) CHARACTER SET latin1 NOT NULL,
  `output_audio_channels` char(16) CHARACTER SET latin1 NOT NULL,
  `hd` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `hq` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `src_bitrate` (`src_bitrate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `{tbl_prefix}video_files`
--
