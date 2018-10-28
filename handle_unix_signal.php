<?php

/**
 * This script shows how we can handle posix signals within our
 * PHP script by using pcntl_signal.
 *
 * @package php-beyond-the-web
 * @author Raheel <raheelwp@gmail.com>
 */

declare(ticks=1);

$pid = getmypid();

echo "You can kill me with process id $pid\n";

$keepRunning = true;

$signalHandler = function ($signal) use (&$keepRunning) {
    switch ($signal) {
        case SIGINT:
            echo "Sorry, I am not going to obey you.\n";
            break;
        case SIGTERM:
            echo "Well if you insist, thats okay bye\n";
            $keepRunning = false;
            break;
    }
};

pcntl_signal(SIGINT, $signalHandler);
pcntl_signal(SIGTERM, $signalHandler);

while ($keepRunning) {
    sleep(1);
}

echo "Thats it, I am going to sleep";
