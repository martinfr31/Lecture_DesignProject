function Chart3(jData) {

    //Erstellen der tmp CSV Datei für Diagramm 3
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    function generateCSV(p, data) {
        var csv = "State,Nicht veraenderbar,Kosten,Potential\r\n";
        var d = JSON.parse(data);
        for (var i = 0; i < d[0].children.length; i++) {
            obj = d[0].children[i];
            if (obj.name == p[0]) {
                obj2 = obj.children;
                for (var k = 0; k < obj2.length; k++) {
                    
                    if (p[1] == obj2[k].ProzessID)
                    {                        
                        obj3 = obj2[k].children;
                        for (var m = 0; m < obj3.length; m++) {
                            csv += obj3[m].ResName + "," + obj3[m].fix + "," + obj3[m].kosten + "," + obj3[m].ResVeranederbar + "\r\n";
                        }
                    }
                }
                //alert(csv);
            }
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    //Parameter aus der URL auslesen
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    var parm = getParameterByName('Key');
    if (parm != "") {
        var p = parm.split(".");
        generateCSV(p, jData);
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



    d3.csv("chart2_demo.csv", function (error, data) {
        if (error) throw error;

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

        var state = svg.selectAll(".state")
            .data(data)
          .enter().append("g")
            .attr("class", "g")
            .attr("transform", function (d) { return "translate(" + x(d.State) + ",0)"; });

        state.selectAll("rect")
            .data(function (d) { return d.ages; })
          .enter().append("rect")
            .attr("width", x.rangeBand())
            .attr("y", function (d) { return y(d.y1); })
            .attr("height", function (d) { return y(d.y0) - y(d.y1); })
            .style("fill", function (d) { return color(d.name); });

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