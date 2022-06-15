<?php

/**
 *+------------------
 * LaravelFlow 普通提交工作流
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
use LaravelFlow\Adaptive\Log;
use LaravelFlow\Adaptive\Bill;
use LaravelFlow\Adaptive\Process;
use LaravelFlow\Adaptive\Run;
use LaravelFlow\Lib\Unit;

class MsgFlow
{
    /**
     * 任务自动执行
     *
     * @param mixed $npid 运行步骤id
     * @param mixed $run_wfid 设计器id
     */
    public function do()
    {
    }
}
