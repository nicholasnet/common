<?php

namespace IdeasBucket\Common\Utils;

class XmlToArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateArray()
    {
        $xml = '<a version="1.0"><b><c><d id="id:pass"/><e name="test" age="24" /><f name="test">ererf</f></c></b></a>';

        $result = '{"a":{"b":{"c":{"d":{"@value":"","@attributes":{"id":"id:pass"}},"e":{"@value":"","@attributes":{"name":"test","age":"24"}},"f":{"@value":"ererf","@attributes":{"name":"test"}}}},"@attributes":{"version":"1.0"}}}';

        // Since comparing array is cumbersome especially if it is multi layer so convert to json string.
        $this->assertEquals($result, json_encode(XmlToArray::createArray($xml)));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage [XML2Array] The input XML object should be of type: DOMDocument.
     */
    public function testCreateArrayInvalidInputException()
    {
        XmlToArray::createArray(new \stdClass());
    }
}
