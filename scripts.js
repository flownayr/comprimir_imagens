$('#input-group-append').on('click', function () {
    $('#image-input').click();
});

$('#image-input').on('change', function () {
    var fileName = $(this).val().split('\\').pop();
    if (fileName) {
        $(this).next().text(fileName);
    } else {
        $(this).next().text('Nenhum arquivo selecionado');
    }
});
