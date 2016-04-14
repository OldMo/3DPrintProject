<?php

	set_time_limit(0);
	header("Content-type: text/html; charset=utf-8");
    include 'simple_html_dom.php';
	$startUrl = "http://www.amazon.com/b/ref=dp_bc_3?ie=UTF8&node=6066128011";
	$brandsArray = array();

	// addToXML('HATCHBOX','HATCHBOX','标题','www.');
	
	$urls = getPageUrls($startUrl);
	$index = 1;
	foreach($urls as $url){
		echo '正在获取第'.$index.'页的信息.............<br/>';
		getProductUrls($url,$brandsArray);
		// echo $url.'<br/>';
		$index++;
		
		if($index > 3)
			break;
	}
	

	/**
	 * 获得产品所有页数url
	 * @param $startUrl
	 * @return array
	 */
	function getPageUrls($startUrl){

		//每一个的url组成规则
		$firstPartUrl = 'http://www.amazon.com/s/ref=sr_pg_';
		$secondPartUrl = '/186-9579278-0825442?rh=n%3A16310091%2Cn%3A!16310161%2Cn%3A6066126011%2Cn%3A6066128011&page=';
		$thirdPartUrl = '&ie=UTF8&qid=1460039178&spIA=B01A524ZKI,B019PGZQO4';

		//获取总的页数
		$html = file_get_html($startUrl);
		$totalPage = $html->find('.pagnDisabled',0)->plaintext;

		$pageUrls = array();
		for($i = 1; $i <= $totalPage; $i++){
			$pageUrl = $firstPartUrl.$i.$secondPartUrl.$i.$thirdPartUrl;
			$pageUrls[$i] = $pageUrl;
			// echo $pageUrl.'<br/>';
		}

		// echo count($pageUrls);
		return $pageUrls;
	}

	/**
	 * 获取每一页中的产品名称和url3
	 * @param $pageUrl
	 */
	function getProductUrls($pageUrl,$brandsArray){
		ob_start();//打开输出控制缓冲
		ob_end_flush();//输出缓冲区内容并关闭缓冲
		ob_implicit_flush(1);//立即输出
	
		$html = file_get_html($pageUrl);
		$ulsdiv = $html->find('.s-result-item'); //获取ul

		$productUrls = array();
		$index = 0;
		foreach($ulsdiv as $li){
			$title = $li->find('h2',0)->plaintext;
			$productUrl = $li->find('a',0)->href;
			$brand = trim($li->find('.a-size-small',1)->plaintext);

			//品牌存在，则说明该品牌的xml文件已经存在，直接将该产品添加到对应的xml文件中，否则新建品牌xml文件
			if(in_array($brand,$brandsArray)){
				addToXML($brand,$brand,$title,$productUrl);
			}else{
				createXML($brand,$brand,$title,$productUrl);
				$brandsArray[$brand] = $brand;
			}

			echo $brand.'-----'.$title.'<br/>';
			$productUrls[$index] = $productUrl;
			
			sleep(1);
			ob_flush();//输出缓冲区中的内容
			flush();//刷新输出缓冲
		}
		
//		return $productUrls;
	}
	
	/**
	*	创建xml文件
	*/
	function createXML($xmlName,$brand,$title,$url){
		$xml=new DOMDocument('1.0','utf-8');
		$xml->formatOutput = true;
		$root = $xml->createElement("ProductInfo");
		$filename = 'xml/'.$xmlName.'.xml';
		//将标签添加到XML文件中

		$productXml=$xml->createElement("product");
		$idXml = $xml->createAttribute("id");
		$idXml->value = 2;
		$titleXml = $xml->createElement("title",$title);
		$brandXml = $xml->createElement("brand",$brand);
		$urlXml = $xml->createElement("url",$url);
		
		$productXml->appendChild($idXml);
		$productXml->appendChild($titleXml);
		$productXml->appendChild($brandXml);
		$productXml->appendChild($urlXml);
		$root->appendChild($productXml);
				
		$xml->appendChild($root);
		//生成XML
		$xml->save($filename);
	}
	
	/**
	*添加信息到xml文件
	*/
	function addToXML($xmlName,$brand,$title,$url){
		$xml = new DOMDocument('1.0','utf-8');
		$xml->formatOutput = true;
		$filename = 'xml/'.$xmlName.'.xml';
		if($xml -> load($filename)){
			$root = $xml -> documentElement;//获得根节点(root)
			
			$productXml=$xml->createElement("product");
			$idXml = $xml->createAttribute("id");
			$idXml->value = 4;
			$titleXml = $xml->createElement("title",$title);
			$brandXml = $xml->createElement("brand",$brand);
			$urlXml = $xml->createElement("url",$url);
			
			$productXml->appendChild($idXml);
			$productXml->appendChild($titleXml);
			$productXml->appendChild($brandXml);
			$productXml->appendChild($urlXml);
			$root->appendChild($productXml);
					
			$xml->appendChild($root);
			//生成XML
			$xml->save($filename);
			
		}else {
			echo 'xml file loaded error!';
		}
	}
	
	/**
	*	判断是否存在filament关键字，存在则默认为印刷品
	*/
	function containsKeyWord($title,$keyWord){
		if(strpos($title,$keyWord))
			return true;
		return false;
		
	}

?>
