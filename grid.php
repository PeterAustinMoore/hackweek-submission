<html>
<head>
<!-- JQUERY BABY -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="assets/js/src/bootstrap-table.css">

</head>
<body>
  <table id="table"
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
             <th data-field="metric"></th>
             <th data-field="goal" width="200">Goal</th>
             <th data-field="q1" data-editable="true">Q1</th>
             <th data-field="q2">Q2</th>
             <th data-field="q3">Q3</th>
             <th data-field="q4">Q4</th>
           </tr>
         </thead>
  </table>

  <!-- Latest compiled and minified JavaScript -->
  <script src="assets/js/src/bootstrap-table.js"></script>

  <!-- Editable -->
  <script src="//rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>
  <script src="assets/js/src/extensions/editable/bootstrap-table-editable.js"></script>
</body>
</html>
