<?php

function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //Ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
        return $ip;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //Ip from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $ip;
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

echo "user Real Ip-" .getUserIpAddr();

//localhost ::1 is equivalent to ipv4 127.0.0.1
//store Ip as an entry in the database


$servername = "localhost";
$username="root";
$password="";
$database_name="customer";
// Create connection
$conn = mysqli_connect($servername,$username,$password,$database_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

 //Escape user input for security
$name=mysqli_real_escape_string($conn,$_REQUEST['name']);
$password=mysqli_real_escape_string($conn,$_REQUEST['password']);


$sql = "INSERT INTO customertable(username,password,address)
VALUES ('$name','$password',INET6_ATON('127.0.0.1'))";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

$sql="SELECT * FROM customertable WHERE INET6_NTOA(address)='127.0.0.1'";
if($sql==TRUE){
    echo 'equal';
    //redirect to homepage
    header('location:homepage.html');
}
else {
    //redirect to mirror homepage ,act as honeypot
    header("location:mirrorhomepage.html");
    echo 'Not equal';
}