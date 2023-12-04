<?php

/**
 * PsReviewRelativeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsReviewRelativeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PsReviewRelativeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PsReviewRelative');
    }
    public function doSelectQuery(Doctrine_Query $query)
    {

        $a = $query->getRootAlias();

        $query->select (
            $a . '.*, ' .
            'p.title AS cate_review_name, ' .
            'c.school_name AS school_name, ' .
            'w.title as title_workplace,' .
            'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

            $query->leftJoin ( $a . '.UserUpdated u' );
            $query->leftJoin ( $a . '.PsCustomer c');
            $query->leftJoin ( $a . '.PsWorkPlaces w');
            $query->leftJoin ( $a . '.PsCategoryReview p');

         return $query;
    }
}