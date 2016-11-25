<?php
/**
 * 管理员控制器
 * User: Zachary Liang
 * Date: 16-11-24
 * Time: 下午4:35
 */

namespace app\manage\controller;
use app\manage\common\controller\BaseController;
use think\Loader;

/**
 * Class Admin
 * @package app\manage\controller
 */
class Admin extends BaseController
{
    /**
     * @var \app\manage\logic\Admin
     */
    private $admin_logic;

    /**
     * 构造方法
     */
    protected function _initialize()
    {
        parent::_initialize();
        $this->admin_logic = Loader::model('Admin', 'logic', null, 'manage');
    }

    /**
     * 列表
     * @return mixed
     */
    public function Index(){
        $this->data['admins'] = $this->admin_logic->get_list();
        $this->assign('data', $this->data);
        return $this->fetch('Admin' . DS . 'Index');
    }

    /**
     * 添加
     * @return mixed|\think\response\Json
     */
    public function Add(){
        if ($_POST){
            return json($this->admin_logic->add($_POST));
        } else {
            $this->assign('data', $this->data);
            return $this->fetch('Admin' . DS . 'Add');
        }
    }

    /**
     * 编辑
     * @param $uuid
     * @return mixed|\think\response\Json
     */
    public function Edit($uuid){
        if ($_POST){
            return json($this->admin_logic->edit($_POST, $uuid));
        } else {
            $this->data['admin'] = $this->admin_logic->get_model($uuid);
            $this->assign('data', $this->data);
            return $this->fetch('Admin' . DS . 'Edit');
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     */
    public function Delete(){
        if ($_POST){
            return json($this->admin_logic->del($_POST));
        }
    }

    /**
     * 批量删除
     * @return \think\response\Json
     */
    public function BatchDelete(){
        if ($_POST){
            return json($this->admin_logic->batch_del($_POST));
        }
    }

    /**
     * 更新密码
     * @return \think\response\Json
     */
    public function UpdatePassword(){
        if ($_POST){
            return json($this->admin_logic->update_password($_POST));
        }
    }


}