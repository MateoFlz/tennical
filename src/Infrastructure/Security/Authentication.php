<?php
declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Exceptions\AuthenticationException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class Authentication
{
    private string $secret;
    private int $expiration;
    
    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'] ?? 'default_secret_key_for_development';
        $this->expiration = (int) ($_ENV['JWT_EXPIRATION'] ?? 3600);
    }
    

    public function generateToken(int $userId, string $role): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->expiration;
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'userId' => $userId,
            'role' => $role
        ];
        
        return JWT::encode($payload, $this->secret, 'HS256');
    }
    

    public function verifyToken(): stdClass
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new AuthenticationException('Token no proporcionado o formato inválido');
        }
        
        $token = $matches[1];
        
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            throw new AuthenticationException('Token inválido: ' . $e->getMessage());
        }
    }
    

    public function getCurrentUserId(): int
    {
        $payload = $this->verifyToken();
        return $payload->userId;
    }
    

    public function getCurrentUserRole(): string
    {
        $payload = $this->verifyToken();
        return $payload->role;
    }
}
