<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Createdb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:db {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MYSQL database based on config file or the provided parameter';

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
        $schemaName = $this->argument('name') ?: config('database.connections.mysql.database');
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_general_ci');
        //on va vider la config actuelle question d'etre sur d'éviter une collision
        config(['database.connections.mysql.database' => null]);

        //suppression de la bd existante
        $dropQuery = "DROP DATABASE IF EXISTS $schemaName;";
        DB::statement($dropQuery);

        //creation de notre bd vide
        $createQuery = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";
        DB::statement($createQuery);

        echo "Database $schemaName created successfully";
        config(['database.connections.mysql.database' => $schemaName]);
        return 0;
    }
}
