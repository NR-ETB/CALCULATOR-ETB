document.getElementById('add3').addEventListener('click', function() {
    const table = document.getElementById('dataTable');
    // Hacer la tabla visible temporalmente
    table.style.visibility = 'visible';

    const rows = Array.from(table.rows);
    const csvContent = rows.map(row => 
        Array.from(row.cells)
        .map(cell => cell.innerText)
        .join(',')
    ).join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.setAttribute('download', 'I&R.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Restaurar la visibilidad original de la tabla
    table.style.visibility = 'hidden';
});