<?php

namespace IdeasBucket\Common\Utils;

/**
 * Class XmlToArray.
 */
class XmlToArray
{
    /**
     * The main document.
     *
     * @var \DOMDocument
     */
    private $xml = null;

    /**
     * The default encoding that we want to use.
     *
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * This method initializes the root XML node.
     *
     * @param string $version      The XML version.
     * @param string $encoding     The encoding to use.
     * @param bool   $formatOutput Flag that indicates whether output needs to formatted or not.
     */
    private function __construct($version = '1.0', $encoding = 'UTF-8', $formatOutput = true)
    {
        $this->xml = new \DOMDocument($version, $encoding);
        $this->xml->formatOutput = $formatOutput;
        $this->encoding = $encoding;
    }

    /**
     * This method converts an XML to and Array.
     *
     * @param           $inputXml
     * @param string    $version
     * @param string    $encoding
     * @param bool|true $formatOutput
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function createArray($inputXml, $version = '1.0', $encoding = 'UTF-8', $formatOutput = true)
    {
        $class = new self($version, $encoding, $formatOutput);
        $xml = $class->xml;

        if (is_string($inputXml)) {

            $parsed = $xml->loadXML($inputXml);

            if (! $parsed) {

                throw new \Exception('[XML2Array] Error parsing the XML string.');

            }

        } else {

            if (get_class($inputXml) !== 'DOMDocument') {

                throw new \Exception('[XML2Array] The input XML object should be of type: DOMDocument.');

            }

            $xml = $class->xml = $inputXml;
        }

        $array = [];
        $array[$xml->documentElement->tagName] = $class->convert($xml->documentElement);

        return $array;
    }

    /**
     * Convert an Array to XML.
     *
     * @param mixed $node - XML as a string or as an object of DOMDocument
     *
     * @return mixed
     */
    private function convert($node)
    {
        $output = [];

        switch ($node->nodeType) {

            case XML_CDATA_SECTION_NODE:
                $output['@cdata'] = trim($node->textContent);
                break;

            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;

            case XML_ELEMENT_NODE:
                // for each child node, call the covert function recursively
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->convert($child);

                    if (isset($child->tagName)) {

                        $t = $child->tagName;

                        // assume more nodes of same kind are coming
                        if (! isset($output[$t])) {

                            $output[$t] = [];

                        }

                        $output[$t][] = $v;

                    } else {

                        //check if it is not an empty text node
                        if ($v !== '') {

                            $output = $v;

                        }
                    }
                }

                if (is_array($output)) {


                    // if only one node of its kind, assign it directly instead if array($value);
                    foreach ($output as $t => $v) {

                        if (is_array($v) && count($v) == 1) {

                            $output[$t] = $v[0];

                        }
                    }

                    if (empty($output)) {

                        //for empty nodes
                        $output = '';
                    }
                }

                // loop through the attributes and collect them
                if ($node->attributes->length) {

                    $a = [];

                    foreach ($node->attributes as $attrName => $attrNode) {

                        $a[$attrName] = (string) $attrNode->value;

                    }

                    // If its an leaf node, store the value in @value instead of directly storing it.
                    if (! is_array($output)) {

                        $output = ['@value' => $output];

                    }

                    $output['@attributes'] = $a;
                }

                break;
        }

        return $output;
    }
}
