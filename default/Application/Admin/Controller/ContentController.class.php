<?php
/**
 * 后台index相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * 文章内容管理
 */
class ContentController extends CommonController {
    
    public function index(){
        $conds = array();
        if($_GET['title']){
            $conds['title'] = $_GET['title'];
            $this->assign('title',$conds['title']);
        }
        if($_GET['catid']){
            $conds['catid'] = $_GET['catid'];
            $this->assign('catid',$conds['catid']);
        }else{
            $this->assign('catid',-1);
        }
        
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 10;
        
        $news = D("News")->getNews($conds,$page,$pageSize);
        
        $count = D("News")->getNewsCount($conds);
        $res = new \Think\Page($count,$pageSize);
        $pageres = $res->show();
        
        $positions = D("Position")->getNormalPositions();
        
        $this->assign('pageres',$pageres);
        $this->assign('news',$news);
        $this->assign('positions',$positions);
        $this->assign('webSiteMenu',D("Menu")->getBarMenus());
    	$this->display();
    }
    
    public function add(){
        if($_POST){
            if(!isset($_POST['title']) || !$_POST['title']){
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']){
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']){
                return show(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']){
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']){
                return show(0,'内容不存在');
            }
            
            if($_POST['news_id']){
                return $this->save($_POST);
            }
            
            $newsId = D("News")->insert($_POST);
            if($newsId){
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->insert($newsContentData);
                if($cId){
                    return show(1,'新增成功');
                }else{
                    return show(1,'主表成功，副表失败');
                }
            }else{
                return show(0,'新增失败');
            }
    
        }else{
            $webSiteMenu = D("Menu")->getBarMenus();
            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom = C("COPY_FROM");
            $this->assign('webSiteMenu',$webSiteMenu);
            $this->assign('titleFontColor',$titleFontColor);
            $this->assign('copyfrom',$copyFrom);
    	    $this->display();
        }
    }
    
    public function edit() {
        
        $newsId = $_GET['id'];
        if(!$newsId){
            //执行跳转
            $this->redirect('/admin.php?c=content');
        }
        $news = D("News")->find($newsId);
        if(!$news){
            //执行跳转
            $this->redirect('/admin.php?c=content');
        }
        $newsContent = D("NewsContent")->find($newsId);
        if($newsContent){
            $news['content'] = $newsContent['content'];
        }
        
        $webSiteMenu = D("Menu")->getBarMenus();
        $titleFontColor = C("TITLE_FONT_COLOR");
        $copyFrom = C("COPY_FROM");
        $this->assign('webSiteMenu',$webSiteMenu);
        $this->assign('titleFontColor',$titleFontColor);
        $this->assign('copyfrom',$copyFrom);
        $this->assign('news',$news);
        $this->display();
    }
    
    public function save($data) {
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try{
            $id =  D("News")->updateById($newsId,$data);
            $newsContentData['content'] = $data['content'];
            $condId = D("NewsContent")->updateNewsById($newsId,$newsContentData);
            if($id===false || $condId===false){
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
        return parent::setStatus($data,'News');
    }
    
    public function listorder() {
        return parent::listorder("News");
    }
    
    public function push() {
        try{
            $jumpUrl = $_SERVER['HTTP_REFERER'];
            $positionId =  intval($_POST['position_id']);
            $newsId = $_POST['push'];
        
            if(!$newsId || !is_array($newsId)){
                return show(0,'请选择推荐的文章ID');
            }
            if(!$positionId){
                return show(0,'没有选择推荐位');
            }
            $news = D("News")->getNewsByNewsIdIn($newsId);
            if(!news){
                return show(0,'没有相关内容');
            }
            foreach($news as $new){
                $data = array(
                    'position_id' => $positionId, 
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => $new['status'],
                    'create_time' => time(),
                );
                $position = D("PositionContent")->insert($data);
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功',array('jump_url'=>$jumpUrl));
    }
    
}