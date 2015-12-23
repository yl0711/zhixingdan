<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 15/11/2
 * Time: 下午3:35
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{

    protected $guarded = [];

}