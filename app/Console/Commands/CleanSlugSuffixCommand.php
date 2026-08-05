<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\SlugService;

class CleanSlugSuffixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unnecessary numeric suffixes (-0, -ID, etc.) from database slugs';

    protected $slugService;

    public function __construct(SlugService $slugService)
    {
        parent::__construct();
        $this->slugService = $slugService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting database slug cleanup...');

        $tables = [
            'tp_pages' => 'id_page',
            'tp_cate_products' => 'id_cate_product',
            'tp_cate_news' => 'id_cate_new',
            'tp_news' => 'id_new',
            'tp_products' => 'id_product',
        ];

        $totalUpdated = 0;

        foreach ($tables as $table => $idColumn) {
            $this->info("Processing table: {$table}...");

            $rows = DB::table($table)->get();

            foreach ($rows as $row) {
                $id = $row->{$idColumn};
                $updates = [];

                // Process VN Slug
                $nameVn = $row->name_vn ?? ($row->name ?? null);
                if (!empty($nameVn)) {
                    $cleanSlugVn = $this->slugService->generateUniqueSlugGlobal($nameVn, $table, 'slug_vn', $id);
                    if ($cleanSlugVn !== $row->slug_vn) {
                        $updates['slug_vn'] = $cleanSlugVn;
                        $this->line("  - [{$table} ID {$id}] VN slug updated: '{$row->slug_vn}' -> '{$cleanSlugVn}'");
                    }
                }

                // Process EN Slug
                $nameEn = $row->name_en ?? null;
                if (!empty($nameEn)) {
                    $cleanSlugEn = $this->slugService->generateUniqueSlugGlobal($nameEn, $table, 'slug_en', $id);
                    if ($cleanSlugEn !== $row->slug_en) {
                        $updates['slug_en'] = $cleanSlugEn;
                        $this->line("  - [{$table} ID {$id}] EN slug updated: '{$row->slug_en}' -> '{$cleanSlugEn}'");
                    }
                }

                if (!empty($updates)) {
                    DB::table($table)->where($idColumn, $id)->update($updates);
                    $totalUpdated++;
                }
            }
        }

        $this->info("Slug cleanup complete. Total records updated: {$totalUpdated}");

        return 0;
    }
}
