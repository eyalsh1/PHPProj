<?php
/*namespace Lib\Entities;

trait SelectAll {
	public static function selectAll():array {
		$result = $db->query("SELECT * FROM " . self::$table_name . " LIMIT 1000");
		$rows = [];
		while ($row = $result->fetch(\PDO::FETCH_NUM)) {
			$rows []= new self(...$row);
		}
		return $rows;
	}
	public static function selectOne(\PDO $db, int $id): self {
		$stmt = $db->prepare("SELECT * FROM " . self::$table_name . " WHERE id = :id LIMIT 1");
		$stmt->bindValue(':id', (int)$id, \PDO::PARAM_INT);
		$stmt->execute();

		return new self(...$stmt->fetch(\PDO::FETCH_NUM));
	}
}*/