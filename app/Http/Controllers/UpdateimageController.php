<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class UpdateimageController extends Controller
{
    // 头像上传
    public function photo (Request $request){
        $path = $request->file('file')->store(
            '/public/photo', 'photo'
        );
        $str = 'http://'.$_SERVER['SERVER_NAME'].substr($path,6);
        $data = [
            'src' => $str,
        ];
        return status(200, '添加成功', $data);
    }

    // 轮播图上传
    public function banner_img (Request $request){
        $path = $request->file('file')->store(
            '/public/bannerimg', 'bannerimg'
        );
        $str = 'http://'.$_SERVER['SERVER_NAME'].substr($path,6);
        $data = [
            'src' => $str,
        ];
        return status(200, '添加成功', $data);
    }

    // 门店logo上传
    public function shop_logo (Request $request){
        $path = $request->file('file')->store(
            '/public/shoplogo', 'shoplogo'
        );
        $str = 'http://'.$_SERVER['SERVER_NAME'].substr($path,6);
        $data = [
            'src' => $str,
        ];
        return status(200, '添加成功', $data);
    }

    // 七牛云存储
    public function qiniu (Request $request){
        $file = $request->file('file');
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey ="Ad03_KpRkFUA-vxOqRS-xL7LzHPj1rLHYzLfRvLQ";
        $secretKey = "HIEHlvRcEHG055axV2LJARA__Rl6nPoIFPX90QKU";
        $bucket = "jiaran";
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = $file->getRealPath();
        // 上传到七牛后保存的文件名
        $key = sn_26() .uniqid().'.'.$file->getClientOriginalExtension();
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return status(400, '上传失败', $data);
        } else {
            $data = [
                'src' => 'http://img.jiaranjituan.cn/'.$key,
            ];
            return status(200, '上传成功', $data);
        }
    }
}
