<?php

namespace App\Console\Commands;

use App\LagouPosition;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CrawlCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'lagou:crawl {--cookie=}';

    /**
     * The console command description.
     */
    protected $description = '抓取广州的技术职位';

    protected $jobs = ['PHP', 'Java', 'Go', 'python', 'Ruby'];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('cookie')) {
            $this->error('必须通过 --cookie 选项提供 Cookie');
            return;
        }

        foreach ($this->jobs as $job) {
            $positionData = $this->runCurlCommand($job);
            $this->info("拉取到 $job 职位的分页数据，共 {$positionData->content->pageSize} 页");

            for ($i = 1; $i <= $positionData->content->pageSize; $i++) {
                $this->output->newLine();
                $this->randomSleep();
                $this->info("正在获取第 $i 页职位数据……");

                $positionData = $this->runCurlCommand($i);

                foreach ($positionData->content->positionResult->result as $position) {
                    $position->updateTime = now();

                    LagouPosition::updateOrCreate(['positionId' => $position->positionId], (array) $position);
                }
            }
        }
    }

    protected function runCurlCommand(string $job, int $page = 1): \stdClass
    {
        // 更换 cookie 不再影响缓存
        $key = 'lagou:ajax:position:' . md5($this->buildCurlCommand($job, '', $page));

        return Cache::remember($key, now()->addHours(12), function () use ($job, $page) {
            $cookie  = $this->option('cookie');
            $command = $this->buildCurlCommand($job, $cookie, $page);
            $data    = json_decode(exec($command));

            if (is_null($data)) {
                $this->error('职位信息curl失败');
                exit;
            }

            if ($data->msg) {
                $this->error('拉勾报错：' . $data->msg);
                exit;
            }

            return $data;
        });
    }

    protected function buildCurlCommand(string $job, string $cookie, int $page): string
    {
        return "curl 'https://www.lagou.com/jobs/positionAjax.json?px=new&city=%E5%B9%BF%E5%B7%9E&needAddtionalResult=false' -H 'Connection: keep-alive' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache' -H 'Origin: https://www.lagou.com' -H 'X-Anit-Forge-Code: 0' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36' -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'X-Requested-With: XMLHttpRequest' -H 'X-Anit-Forge-Token: None' -H 'Sec-Fetch-Site: same-origin' -H 'Sec-Fetch-Mode: cors' -H 'Referer: https://www.lagou.com/jobs/list_{$job}?px=new&city=%E5%B9%BF%E5%B7%9E' -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8' -H 'Cookie: {$cookie}' --data 'first=true&pn=$page&kd={$job}' --compressed";
    }

    protected function randomSleep(): void
    {
        // 为了安全，至少休息60秒
        $microSeconds = random_int(60 * 1000000, 120 * 1000000);

        $this->info('随机休息 ' . ($microSeconds / 1000000) . ' 秒……');

        usleep($microSeconds);
    }
}
