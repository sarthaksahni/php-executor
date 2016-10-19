<?php
require_once('./vendor/autoload.php');
date_default_timezone_set("Asia/Kolkata");
use Aws\Sqs\SqsClient;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

class Executor {

  public static $sqs_queue_url;
  public static $sqs_version;
  public static $sqs_profile;
  public static $sqs_region;
  public static $sqs_client;

  public static function getSqsClient() {
      if(!is_null(self::$sqs_client))
        return self::$sqs_client;

      self::$sqs_queue_url = getenv("SQS_QUEUE_URL");
      self::$sqs_version = getenv("SQS_VERSION");
      self::$sqs_profile = getenv("SQS_PROFILE");
      self::$sqs_region = getenv("SQS_REGION");

      self::$sqs_client = SqsClient::factory(array(
          'version' =>  self::$sqs_version,
          'profile' => self::$sqs_profile,
          'region'  => self::$sqs_region
      ));

      return self::$sqs_client;
  }

  public static function call($class, $method, $args = [], $queue_url = "") {
      if(strlen(trim($queue_url)) == 0) {
        $queue_url = getenv("SQS_QUEUE_URL");
      }
      if(!is_array($args)) {
        $args = [];
      }
      self::getSqsClient()->sendMessage(array(
          'QueueUrl'    => $queue_url,
          'MessageBody' => self::buildSQSMessage($class, $method, $args),
      ));
  }

  public static function buildSQSMessage($class, $method, $args) {
      return json_encode([
        'class'   =>  $class,
        'method'  =>  $method,
        'args'    =>  $args
      ]);
  }
}

?>
