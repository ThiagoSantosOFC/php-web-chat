const socket = new WebSocket('ws://localhost:8080');
console.log(socket);

socket.addEventListener('open', (event) => {
  console.log('WebSocket connected');
});

socket.addEventListener('message', (event) => {
  console.log('WebSocket message received:', event.data);
  const message = JSON.parse(event.data);
  appendMessage(
    message.username,
    '/php-web-chat/src/public/assets/user.png',
    message.side,
    message.content
  );
});

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

let ID = getCookie("id");

window.onload = function () {
  if (ID === null || ID == "null" || ID == "" || ID == "undefined") {
    window.location.href = "/php-web-chat/src/login.html";
  } else {
    console.log("ID: " + ID);
    try {
      fetch("/php-web-chat/src/api/message/get.php?join=true")
        .then((res) => res.json())
        .then((data) => {
          data.forEach((element) => {
            const side = element.user_id == ID ? "right" : "left";
            appendMessage(
              element.username,
              "/php-web-chat/src/public/assets/user.png",
              side,
              element.content
            );
          });
        });
    } catch (error) {
      console.log(error);
    }
  }
};

const msgerForm = get(".msger-inputarea");
const msgerInput = get(".msger-input");
const msgerChat = get(".msger-chat");

var PERSON_NAME = getCookie("username");

msgerForm.addEventListener("submit", (event) => {
  event.preventDefault();

  const msgText = msgerInput.value;
  if (!msgText) {
    return null;
  } else {
    const message = {
      content: msgText,
      user_id: ID,
      username: PERSON_NAME,
      side: "right",
    };

    // Enviar mensagem para o servidor via WebSocket
    socket.send(JSON.stringify(message));

    // Enviar mensagem para o banco de dados
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

function get(selector, root = document) {
  return root.querySelector(selector);
}

function formatDate(date) {
  const h = "0" + date.getHours();
  const m = "0" + date.getMinutes();
  return `${h.slice(-2)}:${m.slice(-2)}`;
}

let logout = document.getElementById("logout");

logout.addEventListener("click", (event) => {
  document.cookie = "id=null; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
});
