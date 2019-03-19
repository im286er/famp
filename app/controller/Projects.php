<?php
/**
 * Created by PhpStorm.
 * User: top-dante
 * Date: 2019/3/14
 * Time: 22:44
 */

namespace app\controller;

use app\model\Products;
use think\facade\Request;
use think\facade\Config;

class Projects extends Base
{
    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $member = new \app\model\Member();
        $userList = $member->getUserList();
        //读取配置文件项目状态
        $projectStatus = Config::get('app.projectStatus');

        $this->assign(['status'=> $projectStatus,'userlist'=>$userList]);
    }

    public function overview(){

        return $this->fetch();
    }
    /**
     * 项目首页
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $projectModel = new \app\model\Projects();
        //项目状态和紧急程度配置输出
        $level = $projectModel->projectLevel;
        $getStatus = Request::get('status', '', 'intval');
        $getInterval = Request::get('interval', '', 'intval');
        $subject = Request::get('subject', '', 'htmlentities');

        $map = [];
        //搜索描述
        if ($subject) {
            $map[] = ['p.subject', 'like', "%$subject%"];
        }
        //状态管理
        if ($getStatus) {
            $map[] = ['p.status', '=', $getStatus];
        }
        //时间判断
        if ($getInterval) {
            switch ($getInterval) {
                case 1:
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime("-30 day")));
                    break;
                case 3:
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime("-3 months")));
                    break;
                case 6:
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime("-6 months")));
                    break;
                case 12:
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime("-1 year")));
                    break;
                case 36:
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime("-3 year")));
                    break;
                default:
                    $startTime = strtotime(date('Y-m-d 00:00:00', strtotime("-30 day")));
            }
            $map[] = ['p.dateline', 'between', $startTime . ',' . time()];
        }
        $list = $projectModel->getProjectsPage($map);
        $date = date('Y-m-d H:i:s', strtotime("+7 day"));
        $this->assign([
            'list' => $list,
            'date' => $date,
            'getStatus' => $getStatus,
            'interval' => $getInterval,
            'level' => $level]
        );
        return $this->fetch();
    }

    /**
     * 添加项目
     * @return array|mixed
     */
    public function create_item(){
        $project = new \app\model\Projects();
        $result = $project->createProject();
        return $result;
    }
    /**
     * 项目详情
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function details(){
        $project = new \app\model\Projects();
        $id = Request::param('id','','intval');
        $data = $project->getProject($id);
        $this->assign(['data'=>$data]);
        return $this->fetch();
    }
    /**
     * 更新项目状态
     * @return array
     */
    public function ed_project_status(){
        $project = new \app\model\Projects();
        $result = $project->edProjectStatus();
        return $result;
    }
    /**
     * 编辑项目
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ed_project()
    {
        $project = new \app\model\Projects();
        $id =Request::param('id','','intval');
        $data = $project->getProject($id);
        $this->assign(['data'=>$data]);
        return $this->fetch();
    }
    // ------------- quotation-----------
    public function quotation(){
        return $this->fetch();
    }
    //---------------products------------
    /**
     * 产品报价首页
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function products()
    {
        $products = new Products();
        $list = $products->getPage();
        $this->assign(['list'=>$list]);
        return $this->fetch();
    }
    /**
     * 添加产品报价
     * @return array
     */
    public function add_products(){
        $qp = new Products();
        $result = $qp->add();
        return $result;
    }
}