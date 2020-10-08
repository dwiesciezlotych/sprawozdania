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
//        $rsm = new ResultSetMapping();
//        $rsm->addEntityResult(\App\Entity\Sections::class, 'sec');
//
//        $rsm->addFieldResult('sec','id','id');
//        $rsm->addFieldResult('sec','name','name');
//        $rsm->addMetaResult('sec', 'course_id', 'course');
        //$rsm->addJoinedEntityResult(\App\Entity\Courses::class, 'co', 'sec', 'course');
//        $rsm->addFieldResult('co', 'id', 'id');
//        $rsm->addFieldResult('co', 'name', 'name');
//        $rsm->addFieldResult('co', 'description', 'description');
//        $rsm->addFieldResult('co', 'password', 'password');
        
        
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Sections::class, 'sec');
        $rsm->addJoinedEntityFromClassMetadata(\App\Entity\Courses::class, 'co', 'sec', 'course', array('id' => 'address_id','name' => 'co_name'));
        
//        $query = $this->_em->createNativeQuery('
//            WITH RECURSIVE sub_tree AS (
//            SELECT *
//            FROM '.$this->getClassMetadata()->getTableName().'
//            WHERE previous_section_id is null
//                  AND course_id = ?
//
//            UNION ALL
//
//            SELECT sec.*
//            FROM '.$this->getClassMetadata()->getTableName().' sec, sub_tree st
//            WHERE sec.previous_section_id = st.id
//          )
//          SELECT id, name, course_id, previous_section_id FROM sub_tree
//        ', $rsm);
        
        $query = $this->_em->createNativeQuery('
            WITH RECURSIVE sub_tree AS (
            SELECT * 
            FROM '.$this->getClassMetadata()->getTableName().'
            WHERE previous_section_id is null
                  AND course_id = ?

            UNION ALL

            SELECT sec.*
            FROM '.$this->getClassMetadata()->getTableName().' sec, sub_tree st
            WHERE sec.previous_section_id = st.id
          )
          SELECT st.id, st.name, co.id as course_id, co.name as co_name, co.description, co.password, co.category_id
          FROM sub_tree st
          INNER JOIN courses co
          ON co.id = st.course_id;
        ', $rsm);

        $query->setParameter(1, $id);

        return $query->getResult();
    }
}
