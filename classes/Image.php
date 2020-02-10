<?php

namespace DieterHolvoet\ImageResizer\Classes;

use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as FlysystemFilesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use October\Rain\Database\Attach\File;

class Image implements ImageInterface
{
    /** @var Filesystem */
    protected $storage;
    /** @var string */
    protected $path;

    public function __construct(Filesystem $storage, string $path)
    {
        $this->storage = $storage;
        $this->path = $path;
    }

    public function getStorage(): Filesystem
    {
        return $this->storage;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public static function fromPath(string $url): self
    {
        $path = urldecode(parse_url($url, PHP_URL_PATH));
        $path = str_start($path, DIRECTORY_SEPARATOR);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (!in_array($extension, File::$imageExtensions, true)) {
            throw new \InvalidArgumentException(
                sprintf('Path %s is not an image.', $path)
            );
        }

        $localFolders = [
            config('cms.themesPath', '/themes'),
            config('cms.pluginsPath', '/plugins'),
        ];

        foreach ($localFolders as $folder) {
            if (starts_with($path, $folder)) {
                return new static(self::getRootFilesystem(), $path);
            }
        }

        foreach (config('cms.storage', []) as $name => $definition) {
            $folder = str_start($definition['folder'], DIRECTORY_SEPARATOR);

            if (starts_with($path, $folder)) {
                return new static(Storage::disk($definition['disk']), $path);
            }

            if (starts_with($url, $definition['path'])) {
                $paths = explode($definition['path'], $url, 2);
                $path = $folder . end($paths);

                return new static(Storage::disk($definition['disk']), $path);
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Unable to parse path %s', $url)
        );
    }

    protected static function getRootFilesystem(): Filesystem
    {
        return new FilesystemAdapter(new FlysystemFilesystem(new Local(base_path())));
    }
}
