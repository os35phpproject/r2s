<html>
<head>
<title> ALL PRODUCTS </title>
</head>
 <body>

<form>
<input type="date" name="start" value="start" id='data' checked>start
<input type="button" name="get" value="get" onclick="getv()">
</form>


<script>
function getv(){
elem=document.getElementById('data');
console.log(elem.valueAsDate);
}
</script>




</body>
	</html>