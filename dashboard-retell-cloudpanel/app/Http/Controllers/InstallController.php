<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\InstallationSetting;
use Exception;

class InstallController extends Controller
{
    private $steps = [
        1 => 'integrity',
        2 => 'requirements', 
        3 => 'database',
        4 => 'admin',
        5 => 'retell',
        6 => 'validation',
        7 => 'finish'
    ];

    /**
     * Affiche l'installateur
     */
    public function index(Request $request)
    {
        // Vérifier si déjà installé
        if ($this->isInstalled()) {
            return redirect('/admin')->with('error', 'L\'application est déjà installée.');
        }

        $currentStep = (int) $request->get('step', 1);
        $currentStep = max(1, min($currentStep, 7));

        $data = [
            'currentStep' => $currentStep,
            'steps' => $this->steps,
            'totalSteps' => count($this->steps)
        ];

        // Données spécifiques selon l'étape
        switch ($currentStep) {
            case 1:
                $data['integrityCheck'] = $this->checkFileIntegrity();
                break;
            case 2:
                $data['requirements'] = $this->checkRequirements();
                break;
            case 6:
                $data['validationSummary'] = $this->getValidationSummary();
                break;
        }

        return view('install.index', $data);
    }

    /**
     * Traite l'installation
     */
    public function install(Request $request)
    {
        try {
            $currentStep = (int) $request->input('step', 1);
            
            switch ($currentStep) {
                case 1:
                    return $this->processIntegrityCheck($request);
                case 2:
                    return $this->processRequirements($request);
                case 3:
                    return $this->processDatabase($request);
                case 4:
                    return $this->processAdmin($request);
                case 5:
                    return $this->processRetell($request);
                case 6:
                    return $this->processValidation($request);
                case 7:
                    return $this->processFinish($request);
                default:
                    return response()->json(['success' => false, 'message' => 'Étape invalide']);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erreur lors de l\'installation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Vérifier l'intégrité des fichiers
     */
    private function checkFileIntegrity(): array
    {
        $checks = [];
        
        // Fichiers essentiels
        $essentialFiles = [
            'artisan' => base_path('artisan'),
            'composer.json' => base_path('composer.json'),
            'public/index.php' => base_path('public/index.php'),
            'bootstrap/app.php' => base_path('bootstrap/app.php'),
            'config/app.php' => base_path('config/app.php'),
            'routes/web.php' => base_path('routes/web.php'),
        ];
        
        foreach ($essentialFiles as $name => $path) {
            $checks['file_' . str_replace(['/', '.'], '_', $name)] = [
                'name' => "Fichier {$name}",
                'required' => true,
                'satisfied' => File::exists($path),
                'current' => File::exists($path) ? 'Présent' : 'Manquant'
            ];
        }
        
        // Dossiers essentiels
        $essentialDirs = [
            'storage' => storage_path(),
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];
        
        foreach ($essentialDirs as $name => $path) {
            $checks['dir_' . str_replace(['/', '.'], '_', $name)] = [
                'name' => "Dossier {$name}",
                'required' => true,
                'satisfied' => File::isDirectory($path) && File::isWritable($path),
                'current' => File::isDirectory($path) ? 
                    (File::isWritable($path) ? 'Écriture OK' : 'Pas d\'écriture') : 
                    'Manquant'
            ];
        }
        
        return $checks;
    }

    /**
     * Vérifier les prérequis système
     */
    private function checkRequirements(): array
    {
        $checks = [];
        
        // Version PHP
        $checks['php_version'] = [
            'name' => 'PHP Version',
            'required' => true,
            'satisfied' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'current' => PHP_VERSION,
            'minimum' => '8.1.0'
        ];
        
        // Extensions PHP
        $extensions = [
            'openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'mysqli'
        ];
        
        foreach ($extensions as $ext) {
            $checks['ext_' . $ext] = [
                'name' => "Extension {$ext}",
                'required' => true,
                'satisfied' => extension_loaded($ext),
                'current' => extension_loaded($ext) ? 'Installée' : 'Manquante'
            ];
        }
        
        // Permissions fichiers
        $permissions = [
            'storage' => storage_path(),
            'bootstrap/cache' => base_path('bootstrap/cache')
        ];
        
        foreach ($permissions as $name => $path) {
            $checks['perm_' . str_replace('/', '_', $name)] = [
                'name' => "Permissions {$name}",
                'required' => true,
                'satisfied' => File::isWritable($path),
                'current' => File::isWritable($path) ? 'Écriture OK' : 'Pas d\'écriture'
            ];
        }
        
        return $checks;
    }

    /**
     * Traiter l'étape d'intégrité
     */
    private function processIntegrityCheck(Request $request): array
    {
        $checks = $this->checkFileIntegrity();
        
        $failed = array_filter($checks, function($check) {
            return $check['required'] && !$check['satisfied'];
        });
        
        if (!empty($failed)) {
            return [
                'success' => false,
                'message' => 'Vérification d\'intégrité échouée',
                'failed_checks' => $failed
            ];
        }
        
        return ['success' => true, 'message' => 'Vérification d\'intégrité réussie', 'next_step' => 2];
    }

    /**
     * Traiter l'étape des prérequis
     */
    private function processRequirements(Request $request): array
    {
        $checks = $this->checkRequirements();
        
        $failed = array_filter($checks, function($check) {
            return $check['required'] && !$check['satisfied'];
        });
        
        if (!empty($failed)) {
            return [
                'success' => false,
                'message' => 'Prérequis système non satisfaits',
                'failed_checks' => $failed
            ];
        }
        
        return ['success' => true, 'message' => 'Prérequis système validés', 'next_step' => 3];
    }

    /**
     * Traiter la configuration base de données
     */
    private function processDatabase(Request $request): array
    {
        $validated = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        // Test connexion
        $result = $this->testDatabaseConnection($validated);
        if (!$result['success']) {
            return [
                'success' => false,
                'message' => 'Erreur de connexion : ' . $result['message']
            ];
        }

        // Sauvegarder dans la session
        session(['db_config' => $validated]);

        return ['success' => true, 'message' => 'Configuration base de données sauvegardée', 'next_step' => 4];
    }

    /**
     * Traiter la configuration administrateur
     */
    private function processAdmin(Request $request): array
    {
        $validated = $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        // Sauvegarder dans la session
        session(['admin_config' => $validated]);

        return ['success' => true, 'message' => 'Configuration administrateur sauvegardée', 'next_step' => 5];
    }
    
    /**
     * Traiter la configuration Retell AI
     */
    private function processRetell(Request $request): array
    {
        $validated = $request->validate([
            'retell_api_key' => 'required|string',
            'retell_webhook_url' => 'nullable|url',
        ]);

        // Générer l'URL du webhook si pas fournie
        if (empty($validated['retell_webhook_url'])) {
            $validated['retell_webhook_url'] = $this->getWebhookUrl();
        }

        // Sauvegarder dans la session
        session(['retell_config' => $validated]);

        return ['success' => true, 'message' => 'Configuration Retell AI sauvegardée', 'next_step' => 6];
    }
    
    /**
     * Traiter l'étape de validation finale
     */
    private function processValidation(Request $request): array
    {
        // Vérifier que toutes les configurations sont présentes
        $dbConfig = session('db_config');
        $adminConfig = session('admin_config');
        $retellConfig = session('retell_config');
        
        $missing = [];
        
        if (!$dbConfig) {
            $missing[] = 'Configuration base de données';
        }
        
        if (!$adminConfig) {
            $missing[] = 'Configuration administrateur';
        }
        
        if (!$retellConfig) {
            $missing[] = 'Configuration Retell AI';
        }
        
        if (!empty($missing)) {
            return [
                'success' => false,
                'message' => 'Configuration manquante : ' . implode(', ', $missing)
            ];
        }
        
        // Vérifier une dernière fois la connexion DB
        if ($dbConfig) {
            $dbTest = $this->testDatabaseConnection($dbConfig);
            if (!$dbTest['success']) {
                return [
                    'success' => false,
                    'message' => 'Erreur de connexion base de données : ' . $dbTest['message']
                ];
            }
        }
        
        return ['success' => true, 'message' => 'Validation finale réussie', 'next_step' => 7];
    }

    /**
     * Finaliser l'installation
     */
    private function processFinish(Request $request): array
    {
        try {
            // Récupérer les configurations
            $dbConfig = session('db_config');
            $adminConfig = session('admin_config');
            $retellConfig = session('retell_config');
            
            if (!$dbConfig || !$adminConfig || !$retellConfig) {
                return ['success' => false, 'message' => 'Configuration manquante'];
            }

            // 1. Mettre à jour le fichier .env
            $this->updateEnvFile($dbConfig, $retellConfig);

            // 2. Générer la clé d'application
            Artisan::call('key:generate', ['--force' => true]);

            // 3. Exécuter les migrations
            Artisan::call('migrate', ['--force' => true]);

            // 4. Créer l'utilisateur administrateur
            $this->createAdminUser($adminConfig);

            // 5. Marquer comme installé
            InstallationSetting::markAsInstalled();

            // Nettoyer les sessions
            session()->forget(['db_config', 'admin_config', 'retell_config']);

            return [
                'success' => true, 
                'message' => 'Installation terminée avec succès !',
                'redirect' => '/admin'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur finale : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tester la connexion base de données
     */
    private function testDatabaseConnection(array $config): array
    {
        try {
            $pdo = new \PDO(
                "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_database']}",
                $config['db_username'],
                $config['db_password'] ?? '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            
            return ['success' => true, 'message' => 'Connexion réussie'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Mettre à jour le fichier .env
     */
    private function updateEnvFile(array $dbConfig, array $retellConfig): void
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Mise à jour des variables DB
        $updates = [
            'DB_HOST' => $dbConfig['db_host'],
            'DB_PORT' => $dbConfig['db_port'],
            'DB_DATABASE' => $dbConfig['db_database'],
            'DB_USERNAME' => $dbConfig['db_username'],
            'DB_PASSWORD' => $dbConfig['db_password'] ?? '',
            'RETELL_API_KEY' => $retellConfig['retell_api_key'],
            'RETELL_WEBHOOK_URL' => $retellConfig['retell_webhook_url'],
        ];

        foreach ($updates as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        File::put($envPath, $envContent);
    }

    /**
     * Créer l'utilisateur administrateur
     */
    private function createAdminUser(array $config): void
    {
        User::create([
            'name' => $config['admin_name'],
            'email' => $config['admin_email'],
            'password' => Hash::make($config['admin_password']),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Résumé de validation
     */
    private function getValidationSummary(): array
    {
        return [
            'database' => session('db_config') ? '✅ Configurée' : '❌ Manquante',
            'admin' => session('admin_config') ? '✅ Configuré' : '❌ Manquant',
            'retell' => session('retell_config') ? '✅ Configuré' : '❌ Manquant',
        ];
    }

    /**
     * Vérifier si l'installation est terminée
     */
    private function isInstalled(): bool
    {
        try {
            // Vérifier si les tables existent et si l'installation est marquée comme terminée
            if (DB::table('installation_settings')->where('key', 'installation_completed')->where('value', 'true')->exists()) {
                return true;
            }
        } catch (Exception $e) {
            // Les tables n'existent probablement pas encore
        }
        
        return false;
    }

    /**
     * Obtenir l'URL du webhook
     */
    private function getWebhookUrl(): string
    {
        return config('app.url') . '/api/webhook';
    }

    /**
     * API pour vérifier les prérequis
     */
    public function checkRequirementsApi()
    {
        return response()->json($this->checkRequirements());
    }

    /**
     * Tester la base de données via API
     */
    public function testDatabase(Request $request)
    {
        $validated = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        return response()->json($this->testDatabaseConnection($validated));
    }
}