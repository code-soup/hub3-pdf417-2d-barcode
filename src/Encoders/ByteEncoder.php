<?php

declare(strict_types=1);

namespace BigFish\PDF417\Encoders;

use BigFish\PDF417\EncoderInterface;

/**
 * Converts a byte array to code words.
 *
 * Can encode: ASCII 0-255
 * Rate: 1.2 bytes per code word.
 *
 * Encoding process converts chunks of 6 bytes to 5 code words in base 900.
 */
class ByteEncoder implements EncoderInterface
{
    public const SWITCH_CODE_WORD = 901;
    public const SWITCH_CODE_WORD_ALT = 924;

    public function canEncode(string $char): bool
    {
        // Can encode any character
        return strlen($char) === 1;
    }

    public function getSwitchCode(string $data): int
    {
        return (strlen($data) % 6 === 0) ? self::SWITCH_CODE_WORD_ALT : self::SWITCH_CODE_WORD;
    }

    /**
     * @return array<int>
     */
    public function encode(string $bytes, bool $addSwitchCode): array
    {

        // Count the number of 6 character chunks
        $byteCount = strlen($bytes);
        $chunkCount = ceil($byteCount / 6);

        $codeWords = [];

        if ($addSwitchCode) {
            $codeWords[] = $this->getSwitchCode($bytes);
        }

        // Encode in chunks of 6 bytes
        for ($i = 0; $i < $chunkCount; $i++) {
            $chunk = substr($bytes, $i * 6, 6);

            if (strlen($chunk) === 6) {
                $cws = $this->encodeChunk($chunk);
            } else {
                $cws = $this->encodeIncompleteChunk($chunk);
            }

            // Avoid using array_merge
            foreach ($cws as $cw) {
                $codeWords[] = $cw;
            }
        }

        return $codeWords;
    }

    /**
     * Takes a chunk of 6 bytes and encodes it to 5 code words.
     *
     * @return array<int>
     */
    private function encodeChunk(string $chunk): array
    {
        $sum = "0";
        for ($i = 0; $i < 6; $i++) {
            $char = substr($chunk, 5 - $i, 1);
            $val = bcmul(bcpow('256', (string) $i), (string) ord($char));
            $sum = bcadd($sum, $val);
        }

        $cws = [];
        while(bccomp($sum, '0') > 0) {
            $cw = bcmod($sum, '900');
            $sum = bcdiv($sum, '900', 0); // Integer division

            array_unshift($cws, (int) $cw);
        }

        return $cws;
    }

    /**
     * Takes a chunk of less than 6 bytes and encodes it.
     *
     * @return array<int>
     */
    private function encodeIncompleteChunk(string $chunk): array
    {
        $cws = [];

        for ($i = 0; $i < strlen($chunk); $i++) {
            $cws[] = ord($chunk[$i]);
        }

        return $cws;
    }
}
