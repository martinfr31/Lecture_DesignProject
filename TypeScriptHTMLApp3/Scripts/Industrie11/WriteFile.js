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