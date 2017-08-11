<?php

namespace Common\Model;
use Think\Model;

class AdminModel extends Model {
    
    private $_db = '';
    
    public function __construct() {
        $this->_db = M('admin');
    }
    
    public function getAdminByUsername($username) {
        $ret = $this->_db->where('username="'.$username.'"')->find();
        return $ret;
    }
    
    public function getAdminById($id){
        $ret = $this->_db->where('admin_id="'.$id.'"')->find();
        return $ret;
    } 
    
    public function getAdmins() {
        $conditions['status'] = array('neq',-1);
        $ret = $this->_db->where($conditions)->select();
        return $ret;
    }
    
    public function updateStatusById($id,$status) {
        if(!is_numeric($id) || !$id){
            throw_exception("ID不合法");
        }
        if(!is_numeric($status)){
            throw_exception("状态不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('admin_id='.$id)->save($data);
    }
    
    public function insert($data = array()) {
        if(!$data || !is_array($data)){
            return 0;
        }
        return $this->_db->add($data);
    }
    
    public function updateByAdminId($id,$data) {
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新的数据不合法');
        }
       return $this->_db->where('admin_id='.$id)->save($data);
    }
    
    public function getLastLoginUsers() {
        $time = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $data = array(
            'status' => 1,
            'lastlogintime' => array("gt",$time),
        );
        
        $res = $this->_db->where($data)->count();
        return $res['tp_count']; 
    }
    
}