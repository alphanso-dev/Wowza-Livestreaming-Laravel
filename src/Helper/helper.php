<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

function RendomString($size=10, $type='mix'){
    /* Type : 'number','string','mix' */
    $size = $size==null?10:$size;
    $code = '';
    if ($type == 'number'){
        $akeys = range('0', '9');
        for ($i = 0; $i < $size; $i++) {
            $code .= $akeys[array_rand($akeys)];
        }
    } elseif ($type == 'string') {
        $akeys = range('A', 'Z');
        $bkeys = range('a', 'z');
        $ckeys = array_merge($akeys,$bkeys);
        for ($i = 0; $i < $size; $i++) {
            $code .= $ckeys[array_rand($ckeys)];
        }
    }else{
        $code = Str::random($size);
    }
    return str_shuffle($code);
}

/* Run All Wowza API */
function RunApi(string $url, string $method, array $data = []){
    $wowza_live_stream_api_endpoint = config('livestream.livestream_endpoint');
    $wowza_live_stream_api_header = [
        "Content-Type:"  	. "application/json",
        "charset:"			. "utf-8",
        "Authorization: Bearer ". config('livestream.livestream_token')
    ];

    $final_url = $wowza_live_stream_api_endpoint.$url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST , $method);
    curl_setopt($ch, CURLOPT_URL,$final_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $wowza_live_stream_api_header);
    if(count($data) > 0 && ($method == 'POST' || $method == 'PATCH')){
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $server_output = curl_exec($ch);
    $err = curl_error($ch);
    curl_close ($ch);
    $output = json_decode($server_output);
    return $output;
}

/* Validation Response - request_type = store or update */
function RequestValidation($request, $request_type='store'){
    if($request_type == 'store'){
        $validator = Validator::make($request, [
            'user_id'               => 'required|integer',
            'stream_title'			=> 'required|max:100',
            'broadcast_location'	=> 'required',
            'encoder'		        => 'required',
            'description'			=> 'nullable|max:10000',
            'image'					=> 'required',
            'stream_price'			=> 'nullable|decimal:0,2',
            'price_currency'        => 'required',
            'stream_date'			=> 'required|date',
            'stream_time'			=> 'required',
        ],[
            'stream_title.required' => 'stream title field is required'
        ]);
    }else{
        $validator = Validator::make($request, [
            'user_id'               => 'required|integer',
            'stream_title'			=> 'required|max:100',
            'encoder'		        => 'required',
            'description'			=> 'nullable|max:10000',
            'image'					=> 'required',
            'stream_price'			=> 'nullable|decimal:0,2',
            'price_currency'        => 'required',
            'stream_date'			=> 'required|date',
            'stream_time'			=> 'required',
        ],[
            'stream_title.required' => 'stream title field is required'
        ]);
    }
    if ($validator->fails()) {
        $response = ['status' => 0, 'message' => 'Validation Failed.', 'data' => $validator->errors()];
    }else{
        $response = ['status' => 1, 'messgae' => 'Validation Success.'];
    }
    return $response;
}
