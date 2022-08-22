<?php

require_once('../vendor/autoload.php');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    private $key = 'agfafhsdahsjsahd';
    private $alg = 'HS256';
    private $iss = "http://hedgehog/api/";

    public function createToken($userId): string
    {
        $date = new DateTimeImmutable();
        $iat = $date->getTimestamp();
        $exp = $date->modify('+10 minutes')->getTimestamp();
        $payload = [
            'iss' => $this->iss,
            'aut' => $this->iss,
            'iat' => $iat,
            'nbf' => $iat,
            'exp' => $exp,
            'userId' => $userId
        ];

        return JWT::encode($payload, $this->key, $this->alg);
    }

    public function validateToken() {
        if (!preg_match('/Bearer\s(\S+\.\S+\.\S+)/', $_SERVER['HTTP_AUTHORISATION'],$matches)) {
            http_response_code(400);
            echo 'Token not found in request';
            return false;
        }

        $jwt = $matches[1];

        if (!$jwt) {
            http_response_code(400);
            echo 'Token not found in request';
            exit;
        }

        try {
            $token = JWT::decode($jwt, new Key($this->key, $this->alg));
        } catch (Exception $error) {
            echo $error->getMessage();
            exit;
        }

        $time = new DateTimeImmutable();
        $currentTime = $time->getTimestamp();

        if ($token->iss !== $this->iss || $token->nbf > $currentTime || $token->exp < $currentTime) {
            http_response_code(401);
            echo "Unauthorised";
            exit;
        }

        return $token->userId;
    }
}