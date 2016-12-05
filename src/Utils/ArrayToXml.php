<?php

namespace IdeasBucket\Common\Utils;

/**
 * Class ArrayToXml.
 */
class ArrayToXml
{
    /**
     * The DOMDocument that will represent the array structure.
     *
     * @var \DOMDocument
     */
    private $xml;

    /**
     * The encoding that we will use.
     *
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * This method initializes the root XML node.
     *
     * @param string $version      XML version that we want to use.
     * @param string $encoding     Encoding that we want to use.
     * @param bool   $formatOutput Boolean to indicate whether we want to format the output or not.
     */
    private function __construct($version = '1.0', $encoding = 'UTF-8', $formatOutput = true)
    {
        $this->xml = new \DomDocument($version, $encoding);
        $this->xml->formatOutput = $formatOutput;
        $this->encoding = $encoding;
    }

    /**
     * This method converts an array to XML.
     *
     * @param string $nodeName     Name of the root node to be converted.
     * @param array  $arr          Array to be converted.
     * @param string $version
     * @param string $encoding
     * @param bool   $formatOutput
     *
     * @throws \Exception
     *
     * @return \DOMDocument
     */
    public static function createXml($nodeName, $arr = [], $version = '1.0', $encoding = 'UTF-8', $formatOutput = true)
    {
        $class = new self($version, $encoding, $formatOutput);
        $class->xml->appendChild($class->convert($nodeName, $arr));

        return $class->xml;
    }

    /**
     * This method returns the string representation of DOMDocument.
     *
     * @param string    $nodeName     Name of the root node to be converted.
     * @param array     $arr          Array to be converted.
     * @param string    $version
     * @param string    $encoding
     * @param bool|true $formatOutput
     *
     * @return string
     */
    public static function getXmlString($nodeName, $arr = [], $version = '1.0', $encoding = 'UTF-8', $formatOutput = true)
    {
        return self::createXml($nodeName, $arr, $version, $encoding, $formatOutput)->saveXML();
    }

    /**
     * This method converts an array into XML recursively.
     *
     * @param string $nodeName Name of the node.
     * @param array  $arr      Data that we want to convert.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function &convert($nodeName, $arr = [])
    {
        /** @var \DOMDocument $xml */
        $xml = $this->xml;

        /** @var \DOMElement $node */
        $node = $xml->createElement($nodeName);

        if (is_array($arr)) {

            // get the attributes first.;
            if (isset($arr['@attributes'])) {

                $this->extractAttributes($node, $arr, $nodeName);

                unset($arr['@attributes']); //remove the key from the array once done.
            }

            // Check if it has a value stored in @value, if yes store the value and return
            // else check if its directly stored as string.
            if (isset($arr['@value'])) {

                $node->appendChild($xml->createTextNode($this->boolToString($arr['@value'])));
                unset($arr['@value']);    //remove the key from the array once done.

                //Return from recursion, as a note with value cannot have child nodes.
                return $node;

            } elseif (isset($arr['@cdata'])) {

                $node->appendChild($xml->createCDATASection($this->boolToString($arr['@cdata'])));
                unset($arr['@cdata']);    //remove the key from the array once done.

                //Return from recursion, as a note with cdata cannot have child nodes.
                return $node;
            }

            // recurse to get the node for that key
            foreach ($arr as $key => $value) {

                if (! $this->isValidTagName($key)) {

                    throw new \Exception(__CLASS__.' Illegal character in tag name. tag: '.$key.' in node: '.$nodeName);

                }

                if (is_array($value) && is_numeric(key($value))) {

                    // MORE THAN ONE NODE OF ITS KIND;
                    // if the new array is numeric index, means it is array of nodes of the same kind
                    // it should follow the parent key name
                    foreach ($value as $k => $v) {

                        $node->appendChild($this->convert($key, $v));

                    }

                } else {

                    // ONLY ONE NODE OF ITS KIND
                    $node->appendChild($this->convert($key, $value));

                }

                unset($arr[$key]); //remove the key from the array once done.
            }

        } else {

            $node->appendChild($xml->createTextNode($this->boolToString($arr)));

        }

        return $node;
    }

    /**
     * Get string representation of boolean value.
     *
     * @param bool $v Value to convert.
     *
     * @return string
     */
    private function boolToString($v)
    {
        //Convert boolean to text value.
        $v = $v === true ? 'true' : $v;
        $v = $v === false ? 'false' : $v;

        return $v;
    }

    /**
     * Check if the tag name or attribute name contains illegal characters.
     *
     * @see http://www.w3.org/TR/xml/#sec-common-syn
     *
     * @param string $tag Name of the tag that we want to validate.
     *
     * @return bool Flag that indicates whether tag was valid or not.
     */
    private function isValidTagName($tag)
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';

        return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
    }

    /**
     * @param \DOMElement $node
     * @param array       $data
     * @param string      $nodeName
     *
     * @throws \Exception
     */
    private function extractAttributes(\DOMElement $node, array $data, $nodeName)
    {
        foreach ($data['@attributes'] as $key => $value) {

            if (! $this->isValidTagName($key)) {

                throw new \Exception(__CLASS__.' Illegal character in attribute name. attribute: '.$key.' in node: '.$nodeName);

            }

            $node->setAttribute($key, $this->boolToString($value));
        }
    }
}
