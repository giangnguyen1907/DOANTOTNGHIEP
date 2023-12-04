<?php

/**
 * PsReduceYourselfTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsReduceYourselfTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PsReduceYourselfTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PsReduceYourself');
    }
    public function doSelectQuery(Doctrine_Query $query)
    {

        $a = $query->getRootAlias();

        $query->select (
            $a . '.*, ' .
            'c.school_name AS school_name, ' .
            'w.title as title_workplace,' .
            'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

            $query->leftJoin ( $a . '.UserUpdated u' );
            $query->leftJoin ( $a . '.PsCustomer c');
            $query->leftJoin ( $a . '.PsWorkPlaces w');

        return $query;
    }
}