<?php

namespace FiveamCode\LaravelNotionApi\Macros;

use GuzzleHttp\Client;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PestHttpRecorder
{
    public static function register()
    {
        Http::macro('recordAndFakeLater', function (array|string $urls = ['*']) {
            if (!is_array($urls)) {
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
    private string $snapshotDirectory = 'snapshots';

    private bool $usePrettyJson = true;

    private array $requestNames = [];

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

    public function nameForNextRequest($name)
    {
        array_push($this->requestNames, $name);
    }

    public function handle(Request $request)
    {
        $forceRecording = in_array('--force-recording', $_SERVER['argv']);

        $urlInfo = parse_url($request->url());
        $payload = null;

        // create specific filename for storing snapshots
        $header = $request->headers();
        $method = Str::lower($request->method());
        $name = Str::slug(Str::replace('/', '-', $urlInfo['path']));
        $payload = ($method === 'get') ? ($urlInfo['query'] ?? null) : $request->body();
        $queryName = array_pop($this->requestNames) ?? ($payload ? hash('adler32', $payload) : '');

        if ($queryName != '') $queryName = '_' . $queryName;

        $fileName = "{$method}_{$name}{$queryName}.json";
        $directoryPath = "tests/{$this->snapshotDirectory}";
        $filePath = "{$directoryPath}/{$fileName}";

        // filter out Notion API Token Header
        $header = Arr::except($header, ['Authorization']);

        if ($forceRecording || !File::exists($filePath)) {
            File::makeDirectory($directoryPath, 0744, true, true);

            $client = new Client();
            $response = $client->request($request->method(), $request->url(), [
                'headers' => $request->headers(),
                'body' => $request->body(),
                'http_errors' => false,
            ]);

            $recordedResponse = [
                'header' => $header,
                'method' => $method,
                'status' => $response->getStatusCode(),
                'payload' => ($method === 'get') ? $payload : json_decode($payload, true),
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
