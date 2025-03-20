<?php

namespace App\Middlewares;

class DemoModeMiddleware
{
    private $demoMode;

    public function __construct()
    {
        // Controlla se la modalità demo è attiva
        $this->demoMode = getenv('DEMO_MODE') === 'true' || $_ENV['DEMO_MODE'] ?? false;
    }

    public function handle()
    {
        if ($this->demoMode) {
            http_response_code(403);
            echo json_encode(["error" => "Demo mode enabled. This action is not allowed."]);
            exit();
        }
    }
}
