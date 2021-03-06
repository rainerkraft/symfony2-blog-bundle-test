<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

/**
 * Class PageController main page controller
 * @package Blogger\BlogBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Homepage controller
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()
                   ->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    /**
     * About page controller
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
    	return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    /**
     * Contact page controller (also handles form submissions)
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $enquiry= new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        if ($request->getMethod() === "POST") {
            
            $form->handleRequest($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom($this->container->getParameter('blogger_blog.emails.contact_email_sender'))
                    ->setTo($this->container->getParameter('blogger_blog.emails.contact_email_recipient'))
                    ->setBody($this->renderView('BloggerBlogBundle:Email:contactEmail.txt.twig', array(
                        'enquiry' => $enquiry
                    )));

                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add(
                    'blogger-notice',
                    'Your contact enquiry was successfully sent. Thank you!'
                );

                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
            }
        }

    	return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Controller for showing sidebar content
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sidebarAction()
    {
        $em = $this->getDoctrine()
                   ->getManager();

        $tags = $em->getRepository('BloggerBlogBundle:Blog')
                   ->getTags();

        $tagWeights = $em->getRepository('BloggerBlogBundle:Blog')
                         ->getTagWeights($tags);

        $commentLimit = $this->container
                             ->getParameter('blogger_blog.comments.latest_comment_limit');

        $latestComments = $em->getRepository('BloggerBlogBundle:Comment')
                             ->getLatestComments($commentLimit);

        return $this->render('BloggerBlogBundle:Page:sidebar.html.twig', array(
            'tags' => $tagWeights,
            'latestComments' => $latestComments
        ));
    }
}
