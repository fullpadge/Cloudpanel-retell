# 🚀 Dashboard Retell AI - Instructions d'Installation CloudPanel

## 📦 Votre Package Complet

Vous avez téléchargé **`dashboard-retell-cloudpanel.zip`** (7.4MB) qui contient :
- ✅ **Projet Laravel 11** complet et configuré
- ✅ **Toutes les dépendances** installées (dossier vendor/)
- ✅ **Installateur 7 étapes** prêt à l'emploi
- ✅ **Clé d'application** générée
- ✅ **Documentation complète**

---

## ⚡ Installation Rapide (5 minutes)

### 1. Téléchargez sur votre serveur
```bash
# Via FTP/SFTP ou interface CloudPanel
# Décompressez dashboard-retell-cloudpanel.zip
# dans votre dossier web
```

### 2. Configurez CloudPanel
- **Websites** → **Add Website**
- **Type**: Laravel (PHP 8.1+)
- **Domain**: `votre-domaine.com`
- **Document Root**: `/public/` (important!)

### 3. Configurez les permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 4. Activez HTTPS
- **SSL/TLS** → **Let's Encrypt**
- Activez le certificat

### 5. Lancez l'installation
Visitez: **`https://votre-domaine.com/install`**

---

## 🎯 Assistant d'Installation (7 Étapes)

L'installateur vous guide automatiquement :

### Étape 1: ✅ Vérification Intégrité
- Contrôle des fichiers Laravel
- Vérification des permissions

### Étape 2: ✅ Prérequis Système  
- PHP 8.1+ et extensions
- Configuration serveur

### Étape 3: ✅ Base de Données
- Configuration MySQL
- Test de connexion automatique
- Création des tables

### Étape 4: ✅ Compte Admin
- Création administrateur
- Email et mot de passe

### Étape 5: ✅ API Retell AI
- Clé API Retell AI
- Configuration webhook
- URL: `https://votre-domaine.com/api/webhook`

### Étape 6: ✅ Validation
- Récapitulatif des configurations
- Validation finale

### Étape 7: ✅ Finalisation
- Installation complète
- Accès au dashboard admin

---

## 🛠️ Informations Base de Données

Préparez ces informations pour l'installation :

```
Host: 127.0.0.1 (ou localhost)
Port: 3306
Database: nom_de_votre_base
Username: votre_utilisateur_mysql
Password: votre_mot_de_passe_mysql
```

---

## 🔧 Configuration Retell AI

1. **Récupérez votre clé API** sur https://retellai.com
2. **Configurez le webhook** :
   - URL: `https://votre-domaine.com/api/webhook`
   - Événement: `call_analyzed`

---

## 📊 Après Installation

### Accès Administration
- URL: `https://votre-domaine.com/admin`
- Utilisez vos identifiants créés à l'étape 4

### Créer vos Clients
1. **Clients** → **Ajouter un Client**
2. Configurez :
   - Nom et email
   - Logo personnalisé
   - Couleurs marque blanche
   - Sous-domaine

### Accès Client
- URL: `https://client.votre-domaine.com`
- Dashboard personnalisé par client

---

## 🚨 Dépannage

### Erreur 500
```bash
# Vérifiez les permissions
chmod -R 755 storage/ bootstrap/cache/

# Consultez les logs
tail -f storage/logs/laravel.log
```

### Problème Document Root
- Assurez-vous qu'il pointe vers `/public/`
- Pas vers le dossier racine !

### Test de Configuration
Visitez: `https://votre-domaine.com/install-test`

---

## 📚 Documentation Complète

Consultez les fichiers inclus :
- **`README.md`** - Guide de démarrage rapide
- **`INSTALLATION_CLOUDPANEL.md`** - Documentation détaillée
- **`storage/logs/`** - Logs d'erreur

---

## ✨ Fonctionnalités Incluses

### 🔐 Sécurité
- Authentification Laravel
- Vérification signatures webhooks
- Protection CSRF/XSS
- HTTPS obligatoire

### 🎨 Marque Blanche
- Logo personnalisable
- Couleurs personnalisées
- Sous-domaines clients
- Aucune mention Retell AI

### 📈 Analytics
- Métriques temps réel
- Graphiques interactifs
- Filtres avancés
- Export CSV

---

**🎉 Installation complète en moins de 5 minutes !**

*Support : Consultez les logs dans `storage/logs/laravel.log`*