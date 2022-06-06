<?php
/**
 *+------------------
 * laravelflow 工作流回退
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace laravelflow\service\command;

//数据库操作
use laravelflow\adaptive\Info;
use laravelflow\adaptive\Flow;
use laravelflow\adaptive\Process;
use laravelflow\adaptive\Log;
use laravelflow\adaptive\Bill;
use laravelflow\adaptive\Run;

class EndFlow
{
	/**
	 * @param $pid
	 * @param $run_id
	 * @return bool|void
	 */
	public static function doTask($pid,$run_id)
	{
		if($pid==''){
			return 0;
		}
		$process = Process::GetProcessInfo($pid,$run_id);
		if($process['process_type']=='node-end'){
			return 1;
		}else{
			return 0;
		}
	}
}
