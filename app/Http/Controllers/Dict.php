<?php

namespace App\Http\Controllers;

use App\Models\Dict as ModelsDict;
use App\Models\Tip;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Dict extends Controller
{
    public function search(Request $request)
    {
        $wordInput = $request->input('word', null);
        if (is_null($wordInput)) {
            $listDict = ModelsDict::where('count', 1)->get();
            $wordResponse = $listDict[random_int(1, count($listDict)) - 1];
        } else {
            $wordResponse = ModelsDict::where('hanzi', $wordInput)->first();
        }
        $data = json_decode($wordResponse->mean, true);
        $means = [];
        foreach ($data as $type => $value) {
            $key = $this->changKey($type);
            $means[$key] = $value;
        }
        $wordResponse->mean = $means;
        $soundCheck = $wordResponse->pinyin;
        $wordCheck = $wordResponse->hanzi;
        $wordSearch = [$wordResponse->toArray()];

        $soundSearch = ModelsDict::orderBy('id_hanzi', 'desc')->where('pinyin', $soundCheck)->get();
        $soundSearch = $soundSearch->map(function ($item) {
            $data = json_decode($item->mean, true);
            $means = [];
            foreach ($data as $type => $value) {
                $key = $this->changKey($type);
                $means[$key] = $value;
            }
            $item->mean = $means;
            return $item;
        })->toArray();
        $soundHomonym = array_filter($soundSearch, function ($item) use ($wordCheck, $soundCheck) {

            return $item['hanzi'] != $wordCheck && $item['pinyin'] == $soundCheck;
        });
        $soundRelated = array_filter($soundSearch, function ($item) use ($wordCheck, $soundCheck) {
            return $item['hanzi'] != $wordCheck && $item['pinyin'] != $soundCheck;
        });
        $wordRelated = ModelsDict::where('hanzi', 'like', "%$wordCheck%")->get();
        $wordRelated = $wordRelated->map(function ($item) {
            $data = json_decode($item->mean, true);
            $means = [];
            foreach ($data as $type => $value) {
                $key = $this->changKey($type);
                $means[$key] = $value;
            }
            $item->mean = $means;
            return $item;
        })->toArray();
        $wordRelated = array_filter($wordRelated, function ($item) use ($wordCheck) {
            return $item['hanzi'] != $wordCheck;
        });
        $wordTip = Tip::all()->toArray();
        return view('app', compact(['wordSearch', 'soundHomonym', 'soundRelated', 'wordRelated', 'wordTip']));
    }

    public function changKey($type)
    {
        switch ($type) {
            case 'n':
                $result = 'Danh từ';
                break;
            case 'v':
                $result = 'Động từ';
                break;
            case 'v, sv':
                $result = 'Động từ, Động từ li hợp';
                break;
            case 'sv':
                $result = 'Động từ li hợp';
                break;
            case 'adj':
                $result = 'Tính từ';
                break;
            case 'adv':
                $result = 'Phó từ';
                break;
            case 'prep':
                $result = 'Giới từ';
                break;
            case 'measure':
                $result = 'Lượng từ';
                break;
            case 'numb':
                $result = 'Số từ';
                break;
            case 'pro':
                $result = 'Đại từ';
                break;
            case 'conj':
                $result = 'Liên từ';
                break;
            case 'intj':
                $result = 'Thán từ';
                break;
            case 'part':
                $result = 'Trợ từ';
                break;
            case '助':
                $result = 'Trợ từ';
                break;

            case 'adage':
                $result = "Ngạn ngữ";
                break;
            case 'allegorical':
                $result = "Yết hậu ngữ";
                break;
            case 'av':
                $result = "Trợ động từ";
                break;
            case 'class':
                $result = "Từ chỉ số lượng";
                break;
            case 'dist':
                $result = "Từ phân loại";
                break;
            case 'idioms':
                $result = "Thành ngữ";
                break;
            case 'locativ':
                $result = "Từ mượn";
                break;
            case 'locution':
                $result = "Quán dụng ngữ";
                break;
            case 'mpart':
                $result = "Trợ/tiểu từ thuộc trạng/lối, trợ/tiểu từ ngữ khí";
                break;
            case 'n':
                $result = "Danh từ";
                break;
            case 'nlocal':
                $result = "Danh từ chỉ vị trí";
                break;
            case 'onom':
                $result = "Từ tượng thanh";
                break;
            case 'phrase':
                $result = "Cụm từ";
                break;
            case 'pref':
                $result = "Tiền tố";
                break;
            case 'proverb':
                $result = "Tục ngữ";
                break;
            case 'punct':
                $result = "Dấu câu";
                break;
            case 'sentence':
                $result = "Câu";
                break;
            case 'stt':
                $result = "Từ trạng thái";
                break;
            case 'suff':
                $result = "Hậu tố";
                break;
            case 'time':
                $result = "Từ chỉ thời gian";
                break;
            default:
                $result = $type;
                break;
        }

        return $result;
    }
}
