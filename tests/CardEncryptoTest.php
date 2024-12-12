<?php

use Decodeblock\Ercaspay\CardEncryptor;

it('successfully encrypts card details', function () {
    // Arrange: Create a temporary public key file
    $publicKeyContent = '
    -----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoLUWhs/7kVxZqJxJ1NEb
1sZWpdmd4NL/iMhVpZ3cBPYhHwZEfP0MYDwh79NiInQpSZRDY2e9OrXDgZ30v+X7
cUfhpP964qxvt5QeCd6VzFKCUsJeadsxaAVdrYOeFRqzoQKpytkS6Jt6ysvOfpGF
rZ7ogV0XdlcYe2Scn7ptp35qcgk1lhN90bSq09M4NjO1tH7gP5FTW3faytAAda7O
d/HWRuvMZNNqFh6nIQLmfzcxSfhe8rkJEX3v1ij8o94z/5nB8bqV0TDMh/uzIzyb
ffCqGi52m/j3YsynUWeOLGHv6I+mofxeIdT7Sn/hfE63FWzLHljA+t3JhN+9gn1M
DQIDAQAB
-----END PUBLIC KEY-----
    ';
    $publicKeyPath = sys_get_temp_dir().'/rsa_public_key.pub';
    file_put_contents($publicKeyPath, $publicKeyContent);

    $cardEncryptor = new CardEncryptor($publicKeyPath);

    $cardParams = [
        'cvv' => '123',
        'pin' => '4567',
        'expiryDate' => '1225',
        'pan' => '4111111111111111',
    ];

    // Act: Encrypt the card details
    $encryptedData = $cardEncryptor->encrypt($cardParams);

    // Assert: Ensure the encrypted data is not empty
    expect($encryptedData)->not()->toBeEmpty();
    expect(base64_decode($encryptedData, true))->not()->toBeFalse();

    // Clean up: Remove the temporary file
    unlink($publicKeyPath);
});

it('throws an exception if the public key file does not exist', function () {
    // Arrange: Provide an invalid path
    $invalidPath = '/invalid/path/to/public_key.pub';

    // Act & Assert: Expect an exception
    expect(fn () => new CardEncryptor($invalidPath))
        ->toThrow(InvalidArgumentException::class, "Public key file not found: {$invalidPath}");
});
