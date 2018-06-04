<?php
    class db{
        // properties
        private $dbhost = 'localhost';
        private $dbuser = 'root';
        private $dbpass = '';
        private $dbname = 'kargodb'; // your database name

        public function getHost(){
            return $this->dbhost;
        }
        public function getDBname(){
            return $this->dbname;
        }
        public function getUser(){
            return $this->dbuser;
        }

        // Connection Method
        public function connect(){
            $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
            $dbConnection = new PDO($mysql_connect_str,
                $this->dbuser,
                $this->dbpass,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//            $dbConnection->exec("set names UTF-8");
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }

        // TR Convention
        function trCharConverterJson($json){
            $Sorunlu = array("\u00fc","\u011f","\u0131","\u015f","\u00e7","\u00f6","\u00dc","\u011e","\u0130","\u015e","\u00c7","\u00d6","\/");
            $Duzeltilecek = array("ü","ğ","ı","ş","ç","ö","Ü","Ğ","İ","Ş","Ç","Ö","/");
            $Duzelmis = str_replace($Sorunlu, $Duzeltilecek, json_encode($json));
            return $Duzelmis;
        }
    }