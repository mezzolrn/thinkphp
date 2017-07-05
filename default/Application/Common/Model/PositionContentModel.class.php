<?php
namespace Common\Model;
use Think\Model;

/**
 * 推荐位内容操作
 */
class PositionContentModel extends Model {
    
    private $_db;
    
    public function __construct() {
        $this->_db = M('PositionContent');
    }
    
    public function insert($data = array()) {
        if(!is_array($data) || !$data){
            return 0;
        }
        $data['create_time'] = time();
        return $this->_db->add($data);
    }
    
    public function select($data=array(),$limit=0) {
        if($data['title']){
            $data['title'] = array('like','%'.$data['title'].'%');
        }
        $this->_db->where($data)->order('listorder desc,id desc');
        if($limit){
            $this->_db->limit($limit);
        }
        $list = $this->_db->select();
        return $list;
    }
    
    public function find($id) {
        return $data = $this->_db->where('id='.$id)->find();
    }
    
    public function updateStatusById($id,$status) {
        if(!is_numeric($status)){
            throw_exception('status不能为非数字');
        }
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        $data['status'] = $status;
        return $this->_db->where('id='.$id)->save($data);
    }
    
    public function updateById($id,$data) {
        if(!is_array($data) || !$data){
            throw_exception('更新的数据不合法');
        }
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        $data['create_time'] = time();
        return $this->_db->where('id='.$id)->save($data);
    }
    
    public function updateListorderById($id,$listorder) {
        if(!$id || !is_numeric($id)){
             throw_exception("ID不合法");
        }
        $data = array(
            listorder => intval($listorder),   
        );
        return $this->_db->where("id=".$id)->save($data);
    }
    
}