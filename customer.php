<?php
// $method = $_SERVER['REQUEST_METHOD'];
// $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

// switch ($method) {
//     case 'PUT':
//         //do_something_with_put($request);  
//         print("method: PUT");
//         break;
//     case 'POST':
//         //do_something_with_post($request);  
//         print("method: POST");
//         break;
//     case 'GET':
//         //do_something_with_get($request);  
//         print("method: GET");
//         break;
//     case 'DELETE':
//         //do_something_with_get($request);  
//         print("method: DELETE");
//         break;
//     default:
//         //handle_error($request);  
//         print("501");
//         break;
// }

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// print json_encode(getallheaders());
// http_response_code(500); //kode "too many request" 

include "database.php";
$db = new Database();
switch ($_SERVER['REQUEST_METHOD']) {
    //jenis method
    case 'PUT':
        $db->open();
        $id = $_REQUEST['id'];
        $sql = 'UPDATE `customer` SET `namacustomer` = "'.$_REQUEST["nama"].'", `telpcustomer` = "'.$_REQUEST["telp"].'" WHERE `customer`.`idcustomer` = "'.$_REQUEST["id"].'";';
        $result = [];
        $res = $db->execute($sql);
        if($res){
            $result = [
                'err_code'  => '00',
                'error'     => false,
                'result'    => $res,
                'msg'       => 'success update data'
            ];
        }
        if($id == '' | $db->mysqli->affected_rows !== 1) {
            $result['err_code'] = '01';
            $result['error']    = true;
            $result['msg']      = 'failed update data';
        }

        $db->close();
        print json_encode($result);
        break;
    case 'POST':
        $db->open();
        $sql = 'INSERT INTO `customer` (`idcustomer`, `namacustomer`, `telpcustomer`) VALUES ("'.$_REQUEST["id"].'", "'.$_REQUEST["nama"].'", "'.$_REQUEST["telp"].'");';

        if($res = $db->execute($sql)){
            $result = [
                'err_code'  => '00',
                'error'     => false,
                'msg'       => 'success add data',
                'result'    => $res
            ];
        }

        if($db->mysqli->affected_rows !== 1) {
            $result['err_code'] = '01';
            $result['error']    = true;
            $result['msg']      = 'failed add data';
        }

        $db->close();
        print json_encode($result);
        break;
    case 'GET':
        $db->open();
        $search = $_REQUEST['search'];
        $sql = 'select * from customer where namacustomer like "%'.$search.'%" OR telpcustomer like "%'.$search.'%"';
        $res = $db->get($sql);
        $result = [];
        if($res) {
            $result = [
                'err_code'      => '00',
                'error'         => false,
                'msg'           => $db->mysqli->affected_rows == 0 ? 'data empty' : 'success',
                'result'        => $res,
                'total_data'    => $db->mysqli->affected_rows
            ];

        } else {
            $result['err_code'] = '01';
            $result['error']    = true;
            $result['msg']      = 'failed get data';
        }

        $db->close();
        print json_encode($result);

        break;
    case 'DELETE':
        $db->open();
        $id = $_REQUEST['id'];
        $sql = 'delete from customer where idcustomer ="'.$id.'"';
        $result = [];
        $res = $db->execute($sql);
        if($res){
            $result = [
                'err_code'  => '00',
                'error'     => false,
                'msg'       => 'success delete data',
                'result'    => $res
            ];
        }
        if($id == '' | $db->mysqli->affected_rows !== 1) {
            $result['err_code'] = '01';
            $result['error']    = true;
            $result['msg']      = 'failed delete';
        }

        // $result['debug']    = [
        //     'affected_rows' => $db->mysqli->affected_rows,
        //     'connect_errno' => $db->mysqli->connect_errno,
        //     'connect_error' => $db->mysqli->connect_error,
        //     'errno' => $db->mysqli->errno,
        //     'error_list' => $db->mysqli->error_list,
        //     'field_count' => $db->mysqli->field_count,
        //     'info' => $db->mysqli->info,
        //     'insert_id' => $db->mysqli->insert_id,
        //     'stat' => $db->mysqli->stat,
        //     'sqlstate' => $db->mysqli->sqlstate,
        //     'thread_id' => $db->mysqli->thread_id,
        //     'warning_count' => $db->mysqli->warning_count,
        // ];

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