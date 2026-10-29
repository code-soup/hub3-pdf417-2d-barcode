<?php

declare(strict_types=1);

namespace BigFish\PDF417;

/**
 * Encodes data into PDF417 code words.
 *
 * This is the top level data encoder which assigns encoding to lower level
 * (byte, number, text) encoders.
 */
class DataEncoder
{
    /** @var list<EncoderInterface> */
    private array $encoders;

    private EncoderInterface $defaultEncoder;

    public function __construct()
    {
        // Encoders sorted in order of preference
        $this->encoders = [
            new Encoders\NumberEncoder(),
            new Encoders\TextEncoder(),
            new Encoders\ByteEncoder(),
        ];

        // Default mode is Text
        $this->defaultEncoder = $this->encoders[1];
    }

    /**
     * Encodes given data into an array of PDF417 code words.
     *
     * @return list<int>
     */
    public function encode(string $data): array
    {
        $chains = $this->splitToChains($data);

        // Add a switch code at the beginning if the first encoder to be used
        // is not the text encoder. Decoders by default start decoding as text.
        $firstEncoder = $chains[0][1];
        $addSwitchCode = (!($firstEncoder instanceof Encoders\TextEncoder));

        $codes = [];
        foreach ($chains as [$chain, $encoder]) {
            $encoded = $encoder->encode($chain, $addSwitchCode);
            foreach ($encoded as $code) {
                $codes[] = $code;
            }

            $addSwitchCode = true;
        }

        return $codes;
    }

    /**
     * @return list<array{0: string, 1: EncoderInterface}>
     */
    private function splitToChains(string $data): array
    {
        $chain = '';
        $chains = [];
        $encoder = $this->defaultEncoder;

        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $char = $data[$i];

            $newEncoder = $this->getEncoder($char);
            if ($newEncoder !== $encoder) {
                // Save & reset chain if not empty
                if ($chain !== '') {
                    $chains[] = [$chain, $encoder];
                    $chain = '';
                }

                $encoder = $newEncoder;
            }

            $chain .= $char;
        }

        if ($chain !== '') {
            $chains[] = [$chain, $encoder];
        }

        return $chains;
    }

    public function getEncoder(string $char): EncoderInterface
    {
        foreach ($this->encoders as $encoder) {
            if ($encoder->canEncode($char)) {
                return $encoder;
            }
        }

        $ord = ord($char);
        throw new \InvalidArgumentException("Cannot encode character $char (ASCII $ord)");
    }
}
