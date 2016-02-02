<?php

	set_time_limit(0);
   
    header("Content-type: text/html; charset=utf-8");  
	
	$colorLibraryName = 'lulzbot.xml';
	$colorXml = createColorLibrary($colorLibraryName);
	
	$productXml = simplexml_load_file('201601150831-lulzbot.xml');
	$products = $productXml->product;

	$colorArray = array();
	$index = 0;
	foreach($products as $p){
		$color = $p->color;
		
		if((strcasecmp($color,'null') != 0) && (!colorExistInArray($color,$colorArray)) ){
			 echo $color.'不存在<br/>';
			 $colorArray[]=$color;
			 
			 //添加到xml文件中
			 addColor($color,$colorXml);
			
		}else{
			 echo $color.'存在<br/>';
		}

	}
	
    $colorXml->asXml($colorLibraryName);

	/**
	*判断颜色数组中是否存在某种颜色
	*
	*/
	function colorExistInArray($color,$colorArray){
		$flag = false;
		foreach ($colorArray as $c) {
			if(strcasecmp($color,$c) == 0){
				$flag = true;
				return $flag;
				break;
			}
		}
		return $flag;
	}

	/**
	 * 添加颜色到颜色库
	 * @param $colorName
	 */
	function createColorLibrary($filename){
		if (file_exists($filename))
		{
			$xml = simplexml_load_file($filename);
			return $xml;
		}else
		{
			$xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Colors />'); 
			$xml->asXml($filename);
			
			$colorXml = simplexml_load_file($filename);
			return $colorXml;
		}
	}
	
	/**
	*判断颜色库中是否存在该颜色
	* @param $colorName  颜色名
	* @param $xml  加载的xml文件信息
	*/
	function isColorExist($colorName,$xml){
		$items = $xml->color;
		$flag = false;
		foreach($items as $it){
			$color = $it->colorName;
			
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
	function addColor($colorName,$xml){
		$xml->addchild('colorName',$colorName);
	}
?>
