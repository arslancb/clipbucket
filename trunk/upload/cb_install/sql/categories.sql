INSERT INTO `{tbl_prefix}collection_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 0, 'Uncategorized', now(), 0, 'yes');


INSERT INTO `{tbl_prefix}group_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 1, 'Uncategorized',  now(), '', 'yes');


INSERT INTO `{tbl_prefix}user_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Basic User', 1, '',  now(), '', 'yes'),
(2, 'Gurus', 1, '',  now(), '', 'no'),
(3, 'Comedian', 1, '',  now(), '', 'no');


INSERT INTO `{tbl_prefix}video_categories` (`category_id`, `parent_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 0, 'Uncategorized', 1, '',  now(), '', 'yes');