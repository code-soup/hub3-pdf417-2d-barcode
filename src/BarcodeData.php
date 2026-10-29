<?php

declare(strict_types=1);

namespace BigFish\PDF417;

/**
 * Container class which holds all data needed to render a PDF417 bar code.
 */
class BarcodeData
{
    /** Width in modules of each PDF417 cluster pattern (ISO/IEC 15438). */
    private const MODULES_PER_SYMBOL = 17;

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
                $bin = sprintf(
                    '%0' . self::MODULES_PER_SYMBOL . 'b',
                    $value & ((1 << self::MODULES_PER_SYMBOL) - 1)
                );
                for ($i = 0; $i < self::MODULES_PER_SYMBOL; $i++) {
                    $pixelRow[] = $bin[$i] === '1';
                }
            }
            $pixelGrid[] = $pixelRow;
        }

        return $pixelGrid;
    }
}
