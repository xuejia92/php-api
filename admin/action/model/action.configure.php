<?php 

class Action_Configure {
    
    private static $_instance;
    
    public static function factory() {
        if (!is_object(self::$_instance)){
            self::$_instance = new Action_Configure();
        }
        return self::$_instance;
    }
    
    
    public function setView($data){
        $result = Activity_Config::$activity;
        
        $id = $_REQUEST['id'];
        $item   = $result[$id];
        $item['name'] = $id;
        
        $aCid    = $item['closeCid'];
        $channel = Base::factory()->getChannel();
        
        foreach ($aCid as $cid){
            $item['cname'] .=$channel[$cid].',';
        }
        $item['cname'] = rtrim($item['cname'],',');
        
        $aGameid = explode(',', $item['closeGameid']);
        
        $item['closeGame'] = $aGameid;
        $game    = Config_Game::$game;
        
        foreach ($aGameid as $gameid){
            if ($gameid){
                $item['gname'] .=$game[$gameid].',';
            }
        }
        
        return $item;
    }
    
    
    public function sort($data){
        $ids = $data['ids'];
        $pos = $data['pos'];
    
        if(empty($ids) || empty($pos)){
            return false;
        }
    
        $id2sort = array();
        foreach ($ids as $k=>$id) {
            $id2sort[$id] = $pos[$k];
        }
    
        asort($id2sort);
        $sort2id = array_keys($id2sort);
        $count = count($pos);
    
        $new_sort_array = array();
        for($i=0;$i<$count;$i++){
            $id = $sort2id[$i];
            $act = Loader_Redis::common()->hGet('aa', $id);
            array_push($new_sort_array, $act);
        }
    
        Loader_Redis::common()->hMset('bb', $new_sort_array);
        Loader_Redis::common()->delete('aa');
        Loader_Redis::common()->renameKey('bb', 'aa');
    
        return true;
    }
}