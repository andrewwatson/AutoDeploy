<?php
	$config = array(
			'S3' => array(
				'key' => 'AKIAJPPIBAYT2JKF27ZQ',
				'secret' => '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',
				'bucket' => 'mixee-static-assets'
			)
	);

	include("sdk-1.5.3/sdk.class.php");

	$s3 = new AmazonS3( 
		array(
			'key' => $config['S3']['key'],
			'secret' => $config['S3']['secret']
		)
	);

	if (!$s3->if_bucket_exists( $config['S3']['bucket'])) {
		error_log("BUCKET DOES NOT EXIST");
		die;
	}

	echo "proceed\n";
