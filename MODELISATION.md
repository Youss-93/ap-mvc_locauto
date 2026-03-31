# Modélisation - Application Location Auto

Cette documentation présente les modèles de l'application: cas d'usage et modèle de données.

---

## 📊 Use Case Diagram (Diagramme des Cas d'Utilisation)

Le diagramme ci-dessous illustre les interactions entre les utilisateurs (Client et Admin) et les fonctionnalités du système.

### Acteurs

- **Client**: Utilisateur non authentifié ou authentifié pouvant consulter et réserver des voitures
- **Admin**: Administrateur ayant accès aux fonctionnalités de gestion du catalogue et des réservations
- **Système d'authentification**: Cas d'utilisation inclus dans plusieurs autres (authentification requise)

### Cas d'utilisation Client

| Cas d'utilisation | Description |
|---|---|
| **S'inscrire** | Créer un compte client avec email et mot de passe sécurisé |
| **Se connecter** | Authentification via email et mot de passe |
| **Consulter les voitures** | Afficher la liste complète des voitures disponibles avec détails |
| **Réserver une voiture** | Sélectionner une voiture et des dates pour créer une réservation |
| **Payer une réservation** | Effectuer le paiement associé à une réservation (inclut authentification) |
| **Consulter ses réservations** | Voir l'historique et le statut de ses réservations |
| **Annuler une réservation** | Supprimer / annuler une réservation existante |
| **Modifier son profil** | Mettre à jour ses données personnelles (nom, email, téléphone) |

### Cas d'utilisation Admin

| Cas d'utilisation | Description |
|---|---|
| **Gérer les voitures (CRUD)** | Ajouter, modifier, supprimer des voitures du catalogue |
| **Gérer les catégories** | Créer et maintenir les catégories de voitures (SUV, Luxe, etc.) |
| **Gérer toutes les réservations** | Consulter, confirmer, ou supprimer n'importe quelle réservation |
| **Confirmer une réservation** | Valider une réservation en attente |
| **Supprimer une réservation** | Annuler une réservation (accès admin complet) |

### Cas d'utilisation partagés

- **Authentifier**: Inclus dans "Réserver une voiture", "Payer", "Consulter ses réservations", "Modifier son profil" (les clients doivent être connectés pour ces actions)

### Diagramme (Textuel)

```
                    ┌─────────────────────────────────────────────────┐
                    │  Système de location de voiture                 │
                    └─────────────────────────────────────────────────┘
                                        ↑
                ┌───────────────────────┼───────────────────────┐
                │                       │                       │
          S'inscrire            Se connecter           Consulter les voitures
                │                       │                       │
                └───────────────────────┼───────────────────────┘
                            ↓ (inclut Authentifier)
                ┌─────────────────────────────────────────────────┐
                │                    Client                       │
                ├─────────────────────────────────────────────────┤
                │ • Réserver une voiture (inclut Authentifier)    │
                │ • Payer une réservation (inclut Authentifier)   │
                │ • Consulter ses réservations (inclut Auth.)      │
                │ • Annuler une réservation                        │
                │ • Modifier son profil (inclut Authentifier)      │
                └─────────────────────────────────────────────────┘
                            
                ┌─────────────────────────────────────────────────┐
                │                    Admin                        │
                ├─────────────────────────────────────────────────┤
                │ • Gérer les voitures (CRUD)                     │
                │ • Gérer les catégories                          │
                │ • Gérer toutes les réservations                 │
                │ • Confirmer une réservation                     │
                │ • Supprimer une réservation                     │
                └─────────────────────────────────────────────────┘
```

---

## 🗄️ Modèle Conceptuel de Données (MCD)

Le MCD définit les entités, attributs et relations de la base de données.

### Entités principales

#### UTILISATEUR
Représente un utilisateur (client ou admin) du système.

| Attribut | Type | Contrainte |
|---|---|---|
| **id_utilisateur** (PK) | INT | Clé primaire, auto-incrémenté |
| nom_utilisateur | VARCHAR(100) | Non nul |
| prenom_utilisateur | VARCHAR(100) | Non nul |
| email | VARCHAR(255) | Unique, Non nul |
| mdp_utilisateur | VARCHAR(255) | Hash bcrypt, Non nul |
| num_tel | VARCHAR(20) | Optionnel |
| role_utilisateur | ENUM('client', 'admin') | Default 'client' |

#### VOITURE
Représente une voiture disponible à la location.

| Attribut | Type | Contrainte |
|---|---|---|
| **id_voiture** (PK) | INT | Clé primaire, auto-incrémenté |
| marque | VARCHAR(100) | Non nul |
| modele | VARCHAR(100) | Non nul |
| annee | INT | Entre 1900 et année actuelle |
| prix_jour | DECIMAL(10,2) | Positif, Non nul |
| caution | DECIMAL(10,2) | Positif, Non nul |
| disponibilite | BOOLEAN | Default TRUE |
| image_loc | VARCHAR(255) | Chemin vers image |

#### RESERVATION
Représente une réservation de voiture par un client.

| Attribut | Type | Contrainte |
|---|---|---|
| **id_reservation** (PK) | INT | Clé primaire, auto-incrémenté |
| **id_voiture** (FK) | INT | Référence VOITURE |
| **id_utilisateur** (FK) | INT | Référence UTILISATEUR |
| date_debut | DATE | Non nul, >= date courante |
| date_fin | DATE | Non nul, > date_debut |
| statut_reservation | ENUM | 'confirmée', 'annulée', 'en attente' |

#### PAIEMENT
Représente le paiement associé à une réservation.

| Attribut | Type | Contrainte |
|---|---|---|
| **id_paiement** (PK) | INT | Clé primaire, auto-incrémenté |
| **id_reservation** (FK) | INT | Référence RESERVATION (Unique) |
| montant | DECIMAL(10,2) | Calculé: (date_fin - date_debut) × prix_jour |
| mode_paiement | ENUM | 'carte', 'virement', 'espèces' |
| statut_paiement | ENUM | 'payé', 'en attente', 'échoué' |

#### CATEGORIE
Représente une catégorie de voiture.

| Attribut | Type | Contrainte |
|---|---|---|
| **id_categorie** (PK) | INT | Clé primaire, auto-incrémenté |
| libelle_categorie | VARCHAR(100) | Unique, Non nul |
| description | TEXT | Optionnel |

**Catégories prédéfinies**: SUV, Luxe, Sport, Économique, Familial

### Relations

#### 1. UTILISATEUR **gérer** VOITURE (1,n)
- Un administrateur gère **0 ou plusieurs** voitures
- Une voiture est gérée par **exactement 1** utilisateur (ou aucun, selon implémentation)
- **Type**: Une relation optionnelle côté voiture

#### 2. UTILISATEUR **confirmer** RESERVATION (1,n)
- Un utilisateur confirmant peut avoir **0 ou plusieurs** réservations confirmées
- Une réservation est confirmée par **exactement 1** utilisateur
- **Type**: Relation standard

#### 3. VOITURE **inclure** RESERVATION (1,n)
- Une voiture peut être l'objet de **0 ou plusieurs** réservations
- Une réservation inclut **exactement 1** voiture
- **Type**: Relation cascade (suppression d'une voiture affecte les réservations)

#### 4. RESERVATION **payée** PAIEMENT (1,1)
- Une réservation a **exactement 1 ou 0** paiement associé
- Un paiement concerne **exactement 1** réservation
- **Type**: Relation optionnelle côté réservation, unique côté paiement

#### 5. **VOITURE** **appartenir** **CATEGORIE** (N,N) ⭐
- Une voiture peut appartenir à **0 ou plusieurs** catégories
- Une catégorie peut contenir **0 ou plusieurs** voitures
- **Type**: Association many-to-many (nécessite une table de jonction)

**Table de jonction**: `appartenirs` (ou `voiture_categorie`)
```sql
CREATE TABLE voiture_categorie (
    id_voiture INT NOT NULL,
    id_categorie INT NOT NULL,
    PRIMARY KEY (id_voiture, id_categorie),
    FOREIGN KEY (id_voiture) REFERENCES voiture(id_voiture),
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
);
```

### Diagramme MCD (Format ER)

```
┌──────────────────────┐
│    UTILISATEUR       │
├──────────────────────┤
│ id_utilisateur (PK)  │
│ nom_utilisateur      │
│ prenom_utilisateur   │
│ email (Unique)       │
│ mdp_utilisateur      │
│ num_tel              │
│ role_utilisateur     │
└──────────────────────┘
       │ 1,n (gérer)      1,n (confirmer)
       │ ◇─────────────┐  ◇─────────────┐
       │               │                │
       ▼               ▼                ▼
┌──────────────────────┐    ┌──────────────────────┐
│      VOITURE         │    │    RESERVATION       │
├──────────────────────┤    ├──────────────────────┤
│ id_voiture (PK)      │    │ id_reservation (PK)  │
│ marque               │    │ id_voiture (FK)      │
│ modele               │ 1  │ id_utilisateur (FK)  │
│ annee                ├──◇─┤ date_debut           │
│ prix_jour            │1,n │ date_fin             │
│ caution              │    │ statut_reservation   │
│ disponibilite        │    └──────────────────────┘
│ image_loc            │         │
└──────────────────────┘         │ 1 (payée)
       │                         │
       │ N,N (appartenir)        ▼
       │  ◇─────────────────┐    ◌──────────────────┐
       │                   │    │    PAIEMENT      │
       ▼                   ▼    ├──────────────────┤
┌──────────────────────┐ ◌──┐   │ id_paiement (PK) │
│    CATEGORIE         │────┼───┤ id_reservation   │
├──────────────────────┤    │   │ montant          │
│ id_categorie (PK)    │    │   │ mode_paiement    │
│ libelle_categorie(U) │    │   │ statut_paiement  │
│ description          │    │   └──────────────────┘
└──────────────────────┘    │
       △                    │
       │ (N,N association) │
       └────◇──────────────┘
       via table voiture_categorie
```

### Cardinalités (Notation Merise)

```
              0,1 ou 1,1 = Obligatoire 1 seul
              0,n ou 1,n = Zéro, un ou plusieurs
              ◯ côté optionnel
              │ côté obligatoire
              ◇ côté N
```

---

## 🔄 Flux de données

### Flux de réservation complète

```
1. Client consulte catalogue
   └─> VOITURE (lecture)

2. Client sélectionne une voiture et des dates
   └─> Vérification: pas de chevauchement avec autre RESERVATION

3. Création RESERVATION
   ├─> INSERT INTO reservation (id_voiture, id_utilisateur, dates...)
   ├─> UPDATE voiture SET disponibilite = FALSE (optionnel)
   └─> Status: 'en attente'

4. Client accède au paiement
   ├─> Calcul montant = (date_fin - date_debut) × voiture.prix_jour
   └─> Affichage formulaire

5. Client valide paiement
   ├─> INSERT INTO paiement (id_reservation, montant, mode...)
   ├─> UPDATE reservation SET statut_reservation = 'confirmée'
   └─> Confirmation à l'utilisateur

6. Admin peut voir et gérer
   └─> SELECT * FROM reservation JOIN utilisateur JOIN voiture
```

---

## 📋 Requêtes SQL courantes

### Voitures par catégorie
```sql
SELECT v.*, c.libelle_categorie
FROM voiture v
JOIN voiture_categorie vc ON v.id_voiture = vc.id_voiture
JOIN categorie c ON vc.id_categorie = c.id_categorie
WHERE c.libelle_categorie = 'SUV';
```

### Réservations non payées
```sql
SELECT r.*, v.marque, v.modele, p.montant
FROM reservation r
JOIN voiture v ON r.id_voiture = v.id_voiture
LEFT JOIN paiement p ON r.id_reservation = p.id_reservation
WHERE p.statut_paiement != 'payé' OR p.id_paiement IS NULL;
```

### Disponibilité d'une voiture
```sql
SELECT v.*, COUNT(r.id_reservation) as reservations_actives
FROM voiture v
LEFT JOIN reservation r ON v.id_voiture = r.id_voiture
WHERE r.date_fin >= CURDATE() AND r.statut_reservation = 'confirmée'
GROUP BY v.id_voiture;
```

### Revenus par mois
```sql
SELECT 
    DATE_FORMAT(NOW(), '%Y-%m') as mois,
    SUM(p.montant) as total_revenus
FROM paiement p
WHERE p.statut_paiement = 'payé'
GROUP BY mois
ORDER BY mois DESC;
```

---

## 🎯 Contraintes et Règles Métier

1. **Unicité email**: Chaque utilisateur a un email unique
2. **Mot de passe sécurisé**: Hash bcrypt avec cost ≥ 12
3. **Ordre des dates**: date_debut < date_fin dans une réservation
4. **Chevauchement**: Pas de deux réservations confirmées pour la même voiture sur des dates qui se chevauchent
5. **Prix positifs**: prix_jour et caution > 0
6. **Statuts contrôlés**: Uniquement les valeurs énumérées acceptées
7. **Rôles contrôlés**: 'client' ou 'admin' uniquement
8. **Catégories prédéfinies**: SUV, Luxe, Sport, Économique, Familial

---

## 📝 Notes

- Le modèle utilise **OneToMany** et **ManyToMany** relations
- Les **foreign keys** garantissent l'intégrité référentielle
- La **table de jonction** `voiture_categorie` maintient l'association N,N
- Les **timestamps** (created_at, updated_at) pourraient être ajoutés pour l'audit
- La **suppression logique** (soft delete) peut être implémentée plutôt que physique

---

**Dernière mise à jour**: 31 mars 2026
