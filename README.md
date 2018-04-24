# Upload是什么?
一个PHP文件上传组件，使用该组件可以简化文件上传验证让上传文件变得更简单方便！

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
    [name] => 919ff6986614ada.jpg      // 上传时的原文件名
    [ext] => jpg                       // 文件后缀名
    [savepath] => E:\WWW\composer\upload_dev\upload/1524562449xR8aEhQd.jpg  // 上传到服务器的存储绝对路径
    [savename] => 1524562449xR8aEhQd.jpg      // 文件上传后在服务器上存储的名称
    [size] => 171635                          // 文件大小(单位:字节)
    [md5] => 6308045756c126c8b823f4ade0bad77d // 文件MD5
)

# 多文件上传

Array
(
    [0] => Array
        (
            [name] => dejan.jpg
            [ext] => jpg
            [savepath] => E:\WWW\composer\upload_dev\upload/1524563032hWMrNpCI.jpg
            [savename] => 1524563032hWMrNpCI.jpg
            [size] => 1964
            [md5] => 9382a7b44ea865519c82d077cd6346b0
        )

    [1] => Array
        (
            [name] => 《系统安装手册》.docx
            [ext] => docx
            [savepath] => E:\WWW\composer\upload_dev\upload/1524563032aFpo1MdK.docx
            [savename] => 1524563032aFpo1MdK.docx
            [size] => 1041956
            [md5] => 9f1c186790769c09a9318eb352deb114
        )

    [2] => Array
        (
            [name] => 测试导入用户.xlsx
            [ext] => xlsx
            [savepath] => E:\WWW\composer\upload_dev\upload/1524563032YpsrrL7C.xlsx
            [savename] => 1524563032YpsrrL7C.xlsx
            [size] => 9249
            [md5] => b1f1c10005fcf0a2b59326a3aa3af032
        )

)
```
# 高级用法


# 联系方式
