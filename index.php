<?php

	try {
		ini_set("error_log", "/tmp/deploy.log");

		error_log("REQUEST INCOMING");
		ob_start();

		$config = parse_ini_file("config.ini", true);
		include("AWSSDKforPHP/sdk.class.php");

		$queue = $config['SQS']['queue'];
		$queue_name = $config['SQS']['queue_name'];

		$sqs = new AmazonSQS(
			array(
				'key' => $config['S3']['key'],
				'secret' => $config['S3']['secret']
			)
		);

		$response = $sqs->list_queues();

		$queue_exists = false;
		foreach ($response->body->ListQueuesResult->QueueUrl as $existing_queue) {
			if ($existing_queue == $queue) {
				$queue_exists = true;
			}
		}

		if (!$queue_exists) {
			error_log("QUEUE {$queue_name} DOES NOT EXIST... CREATING");
			$response = $sqs->create_queue(
				$queue_name, 
				array(
					"VisibilityTimeout" => 180
				)
			);

			if (!$response->isOK()) {
				error_log("Queue {$queue_name} doesn't exist and couldn't be created");
				die;
			}
		}

		$postdata = $_POST['payload'];

		$json = json_decode($postdata);
		$repo = $json->repository->name;

		error_log("Code Changes for ${repo}");
		$files_modified = array();
		foreach ($json->head_commit->added as $item) {
			$files_modified[] = $item;
		}

		foreach ($json->head_commit->modified as $item) {
			$files_modified[] = $item;
		}

		$message = (object) array(
			"repo" => $repo,
			"changed_files" => $files_modified
		);

		$encoded = json_encode($message);
		error_log($encoded);

		$sqs->send_message($queue, $encoded);

	} catch (Exception $e) {
		error_log($e->getMessage());
		header("HTTP 1.0 500 Serve Error");
		echo $e->getMessage();
	}
?>
