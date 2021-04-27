<?php

namespace App\Models;

use App\Services\Uploader\StorageManager;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{


    protected $fillable = [
        'name' , 'size' , 'type' , 'is_private','description'
    ];

    public function url()
    {
        return $this->directoryPrefix($this->type, $this->name);
    }

    public function absolutePath()
    {
        return resolve(StorageManager::class)->getAbsolutePathOf($this->name, $this->type, $this->is_private);
    }

    public function download()
    {
        return resolve(StorageManager::class)->getFile($this->name, $this->type, $this->is_private);
    }

    public function delete()
    {
        resolve(StorageManager::class)->deleteFile($this->name, $this->type, $this->is_private);

        parent::delete();

    }

    private function directoryPrefix($type , $name)
    {
        return $type . DIRECTORY_SEPARATOR . $name;
    }

    public function filePath()
    {
        return $this->type . DIRECTORY_SEPARATOR . $this->name;
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
