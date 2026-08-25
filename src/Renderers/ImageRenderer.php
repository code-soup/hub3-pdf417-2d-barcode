<?php

declare(strict_types=1);

namespace BigFish\PDF417\Renderers;

use BigFish\PDF417\BarcodeData;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageRenderer extends AbstractRenderer
{
    /** @var array<string, string|null> */
    protected array $formats = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'tif' => 'image/tiff',
        'bmp' => 'image/bmp',
        'data-url' => null,
    ];

    /** @var array<string, mixed> */
    protected array $options = [
        'format' => 'png',
        'quality' => 90,
        'scale' => 3,
        'ratio' => 3,
        'padding' => 20,
        'color' => "#000000",
        'bgColor' => "#ffffff",
    ];

    protected function validateOptions(): array
    {
        $errors = [];

        $format = $this->options['format'];
        if (!array_key_exists($format, $this->formats)) {
            $formats = implode(", ", array_keys($this->formats));
            $errors[] = "Invalid option \"format\": \"$format\". Expected one of: $formats.";
        }

        $scale = $this->options['scale'];
        if (!is_numeric($scale) || $scale < 1 || $scale > 20) {
            $errors[] = "Invalid option \"scale\": \"$scale\". Expected an integer between 1 and 20.";
        }

        $ratio = $this->options['ratio'];
        if (!is_numeric($ratio) || $ratio < 1 || $ratio > 10) {
            $errors[] = "Invalid option \"ratio\": \"$ratio\". Expected an integer between 1 and 10.";
        }

        $padding = $this->options['padding'];
        if (!is_numeric($padding) || $padding < 0 || $padding > 50) {
            $errors[] = "Invalid option \"padding\": \"$padding\". Expected an integer between 0 and 50.";
        }

        $quality = $this->options['quality'];
        if (!is_numeric($quality) || $quality < 0 || $quality > 100) {
            $errors[] = "Invalid option \"quality\": \"$quality\". Expected an integer between 0 and 50.";
        }

        // Color validation - intervention/image 3.x handles color parsing internally
        // Simple validation for basic hex colors
        $color = $this->options['color'];
        $bgColor = $this->options['bgColor'];

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color) && !preg_match('/^rgb\(/', $color) && !preg_match('/^rgba\(/', $color)) {
            $errors[] = "Invalid option \"color\": \"$color\". Supported color formats: \"#000000\", \"rgb(0,0,0)\", or \"rgba(0,0,0,0)\"";
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $bgColor) && !preg_match('/^rgb\(/', $bgColor) && !preg_match('/^rgba\(/', $bgColor)) {
            $errors[] = "Invalid option \"bgColor\": \"$bgColor\". Supported color formats: \"#000000\", \"rgb(0,0,0)\", or \"rgba(0,0,0,0)\"";
        }

        return $errors;
    }

    public function getContentType(): ?string
    {
        $format = (string) $this->options['format'];
        return $this->formats[$format] ?? null;
    }

    public function render(BarcodeData $data): string
    {
        $pixelGrid = $data->getPixelGrid();
        $height = count($pixelGrid);
        $width = count($pixelGrid[0]);

        // Extract options
        $bgColor = $this->options['bgColor'];
        $color = $this->options['color'];
        $format = $this->options['format'];
        $padding = $this->options['padding'];
        $quality = $this->options['quality'];
        $ratio = $this->options['ratio'];
        $scale = $this->options['scale'];

        // Create a new image with intervention/image 3.x API
        $manager = new ImageManager(new Driver());
        $img = $manager->create($width, $height)->fill($bgColor);

        // Render the barcode
        foreach ($pixelGrid as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value) {
                    $img->drawPixel($x, $y, $color);
                }
            }
        }

        // Apply scaling & aspect ratio
        $width *= $scale;
        $height *= $scale * $ratio;
        $img->resize($width, $height);

        // Add padding
        $width += 2 * $padding;
        $height += 2 * $padding;
        $img->resizeCanvas($width, $height, $bgColor, 'center');

        // Encode based on format
        switch ($format) {
            case 'jpg':
            case 'jpeg':
                $encoded = $img->toJpeg($quality);
                break;
            case 'png':
                $encoded = $img->toPng();
                break;
            case 'gif':
                $encoded = $img->toGif();
                break;
            case 'webp':
                $encoded = $img->toWebp($quality);
                break;
            case 'bmp':
                $encoded = $img->toBitmap();
                break;
            case 'tif':
            case 'tiff':
                $encoded = $img->toTiff();
                break;
            case 'data-url':
                $encoded = $img->toDataUri();
                return $encoded;
            default:
                $encoded = $img->toPng();
        }

        return $encoded->toString();
    }
}
