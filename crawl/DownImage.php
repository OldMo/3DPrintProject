<?php
	set_time_limit(0);
	$products = simplexml_load_file('products.xml');
	$items = $products->item;

	$index = 0;
	foreach($items as $it){
		$urls = $it->ColorImgUrl->url;
		foreach($urls as $u){
			if(strcasecmp($u,'null') != 0){
				echo $u.'<br/>';
				download_image($u,'old','C:\xampp\htdocs\crawl');
			}
		}
		
	}
		
	
	function download_image($url, $fileName = '', $dirName, $type = 0)
	{
		if ($url == '')
		{
			return false;
		}
		
		// 获取远程文件资源方式选择
		if ($type)
		{
			echo '1';
			$ch = curl_init();
			$timeout = 300;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			ob_start();
			readfile($url);
			$file = ob_get_contents();
			ob_end_clean();
			echo 'd';
		}
		// 设置文件保存路径
		$dirName = $dirName . '/' . date('Ym', time());
		if (!file_exists($dirName))
		{
			mkdir($dirName, 0777, true);
		}
		
		echo $dirName;
		// 保存文件
		$res = fopen($dirName . '/' . $fileName, 'a');
		fwrite($res, $file);
		fclose($res);
		return array(
			'fileName' => $fileName,
			'saveDir' => $dirName
		);
	}
?>

