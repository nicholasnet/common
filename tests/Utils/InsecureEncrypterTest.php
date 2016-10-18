<?php
namespace {

    $MOCK_NATIVE_METHOD = false;
}

namespace IdeasBucket\Common\Utils {

    function function_exists($parameter) {

        global $MOCK_NATIVE_METHOD;

        $args = func_get_args();

        if (isset($MOCK_NATIVE_METHOD) && $MOCK_NATIVE_METHOD === true) {

            return false;

        }

        return call_user_func('\function_exists', $args[0]);
    }

    class InsecureEncrypterTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @expectedException \IdeasBucket\Common\Utils\EncryptException
         */
        public function testExceptionIfInsecureIsNotAllowed()
        {
            global $MOCK_NATIVE_METHOD;
            $MOCK_NATIVE_METHOD = true;
            $e = new Encrypter(str_repeat('a', 16));
            $e->encrypt('foo');
        }

        public function testInsecureEncryption()
        {
            global $MOCK_NATIVE_METHOD;
            $MOCK_NATIVE_METHOD = true;
            $e = new Encrypter(str_repeat('a', 16), 'AES-128-CBC', true);
            $encrypted = $e->encrypt('foo');
            $this->assertNotEquals('foo', $encrypted);
            $this->assertEquals('foo', $e->decrypt($encrypted));
        }
    }
}