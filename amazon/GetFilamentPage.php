<?php

	set_time_limit(0);
	header("Content-type: text/html; charset=utf-8");
    include 'simple_html_dom.php';
	$startUrl = "http://www.amazon.com/b/ref=dp_bc_3?ie=UTF8&node=6066128011";
	$brandsArray = array();

	getProductUrls($startUrl);

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
			echo $pageUrl.'<br/>';
		}

		echo count($pageUrls);
		return $pageUrls;
	}

	/**
	 * 获取每一页中的产品名称和url3
	 * @param $pageUrl
	 */
	function getProductUrls($pageUrl,$brandsArray){
		$html = file_get_html($pageUrl);
		$ulsdiv = $html->find('.s-result-item'); //获取ul

		$productUrls = array();
		$index = 0;
		foreach($ulsdiv as $li){
			$title = $li->find('h2',0)->plaintext;
			$productUrl = $li->find('a',0)->href;
			$brand = $li->find('.a-size-small',1);

			//品牌存在，则说明该品牌的xml文件已经存在，直接将该产品添加到对应的xml文件中，否则新建品牌xml文件
			if(in_array($brand,$brandsArray)){

			}else{
				$brandsArray[$brand] = $brand;
			}

			echo $title.'----'.$productUrl.'<br/>';
			echo $brand.'<br/>';
			$productUrls[$index] = $productUrl;
		}
//		return $productUrls;
	}

?>
