<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/20
 * Time: 上午10:14
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class BaseCostStructureModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_cost_structure';

    public function getOneByID($id)
    {
        return self::where('id', $id)->get();
    }

    public function getMoreByID($ids)
    {
        return self::whereIn('id', $ids)->get();
    }

    public function getList($name, $status=2)
    {
        $query = self::orderBy('id', 'asc');

        if ($name) {
            $query = $query->where('name', $name);
        }
        if (2 != $status) {
            $query = $query->where('status', $status);
        }
        return $query->paginate(config('global.PAGE_SIZE'));
    }

    public function getAll($status=2)
    {
        if (2 != $status) {
            return self::where('status', $status)->get();
        } else {
            return self::get();
        }
    }

    public function getOneByName($name)
    {
        return self::where(['name'=>$name])->get();
    }

    public function add(Array $data)
    {
        try {
            $obj = self::create($data);
        } catch (\Exception $e) {
            throw $e;
        }
        return $obj->id;
    }

    public function modify($id, array $data)
    {
        try {
            self::where('id', $id)->update($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}