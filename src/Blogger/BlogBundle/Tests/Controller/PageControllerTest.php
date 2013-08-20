<?php
// src/Blogger/BlogBundle/Tests/Controller/PageControllerTest.php

namespace Blogger\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// WTF?????
ini_set('xdebug.max_nesting_level', 1000);

class PageControllerTest extends WebTestCase
{
    protected $baseUrl = "http://www.symfony.local:8888";

    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->baseUrl . '/about');

        // Check that the About Page contains a H1 tag with text "About symblog"
        $this->assertEquals(1, $crawler->filter('h1:contains("About symblog")')->count());
    }

    public function testHomepageBlogEntries()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->baseUrl . '/');

        // Check that the homepage contains blog entries
        $this->assertTrue($crawler->filter('article.blog')->count() > 0);
    }

    public function testHomepageClickBlogLink()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->baseUrl . '/');

        $blogLink = $crawler->filter('article.blog h2 a')->first();
        $blogTitle = $blogLink->text();

        $crawler = $client->click($blogLink->link());

        // Check that the H2 has the blog title in it
        $this->assertEquals(1, $crawler->filter('article.blog h2:contains("' . $blogTitle . '")')->count());
    }
}
