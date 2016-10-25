<?php

namespace IdeasBucket\Common\Utils;

/**
 * Class Encrypter.
 *
 * @see  https://github.com/laravel/framework/blob/5.3/LICENSE.md
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
     * Flag that indicates whether insecure random bytes are allowed if user is not using PHP7 and there is no
     * openssl_random_pseudo_bytes method available.
     *
     * @var bool
     */
    private $allowCryptographicallyInsecureRandom = false;

    /**
     * Create a new encrypter instance.
     *
     * @param string $key
     * @param string $cipher
     * @param bool   $allowCryptographicallyInsecureRandom
     *
     * @throws \RuntimeException
     */
    public function __construct($key, $cipher = 'AES-128-CBC', $allowCryptographicallyInsecureRandom = false)
    {
        $key = (string) $key;

        if (static::supported($key, $cipher)) {

            $this->key = $key;
            $this->cipher = $cipher;

        } else {

            throw new \RuntimeException('The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.');

        }

        $this->allowCryptographicallyInsecureRandom = $allowCryptographicallyInsecureRandom;
    }

    /**
     * Determine if the given key and cipher combination is valid.
     *
     * @param string $key
     * @param string $cipher
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
     * @param string $value
     *
     * @throws EncryptException
     *
     * @return string
     */
    public function encrypt($value)
    {
        $iv = $this->getRandomBytes(16);

        $value = openssl_encrypt(serialize($value), $this->cipher, $this->key, 0, $iv);

        if ($value === false) {

            throw new EncryptException('Could not encrypt the data.');

        }

        // Once we have the encrypted value we will go ahead base64_encode the input
        // vector and create the MAC for the encrypted value so we can verify its
        // authenticity. Then, we'll JSON encode the data in a "payload" array.
        $mac = $this->hash($iv = base64_encode($iv), $value);
        $json = json_encode(compact('iv', 'value', 'mac'));

        if (! is_string($json)) {

            throw new EncryptException('Could not encrypt the data.');

        }

        return base64_encode($json);
    }

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     *
     * @throws DecryptException
     *
     * @return string
     */
    public function decrypt($payload)
    {
        $payload = $this->getJsonPayload($payload);
        $iv = base64_decode($payload['iv']);
        $decrypted = \openssl_decrypt($payload['value'], $this->cipher, $this->key, 0, $iv);

        if ($decrypted === false) {

            throw new DecryptException('Could not decrypt the data.');

        }

        return unserialize($decrypted);
    }

    /**
     * Create a MAC for the given value.
     *
     * @param string $iv
     * @param string $value
     *
     * @return string
     */
    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv.$value, $this->key);
    }

    /**
     * Get the JSON array from the given payload.
     *
     * @param string $payload
     *
     * @throws DecryptException
     *
     * @return array
     */
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (! $this->validPayload($payload)) {

            throw new DecryptException('The payload is invalid.');

        }

        if (! $this->validMac($payload)) {

            throw new DecryptException('The MAC is invalid.');

        }

        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param mixed $payload
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
     * @param array $payload
     *
     * @return bool
     */
    protected function validMac(array $payload)
    {
        $bytes = $this->getRandomBytes(16);
        $calcMac = hash_hmac('sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true);

        return hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), $calcMac);
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

    /**
     * Get random bytes from a secure source.
     *
     * This method will fall back to an insecure source an trigger a warning
     * if it cannot find a secure source of random data.
     *
     * @param int $length The number of bytes you want.
     *
     * @return string Random bytes in binary.
     */
    public function getRandomBytes($length)
    {
        if (function_exists('random_bytes')) {

            return random_bytes($length);

        }

        if (function_exists('openssl_random_pseudo_bytes')) {

            $bytes = openssl_random_pseudo_bytes($length, $strongSource);

            if ($strongSource === false) {

                throw new EncryptException(
                    'openssl was unable to use a strong source of entropy. '.
                    'Consider updating your system libraries, or ensuring '.
                    'you have more available entropy.'
                );

            }

            return $bytes;
        }

        if ($this->allowCryptographicallyInsecureRandom === false) {

            throw new EncryptException(
                'You do not have a safe source of random data available. '.
                'Install either the openssl extension, or paragonie/random_compat.'
            );

        }

        return $this->insecureRandomBytes($length);
    }

    /**
     * Like randomBytes() above, but not cryptographically secure.
     *
     * @param int $length The number of bytes you want.
     *
     * @return string Random bytes in binary.
     *
     * @see \IdeasBucket\Common\Utils\Encrypter::getRandomBytes()
     */
    public function insecureRandomBytes($length)
    {
        $length *= 2;
        $bytes = '';
        $byteLength = 0;

        while ($byteLength < $length) {

            $bytes .= $this->hash(StringHelper::uuid().uniqid(mt_rand(), true), 'sha512');
            $byteLength = strlen($bytes);

        }

        $bytes = substr($bytes, 0, $length);

        return pack('H*', $bytes);
    }
}
