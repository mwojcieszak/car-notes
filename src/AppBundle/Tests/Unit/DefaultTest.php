<?php

namespace AppBundle\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultTest extends WebTestCase
{
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetMakes()
    {
        $makes = $this->em->getRepository('AppBundle:Make')->getMakes();

        $this->assertEquals(14, count($makes));
    }

    public function testGetNotes()
    {
        $client = static::createClient();
        $client->insulate();

        $client->request('GET', '/notes/123');

        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
    }

    public function testCreateValidNote()
    {
        $client = static::createClient();
        $client->insulate();

        $client->request('POST', '/notes/create', [], [], [], $this->validJsonNote());

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 201);
    }

    public function testCreateInvalidNote()
    {
        $client = static::createClient();
        $client->insulate();

        $client->request('POST', '/notes/create', [], [], [], $this->invalidJsonNote());

        $response = $client->getResponse();

        $this->assertJsonResponse($response, 400);
    }

    private function validJsonNote() {
        return json_encode([
            'title' => 'Testowa notatka',
            'content' => 'Test123',
            'priority' => 1,
            'models' => [13]

        ]);
    }

    private function invalidJsonNote() {
        return json_encode([
            'title' => 'Testowa notatka',
            'content' => 'Test123'
        ]);
    }

    private function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
}
