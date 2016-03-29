<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/3/28
 * Time: 上午9:51
 */

namespace App\Http\Model\liuchengdan;


use App\Http\Model\BaseModel;

class AttachmentModel extends BaseModel
{
    protected $table = 'base_attachment';

    public function getListByDocumentCostId($id)
    {
        return self::where('status', 1)->where('document_cost_id', $id)->get();
    }

    public function getOneById($id)
    {
        return self::where('id', $id)->get();
    }

    public function add(Array $data)
    {
        return self::create($data)->id;
    }

    public function modify($id, Array $data)
    {
        return self::where('id', $id)->update($data);
    }
}