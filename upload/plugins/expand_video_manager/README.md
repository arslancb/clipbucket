#Plugin ClipBucket - Expand Video Manager

## Installation
Go to the plugin administration panel and install the "Expand Video Manager".

*Activer le plugin "Expand Video Manager" depuis la rubrique plugin de l'administration.*

## Usage
* for developpers
  You have two things to be done when you develop your plugin :
  1. Generate with `echo $cbtpl->fetch(file_path);` instead of the original function `template_file()`
  2. Install (and uninstall) process must insert (or delete) a row in the expand_video_manager sql table.
    * For example in the Waveform plugin install file : 
    `INSERT INTO '.tbl("expand_video_manager").' (`evm_id`, `evm_plugin_url`, `evm_zone`, `evm_is_new_tab`, `evm_tab_title`) VALUES (\'\', \'/var/www/html/cb_uhdf/upload/plugins/waveform/admin/mk_and_show_waveform.php\', \'expand_video_manager_right_panel\', 1, \'Waveform\');`
  3. Write your plugin functionnality (see waveform plugin as demo example) !

* for users
  1. Go to the admin area > Videos > Video Manager. Select a video to edit by clicking to the link. Search for a tab with a name similar to the plugin.

*pour les développeurs*
  *Vous devez penser à faire deux choses lorsque vous développez votre plugin :*
  *1. Générer le template avec `echo $cbtpl->fetch(file_path);` plutôt que la fonction originale `template_file()`*
  *2. La procédure d'installation (et de désinstallation) doit insérer une ligne dans la table SQL expand_video_manager.
    Par exemple dans le plugin Waveform (fichier d'installation) : 
    `INSERT INTO '.tbl("expand_video_manager").' (`evm_id`, `evm_plugin_url`, `evm_zone`, `evm_is_new_tab`, `evm_tab_title`) VALUES (\'\', \'/var/www/html/cb_uhdf/upload/plugins/waveform/admin/mk_and_show_waveform.php\', \'expand_video_manager_right_panel\', 1, \'Waveform\');`
  3. Ecrire les fonctions du plugin (voir le plugin waveform pour exemple) !*

*pour les utilisateurs*
  *1. Aller dans l'admin area > Videos > Video Manager. Choisir une video a éditer en cliquant sur le lien. Chercher un onglet qui porte un nom similaire au plugin.*
