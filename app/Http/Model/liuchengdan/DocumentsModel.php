<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/20
 * Time: 下午4:50
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class DocumentsModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_document';

    public function getList($name='', $company_id=0, $project_id=0, $status=2, array $where=[])
    {
        $query = self::orderBy('id', 'asc');

        if (!empty($name)) {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if (0 < $company_id) {
            $query = $query->where('company_id', $company_id);
        }
        if (0 < $project_id) {
            $query = $query->where('project_id', $project_id);
        }
        if (2 != $status) {
            $query = $query->where('status', $status);
        }
        if (!empty($where)) {
            $query = $query->where($where);
        }

        return $query->paginate(config('global.PAGE_SIZE'));
    }

    public function getAll($status=1)
    {
        $query = self::orderBy('id', 'asc');
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }

        return $query->get();
    }

    public function getOneById($id, $status=1)
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
        //echo __CLASS__ . ' # ' . __FUNCTION__ . "\r\n";
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