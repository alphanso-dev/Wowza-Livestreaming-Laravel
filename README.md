# Wowza-Livestreaming-Laravel

Live streaming Using Wowza - Laravel Package

1 - install laravel library

    - composer require livestream/streaming

2 - Configure config file

    - Please publish the configuration file by running 'php artisan vendor:publish --tag=livestream-config'

3 - Configure migration file

    - Please publish the migration file by running 'php artisan vendor:publish --tag=livestream-migration'
    - Now run this command 'php artisan migrate' to add tables in your database

4 - Add livestream endpoint and access token to .env file using below parameter

    - LIVESTREAM_ENDPOINT = "https://api.video.wowza.com/api/v2.0"
    - LIVESTREAM_TOKEN = "you will get api access token from wowza website - https://auth.wowza.com/client/token-management"

5 - Starting to use library

    - add this code at start of your page 'use Livestream\Streaming\LiveStream'

    - create object of class '$livestream = new LiveStream();'

    - now you can call any functions from library which is given below

    - $livestream->BoradcastLocation(); /* used to get all inbuilt broadcast location detail */

    - $livestream->CameraEncoder(); /* used to get all inbuilt camera encoder detail */

    - $livestream->StoreLiveStream($request); /* to create live stream - here you have to pass full request of form like 'Request $request' */
        - Make sure belowe parameters are available in request
            - "user_id"           => 1, /* here is your authenticated user id */
            -"sname"              => "stream title",
            -"broadcast_location" => "broadcast location name", /* this details you will get from '$livestream->BoradcastLocation()' this function */
            -"description"        => "description",
            -"encoder"            => "camera encoder name", /* this details you will get from '$livestream->CameraEncoder()' this function */
            -"stream_price"       => "5.45", /* you can use any price */
            -"price_currency"     => "USD", /* please use this as it is */
            -"image"              => "image path",
            -"stream_date"        => "date",
            -"stream_time"        => "time"

    - $livestream->GetAllLiveStream($filterData=[], $pagination=true, $limit=10, $order_by=['created_at', 'desc']); /* use this to get live stream details */
        - here all function parameters all optional, if you have no need then don't pass it
        - $filterdata = [
            'search_text' => 'stream title here',
            'user_id' => 'user id which you pass',
            'stream_status' => 'status of stream' /* here you have to pass 'started' or 'stopped' */
        ];
        - $pagination /* true or fale and default will be true */
        - $limit /* default will be 10 */
        - $order_bt = ['created_at', 'desc'] /* you can pass here 'created_at or updated_at'  'asc or desc' */

    - $livestream->SingleLiveStream($stream_id, $wowza_id); /* you have to pass both parameter to get single live stream - stream_id and wowza_id should be get from list api */

    - $livestream->UpdateLiveStream($input, $stream_id, $wowza_id); /* you have to pass all parameter and you can get reference from above functions for parameter value - stream_id and wowza_id should be get from list api */
        - Make sure belowe parameters are available in request
            - "user_id"           => 1, /* here is your authenticated user id */
            -"sname"              => "stream title",
            -"description"        => "description",
            -"encoder"            => "camera encoder name", /* this details you will get from '$livestream->CameraEncoder()' this function */
            -"stream_price"       => "5.45", /* you can use any price */
            -"price_currency"     => "USD", /* please use this as it is */
            -"image"              => "image path",
            -"stream_date"        => "date",
            -"stream_time"        => "time"

    - $livestream->RemoveLiveStream($stream_id, $wowza_id); /* To remove live stream permanently - stream_id and wowza_id should be get from list api */

    - $livestream->StartLiveStream($stream_id, $wowza_id); /* You can start live stream with this function - stream_id and wowza_id should be get from list api */

    - $livestream->PublishLiveStream($stream_id, $wowza_id); /* after start live stream you have to call this function to publish and check live stream started or not - stream_id and wowza_id should be get from list api */

    - $livestream->StopLiveStream($stream_id, $wowza_id); /* To stop started live stream - stream_id and wowza_id should be get from list api */

    - $livestream->StatusLiveStream($stream_id, $wowza_id); /* To check the status of live stream like 'started' or 'stopped' - stream_id and wowza_id should be get from list api */

    - $livestream->StatisticsLiveStream($stream_id, $wowza_id); /* To check the statistics of live stream which will work only for strated live stream - stream_id and wowza_id should be get from list api */