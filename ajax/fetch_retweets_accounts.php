<?php
require __DIR__ . '/../includes/setup.php';
$db = DB::connect();

$column = [
    'retweets_users.id', 'retweets_users.tid', 'retweets_users.name', 'retweets_users.last_status_id', 'retweets_users.screen_name', 'retweets_users.profile_image_url', 'retweets_users.followers_count', 'retweets_users.friends_count', 'retweets_users.statuses_count', 'retweets_users.date_added'
];

$query = '';
$output = '';
$query = 'SELECT * FROM retweets_users WHERE owner_id = :owner_id ';

if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
    $query .= ' WHERE name LIKE :name ';
}

if (isset($_POST['order'])) {
    $query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

if ($_POST['length'] != -1) {
    $query .= "LIMIT " . $_POST['start'] . ', ' . $_POST['length'] ;
}

$stmt = $db->prepare($query);
$stmt->bindValue(':owner_id', $_SESSION['user_id']);

if (isset($_POST['search']['value']) && !empty($_POST['search']['value']))
{
    $stmt->bindValue(':name', '%' . $_POST['search']['value'] . '%');
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data = [];
$filtered_rows = $stmt->rowCount();

foreach ($result as $row) {
    $sub_array = [];
    $sub_array[] = '<img src="' . $row['profile_image_url'] . '">';
    $sub_array[] = '<a href="#">@'.$row['screen_name'].'</a>';
    $sub_array[] = $row['name'];
    $sub_array[] = $row['followers_count'];
    $sub_array[] = $row['friends_count'];
    $sub_array[] = $row['statuses_count'];
    $sub_array[] = date('Y-m-d', $row['date_added']);
//    $sub_array[] = '<button type="button"  id="'.$row['id'].'" class="btn btn-warning btn-xs update update_schedule_tweet">Update</button>';
    $sub_array[] =  '<a href="'.URL_ROOT.'delete_retweet_user.php?id='.$row['id'].'" name="delete_schedule_tweet" id="'.$row["id"].'" class="btn btn-danger btn-xs delete-btn">Delete</button>';
    $data[] = $sub_array;
}

$total_records = \MyApp\Libs\Helper::getTotalRecords('retweets_users');

$output = [
    'draw'				=> intval($_POST['draw']),
    'recordsTotal'		=> $filtered_rows,
    'recordsFiltered'	=> $total_records,
    'data'				=> $data
];
echo json_encode($output);