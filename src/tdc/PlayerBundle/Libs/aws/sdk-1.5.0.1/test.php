<?php
require("sdk.class.php");

$cdn = new AmazonCloudFront();
$s3 = new AmazonS3();
#$response = $cdn->create_oai("videostreamer-".time(),array('Comment'=>'test'));
#$response = $cdn->list_oais();
#var_dump($response);

#$response = $cdn->create_distribution('tdcvideo', 'AWSPHPSDKCFDemo04', array(
#            'Enabled' => true,
#            'Comment' => 'no signers',
#            'Streaming' => true,
#            'OriginAccessIdentity' => 'E2QILF2DENI11O'
#            ));

#$response = $cdn->list_distributions(array(
#        'Streaming' => true
#        ));
         
#$response = $cdn->get_distribution_info('E3AW96NMF2XDJA', array(
#    'Streaming' => true
#    ));

#$response = $s3->delete_bucket_policy('tdcvideo');

#$response = $s3->set_bucket_acl('tdcvideo',array (
#        array( 'id' => 'a4760c4cf90921f8179dd3443befe7ee26ee6a7ad0e4e09e3a422b1677c8299b8f3ec7bb3dd09d22c9286185c2b2e504',
#               'permission' => AmazonS3::GRANT_READ ),
#        array( 'id' => '65b6e0f6ec099f77360488cf32f7871056246912e81124bff53484f5435debf8',
#                'permission' => AmazonS3::GRANT_FULL_CONTROL )// Self, FULL_CONTROL
#        ));

#$response = $s3->set_object_acl('tdcvideo', 'videos/programming/python/video1.mp4', AmazonS3::ACL_PUBLIC);

$response = $s3->set_object_acl('tdcvideo','videos/programming/python/video3.mp4',  array(
        array( 'id' => 'a4760c4cf90921f8179dd3443befe7ee26ee6a7ad0e4e09e3a422b1677c8299b8f3ec7bb3dd09d22c9286185c2b2e504',
               'permission' => AmazonS3::GRANT_READ ),
        array( 'id' => '65b6e0f6ec099f77360488cf32f7871056246912e81124bff53484f5435debf8',
                'permission' => AmazonS3::GRANT_FULL_CONTROL )// Self, FULL_CONTROL
        ));


// Generate a new policy object
#$policy = new CFPolicy($s3, array(
#            'Version' => '2008-10-17',
#            'Statement' => array(
#                array( // Statement #1
#                    'Sid' => 'AddPerm',
#                    'Effect' => 'Allow',
#                    'Principal' => array(
#                        'AWS' => '*'
#                        ),
#                    'Action' => array('s3:GetObject'),
#                    'Resource' => array('arn:aws:s3:::tdcvideo/*'),
#                    'Condition' => array("StringLike"=>array("aws:Referer"=>array("*foobar.com*","*nba.com*")))
#                    ))
#                    ));

// Set the bucket policy
#$response = $s3->set_bucket_policy('tdcvideo', $policy);

var_dump($response->isOK());
// Success?
#var_dump($response);
