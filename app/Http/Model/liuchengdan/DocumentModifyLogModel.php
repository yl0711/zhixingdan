<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/3/8
 * Time: 上午9:05
 */

namespace App\Http\Model\liuchengdan;


use App\Http\Model\BaseModel;

class DocumentModifyLogModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_modify_log';

    public function add($data)
    {
        return self::create($data);
    }

    public function modify($where, $data)
    {
        return self::where($where)->update($data);
    }
}