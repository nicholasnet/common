# ArrayHelper
Back to [index](../index.md)

- [Introduction](#introduction)
- [Available Methods](#available-methods)

<a name="introduction"></a>
## Introduction
#### This class is the copy of Laravel Support Arr package. You can find more information [here](https://laravel.com/docs/5.3/helpers#available-methods).
ArrayHelper provides convenient methods to manipulate the array.

<a name="available-methods"></a>
## Available Methods
* [add](#method-array-add)
* [collapse](#method-array-collapse)
* [divide](#method-array-divide)
* [dot](#method-array-dot)
* [except](#method-array-except)
* [first](#method-array-first)
* [flatten](#method-array-flatten)
* [forget](#method-array-forget)
* [get](#method-array-get)
* [has](#method-array-has)
* [last](#method-array-last)
* [only](#method-array-only)
* [pluck](#method-array-pluck)
* [prepend](#method-array-prepend)
* [pull](#method-array-pull)
* [set](#method-array-set)
* [sort](#method-array-sort)
* [sortRecursive](#method-array-sort-recursive)
* [where](#method-array-where)
* [head](#method-head)
* [last](#method-last)
* [wrap](#method-wrap)

<a name="method-array-add"></a>
#### `add()`

The `add` function adds a given key / value pair to the array if the given key doesn't already exist in the array:

    $array = ArrayHelper::add(['name' => 'Desk'], 'price', 100);

    // ['name' => 'Desk', 'price' => 100]

<a name="method-array-collapse"></a>
#### `collapse()`

The `collapse` function collapses an array of arrays into a single array:

    $array = ArrayHelper::collapse([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);

    // [1, 2, 3, 4, 5, 6, 7, 8, 9]

<a name="method-array-divide"></a>
#### `divide()`

The `divide` function returns two arrays, one containing the keys, and the other containing the values of the original array:

    list($keys, $values) = ArrayHelper::divide(['name' => 'Desk']);

    // $keys: ['name']

    // $values: ['Desk']

<a name="method-array-dot"></a>
#### `dot()`

The `dot` function flattens a multi-dimensional array into a single level array that uses "dot" notation to indicate depth:

    $array = ArrayHelper::dot(['foo' => ['bar' => 'baz']]);

    // ['foo.bar' => 'baz'];

<a name="method-array-except"></a>
#### `except()`

The `except` function removes the given key / value pairs from the array:

    $array = ['name' => 'Desk', 'price' => 100];

    $array = ArrayHelper::except($array, ['price']);

    // ['name' => 'Desk']

<a name="method-array-first"></a>
#### `first()`

The `first` function returns the first element of an array passing a given truth test:

    $array = [100, 200, 300];

    $value = ArrayHelper::first($array, function ($value, $key) {
        return $value >= 150;
    });

    // 200

A default value may also be passed as the third parameter to the method. This value will be returned if no value passes the truth test:

    $value = ArrayHelper::first($array, $callback, $default);

<a name="method-array-flatten"></a>
#### `flatten()`

The `flatten` function will flatten a multi-dimensional array into a single level.

    $array = ['name' => 'Joe', 'languages' => ['PHP', 'Ruby']];

    $array = ArrayHelper::flatten($array);

    // ['Joe', 'PHP', 'Ruby'];

<a name="method-array-forget"></a>
#### `forget()`

The `forget` function removes a given key / value pair from a deeply nested array using "dot" notation:

    $array = ['products' => ['desk' => ['price' => 100]]];

    ArrayHelper::forget($array, 'products.desk');

    // ['products' => []]

<a name="method-array-get"></a>
#### `get()`

The `get` function retrieves a value from a deeply nested array using "dot" notation:

    $array = ['products' => ['desk' => ['price' => 100]]];

    $value = ArrayHelper::get($array, 'products.desk');

    // ['price' => 100]

The `get` function also accepts a default value, which will be returned if the specific key is not found:

    $value = ArrayHelper::get($array, 'names.john', 'default');

<a name="method-array-has"></a>
#### `has()`

The `has` function checks that a given item or items exists in an array using "dot" notation:

    $array = ['product' => ['name' => 'desk', 'price' => 100]];

    $hasItem = ArrayHelper::has($array, 'product.name');

    // true

    $hasItems = ArrayHelper::has($array, ['product.price', 'product.discount']);

    // false

<a name="method-array-last"></a>
#### `last()`

The `last` function returns the last element of an array passing a given truth test:

    $array = [100, 200, 300, 110];

    $value = ArrayHelper::last($array, function ($value, $key) {
        return $value >= 150;
    });

    // 300

<a name="method-array-only"></a>
#### `only()`

The `only` function will return only the specified key / value pairs from the given array:

    $array = ['name' => 'Desk', 'price' => 100, 'orders' => 10];

    $array = ArrayHelper::only($array, ['name', 'price']);

    // ['name' => 'Desk', 'price' => 100]

<a name="method-array-pluck"></a>
#### `pluck()`

The `pluck` function will pluck a list of the given key / value pairs from the array:

    $array = [
        ['developer' => ['id' => 1, 'name' => 'Taylor']],
        ['developer' => ['id' => 2, 'name' => 'Abigail']],
    ];

    $array = ArrayHelper::pluck($array, 'developer.name');

    // ['Taylor', 'Abigail'];

You may also specify how you wish the resulting list to be keyed:

    $array = ArrayHelper::pluck($array, 'developer.name', 'developer.id');

    // [1 => 'Taylor', 2 => 'Abigail'];

<a name="method-array-prepend"></a>
#### `prepend()`

The `prepend` function will push an item onto the beginning of an array:

    $array = ['one', 'two', 'three', 'four'];

    $array = ArrayHelper::prepend($array, 'zero');

    // $array: ['zero', 'one', 'two', 'three', 'four']

<a name="method-array-pull"></a>
#### `pull()`

The `pull` function returns and removes a key / value pair from the array:

    $array = ['name' => 'Desk', 'price' => 100];

    $name = ArrayHelper::pull($array, 'name');

    // $name: Desk

    // $array: ['price' => 100]

<a name="method-array-set"></a>
#### `set()`

The `set` function sets a value within a deeply nested array using "dot" notation:

    $array = ['products' => ['desk' => ['price' => 100]]];

    ArrayHelper::set($array, 'products.desk.price', 200);

    // ['products' => ['desk' => ['price' => 200]]]

<a name="method-array-sort"></a>
#### `sort()`

The `sort` function sorts the array by the results of the given Closure:

    $array = [
        ['name' => 'Desk'],
        ['name' => 'Chair'],
    ];

    $array = ArrayHelper::values(ArrayHelper::sort($array, function ($value) {
        return $value['name'];
    }));

    /*
        [
            ['name' => 'Chair'],
            ['name' => 'Desk'],
        ]
    */

<a name="method-array-sort-recursive"></a>
#### `sortRecursive()`

The `sortRecursive` function recursively sorts the array using the `sort` function:

    $array = [
        [
            'Roman',
            'Taylor',
            'Li',
        ],
        [
            'PHP',
            'Ruby',
            'JavaScript',
        ],
    ];

    $array = ArrayHelper::sortRecursive($array);

    /*
        [
            [
                'Li',
                'Roman',
                'Taylor',
            ],
            [
                'JavaScript',
                'PHP',
                'Ruby',
            ]
        ];
    */

<a name="method-array-where"></a>
#### `where()`

The `where` function filters the array using the given Closure:

    $array = [100, '200', 300, '400', 500];

    $array = ArrayHelper::where($array, function ($value, $key) {
        return is_string($value);
    });

    // [1 => 200, 3 => 400]

<a name="method-head"></a>
#### `head()`

The `head` function simply returns the first element in the given array:

    $array = [100, 200, 300];

    $first = ArrayHelper::head($array);

    // 100

<a name="method-last"></a>
#### `last()`

The `last` function returns the last element in the given array:

    $array = [100, 200, 300];

    $last = ArrayHelper::last($array);

    // 300
    
<a name="method-lwrap"></a>
#### `wrap()`

The `wrap` function wraps the element in an array if it is not an array already:

    $str = '2;

    $result = ArrayHelper::wrap($str);

    // ['2']    
