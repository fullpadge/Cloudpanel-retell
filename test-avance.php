<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔥 DIAGNOSTIC AVANCÉ - Erreur 500 Laravel</h1>";
echo "<hr>";

// Inclure Laravel
require '../vendor/autoload.php';
$app = require '../bootstrap/app.php';

echo "<h2>✅ Laravel chargé avec succès</h2>";

echo "<h2>🔍 Test des Routes Laravel</h2>";

try {
    // Créer une requête pour tester /install
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "✅ Kernel HTTP créé<br>";
    
    // Test simple route
    $request = Illuminate\Http\Request::create('/install', 'GET');
    echo "✅ Requête /install créée<br>";
    
    echo "<h3>🚀 Tentative d'exécution de la route /install...</h3>";
    
    ob_start();
    $response = $kernel->handle($request);
    $output = ob_get_clean();
    
    echo "✅ Route exécutée sans erreur fatale<br>";
    echo "📊 Status HTTP: " . $response->getStatusCode() . "<br>";
    echo "📄 Type de contenu: " . $response->headers->get('content-type') . "<br>";
    
    if ($response->getStatusCode() == 200) {
        echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "🎉 <strong>SUCCESS!</strong> La route /install fonctionne!";
        echo "</div>";
        echo "<p>Contenu (premier 500 chars):</p>";
        echo "<textarea style='width: 100%; height: 200px;'>" . substr($response->getContent(), 0, 500) . "...</textarea>";
    } else {
        echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>ERREUR:</strong> Status " . $response->getStatusCode();
        echo "</div>";
        echo "<p>Contenu de la réponse:</p>";
        echo "<textarea style='width: 100%; height: 200px;'>" . $response->getContent() . "</textarea>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>EXCEPTION:</strong> " . $e->getMessage() . "<br>";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "<br>";
    echo "</div>";
    
    echo "<h3>📋 Stack Trace:</h3>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;'>";
    echo $e->getTraceAsString();
    echo "</pre>";
} catch (Error $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>ERREUR FATALE:</strong> " . $e->getMessage() . "<br>";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "<br>";
    echo "</div>";
    
    echo "<h3>📋 Stack Trace:</h3>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;'>";
    echo $e->getTraceAsString();
    echo "</pre>";
}

echo "<h2>📄 Vérification des Logs Laravel</h2>";

$logFile = '../storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "✅ Fichier de log trouvé<br>";
    $logContent = file_get_contents($logFile);
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -20); // 20 dernières lignes
    
    echo "<h3>🔍 20 dernières lignes du log:</h3>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px; max-height: 300px; overflow-y: scroll;'>";
    echo implode("\n", $recentLines);
    echo "</pre>";
} else {
    echo "❌ Fichier de log non trouvé à: " . $logFile . "<br>";
}

echo "<h2>🔧 Test Contrôleur InstallController</h2>";

try {
    // Tester si le contrôleur existe et peut être instancié
    $controller = new App\Http\Controllers\InstallController();
    echo "✅ InstallController peut être instancié<br>";
    
    // Tester la méthode index
    $reflection = new ReflectionClass($controller);
    if ($reflection->hasMethod('index')) {
        echo "✅ Méthode index() existe<br>";
    } else {
        echo "❌ Méthode index() manquante<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur avec InstallController: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "❌ Erreur fatale avec InstallController: " . $e->getMessage() . "<br>";
}

echo "<h2>📋 Test Routes Définies</h2>";

try {
    $routes = app()->router->getRoutes();
    echo "✅ Routes chargées: " . count($routes) . " routes<br>";
    
    echo "<h3>🔍 Routes contenant 'install':</h3>";
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'install') !== false) {
            echo "- " . $route->methods()[0] . " /" . $uri . " → " . $route->getActionName() . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur routes: " . $e->getMessage() . "<br>";
}

echo "<h2>🧪 Test .htaccess</h2>";

$htaccessContent = file_get_contents('.htaccess');
if ($htaccessContent) {
    echo "✅ Fichier .htaccess trouvé<br>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;'>";
    echo htmlspecialchars($htaccessContent);
    echo "</pre>";
} else {
    echo "❌ Fichier .htaccess manquant<br>";
}

echo "<hr>";
echo "<h2>🎯 CONCLUSION</h2>";
echo "<p>Basé sur les tests ci-dessus, le problème exact devrait être identifié dans les sections suivantes:</p>";
echo "<ul>";
echo "<li>Si 'Route exécutée sans erreur fatale' → Problème dans la vue/template</li>";
echo "<li>Si 'EXCEPTION/ERREUR FATALE' → Problème dans le code Laravel</li>";
echo "<li>Si 'InstallController' erreur → Problème dans le contrôleur</li>";
echo "<li>Si logs récents → Erreurs spécifiques dans les logs</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>SOLUTION IMMÉDIATE:</strong> Utilisez l'installateur simple: <a href='install-simple.php'>install-simple.php</a></p>";
?>