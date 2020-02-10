<?php

namespace DieterHolvoet\ImageResizer\Classes\ProcessorParameters;

class ImgixProcessorParameters implements ProcessorParametersInterface
{
    /** @var array */
    protected $parameters = [
        'auto' => ['compress', 'format'],
    ];

    public function get(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function set(string $key, $value): ProcessorParametersInterface
    {
        $this->parameters[$key] = $value;
        return $this;
    }

    public function getAll(): array
    {
        $params = [];

        foreach ($this->parameters as $key => $value) {
            if (is_array($value)) {
                $params[$key] = implode(',', $value);
            }

            if ($value !== null) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    public function getAuto()
    {
        return $this->get('auto');
    }

    public function setAuto($value): ProcessorParametersInterface
    {
        return $this->set('auto', $value);
    }

    public function getWidth(): ?int
    {
        return $this->get('w');
    }

    public function setWidth(?int $value): ProcessorParametersInterface
    {
        return $this->set('w', $value);
    }

    public function getHeight(): ?int
    {
        return $this->get('h');
    }

    public function setHeight(?int $value): ProcessorParametersInterface
    {
        return $this->set('h', $value);
    }

    public function getQuality(): ?int
    {
        return $this->get('q');
    }

    public function setQuality(?int $value): ProcessorParametersInterface
    {
        return $this->set('q', $value);
    }

    public function getSharpen(): ?int
    {
        return $this->get('sharp');
    }

    public function setSharpen(?int $value): ProcessorParametersInterface
    {
        return $this->set('sharp', $value);
    }
}
