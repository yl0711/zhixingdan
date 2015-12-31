<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/31
 * Time: 上午11:23
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class CompanyModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_company';

    public function getList($name=null, $status=null)
    {
        $query = self::orderBy('id', 'asc');

        if (!is_null($name) && !$name) {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }

        return $query->paginate(config('global.PAGE_SIZE'));
    }

    public function getOneById($id)
    {
        return self::where('id', $id)->get();
    }

    public function getOneByName($name)
    {
        return self::where('name', $name)->get();
    }

    public function add(Array $data)
    {
        try {
            $obj = self::create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
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