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
class SectionRepository extends ServiceEntityRepository
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
    
/*    public function findAllByCourse($id)
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
    }*/
    
    
    public function findAllByCourse($id)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Sections::class, 'sec');
        $selectClausule = $rsm->generateSelectClause(array('sec' => 't1'));
        
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
          SELECT '.$selectClausule.'
          FROM sub_tree t1
        ', $rsm);
        
        $query->setParameter(1, $id);

        return $query->getResult();
    }
    
    public function findLastInCourse($id)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Sections::class, 'sec');
        $selectClausule = $rsm->generateSelectClause(array('sec' => 't1'));
        
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
          SELECT '.$selectClausule.', @rownum := @rownum + 1 AS row_num
          FROM sub_tree t1, (SELECT @rownum := 0) r
          ORDER BY row_num DESC
          LIMIT 1
        ', $rsm);
        
        $query->setParameter(1, $id);
        
        return $query->getOneOrNullResult();
    }
    
    /**
     * Update many rows of table
     * 
     * @param mixed $criteria 
     * [id => [column => value,..],..]
     * id - id of row to change
     * column - column of row to change
     * value - new value set to column in row 
     */
    public function updateManyRows($criteria): void
    {
        /*
         * update sections s
         * 
         * set $column = $value where id = $id
         * 
         * 
         */
        
        $em = $this->getEntityManager();
        
        if(!is_array($criteria)){throw new \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;}
        
        $sql = 'UPDATE '.$this->getClassMetadata()->getTableName().' t1 SET ';
        $q = $em->createQuery();
        $parameterCount=0;
        foreach($criteria as $id => $content){
            if(!is_array($content)){throw new \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;}
     
            foreach($content as $column => $value){
                //dd([$id,$column,$value]);
                $sql .= 'SET '.$column.' = ? WHERE id = ? ';
                
                $q->setParameter(++$parameterCount, $value)
                  ->setParameter(++$parameterCount, $id);
            }
        }
        dd($sql);
        $q->setDQL($sql);
        $q->execute();
        
//
//
//            $em = $this->getEntityManager();
//
//        $count = 0;
//
//        foreach($content as $i => $content_id){
//
//            $q = $em->createQuery('update BRSPageBundle:Content c set c.display_order = ?1 where c.id = ?2')
//                    ->setParameter(1, $i)
//                    ->setParameter(2, $content_id);
//
//            $count += $q->execute();
//        }

        //return $count;
    }

/**
     * Update many rows of table
     * 
     * @param string $column - column of row to change
     * @param mixed $criteria 
     * [id => value,..]
     * id - id of row to change
     * value - new value set to column in row 
     */
    public function updateManyRowsInOneColumn($column,$criteria): void
    {
        $em = $this->getEntityManager();
        if(!is_array($criteria)){throw new \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;}
        $sql = 'UPDATE '.$this->getEntityName().' SET '.$column.' = (CASE ';
        $query = $em->createQuery();
        $nullSql = 'UPDATE '.$this->getClassMetadata()->getTableName().' SET '.$column.' = null WHERE ';
        $nullQuery = $em->createQuery();
        
        $parameterCount=0;
        $nullParameterCount=0;
        foreach($criteria as $id => $value){
            $sql .= 'WHEN id = ? THEN ? '; 
            $query->setParameter(++$parameterCount, $id)
                    ->setParameter(++$parameterCount, $value);
            
            $nullSql .= 'id = ? ';
            $nullQuery->setParameter(++$nullParameterCount, $id);
            if ($value != end($criteria)) { $nullSql .= 'OR '; }
        }
        $sql .= 'END)';
//        $query->setSQL($sql);
//        $nullQuery->setSQL($nullSql);
        $query->setDQL($sql);
        $nullQuery->setDQL($nullSql);
        $em->beginTransaction();
        try{
            //$nullQuery->execute();
            $query->execute();
            $em->commit();
        }catch(Exception $e){
            $em->rollback();
            throw $e;
        }
//
//
//            $em = $this->getEntityManager();
//
//        $count = 0;
//
//        foreach($content as $i => $content_id){
//
//            $q = $em->createQuery('update BRSPageBundle:Content c set c.display_order = ?1 where c.id = ?2')
//                    ->setParameter(1, $i)
//                    ->setParameter(2, $content_id);
//
//            $count += $q->execute();
//        }

        //return $count;
    }
}


//update sections set previous_section_id = null WHERE id in (1,2,3,4,8) 
//        
//update sections t1 set previous_section_id = (CASE when id = 1 then null when id = 2 then 1 when id = 3 then 2 when id = 4 then 3 when id = 8 then 4 end) 