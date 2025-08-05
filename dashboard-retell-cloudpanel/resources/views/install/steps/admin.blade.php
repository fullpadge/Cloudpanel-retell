<!-- Étape 4: Configuration administrateur -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Compte administrateur</h2>
    <p class="text-gray-600">Création du compte administrateur principal</p>
</div>

<form class="space-y-4">
    <div>
        <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet <span class="text-red-500">*</span></label>
        <input type="text" id="admin_name" name="admin_name" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               placeholder="Votre nom complet">
        <p class="text-xs text-gray-500 mt-1">Nom affiché dans l'interface d'administration</p>
    </div>

    <div>
        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email <span class="text-red-500">*</span></label>
        <input type="email" id="admin_email" name="admin_email" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               placeholder="admin@votre-domaine.com">
        <p class="text-xs text-gray-500 mt-1">Utilisé pour la connexion à l'administration</p>
    </div>

    <div>
        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe <span class="text-red-500">*</span></label>
        <input type="password" id="admin_password" name="admin_password" required minlength="8"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               placeholder="Minimum 8 caractères">
        <p class="text-xs text-gray-500 mt-1">Utilisez un mot de passe fort avec au moins 8 caractères</p>
    </div>

    <div>
        <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe <span class="text-red-500">*</span></label>
        <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required minlength="8"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               placeholder="Tapez le même mot de passe">
        <p class="text-xs text-gray-500 mt-1">Doit être identique au mot de passe ci-dessus</p>
    </div>
</form>

<!-- Informations de sécurité -->
<div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
    <h3 class="text-yellow-800 font-semibold mb-2">🔒 Sécurité</h3>
    <div class="text-yellow-700 text-sm space-y-1">
        <p><strong>Conseils pour un mot de passe sécurisé :</strong></p>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Au moins 8 caractères</li>
            <li>Mélange de lettres majuscules et minuscules</li>
            <li>Inclure des chiffres et caractères spéciaux</li>
            <li>Éviter les mots du dictionnaire</li>
        </ul>
        <p class="mt-2"><strong>Important :</strong> Notez ces informations dans un endroit sûr !</p>
    </div>
</div>

<!-- Validation en temps réel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('admin_password');
    const confirmation = document.getElementById('admin_password_confirmation');
    
    function validatePasswords() {
        if (password.value && confirmation.value) {
            if (password.value === confirmation.value) {
                confirmation.classList.remove('border-red-300');
                confirmation.classList.add('border-green-300');
            } else {
                confirmation.classList.remove('border-green-300');
                confirmation.classList.add('border-red-300');
            }
        }
    }
    
    password.addEventListener('input', validatePasswords);
    confirmation.addEventListener('input', validatePasswords);
});
</script>