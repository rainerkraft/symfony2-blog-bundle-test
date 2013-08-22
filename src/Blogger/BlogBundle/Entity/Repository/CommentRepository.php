<?php

namespace Blogger\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Repository class containing comment related methods
 * Class CommentRepository
 * @package Blogger\BlogBundle\Entity\Repository
 */
class CommentRepository extends EntityRepository
{
    /**
     * Returns all comments for a given blog ID
     * @param $blogId
     * @param bool $approved
     * @return array
     */
    public function getCommentsForBlog($blogId, $approved = true)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->where('c.blog = :blog_id')
                   ->addOrderBy('c.created', 'DESC')
                   ->setParameter('blog_id', $blogId);

        if (false === is_null($approved)) {
            $qb->andWhere('c.approved = :approved')
               ->setParameter('approved', $approved);
        }

        return $qb->getQuery()
                  ->getResult();
    }

    /**
     * Gets a number ($limit) of comments reverse ordered by creation date
     * @param int $limit
     * @return array
     */
    public function getLatestComments($limit = 10)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->addOrderBy('c.created', 'DESC');

        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
                  ->getResult();
    }
}
