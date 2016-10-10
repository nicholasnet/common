<?php

namespace IdeasBucket\Common\Utils;

class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testOrdinalize()
    {
        $this->assertEquals('21st', StringHelper::ordinalize('21'));
        $this->assertEquals('22nd', StringHelper::ordinalize('22'));
        $this->assertEquals('23rd', StringHelper::ordinalize('23'));
        $this->assertEquals('24th', StringHelper::ordinalize('24'));
        $this->assertEquals('25th', StringHelper::ordinalize('25'));
        $this->assertEquals('111th', StringHelper::ordinalize('111'));
        $this->assertEquals('113th', StringHelper::ordinalize('113'));
    }

    public function testStrlen()
    {
        $this->assertEquals(4, StringHelper::length('this'));
        $this->assertEquals(6, StringHelper::length('это'));
    }

    public function testCamelCase()
    {
        $this->assertEquals('laravelPHPFramework', StringHelper::camelCase('Laravel_p_h_p_framework'));
        $this->assertEquals('laravelPhpFramework', StringHelper::camelCase('Laravel_php_framework'));
        $this->assertEquals('laravelPhPFramework', StringHelper::camelCase('Laravel-phP-framework'));
        $this->assertEquals('laravelPhpFramework', StringHelper::camelCase('Laravel  -_-  php   -_-   framework   '));
        $this->assertEquals('laravelPhpFramework', StringHelper::camelCase('Laravel  -_-  php   -_-   framework   '));
    }

    public function testStudly()
    {
        $this->assertEquals('LaravelPHPFramework', StringHelper::studlyCase('laravel_p_h_p_framework'));
        $this->assertEquals('LaravelPhpFramework', StringHelper::studlyCase('laravel_php_framework'));
        $this->assertEquals('LaravelPhPFramework', StringHelper::studlyCase('laravel-phP-framework'));
        $this->assertEquals('LaravelPhpFramework', StringHelper::studlyCase('laravel  -_-  php   -_-   framework   '));
        $this->assertEquals('LaravelPHPFramework', StringHelper::studlyCase('laravel_p_h_p_framework'));
    }

    public function testSubstr()
    {
        $this->assertEquals('th', StringHelper::substr('this', 0, 2));
        $this->assertEquals('э', StringHelper::substr('это', 0, 2));
        $this->assertEquals('abcdef', StringHelper::substr('abcdef', 0));
        $this->assertEquals('abcdef', StringHelper::substr('abcdef', 0, null));
        $this->assertEquals('de', StringHelper::substr('abcdef', 3, 2));
        $this->assertEquals('def', StringHelper::substr('abcdef', 3));
        $this->assertEquals('def', StringHelper::substr('abcdef', 3, null));
        $this->assertEquals('cd', StringHelper::substr('abcdef', -4, 2));
        $this->assertEquals('cdef', StringHelper::substr('abcdef', -4));
        $this->assertEquals('cdef', StringHelper::substr('abcdef', -4, null));
        $this->assertEquals('', StringHelper::substr('abcdef', 4, 0));
        $this->assertEquals('', StringHelper::substr('abcdef', -4, 0));
        $this->assertEquals('это', StringHelper::substr('это', 0));
        $this->assertEquals('это', StringHelper::substr('это', 0, null));
        $this->assertEquals('т', StringHelper::substr('это', 2, 2));
        $this->assertEquals('то', StringHelper::substr('это', 2));
        $this->assertEquals('то', StringHelper::substr('это', 2, null));
        $this->assertEquals('т', StringHelper::substr('это', -4, 2));
        $this->assertEquals('то', StringHelper::substr('это', -4));
        $this->assertEquals('то', StringHelper::substr('это', -4, null));
        $this->assertEquals('', StringHelper::substr('это', 4, 0));
        $this->assertEquals('', StringHelper::substr('это', -4, 0));
    }

    public function testStartsWith()
    {
        $this->assertTrue(StringHelper::startsWith('jason', 'jas'));
        $this->assertTrue(StringHelper::startsWith('jason', 'jason'));
        $this->assertTrue(StringHelper::startsWith('jason', ['jas']));
        $this->assertTrue(StringHelper::startsWith('jason', ['day', 'jas']));
        $this->assertFalse(StringHelper::startsWith('jason', 'day'));
        $this->assertFalse(StringHelper::startsWith('jason', ['day']));
        $this->assertFalse(StringHelper::startsWith('jason', ''));
        $this->assertFalse(StringHelper::startsWith('7', ' 7'));
    }

    public function testEndsWith()
    {
        $this->assertTrue(StringHelper::endsWith('jason', 'on'));
        $this->assertTrue(StringHelper::endsWith('jason', 'jason'));
        $this->assertTrue(StringHelper::endsWith('jason', ['on']));
        $this->assertTrue(StringHelper::endsWith('jason', ['no', 'on']));
        $this->assertFalse(StringHelper::endsWith('jason', 'no'));
        $this->assertFalse(StringHelper::endsWith('jason', ['no']));
        $this->assertFalse(StringHelper::endsWith('jason', ''));
        $this->assertFalse(StringHelper::endsWith('7', ' 7'));
    }

    public function testTruncate()
    {
        $this->assertEquals('Laravel is...', StringHelper::truncate('Laravel is a free, open source PHP web application framework.', 10));
        $this->assertEquals('这是一...', StringHelper::truncate('这是一段中文', 6));
    }

    public function testSlug()
    {
        $this->assertEquals('hello-world', StringHelper::slug('hello world'));
        $this->assertEquals('hello-world', StringHelper::slug('hello-world'));
        $this->assertEquals('hello-world', StringHelper::slug('hello_world'));
        $this->assertEquals('hello_world', StringHelper::slug('hello_world', true, '_'));
        $this->assertEquals('Hello-World', StringHelper::slug('Hello World', false));
    }

    /**
     * @dataProvider toAsciiProvider()
     */
    public function testToAscii($expected, $str)
    {
        $result = StringHelper::ascii($str);
        $this->assertEquals($expected, $result);
    }

    public function toAsciiProvider()
    {
        return [
            ['foo bar', 'fòô bàř'],
            [' TEST ', ' ŤÉŚŢ '],
            ['f = z = 3', 'φ = ź = 3'],
            ['perevirka', 'перевірка'],
            ['lysaya gora', 'лысая гора'],
            ['shchuka', 'щука'],
            ['', '漢字'],
            ['xin chao the gioi', 'xin chào thế giới'],
            ['XIN CHAO THE GIOI', 'XIN CHÀO THẾ GIỚI'],
            ['dam phat chet luon', 'đấm phát chết luôn'],
            [' ', ' '], // no-break space (U+00A0)
            ['           ', '           '], // spaces U+2000 to U+200A
            [' ', ' '], // narrow no-break space (U+202F)
            [' ', ' '], // medium mathematical space (U+205F)
            [' ', '　'], // ideographic space (U+3000)
            ['', '𐍉'], // some uncommon, unsupported character (U+10349)
        ];
    }
}
