function check_fullname(object)
{
  var name = object.value;
  var regexp =/^(?!\s*$)[a-zA-Z\s]+$/;
  var bool = regexp.test(name);
  if (bool == false)
  {
    alert("Please enter a valid name (only letters and spaces)");
  }
  return bool;
}

function validateemail(object)
{
  var email = object.value;
  var regexp =/^([\w-.]+)(@(?=[\w-.]+))([\w]+\.(?=[\w]+)){1,3}([\w]{2,3}$)/;
  var bool = regexp.test(email);
  if (bool == false)
  {
    alert("Please enter a valid email.");
  }
  return bool;
}

function validateform(fullname_object, email_object)
{
  return (check_fullname(fullname_object) && validateemail(email_object));
}

// Get the modal
var modal = document.getElementById("myModal");

// Get the link that opens the modal
var aulink = document.getElementById("aboutus");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
aulink.onclick = function() {
modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
if (event.target == modal) {
  modal.style.display = "none";
}
}
