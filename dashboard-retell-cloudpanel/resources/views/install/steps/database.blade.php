<!-- Étape 3: Configuration base de données -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Configuration base de données</h2>
    <p class="text-gray-600">Paramètres de connexion MySQL</p>
</div>

<form class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="db_host" class="block text-sm font-medium text-gray-700 mb-1">Hôte de base de données</label>
            <input type="text" id="db_host" name="db_host" value="127.0.0.1" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Généralement 127.0.0.1 ou localhost</p>
        </div>
        
        <div>
            <label for="db_port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
            <input type="number" id="db_port" name="db_port" value="3306" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Port MySQL standard (3306)</p>
        </div>
    </div>

    <div>
        <label for="db_database" class="block text-sm font-medium text-gray-700 mb-1">Nom de la base de données <span class="text-red-500">*</span></label>
        <input type="text" id="db_database" name="db_database" value="retell" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <p class="text-xs text-gray-500 mt-1">Nom de votre base de données MySQL</p>
    </div>

    <div>
        <label for="db_username" class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur <span class="text-red-500">*</span></label>
        <input type="text" id="db_username" name="db_username" value="retell" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <p class="text-xs text-gray-500 mt-1">Utilisateur avec accès à la base de données</p>
    </div>

    <div>
        <label for="db_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
        <input type="password" id="db_password" name="db_password" value="Quebec101!" 
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <p class="text-xs text-gray-500 mt-1">Mot de passe de l'utilisateur (laissez vide si aucun)</p>
    </div>

    <!-- Test de connexion -->
    <div class="border-t pt-4">
        <button type="button" onclick="testDatabase()" 
                class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors mb-3">
            🔍 Tester la connexion
        </button>
        <div id="db-test-result" class="text-center">
            <span class="text-gray-500 text-sm">Cliquez sur "Tester la connexion" pour vérifier</span>
        </div>
    </div>
</form>

<!-- Aide CloudPanel -->
<div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <h3 class="text-blue-800 font-semibold mb-2">💡 Aide CloudPanel</h3>
    <div class="text-blue-700 text-sm space-y-1">
        <p><strong>Pour trouver vos informations de base de données :</strong></p>
        <ol class="list-decimal list-inside space-y-1 ml-2">
            <li>Connectez-vous à CloudPanel</li>
            <li>Allez dans <code class="bg-blue-100 px-1 rounded">Databases</code></li>
            <li>Sélectionnez votre base de données</li>
            <li>Copiez les informations de connexion</li>
        </ol>
        <p class="mt-2"><strong>Valeurs par défaut :</strong> Host: 127.0.0.1, Port: 3306</p>
    </div>
</div>