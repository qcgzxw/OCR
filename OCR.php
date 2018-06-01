<html>
	<head>
		<title>腾讯云ORC图片识别</title>
		<meta charset = "utf-8">
	</head>
	<body>
		<form action="OCR.php" method = "post" enctype="multipart/form-data">
			<input type="file" name="image" accept="image/*">
			<input type="submit"/>
		</form>
		<p>/***</p>
		<p>*源码出自小文's blog</p>
		<p>*文章地址：https://www.qcgzxw.cn/2701.html</p>
		<p>***/</p>
	</body>
</html>
<?php
/***
*源码出自小文's blog
*文章地址：https://www.qcgzxw.cn/2701.html
***/
//$url = '';
$appid = '';//填写你的appid
$SecretId = ''; //填写你的SecretId
$SecretKey = ''; //填写你的SecretKey
$bucket = '';//填写你的万象优图bucket
$signStr = get_authorization($bucket, $appid, $SecretId, $SecretKey);

if ($_FILES["image"]["error"] > 0)
{
	echo ($_FILES["image"]["error"]); 
}
else
{
	$file = $_FILES["image"]["tmp_name"];
	$image = new CurlFile($file);
	$res = to_curl_image($appid, $image, $signStr ); 
	$length =  count($res[data][items]);
	for($i = 0; $i < $length; $i++)
	{
		echo($res[data][items][$i][itemstring]);
		echo('<br>');
		
	}
}
//本地上传
function to_curl_image($appid, $image, $signStr )
{
	$content = array('appid' => $appid, 'image' => $image);
	$header[] = 'Host: recognition.image.myqcloud.com'; 
    $header[] = 'Authorization: '.$signStr; 
    $header[] = 'Content-Type: multipart/form-data;charset=utf-8'; 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, 'http://recognition.image.myqcloud.com/ocr/general'); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content); 
    $response = json_decode(curl_exec($ch), true); 
	return $response; 
}
//url方式上传
function to_curl_url($appid, $url, $signStr )
{
	$content = array('appid' => $appid, 'url' => $url);
	$header[] = 'Host: recognition.image.myqcloud.com'; 
    $header[] = 'Authorization: '.$signStr; 
    $header[] = 'Content-Type: multipart/form-data;charset=utf-8'; 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, 'http://recognition.image.myqcloud.com/ocr/general'); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content); 
    $response = json_decode(curl_exec($ch), true); 
	return $response; 
}
//获取authorization
function get_authorization($bucket, $appid, $SecretId, $SecretKey)
{	
	$expired = time() + 2592000;
	$onceExpired = 0;
	$current = time();
	$rdm = rand();
	$userid = "0";
	$fileid = "www.qcgzxw.cn";

	$srcStr = 'a='.$appid.'&b='.$bucket.'&k='.$SecretId.'&e='.$expired.'&t='.$current.'&r='.$rdm.'&u='
	.$userid.'&f=';
	
	$authorization = base64_encode(hash_hmac('SHA1', $srcStr, $SecretKey, true).$srcStr);
	return $authorization;
}
