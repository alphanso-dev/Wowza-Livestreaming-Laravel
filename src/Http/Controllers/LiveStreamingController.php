<?php

namespace Livestream\Streaming\Http\Controllers;

use App\Http\Controllers\Controller;

use Livestream\Streaming\Http\Controllers\WowzaLiveStreamingController;
use Livestream\Streaming\LiveStream;
use Livestream\Streaming\Models\LiveStreaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LiveStreamingController extends Controller{

    /* controller global variable */
    public $wowzalivestream;

    /* common global variables */
    public $status_0 = 0;
    public $status_1 = 1;
    public $status_code = 202;
    public $message = '';
    public $response;

    /* model global variable */
    public $livestreammodel;

    public function __construct()
    {
        $this->wowzalivestream = new WowzaLiveStreamingController();
        $this->livestreammodel = new LiveStreaming();
    }

    public function ConfigError(){
        $this->message = "Please publish the configuration file by running 'php artisan vendor:publish --tag=livestream-config'";
        $this->status_code = 202;
        $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
    }

    public function TableError(){
        $this->message = "Please publish the configuration file by running 'php artisan vendor:publish --tag=livestream-migration' and after that run 'php artisan migrate'";
        $this->status_code = 202;
        $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
    }

    /*
    |--------------------------------------------------------------------------
    | Wowza Live Stream Controller
    |--------------------------------------------------------------------------
    |
    */

    /* Broadcast location list */
    public function BroadcastLocationList(){
        $broadcast_location = [
			"asia_pacific_australia"	=> "Asia Pacific Australia",
			"asia_pacific_india"		=> "Asia Pacific India",
			"asia_pacific_japan"		=> "Asia Pacific Japan",
			"asia_pacific_singapore"	=> "Asia Pacific Singapore",
			"asia_pacific_s_korea"		=> "Asia Pacific South Korea",
			"asia_pacific_taiwan"		=> "Asia Pacific Taiwan",
			"eu_belgium"				=> "Europ Belgium",
			"eu_germany"				=> "Europ Germany",
			"eu_ireland"				=> "Europ Ireland",
			"south_america_brazil"		=> "South America Brazil",
			"us_central_iowa"			=> "US Central Iowa",
			"us_east_s_carolina"		=> "US East Carolina",
			"us_east_virginia"			=> "US East Virginia",
			"us_west_california"		=> "US West California",
			"us_west_oregon"			=> "US West Oregon"
		];
        $this->message = "Broadcast Location found";
        $this->response = $broadcast_location;
        
        return ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => $this->response];
    }

    /* Camera encoder list */
    public function CameraEncoderList(){
        $camera_encoder = [
			"other_webrtc"				=> "Webrtc",
			"media_ds"					=> "Media DS",
			"axis"						=> "Axis",
			"epiphan"					=> "Epiphan",
			"hauppauge"					=> "Hauppauge",
			"jvc"						=> "JVC",
			"live_u"					=> "Live U",
			"matrox"					=> "Matrox",
			"newtek_tricaster"			=> "Newtek Tricaster",
			"osprey"					=> "Osprey",
			"sony"						=> "Sony",
			"telestream_wirecast"		=> "Telestream Wirecast",
			"teradek_cube"				=> "Teradek Cube",
			"vmix"						=> "Vmix",
			"x_split"					=> "X Split",
			"ipcamera"					=> "ip Camera",
			"other_rtmp"				=> "Other RTMP",
			"other_rtsp"				=> "Other RTSP",
			"other_srt"					=> "Other SRT",
			"other_udp"					=> "Other UDP",
			// "file"						=> "File",
		];
        $this->message = "Camera Encoder found";
        $this->response = $camera_encoder;
        return ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => $this->response];
    }

    /* Store live stream */
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'user_id'               => 'required|integer',
            'sname'			        => 'required|max:100',
            'broadcast_location'	=> 'required',
            'encoder'		        => 'required',
            'description'			=> 'nullable|max:10000',
            'image'					=> 'required|mimes:jpeg,png,jpg,gif|max:2048',
            'stream_price'			=> 'nullable|decimal:0,2',
            'price_currency'        => 'required',
            'stream_date'			=> 'required|date',
            'stream_time'			=> 'required',
        ],[
            'sname.required' => 'stream title field is required'
        ]);

        if ($validator->fails()) {
            $this->status_code = 202;
            $this->message = $validator->errors()->first();
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }else{
            $input['image'] = 'image path';
            $inputdata['live_stream'] = [
                "name"                  => $input['sname'],
                "broadcast_location"    => $input['broadcast_location'],
                "description"           => $input['description'],
                "transcoder_type"       => "transcoded",
                "billing_mode"          => "pay_as_you_go",
                "encoder"               => $input['encoder'],
                "disable_authentication" => true,
                "aspect_ratio_height"   => "720",
                "aspect_ratio_width"    => "1280",
                "delivery_method"       => "push",
                "player_responsive"     => true,
                "low_latency"           => true,
                "recording"             => false
            ];
            $output = $this->wowzalivestream->WowzaLiveStreamApiCreate($inputdata);
            if(isset($output->live_stream)) {
                $outputData = $output->live_stream;
                $stream_id = (int)RendomString(10, 'number');
                $inputStore = [
                    'stream_id' => $stream_id,
                    'user_id' => $input['user_id'],
                    'wowza_id' => $outputData->id,
                    'stream_title' => $outputData->name,
                    'description' => $outputData->description,
                    'state' => $outputData->state,
                    'billing_mode' => $outputData->billing_mode,
                    'broadcast_location' => $outputData->broadcast_location,
                    'recording' => $outputData->recording,
                    'encoder' => $outputData->encoder,
                    'delivery_method' => $outputData->delivery_method,
                    'sdp_url' => $outputData->source_connection_information->sdp_url,
                    'application_name' => $outputData->source_connection_information->application_name,
                    'stream_name' => $outputData->source_connection_information->stream_name,
                    'hls_playback_url' => $outputData->hls_playback_url,
                    'stream_price' => $input['stream_price'],
                    'price_currency' => $input['price_currency'],
                    'image' => $input['image'],
                    'player_id' => $outputData->player_id,
                    'stream_date' => $input['stream_date'],
                    'stream_time' => $input['stream_time']
                ];
                /* model call */
                $insert = $this->livestreammodel->InsertData($inputStore);
                if(isset($insert->wowza_id)) {
                    $this->message = "Live Streaming create successully.";
                    $response =  ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                }else{
                    /* Wowza stream should be remove */
                    do{
                        $removeOutput = $this->wowzalivestream->WowzaLiveStreamApiRemove($outputData->id);
                    }while($removeOutput != null);

                    $this->message = 'Live Streaming not crete please try again.';
                    $this->status_code = 202;
                    $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }else if(isset($output->meta)) {
                $this->message = $output->meta->message;
                $this->status_code = 202;
                $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
            }else{
                $this->message = 'Live Streaming not crete please try again.';
                $this->status_code = 202;
                $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
            }
        }
        return $response;
    }

    /* List of all live stream */
    public function listAll($filterData, $pagination, $limit, $order_by){
        if(!is_array($filterData)){ $filterData = []; }
        if($pagination != true || $pagination != false){ $pagination = true; }
        if(!is_numeric($limit)){ $limit = 10; }
        if(!is_array($order_by)){ $order_by = ['created_at', 'desc']; }
        /* model call */
        $data = $this->livestreammodel->ListData($filterData, $pagination, $limit, $order_by);
        $this->message = "Live streams data found.";
        $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => $data];
        return $response;
    }

    /* Get Single Live Stream  */
    public function GetSingleLiveStream($stream_id, $wowza_id){
        /* model call */
        $singleStream = $this->livestreammodel->GetSingleData($stream_id, $wowza_id);
        if(!is_null($singleStream)){
            $wowzaData = $this->wowzalivestream->WowzaLiveStreamApiSingle($wowza_id);
            $this->message = "Live stream found.";
            $this->response = $singleStream;
            $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => $singleStream, 'wowzaData' => $wowzaData];
        }else{
            $this->status_code = 202;
            $this->message = "Live stream details not found.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Update live stream */
    public function update(Request $request, $stream_id, $wowza_id){
        if(isset($stream_id) && is_numeric($stream_id)){
            if(isset($wowza_id)){
                $input = $request->all();
                $validator = Validator::make($request->all(), [
                    'user_id'               => 'required|integer',
                    'sname'			        => 'required|max:100',
                    'encoder'		        => 'required',
                    'description'			=> 'nullable|max:10000',
                    'image'					=> 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
                    'stream_price'			=> 'nullable|decimal:0,2',
                    'price_currency'        => 'required',
                    'stream_date'			=> 'required|date',
                    'stream_time'			=> 'required',
                ],[
                    'sname.required' => 'stream title field is required'
                ]);

                if ($validator->fails()) {
                    $this->status_code = 202;
                    $this->message = $validator->errors()->first();
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }else{
                    $input['image'] = 'image path';
                    /* model call */
                    $singleStream = $this->livestreammodel->GetSingleData($stream_id, $wowza_id);
                    if(!is_null($singleStream) && $singleStream->state == 'stopped'){
                        $inputdata['live_stream'] = [
                            "name"                  => $input['sname'],
                            "description"           => $input['description'],
                            "transcoder_type"       => "transcoded",
                            "billing_mode"          => "pay_as_you_go",
                            "encoder"               => $input['encoder'],
                            "disable_authentication" => true,
                            "aspect_ratio_height"   => "720",
                            "aspect_ratio_width"    => "1280",
                            "delivery_method"       => "push",
                            "player_responsive"     => true,
                            "low_latency"           => true,
                            "recording"             => false
                        ];
                        $output = $this->wowzalivestream->WowzaLiveStreamApiUpdate($inputdata, $wowza_id);
                        if(isset($output->live_stream)) {
                            $outputData = $output->live_stream;
                            $inputStore = [
                                'stream_title' => $outputData->name,
                                'description' => $outputData->description,
                                'state' => $outputData->state,
                                'billing_mode' => $outputData->billing_mode,
                                'recording' => $outputData->recording,
                                'encoder' => $outputData->encoder,
                                'delivery_method' => $outputData->delivery_method,
                                'sdp_url' => $outputData->source_connection_information->sdp_url,
                                'application_name' => $outputData->source_connection_information->application_name,
                                'stream_name' => $outputData->source_connection_information->stream_name,
                                'hls_playback_url' => $outputData->hls_playback_url,
                                'stream_price' => $input['stream_price'],
                                'price_currency' => $input['price_currency'],
                                'image' => $input['image'],
                                'player_id' => $outputData->player_id,
                                'stream_date' => $input['stream_date'],
                                'stream_time' => $input['stream_time']
                            ];
                            /* model call */
                            $update = $this->livestreammodel->UpdateData($inputStore, $stream_id, $wowza_id);
                            if($update) {
                                $this->message = "Live Streaming update successully.";
                                $response =  ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                            }else{
                                $this->message = 'Live Streaming not update please try again.';
                                $this->status_code = 202;
                                $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                            }
                        }else if(isset($output->meta)) {
                            $this->message = $output->meta->message;
                            $this->status_code = 202;
                            $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                        }else{
                            $this->message = 'Live Streaming not crete please try again.';
                            $this->status_code = 202;
                            $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                        }
                    }else if(!is_null($singleStream) && $singleStream->state == 'started'){
                        $this->status_code = 202;
                        $this->message = "Live stream is started, please stop first and then update.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }else{
                        $this->status_code = 202;
                        $this->message = "Live stream details not found.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }
                }
            }else{
                $this->message = 'wowza_id is missing.';
                $this->status_code = 202;
                $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];    
            }
        }else{
            $this->message = 'Either stream_id is missing or pass properly.';
            $this->status_code = 202;
            $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Remove Live Stream */
    public function remove($stream_id, $wowza_id){
        /* model call */
        $singleStream = $this->livestreammodel->GetSingleData($stream_id, $wowza_id);
        if(!is_null($singleStream) && $singleStream->state == 'stopped'){
            $output = $this->wowzalivestream->WowzaLiveStreamApiRemove($wowza_id);
            if($output == null){
                /* model call */
                $delete = $this->livestreammodel->DeleteData($stream_id, $wowza_id);
                if($delete){
                    $this->message = "Live stream remove successfully.";
                    $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                }else{
                    $this->status_code = 202;
                    $this->message = "Live stream is not remove, please try again.";
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }else{
                if($output->meta->status == 404){
                    /* model call */
                    $delete = $this->livestreammodel->DeleteData($stream_id, $wowza_id);
                    if($delete){
                        $this->message = "Live stream remove successfully.";
                        $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                    }else{
                        $this->status_code = 202;
                        $this->message = "Live stream is not remove, please try again.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }
                }else{
                    $this->status_code = 202;
                    $this->message = $output->meta->message;
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }
        }else if(!is_null($singleStream) && $singleStream->state == 'started'){
            $this->status_code = 202;
            $this->message = "Live stream is started, please stop first and then remove.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }else{
            $this->status_code = 202;
            $this->message = "Live stream details not found.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Start live stream */
    public function start($stream_id, $wowza_id){
        $streamData = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if($streamData['status'] == 1 && isset($streamData['data']->state) && $streamData['data']->state == 'stopped'){
            /* model call */
            $output = $this->wowzalivestream->WowzaLiveStreamApiStart($wowza_id);
            if(isset($output->live_stream) && $output->live_stream->state == 'starting'){
                $inputStore = ['state' => 'started', 'stream_status' => 1];
                do {
                    $streamStatus = $this->status($stream_id,$wowza_id);
                } while ($streamStatus['status'] == 1 && isset($streamStatus['data']->live_stream) && $streamStatus['data']->live_stream->state != 'started');
                
                /* model call */
                $update = $this->livestreammodel->UpdateData($inputStore, $stream_id, $wowza_id);
                if($update){
                    /* success response */
                    $this->message = "Live stream started";
                    $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                }else{
                    $this->status_code = 202;
                    $this->message = "Live stream not publishing, please try again.";
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }else{
                if($output->meta->status == 422){
                    $inputStore = ['state' => 'started', 'stream_status' => 1];
                    $update = $this->livestreammodel->UpdateData($inputStore, $stream_id, $wowza_id);
                    if($update){
                        /* success response */
                        $this->message = "Live stream started";
                        $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                    }else{
                        $this->status_code = 202;
                        $this->message = "Live stream not publishing, please try again.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }
                }else{
                    $this->status_code = 202;
                    $this->message = $output->meta->message;
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }
        }else if($streamData['status'] == 1 && isset($streamData['data']->state) && $streamData['data']->state == 'started'){
            $this->status_code = 202;
            $this->message = "Live stream already started.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }else{
            $this->status_code = 202;
            $this->message = "Something went wrong please try again.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Publish live stream */
    public function publish($stream_id, $wowza_id){
        $streamData = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if($streamData['status'] == 1){
            if(isset($streamData['data']->state) && $streamData['data']->state == 'started'){
                $getStream = $this->wowzalivestream->WowzaLiveStreamApiSingle($wowza_id);
                if(isset($getStream->live_stream)) {
                    $streamStatus = $this->status($stream_id,$wowza_id);
                    if($streamStatus['status'] == 1 && isset($streamStatus['data']->live_stream) && $streamStatus['data']->live_stream->state != 'stopped'){
                        $this->status_code = 202;
                        $this->message = "Live Streaming is not started please try again.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }
                    if(isset($streamStatus['data']->live_stream->state)){
                        if($streamStatus['data']->live_stream->state == 'started' || $streamStatus['data']->live_stream->state == 'starting' ){
                            do {
                                $streamStatusCheck = $this->status($stream_id,$wowza_id);
                            } while ($streamStatusCheck['status'] == 1 && isset($streamStatusCheck['data']->live_stream) && $streamStatusCheck['data']->live_stream->state != 'started');

                            $this->message = "Live stream published";
                            $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => ['stream_id' => $stream_id, 'stream_data' => '']];
                        }else{
                            $this->status_code = 202;
                            $this->message = "Live Streaming is not started please try again.";
                            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                        }
                    }else{
                        $this->status_code = 202;
                        $this->message = "Live Streaming is not started please try again.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }
                }else if(isset($getStream->meta)) {
                    $this->status_code = 202;
                    $this->message = $getStream->meta->message;
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                } else {
                    $this->status_code = 202;
                    $this->message = "Live Streaming is not started please try again.";
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }else{
                $this->status_code = 202;
                $this->message = "Live Stream not publishing, please publish again.";
                $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];    
            }
        }else{
            $this->status_code = 202;
            $this->message = "Live Stream details not found.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Stop live stream */
    public function stop($stream_id, $wowza_id){
        $streamData = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if($streamData['status'] == 1 && isset($streamData['data']->state) && $streamData['data']->state == 'started'){
            $output = $this->wowzalivestream->WowzaLiveStreamApiStop($wowza_id);
            if(isset($output->live_stream) && $output->live_stream->state == 'stopped'){
                $inputdata = ['state' => 'stopped', 'stream_status' => 0, 'advertisement_status' => 0];
                /* model call */
                $update = $this->livestreammodel->UpdateData($inputdata, $stream_id, $wowza_id);
                if($update){
                    $this->message = "Live stream stopped.";
                    $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                }else{
                    $this->status_code = 202;
                    $this->message = "Live stream not stopped, please try again.";
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }else{
                if($output->meta->status == 422){
                    $inputdata = ['state' => 'stopped', 'stream_status' => 0, 'advertisement_status' => 0];
                    /* model call */
                    $update = $this->livestreammodel->UpdateData($inputdata, $stream_id, $wowza_id);
                    if($update){
                        $this->message = "Live stream stopped.";
                        $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
                    }else{
                        $this->status_code = 202;
                        $this->message = "Live stream not stopped, please try again.";
                        $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                    }
                }else{
                    $this->status_code = 202;
                    $this->message = $output->meta->message;
                    $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
                }
            }
        }else{
            $this->status_code = 202;
            $this->message = "Live stream already stopped.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Status of live stream */
    public function status($stream_id, $wowza_id){
        $streamData = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if($streamData['status'] == 1){
            /* model call */
            $output = $this->wowzalivestream->WowzaLiveStreamApiStatus($wowza_id);
            if(isset($output->live_stream)){
                $this->message = "Status found.";
                $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => $output];
            }else if($output->meta){
                $this->status_code = 202;
                $this->message = $output->meta->message;
                $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];    
            }else{
                $this->status_code = 202;
                $this->message = "Status not found.";
                $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
            }
        }else{
            $this->status_code = 202;
            $this->message = "Something went wrong please try again.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /* Statistics of live stream */
    public function statistics($stream_id, $wowza_id){
        $streamData = $this->GetSingleLiveStream($stream_id, $wowza_id);
        if($streamData['status'] == 1){
            /* model call */
            $ingestoutput = $this->wowzalivestream->WowzaLiveStreamApiIngestAnalyticsData($wowza_id);
            $vieweroutput = $this->wowzalivestream->WowzaLiveStreamApiViewersAnalyticsData($wowza_id);
            if(isset($ingestoutput->live_stream) && isset($vieweroutput->live_stream)){
                $res = [
                    'inbound' => $ingestoutput->live_stream->connected->value,
                    'inbound_bit_rate' => $ingestoutput->live_stream->bytes_in_rate->value,
                    'frame_size' => $ingestoutput->live_stream->frame_size->value,
                    'frame_rate' => $ingestoutput->live_stream->frame_rate->value,
                    'keyframe_interval' => $ingestoutput->live_stream->keyframe_interval->value,
                    'unique_views' => $vieweroutput->live_stream->viewers
                ];
                $this->message = "Statistic details found.";
                $response = ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message, 'data' => $res];    
            }else{
                $this->status_code = 202;
                $this->message = "Statistic details not found.";
                $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];    
            }
        }else{
            $this->status_code = 202;
            $this->message = "Something went wrong please try again.";
            $response = ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }


    /*
    |-------------------------------------------------------------
    | Start:: Common Functions
    |-------------------------------------------------------------
    */

    public function checkCreateParameters($input){
        if(isset($input['user_id']) && isset($input['sname']) && isset($input['broadcast_location']) && isset($input['description']) && isset($input['encoder']) && isset($input['stream_price']) && isset($input['price_currency']) && isset($input['image']) && isset($input['stream_date']) && isset($input['stream_time'])){
            if($input['user_id'] != null && $input['sname'] != null && $input['broadcast_location'] != null && $input['encoder'] != null && $input['stream_price'] != null && $input['price_currency'] != null && $input['stream_date'] != null && $input['stream_time'] != null){
                $this->message = "Parameters properly set";
                $response =  ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
            }else{
                $this->message = "This parameters value should be set: user_id, sname, broadcast_location, encoder, stream_price, price_currency, stream_date, stream_time";
                $this->status_code = 202;
                $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
            }
        }else{
            $this->message = "At least this parameters should be set: user_id, sname, broadcast_location, description, encoder, stream_price, price_currency, image, stream_date, stream_time";
            $this->status_code = 202;
            $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    public function checkUpdateParameters($input){
        if(isset($input['user_id']) && isset($input['sname']) && isset($input['description']) && isset($input['encoder']) && isset($input['stream_price']) && isset($input['price_currency']) && isset($input['image']) && isset($input['stream_date']) && isset($input['stream_time'])){
            if($input['user_id'] != null && $input['sname'] != null && $input['encoder'] != null && $input['stream_price'] != null && $input['price_currency'] != null && $input['stream_date'] != null && $input['stream_time'] != null){
                $this->message = "Parameters properly set";
                $response =  ['status' => $this->status_1, 'status_code' => $this->status_code, 'message' => $this->message];
            }else{
                $this->message = "This parameters value should be set: user_id, sname, encoder, stream_price, price_currency, stream_date, stream_time";
                $this->status_code = 202;
                $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
            }
        }else{
            $this->message = "At least this parameters should be set: user_id, sname, description, encoder, stream_price, price_currency, image, stream_date, stream_time";
            $this->status_code = 202;
            $response =  ['status' => $this->status_0, 'status_code' => $this->status_code, 'message' => $this->message];
        }
        return $response;
    }

    /*
    |-------------------------------------------------------------
    | End:: Common Functions
    |-------------------------------------------------------------
    */

    public function storeValidation($input){
        if(isset($input['user_id'])){
            
        }else{
            $message = "user_id parameter is missing";
        }
    }
}