<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/14
 * Time: 下午5:01
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class CategoryModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_category';

    public function getOneByID($id)
    {
        return self::where('id', $id)->get();
    }

    public function getListByType($type, $status=2)
    {
        if (empty($type) || !is_numeric($type)) {
            throw new \Exception(__CLASS__ . '::' . __FUNCTION__ . ' # 参数错误 ( ' .  __LINE__ . ' )');
        }
        $query = self::where('type', $type)->orderBy('name', 'asc');
        if (2 != $status) {
            $query = $query->where('status', $status);
        }
        return $query->get();
    }

    public function getList($name, $type=0, $status=2)
    {
        $query = self::orderBy('id', 'asc');

        if ($name) {
            $query = $query->where('name', $name);
        }
        if ($type) {
            $query = $query->where('type', $type);
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

    public function getOneByName($name, $type)
    {
        return self::where(['name'=>$name, 'type'=>$type])->get();
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