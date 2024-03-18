<?php

namespace Livestream\Streaming;

use Livestream\Streaming\Http\Controllers\LiveStreamingController;

/*
|--------------------------------------------------------------------------
| Wowza Live Stream Handler
|--------------------------------------------------------------------------
|
*/

class LiveStream{
    public $livestream;
    public function __construct()
    {
        if(is_null(config('livestream'))){
            $res = ["status" => 0, "message" => "Please publish the configuration file by running 'php artisan vendor:publish --tag=livestream-config'"];
            return json_encode($res);
        }

        $this->livestream = new LiveStreamingController();
    }

    /* Broadcast location list */
    public function BoradcastLocation(){
        $response = $this->livestream->BoradcastLocationList();
        return $response;
    }

    /* Camera encoder list */
    public function CameraEncoder(){
        $response = $this->livestream->CameraEncoderList();
        return $response;
    }

    /* Store live stream */
    public function StoreLiveStream($input){
        $response = $this->livestream->store($input);
        return $response;
    }

    /* List of all live stream */
    public function GetAllLiveStream($filterData=[], $pagination=true, $limit=10, $order_by=['created_at', 'desc']){
        $response = $this->livestream->listAll($filterData, $pagination, $limit, $order_by);
        return $response;
    }

    /* Get Single Live Stream  */
    public function SingleLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->GetSingleLiveStream($stream_id, $wowza_id);
        return $response;
    }

    /* Update live stream */
    public function UpdateLiveStream($input, $stream_id, $wowza_id){
        $response = $this->livestream->update($input, $stream_id, $wowza_id);
        return $response;
    }

    /* Remove Live Stream */
    public function RemoveLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->remove($stream_id, $wowza_id);
        return $response;
    }

    /* Start live stream */
    public function StartLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->start($stream_id, $wowza_id);
        return $response;
    }

    /* Publish Live Stream */
    public function PublishLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->publish($stream_id, $wowza_id);
        return $response;
    }

    /* Stop live stream */
    public function StopLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->stop($stream_id, $wowza_id);
        return $response;
    }

    /* Status of live stream */
    public function StatusLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->status($stream_id, $wowza_id);
        return $response;
    }

    /* Statistics of live stream */
    public function StatisticsLiveStream($stream_id, $wowza_id){
        $response = $this->livestream->statistics($stream_id, $wowza_id);
        return $response;
    }
}