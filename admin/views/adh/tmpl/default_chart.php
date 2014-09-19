<?php

/* 
 *  Copyright (C) 2012-2014 charly
 *  com_adh is a joomla! 2.5 component [http://www.volontairesnature.org]
 *  
 *  This file is part of com_adh.
 * 
 *     com_adh is free software: you can redistribute it and/or modify
 *     it under the terms of the Affero GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     com_adh is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     Affero GNU General Public License for more details.
 * 
 *     You should have received a copy of the Affero GNU General Public License
 *     along with com_adh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

?>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'nb cotisations', 'primo-adhérents'],
		  <?php foreach ($this->stats_cotiz_by_year as $i => $stat) : ?>
			['<?php echo $stat->year; ?>', <?php echo $stat->nb; ?>, <?php echo $stat->primo; ?>],
		  <?php endforeach; ?>
        ]);

        var options = {
          title: 'nb adhésions par année'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_cotiz_div'));
        chart.draw(data, options);
      }
</script>

<div id="chart_cotiz_div" style="width: 100%; height: 500px;"></div>
