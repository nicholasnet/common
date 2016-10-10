<?php

namespace IdeasBucket\Common\Utils;

class ArrayToXmlTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateXML()
    {
        $books = '1984 7';
        $test = ArrayToXml::createXML('test', $books);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?><test>1984 7</test>', $this->stripWhiteSpaceFromDOMDocument($test));

        $books = ['@value' => '1984 7'];
        $test = ArrayToXml::createXML('test', $books);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?><test>1984 7</test>', $this->stripWhiteSpaceFromDOMDocument($test));

    }

    public function testNestingXml()
    {
        $nest = ['type' => 'test', 'nest' => [
            'nest' => [
                'nest' => [
                    'nest' => [
                        'nest' => 'bazingaaa!!!!'
                    ]
                ]
            ]
        ]];

        $test = ArrayToXml::createXML('test', $nest);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <test>
                    <type>test</type>
                    <nest>
                        <nest>
                            <nest>
                                <nest>
                                    <nest>bazingaaa!!!!</nest>
                                </nest>
                            </nest>
                        </nest>
                    </nest>
                </test>';

        $this->assertEquals($this->stripWhiteSpaceFromString($xml), $this->stripWhiteSpaceFromDOMDocument($test));
    }

    public function testEncoding()
    {
        $nest = ['type' => 'test'];

        $test = ArrayToXml::createXML('test', $nest, '1.0', 'ISO-8859-1');
        $xml = '<?xml version="1.0" encoding="ISO-8859-1"?><test><type>test</type></test>';

        $this->assertEquals($xml, $this->stripWhiteSpaceFromDOMDocument($test));
    }

    public function testPropertyWithNesting()
    {
        $nest = ['type' => 'test', 'nest' => [
            'nest' => [
                'nest' => [
                    '@attributes' => [
                        'prop1' => 'aloha',
                        'prop2' => 'hello',
                        'prop3' => 'ke cha'
                    ],
                    'nest' => [
                        '@attributes' => [
                            'prop1' => 'bazingaaa!!!!'
                        ]
                    ]
                ]
            ]
        ]];

        $test = ArrayToXml::createXML('test', $nest);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <test>
                    <type>test</type>
                    <nest>
                        <nest>
                            <nest prop1="aloha" prop2="hello" prop3="ke cha">
                                <nest prop1="bazingaaa!!!!"/>
                            </nest>
                        </nest>
                    </nest>
                </test>';

        $this->assertEquals($this->stripWhiteSpaceFromString($xml), $this->stripWhiteSpaceFromDOMDocument($test));
    }

    public function namespaceSchemaTest()
    {
        $restaurant = array();
        $restaurant['@attributes'] = array(
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'http://www.example.com/schmema.xsd',
            'lastUpdated' => date('c')  // dynamic values
        );

        $restaurant['masterChef'] = array(  //empty node with attributes
            '@attributes' => array(
                'name' => 'Mr. Big C.'
            )
        );


        $restaurant['menu'] = array();
        $restaurant['menu']['@attributes'] = array(
            'key' => 'english_menu',
            'language' => 'en_US',
            'defaultCurrency' => 'USD'
        );


        // we have multiple image tags (without value)
        $restaurant['menu']['assets']['image'][] = array(
            '@attributes' => array(
                'info' => 'Logo',
                'height' => '100',
                'width' => '100',
                'url' => 'http://www.example.com/res/logo.png'
            )
        );
        $restaurant['menu']['assets']['image'][] = array(
            '@attributes' => array(
                'info' => 'HiRes Logo',
                'height' => '300',
                'width' => '300',
                'url' => 'http://www.example.com/res/hires_logo.png'
            )
        );

        $restaurant['menu']['item'] = array();
        $restaurant['menu']['item'][] = array(
            '@attributes' => array(
                'lastUpdated' => '2011-06-09T08:30:18-05:00',
                'available' => true  // boolean values will be converted to 'true' and not 1
            ),
            'category' => array('bread', 'chicken', 'non-veg'),	 // we have multiple category tags with text nodes
            'keyword' => array('burger', 'chicken'),
            'assets' => array(
                'title' => 'Zinger Burger',
                'desc' => array('@cdata'=>'The Burger we all love >_< !'),
                'image' => array(
                    '@attributes' => array(
                        'height' => '100',
                        'width' => '100',
                        'url' => 'http://www.example.com/res/zinger.png',
                        'info' => 'Zinger Burger'
                    )
                )
            ),
            'price' => array(
                array(
                    '@value' => 10,  // will create textnode <price currency="USD">10</price>
                    '@attributes' => array(
                        'currency' => 'USD'
                    )
                ),
                array(
                    '@value' => 450,  // will create textnode <price currency="INR">450</price>
                    '@attributes' => array(
                        'currency' => 'INR'
                    )
                )
            ),
            'trivia' => null  // will create empty node <trivia/>
        );
        $restaurant['menu']['item'][] = array(
            '@attributes' => array(
                'lastUpdated' => '2011-06-09T08:30:18-05:00',
                'available' => true  // boolean values will be preserved
            ),
            'category' => array('salad', 'veg'),
            'keyword' => array('greek', 'salad'),
            'assets' => array(
                'title' => 'Greek Salad',
                'desc' => array('@cdata'=>'Chef\'s Favorites'),
                'image' => array(
                    '@attributes' => array(
                        'height' => '100',
                        'width' => '100',
                        'url' => 'http://www.example.com/res/greek.png',
                        'info' => 'Greek Salad'
                    )
                )
            ),
            'price' => array(
                array(
                    '@value' => 20,  // will create textnode <price currency="USD">20</price>
                    '@attributes' => array(
                        'currency' => 'USD'
                    )
                ),
                array(
                    '@value' => 900,  // will create textnode <price currency="INR">900</price>
                    '@attributes' => array(
                        'currency' => 'INR'
                    )
                )
            ),
            'trivia' => 'Loved by the Greek!'
        );

        $xml = $this->stripWhiteSpaceFromDOMDocument(ArrayToXml::createXML('restaurant', $restaurant));

        $expectedResult = '<?xml version="1.0" encoding="UTF-8"?>
                            <restaurant xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.example.com/schmema.xsd" lastUpdated="2011-09-18T10:52:56+00:00">
                              <masterChef name="Mr. Big C."/>
                              <menu key="english_menu" language="en_US" defaultCurrency="USD">
                                <assets>
                                  <image info="Logo" height="100" width="100" url="http://www.example.com/res/logo.png"/>
                                  <image info="HiRes Logo" height="300" width="300" url="http://www.example.com/res/hires_logo.png"/>
                                </assets>
                                <item lastUpdated="2011-06-09T08:30:18-05:00" available="true">
                                  <category>bread</category>
                                  <category>chicken</category>
                                  <category>non-veg</category>
                                  <keyword>burger</keyword>
                                  <keyword>chicken</keyword>
                                  <assets>
                                    <title>Zinger Burger</title>
                                    <desc><![CDATA[The Burger we all love >_< !]]></desc>
                                    <image height="100" width="100" url="http://www.example.com/res/zinger.png" info="Zinger Burger"/>
                                  </assets>
                                  <price currency="USD">10</price>
                                  <price currency="INR">450</price>
                                  <trivia></trivia>
                                </item>
                                <item lastUpdated="2011-06-09T08:30:18-05:00" available="true">
                                  <category>salad</category>
                                  <category>veg</category>
                                  <keyword>greek</keyword>
                                  <keyword>salad</keyword>
                                  <assets>
                                    <title>Greek Salad</title>
                                    <desc><![CDATA[Chef\'s Favorites]]></desc>
                                    <image height="100" width="100" url="http://www.example.com/res/greek.png" info="Greek Salad"/>
                                  </assets>
                                  <price currency="USD">20</price>
                                  <price currency="INR">900</price>
                                  <trivia>Loved by the Greek!</trivia>
                                </item>
                              </menu>
                            </restaurant>';

        $this->assertEquals($this->stripWhiteSpaceFromString($expectedResult), $xml);
    }

    public function testPropertyAndValueWithNesting()
    {
        $books = array(
            '@attributes' => array(
                'type' => 'fiction'
            ),
            'book' => array(
                array(
                    '@attributes' => array(
                        'author' => 'George Orwell'
                    ),
                    'title' => '1984'
                ),
                array(
                    '@attributes' => array(
                        'author' => 'Isaac Asimov'
                    ),
                    'title' => array('@cdata'=>'Foundation'),
                    'price' => '$15.61'
                ),
                array(
                    '@attributes' => array(
                        'author' => 'Robert A Heinlein'
                    ),
                    'title' =>  array('@cdata'=>'Stranger in a Strange Land'),
                    'price' => array(
                        '@attributes' => array(
                            'discount' => '10%'
                        ),
                        '@value' => '$18.00'
                    )
                )
            )
        );

        $test = ArrayToXml::getXmlString('books', $books);

        $expectedResult = '<?xml version="1.0" encoding="UTF-8"?>
                           <books type="fiction">
                              <book author="George Orwell">
                                <title>1984</title>
                              </book>
                              <book author="Isaac Asimov">
                                <title><![CDATA[Foundation]]></title>
                                <price>$15.61</price>
                              </book>
                              <book author="Robert A Heinlein">
                                <title><![CDATA[Stranger in a Strange Land]]></title>
                                <price discount="10%">$18.00</price>
                              </book>
                           </books>';


        $this->assertEquals($this->stripWhiteSpaceFromString($expectedResult), $this->stripWhiteSpaceFromString($test));

    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidTagName()
    {
        $name = 'CaF$%6e=Ã‰';

        $nest = ['type' => 'test', 'nest' => [
            'nest' => [
                $name => [
                    'nest' => [
                        $name => 'bazingaaa!!!!'
                    ]
                ]
            ]
        ]];

        ArrayToXml::createXML('test', $nest);
    }


    private function stripWhiteSpaceFromDOMDocument(\DOMDocument $xml)
    {
        return trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), '', $xml->saveXML()));
    }

    private function stripWhiteSpaceFromString($xml)
    {
        return trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), '', $xml));
    }
}

