<?php
// src/Blogger/BlogBundle/Tests/Entity/BlogTest.php

namespace Blogger\BlogBundle\Tests\Entity;

use Blogger\BlogBundle\Entity\Blog;

class BlogTest extends \PHPUnit_Framework_TestCase
{
    public function testSlugify()
    {
        $blog = new Blog();

        $this->assertEquals('hello-world', $blog->slugify('Hello World'));
        $this->assertEquals('a-day-at-the-races', $blog->slugify('A Day! At The Races'));
        $this->assertEquals('a-no-ther-tes-t', $blog->slugify('A&no!ther        tes;;;;;;;t?'));
    }

    public function testSetSlug()
    {
        $blog = new Blog();

        $blog->setSlug('Symfony2 Blog');
        $this->assertEquals('symfony2-blog', $blog->getSlug());
    }

    public function testSetTitle()
    {
        $blog = new Blog();

        $blog->setTitle('Such a perfect day!');
        $this->assertEquals('such-a-perfect-day', $blog->getSlug());
    }
}
