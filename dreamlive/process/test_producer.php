<?php
require 'ProcessClient.php';

$num = $GLOBALS['argv'][1];
echo $num;
try {
    $product = "test";
    $job = "test_job1";
    $job2 = "test_job2";
    
    $process = new ProcessClient($product);
    while (true) {
        for ($i = 0; $i < $num; $i ++) {
            $process->addTask(
                $job, array(
                "time" => time() . '-' . rand(0, 9999)
                )
            );
            $process->addTask(
                $job2, array(
                "time" => time() . '-' . rand(0, 9999)
                )
            );
        }
        sleep(10);
    }
} catch (Exception $e) {
    
    echo $e->getMessage();
}

