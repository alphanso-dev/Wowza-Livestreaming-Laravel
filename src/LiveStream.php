<?php

namespace Livestream\Streaming;

use Livestream\Streaming\Http\Controllers\LiveStreamingController;
use Illuminate\Support\Facades\Schema;
/*
|--------------------------------------------------------------------------
| Wowza Live Stream Handler
|--------------------------------------------------------------------------
|
*/

class LiveStream{
    public $livestream;
    public $config_status = true;
    public $table_status = true;
    public function __construct()
    {
        if(is_null(config('livestream'))){
            $this->config_status = false;
        }
        if(!Schema::hasTable('live_streamings')){
            $this->table_status = false;
        }
        $this->livestream = new LiveStreamingController();
    }

    /* Broadcast location list */
    public function BroadcastLocation(){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->BroadcastLocationList();
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Camera encoder list */
    public function CameraEncoder(){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->CameraEncoderList();
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Store live stream */
    public function StoreLiveStream($request){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->store($request);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* List of all live stream */
    public function GetAllLiveStream($filterData=[], $pagination=true, $limit=10, $order_by=['created_at', 'desc']){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->listAll($filterData, $pagination, $limit, $order_by);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Get Single Live Stream  */
    public function SingleLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->GetSingleLiveStream($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Update live stream */
    public function UpdateLiveStream($request, $stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->update($request, $stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Remove Live Stream */
    public function RemoveLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->remove($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Start live stream */
    public function StartLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->start($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Publish Live Stream */
    public function PublishLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->publish($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Stop live stream */
    public function StopLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->stop($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Status of live stream */
    public function StatusLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->status($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }

    /* Statistics of live stream */
    public function StatisticsLiveStream($stream_id, $wowza_id){
        if($this->config_status == true){
            if($this->table_status == true){
                $response = $this->livestream->statistics($stream_id, $wowza_id);
            }else{
                $response = $this->livestream->TableError();
            }
        }else{
            $response = $this->livestream->ConfigError();
        }
        return $response;
    }
}