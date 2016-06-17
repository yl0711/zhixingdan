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

    public function getList($name='', $cate1=0, $status=0, $uids=[])
    {
        $query = self::orderBy('id', 'asc');

        if (!empty($name)) {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if (0 < $cate1) {
            $query = $query->where('cate1', 'like', '%,' . $cate1 . ',%');
        }
        if (0 != $status) {
            $query = $query->where('status', $status);
        } else {
            $query = $query->where('status', '!=', '-1');
        }
        if (!empty($uids)) {
            $query = $query->whereIn('created_uid', $uids);
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

    public function getOneById($id, $status=2)
    {
        $query = self::where('id', $id);
        if (2 != $status){
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