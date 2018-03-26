<?php 
$path = PRODUCTION_SERVER? '' : 'test_';

class Action_Actionlist {
    
    private static $_instance;
    
    public static function factory () {
        if (!is_object(self::$_instance)){
            self::$_instance = new Action_Actionlist();
        }
        return self::$_instance;
    }
    
    public function update($data){
        $name       = $data['name'];
        $subject    = $data['subject'];
        $time       = $data['time'];
        $desc       = $data['desc'];
        $image      = $data['image'];
        $icon       = $data['icon'];
        $url        = $data['url'];
        $open       = $data['open'];
        $start_time = $data['start_time'];
        $end_time   = $data['end_time'];
        $openCid    = $data['cid_cid'] ? $data['cid_cid'] : '';
        $closeCtype = rtrim(implode(',', $data['closeCtype'] ? $data['closeCtype'] : ''),',');
        $closeGameid= rtrim(implode(',', $data['closeGameid'] ? $data['closeGameid'] : ''),',');
        $new        = $data['new'];
        $buttonName = $data['buttonName'];
        $consume    = $data['consume'];
    
        if (!$name || !$subject || !$desc || !$image || !$icon || !$url || !$start_time || !$end_time || !$buttonName) {
            return false;
        }
    
        $array  = array(
            'subject'       => $subject,
            'time'          => $time ? $time : '',
            'desc'          => $desc,
            'image'         => $image,
            'icon'          => $icon,
            'url'           => $url,
            'open'          => $open,
            'start_time'    => strtotime($start_time),
            'end_time'      => strtotime($end_time),
            'openCid'       => $openCid ? $openCid : '',
            'closeCtype'    => $closeCtype ? $closeCtype : '',
            'closeGameid'   => $closeGameid ? $closeGameid : '',
            'new'           => $new,
            'buttonName'    => $buttonName,
            'consume'       => $consume ? $consume : ''
        );
    
        $record = Loader_Redis::common()->get(PATH_KEY.'Activity_Config', false, true);
        $exist  = array_key_exists($name, $record);
    
        $record[$name] = $array;
        $status = Loader_Redis::common()->set(PATH_KEY.'Activity_Config', $record, false, true);
    
        if ($status) {
            return true;
        }else {
            return false;
        }
    }
    
    public function setList () {
        $result = Loader_Redis::common()->get(PATH_KEY.'Activity_Config', false, true);
        
        $id = $_REQUEST['id'];
        $item   = $result[$id];
        $item['name'] = $id;
        
        $aTypeid = explode(',', $item['closeCtype']);
        $item['closeType'] = $aTypeid;
        
        
        $aCid    = explode(',', $item['openCid']);
        $channel = Base::factory()->getChannel();
        foreach ($aCid as $cid){
            $item['cname'] .=$channel[$cid].',';
        }
        $item['cname'] = rtrim($item['cname'],',');
        
        
        $aGameid = explode(',', $item['closeGameid']);
        $item['closeGame'] = $aGameid;
        
        return $item;
    }
    
    public function getChannel () {
        
        $result = Loader_Redis::common()->get(PATH_KEY.'Activity_Config', false, true);
        
        $id = $_REQUEST['id'];
        $item   = $result[$id];
        $item['name'] = $id;
        
        $row = array();
        $rows['cname'] = explode(',', $item['openCid']);
        
        $i = 0;
        $channel = Base::factory()->getChannel();
        
        foreach ($channel as $cid=>$cname) {
            $rows['cid'][$i]['cid']   = $cid;
            $rows['cid'][$i]['cname'] = $cname;
            $i++;
        }
        
        return $rows;
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
        $record = Loader_Redis::common()->get(PATH_KEY.'Activity_Config', false, true);
        
        for($i=0;$i<$count;$i++){
            $key = $sort2id[$i];
            $act = $record[$key];
            $new_sort_array[$key] = $act;
        }
        
        Loader_Redis::common()->set(PATH_KEY.'Activity_Config_Copy', $new_sort_array, false, true);
        Loader_Redis::common()->delete(PATH_KEY.'Activity_Config');
        Loader_Redis::common()->renameKey(PATH_KEY.'Activity_Config_Copy', PATH_KEY.'Activity_Config');
    
        return true;
    }
    
    public function del($data){
        $id = $data['id'];
    
        $new_sort_array = array();
        $record = Loader_Redis::common()->get(PATH_KEY.'Activity_Config', false, true);
    
        foreach ($record as $key=>$val) {
            if ($key==$id) {
                continue;
            }
            $new_sort_array[$key] = $val;
        }
    
        Loader_Redis::common()->set(PATH_KEY.'Activity_Config_Copy', $new_sort_array, false, true);
        Loader_Redis::common()->delete(PATH_KEY.'Activity_Config');
        Loader_Redis::common()->renameKey(PATH_KEY.'Activity_Config_Copy', PATH_KEY.'Activity_Config');
    
        return true;
    }
}