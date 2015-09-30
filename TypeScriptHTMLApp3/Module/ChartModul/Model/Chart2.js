function Chart2(jData)
{
    //Erstellen der tmp txt Datei für Diagramm 2
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    function generateCSV(s, data)
    {
        var csv = "State,ProcessID,Nicht veraenderbar,Kosten,Potential\r\n";
        var d = JSON.parse(data);
        for (var i = 0; i < d[0].children.length; i++) {
            obj = d[0].children[i];
            if(obj.name == s)
            {
                obj2 = obj.children;
                for (var k = 0; k < obj2.length; k++) {
                    csv += obj.children[k].name + "," + obj.children[k].ProzessID + ",";
                    obj3 = obj2[k].children;
                    var fix = 0, kosten = 0, veraenderbar = 0;
                    for(var m = 0; m < obj3.length; m++)
                    {
                        fix += parseInt(obj3[m].fix);
                        kosten += parseInt(obj3[m].kosten);
                        veraenderbar += parseInt(obj3[m].ResVeranederbar);
                    }
                    csv += fix + "," + kosten + "," + veraenderbar + '\r\n';
                }
                //alert(csv);
            }
        }
<<<<<<< HEAD
       // path = "C:\\Users\\martin.knelsen\\Documents\\GitHub\\Lecture_DesignProject\\TypeScriptHTMLApp3\\temp1.txt"
       // writefile(csv, path);

        function DeleteKartItems() {     
            $.ajax({
                type: "POST",
                url: 'createFile.aspx',
                data: "",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (msg) {
                    $("#divResult").html("success");
                },
                error: function (e) {
                    $("#divResult").html("Something Wrong.");
                }
            });
        }
        DeleteKartItems();
=======
        path = "C:\\Users\\krings\\Dropbox\\Development\\Neuer Ordner\\Lecture_DesignProject\\TypeScriptHTMLApp3\\temp1.txt"
        writefile(csv, path);
>>>>>>> origin/master
    }

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //Parameter aus URL auslesen
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    var parm = getParameterByName('Key');
    if (parm != "") {
        generateCSV(parm, jData);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //Diagramm erstellen
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
        
        d3.csv("temp1.txt", function (error, data) {
            if (error) throw error;

            //Definition der Werte, aus denen das Diagramm zusammen gebaut werden sollen. Überschrift wird gefiltert
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            color.domain(d3.keys(data[0]).filter(function (key) { return key !== "State"; }).filter(function (key) { return key !== "ProcessID"; }));
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

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

            //var prozess = svg.selectAll('.prozess')
            //        .data(hue.domain().slice(1).reverse())
            //        .enter()
            //        .append('g')
            //        .attr('class', 'prozess')
            //        .attr('transform', function (d, i) {
            //            var height = legendRectSize + legendSpacing;
            //            var offset = height * hue.domain().slice(1).length / 2;
            //            var horz = -2 * legendRectSize;
            //            var vert = i * height - offset;
            //            return 'translate(' + horz + ',' + vert + ')';
            //        });
            //// Fügt die Quadrate der Legende in der jeweiligen Farbe hinzu
            //prozess.append('rect')
            //      .attr('width', legendRectSize)
            //      .attr('height', legendRectSize)
            //      .style('fill', hue)
            //      .style('stroke', hue);
            //// Fügt die Beschriftung der Legende hinzu
            //legend.append('text')
            //      .attr('x', legendRectSize + legendSpacing)
            //      .attr('y', legendRectSize - legendSpacing)
            //      .text(partition.value(function (d) { return d.name; }));

            // svg.append("polygon")
            //.points = ("0,0 100,0 120,30 100,60 00,60 20,30 0,0")
            //.coordinates = ("0,0 100,0 120,30 100,60 00,60 20,30 0,0")
            //  .text("test")
            //   .style = ("fill", "blue")
            //.attr("x", width - 30)
            //  .attr("y", 35);

            svg.append("g")
                .attr("class", "y axis")
                .call(yAxis)
              .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .text("Kosten");

            var state = svg.selectAll(".state")
                .data(data)
              .enter().append("g")
                //Weiterleitung
                .on("click", function (d) {
                    update();
                    // window.location = window.location.href.toString() + "." + d.State;
                })
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

            function deleteChartData() {
                legend
                    .exit().remove();
                //svg
                //  .exit().remove();
            }

        });
    }
}