<?php

namespace DieterHolvoet\ImageResizer\Classes;

use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as FlysystemFilesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use October\Rain\Database\Attach\File;

class Image
{
    /** @var Filesystem */
    protected $storage;
    /** @var Filesystem */
    protected $thumbStorage;
    /** @var string */
    protected $path;
    /** @var File */
    protected $file;

    public function __construct(string $path)
    {
        [$storage, $path] = $this->parsePath($path);
        $this->storage = $storage ? Storage::disk($storage) : $this->getRootFilesystem();
        $this->thumbStorage = Storage::disk(config('cms.storage.uploads.disk', 'local'));
        $this->path = $path;

        $this->file = new File;
        $this->file->disk_name = md5($path);
    }

    public function resize(?int $width = null, ?int $height = null, array $options = []): string
    {
        $options = $this->ensureOptions($options);
        $fileName = pathinfo($this->path, PATHINFO_BASENAME);
        $thumbFilename = $this->getThumbFilename($width, $height, $options);
        $thumbPath = $this->file->getDiskPath($thumbFilename);

        if ($this->thumbStorage->exists($thumbPath)) {
            if (!$this->isThumbOutdated($thumbPath)) {
                return $this->thumbStorage->url($thumbPath);
            }

            $this->thumbStorage->delete($thumbPath);
        }

        // Create a temporary file
        $contents = $this->storage->get($this->path);
        $this->file->fromData($contents, $fileName);

        // Resize it
        $this->file->getThumb($width, $height, $options);

        // Delete temporary file
        $tempPath = $this->file->getDiskPath();
        if ($this->storage->exists($tempPath)) {
            $this->storage->delete($tempPath);
        }

        return $this->thumbStorage->url($thumbPath);
    }

    protected function isThumbOutdated(string $thumbPath): bool
    {
        return $this->storage->getTimestamp($this->path) > $this->thumbStorage->getTimestamp($thumbPath);
    }

    protected function getThumbFilename(?int $width = null, ?int $height = null, array $options = []): string
    {
        $width = (int) $width;
        $height = (int) $height;

        return 'thumb__' . $width . '_' . $height . '_' . $options['offset'][0] . '_' . $options['offset'][1] . '_' . $options['mode'] . '.' . $options['extension'];
    }

    protected function ensureOptions(array $options): array
    {
        $defaults = [
            'mode' => 'auto',
            'offset' => [0, 0],
            'extension' => pathinfo($this->path)['extension'],
            'quality' => 95,
            'sharpen' => 0,
        ];

        return array_merge($defaults, $options);
    }

    protected function parsePath(string $url): array
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
                return [null, $path];
            }
        }

        foreach (config('cms.storage', []) as $name => $definition) {
            $folder = str_start($definition['folder'], DIRECTORY_SEPARATOR);

            if (starts_with($path, $folder)) {
                return [$definition['disk'], $path];
            }

            if (starts_with($url, $definition['path'])) {
                $paths = explode($definition['path'], $url, 2);
                $path = $folder . end($paths);

                return [$definition['disk'], $path];
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Unable to parse path %s', $url)
        );
    }

    protected function getRootFilesystem(): Filesystem
    {
        return new FilesystemAdapter(new FlysystemFilesystem(new Local(base_path())));
    }
}
