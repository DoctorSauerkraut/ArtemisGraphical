
<?php include_once('../Templates/header2.php'); ?>

<body onload="draw();">
<div id="corps">
<div id="graph-popUp">
  <span id="operation">node</span> <br>
  <table id="tableEdit" >
  <tr style="display:none;">
    <td>ID</td><td><input id="node-id" value="new value"></td>
  </tr>

    <tr>
      <td>Name</td><td><input id="node-label" value="new value"> </td>
    </tr>
	  	 <tr>
      <td>IP Address</td><td><input id="node-ip" value="0"> </td>
    </tr>
	 <tr>
      <td>Scheduling</td><td><input id="node-sched" value="FIFO"> </td>
    </tr>
	<tr>
      <td>Criticality</td><td><input id="node-crit" value="1"> </td>
    </tr></table>
  <input type="button" value="save" id="saveButton"></button>
  <input type="button" value="cancel" id="cancelButton"></button>
</div>
<br />
<div id="mygraph"></div>
<p id="selection"></p>
</div>

<?php  include_once('../Templates/footer.php'); ?>
