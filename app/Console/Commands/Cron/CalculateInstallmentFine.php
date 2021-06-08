<?php

namespace App\Console\Commands\Cron;

use App\Models\Installment;
use App\Models\InstallmentItem;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateInstallmentFine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:calculate-installment-fine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '计算分期付款逾期费';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // \DB::connection()->enableQueryLog();
        InstallmentItem::query()
            // 预加载分期付款数据， 避免 N+1 问题
            // ->with(['installment'])
            ->whereHas('installment', function ($query) {
                // 对应的分期状态为还款中
                $query->where('status', Installment::STATUS_REPAYING);
            })
            // 还款截止日期在当前时间之前
            ->where('due_date', '<=', Carbon::now())
            // 尚未还款
            ->whereNull('paid_at')
            // 使用 chunkById 避免一次性查询太多记录
            ->chunkById(100, function($items) {
                // 遍历查询出来的还款计划
                foreach ($items as $item) {
                    // 通过 Carbon 对象的 DiffInDays 直接得到逾期天数
                    $overDueDays = Carbon::now()->diffInDays($item->due_date);
                    // 本金与手续费之和
                    $base = bcadd($item->base, $item->fee, 2);
                    // 计算逾期费
                    $fine = bcdiv(bcmul(bcmul($base, $overDueDays, 2), $item->installment->fine_rate, 2), 100, 2);
                    //避免逾期费用高于本金和手续费之和
                    $fine = bccomp($fine, $base) === 1 ? $base : $fine;
                    $item->update([
                        'fine' => $fine
                    ]);
                }
            });

            // dd( \DB::getQueryLog());
    }
}
