<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeFeatured extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:featured {name : The name of the feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration, model, controller and request for a feature';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        
        // Kiểm tra và chuẩn hóa tên
        if (empty($name)) {
            $this->error('Name argument is required!');
            return 1;
        }
        
        $singular = Str::singular($name);
        $plural = Str::plural(Str::snake($singular));
        $table = 'tp_' . Str::snake($plural);
        
        // Tạo migration
        $this->info('Creating migration...');
        Artisan::call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
        $this->info(Artisan::output());
        
        // Tạo model
        $this->info('Creating model...');
        Artisan::call('make:model', [
            'name' => $singular,
        ]);
        $this->info(Artisan::output());
        
        // Chỉnh sửa model để thêm table name
        $modelPath = app_path("Models/{$singular}.php");
        if (File::exists($modelPath)) {
            $content = File::get($modelPath);
            
            // Tìm vị trí của use HasFactory;
            if (strpos($content, 'use HasFactory;') !== false) {
                // Thay thế class declaration
                $content = preg_replace(
                    '/class ' . $singular . ' extends Model\s*\{(\s*use HasFactory;\s*)/m',
                    "class {$singular} extends Model\n{\n    use HasFactory;\n\n    protected \$table = '{$table}';\n    protected \$fillable = [];\n",
                    $content
                );
            } else {
                // Thay thế class declaration không có HasFactory
                $content = str_replace(
                    "class {$singular} extends Model\n{",
                    "class {$singular} extends Model\n{\n    protected \$table = '{$table}';\n    protected \$fillable = [];",
                    $content
                );
            }
            
            File::put($modelPath, $content);
            $this->info("Model updated with table name: {$table}");
        }
        
        // Tạo controller
        $this->info('Creating controller...');
        $controllerNamespace = "Admin";
        $controllerName = "{$controllerNamespace}\\{$singular}Controller";
        
        Artisan::call('make:controller', [
            'name' => $controllerName,
            '--resource' => true,
            '--model' => $singular,
        ]);
        $this->info(Artisan::output());
        
        // Tạo request
        $this->info('Creating request...');
        $requestName = "{$controllerNamespace}\\{$singular}Request";
        
        Artisan::call('make:request', [
            'name' => $requestName,
        ]);
        $this->info(Artisan::output());
        
        $this->info('All done!');
        $this->info("Created: Migration, Model {$singular}, Controller Admin/{$singular}Controller, Request Admin/{$singular}Request");
        
        return 0;
    }
}
