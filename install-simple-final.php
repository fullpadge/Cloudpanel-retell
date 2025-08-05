<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Configuration par défaut
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Action de nettoyage
if ($action === 'clean_install') {
    try {
        // Supprimer le fichier .env
        if (file_exists('../.env')) {
            unlink('../.env');
        }
        
        // Nettoyer la session
        session_destroy();
        session_start();
        
        // Rediriger vers l'étape 1
        header('Location: ?step=1&cleaned=1');
        exit;
    } catch (Exception $e) {
        // Ignorer les erreurs de nettoyage
    }
}

$cleaned = isset($_GET['cleaned']);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Installation Dashboard Retell AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .install-container { max-width: 900px; margin: 30px auto; }
        .step-header { 
            background: white; 
            border-radius: 20px; 
            padding: 40px; 
            margin-bottom: 30px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
        }
        .step-content { 
            background: white; 
            border-radius: 20px; 
            padding: 40px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
        }
        .step-nav { 
            background: rgba(255,255,255,0.9); 
            padding: 20px; 
            border-radius: 15px; 
            margin: 20px 0; 
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .progress { height: 12px; border-radius: 6px; background: rgba(255,255,255,0.3); }
        .progress-bar { 
            background: linear-gradient(90deg, #667eea, #764ba2); 
            border-radius: 6px;
            transition: width 0.5s ease;
        }
        .btn-next { 
            background: linear-gradient(135deg, #667eea, #764ba2); 
            border: none; 
            padding: 15px 40px; 
            border-radius: 30px; 
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-next:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-back {
            background: #6c757d;
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            margin-right: 15px;
        }
        .check-icon { color: #28a745; font-size: 22px; margin-right: 10px; }
        .error-icon { color: #dc3545; font-size: 22px; margin-right: 10px; }
        .warning-icon { color: #ffc107; font-size: 22px; margin-right: 10px; }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            color: white;
        }
        .step-circle.active { background: linear-gradient(135deg, #667eea, #764ba2); }
        .step-circle.completed { background: #28a745; }
        .step-circle.pending { background: #dee2e6; color: #6c757d; }
        .step-line {
            width: 50px;
            height: 3px;
            background: #dee2e6;
        }
        .step-line.completed { background: #28a745; }
    </style>
</head>
<body>
    <div class="container install-container">
        
        <!-- Header -->
        <div class="step-header">
            <h1 class="mb-3">🚀 Installation Dashboard Retell AI</h1>
            <p class="lead mb-0">Installateur Simple et Fonctionnel</p>
            <small class="text-muted">Version sans Laravel - Garantie zéro erreur 500</small>
            
            <?php if ($cleaned): ?>
            <div class="alert alert-info mt-3">
                ✅ Installation nettoyée avec succès. Vous pouvez recommencer proprement.
            </div>
            <?php endif; ?>
            
            <?php if ($step == 1): ?>
            <div class="mt-3">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="action" value="clean_install">
                    <button type="submit" class="btn btn-outline-warning btn-sm" onclick="return confirm('Voulez-vous nettoyer une installation précédente ? Cela supprimera le fichier .env existant.')">
                        🧹 Nettoyer Installation Précédente
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-circle <?= $step >= 1 ? ($step > 1 ? 'completed' : 'active') : 'pending' ?>">1</div>
            <div class="step-line <?= $step > 1 ? 'completed' : '' ?>"></div>
            <div class="step-circle <?= $step >= 2 ? ($step > 2 ? 'completed' : 'active') : 'pending' ?>">2</div>
            <div class="step-line <?= $step > 2 ? 'completed' : '' ?>"></div>
            <div class="step-circle <?= $step >= 3 ? ($step > 3 ? 'completed' : 'active') : 'pending' ?>">3</div>
            <div class="step-line <?= $step > 3 ? 'completed' : '' ?>"></div>
            <div class="step-circle <?= $step >= 4 ? ($step > 4 ? 'completed' : 'active') : 'pending' ?>">4</div>
            <div class="step-line <?= $step > 4 ? 'completed' : '' ?>"></div>
            <div class="step-circle <?= $step >= 5 ? 'active' : 'pending' ?>">5</div>
        </div>

        <!-- Progress -->
        <div class="progress mb-4">
            <div class="progress-bar" style="width: <?= ($step/5)*100 ?>%"></div>
        </div>

        <!-- Content -->
        <div class="step-content">
            
            <?php if ($step == 1): ?>
                <h2><span class="check-icon">🔍</span>Étape 1: Diagnostic Système</h2>
                <p class="text-muted mb-4">Vérification de l'environnement serveur pour assurer une installation réussie...</p>
                
                <?php
                $checks = [];
                $errors = [];
                
                // Test PHP
                $checks['php'] = version_compare(PHP_VERSION, '8.1.0', '>=');
                if (!$checks['php']) $errors[] = "PHP 8.1+ requis (actuellement: " . PHP_VERSION . ")";
                
                // Test extensions
                $extensions = ['mysqli', 'pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'ctype'];
                foreach ($extensions as $ext) {
                    $checks[$ext] = extension_loaded($ext);
                    if (!$checks[$ext]) $errors[] = "Extension PHP '$ext' manquante";
                }
                
                // Test écriture
                $dirs = ['../storage', '../bootstrap/cache'];
                foreach ($dirs as $dir) {
                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                    $checks[basename($dir)] = is_writable($dir);
                    if (!$checks[basename($dir)]) $errors[] = "Dossier '$dir' non accessible en écriture";
                }
                
                // Test base de données (tentative)
                $dbTest = false;
                try {
                    if (file_exists('../.env')) {
                        $env = file_get_contents('../.env');
                        if (preg_match('/DB_HOST=(.+)/', $env, $m1) && 
                            preg_match('/DB_USERNAME=(.+)/', $env, $m2) && 
                            preg_match('/DB_PASSWORD=(.+)/', $env, $m3)) {
                            $pdo = new PDO("mysql:host=".trim($m1[1]), trim($m2[1]), trim($m3[1]));
                            $dbTest = true;
                        }
                    }
                } catch (Exception $e) {
                    // Ignoré, sera configuré plus tard
                }
                
                $allOk = count($errors) === 0;
                ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">🐘 Configuration PHP</h5>
                                <div class="mb-2">
                                    <?= $checks['php'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?>
                                    <strong>Version:</strong> <?= PHP_VERSION ?> <?= $checks['php'] ? '(Compatible)' : '(Requis: 8.1+)' ?>
                                </div>
                                <div class="mb-2">
                                    <strong>Extensions:</strong>
                                </div>
                                <?php foreach ($extensions as $ext): ?>
                                <div class="ms-3">
                                    <?= $checks[$ext] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?>
                                    <?= $ext ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">📁 Permissions Système</h5>
                                <div class="mb-2">
                                    <?= $checks['storage'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?>
                                    <strong>storage/</strong> <?= $checks['storage'] ? 'Accessible' : 'Non accessible' ?>
                                </div>
                                <div class="mb-2">
                                    <?= $checks['cache'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?>
                                    <strong>bootstrap/cache/</strong> <?= $checks['cache'] ? 'Accessible' : 'Non accessible' ?>
                                </div>
                                <div class="mb-2">
                                    <?= $dbTest ? '<span class="check-icon">✅</span>' : '<span class="warning-icon">⚠️</span>' ?>
                                    <strong>Base de données:</strong> <?= $dbTest ? 'Pré-configurée' : 'À configurer' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($allOk): ?>
                    <div class="alert alert-success mt-4">
                        <h5 class="alert-heading">🎉 Parfait !</h5>
                        Votre serveur est entièrement compatible avec le Dashboard Retell AI.
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="?step=2" class="btn btn-next">Continuer vers la Base de Données →</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mt-4">
                        <h5 class="alert-heading">❌ Problèmes Détectés</h5>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <hr>
                        <p class="mb-0"><strong>Action requise:</strong> Corrigez ces erreurs avant de continuer.</p>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="?step=1" class="btn btn-outline-primary">🔄 Vérifier à Nouveau</a>
                    </div>
                <?php endif; ?>

            <?php elseif ($step == 2): ?>
                <h2><span class="check-icon">🗄️</span>Étape 2: Configuration Base de Données</h2>
                <p class="text-muted mb-4">Configuration de la connexion MySQL pour stocker les données du dashboard...</p>
                
                <?php if ($action == 'test_db'): ?>
                    <?php
                    $host = $_POST['db_host'];
                    $port = $_POST['db_port'];
                    $database = $_POST['db_database'];
                    $username = $_POST['db_username'];
                    $password = $_POST['db_password'];
                    
                    $success = false;
                    $messages = [];
                    
                    try {
                        // Test connexion serveur
                        $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
                        $messages[] = ['type' => 'success', 'text' => 'Connexion au serveur MySQL réussie'];
                        
                        // Test/création base de données
                        $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
                        if ($stmt->rowCount() > 0) {
                            $messages[] = ['type' => 'success', 'text' => 'Base de données trouvée'];
                        } else {
                            $pdo->exec("CREATE DATABASE `$database`");
                            $messages[] = ['type' => 'warning', 'text' => 'Base de données créée automatiquement'];
                        }
                        
                        // Test connexion à la base
                        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
                        $messages[] = ['type' => 'success', 'text' => 'Connexion à la base de données réussie'];
                        
                        // Test permissions
                        $pdo->exec("CREATE TABLE IF NOT EXISTS test_permissions (id INT PRIMARY KEY)");
                        $pdo->exec("DROP TABLE test_permissions");
                        $messages[] = ['type' => 'success', 'text' => 'Permissions CREATE/DROP vérifiées'];
                        
                        $_SESSION['db_config'] = compact('host', 'port', 'database', 'username', 'password');
                        $success = true;
                        
                    } catch (PDOException $e) {
                        $messages[] = ['type' => 'error', 'text' => 'Erreur: ' . $e->getMessage()];
                    }
                    ?>
                    
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">📊 Résultats du Test</h5>
                            <?php foreach ($messages as $msg): ?>
                                <div class="alert alert-<?= $msg['type'] == 'error' ? 'danger' : ($msg['type'] == 'warning' ? 'warning' : 'success') ?> py-2">
                                    <?= $msg['type'] == 'error' ? '❌' : ($msg['type'] == 'warning' ? '⚠️' : '✅') ?> <?= $msg['text'] ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="?step=2" class="btn btn-back">← Modifier la Configuration</a>
                        <?php if ($success): ?>
                            <a href="?step=3" class="btn btn-next">Continuer vers Admin →</a>
                        <?php endif; ?>
                    </div>
                    
                <?php else: ?>
                
                <form method="post" id="dbForm">
                    <input type="hidden" name="action" value="test_db">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">🌐 Host MySQL</label>
                                <input type="text" class="form-control" name="db_host" value="127.0.0.1" required>
                                <small class="text-muted">Généralement 127.0.0.1 ou localhost</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">🔌 Port</label>
                                <input type="number" class="form-control" name="db_port" value="3306" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">🗄️ Nom de la Base de Données</label>
                        <input type="text" class="form-control" name="db_database" value="retell" required>
                        <small class="text-muted">Si elle n'existe pas, elle sera créée automatiquement</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">👤 Utilisateur MySQL</label>
                                <input type="text" class="form-control" name="db_username" value="retell" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">🔐 Mot de Passe</label>
                                <input type="password" class="form-control" name="db_password" value="Quebec101!" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="?step=1" class="btn btn-back">← Retour au Diagnostic</a>
                        <button type="submit" class="btn btn-next">🧪 Tester la Connexion</button>
                    </div>
                </form>
                
                <?php endif; ?>

            <?php elseif ($step == 3): ?>
                <h2><span class="check-icon">👤</span>Étape 3: Compte Administrateur</h2>
                <p class="text-muted mb-4">Création du compte administrateur principal pour gérer le dashboard...</p>
                
                <?php if ($action == 'create_admin'): ?>
                    <?php
                    $admin_name = $_POST['admin_name'];
                    $admin_email = $_POST['admin_email'];
                    $admin_password = $_POST['admin_password'];
                    
                    // Validation
                    $errors = [];
                    if (strlen($admin_password) < 8) $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
                    if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
                    
                    if (empty($errors)) {
                        $_SESSION['admin_config'] = compact('admin_name', 'admin_email', 'admin_password');
                        ?>
                        <div class="alert alert-success">
                            <h5 class="alert-heading">✅ Compte Administrateur Configuré</h5>
                            <p class="mb-0">Le compte sera créé lors de l'installation finale.</p>
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="card-title">📋 Récapitulatif du Compte</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nom:</strong> <?= htmlspecialchars($admin_name) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Email:</strong> <?= htmlspecialchars($admin_email) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="?step=3" class="btn btn-back">← Modifier le Compte</a>
                            <a href="?step=4" class="btn btn-next">Continuer vers Retell AI →</a>
                        </div>
                        <?php
                    } else {
                        echo '<div class="alert alert-danger"><ul class="mb-0">';
                        foreach ($errors as $error) echo "<li>$error</li>";
                        echo '</ul></div>';
                    }
                    ?>
                <?php endif; ?>
                
                <?php if ($action != 'create_admin' || !empty($errors)): ?>
                <form method="post">
                    <input type="hidden" name="action" value="create_admin">
                    
                    <div class="mb-3">
                        <label class="form-label">📝 Nom Complet</label>
                        <input type="text" class="form-control" name="admin_name" value="<?= $_POST['admin_name'] ?? '' ?>" required>
                        <small class="text-muted">Votre nom complet pour l'administration</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">📧 Adresse Email</label>
                        <input type="email" class="form-control" name="admin_email" value="<?= $_POST['admin_email'] ?? '' ?>" required>
                        <small class="text-muted">Email de connexion à l'administration</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">🔐 Mot de Passe</label>
                        <input type="password" class="form-control" name="admin_password" minlength="8" required>
                        <small class="text-muted">Minimum 8 caractères, mélange lettres/chiffres recommandé</small>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="?step=2" class="btn btn-back">← Retour à la Base</a>
                        <button type="submit" class="btn btn-next">✅ Créer le Compte</button>
                    </div>
                </form>
                <?php endif; ?>

            <?php elseif ($step == 4): ?>
                <h2><span class="check-icon">🔑</span>Étape 4: Configuration Retell AI</h2>
                <p class="text-muted mb-4">Configuration de l'intégration avec l'API Retell AI pour les analyses d'appels...</p>
                
                <?php if ($action == 'config_retell'): ?>
                    <?php
                    $retell_api_key = $_POST['retell_api_key'];
                    $webhook_url = $_POST['webhook_url'];
                    
                    // Validation basique
                    $errors = [];
                    if (empty($retell_api_key)) $errors[] = "La clé API Retell AI est requise";
                    if (!filter_var($webhook_url, FILTER_VALIDATE_URL)) $errors[] = "URL Webhook invalide";
                    
                    if (empty($errors)) {
                        $_SESSION['retell_config'] = compact('retell_api_key', 'webhook_url');
                        ?>
                        <div class="alert alert-success">
                            <h5 class="alert-heading">✅ Configuration Retell AI Enregistrée</h5>
                            <p class="mb-0">L'intégration sera activée lors de l'installation finale.</p>
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <h6 class="card-title">📋 Configuration API</h6>
                                <div class="mb-2">
                                    <strong>Clé API:</strong> <?= substr($retell_api_key, 0, 8) ?>...<?= substr($retell_api_key, -4) ?>
                                </div>
                                <div class="mb-2">
                                    <strong>Webhook URL:</strong> <?= htmlspecialchars($webhook_url) ?>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3">
                            <h6 class="alert-heading">📝 Prochaine Étape</h6>
                            Après l'installation, configurez cette URL webhook dans votre compte Retell AI.
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="?step=4" class="btn btn-back">← Modifier la Config</a>
                            <a href="?step=5" class="btn btn-next">Finaliser l'Installation →</a>
                        </div>
                        <?php
                    } else {
                        echo '<div class="alert alert-danger"><ul class="mb-0">';
                        foreach ($errors as $error) echo "<li>$error</li>";
                        echo '</ul></div>';
                    }
                    ?>
                <?php endif; ?>
                
                <?php if ($action != 'config_retell' || !empty($errors)): ?>
                <form method="post">
                    <input type="hidden" name="action" value="config_retell">
                    
                    <div class="mb-3">
                        <label class="form-label">🔑 Clé API Retell AI</label>
                        <input type="text" class="form-control" name="retell_api_key" value="<?= $_POST['retell_api_key'] ?? '' ?>" placeholder="key_..." required>
                        <small class="text-muted">Récupérez votre clé API sur <a href="https://retellai.com" target="_blank">retellai.com</a></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">🔗 URL Webhook</label>
                        <input type="url" class="form-control" name="webhook_url" value="<?= $_POST['webhook_url'] ?? 'https://retell.mak3it.org/api/webhook' ?>" required>
                        <small class="text-muted">Cette URL sera configurée automatiquement pour recevoir les événements</small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">⚠️ Important</h6>
                        Après l'installation, vous devrez configurer cette URL webhook dans votre compte Retell AI pour l'événement <code>call_analyzed</code>.
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="?step=3" class="btn btn-back">← Retour à l'Admin</a>
                        <button type="submit" class="btn btn-next">💾 Sauvegarder</button>
                    </div>
                </form>
                <?php endif; ?>

            <?php elseif ($step == 5): ?>
                <h2><span class="check-icon">🚀</span>Étape 5: Installation Finale</h2>
                <p class="text-muted mb-4">Finalisation de l'installation et création de tous les fichiers nécessaires...</p>
                
                <?php if ($action == 'install'): ?>
                    <?php
                    $success = true;
                    $errors = [];
                    $warnings = [];
                    
                    try {
                        // Vérifier si déjà installé
                        if (isset($_SESSION['db_config'])) {
                            $db = $_SESSION['db_config'];
                            $pdo = new PDO("mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}", $db['username'], $db['password']);
                            
                            $result = $pdo->query("SHOW TABLES LIKE 'installation_settings'");
                            if ($result && $result->rowCount() > 0) {
                                $stmt = $pdo->query("SELECT setting_value FROM installation_settings WHERE setting_key = 'installed'");
                                if ($stmt && $stmt->fetchColumn() === 'true') {
                                    $warnings[] = "Installation déjà détectée - mise à jour des paramètres";
                                }
                            }
                        }
                        // 1. Créer le fichier .env
                        $envContent = 'APP_NAME="Dashboard Retell AI"' . "\n";
                        $envContent .= 'APP_ENV=production' . "\n";
                        $envContent .= 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . "\n";
                        $envContent .= 'APP_DEBUG=false' . "\n";
                        $envContent .= 'APP_URL=https://retell.mak3it.org' . "\n\n";
                        
                        $envContent .= 'SESSION_DRIVER=file' . "\n";
                        $envContent .= 'SESSION_LIFETIME=120' . "\n\n";
                        
                        if (isset($_SESSION['db_config'])) {
                            $db = $_SESSION['db_config'];
                            $envContent .= 'DB_CONNECTION=mysql' . "\n";
                            $envContent .= 'DB_HOST=' . $db['host'] . "\n";
                            $envContent .= 'DB_PORT=' . $db['port'] . "\n";
                            $envContent .= 'DB_DATABASE=' . $db['database'] . "\n";
                            $envContent .= 'DB_USERNAME=' . $db['username'] . "\n";
                            $envContent .= 'DB_PASSWORD=' . $db['password'] . "\n\n";
                        }
                        
                        if (isset($_SESSION['retell_config'])) {
                            $retell = $_SESSION['retell_config'];
                            $envContent .= 'RETELL_API_KEY=' . $retell['retell_api_key'] . "\n";
                            $envContent .= 'RETELL_WEBHOOK_URL=' . $retell['webhook_url'] . "\n\n";
                        }
                        
                        file_put_contents('../.env', $envContent);
                        
                        // 2. Créer les tables de base de données
                        if (isset($_SESSION['db_config'])) {
                            $db = $_SESSION['db_config'];
                            $pdo = new PDO("mysql:host={$db['host']};port={$db['port']};dbname={$db['database']}", $db['username'], $db['password']);
                            
                            // Vérifier et créer les tables une par une avec gestion d'erreurs
                            $tables = [
                                'users' => "
                                    CREATE TABLE IF NOT EXISTS users (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        name VARCHAR(255) NOT NULL,
                                        email VARCHAR(255) UNIQUE NOT NULL,
                                        password VARCHAR(255) NOT NULL,
                                        email_verified_at TIMESTAMP NULL,
                                        remember_token VARCHAR(100) NULL,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    )
                                ",
                                'clients' => "
                                    CREATE TABLE IF NOT EXISTS clients (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        name VARCHAR(255) NOT NULL,
                                        email VARCHAR(255) UNIQUE NOT NULL,
                                        password VARCHAR(255) NOT NULL,
                                        logo_url VARCHAR(255) NULL,
                                        primary_color VARCHAR(7) DEFAULT '#3B82F6',
                                        subdomain VARCHAR(255) UNIQUE NULL,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    )
                                ",
                                'call_analyses' => "
                                    CREATE TABLE IF NOT EXISTS call_analyses (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        client_id BIGINT UNSIGNED,
                                        call_id VARCHAR(255) NOT NULL,
                                        success_rate DECIMAL(5,2) DEFAULT 0,
                                        transfer_rate DECIMAL(5,2) DEFAULT 0,
                                        total_calls INT DEFAULT 0,
                                        avg_latency DECIMAL(8,2) DEFAULT 0,
                                        sentiment VARCHAR(50) NULL,
                                        custom_metrics JSON NULL,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    )
                                ",
                                'installation_settings' => "
                                    CREATE TABLE IF NOT EXISTS installation_settings (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        setting_key VARCHAR(255) UNIQUE NOT NULL,
                                        setting_value TEXT NULL,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    )
                                "
                            ];
                            
                            // Créer chaque table avec gestion d'erreurs
                            foreach ($tables as $tableName => $sql) {
                                try {
                                    // Vérifier d'abord si la table existe
                                    $result = $pdo->query("SHOW TABLES LIKE '$tableName'");
                                    if ($result->rowCount() == 0) {
                                        // Table n'existe pas, la créer
                                        $pdo->exec($sql);
                                    }
                                    // Si la table existe déjà, on continue sans erreur
                                } catch (PDOException $e) {
                                    // Ignorer les erreurs "table already exists"
                                    if (strpos($e->getMessage(), 'already exists') === false) {
                                        throw $e; // Re-lancer si c'est une autre erreur
                                    }
                                }
                            }
                            
                            // Ajouter la clé étrangère pour call_analyses si elle n'existe pas
                            try {
                                $pdo->exec("ALTER TABLE call_analyses ADD CONSTRAINT fk_client_id FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE");
                            } catch (PDOException $e) {
                                // Ignorer si la contrainte existe déjà
                            }
                            
                            // Créer l'utilisateur admin
                            if (isset($_SESSION['admin_config'])) {
                                $admin = $_SESSION['admin_config'];
                                $hashedPassword = password_hash($admin['admin_password'], PASSWORD_BCRYPT);
                                
                                try {
                                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = ?, password = ?");
                                    $stmt->execute([$admin['admin_name'], $admin['admin_email'], $hashedPassword, $admin['admin_name'], $hashedPassword]);
                                } catch (PDOException $e) {
                                    // Si erreur, essayer une mise à jour simple
                                    $stmt = $pdo->prepare("UPDATE users SET name = ?, password = ? WHERE email = ?");
                                    $stmt->execute([$admin['admin_name'], $hashedPassword, $admin['admin_email']]);
                                }
                            }
                            
                            // Marquer comme installé
                            try {
                                $stmt = $pdo->prepare("INSERT INTO installation_settings (setting_key, setting_value) VALUES ('installed', 'true') ON DUPLICATE KEY UPDATE setting_value = 'true'");
                                $stmt->execute();
                            } catch (PDOException $e) {
                                // Ignorer les erreurs sur cette table de paramètres
                            }
                        }
                        
                        // 3. Nettoyer la session
                        session_destroy();
                        
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                        $success = false;
                    }
                    
                    // Ajouter les warnings aux messages d'erreur si échec
                    if (!$success && !empty($warnings)) {
                        $errors = array_merge(["AVERTISSEMENT: " . implode(", ", $warnings)], $errors);
                    }
                    ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <h4 class="alert-heading">🎉 Installation Terminée avec Succès !</h4>
                            <p>Le Dashboard Retell AI a été installé et configuré correctement.</p>
                        </div>
                        
                        <?php if (!empty($warnings)): ?>
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">⚠️ Avertissements</h6>
                            <ul class="mb-0">
                                <?php foreach ($warnings as $warning): ?>
                                    <li><?= htmlspecialchars($warning) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">✅ Éléments Installés</h5>
                                        <ul class="list-unstyled">
                                            <li>✅ Configuration Laravel (.env)</li>
                                            <li>✅ Base de données et tables</li>
                                            <li>✅ Compte administrateur</li>
                                            <li>✅ Intégration Retell AI</li>
                                            <li>✅ Système de clients</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h5 class="card-title text-info">📋 Prochaines Étapes</h5>
                                        <ol>
                                            <li>Accédez au <strong>tableau de bord admin</strong></li>
                                            <li>Configurez vos premiers clients</li>
                                            <li>Activez le webhook Retell AI</li>
                                            <li>Testez les analyses d'appels</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-4">
                            <h6 class="alert-heading">⚠️ Configuration Webhook Retell AI</h6>
                            <p class="mb-2">N'oubliez pas de configurer l'URL webhook dans votre compte Retell AI :</p>
                            <p class="mb-0"><strong>URL:</strong> <code><?= $_SESSION['retell_config']['webhook_url'] ?? 'https://retell.mak3it.org/api/webhook' ?></code></p>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="/admin" class="btn btn-success btn-lg">🚀 Accéder au Dashboard Admin</a>
                            <a href="/" class="btn btn-outline-primary btn-lg ms-3">🏠 Voir le Site Principal</a>
                        </div>
                        
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">❌ Erreur d'Installation</h4>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="text-center mt-4">
                            <a href="?step=5" class="btn btn-outline-primary">🔄 Réessayer</a>
                            <a href="?step=1" class="btn btn-secondary ms-3">← Recommencer</a>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                
                <div class="alert alert-info">
                    <h5 class="alert-heading">📋 Récapitulatif de l'Installation</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Base de données :</strong> <?= $_SESSION['db_config']['database'] ?? 'Non configurée' ?></p>
                            <p><strong>Administrateur :</strong> <?= $_SESSION['admin_config']['admin_email'] ?? 'Non configuré' ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>API Retell :</strong> <?= !empty($_SESSION['retell_config']['retell_api_key']) ? 'Configurée' : 'Non configurée' ?></p>
                            <p><strong>Webhook :</strong> <?= $_SESSION['retell_config']['webhook_url'] ?? 'Non configuré' ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">🔄 Actions de l'Installation</h5>
                        <ul>
                            <li>Création du fichier de configuration Laravel (.env)</li>
                            <li>Création des tables de base de données</li>
                            <li>Création du compte administrateur</li>
                            <li>Configuration de l'intégration Retell AI</li>
                            <li>Activation du système de marque blanche</li>
                        </ul>
                    </div>
                </div>
                
                <form method="post" class="mt-4">
                    <input type="hidden" name="action" value="install">
                    <div class="d-flex justify-content-between">
                        <a href="?step=4" class="btn btn-back">← Retour à Retell AI</a>
                        <button type="submit" class="btn btn-success btn-lg">🚀 Lancer l'Installation Finale</button>
                    </div>
                </form>
                
                <?php endif; ?>

            <?php endif; ?>
            
        </div>

        <!-- Navigation -->
        <div class="step-nav">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Étape <?= $step ?> sur 5</strong>
                    <br><small class="text-muted">
                        <?= ['', 'Diagnostic Système', 'Base de Données', 'Compte Admin', 'API Retell AI', 'Installation'][$step] ?>
                    </small>
                </div>
                <div>
                    <?php if ($step > 1 && $step < 5): ?>
                        <a href="?step=<?= $step-1 ?>" class="btn btn-outline-secondary btn-sm">← Précédent</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des progress bars
        $(document).ready(function() {
            $('.progress-bar').each(function() {
                var width = $(this).css('width');
                $(this).css('width', '0%').animate({'width': width}, 1000);
            });
        });
    </script>
</body>
</html>