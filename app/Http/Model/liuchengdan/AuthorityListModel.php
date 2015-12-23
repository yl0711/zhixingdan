<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/2
 * Time: 下午3:31
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;

class AuthorityListModel extends BaseModel
{

    protected $table = 'authority_list';


    public function get_all()
    {
        return self::where('state', 1)->orderBy('parentid', 'asc')->orderBy('masterid', 'asc')->orderBy('vieworder', 'asc')->get();
    }

    public function get_list($id)
    {
        return self::whereIn('id', $id)->where('state', 1)->orderBy('parentid', 'asc')->orderBy('masterid', 'asc')->orderBy('vieworder', 'asc')->get();
    }

    public function getByController($controller, $method='')
    {
        if ('all' == $method) {
            return self::where('classname', $controller)->where('state', 1)->get();
        } else {
            return self::where('classname', $controller)->where('methodname', $method)->where('state', 1)->get();
        }
    }
    /**
     * @param array $parentList
     * @return array
     * @throws \Exception
     */
    public function createParentAuthority(Array $parentList)
    {
        if (0 == count($parentList)) {
            throw new \Exception('父级菜单为空, 无法创建权限列表');
        }
        $parent = [];

        $list = AuthorityListModel::whereIn('aname', array_keys($parentList))->get()->toArray();
        if (0 < count($list)) {
            foreach ($list as $parentData) {
                if (isset($parentList[$parentData['aname']])) {
                    $parent[$parentData['aname']] = $parentData['id'];

                    AuthorityListModel::where('id', $parentData['id'])
                        ->update(['state'=>1, 'vieworder'=>$parentList[$parentData['aname']]['order']]);
                    unset($parentList[$parentData['aname']]);
                }
            }
        }

        if (0 < count($parentList)) {
            foreach ($parentList as $parentData) {
                $parent[$parentData['name']] = AuthorityListModel::create([
                    'aname'=>$parentData['name'],
                    'vieworder'=>$parentData['order'],
                ])->id;
            }
        }
        return $parent;
    }

    /**
     * @param array $masterList
     * @param array $parent
     * @return array
     * @throws \Exception
     */
    public function createMasterAuthority(Array $masterList, Array $parent)
    {
        if (0 == count($masterList)) {
            throw new \Exception('权限列表为空');
        }

        $master = [];

        $list = AuthorityListModel::whereIn('classname', array_keys($masterList))->get()->toarray();
        if (0 < count($list)) {
            foreach ($list as $masterData) {
                if (isset($masterList[$masterData['classname']])) {
                    $master[$masterData['classname']] = $masterData['id'];

                    AuthorityListModel::where('id', $masterData['id'])
                        ->update([
                            'state'=>1,
                            'vieworder'=>$masterList[$masterData['classname']]['order'],
                            'aname'=>$masterList[$masterData['classname']]['name'],
                            'url'=>$masterList[$masterData['classname']]['url'],
                            'parentid'=>$parent[$masterList[$masterData['classname']]['parent']],
                        ]);
                    unset($masterList[$masterData['classname']]);
                }
            }
        }

        if (0 < count($masterList)) {
            foreach ($masterList as $masterData) {
                $master[$masterData['classname']] = AuthorityListModel::create([
                    'aname'=>$masterData['name'],
                    'url'=>$masterData['url'],
                    'filename'=>$masterData['filename'],
                    'classname'=>$masterData['classname'],
                    'parentid'=>$parent[$masterData['parent']],
                    'vieworder'=>$masterData['order']
                ])->id;
            }
        }

        return $master;
    }

    /**
     * @param array $subList
     * @param array $parent
     * @param array $master
     * @return array|void
     */
    public function createSubAuthority(Array $subList, Array $parent, Array $master)
    {
        if (0 == count($subList)) {
            return;
        }

        $sub = $updateIds = [];

        $list = AuthorityListModel::where('parentid', '>', '0')->where('masterid', '>', '0')->get()->toarray();
        if (0 < count($list)) {
            foreach ($list as $subData) {
                if (isset($subList[$subData['classname'].'|'.$subData['methodname']])) {
                    $sub[$subData['classname'].'|'.$subData['methodname']] = $subData['id'];

                    AuthorityListModel::where('id', $subData['id'])
                        ->update([
                            'state'=>1,
                            'aname'=>$subList[$subData['classname'].'|'.$subData['methodname']]['name'],
                            'url'=>$subList[$subData['classname'].'|'.$subData['methodname']]['url']
                        ]);
                    unset($subList[$subData['classname'].'|'.$subData['methodname']]);
                }
            }
        }

        if (0 < count($subList)) {
            foreach ($subList as $subData) {
                $sub[$subData['classname'].'|'.$subData['methodname']] = AuthorityListModel::create([
                    'aname'=>$subData['name'],
                    'url'=>$subData['url'],
                    'filename'=>$subData['filename'],
                    'classname'=>$subData['classname'],
                    'methodname'=>$subData['methodname'],
                    'parentid'=>$parent[$subData['parent']],
                    'masterid'=>$master[$subData['master']]
                ])->id;
            }
        }

        return $sub;
    }

    /**
     * 停用权限
     * @param array $ids  传入 id 数组后,将这些 id 的 state 改为0, 如此参数为空, 则所有权限的 state 改为0
     */
    public function stopAuthority(Array $ids=[])
    {
        if (!$ids) {
            return AuthorityListModel::update(['state'=>0]);
        } else {
            if (is_array($ids)) {
                return AuthorityListModel::whereIn('id', $ids)->update(['state'=>0]);
            } else {
                throw new \Exception(__CLASS__ .'->'.__FUNCTION__.': 参数错误, '.implode(',', func_get_args()));
            }
        }
    }
}
