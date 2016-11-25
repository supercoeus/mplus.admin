<?php
/**
 * 管理员逻辑层
 * User: Zachary Liang
 * Date: 16-11-24
 * Time: 下午3:32
 */

namespace app\manage\logic;
use app\manage\common;
use app\manage\common\logic\BaseLogic;
use app\manage\common\logic\ILogic;
use think\Loader;

/**
 * Class Admin
 * @package app\manage\logic
 */
class Admin extends BaseLogic implements ILogic
{
    /**
     * @var \app\common\model\Admin
     */
    private $admin_model;

    /**
     * @var \app\common\validate\Admin
     */
//    protected $validate;

    /**
     * 构造函数
     */
    public function initialize()
    {
        parent::initialize();
        $this->admin_model = Loader::model('Admin');
        $this->validate = Loader::validate('Admin');
    }

    /**
     * 获取管理员列表
     * @return array
     */
    public function get_list()
    {
        $admins = $this->admin_model->order('create_time', 'desc')->select();
        return $admins ?: null;
    }

    /**
     * 获取管理员模型
     * @param $uuid
     * @return \app\common\model\Admin
     */
    public function get_model($uuid)
    {
        $map['uuid'] = $uuid;
        $admin = $this->admin_model->where($map)->find();
        return $admin ?: null;
    }

    /**
     * 添加管理员
     * @param $param
     * @return array
     */
    public function add($param)
    {
        if (!$this->validate->scene('add')->check($param)){
            return common::return_result('500', $this->validate->getError(), null);
        } else {
            $result = $this->admin_model->save($param);
            return $result ?
                common::return_result('200', ADD_SUCCESS_TEXT, url('Admin/Index')) :
                common::return_result('500', ADD_FAILURE_TEXT, null);

        }
    }

    /**
     * 编辑管理员
     * @param $param
     * @param $uuid
     * @return array
     */
    public function edit($param, $uuid)
    {
        if (!$this->validate->scene('edit')->check($param)){
            return common::return_result('500', $this->validate->getError(), null);
        } else {
            $result = $this->admin_model->save($param, ['uuid' => $uuid]);
            return $result ?
                common::return_result('200', EDIT_SUCCESS_TEXT, url('Admin/Index')) :
                common::return_result('500', EDIT_FAILURE_TEXT, null);
        }
    }

    /**
     * 删除管理员
     * @param $param
     * @return array
     */
    public function del($param)
    {
        if ($param['item'] === '000000000000000'){
            return common::return_result('500', ADMIN_CANNOT_DELETE_TEXT, null);
        }

        $map['uuid'] = $param['item'];
        $result = $this->admin_model->where($map)->delete();
        return $result ?
            common::return_result('200', DELETE_SUCCESS_TEXT, null) :
            common::return_result('500', DELETE_FAILURE_TEXT, null);
    }

    /**
     * 批量删除管理员
     * @param $param
     * @return array
     */
    public function batch_del($param)
    {
        if (strpos($param['items'], '000000000000000,') !== false){
            return common::return_result('500', ADMIN_CANNOT_DELETE_TEXT, null);
        }

        $map['uuid'] = array('IN', rtrim($param['items'], ','));
        $result = $this->admin_model->where($map)->delete();
        return $result ?
            common::return_result('200', BATCH_DELETE_SUCCESS_TEXT, null) :
            common::return_result('500', BATCH_DELETE_FAILURE_TEXT, null);
    }

    /**
     * 更新密码
     * @param $param
     * @return array
     */
    public function update_password($param){
        if (!$this->validate->scene('update_password')->check($param)){
            return common::return_result('500', $this->validate->getError(), null);
        }

        $data['password'] = $param['password'];
        $result = $this->admin_model->save($data, ['uuid' => $param['item']]);
        return $result ?
            common::return_result('200', UPDATE_PASSWORD_SUCCESS_TEXT, null) :
            common::return_result('500', UPDATE_PASSWORD_FAILURE_TEXT, null);
    }


}