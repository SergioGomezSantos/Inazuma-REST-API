<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import All Data from Wiki';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('| Iniciando importación completa... |');


        $this->warn("⏳ Reseteando la base de datos...");
        $this->call('migrate:fresh');
        $this->info("✅ Base de datos reiniciada.");

        $this->call('import:techniques');
        $this->call('import:data');

        $this->info('| Importación completa terminada |');
    }
}
