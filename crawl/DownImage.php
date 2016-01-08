<?php
	set_time_limit(0);
	$products = simplexml_load_file('products.xml');
	$items = $products->item;

	$index = 0;
	foreach($items as $it){
		$urls = $it->ColorImgUrl->url;
		foreach($urls as $u){
			if(strcasecmp($u,'null') != 0){
				//echo $u.'<br/>';
				download_image($u,'old','C:\xampp\htdocs\crawl');
			}
		}
		
	}
	
	
	
	function download_image($url, $fileName = '', $dirName, $fileType = array('jpg', 'gif', 'png'), $type = 0)
	{
		if ($url == '')
		{
			return false;
		}
		// ��ȡ�ļ�ԭ�ļ���
		$defaultFileName = strtolower(basename($url));
		// ��ȡ�ļ�����
		$suffix = strtolower(substr(strrchr($url, '.'), 1));
		if (!in_array($suffix, $fileType))
		{
			return false;
		}
		// ���ñ������ļ���
		$fileName = $fileName == '' ? time() . rand(0, 9) . '.' . $suffix : $defaultFileName;
		// ��ȡԶ���ļ���Դ��ʽѡ��
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
		}
		// �����ļ�����·��
		$dirName = $dirName . '/' . date('Ym', time());
		if (!file_exists($dirName))
		{
			mkdir($dirName, 0777, true);
		}
		
		echo $dirName;
		// �����ļ�
		$res = fopen($dirName . '/' . $fileName, 'a');
		fwrite($res, $file);
		fclose($res);
		return array(
			'fileName' => $fileName,
			'saveDir' => $dirName
		);
	}
?>

