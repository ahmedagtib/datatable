<?php


include('connection.php');

$column = array('formation', 'last_name', 'first_name', 'email', 'phone','dispo');

$query = "SELECT * FROM data ";

if(isset($_POST['search']['value']))
{
 $query .= '
 WHERE formation LIKE "%'.$_POST['search']['value'].'%" 
 OR last_name LIKE "%'.$_POST['search']['value'].'%" 
 OR first_name LIKE "%'.$_POST['search']['value'].'%" 
 OR email LIKE "%'.$_POST['search']['value'].'%" 
 OR phone LIKE "%'.$_POST['search']['value'].'%" 
 OR dispo LIKE "%'.$_POST['search']['value'].'%" 
 ';
}

if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY CustomerID DESC ';
}

$query1 = '';

if($_POST['length'] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

foreach($result as $row)
{

 $sub_array = array();
 $sub_array[] = $row['formation'];
 $sub_array[] = $row['last_name'];
 $sub_array[] = $row['first_name'];
 $sub_array[] = $row['email'];
 $sub_array[] = $row['phone'];
 $sub_array[] = $row['dispo'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM data";
 $statement = $connect->prepare($query);
 $statement->execute();
 return $statement->rowCount();
}

$output = array(
 'draw'    => intval($_POST['draw']),
 'recordsTotal'  => count_all_data($connect),
 'recordsFiltered' => $number_filter_row,
 'data'    => $data
);

echo json_encode($output);

?>
