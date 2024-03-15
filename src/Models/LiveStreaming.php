<?php

namespace Livestream\Streaming\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveStreaming extends Model
{
    use HasFactory;
    protected $primaryKey = 'stream_id';

    protected $fillable = [
        'user_id',
		'stream_id',
		'wowza_id',
		'stream_title',
		'description',
		'state',
		'billing_mode',
		'broadcast_location',
		'recording',
		'encoder',
		'delivery_method',
        'sdp_url',
        'application_name',
        'stream_name',
        'hls_playback_url',
        'stream_price',
        'price_currency',
        'image',
        'player_id',
        'stream_date',
        'stream_time',
        'stream_status',
        'advertisement_status'
	];

    public function InsertData($input){
        return static::create(\Arr::only($input, $this->fillable));
    }

    function UpdateData($input, $user_id, $wowza_id)
	{
		return static::where(['user_id' => $user_id, 'wowza_id' => $wowza_id])->update(\Arr::only($input, $this->fillable));
	}

    function DeleteData($user_id, $wowza_id)
	{
		return static::where(['user_id' => $user_id, 'wowza_id' => $wowza_id])->delete();
	}

    function ListData($filterData = [], $paginate = 'true', $limit = 10, $order_by = ['created_at', 'desc'])
	{
		$data = static::select('*');

		$this->FilterData($data, $filterData);

		$data->orderBy($order_by[0], $order_by[1]);

		if ($paginate == 'true'):
			$output = $data->paginate((Integer) $limit);
		else:
			$data->limit((Integer) $limit);
			$output = $data->get();
		endif;

		return $output;
	}

    function GetSingleData($user_id, $wowza_id)
	{
		return static::where(['user_id' => $user_id, 'wowza_id' => $wowza_id])->first();
	}

    public function FilterData($data, $filterData){
        $search_text = isset($filterData['search_text']) ? $filterData['search_text'] : '';
		if (isset($search_text) && $search_text != '') {
			$data->where('stream_title', 'LIKE', '%' . $search_text . '%');
		}

        $user_id = isset($filterData['user_id']) ? $filterData['user_id'] : '';
		if (isset($user_id) && $user_id != '') {
			$data->where('user_id', 'LIKE', '%' . $user_id . '%');
		}

        $stream_status = isset($filterData['stream_status']) ? $filterData['stream_status'] : '';
		if (isset($stream_status) && $stream_status != '') {
			$data->where('stream_status', 'LIKE', '%' . $stream_status . '%');
		}
    }
}
