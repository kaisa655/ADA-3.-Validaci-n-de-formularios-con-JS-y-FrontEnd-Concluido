document.addEventListener("DOMContentLoaded", () => {
  const boton = document.getElementById("calcular");
  if (!boton) return;
  boton.addEventListener("click", () => {
    const a = Number(document.getElementById("calcA").value);
    const b = Number(document.getElementById("calcB").value);
    const operacion = document.getElementById("operacion").value;
    const salida = document.getElementById("resultado");
    if (document.getElementById("calcA").value === "" || document.getElementById("calcB").value === "") {
      salida.textContent = "Completa los dos números."; return;
    }
    if (operacion === "/" && b === 0) { salida.textContent = "No se puede dividir entre cero."; return; }
    let resultado;
    switch (operacion) {
      case "+": resultado = a + b; break;
      case "-": resultado = a - b; break;
      case "*": resultado = a * b; break;
      case "/": resultado = a / b; break;
    }
    salida.textContent = "Resultado: " + resultado;
  });
});