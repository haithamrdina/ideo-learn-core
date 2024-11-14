<?php

namespace IdeoLearn\Core\Services\Contracts;

use IdeoLearn\Core\Http\Resources\StorageResource;
use Illuminate\Http\UploadedFile;

interface StorageInterface
{
    public function getMinioClient(): \Aws\S3\S3Client;
    public function getPresignedUrl(string $bucketName, string $path, string $expires = '+1 hour'): string;
    public function uploadFile(string $bucketName, ?string $path, UploadedFile $file): StorageResource;
    public function updateFile(string $bucketName, string $path, UploadedFile $file): StorageResource;
    public function deleteFile(string $bucketName, string $path): bool;
    public function downloadFile(string $bucketName, string $path): string;
}
