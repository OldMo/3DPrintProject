<?php
	// include 'simple_html_dom.php';
	// $url = "http://store.makerbot.com/filament/pla";
	// $name = "pla";
	// $product = new ProductDetailsFromJS();
	// $html = $product->getHtml($url);

	// $product->getPlaWeight($html);

class ProductDetailsFromJS{
	function getHtml($url){
		$html = file_get_html($url);
		return $html;
	}

	/**
	 * 获取PLA颜色、颜色图片地址、价格、类型信息
	 * @param $html
	 * @return string
	 */
	function getPlaProductInfo($html,$name){
		$colorImgUrlPre = "http://store.makerbot.com/mb-images/store/filament/".$name.'/tablet';

		$posStr = stristr($html,'filament_data');
		$start = stripos($posStr,'{');
		$end = stripos($posStr,'}};');
		$subStr = substr($posStr,$start,$end-$start+2);
		$InfoByjsonArray = json_decode($subStr,true);
//		print_r($InfoByjsonArray);

		$weights = $this->getPlaWeight($html);
		$colorInformation = array();
		$i = 0;
		foreach($InfoByjsonArray as $single){
			$color = $single['short_name'];
			$colorUrl = $colorImgUrlPre.'/'.strtolower(str_replace(" ","",$color)).'.png';

			$type = $single['type'];
			$size_list = $single['size_list'];
			foreach($size_list as $size){
				$price = $single['sizes'][strtoupper($size)]['price'];
				$weight = $weights[strtolower($size)];
				echo $color.'--'.$colorUrl.'--'.$price.'--'.$type.'--'.$weight.'<br/>';
				$colorInformation[$i] = array("color"=>$color,"colorImgUrl"=>$colorUrl,"weight"=>$weight,"price"=>$price,"type"=>$type);
			}


			$this->jumpLine();
			$i++;
		}

		return $colorInformation;
	}


	/**
	 * 获取ABS颜色、颜色图片地址、价格、类型信息
	 * @param $html
	 * @return string
	 */
	function getAbsProductInfo($html,$name){
		$colorImgUrlPre = "http://store.makerbot.com/mb-images/store/filament/".$name;

		$posStr = stristr($html,'filament_data');
		$start = stripos($posStr,'{');
		$end = stripos($posStr,'}};');
		$subStr = substr($posStr,$start,$end-$start+2);
		$InfoByjsonArray = json_decode($subStr,true);

		$colorInformation = array();
		$i = 0;
		foreach($InfoByjsonArray as $single){
			if($i < count($InfoByjsonArray) - 1){
				$productInfo = $single['name'];
				$color = $single['short_name'];
				$colorUrl = $colorImgUrlPre.'/'.$single['formatted'].'.jpg';
				$price = $single['price'];
				$type = $single['type'];
				$weight = $this->getAbsWeight($productInfo,$color);

				$colorInformation[$i] = array("color"=>$color,"colorImgUrl"=>$colorUrl,"weight"=>$weight,"price"=>$price,"type"=>$type);
				echo $color.'--'.$colorUrl.'--'.$weight.'--'.$price.'--'.$type;
			}
			$i++;
		}

		return $colorInformation;
	}



	/**
	 * 获取对应的重量
	 * @param $productInfo
	 * @param $color
	 * @return mixed
	 */
	function getAbsWeight($productInfo,$color)
	{
		$infomation = explode(" ", $productInfo);
		if (strcasecmp($color, 'natural') == 0) {
			$weight = $infomation[2];
		}else if(strcasecmp($color,'true white') == 0){
			$weight = $infomation[4];
		} else {
			$weight = $infomation[3];
		}
		$this->jumpLine();
		return $weight;
	}

	/**
	 * 获取pla重量并分割数值和单位
	 * @param $html
	 */
	function getPlaWeight($html){
		$specificationContent = $html->find('.row',6);
		$smallWeightContent = $specificationContent->find('.small',0)->plaintext;
		$largeWeightContent = $specificationContent->find('.large',0)->plaintext;
		$xlWeightContent = $specificationContent->find('.xl',0)->plaintext;
		$xxlWeightContent = $specificationContent->find('.xxl',0)->plaintext;

		$smallWeight = str_replace(" ","",explode("(",$smallWeightContent)[0]);
		$largeWeight = str_replace(" ","",explode("(",$largeWeightContent)[0]);
		$xlWeight = str_replace(" ","",explode("(",$xlWeightContent)[0]);
		$xxlWeight = str_replace(" ","",explode("(",$xxlWeightContent)[0]);

		echo $smallWeight.'--'.$largeWeight.'--'.$xlWeight.'--'.$xxlWeight.'<br/>';

		return array('small'=>$smallWeight,'large'=>$largeWeight,'xl'=>$xlWeight,'xxl'=>$xxlWeight);
	}


	function getDiameterByNameInfo($productInfo,$color){
		$infomation = explode(" ",$productInfo);
		if (strcasecmp($color, 'natural') == 0) {
			$diameter = $infomation[4];
		}else if(strcasecmp($color,'true white') == 0){
			$diameter = $infomation[7];
		} else {
			$diameter = $infomation[5];
		}
		$this->jumpLine();
		return $diameter;
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

	function jumpLine(){
		echo '<br/><br/>';
	}


}
?>
