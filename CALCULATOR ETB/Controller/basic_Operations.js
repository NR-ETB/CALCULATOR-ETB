function calculateResult() {
    var cal = $('#calcular');
    var input = cal.val();

    // Reemplazar fracciones (ej. 1/4) por su valor decimal
    input = input.replace(/(\d+)\/(\d+)/g, function(match, p1, p2) {
        // Convertir la fracción a decimal
        var decimal = (parseFloat(p1) / parseFloat(p2)).toFixed(10);
        return decimal;  // Reemplazar la fracción con su valor decimal
    });

    try {
        // Evaluar la expresión
        var result = eval(input);  // Usar eval para calcular el resultado
        cal.val(result);  // Mostrar el resultado en el input
    } catch (e) {
        cal.val("Error");  // Si hay un error, mostrar "Error"
    }
}

// Función para calcular el porcentaje
// Función para calcular el porcentaje
function calculatePercentage() {
    var cal = $('#calcular');
    var currentValue = cal.val();
    
    if (currentValue.includes('%')) {
        // Eliminar el símbolo % antes de hacer el cálculo
        var number = currentValue.replace('%', '');
        
        // Realizar el cálculo del porcentaje
        var result = parseFloat(number) / 100;
        
        // Mostrar el resultado en el input
        cal.val(result);
    }
}

// Función para calcular la raíz cuadrada
function calculateSquareRoot() {
    var cal = $('#calcular');
    var currentValue = cal.val();
    if (currentValue !== "0") {
        // Calcula la raíz cuadrada
        var result = Math.sqrt(parseFloat(currentValue));
        cal.val(result);
    }
}

// Función para elevar un número
function calculateExponent() {
    var cal = $('#calcular');
    var currentValue = cal.val();
    if (currentValue !== "0") {
        // Agregar el operador de potencia (exponente)
        cal.val(currentValue + "^");
    }
}

// Función para realizar operaciones de fracción
function appendFraction() {
    var cal = $('#calcular');
    var currentValue = cal.val();
    
    if (currentValue.includes('1/4')) {
        // Si ya se ingresó "1/4", se convierte a su valor decimal
        cal.val(cal.val().replace('1/4', '0.25'));  // Reemplaza "1/4" por su valor decimal
    }
}