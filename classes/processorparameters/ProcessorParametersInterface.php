<?php

namespace DieterHolvoet\ImageResizer\Classes\ProcessorParameters;

interface ProcessorParametersInterface
{
    public function getWidth(): ?int;

    public function setWidth(?int $value): self;

    public function getHeight(): ?int;

    public function setHeight(?int $value): self;

    public function getQuality(): ?int;

    public function setQuality(?int $value): self;
}
