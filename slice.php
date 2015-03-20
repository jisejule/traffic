<?php 
include 'settings.php';

$thresholdMin = 1; //number of assignments that have to be made before we try segmenting.

//order of coordinates: 0topleftx,1toplefty,2toprightx,3toprighty,4bottomleftx,5bottomlefty,6bottomrightx,7bottomrighty
//imagefile = name of image we're using
//coords = corner coordinates
//noRows = number of rows
//conn = db connection
//userid = user's id
//tablefile = 
function segment_image($imagefile,$coords,$noRows,$tablefile,$conn) {
     print("Creating image from JPEG ($imagefile)...\n<br/>");
     try {
       $src = imagecreatefromjpeg($imagefile); //TODO BEFORE segmenting check segments are inside image
     } catch (Exception $e) {
       echo 'Caught exception: ',  $e->getMessage(), "\n"; 
     }

     print("done");
     $x1 = $coords[0];
     $y1 = $coords[1];
     $x2 = $coords[2];
     $y2 = $coords[3];
     print("Slicing $imagefile using coords ($x1,$y1 x $x2,$y2) with $noRows rows\n.");
//     printf("($coords[0],$coords[1],$coords[4],$coords[5])\n", $x1,$y1,$x2,$y2);
  //   printf("\n---\n");

    // $noRows = 4;
     $margin = 10;
     $dx1 = ($coords[4]-$coords[0])/$noRows;
     $dy1 = ($coords[5]-$coords[1])/$noRows;
     $dx2 = ($coords[6]-$coords[2])/$noRows;
     $dy2 = ($coords[7]-$coords[3])/$noRows;

     for ($rowi=0;$rowi<$noRows;$rowi++)
     {       
       $colWidths = array(13.2,12.5,12.6,8.2,8.0,4.0,8.0,8.2,8.0,8.0,4.0,5.3);
       $tempx1 = $x1;
       $tempy1 = $y1;
       $nextx1 = $x1 + $dx1;
       $nexty1 = $y1 + $dy1;
       $nextx2 = $x2 + $dx2;
       $nexty2 = $y2 + $dy2;
       $tempnextx1 = $nextx1;
       $tempnexty1 = $nexty1;
       $coli = 0;
       foreach ($colWidths as $wid)
       {
         $wid = $wid/100;
        
         $tempnextx1 = $tempnextx1 + $wid * ($nextx2 - $nextx1);
         $tempnexty1 = $tempnexty1 + $wid * ($nexty2 - $nexty1);
         printf("(%0.0f, %0.0f) to (%0.0f, %0.0f)\n",$tempx1,$tempy1,$tempnextx1,$tempnexty1);
         $width = round($tempnextx1-$tempx1)+$margin*2;
         $height= round($tempnexty1-$tempy1)+$margin*2;
         $left = $tempx1-$margin;
         $top = $tempy1-$margin;
         $scale = 7.2/2; //the actual scale is 4608.0/460.0 (10x) but the images are rescaled in the browser to 640px across: 4608/640=7.2
         $width = $width * $scale;
         $height = $height * $scale;
         $left = $left * $scale;
         $top = $top * $scale;
         
         $dest = imagecreatetruecolor($width,$height);
         
         if (($coli!=1) && ($coli!=2)) { //if we're not looking at columns 1 or 2...
           $name = md5(mt_rand()); //uniqid();
           $sql = sprintf("INSERT INTO traffic_images (tablefile,name,row,col) VALUES (%d,'%s',%d,%d)",$tablefile,$name,$rowi,$coli);
           if ($conn->query($sql) !== TRUE) {print "Error inserting image.".$sql . $conn->error;}
         
           imagecopy($dest, $src, 0, 0, $left, $top, $width, $height);
           imagejpeg($dest, "segmented/$name.jpg");
         }
         imagedestroy($dest);
         
         $tempx1 = $tempx1 + $wid * ($x2 - $x1);
         $tempy1 = $tempy1 + $wid * ($y2 - $y1);
         
         $coli = $coli + 1;
       }
  //     printf("---\n");
       $x1 = $x1 + $dx1;
       $y1 = $y1 + $dy1;
       $x2 = $x2 + $dx2;
       $y2 = $y2 + $dy2;

     }
     imagedestroy($src);
}

function calculate_median($arr) {
    sort($arr);
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}

function calculate_mode($arr) {
  $values = array_count_values($arr); 
  $mode = array_search(max($values), $values);
  return $mode;
}

$conn = new mysqli("localhost",$db_username,$db_password,$db_name);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

//$tablefile_res = $conn->query("SELECT distinct(tablefile) AS tablefile FROM traffic_tablecorners;");
$tablefile_res = $conn->query("SELECT distinct(traffic_tablecorners.tablefile) AS tablefile FROM traffic_tablecorners JOIN traffic_tablefiles ON traffic_tablecorners.tablefile=traffic_tablefiles.tablefile WHERE traffic_tablefiles.segmented=false;");
print "Segmenting...\n";
while($tablefile_row = $tablefile_res->fetch_assoc()) {
  $tablefile = $tablefile_row['tablefile'];
  print "Image #$tablefile\n";
//note: could do averaging in the sql but want opportunity to improve average in future...
  $sql = sprintf("SELECT topleftx,toplefty,toprightx,toprighty,bottomleftx,bottomlefty,bottomrightx,bottomrighty,traffic_tablecorners.tablefile AS tablefile,rows,filename FROM traffic_tablecorners JOIN traffic_tablefiles ON traffic_tablecorners.tablefile=traffic_tablefiles.tablefile WHERE traffic_tablecorners.tablefile = %d",$tablefile);
  $res = $conn->query($sql);

//list of names of columns we're getting from the SQL
  $coord_labels = array('topleftx','toplefty','toprightx','toprighty','bottomleftx','bottomlefty','bottomrightx','bottomrighty');
  $coords = array(); //array to hold corner coordinates in
  $row_counts = array(); //reported number of rows for each submission
  for ($coordi=0;$coordi<8;$coordi++) { array_push($coords,array()); }
  $N=0;
  while($row = $res->fetch_assoc()) {
    $N++;
    for ($coordi=0;$coordi<8;$coordi++) { 
      array_push($coords[$coordi],$row[$coord_labels[$coordi]]); 
    }
    array_push($row_counts,$row['rows']); 
    $filename=$row['filename'];  //this should be the same for each row!   
  }
  print "$N samples\n";
  if ($N<$thresholdMin) { print "Not enough samples\n"; continue; } //if there aren't enough samples, continue.
  $mean_coords = array();
  foreach ($coords as $coord)
  {
    array_push($mean_coords,calculate_median($coord));
  }
  $num_rows = calculate_mode($row_counts);
  segment_image('data/'.$filename,$mean_coords,$num_rows,$tablefile,$conn);
  $conn->query(sprintf("UPDATE traffic_tablefiles SET segmented=true WHERE tablefile=%d;",$tablefile));
}
?>
