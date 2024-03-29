<?php

/**
 *+------------------
 * LaravelFlow 工作流回退
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */

declare(strict_types=1);

namespace LaravelFlow\Service\Command;

//数据库操作
use LaravelFlow\Adaptive\Info;
use LaravelFlow\Adaptive\Flow;
use LaravelFlow\Adaptive\Process;
use LaravelFlow\Adaptive\Log;
use LaravelFlow\Adaptive\Bill;
use LaravelFlow\Adaptive\Run;

class EndFlow
{
    /**
     * @param $pid
     * @param $run_id
     * @return bool|void
     */
    public static function doTask($pid, $run_id)
    {
        if ($pid == '') {
            return 0;
        }
        $process = Process::GetProcessInfo($pid, $run_id);
        if ($process['process_type'] == 'node-end') {
            return 1;
        } else {
            return 0;
        }
    }
}
