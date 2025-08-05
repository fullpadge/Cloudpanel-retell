<!-- Étape 5: Configuration Retell AI -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Configuration Retell AI</h2>
    <p class="text-gray-600">Paramètres de l'API et webhook</p>
</div>

<form class="space-y-4">
    <div>
        <label for="retell_api_key" class="block text-sm font-medium text-gray-700 mb-1">Clé API Retell AI <span class="text-red-500">*</span></label>
        <input type="password" id="retell_api_key" name="retell_api_key" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               placeholder="key_xxxxxxxxxxxxxxxxxx">
        <p class="text-xs text-gray-500 mt-1">Votre clé API secrète Retell AI</p>
    </div>

    <div>
        <label for="retell_webhook_url" class="block text-sm font-medium text-gray-700 mb-1">URL Webhook</label>
        <input type="url" id="retell_webhook_url" name="retell_webhook_url" readonly
               value="{{ config('app.url') }}/api/webhook"
               class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
        <p class="text-xs text-gray-500 mt-1">URL automatiquement générée pour recevoir les événements</p>
    </div>
</form>

<!-- Instructions -->
<div class="mt-6 space-y-4">
    <!-- Comment obtenir la clé API -->
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-blue-800 font-semibold mb-2">🔑 Comment obtenir votre clé API ?</h3>
        <ol class="text-blue-700 text-sm list-decimal list-inside space-y-1">
            <li>Connectez-vous à votre tableau de bord Retell AI</li>
            <li>Allez dans <strong>Settings</strong> → <strong>API Keys</strong></li>
            <li>Cliquez sur <strong>"Create API Key"</strong></li>
            <li>Copiez la clé générée (commençant par <code class="bg-blue-100 px-1 rounded">key_</code>)</li>
            <li>Collez-la dans le champ ci-dessus</li>
        </ol>
    </div>

    <!-- Configuration Webhook -->
    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
        <h3 class="text-green-800 font-semibold mb-2">🔗 Configuration automatique du webhook</h3>
        <div class="text-green-700 text-sm space-y-2">
            <p>L'URL du webhook sera automatiquement configurée :</p>
            <div class="bg-green-100 p-2 rounded font-mono text-xs break-all">
                {{ config('app.url') }}/api/webhook
            </div>
            <p><strong>Après l'installation :</strong></p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li>L'URL sera enregistrée dans votre compte Retell AI</li>
                <li>Les événements <code class="bg-green-100 px-1 rounded">call_analyzed</code> seront reçus automatiquement</li>
                <li>Les signatures des webhooks seront vérifiées pour la sécurité</li>
            </ul>
        </div>
    </div>

    <!-- Test API (à implémenter) -->
    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
        <h3 class="text-gray-800 font-semibold mb-2">🧪 Test de l'API (optionnel)</h3>
        <p class="text-gray-600 text-sm mb-3">Testez votre clé API avant de continuer</p>
        <button type="button" onclick="testRetellAPI()" 
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            🔍 Tester la clé API
        </button>
        <div id="api-test-result" class="mt-2">
            <span class="text-gray-500 text-sm">Cliquez pour tester votre clé API</span>
        </div>
    </div>
</div>

<script>
function testRetellAPI() {
    const apiKey = document.getElementById('retell_api_key').value;
    const resultDiv = document.getElementById('api-test-result');
    
    if (!apiKey) {
        resultDiv.innerHTML = '<span class="text-red-600">❌ Veuillez saisir votre clé API</span>';
        return;
    }
    
    resultDiv.innerHTML = '<span class="text-blue-600">🔍 Test en cours...</span>';
    
    // Simple validation de format
    if (apiKey.startsWith('key_') && apiKey.length > 10) {
        resultDiv.innerHTML = '<span class="text-green-600">✅ Format de clé valide</span>';
    } else {
        resultDiv.innerHTML = '<span class="text-orange-600">⚠️ Format de clé suspect (doit commencer par "key_")</span>';
    }
}

// Auto-test quand l'utilisateur tape
document.getElementById('retell_api_key').addEventListener('input', function() {
    const apiKey = this.value;
    const resultDiv = document.getElementById('api-test-result');
    
    if (apiKey.length === 0) {
        resultDiv.innerHTML = '<span class="text-gray-500 text-sm">Cliquez pour tester votre clé API</span>';
    } else if (apiKey.startsWith('key_') && apiKey.length > 20) {
        resultDiv.innerHTML = '<span class="text-green-600">✅ Format correct</span>';
    } else if (apiKey.length > 0) {
        resultDiv.innerHTML = '<span class="text-orange-600">⚠️ Format attendu: key_xxxxxxxxxx</span>';
    }
});
</script>