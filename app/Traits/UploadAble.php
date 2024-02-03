<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Trait UploadAble
 * @package App\Traits
 */
trait UploadAble
{
    /**
     * @param UploadedFile $file
     * @param mixed $folder
     * @param string $disk
     * @param string $filename
     * @return array
     */
    public function uploadOne(UploadedFile $file, $folder = null, $filename = null, $disk = 'public')
    {
        // Generate a unique filename if not provided
        $name = !is_null($filename) ? $filename : Str::random(25);
        // Generate the full path including the folder
        $path = $folder ? $folder . '/' . $name : $name;

        // Ensure the folder exists, create it if not
        if (!Storage::disk($disk)->exists($folder)) {
            Storage::disk($disk)->makeDirectory($folder);
        }

        $full_file_name = $name . "." . $file->getClientOriginalExtension();

        $file_path = $file->storeAs(
            $folder,
            $full_file_name,
            $disk
        );

        return ['file_path' => $file_path, 'file_name' => $full_file_name];
    }

    /**
     * @param string $path
     * @param string $disk
     */
    public function deleteOne($path = null, $disk = 'public')
    {
        Storage::disk($disk)->delete($path);
    }
}