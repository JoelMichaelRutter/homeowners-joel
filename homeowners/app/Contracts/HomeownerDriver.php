<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface HomeownerDriver
{
    public function getRaw(UploadedFile $file): array;

    public function process(array $homeowners): array;
}
