<?php
namespace Common\Api;
/**
 * 仿写CodeIgniter的FTP类
 * FTP基本操作：
 * 1) 登陆;     connect
 * 2) 当前目录文件列表; filelist
 * 3) 目录改变;   chgdir
 * 4) 重命名/移动;   rename
 * 5) 创建文件夹;    mkdir
 * 6) 删除;       delete_dir/delete_file
 * 7) 上传;       upload
 * 8) 下载        download
 *
 */
class Ftp {
     private $hostname  = '';
     private $username  = '';
     private $password  = '';
     private $port  = 21;
     private $passive = TRUE;
     private $debug   = TRUE;
     private $conn_id = FALSE;
     /**
      * 构造函数
      *
      * @param  array  配置数组 : $config = array('hostname'=>'','username'=>'','password'=>'','port'=>''...);
      */
     public function __construct($config = array()) {
      if(count($config) > 0) {
       $this->_init($config);
      }
     }
     /**
      * FTP连接
      *
      * @access public
      * @param  array  配置数组
      * @return boolean
      */
     public function connect($config = array()) {
      if(count($config) > 0) {
       $this->_init($config);
      }
      if(FALSE === ($this->conn_id = @ftp_connect($this->hostname,$this->port))) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_connect");
       }
       return FALSE;
      }
      if( ! $this->_login()) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_login");
       }
       return FALSE;
      }
      if($this->passive === TRUE) {
       ftp_pasv($this->conn_id, TRUE);
      }
      return TRUE;
     }
     /**
      * 文件夹是否存在
      * @param unknown_type $path
      */
     public function is_dir_exists($path)
     {
      return $this->chgdir($path);
     }
     /**
      * 目录改变
      *
      * @access public
      * @param  string 目录标识(ftp)
      * @param  boolean 
      * @return boolean
      */
     public function chgdir($path = '', $supress_debug = FALSE) {
      if($path == '' OR ! $this->_isconn()) {
       return FALSE;
      }
      $result = @ftp_chdir($this->conn_id, $path);
      if($result === FALSE) {
       if($this->debug === TRUE AND $supress_debug == FALSE) {
        $this->_error("ftp_unable_to_chgdir:dir[".$path."]");
       }
       return FALSE;
      }
      return TRUE;
     }
     /**
      * 目录生成
      *
      * @access public
      * @param  string 目录标识(ftp)
      * @param  int  文件权限列表 
      * @return boolean
      */
     public function mkdir($path = '', $permissions = NULL) {
      if($path == '' OR ! $this->_isconn()) {
       return FALSE;
      }
      $result = @ftp_mkdir($this->conn_id, $path);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_mkdir:dir[".$path."]");
       }
       return FALSE;
      }
      if( ! is_null($permissions)) {
       $this->chmod($path,(int)$permissions);
      }
      return TRUE;
     }
     /**
      * 上传
      *
      * @access public
      * @param  string 本地目录标识
      * @param  string 远程目录标识(ftp)
      * @param  string 上传模式 auto || ascii
      * @param  int  上传后的文件权限列表 
      * @return boolean
      */
     public function upload($localpath, $remotepath, $mode = 'auto', $permissions = NULL) {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      if( ! file_exists($localpath)) {
       if($this->debug === TRUE) {
        $this->_error("ftp_no_source_file:".$localpath);
       }
       return FALSE;
      }
      if($mode == 'auto') {
       $ext = $this->_getext($localpath);
       $mode = $this->_settype($ext);
      }
      $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
      $result = @ftp_put($this->conn_id, $remotepath, $localpath, $mode);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_upload:localpath[".$localpath."]/remotepath[".$remotepath."]");
       }
       return FALSE;
      }
      if( ! is_null($permissions)) {
       $this->chmod($remotepath,(int)$permissions);
      }
      return TRUE;
     }
     /**
      * 下载
      *
      * @access public
      * @param  string 远程目录标识(ftp)
      * @param  string 本地目录标识
      * @param  string 下载模式 auto || ascii 
      * @return boolean
      */
     public function download($remotepath, $localpath, $mode = 'auto') {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      if($mode == 'auto') {
       $ext = $this->_getext($remotepath);
       $mode = $this->_settype($ext);
      }
      $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
      $result = @ftp_get($this->conn_id, $localpath, $remotepath, $mode);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_download:localpath[".$localpath."]-remotepath[".$remotepath."]");
       }
       return FALSE;
      }
      return TRUE;
     }
     /**
      * 重命名/移动
      *
      * @access public
      * @param  string 远程目录标识(ftp)
      * @param  string 新目录标识
      * @param  boolean 判断是重命名(FALSE)还是移动(TRUE) 
      * @return boolean
      */
     public function rename($oldname, $newname, $move = FALSE) {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      $result = @ftp_rename($this->conn_id, $oldname, $newname);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $msg = ($move == FALSE) ? "ftp_unable_to_rename" : "ftp_unable_to_move";
        $this->_error($msg);
       }
       return FALSE;
      }
      return TRUE;
     }
     /**
      * 删除文件
      *
      * @access public
      * @param  string 文件标识(ftp)
      * @return boolean
      */
     public function delete_file($file) {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      $result = @ftp_delete($this->conn_id, $file);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_delete_file:file[".$file."]");
       }
       return FALSE;
      }
      return TRUE;
     }
     /**
      * 删除文件夹
      *
      * @access public
      * @param  string 目录标识(ftp)
      * @return boolean
      */
     public function delete_dir($path) {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      //对目录宏的'/'字符添加反斜杠'\'
      $path = preg_replace("/(.+?)\/*$/", "\\1/", $path);
      //获取目录文件列表
      $filelist = $this->filelist($path);
      if($filelist !== FALSE AND count($filelist) > 0) {
       foreach($filelist as $item) {
        //如果我们无法删除,那么就可能是一个文件夹
        //所以我们递归调用delete_dir()
        if( ! @delete_file($item)) {
         $this->delete_dir($item);
        }
       }
      }
      //删除文件夹(空文件夹)
      $result = @ftp_rmdir($this->conn_id, $path);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_delete_dir:dir[".$path."]");
       }
       return FALSE;
      }
      return TRUE;
     }
     /**
      * 修改文件权限
      *
      * @access public
      * @param  string 目录标识(ftp)
      * @return boolean
      */
     public function chmod($path, $perm) {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      //只有在PHP5中才定义了修改权限的函数(ftp)
      if( ! function_exists('ftp_chmod')) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_chmod(function)");
       }
       return FALSE;
      }
      $result = @ftp_chmod($this->conn_id, $perm, $path);
      if($result === FALSE) {
       if($this->debug === TRUE) {
        $this->_error("ftp_unable_to_chmod:path[".$path."]-chmod[".$perm."]");
       }
       return FALSE;
      }
      return TRUE;
     }
     /**
      * 获取目录文件列表
      *
      * @access public
      * @param  string 目录标识(ftp)
      * @return array
      */
     public function filelist($path = '.') {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      return ftp_nlist($this->conn_id, $path);
     }
     /**
      * 关闭FTP
      *
      * @access public
      * @return boolean
      */
     public function close() {
      if( ! $this->_isconn()) {
       return FALSE;
      }
      return @ftp_close($this->conn_id);
     }
}
    // class class_ftp end

    /************************************** 测试 ***********************************
    $ftp = new class_ftp('192.168.100.143',21,'user','pwd'); // 打开FTP连接
    //$ftp->up_file('aa.txt','a/b/c/cc.txt'); // 上传文件
    //$ftp->move_file('a/b/c/cc.txt','a/cc.txt'); // 移动文件
    //$ftp->copy_file('a/cc.txt','a/b/dd.txt'); // 复制文件
    //$ftp->del_file('a/b/dd.txt'); // 删除文件
    $ftp->close(); // 关闭FTP连接
    ******************************************************************************/
?>