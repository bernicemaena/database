<?php
 include_once("DbManager.php");

 class Locations{
   private $db_handle = null;

   public function __construct(){
    $db = new DbManager();
    $this->db_handle = $db->getHandle();
   }

   public function getAllCountries(){
    $countries = array();
    $query = "select name from country";

    try{
      $this->db_handle->beginTransaction();

      $stmt = $this->db_handle->prepare($query);
      $stmt->execute();

      $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $this->db_handle->commit();
    }catch(PDOException $e){}

     return $countries;
   }

   public function getAllCities(){
     $countries = array();
     $query = "select name from city";

     try{
       $this->db_handle->beginTransaction();

       $stmt = $this->db_handle->prepare($query);
       $stmt->execute();

       $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

       $this->db_handle->commit();
     }catch(PDOException $e){}

      return $countries;
   }

   public function getAllRegions(){
     $regions = array();
     $query = "select distinct region from country";

     try{
       $this->db_handle->beginTransaction();

       $stmt = $this->db_handle->prepare($query);
       $stmt->execute();

       $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);

       $this->db_handle->commit();
     }catch(PDOException $e){}


      return $regions;
   }

   public function getCountryCode($country){
     $query = "select code from country where name = ?";

     try{
       $this->db_handle->beginTransaction();

       $stmt = $this->db_handle->prepare($query);
       $stmt->execute(array($country));

       $code = $stmt->fetch(PDO::FETCH_ASSOC)['code'];

       $this->db_handle->commit();
     }catch(PDOException $e){ }

     return $code;
   }

   public function getCountryCities($country){
     $cities = array();
     $query0 = "select code from country where name = ?";
     $query = "select distinct name from city where countrycode = ?";

     try{
        $this->db_handle->beginTransaction();

        $stmt0 = $this->db_handle->prepare($query0);
        $stmt0->execute(array($country));

        $country_c = $stmt0->fetch(PDO::FETCH_ASSOC);
        #echo($country_c['code']);

        $stmt = $this->db_handle->prepare($query);
        $stmt->execute(array($country_c['code']));

        $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db_handle->commit();
     }catch(PDOException $e){}

     return $cities;
   }

   public function getCitiesCountry($city){
     $country = null;
     
     $query0 = "select countrycode from city where name = ?";
     $query = "select name from country where code = (select countrycode from city where name = ?)";

     try{
       /*
         $stmt0 = $this->db_handle->prepare($query0);
         $stmt0->execute(array($city));
         $res0 = $stmt0->fetch(PDO::FETCH_ASSOC);
         $code = $res0['countrycode'];
       */
       $stmt1 = $this->db_handle->prepare($query);
       $stmt1->execute(array($city));
       $res1 = $stmt1->fetch(PDO::FETCH_ASSOC);
       $country = $res1['name'];

     }catch(PDOException $e){ }

     return $country;
   }

   public function getCitiesRegion($city){
     $query0 = "select countrycode from city where name =?";
     $query = "select region from country where code =?";
     $region = null;

     try{
       $this->db_handle->beginTransaction();

       $stmt0 = $this->db_handle->prepare($query0);
       $stmt0->execute(array($city));
       $res0 = $stmt0->fetch(PDO::FETCH_ASSOC);
       $code = $res0['countrycode'];

       $stmt1 = $this->db_handle->prepare($query);
       $stmt1->execute(array($code));
       $res1 = $stmt1->fetch(PDO::FETCH_ASSOC);
       $country = $res1['name'];
       #echo($country);

       #echo($code);
       $this->db_handle->commit();
     }catch(PDOException $e){ }

     return $country;
   }


   public function getCitiesLike($hint){
     $countries = array();
     $query = "select name from city where name like ?";

     try{
       $this->db_handle->beginTransaction();

       $stmt = $this->db_handle->prepare($query);
       $stmt->execute(array("$hint%"));

       $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

       $this->db_handle->commit();
     }catch(PDOException $e){}

      return $countries;
   }

   public function __destruct(){}

 }

?>
