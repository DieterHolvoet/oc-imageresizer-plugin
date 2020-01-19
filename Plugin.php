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

    public function registerListColumnTypes(): array
    {
        return [
            'thumb' => [$this, 'renderThumbColumn'],
        ];
    }

    public function renderThumbColumn($value, $column, $record)
    {
        $config = $column->config;

        $width = $config['width'] ?? 50;
        $height = $config['height'] ?? 50;
        $options = $config['options'] ?? [];

        if (isset($record->attachMany[$column->columnName])) {
            $file = $value->first();
        } elseif (isset($record->attachOne[$column->columnName])) {
            $file = $value;
        } else {
            $file = '/media' . $value;
        }

        try {
            $image = new Image($file);
            $url = $image->resize($width, $height, $options);
        } catch (\Exception $e) {
            return null;
        }

        return sprintf('<img src="%s"/>', $url);
    }
}
