<?php

namespace IdeoLearn\Core\Helpers;

class StorageConfig
{
    private string $endpoint;
    private string $accessKey;
    private string $secretKey;
    private string $region;
    private string $bucket;
    private bool $usePathStyleEndpoint;
    private ?string $url;

    public function __construct()
    {
        $this->endpoint = config('filesystems.disks.s3.endpoint');
        $this->accessKey = config('filesystems.disks.s3.key');
        $this->secretKey = config('filesystems.disks.s3.secret');
        $this->region = config('filesystems.disks.s3.region');
        $this->bucket = config('filesystems.disks.s3.bucket');
        $this->url = config('filesystems.disks.s3.url');
        $this->usePathStyleEndpoint = config('filesystems.disks.s3.use_path_style_endpoint', false);
    }

    public function getConfig(): array
    {
        return [
            'version' => 'latest',
            'endpoint' => $this->endpoint,
            'credentials' => [
                'key' => $this->accessKey,
                'secret' => $this->secretKey,
            ],
            'region' => $this->region,
            'bucket' => $this->bucket,
            'url' => $this->url,
            'use_path_style_endpoint' => $this->usePathStyleEndpoint
        ];
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}

