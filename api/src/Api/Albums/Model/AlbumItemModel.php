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

class AlbumItemModel extends BaseModel
{

    protected $table = TBL_PS_ALBUM_ITEMS;

    /**
     * Lay thong so anh boi id anh
     */
    public static function getAlbumInfoItemById($item_id, $ps_customer_id)
    {
        $tbl = TBL_PS_ALBUM_ITEMS;

        $image = AlbumItemModel::select($tbl . '.user_created_id', $tbl . '.id', $tbl . '.is_activated', $tbl . '.album_id')->join(TBL_PS_ALBUMS . ' as ab', 'ab.id', '=', $tbl . '.album_id')
            ->where($tbl . '.id', $item_id)
            ->where('ab.ps_customer_id', $ps_customer_id)
            ->get()
            ->first();

        return $image;
    }

    /**
     * Lấy tất cả ảnh của Album
     *
     * @param $ps_album_id int
     *            ID album
     * @param $_arr_img_activated mixed
     *            trạng thái ảnh
     *            
     * @return mixed
     */
    public static function getListImageByAlbumId($ps_album_id, $_arr_img_activated) {
    	
    	$tbl = TBL_PS_ALBUM_ITEMS;
    	
    	$images = AlbumItemModel::select($tbl . '.id as id', $tbl . '.album_id as album_id', $tbl . '.title as title', $tbl . '.url_file as url_file', $tbl . '.is_activated as is_activated', $tbl . '.note as note', $tbl . '.number_like as number_like', $tbl . '.number_dislike as number_dislike', $tbl . '.url_thumbnail as url_thumbnail',$tbl . '.created_at as created_at', 'u.first_name as first_name', 'u.last_name as last_name')
    	
    	->join(CONST_TBL_USER . ' as u', 'u.id', '=', $tbl . '.user_created_id')
    	
        ->where('album_id', $ps_album_id)
        ->whereIn('is_activated', $_arr_img_activated)
        ->orderBy('created_at', 'desc')
        ->get();
        
        return $images;
    }

    // ###########################################################################################
    // Xoa anh theo id
    public static function deleteAlbumItem($item_id)
    {
        $img = AlbumItemModel::find($item_id);
        if ($img) {
            $img->delete();
        }
        return $img;
        // return AlbumItemModel::where('id', $item_id)->delete();
    }

    // Lay anh theo ma album album_key
    public static function getImageByAlbumKey($ps_album_key)
    {
        $tbl = TBL_PS_ALBUM_ITEMS;

        $images = AlbumItemModel::select($tbl . '.id', $tbl . '.album_id', $tbl . '.title', $tbl . '.url_file', $tbl . '.is_activated', $tbl . '.note', $tbl . '.number_like', $tbl . '.number_dislike', $tbl . '.url_thumbnail')
        	->join(TBL_PS_ALBUMS . ' as ab', 'ab.id', '=', $tbl . '.album_id')
            ->where('ab.album_key', $ps_album_key)
            ->orderBy('id', 'desc')
            ->get();

        return $images;
    }

    // Lay anh theo id album
    public static function getImageByAlbumId($ps_album_id)
    {
        $images = AlbumItemModel::select('id', 'album_id', 'title', 'url_file', 'is_activated', 'note', 'number_like', 'number_dislike', 'url_thumbnail')->where('album_id', $ps_album_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $images;
    }
    
    // Tinh tong so anh cua 1 album_id
    public static function getTotalImageOfAlbum($ps_album_id)
    {
    	$number_image = AlbumItemModel::select('id')->where('album_id', $ps_album_id)->get()->count();
    	
    	return $number_image;
    }
}