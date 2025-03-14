<?php 

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ConsulService{

    protected $lockFile = 'consul_registered.lock';

    public function registerService()
    {
        try{
            if (Storage::exists($this->lockFile)) {
                return;
            }

            $serviceData = [
                'Name' => 'AuthService',
                'ID' => 'auth-service-1',
                'Address' => 'http://127.0.0.1', 
                'Port' => (int) env('APP_PORT', 8001),
                'Check' => [
                    'HTTP' => env("APP_URL", 'http://127.0.0.1:8001') . "/api/health",
                    'Interval' => '5s',
                    'Timeout' => '10s',
                    'DeregisterCriticalServiceAfter' => '1m'
                ]
            ];
    
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->put('http://localhost:8500/v1/agent/service/register', $serviceData);
            
            if($response->getStatusCode() === 200){
                Storage::put($this->lockFile, 'registered');
                return true;
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}