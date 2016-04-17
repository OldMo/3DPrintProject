<?php

	set_time_limit(0);

	$dir_1 = '1-15';
	$dir_2 = '16-400';

	$firstArray = getFile($dir_1);
	$secondArray = getFile($dir_2);
	$index = 1;
	//将文件夹1中的文件合并到文件夹2中
	foreach($firstArray as $f){
		foreach($secondArray as $s){
			if(strcasecmp($f,$s) == 0){
				echo $index.'----'.$f.'<br/>';

				//读取文件夹1的文件内容
				$doc = new DOMDocument();
				$fileName = $dir_1.'/'.$f;
				$doc->load($fileName);         //读取xml文件

				$products = $doc->getElementsByTagName( "product" );         //取得product标签的对象数组
				foreach($products as $p){
					$title = $p->getElementsByTagName("title")->item(0)->nodeValue;
					$brand = $p->getElementsByTagName("brand")->item(0)->nodeValue;
					$url = $p->getElementsByTagName("url")->item(0)->nodeValue;

					//将1的内容添加到文件2的同名文件中
					addToXML($dir_2.'/'.$f,$brand,$title,$url);
//					echo "$title - $brand - $url".'<br/> ';
				}

				$index++;
			}
		}

	}



	/**
	 *添加信息到xml文件
	 */
	function addToXML($filename,$brand,$title,$url){
		//处理产品名字符乱码
		$brand = strReplaceToEntity($brand);
		$title = strReplaceToEntity($title);
		$url = strReplaceToEntity($url);
		$xml = new DOMDocument('1.0','utf-8');
		$xml->formatOutput = true;
		if($xml -> load($filename)){
			$root = $xml -> documentElement;//获得根节点(root)

			$productXml=$xml->createElement("product");
			$titleXml = $xml->createElement("title",$title);
			$brandXml = $xml->createElement("brand",$brand);
			$urlXml = $xml->createElement("url",$url);

			$productXml->appendChild($titleXml);
			$productXml->appendChild($brandXml);
			$productXml->appendChild($urlXml);
			$root->appendChild($productXml);

			$xml->appendChild($root);
			//生成XML
			$xml->save($filename);

		}else {
			echo 'xml 文件 '.$filename.'更新错误! <br/>';
		}
	}

	//获取文件列表
	function getFile($dir) {
		$fileArray[]=NULL;
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
					if ($file != "." && $file != ".."&&strpos($file,".")) {
						$fileArray[$i] = $file;
						$i++;
					}
			}
			//关闭句柄
			closedir ( $handle );
		}
		return $fileArray;
	}

	function strReplaceToEntity($str){
		$str = str_replace('©', '&copy;', $str);
		$str = str_replace('®', '&reg;', $str);
		$str = str_replace('±', '&plusmn;', $str);
		$str = str_replace('&', '&amp;', $str);
		return $str;
	}
?>
