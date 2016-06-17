<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/3/2
 * Time: 下午1:22
 */

namespace App\Http\Model\liuchengdan;


use App\Http\Model\BaseModel;

class DocumentReviewModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_review';

    public function getListByDocID($docID)
    {
        return self::where('document_id', $docID)->orderBy('level')->get();
    }

    public function getListByUserID($userID)
    {
        return self::where('review_uid', $userID)->where('status', '!=', '-1')->orderBy('created_at', 'desc')->get();
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