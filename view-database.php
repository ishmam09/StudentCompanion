<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== StudentCompanion Database Viewer ===\n\n";

// Get all tables
$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");

foreach ($tables as $table) {
    $tableName = $table->name;
    $count = DB::table($tableName)->count();
    
    echo "📊 Table: {$tableName} ({$count} records)\n";
    echo str_repeat("-", 60) . "\n";
    
    if ($count > 0) {
        $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $records = DB::table($tableName)->limit(10)->get();
        
        // Print column headers
        echo implode(" | ", $columns) . "\n";
        echo str_repeat("-", 60) . "\n";
        
        // Print records
        foreach ($records as $record) {
            $values = [];
            foreach ($columns as $col) {
                $value = $record->$col ?? 'NULL';
                if (is_string($value) && strlen($value) > 20) {
                    $value = substr($value, 0, 20) . '...';
                }
                $values[] = $value;
            }
            echo implode(" | ", $values) . "\n";
        }
        
        if ($count > 10) {
            echo "... and " . ($count - 10) . " more records\n";
        }
    } else {
        echo "(No records)\n";
    }
    
    echo "\n";
}

