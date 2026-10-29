<?php

declare(strict_types=1);

namespace BigFish\PDF417\Tests;

use BigFish\PDF417\BarcodeData;
use BigFish\PDF417\PDF417;
use PHPUnit\Framework\TestCase;

class BarcodeDataTest extends TestCase
{
    public function testGetPixelGridPadsClusterPatternsToSeventeenModules(): void
    {
        $data = new BarcodeData();
        $data->codes = [[0x102]];

        $grid = $data->getPixelGrid();

        $this->assertCount(1, $grid);
        $this->assertCount(17, $grid[0]);

        $expected = array_map(
            static fn (string $char): bool => $char === '1',
            str_split(sprintf('%017b', 0x102))
        );
        $this->assertSame($expected, $grid[0]);
    }

    public function testEncodedHub3SlipRowsHaveExpectedModuleWidth(): void
    {
        $payload = "HRVHUB30\nHRK\n000000010000000\nIvan Habunek\nSavska cesta 13\n10000 Zagreb\nBig Fish Software d.o.o.\nSavska cesta 13\n10000 Zagreb\nHR6623400091110651272\n00\nHR123456\nANTS\nRazvoj paketa za bar kodove\n";

        $barcode = (new PDF417())->encode($payload);
        $modulesPerRow = ($barcode->columns + 4) * 17;

        foreach ($barcode->getPixelGrid() as $row) {
            $this->assertCount($modulesPerRow, $row);
        }
    }
}
