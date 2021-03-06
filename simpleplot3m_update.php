<?php
# PHPlot Demo
# 2009-12-01 ljb
# For more information see http://sourceforge.net/projects/phplot/

# Load the PHPlot class library:
require 'sysconfig.inc.php';
require_once 'phplot/phplot.php';

/** $sql_text = 'SELECT
	p.periode as \'1\',
	CONCAT(MONTHNAME(p.finaldate),\' \',YEAR(p.finaldate)) as \'2\',
	sum(c.c11) AS \'3\',
	sum(c.c12) AS \'4\',
	sum(c.c13) AS \'5\',
	sum(c.c14) AS \'6\',
	sum(c.c21) AS \'7\',
	sum(c.c22) AS \'8\',
	sum(c.c3) AS \'9\'
FROM periode as p
LEFT JOIN `non_coa` as n ON n.idperiode = p.idperiode 
LEFT JOIN coa as c ON c.idperiode = p.idperiode
LEFT JOIN shu as s ON s.idperiode = p.idperiode
GROUP BY YEAR(p.finaldate),MONTH(p.finaldate)
ORDER BY p.finaldate ASC';
**/
$sql_text = 'SELECT
	MIN(TIMESTAMPDIFF(MONTH,h.periode,curdate())) as \'1\',
	CONCAT(MONTHNAME(h.periode),\' \',YEAR(h.periode)) as \'2\',
	format(sum((h.h1))/1e6,2) AS \'3\',
	format(sum((h.h2))/1e6,2) AS \'4\',
	format(sum((h.h3))/1e6,2) AS \'5\',
	format(sum((h.h4))/1e6,2) AS \'6\',
	format(sum((h.h5))/1e6,2) AS \'7\',
	format(sum((h.h6))/1e6,2) AS \'8\',
	format(avg((h.h7))/1e6,2) AS \'9\',
	format(avg((h.h8)),2) AS \'10\',
	format(avg((h.h9)),2) AS \'11\',
	format(avg((h.h10)),2) AS \'12\'
FROM `koperasi` as k
RIGHT JOIN harian as h ON h.idkoperasi = k.idkoperasi
LEFT JOIN `tipe_koperasi` as tk ON k.jenis = tk.idtipe_koperasi
GROUP BY YEAR(h.periode),MONTH(h.periode)
ORDER BY h.periode DESC
LIMIT 0,5';


$xdata = array();
$xlegend = array();
$arrseries = array();
$arrlegend = array();

$arrseries['0'][]='Simpanan';
$arrseries['1'][]='Pinjaman';
$arrseries['2'][]='Modal Dalam';
$arrseries['3'][]='Modal Luar';
$arrseries['4'][]='Volume Usaha';
$arrseries['5'][]='Aset';
$arrseries['6'][]='SHU';
$arrseries['7'][]='Bunga Simpanan';
$arrseries['8'][]='Bunga Pinjaman';
$arrseries['9'][]='NPL';
 
$set_yearly = $dbs->query($sql_text);
while ($rec = $set_yearly->fetch_assoc()) {
 $arrlegend[] = $rec['2'];
 $arrseries['0'][]=$rec['3'];
 $arrseries['1'][]=$rec['4'];
 $arrseries['2'][]=$rec['5'];
 $arrseries['3'][]=$rec['6'];
 $arrseries['4'][]=$rec['7'];
 $arrseries['5'][]=$rec['8'];
 $arrseries['6'][]=$rec['9'];
 $arrseries['7'][]=$rec['10'];
 $arrseries['8'][]=$rec['11'];
 $arrseries['9'][]=$rec['12'];
 }

 $xdata = $arrseries;
 nl2br(print_r ($arrseries));
 die();

# Create a PHPlot object which will make an 800x400 pixel image:
 $p = new PHPlot(900, 400);

# Use TrueType fonts:
//$p->SetDefaultTTFont('./arial.ttf');

# Set the main plot title:
$p->SetTitle('Data Bulanan Koperasi Indonesia');

# Select the data array representation and store the data:
$p->SetDataType('text-data');
$p->SetDataValues($xdata);

# Select the plot type - bar chart:
$p->SetPlotType('bars');

# Define the data range. PHPlot can do this automatically, but not as well.
//$p->SetPlotAreaWorld(0, 0, 9, 100);

# Select an overall image background color and another color under the plot:
$p->SetBackgroundColor('#ffffcc');
$p->SetDrawPlotAreaBackground(True);
$p->SetPlotBgColor('#ffffff');

# Draw lines on all 4 sides of the plot:
$p->SetPlotBorderType('full');

# Set a 3 line legend, and position it in the upper left corner:
$p->SetLegend($arrlegend);
//$p->SetLegendWorld(0.1, 95);

# Turn data labels on, and all ticks and tick labels off:
$p->SetDrawXGrid(true);
//$p->SetXDataLabelPos('plotdown');
//$p->SetXTickPos('none');
//$p->SetXTickLabelPos('none');
//$p->SetYTickPos('none');
//$p->SetYTickLabelPos('none');

# Generate and output the graph now:
$p->DrawGraph();
