<?php

namespace App\Filesystem;

use Cloudinary\Cloudinary;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;

class CloudinaryAdapter implements FilesystemAdapter
{
    protected Cloudinary $cloudinary;
    protected string $folder;

    public function __construct(array $config = [])
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
        
        $this->folder = $config['folder'] ?? '';
    }

    public function fileExists(string $path): bool
    {
        try {
            $publicId = $this->getPublicId($path);
            $this->cloudinary->adminApi()->asset($publicId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function directoryExists(string $path): bool
    {
        return true;
    }

   public function write(string $path, string $contents, Config $config): void
{
    $publicId = $this->getPublicId($path);
    
    // Handle different content types
    if (is_resource($contents)) {
        $contents = stream_get_contents($contents);
    }
    
    if (is_string($contents) && is_file($contents)) {
        $uploadData = $contents;
    } 
    elseif (is_string($contents) && strpos($contents, 'data:') === 0) {
        $uploadData = $contents;
    }
    else {
        $base64 = base64_encode($contents);
        $uploadData = "data:image/jpeg;base64,{$base64}";
    }
    
    // Upload ke Cloudinary
    $result = $this->cloudinary->uploadApi()->upload($uploadData, [
        'public_id' => $publicId,
        'resource_type' => 'auto',
        'overwrite' => true,
    ]);
    
}

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->write($path, stream_get_contents($contents), $config);
    }

    public function read(string $path): string
    {
        $url = $this->publicUrl($path);
        return file_get_contents($url);
    }

    public function readStream(string $path)
    {
        $url = $this->publicUrl($path);
        return fopen($url, 'r');
    }

    public function delete(string $path): void
    {
        try {
            $publicId = $this->getPublicId($path);
            $this->cloudinary->uploadApi()->destroy($publicId);
        } catch (\Exception $e) {
            // Ignore if file doesn't exist
        }
    }

  public function deleteDirectory(string $path): void
{
    // Cloudinary doesn't need to delete directories
    // Files will be deleted individually via delete() method
}

    public function createDirectory(string $path, Config $config): void
    {
        // Cloudinary doesn't need directories
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // Not implemented
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

   public function lastModified(string $path): FileAttributes
{
    try {
        $publicId = $this->getPublicId($path);
        $result = $this->cloudinary->adminApi()->asset($publicId);
        $timestamp = strtotime($result['created_at'] ?? 'now');
        
        return new FileAttributes($path, null, null, $timestamp);
    } catch (\Exception $e) {
        return new FileAttributes($path, null, null, time());
    }
}

public function mimeType(string $path): FileAttributes
{
    try {
        $publicId = $this->getPublicId($path);
        $result = $this->cloudinary->adminApi()->asset($publicId);
        $format = $result['format'] ?? 'jpg';
        $mimeType = 'image/' . $format;
        
        return new FileAttributes($path, null, null, null, $mimeType);
    } catch (\Exception $e) {
        return new FileAttributes($path, null, null, null, 'image/jpeg');
    }
}

   public function fileSize(string $path): FileAttributes
{
    try {
        $publicId = $this->getPublicId($path);
        $result = $this->cloudinary->adminApi()->asset($publicId);
        $bytes = $result['bytes'] ?? 0;
        
        return new FileAttributes($path, $bytes);
    } catch (\Exception $e) {
        return new FileAttributes($path, 0);
    }
}

    public function listContents(string $path, bool $deep): iterable
    {
        return [];
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $sourceId = $this->getPublicId($source);
        $destId = $this->getPublicId($destination);
        
        $this->cloudinary->uploadApi()->rename($sourceId, $destId);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $content = $this->read($source);
        $this->write($destination, $content, $config);
    }

public function publicUrl(string $path): string
{
    // Jika sudah URL lengkap Cloudinary, return as-is
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    
    $cloudName = config('cloudinary.cloud_name');
    
    // Path sudah dalam format yang benar (products/nama-file tanpa extension)
    // Cloudinary akan otomatis serve dengan format yang sesuai
    return "https://res.cloudinary.com/{$cloudName}/image/upload/{$path}";
}

    protected function getPublicId(string $path): string
    {
        // Remove extension
        $path = preg_replace('/\.[^.]+$/', '', $path);
        
        // Add folder prefix if set
        if ($this->folder) {
            $path = $this->folder . '/' . $path;
        }
        
        return $path;
    }
}