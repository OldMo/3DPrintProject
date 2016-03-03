<?php
	include 'simple_html_dom.php';

//	$url = "http://store.makerbot.com/filament/flexible";
$url = "http://store.makerbot.com/filament/pla#small";
	$product = new ProductDetails();
	$html = $product->getHtml($url);
//	$product->getImage($html);
//	$product->getWeight($html);
//$product->getFeatures($html,"abs");
	$product->getColor($html);
//

//	$diameterIndex = $value->setDiameterIndex($url);
//	$value->getDiameters($html,$diameterIndex);

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
	 * 获取颜色
	 * pla通过以下四个链接获取
	 * http://store.makerbot.com/filament/pla#small
	 * http://store.makerbot.com/filament/pla#large
	 * http://store.makerbot.com/filament/pla#xl
	 * http://store.makerbot.com/filament/pla#xxl
	 * @param $html
	 * @return array
	 */
	function getColor($html){
		$colorNames = array();
		$colorImgUrls = array();
		$colorContent = $html->find('section');
		echo $colorContent[0]->plaintext;
		//有颜色
//		if(count($colorContent) != 0){
//			$colorLi = $colorContent[0]->find('li');
//			$i = 0;
//			foreach($colorLi as $color){
//				$colorText = $color->find('img')[0];
//				$colorName = $colorText->alt;
//				$colorImgUrl = 'https://www.lulzbot.com'.$colorText->src;
//				$colorNames[$i] = $colorName;
//				$colorImgUrls[$i] = $colorImgUrl;
//				$i++;
//			}
//		}
//		return array('colorNames'=>$colorNames,'colorImgUrls'=>$colorImgUrls);
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
