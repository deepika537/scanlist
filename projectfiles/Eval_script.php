<?php
$date = (date ("Y-m-d")); //today
$time = (date ("H-i")); //current time
set_time_limit(0); //set time limit - reports may take long time
$host = '0.0.0.0/3050:D:/Quantum/Database/Quantum.FDB';  //!!!!!here you need to specify your database path.
$username='';   //!!!!!username for database
$password='';   //!!!!!password for database user
$stmt="select distinct P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY from PARTS_MASTER P join Stock S on S. PNM_AUTO_KEY=P.PNM_AUTO_KEY where  S.qty_oh > 0 and S.HISTORICAL_FLAG = 'F'"; //here you need to define your query to database. It can be also view.
include "PHPExcel-1.8/Classes/PHPExcel.php";
ini_set("memory_limit","500M");
$dbh = ibase_connect ( $host, $username, $password ) or die ("error in db connect");
$query = ibase_prepare($stmt);
$result=ibase_execute($query);
// Instantiate a new PHPExcel object
$objPHPExcel = new PHPExcel();
// Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', "Num of parts");
$objPHPExcel->getActiveSheet()->SetCellValue('B1', "PN");
$objPHPExcel->getActiveSheet()->SetCellValue('C1', "AUTO_KEY");
$objPHPExcel->getActiveSheet()->SetCellValue('D1', "CONDITION_CODE");
$objPHPExcel->getActiveSheet()->SetCellValue('E1', "QTY_OH");
$objPHPExcel->getActiveSheet()->SetCellValue('F1', "SO_COUNT");
$objPHPExcel->getActiveSheet()->SetCellValue('G1', "CQ_COUNT");
$objPHPExcel->getActiveSheet()->SetCellValue('H1', "VQ_COUNT");
$objPHPExcel->getActiveSheet()->SetCellValue('I1', "PO_COUNT");
$objPHPExcel->getActiveSheet()->SetCellValue('J1', "QTY_INVOICED");
$objPHPExcel->getActiveSheet()->SetCellValue('K1', "CQ_QTY_QUOTED");
$objPHPExcel->getActiveSheet()->SetCellValue('L1', "VQ_QTY_QUOTED");
$objPHPExcel->getActiveSheet()->SetCellValue('M1', "QTY_ORDERED");
$objPHPExcel->getActiveSheet()->SetCellValue('N1', "SO_TOTAL");
$objPHPExcel->getActiveSheet()->SetCellValue('O1', "CQ_TOTAL");
$objPHPExcel->getActiveSheet()->SetCellValue('P1', "VQ_TOTAL");
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', "PO_TOTAL");
$objPHPExcel->getActiveSheet()->SetCellValue('R1', "MAX_SO_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('S1', "MAX_CQ_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('T1', "MAX_VQ_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('U1', "MAX_PO_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('V1', "MIN_SO_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('W1', "MIN_CQ_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('X1', "MIN_VQ_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('Y1', "MIN_PO_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('Z1', "AVG_SO_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('AA1', "AVG_CQ_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('AB1', "AVG_VQ_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('AC1', "AVG_PO_PRICE");
$objPHPExcel->getActiveSheet()->SetCellValue('AD1', "SO_Median");
$objPHPExcel->getActiveSheet()->SetCellValue('AE1', "CQ_Median");
$objPHPExcel->getActiveSheet()->SetCellValue('AF1', "VQ_Median");
$objPHPExcel->getActiveSheet()->SetCellValue('AG1', "PO_Median");
$objPHPExcel->getActiveSheet()->SetCellValue('AH1', "SO_STD");
$objPHPExcel->getActiveSheet()->SetCellValue('AI1', "CQ_STD");
$objPHPExcel->getActiveSheet()->SetCellValue('AJ1', "VQ_STD");
$objPHPExcel->getActiveSheet()->SetCellValue('AK1', "PO_STD");
$objPHPExcel->getActiveSheet()->SetCellValue('AL1', "SO_COUNT/CQ_COUNT");
$objPHPExcel->getActiveSheet()->SetCellValue('AM1', "QTY_INVOICED/CQ_QTY_QUOTED");
$objPHPExcel->getActiveSheet()->SetCellValue('AN1', "SO_AVG/VQ_AVG");
$objPHPExcel->getActiveSheet()->SetCellValue('AO1', "SO_AVG/CQ_AVG");
$objPHPExcel->getActiveSheet()->SetCellValue('AP1', "CQ_AVG/SO_AVG");
$objPHPExcel->getActiveSheet()->SetCellValue('AQ1', "Alternate_PN");
// Initialise the Excel row number
$rowCount = 2;//$rowCount1 = 3;
// Iterate through each result from the SQL query in turn
// We fetch each database result row into $row in turn
$al=[];$b=[];$i=0;
function medi($arr){
 if($arr){
     $cou = count($arr);
     //$mid = floor($cou/2);
     if($cou%2==0)
      return ($arr[$cou/2]+$arr[$cou/2-1])/2;
    else
       return $arr[$cou/2];
 }
 return 0;
}
function std($arr){
if($arr){
    $avg = array_sum($arr)/count($arr);
    for($j=0;$j<count($arr);$j++)
    {
      $sd1+=pow(($arr[$j]-$avg),2);
    }
    return sqrt($sd1/count($arr));
}
return 0;
}
while($row = ibase_fetch_assoc($result)){
    $a[$i]=$row["MASTER_PN"];
    $b[$i]=$row["AUTO_KEY"];
    $i++;
  }
  for($x=0;$x<sizeof($b);$x++)
   {
    $c=array();$rows = array();$so_price1=array();$so_price2=array();$po_price1=array();$po_price2=array();$cq_price1=array();$cq_price2=array();$vq_price1=array();$vq_price2=array();
    $qty_oh1=0;$qty_oh2=0;
    $Qty_Invoiced1=0.0;$So_Total1=0.0;$So_Count1=0.0;
		$Qty_Invoiced2=0.0;$So_Total2=0.0;$So_Count2=0.0;
    $Po_Total1=0.0;$Po_Count1=0.0;$Qty_Ordered1=0.0;
    $Po_Total2=0.0;$Po_Count2=0.0;$Qty_Ordered2=0.0;
    $Cq_Total1=0.0;$Cq_Count1=0.0;$Qty_Quoted1=0.0;
    $Cq_Total2=0.0;$Cq_Count2=0.0;$Qty_Quoted2=0.0;
    $Vq_Total1=0.0;$Vq_Count1=0.0;$Qty_Quoted3=0.0;
    $Vq_Total2=0.0;$Vq_Count2=0.0;$Qty_Quoted4=0.0;
    array_push($c,$b[$x]);
    for($y=0;$y<sizeof($c);$y++)
    {
    $stmt1="select distinct P.PN as Master_PN ,P.PNM_AUTO_KEY as auto_key,P1.PN as Alternate_PN,P1.PNM_AUTO_KEY as al_auto_key FROM PARTS_MASTER P left join ALTERNATES_PARTS_MASTER AP on P.PNM_AUTO_KEY=AP.PNM_AUTO_KEY left join PARTS_MASTER P1 ON AP.ALT_PNM_AUTO_KEY =P1.PNM_AUTO_KEY where P.PNM_AUTO_KEY=".$c[$y];
    $query1 = ibase_prepare($stmt1);
    $result1=ibase_execute($query1);
    while($row1 = ibase_fetch_assoc($result1)){
      if(!in_array($row1["AL_AUTO_KEY"],$c)&&$row1["ALTERNATE_PN"]!=null)
        {array_push($rows, $row1["ALTERNATE_PN"]);
        array_push($c,$row1["AL_AUTO_KEY"]);
       }
      }
      $stmt2="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,SUM(S.qty_oh) as QTY_OH FROM PARTS_MASTER P "
  			."left join STOCK S on P.PNM_AUTO_KEY=S.PNM_AUTO_KEY "
  			."left join PART_CONDITION_CODES PC on S.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where S.qty_oh > 0 and S.HISTORICAL_FLAG = 'F' and P.PNM_AUTO_KEY=".$c[$y]." group by P.PN,P.PNM_AUTO_KEY,PC.CONDITION_CODE";
      $query2 = ibase_prepare($stmt2);
      $result2=ibase_execute($query2);
      while($row2 = ibase_fetch_assoc($result2)){
        if($row2["CONDITION_CODE"]!=null)
        {if($row2["CONDITION_CODE"]=='NE'||$row2["CONDITION_CODE"]=='NS'||$row2["CONDITION_CODE"]=='FN')
         {$qty_oh1+=$row2["QTY_OH"];}
         else if($row2["CONDITION_CODE"]=='OH'||$row2["CONDITION_CODE"]=='SV'||$row2["CONDITION_CODE"]=='RP'||$row2["CONDITION_CODE"]=='US'||$row2["CONDITION_CODE"]=='AR')
         {$qty_oh2+=$row2["QTY_OH"];}
       }
     }
     $stmt3="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,SUM(SO.qty_invoiced) as QTY_INVOICED,(SUM(SO.qty_invoiced*SO.unit_price)) as SO_TOTAL,COUNT(distinct SH.SO_NUMBER) AS SO_COUNT,SO.UNIT_PRICE as UNIT_PRICE FROM PARTS_MASTER P "
			."left join SO_DETAIL SO on P.PNM_AUTO_KEY=SO.PNM_AUTO_KEY "
      ."join SO_HEADER SH on SO.SOH_AUTO_KEY=SH.SOH_AUTO_KEY "
			."left join PART_CONDITION_CODES PC on SO.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where SO.UNIT_PRICE>1 and P.PNM_AUTO_KEY=".$c[$y]." group by P.PN,P.PNM_AUTO_KEY,PC.CONDITION_CODE,SO.UNIT_PRICE";
     $query3 = ibase_prepare($stmt3);
     $result3=ibase_execute($query3);
     while($row3 = ibase_fetch_assoc($result3)){
       if($row3["CONDITION_CODE"]!=null)
       {if($row3["CONDITION_CODE"]=='NE'||$row3["CONDITION_CODE"]=='NS'||$row3["CONDITION_CODE"]=='FN')
        {
         $Qty_Invoiced1+=$row3["QTY_INVOICED"];
				 $So_Total1+=$row3["SO_TOTAL"];
				 $So_Count1+=$row3["SO_COUNT"];
       }
        else if($row3["CONDITION_CODE"]=='OH'||$row3["CONDITION_CODE"]=='SV'||$row3["CONDITION_CODE"]=='RP'||$row3["CONDITION_CODE"]=='US'||$row3["CONDITION_CODE"]=='AR')
        {$Qty_Invoiced2+=$row3["QTY_INVOICED"];
        $So_Total2+=$row3["SO_TOTAL"];
        $So_Count2+=$row3["SO_COUNT"];
      }
      }
    }
    $stmt4="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,(SO.UNIT_PRICE) as UNIT_PRICE FROM PARTS_MASTER P "
			."left join SO_DETAIL SO on P.PNM_AUTO_KEY=SO.PNM_AUTO_KEY "
      ."join SO_HEADER SH on SO.SOH_AUTO_KEY=SH.SOH_AUTO_KEY "
			."left join PART_CONDITION_CODES PC on SO.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where SO.UNIT_PRICE>1 and P.PNM_AUTO_KEY=".$c[$y]." order by SO.UNIT_PRICE";
    $query4 = ibase_prepare($stmt4);
    $result4=ibase_execute($query4);
    while($row4 = ibase_fetch_assoc($result4)){
      if($row4["CONDITION_CODE"]!=null)
      {if($row4["CONDITION_CODE"]=='NE'||$row4["CONDITION_CODE"]=='NS'||$row4["CONDITION_CODE"]=='FN')
       {
        array_push($so_price1,$row4["UNIT_PRICE"]);
      }
       else if($row4["CONDITION_CODE"]=='OH'||$row4["CONDITION_CODE"]=='SV'||$row4["CONDITION_CODE"]=='RP'||$row4["CONDITION_CODE"]=='US'||$row4["CONDITION_CODE"]=='AR')
       {
       array_push($so_price2,$row4["UNIT_PRICE"]);
     }
     }
   }
   $stmt5="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,SUM(PO.qty_ordered) as QTY_ORDERED,(SUM(PO.qty_ordered*PO.VENDOR_PRICE)) as PO_TOTAL,COUNT(distinct PH.PO_NUMBER) AS PO_COUNT FROM PARTS_MASTER P "
				."left join PO_DETAIL PO on P.PNM_AUTO_KEY=PO.PNM_AUTO_KEY "
        ."join PO_HEADER PH on PO.POH_AUTO_KEY=PH.POH_AUTO_KEY "
				."left join PART_CONDITION_CODES PC on PO.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where PO.VENDOR_PRICE>1 and P.PNM_AUTO_KEY=".$c[$y]." group by P.PN,P.PNM_AUTO_KEY,PC.CONDITION_CODE";
     $query5 = ibase_prepare($stmt5);
     $result5=ibase_execute($query5);
     while($row5 = ibase_fetch_assoc($result5)){
       if($row5["CONDITION_CODE"]!=null)
       {if($row5["CONDITION_CODE"]=='NE'||$row5["CONDITION_CODE"]=='NS'||$row5["CONDITION_CODE"]=='FN')
        {
         $Qty_Ordered1+=$row5["QTY_ORDERED"];
				 $Po_Total1+=$row5["PO_TOTAL"];
				 $Po_Count1+=$row5["PO_COUNT"];
       }
        else if($row5["CONDITION_CODE"]=='OH'||$row5["CONDITION_CODE"]=='SV'||$row5["CONDITION_CODE"]=='RP'||$row5["CONDITION_CODE"]=='US'||$row5["CONDITION_CODE"]=='AR')
        {$Qty_Ordered2+=$row5["QTY_ORDERED"];
        $Po_Total2+=$row5["PO_TOTAL"];
        $Po_Count2+=$row5["PO_COUNT"];
      }
      }
    }
    $stmt6="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,(PO.VENDOR_PRICE) as VENDOR_PRICE FROM PARTS_MASTER P "
			."left join PO_DETAIL PO on P.PNM_AUTO_KEY=PO.PNM_AUTO_KEY "
      ."join PO_HEADER PH on PO.POH_AUTO_KEY=PH.POH_AUTO_KEY "
			."left join PART_CONDITION_CODES PC on PO.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where PO.VENDOR_PRICE>1 and P.PNM_AUTO_KEY=".$c[$y]." order by PO.VENDOR_PRICE";
    $query6 = ibase_prepare($stmt6);
    $result6=ibase_execute($query6);
    while($row6 = ibase_fetch_assoc($result6)){
      if($row6["CONDITION_CODE"]!=null)
      {if($row6["CONDITION_CODE"]=='NE'||$row6["CONDITION_CODE"]=='NS'||$row6["CONDITION_CODE"]=='FN')
       {
        array_push($po_price1,$row6["VENDOR_PRICE"]);
      }
       else if($row6["CONDITION_CODE"]=='OH'||$row6["CONDITION_CODE"]=='SV'||$row6["CONDITION_CODE"]=='RP'||$row6["CONDITION_CODE"]=='US'||$row6["CONDITION_CODE"]=='AR')
       {
       array_push($po_price2,$row6["VENDOR_PRICE"]);
     }
     }
   }
   $stmt7="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,SUM(CQ.QTY_QUOTED) as Qty_QUOTED,(SUM(CQ.QTY_QUOTED*CQ.UNIT_PRICE)) as CQ_TOTAL,COUNT(distinct CH.CQH_AUTO_KEY) AS CQ_COUNT FROM PARTS_MASTER P "
										."left join CQ_DETAIL CQ on P.PNM_AUTO_KEY=CQ.PNM_AUTO_KEY "
                    ."join CQ_HEADER CH on CQ.CQH_AUTO_KEY=CH.CQH_AUTO_KEY "
										."left join PART_CONDITION_CODES PC on CQ.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where CQ.UNIT_PRICE>1 and P.PNM_AUTO_KEY=".$c[$y]." group by P.PN,P.PNM_AUTO_KEY,PC.CONDITION_CODE";
     $query7 = ibase_prepare($stmt7);
     $result7=ibase_execute($query7);
     while($row7 = ibase_fetch_assoc($result7)){
       if($row7["CONDITION_CODE"]!=null)
       {if($row7["CONDITION_CODE"]=='NE'||$row7["CONDITION_CODE"]=='NS'||$row7["CONDITION_CODE"]=='FN')
        {
         $Qty_Quoted1+=$row7["QTY_QUOTED"];
				 $Cq_Total1+=$row7["CQ_TOTAL"];
				 $Cq_Count1+=$row7["CQ_COUNT"];
       }
        else if($row7["CONDITION_CODE"]=='OH'||$row7["CONDITION_CODE"]=='SV'||$row7["CONDITION_CODE"]=='RP'||$row7["CONDITION_CODE"]=='US'||$row7["CONDITION_CODE"]=='AR')
        {$Qty_Quoted2+=$row7["QTY_QUOTED"];
        $Cq_Total2+=$row7["CQ_TOTAL"];
        $Cq_Count2+=$row7["CQ_COUNT"];
      }
      }
    }
    $stmt8="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,(CQ.UNIT_PRICE) as UNIT_PRICE FROM PARTS_MASTER P "
										."left join CQ_DETAIL CQ on P.PNM_AUTO_KEY=CQ.PNM_AUTO_KEY "
                    ."join CQ_HEADER CH on CQ.CQH_AUTO_KEY=CH.CQH_AUTO_KEY "
										."left join PART_CONDITION_CODES PC on CQ.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where CQ.UNIT_PRICE>1 and P.PNM_AUTO_KEY=".$c[$y]." order by CQ.UNIT_PRICE";
    $query8 = ibase_prepare($stmt8);
    $result8=ibase_execute($query8);
    while($row8 = ibase_fetch_assoc($result8)){
      if($row8["CONDITION_CODE"]!=null)
      {if($row8["CONDITION_CODE"]=='NE'||$row8["CONDITION_CODE"]=='NS'||$row8["CONDITION_CODE"]=='FN')
       {
        array_push($cq_price1,$row8["UNIT_PRICE"]);
      }
       else if($row8["CONDITION_CODE"]=='OH'||$row8["CONDITION_CODE"]=='SV'||$row8["CONDITION_CODE"]=='RP'||$row8["CONDITION_CODE"]=='US'||$row8["CONDITION_CODE"]=='AR')
       {
       array_push($cq_price2,$row8["UNIT_PRICE"]);
     }
     }
   }
   $stmt9="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,PC.CONDITION_CODE as CONDITION_CODE,SUM(VQ.QTY_QUOTED) as QTY_QUOTED,(SUM(VQ.QTY_QUOTED*VQ.UNIT_COST)) as VQ_TOTAL,COUNT(distinct VQ.VQ_NUMBER) AS VQ_COUNT FROM PARTS_MASTER P "
			."left join VQ_DETAIL VQ on P.PNM_AUTO_KEY=VQ.PNM_AUTO_KEY "
			."left join PART_CONDITION_CODES PC on VQ.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where VQ.UNIT_COST>1 and P.PNM_AUTO_KEY=".$c[$y]." group by P.PN,P.PNM_AUTO_KEY,PC.CONDITION_CODE";
    $query9 = ibase_prepare($stmt9);
    $result9=ibase_execute($query9);
    while($row9 = ibase_fetch_assoc($result9)){
      if($row9["CONDITION_CODE"]!=null)
      {if($row9["CONDITION_CODE"]=='NE'||$row9["CONDITION_CODE"]=='NS'||$row9["CONDITION_CODE"]=='FN')
       {
        $Qty_Quoted3+=$row9["QTY_QUOTED"];
        $Vq_Total1+=$row9["VQ_TOTAL"];
        $Vq_Count1+=$row9["VQ_COUNT"];
      }
       else if($row9["CONDITION_CODE"]=='OH'||$row9["CONDITION_CODE"]=='SV'||$row9["CONDITION_CODE"]=='RP'||$row9["CONDITION_CODE"]=='US'||$row9["CONDITION_CODE"]=='AR')
       {$Qty_Quoted4+=$row9["QTY_QUOTED"];
       $Vq_Total2+=$row9["VQ_TOTAL"];
       $Vq_Count2+=$row9["VQ_COUNT"];
     }
     }
   }
   $stmt10="select P.PN as Master_PN,P.PNM_AUTO_KEY as AUTO_KEY,(VQ.UNIT_COST) as UNIT_COST,PC.CONDITION_CODE as CONDITION_CODE FROM PARTS_MASTER P "
			."left join VQ_DETAIL VQ on P.PNM_AUTO_KEY=VQ.PNM_AUTO_KEY "
			."left join PART_CONDITION_CODES PC on VQ.PCC_AUTO_KEY = PC.PCC_AUTO_KEY where VQ.UNIT_COST>1 and P.PNM_AUTO_KEY=".$c[$y]." order by VQ.UNIT_COST";
   $query10 = ibase_prepare($stmt10);
   $result10=ibase_execute($query10);
   while($row10 = ibase_fetch_assoc($result10)){
     if($row10["CONDITION_CODE"]!=null)
     {if($row10["CONDITION_CODE"]=='NE'||$row10["CONDITION_CODE"]=='NS'||$row10["CONDITION_CODE"]=='FN')
      {
       array_push($vq_price1,$row10["UNIT_COST"]);
     }
      else if($row10["CONDITION_CODE"]=='OH'||$row10["CONDITION_CODE"]=='SV'||$row10["CONDITION_CODE"]=='RP'||$row10["CONDITION_CODE"]=='US'||$row10["CONDITION_CODE"]=='AR')
      {
      array_push($vq_price2,$row10["UNIT_COST"]);
    }
    }
  }
   }
if($Qty_Invoiced1>=1&&$Qty_Quoted1>=1&&$Qty_Quoted3>=1)
  {$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $rowCount-1);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $a[$x]);
  $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $b[$x]);
  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "NS");
  $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$qty_oh1);
  $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$So_Count1);
  $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$Cq_Count1);
  $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$Vq_Count1);
  $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$Po_Count1);
  $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$Qty_Invoiced1);
  $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$Qty_Quoted1);
  $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,$Qty_Quoted3);
  $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,$Qty_Ordered1);
  $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$So_Total1);
  $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,$Cq_Total1);
  $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,$Vq_Total1);
  $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,$Po_Total1);
  $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount,($so_price1)?max($so_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount,($cq_price1)?max($cq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount,($vq_price1)?max($vq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount,($po_price1)?max($po_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount,($so_price1)?min($so_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount,($cq_price1)?min($cq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount,($vq_price1)?min($vq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount,($po_price1)?min($po_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount,($so_price1)?array_sum($so_price1)/count($so_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount,($cq_price1)?array_sum($cq_price1)/count($cq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount,($vq_price1)?array_sum($vq_price1)/count($vq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount,($po_price1)?array_sum($po_price1)/count($po_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount,($so_price1)?medi($so_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount,($cq_price1)?medi($cq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount,($vq_price1)?medi($vq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount,($po_price1)?medi($po_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount,($so_price1)?std($so_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount,($cq_price1)?std($cq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount,($vq_price1)?std($vq_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount,($po_price1)?std($po_price1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount,($Cq_Count1)?($So_Count1/$Cq_Count1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount,($Qty_Quoted1)?($Qty_Invoiced1/$Qty_Quoted1):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AN'.$rowCount,('=AB'.$rowCount)?(('=Z'.$rowCount.'/AB'.$rowCount)):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AO'.$rowCount,('=AA'.$rowCount)?(('=Z'.$rowCount.'/AA'.$rowCount)):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AP'.$rowCount,('=Z'.$rowCount)?(('=AA'.$rowCount.'/Z'.$rowCount)):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AQ'.$rowCount,json_encode($rows));
  $rowCount=$rowCount+1;
}
if($Qty_Invoiced2>=1&&$Qty_Quoted2>=1&&$Qty_Quoted4>=1)
  {$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $rowCount-1);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $a[$x]);
  $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $b[$x]);
  $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "AR");
  $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$qty_oh2);
  $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$So_Count2);
  $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$Cq_Count2);
  $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$Vq_Count2);
  $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$Po_Count2);
  $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$Qty_Invoiced2);
  $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$Qty_Quoted2);
  $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,$Qty_Quoted4);
  $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,$Qty_Ordered2);
  $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$So_Total2);
  $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,$Cq_Total2);
  $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,$Vq_Total2);
  $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,$Po_Total2);
  $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount,($so_price2)?max($so_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount,($cq_price2)?max($cq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount,($vq_price2)?max($vq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount,($po_price2)?max($po_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount,($so_price2)?min($so_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount,($cq_price2)?min($cq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount,($vq_price2)?min($vq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount,($po_price2)?min($po_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount,($so_price2)?array_sum($so_price2)/count($so_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount,($cq_price2)?array_sum($cq_price2)/count($cq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount,($vq_price2)?array_sum($vq_price2)/count($vq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount,($po_price2)?array_sum($po_price2)/count($po_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount,($so_price2)?medi($so_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount,($cq_price2)?medi($cq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount,($vq_price2)?medi($vq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount,($po_price2)?medi($po_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount,($so_price2)?std($so_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount,($cq_price2)?std($cq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount,($vq_price2)?std($vq_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount,($po_price2)?std($po_price2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount,($Cq_Count2)?($So_Count2/$Cq_Count2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount,($Qty_Quoted2)?($Qty_Invoiced2/$Qty_Quoted2):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AN'.$rowCount,('=AB'.$rowCount)?(('=Z'.$rowCount.'/AB'.$rowCount)):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AO'.$rowCount,('=AA'.$rowCount)?(('=Z'.$rowCount.'/AA'.$rowCount)):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AP'.$rowCount,('=Z'.$rowCount)?(('=AA'.$rowCount.'/Z'.$rowCount)):0);
  $objPHPExcel->getActiveSheet()->SetCellValue('AQ'.$rowCount,json_encode($rows));
  $rowCount=$rowCount+1;
}


    // Increment the Excel row counter

    //$rowCount1=$rowCount1+2;
}
ibase_close($dbh);

/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$select.$date."x".$time.'.xlsx"'); //using query name, current date and time as file name
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');*/
// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
// Write the Excel file to filename some_excel_file.xlsx in the current directory
$filePath='files/'.$date."x".$time.'.xlsx';
$objWriter->save($filePath);
require_once "google_api/google-api-php-client/src/Google_Client.php";

require_once "google_api/google-api-php-client/src/contrib/Google_DriveService.php";

require_once "google_api/google-api-php-client/src/contrib/Google_Oauth2Service.php";

require_once "google_api/vendor/autoload.php";


$DRIVE_SCOPE = 'https://www.googleapis.com/auth/drive';
$SERVICE_ACCOUNT_EMAIL = '';
$SERVICE_ACCOUNT_PKCS12_FILE_PATH = '';

function buildService() {//function for first build up service
global $DRIVE_SCOPE, $SERVICE_ACCOUNT_EMAIL, $SERVICE_ACCOUNT_PKCS12_FILE_PATH;

  $key = file_get_contents($SERVICE_ACCOUNT_PKCS12_FILE_PATH);
  $auth = new Google_AssertionCredentials(
      $SERVICE_ACCOUNT_EMAIL,
      array($DRIVE_SCOPE),
      $key);
  $client = new Google_Client();
  $client->setUseObjects(true);
  $client->setAssertionCredentials($auth);
  return new Google_DriveService($client);
}

function insertFile($service, $title, $description, $parentId, $mimeType, $filename) {//function for insert a file

  $file = new Google_DriveFile();
  $file->setTitle($title);
  $file->setDescription($description);
  $file->setMimeType($mimeType);

  // Set the parent folder.
  if ($parentId != null) {
    $parent = new Google_ParentReference();
    $parent->setId($parentId);
    $file->setParents(array($parent));
  }

  try {
    $data = file_get_contents($filename);

    $createdFile = $service->files->insert($file, array(
      'data' => $data,
      'mimeType' => $mimeType,
    ));


//set the file with MIME
$permission = new Google_Permission();
$permission->setRole( 'writer' );
$permission->setType( 'anyone' );
$permission->setValue( 'me' );
$service->permissions->insert( $createdFile->getId(), $permission );

//insert permission for the file



    return $createdFile;
  } catch (Exception $e) {
print "An error occurred1: " . $e->getMessage();
  }
}

try {


$root_id='';

$service=buildService();

$title="Eval_file-".$date."x".$time;
$description='';
$parentId=$root_id;
//$file="2017-07-25x14-55.xlsx";
$mimeType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";//For Excel File
$filename=$filePath;
$parentId=insertFile($service, $title, $description, $parentId, $mimeType, $filename);


  } catch (Exception $e) {
  print "An error occurred1: " . $e->getMessage();
}
exit;
?>
