<?php
//	include 'simple_html_dom.php';
//	$url = "https://www.lulzbot.com/store/filament/bronzefill";
//
//	$value = new ProductDetails();
//	$html = $value->getHtml($url);
//	$value->getWeight($html);
//	$value->getColor($html);
//
//	$value->getFeatures($html);
//	$diameterIndex = $value->setDiameterIndex($url);
//	$value->getDiameters($html,$diameterIndex);

class ProductDetails{

	function getHtml($url){
		$html = file_get_html($url);
		return $html;
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
	function getWeight($html){
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
	 * 获取颜色
	 * @param $html
	 * @return array
	 */
	function getColor($html){
		$colorNames = array();
		$colorImgUrls = array();
		$colorContent = $html->find('.filament-colors');
		//有颜色
		if(count($colorContent) != 0){
			$colorLi = $colorContent[0]->find('li');
			$i = 0;
			foreach($colorLi as $color){
				$colorText = $color->find('img')[0];
				$colorName = $colorText->alt;
				$colorImgUrl = 'https://www.lulzbot.com'.$colorText->src;
				$colorNames[$i] = $colorName;
				$colorImgUrls[$i] = $colorImgUrl;
				$i++;
			}
		}
		return array('colorNames'=>$colorNames,'colorImgUrls'=>$colorImgUrls);
	}


	/**
	 * 获取特征
	 * @param $productUrl
	 */
	function getFeatures($html){
		$featureContent = $html->find('.accordion-panel')[0]->plaintext;
//		echo $featureContent.'<br/>';
		return $featureContent;

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
