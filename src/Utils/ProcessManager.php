<?php

/**
 * Copyright (c) nicholasnet
 */

namespace IdeasBucket\Common\Utils;

use Symfony\Component\Process\Process;

/**
 * Class ProcessManager
 *
 * @package IdeasBucket\Common\Utils
 */
class ProcessManager
{
    /**
     * @var int
     */
    private $concurrency = 20;

    /**
     * @var array
     */
    private $processes = [];

    /**
     * @var \ArrayIterator
     */
    private $inProcess;

    /**
     * @var Callable
     */
    private $onSuccess;

    /**
     * @var Callable
     */
    private $onError;

    /**
     * @var bool
     */
    private $ran = false;

    /**
     * ProcessManager constructor.
     *
     * @param mixed $processes
     * @param array $opts
     */
    public function __construct($processes, array $opts)
    {
        $this->processes = $this->getIterator($processes);
        $this->onSuccess = (isset($opts['success']) && is_callable($opts['success'])) ? $opts['success'] : $this->getNoOp();
        $this->onError = (isset($opts['error']) && is_callable($opts['error'])) ? $opts['error'] : $this->getNoOp();
    }

    /**
     * Returns an iterator for the given value.
     *
     * @param mixed $value
     *
     * @return \Iterator
     */
    private function getIterator($value)
    {
        if ($value instanceof \Iterator) {

            return $value;
        }

        if (is_array($value)) {

            return new \ArrayIterator($value);
        }

        return new \ArrayIterator([$value]);
    }

    /**
     * @return \Closure
     */
    private function getNoOp()
    {
        return function($response, $index) {};
    }

    /**
     * @param int $concurrency
     *
     * @return ProcessManager
     *
     * @throws \LogicException
     */
    public function setConcurrency($concurrency)
    {
        if ($this->ran === true) {

            throw new \LogicException('Cannot change concurrency after process run.');
        }

        if (filter_var($concurrency, FILTER_VALIDATE_INT) === false) {

            throw new \InvalidArgumentException('Concurrency value must be integer.');
        }

        $concurrency = (int) $concurrency;

        if ($concurrency <= 0) {

            throw new \InvalidArgumentException('Concurrency cannot be 0 or negative value.');
        }

        $this->concurrency = $concurrency;

        return $this;
    }

    /**
     * Executes the processes.
     */
    public function run()
    {
        $this->addPending();
        $this->ran = true;

        while (count($this->inProcess) !== 0) {

            $this->monitorPending();
        }

        // Clear the references for callbacks.
        $this->onSuccess = null;
        $this->onError = null;
    }

    /**
     * Adds the pending
     */
    private function addPending()
    {
        while (count($this->inProcess) < $this->concurrency) {

            $item = $this->getUnprocessed();

            if (empty($item)) {

                break;
            }

            $this->inProcess[$item['index']] = $item['process'];

            $item['process']->start();

            if (count($this->inProcess) >= $this->concurrency) {

                break;
            }
        }
    }

    /**
     * @return array|null
     */
    private function getUnprocessed()
    {
        if (!$this->processes || !$this->processes->valid()) {

            return null;
        }

        $currentProcess = $this->processes->current();

        if (($currentProcess instanceof Process) === false) {

            $this->processes->next();

            return $this->getUnprocessed();
        }

        $data = ['index' => $this->processes->key(), 'process' => $currentProcess];
        $this->processes->next();

        return $data;
    }

    /**
     * Monitors the pending process.
     */
    private function monitorPending()
    {
        foreach ($this->inProcess as $index => $process) {

            /** @var Process $process */
            if ($process->isTerminated()) {

                if ($process->isSuccessful()) {

                    call_user_func($this->onSuccess, $process->getOutput(), $index);

                } else {

                    call_user_func($this->onError, $process->getErrorOutput(), $index);
                }

                unset($this->inProcess[$index]);

                $this->addPending();
            }
        }
    }
}