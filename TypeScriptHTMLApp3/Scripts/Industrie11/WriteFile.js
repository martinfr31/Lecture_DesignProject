function writefile(txt, path)
{
    try {
        var fso, s;
        fso = new ActiveXObject("Scripting.FileSystemObject");
        s = fso.CreateTextFile(path, true);
        s.writeline(txt);
        s.Close();
    }
    catch (err) {
        var strErr = 'Error:';
        strErr += '\nNumber:' + err.number;
        strErr += '\nDescription:' + err.description;
        document.write(strErr);
    }
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function encode(y) {
    while ((y.indexOf("[") > 0) || (y.indexOf("]") > 0) || (y.indexOf("{") > 0) || (y.indexOf("}") > 0) || (y.indexOf(" ") > 0) || (y.indexOf("\"") > 0)) {
        y = y.replace("[", "+");
        y = y.replace("]", "$");
        y = y.replace("{", "(");
        y = y.replace("}", ")");
        y = y.replace(" ", "?");
        y = y.replace("\"", "&");
    }
    return y;
}

function decode(y) {
    while ((y.indexOf("+") > 0) || (y.indexOf("$") > 0) || (y.indexOf("(") > 0) || (y.indexOf(")") > 0) || (y.indexOf("?") > 0) || (y.indexOf("&") > 0)) {
        y = y.replace("+", "[");
        y = y.replace("$", "]");
        y = y.replace("(", "{");
        y = y.replace(")", "}");
        //y = y.replace("?", " ");
        y = y.replace("&", "\"");
    }
    return y;
}

function WertHolen() {

    var Wert = "";

    if (document.cookie) {
        var Wertstart = document.cookie.indexOf("=") + 1;
        var Wertende = document.cookie.indexOf(";");

        if (Wertende == -1)
            Wertende = document.cookie.length;
        Wert = document.cookie.substring(Wertstart, Wertende);
    }
    return decode(Wert);
}

function WertSetzen(Bezeichner, Wert) {
    var temp = encode(Wert);
    //alert("Afert encoding: " + temp);
    var encoded = temp.substring(0, temp.length - 4000);
    //alert("After substring: " + encoded);
    var jetzt = new Date();
    var Auszeit = new Date(jetzt.getTime() + 1000 * 60 * 60);
    document.cookie = Bezeichner + "=" + encoded + "; expires=" + Auszeit.toGMTString() + ";";
    //alert(document.cookie);
}

function setCookie(value) {
    //alert(value);
    WertSetzen("Chart1data", value);
    //alert(document.cookie);
}