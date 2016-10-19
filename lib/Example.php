<?php

class Example {
  public static function test($arguments) {
    verbose("SAMPLE PROCESSING SLEEPING FOR RANDOM 1 - 10 Secs", 1);
    sleep(rand(1,10));
    verbose("DONE SLEEPING OFF NOW!", 1);
    return true;
  }
}

?>
