<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "database.php";
$db = new Database();
switch ($_SERVER['REQUEST_METHOD']) {
    //jenis method
    case 'GET':
        $db->open();
        $id = $_REQUEST['id'];
        if($id == '') {
            $error = [
                'err_code' => '01',
                'error'     => true,
                'msg' => 'id is required',
                'result' => false,
                'total_data' => 0
            ];
            print json_encode($error);
            break;
        }
        $res = $db->get('select * from customer where idcustomer ="'.$id.'"');
        $result = [];
        if($res) {
            $result = [
                'err_code'      => '00',
                'error'         => false,
                'msg'           => $db->mysqli->affected_rows == 0 ? 'data empty' : 'success',
                'result'        => $res[0]
            ];

        } else {
            $result['err_code'] = '01';
            $result['error']    = true;
            $result['msg']      = 'failed get data';
            $result['result']   = $res;
        }

        $db->close();
        print json_encode($result);

        break;
    default:
        http_response_code(400); // kode bad request
        
        $result = [
            'err_code'  => '400',
            'error'     => false,
            'msg'       => 'Bad Request',
            'result'    => null
        ];

        print json_encode($result);

        break; 
}

?>