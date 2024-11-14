<?php

namespace IdeoLearn\Core\Services;


use IdeoLearn\Core\Exceptions\BucketCreationException;
use IdeoLearn\Core\Exceptions\BucketNotFoundException;
use IdeoLearn\Core\Helpers\StorageConfig;
use IdeoLearn\Core\Services\Contracts\BucketInterface;

class BucketService implements BucketInterface
{
    private $minioClient;

    public function __construct(StorageConfig $config)
    {
        $this->minioClient = new \Aws\S3\S3Client([
            'version' => $config->getConfig()['version'],
            'region' => $config->getConfig()['region'],
            'endpoint' => $config->getConfig()['endpoint'],
            'use_path_style_endpoint' => $config->getConfig()['use_path_style_endpoint'],
            'credentials' => [
                'key' => $config->getConfig()['credentials']['key'],
                'secret' => $config->getConfig()['credentials']['secret'],
            ],
        ]);
    }

    public function ensureBucketExists(string $bucketName): void
    {
        if (!$this->isBucketExists($bucketName)) {
            $this->createBucket($bucketName);
        }
        $this->setBucketPolicy($bucketName);
    }

    public function isBucketExists(string $bucketName): bool
    {
        try {
            return $this->minioClient->doesBucketExist($bucketName);
        } catch (\Exception $e) {
            throw new BucketNotFoundException("Error checking bucket existence: " . $e->getMessage());
        }
    }

    public function createBucket(string $bucketName): void
    {
        try {
            $this->minioClient->createBucket([
                'Bucket' => $bucketName,
                'ACL' => 'public-read'
            ]);

            // Wait until the bucket is created and available
            $this->minioClient->waitUntil('BucketExists', [
                'Bucket' => $bucketName
            ]);
        } catch (\Exception $e) {
            throw new BucketCreationException("Error creating bucket: " . $e->getMessage());
        }
    }

    private function setBucketPolicy(string $bucketName): void
    {
        try {
            $policy = [
                'Version' => '2012-10-17',
                'Statement' => [
                    [
                        'Effect' => 'Allow',
                        'Principal' => ['AWS' => ['*']],
                        'Action' => [
                            's3:GetBucketLocation',
                            's3:ListBucket',
                            's3:ListBucketMultipartUploads'
                        ],
                        'Resource' => ["arn:aws:s3:::{$bucketName}"]
                    ],
                    [
                        'Effect' => 'Allow',
                        'Principal' => ['AWS' => ['*']],
                        'Action' => [
                            's3:AbortMultipartUpload',
                            's3:DeleteObject',
                            's3:GetObject',
                            's3:ListMultipartUploadParts',
                            's3:PutObject'
                        ],
                        'Resource' => ["arn:aws:s3:::{$bucketName}/*"]
                    ]
                ]
            ];

            $this->minioClient->putBucketPolicy([
                'Bucket' => $bucketName,
                'Policy' => json_encode($policy)
            ]);
        } catch (\Exception $e) {
            throw new BucketCreationException("Error setting bucket policy: " . $e->getMessage());
        }
    }
}
