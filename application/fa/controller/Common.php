<?php
namespace app\fa\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Session;

class Common extends Controller
{
    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub

        //判断用户是否登陆
        $this->getUserInfo();

        $controller = strtolower(Request::controller());
        $this->assign('controller',$controller);
        $action= strtolower(Request::action());
        $this->assign('action',$action);

        $auth = new \app\fa\model\Authorize();
        $menus= $auth->getList(3);
        $subMenus = $auth->getSubMenus();
        $this->assign('sub_menus',$subMenus[1]);
        $commonMenus = $auth->getCommonMenus();
        $this->assign('common',$commonMenus);
        $this->assign('toplocal',$subMenus[0]);

        $this->assign('menus',$menus);
    }
    public function getUserInfo(){
        $userInfo = Session::get('admin_user');
        if(!$userInfo){
            $this->redirect(url('login/account'));
        }else{
            $this->assign('user',$userInfo);
        }
    }
}