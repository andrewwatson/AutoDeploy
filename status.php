<?php

	try {
		ini_set("error_log", "/tmp/deploy.log");

		ob_start();

		$config = parse_ini_file("config.ini", true);
		$queue = $config['SQS']['queue'];
		$queue_name = $config['SQS']['queue_name'];
		$bucket = $config['S3']['bucket'];

		include("AWSSDKforPHP/sdk.class.php");

		$sqs = new AmazonSQS(
			array(
				'key' => $config['S3']['key'],
				'secret' => $config['S3']['secret']
			)
		);

		print_r($sqs->list_queues());

	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
