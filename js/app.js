document.addEventListener("DOMContentLoaded", () => {
  const estado = document.getElementById("estado");
  const btn = document.getElementById("btn");

  btn.addEventListener("click", () => {
    const now = new Date().toLocaleString();
    estado.textContent = `JS funcionando. Fecha/hora: ${now}`;
  });
});
