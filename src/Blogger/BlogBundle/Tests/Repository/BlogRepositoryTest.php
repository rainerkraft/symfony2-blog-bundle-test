<?php
// src/Blogger/BlogBundle/Tests/Repository/BlogRepositoryTest.php

namespace Blogger\BlogBundle\Tests\Repository;

use Blogger\BlogBundle\Entity\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogRepositoryTest extends WebTestCase
{
    /**
     * @var \Blogger\BlogBundle\Entity\Repository\BlogRepository
     */
    private $blogRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->blogRepository = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('BloggerBlogBundle:Blog');
    }

    public function testGetTags()
    {
        $tags = $this->blogRepository->getTags();

        $this->assertTrue(count($tags) > 1);
        $this->assertContains('symblog', $tags);
    }

    public function testGetTagWeights()
    {
        $tagWeights = $this->blogRepository->getTagWeights(array(
           'php', 'code', 'code', 'symblog', 'blog'
        ));

        $this->assertTrue(count($tagWeights) > 1);

        // Test case where count is over max weight of 5
        $tagWeights = $this->blogRepository->getTagWeights(
            array_fill(0, 10, 'php')
        );

        $this->assertTrue(count($tagWeights) >= 1);
    }
}