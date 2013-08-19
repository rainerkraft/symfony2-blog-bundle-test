<?php
// src/Blogger/BlogBundle/Twig/Extensions/BloggerBlogExtension.php

namespace Blogger\BlogBundle\Twig\Extensions;

class BloggerBlogExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'created_ago' => new \Twig_Filter_Method($this, 'createdAgo'),
        );
    }

    public function createdAgo(\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();

        if ($delta < 0) {
            $delta = 0;
        }

        $duration = "";
        if ($delta < 10) {
            // Winks
            $duration = "just now";
        } elseif ($delta < 60) {
            // Seconds
            $time = $delta;
            $duration = $time . " second" . (($time > 1) ? "s" : "") . " ago";
        } elseif ($delta < 3600) {
            // Minutes
            $time = floor($delta / 60);
            $duration = $time . " minute" . (($time > 1) ? "s" : "") . " ago";
        } elseif ($delta < 86400) {
            // Hours
            $time = floor($delta / 3600);
            $duration = $time . " hour" . (($time > 1) ? "s" : "") . " ago";
        } elseif ($delta < 2592000) {
            // Days
            $time = floor($delta / 86400);
            $duration = $time . " day" . (($time > 1) ? "s" : "") . " ago";
        } elseif ($delta < 31536000) {
            // Months
            $time = floor($delta / 2592000);
            $duration = $time . " month" . (($time > 1) ? "s" : "") . " ago";
        } else {
            // Years
            $time = floor($delta / 31536000);
            $duration = $time . " year" . (($time > 1) ? "s" : "") . " ago";
        }

        return $duration;
    }

    public function getName()
    {
        return 'blogger_blog_extension';
    }
}