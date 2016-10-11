<?php

namespace IdeasBucket\Common\Utils;

/**
 * Class Encrypter
 * @package IdeasBucket\Common\Utils
 *
 * Note: Adapted from Laravel Framework.
 * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
 */
class Encrypter implements EncrypterInterface
{
    /**
     * The encryption key.
     *
     * @var string
     */
    protected $key;

    /**
     * The algorithm used for encryption.
     *
     * @var string
     */
    protected $cipher;

    /**
     * Create a new encrypter instance.
     *
     * @param  string $key
     * @param  string $cipher
     *
     * @throws \RuntimeException
     */
    public function __construct($key, $cipher = 'AES-128-CBC')
    {
        $key = (string)$key;

        if (static::supported($key, $cipher)) {

            $this->key = $key;
            $this->cipher = $cipher;

        } else {

            throw new \RuntimeException('The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');

        }
    }

    /**
     * Determine if the given key and cipher combination is valid.
     *
     * @param  string $key
     * @param  string $cipher
     *
     * @return bool
     */
    public static function supported($key, $cipher)
    {
        $length = mb_strlen($key, '8bit');

        return ($cipher === 'AES-128-CBC' && $length === 16) || ($cipher === 'AES-256-CBC' && $length === 32);
    }

    /**
     * Encrypt the given value.
     *
     * @param  string $value
     *
     * @return string
     *
     * @throws EncryptException
     */
    public function encrypt($value)
    {
        $iv = $this->getRandomBytes();

        if ($iv === false) {

            throw new EncryptException('Could not encrypt the data because no appropriate extension were found.');

        }

        $value = openssl_encrypt(serialize($value), $this->cipher, $this->key, 0, $iv);

        if ($value === false) {

            throw new EncryptException('Could not encrypt the data.');

        }

        // Once we have the encrypted value we will go ahead base64_encode the input
        // vector and create the MAC for the encrypted value so we can verify its
        // authenticity. Then, we'll JSON encode the data in a "payload" array.
        $mac = $this->hash($iv = base64_encode($iv), $value);

        $json = json_encode(compact('iv', 'value', 'mac'));

        if (!is_string($json)) {

            throw new EncryptException('Could not encrypt the data.');

        }

        return base64_encode($json);
    }

    /**
     * Decrypt the given value.
     *
     * @param  string $payload
     *
     * @return string
     *
     * @throws DecryptException
     */
    public function decrypt($payload)
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        $decrypted = openssl_decrypt($payload['value'], $this->cipher, $this->key, 0, $iv);

        if ($decrypted === false) {

            throw new DecryptException('Could not decrypt the data.');

        }

        return unserialize($decrypted);
    }

    /**
     * Create a MAC for the given value.
     *
     * @param  string $iv
     * @param  string $value
     *
     * @return string
     */
    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
     * Get the JSON array from the given payload.
     *
     * @param  string $payload
     *
     * @return array
     *
     * @throws DecryptException
     */
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (!$this->validPayload($payload)) {

            throw new DecryptException('The payload is invalid.');

        }

        if (!$this->validMac($payload)) {

            throw new DecryptException('The MAC is invalid.');

        }

        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param  mixed $payload
     *
     * @return bool
     */
    protected function validPayload($payload)
    {
        return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']);
    }

    /**
     * Determine if the MAC for the given payload is valid.
     *
     * @param  array $payload
     *
     * @return bool
     */
    protected function validMac(array $payload)
    {
        $bytes = $this->getRandomBytes();
        $calcMac = hash_hmac('sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true);

        return hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), $calcMac);
    }

    /**
     * @param int $length
     *
     * @return bool
     */
    private function getRandomBytes($length = 16)
    {
        if (function_exists('random_bytes')) {

            return random_bytes(16);

        }

        if (function_exists('openssl_random_pseudo_bytes')) {

            $bytes = openssl_random_pseudo_bytes($length, $strongSource);

            if (!$strongSource) {

                throw new EncryptException('openssl was unable to use a strong source of entropy. ' .
                    'Consider updating your system libraries, or ensuring ' .
                    'you have more available entropy.');

            }

            return $bytes;
        }

        throw new EncryptException('You do not have a safe source of random data available. ' .
            'Install either the openssl extension, or paragonie/random_compat.');
    }

    /**
     * Get the encryption key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}
