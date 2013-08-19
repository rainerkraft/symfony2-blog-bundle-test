<?php
// src/Blogger/BlogBundle/Tests/Twig/Extensions/BloggerBlogExtensionTest.php

namespace Blogger\BlogBundle\Tests\Twig\Extensions;

use Blogger\BlogBundle\Twig\Extensions\BloggerBlogExtension;

class BloggerBlogExtensionTest extends \PHPUnit_Framework_TestCase
{
	public function testCreatedAgo()
	{
		$blog = new BloggerBlogExtension();

		$this->assertEquals('just now', $blog->createdAgo(new \DateTime()));
		$this->assertEquals("34 seconds ago", $blog->createdAgo($this->getDateTime(-34)));
        $this->assertEquals("1 minute ago", $blog->createdAgo($this->getDateTime(-60)));
        $this->assertEquals("2 minutes ago", $blog->createdAgo($this->getDateTime(-120)));
        $this->assertEquals("1 hour ago", $blog->createdAgo($this->getDateTime(-3600)));
        $this->assertEquals("1 hour ago", $blog->createdAgo($this->getDateTime(-3601)));
        $this->assertEquals("2 hours ago", $blog->createdAgo($this->getDateTime(-7200)));
        $this->assertEquals("1 day ago", $blog->createdAgo($this->getDateTime(-86400)));
        $this->assertEquals("2 days ago", $blog->createdAgo($this->getDateTime((86400*2)*-1)));
        $this->assertEquals("1 month ago", $blog->createdAgo($this->getDateTime(-2592000)));
        $this->assertEquals("2 months ago", $blog->createdAgo($this->getDateTime((2592000*2)*-1)));
        $this->assertEquals("1 year ago", $blog->createdAgo($this->getDateTime(-31536000)));
        $this->assertEquals("2 years ago", $blog->createdAgo($this->getDateTime((31536000*2)*-1)));
	}

	protected function getDateTime($delta)
    {
        return new \DateTime(date("Y-m-d H:i:s", time()+$delta));
    }
}
