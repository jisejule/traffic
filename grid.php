<?php
session_start();

require_once 'header.php';

//Generates the page to allow people to draw a grid over a document for segmentation.

require_once('settings.php');
$conn = new mysqli("localhost",$db_username,$db_password,$db_name);
$query = $conn->prepare("SELECT tablefile, subdir FROM traffic_tablefiles WHERE segmented=false ORDER BY rand() LIMIT 1");
##$query->bind_param('',) #no parameters
$query->execute();
$query->bind_result($imageId, $subdir);
$query->fetch(); #only need to do once.
$query->close();
//$data = mysqli_fetch_row($res);
//$imageId = $data[0];
//$subdir = $data[1];
$query = $conn->prepare("SELECT width FROM traffic_tablesources WHERE subdir=?");
$query->bind_param('s',$subdir);
$query->execute();
$query->bind_result($width);
$widths = [];
while ($query->fetch()) {
  $widths[] = $width;
}
$query->close();


$conn->close();
?>


<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Kampala Crash Collaboration</title>
  <link rel="stylesheet" href="style.css" type="text/css" media="screen">
  <script src="raphael.js"></script>
  <script src="jquery-1.11.2.min.js"></script>
  
<?php echo "<script> imageFile = 'nextImageForSegmentation.php?id=$imageId'; imageId=$imageId;"; ?>

  $(document).ready(function() {
//	 console.log( "TEST" );
   $('button#moreRows').click(function() {changeRows(1);}) 
   $('button#lessRows').click(function() {changeRows(-1);})
   $('button#next').click(function() {
//	 console.log( "Sending data" );
	$.ajax({
	type: "GET",
	url: "grid_ajax.php",
	data: {'topleft':topleft, 'topright':topright, 'bottomleft':bottomleft, 'bottomright':bottomright, 'noRows':noRows, 'imageId':imageId}
	})
	.done(function( msg ) {
//	 console.log( "Data Saved: " + msg );
         location.reload();
	});
     
    });
   $('button#skip').click(function() {
	location.reload(); //skip
    });

  });
  

  function changeRows(delta) {
    n = $('input#numRows').val();
    n = parseInt(n) + delta;
    if (isNaN(n)) {n = 1;}
    if (n<1) { n = 1; }
    if (n>6) { n = 6; }
    $('input#numRows').val(n);
    noRows = n;
    recalcGrid()
  }

  function recalcGrid() {
     x1 = topleft[0];
     y1 = topleft[1];
     x2 = topright[0];
     y2 = topright[1];
     dx1 = (bottomleft[0]-topleft[0])/noRows;
     dy1 = (bottomleft[1]-topleft[1])/noRows;
     dx2 = (bottomright[0]-topright[0])/noRows;
     dy2 = (bottomright[1]-topright[1])/noRows;
     p = [];
     for (rowi=0;rowi<noRows+1;rowi++)
     {
       p=p.concat(["M",x1,y1,"L",x2,y2]);
       x1 = x1 + dx1;
       y1 = y1 + dy1;
       x2 = x2 + dx2;
       y2 = y2 + dy2;
     }
     
     x1 = topleft[0];
     y1 = topleft[1];
     x2 = bottomleft[0];
     y2 = bottomleft[1];
     dx1 = (topright[0]-topleft[0])/100;
     dy1 = (topright[1]-topleft[1])/100;
     dx2 = (bottomright[0]-bottomleft[0])/100;
     dy2 = (bottomright[1]-bottomleft[1])/100;
     for (coli=0;coli<noCols+1;coli++)
     {
       w = colWidths[coli];
       p=p.concat(["M",x1,y1,"L",x2,y2]);
       x1 = x1 + dx1*w;
       y1 = y1 + dy1*w;
       x2 = x2 + dx2*w;
       y2 = y2 + dy2*w;
     }
     lHorGrid.attr("path",p);
  }
  initCorners = [100,100,200,200];
  noRows = 1;
  noCols = 12;
  colWidths = [<?php print(implode(',',$widths)) ?>];

  radius = 20;
  topleft = [initCorners[0], initCorners[1]];
  topright = [initCorners[2], initCorners[1]];
  bottomleft = [initCorners[1], initCorners[2]];
  bottomright = [initCorners[2], initCorners[3]];
   window.onload = function () {
    R = Raphael(canvas_container, "667", "500");
    R.image(imageFile,0,0,640,480);
     lHorGrid = R.path([]);
     recalcGrid();
     cTopLeft = R.circle(topleft[0],topleft[1], radius).attr({fill: "hsb(0, 1, 1)", stroke: "none", opacity: .5}),
     cTopRight = R.circle(topright[0],topright[1], radius).attr({fill: "hsb(.3, 1, 1)", stroke: "none", opacity: .5}),
     cBottomLeft = R.circle(bottomleft[0],bottomleft[1], radius).attr({fill: "hsb(.6, 1, 1)", stroke: "none", opacity: .5}),
     cBottomRight = R.circle(bottomright[0],bottomright[1], radius).attr({fill: "hsb(.8, 1, 1)", stroke: "none", opacity: .5});
     
     
    var start = function () {
     this.ox = this.attr("cx");
     this.oy = this.attr("cy");
     this.animate({r: radius/3, opacity: .25}, 500, ">");
    },
    moveTopLeft = function (dx, dy) {
     this.attr({cx: this.ox + dx, cy: this.oy + dy});
     topleft[0] = this.ox + dx;
     topleft[1] = this.oy + dy;
     recalcGrid();
    },
     moveTopRight = function (dx, dy) {
     this.attr({cx: this.ox + dx, cy: this.oy + dy});
     topright[0] = this.ox + dx;
     topright[1] = this.oy + dy;
     recalcGrid();
    },
    moveBottomLeft = function (dx, dy) {
     this.attr({cx: this.ox + dx, cy: this.oy + dy});
     bottomleft[0] = this.ox + dx;
     bottomleft[1] = this.oy + dy;
     recalcGrid();
    },
    moveBottomRight = function (dx, dy) {
     this.attr({cx: this.ox + dx, cy: this.oy + dy});
     bottomright[0] = this.ox + dx;
     bottomright[1] = this.oy + dy;
     recalcGrid();
    },      
    up = function () {
     this.animate({r: radius, opacity: .5}, 500, ">");
    };
    R.set(cTopLeft).drag(moveTopLeft, start, up);
    R.set(cTopRight).drag(moveTopRight, start, up);
    R.set(cBottomLeft).drag(moveBottomLeft, start, up);
    R.set(cBottomRight).drag(moveBottomRight, start, up);
   };
  </script>
 </head>
 <body>
 <?php draw_header('Segmentation'); ?>
 <div style="width:600px; margin-left:30px;">
 <p>Drag the coloured circles to align the grid over the photographed table. It often fits best if you <em>don't include the far left and right columns</em>. Adjust the number of rows at the bottom using the + and - buttons. Once you are happy it's in the right place, with the right number of rows, press 'Next'.</p>
 </div>
 <div id="canvas_container" style="width:667px; height:500px;"></div>
 <div style="margin-top:15px;">
 <p>
 <span>
 Number of rows<button style="width:20px" id="lessRows">-</button><input type="text" size=1 value="1" style="width:20px;" disabled="disabled" id="numRows"></input><button style="width:20px" id="moreRows">+</button>
 </span>
 <span style="margin-right:10px; margin-left:300px;">
 <button style="width:100px; height:50px;" id="next">Next</button>
 <button style="width:100px; height:50px;" id="skip">Skip</button>
 </span>
 </p>
 </div>
 </body>
</html>
