<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Correcteur Structure - Dashboard Retell AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .status-ok { color: #28a745; }
        .status-error { color: #dc3545; }
        .status-warning { color: #ffc107; }
        .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .progress { height: 8px; border-radius: 4px; }
        .btn-success { background: linear-gradient(45deg, #28a745, #20c997); border: none; }
        .log-area { background: #f8f9fa; border-radius: 8px; padding: 15px; font-family: 'Courier New', monospace; font-size: 14px; max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header bg-warning text-dark text-center">
                        <h3><i class="fas fa-tools"></i> 🔧 Correcteur Structure - Dashboard Retell AI</h3>
                        <p class="mb-0">Correction de la structure des tables et ajout des données</p>
                    </div>
                    <div class="card-body">

<?php
// Configuration de la base de données depuis .env
function getEnvValue($key, $default = null) {
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile)) {
        return $default;
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, $key . '=') === 0) {
            return substr($line, strlen($key) + 1);
        }
    }
    return $default;
}

$host = getEnvValue('DB_HOST', '127.0.0.1');
$port = getEnvValue('DB_PORT', '3306');
$database = getEnvValue('DB_DATABASE', 'retell');
$username = getEnvValue('DB_USERNAME', 'retell');
$password = getEnvValue('DB_PASSWORD', '');

echo "<div class='alert alert-info'>";
echo "<h5>📋 Configuration Base de Données</h5>";
echo "<strong>Host:</strong> $host<br>";
echo "<strong>Port:</strong> $port<br>";
echo "<strong>Database:</strong> $database<br>";
echo "<strong>Username:</strong> $username<br>";
echo "<strong>Password:</strong> " . (empty($password) ? 'Vide' : str_repeat('*', strlen($password)));
echo "</div>";

$log = [];

function addLog($message, $type = 'info') {
    global $log;
    $timestamp = date('H:i:s');
    $icon = $type == 'success' ? '✅' : ($type == 'error' ? '❌' : ($type == 'warning' ? '⚠️' : 'ℹ️'));
    $log[] = "[$timestamp] $icon $message";
    echo "<div class='alert alert-" . ($type == 'error' ? 'danger' : ($type == 'warning' ? 'warning' : ($type == 'success' ? 'success' : 'info'))) . " py-2'>$icon $message</div>";
    flush();
}

try {
    // Connexion à la base de données
    addLog("🔄 Connexion à la base de données...", 'info');
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    addLog("Connexion réussie à la base '$database'", 'success');

    // Fonction pour vérifier si une colonne existe
    function columnExists($pdo, $table, $column) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    }

    // Fonction pour vérifier si une table existe
    function tableExists($pdo, $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        return $stmt->rowCount() > 0;
    }

    // === GESTION TABLE CLIENTS ===
    addLog("🔍 Vérification de la table 'clients'...", 'info');
    
    if (!tableExists($pdo, 'clients')) {
        addLog("🔄 Création de la table 'clients'...", 'info');
        $createClients = "
        CREATE TABLE `clients` (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL UNIQUE,
            `password` varchar(255) NOT NULL,
            `logo_url` varchar(255) NULL,
            `primary_color` varchar(7) DEFAULT '#007bff',
            `subdomain` varchar(255) NOT NULL UNIQUE,
            `retell_api_key` varchar(255) NULL,
            `webhook_url` varchar(255) NULL,
            `is_active` boolean DEFAULT true,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($createClients);
        addLog("Table 'clients' créée avec succès", 'success');
    } else {
        addLog("Table 'clients' existe déjà", 'info');
        
        // Vérifier et ajouter les colonnes manquantes
        $requiredColumns = [
            'name' => "VARCHAR(255) NOT NULL",
            'email' => "VARCHAR(255) NOT NULL",
            'password' => "VARCHAR(255) NOT NULL", 
            'logo_url' => "VARCHAR(255) NULL",
            'primary_color' => "VARCHAR(7) DEFAULT '#007bff'",
            'subdomain' => "VARCHAR(255) NOT NULL",
            'retell_api_key' => "VARCHAR(255) NULL",
            'webhook_url' => "VARCHAR(255) NULL",
            'is_active' => "BOOLEAN DEFAULT true",
            'created_at' => "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP",
            'updated_at' => "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        foreach ($requiredColumns as $column => $definition) {
            if (!columnExists($pdo, 'clients', $column)) {
                addLog("➕ Ajout de la colonne 'clients.$column'...", 'warning');
                try {
                    $pdo->exec("ALTER TABLE `clients` ADD COLUMN `$column` $definition");
                    addLog("Colonne 'clients.$column' ajoutée", 'success');
                } catch (Exception $e) {
                    addLog("Erreur ajout colonne 'clients.$column': " . $e->getMessage(), 'error');
                }
            }
        }
        
        // Ajouter des index si nécessaires
        try {
            $pdo->exec("ALTER TABLE `clients` ADD UNIQUE KEY `clients_email_unique` (`email`)");
        } catch (Exception $e) {
            // Index existe déjà, ignore
        }
        
        try {
            $pdo->exec("ALTER TABLE `clients` ADD UNIQUE KEY `clients_subdomain_unique` (`subdomain`)");
        } catch (Exception $e) {
            // Index existe déjà, ignore
        }
    }

    // === GESTION TABLE CALL_ANALYSES ===
    addLog("🔍 Vérification de la table 'call_analyses'...", 'info');
    
    if (!tableExists($pdo, 'call_analyses')) {
        addLog("🔄 Création de la table 'call_analyses'...", 'info');
        $createCallAnalyses = "
        CREATE TABLE `call_analyses` (
            `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `client_id` bigint UNSIGNED NOT NULL,
            `call_id` varchar(255) NOT NULL,
            `success_rate` decimal(5,2) DEFAULT 0.00,
            `transfer_rate` decimal(5,2) DEFAULT 0.00,
            `total_calls` int DEFAULT 0,
            `avg_latency` decimal(8,2) DEFAULT 0.00,
            `sentiment` varchar(50) DEFAULT 'neutral',
            `custom_metric` json NULL,
            `call_date` timestamp DEFAULT CURRENT_TIMESTAMP,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_client_id` (`client_id`),
            INDEX `idx_call_id` (`call_id`),
            INDEX `idx_call_date` (`call_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($createCallAnalyses);
        addLog("Table 'call_analyses' créée avec succès", 'success');
    } else {
        addLog("Table 'call_analyses' existe déjà", 'info');
        
        // Vérifier et ajouter les colonnes manquantes
        $requiredColumns = [
            'client_id' => "BIGINT UNSIGNED NOT NULL",
            'call_id' => "VARCHAR(255) NOT NULL",
            'success_rate' => "DECIMAL(5,2) DEFAULT 0.00",
            'transfer_rate' => "DECIMAL(5,2) DEFAULT 0.00",
            'total_calls' => "INT DEFAULT 0",
            'avg_latency' => "DECIMAL(8,2) DEFAULT 0.00",
            'sentiment' => "VARCHAR(50) DEFAULT 'neutral'",
            'custom_metric' => "JSON NULL",
            'call_date' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            'created_at' => "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP",
            'updated_at' => "TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        foreach ($requiredColumns as $column => $definition) {
            if (!columnExists($pdo, 'call_analyses', $column)) {
                addLog("➕ Ajout de la colonne 'call_analyses.$column'...", 'warning');
                try {
                    $pdo->exec("ALTER TABLE `call_analyses` ADD COLUMN `$column` $definition");
                    addLog("Colonne 'call_analyses.$column' ajoutée", 'success');
                } catch (Exception $e) {
                    addLog("Erreur ajout colonne 'call_analyses.$column': " . $e->getMessage(), 'error');
                }
            }
        }
    }

    // === AJOUT DES DONNÉES DE TEST ===
    
    // Vérifier s'il y a déjà des clients
    $stmt = $pdo->query("SELECT COUNT(*) FROM clients");
    $clientCount = $stmt->fetchColumn();

    if ($clientCount == 0) {
        addLog("🔄 Ajout de clients de démonstration...", 'info');
        
        // Mot de passe par défaut (hashé) : "password123"
        $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
        
        $clients = [
            [
                'name' => 'Client Démo 1',
                'email' => 'client1@example.com',
                'password' => $defaultPassword,
                'subdomain' => 'demo1',
                'primary_color' => '#007bff',
                'retell_api_key' => 'demo_api_key_1',
                'webhook_url' => 'https://retell.mak3it.org/api/webhook',
                'is_active' => 1
            ],
            [
                'name' => 'Client Démo 2', 
                'email' => 'client2@example.com',
                'password' => $defaultPassword,
                'subdomain' => 'demo2',
                'primary_color' => '#28a745',
                'retell_api_key' => 'demo_api_key_2',
                'webhook_url' => 'https://retell.mak3it.org/api/webhook',
                'is_active' => 1
            ],
            [
                'name' => 'Client Démo 3',
                'email' => 'client3@example.com', 
                'password' => $defaultPassword,
                'subdomain' => 'demo3',
                'primary_color' => '#dc3545',
                'retell_api_key' => 'demo_api_key_3',
                'webhook_url' => 'https://retell.mak3it.org/api/webhook',
                'is_active' => 1
            ]
        ];

        foreach ($clients as $client) {
            $stmt = $pdo->prepare("
                INSERT INTO clients (name, email, password, subdomain, primary_color, retell_api_key, webhook_url, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $client['name'],
                $client['email'], 
                $client['password'],
                $client['subdomain'],
                $client['primary_color'],
                $client['retell_api_key'],
                $client['webhook_url'],
                $client['is_active']
            ]);
            addLog("Client '{$client['name']}' ajouté", 'success');
        }
    } else {
        addLog("$clientCount clients existants trouvés", 'info');
    }

    // Vérifier s'il y a déjà des analyses
    $stmt = $pdo->query("SELECT COUNT(*) FROM call_analyses");
    $analysisCount = $stmt->fetchColumn();

    if ($analysisCount == 0) {
        addLog("🔄 Ajout d'analyses de test...", 'info');
        
        // Récupérer les IDs des clients
        $stmt = $pdo->query("SELECT id FROM clients LIMIT 3");
        $clientIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($clientIds)) {
            $analyses = [
                [
                    'client_id' => $clientIds[0],
                    'call_id' => 'call_001',
                    'success_rate' => 92.5,
                    'transfer_rate' => 12.3,
                    'total_calls' => 150,
                    'avg_latency' => 245.67,
                    'sentiment' => 'positive',
                    'custom_metric' => json_encode(['satisfaction' => 4.5, 'resolution_time' => 180])
                ],
                [
                    'client_id' => $clientIds[0],
                    'call_id' => 'call_002', 
                    'success_rate' => 88.2,
                    'transfer_rate' => 15.7,
                    'total_calls' => 203,
                    'avg_latency' => 189.23,
                    'sentiment' => 'neutral',
                    'custom_metric' => json_encode(['satisfaction' => 3.8, 'resolution_time' => 220])
                ],
                [
                    'client_id' => isset($clientIds[1]) ? $clientIds[1] : $clientIds[0],
                    'call_id' => 'call_003',
                    'success_rate' => 95.1,
                    'transfer_rate' => 8.4,
                    'total_calls' => 87,
                    'avg_latency' => 156.89,
                    'sentiment' => 'positive',
                    'custom_metric' => json_encode(['satisfaction' => 4.8, 'resolution_time' => 145])
                ],
                [
                    'client_id' => isset($clientIds[2]) ? $clientIds[2] : $clientIds[0],
                    'call_id' => 'call_004',
                    'success_rate' => 79.3,
                    'transfer_rate' => 22.1,
                    'total_calls' => 312,
                    'avg_latency' => 298.45,
                    'sentiment' => 'negative',
                    'custom_metric' => json_encode(['satisfaction' => 2.9, 'resolution_time' => 385])
                ],
                [
                    'client_id' => isset($clientIds[1]) ? $clientIds[1] : $clientIds[0],
                    'call_id' => 'call_005',
                    'success_rate' => 91.7,
                    'transfer_rate' => 11.2,
                    'total_calls' => 165,
                    'avg_latency' => 203.76,
                    'sentiment' => 'positive',
                    'custom_metric' => json_encode(['satisfaction' => 4.2, 'resolution_time' => 195])
                ]
            ];

            foreach ($analyses as $analysis) {
                $stmt = $pdo->prepare("
                    INSERT INTO call_analyses (client_id, call_id, success_rate, transfer_rate, total_calls, avg_latency, sentiment, custom_metric, call_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW() - INTERVAL FLOOR(RAND() * 30) DAY)
                ");
                $stmt->execute([
                    $analysis['client_id'],
                    $analysis['call_id'],
                    $analysis['success_rate'],
                    $analysis['transfer_rate'],
                    $analysis['total_calls'],
                    $analysis['avg_latency'],
                    $analysis['sentiment'],
                    $analysis['custom_metric']
                ]);
                addLog("Analyse '{$analysis['call_id']}' ajoutée", 'success');
            }
        }
    } else {
        addLog("$analysisCount analyses existantes trouvées", 'info');
    }

    // Vérification finale
    addLog("🔍 Vérification finale des tables...", 'info');
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM clients");
    $finalClientCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM call_analyses");
    $finalAnalysisCount = $stmt->fetchColumn();
    
    addLog("Base de données prête: $finalClientCount clients, $finalAnalysisCount analyses", 'success');
    
    echo "<div class='alert alert-success mt-4'>";
    echo "<h5>🎉 Correction Structure Terminée avec Succès!</h5>";
    echo "<p><strong>Prochaines étapes:</strong></p>";
    echo "<ol>";
    echo "<li>Visitez <a href='/admin' class='alert-link' target='_blank'>/admin</a> pour accéder au tableau de bord</li>";
    echo "<li>Utilisez vos identifiants admin: <strong>jeanslarose@gmail.com</strong></li>";
    echo "<li>Les sections Clients et Analytics devraient maintenant fonctionner</li>";
    echo "<li>Mot de passe clients de test: <strong>password123</strong></li>";
    echo "<li>Supprimez ce fichier après vérification</li>";
    echo "</ol>";
    echo "</div>";

} catch (Exception $e) {
    addLog("ERREUR: " . $e->getMessage(), 'error');
    echo "<div class='alert alert-danger mt-4'>";
    echo "<h5>❌ Erreur lors de la correction</h5>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fichier:</strong> " . $e->getFile() . " (ligne " . $e->getLine() . ")</p>";
    echo "</div>";
}
?>

                        <div class="mt-4">
                            <h5>📋 Journal des Opérations</h5>
                            <div class="log-area">
                                <?php
                                if (!empty($log)) {
                                    foreach ($log as $logEntry) {
                                        echo htmlspecialchars($logEntry) . "\n";
                                    }
                                } else {
                                    echo "Aucune opération effectuée.";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="/admin" class="btn btn-success btn-lg">
                                🚀 Accéder à l'Administration
                            </a>
                            <a href="/" class="btn btn-outline-primary btn-lg ms-2">
                                🏠 Retour à l'Accueil
                            </a>
                        </div>

                    </div>
                    <div class="card-footer text-center text-muted">
                        <small>Dashboard Retell AI - Correcteur Structure v1.0</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>