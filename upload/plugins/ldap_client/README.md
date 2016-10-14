#Plugin ClipBucket - LDAP Client

## Requirement 
Le module ldap doit être installé et activé. Sur un système debian (ou variante), installer le paquet php5-ldap (penser à relancer apache après l'installation).

## Installation
Activer le plugin depuis la rubrique plugin de l'administration.

## Configuration

### Connexion
Depuis l'administration du site, renseigner les informations requises (serveur, port, basedn, etc...).

### Correspondance des données
Renseigner une ou plusieurs correspondance d'attribut LDAP et de champs de la table user_profile. L'attribut "mail" est toujours ajouté par défaut.
C'est champs seront enregistrer lors de la création d'un compte.

Cliquer sur le bouton "+" pour ajouter des champs de saisie.

### Test
Permet de tester la configuration.
