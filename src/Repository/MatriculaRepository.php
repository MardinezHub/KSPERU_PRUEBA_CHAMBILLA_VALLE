<?php

namespace App\Repository;

use App\Entity\Matricula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matricula>
 */
class MatriculaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matricula::class);
    }

    public function findAllWithCurso(): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.Curso', 'c') // Relación con la tabla Curso
            ->addSelect('c')      // Incluye los datos de Curso
            ->getQuery()
            ->getResult();
    }

    public function findByUsuario(int $usuarioId): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.Usuario = :usuario')
            ->setParameter('usuario', $usuarioId)
            ->getQuery()
            ->getResult();
    }

    public function findByCurso(int $cursoId): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.Curso = :curso')
            ->setParameter('curso', $cursoId)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Matricula[] Returns an array of Matricula objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Matricula
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
