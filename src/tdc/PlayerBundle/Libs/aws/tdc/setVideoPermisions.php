#!/usr/bin/env php

<?php
require("../sdk-1.5.0.1/sdk.class.php");

$s3 = new AmazonS3();
$origin_bucket = 'tdcvideo';

$file_paths = $s3->get_object_list($origin_bucket,
                    array('prefix'=>'videos'));

foreach ($file_paths as $file) {

    $response = $s3->set_object_acl('tdcvideo',$file ,
        array(
            array( 'id' => 'a4760c4cf90921f8179dd3443befe7ee26ee6a7ad0e4e09e3a422b1677c8299b8f3ec7bb3dd09d22c9286185c2b2e504',
                   'permission' => AmazonS3::GRANT_READ ),
            array( 'id' => '65b6e0f6ec099f77360488cf32f7871056246912e81124bff53484f5435debf8',
                    'permission' => AmazonS3::GRANT_FULL_CONTROL )// Self, FULL_CONTROL
            )
    );
    if ($response->isOk())
        print "Successfuly set permissions for ".$file."\n";
    else
        print "Failed to set permissions for ".$file."\n";
}
