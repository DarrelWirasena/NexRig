<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class CloudinaryHelper
{
    public static function upload(UploadedFile|string $file, string $folder = 'games'): string
    {
        $cloudName = config('cloudinary.cloud_name');
        $apiKey    = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');

        // Jika $file berupa string (path temporary dari Filament)
        if (is_string($file)) {
            $tempPath = storage_path('app/public/livewire-tmp/' . $file);
            $mimeType = mime_content_type($tempPath);
            $fileName = basename($tempPath);
        } else {
            $tempPath = $file->getRealPath();
            $mimeType = $file->getMimeType();
            $fileName = $file->getClientOriginalName();
        }

        $timestamp = time();
        $params    = "folder={$folder}&timestamp={$timestamp}{$apiSecret}";
        $signature = sha1($params);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'file'      => new \CURLFile($tempPath, $mimeType, $fileName),
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'folder'    => $folder,
                'signature' => $signature,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['secure_url'])) {
            throw new \Exception('Cloudinary upload failed: ' . ($data['error']['message'] ?? 'Unknown error'));
        }

        return $data['secure_url'];
    }
}