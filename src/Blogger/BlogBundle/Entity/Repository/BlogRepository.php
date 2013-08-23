<?php
// src/Blogger/BlogBundle/Entity/Repository/BlogRepository.php

namespace Blogger\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Repository class containing blog related methods
 * Class BlogRepository
 * @package Blogger\BlogBundle\Entity\Repository
 */
class BlogRepository extends EntityRepository
{
    /**
     * Gets a number ($limit) of blog posts reverse ordered by creation date
     * @param null $limit
     * @return array
     */
    public function getLatestBlogs($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
                   ->select('b', 'c')
                   ->leftJoin('b.comments', 'c')
                   ->addOrderBy('b.created', 'DESC');

        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }

    /**
     * Gets all tags on blog posts
     * @return array
     */
    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
                         ->select('b.tags')
                         ->getQuery()
                         ->getResult();

        $tags = array();
        foreach ($blogTags as $blogTag) {
            $tags = array_merge(explode(",", $blogTag['tags']), $tags);
        }
        foreach ($tags as &$tag) {
            $tag = trim($tag);
        }

        return $tags;
    }

    /**
     * Calculates and returns weighting on tags based on abundance
     * @param $tags
     * @return array
     */
    public function getTagWeights($tags)
    {
        $tagWeights = array();
        if (empty($tags)) {
            return $tagWeights;
        }

        foreach ($tags as $tag) {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }

        uksort($tagWeights, function() {
            return rand() > rand();
        });

        $max = max($tagWeights);

        $multiplier = ($max > 5) ? 5 / $max : 1;
        foreach ($tagWeights as $tag) {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }
}
