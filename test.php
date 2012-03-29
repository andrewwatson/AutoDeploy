<?php

	try {

	$config = array(
			'S3' => array(
				'key' => getenv('AWS_ACCESS_KEY_ID'), 
				'secret' => getenv('AWS_SECRET_KEY'),
				'bucket' => 'mixee-static-assets'
			)
	);
	var_dump($config);
	die;


	include("sdk-1.5.3/sdk.class.php");

	$s3 = new AmazonS3( 
		array(
			'key' => $config['S3']['key'],
			'secret' => $config['S3']['secret']
		)
	);

	if (!$s3->if_bucket_exists( $config['S3']['bucket'])) {
		throw new Exception("BAD BUCKET {$config['S3']['bucket']}");
	}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
