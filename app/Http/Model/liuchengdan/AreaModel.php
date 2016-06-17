<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/25
 * Time: 上午9:36
 */

namespace App\Http\Model\liuchengdan;


use App\Http\Model\BaseModel;

class AreaModel extends BaseModel
{
    protected $table = 'base_area';

    public function getList()
    {
        return self::where('status', 1)->get();
    }

    public function getOneById($id)
    {
        return self::where('id', $id)->get();
    }

    public function nameExists($name)
    {
        return self::where('name', $name)->count();
    }

    public function aliasExists($alias)
    {
        return self::where('alias', $alias)->count();
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