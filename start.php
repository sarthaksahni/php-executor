<?php
require_once('./vendor/autoload.php');
date_default_timezone_set('Asia/Kolkata');
global $verbosity_set;

$verbosity_set = isset(getopt("v:")['v']) ? getopt("v:")['v'] : 0;

function verbose($msg, $verbosity_level) {
  global $verbosity_set;
  if ($verbosity_set == 0)
    return ;
  if($verbosity_level <= $verbosity_set) {
    switch($verbosity_level) {
      case 1: $msg_type = "Error";break;
      case 2: $msg_type = "Warning";break;
      case 3: $msg_type = "Notice";break;
      case 4: $msg_type = "Info";break;
    }
    echo date("Y-m-d H:i:s").": (".$msg_type.") ".$msg."\r\n";
    return;
  }
}

if(!file_exists(__DIR__.DIRECTORY_SEPARATOR.".env")) {
  verbose("ENV file missing. Please create '.env' file at base directory ".__DIR__, 1);
}
verbose("ENV loaded :".__DIR__.".env", 3);
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$worker_file = getenv("WORKER_FILE") ? getenv("WORKER_FILE") : "worker";
$thread_count = getenv("PROC_COUNT") ? getenv("PROC_COUNT") : 3;
$i = 0;
verbose("Starting ".$thread_count." threads..", 4);
for ($i = 1; $i <= $thread_count; ++$i) {
    $pid = pcntl_fork();
    if (!$pid) {
        pcntl_exec("./".$worker_file, ["-t=".$i, "-v=".$verbosity_set]);
        exit($i);
    }
}

while (pcntl_waitpid(0, $status) != -1) {
    $status = pcntl_wexitstatus($status);
    verbose("Worker just ended. Restarting another worker.", 2);
    sleep(1);
    $pid = pcntl_fork();
    if (!$pid) {
        $i++;
        pcntl_exec("./".$worker_file, ["-t=".$i, "-v=".$verbosity_set]);
        exit($i);
    }
}

?>
