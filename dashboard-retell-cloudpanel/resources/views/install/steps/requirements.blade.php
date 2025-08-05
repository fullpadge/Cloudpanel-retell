<!-- Étape 2: Vérification prérequis -->
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Prérequis système</h2>
    <p class="text-gray-600">Vérification de la configuration PHP et des extensions</p>
</div>

<div class="space-y-4">
    @if(isset($requirements))
        @foreach($requirements as $req)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <span class="text-gray-700 font-medium">{{ $req['name'] }}</span>
                    @if(isset($req['minimum']))
                        <span class="text-xs text-gray-500 block">Minimum requis: {{ $req['minimum'] }}</span>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $req['current'] }}</span>
                    @if($req['satisfied'])
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

@if(isset($requirements))
    @php
        $failed = collect($requirements)->filter(function($req) { 
            return $req['required'] && !$req['satisfied']; 
        });
    @endphp

    @if($failed->count() > 0)
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="text-red-800 font-semibold mb-2">❌ Prérequis non satisfaits</h3>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach($failed as $item)
                    <li>• {{ $item['name'] }} : {{ $item['current'] }}</li>
                @endforeach
            </ul>
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                <h4 class="text-yellow-800 font-medium text-sm">Solutions recommandées :</h4>
                <ul class="text-yellow-700 text-xs mt-1 space-y-1">
                    <li>• Contactez votre hébergeur pour activer les extensions manquantes</li>
                    <li>• Vérifiez que PHP 8.1+ est activé dans CloudPanel</li>
                    <li>• Configurez les permissions des dossiers (chmod 755)</li>
                </ul>
            </div>
        </div>
    @else
        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <h3 class="text-green-800 font-semibold">✅ Prérequis validés</h3>
            <p class="text-green-700 text-sm">Votre serveur satisfait tous les prérequis nécessaires.</p>
        </div>
    @endif
@endif