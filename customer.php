<?php
$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
    case 'PUT':
        //do_something_with_put($request);  
        print("method: PUT");
        break;
    case 'POST':
        //do_something_with_post($request);  
        print("method: POST");
        break;
    case 'GET':
        //do_something_with_get($request);  
        print("method: GET");
        break;
    case 'DELETE':
        //do_something_with_get($request);  
        print("method: DELETE");
        break;
    default:
        //handle_error($request);  
        print("501");
        break;
}