<?php

namespace IdeasBucket\Common\Utils;

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'test.php');

class MacroableTraitTest extends \PHPUnit_Framework_TestCase
{
    private $macroable;

    public function setUp()
    {
        $this->macroable = $this->createObjectForTrait();
    }

    private function createObjectForTrait()
    {
        return $this->getObjectForTrait(MacroableTrait::class);
    }

    public function testRegisterMacro()
    {
        $macroable = $this->macroable;
        $macroable::macro(__CLASS__, function () {
            return 'Taylor';
        });

        $this->assertEquals('Taylor', $macroable::{__CLASS__}());

        $macroable = $this->macroable;
        $macroable::macro('testStatic', 'TEST_TEST_TEST');

        $this->assertEquals('test', $macroable::testStatic());
    }

    public function testRegisterMacroAndCallWithoutStatic()
    {
        $macroable = $this->macroable;
        $macroable::macro(__CLASS__, function () {
            return 'Taylor';
        });
        $this->assertEquals('Taylor', $macroable->{__CLASS__}());

        $macroable::macro('test', 'TEST_TEST_TEST');

        $this->assertEquals('test', $macroable::test());
    }

    public function testWhenCallingMacroClosureIsBoundToObject()
    {
        TestMacroable::macro('tryInstance', function () {
            return $this->protectedVariable;
        });

        TestMacroable::macro('tryStatic', function () {
            return static::getProtectedStatic();
        });

        $instance = new TestMacroable();

        $result = $instance->tryInstance();
        $this->assertEquals('instance', $result);

        $result = TestMacroable::tryStatic();
        $this->assertEquals('static', $result);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method noop does not exist.
     */
    public function testExceptionWhenCallingMethodThatDoesNotExist()
    {
        (new TestMacroable())->noop();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method noop does not exist.
     */
    public function testExceptionWhenCallingMethodStaticallyThatDoesNotExist()
    {
        TestMacroable::noop();
    }
}

class TestMacroable
{
    use MacroableTrait;

    protected $protectedVariable = 'instance';

    protected static function getProtectedStatic()
    {
        return 'static';
    }
}
