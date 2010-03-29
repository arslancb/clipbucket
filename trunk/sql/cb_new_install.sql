-- $Id$
-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 04, 2009 at 12:03 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `svn_clean`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE IF NOT EXISTS `action_log` (
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
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `action_log`
--


-- --------------------------------------------------------

--
-- Table structure for table `ads_data`
--

CREATE TABLE IF NOT EXISTS `ads_data` (
  `ad_id` int(50) NOT NULL AUTO_INCREMENT,
  `ad_name` mediumtext NOT NULL,
  `ad_code` mediumtext NOT NULL,
  `ad_placement` varchar(50) NOT NULL DEFAULT '',
  `ad_category` int(11) NOT NULL DEFAULT '0',
  `ad_status` enum('0','1') NOT NULL DEFAULT '0',
  `ad_impressions` bigint(255) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ads_data`
--

INSERT INTO `ads_data` (`ad_id`, `ad_name`, `ad_code`, `ad_placement`, `ad_category`, `ad_status`, `ad_impressions`, `date_added`) VALUES
(1, 'Ad Box of 300 x 250', '&lt;img src=&quot;http://www.lipsum.com/images/banners/black_300x250.gif&quot;&gt;', 'ad_300x250', 0, '1', 1056, '0000-00-00 00:00:00'),
(9, '336x280', '&lt;img src=&quot;http://www.lipsum.com/images/banners/black_336x280.gif&quot;&gt;', '336x280', 0, '1', 33, '0000-00-00 00:00:00'),
(2, 'Adbox 160x600', '<div style="border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:160px; height:600px; vertical-align:middle" align="center">\r\n	Ad Box 160 x 600\r\n</div>', 'ad_160x600', 0, '1', 571, '0000-00-00 00:00:00'),
(3, 'Adbox 468x60', '&lt;div style=''border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:468px; height:60px; line-height:60px;'' align=&quot;center&quot;&gt;\r\n	Ad Box 468 x 60\r\n&lt;/div&gt;', '', 0, '1', 1956, '0000-00-00 00:00:00'),
(4, 'Adbox 728x90', '<div style="border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:728px; height:90px; line-height:90px;" align="center">\r\n	Ad Box 728 x 90\r\n</div>', 'ad_728x90', 0, '1', 694, '0000-00-00 00:00:00'),
(5, 'Adbox 120x600', '&lt;div style=&quot;border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:120px; height:600px; line-height:600px;&quot; align=&quot;center&quot;&gt;\r\n	Ad Box 120 x 600\r\n&lt;/div&gt;', 'ad_468x60', 0, '1', 684, '0000-00-00 00:00:00'),
(6, 'Test', '<div style="border:2px #333333 solid; color:#53baff; font-size:20px; font-family:Geneva, Arial, Helvetica, sans-serif; font-weight:bold; width:300px; height:250px; line-height:250px;" align="center">\r\n	Ad Box 11 x 11\r\n</div>', 'ad_160x600', 0, '1', 570, '0000-00-00 00:00:00'),
(7, 'Rasd', 'asd', 'ad_120x600', 0, '1', 711, '0000-00-00 00:00:00'),
(8, 'Tesdt', 'test', '', 0, '1', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ads_placements`
--

CREATE TABLE IF NOT EXISTS `ads_placements` (
  `placement_id` int(20) NOT NULL AUTO_INCREMENT,
  `placement` varchar(26) NOT NULL,
  `placement_name` varchar(50) NOT NULL,
  `disable` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`placement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ads_placements`
--

INSERT INTO `ads_placements` (`placement_id`, `placement`, `placement_name`, `disable`) VALUES
(1, 'ad_160x600', 'Wide Skyscrapper 160 x 600', 'yes'),
(2, 'ad_468x60', 'Banner 468 x 60', 'yes'),
(3, 'ad_300x250', 'Medium Rectangle 300 x 250', 'yes'),
(4, 'ad_728x90', 'Leader Board 728 x 90', 'yes'),
(7, 'ad_120x600', 'Skyscrapper 120 x 600', 'yes'),
(10, 'ad_300x300', 'AD 300x300', 'no'),
(11, '336x280', '336 x280 ad', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `cb_global_announcement`
--

CREATE TABLE IF NOT EXISTS `cb_global_announcement` (
  `announcement` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cb_global_announcement`
--


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(60) NOT NULL AUTO_INCREMENT,
  `type` varchar(3) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(60) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `parent_id` int(60) NOT NULL,
  `type_id` int(225) NOT NULL,
  `vote` bigint(225) NOT NULL,
  `voters` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `configid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`configid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`configid`, `name`, `value`) VALUES
(45, 'user_comment_opt1', ''),
(18, 'resize', 'no'),
(17, 'r_width', '300'),
(16, 'r_height', '240'),
(15, 'srate', '22050'),
(14, 'vbrate', '500000'),
(10, 'keywords', 'clip bucket video sharing website script'),
(11, 'ffmpegpath', 'E:/wamp/bin/ffmpeg/ffmpeg.exe'),
(12, 'flvpath', ''),
(13, 'whatis', '<p>ClipBucket is the home for video online:</p>\r\n          \r\n            <ul><li><strong>Watch</strong> millions  of videos</li><li><strong>Share favorites</strong> with friends and family</li><li><strong>Connect with other users</strong> who share your interests</li><li><strong>Upload your videos</strong> to a worldwide audience</li></ul>\r\n<h3>Sign up now to join the ClipBucket community!</h3>'),
(9, 'description', 'Clip Bucket is an ultimate Video Sharing script'),
(8, 'closed_msg', 'We Are Updating Our Website, Please Visit us after few hours.'),
(7, 'closed', '0'),
(6, 'player_file', 'cbplayer.plug.php'),
(5, 'template_dir', 'cbv2'),
(4, 'basedir', 'e:\\wamp\\www\\clipbucket\\2.x\\2\\upload'),
(2, 'site_slogan', 'A way to broadcast yourself'),
(1, 'site_title', 'ClipBucket v2'),
(50, 'captcha_type', '1'),
(49, 'user_rate_opt1', ''),
(48, 'user_comment_opt4', ''),
(47, 'user_comment_opt3', ''),
(46, 'user_comment_opt2', ''),
(43, 'thumb_height', '90'),
(42, 'thumb_width', '120'),
(41, 'sbrate', '64000'),
(40, 'max_upload_size', '1000'),
(39, 'recently_viewed_limit', '12'),
(38, 'search_list_per_page', '20'),
(37, 'admin_pages', '50'),
(36, 'seo', 'no'),
(35, 'groups_list_per_page', '15'),
(34, 'video_embed', '1'),
(33, 'video_download', '1'),
(32, 'comment_rating', '1'),
(31, 'video_rating', ''),
(30, 'video_comments', '1'),
(29, 'channels_list_per_tab', '13'),
(28, 'videos_list_per_tab', '12'),
(27, 'channels_list_per_page', '25'),
(26, 'videos_list_per_page', '15'),
(51, 'allow_upload', 'yes'),
(3, 'baseurl', 'http://localhost/clipbucket/2.x/2/upload'),
(25, 'php_path', 'E:/wamp/bin/php/php5.3.0/php-win.exe'),
(24, 'allow_registration', '1'),
(19, 'mencoderpath', ''),
(23, 'email_verification', '1'),
(44, 'ffmpeg_type', ''),
(22, 'mplayerpath', ''),
(21, 'activation', ''),
(20, 'keep_original', '1'),
(52, 'allowed_types', 'wmv avi divx 3gp mov mpeg mpg xvid flv asf rm dat mp4'),
(53, 'version', '1.7.1'),
(54, 'version_type', 'SVN'),
(55, 'allow_template_change', ''),
(56, 'allow_language_change', '1'),
(57, 'default_site_lang', 'en'),
(58, 'video_require_login', 'yes'),
(59, 'audio_codec', ''),
(60, 'con_modules_type', ''),
(61, 'remoteUpload', ''),
(62, 'embedUpload', ''),
(63, 'player_div_id', ''),
(64, 'code_dev', ' (Powered by ClipBucket)'),
(65, 'sys_os', ''),
(66, 'debug_level', '2'),
(67, 'enable_troubleshooter', '1'),
(68, 'vrate', '25'),
(69, 'num_thumbs', '3'),
(70, 'big_thumb_width', '320'),
(71, 'big_thumb_height', '240'),
(72, 'user_max_chr', '15'),
(73, 'disallowed_usernames', 'shit, asshole, fucker'),
(74, 'min_age_reg', '0'),
(75, 'max_comment_chr', '350'),
(76, 'user_comment_own', '1'),
(77, 'anonym_comments', 'yes'),
(78, 'player_dir', 'cbplayer'),
(79, 'player_width', '548'),
(80, 'player_height', '275'),
(81, 'default_country_iso2', 'PK'),
(82, 'channel_player_width', '600'),
(83, 'channel_player_height', '281');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contact_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(225) NOT NULL,
  `contact_userid` int(225) NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `conversion_queue`
--

CREATE TABLE IF NOT EXISTS `conversion_queue` (
  `cqueue_id` int(11) NOT NULL AUTO_INCREMENT,
  `cqueue_name` varchar(32) NOT NULL,
  `cqueue_ext` varchar(5) NOT NULL,
  `cqueue_tmp_ext` varchar(3) NOT NULL,
  `cqueue_conversion` enum('yes','no','p') NOT NULL DEFAULT 'no',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cqueue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `conversion_queue`
--


-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL,
  `iso2` char(2) CHARACTER SET latin1 DEFAULT NULL,
  `iso3` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `name_en` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `iso2`, `iso3`, `name_en`) VALUES
(1, 'AF', 'AFG', 'Afghanistan'),
(4, 'DZ', 'DZA', 'Algeria'),
(3, 'AL', 'ALB', 'Albania'),
(5, 'AS', 'ASM', 'American Samoa'),
(6, 'AD', 'AND', 'Andorra'),
(7, 'AO', 'AGO', 'Angola'),
(8, 'AI', 'AIA', 'Anguilla'),
(9, 'AQ', 'ATA', 'Antarctica'),
(10, 'AG', 'ATG', 'Antigua and Barbuda'),
(11, 'AR', 'ARG', 'Argentina'),
(12, 'AM', 'ARM', 'Armenia'),
(13, 'AW', 'ABW', 'Aruba'),
(14, 'AU', 'AUS', 'Australia'),
(15, 'AT', 'AUT', 'Austria'),
(16, 'AZ', 'AZE', 'Azerbaijan'),
(17, 'BS', 'BHS', 'Bahamas'),
(18, 'BH', 'BHR', 'Bahrain'),
(19, 'BD', 'BGD', 'Bangladesh'),
(20, 'BB', 'BRB', 'Barbados'),
(21, 'BY', 'BLR', 'Belarus'),
(22, 'BE', 'BEL', 'Belgium'),
(23, 'BZ', 'BLZ', 'Belize'),
(24, 'BJ', 'BEN', 'Benin'),
(25, 'BM', 'BMU', 'Bermuda'),
(26, 'BT', 'BTN', 'Bhutan'),
(27, 'BO', 'BOL', 'Bolivia'),
(28, 'BA', 'BIH', 'Bosnia and Herzegovina'),
(29, 'BW', 'BWA', 'Botswana'),
(30, 'BV', 'BVT', 'Bouvet Island'),
(31, 'BR', 'BRA', 'Brazil'),
(32, 'IO', 'IOT', 'British Indian Ocean Territory'),
(33, 'BN', 'BRN', 'Brunei Darussalam'),
(34, 'BG', 'BGR', 'Bulgaria'),
(35, 'BF', 'BFA', 'Burkina Faso'),
(36, 'BI', 'BDI', 'Burundi'),
(37, 'KH', 'KHM', 'Cambodia'),
(38, 'CM', 'CMR', 'Cameroon'),
(39, 'CA', 'CAN', 'Canada'),
(40, 'CV', 'CPV', 'Cape Verde'),
(41, 'KY', 'CYM', 'Cayman Islands'),
(42, 'CF', 'CAF', 'Central African Republic'),
(43, 'TD', 'TCD', 'Chad'),
(44, 'CL', 'CHL', 'Chile'),
(45, 'CN', 'CHN', 'China'),
(46, 'CX', 'CXR', 'Christmas Island'),
(47, 'CC', 'CCK', 'Cocos Islands'),
(48, 'CO', 'COL', 'Colombia'),
(49, 'KM', 'COM', 'Comoros'),
(50, 'CG', 'COG', 'Congo, Republic Of'),
(52, 'CK', 'COK', 'Cook Islands'),
(53, 'CR', 'CRI', 'Costa Rica'),
(55, 'HR', 'HRV', 'Croatia'),
(56, 'CU', 'CUB', 'Cuba'),
(57, 'CY', 'CYP', 'Cyprus'),
(58, 'CZ', 'CZE', 'Czech Republic'),
(59, 'DK', 'DNK', 'Denmark'),
(60, 'DJ', 'DJI', 'Djibouti'),
(61, 'DM', 'DMA', 'Dominica'),
(62, 'DO', 'DOM', 'Dominican Republic'),
(63, 'EC', 'ECU', 'Ecuador'),
(64, 'EG', 'EGY', 'Egypt'),
(65, 'SV', 'SLV', 'El Salvador'),
(66, 'GQ', 'GNQ', 'Equatorial Guinea'),
(67, 'ER', 'ERI', 'Eritrea'),
(68, 'EE', 'EST', 'Estonia'),
(69, 'ET', 'ETH', 'Ethiopia'),
(70, 'FO', 'FRO', 'Faeroe Islands'),
(71, 'FK', 'FLK', 'Falkland Islands'),
(72, 'FJ', 'FJI', 'Fiji'),
(73, 'FI', 'FIN', 'Finland'),
(74, 'FR', 'FRA', 'France'),
(75, 'GF', 'GUF', 'French Guiana'),
(76, 'PF', 'PYF', 'French Polynesia'),
(78, 'GA', 'GAB', 'Gabon'),
(79, 'GM', 'GMB', 'Gambia, The'),
(80, 'GE', 'GEO', 'Georgia'),
(81, 'DE', 'DEU', 'Germany'),
(82, 'GH', 'GHA', 'Ghana'),
(83, 'GI', 'GIB', 'Gibraltar'),
(84, 'GB', 'GBR', 'Great Britain'),
(85, 'GR', 'GRC', 'Greece'),
(86, 'GL', 'GRL', 'Greenland'),
(87, 'GD', 'GRD', 'Grenada'),
(88, 'GP', 'GLP', 'Guadeloupe'),
(89, 'GU', 'GUM', 'Guam'),
(90, 'GT', 'GTM', 'Guatemala'),
(91, 'GN', 'GIN', 'Guinea'),
(92, 'GW', 'GNB', 'Guinea-bissau'),
(93, 'GY', 'GUY', 'Guyana'),
(94, 'HT', 'HTI', 'Haiti'),
(95, 'HM', 'HMD', 'Heard Island'),
(96, 'HN', 'HND', 'Honduras'),
(97, 'HK', 'HKG', 'Hong Kong'),
(98, 'HU', 'HUN', 'Hungary'),
(99, 'IS', 'ISL', 'Iceland'),
(100, 'IN', 'IND', 'India'),
(101, 'ID', 'IDN', 'Indonesia'),
(102, 'IR', 'IRN', 'Iran'),
(103, 'IQ', 'IRQ', 'Iraq'),
(104, 'IE', 'IRL', 'Ireland'),
(105, 'IL', 'ISR', 'Israel'),
(106, 'IT', 'ITA', 'Italy'),
(107, 'JM', 'JAM', 'Jamaica'),
(108, 'JP', 'JPN', 'Japan'),
(109, 'JO', 'JOR', 'Jordan'),
(110, 'KZ', 'KAZ', 'Kazakhstan'),
(111, 'KE', 'KEN', 'Kenya'),
(112, 'KI', 'KIR', 'Kiribati'),
(113, 'KP', 'PRK', 'Korea'),
(114, 'KR', 'KOR', 'Korea'),
(115, 'KW', 'KWT', 'Kuwait'),
(116, 'KG', 'KGZ', 'Kyrgyzstan'),
(117, 'LA', 'LAO', 'Lao'),
(118, 'LV', 'LVA', 'Latvia'),
(119, 'LB', 'LBN', 'Lebanon'),
(120, 'LS', 'LSO', 'Lesotho'),
(121, 'LR', 'LBR', 'Liberia'),
(122, 'LY', 'LBY', 'Libya'),
(124, 'LT', 'LTU', 'Lithuania'),
(125, 'LU', 'LUX', 'Luxembourg'),
(128, 'MG', 'MDG', 'Madagascar'),
(129, 'MW', 'MWI', 'Malawi'),
(130, 'MY', 'MYS', 'Malaysia'),
(131, 'MV', 'MDV', 'Maldives'),
(132, 'ML', 'MLI', 'Mali'),
(133, 'MT', 'MLT', 'Malta'),
(134, 'MH', 'MHL', 'Marshall Islands'),
(135, 'MQ', 'MTQ', 'Martinique'),
(136, 'MR', 'MRT', 'Mauritania'),
(137, 'MU', 'MUS', 'Mauritius'),
(138, 'YT', 'MYT', 'Mayotte'),
(139, 'MX', 'MEX', 'Mexico'),
(141, 'MD', 'MDA', 'Moldova'),
(142, 'MC', 'MCO', 'Monaco'),
(143, 'MN', 'MNG', 'Mongolia'),
(144, 'MS', 'MSR', 'Montserrat'),
(145, 'MA', 'MAR', 'Morocco'),
(147, 'MM', 'MMR', 'Myanmar '),
(148, 'NA', 'NAM', 'Namibia'),
(149, 'NR', 'NRU', 'Nauru'),
(150, 'NP', 'NPL', 'Nepal'),
(151, 'NL', 'NLD', 'Netherlands'),
(152, 'AN', 'ANT', 'Netherlands Antilles'),
(153, 'NC', 'NCL', 'New Caledonia'),
(154, 'NZ', 'NZL', 'New Zealand'),
(155, 'NI', 'NIC', 'Nicaragua'),
(156, 'NE', 'NER', 'Niger'),
(157, 'NG', 'NGA', 'Nigeria'),
(158, 'NU', 'NIU', 'Niue'),
(159, 'NF', 'NFK', 'Norfolk Island'),
(160, 'MP', 'MNP', 'Northern Mariana Islands'),
(161, 'NO', 'NOR', 'Norway'),
(162, 'OM', 'OMN', 'Oman'),
(163, 'PK', 'PAK', 'Pakistan'),
(164, 'PW', 'PLW', 'Palau'),
(165, 'PS', 'PSE', 'Palestinian Territories'),
(166, 'PA', 'PAN', 'Panama'),
(167, 'PG', 'PNG', 'Papua New Guinea'),
(168, 'PY', 'PRY', 'Paraguay'),
(169, 'PE', 'PER', 'Peru'),
(170, 'PH', 'PHL', 'Philippines'),
(171, 'PN', 'PCN', 'Pitcairn'),
(172, 'PL', 'POL', 'Poland'),
(173, 'PT', 'PRT', 'Portugal'),
(174, 'PR', 'PRI', 'Puerto Rico'),
(175, 'QA', 'QAT', 'Qatar'),
(177, 'RO', 'ROU', 'Romania'),
(178, 'RU', 'RUS', 'Russian Federation'),
(179, 'RW', 'RWA', 'Rwanda'),
(180, 'SH', 'SHN', 'Saint Helena'),
(181, 'KN', 'KNA', 'Saint Kitts and Nevis'),
(182, 'LC', 'LCA', 'Saint Lucia'),
(183, 'PM', 'SPM', 'Saint Pierre and Miquelon'),
(184, 'VC', 'VCT', 'Saint Vincent '),
(185, 'WS', 'WSM', 'Samoa '),
(186, 'SM', 'SMR', 'San Marino'),
(187, 'ST', 'STP', 'Sao Tome and Principe'),
(188, 'SA', 'SAU', 'Saudi Arabia'),
(189, 'SN', 'SEN', 'Senegal'),
(190, 'CS', 'SCG', 'Serbia and Montenegro '),
(191, 'SC', 'SYC', 'Seychelles'),
(192, 'SL', 'SLE', 'Sierra Leone'),
(193, 'SG', 'SGP', 'Singapore'),
(194, 'SK', 'SVK', 'Slovakia '),
(195, 'SI', 'SVN', 'Slovenia'),
(196, 'SB', 'SLB', 'Solomon Islands'),
(197, 'SO', 'SOM', 'Somalia'),
(198, 'ZA', 'ZAF', 'South Africa'),
(199, 'GS', 'SGS', 'South Georgia'),
(200, 'ES', 'ESP', 'Spain'),
(201, 'LK', 'LKA', 'Sri Lanka'),
(202, 'SD', 'SDN', 'Sudan'),
(203, 'SR', 'SUR', 'Suriname'),
(204, 'SJ', 'SJM', 'Svalbard and Jan Mayen'),
(205, 'SZ', 'SWZ', 'Swaziland'),
(206, 'SE', 'SWE', 'Sweden'),
(207, 'CH', 'CHE', 'Switzerland'),
(208, 'SY', 'SYR', 'Syrian Arab Republic'),
(209, 'TW', 'TWN', 'Taiwan'),
(210, 'TJ', 'TJK', 'Tajikistan'),
(211, 'TZ', 'TZA', 'Tanzania'),
(212, 'TH', 'THA', 'Thailand'),
(213, 'TL', 'TLS', 'Timor-Leste'),
(214, 'TG', 'TGO', 'Togo'),
(215, 'TK', 'TKL', 'Tokelau'),
(216, 'TO', 'TON', 'Tonga'),
(217, 'TT', 'TTO', 'Trinidad and Tobago'),
(218, 'TN', 'TUN', 'Tunisia'),
(219, 'TR', 'TUR', 'Turkey'),
(220, 'TM', 'TKM', 'Turkmenistan'),
(221, 'TC', 'TCA', 'Turks and Caicos Islands'),
(222, 'TV', 'TUV', 'Tuvalu'),
(223, 'UG', 'UGA', 'Uganda'),
(224, 'UA', 'UKR', 'Ukraine'),
(225, 'AE', 'ARE', 'United Arab Emirates'),
(226, 'GB', 'GBR', 'United Kingdom'),
(227, 'US', 'USA', 'United States'),
(229, 'UY', 'URY', 'Uruguay'),
(230, 'UZ', 'UZB', 'Uzbekistan'),
(231, 'VU', 'VUT', 'Vanuatu'),
(232, 'VA', 'VAT', 'Vatican City'),
(233, 'VE', 'VEN', 'Venezuela'),
(234, 'VN', 'VNM', 'Viet Nam'),
(235, 'VG', 'VGB', 'Virgin Islands, British'),
(236, 'VI', 'VIR', 'Virgin Islands, U.S.'),
(237, 'WF', 'WLF', 'Wallis and Futuna'),
(238, 'EH', 'ESH', 'Western Sahara'),
(239, 'YE', 'YEM', 'Yemen'),
(240, 'ZM', 'ZMB', 'Zambia'),
(241, 'ZW', 'ZWE', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE IF NOT EXISTS `custom_fields` (
  `custom_field_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field_title` text NOT NULL,
  `custom_field_type` text NOT NULL,
  `custom_field_name` text NOT NULL,
  `custom_field_id` text NOT NULL,
  `custom_field_value` text NOT NULL,
  `custom_field_hint_1` text NOT NULL,
  `custom_field_db_field` text NOT NULL,
  `custom_field_required` enum('yes','no') NOT NULL DEFAULT 'no',
  `custom_field_validate_function` text NOT NULL,
  `custom_field_invalid_err` text NOT NULL,
  `custom_field_display_function` text NOT NULL,
  `custom_field_anchor_before` text NOT NULL,
  `custom_field_anchor_after` text NOT NULL,
  `custom_field_hint_2` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`custom_field_list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `custom_fields`
--


-- --------------------------------------------------------

--
-- Table structure for table `editors_picks`
--

CREATE TABLE IF NOT EXISTS `editors_picks` (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `editors_picks`
--


-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE IF NOT EXISTS `email_settings` (
  `email_settings_id` int(25) NOT NULL AUTO_INCREMENT,
  `email_settings_name` varchar(60) NOT NULL,
  `email_settings_value` mediumtext NOT NULL,
  `email_settings_headers` mediumtext NOT NULL,
  PRIMARY KEY (`email_settings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`email_settings_id`, `email_settings_name`, `email_settings_value`, `email_settings_headers`) VALUES
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
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_name` varchar(225) NOT NULL,
  `email_template_code` varchar(225) NOT NULL,
  `email_template_subject` mediumtext NOT NULL,
  `email_template` text NOT NULL,
  `email_template_allowed_tags` mediumtext NOT NULL,
  PRIMARY KEY (`email_template_id`),
  UNIQUE KEY `email_template_code` (`email_template_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`email_template_id`, `email_template_name`, `email_template_code`, `email_template_subject`, `email_template`, `email_template_allowed_tags`) VALUES
(1, 'Share Video Template', 'share_video_template', '[{website_title}] - {username} wants to share a video with you', '<html>\r\n<head>\r\n<style type="text/css">\r\n<!--\r\n.title {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #FFFFFF;\r\n	font-size: 16px;\r\n}\r\n.title2 {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 14px;\r\n}\r\n.messege {\r\n	font-family: Arial, Helvetica, sans-serif;\r\n	padding: 5px;\r\n	font-weight:bold;\r\n	color: #000000;\r\n	font-size: 12px;\r\n}\r\n#videoThumb{\r\n	width: 120px;\r\n	padding: 2px;\r\n	margin: 3px;\r\n	border: 1px solid #F0F0F0;\r\n	text-align: center;\r\n	vertical-align: middle;\r\n}\r\n#videoThumb img{border:0px}\r\nbody,td,th {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #FFFFFF;\r\n}\r\n.text {\r\n	font-family: tahoma;\r\n	font-size: 11px;\r\n	color: #000000;\r\n	padding: 5px;\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>\r\n<table width="100%" border="0" cellspacing="0" cellpadding="5">\r\n  <tr>\r\n    <td bgcolor="#53baff" ><span class="title">{website_title}</span>share video</td>\r\n  </tr>\r\n  <tr>\r\n    <td height="20" class="messege">{username} wants to share Video With You\r\n      <div id="videoThumb"><a href="{video_link}"><img src="{video_thumb}"><br>\r\n    watch video</a></div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class="text" ><span class="title2">Video Description</span><br>\r\n      <span class="text">{video_description}</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td><span class="title2">Personal Message</span><br>\r\n      <span class="text">{user_message}\r\n      </span><br>\r\n      <br>\r\n<span class="text">Thanks,</span><br> \r\n<span class="text">{username}</span></td>\r\n  </tr>\r\n  <tr>\r\n    <td bgcolor="#53baff">copyrights {date_year} {website_title}</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>', '{website_title},{'),
(2, 'Email Verification Template', 'email_verify_template', '[{website_title}] - Account activation email', 'Hello {username},\r\nThank you for joining us, your account details are\r\n\r\nUsername     : {username}\r\nPassword     : {password}\r\nEmail        : {email}\r\nDate Joined  : {date}\r\n\r\nYour account is not activated yet, please activate it by using following link\r\n\r\n<a href={baseurl}/activation.php?username={username}&avcode={avcode}>Click Here</a>\r\n\r\n{baseurl}/activation.php?username={username}&avcode={avcode}\r\n\r\nAVCODE : {avcode}\r\n\r\n====================\r\nRegards\r\n{website_title}', ''),
(3, 'Private Message Notification', 'pm_email_message', '[{website_title}] - {sender} has sent you a private message', '{sender} has sent you a private message, \r\n\r\n{subject}\r\n"{content}"\r\n\r\nclick here to view your inbox <a href="{baseurl}/private_message.php?mode=inbox&mid={msg_id}">{baseurl}/private_message.php?mode=inbox&mid={msg_id}</a>\r\n\r\n{website_title}', '');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `favorite_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `favorites`
--


-- --------------------------------------------------------

--
-- Table structure for table `flagged_videos`
--

CREATE TABLE IF NOT EXISTS `flagged_videos` (
  `flagged_id` int(11) NOT NULL AUTO_INCREMENT,
  `videoid` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`flagged_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `flagged_videos`
--


-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

CREATE TABLE IF NOT EXISTS `flags` (
  `flag_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `flag_type` bigint(25) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`flag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `flags`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_name` mediumtext NOT NULL,
  `group_description` mediumtext NOT NULL,
  `group_tags` mediumtext NOT NULL,
  `group_url` mediumtext NOT NULL,
  `group_category` int(20) NOT NULL,
  `group_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `video_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `post_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(26) NOT NULL,
  `userid` int(11) NOT NULL,
  `featured` enum('yes','no') NOT NULL DEFAULT 'no',
  `group_thumb` mediumtext NOT NULL,
  `total_videos` int(225) NOT NULL,
  `total_members` int(225) NOT NULL,
  `total_topics` int(225) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_invitations`
--

CREATE TABLE IF NOT EXISTS `group_invitations` (
  `invitation_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `invited_user` varchar(16) NOT NULL,
  `invited_by` varchar(16) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_invitations`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE IF NOT EXISTS `group_members` (
  `group_mid` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `username` varchar(26) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`group_mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

CREATE TABLE IF NOT EXISTS `group_posts` (
  `post_id` int(225) NOT NULL AUTO_INCREMENT,
  `topic_id` int(225) NOT NULL,
  `post` mediumtext NOT NULL,
  `username` varchar(26) NOT NULL,
  `userid` int(11) NOT NULL,
  `reply_to` int(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_posts`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_topics`
--

CREATE TABLE IF NOT EXISTS `group_topics` (
  `topic_id` int(225) NOT NULL AUTO_INCREMENT,
  `topic_title` mediumtext NOT NULL,
  `group_id` int(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_reply` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `username` varchar(26) NOT NULL,
  `userid` int(11) NOT NULL,
  `videokey` mediumtext NOT NULL,
  `approved` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_videos`
--

CREATE TABLE IF NOT EXISTS `group_videos` (
  `group_vid` int(225) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `group_id` int(225) NOT NULL,
  `username` varchar(26) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` enum('yes','no') NOT NULL,
  PRIMARY KEY (`group_vid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_videos`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `language_id` int(9) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `language_regex` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `language_default` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`language_id`, `language_code`, `language_name`, `language_regex`, `language_default`) VALUES
(2, 'en', 'English', '/^en/i', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
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
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `module_id` int(25) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(25) NOT NULL,
  `module_file` varchar(60) NOT NULL,
  `active` varchar(5) NOT NULL,
  `module_include_file` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `modules`
--


-- --------------------------------------------------------

--
-- Table structure for table `phrases`
--

CREATE TABLE IF NOT EXISTS `phrases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_iso` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT 'en',
  `varname` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=839 ;

--
-- Dumping data for table `phrases`
--

INSERT INTO `phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(507, 'en', 'grp_add_vdo_msg', 'Add Videos To Group '),
(2, 'en', 'ad_name_error', 'Please Enter Name For The Advertisments'),
(3, 'en', 'ad_code_error', 'Error : Please Enter Code For Advertisement'),
(4, 'en', 'ad_exists_error1', 'Add Does not exists'),
(5, 'en', 'ad_exists_error2', 'Error : Advertisement With This Name Already Exists'),
(6, 'en', 'ad_add_msg', 'Advertisment Has Been Added'),
(7, 'en', 'ad_msg', 'Ad Has Been '),
(8, 'en', 'ad_update_msg', 'Advertisment Has Been Updated'),
(9, 'en', 'ad_del_msg', 'Advertisement Has Been Deleted'),
(10, 'en', 'ad_deactive', 'Deactivated'),
(11, 'en', 'ad_active', 'Activated'),
(12, 'en', 'ad_placment_delete_msg', 'Placement Has Been Removed'),
(13, 'en', 'ad_placement_err1', 'Placement already exists'),
(14, 'en', 'ad_placement_err2', 'Please Enter Name For Placement'),
(15, 'en', 'ad_placement_err3', 'Please Enter Code For Placement'),
(16, 'en', 'ad_placement_msg', 'Placement Has Been Added'),
(17, 'en', 'cat_img_error', 'Please Upload JPEG, GIF or PNG image only'),
(18, 'en', 'cat_exist_error', 'Category doesn&#8217;t exist'),
(19, 'en', 'cat_add_msg', 'Category has been added successfully'),
(20, 'en', 'cat_update_msg', 'Category has been updated'),
(21, 'en', 'grp_err', 'Group Doesn&#8217;t Exist'),
(22, 'en', 'grp_fr_msg', 'Group Has Been Set to Featured'),
(23, 'en', 'grp_fr_msg1', 'Selected Groups Have Been Removed From The Featured List'),
(24, 'en', 'grp_ac_msg', 'Selected Groups Have Been Activated'),
(25, 'en', 'grp_dac_msg', 'Selected Groups Have Been Dectivated'),
(26, 'en', 'grp_del_msg', 'Group Has Been Delete'),
(27, 'en', 'editor_pic_up', 'Video Has Been Moved Up'),
(28, 'en', 'editor_pic_down', 'Video Has Been Moved Down'),
(29, 'en', 'plugin_install_msg', 'Plugin has been installed'),
(30, 'en', 'plugin_no_file_err', 'No file was found'),
(31, 'en', 'plugin_file_detail_err', 'Unknown plugin details found'),
(32, 'en', 'plugin_installed_err', 'Plugin already installed'),
(33, 'en', 'plugin_no_install_err', 'Plugin is not installed'),
(34, 'en', 'grp_name_error', 'Please Enter Name For Group'),
(35, 'en', 'grp_name_error1', 'Group Name Already Exists'),
(36, 'en', 'grp_des_error', 'Please Enter Little Description For Group'),
(37, 'en', 'grp_tags_error', 'Please Enter Tags For Group'),
(38, 'en', 'grp_url_error', 'Please Enter URL For Group&#8221;'),
(39, 'en', 'grp_url_error1', 'Please enter Valid URL name'),
(40, 'en', 'grp_url_error2', 'Group URL Already Exists, Please Choose a Different URL'),
(41, 'en', 'grp_tpc_error', 'Please enter a topic to add'),
(42, 'en', 'grp_comment_error', 'You must enter a comment'),
(43, 'en', 'grp_join_error', 'You Have Already Joined This Group'),
(44, 'en', 'grp_prvt_error', 'This Group Is Private, Please Login to View this Group'),
(45, 'en', 'grp_inact_error', 'This Group Is Inactive, Please Contact Administrator for the problem'),
(46, 'en', 'grp_join_error1', 'You Have Not Joined This Group Yet'),
(47, 'en', 'grp_exist_error', 'Sorry, Group Doesn&#8217;t Exist'),
(48, 'en', 'grp_tpc_error1', 'This Topic Is Not Approved By The Group Owner'),
(49, 'en', 'grp_cat_error', 'Please Select A Category For Your group'),
(50, 'en', 'grp_tpc_error2', 'Please enter topic to add'),
(51, 'en', 'grp_tpc_error3', 'Your Topic Requires Approval From Owner Of This Group'),
(52, 'en', 'grp_tpc_msg', 'Topic Has Been Added'),
(53, 'en', 'grp_comment_msg', 'Comment Has Been Added'),
(54, 'en', 'grp_vdo_msg', 'Videos Have Been Deleted'),
(55, 'en', 'grp_vdo_msg1', 'Videos Has Been Added Successfully'),
(56, 'en', 'grp_vdo_msg2', 'Videos Have Been Approved'),
(57, 'en', 'grp_mem_msg', 'Member Has Been Deleted'),
(58, 'en', 'grp_mem_msg1', 'Member Has Been Approved'),
(59, 'en', 'grp_inv_msg', 'Your Invitation Has Been Sent'),
(60, 'en', 'grp_tpc_msg1', 'Topic Has Been Delete'),
(61, 'en', 'grp_tpc_msg2', 'Topic Has Been Approved'),
(62, 'en', 'grp_fr_msg2', 'Group has been un featured'),
(63, 'en', 'grp_inv_msg1', 'Has Invited You To Join '),
(64, 'en', 'grp_av_msg', 'Group Has Been Activated'),
(65, 'en', 'grp_da_msg', 'Group Has Been DeActivated'),
(66, 'en', 'grp_post_msg', 'Post Has Been Delete'),
(67, 'en', 'grp_update_msg', 'Group Has Been Updated'),
(68, 'en', 'grp_owner_err', 'Only Owner Can Add Videos To This Group'),
(69, 'en', 'grp_owner_err1', 'You Are Not Group Owner'),
(70, 'en', 'grp_owner_err2', 'You Are Group Owner , You Cannot Leave Your Group'),
(71, 'en', 'grp_prvt_err1', 'This Group is Private, You require an Invitation from group Owner'),
(72, 'en', 'grp_rmv_msg', 'Selected Groups Have Been Removed From Your Account'),
(73, 'en', 'grp_tpc_err4', 'Sorry, Topic Doesn&#8217;t Exist'),
(74, 'en', 'grp_title_topic', 'Groups - Topic - '),
(75, 'en', 'grp_add_title', '- Add Video'),
(76, 'en', 'usr_sadmin_err', 'You Cannot Set SuperAdmin Username as Blank'),
(77, 'en', 'usr_cpass_err', 'Confirm Password Doesn&#8217;t Match'),
(78, 'en', 'usr_pass_err', 'Old password is incorrect'),
(79, 'en', 'usr_email_err', 'Please Provide A Valid Email Address'),
(80, 'en', 'usr_cpass_err1', 'Confirm password is incorrect'),
(81, 'en', 'usr_pass_err1', 'Password is Incorrect'),
(82, 'en', 'usr_cmt_err', 'You Must Login First To Comment'),
(83, 'en', 'usr_cmt_err1', 'Please Type Something In Comment Box'),
(84, 'en', 'usr_cmt_err2', 'You Cannot Post Comment on  Your Own Video'),
(85, 'en', 'usr_cmt_err3', 'You Have Already Posted a Comment on this channel.'),
(86, 'en', 'usr_cmt_err4', 'Comment Has Been Added'),
(87, 'en', 'usr_cmt_del_msg', 'Comment Has Been Deleted'),
(88, 'en', 'usr_cmt_del_err', 'An Error Occured While deleting a Comment'),
(89, 'en', 'usr_cnt_err', 'You Cannot Add Yourself as a Contact'),
(90, 'en', 'usr_cnt_err1', 'You Have Already Added This User To Your Contact List'),
(91, 'en', 'usr_sub_err', 'You are already subsctibed to %s'),
(92, 'en', 'usr_exist_err', 'User Doesnt Exist'),
(93, 'en', 'usr_ccode_err', 'You Have Entered Wrong Confirmation Code'),
(94, 'en', 'usr_exist_err1', 'Sorry, No User Exists With This Email'),
(95, 'en', 'usr_exist_err2', 'Sorry , User Doesnt Exists'),
(96, 'en', 'usr_uname_err', 'Username is empty'),
(97, 'en', 'usr_uname_err2', 'Username already exists'),
(98, 'en', 'usr_pass_err2', 'Password Is Empty'),
(99, 'en', 'usr_email_err1', 'Email is Empty'),
(100, 'en', 'usr_email_err2', 'Please Enter A Valid Email Address'),
(101, 'en', 'usr_email_err3', 'Email Address Is Already In Use'),
(102, 'en', 'usr_pcode_err', 'Postal Code Only Contains Number'),
(103, 'en', 'usr_fname_err', 'First Name Is Empty'),
(104, 'en', 'usr_lname_err', 'Last Name Is Empty'),
(105, 'en', 'usr_uname_err3', 'Username Contains Unallowed Characters'),
(106, 'en', 'usr_pass_err3', 'Passwords MisMatched'),
(107, 'en', 'usr_dob_err', 'Please Select Date Of Birth'),
(108, 'en', 'usr_ament_err', 'Sorry, you need to agree to the terms of use and privacy policy to create an account'),
(109, 'en', 'usr_reg_err', 'Sorry, Registrations Are Temporarily Not Allowed, Please Try Again Later'),
(110, 'en', 'usr_ban_err', 'User Account Banned.Contact the Site Administrator for further details.'),
(111, 'en', 'usr_login_err', 'Username Password Didn&#8217;t Match'),
(112, 'en', 'usr_sadmin_msg', 'Super Admin Has Been Updated'),
(113, 'en', 'usr_pass_msg', 'Your Password Has Been Changed'),
(114, 'en', 'usr_cnt_msg', 'This User Has Been Added To Your Contact List'),
(115, 'en', 'usr_sub_msg', 'You are now subsribed to %s'),
(116, 'en', 'usr_uname_email_msg', 'We Have Sent you an Email containing Your Usename, Please Check It'),
(117, 'en', 'usr_rpass_email_msg', 'Email Has Sent To You Please Follow the Instructions to Reset Your Password'),
(118, 'en', 'usr_pass_email_msg', 'Password has been changed successfully'),
(119, 'en', 'usr_email_msg', 'Email Settings Has Been Updated'),
(120, 'en', 'usr_del_msg', 'User Has Been Deleted Successfully'),
(121, 'en', 'usr_dels_msg', 'Selected Users Have Been Deleted'),
(122, 'en', 'usr_ac_msg', 'User Has Been Activated'),
(123, 'en', 'usr_dac_msg', 'User Has Been Deactivated'),
(124, 'en', 'usr_mem_ac', 'Selected Members Have Been Activated'),
(125, 'en', 'usr_mems_ac', 'Selected Members Have Been Dectivated'),
(126, 'en', 'usr_fr_msg', 'User Has Been Made Featured Member'),
(127, 'en', 'usr_ufr_msg', 'User Has Been Unfeatured'),
(128, 'en', 'usr_frs_msg', 'Selected Users Have Been Set As Featured'),
(129, 'en', 'usr_ufrs_msg', 'Selected Users Have Been Removed From The Featured List'),
(130, 'en', 'usr_uban_msg', 'User Has Been Banned'),
(131, 'en', 'usr_uuban_msg', 'User Has Been Unbanned'),
(132, 'en', 'usr_ubans_msg', 'Selected Members Have Been Banned'),
(133, 'en', 'usr_uubans_msg', 'Selected Members Have Been Unbanned'),
(134, 'en', 'usr_pass_reset_conf', 'Password Reset Confirmation'),
(135, 'en', 'usr_dear_user', 'Dear User'),
(136, 'en', 'usr_pass_reset_msg', 'You Requested A Password Reset, Follow The Link To Reset Your Password'),
(137, 'en', 'usr_rpass_msg', 'Password Has Been Reset'),
(138, 'en', 'usr_rpass_req_msg', 'You Requested A Password Reset, Here is your new password : '),
(139, 'en', 'usr_uname_req_msg', 'You Requested to Recover Your Username, Here is you username: '),
(140, 'en', 'usr_uname_recovery', 'Username Recovery Email'),
(141, 'en', 'usr_add_succ_msg', 'User Has Been Added, Successfully'),
(142, 'en', 'usr_upd_succ_msg', 'User has been updated'),
(143, 'en', 'usr_activation_msg', 'your account has been activated, Now you can login to your account and upload videos'),
(144, 'en', 'usr_activation_err', 'Sorry This User either has been already Activated or Username and activation code is WRONG'),
(145, 'en', 'usr_activation_em_msg', 'Activation Code Has Been Sent To Your Mail Box, Please Check It'),
(146, 'en', 'usr_activation_em_err', 'Email Doesn&#8217;t Exist or User With This Email already Acitvated'),
(147, 'en', 'usr_no_msg_del_err', 'No Message Was Selected To Delete'),
(148, 'en', 'usr_sel_msg_del_msg', 'Selected Messages Have Been Deleted'),
(149, 'en', 'usr_pof_upd_msg', 'Profile has been updated'),
(150, 'en', 'usr_arr_no_ans', 'no answer'),
(151, 'en', 'usr_arr_elementary', 'Elementary'),
(152, 'en', 'usr_arr_hi_school', 'High School'),
(153, 'en', 'usr_arr_some_colg', 'Some College'),
(154, 'en', 'usr_arr_assoc_deg', 'Associates Degree'),
(155, 'en', 'usr_arr_bach_deg', 'Bachelor&#8217;s Degree'),
(156, 'en', 'usr_arr_mast_deg', 'Master&#8217;s Degree'),
(157, 'en', 'usr_arr_phd', 'Ph.D.'),
(158, 'en', 'usr_arr_post_doc', 'Postdoctoral'),
(159, 'en', 'usr_arr_single', 'Single'),
(160, 'en', 'usr_arr_married', 'Married'),
(161, 'en', 'usr_arr_comitted', 'Comitted'),
(162, 'en', 'usr_arr_open_marriage', 'Open Marriage'),
(163, 'en', 'usr_arr_open_relate', 'Open Relationship'),
(164, 'en', 'title_crt_new_msg', ' - Create New Message'),
(165, 'en', 'title_forgot', 'Forgot Something? Find it now !'),
(166, 'en', 'title_inbox', ' - Inbox'),
(167, 'en', 'title_sent', ' - Sent Folder'),
(168, 'en', 'title_usr_contact', '&#8217;s Contact List'),
(169, 'en', 'title_usr_fav_vids', '&#8217;s Favourite Videos'),
(170, 'en', 'title_view_channel', '&#8217;s Channel'),
(171, 'en', 'title_edit_video', 'Edit Video - '),
(172, 'en', 'vdo_title_err', 'Please Enter Video Title'),
(173, 'en', 'vdo_des_err', 'Please Enter Video Description'),
(174, 'en', 'vdo_tags_err', 'Please Enter Tags For The Video'),
(175, 'en', 'vdo_cat_err', 'Please Choose Atleast 1 Category'),
(176, 'en', 'vdo_cat_err1', 'You Can Only Choose Upto 3 Categories'),
(177, 'en', 'vdo_sub_email_msg', ' and therefore this message is sent to you automatically that '),
(178, 'en', 'vdo_has_upload_nv', 'Has Uploaded New Video'),
(179, 'en', 'vdo_del_selected', 'Selected Videos Have Been Deleted'),
(180, 'en', 'vdo_cheat_msg', 'Please Dont Try To Cheat'),
(181, 'en', 'vdo_limits_warn_msg', 'Please Dont Try To Cross Your Limits'),
(182, 'en', 'vdo_cmt_del_msg', 'Comment Has Been Deleted'),
(183, 'en', 'vdo_iac_msg', 'Video Is Inactive - Please Contact Admin For Details'),
(184, 'en', 'vdo_is_in_process', 'Video Is In Process - Please Contact Administrator for further details'),
(185, 'en', 'vdo_upload_allow_err', 'Uploading Is Not Allowed By Website Owner'),
(186, 'en', 'vdo_download_allow_err', 'Video Downloading Is Not Allowed'),
(187, 'en', 'vdo_edit_owner_err', 'You Are Not Video Owner'),
(188, 'en', 'vdo_embed_code_wrong', 'Embed Code Was Wrong'),
(189, 'en', 'vdo_seconds_err', 'Wrong Value Entered For Seconds Field'),
(190, 'en', 'vdo_mins_err', 'Wrong Value Entered For Minutes Field'),
(191, 'en', 'vdo_thumb_up_err', 'Error In Uploading Thumb'),
(192, 'en', 'class_error_occured', 'Sorry, An Error Occured'),
(193, 'en', 'class_cat_del_msg', 'Category has been deleted'),
(194, 'en', 'class_vdo_del_msg', 'Video has been deleted'),
(195, 'en', 'class_vdo_fr_msg', 'Video has been to &#8220;Featured Video&#8221;'),
(196, 'en', 'class_fr_msg1', 'Video has been removed from &#8220;Featured Videos&#8221;'),
(197, 'en', 'class_vdo_act_msg', 'Video has been activated'),
(198, 'en', 'class_vdo_act_msg1', 'Vidoe has been deactivated'),
(199, 'en', 'class_vdo_update_msg', 'Video Has Been Updated Successfully'),
(200, 'en', 'class_comment_err', 'You Must Login First To Comment'),
(201, 'en', 'class_comment_err1', 'Please Type Something In Comment Box'),
(202, 'en', 'class_comment_err2', 'You Cannot Post Comment on  Your Own Video'),
(203, 'en', 'class_comment_err3', 'You Have Already Posted a Comment, Please Wait for the others.'),
(204, 'en', 'class_comment_err4', 'You Have Already Replied To That a Comment, Please Wait for the others.'),
(205, 'en', 'class_comment_err5', 'You Cannot Post Reply To Yourself'),
(206, 'en', 'class_comment_msg', 'Comment Has Been Added'),
(207, 'en', 'class_comment_err6', 'You Must Login First To Rate Comment'),
(208, 'en', 'class_comment_err7', 'You Have Already Rated The Comment'),
(209, 'en', 'class_vdo_fav_err', 'This Video is Already Added To Your Favourites'),
(210, 'en', 'class_vdo_fav_msg', 'This Video Has Been Added To Your Favourites'),
(211, 'en', 'class_vdo_flag_err', 'You Have Already Flagged This Video'),
(212, 'en', 'class_vdo_flag_msg', 'This Video Has Been Flagged As Inappropriate'),
(213, 'en', 'class_vdo_flag_rm', 'Flag(s) Has/Have Been Removed'),
(214, 'en', 'class_send_msg_err', 'Please Enter a Username or Select any User to Send Message'),
(215, 'en', 'class_invalid_user', 'Invalid Username'),
(216, 'en', 'class_subj_err', 'Message subject was empty'),
(217, 'en', 'class_msg_err', 'Please Type Something In Message Box'),
(218, 'en', 'class_sent_you_msg', 'Sent You A Message'),
(219, 'en', 'class_sent_prvt_msg', 'Sent You A Private Message on '),
(220, 'en', 'class_click_inbox', 'Please Click here To View Your Inbox'),
(221, 'en', 'class_click_login', 'Click Here To Login'),
(222, 'en', 'class_email_notify', 'Email Notification'),
(223, 'en', 'class_msg_has_sent_to', 'Message Has Been Sent To '),
(224, 'en', 'class_inbox_del_msg', 'Message Has Been Delete From Inbox '),
(225, 'en', 'class_sent_del_msg', 'Message Has Been Delete From Sent Folder'),
(226, 'en', 'class_msg_exist_err', 'Message Doesnt Exist'),
(227, 'en', 'class_vdo_del_err', 'Video does not exist'),
(228, 'en', 'class_unsub_msg', 'You Have Unsubscribed'),
(229, 'en', 'class_sub_exist_err', 'Subscription Does Not Exist'),
(230, 'en', 'class_vdo_rm_fav_msg', 'Video Has Been Removed From Favourites'),
(231, 'en', 'class_vdo_fav_err1', 'This Video Is Not In Your Favourite List'),
(232, 'en', 'class_cont_del_msg', 'Contact Has Been Delete'),
(233, 'en', 'class_cot_err', 'Sorry, This Contact Is Not In Your Contact List'),
(234, 'en', 'class_vdo_ep_add_msg', 'Video Has Been Added To Editor&#8217;s Pick'),
(235, 'en', 'class_vdo_ep_err', 'Video Is Already In The Editor&#8217;s Pick'),
(236, 'en', 'class_vdo_ep_err1', 'You Have Already Picked 10 Videos Please Delete Alteast One to Add More'),
(237, 'en', 'class_vdo_ep_msg', 'Video Has Been Removed From Editor&#8217;s Pick'),
(238, 'en', 'class_vdo_exist_err', 'Sorry, Video Doesnt Exist'),
(239, 'en', 'class_img_gif_err', 'Please Upload Gif Image Only'),
(240, 'en', 'class_img_png_err', 'Please Upload Png Image Only'),
(241, 'en', 'class_img_jpg_err', 'Please Upload Jpg Image Only'),
(242, 'en', 'class_logo_msg', 'Logo Has Been ChangedPlease Clear Cache If You Are Not Able To See Changed Logo'),
(243, 'en', 'com_forgot_username', 'Forgot Username | Password'),
(244, 'en', 'com_join_now', 'Join Now'),
(245, 'en', 'com_my_account', 'My Account'),
(246, 'en', 'com_manage_vids', 'Manage Videos'),
(247, 'en', 'com_view_channel', 'View My Channel'),
(248, 'en', 'com_my_inbox', 'My Inbox'),
(249, 'en', 'com_welcome', 'Welcome'),
(250, 'en', 'com_top_mem', 'Top Members '),
(251, 'en', 'com_vidz', 'Videos'),
(252, 'en', 'com_sign_up_now', 'Sign Up Now !'),
(253, 'en', 'com_my_videos', 'My Videos'),
(254, 'en', 'com_my_channel', 'My Channel'),
(255, 'en', 'com_my_subs', 'My Subscriptions'),
(256, 'en', 'com_user_no_contacts', 'User Does Not Have Any Contact'),
(257, 'en', 'com_user_no_vides', 'User Does Not Have Any Favourite Video'),
(258, 'en', 'com_user_no_vid_com', 'User Has No Video Comments'),
(259, 'en', 'com_view_all_contacts', 'View All Contacts of'),
(260, 'en', 'com_view_fav_all_videos', 'View All Favourite Videos Of'),
(261, 'en', 'com_login_success_msg', 'You Have Been Successfully Logged In.'),
(262, 'en', 'com_logout_success_msg', 'You Have Been Successfully Logged Out.'),
(263, 'en', 'com_not_redirecting', 'You are now Redirecting .'),
(264, 'en', 'com_not_redirecting_msg', 'if your are not redirecting'),
(265, 'en', 'com_manage_contacts', 'Manage Contacts '),
(266, 'en', 'com_send_message', 'Send Message'),
(267, 'en', 'com_manage_fav', 'Manage Favourites '),
(268, 'en', 'com_manage_subs', 'Manage Subscriptions'),
(269, 'en', 'com_subscribe_to', 'Subscribe to %s''s channel'),
(270, 'en', 'com_total_subs', 'Total Subscribtions'),
(271, 'en', 'com_total_vids', 'Total Videos'),
(272, 'en', 'com_date_subscribed', 'Date Subscribed'),
(273, 'en', 'com_search_results', 'Search Results'),
(274, 'en', 'com_advance_results', 'Advance Search'),
(275, 'en', 'com_search_results_in', 'Search Results In'),
(276, 'en', 'videos_being_watched', 'Recently Viewed...'),
(277, 'en', 'latest_added_videos', 'Recent Additions'),
(278, 'en', 'most_viewed', 'Most Viewed'),
(279, 'en', 'recently_added', 'Recently Added'),
(280, 'en', 'featured', 'Featured'),
(281, 'en', 'highest_rated', 'Highest Rated'),
(282, 'en', 'most_discussed', 'Most Discussed'),
(283, 'en', 'style_change', 'Style Change'),
(284, 'en', 'rss_feed_latest_title', 'RSS Feed for Most Recent Videos'),
(285, 'en', 'rss_feed_featured_title', 'RSS Feed for Featured Videos'),
(286, 'en', 'rss_feed_most_viewed_title', 'RSS Feed for Most Popular Videos'),
(287, 'en', 'lang_folder', 'en'),
(288, 'en', 'reg_closed', 'Registration Closed'),
(289, 'en', 'reg_for', 'Registration for'),
(290, 'en', 'is_currently_closed', 'is currently closed'),
(291, 'en', 'about_us', 'About Us'),
(292, 'en', 'account', 'Account'),
(293, 'en', 'added', 'Added'),
(294, 'en', 'advertisements', 'Advertisements'),
(295, 'en', 'all', 'All'),
(296, 'en', 'active', 'Active'),
(297, 'en', 'activate', 'Activate'),
(298, 'en', 'age', 'Age'),
(299, 'en', 'approve', 'Approve'),
(300, 'en', 'approved', 'Approved'),
(301, 'en', 'approval', 'Approval'),
(302, 'en', 'books', 'Books'),
(303, 'en', 'browse', 'Browse'),
(304, 'en', 'by', 'by'),
(305, 'en', 'cancel', 'Cancel'),
(306, 'en', 'categories', 'Categories'),
(307, 'en', 'category', 'Category'),
(308, 'en', 'channels', 'channels'),
(309, 'en', 'check_all', 'Check All'),
(310, 'en', 'click_here', 'Click Here'),
(311, 'en', 'comments', 'Comments'),
(312, 'en', 'community', 'Community'),
(313, 'en', 'companies', 'Companies'),
(314, 'en', 'contacts', 'Contacts'),
(315, 'en', 'contact_us', 'Contact Us'),
(316, 'en', 'country', 'Country'),
(317, 'en', 'created', 'Created'),
(318, 'en', 'date', 'Date'),
(319, 'en', 'date_added', 'Date Added'),
(320, 'en', 'date_joined', 'Date Joined'),
(321, 'en', 'dear', 'Dear'),
(322, 'en', 'delete', 'Delete'),
(323, 'en', 'delete_selected', 'Delete Selected'),
(324, 'en', 'des_title', 'Description:'),
(325, 'en', 'duration', 'Duration'),
(326, 'en', 'education', 'Education'),
(327, 'en', 'email', 'email'),
(328, 'en', 'embed', 'Embed'),
(329, 'en', 'embed_code', 'Embed Code'),
(330, 'en', 'favourite', 'Favorite'),
(331, 'en', 'favourited', 'Favorited'),
(332, 'en', 'favourites', 'Favorites'),
(333, 'en', 'female', 'Female'),
(334, 'en', 'filter', 'Filter'),
(335, 'en', 'forgot', 'Forgot'),
(336, 'en', 'friends', 'Friends'),
(337, 'en', 'from', 'From'),
(338, 'en', 'gender', 'Gender'),
(339, 'en', 'groups', 'Groups'),
(340, 'en', 'hello', 'Hello'),
(341, 'en', 'help', 'Help'),
(342, 'en', 'hi', 'Hi'),
(343, 'en', 'hobbies', 'Hobbies'),
(344, 'en', 'Home', 'Home'),
(345, 'en', 'inbox', 'Inbox'),
(346, 'en', 'interests', 'Interests'),
(347, 'en', 'join_now', 'Join Now'),
(348, 'en', 'joined', 'Joined'),
(349, 'en', 'join', 'Join'),
(350, 'en', 'keywords', 'Keywords'),
(351, 'en', 'latest', 'Latest'),
(352, 'en', 'leave', 'Leave'),
(353, 'en', 'location', 'Location'),
(354, 'en', 'login', 'Login'),
(355, 'en', 'logout', 'Logout'),
(356, 'en', 'male', 'Male'),
(357, 'en', 'members', 'Members'),
(358, 'en', 'messages', 'Messages'),
(359, 'en', 'message', 'Message'),
(360, 'en', 'minutes', 'minutes'),
(361, 'en', 'most_members', 'Most Members'),
(362, 'en', 'most_recent', 'Most Recent'),
(363, 'en', 'most_videos', 'Most Videos'),
(364, 'en', 'music', 'Music'),
(365, 'en', 'my_account', 'My Account'),
(366, 'en', 'next', 'Next'),
(367, 'en', 'no', 'No'),
(368, 'en', 'no_user_exists', 'No User Exists'),
(369, 'en', 'no_video_exists', 'No Video Exists'),
(370, 'en', 'occupations', 'Occupations'),
(371, 'en', 'optional', 'optional'),
(372, 'en', 'owner', 'Owner'),
(373, 'en', 'password', 'password'),
(374, 'en', 'please', 'Please'),
(375, 'en', 'privacy', 'Privacy'),
(376, 'en', 'privacy_policy', 'Privacy Policy'),
(377, 'en', 'random', 'Random'),
(378, 'en', 'rate', 'Rate'),
(379, 'en', 'request', 'Request'),
(380, 'en', 'related', 'Related'),
(381, 'en', 'reply', 'Reply'),
(382, 'en', 'results', 'Results'),
(383, 'en', 'relationship', 'Relationship'),
(384, 'en', 'seconds', 'seconds'),
(385, 'en', 'select', 'Select'),
(386, 'en', 'send', 'Send'),
(387, 'en', 'sent', 'Sent'),
(388, 'en', 'signup', 'Signup'),
(389, 'en', 'subject', 'Subject'),
(390, 'en', 'tags', 'Tags'),
(391, 'en', 'times', 'Times'),
(392, 'en', 'to', 'To'),
(393, 'en', 'type', 'Type'),
(394, 'en', 'update', 'Update'),
(395, 'en', 'upload', 'Upload'),
(396, 'en', 'url', 'Url'),
(397, 'en', 'verification', 'Verification'),
(398, 'en', 'videos', 'Videos'),
(399, 'en', 'viewing', 'Viewing'),
(400, 'en', 'welcome', 'Welcome'),
(401, 'en', 'website', 'Website'),
(402, 'en', 'yes', 'Yes'),
(403, 'en', 'of', 'of'),
(404, 'en', 'on', 'on'),
(405, 'en', 'previous', 'Previous'),
(406, 'en', 'rating', 'Rating'),
(407, 'en', 'ratings', 'Ratings'),
(408, 'en', 'remote_upload', 'Remote Upload'),
(409, 'en', 'remove', 'Remove'),
(410, 'en', 'search', 'search'),
(411, 'en', 'services', 'Services'),
(412, 'en', 'show_all', 'Show All'),
(413, 'en', 'signupup', 'Sign Up'),
(414, 'en', 'sort_by', 'Sort By'),
(415, 'en', 'subscriptions', 'Subscriptions'),
(416, 'en', 'subscribers', 'Subscribers'),
(417, 'en', 'tag_title', 'Tags'),
(418, 'en', 'time', 'time'),
(419, 'en', 'top', 'Top'),
(420, 'en', 'tos_title', 'Terms of Use'),
(421, 'en', 'username', 'username'),
(422, 'en', 'views', 'Views'),
(423, 'en', 'proccession_wait', 'Processing, Please Wait'),
(424, 'en', 'mostly_viewed', 'Most Viewed'),
(425, 'en', 'most_comments', 'Most Comments'),
(426, 'en', 'group', 'Group'),
(427, 'en', 'not_logged_in', 'You are not logged in or you do not have permission to access this page. This could be due to one of several reasons:'),
(428, 'en', 'fill_auth_form', 'You are not logged in. Fill in the form below and try again.'),
(429, 'en', 'insufficient_privileges', 'You may not have sufficient privileges to access this page.'),
(430, 'en', 'admin_disabled_you', 'The site administrator may have disabled your account, or it may be awaiting activation.'),
(431, 'en', 'Recover_Password', 'Recover Password'),
(432, 'en', 'Submit', 'Submit'),
(433, 'en', 'Reset_Fields', 'Reset Fields'),
(434, 'en', 'admin_reg_req', 'The administrator may have required you to register before you can view this page.'),
(435, 'en', 'lang_change', 'Language Change'),
(436, 'en', 'lang_changed', 'Your language has been changed'),
(437, 'en', 'lang_choice', 'Language'),
(438, 'en', 'if_not_redir', 'Click here to continue if you are not automatically redirected.'),
(439, 'en', 'style_changed', 'Your style has been changed'),
(440, 'en', 'style_choice', 'Style'),
(441, 'en', 'vdo_edit_vdo', 'Edit Video'),
(442, 'en', 'vdo_stills', 'Video Stills'),
(443, 'en', 'vdo_watch_video', 'Watch Video'),
(444, 'en', 'vdo_video_details', 'Video Details'),
(445, 'en', 'vdo_title', 'Title'),
(446, 'en', 'vdo_desc', 'Description'),
(447, 'en', 'vdo_cat', 'Video Category'),
(448, 'en', 'vdo_cat_msg', 'You May Select Upto 3 Categories'),
(449, 'en', 'vdo_tags_msg', 'Tags are separated by commas ie Arslan Hassan, Awsome, ClipBucket'),
(450, 'en', 'vdo_br_opt', 'Broadcast Options'),
(451, 'en', 'vdo_br_opt1', 'Public Share your video with the Everyone! (Recommended)'),
(452, 'en', 'vdo_br_opt2', 'Private Viewable by you and your friends only.'),
(453, 'en', 'vdo_date_loc', 'Date And Location'),
(454, 'en', 'vdo_date_rec', 'Date Recorded'),
(455, 'en', 'vdo_for_date', 'format MM / DD / YYYY '),
(456, 'en', 'vdo_add_eg', 'e.g London Greenland, Sialkot Mubarak Pura'),
(457, 'en', 'vdo_share_opt', 'Sharing Options'),
(458, 'en', 'vdo_allow_comm', 'Allow Comments '),
(459, 'en', 'vdo_dallow_comm', 'Do Not Allow Comments'),
(460, 'en', 'vdo_comm_vote', 'Comments Voting'),
(461, 'en', 'vdo_allow_com_vote', 'Allow Comments Voting '),
(462, 'en', 'vdo_dallow_com_vote', 'Do Not Allow Comments Voting '),
(463, 'en', 'vdo_allow_rating', 'Yes, Allow Rating on this video'),
(464, 'en', 'vdo_dallow_ratig', 'No, Do Not Allow Rating on this video'),
(465, 'en', 'vdo_embedding', 'Embedding'),
(466, 'en', 'vdo_embed_opt1', 'Yes, People can play this video on other websites'),
(467, 'en', 'vdo_embed_opt2', 'No, People cannot play this video on other websites'),
(468, 'en', 'vdo_update_title', 'Update'),
(469, 'en', 'vdo_inactive_msg', 'Your Account is Inactive Please Activate it to Upload Videos, To Activate your account Please'),
(470, 'en', 'vdo_click_here', 'Click Here'),
(471, 'en', 'vdo_continue_upload', 'Continue to Upload'),
(472, 'en', 'vdo_upload_step1', 'Video Upload'),
(473, 'en', 'vdo_upload_step2', '(Step 1/2) Filling Up Details'),
(474, 'en', 'vdo_upload_step3', '(Step 2/2)'),
(475, 'en', 'vdo_select_vdo', 'Select a video to upload.'),
(476, 'en', 'vdo_enter_remote_url', 'Enter Url Of The Video.'),
(477, 'en', 'vdo_enter_embed_code_msg', 'Enter Embed Video Code from other websites ie Youtube or Metacafe.'),
(478, 'en', 'vdo_enter_embed_code', 'Enter Embed Code'),
(479, 'en', 'vdo_enter_druation', 'Enter Duration'),
(480, 'en', 'vdo_select_vdo_thumb', 'Select Video Thumb'),
(481, 'en', 'vdo_having_trouble', 'Having Trouble?'),
(482, 'en', 'vdo_if_having_problem', 'if you having problem with the uploader'),
(483, 'en', 'vdo_clic_to_manage_all', 'Click Here To Manage All Videos'),
(484, 'en', 'vdo_manage_vdeos', 'Manage Videos '),
(485, 'en', 'vdo_status', 'Status'),
(486, 'en', 'vdo_rawfile', 'RawFile'),
(487, 'en', 'vdo_video_upload_complete', 'Video Upload - Upload Complete'),
(488, 'en', 'vdo_thanks_you_upload_complete_1', 'Thank you! Your upload is complete'),
(489, 'en', 'vdo_thanks_you_upload_complete_2', 'This video will be available in'),
(490, 'en', 'vdo_after_it_has_process', 'after it has finished processing.'),
(491, 'en', 'vdo_embed_this_video_on_web', 'Embed this video on your website.'),
(492, 'en', 'vdo_copy_and_paste_the_code', 'Copy and paste the code below to embed this video.'),
(493, 'en', 'vdo_upload_another_video', 'Upload Another Video'),
(494, 'en', 'vdo_goto_my_videos', 'Goto My Videos'),
(495, 'en', 'vdo_sperate_emails_by', 'seperate emails by commas'),
(496, 'en', 'vdo_personal_msg', 'Personal Message'),
(497, 'en', 'vdo_s_details', '&#8217;s Details'),
(498, 'en', 'vdo_s_description', '&#8217;s Description'),
(499, 'en', 'vdo_related_tags', 'Related Tags'),
(500, 'en', 'vdo_reply_to_this', 'Reply To This '),
(501, 'en', 'vdo_add_reply', 'Add Reply'),
(502, 'en', 'vdo_share_video', 'Share Video'),
(503, 'en', 'vdo_about_this_video', 'About This Video'),
(504, 'en', 'vdo_post_to_a_services', 'Post to an Aggregating Service'),
(505, 'en', 'vdo_commentary', 'Commentary'),
(506, 'en', 'vdo_post_a_comment', 'Post A Comment'),
(508, 'en', 'grp_no_vdo_msg', 'You Don&#8217;t Have Any Video'),
(509, 'en', 'grp_add_to', 'Add To Group'),
(510, 'en', 'grp_add_vdos', 'Add Videos'),
(511, 'en', 'grp_name_title', 'Group Name:'),
(512, 'en', 'grp_tag_title', 'Tags:'),
(513, 'en', 'grp_des_title', 'Description:'),
(514, 'en', 'grp_tags_msg', 'Enter one or more tags, separated by spaces.'),
(515, 'en', 'grp_tags_msg1', 'Tags are keywords used to describe your group so it can be easily found by other users. For example, if you have a group for surfers, you might tag it: surfing, beach, waves.'),
(516, 'en', 'grp_url_title', 'Choose a unique group name URL:'),
(517, 'en', 'grp_url_msg', 'Enter 3-18 characters with no spaces (such as &#8220;skateboarding skates&#8221;), that will become part of your group&#8217;s web address. Please note, the group name URL you pick is permanent and can&#8217;t be changed.'),
(518, 'en', 'grp_cat_tile', 'Group Category:'),
(519, 'en', 'grp_vdo_uploads', 'Video Uploads:'),
(520, 'en', 'grp_forum_posting', 'Forums Posting:'),
(521, 'en', 'grp_join_opt1', 'Public, anyone can join.'),
(522, 'en', 'grp_join_opt2', 'Protected, requires founder approval to join.'),
(523, 'en', 'grp_join_opt3', 'Private, by founder invite only, only members can view group details.'),
(524, 'en', 'grp_vdo_opt1', 'Post videos immediately.'),
(525, 'en', 'grp_vdo_opt2', 'Founder approval required before video is available.'),
(526, 'en', 'grp_vdo_opt3', 'Only Founder can add new videos.'),
(527, 'en', 'grp_post_opt1', 'Post topics immediately.'),
(528, 'en', 'grp_post_opt2', 'Founder approval required before topic is available.'),
(529, 'en', 'grp_post_opt3', 'Only Founder can create a new topic.'),
(530, 'en', 'grp_crt_grp', 'Create Group'),
(531, 'en', 'grp_thumb_title', 'Group Thumb'),
(532, 'en', 'grp_upl_thumb', 'Upload Group Thumb'),
(533, 'en', 'grp_must_be', 'Must Be'),
(534, 'en', 'grp_90x90', '90  x 90 Ratio Will Give Best quality'),
(535, 'en', 'grp_thumb_warn', 'Do Not Upload Vulgur or Copyrighted Material'),
(536, 'en', 'grp_del_confirm', 'Are You Sure You Want To Delete This Group'),
(537, 'en', 'grp_del_success', 'You Have Successfully Deleted'),
(538, 'en', 'grp_click_go_grps', 'Click Here To Go To Groups'),
(539, 'en', 'grp_edit_grp_title', 'Edit Group'),
(540, 'en', 'grp_manage_vdos', 'Manage Videos'),
(541, 'en', 'grp_manage_mems', 'Manage Members'),
(542, 'en', 'grp_del_group_title', 'Delete Group'),
(543, 'en', 'grp_add_vdos_title', 'Add Videos'),
(544, 'en', 'grp_join_grp_title', 'Join Group'),
(545, 'en', 'grp_leave_group_title', 'Leave Group'),
(546, 'en', 'grp_invite_grp_title', 'Invite Members'),
(547, 'en', 'grp_view_mems', 'View Members'),
(548, 'en', 'grp_view_vdos', 'View Videos'),
(549, 'en', 'grp_create_grp_title', 'Create A New Group'),
(550, 'en', 'grp_most_members', 'Most Members'),
(551, 'en', 'grp_most_discussed', 'Most Discussed'),
(552, 'en', 'grp_invite_msg', 'Invite Users To This Group'),
(553, 'en', 'grp_invite_msg1', 'Has Invited You To Join'),
(554, 'en', 'grp_invite_msg2', 'Enter Emails or Usernames (seperate by commas)'),
(555, 'en', 'grp_url_title1', 'Group url'),
(556, 'en', 'grp_invite_msg3', 'Send Invitation'),
(557, 'en', 'grp_join_confirm_msg', 'Are You Sure You Want To Join This Group'),
(558, 'en', 'grp_join_msg_succ', 'You Have Successfully Joined'),
(559, 'en', 'grp_click_here_to_go', 'Click Here To Go To'),
(560, 'en', 'grp_leave_confirm', 'Are You Sure You Want To Leave This Group'),
(561, 'en', 'grp_leave_succ_msg', 'You Have Successfully Left'),
(562, 'en', 'grp_manage_members_title', 'Manage Members '),
(563, 'en', 'grp_for_approval', 'For Approval'),
(564, 'en', 'grp_rm_videos', 'Remove Videos'),
(565, 'en', 'grp_rm_mems', 'Remove Members'),
(566, 'en', 'grp_groups_title', 'Manage Groups'),
(567, 'en', 'grp_remove_group', 'Remove Group'),
(568, 'en', 'grp_bo_grp_found', 'No Group Found'),
(569, 'en', 'grp_joined_groups', 'Joined Groups'),
(570, 'en', 'grp_owned_groups', 'Owned Groups'),
(571, 'en', 'grp_edit_this_grp', 'Edit This Group'),
(572, 'en', 'grp_topics_title', 'Topics'),
(573, 'en', 'grp_topic_title', 'Topic'),
(574, 'en', 'grp_posts_title', 'Posts'),
(575, 'en', 'grp_discus_title', 'Discussions'),
(576, 'en', 'grp_author_title', 'Author'),
(577, 'en', 'grp_replies_title', 'Replies'),
(578, 'en', 'grp_last_post_title', 'Last Post '),
(579, 'en', 'grp_viewl_all_videos', 'View All Videos of This Group'),
(580, 'en', 'grp_add_new_topic', 'Add New Topic'),
(581, 'en', 'grp_attach_video', 'Attach Video '),
(582, 'en', 'grp_add_topic', 'Add Topic'),
(583, 'en', 'grp_please_login', 'Please Login To Post Topics'),
(584, 'en', 'grp_please_join', 'Please Join This Group To Post Topics'),
(585, 'en', 'grp_inactive_account', 'Your Account Is Inactive And Required Activation From Group Owner'),
(586, 'en', 'grp_about_this_grp', 'About This Group '),
(587, 'en', 'grp_no_vdo_err', 'This Group Has No Vidoes'),
(588, 'en', 'grp_posted_by', 'Posted by'),
(589, 'en', 'grp_add_new_comment', 'Add New Comment'),
(590, 'en', 'grp_add_comment', 'Add Comment'),
(591, 'en', 'grp_pls_login_comment', 'Please Login To Post Comments'),
(592, 'en', 'grp_pls_join_comment', 'Please Join This Group To Post Comments'),
(593, 'en', 'usr_activation_title', 'User Activation'),
(594, 'en', 'usr_actiavation_msg', 'Enter Your Username and Activation Code that has been sent to your email.'),
(595, 'en', 'usr_actiavation_msg1', 'Request Activation Code'),
(596, 'en', 'usr_activation_code_tl', 'Activation Code'),
(597, 'en', 'usr_compose_msg', 'Compose Message'),
(598, 'en', 'usr_inbox_title', 'Inbox'),
(599, 'en', 'usr_sent_title', 'Sent'),
(600, 'en', 'usr_to_title', 'To: (Enter Username)'),
(601, 'en', 'usr_or_select_frm_list', 'or select from contact list'),
(602, 'en', 'usr_attach_video', 'Attach Video'),
(603, 'en', 'user_attached_video', 'Attached Video'),
(604, 'en', 'usr_send_message', 'Send Message'),
(605, 'en', 'user_no_message', 'No Message'),
(606, 'en', 'user_delete_message_msg', 'Delete This Message'),
(607, 'en', 'user_forgot_message', 'Forgot Username or Password?'),
(608, 'en', 'user_forgot_message_2', 'Dont Worry, recover it now'),
(609, 'en', 'user_pass_reset_msg', 'Password Reset'),
(610, 'en', 'user_pass_forgot_msg', 'if you have forgot your password, please enter you username and verification code in the box, and password reset instructions will be sent to your mail box.'),
(611, 'en', 'user_veri_code', 'Verification Code'),
(612, 'en', 'user_reocover_user', 'Recover Username'),
(613, 'en', 'user_user_forgot_msg', 'Forgot Username?'),
(614, 'en', 'user_recover', 'Recover'),
(615, 'en', 'user_reset', 'Reset'),
(616, 'en', 'user_inactive_msg', 'Your Account is Inactive Please Activate it , To Activate your account Please'),
(617, 'en', 'user_dashboard', 'Dash Board'),
(618, 'en', 'user_manage_prof_chnnl', 'Manage Profile &amp; Channel'),
(619, 'en', 'user_manage_friends', 'Manage Friends &amp; Contacts'),
(620, 'en', 'user_prof_channel', 'Profile/Channel'),
(621, 'en', 'user_message_box', 'Message Box'),
(622, 'en', 'user_new_messages', 'New Messages'),
(623, 'en', 'user_goto_inbox', 'Goto Inbox'),
(624, 'en', 'user_goto_sentbox', 'Goto Sent Box'),
(625, 'en', 'user_compose_new', 'Compose New Messages'),
(626, 'en', 'user_total_subs_users', 'Total Subscribed Users'),
(627, 'en', 'user_you_have', 'You Have'),
(628, 'en', 'user_fav_videos', 'Favourite Videos'),
(629, 'en', 'user_your_vids_watched', 'Your Videos Watched'),
(630, 'en', 'user_times', 'Times'),
(631, 'en', 'user_you_have_watched', 'You Have Watched'),
(632, 'en', 'user_channel_profiles', 'Channel &amp; Profile'),
(633, 'en', 'user_channel_views', 'Channel Views'),
(634, 'en', 'user_channel_comm', 'Channel Comments '),
(635, 'en', 'user_manage_prof', 'Manage Profile / Channel'),
(636, 'en', 'user_you_created', 'You Have Created'),
(637, 'en', 'user_you_joined', 'You Have Joined'),
(638, 'en', 'user_create_group', 'Create New Group'),
(639, 'en', 'user_manage_my_account', 'Manage My Account '),
(640, 'en', 'user_manage_my_videos', 'Manage My Videos'),
(641, 'en', 'user_manage_my_channel', 'Manage My Channe'),
(642, 'en', 'user_sent_box', 'Sent Box'),
(643, 'en', 'user_manage_channel', 'Manage Channel'),
(644, 'en', 'user_manage_my_contacts', 'Manage My Contacts'),
(645, 'en', 'user_manage_contacts', 'Manage Contacts'),
(646, 'en', 'user_manage_favourites', 'Manage Favourite Videos'),
(647, 'en', 'user_mem_login', 'Members Login'),
(648, 'en', 'user_already_have', 'Please Login Here if You Already have an account of'),
(649, 'en', 'user_forgot_username', 'Forgot Username'),
(650, 'en', 'user_forgot_password', 'Forgot Password'),
(651, 'en', 'user_create_your', 'Create Your '),
(652, 'en', 'user_all_fields_req', 'All Fields Are Required'),
(653, 'en', 'user_valid_email_addr', 'Valid Email Address'),
(654, 'en', 'user_allowed_format', 'Letters A-Z or a-z , Numbers 0-9 and Underscores _'),
(655, 'en', 'user_confirm_pass', 'Confirm Password'),
(656, 'en', 'user_reg_msg_0', 'Register as '),
(657, 'en', 'user_reg_msg_1', 'member, its free and easy just fill out the form below'),
(658, 'en', 'user_date_of_birth', 'Date Of Birth'),
(659, 'en', 'user_enter_text_as_img', 'Enter Text As Seen In The Image'),
(660, 'en', 'user_refresh_img', 'Refresh Image'),
(661, 'en', 'user_i_agree_to_the', 'I Agree to the'),
(662, 'en', 'user_thanks_for_reg', 'Thank You For Registering on '),
(663, 'en', 'user_email_has_sent', 'An email has been sent to your inbox containing Your Account'),
(664, 'en', 'user_and_activation', '&amp; Activation'),
(665, 'en', 'user_details_you_now', 'Details. You may now do the following things on our network'),
(666, 'en', 'user_upload_share_vds', 'Upload, Share Videos'),
(667, 'en', 'user_make_friends', 'Make Friends'),
(668, 'en', 'user_send_messages', 'Send Messages'),
(669, 'en', 'user_grow_your_network', 'Grow Your Networks by Inviting more Friends'),
(670, 'en', 'user_rate_comment', 'Rate and Comment Videos'),
(671, 'en', 'user_make_customize', 'Make and Customize Your Channel'),
(672, 'en', 'user_to_upload_vid', 'To Upload Video, You Need to Activate your account first, activation details has been sent to your email account, it may take sometimes to reach your inbox'),
(673, 'en', 'user_click_to_login', 'Click here To Login To Your Account'),
(674, 'en', 'user_view_my_channel', 'View My Channel'),
(675, 'en', 'user_change_pass', 'Change Password'),
(676, 'en', 'user_email_settings', 'Email Settings'),
(677, 'en', 'user_profile_settings', 'Profile Settings'),
(678, 'en', 'user_usr_prof_chnl_edit', 'User Profile &amp; Channel Edit'),
(679, 'en', 'user_personal_info', 'Personal Information'),
(680, 'en', 'user_fname', 'First Name'),
(681, 'en', 'user_lname', 'Last Name'),
(682, 'en', 'user_gender', 'Gender'),
(683, 'en', 'user_relat_status', 'Relationship Status'),
(684, 'en', 'user_display_age', 'Display Age'),
(685, 'en', 'user_about_me', 'About Me'),
(686, 'en', 'user_website_url', 'Website Url'),
(687, 'en', 'user_eg_website', 'e.g www.cafepixie.com'),
(688, 'en', 'user_prof_info', 'Professional Information'),
(689, 'en', 'user_education', 'Education'),
(690, 'en', 'user_school_colleges', 'Schools / Colleges'),
(691, 'en', 'user_occupations', 'Occupations'),
(692, 'en', 'user_companies', 'Companies'),
(693, 'en', 'user_sperate_by_commas', 'seperate with commas'),
(694, 'en', 'user_interests_hobbies', 'Interests and Hobbies'),
(695, 'en', 'user_fav_movs_shows', 'Favorite movies & shows'),
(696, 'en', 'user_fav_music', 'Favorite music'),
(697, 'en', 'user_fav_books', 'Favorite books'),
(698, 'en', 'user_user_avatar', 'User Avatar'),
(699, 'en', 'user_upload_avatar', 'Upload Avatar'),
(700, 'en', 'user_channel_info', 'Channel Info'),
(701, 'en', 'user_channel_title', 'Channel Title'),
(702, 'en', 'user_channel_description', 'Channel Description'),
(703, 'en', 'user_channel_permission', 'Channel Permissions'),
(704, 'en', 'user_allow_comments_msg', 'users can comments'),
(705, 'en', 'user_dallow_comments_msg', 'users cannot comments'),
(706, 'en', 'user_allow_rating', 'Allow Rating'),
(707, 'en', 'user_dallow_rating', 'Do Not Allow Rating'),
(708, 'en', 'user_allow_rating_msg1', 'users can rate'),
(709, 'en', 'user_dallow_rating_msg1', 'users cannot rate'),
(710, 'en', 'user_channel_feature_vid', 'Channel Featured Video'),
(711, 'en', 'user_select_vid_for_fr', 'Select Video To set as Featured'),
(712, 'en', 'user_chane_channel_bg', 'Change Channel Background'),
(713, 'en', 'user_remove_bg', 'Remove Background'),
(714, 'en', 'user_currently_you_d_have_pic', 'Currently You Don&#8217;t Have Background Picture'),
(715, 'en', 'user_change_email', 'Change Email'),
(716, 'en', 'user_email_address', 'Email Address'),
(717, 'en', 'user_new_email', 'New Email'),
(718, 'en', 'user_notify_me', 'Notify Me When User Sends Me A Message'),
(719, 'en', 'user_old_pass', 'Old Password'),
(720, 'en', 'user_new_pass', 'New Password'),
(721, 'en', 'user_c_new_pass', 'Confirm New Password'),
(722, 'en', 'user_doesnt_exist', 'User Doesn&#8217;t Exist'),
(723, 'en', 'user_do_not_have_contact', 'User Does Not Have Any Contact'),
(724, 'en', 'user_no_fav_video_exist', 'User Does Not Have Favourite Video'),
(725, 'en', 'user_have_no_vide', 'User has no videos'),
(726, 'en', 'user_s_channel', '%s’s Channel '),
(727, 'en', 'user_last_login', 'Last Login'),
(728, 'en', 'user_send_message', 'Send Message'),
(729, 'en', 'user_add_contact', 'Add Contact'),
(730, 'en', 'user_dob', 'Dob'),
(731, 'en', 'user_movies_shows', 'Movies &amp; Shows'),
(732, 'en', 'user_add_comment', 'Add Comment '),
(733, 'en', 'user_view_all_comments', 'View All Comments'),
(734, 'en', 'user_no_fr_video', 'User Has Not Selected Any Video To Set As Featured'),
(735, 'en', 'user_view_all_video_of', 'View All Videos of '),
(736, 'en', 'menu_home', 'Home'),
(737, 'en', 'menu_videos', 'Videos'),
(738, 'en', 'menu_upload', 'Upload'),
(739, 'en', 'menu_signup', 'SignUp'),
(740, 'en', 'menu_account', 'Account'),
(741, 'en', 'menu_groups', 'Groups'),
(742, 'en', 'menu_channels', 'Channels'),
(743, 'en', 'menu_community', 'Community'),
(744, 'en', 'menu_inbox', 'Inbox'),
(745, 'en', 'vdo_cat_err2', 'You cannot select more than %d categories'),
(746, 'en', 'user_subscribe_message', 'Hello %subscriber%\r\nYou Have Subscribed To %user% and therefore this message is sent to you automatically that %user% Has Uploaded New Video\r\n\r\n%website_title%'),
(747, 'en', 'user_subscribe_subject', '%user% has uploaded new video'),
(748, 'en', 'you_already_logged', 'You are already logged in'),
(749, 'en', 'You are not logged in', ''),
(750, 'en', 'you_not_logged_in', 'You are not logged in'),
(751, 'en', 'invalid_user', 'Invalid User\r\n'),
(752, 'va', 'value', 'value'),
(753, 'en', 'vdo_cat_err3', 'Please select atleast 1 category'),
(754, 'en', 'embed_code_invalid_err', 'Invalid video embed code'),
(755, 'en', 'invalid_duration', 'Invalid duration'),
(756, 'en', 'vid_thumb_changed', 'Video default thumb has been changed'),
(757, 'en', 'vid_thumb_change_err', 'Video thumbnail was not found'),
(758, 'en', 'upload_vid_thumbs_msg', 'All video thumbs have been uploaded'),
(759, 'en', 'video_thumb_delete_msg', 'Video thumb has been deleted'),
(760, 'en', 'video_thumb_delete_err', 'Could not delete video thumb'),
(761, 'en', 'no_comment_del_perm', 'You dont have permission to delete this comment'),
(764, 'en', '', ''),
(765, 'en', 'my_text_context', 'My test context'),
(766, 'en', 'user_contains_disallow_err', 'Username contains disallowed characters'),
(769, 'en', 'cust_field_err', 'Invalid ''%s'' field value'),
(770, 'en', 'add_cat_erro', 'Category already exists'),
(771, 'en', 'add_cat_no_name_err', 'Please enter name for category'),
(772, 'en', 'cat_default_err', 'Default cannot be deleted, please choose other category as &#8220;default&#8221; and then delete this'),
(773, 'en', 'pic_upload_vali_err', 'Please upload valid JPG, GIF or PNG image'),
(774, 'en', 'cat_dir_make_err', 'Unable to make category thumb directory'),
(775, 'en', 'cat_set_default_ok', 'Category has been set as default'),
(776, 'en', 'vid_thumb_removed_msg', 'Video thumbs have been removed'),
(777, 'en', 'vid_files_removed_msg', 'Video files have been removed'),
(778, 'en', 'vid_log_delete_msg', 'Video log has been deleted'),
(779, 'en', 'vdo_multi_del_erro', 'Videos has have been deleted'),
(780, 'en', 'add_fav_message', 'This %s has been added to your favorites'),
(781, 'en', 'obj_not_exists', '%s does not exist'),
(782, 'en', 'already_fav_message', 'This %s is already added to your favorites'),
(783, 'en', 'obj_report_msg', 'this %s has been reported'),
(784, 'en', 'obj_report_err', 'You have already reported this %s'),
(785, 'en', 'user_no_exist_wid_username', '&#8216;%s&#8217; does not exist'),
(786, 'en', 'share_video_no_user_err', 'Please enter usernames or emails to send this %s'),
(787, 'en', 'uploaded', 'Uploaded'),
(788, 'en', 'today', 'Today'),
(789, 'en', 'yesterday', 'Yesterday'),
(790, 'en', 'thisweek', 'This Week'),
(791, 'en', 'lastweek', 'Last Week'),
(792, 'en', 'thismonth', 'This Month'),
(793, 'en', 'lastmonth', 'Last Month'),
(794, 'en', 'thisyear', 'This Year'),
(795, 'en', 'lastyear', 'Last Year'),
(796, 'en', 'favorites', 'Favorites'),
(797, 'en', 'alltime', 'All Time'),
(798, 'en', 'insufficient_privileges_loggin', 'You cannot access this page Click Here to <a href="%s">Login</a> or <a href="%s">Register</a>'),
(799, 'en', 'profile_title', 'Profile Title'),
(800, 'en', 'show_dob', 'Show Date of birth'),
(801, 'en', 'profile_tags', 'Profile Tags'),
(802, 'en', 'profile_desc', 'Profile description'),
(803, 'en', 'online_status', 'User status'),
(804, 'en', 'show_profile', 'Show profile'),
(805, 'en', 'allow_ratings', 'Allow ratings on profile'),
(806, 'en', 'postal_code', 'Postal code'),
(807, 'en', 'temp_file_load_err', 'Unable to load tempalte file &#8216;%s&#8217; in directory &#8216;%s&#8217;'),
(808, 'en', 'no_date_provided', 'No date provided'),
(809, 'en', 'second', 'second'),
(810, 'en', 'minute', 'minute'),
(811, 'en', 'bad_date', 'Bad date'),
(812, 'en', 'users_videos', '%s&#8217;s videos'),
(813, 'en', 'please_login_subscribe', 'Please login to subsribe %s'),
(814, 'en', 'users_subscribers', '%s&#8217;s subscribers'),
(815, 'en', 'user_no_subscribers', '%s has no subsribers'),
(816, 'en', 'user_subscriptions', '%s&#8217;s subscriptions'),
(817, 'en', 'user_no_subscriptions', '%s has no subscriptions'),
(818, 'en', 'usr_avatar_bg_update', 'User avatar and background have been updated'),
(819, 'en', 'user_email_confirm_email_err', 'Confirm email mismatched'),
(820, 'en', 'email_change_msg', 'Email has been changed successfullyrnrn'),
(821, 'en', 'no_edit_video', 'You cannot edit this video'),
(822, 'en', 'confirm_del_video', 'Are you sure you want to delete this video ?'),
(823, 'en', 'remove_fav_video_confirm', 'Are you sure you want to remove this video from your favorites ?'),
(824, 'en', 'fav_remove_msg', '%s has been removed from your favorites'),
(825, 'en', 'unknown_favorite', 'Unknown favorite %s'),
(826, 'en', 'vdo_multi_del_fav_msg', 'Videos have been removed from your favorites'),
(827, 'en', 'unknown_sender', 'Unknown Sender'),
(828, 'en', 'please_enter_message', 'Please enter something for message'),
(829, 'en', 'unknown_reciever', 'Unknown reciever'),
(830, 'en', 'no_pm_exist', 'Private message does not exist'),
(831, 'en', 'pm_sent_success', 'Private message has been sent successfully'),
(832, 'en', 'msg_delete_inbox', 'Message has been deleted from inbox'),
(833, 'en', 'msg_delete_outbox', 'Message has been deleted from your outbox'),
(834, 'en', 'private_messags_deleted', 'Private messages have been deleted'),
(835, 'en', 'ban_users', 'Ban users'),
(836, 'en', 'spe_users_by_comma', 'separate usernames by comma'),
(837, 'en', 'user_ban_msg', 'Users have been banned successfully'),
(838, 'en', 'no_user_ban_msg', 'No user is banned from your account!');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int(10) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(25) NOT NULL,
  `player_file` varchar(30) NOT NULL,
  `template_id` varchar(15) NOT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `players`
--


-- --------------------------------------------------------

--
-- Table structure for table `player_skins`
--

CREATE TABLE IF NOT EXISTS `player_skins` (
  `player_skins_id` int(20) NOT NULL AUTO_INCREMENT,
  `player_id` int(20) NOT NULL,
  `player_skin_name` varchar(60) CHARACTER SET latin1 NOT NULL,
  `player_skin_file` text CHARACTER SET latin1 NOT NULL,
  `player_skin_type` enum('name','file') CHARACTER SET latin1 NOT NULL DEFAULT 'file',
  PRIMARY KEY (`player_skins_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `player_skins`
--


-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `plugin_id` int(255) NOT NULL AUTO_INCREMENT,
  `plugin_file` text NOT NULL,
  `plugin_folder` text NOT NULL,
  `plugin_version` float NOT NULL,
  `plugin_license_type` varchar(10) NOT NULL DEFAULT 'GPL',
  `plugin_license_key` varchar(5) NOT NULL,
  `plugin_license_code` text NOT NULL,
  `plugin_active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`plugin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `plugins`
--


-- --------------------------------------------------------

--
-- Table structure for table `plugin_config`
--

CREATE TABLE IF NOT EXISTS `plugin_config` (
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
-- Dumping data for table `plugin_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_user` int(11) NOT NULL,
  `session_string` varchar(60) NOT NULL,
  `session_value` varchar(32) NOT NULL,
  `session_date` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `stat_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `value` varchar(60) NOT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `stats`
--


-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `subscription_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `subscribed_to` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `subscriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `template_id` int(20) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(25) NOT NULL,
  `template_dir` varchar(30) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`template_id`, `template_name`, `template_dir`) VALUES
(1, 'ClipBucket Blue', 'clipbucketblue'),
(4, 'YouTube', 'youtube'),
(5, 'ClipBucket Black', 'clipbucketblack');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `featured_video` mediumtext NOT NULL,
  `username` text NOT NULL,
  `user_session_key` varchar(32) NOT NULL,
  `user_session_code` int(5) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `usr_status` enum('Ok','ToActivate','Locked','Deleted') NOT NULL DEFAULT 'ToActivate',
  `msg_notify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar` varchar(225) NOT NULL DEFAULT 'no_avatar.jpg',
  `avatar_url` text NOT NULL,
  `sex` enum('male','female') NOT NULL DEFAULT 'male',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `country` varchar(20) NOT NULL DEFAULT 'PK',
  `level` int(6) NOT NULL DEFAULT '4',
  `avcode` mediumtext NOT NULL,
  `doj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_visits` bigint(20) NOT NULL DEFAULT '0',
  `session` varchar(32) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `signup_ip` varchar(15) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `featured` enum('No','Yes') NOT NULL DEFAULT 'No',
  `profile_hits` bigint(20) DEFAULT '0',
  `total_watched` bigint(20) NOT NULL DEFAULT '0',
  `total_videos` bigint(20) NOT NULL,
  `total_comments` bigint(20) NOT NULL,
  `ban_status` enum('yes','no') NOT NULL DEFAULT 'no',
  `upload` varchar(20) NOT NULL DEFAULT '1',
  `subscribers` varchar(25) NOT NULL DEFAULT '0',
  `background` mediumtext NOT NULL,
  `background_color` varchar(25) NOT NULL,
  `background_url` text NOT NULL,
  `background_repeat` enum('no-repeat','repeat','repeat-x','repeat-y') NOT NULL DEFAULT 'repeat',
  `total_groups` bigint(20) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rating` bigint(25) NOT NULL,
  `rated_by` text NOT NULL,
  `banned_users` text NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `ind_status_doj` (`doj`),
  KEY `ind_status_id` (`userid`),
  KEY `ind_hits_doj` (`profile_hits`,`doj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `featured_video`, `username`, `user_session_key`, `user_session_code`, `password`, `email`, `usr_status`, `msg_notify`, `avatar`, `avatar_url`, `sex`, `dob`, `country`, `level`, `avcode`, `doj`, `last_logged`, `num_visits`, `session`, `ip`, `signup_ip`, `time_zone`, `featured`, `profile_hits`, `total_watched`, `total_videos`, `total_comments`, `ban_status`, `upload`, `subscribers`, `background`, `background_color`, `background_url`, `background_repeat`, `total_groups`, `last_active`, `rating`, `rated_by`, `banned_users`) VALUES
(1, '', 'admin', '777750fea4d3bd585bf47dc1873619fc', 10192, '38d8e594a1ddbd29fdba0de385d4fefa', 'arslan@clip-bucket.com', 'Ok', 'yes', '', '', 'male', '1989-10-14', 'PK', 1, '', '2008-11-23 16:12:43', '2009-10-28 11:12:42', 95, 'pub6e7fq5oj76vakuov2j03hm1', 'localhost', '', 0, 'No', 723132, 248, 52121, 1122, 'no', '1', '121', '1.jpg', '#53baff', '', 'repeat', 3, '2009-11-04 09:11:22', 10, '212', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

CREATE TABLE IF NOT EXISTS `user_levels` (
  `user_level_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_level_active` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  `user_level_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `user_level_is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`user_level_id`, `user_level_active`, `user_level_name`, `user_level_is_default`) VALUES
(4, 'yes', 'Guest', 'yes'),
(2, 'yes', 'Registered User', 'yes'),
(3, 'yes', 'Inactive User', 'yes'),
(1, 'yes', 'Administrator', 'yes'),
(5, 'yes', 'Global Moderator', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_levels_permissions`
--

CREATE TABLE IF NOT EXISTS `user_levels_permissions` (
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
  PRIMARY KEY (`user_level_permission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user_levels_permissions`
--

INSERT INTO `user_levels_permissions` (`user_level_permission_id`, `user_level_id`, `admin_access`, `allow_video_upload`, `view_video`, `view_channel`, `view_group`, `view_videos`, `avatar_upload`, `video_moderation`, `member_moderation`) VALUES
(5, 5, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no'),
(2, 2, 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no'),
(3, 3, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no'),
(1, 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(4, 4, 'no', 'no', 'no', 'no', 'yes', 'yes', 'no', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE IF NOT EXISTS `user_permissions` (
  `permission_id` int(225) NOT NULL AUTO_INCREMENT,
  `permission_type` int(225) NOT NULL,
  `permission_name` varchar(225) NOT NULL,
  `permission_code` varchar(225) NOT NULL,
  `permission_desc` mediumtext NOT NULL,
  `permission_default` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES
(12, 3, 'Admin Access', 'admin_access', 'User can access admin panel', 'no'),
(13, 1, 'View Video', 'view_video', 'User can view videos', 'yes'),
(11, 2, 'Allow Video Upload', 'allow_video_upload', 'Allow user to upload videos', 'yes'),
(14, 1, 'View Channel', 'view_channel', 'User Can View Channels', 'yes'),
(15, 1, 'View Group', 'view_group', 'User Can View Groups', 'yes'),
(16, 1, 'View Videos Page', 'view_videos', 'User Can view videos page', 'yes'),
(17, 2, 'Allow Avatar Upload', 'avatar_upload', 'User can upload video', 'yes'),
(19, 3, 'Video Moderation', 'video_moderation', 'User Can Moderate Videos', 'no'),
(20, 3, 'Member Moderation', 'member_moderation', 'User Can Moderate Members', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `user_permission_types`
--

CREATE TABLE IF NOT EXISTS `user_permission_types` (
  `user_permission_type_id` int(225) NOT NULL AUTO_INCREMENT,
  `user_permission_type_name` varchar(225) NOT NULL,
  `user_permission_type_desc` mediumtext NOT NULL,
  PRIMARY KEY (`user_permission_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_permission_types`
--

INSERT INTO `user_permission_types` (`user_permission_type_id`, `user_permission_type_name`, `user_permission_type_desc`) VALUES
(1, 'Viewing Permission', ''),
(2, 'Uploading Permission', ''),
(3, 'Administrator Permission', ''),
(4, 'General Permission', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`user_profile_id`, `userid`, `profile_title`, `profile_desc`, `featured_video`, `first_name`, `last_name`, `avatar`, `show_dob`, `postal_code`, `time_zone`, `profile_tags`, `web_url`, `hometown`, `city`, `online_status`, `show_profile`, `allow_comments`, `allow_ratings`, `content_filter`, `icon_id`, `browse_criteria`, `about_me`, `education`, `schools`, `occupation`, `companies`, `relation_status`, `hobbies`, `fav_movies`, `fav_music`, `fav_books`, `background`, `profile_video`) VALUES
(8, 1, 'This is my profile title', 'test\r\n\r\n', '', 'Arslan Hassan', 'Hassan', 'no_avatar.jpg', 'yes', '51310', 0, 'arslan, hassan, great, developer', 'clip-bucket.com', 'Sialkot', 'Sialkot', 'offline', 'members', 'Yes', 'Yes', 'Nothing', 0, NULL, '', 'no ', 'AIPS, ma own school and no more', 'occupation', 'PHPBUcket, Webex etc etc', 'Single', 'Nothing special...', 'One piece', 'any thing , depends on my mood', 'Holy Quran and ebooks', '', 27),
(9, 10, '', '', '', 'test', 'asdasd', 'no_avatar.jpg', 'no', '', 0, '', '', '', '', 'online', 'all', 'Yes', 'Yes', 'Nothing', 0, NULL, '', 'no ', '', '', '', 'Single', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `validation_re`
--

CREATE TABLE IF NOT EXISTS `validation_re` (
  `re_id` int(25) NOT NULL AUTO_INCREMENT,
  `re_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `re_code` varchar(60) CHARACTER SET utf8 NOT NULL,
  `re_syntax` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`re_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `validation_re`
--

INSERT INTO `validation_re` (`re_id`, `re_name`, `re_code`, `re_syntax`) VALUES
(1, 'Username', 'username', '^^[a-zA-Z0-9_]+$'),
(2, 'Email', 'email', '^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,10})$'),
(3, 'Username', 'username', '^^[a-zA-Z0-9 ]+$');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `videoid` bigint(20) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `username` text NOT NULL,
  `userid` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
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
  `status` enum('Successful','Processing') NOT NULL DEFAULT 'Processing',
  `flv_file_url` text,
  `default_thumb` int(3) NOT NULL DEFAULT '1',
  `embed_code` text NOT NULL,
  PRIMARY KEY (`videoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video`
--


-- --------------------------------------------------------

--
-- Table structure for table `video_categories`
--

CREATE TABLE IF NOT EXISTS `video_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `video_favourites`
--

CREATE TABLE IF NOT EXISTS `video_favourites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `videoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video_favourites`
--


-- --------------------------------------------------------

--
-- Table structure for table `video_files`
--

CREATE TABLE IF NOT EXISTS `video_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL,
  `file_conversion_log` text NOT NULL,
  `encoder` char(16) NOT NULL,
  `command_used` text NOT NULL,
  `src_path` text NOT NULL,
  `src_name` char(64) NOT NULL,
  `src_ext` char(8) NOT NULL,
  `src_format` char(32) NOT NULL,
  `src_duration` char(10) NOT NULL,
  `src_size` char(10) NOT NULL,
  `src_bitrate` char(6) NOT NULL,
  `src_video_width` char(5) NOT NULL,
  `src_video_height` char(5) NOT NULL,
  `src_video_wh_ratio` char(10) NOT NULL,
  `src_video_codec` char(16) NOT NULL,
  `src_video_rate` char(10) NOT NULL,
  `src_video_bitrate` char(10) NOT NULL,
  `src_video_color` char(16) NOT NULL,
  `src_audio_codec` char(16) NOT NULL,
  `src_audio_bitrate` char(10) NOT NULL,
  `src_audio_rate` char(10) NOT NULL,
  `src_audio_channels` char(16) NOT NULL,
  `output_path` text NOT NULL,
  `output_format` char(32) NOT NULL,
  `output_duration` char(10) NOT NULL,
  `output_size` char(10) NOT NULL,
  `output_bitrate` char(6) NOT NULL,
  `output_video_width` char(5) NOT NULL,
  `output_video_height` char(5) NOT NULL,
  `output_video_wh_ratio` char(10) NOT NULL,
  `output_video_codec` char(16) NOT NULL,
  `output_video_rate` char(10) NOT NULL,
  `output_video_bitrate` char(10) NOT NULL,
  `output_video_color` char(16) NOT NULL,
  `output_audio_codec` char(16) NOT NULL,
  `output_audio_bitrate` char(10) NOT NULL,
  `output_audio_rate` char(10) NOT NULL,
  `output_audio_channels` char(16) NOT NULL,
  `hd` enum('yes','no') NOT NULL DEFAULT 'no',
  `hq` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `src_bitrate` (`src_bitrate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `video_files`
--
