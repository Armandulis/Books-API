<?php

namespace App\Tests\Controller\V2;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BookControllerTest extends WebTestCase{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v2/book');

        self::assertResponseIsSuccessful();
    }
}
