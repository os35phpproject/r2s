<html>
<head>
	<style>
            table, th {
                border-style: solid;
                border-width: 1px;
                clear: left;
            }
            td{
                width: 200px;
            }
            select{
                width: 150px;
                height: 30px;
                font-size: 15px;
            }
            button{
                font-size: 15px;
            }
            .ordersDiv{
                margin-left: 30px;
            }
            .orderDivDet{
                margin-left: 30px;
                display: block;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
            }

            img{
                width: 100px;
                height: 100px;
            }
            .details{
                float: left;
                margin-left: 5px;
            }
            #container{
                width: 1100px;
                margin-left: 30px;
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
             #container{
                width: 900px;
                margin-left: 100px;
            }
            img.name{
                width:  100px;
                height: 100px;
                border-style: solid;
                border-width: 1px;
            
            }
            

	</style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
<div id="nav">
<ul class="nav nav-tabs">
  <li role="presentation"><a href="admin.php">Home</a></li>
  <li role="presentation"><a href="showproduct.php">Products</a></li>
  <li role="presentation"><a href="allUsers.php">Users</a></li>
  <li role="presentation"><a href="manualOrder.php">Manual Orders</a></li>
  <li role="presentation" class="active"><a href="checks.php">Checks</a></li>
</ul>
<h1 style="margin-left:100px;"> Checks </h1>
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
    $today = date('Y-m-d');
    ?>
<br/>
    <div id="container">

    <label> Date from: </label><input id="dateFrom" type="date" value="<?php echo $dateFrom = "2014-03-07"; ?>" oninput="changeDate()" />&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp
    <label> Date to: </label><input id="dateTo" type="date" value="<?php echo $dateTo = $today; ?>" oninput="changeDate()" />
    <br><br><br>
    <select onchange="listUsers(this.value)" id="select">
          <option> all users </option>
        <?php
             require_once('ORM.php');
             $obj = ORM::getInstance();
             $obj->setTable('users');
             $result = $obj->select(array("usname"));
             $num_results = mysqli_num_rows($result);
             for ($i=0; $i <$num_results; $i++) {
             $row = mysqli_fetch_assoc($result);
        ?>
          <option> <?php echo $row["usname"]; ?> </option>
        <?php  
           }
        ?> 
    </select> 
    <br><br>
    <table class="table table-striped" style="border-width:1px;border-style:solid;margin-bottom:0px;">
        <tr>
            <td> Name </td>
            <td> Total amount </td> 
        </tr>
    </table >
    <div id="usersTable">
        <?php
        $dateFrom .= ' 00:00:00';
        $dateTo .= ' 23:59:59';
        $obj->setTable('users');
        $result = $obj->select(array("usid","usname")); 
        $obj->setTable('orders');
        $num_results = mysqli_num_rows($result);
        $names = array();
        for ($i=0; $i <$num_results; $i++) {
            $row = mysqli_fetch_assoc($result);
            $names[$i] = $row["usname"];
            $cost = $obj->select_extra(array("sum(orcost*ornumber)"), array("orusid"=>"=".$row["usid"],"ordate"=>" BETWEEN '$dateFrom' AND '$dateTo'", "status" => "= 'Done'"));
            $num_costs = mysqli_num_rows($cost);
            for ($j=0; $j <$num_costs; $j++) {
                $row_cost = mysqli_fetch_assoc($cost);
                ?>
                <table style="border-width:1px;border-style:solid;margin-bottom:0px;" class="table table-striped">
                <tr>
                <td> <button type="button" id="<?php echo $row["usid"]; ?>" class="users" onclick="checkOrders(this.id)">+</button> <?php echo $row["usname"]; ?></td>
                <td><?php echo $row_cost["sum(orcost*ornumber)"]; ?></td>
                </tr>
                </table>
                <div id="<?php echo "order_".$row["usid"]; ?>" class="ordersDiv"></div>
                <?php
            }
        }
        ?>
    </div>
    </div>

<script type="text/javascript">

   function listUsers(query){ 
        var from = document.getElementById("dateFrom").value;
        var to = document.getElementById("dateTo").value;       
        if(window.XMLHttpRequest){
            ajaxRequest = new XMLHttpRequest();
        }else{
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        }
        ajaxRequest.open("GET","selectUsers.php?q="+query+"&dateFrom="+from+"&dateTo="+to,true);
        ajaxRequest.send();
        ajaxRequest.onreadystatechange=function(){
            if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
                //alert(ajaxRequest.responseText);
                var out = ajaxRequest.responseText.split("|");
                var names = out[0].split(",");
                var costs = out[1].split(",");
                var id = out[2].split(",");
                document.getElementById("usersTable").remove();
                if(document.getElementById("orders")){
                    document.getElementById("orders").remove();
                }
                var newDiv = document.createElement("div");
                newDiv.setAttribute("id","usersTable");
                for(var j = 0; j < names.length-1; j++){
                    var ordersDiv= document.createElement("div");
                    ordersDiv.setAttribute("id","order_"+id[j]);
                    ordersDiv.setAttribute("class", "ordersDiv");
                    var newTable = document.createElement("table");
                    newTable.setAttribute("class","table table-striped");
                    newTable.setAttribute("style","border-width:1px;border-style:solid;margin-bottom:0px;");
                    var btn = document.createElement("BUTTON");
                    var text = document.createTextNode("+"); 
                    btn.setAttribute("id",id[j]);
                    btn.setAttribute("class","users");
                    btn.addEventListener('click', function(){
                         checkOrders(this.id);
                    });
                    btn.appendChild(text); 
                    var tbody = document.createElement("tbody");     
                    var newRow = document.createElement("tr");
                    var newCol = document.createElement("td");
                    var index = document.createTextNode(names[j]);
                    newCol.appendChild(btn);
                    newCol.appendChild(index);
                    newCol.appendChild(index);
                    newRow.appendChild(newCol);
                    var newCol = document.createElement("td");
                    var index = document.createTextNode(costs[j]);
                    newCol.appendChild(index);
                    newRow.appendChild(newCol);
                    tbody.appendChild(newRow);
                    newTable.appendChild(tbody);
                    newDiv.appendChild(newTable);
                    newDiv.appendChild(ordersDiv);
                }
                document.getElementById("container").appendChild(newDiv);
            }
        }
    }
    function checkOrders(query){  
        if(document.getElementById(query).childNodes[0].nodeValue == "-"){
            document.getElementById(query).childNodes[0].nodeValue = "+";
            document.getElementById("order_"+query).innerHTML="";                  
        }else{
            document.getElementById(query).childNodes[0].nodeValue = "-"
            listOrders(query);
        }
    }
    function listOrders(query){       
            var from = document.getElementById("dateFrom").value;
            var to = document.getElementById("dateTo").value;
            if(window.XMLHttpRequest){
                ajaxRequest = new XMLHttpRequest();
            }else{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            ajaxRequest.open("GET","selectOrders.php?id="+query+"&dateFrom="+from+"&dateTo="+to,true);
            ajaxRequest.send();
            ajaxRequest.onreadystatechange=function(){
                if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
                    //alert(ajaxRequest.responseText);
                    document.getElementById("order_"+query).innerHTML=""; 
                    var out = ajaxRequest.responseText.split("|");
                    var dates = out[0].split(",");
                    var amounts = out[1].split(",");
                    var usid = out[2].split(",");
                    var newTable = document.createElement("table");
                    newTable.setAttribute("class","table table-striped");
                    newTable.setAttribute("style","border-width:1px;border-style:solid;margin-bottom:0px;");
                    var tbody = document.createElement("tbody");
                    newTable.setAttribute("id","orders");
                    var newRow = document.createElement("tr");
                    var newCol = document.createElement("td");
                    var index = document.createTextNode("Order Date");
                    newCol.appendChild(index);
                    newRow.appendChild(newCol);
                    var newCol = document.createElement("td");
                    var index = document.createTextNode("Amount");
                    newCol.appendChild(index);
                    newRow.appendChild(newCol);
                    tbody.appendChild(newRow);
                    newTable.appendChild(tbody);
                    document.getElementById("order_"+query).appendChild(newTable);
                    for(var j = 0; j < dates.length-1; j++){
                        var newTable = document.createElement("table");
                        newTable.setAttribute("class","table table-striped");
                        newTable.setAttribute("style","border-width:1px;border-style:solid;margin-bottom:0px;");
                        var btn = document.createElement("BUTTON");
                        var text = document.createTextNode("+"); 
                        btn.setAttribute("id",dates[j]+","+usid);
                        btn.setAttribute("class","orders");
                        btn.appendChild(text); 
                        btn.addEventListener('click', function(){
                             checkOrderDetails(this.id);
                        }); 
                        var tbody = document.createElement("tbody");    
                        var newRow = document.createElement("tr");
                        var newCol = document.createElement("td");
                        var index = document.createTextNode(dates[j]);
                        newCol.appendChild(btn);
                        newCol.appendChild(index);
                        newCol.appendChild(index);
                        newRow.appendChild(newCol);
                        var newCol = document.createElement("td");
                        var index = document.createTextNode(amounts[j]);
                        newCol.appendChild(index);
                        newRow.appendChild(newCol);
                        tbody.appendChild(newRow);
                        newTable.appendChild(tbody);
                        var orderDiv= document.createElement("div");
                        orderDiv.setAttribute("id","order_"+dates[j]+","+usid);
                        orderDiv.setAttribute("class", "orderDivDet");
                        document.getElementById("order_"+query).appendChild(newTable);
                        document.getElementById("order_"+query).appendChild(orderDiv);
                    }         
                }
            }
    }
    function checkOrderDetails(query){  
        if(document.getElementById(query).childNodes[0].nodeValue == "-"){
            document.getElementById(query).childNodes[0].nodeValue = "+";
            document.getElementById("order_"+query).innerHTML=""; 
        }else{
            document.getElementById(query).childNodes[0].nodeValue = "-"
            orderDetails(query);
        }
    }
    function orderDetails(query){
            var from = document.getElementById("dateFrom").value;
            var to = document.getElementById("dateTo").value;
            var buttons = document.getElementsByClassName("users")
            if(window.XMLHttpRequest){
                ajaxRequest = new XMLHttpRequest();
            }else{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            var data = query.split(",");
            ajaxRequest.open("GET","orderDetails.php?date="+data[0]+"&usid="+data[1],true);
            ajaxRequest.send();
            ajaxRequest.onreadystatechange=function(){
                if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
                    // alert(ajaxRequest.responseText);
                    var out = ajaxRequest.responseText.split("|");
                    var name = out[0].split(",");
                    var image = out[1].split(",");
                    var num = out[2].split(",");
                    var cost = out[3].split(",");
                    document.getElementById("order_"+query).innerHTML="";
                    for(var j = 0; j < name.length-1; j++){
                        var newDiv = document.createElement("div");
                        newDiv.setAttribute("class","details");
                        var img = document.createElement("img");
                        img.setAttribute("src","./"+image[j]);
                        newDiv.appendChild(img);
                        var br = document.createElement("br")
                        newDiv.appendChild(br);
                        var index = document.createTextNode(name[j]); 
                        newDiv.appendChild(index);
                        var br = document.createElement("br")
                        newDiv.appendChild(br);
                        var index = document.createTextNode(num[j]); 
                        newDiv.appendChild(index);
                        var br = document.createElement("br")
                        newDiv.appendChild(br);
                        var index = document.createTextNode(cost[j]+"LE"); 
                        newDiv.appendChild(index);
                        document.getElementById("order_"+query).appendChild(newDiv);
                    }  
                }
            }
    }
     function changeDate(){
        var user = document.getElementById("select").value;
        listUsers(user);
        // var orders = [];
        // var details= [];
        // if(document.getElementById("orders")){
        //     var buttons = document.getElementsByClassName("users")
        //     var j = 0;
        //     for(var i = 0; i < buttons.length; i++ ){
        //         if(buttons[i].childNodes[0].nodeValue == "-"){
        //             orders[j] = buttons[i].id;
        //             j++;
        //         }
        //     }
           
        //     for(var i = 0; i < j; i++ ){
        //         checkOrders(orders[i])
                
        //     }
        // }

     }

</script>
</body>
</html>