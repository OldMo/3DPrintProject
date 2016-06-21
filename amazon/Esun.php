<?php
	
	include 'simple_html_dom.php';
	set_time_limit(0);
	$fileName = "xml/esun.xml";
	$xml = new DOMDocument();
	$xml->load($fileName);

	$productDetailsClass = new ProductDetailsClass();
	
	$products = $xml->getElementsByTagName("product");
	$index = 1;
	
	$url  = "http://www.amazon.com/eSUN-filament-Natural-Makerbot-Printers/dp/B00PTKFURO/ref=sr_1_169/177-4129779-4280449?s=industrial&amp;ie=UTF8&amp;qid=1460819254&amp;sr=1-169";
	
//	foreach($products as $p){
	//	if(index > 2)
//			break;
//		$title = $p->getElementsByTagName("title")->item(0)->nodeValue;
//		$brand = $p->getElementsByTagName("brand")->item(0)->nodeValue;
//		$url = $p->getElementsByTagName("url")->item(0)->nodeValue;
		$html = $productDetailsClass->getHtml($url);
//		echo $html;
//		 $productDetailsClass->getBrand($html);
//		 $productDetailsClass->getTitle($html);
//		$productDetailsClass->getPrice($html);

//		$productDetailsClass->getMaterialType($html);
//
//		$productDetailsClass->getColor($html);
//
//		$productDetailsClass->getWeight($html);
		$productDetailsClass->getDiameter($html);

		$productDetailsClass->getDescription($html);
//		$index++;
		// echo "$title - $brand - $url".'<br/> ';
//	}
	
	
/**
*获取链接内信息
**/
class ProductDetailsClass{

	function getHtml($url){
		$html = file_get_html($url);
		return $html;
	}
	
	function getBrand($html){
		$brandContent = $html->find('#brand',0)->plaintext;
		echo $brandContent.'<br/>';
	}
	
	function getTitle($html){
		$titleContent = $html->find('#productTitle',0)->plaintext;
		echo $titleContent.'<br/>';
	}
	function getPrice($html){
		$priceContent = $html->find('#priceblock_ourprice',0)->plaintext;
		echo $priceContent.'<br/>';
	}

	function getMaterialType($html){
		$tableContent = $html->find('#product-specification-table',0);
		$materialTypeContent = $tableContent->find('td',3)->plaintext;
		echo $materialTypeContent.'<br/>';
	}
	/**
	 * 获取颜色
	 * @param $html
	 * @return color
	 */
	function getColor($html){
		$tableContent = $html->find('#product-specification-table',0);
		$colorContent = $tableContent->find('td',4)->plaintext;
		echo $colorContent.'<br/>';
	}

	function getWeight($html){
		$tableContent = $html->find('#product-specification-table',1);
		$weightContent = $tableContent->find('td',2)->plaintext;
		echo $weightContent.'<br/>';
	}

	function getDiameter($html){
		$tableContent = $html->find('#product-specification-table',1);
		$diameterContent = $tableContent->find('td',4)->plaintext;
		echo $diameterContent.'<br/>';
	}

	/**
	 * 在iframe中暂时没有获取到内容
	 * @param $html
	 */
	function getDescription($html){
		$descriptionContent = $html->find('#productDescription',0);
		echo $descriptionContent.'<br/>----';
	}

	

	/**
	 * 由于某些页面中直径的信息在第三个class中，所以需要对获取的位置进行判断
	 * @param $url
	 * @return int
	 */
	function setDiameterIndex($url){
		$diameterIndex = 1;
		if(strcmp($url,'https://www.lulzbot.com/store/filament/bronzefill') == 0){
			$diameterIndex = 2;
		}
		return $diameterIndex;
	}

	/**
	 * 获取重量信息
	 * @param $html
	 * @return array
	 */
	function getWeightss($html){
		$widthWeightContent = $html->find('.filament-width-weight');
		$widthWeightValue = $widthWeightContent[0]->plaintext;
		$arr = explode(' , ',$widthWeightValue);
		$weigths = explode(' ',$arr[1]);

		$packForm = $weigths[1];
		$weightValue = $this->splitNumberAndChar($weigths[0]);

		$weightInKg = 0;
		switch($weightValue['unit']){
			case "g":
				$weightInKg = $weightValue['value'] / 1000;
				break;
			case "kg":
				$weightInKg = $weightValue['value'];
				break;
			case "lb":
				$weightInKg = $weightValue['value'] * 0.45;
				break;
		}

//		echo 'weight:'.$weightValue['value'].'-weightUnit:'.$weightValue['unit'].'-packForm:'.$packForm.'-weightInKg：'.$weightInKg.'<br/>';
		return array('weight'=>$weightValue['value'],'weightUnit'=>$weightValue['unit'],'packForm'=>$packForm,'weightInKg'=>$weightInKg);
	}




	/**
	 * 获取特征
	 * @param $productUrl
	 */
	function getFeatures($html){
		$featureContent = $html->find('.accordion-panel')[0]->plaintext;
		//echo $featureContent.'<br/>';
		return trim($featureContent);

	}

	/**
	 * 获取直径并分割数值和单位
	 * @param $productUrl
	 */
	function getDiameters($html,$index){
		$specificationsContent = $html->find('.accordion-panel')[$index];
		$diameterContent = $specificationsContent->find('p')[0]->plaintext;

		//获取':'和'('之间的内容，如Filament Diameter: 3mm (.118 inches)
		preg_match_all("/(?:\:)(.*)(?:\()/i",$diameterContent, $result);
		$diameter = trim($result[1][0]);

		$diameterValue = $this->splitNumberAndChar($diameter);
//		echo trim($diameterValue['value']).'-'.trim($diameterValue['unit']).'<br/>';

		return array('diameter'=>trim($diameterValue['value']),'diameterUnit'=>trim($diameterValue['unit']));
	}



	/**
	 * 分割字符和数字
	 * @param $str
	 */
	function splitNumberAndChar($str){
		$value = preg_replace("/[a-zA-Z]+/","", $str);
		$unit = preg_replace('/[0-9_.-]/', '', $str);
		return array('value'=>$value,'unit'=>$unit);
	}
}
	
	
?>
