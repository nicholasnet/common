# ArrayToXml
Back to [index](../index.md)

- [Introduction](#introduction)
- [Available Methods](#available-methods)

<a name="introduction"></a>
## Introduction
##### This class is inspired by work of [Lalit Lab](http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array/)
The `IdeasBucket\Common\Utils\ArrayToXml` class provides method that can convert array into XML.

<a name="available-methods"></a>
## Available Methods
* [createXml](#create-xml)
* [getXmlString](#get-xml-string)

<a name="create-xml"></a>
#### `createXml()`
The `createXml` method coverts the given Array to DOMDocument. 

    $nest = ['type' => 'test', 'nest' => [
                'nest' => [
                    'nest' => [
                        '@attributes' => [
                            'prop1' => 'aloha',
                            'prop2' => 'hello',
                            'prop3' => 'ke cha',
                        ],
                        'nest' => [
                            '@attributes' => [
                                'prop1' => 'bazingaaa!!!!',
                            ],
                        ],
                    ],
                ],
            ]];
    
    ArrayToXml::createXml('test', $nest); // Will return \DOMDocument
    OR 
    ArrayToXml::createXml('test', $nest, 'UTF-8') // Will return \DOMDocument
    
    // Will produce \DOMDocument instace with following content.
    // <?xml version="1.0" encoding="UTF-8"?>
    // <test>
    //     <type>test</type>
    //     <nest>
    //         <nest>
    //             <nest prop1="aloha" prop2="hello" prop3="ke cha">
    //                 <nest prop1="bazingaaa!!!!"/>
    //             </nest>
    //         </nest>
    //     </nest>
    // </test>
    
<a name="get-xml-string"></a>
#### `getXmlString()`
The `getXmlString` method coverts the given Array to string. 

    $nest = ['type' => 'test', 'nest' => [
                'nest' => [
                    'nest' => [
                        '@attributes' => [
                            'prop1' => 'aloha',
                            'prop2' => 'hello',
                            'prop3' => 'ke cha',
                        ],
                        'nest' => [
                            '@attributes' => [
                                'prop1' => 'bazingaaa!!!!',
                            ],
                        ],
                    ],
                ],
            ]];
    
    ArrayToXml::getXmlString'test', $nest); // Will return string
    OR 
    ArrayToXml::getXmlString('test', $nest, 'UTF-8') // Will return string
    
    // Will produce string instace with following content.
    // <?xml version="1.0" encoding="UTF-8"?>
    // <test>
    //     <type>test</type>
    //     <nest>
    //         <nest>
    //             <nest prop1="aloha" prop2="hello" prop3="ke cha">
    //                 <nest prop1="bazingaaa!!!!"/>
    //             </nest>
    //         </nest>
    //     </nest>
    // </test>