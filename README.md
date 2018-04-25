# Upload是什么?
一个PHP文件上传组件，该组件简化了上传文件的验证使用会更简单优雅！

# 安装
通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者直接运行下面的命令。
```
composer require aileshe/upload:*
```
放入composer.json文件中
```
    "require": {
        "aileshe/upload": "*"
    }
```
然后运行
```
composer update
```

# 基本用法
以下是一个模拟 "单文件上传" 和 "多文件上传" 的Demo表单HTML代码
```
<!-- 本地单文件上传 Start-->
<div>
    <span>本地单文件上传</span>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file"/>
        <input type="submit" value="上传"/>
    </form>
</div>
<!-- 本地单文件上传 End-->
    
<!-- 本地多文件上传 Start-->
<div style="margin-top:20px;">
    <span>本地多文件上传</span>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file[]"/><br/>
        <input type="file" name="file[]"/><br/>
        <input type="file" name="file[]"/>
        <input type="submit" value="上传"/>
    </form>
</div>
<!-- 本地多文件上传 End-->
```
upload.php 代码如下
```
$upload = new \Dj\Upload();
$filelist = $upload->save('./upload');
if(is_array($filelist)){
    # 返回数组，文件就上传成功了
    print_r($filelist);
}else{
    # 如果返回负整数(int)就是发生错误了
    $error_msg = [-1=>'上传失败',-2=>'文件存储路径不合法',-3=>'上传非法格式文件',-4=>'文件大小不合符规定',-5=>'token验证错误'];
    echo $error_msg[$filelist];
}
```
上传成功返回打印结果
```
# 单文件上传
Array
(
    [name] => 919ff6986614ada.jpg    // 上传时的原文件名
    [ext] => jpg                     // 文件后缀名
    [mime] => image/jpeg             // 文件MIME
    [size] => 171635                 // 文件大小(单位:字节)
    [savename] => 1524626782VGdnXS50.jpg    // 文件上传后在服务器上存储的名称
    [savepath] => E:/WWW/composer/upload_dev/upload/1524626782VGdnXS50.jpg  // 上传到服务器的存储绝对路径
    [url] => http://upload.a.com/upload/1524626782VGdnXS50.jpg  // 文件访问URL地址
    [uri] => /upload/1524626782VGdnXS50.jpg                     // 文件访问URI地址
    [md5] => 6308045756c126c8b823f4ade0bad77d                   // 文件MD5
)

# 多文件上传
Array
(
    [0] => Array
        (
            [name] => dejan.jpg
            [ext] => jpg
            [mime] => image/jpeg
            [size] => 1964
            [savename] => 1524627074JQLQuKXs.jpg
            [savepath] => E:/WWW/composer/upload_dev/upload/1524627074JQLQuKXs.jpg
            [url] => http://upload.a.com/upload/1524627074JQLQuKXs.jpg
            [uri] => /upload/1524627074JQLQuKXs.jpg
            [md5] => 9382a7b44ea865519c82d077cd6346b0
        )

    [1] => Array
        (
            [name] => 《系统安装手册》.docx
            [ext] => docx
            [mime] => application/vnd.openxmlformats-officedocument.wordprocessingml.document
            [size] => 1041956
            [savename] => 1524627074oQVSkUO2.docx
            [savepath] => E:/WWW/composer/upload_dev/upload/1524627074oQVSkUO2.docx
            [url] => http://upload.a.com/upload/1524627074oQVSkUO2.docx
            [uri] => /upload/1524627074oQVSkUO2.docx
            [md5] => 9f1c186790769c09a9318eb352deb114
        )

    [2] => Array
        (
            [name] => 测试导入用户.xlsx
            [ext] => xlsx
            [mime] => application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
            [size] => 9249
            [savename] => 1524627074LUkMlrPQ.xlsx
            [savepath] => E:/WWW/composer/upload_dev/upload/1524627074LUkMlrPQ.xlsx
            [url] => http://upload.a.com/upload/1524627074LUkMlrPQ.xlsx
            [uri] => /upload/1524627074LUkMlrPQ.xlsx
            [md5] => b1f1c10005fcf0a2b59326a3aa3af032
        )

)

```
### $upload->save($storage, $allow, $host) 

> \- storage [string]

上传文件到哪的存储路径

> \- allow (optional) [array]

文件上传过滤允许规则定义

> \- host (optional) [string]

上传到服务器后文件的URL访问域名

### $upload->save($storage, $host) 

> \- storage [string]

上传文件到哪的存储路径

> \- host (optional) [string]

上传到服务器后文件的URL访问域名



#### 返回值参照:

返回值     |  说明
:--------:|:----------:
Array()   | 上传文件成功
-1        | 上传失败
-2        | 指定上传文件的存储路径不合法
-3        | 上传非法格式文件
-4        | 文件大小不合符规定
-5        | token验证错误

# 高级用法
#### 1) 设置上传文件表单name, 默认是'file'
```
new \Dj\Upload('form-file-name');   # <input type="file" name="form-file-name"/>
```

#### 2) 开启 token验证
```
$upload = new \Dj\Upload();
$upload->token('FFFX123456');  # 设置 token
$filelist = $upload->save('./upload');

# 或

$upload = new \Dj\Upload();
$filelist = $upload->token('FFFX123456')->save('./upload');
```
同时在上传文件时要也要POST提交正确的token
```
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="FFFX123456"/>
    <input type="file" name="file"/>
    <input type="submit" value="上传"/>
</form>
```

#### 3) 上传指定格式文件(通过后缀名方式限制)
```
$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'ext'=>'jpg,jpeg,png,gif'
]);

# 或

$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'ext'=>['jpg','jpeg','png','gif']
]);
```

#### 4) 上传指定格式文件(通过MIME方式限制)
```
$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'mime'=>'image/jpeg,image/gif,image/bmp'
]);

# 或

$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'mime'=>['image/jpeg','image/gif','image/bmp']
]);
```

#### 5) 上传文件许可的大小限制
```
$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'size'=>5242880
]);
```
同时"过滤参数"是可以混用的, 如 只想限制文件大小和文件类型、可以上传xx后缀的同时要匹配MIME等.. 都可以的大胆相信无所不能!
```
$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'ext'=>'jpg,jpeg,png,gif',
    'mime'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'size'=>5242880
]);

# 或

$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'ext'=>'jpg,jpeg,png,gif',
    'size'=>5242880
]);

# 或

$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'ext'=>'jpg,jpeg,png,gif',
    'mime'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);
```

#### 6) 设置上传后返回文件URL的域名
```
$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', 'img.sop6.com');

# 如果有过滤参数还可以这样定义

$upload = new \Dj\Upload();
$filelist = $upload->save('./upload', [
    'ext'=>'jpg,jpeg,png,gif',
    'mime'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'size'=>5242880
], 'img.sop6.com');
```
设置域名img.sop6.com后返回的上传结果如下(带 * 号那行)
```
Array
(
    [name] => 919ff6986614ada.jpg    // 上传时的原文件名
    [ext] => jpg                     // 文件后缀名
    [mime] => image/jpeg             // 文件MIME
    [size] => 171635                 // 文件大小(单位:字节)
    [savename] => 1524626782VGdnXS50.jpg    // 文件上传后在服务器上存储的名称
    [savepath] => E:/WWW/composer/upload_dev/upload/1524626782VGdnXS50.jpg  // 上传到服务器的存储绝对路径
    [url] => http://img.sop6.com/upload/1524626782VGdnXS50.jpg  // * 文件访问URL地址
    [uri] => /upload/1524626782VGdnXS50.jpg                     // 文件访问URI地址
    [md5] => 6308045756c126c8b823f4ade0bad77d                   // 文件MD5
)
```

# 联系方式
Author: Dejan

QQ: 673008865
