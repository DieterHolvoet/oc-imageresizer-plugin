<?php

namespace DieterHolvoet\ImageResizer\Classes\Processor;

use DieterHolvoet\ImageResizer\Classes\ImageInterface;
use DieterHolvoet\ImageResizer\Classes\ProcessorParameters\ProcessorParametersInterface;

interface ImageProcessorInterface
{
    public function getUrl(ImageInterface $image, ProcessorParametersInterface $parameters): string;
}
