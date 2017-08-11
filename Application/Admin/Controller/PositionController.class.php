<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Exception;
class PositionController extends CommonController {
    
    public function index(){
        $positions = D("Position")->getNormalPositions();
        $this->assign('positions',$positions);
    	$this->display();
    }
    
    public function setStatus() {
        $data = array(
            'id' => intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($data,'Position');
    }
    
    public function add() {
        if($_POST){
            if(!isset($_POST['name']) || !$_POST['name']){
                return show(0,'推荐位不能为空');
            }
            if(!isset($_POST['description']) || !$_POST['description']){
                return show(0,'简介不能为空');
            }
            if($_POST['id']){
                return $this->save($_POST);
            }
            try{
                $id = D("Position")->insert($_POST);
                if($id){
                    return show(1,'新增成功');
                }
                return show(0,'新增失败');
            }catch(Exception $e){
                return show(0,$e->getMessage());
            }
        }else{
            $this->display();
        }
    }
    
    public function edit() {
        $id = $_GET['id'];
        $position = D("Position")->find($id);

        $this->assign('vo',$position);
        $this->display();
    }
    
    public function save($data) {
        $id = $data['id'];
        unset($data['id']);
        
        try{
            $resId = D("Position")->updateById($id,$data);
            if($resId === false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

}