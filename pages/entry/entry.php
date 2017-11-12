<html>
<head>
<!-- JQUERY BABY -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>


<!-- Bootstrap3 -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


  <!-- Bootstrap Table -->
  <link rel="stylesheet" href="assets/js/src/bootstrap-table.css">
  <script src="assets/js/src/bootstrap-table.js"></script>

  <!-- Editable Tables -->
  <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
  <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

<script src="assets/js/src/extensions/editable/bootstrap-table-editable.js"></script>
<script src="assets/js/src/extensions/print/bootstrap-table-print.js"></script>
<script src="assets/js/src/extensions/export/bootstrap-table-export.js"></script>
<script src="//rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js"></script>
</head>
<body>
    <div class="container">
      <button id="button">Submit For Approval</button><br>
      <button id="save">Save</button><br>
      <button id="month">Monthly</button>
      <button id="quarter">Quarterly</button>
      <button id="annual">Annual</button>

        <table id="quartertable"
               data-toggle="table"
               data-show-print="true"
               data-show-export="true">
            <thead>
            <tr>
                <th data-field="id">Metric</th>
                <th data-field="name">Goal</th>
                <th data-field="unit">Unit</th>
                <th data-field="q1" data-editable="true">Q1</th>
                <th data-field="q2">Q2</th>
                <th data-field="q3">Q3</th>
                <th data-field="q4">Q4</th>
            </tr>
          </thead>
        </table>
        <table id="annualtable"
               data-classes="myclass"
               data-show-print="true"
               data-show-export="true">
            <thead>
            <tr>
                <th data-field="id">Metric</th>
                <th data-field="name">Goal</th>
                <th data-field="unit">Unit</th>
                <th data-field="2014" data-editable="true">2014</th>
                <th data-field="2015">2015</th>
                <th data-field="2016">2016</th>
                <th data-field="2017">2017</th>
            </tr>
          </thead>
        </table>
    </div>
    <script>
    var $quartertable = $('#quartertable'),
        $annualtable = $('#annualtable'),
        $annual = $('#annual'),
        $quarter = $('#quarter'),
        $month = $('#month'),
        $button = $('#button');
    $(function () {
        insertQuarterRows();

        $button.click(function () {
            alert(JSON.stringify($quartertable.bootstrapTable('getData')));
        });
        $annual.click(function(){
          $quartertable.bootstrapTable("destroy");
          $quartertable.hide();
          $("#annualtable").bootstrapTable();
          $("#annualtable").show();
        });
        $quarter.click(function(){
          $annualtable.bootstrapTable("destroy");
          $annualtable.hide();
          $quartertable.bootstrapTable();
          $quartertable.show();
          insertQuarterRows();
        });
        $("#annualtable").hide();
        function insertQuarterRows() {
          for(i=0;i<10;i++) {
            $quartertable.bootstrapTable('insertRow', {
                index: i,
                row: {
                    id: "Metric "+i,
                    name: 'Goal ' + i,
                    unit: 'Percent',
                    q1: i+30
                }
            });
          }
        }
    });
</script>
</body>
</html>
