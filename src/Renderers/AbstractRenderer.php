<?php

declare(strict_types=1);

namespace BigFish\PDF417\Renderers;

use BigFish\PDF417\BarcodeData;
use BigFish\PDF417\RendererInterface;

abstract class AbstractRenderer implements RendererInterface
{
    /** @var array<string, mixed> */
    protected array $options = [];

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        // Merge options with defaults, ignore options not specified in defaults
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            }
        }

        $errors = $this->validateOptions();
        if (!empty($errors)) {
            $errors = implode("\n", $errors);
            throw new \InvalidArgumentException($errors);
        }
    }

    /**
     * Validates the options.
     *
     * @return array<string> An array of error messages, empty if no errors.
     */
    protected function validateOptions(): array
    {
        return [];
    }

    abstract public function getContentType(): ?string;

    abstract public function render(BarcodeData $data): string;
}
