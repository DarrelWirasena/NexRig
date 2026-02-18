<?php

namespace App\Filesystem;

use Illuminate\Filesystem\FilesystemAdapter;

class CloudinaryFilesystemAdapter extends FilesystemAdapter
{
    protected CloudinaryAdapter $cloudinaryAdapter;

    public function __construct($driver, CloudinaryAdapter $adapter, $config = [])
    {
        parent::__construct($driver, $adapter, $config);
        $this->cloudinaryAdapter = $adapter;
    }

    public function url($path)
    {
        return $this->cloudinaryAdapter->publicUrl($path);
    }


    // ← TAMBAHKAN METHOD INI JUGA
    public function temporaryUrl($path, $expiration, array $options = [])
    {
        return $this->cloudinaryAdapter->publicUrl($path);
    }
}