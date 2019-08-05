<?php
require 'ProcessClient.php';

try {
    $product = "test";
    $job1 = "test_job1";
    $job2 = "test_job2";
    $max_thread = 1;
    $max_task = 1000;
    
    $worker1 = array(
        "TestWorker",
        "execute1"
    );
    $worker2 = array(
        "TestWorker",
        "execute2"
    );
    
    $process = new ProcessClient($product);
    $process->addWorker($job1, $worker1, $max_thread, $max_task, - 1);
    $process->addWorker($job2, $worker2, $max_thread, $max_task, - 1);
    
    $process->run();
} catch (Exception $e) {
}

class TestWorker
{

    function execute1($v)
    {
        echo ($v["traceid"]) . "\n";
        sleep(1);
        // require 'Queue.php';
        ProcessClient::addLog(
            array(
            "a" => 1
            )
        );
        ProcessClient::addLog(
            array(
            "a" => 2
            )
        );
        ProcessClient::addLog(
            array(
            "b" => 2
            )
        );
        ProcessClient::addLog(
            array(
            "c" => array(
                2,
                3
            )
            )
        );
        ProcessClient::addLog("sdsdfsdf");
        if (rand(1, 50) == 1) {
            throw new Exception('aaaaaaaaaaaaaaaa');
        }
        
        echo 1;
        return true;
    }

    function execute2($v)
    {
        echo ($v["traceid"]) . "\n";
        sleep(1);
        if (rand(1, 50) == 1) {
            Mogo::test();
        }
        // sleep(10);
        // require 'Queue.php';
        // mysql_connect();
        ProcessClient::addLog(
            array(
            "a" => 1
            )
        );
        ProcessClient::addLog(
            array(
            "a" => 2
            )
        );
        ProcessClient::addLog(
            array(
            "b" => 2
            )
        );
        ProcessClient::addLog(
            array(
            "c" => array(
                2,
                3
            )
            )
        );
        ProcessClient::addLog("sdsdfsdf");
        
        echo 2;
        return true;
    }
}
