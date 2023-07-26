namespace App\NotionModels;

use FiveamCode\LaravelNotionApi\Models\NotionModel;

/** 
{{$phpDocsProperties}}*/
class {{$databaseName}} extends NotionModel
{
    public static $databaseId = '{{$databaseId}}';

    public static $cacheDurationInSeconds = 0;

    public static $convertPropsToText = false;

    public static $visible = [
{{$visibleArray}}    ];

    public static $propertyTypeMap = [
{{$propertyTypeMape}}    ];
    
    public static $propertyTitleMap = [
{{$propertyTitleMap}}    ];

}