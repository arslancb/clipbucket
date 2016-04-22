
INSERT INTO `{tbl_prefix}collection_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 0, 'Uncategorized', '2010-12-29 19:21:47', 0, 'yes');

--
-- Dumping data for table `group_categories`
--

INSERT INTO `{tbl_prefix}group_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 1, 'Uncategorized', '2010-01-14 06:26:47', '', 'yes');

--
-- Dumping data for table `user_categories`
--

INSERT INTO `{tbl_prefix}user_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Basic User', 1, '', '2009-12-03 12:18:15', '', 'yes'),
(2, 'Gurus', 1, '', '2009-12-03 12:18:21', '', 'no'),
(3, 'Comedian', 1, '', '2009-12-03 12:18:25', '', 'no');

--
-- Dumping data for table `video_categories`
--

INSERT INTO `{tbl_prefix}video_categories` (`category_id`, `parent_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(12, 0, 'Uncategorized', 1, '', '2011-02-12 16:40:32', '', 'yes');