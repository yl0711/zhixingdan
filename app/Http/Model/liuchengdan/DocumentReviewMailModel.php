<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/3/2
 * Time: 下午1:22
 */

namespace App\Http\Model\liuchengdan;


use App\Http\Model\BaseModel;

class DocumentReviewMailModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_review_mail';

    public function getListByDocID($docID)
    {
        return self::where('document_id', $docID)->orderBy('level')->get();
    }

    public function getListByUserID($userID)
    {
        return self::where('review_uid', $userID)->orderBy('created_at')->get();
    }

    public function getOneByReviewId($reviewId)
    {
        return self::where('review_id', $reviewId)->get();
    }

    public function add(Array $data)
    {
        return self::create($data);
    }

    public function modify($id, Array $data)
    {
        return self::where('id', $id)->save($data);
    }


}