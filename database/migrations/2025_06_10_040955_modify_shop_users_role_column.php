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
        // First, get the column type information
        $columnType = DB::select("SHOW COLUMNS FROM shop_users WHERE Field = 'role'")[0]->Type;
        
        // If it's an enum, modify it to include 'admin'
        if (str_contains(strtolower($columnType), 'enum')) {
            // Extract current values
            preg_match("/^enum\(\'(.*)\'\)$/", $columnType, $matches);
            $currentValues = explode("','", $matches[1]);
            
            // Add 'admin' if it doesn't exist
            if (!in_array('admin', $currentValues)) {
                $currentValues[] = 'admin';
                $newValues = "'" . implode("','", $currentValues) . "'";
                
                // Alter the column
                DB::statement("ALTER TABLE shop_users MODIFY COLUMN role ENUM($newValues) NOT NULL");
            }
        }
        
        // Set the first owner as admin
        DB::statement("UPDATE shop_users SET role = 'admin' WHERE role = 'owner' LIMIT 1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change any admin roles back to owner
        DB::statement("UPDATE shop_users SET role = 'owner' WHERE role = 'admin'");
    }
};
