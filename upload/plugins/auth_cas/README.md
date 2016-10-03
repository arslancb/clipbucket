#Plugin ClipBucket - Central Authentication Service

# Installation
Activer le plugin depuis la rubrique plugin de l'administration.

# Configuration
Depuis l'administration du site, renseigner les informations requises (serveur, port, etc...).

# Utilisation
Dans votre template (styles/<nom_du_template>/layout/signup.html), ajouter le code suivant :{ANCHOR place="is_auth_cas"}
Si besoin d'une personallisation plus forte du lien d'authentification : ajouter {ANCHOR place="is_auth_cas" data="true"}. Ceci retourne uniquement l'url de connexion SSO-CAS que l'on peu placer dans un lien HTML customisé.
