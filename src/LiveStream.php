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
            //$this->config_status = false;
            throw new \Exception("Please publish the configuration file by running 'php artisan vendor:publish --tag=livestream-config'");
        }
        if(!Schema::hasTable('live_streamings')){
            //$this->table_status = false;
            throw new \Exception("Please publish the migration file by running 'php artisan vendor:publish --tag=livestream-migration' and after that run 'php artisan migrate'");
        }
        $this->livestream = new LiveStreamingController();
    }

    /* Broadcast location list */
    public function BroadcastLocation(){
        $response = $this->livestream->BroadcastLocationList();
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->BroadcastLocationList();
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Camera encoder list */
    public function CameraEncoder(){
        $response = $this->livestream->CameraEncoderList();
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->CameraEncoderList();
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Store live stream */
    public function StoreLiveStream(array $request=[]){
        $response = $this->livestream->store($request);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->store($request);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* List of all live stream */
    public function GetAllLiveStream(array $filterData = [], bool $pagination=true, int $limit=10, array $order_by=['created_at', 'desc']){
        $response = $this->livestream->listAll($filterData, $pagination, $limit, $order_by);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->listAll($filterData, $pagination, $limit, $order_by);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Get Single Live Stream  */
    public function SingleLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->GetSingleLiveStream($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->GetSingleLiveStream($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Update live stream */
    public function UpdateLiveStream(array $request = [], int $stream_id, string $wowza_id){
        $response = $this->livestream->update($request, $stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->update($request, $stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Remove Live Stream */
    public function RemoveLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->remove($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->remove($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Start live stream */
    public function StartLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->start($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->start($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Publish Live Stream */
    public function PublishLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->publish($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->publish($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Stop live stream */
    public function StopLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->stop($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->stop($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Status of live stream */
    public function StatusLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->status($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->status($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }

    /* Statistics of live stream */
    public function StatisticsLiveStream(int $stream_id, string $wowza_id){
        $response = $this->livestream->statistics($stream_id, $wowza_id);
        // if($this->config_status == true){
        //     if($this->table_status == true){
        //         $response = $this->livestream->statistics($stream_id, $wowza_id);
        //     }else{
        //         $response = $this->livestream->TableError();
        //     }
        // }else{
        //     $response = $this->livestream->ConfigError();
        // }
        return $response;
    }
}