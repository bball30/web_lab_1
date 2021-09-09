let x, y, r;

function validateX() {
    x = document.getElementById("x_field")[document.getElementById("x_field").selectedIndex].value;
    return true;
}

function validateY() {
    y = document.getElementById("y_field").value.replace(",", ".");
    if (y === "") {
        alert("Введите координату Y!");
        document.getElementById("y_field").focus();
        return false;
    }
    if (!(!isNaN(parseFloat(y)) && isFinite(y))) {
        alert("Y не является числом!");
        document.getElementById("y_field").value = "";
        document.getElementById("y_field").focus();
        return false;
    }
    if (!((y > -5) && (y < 3))) {
        alert("Y должен попадать в промежуток (-5, 3)!");
        document.getElementById("y_field").value = "";
        document.getElementById("y_field").focus();
        return false;
    }

    return true;
}

function validateR() {
    if (document.querySelector('input[name="R"]:checked') != null) {
        r = document.querySelector('input[name="R"]:checked').value;
    } else {
        alert("Выберете значение R!");
        return false;
    }
    return true;
}

function validate() {
    return validateX() && validateY() && validateR();
}

function submit() {
    if (validate()) {
        $.get("php/answer.php", {
            'x': x,
            'y': y,
            'r': r,
            'timezone': new Date().getTimezoneOffset()
        }).done(function (data) {
            let arr = JSON.parse(data);
            arr.forEach(function (elem) {
                if (!elem.validate) return;
                let newRow = elem.isHit ? '<tr class="green" height="60px">' : '<tr class="red" height="60px">';
                newRow += '<td>' + elem.x + '</td>';
                newRow += '<td>' + elem.y + '</td>';
                newRow += '<td>' + elem.r + '</td>';
                newRow += '<td>' + elem.currentTime + '</td>';
                newRow += '<td>' + elem.execTime + '</td>';
                newRow += '<td>' + (elem.isHit ? 'Попал' : 'Мимо') + '</td>';
                $('#result-table tr:first').after(newRow);
            });
        }).fail(function (err) {
            alert(err);
        });
    }
}