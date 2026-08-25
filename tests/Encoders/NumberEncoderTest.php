<?php

namespace BigFish\PDF417\Tests\Encoders;

use BigFish\PDF417\Encoders\NumberEncoder;
use PHPUnit\Framework\TestCase;

/**
 * @group encoders
 */
class NumberEncoderTest extends TestCase
{
    public function testCanEncode()
    {
        $ne = new NumberEncoder();

        $this->assertTrue($ne->canEncode("0"));
        $this->assertTrue($ne->canEncode("1"));
        $this->assertTrue($ne->canEncode("2"));
        $this->assertTrue($ne->canEncode("3"));
        $this->assertTrue($ne->canEncode("4"));
        $this->assertTrue($ne->canEncode("5"));
        $this->assertTrue($ne->canEncode("6"));
        $this->assertTrue($ne->canEncode("7"));
        $this->assertTrue($ne->canEncode("8"));
        $this->assertTrue($ne->canEncode("9"));

        $this->assertFalse($ne->canEncode(""));
        $this->assertFalse($ne->canEncode("a"));
        $this->assertFalse($ne->canEncode("abc"));
        $this->assertFalse($ne->canEncode("123"));
    }

    public function testGetSwitchCode()
    {
        $ne = new NumberEncoder();
        $sw = NumberEncoder::SWITCH_CODE_WORD;

        $this->assertSame($sw, $ne->getSwitchCode("123"));
        $this->assertSame($sw, $ne->getSwitchCode("foo"));
        $this->assertSame($sw, $ne->getSwitchCode("bar"));
    }

    public function testEncode()
    {
        $ne = new NumberEncoder();

        $actual = $ne->encode("01234", true);
        $expected = [902, 112, 434];
        $this->assertSame($expected, $actual);

        $actual = $ne->encode("01234", false);
        $expected = [112, 434];
        $this->assertSame($expected, $actual);
    }

    public function testInvalidInput1()
    {
        $this->expectException(\TypeError::class);
        $ne = new NumberEncoder();
        $ne->encode([], true);
    }

    public function testInvalidInput2()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First parameter contains non-numeric characters.');
        $ne = new NumberEncoder();
        $ne->encode("foo", true);
    }
}
