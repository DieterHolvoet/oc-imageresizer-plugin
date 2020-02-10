<?php

namespace DieterHolvoet\ImageResizer\Classes\Processor;

use DieterHolvoet\ImageResizer\Classes\ImageInterface;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\ImgixProcessorParameters;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\ProcessorParametersInterface;
use DieterHolvoet\ImageResizer\Models\Settings;

class ImgixProcessor implements ImageProcessorInterface
{
    public function getUrl(ImageInterface $image, ProcessorParametersInterface $parameters): string
    {
        if (!$parameters instanceof ImgixProcessorParameters) {
            throw new \InvalidArgumentException('Wrong parameters!');
        }

        $settings = Settings::instance();
        $queryParams = $parameters->getAll();

        if (!$settings->imgix_domain) {
            throw new \RuntimeException('Imgix domain must be specified before urls can be generated.');
        }

        if ($token = $settings->imgix_secure_url_token) {
            $queryParams['s'] = md5($token . $image->getPath() . http_build_query($queryParams));
        }

        return sprintf(
            '%s://%s/%s/%s?%s',
            $settings->imgix_use_https ? 'https' : 'http',
            $settings->imgix_domain,
            $settings->imgix_prefix,
            trim($image->getPath(), '/'),
            http_build_query($queryParams)
        );
    }
}
