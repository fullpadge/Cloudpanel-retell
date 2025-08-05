<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Dashboard Retell AI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .step-indicator {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .step-active {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .step-completed {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Dashboard Retell AI</h1>
            <p class="text-blue-100 text-lg">Assistant d'installation</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold 
                                    {{ $i < $currentStep ? 'step-completed' : ($i == $currentStep ? 'step-active' : 'step-indicator') }}">
                            {{ $i }}
                        </div>
                        @if ($i < $totalSteps)
                            <div class="flex-1 h-1 mx-2 bg-white/20 rounded">
                                <div class="h-full {{ $i < $currentStep ? 'bg-green-400' : ($i == $currentStep ? 'bg-blue-400' : 'bg-transparent') }} rounded transition-all duration-300"></div>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
            <div class="flex justify-between text-white text-xs">
                <span>Intégrité</span>
                <span>Prérequis</span>
                <span>Base de données</span>
                <span>Administrateur</span>
                <span>API Retell</span>
                <span>Validation</span>
                <span>Finalisation</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <div class="p-8">
                <!-- Step Content -->
                <div id="step-content">
                    @if ($currentStep == 1)
                        @include('install.steps.integrity')
                    @elseif ($currentStep == 2)
                        @include('install.steps.requirements')
                    @elseif ($currentStep == 3)
                        @include('install.steps.database')
                    @elseif ($currentStep == 4)
                        @include('install.steps.admin')
                    @elseif ($currentStep == 5)
                        @include('install.steps.retell')
                    @elseif ($currentStep == 6)
                        @include('install.steps.validation')
                    @elseif ($currentStep == 7)
                        @include('install.steps.finish')
                    @endif
                </div>

                <!-- Navigation -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <button id="prev-btn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors" 
                            onclick="previousStep()" {{ $currentStep <= 1 ? 'disabled style=opacity:0.5' : '' }}>
                        ← Précédent
                    </button>
                    
                    <span class="text-gray-500">
                        Étape {{ $currentStep }} sur {{ $totalSteps }}
                    </span>
                    
                    <button id="next-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            onclick="nextStep()">
                        {{ $currentStep >= $totalSteps ? 'Terminer' : 'Suivant →' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="text-gray-700">Installation en cours...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = {{ $currentStep }};
        const totalSteps = {{ $totalSteps }};

        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('loading-overlay').classList.add('flex');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
            document.getElementById('loading-overlay').classList.remove('flex');
        }

        function nextStep() {
            if (currentStep >= totalSteps) {
                finishInstallation();
                return;
            }

            if (validateCurrentStep()) {
                processCurrentStep();
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                window.location.href = `/install?step=${currentStep - 1}`;
            }
        }

        function validateCurrentStep() {
            // Validation spécifique selon l'étape
            switch(currentStep) {
                case 3: // Database
                    const required = ['db_host', 'db_port', 'db_database', 'db_username'];
                    for (let field of required) {
                        if (!document.getElementById(field)?.value) {
                            alert('Veuillez remplir tous les champs obligatoires');
                            return false;
                        }
                    }
                    break;
                case 4: // Admin
                    const adminRequired = ['admin_name', 'admin_email', 'admin_password', 'admin_password_confirmation'];
                    for (let field of adminRequired) {
                        if (!document.getElementById(field)?.value) {
                            alert('Veuillez remplir tous les champs obligatoires');
                            return false;
                        }
                    }
                    if (document.getElementById('admin_password').value !== document.getElementById('admin_password_confirmation').value) {
                        alert('Les mots de passe ne correspondent pas');
                        return false;
                    }
                    break;
                case 5: // Retell
                    if (!document.getElementById('retell_api_key')?.value) {
                        alert('Veuillez saisir votre clé API Retell AI');
                        return false;
                    }
                    break;
            }
            return true;
        }

        function processCurrentStep() {
            showLoading();

            const formData = new FormData();
            formData.append('step', currentStep);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Collecter les données selon l'étape
            if (currentStep === 3) {
                ['db_host', 'db_port', 'db_database', 'db_username', 'db_password'].forEach(field => {
                    const element = document.getElementById(field);
                    if (element) formData.append(field, element.value);
                });
            } else if (currentStep === 4) {
                ['admin_name', 'admin_email', 'admin_password', 'admin_password_confirmation'].forEach(field => {
                    const element = document.getElementById(field);
                    if (element) formData.append(field, element.value);
                });
            } else if (currentStep === 5) {
                ['retell_api_key', 'retell_webhook_url'].forEach(field => {
                    const element = document.getElementById(field);
                    if (element) formData.append(field, element.value);
                });
            }

            fetch('/install', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    if (data.next_step) {
                        window.location.href = `/install?step=${data.next_step}`;
                    } else if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                alert('Une erreur s\'est produite');
            });
        }

        function finishInstallation() {
            if (confirm('Êtes-vous sûr de vouloir finaliser l\'installation ?')) {
                showLoading();
                
                fetch('/install', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ step: 7 })
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.success) {
                        alert('Installation terminée avec succès !');
                        window.location.href = data.redirect || '/admin';
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    alert('Une erreur s\'est produite lors de la finalisation');
                });
            }
        }

        // Test de base de données en temps réel
        function testDatabase() {
            const data = {
                db_host: document.getElementById('db_host').value,
                db_port: document.getElementById('db_port').value,
                db_database: document.getElementById('db_database').value,
                db_username: document.getElementById('db_username').value,
                db_password: document.getElementById('db_password').value
            };

            fetch('/install/test-database', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                const indicator = document.getElementById('db-test-result');
                if (result.success) {
                    indicator.innerHTML = '<span class="text-green-600">✅ Connexion réussie</span>';
                } else {
                    indicator.innerHTML = '<span class="text-red-600">❌ ' + result.message + '</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('db-test-result').innerHTML = '<span class="text-red-600">❌ Erreur de test</span>';
            });
        }
    </script>
</body>
</html>