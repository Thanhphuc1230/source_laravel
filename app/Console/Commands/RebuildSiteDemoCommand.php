<?php

namespace App\Console\Commands;

use App\Services\DemoImageDownloaderService;
use Illuminate\Console\Command;

class RebuildSiteDemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:rebuild {--topic=cosmetics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động dọn dẹp media và xây dựng lại dữ liệu demo cho website theo chủ đề';

    protected DemoImageDownloaderService $demoImageDownloaderService;

    public function __construct(DemoImageDownloaderService $demoImageDownloaderService)
    {
        parent::__construct();
        $this->demoImageDownloaderService = $demoImageDownloaderService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $topic = $this->option('topic') ?: 'cosmetics';

        $this->info('====================================================');
        $this->info("🚀 BẮT ĐẦU REBUILD SITE DEMO VỚI CHỦ ĐỀ: [{$topic}]");
        $this->info('====================================================');

        // Bước 1: Dọn dẹp ảnh/folder cũ trong public/uploads/demo/
        $this->info('👉 [Bước 1/3] Đang dọn dẹp thư mục media demo cũ (public/uploads/demo/)...');
        $this->demoImageDownloaderService->cleanOldUploads();
        $this->info('   ✔ Đã dọn dẹp toàn bộ thư mục demo cũ.');

        // Bước 2: Thiết lập cấu hình topic demo
        $this->info("👉 [Bước 2/3] Thiết lập cấu hình demo: [demo.current_topic => {$topic}]...");
        config(['demo.current_topic' => $topic]);
        $this->info("   ✔ Đã cấu hình topic hiện tại: {$topic}");

        // Bước 3: Chạy migrate:fresh --seed và optimize:clear
        $this->info('👉 [Bước 3/3] Chạy migrate:fresh --seed và làm sạch cache...');
        $this->call('migrate:fresh', ['--seed' => true]);
        $this->info('   ✔ Hoàn thành migrate & seed dữ liệu.');

        $this->info('👉 Làm sạch cache hệ thống...');
        $this->call('optimize:clear');
        $this->info('   ✔ Đã làm sạch toàn bộ cache hệ thống.');

        $this->info('====================================================');
        $this->info("🎉 HOÀN TẤT REBUILD SITE DEMO [{$topic}] THÀNH CÔNG!");
        $this->info('====================================================');

        return Command::SUCCESS;
    }
}
