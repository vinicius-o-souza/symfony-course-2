<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Video::class);
        $this->paginator = $paginator;
    }
    
    public function findAllPaginated($page)
    {
        $dbQuery = $this->createQueryBuilder('v')->getQuery();
        
        $pagination = $this->paginator->paginate($dbQuery, $page, 5);
        return $pagination;
    }
    
    public function findByChildIds(array $value, int $page, ?string $sortMethod)
    {
        if ($sortMethod != 'rating') {
            $dbQuery = $this->createQueryBuilder('v')
              ->andWhere('v.category IN (:val)')
              ->leftJoin('v.comments', 'c')
              ->leftJoin('v.usersThatLike', 'l')
              ->leftJoin('v.usersThatDontLike', 'd')
              ->addSelect('c', 'l', 'd')
              ->setParameter('val', $value)
              ->orderBy('v.title', $sortMethod);
        } else {
            $dbQuery = $this->createQueryBuilder('v')
              ->addSelect('COUNT(l) AS HIDDEN likes')
              ->leftJoin('v.usersThatLike', 'l')
              ->andWhere('v.category IN (:val)')
              ->setParameter('val', $value)
              ->groupBy('v')
              ->orderBy('likes', 'DESC');
        }
        $dbQuery->getQuery();
        
        return $this->paginator->paginate($dbQuery, $page, Video::perPage);
    }
    
    public function findByTitle(string $query, int $page, ?string $sortMethod)
    {        
        $queryBuilder = $this->createQueryBuilder('v');
        $searchTerms = $this->prepareQuery($query);
        
        foreach($searchTerms as $key => $term) {
            $queryBuilder->orWhere('v.title LIKE :t_'. $key)
                ->setParameter('t_'. $key, '%'. trim($term) . '%');
        }

        if ($sortMethod != 'rating') {
            $dbQuery = $queryBuilder
                ->orderBy('v.title', $sortMethod)
                ->leftJoin('v.comments', 'c')
                ->leftJoin('v.usersThatLike', 'l')
                ->leftJoin('v.usersThatDontLike', 'd')
                ->addSelect('c', 'l', 'd');
        } else {
            $dbQuery = $queryBuilder
              ->addSelect('COUNT(l) AS HIDDEN likes', 'c')
              ->leftJoin('v.usersThatLike', 'l')
              ->leftJoin('v.comments', 'c')
              ->groupBy('v', 'c')
              ->orderBy('likes', 'DESC');
        }
        $dbQuery->getQuery();

        return $this->paginator->paginate($dbQuery, $page, Video::perPage);
    }
    
    public function videoDetails(int $id)
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.comments', 'c')
            ->leftJoin('c.user', 'u')
            ->addSelect('c', 'u')
            ->where('v.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    private function prepareQuery(string $query): array
    {
        $terms = array_unique(explode(' ', $query));
        return array_filter($terms, function($term) {
            return 2 <= mb_strlen($term);
        });
    }

}
