<?php

declare(strict_types=1);

namespace BigFish\PDF417;

/**
 * Container class which holds all data needed to render a PDF417 bar code.
 */
class BarcodeData
{
    /** @var array<int> */
    public array $codeWords = [];

    public int $columns = 0;

    public int $rows = 0;

    /** @var array<int, array<int, int>> */
    public array $codes = [];

    public int $securityLevel = 0;

    /**
     * @return array<int, array<int, bool>>
     */
    public function getPixelGrid(): array
    {
        $pixelGrid = [];
        foreach ($this->codes as $row) {
            $pixelRow = [];
            foreach ($row as $value) {
                $bin = decbin($value);
                $len = strlen($bin);
                for ($i = 0; $i < $len; $i++) {
                    $pixelRow[] = $bin[$i] === '1';
                }
            }
            $pixelGrid[] = $pixelRow;
        }

        return $pixelGrid;
    }
}
