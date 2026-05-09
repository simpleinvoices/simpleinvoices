<?php

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\AwsS3V3\PortableVisibilityConverter;
use League\Flysystem\Filesystem;
use League\Flysystem\Visibility;

class S3LogoStore
{
    private static ?Filesystem $filesystem = null;
    private static ?bool $s3Configured = null;

    private static function isConfigured(): bool
    {
        if (self::$s3Configured !== null) {
            return self::$s3Configured;
        }
        global $config;
        $enabled = trim((string)($config->s3->enabled ?? ''));
        $endpoint = trim((string)($config->s3->endpoint ?? ''));
        $key = trim((string)($config->s3->key ?? ''));
        $secret = trim((string)($config->s3->secret ?? ''));
        $bucket = trim((string)($config->s3->bucket ?? ''));
        self::$s3Configured = in_array(strtolower($enabled), ['true', '1', 'yes', 'on'])
            && $endpoint !== ''
            && $key !== ''
            && $secret !== ''
            && $bucket !== '';
        return self::$s3Configured;
    }

    private static function instance(): ?Filesystem
    {
        if (!self::isConfigured()) {
            return null;
        }
        if (self::$filesystem !== null) {
            return self::$filesystem;
        }
        global $config;
        $endpoint = trim((string)$config->s3->endpoint);
        $key = trim((string)$config->s3->key);
        $secret = trim((string)$config->s3->secret);
        $bucket = trim((string)$config->s3->bucket);
        $region = trim((string)($config->s3->region ?? 'us-east-1'));

        $client = new S3Client([
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
            'region'      => $region,
            'endpoint'    => $endpoint,
            'version'     => 'latest',
            'use_path_style_endpoint' => true,
        ]);

        $adapter = new AwsS3V3Adapter(
            $client,
            $bucket,
            '',
            new PortableVisibilityConverter(Visibility::PRIVATE)
        );

        $fs = new Filesystem($adapter);

        try {
            if (!$client->doesBucketExist($bucket)) {
                $client->createBucket([
                    'Bucket' => $bucket,
                ]);
            }
        } catch (\Throwable $e) {
            error_log('S3LogoStore bucket init error: ' . $e->getMessage());
        }

        self::$filesystem = $fs;
        return self::$filesystem;
    }

    public static function upload(string $tmpPath, string $originalName): ?string
    {
        $fs = self::instance();
        if ($fs === null) {
            return null;
        }
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        if (!in_array($ext, $allowed, true)) {
            return null;
        }
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }
        $mimeTypes = [
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
        ];
        $uuid = sprintf('%s.%s', bin2hex(random_bytes(18)), $ext);
        $stream = fopen($tmpPath, 'r');
        if ($stream === false) {
            return null;
        }
        try {
            $fs->writeStream($uuid, $stream, [
                'ContentType' => $mimeTypes[$ext] ?? 'application/octet-stream',
            ]);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
        return $uuid;
    }

    public static function delete(string $filename): bool
    {
        $fs = self::instance();
        if ($fs === null) {
            return false;
        }
        try {
            if ($fs->fileExists($filename)) {
                $fs->delete($filename);
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            error_log('S3LogoStore::delete error: ' . $e->getMessage());
            return false;
        }
    }

    public static function list(): array
    {
        $fs = self::instance();
        if ($fs === null) {
            return [];
        }
        try {
            $items = $fs->listContents('', false);
            $files = [];
            foreach ($items as $item) {
                if ($item->isFile()) {
                    $files[] = $item->path();
                }
            }
            sort($files);
            return $files;
        } catch (\Throwable $e) {
            error_log('S3LogoStore::list error: ' . $e->getMessage());
            return [];
        }
    }

    public static function getStream(string $filename)
    {
        $fs = self::instance();
        if ($fs === null) {
            return null;
        }
        try {
            return $fs->readStream($filename);
        } catch (\Throwable $e) {
            error_log('S3LogoStore::getStream error: ' . $e->getMessage());
            return null;
        }
    }

    public static function getMimeType(string $filename): ?string
    {
        $fs = self::instance();
        if ($fs === null) {
            return null;
        }
        try {
            return $fs->mimeType($filename);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
