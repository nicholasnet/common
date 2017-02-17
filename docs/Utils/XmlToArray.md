# XmlToArray
Back to [index](../index.md)

- [Introduction](#introduction)
- [Available Methods](#available-methods)

<a name="introduction"></a>
## Introduction
##### This class is inspired by work of [Lalit Lab](http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array/)
The `IdeasBucket\Common\Utils\XmlToArray` class provides method that can convert XML into plain PHP array.

<a name="available-methods"></a>
## Available Methods
* [createArray](#create-array)

<a name="create-array"></a>
#### `createArray()`
The `createArray` method coverts the given XML to Array. 

    XmlToArray::createArray('<a version="1.0"><b><c><f name="test">ererf</f></c></b></a>');
    
    // Will produce array like this.
    // Array
    //   (
    //       [a] => Array
    //           (
    //               [b] => Array
    //                   (
    //                       [c] => Array
    //                           (
    //                               [f] => Array
    //                                   (
    //                                       [@value] => ererf
    //                                       [@attributes] => Array
    //                                           (
    //                                               [name] => test
    //                                           )
    //   
    //                                   )
    //   
    //                           )
    //   
    //                   )
    //   
    //               [@attributes] => Array
    //                   (
    //                       [version] => 1.0
    //                   )
    //   
    //           )
    //   
    //   )

    
