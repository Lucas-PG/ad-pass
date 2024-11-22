// =======================================================================================
// APAGAR MENSAGEM DE ERRO E SUCESSO
// =======================================================================================

function deleteMessage() {
  let messageDiv = document.querySelector(".message-div");

  if (messageDiv) {
    messageDiv.remove();
  }
}

if (document.getElementById("deleteMessageButton")) {
  const deleteMessageButton = document.getElementById("deleteMessageButton");

  deleteMessageButton.addEventListener("click", () => {
    deleteMessage();
  });
}

// Alterna entre tipos de input 'text' e 'password'
function toggleShowPassword(inputBox, inputOpenEye, inputClosedEye) {
  if (inputBox.type == "text") {
    // Esconde olho aberto, mostra fechado, input type vira password
    inputClosedEye.style.display = "initial";
    inputOpenEye.style.display = "none";
    inputBox.type = "password";
  } else {
    // Esconde olho fechado, mostra aberto, input type vira text
    inputOpenEye.style.display = "initial";
    inputClosedEye.style.display = "none";
    inputBox.type = "text";
  }
}

// Garante que o icone certo seja mostrado ao carregar a página (sem essa func pode ocorrer erros)
function checkInitialInputType(inputBox, inputOpenEye, inputClosedEye) {
  if (inputBox.type == "text") {
    inputOpenEye.style.display = "initial";
    inputClosedEye.style.display = "none";
  } else {
    inputClosedEye.style.display = "initial";
    inputOpenEye.style.display = "none";
  }
}

window.onload = () => {
  // Seleciona todos os containers de ícones de olho
  const eyeDivs = document.querySelectorAll(".input-eye-div");

  // Itera sobre cada div de ícone do olho
  eyeDivs.forEach((eyeDiv) => {
    const inputBox = eyeDiv.parentNode.querySelector("input"); // Encontra o input no mesmo elemento pai
    const inputOpenEye = eyeDiv.querySelector(".fa-eye");
    const inputClosedEye = eyeDiv.querySelector(".fa-eye-slash");

    // Verifica o tipo inicial do input
    checkInitialInputType(inputBox, inputOpenEye, inputClosedEye);

    // Adiciona evento ao clicar para as eyeDivs
    eyeDiv.addEventListener("click", () =>
      toggleShowPassword(inputBox, inputOpenEye, inputClosedEye),
    );
  });
};
