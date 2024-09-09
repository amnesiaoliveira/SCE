// autocomplete.js
function buscarSugestoes() {
    var query = document.getElementById('codigo').value;
    if (query.length > 0) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'buscar_sugestoes.php?query=' + query, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('sugestoes').innerHTML = xhr.responseText;
                    document.getElementById('sugestoes').style.display = 'block';
                }
            }
        };
        xhr.send();
    } else {
        document.getElementById('sugestoes').style.display = 'none';
    }
}

function selecionarSugestao(codigo, nome) {
    document.getElementById('codigo').value = codigo;
    document.getElementById('produto').value = nome;
    document.getElementById('sugestoes').style.display = 'none';
}