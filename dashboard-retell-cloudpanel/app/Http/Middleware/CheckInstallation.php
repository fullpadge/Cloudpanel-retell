<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\InstallationSetting;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for install routes and API webhooks
        if ($request->is('install*') || $request->is('api/webhook*') || $request->is('install-test')) {
            return $next($request);
        }

        // Check if installation is completed
        try {
            // First, check if we can connect to database
            DB::connection()->getPdo();
            
            // Check if installation_settings table exists
            if (!$this->tableExists('installation_settings')) {
                return redirect('/install');
            }
            
            // Check if installation is completed
            if (!InstallationSetting::isInstalled()) {
                return redirect('/install');
            }
        } catch (Exception $e) {
            // Database not accessible, redirect to install
            return redirect('/install');
        }

        return $next($request);
    }
    
    /**
     * Check if a table exists in the database
     */
    private function tableExists($tableName)
    {
        try {
            return DB::getSchemaBuilder()->hasTable($tableName);
        } catch (Exception $e) {
            return false;
        }
    }
}