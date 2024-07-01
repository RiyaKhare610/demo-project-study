<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dropdown Example</title>
<style>
  .hidden {
    display: none;
  }
</style>
</head>
<body>

<select id="country" onchange="populateStates()">
  <option value="">Select Country</option>
  <option value="india">India</option>
  <option value="us">United States</option>
  <option value="uk">United Kingdom</option>
</select>

<select id="state" class="hidden" onchange="populateCities()">
  <option value="">Select State</option>
</select>

<select id="city" class="hidden">
  <option value="">Select City</option>
</select>

<script>
function populateStates() {
  var country = document.getElementById("country").value;
  var stateDropdown = document.getElementById("state");
  stateDropdown.classList.remove("hidden");
  stateDropdown.disabled = false;
  stateDropdown.innerHTML = "";
  var states = [];

  if (country === "india") {
    states = ["UP", "Maharashtra", "Delhi", "Rajasthan"];
  } else if (country === "us") {
    states = ["California", "New York", "Texas", "Florida"];
  } else if (country === "uk") {
    states = ["England", "Scotland", "Wales", "Northern Ireland"];
  }

  for (var i = 0; i < states.length; i++) {
    var option = document.createElement("option");
    option.value = states[i];
    option.text = states[i];
    stateDropdown.appendChild(option);
  }
}

function populateCities() {
  var state = document.getElementById("state").value;
  var cityDropdown = document.getElementById("city");
  cityDropdown.classList.remove("hidden");
  cityDropdown.disabled = false;
  cityDropdown.innerHTML = "";
  var cities = [];

  if (state === "UP") {
    cities = ["Lucknow", "Kanpur", "Agra", "Varanasi"];
  } else if (state === "Maharashtra") {
    cities = ["Mumbai", "Pune", "Nagpur", "Nashik"];
  } else if (state === "Delhi") {
    cities = ["New Delhi"];
  } else if (state === "Rajasthan") {
    cities = ["Jaipur", "Jodhpur", "Udaipur", "Ajmer"];
  } else if (state === "California") {
    cities = ["Los Angeles", "San Francisco", "San Diego", "Sacramento"];
  } else if (state === "New York") {
    cities = ["New York City", "Buffalo", "Rochester", "Albany"];
  } else if (state === "Texas") {
    cities = ["Houston", "Dallas", "Austin", "San Antonio"];
  } else if (state === "Florida") {
    cities = ["Miami", "Orlando", "Tampa", "Jacksonville"];
  } else if (state === "England") {
    cities = ["London", "Manchester", "Birmingham", "Liverpool"];
  } else if (state === "Scotland") {
    cities = ["Edinburgh", "Glasgow", "Aberdeen", "Dundee"];
  } else if (state === "Wales") {
    cities = ["Cardiff", "Swansea", "Newport", "Bangor"];
  } else if (state === "Northern Ireland") {
    cities = ["Belfast", "Londonderry", "Newry", "Armagh"];
  }

  for (var i = 0; i < cities.length; i++) {
    var option = document.createElement("option");
    option.value = cities[i];
    option.text = cities[i];
    cityDropdown.appendChild(option);
  }
}
</script>

</body>
</html>
