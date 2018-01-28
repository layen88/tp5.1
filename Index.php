<?php
namespace app\index\controller;
use think\Controller;
// use think\Request;
use think\facade\Request;

class Index extends Controller
{
    public function index()
    {
        $info = Request::file();
		print_r($info);
    }

    public function upload($name = 'ThinkPHP5')
    {	
    	if(Request::isGet()){
       		 return $this->fetch();
    	}elseif(Request::isPost()){
    		$file = Request::file('upload');
    		$info = $file->validate(['size'=>12121212,'ext'=>'jpg,png,gif'])->move('./uploads');
			if($info){
		        $path ="./uploads/".$info->getSaveName();
		        $base64 =$this->imgToBase64($path);
		        return $base64;
		    }else{
		        // 上传失败获取错误信息
		        echo $file->getError();
		    }
    	}
    }

    /**
	 * 获取图片的Base64编码(不支持url)
	 * @date 2017-02-20 19:41:22
	 *
	 * @param $img_file 传入本地图片地址
	 *
	 * @return string
	 */
	function imgToBase64($img_file) {

	    $img_base64 = '';
	    if (file_exists($img_file)) {
	        $app_img_file = $img_file; // 图片路径
	        $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

	        //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
	        $fp = fopen($app_img_file, "r"); // 图片是否可读权限

	        if ($fp) {
	            $filesize = filesize($app_img_file);
	            $content = fread($fp, $filesize);
	            $file_content = chunk_split(base64_encode($content)); // base64编码
	            switch ($img_info[2]) {           //判读图片类型
	                case 1: $img_type = "gif";
	                    break;
	                case 2: $img_type = "jpg";
	                    break;
	                case 3: $img_type = "png";
	                    break;
	            }

	            $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

	        }
	        fclose($fp);
	    }

	    return $img_base64; //返回图片的base64
	}

}
