<?php
/**
 *
 * @package truongnet.com
 * @subpackage API app
 *
 * @file AlbumItemModel.php
 * @author trint
 *
 */
namespace Api\Albums\Model;

use App\Model\BaseModel;

class AlbumCommentModel extends BaseModel
{

    protected $table = TBL_PS_ALBUM_COMMENT;

    /**
     * Lay thong so anh boi id anh
     */
    public static function getCommentToAlbum($album_id) {
        
        $tbl = TBL_PS_ALBUM_COMMENT;
        $list_comment_albums = AlbumCommentModel::select ( $tbl . '.id AS id', '.title AS title','.relative_id AS relative_id','u.first_name AS first_name','u.last_name AS last_name')
        ->leftJoin ( CONST_TBL_USER . ' as u', function ($q) use ($tbl) {
            $q->on ( 'u.id', '=',  $tbl . '.relative_id' )
            ->where ( 'u.is_active', '=', '1' );
        })
        ->where(  $tbl . '.album_id', '=', $album_id )
        ->orderBy(  $tbl . '.created_at','desc' )
        ->get();
        return $list_comment_albums;
    }
    
}