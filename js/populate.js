// Generate Heatmap
var width = 960, height = 500;
var canvas = d3.select("#canvas").append("svg")
               .attr("width", width)
               .attr("height", height)
               .style("background", "#FFFFAA");

// declare Heatmap
var treemap = d3.layout.treemap()
                .size([width, height])
                .value(function (d) { return d.size; });

// Populate keywords into the list for the maps
function fillKeyWords(words) {
    $("#keywords").html("");
    words.forEach(function (e, i, a) {
        $("#keywords").append($("<option />").html(e));
    });
}

// update the map with new data from the server
function updateData(incoming) {
    // remove tooltip if exists
    canvas.select("#tooltip").remove();

    // fill keywords list from incoming words
    fillKeyWords(incoming.words);

    // data for the heatmap
    var data = incoming.heatmap;

    // set color range
    var color = d3.scale.linear().domain([1, data.max]).range(['#FFEE00', '#FF0000']);

    // generate basic svg
    var cells = canvas.datum(data).selectAll("g").data(treemap.nodes);

    // remove heat nodes on update
    s = cells.selectAll(".node")
             .on('mouseenter', null)
             .on('mouseleave', null)
             .remove();
    delete s;

    // create new nodes
    cells.enter()
             .append("g")
             .attr("class", "cell");

    cells.append("rect")
             .attr("class", "node")
             .attr("x", function (d) { return d.x; })
             .attr("y", function (d) { return d.y; })
             .attr("width", function (d) { return d.dx; })
             .attr("height", function (d) { return d.dy })
             .attr("fill", function (d) { return d.children ? null : color(d.size); })
             .attr("stroke", "#FFFFAA");

    // bind hover to show tootip
    cells.selectAll("rect")
             .on('mouseenter', function (d) {
                 var rect = d3.select(this)
                 canvas.append("text")
                       .attr("id", "tooltip")
                       .attr("x", parseInt(rect.attr("x")) + 3)
                       .attr("y", parseInt(rect.attr("y")) + 15)
                       .attr("fill", "#2ecc71")
                       .text(d.name + ": " + d.size);

             })
             .on('mouseleave', function () {
                 canvas.select("#tooltip").remove();
             });
}

// Page onload
$(function () {
    // set default location to chart
    window.location.hash = "canvas";

    var hash = null;
    var factor = 5000;
    var time = new Date().getTime();
    var limit = 0;
    var waittime = 20000;
    var incoming = null;

    // merge the new data to old data
    // merge the data in such way to facilitate the chart's input
    function merge(e, cb) {
        incoming = null;
        if (!hash) hash = e;
        else { }
        for (var k in e) {
            if (k in hash) hash[k] += e[k];
            else hash[k] = e[k];
        }
        var data = { name: "data", children: [], max: 0 };
        var alpha = {};
        for (k in hash) {
            if (!(k[0] in alpha)) alpha[k[0]] = [];
            alpha[k[0]].push({ name: k, size: hash[k] });
            data.max = Math.max(data.max, hash[k]);
        }
        for (k in alpha) {
            data.children.push({ name: k, children: alpha[k] });
        }
        incoming = { words: Object.keys(hash).sort(), heatmap: data };
        cb();
    }

    // Asynchronously load the data from the server in 1000s
    function loadData() {
        $.ajax({
            url: "ajax/data.php?from=" + limit + "&limit=" + factor,
            dataType: "json",
            success: function (e) {
                // end of data // wait for streaming
                if (!e || e instanceof Array) afterCallback();
                // get next 1000 rows
                else merge(e, nextCallback);
            },
            error: function () {
                factor = 1000;
            }
        });
    }

    // next 1000 rows
    function nextCallback(r) {
        // set limit to next 1000 rows
        limit += factor;
        // update the map with the data so far
        if (!r)
            updateData(incoming);
        // get next 1000 rows
        setTimeout(loadData, waittime);
    }

    // end of data 
    // reduce factor of getting from 1000s to 100s 
    // and ping every 10 seconds
    function afterCallback() {
        factor = 10;
        // updateData(incoming);
        // wait for 10s to next update
        setInterval(loadData, waittime);
    }

    // initialize loadData stream
    loadData();
});