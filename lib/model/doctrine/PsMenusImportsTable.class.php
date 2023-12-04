<?php

/**
 * PsMenusImportsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsMenusImportsTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object PsMenusImportsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PsMenusImports');
    }
    
    public function doSelectQuery(Doctrine_Query $query) {
    	
    	$a = $query->getRootAlias ();
    	
    	$query->select ( $a . '.id AS id_menu, ' . $a . '.description AS description, ' . $a . '.date_at AS date_at, ' . $a . '.ps_object_group_id AS ps_object_group_id, og.title AS object_group_title, ' . $a . '.ps_customer_id AS ps_customer_id, ' . 'cus.title AS customer_title,' . $a . '.ps_meal_id AS ps_meal_id, M.title AS meal_title, ' . $a . '.user_updated_id AS user_updated_id, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );
    	$query->leftJoin ( $a . '.PsCustomer cus' );
    	$query->leftJoin ( $a . '.UserUpdated u' );
    	$query->leftJoin ( $a . '.PsMeals M' );
    	$query->leftJoin ( $a . '.PsObjectGroups og' );
    	
    	if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0)
    		$query->orWhere ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );
    		else
    			$query->orWhere ( '1=1' );
    			
    			return $query;
    }
    
    public function getListMenuWeek($date_from, $date_to, $ps_customer_id, $ps_object_group_id, $ps_workplace_id = null) {
        
        //echo $date_from.$date_to.$ps_customer_id.$ps_object_group_id.$ps_workplace_id;die;
        $q = $this->createQuery ( 'mn' )
        ->select ( 'mn.id, mn.date_at,mn.file_image, m.id as meal_id, m.title as meal_title, mn.ps_customer_id, mn.ps_object_group_id, mn.description,im.id as im_id,im.file_name as file_name' );
        
        $q->leftJoin ( 'mn.PsMeals m' );
        $q->leftJoin ( 'mn.PsImages im' );
        $q->where ( 'DATE_FORMAT(mn.date_at,"%Y%m%d") <= ? AND  DATE_FORMAT(mn.date_at,"%Y%m%d") >= ? AND mn.ps_customer_id = ?', array (
            date ( 'Ymd', strtotime ( $date_to ) ),
            date ( 'Ymd', strtotime ( $date_from ) ),
            $ps_customer_id ) );
        
        if ($ps_workplace_id > 0){
            $q->andWhere ( 'mn.ps_workplace_id = ?', $ps_workplace_id );
        }
        		if ($ps_object_group_id > 0){
			$q->andWhere ( 'mn.ps_object_group_id IS NULL OR mn.ps_object_group_id = ?', $ps_object_group_id );		}else{			$q->andWhere ( 'mn.ps_object_group_id IS NULL');		}
        
        return $q->execute ();
    }
}