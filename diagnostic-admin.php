<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🚨 DIAGNOSTIC ADMINISTRATION - Dashboard Retell AI</h1>";
echo "<hr>";

try {
    // Inclure Laravel
    require '../vendor/autoload.php';
    $app = require '../bootstrap/app.php';
    echo "✅ Laravel chargé avec succès<br>";
    
    // Test base de données
    echo "<h2>🗄️ Test Base de Données</h2>";
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=retell", "retell", "Quebec101!");
    echo "✅ Connexion base de données OK<br>";
    
    // Vérifier les tables existantes
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>📋 Tables Existantes (" . count($tables) . "):</h3>";
    foreach ($tables as $table) {
        echo "- " . $table . "<br>";
    }
    
    // Vérifier les tables spécifiques Retell AI
    $requiredTables = ['users', 'clients', 'call_analyses', 'installation_settings'];
    $missingTables = [];
    
    echo "<h3>🔍 Vérification Tables Requises:</h3>";
    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "✅ " . $table . " - Existe<br>";
        } else {
            echo "❌ " . $table . " - MANQUANTE<br>";
            $missingTables[] = $table;
        }
    }
    
    // Vérifier l'utilisateur admin
    echo "<h3>👤 Vérification Utilisateur Admin:</h3>";
    $user = $pdo->query("SELECT * FROM users WHERE email = 'jeanslarose@gmail.com'")->fetch();
    if ($user) {
        echo "✅ Utilisateur trouvé: " . $user['name'] . " (" . $user['email'] . ")<br>";
        echo "📅 Créé le: " . $user['created_at'] . "<br>";
    } else {
        echo "❌ Utilisateur admin non trouvé<br>";
    }
    
    // Test des routes d'administration
    echo "<h2>🛣️ Test Routes Administration</h2>";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $adminRoutes = [
        '/admin',
        '/admin/clients',
        '/admin/clients/create',
        '/admin/settings'
    ];
    
    foreach ($adminRoutes as $route) {
        try {
            $request = Illuminate\Http\Request::create($route, 'GET');
            ob_start();
            $response = $kernel->handle($request);
            $output = ob_get_clean();
            
            $status = $response->getStatusCode();
            if ($status == 200) {
                echo "✅ " . $route . " - Status " . $status . " (OK)<br>";
            } elseif ($status == 302) {
                echo "🔄 " . $route . " - Status " . $status . " (Redirection)<br>";
            } elseif ($status == 404) {
                echo "❌ " . $route . " - Status " . $status . " (Route non trouvée)<br>";
            } else {
                echo "⚠️ " . $route . " - Status " . $status . "<br>";
            }
        } catch (Exception $e) {
            echo "❌ " . $route . " - ERREUR: " . $e->getMessage() . "<br>";
        }
    }
    
    // Test des contrôleurs
    echo "<h2>🎛️ Test Contrôleurs</h2>";
    
    $controllers = [
        'App\Http\Controllers\InstallController',
        'App\Http\Controllers\AdminController', 
        'App\Http\Controllers\ClientController'
    ];
    
    foreach ($controllers as $controller) {
        try {
            if (class_exists($controller)) {
                echo "✅ " . $controller . " - Existe<br>";
                $reflection = new ReflectionClass($controller);
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                echo "   📋 Méthodes: ";
                foreach ($methods as $method) {
                    if (!$method->isConstructor() && !$method->isDestructor()) {
                        echo $method->getName() . "() ";
                    }
                }
                echo "<br>";
            } else {
                echo "❌ " . $controller . " - N'existe pas<br>";
            }
        } catch (Exception $e) {
            echo "❌ " . $controller . " - ERREUR: " . $e->getMessage() . "<br>";
        }
    }
    
    // Test des vues
    echo "<h2>👁️ Test Vues Blade</h2>";
    
    $views = [
        'admin.dashboard',
        'admin.clients.index',
        'admin.clients.create',
        'install.index'
    ];
    
    foreach ($views as $view) {
        $viewPath = '../resources/views/' . str_replace('.', '/', $view) . '.blade.php';
        if (file_exists($viewPath)) {
            echo "✅ " . $view . " - Existe<br>";
        } else {
            echo "❌ " . $view . " - Manquante (" . $viewPath . ")<br>";
        }
    }
    
    // Test configuration
    echo "<h2>⚙️ Test Configuration</h2>";
    
    $envFile = '../.env';
    if (file_exists($envFile)) {
        echo "✅ Fichier .env existe<br>";
        $env = file_get_contents($envFile);
        
        $configs = [
            'APP_URL' => 'retell.mak3it.org',
            'DB_DATABASE' => 'retell',
            'RETELL_API_KEY' => 'key_'
        ];
        
        foreach ($configs as $key => $expected) {
            if (strpos($env, $key) !== false) {
                echo "✅ " . $key . " configuré<br>";
            } else {
                echo "❌ " . $key . " manquant<br>";
            }
        }
    } else {
        echo "❌ Fichier .env manquant<br>";
    }
    
    // Solution recommandée
    echo "<h2>🎯 SOLUTION RECOMMANDÉE</h2>";
    
    if (!empty($missingTables)) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>PROBLÈME PRINCIPAL: Tables manquantes</strong><br>";
        echo "Tables à créer: " . implode(', ', $missingTables) . "<br>";
        echo "Solution: Exécuter le correcteur de base de données ci-dessous";
        echo "</div>";
    }
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "🔧 <strong>ACTIONS RECOMMANDÉES:</strong><br>";
    echo "1. Utiliser le correcteur de base de données<br>";
    echo "2. Vérifier l'authentification admin<br>";
    echo "3. Tester les routes individuellement<br>";
    echo "4. Consulter les logs Laravel";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "❌ <strong>ERREUR CRITIQUE:</strong> " . $e->getMessage() . "<br>";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine();
    echo "</div>";
}

echo "<hr>";
echo "<h2>🔧 CORRECTEUR AUTOMATIQUE</h2>";
echo "<form method='post'>";
echo "<input type='hidden' name='action' value='fix_database'>";
echo "<button type='submit' style='background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px;'>🚀 Corriger la Base de Données</button>";
echo "</form>";

// Action de correction
if (isset($_POST['action']) && $_POST['action'] === 'fix_database') {
    echo "<hr>";
    echo "<h3>🔄 Exécution du Correcteur...</h3>";
    
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=retell", "retell", "Quebec101!");
        
        // Créer table clients
        $pdo->exec("
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
        ");
        echo "✅ Table 'clients' créée<br>";
        
        // Créer table call_analyses
        $pdo->exec("
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
        ");
        echo "✅ Table 'call_analyses' créée<br>";
        
        // Ajouter des données de test
        $pdo->exec("
            INSERT IGNORE INTO clients (name, email, password, primary_color, subdomain) 
            VALUES 
            ('Client Demo', 'demo@client.com', '$2y$12$f2UL.pRQmgk.7DJf2vLAEeDG40Mcru4E6vmCObCLDmu3uwfPlR7oS', '#FF6B6B', 'demo'),
            ('Entreprise ABC', 'abc@entreprise.com', '$2y$12$f2UL.pRQmgk.7DJf2vLAEeDG40Mcru4E6vmCObCLDmu3uwfPlR7oS', '#4ECDC4', 'abc')
        ");
        echo "✅ Clients de démonstration ajoutés<br>";
        
        // Ajouter des analyses de test
        $pdo->exec("
            INSERT IGNORE INTO call_analyses (client_id, call_id, success_rate, transfer_rate, total_calls, avg_latency, sentiment) 
            VALUES 
            (1, 'call_001', 85.5, 12.3, 150, 1200.5, 'positive'),
            (1, 'call_002', 92.1, 8.7, 89, 950.2, 'positive'),
            (2, 'call_003', 78.9, 15.2, 200, 1450.8, 'neutral')
        ");
        echo "✅ Analyses de test ajoutées<br>";
        
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "🎉 <strong>CORRECTION TERMINÉE!</strong><br>";
        echo "Base de données mise à jour avec succès.<br>";
        echo "Vous pouvez maintenant tester l'administration.";
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "❌ <strong>ERREUR lors de la correction:</strong> " . $e->getMessage();
        echo "</div>";
    }
}

echo "<hr>";
echo "<p><strong>Testez maintenant:</strong> <a href='/admin'>Aller au Dashboard Admin</a></p>";
?>