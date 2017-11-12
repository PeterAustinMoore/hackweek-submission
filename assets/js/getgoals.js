var goalGetter = {
  get: function() {
    function getGoals(base, d_id, dashboard) {
      var d_url = base + "/api/stat/v1/dashboards/" + d_id + ".json";
      var ontarget = 0;
      var offtarget = 0;
      var gArray = [];
      $.ajax({
          url: d_url,
          async: false,
          dataType: 'json',
          success: function(s) {
            categories = s['categories'];
            for(var j in categories) {
              goals = categories[j]['goals'];
              for(var k in goals) {
                var goalInfo = {};
                goalInfo["id"] = goals[k]["id"];
                goalInfo["name"] = goals[k]["name"];
                goalInfo["category"] = categories[j]["name"];
                goalInfo["dashboard"] = dashboard;
                goalInfo["dashboard_url"] = base + "/stat/goals/" + d_id;
                goalInfo["api_url"] = base + "/api/stat/v1/goals/" + goals[k]['id'] + ".json";
                goalInfo["url"] = base + "/stat/goals/" + d_id + "/" + categories[j]["id"] + "/" + goals[k]['id'];
                gArray.push(goalInfo);
              }
            }
          }
      });
      return gArray;
    }
    var base = "https://peter.demo.socrata.com";
    var dashboards_url = "https://peter.demo.socrata.com/api/stat/v1/dashboards.json";
    goalArray = Array();
    $.ajax({
          url: dashboards_url,
          dataType: 'json',
          async: false,
          success: function(data) {
            for(i in data){
              goalArray.push.apply(goalArray, getGoals(base, data[i]["id"], data[i]["name"]));
            }
          }
        });
        return goalArray;
  }
}
