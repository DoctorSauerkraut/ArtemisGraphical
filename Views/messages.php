	<!---------- Messages Table ---------->
	<?php
		/* Get criticality levels and corresponding WCETS */
		$cptLevels = 0;
			$levels = CriticalityLevel::load();	
			
			while($rst = $levels->fetch()) {
				$levelsTab[$cptLevels] = new CriticalityLevel($rst["name"], $rst["code"]);
				$cptLevels++;
			}
			
			if ($cptLevels == 0) {
				$newCritLvl = new CriticalityLevel("Non critical", "NC");
				$newCritLvl->save();
				
				$levels = CriticalityLevel::load();	
			
				while($rst = $levels->fetch()) {
					$levelsTab[$cptLevels] = new CriticalityLevel($rst["name"], $rst["code"]);
					$cptLevels++;
				}
			}

	//////////// SETTINGS MODELE, RATE, PROTOCOL, SWITCH ///////////////////

    echo "<table class=\"tableShow\">";
   echo "<tr>";
   
echo "<tr><td>Simulation Time</td>";
echo "<td>Worst-case analysis</td>";
echo "<td>Electronical latency</td>";
echo "<td>WCTT computation model</td>";
echo '<td>WCTT computation rate</td>';
echo '<td>Switch</td>';
echo '<td>Protocol</td>';
echo "<td>Save</td></tr>";

/* WC Analysis set */
echo "<tr><td><input type=\"text\" id=\"timelimit\" value=\"".Settings::getParameter("timelimit", $_SESSION["simuid"])."\"/> ms</td>";

echo "<td>";
echo "<select id=\"wcanalysis\">";

echo "<option value=\"true\"";
if(Settings::getParameter("wcanalysis", $_SESSION["simuid"]) == "true") {
    echo " selected='selected' ";
}

echo ">true</option>";
echo "<option value=\"false\"";
if(Settings::getParameter("wcanalysis", $_SESSION["simuid"]) != "true") {
    echo " selected='selected' ";
}

echo ">false</option>";
echo "</select>";
echo "</td>";

/* Electronical latency */
echo "<td><input type=\"text\" id=\"elatency\" value=\"".Settings::getParameter("elatency", $_SESSION["simuid"])."\"/> ms</td>";
echo "<td>";
echo "<select id=\"wcttmodel\">";

$wcttModels = array("LIN", "GAU", "GCO", "GAP");

foreach($wcttModels as $model) {
    echo "<option value=\"$model\" ";   
if(Settings::getParameter("wcttmodel", $_SESSION["simuid"]) == $model) {
    echo " selected=\"selected\" ";
}
echo ">$model</option>";
}
echo "</select>";
echo "</td>";


echo "<td>";
echo "<select id=\"wcttrate\">";

$wcttRates = array(10, 20, 30, 40, 50, 60, 70, 80, 90);

foreach($wcttRates as $rate) {
    echo "<option value=\"$rate\" ";   
if(Settings::getParameter("wcttrate", $_SESSION["simuid"]) == $rate) {
    echo " selected=\"selected\" ";
}
echo ">$rate</option>";
}
echo "</select>";
echo "</td>";


echo "<td>";
echo "<select id=\"switch\" style=\"width:115px\" onclick=\"correction();\">";

$swits = array("Static"=>"S", "Dynamic"=>"D");

foreach($swits as $key=>$sw) {
    echo "<option value=\"$sw\" ";   
if(Settings::getParameter("switch", $_SESSION["simuid"]) == $sw) {
    echo " selected=\"selected\" ";
}
echo ">$key</option>";
}
echo "</select>";
echo "</td>";


echo "<td>";
echo "<select id=\"protocol\" style=\"width:115px\" onclick=\"notAllowed();\">";

$protos = array("Centralized", "Decentralized");

foreach($protos as $proto) {
    echo "<option value=\"$proto\" ";   
if(Settings::getParameter("protocol", $_SESSION["simuid"]) == $proto) {
    echo " selected=\"selected\" ";
}
echo ">$proto</option>";
}
echo "</select>";


echo "<td><a class=\"button blue\" onclick=\"saveSettings()\">Save</a></td></tr></table>";


// Automatic task generation table
/*
echo "<table class=\"tableShow\"><tr>";
echo "<td>Automatic task generation</td>";
echo "<td>Number of tasks</td>";
echo "<td>Highest WCTT</td>";
echo "<td>Load</td>";
echo "<td>Generate</td></tr>";
    $autogen = Settings::getParameter("autogen", $_SESSION["simuid"]);

    echo "<td><input type=\"radio\" value=\"n\" name=\"radiotask\"";
    if ($autogen == "1") echo "checked";
    echo "  onclick=\"desactivateGenerateTextFields()\" /> No";

    echo "<input type=\"radio\" value=\"y\" name=\"radiotask\"";
    if ($autogen == "0") echo "checked";
    echo " onclick=\"activateGenerateTextFields()\" /> Yes</td>";

    echo "<td><input type=\"text\" class=\"autogenTextField\" id=\"autotasks\" value=\"".Settings::getParameter("autotasks", $_SESSION["simuid"])."\"/> tasks</td></td>";

    echo "<td><input type=\"text\" class=\"autogenTextField\" id=\"highestwcet\" value=\"".Settings::getParameter("highestwcet", $_SESSION["simuid"])."\"/> ms</td></td>";

    echo "<td><input type=\"text\" class=\"autogenTextField\" id=\"autoload\" value=\"".Settings::getParameter("autoload", $_SESSION["simuid"])."\"/> load < 1.0</td></td>";
    
echo "<td><a class=\"button blue\" onclick=\"generateMessagesSet()\">Generate</a></td></tr>";
    echo "</table>";*/
?>
	

		<div class="tabledetailsdiv" id="tabledetailsmessages">
		<table class="tableShow">
			<caption> Messages Table </caption>
			<tr>
                <th>ID</th></th><th>Path</th><th>Period</th><th>Offset</th>
				<?php
					for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
						echo "<th>WCTT ".$levelsTab[$cptTd]->getName()."</th>";
					}	
				?>
				<th>Color</th><th>Edit</th><th>Add</th><th>Delete</th>
			</tr>
			<?php 
			foreach ($donnees1 as $node) {
				$nodes[]=$node->name();
			}
			asort($nodes);
			$codeStr = "";
			$newMessage='';
			foreach($donnees3 as $element){ 	
				echo "<tr>";
                    echo "<td>".$element->id()."</td>";
					echo "<td id=\"path_".$element->id()."\" >".$element->path()."</td>";
					echo "<td id=\"peri_".$element->id()."\" onclick=\"editValue(this)\" >".$element->period()."</td>";
					echo "<td id=\"offs_".$element->id()."\" onclick=\"editValue(this)\" >".$element->offset()."</td>";
					
					/* Criticality levels */
					$codeStr ="";
					for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
						$code = $levelsTab[$cptTd]->getCode();
						$wcet = ($element->wcet_($code) != "") ? $element->wcet_($code):"-1"; 
						
						echo "<td id=\"wcet_".$code."_".$element->id()."\" onclick=\"editWcet(this, '$code')\" >".$wcet."</td>";
						
						$codeStr .= "'".$code."'";
						if($cptTd<$cptLevels-1)
							$codeStr .= ",";
					}

					echo '<td>';
					activeColorBox($element->id(),$element->color());
					echo '</td>';

					echo "<td>";
					
					/* Building js table containing all wcet-critical codes */
					echo "<a class=\"button green\" onclick=\"saveEditedMessage('".$element->id()."', new Array(".$codeStr;
					echo "))\" />Edit</a>";
					echo "</td>";
			
			
					echo "<td>-</td>";
					echo "<td><a class=\"button red\" onclick=\"deleteMessage(".$element->id().")\"/>Delete</a></td>";
				echo "</tr>";		
			 } 
			
				echo "<tr>";
                echo "<td></td>";
				echo "<td>";

					echo '<input style="display: none; text-align: center; min-width:90px; margin-bottom: 5px;" type="text" value="" id="path" readonly="true"></input>';
					echo '<a class="button blue" onclick="displayGraph();"/>View</a></td>';
				echo "<td><input type=\"text\" id=\"period\" /></td>";
				echo "<td><input type=\"text\" id=\"offset\" /></td>";
				
				$codeStr ="";
				for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
					$critLvl = $levelsTab[$cptTd]->getCode();
					
					echo "<td><input type=\"text\" id=\"wcet_".$critLvl."\" /></td>";
					
					$codeStr .= "'".$critLvl."'";
					if($cptTd<$cptLevels-1)
							$codeStr .= ",";
				}
				
				echo '<td>';
				    activeColorBox('');
				    
				echo '</td>';


				echo "<td>-</td>";
				echo "<td><a class=\"button blue\" onclick=\"addMessageTable(new Array($codeStr))\" />Add</a></td>";
				echo "<td>-</td>";
				echo "</tr></table> ";
			?> 
				
		
		<br />	<!-- <a class="button blue" id="buttonAddCriticality" onclick="popup('critButton')">Add a criticality level</a> -->
		
	</div>

	<div id="band" class="band">
		<a class="hideGraph" onclick="hideGraph();"/></a>
		<div id="mygraph2" style="position: relative; width:100%;height:auto;"></div>
	</div>
	<div id="box" class="box"></div>

	</br></br></br></br></br></br></br></br>
	<div>   </div>
