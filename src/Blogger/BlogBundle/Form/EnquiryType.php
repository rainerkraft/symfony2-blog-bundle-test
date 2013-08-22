<?php
// src/Blogger/BlogBundle/Form/EnquiryType.php

namespace Blogger\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Type to hold the contact form
 *
 * Class EnquiryType
 * @package Blogger\BlogBundle\Form
 */
class EnquiryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name');
		$builder->add('email','email');
		$builder->add('subject');
		$builder->add('body', 'textarea');
	}

	public function getName()
	{
		return 'contact';
	}
}