<?php
if($_GET && $_GET['token'] == 'sb'){
    function recurDir($pathName)
    {
        //将结果保存在result变量中
        $result = array();
        $temp = array();
        //判断传入的变量是否是目录
        if(!is_dir($pathName) || !is_readable($pathName)) {
            return null;
        }
        //取出目录中的文件和子目录名,使用scandir函数
        $allFiles = scandir($pathName);
        //遍历他们
        foreach($allFiles as $fileName) {
            //判断是否是.和..因为这两个东西神马也不是。。。
            if(in_array($fileName, array('.', '..'))) {
                continue;
            }
            //路径加文件名
            $fullName = $pathName.'/'.$fileName;
            //如果是目录的话就继续遍历这个目录
            if(is_dir($fullName)) {
                //将这个目录中的文件信息存入到数组中
                $result[$fullName] = recurDir($fullName);
            }else {
                //如果是文件就先存入临时变量
                $temp[] = $fullName;
            }
        }
        //取出文件
        if($temp) {
            foreach($temp as $f) {
                $result[] = $f;
            }
        }
        return $result;
    }

    function beautifulTree($arr, $l = '-| ')
    {
        static $l = '';
        static $str = '';
        //遍历刚才得到的目录树
        foreach($arr as $key=>$val) {
            //如果是个数组，也就代表它是个目录，那么就在它的子文件中加入-|来表示是下一级吧
            if(is_array($arr[$key])) {
                $str.=$l.$key."<br/>";
                $l.='-| ';
                beautifulTree($arr[$key], $l);
            }else {
                $str.=$l.$val."<br/>";
            }
        }
        $l = '';
        return $str;
    }
//验证一下这个函数是否好用！
    $dir = $_SERVER['SCRIPT_FILENAME'];
    $dir = str_replace($_SERVER['PHP_SELF'],'',$dir);
    $tree = recurDir($dir);
    $beautiful = beautifulTree($tree);
    echo "<pre>";
    print_r($beautiful);
    echo "</pre>";
}else{
    return array(
    );
}
?>