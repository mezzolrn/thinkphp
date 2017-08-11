<?php
namespace Admin\Controller;
use Think\Controller;

class MenuController extends CommonController {
    
    public function add() {
        if($_POST) {
            if(!$_POST['name'] || !isset($_POST['name'])){
                return show(0,'菜单名不能为空');
            }
            if(!$_POST['m'] || !isset($_POST['m'])){
                return show(0,'模块名不能为空');
            }
            if(!$_POST['c'] || !isset($_POST['c'])){
                return show(0,'控制器不能为空');
            }
            if(!$_POST['f'] || !isset($_POST['f'])){
                return show(0,'方法名不能为空');
            }
            
            if($_POST['menu_id']){
                return $this->save($_POST);
            }
            
            $menuId = D("Menu")->insert($_POST);
            if($menuId) {
                return show(1,'新增成功',$menuId);
            }
            return show(0,'新增失败',$menuId);
        } else {
            $this->display();
        }
    }
    
    public function index() {
        $data = array();
        if(isset($_REQUEST['type']) && in_array($_REQUEST['type'],array(0,1))){
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type',$data['type']);
        }else{
            $this->assign('type',-1);
        }
        /**
         * 分页操作逻辑
         */
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 3;
        $menus = D("Menu")->getMenus($data,$page,$pageSize);
        $menusCount = D("Menu")->getMenusCount($data);
        
        $res = new \Think\Page($menusCount,$pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes',$pageRes);
        $this->assign('menus',$menus);
        $this->display();
    } 
    
    public function edit() {
        $menuId = $_GET['id'];
        
        $menu = D("Menu")->find($menuId);
        $this->assign('menu',$menu);
        $this->display();
    }
    
    public function save($data) {
        $menuId = $data['menu_id'];
        unset($data['menu_id']);
        
        try{
            $id = D("Menu")->updateMenuById($menuId,$data);
            if($id === false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        
    }
    
    public function setStatus() {
        $data = array(
            'id' => intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($data,'Menu');
    }
    
    public function listorder() {
        return parent::listorder("Menu");
    }
    
    
    
}