function appendValue(value) {
    const cal = $('#calcular'); 
    if (cal.val() === "0") {
        cal.val(value);  // Reemplaza el valor "0" inicial
    } else {
        cal.val(cal.val() + value);  // Agrega el valor al contenido existente
    }
}

function clearValue() {
    $('#calcular').val("");  // Limpia el contenido del input
}

function deleteLast() {
    const cal = $('#calcular');
    cal.val(cal.val().slice(0, -1));  // Elimina el último carácter
    if (cal.val() === "") {
        cal.val("");  // Limpia el contenido del input si está vacío
    }
}