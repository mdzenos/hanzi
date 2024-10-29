<?php

namespace App\Http\Controllers;

use App\Models\Dict;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class SyncDict extends Controller
{
    public function sync(Request $request)
    {
        $listAPI = [
            'https://api.hanzii.net/api/hsk/1',
            'https://api.hanzii.net/api/hsk/2',
            'https://api.hanzii.net/api/hsk/3',
            'https://api.hanzii.net/api/hsk/4',
            'https://api.hanzii.net/api/hsk/5',
            'https://api.hanzii.net/api/hsk/6',
            'https://api.hanzii.net/api/hsk/7-9',
            'https://api.hanzii.net/api/notebooks/tocfl/1',
            'https://api.hanzii.net/api/notebooks/tocfl/2',
            'https://api.hanzii.net/api/notebooks/tocfl/3',
            'https://api.hanzii.net/api/notebooks/tocfl/4',
            'https://api.hanzii.net/api/notebooks/tocfl/5',
        ];

        $client = new Client();
        $result = ['message' => 'Thành công!'];

        try {
            foreach ($listAPI as $apiUrl) {
                $responses = $this->fetchDataFromApi($client, $apiUrl);
                if (empty($responses)) {
                    Log::error("Không nhận được dữ liệu từ API: $apiUrl");
                }
                foreach ($responses as $response) {
                    try {
                        $id = $response['detail']['id'] ?? $response['id'];
                        $soundName = $id . '.mp3';
                        $link = $this->downloadSound($client, $soundName);
                        $mean = $this->formatMean($response['detail']['content'] ?? $response['content']);
                        $hanzi = $response['word'];
                        $pinyin = $response['detail']['pinyin'] ?? $response['pinyin'];
                        $hanviet = $response['detail']['cn_vi'] ?? ($response['cn_vi'] ?? $response['phonetic']);
                        $count = count(explode(' ', $hanviet));
                        $rank = $response['detail']['rank'] ?? $response['rank'];
                        Dict::updateOrCreate(
                            [
                                'id_hanzi' => $id,
                            ],
                            [
                                'hanzi' => $hanzi,
                                'pinyin' => $pinyin,
                                'hanviet' => $hanviet,
                                'mean' => json_encode($mean),
                                'rank' => $rank,
                                'sound' => $link,
                                'from' => $apiUrl,
                                'count' => $count
                            ]
                        );
                    } catch (\Exception $e) {
                        Log::error("Có lỗi khi lưu dữ liệu: " . $e->getMessage() . " - API: $apiUrl " . json_encode($response));
                    }
                }
            }
        } catch (\Exception $e) {
            $result = ['message' => $e->getMessage()];
        }

        return $result;
    }

    private function fetchDataFromApi($client, $apiUrl)
    {
        $responses = [];
        $page = 1;
        do {
            $queryParams = [
                'page' => $page,
                'limit' => 200,
                'lang' => 'vi',
                'version' => 2,
            ];
            $res = $client->request('GET', $apiUrl, [
                'query' => $queryParams,
            ]);

            $data = json_decode($res->getBody(), true);
            if (!empty($data['data'])) {
                $page++;
                $responses = array_merge($responses, $data['data']);
            } else {
                break;
            }
        } while (true);
        return $responses;
    }

    private function downloadSound($client, $soundName)
    {
        $soundUrl = 'https://audio.hanzii.net/audios/cnvi/0/' . $soundName;
        try {
            $response = $client->request('GET', $soundUrl);
            if ($response->getStatusCode() === 200) {
                $filePath = storage_path('/app/public/audios/' . $soundName);
                file_put_contents($filePath, $response->getBody()->getContents());
                return url('storage/audios/' . $soundName);
            }
        } catch (\Exception $e) {
            return '';
        }
        return '';
    }

    private function formatMean($content)
    {
        $mean = [];
        foreach ($content as $meanItem) {
            foreach ($meanItem['means'] as $item) {
                $mean[$meanItem['kind']][] = $item['mean'];
            }
        }
        return $mean;
    }
}
