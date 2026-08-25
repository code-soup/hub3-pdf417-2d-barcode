<?php

declare(strict_types=1);

namespace BigFish\PDF417\Encoders;

use BigFish\PDF417\EncoderInterface;

/**
 * Converts numbers to code words.
 *
 * Can encode: digits 0-9
 * Rate: 2.9 digits per code word.
 */
class NumberEncoder implements EncoderInterface
{
    public const SWITCH_CODE_WORD = 902;

    public function canEncode(string $char): bool
    {
        return 1 === preg_match('/^[0-9]$/', $char);
    }

    public function getSwitchCode(string $data): int
    {
        return self::SWITCH_CODE_WORD;
    }

    /**
     * @return array<int>
     */
    public function encode(string $digits, bool $addSwitchCode): array
    {
        if (!preg_match('/^[0-9]+$/', $digits)) {
            throw new \InvalidArgumentException("First parameter contains non-numeric characters.");
        }

        // Count the number of 44 character chunks
        $digitCount = strlen($digits);
        $chunkCount = ceil($digitCount / 44);

        $codeWords = [];

        if ($addSwitchCode) {
            $codeWords[] = self::SWITCH_CODE_WORD;
        }

        // Encode in chunks of 44 digits
        for ($i = 0; $i < $chunkCount; $i++) {
            $chunk = substr($digits, $i * 44, 44);

            $cws = $this->encodeChunk($chunk);

            // Avoid using array_merge
            foreach ($cws as $cw) {
                $codeWords[] = $cw;
            }
        }

        return $codeWords;
    }

    /**
     * @return array<int>
     */
    private function encodeChunk(string $chunk): array
    {
        $chunk = "1" . $chunk;

        $cws = [];
        while(bccomp($chunk, '0') > 0) {
            $cw = bcmod($chunk, '900');
            $chunk = bcdiv($chunk, '900', 0); // Integer division

            array_unshift($cws, (int) $cw);
        }

        return $cws;
    }
}
