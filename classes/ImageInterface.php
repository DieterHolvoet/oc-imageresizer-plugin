<?php

namespace DieterHolvoet\ImageResizer\Classes;

use Illuminate\Contracts\Filesystem\Filesystem;

interface ImageInterface
{
    public function getStorage(): Filesystem;

    public function getPath(): string;
}
