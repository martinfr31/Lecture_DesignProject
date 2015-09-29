function Chart2(jData)
{
    function generateCSV(s, data)
    {
        var csv = "State,Nicht veraenderbar,Kosten,Potential\r\n";
        var d = JSON.parse(data);
        for (var i = 0; i < d[0].children.length; i++) {
            
            obj = d[0].children[i];
            if(obj.name == s)
            {
                obj2 = obj.children;
                for (var k = 0; k < obj2.length; k++) {
                    csv += obj.children[k].name + ",";
                    //alert(obj2[k].name);
                    obj3 = obj2[k].children;
                    var fix = 0, kosten = 0, veraenderbar = 0;
                    for(var m = 0; m < obj3.length; m++)
                    {
                        fix += parseInt(obj3[m].fix);
                        kosten += parseInt(obj3[m].kosten);
                        veraenderbar += parseInt(obj3[m].ResVeranederbar);
                        //alert(obj3[m].name);
                    }
                    csv += fix + "," + kosten + "," + veraenderbar + '\r\n';
                }
                //alert(csv);
            }
        }
    }

    //var data = "...";// this is your data that you want to pass to the server (could be json)
    ////next you would initiate a XMLHTTPRequest as following (could be more advanced):
    //var url = "get_data.php";//your url to the server side file that will receive the data.
    //var http = new XMLHttpRequest();
    //http.open("POST", url, true);

    ////Send the proper header information along with the request
    //http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //http.setRequestHeader("Content-length", params.length);
    //http.setRequestHeader("Connection", "close");

    //http.onreadystatechange = function () {//Call a function when the state changes.
    //    if (http.readyState == 4 && http.status == 200) {
    //        alert(http.responseText);//check if the data was received successfully.
    //    }
    //}
    //http.send(data);

        try {
            var fso, s;
            fso = new ActiveXObject("Scripting.FileSystemObject");
            s = fso.CreateTextFile("C:\1_Daten_UNITY\Martin.Knelsen\Desktop\test.txt", true);
            s.writeline("This is a test");
            s.Close();
        }
        catch (err) {
            var strErr = 'Error:';
            strErr += '\nNumber:' + err.number;
            strErr += '\nDescription:' + err.description;
            document.write(strErr);
        }
 


    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    var parm = getParameterByName('Key');
    if (parm != "") {
        generateCSV(parm, jData);
    }


    var margin = { top: 20, right: 20, bottom: 30, left: 40 },
        width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

    var x = d3.scale.ordinal()
        .rangeRoundBands([0, width], .1);

    var y = d3.scale.linear()
        .rangeRound([height, 0]);

    var color = d3.scale.ordinal()
        .range(["#515151", "#319027", "#F5180C"]);

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom");

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left")
        .tickFormat(d3.format(".2s"));

    var svg = d3.select("body").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
      .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
        


   d3.csv("chart3_demo.csv", function (error, data) {
        if (error) throw error;

        //Definition der Werte, aus denen das Diagramm zusammen gebaut werden sollen. Überschrift wird gefiltert
        color.domain(d3.keys(data[0]).filter(function (key) { return key !== "State"; }));

        data.forEach(function (d) {
            var y0 = 0;
            d.ages = color.domain().map(function (name) { return { name: name, y0: y0, y1: y0 += +d[name] }; });
            d.total = d.ages[d.ages.length - 1].y1;
        });

        //data.sort(function (a, b) { return b.total - a.total; });

        x.domain(data.map(function (d) { return d.State; })); 
        y.domain([0, d3.max(data, function (d) { return d.total; })]);

        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
          .append("text")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", ".71em")
            .style("text-anchor", "end")
            .text("Kosten");

        //var processID = svg.selectAll(".ProcessID")
        //    .data(data, function (d) { alert(d.ProcessID); })
        //    .text("ProcessID");
            
        //alert(processID);
        //alert(processID.data);


        var state = svg.selectAll(".state")
            .data(data)
          .enter().append("g")
            .on("click", function (d) { window.location = window.location.href.toString() + "." + d.ProcessID; }) //Weiterleitung
            .attr("class", "g")
            .attr("transform", function (d) { return "translate(" + x(d.State) + ",0)"; });

        state.selectAll("rect")
            .data(function (d) { return d.ages; })
          .enter().append("rect")
            .attr("width", x.rangeBand())
            .attr("y", function (d) { return y(d.y1); })
            .attr("height", function (d) { return y(d.y0) - y(d.y1); })
            .style("fill", function (d) { return color(d.name); })
            .text(function (d) { return d; });
            

        var legend = svg.selectAll(".legend")
            .data(color.domain().slice().reverse())
          .enter().append("g")
            .attr("class", "legend")
            .attr("transform", function (d, i) { return "translate(0," + i * 20 + ")"; });

        legend.append("rect")
            .attr("x", width - 18)
            .attr("width", 18)
            .attr("height", 18)
            .style("fill", color);

        legend.append("text")
            .attr("x", width - 24)
            .attr("y", 9)
            .attr("dy", ".35em")
            .style("text-anchor", "end")
            .text(function (d) { return d; });

    });
}