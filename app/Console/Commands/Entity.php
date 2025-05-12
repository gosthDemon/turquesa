<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Entity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:entity {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for create a full entity structure [Model, Migration, Controller, DTO, Service and Repository]';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $model = $name;
        $controller = "{$name}Controller";
        $service = "{$name}Service";
        $repository = "{$name}Repository";
        $dto = "{$name}DTO";

        // Create model, migration and controller
        $this->call('make:model', ['name' => $model, '--migration' => true]);
        $this->call('make:controller', ['name' => $controller, '--resource' => true]);

        // Crear directorios si no existen
        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'));
        }

        if (!File::exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'));
        }
        if (!File::exists(app_path('DTOs'))) {
            File::makeDirectory(app_path('DTOs'));
        }

        // Create DTO folder if not exists
        $dtoFolder = app_path("DTOs/{$name}");
        if (!File::exists($dtoFolder)) {
            File::makeDirectory($dtoFolder, 0755, true);
        }

        // Create a DTO if not exists
        $dtoPath = "{$dtoFolder}/{$dto}.php";
        if (!File::exists($dtoPath)) {
            File::put($dtoPath, "<?php\n\nnamespace App\\DTOs\\{$name};\n\nuse App\DTOs\DTO; \n\nclass {$dto} extends DTO\n{\n    // Define your DTO properties here\n}");
        }
        // Create Service
        $servicePath = app_path("Services/{$service}.php");
        File::put($servicePath, "<?php\n\nnamespace App\Services;\n\nuse App\Services\Service;\n\nclass {$service} extends Service {\n    // \n}");

        // Create Repository
        $repositoryPath = app_path("Repositories/{$repository}.php");
        File::put($repositoryPath, "<?php\n\nnamespace App\Repositories;\n\nuse App\Services\Service;\n\nclass {$repository} extends Repository {\n    // \n}");

        $this->info("Create structure for {$name} Entity");
    }
}