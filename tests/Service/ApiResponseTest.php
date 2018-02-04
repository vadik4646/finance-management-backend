<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
  public function testGetNoConfigured()
  {
    $response = (new ApiResponse())->get();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals('{}', $response->getContent());
    $this->assertEquals('application/json', $response->headers->get('Content-Type'));
  }

  public function testAllConfiguration()
  {
    $response = (new ApiResponse())
      ->setCode(400)
      ->setAuthenticationToken('token')
      ->appendData(['someData' => 'some'])
      ->setMessage('test message')
      ->get();

    $this->assertEquals(400, $response->getStatusCode());
    $content = json_decode($response->getContent());
    $this->assertTrue($response->headers->has('Content-Type'));
    $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    $this->assertEquals('X-AUTH-TOKEN', $response->headers->getCookies()[0]->getName());
    $this->assertEquals('token', $response->headers->getCookies()[0]->getValue());
    $this->assertEquals('token', $response->headers->get('X-AUTH-TOKEN'));
    $this->assertEquals('some', $content->data->someData);
    $this->assertEquals('test message', $content->message);
  }
}
