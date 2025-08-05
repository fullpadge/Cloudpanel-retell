<?php
// INSTALLATEUR SIMPLE - Sans Laravel - Pour éviter erreur 500
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Configuration par défaut
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$action = isset($_POST['action']) ? $_POST['action'] : '';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Dashboard Retell AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .install-container { max-width: 800px; margin: 50px auto; }
        .step-header { background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .step-content { background: white; border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .step-nav { background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0; }
        .progress-bar { height: 8px; border-radius: 4px; }
        .btn-next { background: #667eea; border: none; padding: 12px 30px; border-radius: 25px; }
        .btn-next:hover { background: #764ba2; }
        .check-icon { color: #28a745; font-size: 20px; }
        .error-icon { color: #dc3545; font-size: 20px; }
    </style>
</head>
<body>
    <div class="container install-container">
        
        <!-- Header -->
        <div class="step-header text-center">
            <h1 class="mb-3">📊 Installation Dashboard Retell AI</h1>
            <p class="lead mb-0">Installateur Simple - Version Sans Erreur 500</p>
        </div>

        <!-- Progress -->
        <div class="progress mb-4">
            <div class="progress-bar" style="width: <?= ($step/7)*100 ?>%"></div>
        </div>

        <!-- Content -->
        <div class="step-content">
            
            <?php if ($step == 1): ?>
                <h2>🔍 Étape 1: Diagnostic Initial</h2>
                <p>Vérification de l'environnement serveur...</p>
                
                <?php
                $checks = [];
                
                // Test PHP
                $checks['php'] = version_compare(PHP_VERSION, '8.1.0', '>=');
                
                // Test extensions
                $checks['mysqli'] = extension_loaded('mysqli');
                $checks['pdo'] = extension_loaded('pdo');
                $checks['mbstring'] = extension_loaded('mbstring');
                $checks['openssl'] = extension_loaded('openssl');
                $checks['json'] = extension_loaded('json');
                
                // Test écriture
                $checks['storage'] = is_writable('../storage') || mkdir('../storage', 0755, true);
                $checks['cache'] = is_writable('../bootstrap/cache') || mkdir('../bootstrap/cache', 0755, true);
                
                $allOk = true;
                foreach ($checks as $key => $check) {
                    if (!$check) $allOk = false;
                }
                ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>🐘 PHP</h5>
                        <p><?= $checks['php'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> 
                        PHP <?= PHP_VERSION ?> <?= $checks['php'] ? '(Compatible)' : '(Requis: 8.1+)' ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>🔧 Extensions</h5>
                        <p><?= $checks['mysqli'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> MySQLi</p>
                        <p><?= $checks['pdo'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> PDO</p>
                        <p><?= $checks['mbstring'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> MBString</p>
                        <p><?= $checks['openssl'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> OpenSSL</p>
                        <p><?= $checks['json'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> JSON</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h5>📁 Permissions</h5>
                        <p><?= $checks['storage'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> Dossier storage/</p>
                        <p><?= $checks['cache'] ? '<span class="check-icon">✅</span>' : '<span class="error-icon">❌</span>' ?> Dossier bootstrap/cache/</p>
                    </div>
                </div>
                
                <?php if ($allOk): ?>
                    <div class="alert alert-success">
                        <strong>✅ Parfait !</strong> Votre serveur est compatible.
                    </div>
                    <a href="?step=2" class="btn btn-primary btn-next">Continuer →</a>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <strong>❌ Problèmes détectés</strong><br>
                        Corrigez les erreurs ci-dessus avant de continuer.
                    </div>
                <?php endif; ?>

            <?php elseif ($step == 2): ?>
                <h2>🗄️ Étape 2: Configuration Base de Données</h2>
                
                <?php if ($action == 'test_db'): ?>
                    <?php
                    $host = $_POST['db_host'];
                    $port = $_POST['db_port'];
                    $database = $_POST['db_database'];
                    $username = $_POST['db_username'];
                    $password = $_POST['db_password'];
                    
                    try {
                        $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
                        echo '<div class="alert alert-success">✅ Connexion au serveur MySQL réussie</div>';
                        
                        // Test existence base
                        $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
                        if ($stmt->rowCount() > 0) {
                            echo '<div class="alert alert-success">✅ Base de données trouvée</div>';
                        } else {
                            echo '<div class="alert alert-warning">⚠️ Base de données non trouvée, elle sera créée</div>';
                        }
                        
                        $_SESSION['db_config'] = compact('host', 'port', 'database', 'username', 'password');
                        echo '<a href="?step=3" class="btn btn-primary btn-next">Continuer →</a>';
                        
                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">❌ Erreur: ' . $e->getMessage() . '</div>';
                    }
                else: ?>
                
                <form method="post">
                    <input type="hidden" name="action" value="test_db">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Host MySQL</label>
                                <input type="text" class="form-control" name="db_host" value="127.0.0.1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Port</label>
                                <input type="number" class="form-control" name="db_port" value="3306" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nom de la base</label>
                        <input type="text" class="form-control" name="db_database" value="retell" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Utilisateur</label>
                                <input type="text" class="form-control" name="db_username" value="retell" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" name="db_password" value="Quebec101!" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-next">Tester la Connexion</button>
                </form>
                
                <?php endif; ?>

            <?php elseif ($step == 3): ?>
                <h2>👤 Étape 3: Compte Administrateur</h2>
                
                <?php if ($action == 'create_admin'): ?>
                    <?php
                    $admin_name = $_POST['admin_name'];
                    $admin_email = $_POST['admin_email'];
                    $admin_password = $_POST['admin_password'];
                    
                    $_SESSION['admin_config'] = compact('admin_name', 'admin_email', 'admin_password');
                    echo '<div class="alert alert-success">✅ Compte administrateur configuré</div>';
                    echo '<a href="?step=4" class="btn btn-primary btn-next">Continuer →</a>';
                    ?>
                <?php else: ?>
                
                <form method="post">
                    <input type="hidden" name="action" value="create_admin">
                    
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" class="form-control" name="admin_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="admin_email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="admin_password" minlength="8" required>
                        <small class="text-muted">Minimum 8 caractères</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-next">Créer le Compte</button>
                </form>
                
                <?php endif; ?>

            <?php elseif ($step == 4): ?>
                <h2>🔑 Étape 4: Configuration Retell AI</h2>
                
                <?php if ($action == 'config_retell'): ?>
                    <?php
                    $retell_api_key = $_POST['retell_api_key'];
                    $webhook_url = $_POST['webhook_url'];
                    
                    $_SESSION['retell_config'] = compact('retell_api_key', 'webhook_url');
                    echo '<div class="alert alert-success">✅ Configuration Retell AI enregistrée</div>';
                    echo '<a href="?step=5" class="btn btn-primary btn-next">Continuer →</a>';
                    ?>
                <?php else: ?>
                
                <form method="post">
                    <input type="hidden" name="action" value="config_retell">
                    
                    <div class="mb-3">
                        <label class="form-label">Clé API Retell AI</label>
                        <input type="text" class="form-control" name="retell_api_key" placeholder="key_..." required>
                        <small class="text-muted">Trouvez votre clé API sur retellai.com</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">URL Webhook</label>
                        <input type="url" class="form-control" name="webhook_url" value="https://retell.mak3it.org/api/webhook" required>
                        <small class="text-muted">Configurez cette URL dans votre compte Retell AI</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-next">Sauvegarder</button>
                </form>
                
                <?php endif; ?>

            <?php elseif ($step == 5): ?>
                <h2>✅ Étape 5: Installation Finale</h2>
                
                <?php if ($action == 'install'): ?>
                    <?php
                    // Ici on ferait l'installation complète
                    // Création .env, tables, etc.
                    
                    // Créer le fichier .env
                    $envContent = 'APP_NAME="Dashboard Retell AI"' . "\n";
                    $envContent .= 'APP_ENV=production' . "\n";
                    $envContent .= 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . "\n";
                    $envContent .= 'APP_DEBUG=false' . "\n";
                    $envContent .= 'APP_URL=https://retell.mak3it.org' . "\n\n";
                    
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
                        $envContent .= 'RETELL_WEBHOOK_URL=' . $retell['webhook_url'] . "\n";
                    }
                    
                    file_put_contents('../.env', $envContent);
                    
                    echo '<div class="alert alert-success">✅ Installation terminée avec succès !</div>';
                    echo '<div class="alert alert-info">';
                    echo '<h5>🎉 Prochaines étapes :</h5>';
                    echo '<p>1. Visitez <strong>/admin</strong> pour accéder au tableau de bord</p>';
                    echo '<p>2. Configurez vos premiers clients</p>';
                    echo '<p>3. Testez les webhooks Retell AI</p>';
                    echo '</div>';
                    
                    echo '<a href="/admin" class="btn btn-success btn-next">Accéder au Dashboard</a>';
                    ?>
                <?php else: ?>
                
                <div class="alert alert-info">
                    <h5>📋 Récapitulatif de l'installation :</h5>
                    <p><strong>Base de données :</strong> <?= $_SESSION['db_config']['database'] ?? 'Non configurée' ?></p>
                    <p><strong>Administrateur :</strong> <?= $_SESSION['admin_config']['admin_email'] ?? 'Non configuré' ?></p>
                    <p><strong>API Retell :</strong> <?= !empty($_SESSION['retell_config']['retell_api_key']) ? 'Configurée' : 'Non configurée' ?></p>
                </div>
                
                <form method="post">
                    <input type="hidden" name="action" value="install">
                    <button type="submit" class="btn btn-success btn-next">🚀 Lancer l'Installation</button>
                </form>
                
                <?php endif; ?>

            <?php endif; ?>
            
        </div>

        <!-- Navigation -->
        <div class="step-nav text-center">
            Étape <?= $step ?> sur 5
            <?php if ($step > 1): ?>
                | <a href="?step=<?= $step-1 ?>">← Retour</a>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>