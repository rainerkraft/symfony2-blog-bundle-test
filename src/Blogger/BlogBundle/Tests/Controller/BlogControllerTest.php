<?php
// src/Blogger/BlogBundle/Tests/Controller/BlogControllerTest.php

namespace Blogger\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testAddBlogComment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/1/a-day-with-symfony2');

        $this->assertEquals(1, $crawler->filter('h2:contains("A day with Symfony2")')->count());

        // Select based on button value
        $form = $crawler->selectButton('Submit')->form();

        $crawler = $client->submit($form, array(
            'blogger_blogbundle_commenttype[user]' => "testname",
            'blogger_blogbundle_commenttype[comment]' => "testcomment"
        ));


        // Need to follow redirect
        $crawler = $client->followRedirect();

        // Check comment is now displaying on page, as the last entry. This ensures comments are posted
        // in order of oldest to newest
        $articleCrawler = $crawler->filter('section .previous-comments article')->first();

        $this->assertEquals('testname', $articleCrawler->filter('header span.highlight')->text());
        $this->assertEquals('testcomment', $articleCrawler->filter('p')->last()->text());

        // Check the sidebar to ensure latest comment is displayed
        $this->assertEquals('testname', $crawler->filter('aside.sidebar section')->last()
            ->filter('article')->first()
            ->filter('header span.highlight')->text());
    }
}
