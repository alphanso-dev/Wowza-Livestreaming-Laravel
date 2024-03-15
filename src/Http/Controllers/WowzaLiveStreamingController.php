<?php

namespace Livestream\Streaming\Http\Controllers;

use App\Http\Controllers\Controller;

class WowzaLiveStreamingController extends Controller{
    
    public $wowza_live_stream_api_endpoint;
    public $wowza_live_stream_api_header;

    public function __construct()
    {
        $this->wowza_live_stream_api_endpoint = config('livestream.livestream_endpoint');
        $this->wowza_live_stream_api_header = [
			"Content-Type:"  	. "application/json",
			"charset:"			. "utf-8",
			"Authorization: Bearer ". config('livestream.livestream_token')
		];
    }

    /*
    |--------------------------------------------------------------------------
    | Start:: Wowza Live Stream Api
    |--------------------------------------------------------------------------
    |
    */

    /* Live Stream Create API */
    public function WowzaLiveStreamApiCreate($data){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams";

        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$server_output = curl_exec($ch);
		$err = curl_error($ch);
		curl_close ($ch);
		$output = json_decode($server_output);
        return $output;
    }

    /* Live Stream Update API */
    public function WowzaLiveStreamApiUpdate($data, $wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams/$wowza_id";

        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$server_output = curl_exec($ch);
		$err = curl_error($ch);
		curl_close ($ch);
		$output = json_decode($server_output);
        return $output;
    }

    /* Live Stream Remove API */
    public function WowzaLiveStreamApiRemove($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams/$wowza_id";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "DELETE");
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

        $server_output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $output = json_decode($server_output);

        return $output;
    }

    /* Live stream Start API */
    public function WowzaLiveStreamApiStart($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams/$wowza_id/start";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "PUT");
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

        $server_output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $output = json_decode($server_output);
        return $output;
    }

    /* Live stream Stop API */
    public function WowzaLiveStreamApiStop($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams/$wowza_id/stop";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "PUT");
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

        $server_output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $output = json_decode($server_output);
        return $output;
    }

    /* Live stream status */
    public function WowzaLiveStreamApiStatus($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams/$wowza_id/state";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

        $server_output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $output = json_decode($server_output);
        return $output;
    }

    /* Live stream single get */
    public function WowzaLiveStreamApiSingle($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/live_streams/$wowza_id";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

		$server_output = curl_exec($ch);
		$err = curl_error($ch);
		curl_close ($ch);
		$output = json_decode($server_output);
		return $output;
    }

    /* Live stream ingest analytics data */
    public function WowzaLiveStreamApiIngestAnalyticsData($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/analytics/ingest/live_streams/$wowza_id";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

        $server_output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $output = json_decode($server_output);
        return $output;
    }

    /* Live stream viewers analytics data */
    public function WowzaLiveStreamApiViewersAnalyticsData($wowza_id){
        $url = $this->wowza_live_stream_api_endpoint."/analytics/viewers/live_streams/$wowza_id";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->wowza_live_stream_api_header);

        $server_output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        $output = json_decode($server_output);
        return $output;
    }

    /*
    |--------------------------------------------------------------------------
    | End:: Wowza Live Stream Api
    |--------------------------------------------------------------------------
    |
    */
}