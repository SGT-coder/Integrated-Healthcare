const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const chatbox = document.querySelector(".chatbox");
const chatbotToggler = document.querySelector(".chatbot-toggler");
const chatbotCloseIcon = document.querySelector(".close-icon");
const toggleIcon = document.querySelector(".toggle-icon");

let userMessage;
const API_KEY = "17bd2c20-0b18-475c-ac66-83106d7a4ac4";
const API_URL = "wss://public.backend.medisearch.io:443/ws/medichat/api";
let socket;

const createChatLi = (message, className) => {
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", className);
  let chatContent;
  if (className === "outgoing") {
    chatContent = `<p style=" color:rgba(0, 0, 0, 0.8);">${message}</p>`;
  } else {
    chatContent = `
      <span class="material-symbols-outlined">smart_toy</span>
      <p>
        <span style="font-size:16px; width:60px; height:15px; padding:-10px;"class="thinking-animation">Thinking...</span><br>
        ${message}
      </p>`;
  }
  chatLi.innerHTML = chatContent;
  return chatLi;
};

let currentResponseText = "";

const handleChat = () => {
  userMessage = chatInput.value.trim();
  if (!userMessage) return;

  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  const incomingChatLi = createChatLi("", "incoming");
  chatbox.appendChild(incomingChatLi);
  chatbox.scrollTo(0, chatbox.scrollHeight);

  const userConversation = {
    event: "user_message",
    conversation: [userMessage],
    key: API_KEY,
    id: Date.now().toString(),
    settings: {
      language: "English",
    },
  };

  socket.send(JSON.stringify(userConversation));
  chatInput.value = "";
};

const handleResponse = (response) => {
  const { event, text, articles, error_code } = response;

  if (event === "llm_response") {
    currentResponseText = text;
    const incomingChatLi = chatbox.lastElementChild;
    const messageElement = incomingChatLi.querySelector("p");
    messageElement.textContent = currentResponseText;
  } else if (event === "articles") {
    const incomingChatLi = chatbox.lastElementChild;
    const messageElement = incomingChatLi.querySelector("p");
    const articlesList = articles.map((article, index) => `[${index + 1}] ${article.title}`).join("\n");
    messageElement.textContent += `\n\nReferences:\n${articlesList}`;
    currentResponseText = "";
  } else if (event === "error") {
    const incomingChatLi = createChatLi(`Error: ${error_code}`, "incoming");
    chatbox.appendChild(incomingChatLi);
    currentResponseText = "";
  }

  chatbox.scrollTo(0, chatbox.scrollHeight);
};

const toggleChatbot = () => {
  document.body.classList.toggle("show-chatbot");
  toggleIcon.classList.toggle("hide");
};

const connectToWebSocket = () => {
  socket = new WebSocket(API_URL);

  socket.onopen = () => {
    console.log("WebSocket connection established");
  };

  socket.onmessage = (event) => {
    const response = JSON.parse(event.data);
    handleResponse(response);
  };

  socket.onclose = () => {
    console.log("WebSocket connection closed");
  };

  socket.onerror = (error) => {
    console.error("WebSocket error:", error);
  };
};

connectToWebSocket();
sendChatBtn.addEventListener("click", handleChat);
chatbotToggler.addEventListener("click", toggleChatbot);
chatbotCloseIcon.addEventListener("click", toggleChatbot);