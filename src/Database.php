<?php
// namespace DB\src;

require_once __DIR__.'/../database/connection.php';

class MySql{
   public static $conn;
   public static function setConnection($conn){
     self::$conn = $conn;
   }
   public static function selectPost($condition = null)
   {
      $pdo = self::$conn;
      $sql = "SELECT * FROM post ORDER BY post.id DESC";
      if (!empty($condition)) {
        $sql = "SELECT * FROM post $condition ORDER BY post.id DESC";
      }  
      $statement = $pdo->query($sql);

      return $statement->fetchAll(PDO::FETCH_NAMED);
   }
}