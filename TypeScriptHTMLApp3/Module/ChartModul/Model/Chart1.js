function Chart1(jData)
{
    //Datei für Diagramm 1 erstellen
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //var jData = jData.substring(0, jData.length);

    //setTimeout(setCookie(jData), 100);
  
    //window.location = "./createFile.aspx?Key=Kostenuebersicht&name=temp0.txt&created=0";
    
    //path = "C:\\Users\\martin.knelsen\\Documents\\GitHub\\Lecture_DesignProject\\TypeScriptHTMLApp3\\temp0.txt"
    //writefile(wert3, path);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // Initialisierung von Variablen für die Legende
    var legendRectSize = 18;
    var legendSpacing = 4;

    var margin = { top: 350, right: 480, bottom: 350, left: 480 },
    radius = Math.min(margin.top, margin.right, margin.bottom, margin.left) - 10;

    var hue = d3.scale.category10();

    var luminance = d3.scale.sqrt()
        .domain([0, 1e6])
        .clamp(true)
        .range([90, 20]);

    var svg = d3.select("body").append("svg")
        .attr("width", margin.left + margin.right)
        .attr("height", margin.top + margin.bottom)
      .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    var partition = d3.layout.partition()
        .sort(function (a, b) { return d3.ascending(a.name, b.name); })
        .size([2 * Math.PI, radius]);

    var arc = d3.svg.arc()
        .startAngle(function (d) { return d.x; })
        .endAngle(function (d) { return d.x + d.dx - .01 / (d.depth + .5); })
        .innerRadius(function (d) { return radius / 3 * d.depth; })
        .outerRadius(function (d) { return radius / 3 * (d.depth + 1) - 1; });


    d3.json("temp0.txt", function (error, root) {

        //Weiterleitung bei Klick auf das Diagramm
        //+++++++++++++++++++++++++++++++++++++++++++
        function weiterleitung(parm) {
            window.location = "./index.html?Key=" + parm; 
        }
        //+++++++++++++++++++++++++++++++++++++++++++

        // Compute the initial layout on the entire tree to sum sizes.
        // Also compute the full name and fill color for each node,
        // and stash the children so they can be restored as we descend.
        partition
            .value(function (d) {
                return d.size;
            })
            .nodes(root)
            .forEach(function (d) {
                //alert(key(d) + " " + d.value);
                d._children = d.children;
                d.sum = d.value;    //Chartgröße
                d.key = key(d);     //Name Produkt
                d.fill = fill(d);   //Farbe Produkt
            });

        // Now redefine the value function to use the previously-computed sum.
        partition
            .children(function (d, depth) { return depth < 2 ? d._children : null; })
            .value(function (d) { return d.sum; });

        var center = svg.append("circle")
            .attr("r", radius / 3)
            .on("click", zoomOut);

        center.append("title")
            .text("zoom out");

        var path = svg.selectAll("path")
            .data(partition.nodes(root).slice(1))
            .enter().append("path")
            .attr("d", arc)
            .style("fill", function (d) { return d.fill; })
            .each(function (d) { this._current = updateArc(d); })
            .on("click", zoomIn);

        // Legt den Bereich der Legende fest
        var legend = svg.selectAll('.legend')
                        .data(hue.domain().slice(1).reverse())
                        .enter()
                        .append('g')
                        .attr('class', 'legend')
                        .attr('transform', function (d, i) {
                            var height = legendRectSize + legendSpacing;
                            var offset = height * hue.domain().slice(1).length / 2;
                            var horz = -2 * legendRectSize;
                            var vert = i * height - offset;
                            return 'translate(' + horz + ',' + vert + ')';
                        });
        // Fügt die Quadrate der Legende in der jeweiligen Farbe hinzu
        legend.append('rect')
              .attr('width', legendRectSize)
              .attr('height', legendRectSize)
              .style('fill', hue)
              .style('stroke', hue);
        // Fügt die Beschriftung der Legende hinzu
        legend.append('text')
              .attr('x', legendRectSize + legendSpacing)
              .attr('y', legendRectSize - legendSpacing)
              .text(partition.value(function (d) { return d.name; }));

        function zoomIn(p) {
            weiterleitung(p.key);
            if (p.depth > 1) p = p.parent;
            if (!p.children) return;
            zoom(p, p);
        }

        function zoomOut(p) {
            if (typeof p != 'undefined') {
                if (!p.parent) return;
                zoom(p.parent, p);
            }
        }

        // Zoom to the specified new root.
        function zoom(root, p) {

            if (document.documentElement.__transition__) return;

            // Rescale outside angles to match the new layout.
            var enterArc,
                exitArc,
                outsideAngle = d3.scale.linear().domain([0, 2 * Math.PI]);

            function insideArc(d) {
                return p.key > d.key
                    ? { depth: d.depth - 1, x: 0, dx: 0 } : p.key < d.key
                    ? { depth: d.depth - 1, x: 2 * Math.PI, dx: 0 }
                    : { depth: 0, x: 0, dx: 2 * Math.PI };
            }

            function outsideArc(d) {
                return { depth: d.depth + 1, x: outsideAngle(d.x), dx: outsideAngle(d.x + d.dx) - outsideAngle(d.x) };
            }

            center.datum(root);

            // When zooming in, arcs enter from the outside and exit to the inside.
            // Entering outside arcs start from the old layout.
            if (root === p) enterArc = outsideArc, exitArc = insideArc, outsideAngle.range([p.x, p.x + p.dx]);

            path = path.data(partition.nodes(root).slice(1), function (d) { return d.key; });
            

            // When zooming out, arcs enter from the inside and exit to the outside.
            // Exiting outside arcs transition to the new layout.
            if (root !== p) enterArc = insideArc, exitArc = outsideArc, outsideAngle.range([p.x, p.x + p.dx]);

            d3.transition().duration(d3.event.altKey ? 7500 : 750).each(function () {
                path.exit().transition()
                    .style("fill-opacity", function (d) { return d.depth === 1 + (root === p) ? 1 : 0; })
                    .attrTween("d", function (d) { return arcTween.call(this, exitArc(d)); })
                    .remove();

                path.enter().append("path")
                    .style("fill-opacity", function (d) { return d.depth === 2 - (root === p) ? 1 : 0; })
                    .style("fill", function (d) { return d.fill; })
                    .on("click", zoomIn)
                    .each(function (d) { this._current = enterArc(d); });

                path.transition()
                    .style("fill-opacity", 1)
                    .attrTween("d", function (d) { return arcTween.call(this, updateArc(d)); });
            });
        }
    });

    function key(d) {
        var k = [], p = d;
        while (p.depth) k.push(p.name), p = p.parent;
        return k.reverse().join(".");
    }

    function fill(d) {
        var p = d;
        while (p.depth > 1) p = p.parent;
        var c = d3.lab(hue(p.name));
        c.l = luminance(d.sum);
        return c;
    }

    function arcTween(b) {
        var i = d3.interpolate(this._current, b);
        this._current = i(0);
        return function (t) {
            return arc(i(t));
        };
    }

    function updateArc(d) {
        return { depth: d.depth, x: d.x, dx: d.dx };
    }

    d3.select(self.frameElement).style("height", margin.top + margin.bottom + "px");
}