<?php

namespace App\Tests;

use App\Services\Application;
use App\Services\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewTest extends \PHPUnit\Framework\TestCase
{
    public function testCanGetAViewResponse()
    {
        $application = new Application(
            new Request([], [], [], [], [], ['REQUEST_URI' => '/'])
        );

        $response = $application->run();

        $this->assertInstanceOf(Response::class, $response);

        $this->assertContains('<body>', (string) $response);
    }

    public function testFrontEndCanPlay()
    {
        $application = new Application(
            ($request = new Request(
                [],
                [
                    "board" => ($initial = [
                        ["O", "", ""],
                        ["", "X", ""],
                        ["", "", ""],
                    ]),
                    "column" => "2",
                    "row" => "2",
                    "player" => "X",
                ],
                [],
                [],
                [],
                ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/play']
            ))
        );

        $response = $application->run();

        $this->assertInstanceOf(Response::class, $response);

        $final = json_decode($response->getContent(), true)['board'];

        $this->assertNotEquals($initial, $final);

        $this->assertEquals(
            json_encode([["O", "", "O"], ["", "X", ""], ["", "", "X"]]),
            json_encode($final)
        );
    }
}
