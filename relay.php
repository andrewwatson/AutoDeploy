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

		$s3 = new AmazonS3( 
			array(
				'key' => $config['S3']['key'],
				'secret' => $config['S3']['secret']
			)
		);

		if (!$s3->if_bucket_exists( $bucket)) {
			throw new Exception("BAD BUCKET {$bucket}");
		}

		$resp = $sqs->receive_message($config['SQS']['queue']);

		if (!empty($resp->body->ReceiveMessageResult->Message)) {
			$handle = $resp->body->ReceiveMessageResult->Message->ReceiptHandle;
			$body = $resp->body->ReceiveMessageResult->Message->Body;
			$message = json_decode($body);

			chdir($message->repo);
			system("git pull origin master");

			foreach ($message->changed_files as $filename) {
				echo "Uploading {$filename}\n";
         	$s3->batch()->create_object($bucket, $filename, array( 'fileUpload' => $filename));

			}

			$s3->batch()->send();
			
			// remove the message from the queue
			$d_resp = $sqs->delete_message($config['SQS']['queue'], $handle);
			if (!$d_resp->isOK()) {
				var_dump($d_resp);
			}
		}


	} catch (Exception $e) {
		error_log($e->getMessage());
		echo $e->getMessage();

	}
