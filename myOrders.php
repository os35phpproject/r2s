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
                margin-left: 50px;
            }
            .details{
                float: left;
                margin-left: 5px;
            }
            .det{
                border-style: solid;
                border-width: 1px;
                margin-left: 30px;
                overflow: hidden;
            }
            img{
                width: 100px;
                height: 100px;
            }
                .name{
        float: right;
        margin-right: 15px; 
        margin-top: 10px;
    }
    #nav{
        float: left;
        width: 500px;
        margin-left: 30px;
        margin-top: 10px;

    }
    #confirm{
        width: 100px;
        height: 40px;
    }
    img.name{
                width:  100px;
                height: 100px;
                border-style: solid;
                border-width: 1px;
            
            }
 #container{
                width: 1100px;
                margin-left: 30px;
            }

    </style>
        <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
</head>
<body>
<div id="nav">
<ul class="nav nav-tabs">
  <li role="presentation"><a href=http://localhost/client_page.php>Home</a></li>
  <li role="presentation" class="active"><a href=http://localhost/myOrders.php>My Orders</a></li>
</ul>
<h1> My Orders </h1>
</div>
<?php 
session_start();
//var_dump("$_SESSION") ;
if(isset($_SESSION['logged'])){
    if($_SESSION['logged'] == 'true'){
        
    }else{
        header("Location:http://localhost/prologin.php");
    }
}else{
    header("Location:http://localhost/prologin.php");
}


require_once("ORM.php");
$obj=ORM::getInstance();

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
 echo "<div  style='margin-right:30px;' align='right'>";
 

echo "<img src=/".$usrimg." width=70px height=70px class='name'>";
echo "</img>"."<br/>";
echo "<div id='username' class='name'>".$usrnm."</div><div class='name'>Hello,</div><br/><br/><div class='name'><a href=http://localhost/logout.php>LogOut</a></div>";
echo "</div>";

echo "</div>";



echo "<div id='iddiv' hidden>";
echo $_SESSION['usrid'];
echo "</div>";


 ?>
<br>

<?php
$today = date('Y-m-d');
?>
<br/>
<div id="container">
    <label> Date from: </label><input id="dateFrom" type="date" value="<?php echo $dateFrom = "2014-03-07"; ?>" oninput="listOrders()" />&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp
    <label> Date to: </label><input id="dateTo" type="date" value="<?php echo $dateTo = $today; ?>" oninput="listOrders()" />
    <br><br><br>

    <table class="table table-striped" style="border-width:1px;border-style:solid;margin-bottom:0px;">
        <tr>
            <td> Order Date </td>
            <td> Status </td> 
            <td> Amount</td>
            <td> Action </td> 
        </tr>
    </table>
    <div id="ordersTable">
        <?php
        $val=0;
        
        $obj->setTable('orders');
        $dateFrom.=' 00:00:00';
        $dateTo.=' 23:59:59';
        $result = $obj->select_extra(array("ordate","status","sum(orcost*ornumber)","orid"),array('orusid' => " = ".$_SESSION['usrid'],"ordate"=>" BETWEEN '$dateFrom' AND '$dateTo'"),"group by ordate");
        $num_results = mysqli_num_rows($result);
        for ($i=0; $i <$num_results; $i++) {
            $row = mysqli_fetch_assoc($result);
            $val++;
                ?>
                <table id="<?php echo "rectab".$val; ?>" class="table table-striped" style="border-width:1px;border-style:solid;margin-bottom:0px;">
                <tr>
                <td> <button type="button" id="<?php echo $row["ordate"]; ?>" onclick="checkOrders(this.id)">+</button> <?php echo $row["ordate"]; ?></td>
                <td><?php echo $row["status"]; ?></td>
                <td><?php echo $row["sum(orcost*ornumber)"]; ?></td>
                <?php
                if($row["status"] == "processing"){
                    ?>
                <td><input type="button" onclick=<?php echo "cancel("."rectab".$val.")";?> value="Cancel"></input></td>    
                <?php }else{ ?>
                <td><input type="button" onclick=<?php echo "cancel("."rectab".$val.")";?> value="Cancel" hidden></input></td>
                <?php } ?>        
                </tr>
                </table>
                <div id="<?php echo "order_".$row["ordate"]; ?>" class="det"></div>
                <?php
        }
        ?>
    </div>

<script type="text/javascript">
var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    if(e.data.split("$")[0]=="changestatus"){
    console.log(e.data);
    thecliname=(e.data.split("#")[1]).split("&")[0];
    theordrate=(e.data.split("$")[1]).split("#")[0];
    thestat=e.data.split("&")[1];
    if(document.getElementById("username").innerHTML==thecliname){
        console.log("right client"+thecliname+theordrate);
        // console.log(document.getElementById(theordrate));
        // console.log(document.getElementById(theordrate).parentNode);
        // console.log(document.getElementById(theordrate).parentNode.nextSibling);
        console.log(document.getElementById(theordrate).parentNode.nextSibling.nextSibling.innerHTML);
        console.log(document.getElementById(theordrate).parentNode.nextSibling.nextSibling.nextSibling);
        console.log(document.getElementById(theordrate).parentNode.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.childNodes[0]);
        thbtn=document.getElementById(theordrate).parentNode.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.childNodes[0];
        if(thestat=="processing"){
               thbtn.removeAttribute("hidden"); 
        }else{
            
            thbtn.setAttribute("hidden","true");
        }
        document.getElementById(theordrate).parentNode.nextSibling.nextSibling.innerHTML=thestat;
    }
}

};


theidele=0;
function cancel(theeleid){
     // console.log(theeleid);
     // console.log(theeleid.nextSibling);
     // console.log(theeleid.childNodes[0]);
     // console.log(theeleid.childNodes[0].childNodes[0].childNodes[0].id);
console.log(theeleid.childNodes[1].childNodes[0].childNodes[1].childNodes[1].id);
orddate=theeleid.childNodes[1].childNodes[0].childNodes[1].childNodes[1].id;
usrid=document.getElementById("iddiv").innerHTML;
console.log(usrid);
console.log(orddate);

if(window.XMLHttpRequest){

cancelRequest = new XMLHttpRequest();

}else{

    cancelRequest = new ActiveXObject("Microsoft.XMLHTTP");
}

cancelRequest.open("GET","cancelmyorder.php?theusrid="+usrid+"&orderdate="+orddate,true);
cancelRequest.send();
cancelRequest.onreadystatechange=function(){
//console.log("hamada");

    if(cancelRequest.readyState==4 && cancelRequest.status==200){

        theeleid.remove();
        document.getElementById("order_"+orddate).remove();
        username=document.getElementById("username").innerHTML; 
        conn.send("delete$"+orddate+"#"+username);
        console.log("delete$"+orddate+"#"+username);
    }
}








}

    function checkOrders(query){  
        if(document.getElementById(query).childNodes[0].nodeValue == "-"){
            document.getElementById(query).childNodes[0].nodeValue = "+";
            document.getElementById("order_"+query).innerHTML="";                  
        }else{
            document.getElementById(query).childNodes[0].nodeValue = "-"
            orderDetails(query);
        }
    }
    function listOrders(){       
            var from = document.getElementById("dateFrom").value;
            var to = document.getElementById("dateTo").value;
            if(window.XMLHttpRequest){
                ajaxRequest = new XMLHttpRequest();
            }else{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            ajaxRequest.open("GET","selectMyOrders.php?dateFrom="+from+"&dateTo="+to,true);
            ajaxRequest.send();
            ajaxRequest.onreadystatechange=function(){
                if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
                    //alert(ajaxRequest.responseText);
                    var out = ajaxRequest.responseText.split("|");
                    var date = out[0].split(",");
                    var status = out[1].split(",");
                    var amount = out[2].split(",");
                    document.getElementById("ordersTable").innerHTML="";
                    for(var j = 0; j < date.length-1; j++){
                        var newTable = document.createElement("table");
                        newTable.setAttribute("id","rectab"+theidele);
                        newTable.setAttribute("class","table table-striped");
                        newTable.setAttribute("style","border-width:1px;border-style:solid;margin-bottom:0px;");
                        var tbody = document.createElement("tbody");
                        var btn = document.createElement("BUTTON");
                        var text = document.createTextNode("+"); 
                        btn.setAttribute("id",date[j]);
                        btn.appendChild(text); 
                        btn.addEventListener('click', function(){
                             checkOrders(this.id);
                        });     
                        var newRow = document.createElement("tr");
                        var newCol = document.createElement("td");
                        var index = document.createTextNode(date[j]);
                        newCol.appendChild(btn);
                        newCol.appendChild(index);
                        newCol.appendChild(index);
                        newRow.appendChild(newCol);
                        var newCol = document.createElement("td");
                        var index = document.createTextNode(status[j]);
                        newCol.appendChild(index);
                        newRow.appendChild(newCol);
                        tbody.appendChild(newRow);
                        newTable.appendChild(tbody);
                        var newCol = document.createElement("td");
                        var index = document.createTextNode(amount[j]);
                        newCol.appendChild(index);
                        newRow.appendChild(newCol);
                        tbody.appendChild(newRow);
                        newTable.appendChild(tbody);
                        theidele++;
                        if(status[j] == "processing"){
                            var newCol = document.createElement("td");
                            tbody.appendChild(newRow);
                            newTable.appendChild(tbody);
                            var link = document.createElement('input');
                            link.setAttribute("type","button");
                            link.setAttribute("value","Cancel");
                            link.setAttribute("onclick","cancel("+"rectab"+theidele+")");
                            newCol.appendChild(link);
                            newRow.appendChild(newCol);
                        }
                        else{
                            var newCol = document.createElement("td");
                            tbody.appendChild(newRow);
                            newTable.appendChild(tbody);
                            var link = document.createElement('input');
                            link.setAttribute("type","button");
                            link.setAttribute("value","Cancel");
                            link.setAttribute("hidden","true");
                            link.setAttribute("onclick","cancel("+"rectab"+theidele+")");
                            newCol.appendChild(link);
                            newRow.appendChild(newCol);
                        }
                        document.getElementById("ordersTable").appendChild(newTable);
                        var newDiv = document.createElement("div");
                        newDiv.setAttribute("id", "order_"+date[j]);
                        newDiv.setAttribute("class", "det");
                        document.getElementById("ordersTable").appendChild(newDiv);
                    }         
                }
            }
    }
    function orderDetails(query){
            if(window.XMLHttpRequest){
                ajaxRequest = new XMLHttpRequest();
            }else{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            ajaxRequest.open("GET","myOrderDetails.php?date="+query,true);
            ajaxRequest.send();
            ajaxRequest.onreadystatechange=function(){
                if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
                   // alert(ajaxRequest.responseText);
                    var out = ajaxRequest.responseText.split("|");
                    var name = out[0].split(",");
                    var image = out[1].split(",");
                    var num = out[2].split(",");
                    var cost = out[3].split(",");
                   // if(document.getElementById("order_"+query){
                        document.getElementById("order_"+query).innerHTML="";
                  //  }
                    for(var j = 0; j < name.length-1; j++){
                        var newDiv = document.createElement("div");
                        newDiv.setAttribute("class","details");
                        var img = document.createElement("img");
                        img.setAttribute("src","/"+image[j]);
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

</script>
</body>
</html>
