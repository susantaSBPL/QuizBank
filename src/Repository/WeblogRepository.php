<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Repository;

use App\Entity\Weblog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class WeblogRepository
 *
 * @method Weblog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weblog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weblog[]    findAll()
 * @method Weblog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeblogRepository extends ServiceEntityRepository
{
    /**
     * WeblogRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weblog::class);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $query = $this->getEntityManager()->createQueryBuilder()
                ->select('w')->from(Weblog::class, 'w')->orderBy('w.page, w.datestamp', 'ASC');

        return $query->getQuery()->execute();
    }
}
