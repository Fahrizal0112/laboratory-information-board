<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE monitorings DROP CONSTRAINT IF EXISTS monitorings_status_check');
        
        DB::table('monitorings')
            ->where('status', 'approved')
            ->update(['status' => 'on_queue']);
            
        DB::table('monitorings')
            ->where('status', 'completed')
            ->update(['status' => 'approved_finish']);
        
        DB::statement("ALTER TABLE monitorings ADD CONSTRAINT monitorings_status_check CHECK (status IN ('pending', 'rejected', 'on_queue', 'in_progress', 'on_progress_approval', 'approved_finish'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE monitorings DROP CONSTRAINT IF EXISTS monitorings_status_check');
        
        DB::table('monitorings')
            ->where('status', 'on_queue')
            ->update(['status' => 'approved']);
            
        DB::table('monitorings')
            ->where('status', 'approved_finish')
            ->update(['status' => 'completed']);
            
        DB::table('monitorings')
            ->where('status', 'on_progress_approval')
            ->update(['status' => 'in_progress']);

        DB::statement("ALTER TABLE monitorings ADD CONSTRAINT monitorings_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'in_progress', 'completed'))");
    }
};