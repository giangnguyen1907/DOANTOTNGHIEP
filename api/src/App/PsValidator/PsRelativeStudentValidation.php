<?php
/**
 * Created by PhpStorm.
 * User: Luxury
 * Date: 7/17/2017
 * Time: 3:43 PM
 */

namespace App\PsValidator;


use App\Model\BaseModel;

class PsRelativeStudentValidation extends BaseModel
{
    protected $table = CONST_TBL_RELATIVESTUDENT;

    public static function RelativeStudentVal($student_id, $relative_id){
        
        return PsRelativeStudentValidation::where('student_id',$student_id)->where('relative_id', $relative_id)->get()->first();
    }

}