<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Maatwebsite\Excel\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileManager
{
    public $disk = 'public';

    public $mimes = [
        'png'   =>  'image/png',
        'jpg'   =>  'image/jpeg',
        'jpeg'  =>  'image/jpeg',
        'csc'   =>  'text/csv',
        'pdf'   =>  'application/pdf',
        'xls'   =>  'application/vnd.ms-excel',
        'xlsx'  =>  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'svg'   =>  'image/svg+xml',
        'zip'   =>  'application/zip'
    ];

    public static function instance()
    {
        return new self();
    }

    public function setCustomDisk(string $disk): object
    {
        $this->disk = $disk;

        return $this;
    }

    private function getStorageDisk()
    {
        return Storage::disk($this->disk);
    }

    public function store(string $store_path, $save_file, string $old_file = null, string $filename = null): string
    {
        $storage = $this->getStorageDisk();

        if (empty($save_file)) {
            return $old_file;
        }

        if (!empty($old_file)) {
            $this->removeFile($old_file);
        }

        if ($this->checkImageMimes($save_file->getClientOriginalExtension())) {
            return $this->resizeImage($store_path, $save_file);
        }

        return (empty($filename))
            ? $storage->putFile($store_path, new File($save_file))
            : $storage->putFileAs($store_path, new File($save_file), $filename);
    }

    public function getMimesType(string $extension): string
    {
        $extension = strtolower($extension);

        return $this->mimes[$extension];
    }

    public function getExcelReaderType(string $extension): string
    {
        $extension = strtolower($extension);

        $reader = [
            'xlsx'  =>  Excel::XLSX,
            'csv'   =>  Excel::CSV,
            'txt'   =>  Excel::CSV
        ];

        return $reader[$extension];
    }

    public function getExtensions(array $inclusives = []): array
    {
        return collect($this->mimes)->keys()
            ->when(!empty($inclusives), function ($collection) use ($inclusives) {
                return $collection->only($inclusives);
            })
            ->sort()
            ->toArray();
    }

    public function removeFile(string $file_path): void
    {
        $storage = $this->getStorageDisk();

        if ($storage->exists($file_path)) {
            $storage->delete($file_path);
        }
    }

    public function putFileContent(string $store_path, $content): void
    {
        $this->getStorageDisk()->put($store_path, $content);
    }

    public function resizeImage($store_path, $image)
    {
        $max_width = 1024;
        $max_height = 1024;


        $img = Image::make($image->path());
        $img->height() > $img->width() ? $max_width = null : $max_height = null;

        $img->resize($max_width, $max_height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $filename = md5($img->__toString() . time());
        $save_path = $store_path . '/' . $filename . '.' . $image->extension();
        $img->save(public_path('storage/' . $save_path));

        return $save_path;
    }

    private function checkImageMimes(string $mimes): bool
    {
        $mimes = strtolower($mimes);

        $image_mimes = ['png', 'jpg', 'jpeg'];

        return in_array($mimes, $image_mimes);
    }
}
