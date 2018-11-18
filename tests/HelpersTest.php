<?php

namespace App\Tests;

class HelpersTest extends \PHPUnit\Framework\TestCase
{
    public function testCanInferOpponent()
    {
        $this->assertEquals('X', infer_opponent('O'));

        $this->assertEquals('O', infer_opponent('X'));
    }
}
