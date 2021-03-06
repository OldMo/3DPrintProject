<?php
	  include 'simple_html_dom.php';
	 $url = 'http://www.amazon.com/s/ref=sr_pg_200/186-9579278-0825442?rh=n%3A16310091%2Cn%3A!16310161%2Cn%3A6066126011%2Cn%3A6066128011&page=200&ie=UTF8&qid=1460039178&spIA=B01A524ZKI,B019PGZQO4';
// $url = 'http://store.makerbot.com/dissolvable-filament.html';
	// $url = "http://store.makerbot.com/filament/flexible";
//$url = "http://store.makerbot.com/filament/abs";
	  $product = new ProductDetails();
	  $html = $product->getHtml($url);
	  
	  // $product->getList($html);
	   $result = $html->find('#result_3201',0);
	  echo $result;
	// $product->getPlaImage($html);
	// $product->getWeight($html);
// $product->getFeatures($html,"abs");
	// $product->getPlaColor($html);
//$product->getDiameters($html);
	// $product->getPrice($html);

	// echo $product->delete_special_mark($str);
	
class ProductDetails{
	function getHtml($url){
		$html = file_get_html($url);
		return $html;
	}

	function getList($html){
		$ulsdiv = $html->find('.s-result-item');
		// echo $ulsdiv[5];
		// $lis = $ulsdiv->find('li');
		echo 'count--'.count($ulsdiv).'<br/>';
		foreach($ulsdiv as $li){
			$title = $li->find('h2',0);
			 echo $title.'<br/>';
		}
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
	 * 获取产品图片pla,只能从<img data-screen="mobile" data-src="/mb-images/store/filament/pla/spool-mobile.png" alt="MakerBot PLA Filament Spool">提取
	 * @param $html
	 * @return string
	 */
	function getPlaImage($html){
		$imgContent = $html->find('.pane',1);
		$imgPos = $imgContent->find('img',1);
		$imgText = explode(" ",$imgPos)[2];
		preg_match_all('/\"(.*?)\"/', $imgText,$matches);//提取双引号内容；即图片地址
		$imgSrc = 'http://store.makerbot.com'.$matches[1][0];
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

		$weightValue = $weightArray[0];
		$weightWeight = $weightArray[1];

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
			$featureStr = $this->getPlaFeature($html);
			return $featureStr;
		}else{
			$featureContent = $html->find('.container')[1];
		}
		$h4Content = $featureContent->find('h4');
		$feaContent = $featureContent->find('.feature');
		foreach($h4Content as $h4){
			$featureStr .= strip_tags($h4);
		}
		foreach($feaContent as $f){
			$featureStr .= strip_tags($f->find('.text',0));
		}

		$fStr = $this->delete_special_mark($featureStr);

		echo ($fStr);
		return $fStr;

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
		$featureStr .= trim($des1);

		//产品特征第二部分内容
		$wightBg = $featureContent->find('.whitebg')[0];
		$des2 = $wightBg->find('li');
		foreach($des2 as $des){
			$featureStr .= trim($des->plaintext);
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

		preg_match_all('/\'(.*?)\'/', $colorImg,$matches);//提取单引号内容；即图片地址
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
		return array('value'=>trim($value),'unit'=>trim($unit));
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

	
	function delete_special_mark($str){   
		//去除字符串 首尾 空白等特殊符号或指定字符序列   
		$str = trim($str);    
		//去掉空白   
		$str = str_replace("  ","",$str);
		return $str;
}  

}
?>
