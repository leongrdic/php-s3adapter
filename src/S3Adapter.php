<?php

namespace Le\S3Adapter;

use Aws\S3\{S3Client, S3ClientInterface};

class S3Adapter
{

    public function __construct(
        public string             $bucket,
        string                    $region,
        string                    $key,
        string                    $secret,
        string                    $endpoint = '',
        string                    $version = 'latest',
        bool                      $useSharedConfig = false,
        public ?S3ClientInterface $s3Client = null
    )
    {
        $this->s3Client ??= new S3Client([
            'region' => $region,
            'version' => $version,
            'endpoint' => $endpoint,
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
            'use_aws_shared_config_files' => $useSharedConfig,
        ]);
    }

    public function put(string $key, string $data)
    {
        return $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'Body'   => $data,
        ]);
    }

    public function get(string $key, bool $justBody = false)
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);

        return $justBody ? $result['Body'] : $result;
    }

    public function delete(string $key)
    {
        return $this->s3Client->deleteObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ));
    }

}