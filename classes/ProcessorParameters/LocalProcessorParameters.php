<?php

namespace DieterHolvoet\ImageResizer\Classes\ProcessorParameters;

class LocalProcessorParameters implements ProcessorParametersInterface
{
    /** @var int|null */
    protected $width;
    /** @var int|null */
    protected $height;
    /** @var int|null */
    protected $quality = 95;
    /** @var int[] */
    protected $offset = [0, 0];
    /** @var string */
    protected $mode = 'auto';
    /** @var string */
    protected $extension = 'jpg';
    /** @var int */
    protected $sharpen = 0;

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): ProcessorParametersInterface
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): ProcessorParametersInterface
    {
        $this->height = $height;
        return $this;
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(?int $quality): ProcessorParametersInterface
    {
        $this->quality = $quality;
        return $this;
    }

    public function getOffset(): ?array
    {
        return $this->offset;
    }

    public function setOffset(?array $offset): ProcessorParametersInterface
    {
        $this->offset = $offset;
        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): ProcessorParametersInterface
    {
        $this->mode = $mode;
        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): ProcessorParametersInterface
    {
        $this->extension = $extension;
        return $this;
    }

    public function getSharpen(): ?int
    {
        return $this->sharpen;
    }

    public function setSharpen(?int $sharpen): ProcessorParametersInterface
    {
        $this->sharpen = $sharpen;
        return $this;
    }

    public function getOptions(): array
    {
        return [
            'mode' => $this->mode,
            'offset' => $this->offset,
            'extension' => $this->extension,
            'quality' => $this->quality,
            'sharpen' => $this->sharpen,
        ];
    }
}
