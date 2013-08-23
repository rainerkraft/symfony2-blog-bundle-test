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

    public function testContact()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->baseUrl . '/contact');

        $this->assertEquals(1, $crawler->filter('h1:contains("Contact symblog")')->count());

        // Select based on button value, or id or name for buttons
        $form = $crawler->selectButton('Submit')->form();

        $form['blogger_blogbundle_enquirytype[name]'] = 'name';
        $form['blogger_blogbundle_enquirytype[email]'] = 'email@email.com';
        $form['blogger_blogbundle_enquirytype[subject]'] = 'Subject';
        $form['blogger_blogbundle_enquirytype[body]'] = 'The comment body must be at least 50 characters long as '
            . 'there is a validation constraint on the Enquiry entity';

        $client->submit($form);

        // Check email has been sent
        if ($profile = $client->getProfile()) {
            $swiftMailerProfiler = $profile->getCollector('swiftmailer');

            // Only 1 message should have been sent
            $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

            // Get the first message
            $messages = $swiftMailerProfiler->getMessages();
            $message = array_shift($messages);

            $symblogEmail = $client->getContainer()->getParameter('blogger_blog.emails.contact_email');
            // Check message is being sent to correct address
            $this->assertArrayHasKey($symblogEmail, $message->getTo());
        }

        // Need to follow redirect
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.blogger-notice:contains("Your contact enquiry was successfully '
            . 'sent. Thank you' . '!")')->count());
    }
}
