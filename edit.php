<?php
    include "connect.php";
    if(isset($_POST['editval'])){
        $user_id=$_POST['editval'];
        $sql = "select * from product WHERE id = $user_id";        $result=mysqli_query($con,$sql);
        $response=array();
        while($row=mysqli_fetch_assoc($result)){
            $response=$row;
        }
        echo json_encode($response);
    } else{
        $response['status']=200;
        $response['message']="Invalid Data";
    }


    //update
    if(isset($_POST['hiddendata'])){
        $uniqueid=$_POST['hiddendata'];
        $productname=$_POST['editproductname'];
        $quantity=$_POST['editquantity'];
        $exp=$_POST['editexp'];
        $image=$_POST['editimage'];

        $sql="update product set productname='$productname',quantity='$quantity',exp='$exp',image='$image' where id=$uniqueid";

        $result=mysqli_query($con,$sql);
    }
?>