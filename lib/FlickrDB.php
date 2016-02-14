<?php
class FlickrDB
{
	// property declaration
	public static $version = "0.1";
	private $db;
	public static $sizeDelimeter = "#";
	private $_flickr = false;
	private $_config = false;

	const ERROR_NONE = true;
	const ERROR_NO_PHOTOS = 1;
	const IMAGE_LOOP_DELIMETER = "#IMAGE_LOOP#";
	const IMAGE_URL_SIZE_DELIMETER = "#IMAGE_URL_SIZE_";

	const SET_STATUS_ACTIVE = 0;
	const SET_STATUS_INACTIVE = 1;

	const SET_TYPE_MAIN = 0;
	const SET_TYPE_OTHER = 1;
	const SET_TYPE_VARIANTS = 2;
	const SET_TYPE_FRONTPAGE = 3;

	const POPULARITY_MULTIPLIER_VIEWS = 1;
	const POPULARITY_MULTIPLIER_LIKES = 10;
	const POPULARITY_MULTIPLIER_SHARES = 50;
	const POPULARITY_MULTIPLIER_COMMENTS = 100;
	const POPULARITY_MULTIPLIER_BUYS = 1000;


	// method declaration
	function __construct($flickr,$config)
	{

		require_once "FlickrHA.php";
		require_once "FlickrPhotoHA.php";
		require_once "DB.php";
		if (!($flickr instanceof FlickrHA)) throw new Exception('FlickrHA must be defined');
		$this->_flickr = $flickr;
		$this->_config = $config;

	}
	private function _connectDB(){
		if($this->db) return;

		$this->db = new DB($this->_config->user,$this->_config->password,$this->_config->host,$this->_config->database);
		//$this->db = new DB("hellepho_user1","f{b5E_7!TKLU","localhost","hellepho_image_service");
	}

	public static function createErrorObject($error){
		$ret = array(
				"errors" =>true,
				"message"=>$error
		);

		return (object) $ret;
	}

	public function deletePhoto($setId,$photo)
	{

		$this->_connectDB();
		$sql = "DELETE FROM images WHERE set_id=" . $this->db->quote($setId) . " AND id=" . $this->db->quote($photo->id);
		$this->db->delete($sql);

	}
	public function deleteSetPhotos($setId)
	{

		$this->_connectDB();
		$sql = "DELETE FROM images WHERE set_id=".$this->db->quote($setId);
		$this->db->delete($sql);

	}
	public function savePhoto($setId,$photo,$originalSource,$xlSource){

		$this->_connectDB();

		//$this->deletePhoto($setId,$photo);

		$sql = "INSERT INTO images (set_id,id,secret,server,farm,original_width,original_height,size_ratio, sizes,comments,original_source,xl_source,added) VALUES (";

		$width = $photo->width;
		$height = $photo->height;
		$size_ratio = ($height===0 || $width===0) ? 0 : $width/$height;
		$sql .=$this->db->quote($setId);
		$sql .=",".$this->db->quote($photo->id);
		$sql .=",".$this->db->quote($photo->secret);
		$sql .=",".$this->db->quote($photo->server);
		$sql .=",".$this->db->quote($photo->farm);
		$sql .=",".$width;
		$sql .=",".$height;
		$sql .=",".$size_ratio;
		$sql .=",".$photo->sizes;
		$sql .=",".$this->db->quote($photo->comments);
		$sql .=",".$this->db->quote($originalSource);
		$sql .=",".$this->db->quote($xlSource);
		$sql .=", NOW()";

		$sql .=")";
		//echo "d:".$sql;
		$ret = $this->db->insert($sql);
		//var_dump($ret);
		if(!$ret) return FlickrDB::createErrorObject("error:".$this->db->error.",sql:".$sql);
		return $ret;


	}

	public function getDBSetPhotos($id,$orderBy="db_id",$dir="ASC",$status=false,$num=0,$page=0){
		$this->_connectDB();
		$id = intval($id);
		$num = intval($num);
		$page = intval($page);
		$orderBy = urlencode($orderBy);
		$dir= urlencode($dir);

		if($id>0) $sql = "SELECT * FROM images WHERE set_id=".$id;
		else $sql = "SELECT * FROM images WHERE set_id>0";
		if($status !== false) $sql .=" AND status=". intval($status);
		$sql .=" ORDER BY ".$orderBy." ".$dir;

		if($num >0) $sql .=" LIMIT ".$num *$page.",".$num;
		$ret = $this->db->select($sql);
		if($this->db->error !==false) return $this->db->error;
		if(!isset($ret[0])) return false;
		return $ret;
	}

	/*
       public function getSetId($flickrId){
             $this->_connectDB();
             $sql = "SELECT id FROM sets WHERE flickr_id=".$this->db->quote($flickrId);
            $ret = $this->db->select($sql);
            if($ret===false) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            if(!is_array($ret) || !isset($ret[0]) || !isset($ret[0]["id"])) return 0;
            return intval($ret[0]["id"]);
        }

         public function getFlickrId($setId){
             $this->_connectDB();
             $sql = "SELECT flickr_id FROM sets WHERE id=".intval($setId);
            $ret = $this->db->select($sql);
            if($ret===false) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            if(!is_array($ret) || !isset($ret[0]) || !isset($ret[0]["flickr_id"])) return 0;
            return intval($ret[0]["flickr_id"]);
        }


        public function saveCompleteSet($flickrId,$name,$hash=false,$status=false,$type=false,$view_rights=0,$download_rights=0,$expires=false){
            $photos = $this->_flickr->getSetPhotos($flickrId);
            //print_r("err:".FlickrHA::hasError($photos));

            if(FlickrHA::hasError($photos)) return FlickrViewer::createErrorObject("Error while getting set photos:".$photos->message); //???create common error object
            $setId = $this->saveSetData($flickrId,$name,$hash,$status,$type,$view_rights,$download_rights,$expires);
            if(FlickrViewer::hasError($setId)) return FlickrViewer::createErrorObject("Error while saving set :".$setId->message);

            //print_r($photos["photoset"]["photo"]);
            //return;
            foreach($photos["photoset"]["photo"] as $photo){
                //print_r($photo);
                //continue;
                set_time_limit(20);
                $sizes = $this->_flickr->getPhotosSizes($photo);
                $originalSecret = 	$this->_flickr->getPhotoInfo($photo);
                if(FlickrHA::hasError($originalSecret)) return FlickrViewer::createErrorObject("Error while getting original secret :".$originalSecret->message);
                $originalSecret = $originalSecret["originalsecret"];
                $photoId = $this->savePhotoData($setId,$photo,$originalSecret,$sizes);
                if(FlickrViewer::hasError($photoId)) return FlickrViewer::createErrorObject("Error while saving set photo :".$photoId->message);
            }

            return $setId;
        }

        public function saveSetData($flickrId,$name,$hash=false,$status=false,$type=false,$view_rights=0,$download_rights=0,$expires=false){

            $this->_connectDB();
            if(!$hash || empty($hash)) $hash = FlickrViewer::createHash("set",$flickrId);
            if($status===false) $status = FlickrViewer::SET_STATUS_ACTIVE;
            else $status = intval($status);
            if($type===false) $type = FlickrViewer::SET_TYPE_MAIN;
            else $type = intval($type);
            $sql = "INSERT INTO sets (flickr_id,name,hash,status,type,view_rights,download_rights,expires) VALUES (";
            $sql .=$this->db->quote($flickrId);
            $sql .=",".$this->db->quote($name);
            $sql .=",".$this->db->quote($hash);
            $sql .=",".$status;
            $sql .=",".$type;
            $sql .=",".intval($view_rights);
            $sql .=",".intval($download_rights);
            if(!$expires || empty($expires)) $sql .=",NULL";
            else $sql .=",".$this->db->quote($expires);
            $sql .=")";

            $ret = $this->db->insert($sql);
            if(!$ret) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            return $ret;


        }

       public function savePhotoData($setId,$photo,$originalSecret,$sizes){

               $this->_connectDB();
            $sql = "INSERT INTO images (set_id,image_id,secret,server_id,farm_id,original_secret,original_width,original_height,size_ratio, sizes,added) VALUES (";

            $width = intval($sizes[FlickrPhotoHA::SIZE_ORIGINAL]["width"]);
            $height = intval($sizes[FlickrPhotoHA::SIZE_ORIGINAL]["height"]);
            $size_ratio = ($height===0 || $width===0) ? 0 : $width/$height;
            $sizes = FlickrViewer::sizesArrayToValue($sizes);
            $sql .= intval($setId);
            $sql .=",".$this->db->quote($photo["id"]);
            $sql .=",".$this->db->quote($photo["secret"]);
            $sql .=",".$this->db->quote($photo["server"]);
            $sql .=",".$this->db->quote($photo["farm"]);
            $sql .=",".$this->db->quote($originalSecret);
            $sql .=",".$width;
            $sql .=",".$height;
            $sql .=",".$size_ratio;
            $sql .=",".$sizes;
            $sql .=", NOW()";

            $sql .=")";
            //return $sql;
            $ret = $this->db->insert($sql);
            if(!$ret) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            return $ret;


        }

          public function reloadSetPhotos($setId){

            $flickrId = $this->getFlickrId($setId);
            $photos = $this->_flickr->getSetPhotos($flickrId);


            if(FlickrHA::hasError($photos)) return FlickrViewer::createErrorObject("Error while getting set photos:".$photos->message); //???create common error object

            $this->updatePhotoStatus(false,FlickrPhotoHA::STATUS_REMOVED_IN_SET_UPDATE,$setId); //??? ERROR?

            //print_r($photos["photoset"]["photo"]);
            //return;
            foreach($photos["photoset"]["photo"] as $photo){
                //print_r($photo);
                //continue;
                set_time_limit(20);
                $dbPhoto = $this->getDBPhoto(0,$photo["id"]);
                if($dbPhoto !==false) {
                    $this->updatePhotoStatus($dbPhoto["id"],FlickrPhotoHA::STATUS_OK);
                    continue; //if error???
                }
                $sizes = $this->_flickr->getPhotosSizes($photo);
                $originalSecret = 	$this->_flickr->getPhotoInfo($photo);
                if(FlickrHA::hasError($originalSecret)) return FlickrViewer::createErrorObject("Error while getting original secret :".$originalSecret->message);
                $originalSecret = $originalSecret["originalsecret"];
                $photoId = $this->savePhotoData($setId,$photo,$originalSecret,$sizes);
                if(FlickrViewer::hasError($photoId)) return FlickrViewer::createErrorObject("Error while saving set photo :".$photoId->message);
            }

            return true;
        }

          public function updatePhotoStats($photoId,$views=0,$likes=0,$shares=0,$buys=0,$comments=0){
            $this->_connectDB();
            $sql = "UPDATE images SET";

            $photoId = intval($photoId);
            $views = $views === "+" ? $views : intval($views);
            $likes = $likes === "+" ? $likes : intval($likes);
            $shares = $shares === "+" ? $shares : intval($shares);
            $buys = $buys === "+" ? $buys : intval($buys);
            $comments = $comments === "+" ? $comments : intval($comments);

            if($views === "+") $sql .=" views=views+1";
            else if($views >0) $sql .=" views=".$views;
            else $sql .=" views=views"; //just to avoid checking the sql clause for "," with next values

            if($likes === "+") $sql .=" ,likes=likes+1";
            else if($likes >0) $sql .=" ,likes=".$likes;
            else $sql .=" ,likes=likes"; //just to avoid checking the sql clause for "," with next values

            if($shares === "+") $sql .=" ,shares=shares+1";
            else if($shares >0) $sql .=" ,shares=".$shares;
            else $sql .=" ,shares=shares"; //just to avoid checking the sql clause for "," with next values

            if($buys === "+") $sql .=" ,buys=buys+1";
            else if($buys >0) $sql .=" ,buys=".$buys;
            else $sql .=" ,buys=buys"; //just to avoid checking the sql clause for "," with next values

            if($comments === "+") $sql .=" ,comments=comments+1";
            else if($comments >0) $sql .=" ,comments=".$comments;
            else $sql .=" ,comments=comments"; //just to avoid checking the sql clause for "," with next values

            $sql .=" WHERE id=".$photoId;

            //return $sql;
            $ret = $this->db->update($sql);

            if(!$ret && $ret !== 0) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);

            $sql = "UPDATE images SET popularity=views*".FlickrViewer::POPULARITY_MULTIPLIER_VIEWS."+likes*".FlickrViewer::POPULARITY_MULTIPLIER_LIKES;
            $sql .=" WHERE id=".$photoId;
            $ret = $this->db->update($sql);
            if(!$ret && $ret !== 0) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);

            return $ret; //returns number of affected rows

        }

        public function updatePhotoStatus($photoId,$status,$setId=false){

            $this->_connectDB();

            $photoId = intval($photoId);
            $status = intval($status);
            if($setId) $setId = intval($setId);

            if($setId) $sql = "UPDATE images SET status=".$status." WHERE set_id=".$setId;
            else $sql = "UPDATE images SET status=".$status." WHERE id=".$photoId;
            //return $sql;
            $ret = $this->db->update($sql);
            if(!$ret && $ret !== 0) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            return $ret; //returns number of affected rows

        }

        public function updatePhotoDescription($photoId,$description){

            $this->_connectDB();

            $photoId = intval($photoId);
            $description = $this->db->quote($description);

            $sql = "UPDATE images SET description=".$description." WHERE id=".$photoId;

            //return $sql;
            $ret = $this->db->update($sql);
            if(!$ret && $ret !== 0) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            return $ret; //returns number of affected rows

        }

        public function updatePhotoVariantSetId($photoId,$variantSetId){

            $this->_connectDB();

            $photoId = intval($photoId);
            $variantSetId = intval($variantSetId);

            $sql = "UPDATE images SET variant_set_id=".$variantSetId." WHERE id=".$photoId;

            //return $sql;
            $ret = $this->db->update($sql);
            if(!$ret && $ret !== 0) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            return $ret; //returns number of affected rows

        }

        public function updateSetStats($setId,$likes=0,$shares=0,$comments=0){
            $this->_connectDB();
            $sql = "UPDATE sets SET";

            $setId = intval($setId);

            $likes = $likes === "+" ? $likes : intval($likes);
            $shares = $shares === "+" ? $shares : intval($shares);
            $comments = $comments === "+" ? $comments : intval($comments);

            if($likes === "+") $sql .=" likes=likes+1";
            else if($likes >0) $sql .=" likes=".$likes;
            else $sql .=" likes=likes"; //just to avoid checking the sql clause for "," with next values

            if($shares === "+") $sql .=" ,shares=shares+1";
            else if($shares >0) $sql .=" ,shares=".$shares;
            else $sql .=" ,shares=shares"; //just to avoid checking the sql clause for "," with next values

            if($comments === "+") $sql .=" ,comments=comments+1";
            else if($comments >0) $sql .=" ,comments=".$comments;
            else $sql .=" ,comments=comments"; //just to avoid checking the sql clause for "," with next values


            $sql .=" WHERE id=".$setId;

            //return $sql;
            $ret = $this->db->update($sql);
            if(!$ret && $ret !== 0) return FlickrViewer::createErrorObject("error:".$this->db->error.",sql:".$sql);
            return $ret; //returns number of affected rows

        }

        public function getDBUser($id,$hash=false){
             $id = intval($id);
             $this->_connectDB();
             if($hash===false) $sql = "SELECT * FROM users WHERE id=".$id." LIMIT 1";
             else $sql = "SELECT * FROM users WHERE hash=\"".$hash."\" LIMIT 1";
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret[0];
        }

        public function getSetRights($setId,$userId){
             $setId = intval($setId);
              $userId = intval($userId);
             $this->_connectDB();
             $sql = "SELECT view_rights,download_rights FROM rights WHERE set_id=".$setId." AND user_id=".$userId." LIMIT 1";
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret[0];
        }

        public function getDBPhoto($id,$flickrId=false){
             $id = intval($id);
             if($flickrId) $flickrId = $this->db->quote($flickrId);
             $this->_connectDB();
             if($flickrId) $sql = "SELECT * FROM images WHERE image_id=".$flickrId." LIMIT 1";
             else $sql = "SELECT * FROM images WHERE id=".$id." LIMIT 1";
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret[0];
        }

        public function getDBSet($id,$hash=false,$type=false){
             $id = intval($id);
             $this->_connectDB();
             if($hash) $hash = $this->db->quote($hash);
             if($type) $type = intval($type);
             if($hash ===false && $type ===false) $sql = "SELECT * FROM sets WHERE id=".$id." LIMIT 1";
             else if($type !==false) $sql = "SELECT * FROM sets WHERE type=".$type." LIMIT 1";
             else $sql = "SELECT * FROM sets WHERE hash=\"".$hash."\" LIMIT 1";
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret[0];
        }

        public function getDBSets($orderBy="id",$dir="ASC",$status=false,$type=false){
             $this->_connectDB();
             $orderBy = urlencode($orderBy);
             $dir= urlencode($dir);

             if($status) $status = intval($status);
             else $status = FlickrViewer::SET_STATUS_ACTIVE;


            $sql = "SELECT * FROM sets WHERE id>0";
            if($status !== false) $sql.=" AND status=".intval($status);
            if($type !== false) $sql.=" AND type=".intval($type);
            $sql .=" ORDER BY ".$orderBy." ".$dir;
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret;
        }

        public function getDBSetUsers($id){
             $id = intval($id);
             $this->_connectDB();
            $sql = "SELECT rights.*, users.*, users.expires AS user_expires FROM rights LEFT JOIN users ON users.id = rights.user_id WHERE rights.set_id=".$id;
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret;
        }

        public function getDBSetPhotos($id,$orderBy="id",$dir="ASC",$status=false,$num=0,$page=0){
            $this->_connectDB();
             $id = intval($id);
             $num = intval($num);
             $page = intval($page);
             $orderBy = urlencode($orderBy);
             $dir= urlencode($dir);

            if($id>0) $sql = "SELECT * FROM images WHERE set_id=".$id;
            else  $sql = "SELECT * FROM images WHERE set_id>0";
            if($status !== false) $sql .=" AND status=". intval($status);
            $sql .=" ORDER BY ".$orderBy." ".$dir;



             if($num >0) $sql .=" LIMIT ".$num *$page.",".$num;
             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;
             return $ret;
        }

        public function getDBSetData($setId){
             $setId = intval($setId);

             $this->_connectDB();
             $sql = "SELECT COUNT(*) AS image_count,SUM(views) AS view_count,SUM(likes) AS like_count,SUM(shares) AS share_count,SUM(buys) AS buy_count,SUM(comments) AS comment_count FROM images WHERE status=".FlickrPhotoHA::STATUS_OK." AND set_id=".$setId;

             $ret = $this->db->select($sql);
             if($this->db->error !==false) return $this->db->error;
             if(!isset($ret[0])) return false;

             return $ret[0];
        }

        public function buildSiteMap(){

            $frontpage = $this->getDBSet(false,false,FlickrViewer::SET_TYPE_FRONTPAGE);
            $map = FlickrViewer::buildMapObject("#frontpage","frontpage",$frontpage["id"]);

            $map->properties->data->like_count = $frontpage["likes"];
            $map->properties->data->share_count = $frontpage["shares"];
            $map->properties->data->comment_count = $frontpage["comments"];

            $sets = $this->getDBSets("order_num","DESC",FlickrViewer::SET_STATUS_ACTIVE,FlickrViewer::SET_TYPE_MAIN);
            foreach($sets as $set){
                $setData =$this->buildSetMap($set["id"]);
                array_push($map->subpages,$setData);

            }

            return json_encode(array("pages"=>array($map)));
        }

        public function buildSetMap($setId){
            $dbSet = $this->getDBSet($setId);
            $set = FlickrViewer::buildMapObject("#set-".$setId,"set",$setId);

            $set->properties->data->like_count = $dbSet["likes"];
            $set->properties->data->share_count = $dbSet["shares"];
            $set->properties->data->comment_count = $dbSet["comments"];
            $photos = $this->getDBSetPhotos($setId,"popularity","DESC",FlickrPhotoHA::STATUS_OK);
            foreach($photos as $photo){
                $photoData =$this->buildPhotoMap($photo["id"]);
                array_push($set->subpages,$photoData);

            }
            return $set;
        }

        public function buildPhotoMap($photoId){
            $dbPhoto = $this->getDBPhoto($photoId);
            $photo = FlickrViewer::buildMapObject("#photo-".$photoId,"photo",$photoId);



            $photo->properties->data->view_count = $dbPhoto["views"];
            $photo->properties->data->like_count = $dbPhoto["likes"];
            $photo->properties->data->share_count = $dbPhoto["shares"];
            $photo->properties->data->comment_count = $dbPhoto["comments"];
            //$photo->properties->data->thumb = FlickrPhotoHA::getUrl($dbPhoto,FlickrPhotoHA::SIZE_LARGE_SQUARE_SHORT);
            //$photo->properties->data->large= FlickrPhotoHA::getUrl($dbPhoto,FlickrPhotoHA::SIZE_LARGE_SHORT);
            return $photo;
        }

        public static function buildMapObject($selector,$type,$id){
            $data = array(
                "type"=>$type,
                "id"=>$id
            );

            $data =(object) $data;

            $properties = array(
                "data"=>$data

            );
            $properties =(object) $properties;
            $ret = array(
                "selector"=>$selector,
                "properties"=>$properties,

            );
            $ret["subpages"] =array();
            return (object) $ret;
        }

        public static function createErrorObject($error){
            $ret = array(
            "errors" =>true,
            "message"=>$error
            );

            return (object) $ret;
        }

         public static function hasError($rspObj) {

             return (is_object($rspObj) && isset($rspObj->errors) && $rspObj->errors===true);
         }

        private function _connectDB(){
            if($this->db) return;
            $this->db = new DB("root","root","localhost","image_service_ha");
            //$this->db = new DB("hellepho_user1","f{b5E_7!TKLU","localhost","hellepho_image_service");
        }

        public static function createHash($target,$id=""){
            if($target==="set") return md5(time()."set_".$id);
        }

        public static function sizesArrayToValue($sizes){
            $ret = 0;
            if(isset($sizes[FlickrPhotoHA::SIZE_SQUARE])) $ret += FlickrPhotoHA::SIZE_SQUARE_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_LARGE_SQUARE])) $ret +=FlickrPhotoHA::SIZE_LARGE_SQUARE_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_THUMBNAIL])) $ret +=FlickrPhotoHA::SIZE_THUMBNAIL_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_SMALL])) $ret +=FlickrPhotoHA::SIZE_SMALL_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_SMALL_240])) $ret +=FlickrPhotoHA::SIZE_SMALL_240_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_SMALL_320])) $ret +=FlickrPhotoHA::SIZE_SMALL_320_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_MEDIUM])) $ret +=FlickrPhotoHA::SIZE_MEDIUM_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_MEDIUM_640])) $ret +=FlickrPhotoHA::SIZE_MEDIUM_640_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_MEDIUM_800])) $ret +=FlickrPhotoHA::SIZE_MEDIUM_800_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_LARGE])) $ret +=FlickrPhotoHA::SIZE_LARGE_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_LARGE_1600])) $ret +=FlickrPhotoHA::SIZE_LARGE_1600_CODE;
            if(isset($sizes[FlickrPhotoHA::SIZE_ORIGINAL])) $ret +=FlickrPhotoHA::SIZE_ORIGINAL_CODE;

            return $ret;

        }

        public static function hasSize($sizes,$size){
            if(is_string($sizes)) return (strpos($sizes,FlickrViewer::$sizeDelimeter.$size.FlickrViewer::$sizeDelimeter) !== false);
            else return isset($sizes[$size]);
        }

        public function renderTemplate($set,$user){

            //$set = $viewer->getDBSet($setId);
            $photos = $this->getDBSetPhotos($set["id"]);
            $rights = $this->getSetRights($set["id"],$user["id"]);
            $template = file_get_contents("templates/basic.html");

            $pieces = explode(FlickrViewer::IMAGE_LOOP_DELIMETER,$template);
            $ret = "";

            $pieces[0] = FlickrViewer::renderData($pieces[0],$set,"SET");
            $pieces[0] = FlickrViewer::renderData($pieces[0],$user,"USER");

            $pieces[1] = FlickrViewer::renderImageLoop($pieces[1],$photos,$set,$rights);


            $pieces[2] = FlickrViewer::renderData($pieces[2],$set,"SET");
            $pieces[2] = FlickrViewer::renderData($pieces[2],$user,"USER");


            return array("body"=>implode("",$pieces),"js"=>"templates/basic.js","css"=>"templates/basic.css");

        }

        public static function renderData($html,$data,$part){
            foreach($data as $k => $v){
                $html = str_replace("#".strtoupper($part."_".$k)."#",$v,$html);
            }
            return $html;
        }

        public static function renderImageLoop($html,$photos,$set,$rights){
            $ret = "";
            $i=-1;
            $sizeRequests = explode(FlickrViewer::IMAGE_URL_SIZE_DELIMETER,$html);
            $sizes = array();

            foreach($sizeRequests as $size){
                $i++;
                if($i===0) continue;

                $strSize = explode("#", $size);
                //$ret .= $strSize[0];//"size:".$strSize[0]."--- %:".($i % 2).", i:".$i;
                array_push($sizes,$strSize[0]);

            }

            foreach($photos as $photo){
                    $img = "";
                    foreach($photo as $k => $v){
                        $img = str_replace("#".strtoupper("IMAGE_".$k)."#",$v,$html);
                    }

                    foreach($sizes as $size){
                        if($size === "MAX_VIEW") $url = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::numSizeToShort(FlickrViewer::getMaxViewSize($set,$rights)));
                        else if($size === "MAX_DOWNLOAD") $url = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::numSizeToShort(FlickrViewer::getMaxDownloadSize($set,$rights)));
                        else $url = FlickrPhotoHA::getUrl($photo,strtolower($size));
                        $img = str_replace(strtoupper(FlickrViewer::IMAGE_URL_SIZE_DELIMETER.$size)."#",$url,$img);
                    }

                    $img= FlickrViewer::renderData($img,$photo,"IMAGE");

                    $ret .= $img;


            }
            return $ret;
        }

        public static function addPhotoUrls(&$photo){

            $photo["url_size_".FlickrPhotoHA::SIZE_SQUARE_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_SQUARE_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_LARGE_SQUARE_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_LARGE_SQUARE_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_THUMBNAIL_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_THUMBNAIL_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_SMALL_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_SMALL_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_SMALL_240_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_SMALL_240_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_SMALL_320_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_SMALL_320_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_MEDIUM_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_MEDIUM_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_MEDIUM_640_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_MEDIUM_640_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_MEDIUM_800_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_MEDIUM_800_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_LARGE_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_LARGE_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_LARGE_1600_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_LARGE_1600_CODE);
            $photo["url_size_".FlickrPhotoHA::SIZE_ORIGINAL_SHORT] = FlickrPhotoHA::getUrl($photo,FlickrPhotoHA::SIZE_ORIGINAL_CODE);

        }

        public function getTemplateCSS($setId){
        }

        public static function getMaxViewSize($set,$user){
            return min(intval($set["view_rights"]),intval($user["view_rights"]));
        }

        public static function getMaxDownloadSize($set,$user){
            return min(intval($set["download_rights"]),intval($user["download_rights"]));
        }

        public static function listPhotoRights($key="num",$value="name"){
            $ret = array();
            foreach(explode(",",FlickrPhotoHA::SIZE_LIST) as $size){
                $k = "FlickrPhotoHA::".$size;
                $v = "FlickrPhotoHA::".$size;
                if($key === "num") $k.="_CODE";
                else if($key === "short") $k.="_SHORT";
                if($value === "num") $v.="_CODE";
                else if($value === "short") $v.="_SHORT";
                $ret[constant($k)] =constant($v) ;
            }
            return $ret;
        }
        */

}
?>