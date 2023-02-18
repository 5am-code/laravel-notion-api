<?php

namespace FiveamCode\LaravelNotionApi\Tests\Plugins;

use GuzzleHttp\Client;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PestPluginHttpRecorder
{
    public static function register()
    {
        Http::macro('recordAndFakeLater', function (array|string $urls = ['*']) {
            if (! is_array($urls)) {
                $urls = [$urls];
            }

            $recorder = new HttpRecorder();
            foreach ($urls as $url) {
                Http::fake([
                    $url => function (Request $request) use ($recorder) {
                        return $recorder->handle($request);
                    },
                ]);
            }

            return $recorder;
        });
    }
}

class HttpRecorder
{
    private $stubsFolder = '__recorded_stubs__';

    private $usePrettyJson = true;

    public function storeIn($directory)
    {
        $this->stubsFolder = $directory;

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

        //create specific filename for storing stubs
        $filename = Str::lower($request->method()).'_';
        $filename .= Str::slug(Str::replace('/', '-', $urlInfo['path']));
        $filename .= '_'.Str::slug(Str::replace('&', '_', Str::replace('=', '-', $urlInfo['query'])));
        $filename .= '.json';

        if ($forceRecording || ! File::exists('tests/'.$this->stubsFolder.'/'.$filename)) {
            File::makeDirectory('tests/'.$this->stubsFolder, 0777, true, true);

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
                'tests/'.$this->stubsFolder.'/'.$filename,
                json_encode($recordedResponse, $this->usePrettyJson ? JSON_PRETTY_PRINT : 0)
            );

            return Http::response($recordedResponse['data'], $response->getStatusCode());
        }

        $preRecordedData = json_decode(file_get_contents('tests/'.$this->stubsFolder.'/'.$filename), true);

        return Http::response($preRecordedData['data'], $preRecordedData['status']);
    }
}
