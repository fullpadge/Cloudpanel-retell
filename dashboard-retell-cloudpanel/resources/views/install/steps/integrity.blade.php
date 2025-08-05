<!-- Étape 1: Vérification d'intégrité -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Vérification d'intégrité</h2>
    <p class="text-gray-600">Vérification des fichiers et dossiers essentiels</p>
</div>

<div class="space-y-4">
    @if(isset($integrityCheck))
        @foreach($integrityCheck as $check)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-700">{{ $check['name'] }}</span>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $check['current'] }}</span>
                    @if($check['satisfied'])
                        <span class="text-green-600 font-bold">✅</span>
                    @else
                        <span class="text-red-600 font-bold">❌</span>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Vérification en cours...</p>
        </div>
    @endif
</div>

@if(isset($integrityCheck))
    @php
        $failed = collect($integrityCheck)->filter(function($check) { 
            return $check['required'] && !$check['satisfied']; 
        });
    @endphp

    @if($failed->count() > 0)
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="text-red-800 font-semibold mb-2">❌ Problèmes détectés</h3>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach($failed as $item)
                    <li>• {{ $item['name'] }} : {{ $item['current'] }}</li>
                @endforeach
            </ul>
            <p class="text-red-600 text-sm mt-2">Veuillez corriger ces problèmes avant de continuer.</p>
        </div>
    @else
        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <h3 class="text-green-800 font-semibold">✅ Vérification réussie</h3>
            <p class="text-green-700 text-sm">Tous les fichiers et dossiers sont présents et accessibles.</p>
        </div>
    @endif
@endif