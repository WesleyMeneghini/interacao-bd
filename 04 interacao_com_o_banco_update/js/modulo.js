function validarEntrada(caracter, tipoDeDados) {

    var tipo = tipoDeDados;

    // serve para padronizar a conversao em ascii em todas as versoes de navegadores
    if (window.event) {
        // Os que sao baseados em janela ou nao
        // charCode converte o caracter em ascii
        var asc = caracter.charCode;
    } else {
        // para navegadores antigos
        var asc = caracter.which;
    }


    if (tipo == "string") {
        if (asc >= 48 && asc <= 57) {
            return false;
        }
    } else if (tipo == "numeric") {
        if (asc < 48 || asc > 57) {
            return false;
        }
    }

}

function mascaraFone(objeto) {

    if (validarEntrada(caracter, "string") == false) {
        return false
    } else {
        var input = objeto.value;
        var id = objeto.id;
        var resultado = input;


        if (input.length == 0) {
            resultado = "(";
        } else if (input.length == 4) {
            resultado += ") ";
        } else if (input.length == 10) {
            resultado += "-";
        } else if (input.length == 15) {
            return false;
        }

        return document.getElementById(id).value = resultado;
    }





    // console.log(resultado);
}