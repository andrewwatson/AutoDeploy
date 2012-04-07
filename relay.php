<?php

	try {
		ini_set("error_log", "/tmp/deploy.log");

		ob_start();

		$config = parse_ini_file("config.ini", true);
		$queue = $config['SQS']['queue'];
		$queue_name = $config['SQS']['queue_name'];
		$bucket = $config['S3']['bucket'];

		include("AWSSDKforPHP/sdk.class.php");


		$s3 = new AmazonS3( 
			array(
				'key' => $config['S3']['key'],
				'secret' => $config['S3']['secret']
			)
		);

		if (!$s3->if_bucket_exists( $bucket)) {
			throw new Exception("BAD BUCKET {$bucket}");
		}

	} catch (Exception $e) {

	}
