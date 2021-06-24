<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Maatwebsite\Excel\Excel;
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

    public function store(string $store_path, $file, string $filename = null): string
    {
        $storage = Storage::disk($this->disk);

        if (empty($filename)) {
            return $storage->putFile($store_path, new File($file));
        }

        return $storage->putFileAs($store_path, new File($file), $filename);
    }

    public function removeAndStore(string $store_path, $new_file, $old_file, string $filename = null): string
    {
        if (empty($new_file)) {
            return $old_file;
        }

        if (!empty($old_file)) {
            $this->removeFile($old_file);
        }

        return $this->store($store_path, $new_file, $filename);
    }

    public function putFileContent(string $store_path, $content): void
    {
        Storage::disk($this->disk)
            ->put($store_path, $content);
    }

    public function getMimesType(string $extension): string
    {
        return $this->mimes[$extension];
    }

    public function getExcelReaderType(string $extension): string
    {
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
            ->sort()->toArray();
    }

    public function removeFile(string $file_path)
    {
        $storage = Storage::disk($this->disk);

        if ($storage->exists($file_path)) {
            $storage->delete($file_path);
        }
    }
}
