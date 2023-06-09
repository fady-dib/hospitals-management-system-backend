<?php

include('connection.php');

$departement_id =$_POST['department_id'];

$response = [
    'rooms' => [],
    'beds' => []
];

$query_rooms_beds =$mysqli -> prepare('SELECT rooms.id, room_number, beds.id, bed_number  from rooms inner join beds on rooms.id = beds.room_id where department_id=? and occupied = 0');
    $query_rooms_beds->bind_param('i', $department_id);
    $query_rooms_beds->execute();
    $query_rooms_beds->store_result();
    $num_rows_rooms_beds = $query_rooms_beds -> num_rows();
    $query_rooms_beds -> bind_result($room_id, $room_number, $bed_id, $bed_number);

    if($num_rows_rooms_beds == 0){
        $response['response'] = "No available rooms";
    }
    else{
       
        while($query_rooms_beds -> fetch()){
            $rooms_data =[
                'room_id' => $room_id,
                'room_number' => $room_number,
            ];
            $beds_data =[
                'bed_id' => $bed_id,
                'bed_number' => $bed_number,
            ];
            array_push($response['rooms'], $rooms_data);
            array_push($response['beds'], $beds_data);
        }
        
    }

    $query_rooms_beds->free_result();
    $query_rooms_beds->close();

    echo json_encode($response);

?>