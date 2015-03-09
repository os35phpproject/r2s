<html>
<head>
	<style type="text/css">
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
            select{
            	width: 110px;
            	height: 30px;
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
  <li role="presentation" class="active"><a href="manualOrder.php">Manual Orders</a></li>
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

echo "<div id='extdiv' hidden>";
echo $usrext;
echo "</div>";
echo "<div id='iddiv' hidden>";
echo $_SESSION['usrid'];
echo "</div>";

$obj->setTable("product");
$data=["prname","primage","prprice",];
$res=$obj->select($data,["statues"=>1,]);
//var_dump($res);
//echo mysqli_num_rows($res);

 

?>


<div id="controldiv" style="width:28%;min-height:500px;border-width:1px;float:left;border-style: solid;margin:30px;padding:10px 10px 10px 10px;">
<div id="maindiv">
<table id="maintab"></table>
	
</div>
<div id="thedivnote" style="margin:4%;">
	
</div>
    
<div id="roomchoice">

	
</div>	

<div id="totalprice">

	
</div>	
</div>

<div style="width:60%;height:100%;float:left;margin:30px;">
<div id="latest_orders">
	<b>Select User: </b>
	 <select id="select">
         
        <?php
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

	<?php
echo "<br/>"."____________________________________________________________________________"."<br/><br/>";
	?>
</div>
<?php 
for($j=0;$j<mysqli_num_rows($res);$j++){
 	$therec=mysqli_fetch_assoc($res);
 	$nm= $therec['prname'];
 	$im=$therec['primage'];
 	$pp=$therec['prprice'];
 	if($j<mysqli_num_rows($res)-1){
 	echo "<div style='float:left;margin-left:15px;;'>";
 }else if($j==mysqli_num_rows($res)-1){
 	echo "<div style='display:inline-block;margin-left:15px;'>";
 }
 	echo "<img src=".'"'."/".$im.'"'." id=".'"'.$therec['prname'].'"'." onclick="."addproduct(".$nm.")"." width="."70px "."height="."70px ".">";
 	echo "</img>"."<br/>";
 	echo $nm."<br/>".$pp."LE";
 	echo "</div>";
 }

?>

	
</div>
<script>
rowind=0;
var elements = new Array();







var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onmessage = function(e) {
    console.log(e.data);
};



function submitorder(){

if(window.XMLHttpRequest){

theajax = new XMLHttpRequest();

}else{

	theajax = new ActiveXObject("Microsoft.XMLHTTP");
}

theajax.open("POST","/sendorder.php",true);
theajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

console.log(document.getElementsByClassName("prodname"));
console.log(document.getElementsByClassName("pronumber"));
console.log(document.getElementsByClassName("itemcost"));
roomnum=document.getElementById("rooms").value;
notes=document.getElementById("thenotes").value;

pronames=document.getElementsByClassName("prodname");
pronum=document.getElementsByClassName("pronumber");
procost=document.getElementsByClassName("itemcost");
ordnames="";
ordnum="";
orditemcost="";

for(var xy=0;xy<pronames.length;xy++){
	ordnames+=pronames[xy].innerHTML+" ";
	ordnum+=pronum[xy].value+" ";
	orditemcost+=procost[xy].innerHTML+" ";
}
roomnum=Number(roomnum);

// console.log(ordnames);
// console.log(ordnum);
// console.log(orditemcost);
// console.log(Number(roomnum));
// console.log(notes);
theclient=document.getElementById("select").value;

usrext=document.getElementById("extdiv").innerHTML;
console.log(theclient);
theajax.send("client="+theclient+"&ordpct="+ordnames+"&orditnum="+ordnum+"&thitemco="+orditemcost+"&romnumer="+roomnum+"&notes="+notes+"&datetime="+ new Date().getTime());
conn.send("client:"+theclient+",ext:"+usrext+",ordpct:"+ordnames+",orditnum:"+ordnum+",thitemco:"+orditemcost+",romnumer:"+roomnum+",datetime:"+new Date().getTime()+",notes:"+notes);


theajax.onreadystatechange=function(){
//console.log("hamada");

	if(theajax.readyState==4 && theajax.status==200){
		thereso=theajax.responseText;
			console.log(thereso);
			theusrid=document.getElementById("iddiv").innerHTML;
			document.getElementById("roomchoice").innerHTML="Order Sent";
			document.getElementById("totalprice").innerHTML="<a href=http://localhost/manualOrder.php>send another order</a>";
			document.getElementById("divnote").innerHTML="";
			document.getElementById("confirm").remove();
			document.getElementById("maindiv").innerHTML="";
			
	}



}}
// tr=document.getElementById("tea");
// console.log(tr);
//var response;
//elements=elements.toString();

function costchange(theid){
	//console.log(theid);
	theid=theid.id;
	theinput=document.getElementById(theid);
	// console.log(theinput.value);
	theid=theid.split('1');
	thetotal=theid[0]+'2';
	theubit=theid[0]+'3';
	thetotcost=document.getElementById(thetotal);
	theunitcost=document.getElementById(theubit);
	thetotcost.innerHTML=Number(theunitcost.innerHTML)*Number(theinput.value);
	var totalcosts=0;
	for(var t=0;t<thecosts.length;t++){
		totalcosts+=Number(thecosts[t].innerHTML);
	}
	totalpricediv.innerHTML=" _____________________________ <br/><br/>Total: "+totalcosts+" LE <br/><br/>";


	// console.log(thetotcost.innerHTML);
	// console.log(theunitcost.innerHTML);

}




function addproduct(proid){
	console.log(proid);
	console.log(elements);
proid=proid.id;
console.log(proid);
	if(window.XMLHttpRequest){

ajaxRequest = new XMLHttpRequest();

}else{

	ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
}

ajaxRequest.open("GET","/getcost.php?proid="+proid,true);





ajaxRequest.send();



ajaxRequest.onreadystatechange=function(){
//console.log("hamada");

	if(ajaxRequest.readyState==4 && ajaxRequest.status==200){
		//console.log("riham");
		 response=ajaxRequest.responseText;
		//console.log(Number(response));
		maind=document.getElementById("maindiv");
		maintab=document.getElementById("maintab");
	//elem=document.getElementById(proid);
	//console.log(proid);
	//console.log(elements);
	if(elements.indexOf(proid)!=-1){
		console.log("hamada");
	}else{
	elements.push(proid);
	//console.log(elements);
	//console.log(response);
	ord=document.createElement("tr");
	ord.setAttribute("style","padding:auto;");
	rowind++;
	ord.setAttribute("id","row"+rowind);
	maind.appendChild(ord);

	labcol=document.createElement("td");
	lab=document.createElement("label");
	lab.setAttribute("style","padding:auto;");
	lab.setAttribute("class","prodname");
	lab.innerHTML=proid;
	labcol.appendChild(lab)
	ord.appendChild(labcol);
	
	quancol=document.createElement("td");
	quan=document.createElement("input");
	quan.setAttribute("style","padding:auto;");
	quan.setAttribute("type","number");
	quan.setAttribute("class","pronumber");
	thenumis=proid+'1';
	quan.setAttribute("id",thenumis);
	quan.setAttribute("onchange","costchange("+thenumis+")");
	quan.setAttribute("value","1");
	quan.setAttribute("min","1");
	quan.setAttribute("max","100");
	quan.setAttribute("step","1");
	quancol.appendChild(quan)
	ord.appendChild(quancol);
	
	timecol=document.createElement("td");
	times=document.createElement("label");
	times.innerHTML="x";
	times.setAttribute("style","padding:auto;");
	timecol.appendChild(times)
	ord.appendChild(timecol);
	
	egpcol=document.createElement("td");
	egp=document.createElement("label");
	egp.innerHTML="EGP";
	egp.setAttribute("style","padding:auto;");
	egpcol.appendChild(egp)
	ord.appendChild(egpcol);
	//console.log(response+50);
	response=Number(response);
	thecos=proid+'3';
	cstcol=document.createElement("td");
	cst=document.createElement("label");
	cst.setAttribute("id",thecos);
	cst.setAttribute("class","itemcost");
	cst.setAttribute("style","padding:auto;");
	cst.innerHTML=response;
	cstcol.appendChild(cst)
	ord.appendChild(cstcol);


	equal=document.createElement("td");
	equlab=document.createElement("label");
	equlab.innerHTML=" = ";
	equal.appendChild(equlab)
	ord.appendChild(equal);

	thetot=proid+'2';
	totcstcol=document.createElement("td");
	totcst=document.createElement("label");
	totcst.setAttribute("id",thetot);
	totcst.setAttribute("class","rowcost");
	totcst.innerHTML=response;
	totcstcol.appendChild(totcst)
	ord.appendChild(totcstcol);

	cancel=document.createElement("td");
	canbtn=document.createElement("input");
	canbtn.setAttribute("type","button");
	canbtn.setAttribute("id","can"+rowind);
	canbtn.setAttribute("value","x");
	canbtn.setAttribute("onclick","canpro("+"can"+rowind+")");
	cancel.appendChild(canbtn)
	ord.appendChild(cancel);


	totalpricediv=document.getElementById("totalprice");
	thecosts=document.getElementsByClassName("rowcost");
	var totalcosts=0;
	for(var t=0;t<thecosts.length;t++){
		totalcosts+=Number(thecosts[t].innerHTML);
	}
	totalpricediv.innerHTML=" _____________________________ <br/><br/>Total: "+totalcosts+" LE <br/><br/>";


	if(elements.length>=1){
		submit=document.getElementById("confirm");
		note=document.getElementById("divnote");
		
		


		if(submit){
			submit.remove();
		}
		if(note){
			note.remove();
		}
		
		submit=document.createElement("input");
		submit.setAttribute("type","button");
		submit.setAttribute("value","confirm");
		submit.setAttribute("id","confirm");
		submit.setAttribute("onclick","submitorder()");
		document.getElementById("controldiv").appendChild(submit);
		thedivnote=document.getElementById("thedivnote");
		divnote=document.createElement("div");
		divnote.setAttribute("id","divnote");
		divnote.innerHTML="Note <br/>";
		note=document.createElement("textarea");
		
		note.setAttribute("style","resize:none");
		note.setAttribute("rows",'5');
		note.setAttribute("cols",'35');
		note.setAttribute("id",'thenotes');
		divnote.appendChild(note);
		thedivnote.appendChild(divnote);

	}
	if(elements.length>=1){

		combo=document.getElementById("rooms");


		if(window.XMLHttpRequest){

			ajaxquery = new XMLHttpRequest();

			}else{

			ajaxquery = new ActiveXObject("Microsoft.XMLHTTP");
			}

			ajaxquery.open("GET","/getrooms.php?romid=rooms",true);

			ajaxquery.send();


			if(combo){
			combo.remove();

		}
		romchoice=document.getElementById("roomchoice");
		romchoice.innerHTML="Room Number: ";
		combo=document.createElement("select");
		combo.setAttribute("id","rooms");
		
			ajaxquery.onreadystatechange=function(){
				if(ajaxquery.readyState==4 && ajaxquery.status==200){
					//console.log("rakoty");
					 therooms=ajaxquery.responseText;
					 therooms=therooms.split(","); 
					 for(var i=0;i<therooms.length;i++){
					 	romsel=document.createElement("option");
					 	romsel.setAttribute("value",therooms[i]);
					 	romsel.innerHTML=therooms[i];
					 	combo.appendChild(romsel);
					 }
					 romchoice.appendChild(combo);

					}

			}





		
	}

}
	}
}





	
}	

function canpro(therowid){
console.log(therowid);
console.log(elements);
therowid=therowid.id;
therowid=Number(therowid.split("n")[1]);
productname=document.getElementById("row"+therowid).childNodes[0].childNodes[0].innerHTML;
console.log(productname);
 var theindex = elements.indexOf(productname);
 console.log(theindex);
 var temp=elements[elements.length-1];
 elements[elements.length-1]=elements[theindex];
 elements[theindex]=temp;
 theindex = elements.indexOf(productname);
 console.log(theindex);
 elements.pop();
// if(theindex!=-1){
// 	elements=elements.splice(theindex, 1);
// }

document.getElementById("row"+therowid).remove();
if(elements.length<=0){
document.getElementById("roomchoice").innerHTML="";
document.getElementById("totalprice").innerHTML="";
document.getElementById("divnote").innerHTML="";
document.getElementById("confirm").remove();


}
console.log(elements);
}

</script>

</body>
</html>