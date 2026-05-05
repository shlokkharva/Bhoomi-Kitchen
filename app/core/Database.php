<?php
  /*
   * PDO Database Class
   * Connect to database
   * Create prepared statements
   * Bind values
   * Return rows and results
   */
  class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
      $port = defined('DB_PORT') ? DB_PORT : '3306';
      $driver = defined('DB_DRIVER') ? DB_DRIVER : 'mysql';
      
      if ($driver === 'pgsql') {
        // PostgreSQL DSN for Supabase
        $dsn = "pgsql:host={$this->host};port={$port};dbname={$this->dbname};sslmode=require";
      } else {
        // MySQL DSN for local
        $dsn = "mysql:host={$this->host};port={$port};dbname={$this->dbname};charset=utf8mb4";
      }

      $options = array(
        PDO::ATTR_PERSISTENT    => true,
        PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      );

      // Create PDO instance
      try{
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        $this->showDbError($this->error);
      }
    }

    /**
     * Show a friendly error page when MySQL cannot be reached.
     * Stops execution so nothing downstream crashes.
     */
    private function showDbError($msg){
      $port = defined('DB_PORT') ? DB_PORT : '3306';
      http_response_code(500);
      die('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
      <title>Database Error</title>
      <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:Inter,sans-serif;background:#0f0f1a;color:#f1f5f9;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:2rem}
        .box{max-width:560px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:2.5rem;text-align:center}
        h1{font-size:1.8rem;font-weight:800;color:#ef4444;margin-bottom:.5rem}
        p{color:#94a3b8;line-height:1.7;margin:.5rem 0}
        code{background:rgba(255,255,255,.1);padding:.2rem .5rem;border-radius:6px;font-size:.85rem;color:#f97316}
        ul{text-align:left;color:#94a3b8;line-height:2;margin:1rem 0 0 1.5rem}
        .step{background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);border-radius:8px;padding:.8rem 1rem;margin-top:.8rem;font-size:.85rem;text-align:left}
      </style></head><body>
      <div class="box">
        <div style="font-size:3rem;margin-bottom:1rem">⚠️</div>
        <h1>MySQL Not Reachable</h1>
        <p>The app cannot connect to the database.</p>
        <p><strong>Error:</strong> <code>'.htmlspecialchars($msg).'</code></p>
        <div class="step">
          <strong>Fix checklist:</strong>
          <ul>
            <li>Open <strong>XAMPP Control Panel</strong></li>
            <li>Click <strong>Start</strong> next to <strong>MySQL</strong></li>
            <li>If MySQL shows port <strong>3307</strong>, open<br>
                <code>app/config/config.php</code> and change<br>
                <code>DB_PORT</code> from <code>3306</code> → <code>3307</code></li>
            <li>Import <code>database.sql</code> then <code>sample_data.sql</code><br>in <strong>phpMyAdmin</strong> if not done yet</li>
            <li>Refresh this page</li>
          </ul>
        </div>
        <p style="margin-top:1.2rem;font-size:.82rem">Current config: <code>'.DB_HOST.':'.$port.'</code> · DB: <code>'.DB_NAME.'</code></p>
      </div></body></html>');
    }

    // Prepare statement with query
    public function query($sql){
      $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null){
      if(is_null($type)){
        switch(true){
          case is_int($value):
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value):
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $type = PDO::PARAM_NULL;
            break;
          default:
            $type = PDO::PARAM_STR;
        }
      }

      $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(){
      return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single(){
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount(){
      return $this->stmt->rowCount();
    }
    
    // Get last inserted id
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
  }
