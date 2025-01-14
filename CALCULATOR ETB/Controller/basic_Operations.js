function calculateResult() {
    var cal = $('#calcular');
    try {
        // Evalúa la expresión matemática ingresada
        var result = eval(cal.val());
        cal.val(result);  // Muestra el resultado en el input
    } catch (error) {
        cal.val("Error");  // Si hay un error (por ejemplo, sintaxis incorrecta), muestra "Error"
    }
}