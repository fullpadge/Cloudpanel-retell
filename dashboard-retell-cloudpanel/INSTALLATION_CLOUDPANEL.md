# 📊 Dashboard Retell AI - Installation CloudPanel

## 🎯 Guide d'Installation Complet pour CloudPanel

Cette documentation vous guide dans l'installation du **Dashboard Retell AI** sur un serveur CloudPanel.

---

## 📋 Prérequis

### Serveur
- **OS**: Ubuntu 22.04 LTS
- **Panneau**: CloudPanel
- **PHP**: 8.1 ou supérieur
- **MySQL**: 5.7 ou supérieur
- **Domaine**: Configuré et pointant vers votre serveur

### Extensions PHP Requises
```
- mysqli (connexion MySQL)
- pdo (base de données PDO)
- pdo_mysql (PDO MySQL)
- mbstring (chaînes multi-octets)
- openssl (chiffrement SSL)
- tokenizer (analyseur de tokens)
- xml (support XML)
- ctype (vérification caractères)
- json (support JSON)
- bcmath (mathématiques précises)
```

---

## 🚀 Installation Étape par Étape

### Étape 1: Préparer CloudPanel

1. **Connectez-vous à CloudPanel** (port 8888)
2. **Ajoutez votre domaine**:
   - Websites → Add Website
   - Type: Laravel (PHP)
   - Domain: `votre-domaine.com`
   - PHP Version: 8.1

### Étape 2: Préparer la Base de Données

1. **Créez une base de données MySQL**:
   - Databases → Add Database
   - Database Name: `retell_dashboard`
   - User: `retell_user`
   - Password: `VotreMotDePasse123!`

### Étape 3: Télécharger et Déployer

1. **Téléchargez** le package `dashboard-retell-cloudpanel.zip`
2. **Décompressez** le contenu dans le dossier de votre site:
   ```
   /home/nom-utilisateur/htdocs/votre-domaine.com/
   ```
3. **Configurez les permissions**:
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

### Étape 4: Configurer le Document Root

Dans CloudPanel, configurez le **Document Root** vers le dossier `public/`:
```
/home/nom-utilisateur/htdocs/votre-domaine.com/public/
```

### Étape 5: Activer HTTPS

1. **SSL/TLS Certificates** → **Let's Encrypt**
2. Activez le certificat pour votre domaine

---

## 🔧 Installation via Interface Web

### Accéder à l'Installateur

Visitez: `https://votre-domaine.com/install`

### Étapes de l'Installation

#### **Étape 1: Vérification d'Intégrité**
- Vérification des fichiers Laravel essentiels
- Contrôle des permissions des dossiers
- **Action**: Cliquez "Continuer" si tout est ✅

#### **Étape 2: Prérequis Système**
- Vérification version PHP (8.1+)
- Contrôle des extensions PHP requises
- **Action**: Corrigez les erreurs rouges ou continuez

#### **Étape 3: Configuration Base de Données**
- **Host**: `127.0.0.1` ou `localhost`
- **Port**: `3306`
- **Database**: `retell_dashboard`
- **Username**: `retell_user`
- **Password**: `VotreMotDePasse123!`
- **Action**: Test de connexion automatique

#### **Étape 4: Compte Administrateur**
- **Nom**: Votre nom complet
- **Email**: votre.email@domaine.com
- **Mot de passe**: Mot de passe sécurisé (8+ caractères)
- **Action**: Création du compte admin

#### **Étape 5: Configuration Retell AI**
- **Clé API**: Votre clé API Retell AI
- **URL Webhook**: `https://votre-domaine.com/api/webhook`
- **Action**: Validation de la configuration

#### **Étape 6: Validation**
- Récapitulatif de toutes les configurations
- **Action**: Validation finale

#### **Étape 7: Finalisation**
- Installation des tables de base de données
- Configuration finale
- **Action**: Accès au dashboard admin

---

## 🛠️ Configuration Post-Installation

### Configurer le Webhook Retell AI

1. **Connectez-vous** à votre compte Retell AI
2. **Accédez** aux Webhooks Settings
3. **Ajoutez** l'URL: `https://votre-domaine.com/api/webhook`
4. **Activez** l'événement `call_analyzed`

### Créer votre Premier Client

1. **Accédez** à `/admin`
2. **Clients** → **Ajouter un Client**
3. Configurez:
   - Nom du client
   - Email de connexion
   - Mot de passe
   - Logo personnalisé
   - Couleurs marque blanche
   - Sous-domaine

---

## 🎨 Fonctionnalités

### Section Administrateur (`/admin`)
- ✅ Gestion complète des clients
- ✅ Visualisation globale des analyses
- ✅ Configuration API Retell AI
- ✅ Personnalisation marque blanche

### Section Client (sous-domaine)
- ✅ Dashboard personnalisé
- ✅ Métriques d'appels en temps réel
- ✅ Graphiques interactifs (Chart.js)
- ✅ Filtres par période/agent
- ✅ Export CSV

---

## 🔒 Sécurité

### Fonctionnalités Implémentées
- ✅ Authentification Laravel sécurisée
- ✅ Vérification signatures webhooks
- ✅ Protection CSRF/XSS
- ✅ Chiffrement bcrypt des mots de passe
- ✅ HTTPS obligatoire

### Recommandations
- Utilisez des mots de passe forts
- Maintenez PHP et Laravel à jour
- Surveillez les logs d'erreur
- Sauvegardez régulièrement la base de données

---

## 🐛 Dépannage

### Erreur 500 "vendor/autoload.php not found"
```bash
# Réinstallez les dépendances
composer install --no-dev --optimize-autoloader
```

### Problème de Permissions
```bash
# Corrigez les permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/ bootstrap/cache/
```

### Erreur Base de Données
- Vérifiez les informations de connexion dans `.env`
- Assurez-vous que l'utilisateur MySQL a les bonnes permissions

### Page d'Installation Inaccessible
- Vérifiez que le Document Root pointe vers `public/`
- Assurez-vous que mod_rewrite est activé

---

## 📞 Support

### Logs d'Erreur
```bash
# Consultez les logs Laravel
tail -f storage/logs/laravel.log

# Logs du serveur web
tail -f /var/log/nginx/error.log
```

### Test de Configuration
Utilisez le fichier de test: `https://votre-domaine.com/install-test`

---

## 📦 Structure du Projet

```
dashboard-retell-cloudpanel/
├── app/                    # Code de l'application
├── bootstrap/              # Démarrage Laravel
├── config/                 # Configuration
├── database/               # Migrations & seeders
├── public/                 # Point d'entrée web
├── resources/              # Vues & assets
├── routes/                 # Routes web & API
├── storage/                # Stockage & logs
├── vendor/                 # Dépendances Composer
├── .env                    # Variables d'environnement
└── composer.json           # Dépendances PHP
```

---

## ✨ Marque Blanche

Le dashboard est entièrement personnalisable:
- **Logo**: Upload via interface admin
- **Couleurs**: Palette personnalisée par client
- **Sous-domaines**: `client.votre-domaine.com`
- **Aucune** mention de Retell AI visible

---

*Installation réalisée avec ❤️ pour CloudPanel*