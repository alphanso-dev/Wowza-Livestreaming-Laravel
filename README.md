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

5 - 