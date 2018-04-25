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
### $upload->save($storage, $filter[可选], $host[可选]) 返回值参照:

返回值     |  说明
:--------:|:----------:
Array()   | 上传文件成功
-1        | 上传失败
-2        | 指定上传文件的存储路径不合法
-3        | 上传非法格式文件
-4        | 文件大小不合符规定
-5        | token验证错误

# 高级用法
1) 指定文件表单name

2) 开启 token验证设置


3) 限制上传指定格式的文件(后缀名方式)


4) 限制上传指定格式的文件(MIME方式)


5) 限制上传文件许可的大小

6) 自定义返回文件ULR的域名

# 联系方式
