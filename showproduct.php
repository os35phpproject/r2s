<html>
<head>
<title> ADD PRODUCT </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<style>
            table, th, td {
                border-style: solid;
                border-width: 1px;
            }
            td{
                width: 150px;
                padding-left: 15px;
            }
            img{
                width: 200px;
                height: 100px;
            }
            h1{
                margin-left: 70px;
            }
              #container{
                width: 1000px;
                margin-left: 100px;
            }
            #nav{
                float: left;
                width: 800px;
                margin-left: 30px;
                margin-top: 10px;

            }
            .name{
                    float: right;
                    margin-right: 25px; 
                    margin-top: 20px;
            }
            img.name{
                width:  100px;
                height: 100px;
                border-style: solid;
                border-width: 1px;
            
            }
            #add{
                float: right;
                font-size: 20px;
            }
            h1{
                margin-left: 70px;
            }
	</style>
</head>
 <body>
 <div id="nav">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="admin.php">Home</a></li>
  <li role="presentation" class="active"><a href="showproduct.php">Products</a></li>
  <li role="presentation"><a href="allUsers.php">Users</a></li>
  <li role="presentation"><a href="manualOrder.php">Manual Orders</a></li>
  <li role="presentation"><a href="checks.php">Checks</a></li>
</ul>
<h1 style="margin-left:100px;"> All Products </h1>

</div>
<?php
    session_start();
//var_dump("$_SESSION") ;
if(isset($_SESSION['logged'])){
    if($_SESSION['logged'] == 'true'){
        if($_SESSION['usrid'] == 1){

        }else{
            header("Location:http://localhost/prologin.php");
        }
        
    }else{
        header("Location:http://localhost/prologin.php");
    }
}else{
    header("Location:http://localhost/prologin.php");
}

require_once('ORM.php');
$obj = ORM::getInstance();
if(isset($_SESSION['usrid'])){
    $obj->setTable("users");
$condi=["usid"=>$_SESSION['usrid'],];
$usrdata=$obj->select("all",$condi);

for($j=0;$j<mysqli_num_rows($usrdata);$j++){
    $therecord=mysqli_fetch_assoc($usrdata);
    $usrnm= $therecord['usname'];
    $usrext= $therecord['usext'];
    $usrimg=$therecord['usimage'];
 }
 
 echo "<div style='overflow:hidden'>";
 echo "<div id='namediv' style='margin-right:30px;' align='right'>";
echo "<img src=/".$usrimg." width=70px height=70px class='name'>";
echo "</img>"."<br/>";
echo  "<div class='name'>Hello, ".$usrnm."<br><a href=http://localhost/logout.php>"."LogOut"."</a></div>";
echo "</div>";
echo "</div>";

}
?><br/>
<div id="container">
<div id="add"><a href="productramy.php" id="addUser"> Add Product </a> </div><br/>
<table border="1" style="width:100%" class="table table-striped">
  	<tr>
 
    		<td><b>product</td>		
    		<td><b>price</td>
    		<td><b>image</td>
    		<td><b>action</td>
  	</tr>	
<?php
require_once("ORM.php");
	$obj = ORM::getInstance(); 
    $obj->setTable('product');
    $result=$obj->select();
    $num_results = mysqli_num_rows($result);
	for ($i=0; $i <$num_results; $i++) {
		$row = mysqli_fetch_assoc($result);
	?>
		<tr>
		<td><?php echo $row["prname"]; ?></td>
		<td><?php echo $row["prprice"]; ?></td>
		<td><img src="/<?php echo $row["primage"]; ?>" width="80px" height="80px" ></td>


		
		<td><a href="editproduct.php?id=<?php echo $row["id"];?>">Edit</a> &nbsp &nbsp 
		<a href="deleteproduct.php?id=<?php echo $row["id"]; ?>">Delete</a> &nbsp &nbsp
		<a href="changeavalabilityproduct.php?id=<?php echo $row["id"]; ?>"><?php if($row["statues"]==1){echo "Available";}else{echo "Unavailable";} ?></a>
		</tr>
		<?php
		}
		?>

</div>
 
	</body>
	</html>
