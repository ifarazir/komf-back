<?php

namespace App\Services\Uploader;

use App\Exceptions\FileHasExistsException;
use App\Models\File;
use Illuminate\Http\Request;

class Uploader
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var StorageManager
     */
    private $storageManager;

    private $file;

    /**
     * @var FFMpegService
     */
    private $ffmpeg;


    public function __construct(Request $request, StorageManager $storageManager)
    {
        $this->request = $request;
        $this->storageManager = $storageManager;
        $this->file = $request->file;
    }


    public function upload()
    {
        $name = $this->file->getClientOriginalName();

        if ($this->isFileExists($name)){
            $name = mt_rand(10000000, 99999999).$this->file->getClientOriginalName();
            if ($this->isFileExists($name)){
                return false;
            }
        };

        $this->putFileIntoStorage($name);

        return $this->saveFileIntoDatabase($name);
    }


    private function saveFileIntoDatabase($name)
    {
        $file = new File([
            'name' => $name,
            'size' => $this->file->getSize(),
            'type' => $this->getType(),
            'is_private' => $this->isPrivate()
        ]);

        $file->save();

        return $file;
    }


    private function putFileIntoStorage($name)
    {
        $method = $this->isPrivate() ? 'putFileAsPrivate' : 'putFileAsPublic';

        $this->storageManager->$method($name, $this->file,$this->getType());

    }


    private function isPrivate()
    {
        return $this->request->has('is-private');
    }

    private function getType()
    {
        return [
            'image/jpeg' => 'image',
            'video/mp4' => 'video',
            'image/svg+xml' => 'image',
            'image/png' => 'image',
            'application/zip' => 'archive'
        ][$this->file->getClientMimeType()];
    }

    private function isFileExists($name)
    {
       return $this->storageManager->isFileExists($name, $this->getType(), $this->isPrivate());
    }


}
