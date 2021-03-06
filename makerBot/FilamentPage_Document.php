<?php

	set_time_limit(0);
    include 'simple_html_dom.php';
	include 'ProductDetails.php';
	include 'ProductDetailsFromJS.php';
	$company = 'makerbot';
	$companyWeb = 'makerbot.com';
	$companyUrl = 'http://store.makerbot.com';
    $startUrl = "http://store.makerbot.com/filament";
    
    header("Content-type: text/html; charset=utf-8");  
    $xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><ProductInfo />');
	$startUrlXml = $xml->addAttribute('startUrl',$startUrl);
	$companyXml = $xml->addAttribute('company',$company);
		
    $html = file_get_html($startUrl);
    $content = $html->find('.nav_box_container');
    $index = 1;
    
	$breakFlag = 1;
	
    ob_start();//打开输出控制缓冲
    ob_end_flush();//输出缓冲区内容并关闭缓冲
    ob_implicit_flush(1);//立即输出
    foreach($content[0]->find('a') as $element){

        $hrefStr = $companyUrl.$element->href;
		$productName = strtolower($element->plaintext);
		echo $hrefStr.'--'.$productName.'<br/>';

		//abs,pla直径信息
		$diameterInformation = array();

		//调用产品信息类
		$productDetails = new ProductDetails();
		$productHtml = $productDetails->getHtml($hrefStr);

		$productDetailFromJs = new ProductDetailsFromJS();
		$productInfo = array();
		if(strcasecmp($productName,'abs') == 0){
			$productInfo = $productDetailFromJs->getAbsProductInfo($productHtml,$productName);
			$diameterInformation = $productDetails->getDiameters($productHtml);
		}else if(strcasecmp($productName,'pla') == 0){
			$productInfo = $productDetailFromJs->getPlaProductInfo($productHtml,$productName);
			$diameterInformation = $productDetails->getPlaDiameters($productHtml);
		}

		//图片
		if(strcasecmp($productName,'pla')==0){
			$imageUrl = $productDetails->getPlaImage($productHtml);
		}else{
			$imageUrl = $productDetails->getImage($productHtml);
		}
		//描述
		$description = $productDetails->getFeatures($productHtml,$productName);

		if(strcasecmp($productName,'dissolvable')== 0){
			$url = "http://store.makerbot.com/dissolvable-filament.html";
		}else{
			$url = $hrefStr;
		}

		//单位
		$packForm  = 'reel';

		//没有颜色信息
		if(count($productInfo) == 0){
			//价格信息
			$priceInfo = $productDetails->getPrice($productHtml);
			$price = trim($priceInfo['price']);
			$priceUnit = trim($priceInfo['priceUnit']);

			//直径
			$diameterInfo = $productDetails->getDiameters($productHtml);
			$diameter = trim($diameterInfo['diameter']);
			$diameterUnit = trim($diameterInfo['diameterUnit']);

			//重量
			$weightInfo = $productDetails->getWeight($productHtml);
			$weight = trim($weightInfo['weight']);
			$weightUnit = trim($weightInfo['weightUnit']);

			$productXml=$xml->addchild("product");
			$idXml = $productXml->addAttribute("id",$index);
			$materialTypeXml = $productXml->addchild("materialType",$productName);
			$matherialSubTypeXml = $productXml->addchild("matherialSubType",$productName);
			$brandXml = $productXml->addchild("brand",$company);
			$producerXml = $productXml->addchild("producer",$company);
			$ingredientXml = $productXml->addchild("ingredient","");
			$priceXml = $productXml->addchild("price",$price);
			$priceUnit = $productXml->addChild("priceUnit",$priceUnit);
			$diameterXml = $productXml->addchild("diameter",$diameter);
			$diameterUnit = $productXml->addchild("diameterUnit",$diameterUnit);
			$colorXml = $productXml->addchild("color",'');
			$colorImgUrlXml = $productXml->addchild("colorImgUrl",'');
			$weightXml = $productXml->addchild("weight",$weight);
			$weightUnit = $productXml->addchild("weightUnit",$weightUnit);
			$packFormXml = $productXml->addchild("packForm",$packForm);
			$weightInKgXml = $productXml->addchild("weightInKg",$weight);
			$imageUrlXml = $productXml->addchild("imageUrl",$imageUrl);
			$urlXml = $productXml->addchild("url",$url);
			$descriptionXml = $productXml->addchild("description",$description);
			$sellerXml = $productXml->addchild("seller",$company);
			$sellerWebXml = $productXml->addchild("sellerWeb",$companyWeb);

			$index++;

		}else{
			foreach($productInfo as $product){
				$priceUnit = 'USD';

				//直径
				$diameter = trim($diameterInformation['diameter']);
				$diameterUnit = trim($diameterInformation['diameterUnit']);

				$productXml=$xml->addchild("product");
				$idXml = $productXml->addAttribute("id",$index);
				$materialTypeXml = $productXml->addchild("materialType",$productName);
				$matherialSubTypeXml = $productXml->addchild("matherialSubType",$productName);
				$brandXml = $productXml->addchild("brand",$company);
				$producerXml = $productXml->addchild("producer",$company);
				$ingredientXml = $productXml->addchild("ingredient","");
				$priceXml = $productXml->addchild("price",$product['price']);
				$priceUnit = $productXml->addChild("priceUnit",$priceUnit);
				$diameterXml = $productXml->addchild("diameter",$diameter);
				$diameterUnit = $productXml->addchild("diameterUnit",$diameterUnit);
				$colorXml = $productXml->addchild("color",$product['color']);
				$colorImgUrlXml = $productXml->addchild("colorImgUrl",$product['colorImgUrl']);
				$weightXml = $productXml->addchild("weight",$product['weight']);
				$weightUnit = $productXml->addchild("weightUnit",$product['weightUnit']);
				$packFormXml = $productXml->addchild("packForm",$packForm);
				$weightInKgXml = $productXml->addchild("weightInKg",$product['weight']);
				$imageUrlXml = $productXml->addchild("imageUrl",$imageUrl);
				$urlXml = $productXml->addchild("url",$url);
				$descriptionXml = $productXml->addchild("description",$description);
				$sellerXml = $productXml->addchild("seller",$company);
				$sellerWebXml = $productXml->addchild("sellerWeb",$companyWeb);

				$index++;
			}
		}
		  
        sleep(1);
        ob_flush();//输出缓冲区中的内容
        flush();//刷新输出缓冲
    }
    
	$filename = 'xml/'.date('YmdHi', time()).'-'.$company.'.xml';
    $newxml = $xml->asXml();  //标准化 XML数据
	$fp = fopen($filename, "w"); //打开要写入 XML数据的文件
	fwrite($fp, $newxml); //写入 XML数据
	fclose($fp); //关闭文件
	
	
	// $dom = new DOMDocument('1.0');
	// $dom-> preserveWhiteSpace = false;
	// $dom-> formatOutput = true;
	// $dom-> loadXML();
	//Echo XML - remove this and following line if echo not desired
	// echo $dom-> saveXML();
	//Save XML to file - remove this and following line if save not desired
	// $dom-> save($filename);


	/**
	 * 从类型字符串中提取类型和品牌
	 * @param $typeString
	 * @return array
	 */
	function getBrandAndType($typeString){
		if (strstr($typeString, '(') && strstr($typeString,')')) {

			$brandText = preg_replace('/\((.*)\)/', '', $typeString);
			preg_match_all("/(?:\()(.*)(?:\))/i",$typeString, $result);
			$type = $result[1][0];
			$brand = trim($brandText);

			return array('type'=>$type,'brand'=>$brand);

		} else {
			$value = str_replace(' ','',$typeString);
			return array('type'=>$value,'brand'=>$value);;
		}
	}

	/**
	 * 将价格和价格单位分割
	 * @param $priceStr
	 * @return array
	 */
	function splitPrice($priceStr){
		$priceUnit = substr($priceStr,0,1);
		$price = substr($priceStr,1,strlen($priceStr) - 1);

		return array('price'=>$price,'priceUnit'=>$priceUnit);
	}

	/**
	 * 添加颜色到颜色库
	 * @param $colorName
	 */
	function colorLibraryOperate($colorName){
		if (file_exists('test.xml'))
		{
		  $xml = simplexml_load_file('test.xml');
		  var_dump($xml);
		}

		else
		{
			$xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Colors />'); 
			$filename = date('YmdHi', time()).'-'.'color.xml';
			$xml->asXml($filename);
		}
	}
	
	/**
	*判断颜色库中是否存在该颜色
	* @param $colorName  颜色名
	* @param $xml  加载的xml文件信息
	*/
	function isColorExist($colorName,$xml){
			$items = $xml->product;
		$flag = false;
		foreach($items as $it){
			$color = $it->color;
			
			if(strcasecmp($color,$colorName) == 0){
				echo $colorName.'    exsits';
				$flag = true;
				return $flag;
			}
		}
		var_dump($flag);
		return $flag;
	}
	
	/**
	*添加颜色到颜色库
	* @param $colorName 颜色名称
	* @param $xml 加载的xml信息
	* @param $fileName xml文件名
	*/
	function addColor($colorName,$xml,$filename){
		$xml->addchild('color',$colorName);
		$xml->asXml($filename);
	}
?>
