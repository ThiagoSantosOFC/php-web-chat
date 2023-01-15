//get id cookie and set it to ID
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

let ID = getCookie("id");

onload = function () {
  if (ID == null || ID == "" || ID == "undefined") {
    window.location.href = "/php-web-chat/src/login";
  } else {
    console.log("ID: " + ID);
    //get mensagens from database
    try {
      fetch("/php-web-chat/src/api/message/get.php?join=true")
        .then((res) => res.json())
        .then((data) => {
          data.forEach((element) => {
            if (element.user_id == ID) {
              appendMessage(
                element.username,
                "/php-web-chat/src/public/assets/user.png",
                "right",
                
                element.content
              );
            } else {
              appendMessage(
                element.username,
                "/php-web-chat/src/public/assets/user.png",
                "left",
                
                element.content
              );
            }
           
          });
        });
    }
    catch (error) {
      console.log(error);
    }

  }
};

const msgerForm = get(".msger-inputarea");
const msgerInput = get(".msger-input");
const msgerChat = get(".msger-chat");

//get cookie username and set it to PERSON_NAME

var PERSON_NAME = getCookie("username");


msgerForm.addEventListener("submit", (event) => {
  event.preventDefault();

  const msgText = msgerInput.value;
  if (!msgText) {
    return null;
  } else {
    //send msg to database
    try {
      fetch("/php-web-chat/src/api/message/create.php?jsonpost=true", {
        method: "POST",
        body: JSON.stringify({
          content: msgText,
          user_id: ID,
        }),
        headers: {
          "Content-Type": "application/json",
        },
      });
    } catch (error) {
      console.log(error);
    } finally {
      appendMessage(
        PERSON_NAME,
        "/php-web-chat/src/public/assets/user.png",
        "right",
        
        msgText
      );
      msgerInput.value = "";
    }
  }
});

function appendMessage(name, img, side, text) {
  //   Simple solution for small apps
  const msgHTML = `
    <div class="msg ${side}-msg">
      <div class="msg-img" style="background-image: url(${img})"></div>

      <div class="msg-bubble">
        <div class="msg-info">
          <div class="msg-info-name">${name}</div>
          <div class="msg-info-time">${formatDate(new Date())}</div>
        </div>

        <div class="msg-text">${text}</div>
      </div>
    </div>
  `;

  msgerChat.insertAdjacentHTML("beforeend", msgHTML);
  msgerChat.scrollTop += 500;
}

// Utils
function get(selector, root = document) {
  return root.querySelector(selector);
}

function formatDate(date) {
  const h = "0" + date.getHours();
  const m = "0" + date.getMinutes();

  return `${h.slice(-2)}:${m.slice(-2)}`;
}

