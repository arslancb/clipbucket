-- update template adn player query on cb update
UPDATE `{tbl_prefix}config` SET value = 'cb_27' WHERE name = 'template_dir';
UPDATE `{tbl_prefix}config` SET value = 'html5_player.php' WHERE name = 'player_file';