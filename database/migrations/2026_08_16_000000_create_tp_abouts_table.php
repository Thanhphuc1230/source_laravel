<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tp_abouts', function (Blueprint $table) {
            $table->id('id_about');
            $table->uuid()->unique();
            $table->string('name_vn');
            $table->string('name_en')->nullable();
            $table->text('intro_vn')->nullable();
            $table->text('intro_en')->nullable();
            $table->longText('content_vn');
            $table->longText('content_en')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedInteger('stt')->default(1)->nullable();
            $table->json('stats')->nullable();

            $table->timestamps();

            // Essential indexes only
            $table->index(['status', 'stt']);       // Covers: status filtering + ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tp_abouts');

        // Delete seeded permissions
        try {
            $permissionIds = DB::table('tp_permissions')
                ->where('group_name', 'about')
                ->pluck('id');

            if ($permissionIds->isNotEmpty()) {
                DB::table('tp_role_permission')->whereIn('permission_id', $permissionIds)->delete();
                DB::table('tp_permissions')->whereIn('id', $permissionIds)->delete();
            }
        } catch (\Exception $e) {
            // Silently handle if tables do not exist
        }
    }
};
