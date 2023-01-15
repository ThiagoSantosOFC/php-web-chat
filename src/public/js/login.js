function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
  }
  

let ID = getCookie("id");

onload = function () {
  if (ID == null ||ID =="null" ||  ID == "" || ID == "undefined") {
//do nothing
  } else {
    console.log("ID: " + ID);
    //go to chat
    window.location.href = "/php-web-chat/src/chat.html";

  }
};

