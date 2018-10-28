<?php

/**
 *
 * This is a message queue system built on php without any third party software
 * This particular file will write data into memory and the display.php file will read from
 * the memory and process the data.
 * @author Raheel <raheelwp@gmail.com>
 * @package php-beyond-the-web
 */

 $key = 519051;
 $queue = msg_get_queue($key, 0666);

for ($i=0; $i<=20; $i++) {
    $message = "I am message number $i";
    msg_send($queue, 1, $message, true, true);
    sleep(1);
}
