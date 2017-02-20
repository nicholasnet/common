<?php

/**
 * Copyright (c) nicholasnet
 */

namespace IdeasBucket\Common\Utils;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ProcessManagerTest extends TestCase
{
    private function getSuccessProcess()
    {
        $stub = $this->getMockBuilder(Process::class)
                     ->disableOriginalConstructor()
                     ->disableOriginalClone()
                     ->disableArgumentCloning()
                     ->disallowMockingUnknownTypes()
                     ->setMethods(['isTerminated', 'isSuccessful'])
                     ->getMock();

        $stub->method('isTerminated')->will($this->onConsecutiveCalls(false, false, false, false, true));
        $stub->method('isSuccessful')->willReturn(true);

        $stub->expects($this->once())->method('isSuccessful');
        $stub->expects($this->exactly(5))->method('isTerminated');

        return $stub;
    }

    private function getErrorProcess()
    {
        $stub = $this->getMockBuilder(Process::class)
                     ->disableOriginalConstructor()
                     ->disableOriginalClone()
                     ->disableArgumentCloning()
                     ->disallowMockingUnknownTypes()
                     ->setMethods(['isTerminated', 'isSuccessful'])
                     ->getMock();

        $stub->method('isTerminated')->will($this->onConsecutiveCalls(false, false, false, false, false, true));
        $stub->method('isSuccessful')->willReturn(false);

        $stub->expects($this->once())->method('isSuccessful');
        $stub->expects($this->exactly(6))->method('isTerminated');

        return $stub;
    }

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
        $process1 = $this->getSuccessProcess();

        (new ProcessManager([$process1]))->setConcurrency(2)->run();
    }

    public function testProcessManagerAcceptsProcessByItself()
    {
        $process1 = $this->getErrorProcess();

        (new ProcessManager($process1))->setConcurrency(2)->run();
    }

    public function testProcessManagerAcceptsGenerators()
    {
        $requests = function ($total) {

            for ($i = 0; $i < $total; $i++) {

                if ($i === 8) {

                    yield new \stdClass();

                } else {

                    yield $this->getSuccessProcess();
                }
            }
        };

        (new ProcessManager($requests(20)))->setConcurrency(10)->run();
    }
}
