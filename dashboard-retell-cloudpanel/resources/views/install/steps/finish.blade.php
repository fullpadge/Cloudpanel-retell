<!-- Étape 7: Finalisation -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">🎉 Installation terminée !</h2>
    <p class="text-gray-600">Votre Dashboard Retell AI est prêt</p>
</div>

<div class="space-y-6">
    <!-- Status de succès -->
    <div class="text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Installation réussie !</h3>
        <p class="text-gray-600">Votre Dashboard Retell AI est maintenant opérationnel</p>
    </div>

    <!-- Informations d'accès -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-2">🔐 Administration</h4>
            <div class="space-y-2 text-sm">
                <p><strong>URL :</strong> <a href="/admin" class="text-blue-600 hover:underline">{{ config('app.url') }}/admin</a></p>
                <p><strong>Email :</strong> <span class="font-mono bg-gray-100 px-1 rounded">Configuré à l'étape 4</span></p>
                <p><strong>Fonctionnalités :</strong> Gestion clients, analytics, configuration</p>
            </div>
        </div>

        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-2">🔗 API Webhook</h4>
            <div class="space-y-2 text-sm">
                <p><strong>URL :</strong> <span class="font-mono bg-gray-100 px-1 rounded text-xs">{{ config('app.url') }}/api/webhook</span></p>
                <p><strong>Événements :</strong> call_analyzed</p>
                <p><strong>Sécurité :</strong> Signature vérifiée</p>
            </div>
        </div>
    </div>

    <!-- Prochaines étapes -->
    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
        <h4 class="text-green-800 font-semibold mb-2">✅ Prochaines étapes</h4>
        <ol class="text-green-700 text-sm list-decimal list-inside space-y-1">
            <li><strong>Connectez-vous à l'administration</strong> avec vos identifiants</li>
            <li><strong>Configurez votre premier client</strong> dans la section Clients</li>
            <li><strong>Testez les webhooks</strong> en effectuant un appel via Retell AI</li>
            <li><strong>Personnalisez l'interface</strong> avec votre marque (logo, couleurs)</li>
            <li><strong>Configurez SSL/HTTPS</strong> dans CloudPanel si pas encore fait</li>
        </ol>
    </div>

    <!-- Configuration CloudPanel -->
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h4 class="text-blue-800 font-semibold mb-2">🛠️ Configuration CloudPanel recommandée</h4>
        <div class="text-blue-700 text-sm space-y-2">
            <p><strong>SSL/HTTPS :</strong></p>
            <ol class="list-decimal list-inside space-y-1 ml-2">
                <li>Dans CloudPanel → Sites → Votre domaine</li>
                <li>Onglet <strong>SSL/TLS</strong></li>
                <li>Activez <strong>Let's Encrypt</strong></li>
                <li>Forcez la redirection HTTPS</li>
            </ol>
            
            <p class="mt-3"><strong>Sauvegardes :</strong></p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li>Configurez des sauvegardes automatiques</li>
                <li>Incluez les fichiers ET la base de données</li>
                <li>Testez la restauration</li>
            </ul>
        </div>
    </div>

    <!-- Support et documentation -->
    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
        <h4 class="text-gray-800 font-semibold mb-2">📖 Support et documentation</h4>
        <div class="text-gray-700 text-sm space-y-1">
            <p><strong>Fichiers importants :</strong></p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li><code class="bg-gray-100 px-1 rounded">.env</code> - Configuration principale</li>
                <li><code class="bg-gray-100 px-1 rounded">storage/logs/</code> - Logs d'erreur</li>
                <li><code class="bg-gray-100 px-1 rounded">/admin</code> - Interface d'administration</li>
            </ul>
            <p class="mt-2"><strong>En cas de problème :</strong> Consultez les logs dans CloudPanel → Sites → Logs</p>
        </div>
    </div>

    <!-- Bouton d'accès -->
    <div class="text-center">
        <a href="/admin" class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
            🚀 Accéder à l'administration
        </a>
        <p class="text-gray-500 text-sm mt-2">Vous pouvez fermer cette page d'installation</p>
    </div>
</div>

<script>
// Redirection automatique après 30 secondes (optionnel)
setTimeout(function() {
    if (confirm('Voulez-vous être redirigé vers l\'administration maintenant ?')) {
        window.location.href = '/admin';
    }
}, 30000);
</script>