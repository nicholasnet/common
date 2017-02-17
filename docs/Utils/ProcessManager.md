# ProcessManager
Back to [index](../index.md)

- [Introduction](#introduction)
- [Usage](#usage)
- [Available Methods](#available-methods)

<a name="introduction"></a>
## Introduction
##### This class is inspired by EachPromise from Guzzle Promises library. You can find more information about it [here](https://github.com/guzzle/promises).

ProcessManager allows user to run several async Symfony Processes at once.

**Note:** You will need to install Symfony Process for this to work.
    
    composer require symfony/process
        
<a name="Usage"></a>
## Usage
    use IdeasBucket\Common\Utils\ProcessManager;
    
    $requests = function ($total) {
    
        for ($i = 0; $i < $total; $i++) {
            
            yield $i => new Process('echo Test');
        }
    };
    
    (new ProcessManager($requests(100), [
    
        'error' => function ($response, $index) {
        
            // Callback that will get executed in each process failure.
        },
        'success' => function ($response, $index) {
        
            // Callback that will get executed in each process success.
        }
    
    ]))->run();
    
    // OR
    
    $processes = [];
    
    for ($i = 0; $i < 100; $i++) {
    
        $process[] = new Process('ls -lsa');
    }
    
    (new ProcessManager($processes, [
    
        'error' => function ($response, $index) {},
        'success' => function ($response, $index) {}
    
    ]))->setConcurrency(10)->run();

<a name="AvailableMethods"></a>
## Available Methods
* [run](#run)
* [setConcurrency](#concurrency)

<a name="run"></a>
#### `run()`
The `run` method executes the processes that are given to the process manager.     

<a name="setConcurency"></a>
#### `setConcurrency()`
The `setConcurrency` method allows user to set concurrency that basically tells ProcessManager how many Process to run simultaneously in one go. Default is `20`

__Remember you cannot change concurrency after invoking `run` method.__     