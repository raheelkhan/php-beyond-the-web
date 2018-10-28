<?php

/**
 *
 * Demonstrate how to start a php daemon by forking out child process
 * The idea is to fork the php script and exit from the parent.
 * Then in the child script become the "Session Leader".
 * Then fork a new process from child script e.g grandchild and exit the child script.
 *
 * Our daemon should not write to php standard streams, therefore we need to close those streams
 * STDIN, STDOUT and STDERROR
 *
 * This script also calls the system notification api through shell and write
 * a mother fucking notification every 2 second. Goodluck killing the daemon
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

$message = "A mother fucking notification";
if (php_uname('s') === 'Darwin') {
    $command = "terminal-notifier -sound default -message '$message'";
}
if (php_uname('s') === 'Linux') {
    $command = "notify-send '$message'";
}


while ($stayInLoop) {
    `$command`;
    sleep(2);
}
