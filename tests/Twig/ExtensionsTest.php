<?php

namespace IdeasBucket\Common\Twig;

use Twig_Test_IntegrationTestCase;

class ExtensionsTest extends Twig_Test_IntegrationTestCase
{
    protected function setUp()
    {
        parent::setUp();

        if (!extension_loaded('intl')) {

            $this->markTestSkipped('The PHP-Intl extension is not available.');

        }
    }

    public function getExtensions()
    {
        return [new Extensions];
    }

    /**
     * @return string
     */
    protected function getFixturesDir()
    {
        return dirname(__FILE__). DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR . 'fixtures'. DIRECTORY_SEPARATOR;
    }
}
