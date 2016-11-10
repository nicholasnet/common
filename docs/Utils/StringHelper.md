# ArrayHelper
Back to [index](../index.md)

- [Introduction](#introduction)
- [Available Methods](#available-methods)

<a name="introduction"></a>
## Introduction
#### This class is inspired by Laravel and YII2 frameworks. You can find more information about YII2 here and Laravel here.

<a name="available-methods"></a>
## Available Methods
* [slug](#method-slug)
* [length](#method-length)
* [truncate](#method-truncate)
* [substr](#method-substr)
* [ascii](#method-ascii)
* [ordinalize](#method-ordinalize)
* [camelCase](#method-camelCase)
* [studlyCase](#method-studlyCase)
* [endsWith](#method-endsWith)
* [startsWith](#method-startsWith)
* [uuid](#method-uuid)

<a name="method-slug"></a>
#### `slug()`
The `slug` function generates a URL friendly "slug" from the given string:

    $title = StringHelper::slug('Laravel 5 Framework', '-');

    // laravel-5-framework

<a name="method-truncate"></a>
#### `truncate()` {#collection-method}

The `truncate` function limits the number of characters in a string. The function accepts a string as its first argument and the maximum number of resulting characters as its second argument:

    $value = StringHelper::truncate('The PHP framework for web artisans.', 7);

    // The PHP...
