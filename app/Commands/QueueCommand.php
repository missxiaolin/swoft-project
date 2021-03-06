<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Commands;

use App\Core\Queue\Queue;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;

/**
 * This is a example command class
 * @Command(coroutine=false)
 * @package App\Commands
 */
class QueueCommand
{
    /**
     * this is a example command
     * @Usage {command} [arguments ...] [--options ...]
     * @Arguments
     *   first STRING        The first argument value
     *   second STRING       The second argument value
     * @Options
     *   --opt STRING        This is a long option
     *   -s BOOL             This is a short option(<comment>use color</comment>)
     * @Example {command} FIRST SECOND --opt VALUE -s
     * @param Input $input
     * @param Output $output
     * @throws \Lin\Swoole\Queue\QueueException
     * @return int
     */
    public function handle(Input $input, Output $output): int
    {
        $queue = Queue::instance();
        $queue->run();
        return 0;
    }

    /**
     * 重载失败的消息到队列
     * @Usage {command}
     * @Example {command}
     * @return int
     */
    public function reload(Output $output): int
    {
        $queue = Queue::instance();
        $count = $queue->reloadErrorJobs();
        $output->colored("已将{$count}条消息，重载到消费队列");
        return 0;
    }

    /**
     * 删除所有失败的消息
     * @Usage {command}
     * @Example {command}
     * @return int
     */
    public function flush(Output $output): int
    {
        $queue = Queue::instance();
        $count = $queue->flushErrorJobs();
        $output->colored("已删除所有失败的消息，共{$count}条");
        return 0;
    }

    /**
     * 查看当前消息的数量
     * @param Output $output
     * @return int
     */
    public function count(Output $output): int
    {
        $queue = Queue::instance();
        $count = $queue->countCurrentJobs();
        $output->colored("当前消息数量，共{$count}条");

        $count = $queue->countErrorJobs();
        $output->colored("失败消息数量，共{$count}条");
        return 0;
    }
}
