
    var xmlhttp = new XMLHttpRequest();
    var url = "nav.txt";

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            myFunction(xmlhttp.responseText);
        }
    }

    xmlhttp.open("GET", url, true);
    xmlhttp.send();


function myFunction(response) {
    var arr = JSON.parse(response);
    console.log(arr);
    var i;
    var out = "";

    for (i = 0; i < arr.length; i++) {
        out += '<a href="' + arr[i].Link + '">' + arr[i].Name + '</a><br>';
    }
    document.getElementById("majornav").innerHTML = out;
}