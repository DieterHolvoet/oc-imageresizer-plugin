<?php

namespace DieterHolvoet\ImageResizer\Models;

use DieterHolvoet\ImageResizer\Classes\Processor\ImageProcessorInterface;
use DieterHolvoet\ImageResizer\Classes\Processor\ImgixProcessor;
use DieterHolvoet\ImageResizer\Classes\Processor\LocalProcessor;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\ImgixProcessorParameters;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\LocalProcessorParameters;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\ProcessorParametersInterface;
use Model;

/**
 * @method static self instance
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'dieterholvoet_imageresizer_settings';
    public $settingsFields = 'fields.yaml';

    public function getProcessor(): ImageProcessorInterface
    {
        switch ($this->image_processor) {
            case 'imgix':
                return new ImgixProcessor;
            case 'local':
            default:
                return new LocalProcessor;
        }
    }

    public function getParameters(): ProcessorParametersInterface
    {
        switch ($this->image_processor) {
            case 'imgix':
                return new ImgixProcessorParameters;
            case 'local':
            default:
                return new LocalProcessorParameters;
        }
    }
}
