# 🚗 Location Auto - Application PHP MVC

Une application web professionnelle de location de voitures de luxe, construite avec une architecture MVC pure (Model-View-Controller) en PHP vanille.

## 📋 Table des matières

- [Présentation du projet](#présentation-du-projet)
- [Fonctionnalités](#fonctionnalités)
- [Langages et outils](#langages-et-outils)
- [Architecture MVC](#architecture-mvc)
- [Installation et configuration](#installation-et-configuration)
- [Lancer l'application](#lancer-lapplication)
- [Base de données](#base-de-données)
- [Tests unitaires](#tests-unitaires)
- [Flux de l'application](#flux-de-lapplication)
- [Guide développeur](#guide-développeur)

---

## Présentation du projet

**Location Auto** est une plateforme de location de voitures haut de gamme qui permet:

- **Clients**: Consulter un catalogue de voitures, effectuer des réservations, gérer leur profil, consulter l'historique des locations et paiements
- **Administrateurs**: Gérer le catalogue de voitures (ajout/modification/suppression), consulter les réservations, superviser les paiements

L'application est conçue selon les principes SOLID et suit l'architecture MVC pour une séparation claire des responsabilités, facilitant la maintenabilité et l'évolution.

**État**: Entièrement fonctionnelle avec suite de tests (31 tests unitaires passants)

---

## Fonctionnalités

### Authentification et Profil

- ✅ Inscription client (validation email, sécurité mot de passe avec bcrypt)
- ✅ Connexion/Déconnexion sécurisée
- ✅ Gestion profil (modification données personnelles)
- ✅ Suppression de compte avec confirmation
- ✅ Rôles utilisateur (client / admin)

### Catalogue et Réservations

- ✅ Affichage dynamique des voitures disponibles
- ✅ Detail détaillé d'une voiture (spécifications, catégories)
- ✅ Réservation avec sélection de dates
- ✅ Vérification des disponibilités et chevauchements
- ✅ Gestion des statuts de réservation (confirmée, annulée, en attente)
- ✅ Catégorisation des voitures (SUV, Luxe, Sport, Économique, Familial)

### Paiements

- ✅ Calcul automatique du montant (nombre de jours × prix/jour)
- ✅ Gestion des modes de paiement
- ✅ Suivi du statut (payé, en attente, échoué)
- ✅ Historique des transactions completes
- ✅ Conformation de paiement avec détails

### Administration

- ✅ Ajout/modification/suppression de voitures
- ✅ Gestion des catégories associées
- ✅ Upload d'images pour les voitures
- ✅ Vue d'ensemble des réservations
- ✅ Statuts de détail pour chaque réservation

---

## Langages et outils

### Backend

| Langage/Framework | Version    | Utilisation                                       |
| ----------------- | ---------- | ------------------------------------------------- |
| **PHP**           | 8.4+       | Langage serveur principal                         |
| **MySQL/MariaDB** | 5.7+       | Base de données relationnelle                     |
| **PDO**           | Native PHP | Abstraction base de données (prepared statements) |

### Frontend

| Technology     | Utilisation                                   |
| -------------- | --------------------------------------------- |
| **HTML5**      | Structure des pages                           |
| **CSS3**       | Styling, responsive design (grid, flexbox)    |
| **JavaScript** | Interactivité légère (validation côté client) |

### Outils Développement

| Outil                   | Utilisation                                |
| ----------------------- | ------------------------------------------ |
| **Herd** (Laravel Herd) | Serveur PHP local `locauto.test`           |
| **PHPUnit-like tests**  | Suite de tests unitaires maison (31 tests) |
| **Git**                 | Versionning du code                        |

### Architecture & Patterns

- **MVC Pattern**: Séparation Model/View/Controller
- **Routage custom**: Basé sur paramètres URL `?controller=X&action=Y`
- **OOP**: Programmation orientée objet (classes, héritage)
- **SOLID Principles**: Séparation des responsabilités

---

## Architecture MVC

### 1. Modèle (Logique métier + données)

**Localisation**: `site_fix-2/modele/`

Les modèles gèrent l'accès aux données et la logique métier avec PDO:

- **`Database.php`**: Connexion centralisée à MySQL, gestion de la session
- **`Voiture.php`**: CRUD voitures, disponibilités, images
- **`Reservation.php`**: Gestion des réservations, vérification des chevauchements
- **`Paiement.php`**: Calcul et suivi des paiements
- **`Utilisateur.php`**: Authentification, hash bcrypt, profil
- **`Categorie.php`**: Gestion des catégories, relations N:N avec voitures

**Exemple flux Modèle**:

```php
$voiture = new Voiture();
$liste = $voiture->getListe();           // SELECT * FROM voiture
$voiture->modifier($id, $data);          // UPDATE avec validation
```

### 2. Contrôleur (Orchestrateur)

**Localisation**: `site_fix-2/controleur/`

Les contrôleurs reçoivent les requêtes HTTP, coordonnent modèles et vues:

- **`AccueilControlleur.php`**: Page d'accueil, voitures vedettes
- **`VoitureControlleur.php`**: Liste, détail, ajout (admin), modification, suppression
- **`ReservationControlleur.php`**: Création, modification, consultation réservations
- **`PaiementControlleur.php`**: Formulaire, confirmation, historique
- **`UtilisateurControlleur.php`**: Auth (login/register), profil, déconnexion

**Exemple flux Contrôleur**:

```php
class VoitureControlleur {
    public function liste() {
        $voitureModel = new Voiture();
        $voitures = $voitureModel->getListe();    // Appel modèle
        require 'vue/voiture.liste.php';          // Charge vue + $voitures
    }
}
```

### 3. Vue (Présentation)

**Localisation**: `site_fix-2/vue/`

Les vues affichent le contenu HTML, sans logique métier:

**Templates partagés**:

- `header.php`: Navbar, styles, meta tags
- `footer.php`: Pied de page, fermeture HTML

**Pages métier** (~30 fichiers):

- `accueil.php`: Affichage grille voitures
- `voiture.liste.php`, `voiture.detail.php`, `voiture.*.php`: Gestion voitures
- `resa.*.php`: Interfaces réservations
- `paiement.*.php`: Formulaires et confirmations paiement
- `login.php`, `register.php`: Authentification
- `profil.php`, `profil.modifier.php`: Gestion profil

**Exemple template**:

```php
<?php require_once 'vue/header.php'; ?>
<div class="voitures-grid">
    <?php foreach($voitures as $v): ?>
        <div class="voiture-card">
            <h3><?= htmlspecialchars($v['marque']) ?></h3>
            <p><?= $v['prix_jour'] ?> €/jour</p>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once 'vue/footer.php'; ?>
```

### 4. Point d'entrée (Front Controller)

**Fichier**: `site_fix-2/index.php`

```php
session_start();
require_once 'config.php';

$controller = $_GET['controller'] ?? 'accueil';
$action = $_GET['action'] ?? 'index';

$controllerClass = ucfirst($controller) . 'Controlleur';
$controllerFile = "controleur/$controllerClass.php";

require $controllerFile;
$instance = new $controllerClass();
$instance->$action();  // Exécute l'action
```

**Cycle d'une requête**:

```
URL: index.php?controller=voiture&action=liste
  ↓
Front Controller charge VoitureControlleur
  ↓
Appelle $voitureControlleur->liste()
  ↓
Modèle récupère données DB
  ↓
Vue affiche HTML avec données
```

---

## Installation et configuration

### Prérequis

- **PHP 8.4+** avec extensions: `pdo_mysql`, `sessions`
- **MySQL 5.7+** ou **MariaDB 10.3+**
- **Herd** (ou serveur PHP local compatible)
- **Git**

### Étape 1 : Cloner le dépôt

```bash
git clone https://github.com/Youss-93/ap-mvc_locauto.git
cd ap-mvc_locauto
```

### Étape 2 : Configurer la base de données

1. Créer une base de données MySQL:

   ```sql
   CREATE DATABASE ytape_aploc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Importer le schéma SQL depuis `base de données/ytape_aploc_secure.sql`:

   ```bash
   mysql -u root -p ytape_aploc < "base de données/ytape_aploc_secure.sql"
   ```

3. Vérifier la connexion dans **`site_fix-2/config.php`**:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'ytape_aploc');
   define('DB_USER', 'root');
   define('DB_PASS', '');          // Si pas de mot de passe
   define('DB_PORT', '8889');      // Port MySQL local
   ```

### Étape 3 : Configurer l'application

Éditer `site_fix-2/config.php` si nécessaire:

```php
define('APP_NAME', 'Location de Voitures');
define('APP_URL', 'http://locauto.test');
define('DEBUG_MODE', true);         // true en dev, false en prod
date_default_timezone_set('Europe/Paris');
```

### Étape 4 : Permissions des dossiers

```bash
chmod -R 755 site_fix-2/assets/        # Images uploads
chmod -R 755 site_fix-2/vue/           # Vues generées
```

---

## Lancer l'application

### Avec Herd (Recommandé)

```bash
cd site_fix-2

# Créer le lien local
herd link locauto

# Redémarrer les services
herd restart

# Ouvrir dans le navigateur
open http://locauto.test
```

### Vérifier que ça marche

```bash
# Test simple
curl -I http://locauto.test
# Réponse attendue: HTTP/1.1 200 OK
```

### Comptes de test

Pour faciliter les tests rapides de l'application:

- **Admin**
  - Email: adminHY93@hotmail.fr
  - Mot de passe: HuseyinY123
- **Client**
  - Email: teste6@turgot.com
  - Mot de passe: siotestE6

### Arrêter l'application

```bash
herd unlink locauto
herd restart
```

---

## Base de données

### Schéma des tables

**Modèle de données** (5 tables principales):

```
UTILISATEUR (1)
  ├─ id_utilisateur (PK)
  ├─ nom_utilisateur, prenom_utilisateur
  ├─ email, mdp_utilisateur (bcrypt)
  ├─ num_tel, role_utilisateur (client/admin)
  └─ Relations: gère VOITURE (1:N), confirme RESERVATION (1:N)

VOITURE (1)
  ├─ id_voiture (PK)
  ├─ marque, modele, annee
  ├─ prix_jour, caution, disponibilite
  ├─ image_loc (chemin fichier)
  └─ Relations: inclut RESERVATION (1:N), appartient CATEGORIE (N:N)

RESERVATION (N)
  ├─ id_reservation (PK)
  ├─ id_voiture (FK)
  ├─ id_utilisateur (FK)
  ├─ date_debut, date_fin
  ├─ statut_reservation (confirmée/annulée/en attente)
  └─ Relations: payée PAIEMENT (1:1)

PAIEMENT (1)
  ├─ id_paiement (PK)
  ├─ id_reservation (FK)
  ├─ montant (calculé: jours × prix/jour)
  ├─ mode_paiement (virement/carte/espèces)
  └─ statut_paiement (payé/en attente/échoué)

CATEGORIE (N)
  ├─ id_categorie (PK)
  ├─ libelle_categorie (SUV/Luxe/Sport/Économique/Familial)
  └─ description
```

### Requêtes SQL courantes

```sql
-- Voitures disponibles
SELECT * FROM voiture WHERE disponibilite = 1;

-- Réservations d'un utilisateur
SELECT * FROM reservation WHERE id_utilisateur = ?;

-- Vérifier chevauchement dates
SELECT * FROM reservation
WHERE id_voiture = ?
AND date_debut <= ? AND date_fin >= ?;

-- Total revenus par mois
SELECT DATE_FORMAT(date_reservation, '%Y-%m') as mois, SUM(montant)
FROM paiement WHERE statut_paiement = 'payé'
GROUP BY mois;
```

---

## Tests unitaires

### Structure des tests

**Localisation**: `tests/`

Les tests couvrent la logique métier sans dépendre de la base de données (mocks):

- `TestUtilisateur.php` (7 tests): Sécurité mots de passe, bcrypt, complexité
- `TestVoiture.php` (5 tests): Logique CRUD, validations prix/marque
- `TestReservation.php` (7 tests): Calculs prix, chevauchements dates
- `TestCategorie.php` (12 tests): Relations N:N, unicité, filtrage

**Total**: 31 tests unitaires.

### Exécuter les tests

```bash
# Tous les tests
php tests/run_all_tests.php

# Output attendu:
# ╔════════════════════════════════════════════════════════════╗
# ║           Test Suite - Application Locauto                ║
# ╚════════════════════════════════════════════════════════════╝
#
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# Exécution: TestUtilisateur
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# ✓ PASS: Deux hashs du même mot de passe doivent être différents
# ✓ PASS: password_verify() doit retourner true avec le bon mot de passe
# ...
#
# ╔════════════════════════════════════════════════════════════╗
# ║                    RÉSUMÉ GLOBAL                          ║
# ╚════════════════════════════════════════════════════════════╝
# Tests Totaux: 31
# Tests Réussis: 31
# Taux de Réussite: 100%
# ✓ TOUS LES TESTS SONT PASSÉS AVEC SUCCÈS!
```

### Vérifier le code de sortie

```bash
php tests/run_all_tests.php
echo $?  # 0 = succès, 1+ = erreur
```

### Ajouter un nouveau test

Créer `tests/TestNouveau.php`:

```php
<?php
class TestNouveau {
    private $test_results = [];

    public function testExemple() {
        $valeur = 1 + 1;
        $this->assertEqual($valeur, 2, "1+1 doit égal 2");
    }

    private function assertEqual($actual, $expected, $msg) {
        $pass = $actual === $expected;
        $this->test_results[] = ['test' => $msg, 'pass' => $pass];
    }

    private function printResults() {
        foreach ($this->test_results as $r) {
            echo ($r['pass'] ? '✓ PASS' : '✗ FAIL') . ": {$r['test']}\n";
        }
        $total = count($this->test_results);
        $passed = count(array_filter($this->test_results, fn($r) => $r['pass']));
        echo "\nRésultat: $passed/$total tests passés\n";
    }

    public function runAllTests() {
        echo "=== Tests Unitaires Nouveau ===\n";
        $this->testExemple();
        $this->printResults();
    }
}

if (php_sapi_name() === 'cli') {
    $test = new TestNouveau();
    $test->runAllTests();
}
```

---

## Flux de l'application

### Flux d'authentification

```
1. Utilisateur visite index.php?controller=utilisateur&action=register
   ↓
2. UtilisateurControlleur->register() charge vue/register.php
   ↓
3. Formulaire soumis en POST
   ↓
4. Modèle Utilisateur valide et hash mot de passe avec bcrypt
   ↓
5. INSERT INTO utilisateur (PDO prepared statement)
   ↓
6. Session créée, utilisateur redirigé vers accueil
```

### Flux de réservation

```
1. Client clique "Réserver" sur une voiture (id=101)
   ↓
2. URL: index.php?controller=reservation&action=creer&id=101
   ↓
3. ReservationControlleur->creer() charge formulaire
   ↓
4. Client sélectionne dates (date_debut, date_fin)
   ↓
5. Modèle vérifie:
   - Dates valides (future, fin > début)
   - Voiture disponible
   - Pas de chevauchement avec autre réservation
   ↓
6. INSERT INTO reservation
   ↓
7. Redirection vers paiement avec id_reservation
```

### Flux de paiement

```
1. Après réservation confirmée, redirection paiement
   ↓
2. PaiementControlleur->form() calcule:
   montant = (date_fin - date_debut) * voiture.prix_jour
   ↓
3. Affiche formulaire avec details
   ↓
4. Client valide paiement
   ↓
5. INSERT INTO paiement, UPDATE reservation statut = 'confirmée'
   ↓
6. Page confirmation avec détails
```

---

## Guide développeur

### Ajouter une nouvelle fonctionnalité

#### Exemple: Ajouter un système d'évaluations

1. **Modèle** (`site_fix-2/modele/Evaluation.php`):

   ```php
   class Evaluation {
       public function creer($id_reservation, $note, $commentaire) {
           $query = "INSERT INTO evaluation (id_reservation, note, commentaire) VALUES (?, ?, ?)";
           $stmt = $this->db->prepare($query);
           return $stmt->execute([$id_reservation, $note, $commentaire]);
       }
   }
   ```

2. **Contrôleur** (`site_fix-2/controleur/EvaluationControlleur.php`):

   ```php
   class EvaluationControlleur {
       private $evaluationModel;

       public function __construct() {
           require_once 'modele/Evaluation.php';
           $this->evaluationModel = new Evaluation();
       }

       public function ajouter() {
           if ($_SERVER['REQUEST_METHOD'] === 'POST') {
               $this->evaluationModel->creer(...);
               header('Location: index.php');
           }
           require_once 'vue/evaluation.ajouter.php';
       }
   }
   ```

3. **Vue** (`site_fix-2/vue/evaluation.ajouter.php`):

   ```php
   <form method="POST">
       <textarea name="commentaire" required></textarea>
       <input type="number" name="note" min="1" max="5">
       <button type="submit">Évaluer</button>
   </form>
   ```

4. **Test** (`tests/TestEvaluation.php`):
   ```php
   public function testEvaluationValide() {
       $eval = new Evaluation();
       // Test logique
   }
   ```

### Conventions de code

- **Nommage**: `camelCase` pour les fonctions, `CONSTANT_CASE` pour les constantes
- **Classes**: `PascalCase` (ex: `VoitureControlleur`)
- **Fichiers**: matching nom classe (ex: `Voiture.php`)
- **Sécurité**: Toujours utiliser prepared statements, htmlspecialchars() pour l'affichage
- **Commentaires**: Commenter les algorithmes complexes, pas le code trivial

### Ajouter une route

Créer un lien dans les vues ou formulaires:

```php
<a href="index.php?controller=voiture&action=detail&id=<?= $voiture['id_voiture'] ?>">
    Voir détails
</a>
```

### Débogage

Activer/désactiver en `site_fix-2/config.php`:

```php
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
```

---

## Structure complète du dépôt

```
ap-mvc_locauto/
├── README.md                          # Cette documentation
├── SECURITY_TESTING_REPORT.md         # Rapport de sécurité
├── base de données/
│   └── ytape_aploc_secure.sql        # Schéma SQL complet
├── MCD/                              # Modèles conceptuels de données
├── tests/
│   ├── run_all_tests.php             # Lanceur de tests
│   ├── TestUtilisateur.php           # Tests authentification/sécurité
│   ├── TestVoiture.php               # Tests gestion voitures
│   ├── TestReservation.php           # Tests réservations
│   └── TestCategorie.php             # Tests catégories
└── site_fix-2/                       # Application web
    ├── index.php                     # Front controller
    ├── config.php                    # Configuration application
    ├── controleur/                   # Contrôleurs MVC
    │   ├── AccueilControlleur.php
    │   ├── VoitureControlleur.php
    │   ├── ReservationControlleur.php
    │   ├── PaiementControlleur.php
    │   └── UtilisateurControlleur.php
    ├── modele/                       # Modèles (données + logique)
    │   ├── Database.php
    │   ├── Voiture.php
    │   ├── Reservation.php
    │   ├── Paiement.php
    │   ├── Utilisateur.php
    │   └── Categorie.php
    ├── vue/                          # Vues (templates HTML)
    │   ├── header.php
    │   ├── footer.php
    │   ├── accueil.php
    │   ├── voiture.*.php
    │   ├── resa.*.php
    │   ├── paiement.*.php
    │   ├── login.php
    │   ├── register.php
    │   └── profil.*.php
    ├── css/
    │   └── style.css                 # Styles globaux
    └── assets/
        └── photos/voitures/          # Images voitures
```

---

## Sécurité

- ✅ Passwords hashés avec **bcrypt** (password_hash/password_verify)
- ✅ Sessions sécurisées (session_start())
- ✅ Prepared statements **PDO** (injection SQL impossible)
- ✅ Sanitization HTML (htmlspecialchars())
- ✅ Valeurs GET/POST filtrées (filter_input)
- ⚠️ HTTPS recommandée en production

Voir `SECURITY_TESTING_REPORT.md` pour audit complet.

---

## Performances

- **Requêtes optimisées**: Index sur clés étrangères
- **Caching**: Peut être ajouté facilement (Redis/Memcached)
- **Assets minifiés**: CSS/JS peuvent être compressés en production
- **Temps réponse**: < 100ms en local

---

## Contribuer

1. Fork le dépôt
2. Créer une branche (`git checkout -b feature/ma-fonctionnalite`)
3. Commit les changements (`git commit -m 'Ajout ma fonctionnalité'`)
4. Push vers la branche (`git push origin feature/ma-fonctionnalite`)
5. Ouvrir une Pull Request

### Avant de soumettre

- ✅ Tous les tests passent (`php tests/run_all_tests.php`)
- ✅ Code formaté et commenté
- ✅ Pas de fichiers système ajoutés (.DS_Store, node_modules, etc.)

---

## Licence

Ce projet est fourni à titre éducatif.

---

## Auteur

**Youss-93** - [Profil GitHub](https://github.com/Youss-93)

---

## Support et Questions

Pour toute question ou problème:

- Ouvrir une issue GitHub
- Consulter la documentation dans ce README
- Vérifier les logs en mode DEBUG

---

**Dernière mise à jour**: 31 mars 2026
