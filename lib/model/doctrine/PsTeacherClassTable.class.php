<?php

/**
 * PsTeacherClassTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsTeacherClassTable extends Doctrine_Table
{
    
    /**
     * Returns an instance of this class.
     *
     * @return object PsTeacherClassTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PsTeacherClass');
    }
    
    /**
     * Lay giao vien duoc phan cong cua mot lop trong khoang thoi gian hien tai
     *
     * author thangnc - newwaytech.vn
     *
     * @param $class_id -
     *            int
     * @return $list object
     */
    public function getTeachersByClassIdWithTime($ps_myclass_id, $from_date = null, $to_date = null)
    {
        $check_from_date = $from_date ? true : false;
        
        $check_to_date = $from_date ? true : false;
        
        $from_date = $from_date ? date('Ymd', strtotime($from_date)) : date('Ymd');
        
        $from_year = substr($from_date, 0, 4);
        
        $to_date = $to_date ? date('Ymd', strtotime($to_date)) : date('Ymd');
        
        $to_year = substr($to_date, 0, 4);
        
        $query = $this->createQuery('a')->select('a.id as id, a.ps_myclass_id AS class_id,m.id AS m_id, m.image AS image, CONCAT(m.first_name," ",m.last_name) AS full_name, a.primary_teacher AS primary_teacher, a.start_at,a.stop_at,a.is_activated,CONCAT(m.first_name," ",m.last_name) AS title');
        
        if (is_array($ps_myclass_id)) {
            $query->innerJoin('a.PsMember m');
            $query->andWhereIn('a.ps_myclass_id', $ps_myclass_id);
        } else {
            $query->innerJoin('a.PsMember m');
            $query->addWhere('a.ps_myclass_id = ?', $ps_myclass_id);
        }
        $query->andWhere('a.is_activated = ?', PreSchool::ACTIVE);
        
	//$query->andWhere('a.primary_teacher = ?', PreSchool::ACTIVE);
        
        //         if(!$check_from_date && !$check_to_date){
        //             $query->andWhere('DATE_FORMAT(a.start_at,"%Y%m%d") <= ? AND (a.stop_at IS NULL OR  DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?)', array(
        //                 $from_date,
        //                 $to_date
        //             ));
        //         }
        
        if($check_from_date && $check_to_date) {
            $query->andWhere('DATE_FORMAT(a.start_at,"%Y") >= ? AND (a.stop_at IS NULL OR DATE_FORMAT(a.stop_at,"%Y") <= ?)', array(
                $from_year,
                $to_year
            ));
        }elseif(!$check_from_date && !$check_to_date){
            $query->andWhere('DATE_FORMAT(a.start_at,"%Y%m%d") <= ? AND (a.stop_at IS NULL OR  DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?)', array(
                $from_date,
                $to_date
            ));
        }elseif($check_from_date && !$check_to_date){
            $query->andWhere('DATE_FORMAT(a.start_at,"%Y") >= ? AND (a.stop_at IS NULL OR  DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?)', array(
                $from_year,
                $to_date
            ));
        }elseif(!$check_from_date && $check_to_date){
            $query->andWhere('DATE_FORMAT(a.start_at,"%Y%m%d") <= ? AND (a.stop_at IS NULL OR  DATE_FORMAT(a.stop_at,"%Y") <= ?)', array(
                $from_date,
                $to_year
            ));
        }
        
        $query->groupBy('a.ps_myclass_id, m.id');
        
        $query->orderBy('a.primary_teacher DESC');
        
        return $query->execute();
    }
    
    // lay ra lop hien tai theo member_id
    public function getClassByMemberId($member_id)
    {
        // $date = date('Ymd');
        $query = $this->createQuery('a')->select('a.id as id,mc.id AS mc_id CONCAT(mc.first_name," ",mc.last_name) AS full_name, a.primary_teacher AS primary_teacher,cr.ps_workplace_id as ps_workplace_id,cr.id as wp_id, a.ps_myclass_id as ps_myclass_id, a.start_at,a.stop_at,a.is_activated');
        $query->innerJoin('a.MyClass mc');
        $query->innerJoin('mc.PsClassRooms cr');
        $query->andWhere('a.is_activated = ?', PreSchool::ACTIVE);
        $query->addWhere('a.ps_member_id = ?', $member_id);
        $query->orderBy('a.primary_teacher DESC');
        
        return $query->fetchOne();
    }
    
    // lay ra danh sach lop
    public function getClassByMemberIds($member_id)
    {
        $date_at = date('Ymd');
        $query = $this->createQuery('a')->select('a.id as id,mc.id AS mc_id CONCAT(mc.first_name," ",mc.last_name) AS full_name, a.primary_teacher AS primary_teacher,cr.ps_workplace_id as ps_workplace_id,cr.id as wp_id, a.ps_myclass_id as ps_myclass_id, a.start_at,a.stop_at,a.is_activated');
        $query->innerJoin('a.MyClass mc');
        $query->innerJoin('mc.PsClassRooms cr');
        $query->andWhere('a.is_activated = ?', PreSchool::ACTIVE);
        $query->addWhere('a.ps_member_id = ?', $member_id);
        $query->orderBy('a.primary_teacher DESC');
        $query->andWhere('(DATE_FORMAT(a.start_at,"%Y%m%d") <= ? AND (a.stop_at IS NULL OR  DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?))', array($date_at, $date_at) );
        return $query->execute();
    }
    
    public function getChoiceTeachersByClassId($ps_myclass_id)
    {
        $objs = $this->setTeachersByClassId($ps_myclass_id)->execute();
        
        $chois = array();
        
        foreach ($objs as $obj) {
            
            $chois[$obj->getId()] = $obj->getFullName();
        }
        
        return $chois;
    }
    
    /**
     * Cai nĂ y viáº¿t thiáº¿u Ä‘k, pháº£i cĂ³ Ä‘iá»�u kiĂªn $date Ä‘á»ƒ xem GV cĂ²n á»Ÿ trong lá»›p ko
     *
     * setTeachersByClassId($ps_myclass_id)
     * Set sql lay giao vien duoc phan cong cua mot lop
     *
     * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
     * @param $class_id -
     *            int
     * @return $list object
     */
    public function setTeachersByClassId($ps_myclass_id, $tracked_at = null)
    {
        $query = $this->createQuery('a')->select('a.ps_member_id as id, m.image AS image, CONCAT(m.first_name," ",m.last_name) AS full_name, a.primary_teacher AS primary_teacher, a.start_at,a.stop_at,a.is_activated,CONCAT(m.first_name," ",m.last_name) AS title');
        $query->innerJoin('a.PsMember m');
        
        if ($ps_myclass_id > 0) {
            $query->addWhere('a.ps_myclass_id = ?', $ps_myclass_id);
        }
        
        if ($tracked_at != '') {
            
            $tracked_at = date("Ymd", strtotime($tracked_at));
            
            $query->andWhere('DATE_FORMAT(a.start_at,"%Y%m%d") <= ?', $tracked_at);
            
            $query->andWhere('((DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?) OR (a.stop_at IS NULL) )', $tracked_at);
        }
        
        $query->orderBy('a.primary_teacher DESC');
        
        return $query;
    }
    
    /**
     *  lay giao 1 giao vien cua lop de phuc vu import diem danh
     */
    public function getTeachersFindOneByClassId($ps_myclass_id, $tracked_at)
    {
        $query = $this->createQuery('a')
        
        ->select('a.id as a_id,a.ps_member_id as teacher_id, mb.id as id,CONCAT(mb.first_name," ",mb.last_name) AS title')
        ->innerJoin('a.PsMember mb');
        
        if ($ps_myclass_id > 0) {
            $query->addWhere('a.ps_myclass_id = ?', $ps_myclass_id);
        }
        
        if ($tracked_at != '') {
            
            $tracked_at = date("Ym", strtotime($tracked_at));
            
            $query->andWhere('DATE_FORMAT(a.start_at,"%Y%m") <= ?', $tracked_at);
            
            $query->andWhere('((DATE_FORMAT(a.stop_at,"%Y%m") >= ?) OR (a.stop_at IS NULL) )', $tracked_at);
        }
        
        $query->addWhere('a.is_activated = ?', PreSchool::ACTIVE);
        
        $query->orderBy('a.primary_teacher DESC');
        
        return $query;
        
    }
    
    
    
    /**
     * Lay tat ca giao vien da tung duoc phan cong cua mot lop
     *
     * author thangnc - newwaytech.vn
     *
     * @param $class_id -
     *            int
     * @return $list object
     */
    public function getTeachersByClassId($ps_myclass_id)
    {
        $query = $this->createQuery('a')->select('a.id as id, m.member_code AS member_code ,m.image AS image, CONCAT(m.first_name," ",m.last_name) AS full_name, a.primary_teacher AS primary_teacher, a.start_at AS start_at, a.stop_at AS stop_at,a.is_activated,CONCAT(m.first_name," ",m.last_name) AS title');
        $query->innerJoin('a.PsMember m');
        
        $query->where('a.ps_myclass_id = ?', $ps_myclass_id);
        
        $query->orderBy('a.primary_teacher DESC');
        
        return $query->execute();
    }
    
    /**
     * setTeachersByClassId($ps_myclass_id)
     * Set sql lay giao vien duoc phan cong cua mot lop
     *
     * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
     * @param $class_id -
     *            int
     * @return list object
     */
    
    /**
     * setTeachersByClassId($ps_myclass_id)
     * Set sql lay giao vien chu nhiem cua mot lop
     *
     * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
     * @param $class_id -
     *            int
     * @return object
     */
    public function setTeachersPrimaryByClassId($ps_myclass_id)
    {
        $query = $this->createQuery('a')->select('m.id as id, m.image AS image, a.ps_member_id as ps_member_id,CONCAT(m.first_name," ",m.last_name) AS full_name, a.primary_teacher AS primary_teacher, a.start_at,a.stop_at,a.is_activated,CONCAT(m.first_name," ",m.last_name) AS title');
        $query->innerJoin('a.PsMember m');
        
        if ($ps_myclass_id > 0) {
            $query->where('a.ps_myclass_id = ?', $ps_myclass_id);
        }
        
        $query->orderBy('a.primary_teacher DESC');
        
        return $query->fetchOne();
    }
    
    /**
     * getNumberTeacherByClassId($ps_myclass_id)
     * Kiem tra so luong giao vien da duoc phan vĂ o lop
     *
     * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
     *
     * @param $class_id -
     *            int
     * @return int
     */
    public function getNumberTeacherByClassId($class_id)
    {
        $query = $this->createQuery('a')->where('a.ps_myclass_id = ?', $class_id);
        
        return $query->count();
    }
    
    /**
     * getAllClassByUserId($user_id)
     * Lay lop duoc phan cong boi user dang nhap
     *
     * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
     * @param $class_id -
     *            int
     * @return $object
     */
    public function getClassIdByUserId($user_id, $time = null)
    {
        $date 	= date('Ymd');
        
        $query 	= $this->createQuery('a')->select('a.ps_myclass_id as myclass_id, mc.ps_customer_id AS ps_customer_id, mc.ps_workplace_id as ps_workplace_id');
        
        $query->addWhere('u.id = ?', $user_id);
        
        $query->addWhere('u.user_type = ?', PreSchool::USER_TYPE_TEACHER);
        
        $query->andWhere('DATE_FORMAT(a.start_at,"%Y%m%d") <= ?', $date);
        
        $query->andWhere('((DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?) OR (a.stop_at IS NULL) )', $date);
        
        $query->innerJoin('a.PsMember m');
        
        $query->innerJoin('m.sfGuardUser u');
        
        $query->innerJoin('a.MyClass mc');
        
        return $query->fetchOne();
    }
    
    /**
     * getAllClassByUserId($user_id)
     * Lay 1 lop duoc phan cong boi user, ko xet toi thoi diem phan cong
     *
     * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
     * @param $class_id -
     *            int
     * @return $object
     */
    public function getAllClassByUserId($user_id)
    {
        $query = $this->createQuery('a')->select('ps_myclass_id as myclass_id');
        
        $query->addWhere('u.id = ?', $user_id);
        
        $query->addWhere('u.user_type = ?', PreSchool::USER_TYPE_TEACHER);
        
        $query->innerJoin('a.PsMember m');
        
        $query->innerJoin('m.sfGuardUser u');
        
        return $query->execute();
    }
    
    /**
     * lay danh sach giao vien theo truong va co so
     * Phung Van Thanh
     * 15/10/2019
     */
    public function getAllTeachersByCustomerId($ps_customer_id,$ps_workplace_id,$date_at)
    {
    	$date_at = $date_at ? date('Ymd',strtotime($date_at)) : date('Ymd');
    	
    	$query = $this->createQuery('a')->select('m.id as id, m.image AS image, a.ps_member_id as ps_member_id,a.ps_myclass_id as myclass_id,CONCAT(m.first_name," ",m.last_name) AS full_name, a.primary_teacher AS primary_teacher, a.start_at,a.stop_at,a.is_activated,CONCAT(m.first_name," ",m.last_name) AS title');
    	
    	$query->innerJoin('a.PsMember m');
    	
    	if ($ps_customer_id > 0) {
    		$query->where('m.ps_customer_id = ?', $ps_customer_id);
    	}
    	
    	if ($ps_workplace_id > 0) {
    		$query->where('m.ps_workplace_id = ?', $ps_workplace_id);
    	}
    	
    	$query->addWhere ( 'm.is_status = ?', PreSchool::HR_STATUS_WORKING );
    	
    	$query->andWhere ( '(DATE_FORMAT(a.start_at,"%Y%m%d") <= ? AND (a.stop_at IS NULL OR  DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?))', array (
    			$date_at,
    			$date_at
    	) );
    	
    	$query->orderBy('a.primary_teacher DESC');
    	
    	return $query->execute();
    }
}