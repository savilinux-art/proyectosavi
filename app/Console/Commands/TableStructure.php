<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:table-structure {table*}')]
#[Description('Display the SQL structure of one or more tables')]
class TableStructure extends Command
{
    public function handle(): int
    {
        $tables = $this->argument('table');
        $driver = DB::connection()->getDriverName();

        foreach ($tables as $i => $table) {
            if ($i > 0) {
                $this->newLine();
            }

            $this->info("=========== {$table} ===========");

            $sql = match ($driver) {
                'mysql', 'mariadb' => "SHOW CREATE TABLE `{$table}`",
                'pgsql' => "SELECT column_name, data_type, is_nullable, column_default
                    FROM information_schema.columns
                    WHERE table_name = '{$table}'",
                'sqlite' => "SELECT sql FROM sqlite_master WHERE name = '{$table}'",
                default => null,
            };

            if (! $sql) {
                $this->error("Driver '{$driver}' no soportado.");
                continue;
            }

            try {
                $rows = DB::select($sql);
            } catch (\Throwable $e) {
                $this->error("Error en '{$table}': " . $e->getMessage());
                continue;
            }

            foreach ($rows as $row) {
                foreach ((array) $row as $value) {
                    $this->line($value);
                }
            }
        }

        return 0;
    }
}