<!DOCTYPE html>
<html>
<head><title>Search Test</title></head>
<body>
<input type="text" id="searchCity" placeholder="City..." style="padding:8px;width:200px"/>
<button onclick="searchHotels()" style="padding:8px 16px;background:#f59e0b;border:none;cursor:pointer">Search Hotels</button>
<p id="result" style="margin-top:10px;color:green"></p>
<script>
function searchHotels(){
  var city = document.getElementById('searchCity').value.trim().toLowerCase();
  document.getElementById('result').textContent = 'Redirecting to: hotels.php?city=' + city;
  // window.location.href = 'hotels.php?city=' + city;
}
</script>
</body>
</html>


