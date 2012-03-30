<?php

	try {

		$config = parse_ini_file("config.ini", true);
		include("sdk-1.5.3/sdk.class.php");

		$bucket = $_GET['bucket'];

		$s3 = new AmazonS3( 
			array(
				'key' => $config['S3']['key'],
				'secret' => $config['S3']['secret']
			)
		);

		if (!$s3->if_bucket_exists( $bucket)) {
			throw new Exception("BAD BUCKET {$bucket)");
		}

		$postdata = file_get_contents("php://input");
		mail("andrewmoorewatson@gmail.com", "subject", $postdata);

		echo "let's go...";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
