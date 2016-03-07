<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/20
 * Time: 上午10:16
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class DocumentCostStructureModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_cost_structure';

    public function getByDocID($id)
    {
        return self::where('document_id', $id)->get();
    }

    public function add(array $data)
    {
        return self::create($data)->id;
    }

    public function modify(array $data, $id) {
        return self::where('id', $id)->update($data);
    }
}