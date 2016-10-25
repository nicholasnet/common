# Encryption

Back to [index](../index.md)

- [Introduction](#introduction)
- [Configuration](#configuration)
- [Using The Encrypter](#using-the-encrypter)

<a name="introduction"></a>
## Introduction

#### This class is the inspired by Laravel Encryption package. You can find more information [here](https://laravel.com/docs/5.3/encryption).

<a name="configuration"></a>
## Configuration

Before using Encrypter, you must set a `key` option. Encrypter uses PHP's secure random bytes if available if not then it will fallback to OpenSSL if even that is not available then it will fallback to mt_rand **which is insecure so this option must be enable explicitly enabled during instantiation**.
    
    <?php
    
    // Allow insecure random bytes if not using PHP >= 7 or does not have openssl_random_pseudo_byte.
    $enctypter = new Encrypter($key, 'AES-128-CBC', true);
    
    // Disallow insecure random bytes if not using PHP >= 7 or does not have openssl_random_pseudo_byte.
    $enctypter = new Encrypter($key); // Default behaviour
    
<a name="using-the-encrypter"></a>
## Using The Encrypter

#### Encrypting A Value

You may encrypt a value using the `encrypt` helper. All encrypted values are encrypted using OpenSSL and the `AES-256-CBC` cipher. Furthermore, all encrypted values are signed with a message authentication code (MAC) to detect any modifications to the encrypted string:

    <?php

    $enctypter = new Encrypter($key);
    $enctypter->encrypt('Very secret message');

> {note} Encrypted values are passed through `serialize` during encryption, which allows for encryption of objects and arrays. Thus, non-PHP clients receiving encrypted values will need to `unserialize` the data.

#### Decrypting A Value

You may decrypt values using the `decrypt` helper. If the value can not be properly decrypted, such as when the MAC is invalid, an `IdeasBucket\Common\Utils\DecryptException` will be thrown:

    use IdeasBucket\Common\Utils\DecryptException;

    try {
    
        $enctypter = new Encrypter($key);
        $encryptedValue = $enctypter->encrypt('Very secret message');
        $decrypted = $enctypter->decrypt($encryptedValue);
        
    } catch (DecryptException $e) {
    
        //
        
    }
