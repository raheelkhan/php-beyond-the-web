<?php

/**
 *
 * This is a message queue system built on php without any third party software
 * This particular file will read data from memory and process the data.
 * @author Raheel <raheelwp@gmail.com>
 * @package php-beyond-the-web
 */

 $key = 519051;
 $queue = msg_get_queue($key, 0444);

while (true) {
    msg_receive($queue, 1, $msgType, 1024, $message);
    echo "$message \n";
}
