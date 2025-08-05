<!-- Étape 6: Validation finale -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Validation finale</h2>
    <p class="text-gray-600">Vérification de toutes les configurations</p>
</div>

<div class="space-y-4">
    @if(isset($validationSummary))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">🗄️ Base de données</h3>
                <p class="text-sm">{!! $validationSummary['database'] !!}</p>
            </div>
            
            <div class="p-4 border border-gray-200 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">👤 Administrateur</h3>
                <p class="text-sm">{!! $validationSummary['admin'] !!}</p>
            </div>
            
            <div class="p-4 border border-gray-200 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">🔗 Retell AI</h3>
                <p class="text-sm">{!! $validationSummary['retell'] !!}</p>
            </div>
        </div>
    @endif

    <!-- Récapitulatif détaillé -->
    <div class="border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="font-semibold text-gray-800">📋 Récapitulatif de la configuration</h3>
        </div>
        <div class="p-4 space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Type d'installation :</span>
                <span class="font-medium">Dashboard Retell AI CloudPanel</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Domaine :</span>
                <span class="font-medium">{{ config('app.url') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Version PHP :</span>
                <span class="font-medium">{{ PHP_VERSION }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Base de données :</span>
                <span class="font-medium">MySQL (configurée)</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">API Retell AI :</span>
                <span class="font-medium">Configurée</span>
            </div>
        </div>
    </div>

    <!-- Actions après installation -->
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-blue-800 font-semibold mb-2">🚀 Après l'installation</h3>
        <div class="text-blue-700 text-sm space-y-1">
            <p><strong>Vous pourrez accéder à :</strong></p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li><strong>Administration :</strong> <code class="bg-blue-100 px-1 rounded">{{ config('app.url') }}/admin</code></li>
                <li><strong>Webhook API :</strong> <code class="bg-blue-100 px-1 rounded">{{ config('app.url') }}/api/webhook</code></li>
                <li><strong>Dashboards clients :</strong> Via sous-domaines configurés</li>
            </ul>
            <p class="mt-2"><strong>N'oubliez pas de :</strong></p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li>Configurer SSL/HTTPS dans CloudPanel</li>
                <li>Sauvegarder vos identifiants d'administration</li>
                <li>Tester la réception des webhooks Retell AI</li>
            </ul>
        </div>
    </div>

    <!-- Avertissement final -->
    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <h3 class="text-yellow-800 font-semibold mb-2">⚠️ Important</h3>
        <div class="text-yellow-700 text-sm space-y-1">
            <p>La finalisation va :</p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li>Créer les tables de base de données</li>
                <li>Installer le compte administrateur</li>
                <li>Configurer les paramètres système</li>
                <li>Activer le système de production</li>
            </ul>
            <p class="mt-2 font-semibold">Cette action est irréversible. Assurez-vous que toutes les informations sont correctes.</p>
        </div>
    </div>
</div>