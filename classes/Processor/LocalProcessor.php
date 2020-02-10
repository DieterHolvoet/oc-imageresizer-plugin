<?php

namespace DieterHolvoet\ImageResizer\Classes\Processor;

use DieterHolvoet\ImageResizer\Classes\ImageInterface;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\LocalProcessorParameters;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\ProcessorParametersInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use October\Rain\Database\Attach\File;

class LocalProcessor implements ImageProcessorInterface
{
    /** @var Filesystem */
    protected $thumbStorage;
    /** @var File */
    protected $file;

    public function __construct(?string $thumbStorage = null)
    {
        $this->thumbStorage = Storage::disk($thumbStorage ?? config('cms.storage.uploads.disk') ?? 'local');
    }

    public function getUrl(ImageInterface $image, ProcessorParametersInterface $parameters): string
    {
        if (!$parameters instanceof LocalProcessorParameters) {
            throw new \InvalidArgumentException('Wrong parameters!');
        }

        $path = $image->getPath();
        $storage = $image->getStorage();

        $file = new File;
        $file->disk_name = md5($image->getPath());

        $fileName = pathinfo($path, PATHINFO_BASENAME);
        $thumbFilename = $this->getThumbFilename($parameters);
        $thumbPath = $file->getDiskPath($thumbFilename);

        if ($this->thumbStorage->exists($thumbPath)) {
            if (!$this->isThumbOutdated($image, $thumbPath)) {
                return $this->thumbStorage->url($thumbPath);
            }

            $this->thumbStorage->delete($thumbPath);
        }

        // Create a temporary file
        $contents = $storage->get($path);
        $file->fromData($contents, $fileName);

        // Resize it
        $file->getThumb(
            $parameters->getWidth(),
            $parameters->getHeight(),
            $parameters->getOptions()
        );

        // Delete temporary file
        $tempPath = $file->getDiskPath();
        if ($storage->exists($tempPath)) {
            $storage->delete($tempPath);
        }

        return $this->thumbStorage->url($thumbPath);
    }

    protected function isThumbOutdated(ImageInterface $image, string $thumbPath): bool
    {
        return $image->getStorage()->getTimestamp($image->getPath()) > $this->thumbStorage->getTimestamp($thumbPath);
    }

    protected function getThumbFilename(LocalProcessorParameters $parameters): string
    {
        [$offsetX, $offsetY] = $parameters->getOffset();

        return sprintf(
            'thumb__%s_%s_%s_%s_%s.%s',
            $parameters->getWidth(),
            $parameters->getHeight(),
            $offsetX,
            $offsetY,
            $parameters->getMode(),
            $parameters->getExtension()
        );
    }
}
