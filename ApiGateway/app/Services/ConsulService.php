<?php 

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ConsulService{

    protected $lockFile = 'consul_registered.lock';
    /**
     * Register The Service
     */
    public function registerService()
    {
        try{
            if (Storage::exists($this->lockFile)) {
                return; // Skip registration
            }
            
            $serviceData = [
                'Name' => 'ApiGateway',
                'ID' => 'api-gateway-1',
                'Address' => 'http://127.0.0.1', 
                'Port' => (int) env('APP_PORT', 8000),
                'Check' => [
                    'HTTP' => env("APP_URL", 'http://127.0.0.1:8000') . "/api/health",
                    'Interval' => '5s',
                    'Timeout' => '10s',
                    'DeregisterCriticalServiceAfter' => '1m'
                ]
            ];

            $consul_url = env("CONSUL_URL", "http://localhost:8500");
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->put("$consul_url/v1/agent/service/register", $serviceData);
            
            if($response->getStatusCode() === 200){
                Storage::put($this->lockFile, 'registered');
                return true;
            }

        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Get Sercice
     */
    public function getService($serviceName)
    {
        $consul_url = env("CONSUL_URL", "http://localhost:8500");
        $response =   Http::get("$consul_url/v1/catalog/service/{$serviceName}");
        $services = $response->json();
        $service_index = array_rand($services);

        if (count($services) > 0) {
            return $services[$service_index]['ServiceAddress'] . ":" . $services[$service_index]['ServicePort'];
        }

        return null;
    }
}