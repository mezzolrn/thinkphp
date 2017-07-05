<?php
namespace Admin\Controller;
use Think\Controller;

class CronController extends Controller {
 
    public function crontab_dumpmysql() {
        $result = D("Basic")->select();
        if(!result['dumpmysql']){
            die("系统没有开启自动备份");
        }
        
		$shell = "mysqldump ".C("DB_NAME")." > /tmp/cms".date("Ymd").".sql";
		exec($shell);
    }

    public function dumpmysql() {        
        $shell = "mysqldump ".C("DB_NAME")." > /tmp/cms".date("Ymd").".sql";
        
        exec($shell);
        return show(1,$shell);
	    
    }
    

 
}
