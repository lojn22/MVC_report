<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 *
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
     * Find all producs having a value above the specified one.
     *
     * @return Book[] Returns an array of Product objects
     */
    public function findByMinimumValue($value): array
    {
        // return $this->createQueryBuilder('b')
        $result = $this->createQueryBuilder('b')
            ->andWhere('b.ISBN >= :value')
            ->setParameter('ISBN', $value)
            ->orderBy('b.ISBN', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return is_array($result) ? $result : [];
    }

    /**
     * Find all producs having a value above the specified one with SQL.
     *
     * @return array[] Returns an array of arrays (i.e. a raw data set)
     */
    public function findByMinimumValue2($value): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT * FROM library AS l
        WHERE l.ISBN >= :value
        ORDER BY l.value ASC
    ';

        $resultSet = $conn->executeQuery($sql, ['ISBN' => $value]);

        return $resultSet->fetchAllAssociative();
    }
}
