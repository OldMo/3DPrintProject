<?php

	set_time_limit(0);

	$dir_1 = '1-15';

	getFile($dir_1);

	//获取文件列表
	function getFile($dir) {
		$fileArray[]=NULL;
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
//				if ($file != "." && $file != ".."&&strpos($file,".")) {
//					$fileArray[$i]="./imageroot/current/".$file;
//					if($i==100){
//						break;
//					}
//					$i++;
//				}
				echo $file.'<br/>';
			}
			//关闭句柄
			closedir ( $handle );
		}
		return $fileArray;
	}

?>
