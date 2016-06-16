<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/6/16
 * Time: 上午10:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminBaseController;
use App\Http\Model\liuchengdan\SettingModel;
use Illuminate\Http\Request;

class SettingController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $setting = [];
        $data = SettingModel::where(['type'=>'sys', 'status'=>1])->get()->toArray();
        if ($data) {
            foreach ($data as $item) {
                $setting[$item['setting_key']] = $item['setting_value'];
            }
        }

        return view('admin.setting.sys', compact('setting'));
    }

    /**
     * @Authorization 修改系统设置
     */
    public function setSystem(Request $request)
    {
        try {
            $data = $request->all();
            foreach ($data as $key=>$value) {
                if (!SettingModel::where(['type'=>'sys', 'setting_key'=>$key])->update(['setting_value'=>$value, 'status'=>1])) {
                    SettingModel::create(['type'=>'sys', 'setting_key'=>$key, 'setting_value'=>$value]);
                }
            }
            return json_encode(['status'=>'success']);
        } catch (\Exception $e) {
            return json_encode(['status'=>'error', 'code'=>$e->getCode(), 'info'=>$e->getMessage()]);
        }
    }
}