<?php

/**
 * Not working on this since inotify pecl extension is not
 * available on Mac OS
 *
 * @package php-beyond-the-web
 * @author Raheel <raheelwp@gmail.com>
 */

if (!function_exists('pcntl_fork')) {
    die('PCNTL functions not available on this PHP installation');
}

$pid = pcntl_fork();

if ($pid == -1) {
    exit("Could not fork the child process");
}

if ($pid) {
    exit(0);
}

if (posix_setsid() == -1) {
    exit("Could not become session leader");
}

if ($pid == -1) {
    exit("Could not fork the child process into grand child");
}

if ($pid) {
    exit(0);
}

if (!fclose(STDIN)) {
    exit("Could not close STDIN");
}

if (!fclose(STDOUT)) {
    exit("Could not close STDOUT");
}

if (!fclose(STDERR)) {
    exit("Could not close STDERR");
}

$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen('/dev/null', 'w');
$STDERR = fopen('/var/log/our_error.log', 'wb');

$stayInLoop = true;
