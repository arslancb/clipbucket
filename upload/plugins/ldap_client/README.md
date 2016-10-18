#Plugin ClipBucket - LDAP Client

## Requirement - *Minimum requis*
In order to use this plugin, you have to install and enable the "php5-ldap" module. (Keep in touch to reload apache after installation).

__Be careful__, the Common Library plugin must be firstly installed and activated on the ClipBucket platform.

*Le module ldap doit être installé et activé. Sur un système debian (ou variante), installer le paquet "php5-ldap" (penser à relancer apache après l'installation).

__Attention__, le plugin Common Library doit d'abord être installé et activé sur la plateforme Clipbucket.
*

## Installation
Go to the plugin administration panel and install the "LDAP Client" plugin. The translation were not updated immeditaly. You have to reload another page.

*Activer le plugin "LDAP Client" depuis la rubrique plugin de l'administration. Les traductions ne sont pas chargées immédiatement. Vous devez charger une page.*

## Usage
An item is added in administration dashbord menu. Go to the "Stats and configurations", then "LDAP configuration".

*Une entrée est ajoutée dans le menu. Rendez-vous dans la rubrique "Stats and configurations", puis "Configuration LDAP".*

## Configuration

### Connexion
From admin panel, save the required informations (server, port, ...).

*Depuis l'administration du site, renseigner les informations requises (serveur, port, basedn, etc...).*

### Data correlation - *Correspondance des données*
This system make a correlation between the LDAP attributes and the ClipBucket database model. It populates the corresponding table on the ClipBucket system ("user_profile"). The "mail" attribute of LDAP is always integrated by default. This fields will be saved when an account is going to be created.

Click on th "+" button in order to add item.

*Renseigner une ou plusieurs correspondance d'attribut LDAP et de champs de la table ClipBucket "user_profile". L'attribut "mail" est toujours ajouté par défaut.
Ces champs seront rapatriés lors de la création d'un compte.*

*Cliquer sur le bouton "+" pour ajouter des champs de saisie.*

### Test
Test tab let the root manager to test the system configuration.

*Permet à l'administrateur de tester la configuration.*
