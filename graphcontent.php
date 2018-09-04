    
<html>
  <head>
      <style type="text/css">
      #chart-container {
        width: 640px;
        height: auto;
      }
      </style>
<!--       <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/locale/af.js"></script>
 -->
      <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/jquery.jqplot.js"></script>
<!-- <script type="text/javascript" src="./jqplot/plugins/jqplot.barRenderer.js"></script> -->
<script type="text/javascript" src="./jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="./jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script type="text/javascript" src="./jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="./jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script type="text/javascript" src="./jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/jquery.jqplot.css" />

<script>

$(function() 
{

   var start = moment();
   var end = moment();
    function cb(start, end) 
      {
       $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
      }
    var rangestart='';
    var rangeend='';
    var startDate='';
    var endDate='';
    $('#reportrange').daterangepicker({
       startDate: start,
       endDate: end,
       opens: 'left',
       ranges: 
       {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment()],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
   }, cb);  //function close

   cb(start, end);

    $('#reportrange').change(function()
    {
    var rangestart= $('#reportrange').data('daterangepicker').startDate;
    var rangeend= $('#reportrange').data('daterangepicker').endDate;
    var startDate = rangestart.format('YYYY-MM-DD');
    var endDate=rangeend.format('YYYY-MM-DD');
     $.ajax({
        url:"dbconnect.php",
        type:"POST",
        datatype:'json',
        data:{
          'startDate':startDate,
          'endDate':endDate,
      },
        success:function(data)
        {
         var txn_count = [];
        var parsedate =JSON.parse(data);
      console.log(parsedate);
        for (var i = 1; i < parsedate.length; i++)
             {
           txn_count.push([parsedate[i].created_date,parsedate[i].txn_count])
            }
           console.log(txn_count);
       function getDaysArrayByMonth() {
      var first = moment(startDate);
      var second = moment(endDate);
      var numberOfDays=  second.diff(first,'days');
             // console.log(numberOfDays);
      var arrXaxis = [];
               
      for (var i = 0; i <= numberOfDays; i++) 
      {
                 
      arrXaxis.push(moment(startDate).add(i,'days').format('YYYY-MM-DD'));
      }

      return arrXaxis;
      }
        var plot = [];
        var schedule = getDaysArrayByMonth();
        console.log(schedule);
for (var i = 0; i < schedule.length; i++) 
       {
        var temp = null;
        for (var j = 0; j < txn_count.length; j++)
         {
           console.log(schedule[i] + 'add' + txn_count[j][0])
            if(schedule[i] === txn_count[j][0])
              {
              console.log('pass');
              temp = txn_count[j];
                console.log(temp);
              }
          }
          if (temp!=null) 
            {
             console.log('temp pushed')
              plot.push(temp);
            }
      else
      {
        plot.push([schedule[i],0]);
      }
  }
                 
        $('#chart').empty(); 
        $.jqplot.config.enablePlugins = true;
        $('#chart').jqplot([plot], {
        title:'Calculating count',
        seriesDefaults:
        {
            renderer:$.jqplot.lineRenderer,
            pointLabels: { show: true }
        },
        axes:{
            xaxis:{
               renderer: $.jqplot.CategoryAxisRenderer,
              },
           
        },
    });
        
        }  //success function   
      }); //ajax
  }); //function
 });//document
function datediff(first, second) 
       {
       return Math.round((second-first)/(1000*60*60*24));
        }

</script>
</head>
<body>
  
  <input type="text" id="reportrange" name="datepicker" value="DATE" style="background: #8eede1; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; display: inline; margin-left: 67%;">

                                   <i class="fa fa-calendar"></i>&nbsp;
                                   <span></span> <i class="fa fa-caret-down"></i>
                               
     
      <div id="chart"></div>

    </div>
    
  
</body>
</html>