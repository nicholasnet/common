# ArrayHelper
Back to [index](../index.md)

- [Introduction](#introduction)
- [Available Methods](#available-methods)

<a name="introduction"></a>
## Introduction
#### This class is inspired by Laravel, CakePHP 3 and YII2 frameworks.

<a name="available-methods"></a>
## Available Methods
* [ascii](#ascii)
* [classBasename](#class-basename)
* [slug](#method-slug)
* [truncate](#method-truncate)
* [ordinalize](#method-ordinalize)
* [camelCase](#method-camelCase)
* [studlyCase](#method-studlyCase)
* [endsWith](#method-endsWith)
* [random](#method-random)
* [startsWith](#method-startsWith)
* [uuid](#method-uuid)

<a name="method-ascii"></a>
#### `ascii()`
The `ascii` by default converts all characters in provided string into equivalent ASCII characters. The method expects UTF-8 encoding. The character conversion can be controlled using transliteration identifiers which you can pass using the $transliteratorId argument. ICU transliteration identifiers are basically of form ``<source script>:<target script>`` and you can specify multiple conversion pairs separated by ``;``. You can find more info about transliterator identifiers `here <http://userguide.icu-project.org/transforms/general#TOC-Transliterator-Identifiers>`_::

    // apple puree
    StringHelper::ascii('apple purée');
    
    // Ubermensch (only latin characters are transliterated)
    StringHelper::ascii('Übérmensch', 'Latin-ASCII;');
    
<a name="method-class-basename"></a>
#### `classBasename()`

The `classBasename` returns the class name of the given class with the class' namespace removed:

    $class = StringHelper::classBasename('Foo\Bar\Baz');

    // Baz
    
<a name="method-slug"></a>
#### `slug()`
The `slug` function generates a URL friendly "slug" from the given string:

    $title = StringHelper::slug('Laravel 5 Framework', '-');

    // laravel-5-framework

<a name="method-truncate"></a>
#### `truncate()`

The `truncate` function limits the number of characters in a string. The function accepts a string as its first argument and the maximum number of resulting characters as its second argument:

    $value = StringHelper::truncate('The PHP framework for web artisans.', 7);

    // The PHP...
    
<a name="method-ordinalize"></a>
#### `ordinalize()`

The `ordinalize` converts number to its ordinal English form. For example, converts 13 to 13th, 2 to 2nd:

    $value = StringHelper::ordinalize(1);

    // 1st   

<a name="method-camel-case"></a>
#### `camelCase()`

The `camelCase` function converts the given string to `camelCase`:

    $camel = StringHelper::camelCase('foo_bar');

    // fooBar
    
<a name="method-studly-case"></a>
#### `studlyCase()`

The `studlyCase` function converts the given string to `StudlyCase`:

    $value = StringHelper::studlyCase('foo_bar');

    // FooBar
    
<a name="method-ends-with"></a>
#### `endsWith()`

The `endsWith` function determines if the given string ends with the given value:

    $value = StringHelper::endsWith('This is my name', 'name');

    // true
        
<a name="method-starts-with"></a>
#### `startsWith()`

The `startsWith` function determines if the given string begins with the given value:

    $value = StringHelper::startsWith('This is my name', 'This');

    // true 
    
<a name="method-random"></a>
#### `random()`

The `random` function generates a random string of the specified length. This function uses PHP's random_bytes function:

    $value = StringHelper::random(16);
    
**Added since version 1.1**    
       
<a name="method-uuid"></a>
#### `uuid()`

The `uuid` generate unique identifiers as per **RFC 4122**. The UUID is a 128-bit string in the format of 485fc381-e790-47a3-9794-1337c0a8fe68: 

**_This method should not be used as a random seed for any cryptographic operations._**

    $value = StringHelper::uuid();
    