<!DOCTYPE html>
<html>
<head>
    <title>Test Dashboard Retell AI CloudPanel</title>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $message }}</h1>
        
        <div class="space-y-4">
            <p><strong>Étape actuelle :</strong> {{ $currentStep }}</p>
            <p><strong>Total étapes :</strong> {{ $totalSteps }}</p>
            
            <h2 class="text-lg font-semibold mt-6 mb-3">Étapes de l'installateur :</h2>
            <ul class="space-y-2">
            @foreach($steps as $num => $name)
                <li class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full {{ $num <= $currentStep ? 'bg-green-500' : 'bg-gray-300' }} text-white text-xs flex items-center justify-center">{{ $num }}</span>
                    <span class="{{ $num == $currentStep ? 'font-bold text-blue-600' : 'text-gray-700' }}">{{ ucfirst($name) }}</span>
                </li>
            @endforeach
            </ul>
            
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800"><strong>✅ Test réussi !</strong></p>
                <p class="text-green-700 text-sm mt-1">Si vous voyez ce message, Laravel fonctionne correctement sur CloudPanel.</p>
            </div>
            
            <div class="mt-4 space-x-4">
                <a href="/install" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Aller à l'installateur
                </a>
                <a href="/" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Page d'accueil
                </a>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-8 text-gray-500 text-sm">
        <p>Dashboard Retell AI - Version CloudPanel</p>
        <p>PHP {{ PHP_VERSION }} - Laravel {{ app()->version() }}</p>
    </div>
</body>
</html>