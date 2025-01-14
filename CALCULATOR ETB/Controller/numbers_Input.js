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

function formatNumber() {
    var cal = $('#calcular');
    var input = cal.val();

    // Eliminar todos los caracteres no numéricos excepto el punto decimal
    var cleanInput = input.replace(/[^0-9.]/g, '');

    // Si hay un punto, limitar a uno solo
    if (cleanInput.split('.').length > 2) {
        cleanInput = cleanInput.slice(0, -1);
    }

    // Formatear el número con comas como separador de miles
    if (cleanInput !== "") {
        var formattedValue = Number(cleanInput).toLocaleString();  // Formatear el número con comas
        cal.val(formattedValue);  // Mostrar el número formateado en el input
    }
}
