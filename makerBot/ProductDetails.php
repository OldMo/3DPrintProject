<?php
	include 'simple_html_dom.php';
//	$url = 'http://store.makerbot.com/pla';
$url = 'http://store.makerbot.com/dissolvable-filament.html';
//	$url = "http://store.makerbot.com/filament/flexible";
//$url = "http://store.makerbot.com/filament/abs";
	$product = new ProductDetails();
	$html = $product->getHtml($url);
//	$product->getImage($html);
//	$product->getWeight($html);
//$product->getFeatures($html,"abs");
	// $product->getPlaColor($html);
//$product->getDiameters($html);
	$product->getPrice($html);

class ProductDetails{
	function getHtml($url){
		$html = file_get_html($url);
		return $html;
	}

	/**
	 * 获取产品图片dissolvable,flexible,abs
	 * @param $html
	 * @return string
	 */
	function getImage($html){
		$imgContent = $html->find('.active');
		$imgSrc = 'http://store.makerbot.com'.$imgContent[0]->src;
		echo $imgSrc;
		return $imgSrc;
	}

	/**
	 * 获取产品价格dissolvable,flexible
	 * @param $html
	 * @return string
	 */
	function getPrice($html){
		$orderContent = $html->find('.order_inside',0);
		$price = trim($orderContent->find('div[id=price]',0)->plaintext);
		$priceInfo = $this->splitPrice($price);
		return $priceInfo;
	}

	/**
	 * 获取重量信息flexible,dissolvable,abs
	 * @param $html
	 * @return array
	 */
	function getWeight($html){
		$weightContent = $html->find('.spool');
		$weight = $weightContent[0]->plaintext;
		$weightArray = explode(' ',$weight);

		$weightWeight = $weightArray[0];
		$weightValue = $weightArray[1];

		return array('weight'=>$weightValue,'weightUnit'=>$weightWeight);
	}


	/**
	 * 获取产品描述flexible，dissolvable 为1
	 * abs为2,pla也为2，但获取内容的地方不一样
	 */
	function getFeatures($html,$name){

		$featureStr = "";
		if(strcasecmp("abs",$name) == 0){
			$featureContent = $html->find('.container')[2];
		}else if(strcasecmp("pla",$name) == 0){
			$this->getPlaFeature($html);
		}else{
			$featureContent = $html->find('.container')[1];
		}
		$h4Content = $featureContent->find('h4');
		$feaContent = $featureContent->find('.feature');
		foreach($h4Content as $h4){
			$featureStr .= $h4->plaintext;
		}
		foreach($feaContent as $f){
			$featureStr .= $f->plaintext;
		}
		echo $featureStr;
		return trim($featureStr);

	}

	/**
	 * 获取pla的特征信息
	 * @param $html
	 * @return string
	 */
	function getPlaFeature($html){
		$featureStr = "";
		$featureContent = $html->find('.pane')[1];
		//产品特征第一部分内容
		$des1 = $featureContent->find('.container')[1]->plaintext;
		$featureStr .= $des1;

		//产品特征第二部分内容
		$wightBg = $featureContent->find('.whitebg')[0];
		$des2 = $wightBg->find('ul');
		foreach($des2 as $des){
			$featureStr .= $des->plaintext;
		}

		echo $featureStr;
		return $featureStr;
	}

	/**
	 * 获取直径并分割数值和单位abs,flexible,dissolvable
	 * @param $html
	 */
	function getDiameters($html){
		$specificationsContent = $html->find('.row',4);
		$diameterContent = $specificationsContent->find('.kg',0)->plaintext;
		$diameter = str_replace(" ","",$diameterContent);
		echo $diameter.'<br/>';


		$diameterValue = $this->splitNumberAndChar($diameter);
		echo trim($diameterValue['value']).'-'.trim($diameterValue['unit']).'<br/>';

		return array('diameter'=>trim($diameterValue['value']),'diameterUnit'=>trim($diameterValue['unit']));
	}


	/**
	 * 获取pla直径并分割数值和单位
	 * @param $html
	 */
	function getPlaDiameters($html){
		$specificationContent = $html->find('.row',4);
		$diameterContent = $specificationContent->find('.small',0)->plaintext;
		$diameter = str_replace(" ","",$diameterContent);
		echo $diameter.'<br/>';

		$diameterValue = $this->splitNumberAndChar($diameter);
		echo trim($diameterValue['value']).'-'.trim($diameterValue['unit']).'<br/>';

		return array('diameter'=>trim($diameterValue['value']),'diameterUnit'=>trim($diameterValue['unit']));
	}



	/**
	 * 获取flexible,dissolvable颜色图片地址,
	 * @param $html
	 */
	function getColorImg($html){
		$colorImgContent = $html->find('div[id=hero]',0);
		$colorImg = $colorImgContent->find('.img',0)->style;

		preg_match_all('/\'(.*?)\'/', $colorImg,$matches);//提取双引号内容；即图片地址
		$colorUrl = 'http://store.makerbot.com'.$matches[1][0];

		echo $colorImg.'---'.$colorUrl;
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

	/**
	 * 将价格和价格单位分割
	 * @param $priceStr
	 * @return array
	 */
	function splitPrice($priceStr){
		$priceFlag = substr($priceStr,0,1);
		$price = substr($priceStr,1,strlen($priceStr) - 1);

		switch($priceFlag){
			case '$': $priceUnit = 'USD';break;
			case '¥': $priceUnit = 'RMB'; break;
			case '£': $priceUnit = 'GBP'; break;
			default: $priceUnit = 'USD';break;
		}
		return array('price'=>$price,'priceUnit'=>$priceUnit);
	}


}
?>
