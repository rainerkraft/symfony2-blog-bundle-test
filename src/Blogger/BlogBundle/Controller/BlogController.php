<?php
// src/Blogger/BlogBundle/Controller/BlogController

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller
 */
class BlogController extends Controller
{
	/**
	 * [showAction description]
	 * @param  int $id ID of the blog entry
	 * @return view
	 */
	public function showAction($id, $slug)
	{
		$em = $this->getDoctrine()->getManager();

		$blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);

		if (!$blog) {
			throw $this->createNotFoundException('Unable to find blog post.');
		}

		$comments = $em->getRepository('BloggerBlogBundle:Comment')
		               ->getCommentsForBlog($blog->getId());

		return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
			'blog' => $blog,
			'comments' => $comments
		));
	}
}
