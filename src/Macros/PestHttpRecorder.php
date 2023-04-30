<?php

namespace FiveamCode\LaravelNotionApi\Macros;

use GuzzleHttp\Client;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PestHttpRecorder
{
    public static function register()
    {
        Http::macro('recordAndFakeLater', function (array|string $urls = ['*']) {
            if (! is_array($urls)) {
                $urls = [$urls];
            }

            $recorder = new HttpRecorder();
            $httpFakeCallbacks = [];

            foreach ($urls as $url) {
                $httpFakeCallbacks[$url] = fn (Request $request) => $recorder->handle($request);
            }

            Http::fake($httpFakeCallbacks);

            return $recorder;
        });
    }
}

class HttpRecorder
{
    private $snapshotDirectory = 'snapshots';

    private $usePrettyJson = true;

    public function storeIn($directory)
    {
        $this->snapshotDirectory = $directory;

        return $this;
    }

    public function minifyJson()
    {
        $this->usePrettyJson = false;

        return $this;
    }

    public function handle(Request $request)
    {
        $forceRecording = in_array('--force-recording', $_SERVER['argv']);

        $urlInfo = parse_url($request->url());

        //create specific filename for storing snapshots
        $method = Str::lower($request->method());
        $name = Str::slug(Str::replace('/', '-', $urlInfo['path']));
        $query = Str::slug(Str::replace('&', '_', Str::replace('=', '-', $urlInfo['query'])));

        $fileName = "{$method}_{$name}_{$query}.json";
        $directoryPath = "tests/{$this->snapshotDirectory}";
        $filePath = "{$directoryPath}/{$fileName}";

        if ($forceRecording || ! File::exists($filePath)) {
            File::makeDirectory($directoryPath, 0777, true, true);

            $client = new Client();
            $response = $client->request($request->method(), $request->url(), [
                'headers' => $request->headers(),
                'body' => $request->body(),
                'http_errors' => false,
            ]);

            $recordedResponse = [
                'status' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true),
            ];

            file_put_contents(
                $filePath,
                json_encode($recordedResponse, $this->usePrettyJson ? JSON_PRETTY_PRINT : 0)
            );

            return Http::response($recordedResponse['data'], $response->getStatusCode());
        }

        $preRecordedData = json_decode(file_get_contents($filePath), true);

        return Http::response($preRecordedData['data'], $preRecordedData['status']);
    }
}
