<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/8
 * Time: 下午2:55
 */

namespace App\Http\Model\liuchengdan;

use App\Http\Model\BaseModel;
use Schema;
use DB;

class AdminLogModel extends BaseModel
{
    protected $table = 'admin_log';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $table = $this->table . '_' . date('Ym');

        if (!Schema::hasTable($table)) {
            DB::statement('CREATE TABLE `' . $table . '` LIKE `' . $this->table . '`');
        }

        $this->table = $table;
    }

    public function add(array $data)
    {
        return self::create($data);
    }

    public function modify($id, array $data)
    {
        return self::where('id', $id)->update($data);
    }
}