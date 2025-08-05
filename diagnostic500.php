<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🚨 DIAGNOSTIC ERREUR 500 - Dashboard Retell AI</h1>";
echo "<hr>";

echo "<h2>1. Test PHP de base</h2>";
echo "✅ PHP Version: " . phpversion() . "<br>";
echo "✅ PHP fonctionne correctement<br>";

echo "<h2>2. Test des chemins</h2>";
$currentPath = __DIR__;
echo "📁 Dossier actuel: " . $currentPath . "<br>";
echo "📁 Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

$expectedAutoload = dirname(__DIR__) . '/vendor/autoload.php';
echo "📁 Autoload attendu: " . $expectedAutoload . "<br>";

if (file_exists($expectedAutoload)) {
    echo "✅ vendor/autoload.php TROUVÉ<br>";
} else {
    echo "❌ vendor/autoload.php MANQUANT<br>";
    echo "🔍 Recherche vendor/autoload.php...<br>";
    
    // Chercher dans différents endroits
    $possiblePaths = [
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/../../vendor/autoload.php', 
        __DIR__ . '/../../../vendor/autoload.php',
        __DIR__ . '/vendor/autoload.php'
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            echo "✅ Trouvé à: " . $path . "<br>";
        } else {
            echo "❌ Pas à: " . $path . "<br>";
        }
    }
}

echo "<h2>3. Test structure Laravel</h2>";
$bootstrapApp = dirname(__DIR__) . '/bootstrap/app.php';
echo "📁 bootstrap/app.php: ";
if (file_exists($bootstrapApp)) {
    echo "✅ TROUVÉ<br>";
} else {
    echo "❌ MANQUANT à " . $bootstrapApp . "<br>";
}

echo "<h2>4. Test fichier .env</h2>";
$envFile = dirname(__DIR__) . '/.env';
echo "📁 .env: ";
if (file_exists($envFile)) {
    echo "✅ TROUVÉ<br>";
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'APP_KEY=') !== false) {
        echo "✅ APP_KEY présente<br>";
    } else {
        echo "❌ APP_KEY manquante<br>";
    }
} else {
    echo "❌ MANQUANT à " . $envFile . "<br>";
}

echo "<h2>5. Test permissions</h2>";
$storageDir = dirname(__DIR__) . '/storage';
$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';

echo "📁 storage/: ";
if (is_writable($storageDir)) {
    echo "✅ ÉCRITURE OK<br>";
} else {
    echo "❌ PAS D'ÉCRITURE<br>";
}

echo "📁 bootstrap/cache/: ";
if (is_writable($bootstrapCache)) {
    echo "✅ ÉCRITURE OK<br>";
} else {
    echo "❌ PAS D'ÉCRITURE<br>";
}

echo "<h2>6. Test inclusion Laravel</h2>";
try {
    echo "🔄 Tentative d'inclusion de vendor/autoload.php...<br>";
    require $expectedAutoload;
    echo "✅ Autoload chargé<br>";
    
    echo "🔄 Tentative d'inclusion de bootstrap/app.php...<br>";
    $app = require $bootstrapApp;
    echo "✅ Application Laravel chargée<br>";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "<br>";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "<br>";
} catch (Error $e) {
    echo "❌ ERREUR FATALE: " . $e->getMessage() . "<br>";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "<br>";
}

echo "<h2>7. Listing des fichiers</h2>";
echo "📁 Contenu du dossier parent:<br>";
$parentDir = dirname(__DIR__);
if (is_dir($parentDir)) {
    $files = scandir($parentDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- " . $file . "<br>";
        }
    }
}

echo "<hr>";
echo "<h2>🎯 SOLUTION</h2>";
echo "Basé sur les tests ci-dessus, voici ce qui doit être corrigé:<br>";
echo "1. Si vendor/autoload.php manque → Exécuter 'composer install'<br>";
echo "2. Si permissions manquent → chmod -R 755 storage/ bootstrap/cache/<br>";
echo "3. Si .env manque → Copier .env.example vers .env<br>";
echo "4. Si APP_KEY manque → Exécuter 'php artisan key:generate'<br>";

echo "<hr>";
echo "<p><strong>Testez maintenant:</strong> <a href='/install'>Aller à /install</a></p>";
?>