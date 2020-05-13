<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/music.php';
include_once '../objects/like.php';
include_once '../objects/comment.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$music = new Music($db);
$like = new Like($db);
$comment = new Comment($db);
$user = new User($db);
 
$stmt = $music->read_one_music($_GET['id_music']);
$status_like = null;
if (isset($_GET['id_user']))
    $status_like = $like->get_state_like($_GET['id_music'], $_GET['id_user']);
$comments = $comment->get_comments($_GET['id_music']);

function russian_date($date){
    $date_str = new DateTime($date);
    $date = $date_str->Format('d.m.Y');
    $date_month = $date_str->Format('d.m');
    $date_year = $date_str->Format('Y');
    
    $date_time = $date_str->Format('H:i');
    
     $ndate = date('d.m.Y');
     $ndate_time = date('H:i');
     $ndate_time_m = date('i');
     $ndate_exp = explode('.', $date);
     $nmonth = array(
    1 => 'янв',
      2 => 'фев',
      3 => 'мар',
      4 => 'апр',
      5 => 'мая',
      6 => 'июн',
      7 => 'июл',
      8 => 'авг',
      9 => 'сен',
      10 => 'окт',
      11 => 'ноя',
      12 => 'дек'
     );
    
    foreach ($nmonth as $key => $value) {
      if($key == intval($ndate_exp[1])) $nmonth_name = $value;
     }
    
    if ($date == date('d.m.Y')){
    $datetime = 'сегодня в ' .$date_time;
    }
    
    else if ($date == date('d.m.Y', strtotime('-1 day'))){
    $datetime = 'вчера в ' .$date_time;
    }
    
    else if ($date != date('d.m.Y') && $date_year != date('Y')){
    $datetime = $ndate_exp[0].' '.$nmonth_name.' '.$ndate_exp[2]. ' в '.$date_time;
    }
    
    else {
    $datetime = $ndate_exp[0].' '.$nmonth_name.' в '.$date_time;
    }
    return $datetime;
}

if (isset($stmt)) {

        $arr_comments["arr_comments"] = array();
        $arr_comment = array();
        while ($row = $comments->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            
            $user_info = $user->get_info($id_user);
            extract($user_info);

            $arr_comment=array(
                "id_comment" => $id_comment,
                "text" => $text,
                "id_music" => $id_music,
                "id_user" => $id_user,
                "login" => $user_info['login'],
                "img_account" => $user_info['img_account'],
                "response" => $response,
                "date" => russian_date($date)
            );
            array_push($arr_comments["arr_comments"], $arr_comment);
        }

        extract($stmt);

        $music_item=array(
            "id_music" => $id_music,
            "name" => $name,
            "id_category" => $id_category,
            "duration" => $duration,
            "genre" => $genre,
            "path" => $path,
            "date" => $date,
            "status_like" => $status_like,
            "arr_comments" => $arr_comments
        );

    http_response_code(200);

    echo json_encode($music_item);
}

else {

    http_response_code(404);

    echo json_encode(array("message" => "Ошибка"), JSON_UNESCAPED_UNICODE);
}