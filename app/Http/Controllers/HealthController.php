<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index(): JsonResponse
    {
        $dbStatus = 'unknown';
        try {
            DB::connection()->getPdo();
            $dbStatus = 'ok';
        } catch (\Throwable $e) {
            $dbStatus = 'fail';
        }

        $isTidb = in_array(env('DB_CONNECTION'), ['tidb', 'mysql']) && (
            env('TIDB_HOST') || str_contains(env('DB_HOST', ''), 'tidb')
        );

        $tidbSsl = null;
        if ($isTidb) {
            $caPath = env('TIDB_SSL_CA') ?: env('MYSQL_ATTR_SSL_CA');
            $tidbSsl = [
                'ca_path' => $caPath,
                'ca_exists' => $caPath ? file_exists($caPath) : false,
            ];
        }

        return response()->json([
            'status' => $dbStatus === 'ok' ? 'ok' : 'fail',
            'db' => $dbStatus,
            'tidb' => $tidbSsl,
            'app_env' => env('APP_ENV'),
            'version' => config('app.version', null),
        ]);
    }
}
