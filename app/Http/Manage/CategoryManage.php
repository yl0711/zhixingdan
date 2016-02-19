<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/2/14
 * Time: 下午5:02
 */

namespace App\Http\Manage;

use App\Http\Model\liuchengdan\CategoryModel;

class CategoryManage
{
    private $categoryModel = null;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function getOneByID($id)
    {
        return $this->categoryModel->getOneByID($id);
    }

    public function getList($name='', $type=0, $status=2)
    {
        $data = $this->categoryModel->getList($name, $type, $status);
        foreach ($data as $item) {
            $item->typeStr = self::categoryTypeId2Str($item->type);
        }
        return $data;
    }

    public function add(array $request)
    {
        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写项目分类名称');
        }
        if (!empty($this->categoryModel->getOneByName($request['name'], $request['type'])->toArray())) {
            throw new \Exception($request['name'] . ' 在 ' . self::categoryTypeId2Str($request['type']) . ' 中已经存在');
        }

        return $this->categoryModel->add($request);
    }

    public function modify(array $request, $id)
    {
        if (!$this->getOneByID($id)->toArray()) {
            throw new \Exception('数据不存在');
        }

        if (!isset($request['name']) || empty($request['name'])) {
            throw new \Exception('请填写名称');
        }
        if ($request['name'] != $request['oldname'] && !empty($this->categoryModel->getOneByName($request['name'], $request['type'])->toArray())) {
            throw new \Exception($request['name'] . ' 已经存在');
        }
        unset($request['id']);
        unset($request['oldname']);

        try {
            $this->categoryModel->modify($id, $request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function categoryTypeId2Str($type)
    {
        switch ($type) {
            case 1:
                $str = '工作类别';
                break;
            case 2:
                $str = '工作分项';
                break;
            case 3:
                $str = '工作项目';
                break;
            default:
                $str = '未知';
                break;
        }
        return $str;
    }
}