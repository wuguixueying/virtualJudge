<?php
@session_start();

//随机码的个数
$code=4;
//创建随机码
for($i=0;$i<$code;$i++){
   $_nmsg.=dechex(mt_rand(0,15));
}

//将验证码保存在session里
$_SESSION['code']=$_nmsg;

//长和高
$_width=65;
$_height=30;

//创建一张图像

$_img=imagecreatetruecolor($_width,$_height);//resource image资源的形象9888888888888888888888888

//白色
$_white=imagecolorallocate($_img,210,210,210);//imagecolorallocate ( resource image, int red, int green, int blue)为一幅图像分配颜色


//填充
imagefill($_img,0,0,$_white);//imagefill ( resource image, int x, int y, int color)区域填充



$_flag=false;

if($_flag){
//黑色边框
$_black=imagecolorallocate($_img,0,0,0);
imagerectangle($_img,0,0,$_width-1,$_height-1,$_black);//imagerectangle ( resource image, int x1, int y1, int x2, int y2, int col)画一个矩形

}

//随机画出6个线条
for($i=0;$i<6;$i++){
  $_rnd_color=imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
   imageline($_img,mt_rand(0,$_width),mt_rand(0,$_height),mt_rand(0,$_width),mt_rand(0,$_height),$_rnd_color);
}

// imageline ( resource image, int x1, int y1, int x2, int y2, int color)画一条直线


//随机雪花
for($i=0;$i<100;$i++){
    $_rnd_color=imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
    imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),'*',$_rnd_color);
	}

//输出验证码
for($i=0;$i<strlen($_SESSION['code']);$i++){
	$_rnd_color=imagecolorallocate($_img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));
	imagestring($_img,5,$i*$_width/$code+mt_rand(1,10),mt_rand(1,$_height/2),$_SESSION['code'][$i],$_rnd_color);
	}

//输出图像
ob_clean();
@header("Content-type:image/gif");
imagegif($_img);

//销毁
imagedestroy($_img);
?>
