<?php

function exec_query($array,$where="",$debug=false)
{
	global $mysqli;
	$fields_name = "";
	$field_value = "";	
	foreach($array as $key => $val)
	{
		foreach($val as $field => $value)
		{
			 
			if(trim($where) != "")
			{
				if($fields_name==""){
					$field_value = clear_input(trim($value));
					$fields_name= trim($field)." = '".$field_value."'";
				}
				else
				{
					$field_value = clear_input(trim($value));
					$fields_name.= ", ".trim($field)." = '".$field_value."'";
				}
			}
			else
			{
				if(trim($fields_name) == "")
				{
					$fields_name = trim($field);
					$field_value = "'".clear_input(trim($value))."'";
				}
				else
				{
					$fields_name.= ",".trim($field);
					$field_value.= ",'".clear_input(trim($value))."'";
				}
			}
		}
		$table_name = trim($key);
	}

	if($where == "")
		$query = "Insert into ".$table_name." (".$fields_name.") values(".$field_value.")"; 
	else
		$query = "update ".$table_name." set ".$fields_name." where ".$where ;
	
	if($debug == true){
		echo "<span><font color=green>".$query."</font></span>";
	}
	else
	{
		//mysqli_query($connection_string,$query);
		$mysqli->query($query) or die($mysqli->error.__LINE__);
	}
	if($where == "")
		return $mysqli->insert_id;
}