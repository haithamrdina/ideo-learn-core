<?php

namespace IdeoLearn\Core\Services\Contracts;

interface BucketInterface
{
    public function ensureBucketExists(string $bucketName): void;
    public function isBucketExists(string $bucketName): bool;
    public function createBucket(string $bucketName): void;
}
