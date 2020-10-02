<?php

namespace App\Repository;

use App\Entity\Sections;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Sections|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sections|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sections[]    findAll()
 * @method Sections[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sections::class);
    }

    // /**
    //  * @return Sections[] Returns an array of Sections objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sections
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    public function findAllByCourse($id)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(\App\Entity\Sections::class, 'sec');

        $rsm->addFieldResult('sec','id','id');
        $rsm->addFieldResult('sec','name','name');
        $rsm->addJoinedEntityResult(\App\Entity\Courses::class, 'co', 'sec', 'course');
        $rsm->addMetaResult('co', 'course_id', 'course');
        
        
        //$rsm->addJoinedEntityResult(\App\Entity\Sections::class, 'sec1', 'sec', 'previousSection');
        
        //$rsm->addFieldResult('sec','course_id','course');
        //$rsm->addFieldResult('sec','previous_section_id','previousSection');
        //$rsm->addEntityResult(\App\Entity\Courses::class,'sec','course');
        //$rsm->addEntityResult(Sections::class,'sec','previous_section');
        
        
        
//        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->getEntityManager());
//        $rsm->addRootEntityFromClassMetadata(Sections::class, 'sec');
//        $rsm->add
        
        $query = $this->_em->createNativeQuery('
            WITH RECURSIVE sub_tree AS (
            SELECT *
            FROM '.$this->getClassMetadata()->getTableName().'
            WHERE previous_section_id is null
                  and course_id = ?

            UNION ALL

            SELECT sec.*
            FROM '.$this->getClassMetadata()->getTableName().' sec, sub_tree st
            WHERE sec.previous_section_id = st.id
          )
          SELECT id, name, course_id, previous_section_id FROM sub_tree
        ', $rsm);

        $query->setParameter(1, $id);

        return $query->getResult();
    }
}
