<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://iq.vntu.edu.ua/b04213/curriculum/api.php?view=g&group_id=5788",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);

$response_arr = json_decode($response, true);


$date = isset($_GET['date']) ? $_GET['date'] : date('d.m');
//print_r($date);
$shed = [];
$shed_for_selected_day = [];

foreach ($response_arr['sched'] as $day) {
    $shed[$day['date']] = [
        'lessons' => [],
        "date" => $day['date'],
        "dow" => $day['dow'],
        "week_num" => $day['week_num'],
        "weeks_shift" => $day['weeks_shift']
    ];

    for ($i = 1; $i <=8; $i++) {
        $shed[$day['date']]['lessons'][$i] = isset($day[$i]) ? $day[$i][''] : null;
    }
}

$shed_for_selected_day = isset($shed[$date]) ? $shed[$date] : array_shift(array_values($shed));;
?>

<!doctype html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Розклад</title>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="date_form">
                <select name="date" id="date_select">
                    <?php foreach (array_values($shed) as $day) : ?>
                        <option value="<?php echo $day['date']; ?>" <?php if ($shed_for_selected_day['date'] == $day['date']) echo 'selected'; ?>><?php echo $day['date']; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <div class="card" style="width: 38rem;">
                <div class="card-header">
                    <?php echo $shed_for_selected_day['date'] . ' (' . $shed_for_selected_day['dow'] . ')'?>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($shed_for_selected_day['lessons'] as $lesson_number => $lesson_data) : ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-1">
                                    <?php echo $lesson_number; ?>
                                </div>
                                <div class="col-9 border-left border-right">
                                    <span> <?php if (!is_null($lesson_data)) echo $lesson_data['subject'] . ' (' . $lesson_data['type'] . ')' ?> </span>
                                    <br>
                                    <small class="text-muted"><?php if (!is_null($lesson_data)) echo $lesson_data['t_name'] ?></small>
                                </div>
                                <div class="col-2 text-right"><?php if (!is_null($lesson_data)) echo $lesson_data['aud'] ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--<pre>
    <?php /*print_r($shed_for_selected_day);  */?>
</pre>-->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>



<script>
    $('#date_select').on('change', function (event) {
        $('#date_form').submit();
    });
</script>
</body>
</html>