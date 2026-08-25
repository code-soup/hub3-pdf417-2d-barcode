<?php

declare(strict_types=1);

namespace BigFish\PDF417;

interface RendererInterface
{
    /**
     * Renders the barcode data to the appropriate output format.
     */
    public function render(BarcodeData $data): string;

    /**
     * Returns the MIME content type of the rendered output.
     */
    public function getContentType(): ?string;
}
