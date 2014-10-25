<?php

/*
* MADSQLi Class - PHP MySQLi Class
*
* Author: Patrik Sadaić (http://github.com/psadaic)
* Email: patriksadaic@gmail.com
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
* Copyright (C) 2014
*
* Version 1.0.0 (25/10/2014)
* --- Fast and easy connection to database using mysqli
* --- Select data function
* --- Insert data function
* --- Update data function
* --- Delete data function
* --- Custom query function (e.g. CREATE TABLE, ...)
*
*/

class MADSQLi {

	//Base variables
	protected $connection;
	protected $host;
	protected $user;
	protected $pass;
	protected $db;
	protected $port;
	protected $thread;

	//Class constructor
	public function __construct($host = NULL, $user = NULL, $pass = NULL, $db = NULL , $port = NULL) 
	{
		error_reporting(E_ERROR | E_PARSE);
		if((isset($host))&&(!empty($host))&&(isset($user))&&(!empty($user))&&(isset($db))&&(!empty($db)))
		{
			$this->host = $host;
			$this->user = $user;
			$this->db = $db;
			if($pass==NULL)
			{
				$this->pass = '';
			}
			else
			{
				$this->pass = $pass;
			}
			if($port==NULL)
			{
				$this->port = 3306;
			}
			else
			{
				$this->port = $port;
			}
		}
		else
		{
			die('Invalid connection data.');
		}
		$this->open_connection();
	}

	//Private function - establish mysqli connection
	private function open_connection()
	{
		$this->connection = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);
		if($this->connection->connect_error)
		{
			die("Connection error.");
		}
		else
		{
			$this->thread = $this->connection->thread_id;
			$this->connection->set_charset("utf8");
		}
	}

	//Public function - select data from database
	public function select($table = NULL, $cols = NULL, $where = NULL, $order_by = NULL, $limit = NULL)
	{
		if((isset($table))&&(!empty($table)))
		{
			$mysqli = $this->connection;
			if(empty($cols))
			{
				$colso = '*';
			}
			else
			{
				$colss="";
				foreach ($cols as $col)
				{
					$colss=$colss.$col.', ';
				}
				$colso=substr($colss,0,-2);
			}
			$sql = "SELECT $colso FROM $table";
			if(!empty($where))
			{
				$sql.=" WHERE $where";
			}
			if(!empty($order_by))
			{
				$sql.=" ORDER BY $order_by";
			}
			if(!empty($limit))
			{
				$sql.=" LIMIT $limit";
			}
			$out = $mysqli->query($sql);
			if(!$out === false) 
			{
  				$arr = $out->fetch_all(MYSQLI_ASSOC);
  				return($arr);
			}
			else
			{
				die('Invalid select data input.');
			}
			$mysqli->close();
		}
	}

	//Public function - insert data in database
	public function insert($table = NULL, $cols = NULL, $values = NULL)
	{
		if((isset($table))&&(!empty($table)))
		{
			if((isset($cols))&&(!empty($cols)))
			{
				if((isset($values))&&(!empty($values)))
				{
					$mysqli = $this->connection;
					$colss="";
					foreach ($cols as $col)
					{
						$colss.=$col.', ';
					}
					$colso=substr($colss,0,-2);
					$valss="";
					foreach ($values as $val)
					{
						$valss.="'".$val."', ";
					}
					$valso=substr($valss,0,-2);
					$sql="INSERT INTO $table ($colso) VALUES ($valso)";
					$out = $mysqli->query($sql);
					if($out === false) 
					{
						die('Invalid insert data input.');
					}
					$mysqli->close();
				}
			}
		}
	}

	//Public function - update data in database
	public function update($table = NULL, $cols = NULL, $values = NULL, $where = NULL)
	{
		if((isset($table))&&(!empty($table)))
		{
			if((isset($cols))&&(!empty($cols)))
			{
				if((isset($values))&&(!empty($values)))
				{
					if((isset($where))&&(!empty($where)))
					{
						$i=0;
						foreach ($cols as $col)
						{
							$cdb[$i]=$col;
							$i++;
						}
						$i=0;
						foreach ($values as $val)
						{
							$vdb[$i]=$val;
							$i++;
						}
						if(count($cdb)==count($vdb))
						{
							$cvdb="";
							for($i=0;$i<count($cdb);$i++)
							{
								$cvdb.=$cdb[$i]."=".$vdb[$i].", ";
							}
							$cvdbo=substr($cvdb,0,-2);
							$sql="UPDATE $table SET $cvdbo WHERE $where";
							$mysqli = $this->connection;
							$out = $mysqli->query($sql);
							if($out === false) 
							{
								die('Invalid update data input.');
							}
							$mysqli->close();
						}
					}
				}
			}
		}
	}

	//Public function - delete data from database
	public function delete($table = NULL, $where = NULL)
	{
		if((isset($table))&&(!empty($table)))
		{
			if((isset($where))&&(!empty($where)))
			{
				$sql="DELETE FROM $table WHERE $where";
				$mysqli = $this->connection;
				$out = $mysqli->query($sql);
				if($out === false) 
				{
					die('Invalid delete data input.');
				}
				$mysqli->close();
			}
		}
	}

	//Public function - custom sql query
	public function custom_query($sql = NULL, $return = NULL)
	{
		if((isset($sql))&&(!empty($sql)))
		{
			$mysqli = $this->connection;
			$out = $mysqli->query($sql);
			if($return==1)
			{
				if(!$out === false) 
				{
  					$arr = $out->fetch_all(MYSQLI_ASSOC);
  					return($arr);
				}
			}
			if($out === false) 
			{
				die('Invalid custom query data input.');
			}
			$mysqli->close();
		}
	}

	//Public function - close/kill mysqli connection
	public function kill()
	{
		$this->connection->close();
		$this->connection->kill($this->thread);
	}

}

?>