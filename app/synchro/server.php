<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);
require 'synchro.class.php';

$synchro = new Synchro();

header("Content-Type: text/event-stream\n\n");
header('Cache-Control: no-cache');

/**/
while (1) {

    // if ((time() - $startedAt) > 10) { die(); }
    //$curDate = date(DATE_ISO8601);

    $synchro->shmAttach();

    if ($synchro->shmHasVar()) {

        $stack = $synchro->getStack();

        foreach ($stack as $k => $value) {
            if (time() - $value["time"] > 2) {
                $synchro->pop($k);
            }
        }
        if (!empty($stack)) {
            echo "event: msg" . PHP_EOL;
            echo 'data: {"data": ' . json_encode($stack) . '}' . PHP_EOL;
            echo "\n";
            ob_flush();
            flush();
        }
    }

    $synchro->shmDetach();

    sleep(1);
}



