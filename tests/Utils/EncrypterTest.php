<?php

namespace IdeasBucket\Common\Utils;

class EncrypterTest extends \PHPUnit_Framework_TestCase
{
    public function testEncryption()
    {
        $e = new Encrypter(str_repeat('a', 16));
        $encrypted = $e->encrypt('foo');
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', $e->decrypt($encrypted));
    }

    public function testEncryptionUsingBase64EncodedKey()
    {
        $e = new Encrypter($this->getRandomBytes());
        $encrypted = $e->encrypt('foo');
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', $e->decrypt($encrypted));
    }

    public function testWithCustomCipher()
    {
        $e = new Encrypter(str_repeat('b', 32), 'AES-256-CBC');
        $encrypted = $e->encrypt('bar');
        $this->assertNotEquals('bar', $encrypted);
        $this->assertEquals('bar', $e->decrypt($encrypted));
        $e = new Encrypter($e->getRandomBytes(32), 'AES-256-CBC');
        $encrypted = $e->encrypt('foo');
        $this->assertNotEquals('foo', $encrypted);
        $this->assertEquals('foo', $e->decrypt($encrypted));
    }

    public function testGetter()
    {
        $key = str_repeat('b', 32);
        $e = new Encrypter($key, 'AES-256-CBC');
        $this->assertEquals($key, $e->getKey());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key
     *                           lengths.
     */
    public function testDoNoAllowLongerKey()
    {
        new Encrypter(str_repeat('z', 32));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key
     *                           lengths.
     */
    public function testWithBadKeyLength()
    {
        new Encrypter(str_repeat('a', 5));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key
     *                           lengths.
     */
    public function testWithBadKeyLengthAlternativeCipher()
    {
        new Encrypter(str_repeat('a', 16), 'AES-256-CFB8');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key
     *                           lengths.
     */
    public function testWithUnsupportedCipher()
    {
        new Encrypter(str_repeat('c', 16), 'AES-256-CFB8');
    }

    /**
     * @expectedException \IdeasBucket\Common\Utils\DecryptException
     * @expectedExceptionMessage The payload is invalid.
     */
    public function testExceptionThrownWhenPayloadIsInvalid()
    {
        $e = new Encrypter(str_repeat('a', 16));
        $payload = $e->encrypt('foo');
        $payload = str_shuffle($payload);
        $e->decrypt($payload);
    }

    /**
     * @expectedException \IdeasBucket\Common\Utils\DecryptException
     * @expectedExceptionMessage The MAC is invalid.
     */
    public function testExceptionThrownWithDifferentKey()
    {
        $a = new Encrypter(str_repeat('a', 16));
        $b = new Encrypter(str_repeat('b', 16));
        $b->decrypt($a->encrypt('baz'));
    }

    /**
     * @param int $length
     *
     * @return string|void
     */
    private function getRandomBytes($length = 16)
    {
        if (function_exists('random_bytes')) {
            return random_bytes(16);
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strongSource);

            if (!$strongSource) {
                throw new EncryptException('openssl was unable to use a strong source of entropy. '.
                    'Consider updating your system libraries, or ensuring '.
                    'you have more available entropy.');
            }

            return $bytes;
        }

        throw new EncryptException('You do not have a safe source of random data available. '.
            'Install either the openssl extension, or paragonie/random_compat.');
    }
}
