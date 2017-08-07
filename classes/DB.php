<?php
require_once('config/db.php');

class DB
{
	/**
	 * @var mysqli $_conn
	 */
	static protected $_instance;

	private
		$_conn;


	static public function Instance()
	{
		if (null === self::$_instance)
			self::$_instance = new self();

		return self::$_instance;
	}

	public function __clone()
	{
		throw new Exception('Clone is forbidden');
	}

	private function __construct()
	{
		$this->connect();
	}

	/**
	 * @return bool
	 */
	public function connect()
	{
		$this->_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$this->_conn->set_charset('utf8');
		if ($this->_conn->connect_error) return false;
		else return true;
	}


	public function selectRaw($sql)
	{
		if ($query = $this->_conn->query($sql))
		{
			$ret = array();

			while ($obj = $query->fetch_object())
			{
				$ret[] = $obj;
			}

			$query->close();

			return $ret;
		}
	}

	/**
	 * @param $table
	 * @param null $where
	 * @param string $column
	 * @param string $join
	 * @param string $joinOn
	 * @param bool $numRows
	 * @return array
	 */
	public function select($table, $where = null, $column = '*', $join = '', $joinOn = '', $numRows = false)
	{

		if ($column != '*')
			$sql = "SELECT `$column` FROM `$table`";
		else $sql = "SELECT $column FROM `$table`";

		if ($join != '' && $joinOn != '')
			$sql .= "LEFT JOIN $join on $joinOn";

		if ($where)
			$sql .= " WHERE $where";


		if ($query = $this->_conn->query($sql))
		{
			if ($numRows)
			{
				$ret = $query->num_rows;
			}
			else
			{
				$ret = array();

				while ($obj = $query->fetch_object())
				{
					$ret[] = $obj;
				}

				$query->close();
			}

			return $ret;
		}
	}

	/**
	 * @param string $table
	 * @param array $value
	 * @return bool
	 * @todo escape value
	 */
	public function insert($table, $value, $escape = false)
	{
		if ($escape)
			$sql = 'INSERT INTO `' . $table . '` (`' . mysqli_real_escape_string($this->_conn, implode('`, `', array_keys($value))) . '`) VALUES ("' . mysqli_real_escape_string($this->_conn, implode('", "', $value)) . '")';
		else
			$sql = 'INSERT INTO `' . $table . '` (`' . implode('`, `', array_keys($value)) . '`) VALUES ("' . implode('", "', $value) . '")';

		var_dump($sql);
		if ($this->_conn->query($sql)) return $this->_conn->insert_id;
		else return false;
	}

	/**
	 * @param string $table
	 * @param array $value
	 * @param string $where
	 * @param bool $escape
	 * @return bool
	 */
	public function update($table, array $value, $where, $escape = false)
	{
		$args = array();

		foreach ($value as $k => $v)
			$args[] = '`' . $k . '`="' . mysqli_real_escape_string($this->_conn, $v) . '"';

		if ($escape)
			$sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $args) . ' WHERE ' . $where;
		else
			$sql = 'UPDATE `' . $table . '` SET ' . implode(',', $args) . ' WHERE ' . $where;

		$this->_conn->query($sql);

		return $this->_conn->affected_rows;
	}

	/**
	 * @param string $table
	 * @param string $where
	 * @return bool
	 */
	public function delete($table, $where)
	{
		$sql = "DELETE FROM `$table` WHERE $where";

		if ($this->_conn->query($sql)) return true;
		else return false;
	}

	public function truncate($table)
	{
		$sql = "TRUNCATE TABLE `$table`";

		if ($this->_conn->query($sql)) return true;
		else return false;
	}
}