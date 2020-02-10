<?php

namespace DieterHolvoet\ImageResizer;

use DieterHolvoet\ImageResizer\Classes\Image;
use DieterHolvoet\ImageResizer\Models\Settings;
use Illuminate\Support\Facades\Lang;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

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
                'image' => static function (?string $path, ?int $width = null, ?int $height = null, ?int $quality = null): ?string {
                    if (!$path) {
                        return null;
                    }

                    $settings = Settings::instance();
                    $parameters = $settings->getParameters()
                        ->setWidth($width)
                        ->setHeight($height)
                        ->setQuality($quality);

                    try {
                        $image = Image::fromPath($path);
                        return $settings->getProcessor()->getUrl($image, $parameters);
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'dieterholvoet.imageresizer.access_settings' => [
                'label' => Lang::get('dieterholvoet.imageresizer::app.permission.access_settings'),
                'tab' => Lang::get('dieterholvoet.imageresizer::app.name'),
                'order' => 100,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'location' => [
                'label'       => Lang::get('dieterholvoet.imageresizer::app.name'),
                'description' => Lang::get('dieterholvoet.imageresizer::app.settings'),
                'category'    => SettingsManager::CATEGORY_CMS,
                'icon'        => 'icon-picture-o',
                'class'       => Settings::class,
                'order'       => 500,
                'keywords'    => 'settings imageresizer image imgix',
                'permissions' => ['dieterholvoet.imageresizer.access_settings'],
            ],
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
        $settings = Settings::instance();
        $parameters = $settings->getParameters()
            ->setWidth($config['width'] ?? 50)
            ->setHeight($config['height'] ?? 50);

        if (isset($config['quality'])) {
            $parameters->setQuality($config['quality']);
        }

        if (isset($record->attachMany[$column->columnName])) {
            $file = $value->first();
        } elseif (isset($record->attachOne[$column->columnName])) {
            $file = $value;
        } else {
            $file = '/media' . $value;
        }

        try {
            $image = Image::fromPath($file);
            $url = $settings->getProcessor()->getUrl($image, $parameters);
        } catch (\Exception $e) {
            return null;
        }

        return sprintf('<img src="%s"/>', $url);
    }
}
