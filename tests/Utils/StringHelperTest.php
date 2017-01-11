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

    public function testUuidGeneration()
    {
        $result = StringHelper::uuid();
        $pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/';
        $match = (bool) preg_match($pattern, $result);
        $this->assertTrue($match);
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

    public function testRandom()
    {
        $this->assertEquals(16, strlen(StringHelper::random()));
        $randomInteger = random_int(1, 100);
        $this->assertEquals($randomInteger, strlen(StringHelper::random($randomInteger)));
        $this->assertInternalType('string', StringHelper::random());
    }

    public function testTruncate()
    {
        $this->assertEquals('Laravel is...', StringHelper::truncate('Laravel is a free, open source PHP web application framework.', 10));
        $this->assertEquals('这是一...', StringHelper::truncate('这是一段中文', 6));
        $this->assertEquals('test', StringHelper::truncate('test', 6));
    }

    /**
     * @requires extension intl
     */
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
     * @requires extension intl
     */
    public function testToAscii($string, $transliteratorId, $expected)
    {
        $result = StringHelper::ascii($string, $transliteratorId);
        $this->assertEquals($expected, $result);
    }

    public function toAsciiProvider()
    {
        return [
            [
                'Foo Bar: Not just for breakfast any-more', null,
                'Foo Bar: Not just for breakfast any-more'
            ],
            [
                'A æ Übérmensch på høyeste nivå! И я люблю PHP! ест. ﬁ ¦', null,
                'A ae Ubermensch pa hoyeste niva! I a lublu PHP! est. fi '
            ],
            [
                'Äpfel Über Öl grün ärgert groß öko', null,
                'Apfel Uber Ol grun argert gross oko'
            ],
            [
                'La langue française est un attribut de souveraineté en France', null,
                'La langue francaise est un attribut de souverainete en France'
            ],
            [
                '!@$#exciting stuff! - what !@-# was that?', null,
                '!@$#exciting stuff! - what !@-# was that?'
            ],
            [
                'controller/action/りんご/1', null,
                'controller/action/ringo/1'
            ],
            [
                'の話が出たので大丈夫かなあと', null,
                'no huaga chutanode da zhang fukanaato'
            ],
            [
                'posts/view/한국어/page:1/sort:asc', null,
                'posts/view/hangug-eo/page:1/sort:asc'
            ],
            [
                "non\xc2\xa0breaking\xc2\xa0space", null,
                'non breaking space'
            ]
        ];
    }
}
