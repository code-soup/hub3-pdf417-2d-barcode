<?php

declare(strict_types=1);

namespace BigFish\PDF417;

interface EncoderInterface
{
    /**
     * Checks whether the given character can be encoded using this encoder.
     */
    public function canEncode(string $char): bool;

    /**
     * Encodes a string into codewords.
     *
     * @param bool $addSwitchCode Whether to add the mode switch code at the beginning.
     * @return array<int> An array of code words.
     * @throws \InvalidArgumentException If any of the characters cannot be encoded
     */
    public function encode(string $string, bool $addSwitchCode): array;

    /**
     * Returns the switch code word for the encoding mode implemented by the encoder.
     */
    public function getSwitchCode(string $data): int;
}