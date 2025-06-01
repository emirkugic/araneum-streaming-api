<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;
use OpenApi\Annotations\OpenApi;

class GenerateSwaggerDocsCommand extends Command
{
    protected $signature = 'swagger:generate';
    protected $description = 'Generate Swagger documentation';

    public function handle()
    {
        $this->info('Generating Swagger documentation...');

        // The output path for the Swagger JSON file
        $outputPath = public_path('swagger-docs.json');

        try {
            // Generate the Swagger/OpenAPI documentation with a custom error handler
            $openapi = Generator::scan([app_path('Http/Controllers')], [
                'logger' => function ($type, $message) {
                    if ($type === 'warning' || $type === 'error') {
                        $this->warn($message);
                    } else {
                        $this->info($message);
                    }
                },
            ]);

            // Validate the documentation is complete
            $this->validateOpenApi($openapi);

            // Save to file
            file_put_contents($outputPath, $openapi->toJson());

            $this->info('Swagger documentation generated: ' . $outputPath);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error generating Swagger documentation: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    protected function validateOpenApi(OpenApi $openapi)
    {
        // Ensure there are paths
        if (empty($openapi->paths)) {
            $this->warn('No paths found in the OpenAPI documentation');
        }

        // Check for common issues
        foreach ($openapi->paths as $path) {
            if (empty($path->operations)) {
                $this->warn("Path '{$path->path}' has no operations");
            }
        }
    }
}
