<?php

namespace App\Services;

use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseService
{
    protected $database;
    protected $storage;

    public function __construct()
    {
        $this->database = Firebase::database();
        $this->storage = Firebase::storage();
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function uploadFile($file, $path)
    {
        $bucket = $this->storage->getBucket();
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $path . '/' . $fileName;

        $bucket->upload(
            file_get_contents($file),
            ['name' => $filePath]
        );

        $fileReference = $bucket->object($filePath);
        $fileReference->acl()->add('allUsers', 'READER');

        return $fileReference->info()['mediaLink'];
    }

    public function deleteFile($fileUrl)
    {
        $bucket = $this->storage->getBucket();

        $filePath = parse_url($fileUrl, PHP_URL_PATH);
        $filePath = ltrim($filePath, '/');

        try {
            $object = $bucket->object($filePath);
            if ($object->exists()) {
                $object->delete();
            }
        } catch (\Exception $e) {
            throw new \Exception('Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
