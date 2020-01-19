<?php

namespace DieterHolvoet\ImageResizer;

use DieterHolvoet\ImageResizer\Classes\Image;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => 'dieterholvoet.imageresizer::plugin.name',
            'description' => 'dieterholvoet.imageresizer::plugin.description',
            'author'      => 'Dieter Holvoet',
            'icon'        => 'icon-picture-o'
        ];
    }

    public function registerMarkupTags(): array
    {
        return [
            'filters' => [
                'resize' => static function (?string $path, ?int $width = null, ?int $height = null, array $options = []): ?string {
                    if (!$path) {
                        return null;
                    }

                    try {
                        return (new Image($path))->resize($width, $height, $options);
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            ]
        ];
    }
}
