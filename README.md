# Application de Location de Voitures (PHP MVC)

## Description

Ce projet est une application web de location de voitures construite en PHP avec une architecture MVC (Modele - Vue - Controleur).

L'application permet notamment:

- la consultation des voitures disponibles,
- la gestion des reservations,
- l'authentification utilisateur (client/admin),
- la gestion du profil,
- la gestion des paiements,
- l'administration du catalogue de voitures.

Le dossier web actuellement utilise est `site_fix-2`.

## Fonctionnement global

### 1. Point d'entree

Le front controller est `site_fix-2/index.php`.

Il:

- demarre la session,
- charge la configuration,
- lit les parametres `controller` et `action` depuis l'URL,
- instancie dynamiquement le controleur cible,
- execute l'action demandee,
- gere les erreurs via un `try/catch` et redirection.

Exemple d'URL:

- `index.php?controller=voiture&action=liste`
- `index.php?controller=reservation&action=creer&id=101`

### 2. Controleurs

Les controleurs se trouvent dans `site_fix-2/controleur`.

Ils recoivent la requete HTTP, effectuent les verifications d'acces/validation, appellent les modeles puis chargent les vues.

Controleurs principaux:

- `AccueilControlleur.php`
- `VoitureControlleur.php`
- `ReservationControlleur.php`
- `PaiementControlleur.php`
- `UtilisateurControlleur.php`

### 3. Modeles

Les modeles se trouvent dans `site_fix-2/modele`.

Ils contiennent la logique metier et l'acces aux donnees (PDO/MySQL), par exemple:

- `Database.php` (connexion base de donnees),
- `Voiture.php`, `Reservation.php`, `Paiement.php`,
- `Utilisateur.php`, `Categorie.php`.

### 4. Vues

Les vues se trouvent dans `site_fix-2/vue`.

Elles affichent les pages HTML, en s'appuyant sur des templates partages:

- `header.php`
- `footer.php`

Les pages metier incluent par exemple:

- `accueil.php`
- `voiture.liste.php`, `voiture.detail.php`
- `resa.*.php`
- `paiement.*.php`
- `login.php`, `register.php`, `profil*.php`

### 5. Assets

- Feuille de style principale: `site_fix-2/css/style.css`
- Images voitures: `site_fix-2/assets/photos/voitures`

## Architecture des dossiers

Structure utile du depot:

```text
ap-mvc_locauto/
  README.md
  tests/
    run_all_tests.php
    TestCategorie.php
    TestReservation.php
    TestUtilisateur.php
    TestVoiture.php
  site_fix-2/
    index.php
    config.php
    controleur/
      AccueilControlleur.php
      PaiementControlleur.php
      ReservationControlleur.php
      UtilisateurControlleur.php
      VoitureControlleur.php
    modele/
      Database.php
      Categorie.php
      Paiement.php
      Reservation.php
      Utilisateur.php
      Voiture.php
    vue/
      header.php
      footer.php
      accueil.php
      voiture.*.php
      resa.*.php
      paiement.*.php
      login.php
      register.php
      profil*.php
    css/
      style.css
    assets/
      photos/
        voitures/
```

## Configuration

Le fichier de configuration principal est `site_fix-2/config.php`.

Parametres principaux:

- base de donnees: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`
- application: `APP_NAME`, `APP_URL`, `APP_VERSION`
- debug: `DEBUG_MODE`

Adapter ces valeurs a votre environnement local (MySQL/MariaDB).

## Lancer le projet en local (Herd)

Depuis la racine du depot:

```bash
cd /Users/Yousstape/Documents/locauto/ap-mvc_locauto/site_fix-2
herd unlink locauto || true
herd link locauto
herd restart
```

Puis ouvrir:

- `http://locauto.test`

## Base de donnees

Le schema SQL est present dans le dossier `base de données`.

Importer le script SQL dans votre serveur local, puis verifier que les constantes de `site_fix-2/config.php` correspondent a votre configuration.

## Tests

Les tests sont dans le dossier `tests`.

Executer tous les tests:

```bash
php tests/run_all_tests.php
```

Le script affiche un resume global (tests passes/echoues) et retourne un code de sortie non nul en cas d'erreur.

## Notes

- Le routage est base sur les parametres `controller`/`action`.
- Le projet suit une architecture MVC simple, sans framework externe.
- La separation des couches facilite la maintenance et l'ajout de nouvelles fonctionnalites.
