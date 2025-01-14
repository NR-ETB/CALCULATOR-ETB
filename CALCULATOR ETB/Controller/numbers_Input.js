function appendValue(value) {
    const cal = document.getElementById('calcular');
    if (cal.value === "0") {
      cal.value = value;  // Reemplaza el valor "0" inicial
    } else {
      cal.value += value; // Agrega el número al contenido existente
    }
}

function clearValue() {
    document.getElementById('calcular').value = "";  // Limpia el contenido del input
}

function deleteLast() {
    const cal = document.getElementById('calcular');
    cal.value = cal.value.slice(0, -1);  // Elimina el último carácter
    if (cal.value === "") {
        document.getElementById('calcular').value = "";  // Limpia el contenido del input
    }
}