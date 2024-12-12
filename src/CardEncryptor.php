<?php

namespace Decodeblock\Ercaspay;

class CardEncryptor
{
    protected string $publicKeyPath;

    public function __construct(string $publicKeyPath)
    {
        if (! file_exists($publicKeyPath)) {
            throw new \InvalidArgumentException("Public key file not found: {$publicKeyPath}");
        }

        $this->publicKeyPath = $publicKeyPath;
    }

    public function encrypt(array $cardParams): string
    {
        // Load the public key
        $publicKey = file_get_contents($this->publicKeyPath);
        if (! $publicKey) {
            throw new \RuntimeException("Failed to read public key file: {$this->publicKeyPath}");
        }

        // Remove "RSA" from the header and trim whitespace
        $publicKey = str_replace('RSA', '', $publicKey);
        $publicKey = trim($publicKey);

        // Convert card details to JSON
        $cardJson = json_encode($cardParams);

        // Encrypt the card details
        if (openssl_public_encrypt($cardJson, $encrypted, $publicKey)) {
            // Return the encrypted data as a Base64-encoded string
            return base64_encode($encrypted);
        } else {
            throw new \RuntimeException('Encryption failed: '.openssl_error_string());
        }
    }
}
