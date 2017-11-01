<?php
#http://www.webslesson.info/2016/02/live-table-add-edit-delete-using-ajax-jquery-in-php-mysql.html
ob_start();
session_start();
 require_once 'dbconnect.php';
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }
 // select loggedin users detail
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user'],$conn);
 $userRow=mysql_fetch_array($res);
 ?>
 <div>
 <ul style="list-style-type: none;float:right;padding-right:50px;padding-top:10px;">
   <li style="color:#216288"><img src="resources/usericon.png" alt="User" height="15" width="15"> <b><?php echo $userRow['userName']; ?>&nbsp;&nbsp;</b></li>
   <li><a href="Logout.php?logout">Logout</a></li>
 </ul>
 </div>



<html>
      <head>
           <title>Web Crawler</title>
           <link rel="stylesheet" type="text/css" href="bootstrap/bootstrap.min.css" />
           <link rel="stylesheet" type="text/css" href="DataTables/media/css/jquery.dataTables.min.css"/>
           <script type="text/javascript" src="bootstrap/jquery.min.js"></script>
           <script type="text/javascript" src="bootstrap/bootstrap.min.js"></script>
           <script type="text/javascript" src="DataTables/media/js/jquery.dataTables.min.js"></script>
           <script type="text/javascript" src="DataTables/media/js/dataTables.scroller.min.js"></script>
           <script type="text/javascript" src="cron/jquery-cron.js"></script>
           <link type="text/css" href="cron/jquery-cron.css" rel="stylesheet" />
           <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/css/dataTables.checkboxes.css" rel="stylesheet" />
           <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/js/dataTables.checkboxes.min.js"></script>
           <script src="js/md5.min.js"></script>

           <style>
           table.fixed {table-layout:fixed; width:1300px;}/*Setting the table width is important!*/
           table.fixed td {overflow:hidden;}/*Hide text outside the cell.*/
           td.details-control {
              background: url('resources/details_open.png') no-repeat center center;
              cursor: pointer;
               }
           tr.shown td.details-control {
              background: url('resources/details_close.png') no-repeat center center;
               }
           </style>

      </head>
      <body>
           <div class="container" style="margin-left:200px;">
                <br />
                <br />
                <br />
                <div class="table-responsive" style="width:fit-content;">
                     <h3 align="center">Saved search Criteria</h3><br />
                      <!------>
                      <div class="container">
                          <div class="row">
                            <div class="col-md-6 col-md-offset-0" style="width:fit-content;">
                              <table><tr>
                              <!--td><button name="delete_btn" data-toggle="modal" data-target="#myModal" class="btn btn-warning btn_filter">Filter By</button>&nbsp;</td-->
                              <td><button type="button" name="btn_mark" id="btn_mark" class="btn btn-warning">Mark as Read</button>&nbsp;</td>
                              <td><button type="button" name="btn_add" id="btn_add" class="btn btn-success">Add</button>&nbsp;</td>
                              <td><button type="button" name="delete_btn" class="btn btn-danger btn_delete">Delete</button>&nbsp;</td>
                              <td><button type="button" name="btn_import" id="btn_import" class="btn btn-info"
                      onClick="document.location.href='importcsv.php'">Import</button></td>
                              <td>&nbsp;<button type="button" name="start-crawl" id="start-crawl" class="btn btn-primary startcrawl">Start</button></td>
                              <td>
                                <form style="margin-top: 15px;margin-left:-30px;" class="form-horizontal" action="functions.php" method="post" name="upload_excel"
                                          enctype="multipart/form-data">
                                      <div class="form-group">
                                                <div class="col-md-4 col-md-offset-4">
                                                    <input type="submit" name="Export" class="btn btn-success" value="Export CSV"/>
                                                </div>
                                       </div>
                                   </form>
                                 </td>
                             </tr></table>
                                   <br>
                              </div>
                          </div>
                      </div>
                      <div class="modal" id="propertiesModal" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content" style="width: 800px;">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Properties</h4>
                            </div>
                            <div class="modal-body">
                            <table>
                            <tr style="display:none"><td>ID:&nbsp;</td><td><input type="text" id="editid" value=""></td></tr>
                            <tr><td>URL:&nbsp;</td><td><input type="text" id="editurl" value=""></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td>Alerts:&nbsp;</td><td><select id="editalert"><option value="Keyword">Keyword</option><option value="Anychange">Anychange</option></select></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr id="editdepthrow"><td>Depth:&nbsp;</td><td><input type="text" id="editdepth" value=""></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td width="200px">Schedule: </td><td width="600px"><div id="editexample1"></div></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td>cron value:&nbsp;</td><td><span class="example-text" id="editCronvalue"></span></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td><input type="checkbox" id="editresults">&nbsp;</td><td>Send me a copy of the results</td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr id="editemailrow" style="display:none"><td>Email:&nbsp;</td><td><input type="text" id="editemail" value=""></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td colspan="2">Enter Keywords separated by comma&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td colspan="2"><textarea id="kword1" class="form-control input-sm"  type="text"></textarea></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                           <tr><td>
                           <select id="select1">
                            <option value="&&">AND</option>
                            <option value="||">OR</option>
                            <option value="&&!">NOT</option>
                           </select>
                           </td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          <tr><td colspan="2"><textarea id="kword2" class="form-control input-sm"type="text"></textarea></td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          <tr><td>
                            <select id="select2">
                            <option value="||">OR</option>
                            <option value="&&">AND</option>
                            <option value="&&!">NOT</option>
                            </select>
                           </td></tr>
                           <tr><td colspan="2">&nbsp;</td></tr>
                           <tr><td colspan="2"><textarea id="kword3" class="form-control input-sm"  type="text"></textarea></td></tr>
                           <tr><td colspan="2">&nbsp;</td></tr>
                           <tr><td>
                            <select id="select3">
                            <option value="&&!">NOT</option>
                            <option value="&&">AND</option>
                            <option value="||">OR</option>
                           </select>
                          </td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          <tr><td colspan="2"><textarea id="kword4" class="form-control input-sm"   type="text"></textarea></td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          </table>

                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                              <button type="button" class="btn btn-success saveProperties">Update</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--insertdiv-->
                      <div class="modal fade" id="insertModal" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content" style="width:800px">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">New Information</h4>
                            </div>
                            <div class="modal-body">
                            <table>
                            <tr><td>URL:&nbsp;</td><td><input type="text" id="inserturl" value=""></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td>Alerts:&nbsp;</td><td><select id="insertalert"><option value="Keyword">Keyword</option><option value="Anychange">Anychange</option></select></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr id="depthrow"><td>Depth:&nbsp;</td><td><input type="text" id="insertdepth" value=""></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td width="200px">Schedule:</td><td width="600px"><div id="insertexample1"> </div></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td>cron value:&nbsp;</td><td><span class="example-text" id="insertCronvalue"></span></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td><input type="checkbox" id="insertresults">&nbsp;</td><td>Send me a copy of the results</td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr id="emailrow" style="display:none"><td>Email:&nbsp;</td><td><input type="text" id="insertemail" value="programmer2@partslogistics.com"></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td td colspan="2">Enter Keywords separated by comma&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
                            <tr><td colspan="2"><textarea id="insertkword1" class="form-control input-sm"  type="text"></textarea></td></tr>
                            <tr><td colspan="2">&nbsp;</td></tr>
                           <tr><td>
                           <select id="insertselect1">
                            <option value="&&">AND</option>
                            <option value="||">OR</option>
                            <option value="&&!">NOT</option>
                           </select>
                           </td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          <tr><td colspan="2"><textarea id="insertkword2" class="form-control input-sm"type="text"></textarea></td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          <tr><td>
                            <select id="insertselect2">
                            <option value="||">OR</option>
                            <option value="&&">AND</option>
                            <option value="&&!">NOT</option>
                            </select>
                           </td></tr>
                           <tr><td colspan="2">&nbsp;</td></tr>
                           <tr><td colspan="2"><textarea id="insertkword3" class="form-control input-sm"  type="text"></textarea></td></tr>
                           <tr><td colspan="2">&nbsp;</td></tr>
                           <tr><td>
                            <select id="insertselect3">
                            <option value="&&!">NOT</option>
                            <option value="&&">AND</option>
                            <option value="||">OR</option>
                           </select>
                          </td></tr>
                          <tr><td colspan="2">&nbsp;</td></tr>
                          <tr><td colspan="2"><textarea id="insertkword4" class="form-control input-sm"   type="text"></textarea></td></tr>
                          </table>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                              <button type="button" class="btn btn-success insertProperties">Insert</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!------>
                      <div class="table-responsive">
                           <table class="fixed table table-bordered display" id="myTable">
                                <thead>
                                <tr>
                                     <th width="20px"></th>
                                     <th width="20px"></th>
                                     <th width="145px">URL</th>
                                     <th width="45px">Depth</th>
                                     <th width="150px">Crawled time</th>
                                     <th width="150px">Alerts</th>
                                     <th width="150px">Schedule</th>
                                     <th width="150px">Results</th>
                                     <th width="150px">Properties</th>
                                     <th width="150px">Email</th>
                                     <th width="150px">Keyword1</th>
                                     <th width="150px">Keyword2</th>
                                     <th width="150px">Keyword3</th>
                                      <th width="150px">Keyword4</th>
                                      <th width="150px">Logic1</th>
                                      <th width="150px">Logic2</th>
                                      <th width="150px">Logic3</th>
                                      <th width="150px">sendemail</th>
                                      <th width="150px">markread</th>
                                </tr></thead></table>
                        </div>
                     <!--div id="live_data"></div-->
                      <div id="new_update"></div>
                </div>
           </div>
      </body>
 </html>
 <script>

 function extractHostname(url) {
    var hostname;
        //find & remove protocol (http, ftp, etc.) and get hostname
    if (url.indexOf("://") > -1) {
        hostname = url.split('/')[2];
    }
    else {
        hostname = url.split('/')[0];
    }
    //find & remove port number
    hostname = hostname.split(':')[0];
    //find & remove "?"
    hostname = hostname.split('?')[0];
    return hostname;
}

function extractRootDomain(url) {
    var domain = extractHostname(url),
        splitArr = domain.split('.'),
        arrLen = splitArr.length;

    //extracting the root domain here
    //if there is a subdomain
    if (arrLen == 2){
        domain = splitArr[0];
    }
    if (arrLen > 2) {
        domain = splitArr[arrLen - 2];
        //check to see if it's using a country code (i.e. ".me.uk")
        if (splitArr[arrLen - 1].length == 2 && splitArr[arrLen - 1].length == 2) {
            //this is using a Country Code (ccTLD)
            domain = splitArr[arrLen - 3];
        }
    }
    return domain;
}
var cron_field="";
 $(document).ready(function(){
   $(document).ready(function () {
   $('#insertexample1').cron({
       initial: "* * * * *",
       onChange: function() {
           $('#insertCronvalue').text($(this).cron("value"));
       }
   });
 });
   window.cron_field=$('#editexample1').cron({initial: "* * * * *"});
      $(document).ready(function () {
      var table=$('#myTable').DataTable({
        "deferRender":true,
        "processing": true,
        "pageLength": 5,
        "iDisplayLength": 5,
        "aLengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
        "ajax": "jsoncontent.php",
        'columnDefs': [
          { "className": "Results",
            "targets": [ 7 ] },
          { "className": "details-control",
              "targets": [ 1 ] },
          {
                "targets": [ 9 ],
                "visible": false

          },
          {
                "targets": [ 10 ],
                "visible": false

          },
          {
                "targets": [ 11 ],
                "visible": false

          },
          {
                "targets": [ 12 ],
                "visible": false
          },
          {
                "targets": [ 13 ],
                "visible": false
          },
          {
                "targets": [ 14 ],
                "visible": false
          },
          {
                "targets": [ 15 ],
                "visible": false
          },
          {
                "targets": [ 16 ],
                "visible": false
          },
          {
                "targets": [ 17 ],
                "visible": false
          },
          {
                "targets": [ 18 ],
                "visible": false
          },
          { 'orderData':[18], 'targets': [1] },
          {
            'targets': [18]
          },
         {
            'targets': 0,
            'checkboxes': {
               'selectRow': true
            }
         }
      ],
      'select': {
         'style': 'multi'
      },
      //'order': [[1, 'asc']],
        "columns": [
            { "data": "ID" },
            {"data": null,
            "render": function(data, type, row, meta){
             return '';}},
            { "data": "URL",
            "render": function(data, type, row, meta){
                   data = '<a href='+row["URL"]+' target="_blank">' + data + '</a>';
               return data;
            } },
            { "data": "DEPTH" },
            { "data": "Status" },
            { "data": "Alerts" },
            { "data": "Cronvalue" },
            {
         "data": "Results",
         "render": function(data, type, row, meta){
                data = '<a href="Results/'+md5(row["URL"]+row["DEPTH"])+'.html" target="_blank">' + data + '</a>';
            return data;
         }
      },
            {
            "className":      'options',
            "data":           null,
            "render": function(data, type, row, meta){
             return '<button name="properties_btn" data-toggle="modal" data-target="#propertiesModal" class="btn btn-primary active btn_properties">Edit</button>';
           }},
           { "data": "EMAIL" },
           { "data": "keyword1" },
           { "data": "keyword2" },
           { "data": "keyword3" },
           { "data": "keyword4" },
           { "data": "logic1" },
           { "data": "logic2" },
           { "data": "logic3" },
           { "data": "sendemail" },
           { "data": "Mark_read" },

      ],
      "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
      {
       if ( aData['Mark_read'] == 1 )
       {
       $('td', nRow).css('background-color', 'LightGray');
       }
       else if ( aData['Mark_read'] == null )
       {
       $('td', nRow).css('background-color', 'White');
       }
       else if ( aData['Mark_read'] == 0 )
       {
       $('td', nRow).css('background-color', 'White');
       }
       else
       {
       $('td', nRow).css('background-color', 'Blue');
       }
      }
      });

    });
      $(document).on('click', '#btn_add', function(){
        $('#insertModal').modal('show');
        if($('#insertCronvalue').text()=="")
        {
        $('#insertexample1').cron({
            initial: "* * * * *",
            onChange: function() {
                $('#insertCronvalue').text($(this).cron("value"));
            }
        });
        window.cron_field=$('#editexample1').cron({initial: "* * * * *"});
      }
        });
        $(document).on('click','#insertresults',function () {
          var checked = $(this).is(':checked');
          if(checked)
          {$("#emailrow").show();
          } else {
          $("#emailrow").hide();
          }
      });
      $(document).on('click','#editresults',function () {
      if($('#editresults').prop('checked') == true){
          $("#editemailrow").show();
      } else {
          $("#editemailrow").hide();
      }
    });
    $(document).on('change','#insertalert',function () {
     if($('#insertalert').val()=="Anychange")
        {$('#depthrow').hide();
    } else {
        $('#depthrow').show();
    }
   });
   $(document).on('change','#editalert',function () {
    if($('#editalert').val()=="Anychange")
       {$('#editdepthrow').hide();
   } else {
       $('#editdepthrow').show();
   }
  });

        $(document).on('click', '.insertProperties', function(){
           var url = $('#inserturl').val();
           var Email = $('#insertemail').val();
           var keyword1 = $('#insertkword1').val();
           var keyword2 = $('#insertkword2').val();
           var keyword3 = $('#insertkword3').val();
           var keyword4 = $('#insertkword4').val();
           var logic1=$('#insertselect1').val();
           var logic2=$('#insertselect2').val();
           var logic3=$('#insertselect3').val();
           var insertalert=$('#insertalert').val();
           var results='';
           var Cronvalue=$('#insertCronvalue').text();
           var sendemail="no";
           var domain=extractRootDomain(url);
           if(insertalert=="Anychange")
            {var Depth=1;}
           else
            {var Depth = $('#insertdepth').val();}
           if($('#insertresults').prop('checked') == true){
             sendemail="yes";
           }
           if(url == '')
           {
                alert("Enter url");
                return false;
           }
           if(Depth == '')
           {
                alert("Enter Depth");
                return false;
           }
           if(sendemail=="yes"&&Email=="")
           {
             alert("Please provide emailid");
             return false;
           }
           if(insertalert=="Keyword")
           {
             if(keyword1==""||keyword2==""||keyword3==""||keyword4=="")
               {alert("Please enter keywords");
               return false;}
           }
           $('#insertModal').modal('hide');
           $.ajax({
                url:"valid_url.php",
                method:"POST",
                data:{url:url,domain:domain},
                dataType:"text",
                success:function(data)
                {
                var result = $.trim(data);
                if(result === 'valid'){
           $.ajax({
                url:"insert.php",
                method:"POST",
                data:{url:url,Depth:Depth,Email:Email,keyword1:keyword1,keyword2:keyword2,keyword3:keyword3,keyword4:keyword4,logic1:logic1,logic2:logic2,logic3:logic3,insertalert:insertalert,results:results,Cronvalue:Cronvalue,sendemail:sendemail,domain:domain},
                dataType:"text",
                success:function(data)
                {
                     alert(data);
                    table.ajax.reload();
                }
           });
         }
         if(result === 'duplicate'){
                        WRN_PROFILE_ADD = "Click OK to add a duplicate monitor?";
                        var check = confirm(WRN_PROFILE_ADD);
                        if(check == true){
                            $.ajax({
                                url:"insert.php",
                                method:"POST",
                                data:{url:url,Depth:Depth,Email:Email,keyword1:keyword1,keyword2:keyword2,keyword3:keyword3,keyword4:keyword4,logic1:logic1,logic2:logic2,logic3:logic3,insertalert:insertalert,results:results,Cronvalue:Cronvalue,sendemail:sendemail,domain:domain},
                                dataType:"text",
                                success:function(data)
                                {
                                     alert(data);
                                     table.ajax.reload();
                                }
                            });
                        }else{
                            alert('Duplicate monitor not added.');
                        }
                    }
                    if(result === 'invalid'){
                        alert('URL entered was invalid');
                        table.ajax.reload();
                    }
                }
            });
      });
      $(document).on('click', '.Results', function(){
        var table = $(this).closest('table').DataTable();
        var tr = $(this).parents("tr");
        var id=table.row($(this).closest('tr')).data()['ID'];
        tr.css("background-color","LightGray");
        var read=1;
        $.ajax({
             url:"read_edit.php",
             method:"POST",
             data:{id:id,read:read},
             dataType:"text",
             success:function(data){
                  table.ajax.reload();
             }
        })
      });
      $(document).on('click', '#btn_mark', function(){
        var allVals = [];
        var table = $('#myTable').DataTable();
        var rows_selected = table.column(0).checkboxes.selected();
        if(rows_selected.length <=0)
          {
            alert("Please select row.");
          }
          else {
        var join_selected_values = rows_selected.join(",");
        var read=1;
        $.ajax({
             url:"read_edit.php",
             method:"POST",
             data:{read:read,id:join_selected_values},
             dataType:"text",
             success:function(data){
                  table.ajax.reload();
             }
        })
        }
      });
      $(document).on('change','#editexample1',function(){$('#editCronvalue').text($(this).cron("value"));});
      $(document).on('click', '.btn_properties', function(){
        var table = $(this).closest('table').DataTable();
        var tr = $(this).parents("tr");
        var id=table.row($(this).closest('tr')).data()['ID'];
        var url=tr.find("td").eq(2).text();
        var depth=tr.find("td").eq(3).text();
        var Alert=tr.find("td").eq(5).text();
        var Cronvalue=tr.find("td").eq(6).text();
        var Results = tr.find("td").eq(7).text();
        var Email = table.row($(this).closest('tr')).data()['EMAIL'];
        var keyword1 = table.row($(this).closest('tr')).data()['keyword1'];
        var keyword2 = table.row($(this).closest('tr')).data()['keyword2'];
        var keyword3 = table.row($(this).closest('tr')).data()['keyword3'];
        var keyword4 = table.row($(this).closest('tr')).data()['keyword4'];
        var logic1 = table.row($(this).closest('tr')).data()['logic1'];
        var logic2 = table.row($(this).closest('tr')).data()['logic2'];
        var logic3 = table.row($(this).closest('tr')).data()['logic3'];
        var sendemail=table.row($(this).closest('tr')).data()['sendemail'];
        cron_field.cron("value",Cronvalue);
        $('#editid').val(id);
        $('#editurl').val(url);
        $('#editdepth').val(depth);
        $('#editalert').val(Alert);
        $('#editCronvalue').text(Cronvalue);
        if(Alert=="Anychange")
        {$('#editdepthrow').hide();}
        else {
          $('#editdepthrow').show();
        }
        if(sendemail=="yes")
        {$('#editresults').prop('checked',true);}
        else
        {$('#editresults').prop('checked',false);}
        if($('#editresults').prop('checked') == true){
            $("#editemailrow").show();
        } else {
            $("#editemailrow").hide();
        }
        $('#editemail').val(Email);
        $('#kword1').val(keyword1);
        $('#kword2').val(keyword2);
        $('#kword3').val(keyword3);
        $('#kword4').val(keyword4);
        $('#select1').val(logic1);
        $('#select2').val(logic2);
        $('#select3').val(logic3);
       });
      $(document).on('click', '.saveProperties', function(){
        $('#propertiesModal').modal('hide');
        var table = $('#myTable').DataTable();
        var editid=$('#editid').val();
        var editurl=$('#editurl').val();
        var editalert=$('#editalert').val();
        var editemail=$('#editemail').val();
        var editCronvalue=$('#editCronvalue').text();
        var editemail=$('#editemail').val();
        var editkeyword1=$('#kword1').val();
        var editkeyword2=$('#kword2').val();
        var editkeyword3=$('#kword3').val();
        var editkeyword4=$('#kword4').val();
        var editlogic1=$('#select1').val();
        var editlogic2=$('#select2').val();
        var editlogic3=$('#select3').val();
        var editsendemail="no";
        if(editalert=="Anychange")
        {
          var editdepth=1;
        }
        else {
          var editdepth=$('#editdepth').val();
        }
        if($('#editresults').prop('checked') == true){
          editsendemail="yes";
        }
        else if($('#editresults').prop('checked') == false){
          editsendemail="no";
          editemail="";
        }
        if(editsendemail=="yes"&&editemail=="")
        {
          alert("Please provide emailid");
          return false;
        }
        if(editalert=="Keyword")
        {
          if(editkeyword1==""||editkeyword2==""||editkeyword3==""||editkeyword4=="")
           {alert("Please enter keywords");
            return false;}
        }
        $.ajax({
             url:"edit.php",
             method:"POST",
             data:{editid:editid, editurl:editurl, editdepth:editdepth,editemail:editemail,editsendemail:editsendemail,editalert:editalert,editCronvalue:editCronvalue,editemail:editemail,editkeyword1:editkeyword1,editkeyword2:editkeyword2,editkeyword3:editkeyword3,editkeyword4:editkeyword4,editlogic1:editlogic1,editlogic2:editlogic2,editlogic3:editlogic3},
             dataType:"text",
             success:function(data){
                  alert(data);
                  table.ajax.reload();
             }
        })
      });
      $(document).on('click', '.btn_delete', function(){
        var table = $('#myTable').DataTable();
        var rows_selected = table.column(0).checkboxes.selected();
        if(rows_selected.length <=0)
          {
            alert("Please select row.");
          }
          else {
         WRN_PROFILE_DELETE = "Are you sure you want to delete this row?";
         var check = confirm(WRN_PROFILE_DELETE);
         if(check == true){
         //for server side
        var join_selected_values = rows_selected.join(",");
         $.ajax({
            type: "POST",
            url: "delete.php",
            cache:false,
            data: 'ids='+join_selected_values,
            success: function(response)
            {
              alert(response);
              table.ajax.reload();
            }
          });
        }
      }
  });
      $(document).on('click', '#selectall', function(){
        $(':checkbox').prop('checked', this.checked);
      });
      $(document).on('click', '.startcrawl', function(){
        var checkedRows = [];
        $("#myTable tbody tr").each(function () {

        if ($(this).find("input").is(":checked")) {
          var table = $('#myTable').DataTable();
          var tr = $(this).closest("tr");
          var id=table.row($(this).closest('tr')).data()['ID'];
          var url=table.row($(this).closest('tr')).data()['URL'];
          var Depth =table.row($(this).closest('tr')).data()['DEPTH'];
          var Alert=table.row($(this).closest('tr')).data()['Alerts'];
          var Cronvalue = table.row($(this).closest('tr')).data()['Cronvalue'];
          var Results = table.row($(this).closest('tr')).data()['Results'];
          var kword1 = table.row($(this).closest('tr')).data()['keyword1'];
          var kword2 = table.row($(this).closest('tr')).data()['keyword2'];
          var kword3 = table.row($(this).closest('tr')).data()['keyword3'];
          var kword4 = table.row($(this).closest('tr')).data()['keyword4'];
          var select1 = table.row($(this).closest('tr')).data()['logic1'];
          var select2 = table.row($(this).closest('tr')).data()['logc2'];
          var select3 = table.row($(this).closest('tr')).data()['logic3'];
          var sendemail = table.row($(this).closest('tr')).data()['sendemail'];
          var email = table.row($(this).closest('tr')).data()['EMAIL'];
          var indexvalue=table.row($(this).closest('tr')).data()['ID'];
       $.ajax({
          url:"startcrawl.php",
          indexValue:indexvalue,
          link:url,depth:Depth,
           type: "POST",
          data:{url:url,
                Depth:Depth,
                Alert:Alert,
                Cronvalue:Cronvalue,
                Results:Results,
                keyword1:kword1,
                keyword2:kword2,
                keyword3:kword3,
                keyword4:kword4,
                  oper1 : select1,
                  oper2 : select2,
                  oper3 : select3,
                  email:email,
                sendemail:sendemail},
          dataType:"text",
          //async:false,
          beforeSend: function () {tr.find("td").eq(4).text("pending");},
          success:function(response)
          {
              var index_value=this.indexValue;
              if(response=="no new content added")
              {tr.find("td").eq(7).text("No change");}
              else if(response=="no new content added for searched keywords")
              {
                tr.find("td").eq(7).text("No new results found for specified keywords");
              }
              else if(response.indexOf("The following page has changed!") !== -1){
                tr.find("td").eq(7).text("New results");
              }
              else if(response.indexOf("childurl not Inserted") !== -1){
                tr.find("td").eq(7).text("Error Occured");
              }
              else if(response=="page not found"){
                tr.find("td").eq(7).text("Error occured (404)");
              }
              else{
                tr.find("td").eq(7).text("Error occured (Time out)");
              }
              var data1=this.link;var data2=this.depth;var data3=response;
              setTimeout(function () {tr.find("td").eq(4).text("complete");}, 1000);
              $.ajax({
                   url:"updatestatus.php",
                   method:"POST",
                   data:{url:data1,depth:data2,response:data3},
                   success:function(data){

                    setTimeout(function () {table.ajax.reload();},2000);
                   }});
               //setTimeout(function () {fetch_data();},2000);
               //fetch_data();
          }
     })
   }
 });
   });
   // Add event listener for opening and closing details
   $(document).on('click', 'td.details-control', function () {
     var table = $('#myTable').DataTable();
       var tr = $(this).closest('tr');
       var row = table.row( tr );
       var id=table.row($(this).closest('tr')).data()['ID'];
       var outputdata="";
       $.ajax({
          url:"crawl_index_new.php",
          method:"POST",
          dataType:"json",
          data:{urlid:id},
          success:function(response)
          {
              for(var i = 0, len = response.length; i < len; i++) {
               //outputdata+="<tr><td><a href='' onclick=window.open('"+response[i].childurl+"','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=768,directories=no,location=no');return false;>" + response[i].childurl + "</a></td></tr>";
               outputdata+="<tr><td><a href="+response[i].childurl+" target='_blank'>" + response[i].childurl + "</a></td></tr>";
              }
         },
         async: false
      })
       if ( row.child.isShown() ) {
             // This row is already open - close it
             row.child.hide();
             tr.removeClass('shown');
         }
         else {
            // Open this row
            row.child(outputdata).show();
            tr.addClass('shown');
        }
    } );
 });
 </script>
 <?php ob_end_flush();
  ?>
