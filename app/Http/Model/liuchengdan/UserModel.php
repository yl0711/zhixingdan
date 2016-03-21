<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/12
 * Time: 下午4:16
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserModel extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_user';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * 获取管理员列表
     * @param string $name
     * @param int    $gid
     * @param int    $state
     * @return mixed
     */
	public function getList($name='', $group_id=0, $department_id=0, $status=2)
    {
        //$query = self::where('superadmin', '!=', 1);

        $query = self::orderBy('created_at', 'desc');

        if (!empty($name)) {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if ($group_id) {
            if (is_array($group_id)) {
                $query = $query->whereIn('group_id', $group_id);
            } else {
                $query = $query->where('group_id', $group_id);
            }
        }
        if (0 < $department_id) {
            $query = $query->where('department_id', $department_id);
        }
        if (2 != $status) {
            $query = $query->where('status', $status);
        }

        return $query->paginate(config('global.PAGE_SIZE'));
	}

    public function getCount(array $where)
    {
        return self::where($where)->count();
    }

    public function getAll($status=1)
    {
        return self::where('status', $status)->orderBy('name', 'asc')->get();
    }

    /**
     * 获取指定ID的管理员
     * @param  mixed $uid 可以是数组或单个ID
     * @return mixed
     */
    public function getByUid($id)
    {
        if (is_array($id)) {
            $data = self::whereIn('id', $id)->get();
        } elseif (is_numeric($id)) {
            $data = self::where('id', $id)->get();
        } else {
            throw new \Exception(__CLASS__ . '->' . __FUNCTION__ . ': 参数不符合规范, ' . implode(',', func_get_args()));
        }

        if (empty($data->toarray())) {
            throw new \Exception('用户不存在');
        }
        return $data;
    }

    public function getByUname($name)
    {
        return self::where('name', $name)->get()->toarray();
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

    public function modify($id, Array $data)
    {
        self::where('id', $id)->update($data);
    }

    public function state($id)
    {

    }

}