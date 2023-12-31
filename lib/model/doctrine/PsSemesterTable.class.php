<?php

/**
 * PsSemesterTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsSemesterTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PsSemesterTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PsSemester');
    }
    
    public function getSemesterConfig($ps_school_year_id, $ps_customer_id, $ps_workplace_id) {
    	
    	$q = $this->createQuery ( 'pm' )
    	->select ( "pm.*" )->addSelect("CONCAT(u.first_name, ' ', u.last_name) AS updated_by")
        ->addWhere ( 'pm.ps_workplace_id = ?', $ps_workplace_id )
        ->andWhere ( 'pm.ps_customer_id = ?', $ps_customer_id )
        ->andWhere ( 'pm.school_year_id = ?', $ps_school_year_id );
        $q->leftJoin ( 'pm.UserUpdated u' );
        
        return $q->fetchOne();
    }
    
}