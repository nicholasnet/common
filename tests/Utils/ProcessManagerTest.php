<?php

/**
 * Copyright (c) nicholasnet
 */

namespace IdeasBucket\Common\Utils;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ProcessManagerTest extends TestCase
{
    public function testThrowsLogicExceptionIfConcurrencyGetsChangedAfterRunProcess()
    {
        $this->expectException(\LogicException::class);

        $process = new ProcessManager([],[]);
        $process->run();
        $process->setConcurrency(2);
    }

    public function testThrowsTypeErrorIfInvalidArgumentIsGivenInSetConcurrencyMethod()
    {
        $this->expectException(\InvalidArgumentException::class);

        $process = new ProcessManager([],[]);
        $process->setConcurrency('sdf');
    }

    public function testThrowsInvalidArgumentIfSetConcurrencyIsGivenZeroOrLessThanZero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Concurrency cannot be 0 or negative value.');

        $process = new ProcessManager([],[]);
        $process->setConcurrency(0);
    }

    public function testProcessManagerAcceptsArrayOfProcess()
    {
        $process1 = new DummySuccessProcess('Hello World');

        (new ProcessManager([$process1], [

            'success' => function($response, $index) {

                $this->assertSame($response, 'Hello World');
            }

        ]))->setConcurrency(2)->run();
    }

    public function testProcessManagerAcceptsProcessByItself()
    {
        $process1 = new DummyErrorProcess('h');

        (new ProcessManager($process1, [

            'error' => function($response, $index) {

                $this->assertContains('h', $response);
            }

        ]))->setConcurrency(2)->run();
    }

    public function testProcessManagerAcceptsGenerators()
    {
        $requests = function ($total) {

            for ($i = 0; $i < $total; $i++) {

                if ($i === 8) {

                    yield new \stdClass();

                } else {

                    yield new DummySuccessProcess('test');
                }
            }
        };

        (new ProcessManager($requests(20), [

            'success' => function($response, $index) {

                $this->assertSame($response, 'test');
            }

        ]))->setConcurrency(10)->run();
    }
}

class DummySuccessProcess extends Process
{
    private $counter = 0;
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function isTerminated()
    {
        ++$this->counter;

        return ($this->counter === 5);
    }

    public function start(callable $callback = null)
    {

    }

    public function isSuccessful()
    {
        return true;
    }

    public function getOutput()
    {
        return $this->message;
    }
}

class DummyErrorProcess extends DummySuccessProcess
{
    public function isSuccessful()
    {
        return false;
    }

    public function getErrorOutput()
    {
        return $this->message;
    }
}
