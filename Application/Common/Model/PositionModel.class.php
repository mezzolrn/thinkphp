<?php
namespace Common\Model;
use Think\Model;

/**
 * 推荐位操作
 */
class PositionModel extends Model {
    
    private $_db;
    
    public function __construct() {
        $this->_db = M('Position');
    }
    
    public function insert($data = array()) {
        if(!is_array($data) || !$data){
            return 0;
        }
        $data['create_time'] = time();
        return $this->_db->add($data);
    }
    
    public function select($data=array()) {
        $conditions = $data;
        $list = $this->_db->where($condition)->order('id')->select();
        return $list;
    }
    
    public function find($id) {
        return $data = $this->_db->where('id='.$id)->find();
    }
    
    public function updateById($id,$data) {
        if(!$id || !is_numeric($id)){
            throw_exception("ID不合法");
        }
        if(!$data || !is_array($data)){
            throw_exception("数据不合法");
        }
        $data['update_time'] = time();
        return $this->_db->where('id='.$id)->save($data);
    }
    
    //获取正常的推荐位内容
    public function getNormalPositions() {
        $conditions = array('status'=>array('neq',-1));
        $list = $this->_db->where($conditions)->order('id')->select();
        return $list;
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
    
    public function getCount($data=array()) {
        $conditions = $data;
        $conditions['status'] = $conditions['status'] ? $conditions['status'] : array('eq',1);
        
        return $this->_db->where($conditions)->count();
    }
    
}