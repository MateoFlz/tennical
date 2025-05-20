<?php
declare(strict_types=1);

namespace App\Infrastructure\API;


class Response
{
    private array $data = [];
    private int $statusCode = 200;
    private array $headers = [];
    

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }
    

    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }
    

    public function success(array $data, int $code = 200): self
    {
        $this->data = [
            'success' => true,
            'data' => $data
        ];
        $this->statusCode = $code;
        return $this;
    }
    

    public function error(string $message, int $code = 400, array $errors = []): self
    {
        $this->data = [
            'success' => false,
            'message' => $message
        ];
        
        if (!empty($errors)) {
            $this->data['errors'] = $errors;
        }
        
        $this->statusCode = $code;
        return $this;
    }
    

    public function send(): void
    {
        http_response_code($this->statusCode);
        
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
