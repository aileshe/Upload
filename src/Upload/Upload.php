<?php
/**
 * PHP文件上传类
 * Author: Dejan  QQ:673008865
 * Date  : 2018.04.23
 */
namespace Dj;

class Upload{
    public $token = NULL;    # 上传验证口令(string)
    public $frm_name = NULL; # 文件表单name值(string)
    public $ext = NULL;      # 允许上传文件的后缀(一维的索引数组)
    public $size = NULL;     # 允许上传文件的大小(int)
    public $mime = NULL;     # 允许上传文件的MIME(一维的索引数组)
    
    /**
     * 文件接收入口 - 单文件、多文件上传
     * @param  String  $frm_name  提交表单name
     */
    public function __construct($frm_name = 'file'){
        $this->frm_name = $frm_name;
    }
    
    /**
     * 设置上传口令 token
     * @param  String  $token     验证口令
     * @return ClassObject
     */
    public function token($token = NULL){
        $this->token = $token;
        return $this;
    }
    
    /**
     * 过滤设置
     * @param  Array   $filter   过滤规则 ['ext'=>'后缀名限制','size'=>193038] = ['ext'=>'png,jpg,gif','size'=>193038]
     * @return ClassObject
     */
    private function filter($filter){
        if($filter != NULL){
            # 文件格式过滤 - 文件后缀
            if(isset($filter['ext']) && !empty($filter['ext'])){
                if(is_array($filter['ext'])){
                    $this->ext = $filter['ext'];
                }else{
                    $this->ext = explode(',', $filter['ext']);
                }
            }
            
            # 文件格式过滤 - MIME
            if(isset($filter['mime']) && !empty($filter['mime'])){
                if(is_array($filter['mime'])){
                    $this->mime = $filter['mime'];
                }else{
                    $this->mime = explode(',', $filter['mime']);
                }
            }
            
            # 文件大小过滤
            if(isset($filter['size']) && !empty($filter['size'])){
                $this->size = (int)$filter['size'];
            }
        }
        return $this;
    }
    
    /**
     * 文件接收入口 - 单文件、多文件上传
     * @param  String  $storage  文件存储路径
     * @param  Array   $filter   过滤规则 ['ext'=>'后缀名限制','size'=>193038] = ['ext'=>'png,jpg,gif','size'=>193038]
     * @return Int  [0上传提交空文件  -1上传失败  -2文件存储路径不合法  -5验证token为空]
     */
    public function save($storage = NULL, $filter = NULL){
        # token 验证
        if($this->token != NULL){
            if(!isset($_POST['token']) || $_POST['token'] != $this->token){
                return -5;
            }
        }
        
        # 存储路径合法判断
        if(empty($storage) || !is_string($storage)){
            return -2;
        }
        
        # 初始化过滤设置
        $this->filter($filter);
        
        if(!empty($_FILES) && isset($_FILES[$this->frm_name])){
            # 判断存储目录是否存在,无则自动创建
            if(!is_dir($storage)){
                mkdir($storage,'0777',true);
                chmod($storage,0777);
            }
            
            $save_path = realpath($storage); # 上传文件存储目录的绝对路径
            $filelist = array(); # 文件数组
            
            $files = $_FILES[$this->frm_name]; # 简化数组
            if(is_string($files['name'])){
                # 单文件上传
                $check_res = $this->file_check($files);
                if($check_res === 0){ # 0 校检没问题,
                    $filelist[] = $files;
                }else{
                    return $check_res;
                }
            }else{
                # 多文件上传
                $file = array();
                foreach($files['name'] as $k=>$v){
                    $file = array(
                        'name'    => $v,
                        'type'    => $files['type'][$k],
                        'tmp_name'=> $files['tmp_name'][$k],
                        'error'   => $files['error'][$k],
                        'size'    => $files['size'][$k]
                    );
                    
                    $check_res = $this->file_check($file);
                    if($check_res === 0){ # 0 校检没问题
                        $filelist[] = $file;
                    }else{
                        return $check_res;
                    }
                }
            }
            
            # 从临时空间里提取出文件到真实路径、文件信息补全
            $new_arr = array();
            if(count($filelist) === 1){
                # 单文件上传
                $ext = $this->get_file_ext($filelist[0]['name']); # 文件后缀名
                $fileName = $ext == '' ? $this->uuid() : $this->uuid().'.'.$ext;
                move_uploaded_file($filelist[0]['tmp_name'], $save_path.'/'.$fileName);
                
                # - 上传文件回调数据信息
                $new_arr['name']     = $filelist[0]['name']; # 文件上传时的原名称
                $new_arr['ext']      = $ext; # 文件后缀名
                $new_arr['savepath'] = $save_path.'/'.$fileName; # 文件存储绝对路径(包含文件名)
                $new_arr['savename'] = $fileName; # 文件保存在服务器上名称
                $new_arr['size']     = $filelist[0]['size']; # 文件大小(单位:字节)
                $new_arr['md5']      = md5_file($save_path.'/'.$fileName); # 文件MD5
            }else{
                # 多文件上传
                foreach($filelist as $v){
                    $ext = $this->get_file_ext($v['name']); # 文件后缀名
                    $fileName = $ext == '' ? $this->uuid() : $this->uuid().'.'.$ext;
                    move_uploaded_file($v['tmp_name'], $save_path.'/'.$fileName);
                
                    # - 上传文件回调数据信息
                    $new_arr[] = array(
                    'name'     => $v['name'] , # 文件上传时的原名称
                    'ext'      => $ext ,       # 文件后缀名
                    'savepath' => $save_path.'/'.$fileName , # 文件存储绝对路径(包含文件名)
                    'savename' => $fileName ,  # 文件保存在服务器上名称
                    'size'     => $v['size'] , # 文件大小(单位:字节)
                    'md5'      => md5_file($save_path.'/'.$fileName) # 文件MD5
                    );
                }
            }
            
            return $new_arr;
        }else{
            return 0; # 提交上传的文件为空
        }
    }
    
    /**
     * 获取文件后缀名
     * @param  String  $file  文件名
     * @return String
     */
    private function get_file_ext($file){
        $r_offset = strrpos($file, '.');
        if($r_offset){
            $ext = substr($file, $r_offset + 1);
        }else{
            $ext = '';
        }
        
        return $ext;
    }
    
    /**
     * 文件唯一名称生成
     * @param  Int  $size  随机字符长度, 默认8
     * @return String
     */
    private function uuid($size=8){
        $chars='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $rand_str=null;
        for($i=0;$i<$size;$i++){
            $rand_str.=$chars[mt_rand(0,61)];
        }
        return time().$rand_str;
    }
    
    /**
     * 文件校检
     * @param  Array  $file  单个文件数组 
     * @return Int  [0校检通过   -1上传文件失败   -3非法格式文件  -4文件大小不合符]
     */
    private function file_check($file){
        # 上传文件是否存在失败
        if($file['error'] !== 0){
            return -1;
        }
        
        # 上传文件是否存在不合法的格式文件
        if($this->ext != NULL || $this->mime != NULL){
            if(@!(in_array($this->get_file_ext($file['name']), $this->ext) || in_array($file['type'], $this->mime))){
                return -3;
            }
        }
        
        # 上传文件的大小不符合规定的大小
        if($this->size != NULL){
            if($file['size'] > $this->size){
                return -4;
            }
        }
        
        return 0;
    }
}