# 📊 Dashboard Retell AI - Installation Rapide

**Dashboard en marque blanche pour analyses d'appels Retell AI**

---

## 🚀 Installation en 3 Étapes

### 1. Téléchargez et Décompressez
- Placez tous les fichiers dans votre dossier web
- Configurez le Document Root vers le dossier `public/`

### 2. Configurez les Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 3. Lancez l'Installation
Visitez: **`https://votre-domaine.com/install`**

---

## 📋 Prérequis
- **PHP 8.1+** avec extensions: mysqli, pdo, mbstring, openssl, json
- **MySQL 5.7+**
- **HTTPS** configuré
- **CloudPanel** ou serveur compatible

---

## 🔧 Installation Guidée

L'installateur web vous guide à travers:
1. ✅ **Vérification** des fichiers et permissions
2. ✅ **Contrôle** des prérequis système
3. ✅ **Configuration** base de données MySQL
4. ✅ **Création** compte administrateur
5. ✅ **Configuration** API Retell AI
6. ✅ **Validation** et finalisation
7. ✅ **Accès** au dashboard admin

---

## 🎯 Fonctionnalités

### Administration (`/admin`)
- Gestion clients marque blanche
- Configuration API Retell AI
- Visualisation globale des analyses

### Client (sous-domaines)
- Dashboard personnalisé
- Métriques temps réel
- Graphiques interactifs
- Export CSV

---

## 📚 Documentation Complète

Consultez `INSTALLATION_CLOUDPANEL.md` pour:
- Guide détaillé CloudPanel
- Configuration webhooks Retell AI
- Dépannage et support
- Personnalisation marque blanche

---

## ⚡ Démarrage Rapide

1. **Décompressez** dans votre dossier web
2. **Pointez** Document Root vers `/public/`
3. **Visitez** `/install`
4. **Suivez** l'assistant d'installation
5. **Configurez** vos premiers clients

*Installation complète en moins de 5 minutes !*

---

**Support**: Consultez la documentation ou les logs d'erreur dans `storage/logs/`
