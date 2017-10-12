<html>
<head>
<!-- JQUERY BABY -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">

  <!-- Latest compiled and minified JavaScript -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

  <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
  <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
  <script src="//rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>
<script>

</script>
</head>
<body>
  <table id="table"
         data-toggle="table"
         data-toolbar="#toolbar"
         data-show-refresh="true"
         data-show-export="true"
         data-detail-view="true"
         data-detail-formatter="detailFormatter"
         data-minimum-count-columns="2"
         data-show-pagination-switch="true"
         data-pagination="true"
         data-id-field="id"
         data-show-footer="false"
         data-side-pagination="server"
         data-response-handler="responseHandler">
         <thead>
           <tr>
             <th data-field="metric">Metric</th>
             <th data-field="goal" width="200">Goal</th>
             <th data-field="q1" data-editable="true">Q1</th>
             <th data-field="q2">Q2</th>
             <th data-field="q3">Q3</th>
             <th data-field="q4">Q4</th>
           </tr>
         </thead>
         <tr>
           <td>Metric 1</td>
           <td>Goal 1</td>
           <td>100</td>
         </tr>
         <tr>
           <td></td>
           <td>Goal 2</td>
           <td>50</td>
         </tr>
  </table>
</body>
</html>
