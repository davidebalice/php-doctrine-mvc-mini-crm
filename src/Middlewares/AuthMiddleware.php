<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware
{
    private $secretKey = 'F934gG034jkgg431f';  // Chiave segreta per firmare il token

    public function checkAuth()
    {
        // Verifica se il token JWT è presente nel cookie
        if (!isset($_COOKIE['jwt_token'])) {
            return false; // Accesso negato se il token non esiste
        }

        $jwt = $_COOKIE['jwt_token']; // Prendi il token dal cookie

        try {
            // Decodifica il token JWT
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));


            // Se il token è valido, continua
            if (isset($decoded->exp) && $decoded->exp < time()) {
                // Se il token è scaduto
                return false;
            }

            return true; // Il token è valido
       } catch (Exception $e) {
           return false; // Se la decodifica fallisce, il token è invalido
       }
    }
}
