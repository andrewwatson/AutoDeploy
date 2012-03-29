<?php

	try {

	$config = array(
			'S3' => array(
				'key' => getenv('AWS_ACCESS_KEY_ID'), 
				'secret' => getenv('AWS_SECRET_KEY'),
				'bucket' => getenv('PARAM1')
			)
	);

	include("sdk-1.5.3/sdk.class.php");

	$s3 = new AmazonS3( 
		array(
			'key' => $config['S3']['key'],
			'secret' => $config['S3']['secret']
		)
	);

	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
FOO
