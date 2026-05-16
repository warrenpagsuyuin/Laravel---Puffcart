<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageService
{
    public const DIRECTORY = 'products';

    public function store(UploadedFile $file, string $productName): string
    {
        Storage::disk('public')->makeDirectory(self::DIRECTORY);

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = now()->format('YmdHisv') . '-' . Str::slug($productName ?: 'product') . '-' . Str::lower(Str::random(10)) . ".{$extension}";

        return $file->storeAs(self::DIRECTORY, $filename, 'public');
    }

    public function delete(?string $path): void
    {
        if ($path && Str::startsWith($path, self::DIRECTORY . '/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
