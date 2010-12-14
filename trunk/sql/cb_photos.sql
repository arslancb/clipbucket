-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 06, 2010 at 07:46 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `clipbucket_svn`
--

-- --------------------------------------------------------

--
-- Table structure for table `cb_photos`
--

CREATE TABLE IF NOT EXISTS `cb_photos` (
  `photo_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `photo_title` mediumtext NOT NULL,
  `photo_description` mediumtext NOT NULL,
  `photo_tags` mediumtext NOT NULL,
  `userid` int(255) NOT NULL,
  `collection_id` int(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_viewed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `views` bigint(255) NOT NULL,
  `allow_comments` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_embedding` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_tagging` enum('yes','no') NOT NULL DEFAULT 'yes',
  `featured` enum('yes','no') NOT NULL DEFAULT 'no',
  `reported` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` int(255) NOT NULL,
  `total_favorites` int(255) NOT NULL,
  `rating` int(15) NOT NULL,
  `rated_by` int(25) NOT NULL,
  `voters` mediumtext NOT NULL,
  `filename` varchar(100) NOT NULL,
  `ext` char(5) NOT NULL,
  `downloaded` bigint(255) NOT NULL,
  `owner_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cb_photos`
--

/* ADDING BROADCAST AND ACTIVE */
ALTER TABLE  `cb_photos` ADD  `broadcast` ENUM(  'public',  'private' ) NOT NULL DEFAULT  'public' AFTER  `allow_rating` ,
ADD  `active` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes' AFTER  `broadcast`

/* ADDING PHOTO KEY */
ALTER TABLE  `cb_photos` ADD  `photo_key` MEDIUMTEXT NOT NULL AFTER  `photo_id`

/* ADDING PHOTO CONFIG */
INSERT INTO `cb_config` (`configid`, `name`, `value`) VALUES (NULL, 'photo_ratio', '16:10'), (NULL, 'photo_thumb_width', '120'), (NULL, 'photo_thumb_height', '75'), (NULL, 'photo_med_width', '185'), (NULL, 'photo_med_height', '116'), (NULL, 'photo_lar_width', '600'), (NULL, 'photo_crop', '1'), (NULL, 'photo_multi_upload', '5'), (NULL, 'photo_download', '1'), (NULL, 'photo_comments', '1'), (NULL, 'photo_rating', '1');

INSERT INTO `cb_config` (`configid`, `name`, `value`) VALUES (NULL, 'max_photo_size', '2');

/* ADDING WATERMARK CONFIG */
INSERT INTO `clipbucket_svn`.`cb_config` (`configid`, `name`, `value`) VALUES (NULL, 'watermark_photo', '0'), (NULL, 'watermark_max_width', '120'), (NULL,'watermark_placement','left:top');

/* SETTING COLLECTION FEATURED DEFAULT TO 'NO' */
ALTER TABLE  `cb_collections` CHANGE  `featured`  `featured` VARCHAR( 4 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  'no';

/* SHARE TEMPLATE */
INSERT INTO `cb_email_templates` (`email_template_id`, `email_template_name`, `email_template_code`, `email_template_subject`, `email_template`, `email_template_allowed_tags`) VALUES (NULL, 'Photo Share Template', 'photo_share_template', '{username} wants to share photo with you', '<html>
<head>
<style type="text/css">
<!--
.title {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #FFFFFF;
	font-size: 16px;
}
.title2 {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #000000;
	font-size: 14px;
}
.messege {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #000000;
	font-size: 12px;
}
#videoThumb{
	float:left;
	padding: 2px;
	margin: 3px;
	border: 1px solid #F0F0F0;
	text-align: center;
	vertical-align: middle;
}
#videoThumb img{border:0px}
body,td,th {
	font-family: tahoma;
	font-size: 11px;
	color: #FFFFFF;
}
.text {
	font-family: tahoma;
	font-size: 11px;
	color: #000000;
	padding: 5px;
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td bgcolor="#0099cc" ><span class="title">{website_title}</span></td>
  </tr>

  <tr>
    <td height="20" class="messege">{username} wants to share this photo with you<br>
      <div id="videoThumb"><a class="text" title="{photo_title}" href="{photo_link}"><img src="{photo_thumb}"><br>
    View Photo</a></div></td>
  </tr>
  <tr>
    <td class="text" ><span class="title2">Photo Description</span><br>
      <span class="text">{photo_description}</span></td>
  </tr>
  <tr>
    <td><span class="title2">Personal Message</span><br>
      <span class="text">{user_message}
      </span><br>
      <br>
<span class="text">Thanks,</span><br> 
<span class="text">{website_title}</span></td>
  </tr>
  <tr>
    <td bgcolor="#0099cc">copyrights {date_year} {website_title}</td>
  </tr>
</table>
</body>
</html>', '');