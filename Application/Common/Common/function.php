<?php

function show($status, $message, $data=array()) {
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data,
    );
    
    exit(json_encode($result));
}

function getMd5Password($password) {
    return md5($password.C('MD5_PRE'));
}

function getMenuType($type) {
    return $type ? '后台菜单' : '前端导航';
}

function status($status) {
    if($status == 0){
        $res = '关闭';
    }elseif($status == 1){
        $res = '正常';
    }elseif($status == -1){
        $res = '删除';
    }
    return $res;
}

function getAdminMenuUrl($nav) {
    $url = '/admin.php?c='.$nav['c'].'&a='.$nav['f'];
    return $url;
}

function getActive($navc) {
    $c = strtolower(CONTROLLER_NAME);
    if(strtolower($navc) == $c){
        return 'class="active"';
    }
    return '';
}

function showKind($status,$data) {
    header('Content-type:application/json;charset=UTF-8');
    exit(json_encode(array('error'=>$status,'url'=>$data)));
}

function getLoginUsername() {
    return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username'] : '';
}

function getCatName($navs,$id) {
    foreach($navs as $nav){
        $navList[$nav['menu_id']] = $nav['name'];
    }
    return isset($navList[$id]) ? $navList[$id] : '';
}

function getCopyFromById($id) {
    $copyFrom = C("COPY_FROM");
    return isset($copyFrom[$id]) ? $copyFrom[$id] : '';
}

function isThumb($thumb) {
    if($thumb){
        return '<span style="color:red">有</span>';
    }
    return '无';
}








?>