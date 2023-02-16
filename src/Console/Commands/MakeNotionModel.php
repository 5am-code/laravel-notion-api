<?php

namespace FiveamCode\LaravelNotionApi\Console\Commands;

use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeNotionModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notion:model {database_id} {database_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Notion Model based on a provided {database_id}';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $databaseId = $this->argument('database_id');
        $databaseName = $this->argument('database_name');

        $notion = new Notion(config('laravel-notion-api.notion-api-token'));

        $this->info("Fetching structure of {$databaseId} database from Notion...");

        $databaseStruct = $notion->databases()->find($databaseId);
        $phpDocsProperties = '';
        $visibleArray = '';
        $propertyTypeMape = '';
        $propertyTitleMap = '';

        if ($databaseName === null) {
            $databaseName = Str::singular(Str::studly(Str::slug($databaseStruct->getTitle(), '_')));
        }

        foreach ($databaseStruct->getProperties() as $propertyInfo) {
            $reflection = new \ReflectionMethod($propertyInfo, 'getContent');
            $propType = $reflection->getReturnType() ?? 'mixed';
            $notionPropType = Str::studly(Str::slug($propertyInfo->getType(), '_'));

            if ($reflection->getReturnType() !== null && ! $propType->isBuiltin()) {
                $propType = '\\'.$propType->getName();
            }

            $propName = Str::slug($propertyInfo->getTitle(), '_');
            $phpDocsProperties .= " * @property {$propType} \${$propName} $notionPropType \n";
            $visibleArray .= "        '$propName',\n";
            $propertyTypeMape .= "        '$propName' => '$notionPropType',\n";
            $propertyTitleMap .= "        '$propName' => '{$propertyInfo->getTitle()}',\n";
        }

        $contents = "<?php
namespace App\NotionModels;

use FiveamCode\LaravelNotionApi\Models\NotionModel;

/** 
{$phpDocsProperties}*/
class {$databaseName} extends NotionModel
{
    public static \$databaseId = '{$databaseId}';

    public static \$cacheDurationInSeconds = 0;

    public static \$convertPropsToText = false;

    public static \$visible = [
$visibleArray    ];

    public static \$propertyTypeMap = [
$propertyTypeMape    ];
    
    public static \$propertyTitleMap = [
$propertyTitleMap    ];

}

";
        File::ensureDirectoryExists('app/NotionModels');

        File::put("app/NotionModels/$databaseName.php", $contents);
        $this->info("Model for database {$this->argument('database_id')} has been created at 'app/NotionModels/$databaseName.php' .");

        return 0;
    }
}
