<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/12/31
 * Time: 上午11:23
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class ProjectModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_project';

    public function getList($name='', $company_id=0, $status=2, array $where=[])
    {
        $query = self::orderBy('id', 'asc');

        if (!empty($name)) {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if (0 < $company_id) {
            $query = $query->where('company_id', $company_id);
        }
        if (2 != $status) {
            $query = $query->where('status', $status);
        }
        if (!empty($where)) {
            $query = $query->where($where);
        }

        return $query->paginate(config('global.PAGE_SIZE'));
    }

    public function getOneById($id, $status=2)
    {
        $query = self::where('id', $id);
        if (2 != $status)
        {
            $query = $query->where('status', $status);
        }
        return $query->get();
    }

    public function getOneByName($name)
    {
        return self::where('name', $name)->get();
    }

    public function getCount(array $where=[])
    {
        if ($where) {

        }
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