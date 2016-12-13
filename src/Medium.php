<?php

namespace Microit\LaravelAdminBaseStandalone;

use Illuminate\Support\Facades\Storage;

class Medium extends BaseModel
{
    protected $table = 'media';

    protected $fillable = [
        'original_name',
        'storage_name',
        'mediable_id',
        'mediable_type',
        'position',
        'storage_disk',
        'width',
        'height'
    ];

    protected $queryable = [
        'id',
        'mediable_id',
        'mediable_type',
        'position',
        'storage_disk'
    ];

    /**
     * Returns the url of a media item
     *
     * @todo Clean up this code, make it storage disk independent
     * @return mixed
     */
    public function getUrl()
    {
        $folder = "";
        switch($this->storage_disk) {
            case 'image_category':
                $folder = 'category/';
                break;
            case 'image_accessory':
                $folder = 'accessory/';
                break;
            case 'image_brand':
                $folder = 'brand/';
                break;
            case 'image_device':
                $folder = 'device/';
                break;
            case 'image_repair':
                $folder = 'repair/';
                break;
        }

        return asset('images/uploads/' . $folder . $this->storage_name);
    }

    /**
     * Returns the local path of a media item
     *
     * @todo Clean up this code, make it storage disk independent
     * @return mixed
     */
    public function getPath()
    {
        $folder = '';
        switch($this->storage_disk) {
            case 'image_category':
                $folder = 'category/';
                break;
            case 'image_accessory':
                $folder = 'accessory/';
                break;
            case 'image_brand':
                $folder = 'brand/';
                break;
            case 'image_device':
                $folder = 'device/';
                break;
            case 'image_repair':
                $folder = 'repair/';
                break;
        }

        return public_path('images/uploads/'.$folder.$this->storage_name);
    }
}
