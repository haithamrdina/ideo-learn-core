<?php

namespace IdeoLearn\Core\Services;

use IdeoLearn\Core\Exceptions\FileNotFoundException;
use IdeoLearn\Core\Exceptions\FileUploadException;
use IdeoLearn\Core\Helpers\StorageConfig;
use IdeoLearn\Core\Http\Resources\StorageResource;
use IdeoLearn\Core\Services\Contracts\BucketInterface;
use IdeoLearn\Core\Services\Contracts\StorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class StorageService implements StorageInterface
{
    private $minioClient;
    private $bucketInterface;
    private $minioConfig;
    private $publicClient;

    public function __construct(StorageConfig $minioConfig, BucketInterface $bucketInterface)
    {
        $this->minioConfig = $minioConfig;

        // Client for internal operations (upload, delete, etc)
        $this->minioClient = new \Aws\S3\S3Client([
            'version' => $minioConfig->getConfig()['version'],
            'region' => $minioConfig->getConfig()['region'],
            'endpoint' => $minioConfig->getConfig()['endpoint'],
            'use_path_style_endpoint' => $minioConfig->getConfig()['use_path_style_endpoint'],
            'credentials' => [
                'key' => $minioConfig->getConfig()['credentials']['key'],
                'secret' => $minioConfig->getConfig()['credentials']['secret'],
            ],
        ]);

        // Client for generating public URLs
        $this->publicClient = new \Aws\S3\S3Client([
            'version' => $minioConfig->getConfig()['version'],
            'region' => $minioConfig->getConfig()['region'],
            'endpoint' => $minioConfig->getConfig()['url'], // Use public URL here
            'use_path_style_endpoint' => $minioConfig->getConfig()['use_path_style_endpoint'],
            'credentials' => [
                'key' => $minioConfig->getConfig()['credentials']['key'],
                'secret' => $minioConfig->getConfig()['credentials']['secret'],
            ],
        ]);

        $this->bucketInterface = $bucketInterface;
    }


    public function getMinioClient(): \Aws\S3\S3Client
    {
        return $this->minioClient;
    }

    public function uploadFile(string $bucketName, ?string $path, UploadedFile $file): StorageResource
    {
        $this->bucketInterface->ensureBucketExists($bucketName);
        $filename = $this->generateUniqueFilename($file);
        $fullPath = $path ? trim($path, '/') . '/' . $filename : $filename;

        try {
            $this->minioClient->putObject([
                'Bucket' => $bucketName,
                'Key' => $fullPath,
                'Body' => fopen($file->getRealPath(), 'r'),
                'ACL' => 'public-read',
                'ContentType' => $file->getMimeType()
            ]);

            return new StorageResource([
                'bucket' => $bucketName,
                'filename' => $filename,
                'path' => $fullPath,
                'url' => $this->generateFileUrl($bucketName, $fullPath)
            ]);
        } catch (\Exception $e) {
            throw new FileUploadException("Error uploading file: " . $e->getMessage());
        }
    }

    public function updateFile(string $bucketName, string $path, UploadedFile $file): StorageResource
    {
        $this->deleteFile($bucketName, $path);
        return $this->uploadFile($bucketName, dirname($path), $file);
    }

    public function deleteFile(string $bucketName, string $path): bool
    {
        try {
            if (!$this->minioClient->doesObjectExist($bucketName, $path)) {
                throw new FileNotFoundException("File not found: {$path}");
            }

            $this->minioClient->deleteObject([
                'Bucket' => $bucketName,
                'Key' => $path
            ]);

            return true;
        } catch (FileNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting file: " . $e->getMessage());
        }
    }

    public function downloadFile(string $bucketName, string $path): string
    {
        try {
            if (!$this->minioClient->doesObjectExist($bucketName, $path)) {
                throw new FileNotFoundException("File not found: {$path}");
            }

            $result = $this->minioClient->getObject([
                'Bucket' => $bucketName,
                'Key' => $path
            ]);

            return $result['Body']->getContents();
        } catch (FileNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception("Error downloading file: " . $e->getMessage());
        }
    }

    private function generateUniqueFilename(UploadedFile $file): string
    {
        return sprintf(
            '%s_%s.%s',
            uniqid(),
            Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
            $file->getClientOriginalExtension()
        );
    }

    public function getPresignedUrl(string $bucketName, string $path, string $expires = '+1 hour'): string
    {
        try {
            if (!$this->minioClient->doesObjectExist($bucketName, $path)) {
                throw new FileNotFoundException("File not found: {$path}");
            }

            // Use the public client to generate the presigned URL
            $command = $this->publicClient->getCommand('GetObject', [
                'Bucket' => $bucketName,
                'Key' => $path
            ]);

            $request = $this->publicClient->createPresignedRequest($command, $expires);
            return (string) $request->getUri();
        } catch (FileNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception("Error generating presigned URL: " . $e->getMessage());
        }
    }

    private function generateFileUrl(string $bucketName, string $path): string
    {
        $endpoint = rtrim($this->minioConfig->getConfig()['url'], '/');
        return "{$endpoint}/{$bucketName}/{$path}";
    }
}
