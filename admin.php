<html>
<head>
	<style>
            .details {
                border-style: solid;
                border-width: 1px;
                padding-top: 10px;
            }
             #maindiv{
                width: 1200px;
                margin-left: 50px;
 
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
            
	</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>	
<body>

<audio id="bill" >
			<source src="bill.mp3" type="audio/mpeg" >
		
</audio>

<audio id="cancelord" >
			<source src="cancel.mp3" type="audio/mpeg" >
	
</audio>


<div id="nav">
<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="admin.php">Home</a></li>
  <li role="presentation"><a href="showproduct.php">Products</a></li>
  <li role="presentation" ><a href="allUsers.php">Users</a></li>
  <li role="presentation"><a href="manualOrder.php">Manual Orders</a></li>
  <li role="presentation"><a href="checks.php">Checks</a></li>
</ul>
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
?>
<div id="maindiv">
<?php 
$num=0;
require_once("ORM.php");
$obj=ORM::getInstance();
$obj->setTable("orders");
$indexes=[];
$res=$obj->select("all",["status"=>"processing",]);
$res2=$obj->select("all",["status"=>"out for delivery",]);
for($m=0;$m<mysqli_num_rows($res);$m++){
$result[]=mysqli_fetch_assoc($res);
}
for($m=0;$m<mysqli_num_rows($res2);$m++){
$result[]=mysqli_fetch_assoc($res2);
}
if(isset($result)){
$compres=$result;	



for($g=0;$g<count($result);$g++){
	if(!in_array($g,$indexes)){
	for($j=0;$j<count($compres);$j++){
		if($result[$g]['orusid']==$compres[$j]['orusid'] && $result[$g]['ordate']==$compres[$j]['ordate']){
			$theprids[]=$compres[$j]['orprid'];
			$theprnum[]=$compres[$j]['ornumber'];
			$theprcosts[]=$compres[$j]['orcost'];
			$indexes[]=$j;
		}
	}
	$thewholecost=0;
	for($y=0;$y<count($theprnum);$y++){
		$thewholecost+=(int)$theprnum[$y]*(int)$theprcosts[$y];
	}
		$obj->setTable("users");
		$theusname=$obj->select("all",["usid"=>$result[$g]['orusid'],]);
		for($m=0;$m<mysqli_num_rows($theusname);$m++){
					$userrow=mysqli_fetch_assoc($theusname);
					$usnames[]=$userrow['usname'];
					$usext[]=$userrow['usext'];
				}
				$prnmr1=implode("", $usnames);
				$prnmr2=implode("", $usext);
				$usnames=[];
				$usext=[];		


$num++;
?>

<div id="<?php echo "tab".$num; ?>" class=<?php echo '"'.$result[$g]['ordate'].'#'.$prnmr1.'"'; ?> >
	<table width="100%" style="border-width:1px;border-style:solid;margin-bottom:0px; margin-top:20px;" class="table table-striped">
		<tr>
			<td>Order Date</td>
    		<td>Name</td>
    		<td>Room</td>
    		<td>Ext</td>
    		<td>Notes</td>
    		<td>Action</td>
    	</tr>
    	<tr>
			<td id=<?php echo "dat".$num; ?> ><?php echo $result[$g]['ordate']; ?></td>
    		<td id=<?php echo "nm".$num; ?>><?php echo $prnmr1; ?></td>
    		<td><?php echo $result[$g]['orroom']; ?></td>
    		<td><?php echo $prnmr2; ?></td>
    		<td><?php echo $result[$g]['ornote']; ?></td>
    		<td>
    			<select id=<?php echo "sel".$num; ?> onchange=<?php echo "checkend(sel".$num.")"; ?>>
    				<option value="processing" <?php if($result[$g]['status']=="processing"){ echo "selected"; } ?> >processing</option>
    				<option value="out for delivery" <?php if($result[$g]['status']=="out for delivery"){ echo "selected"; } ?>>out for delivery</option>
    				<option value="Done" <?php if($result[$g]['status']=="Done"){ echo "selected"; } ?>>Done</option>
    			</select>
    		</td>
    	</tr>
	</table>
	<div  class="details">
		<?php
			//echo count($theprids);
			for($r=0;$r<count($theprids);$r++){
				$obj->setTable("product");
				$prnm=$obj->select("all",["id"=>$theprids[$r],]);
				for($m=0;$m<mysqli_num_rows($prnm);$m++){
					$prnmr=mysqli_fetch_assoc($prnm);
					$prnames[]=$prnmr['prname'];
					$primages[]=$prnmr['primage'];
				}
				$prnmr1=implode("", $prnames);
				$prnmr2=implode("", $primages);
				$prnames=[];
				$primages=[];
				if($r<count($theprids)-1){
		?>
			<div style="float:left;margin-right:1%;margin-left:1%;">
			<?php }else if($r==count($theprids)-1){ ?>
				<div style="margin-right:1%;margin-left:1%;">
			<?php } ?>
				<img width="50px" height="50px" src=<?php echo $prnmr2; ?>></img>
				<br/><?php echo $prnmr1; ?>
				<br/><?php echo $theprnum[$r]; ?><br/><?php echo $theprcosts[$r]; ?>L.E
			</div>
			
		<?php
			}
			$theprids=[];
			$theprnum=[];
			$theprcosts=[];
		?>
		<div align="right" style="margin-right:1%;margin-left:1%;">Total: EGP <?php echo $thewholecost; ?></div>
	</div>

</div>



<?php
$thewholecost=0;
}



}

}







?>
<div id="tostart" hidden><?php echo $num; ?> </div>	
</div>	



<script type="text/javascript">
var conn = new WebSocket('ws://localhost:8080');
var therowid=Number(document.getElementById("tostart").innerHTML);	
console.log(therowid);
var theproimages=0;

function creatordsimg(ndiv,itmname,itmnum,itmcost){
console.log(ndiv);
console.log(itmname);
console.log(itmnum);
console.log(itmcost);

if(window.XMLHttpRequest){

ajaximgs = new XMLHttpRequest();

}else{

	ajaximgs = new ActiveXObject("Microsoft.XMLHTTP");
}

ajaximgs.open("GET","getimages.php?prods="+itmname,true);

ajaximgs.send();



ajaximgs.onreadystatechange=function(){


	if(ajaximgs.readyState==4 && ajaximgs.status==200){
		//console.log("hamada");
		theproimages=ajaximgs.responseText;
		console.log(theproimages);
		theproimages=theproimages.split(",");
		console.log(theproimages);






// thtable=document.createElement("table");
// thtable.setAttribute("width","100%");
// thtable.setAttribute("style","border-width:1%;border-style:solid;");
// throwele=document.createElement("tr");
// thtable.appendChild(throwele);
itmname=itmname.split(" ");
itmnum=itmnum.split(" ");
itmcost=itmcost.split(" ");
// for(i=0;i<itmname.length;i++){
// 	thcolele=document.createElement("td");
// 	thcolele.innerHTML=itmname[i]+"<br/>"+itmnum[i]+"<br/>"+itmcost[i]+"<br/>";
// 	throwele.appendChild(thcolele);
// }
// ndiv.appendChild(thtable);
// }
thdivcon=document.createElement("div");
// thdivcon.setAttribute("style","width:100%;margin-right:1%;margin-left:1%;");
thdivcon.setAttribute("class","details");
var total=0;
for(i=0;i<itmname.length-2;i++){
	thdivaya=document.createElement("div");
	thdivaya.setAttribute("style","float:left;margin-right:1%;margin-left:1%;");
	theimg=document.createElement("img");
	theimg.setAttribute("src","http://localhost/"+theproimages[i]);
	theimg.setAttribute("height","50px");
	theimg.setAttribute("width","50px");
	thdivaya.appendChild(theimg);
	thdivaya.innerHTML+="<br/>"+itmname[i]+"<br/>"+itmnum[i]+"<br/>"+itmcost[i]+"L.E"+"<br/>";
	thdivcon.appendChild(thdivaya);
	total+=Number(itmnum[i])*Number(itmcost[i]);
}
thdivaya2=document.createElement("div");
thdivaya2.setAttribute("style","margin-right:1%;margin-left:1%;");
theimg2=document.createElement("img");
theimg2.setAttribute("src","http://localhost/"+theproimages[itmname.length-2]);
theimg2.setAttribute("height","50px");
theimg2.setAttribute("width","50px");
thdivaya2.appendChild(theimg2);
thdivaya2.innerHTML+="<br/>"+itmname[itmname.length-2]+"<br/>"+itmnum[itmname.length-2]+"<br/>"+itmcost[itmname.length-2]+"L.E"+"<br/>";
thdivcon.appendChild(thdivaya2);
total+=Number(itmnum[itmname.length-2])*Number(itmcost[itmname.length-2]);
// thedummydiv=document.createElement("div");
// thedummydiv.innerHTML="<br/>";
// ndiv.appendChild(thedummydiv);
thtotaldiv=document.createElement("div");
thtotaldiv.setAttribute("align","right");
thtotaldiv.setAttribute("style","margin-right:1%;margin-left:1%;");
thtotaldiv.innerHTML="Total: EGP "+total;
thdivcon.appendChild(thtotaldiv);
ndiv.appendChild(thdivcon);
}	}
}



function checkend(selid){
	//conn.send();
	console.log(selid.id);
	theck=selid.id.split('l')[1];
	console.log(theck);
	thedtele=document.getElementById("dat"+theck).innerHTML;
	console.log(thedtele);
	theumele=document.getElementById("nm"+theck).innerHTML;
	console.log(theumele);
	if(window.XMLHttpRequest){

ajaxRequest = new XMLHttpRequest();

}else{

	ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
}

ajaxRequest.open("GET","changeorderstatus.php?ordate="+thedtele+"&clinm="+theumele+"&status="+selid.value,true);

console.log("changeorderstatus.php?ordate="+thedtele+"&clinm="+theumele+"&status="+selid.value);
conn.send("changestatus$"+thedtele+"#"+theumele+"&"+selid.value);
console.log("changestatus$"+thedtele+"#"+theumele+"&"+selid.value);


ajaxRequest.send();



ajaxRequest.onreadystatechange=function(){
	//thefinele=document.getElementById("tab"+therowid);
	console.log(selid.parentNode.parentNode.parentNode.parentNode);

if(selid.value=="Done"){
		document.getElementById("tab"+theck).remove();
		
		//selid.parentNode.parentNode.parentNode.parentNode.remove();


	}

}
	
	

}



conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {

	if(e.data.split("$")[0]=="delete"){
		console.log(e.data);
		document.getElementsByClassName(e.data.split("$")[1])[0].remove();
		document.getElementById("cancelord").play();

	}else{


    console.log(e.data);
    dt=e.data;
    
    console.log(dt);
    dt=dt.split(",");

 
    onrddiv=document.createElement("div");
    thetab=document.createElement("table");
     thetab.setAttribute("class","table table-striped");
     thetab.setAttribute("style","border-width:1px;border-style:solid;margin-bottom:0px; margin-top:20px;");
    therowid++;
    onrddiv.setAttribute("id","tab"+therowid);

    onrddiv.setAttribute("style","display:block");
    thetab.setAttribute("width","100%");
    // thetab.setAttribute("style","border-width:1%;border-style:solid;");
    

    var tbody = document.createElement("tbody");
    therow=document.createElement("tr");
    tbody.appendChild(therow);
    thetab.appendChild(tbody);

    c1=document.createElement("td");
    c1.innerHTML="Order Date";
    therow.appendChild(c1);

    c2=document.createElement("td");
    c2.innerHTML="Name";
    therow.appendChild(c2);

    c3=document.createElement("td");
    c3.innerHTML="Room";
    therow.appendChild(c3);

    c4=document.createElement("td");
    c4.innerHTML="Ext";
    therow.appendChild(c4);

    c5=document.createElement("td");
    c5.innerHTML="Notes";
    therow.appendChild(c5);

    c6=document.createElement("td");
    c6.innerHTML="Action";
    therow.appendChild(c6);

    therow2=document.createElement("tr");
    tbody.appendChild(therow2);

    c12=document.createElement("td");
    c12.setAttribute("id","dat"+therowid);
    var theodate= new Date(Number(dt[6].split(":")[1]));
    c12.innerHTML=theodate.getFullYear()+"-"+("0"+(theodate.getMonth()+1)).slice(-2)+"-"+("0"+theodate.getDate()).slice(-2)+' '+("0"+theodate.getHours()).slice(-2) + ':' + ("0"+theodate.getMinutes()).slice(-2)+ ':' + ("0"+theodate.getSeconds()).slice(-2);

    therow2.appendChild(c12);
//theodate.getFullYear()+"-"+("0"+(theodate.getMonth()+1)).slice(-2)+"-"+("0"+theodate.getDate()).slice(-2)+' '+("0"+theodate.getHours()).slice(-2) + ':' + ("0"+theodate.getMinutes()).slice(-2)+ ':' + ("0"+theodate.getSeconds()).slice(-2);

    

    c22=document.createElement("td");
    c22.setAttribute("id","nm"+therowid);
    c22.innerHTML=dt[0].split(":")[1];
    onrddiv.setAttribute("class",c12.innerHTML+"#"+c22.innerHTML);
    therow2.appendChild(c22);

    c32=document.createElement("td");
    c32.innerHTML=dt[5].split(":")[1];
    therow2.appendChild(c32);

    c42=document.createElement("td");
    c42.innerHTML=dt[1].split(":")[1];
    therow2.appendChild(c42);

    c52=document.createElement("td");
    c52.innerHTML=dt[7].split(":")[1];
    therow2.appendChild(c52);

    c62=document.createElement("td");
    c622=document.createElement("select");
    
    c622.setAttribute("id","sel"+therowid);
    c622.setAttribute("onchange","checkend("+"sel"+therowid+")");
    c6221=document.createElement("option");
    c6221.setAttribute("value","processing");
    c6221.innerHTML="processing";

    c6222=document.createElement("option");
    c6222.setAttribute("value","out for delivery");
    c6222.innerHTML="out for delivery";

    c6223=document.createElement("option");
    c6223.setAttribute("value","Done");
    c6223.innerHTML="Done";

    c622.appendChild(c6221);
    c622.appendChild(c6222);
    c622.appendChild(c6223);
    c62.appendChild(c622);
    therow2.appendChild(c62);
    onrddiv.appendChild(thetab);  

    creatordsimg(onrddiv,dt[2].split(":")[1],dt[3].split(":")[1],dt[4].split(":")[1]);
    maindiv=document.getElementById("maindiv");
    maindiv.appendChild(onrddiv);
    document.getElementById("bill").play();
}
};


</script>

</body>


</html>