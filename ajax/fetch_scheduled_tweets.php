<?php
require __DIR__ . '/../includes/setup.php';
$db = DB::connect();

$column = array("scheduled_tweets.id", "scheduled_tweets.owner_id", "scheduled_tweets.tweet_content", "scheduled_tweets.tweet_media", "scheduled_tweets.time_to_post");

$query = '';
$output = '';
$query = "SELECT * FROM scheduled_tweets WHERE owner_id = :owner_id ";

if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
    $query .= ' AND tweet_content LIKE :tweet_content';
}

if (isset($_POST['order'])) {
    $query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

if ($_POST['length'] != -1) {
    $query .= "LIMIT " . $_POST['start'] . ', ' . $_POST['length'] ;
}

$stmt = $db->prepare($query);
$stmt->bindValue('owner_id', $_SESSION['user_id']);
if (isset($_POST['search']['value']) && !empty($_POST['search']['value']))
{
    $stmt->bindValue(':tweet_content', '%' . $_POST['search']['value'] . '%');
}
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$data = [];
$filtered_rows = $stmt->rowCount();

foreach ($result as $row) {
    $sub_array = [];
    $sub_array[] = $row['time_to_post'];
    $sub_array[] = $row['tweet_content'];
    $sub_array[] = $row['tweet_media'];
    $sub_array[] = '<button type="button"  id="'.$row['id'].'" class="btn btn-warning btn-xs update update_schedule_tweet">Update</button>';
    $sub_array[] =  '<a href="'.URL_ROOT.'delete_schedule.php?id='.$row['id'].'" name="delete_schedule_tweet" id="'.$row["id"].'" class="btn btn-danger btn-xs delete-btn">Delete</button>';
    $data[] = $sub_array;
}

$total_records = \MyApp\Libs\Helper::getTotalRecords('scheduled_tweets');

$output = [
    'draw'				=> intval($_POST['draw']),
    'recordsTotal'		=> $filtered_rows,
    'recordsFiltered'	=> $total_records,
    'data'				=> $data
];
echo json_encode($output);