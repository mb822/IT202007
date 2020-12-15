<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
  //  flash("You don't have permission to access this page");
  //  die(header("Location: login.php"));
//}
?>
<?php
$query = "";
$results = [];
$sort = "";



if(isset($_SESSION["query"])){$query =  $_SESSION["query"];}
elseif(isset($_GET["query"])){$query = $_POST["query"] ;}

if(isset($_SESSION["sort"])){$sort = $_SESSION["sort"];}
elseif(isset($_GET["sort_by"])){$query = $_POST["sort_by"] ;}



//if(isset($_POST["sort_by"])){
//    $sort = $_POST["sort_by"];
//}


//if(isset($_GET["query"])){
//echo "post query is set";
//$query = $_GET["query"];
//}

//elseif (isset($_SESSION["query"]) ) {
//    $query = $_POST["query"];
//}

//else{
//    $db = getDB();
//    $stmt = $db->prepare("SELECT average_rating,checkout_img, id, name, quantity, price, description, user_id from Products WHERE category = 'iphone'");
//    $r = $stmt->execute();
//    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//}



echo $query;
echo $sort;






///////////////////////////pagination
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
	$page = (int)$_GET["page"];
    }
    catch(Exception $e){

    }
}
$db = getDB();

if(has_role('Admin')){$stmt = $db->prepare("SELECT count(*) as total from Products WHERE (name like :q OR category = :category)");}
else{$stmt = $db->prepare("SELECT count(*) as total from Products WHERE (name like :q OR category = :category) AND visibility = 1");}

$stmt->execute([":q" => "%$query%", ":category" => "$query" ]);
$res = $stmt->fetch(PDO::FETCH_ASSOC);
$total = 0;
if($res){
    $total = (int)$res["total"];
}
$total_pages = ceil($total / $per_page);
$offset = ($page-1) * $per_page;
/////////////////////////pagination






    


	if(strcmp($sort, "lh")==0){    $stmt = $db->prepare("SELECT average_rating, checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY price ASC LIMIT :offset, :count");}
        elseif(strcmp($sort, "rLH")==0){    $stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY average_rating ASC LIMIT :offset, :count");}
        elseif(strcmp($sort, "rHL")==0){    $stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY average_rating DESC LIMIT :offset, :count");}
        elseif(strcmp($sort, "hl")==0){$stmt = $db->prepare("SELECT average_rating, checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY price DESC LIMIT :offset, :count");}
        else{$stmt = $db->prepare("SELECT average_rating,checkout_img, id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 LIMIT :offset, :count");}    


    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":q", "%$query%");
$stmt->bindValue(":category", "$query"   );

    $r = $stmt->execute();

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);























if (isset($_POST["search"])) { //!empty($query)
    $db = getDB();




   //if(empty($query)){
     //  $query = "";
   //}



	$_SESSION["query"] = $_POST["query"]; echo $_POST["query"]; echo "   ";
	$_SESSION["sort"] = $_POST["sort_by"];  echo $_POST["sort_by"];
	$query = $_SESSION["query"]; 
	$sort = $_SESSION["sort"];
	
	

    if(has_role("Admin")){
	//id = id because bind requires key to be in query to my knowledge
	if($query == "ALL"){$stmt = $db->prepare("SELECT average_rating,checkout_img, id, name, quantity, price, description, user_id from Products  WHERE     :q IS NOT NULL  OR :category IS NOT NULL  OR :offset IS NOT NULL OR  :count IS NOT NULL         "         );}
    	else{$stmt = $db->prepare("SELECT average_rating,checkout_img, id, name, quantity, price, description, user_id from Products WHERE   (name like :q OR category = :category)       AND (:offset IS NOT NULL OR  :count IS NOT NULL)     ");}
    }
    else{


	if(strcmp($sort, "lh")==0){    $stmt = $db->prepare("SELECT average_rating, checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY price ASC LIMIT :offset, :count");}
	//elseif(strcmp($sort, "1")==0){$stmt = $db->prepare("SELECT average_rating, checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 AND average_rating >=1");}
        //elseif(strcmp($sort, "2")==0){$stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 AND average_rating >=3");}
        //elseif(strcmp($sort, "3")==0){$stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 AND average_rating >=3");}
        //elseif(strcmp($sort, "4")==0){$stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 AND average_rating >=4");}
        //elseif(strcmp($sort, "5")==0){$stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 AND average_rating >=5");}
	elseif(strcmp($sort, "rLH")==0){    $stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY average_rating ASC LIMIT :offset, :count");}
	elseif(strcmp($sort, "rHL")==0){    $stmt = $db->prepare("SELECT average_rating,checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY average_rating DESC LIMIT :offset, :count");}

	elseif(strcmp($sort, "hl")==0){$stmt = $db->prepare("SELECT average_rating, checkout_img,id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1  ORDER BY price DESC LIMIT :offset, :count");}
	else{$stmt = $db->prepare("SELECT average_rating,checkout_img, id, name, quantity, price, description, user_id from Products WHERE (name like :q OR category = :category) AND visibility = 1 LIMIT :offset, :count");}
    }


    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
$stmt->bindValue(":q", "%$query%");
$stmt->bindValue(":category", "$query"   );
    
    $r = $stmt->execute();

    //$r = $stmt->execute([":q" => "%$query%", ":category" => "$query" ]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
<form method="POST">
    <label for= "pleasesignin">Search Inventory.</label>
    <input name="query" placeholder="Search by Name or Category" value="<?php safer_echo($query); ?>"/>
	 
	<select name="sort_by" id="sort_by"  value="<?php safer_echo($sort); ?>" >

	    <option value="0" <?php echo($sort== "0"?'selected="selected"': '');    ?>>Sort by: None</option>
	    <option value="hl" <?php echo($sort== "hl"?'selected="selected"': '');    ?>>Price: High to Low</option>
	    <option value="lh" <?php echo($sort== "lh"?'selected="selected"': '');    ?>>Price: Low to High</option>
<!--<option value="5" <?php echo($sort== "5"?'selected="selected"': '');    ?>>Rating: ★★★★★</option>
<option value="4" <?php echo($sort== "4"?'selected="selected"': '');    ?>>Rating: ★★★★ and Greater</option>
<option value="3" <?php echo($sort== "3"?'selected="selected"': '');    ?>>Rating: ★★★ and Greater</option>
<option value="2" <?php echo($sort== "2"?'selected="selected"': '');    ?>>Rating: ★★ and Greater</option>
<option value="1" <?php echo($sort== "1"?'selected="selected"': '');    ?>>Rating: ★ and Greater</option>  -->
	<option value="rHL" <?php echo($sort== "rHL"?'selected="selected"': '');    ?>>Rating: High to Low</option>
	<option value="rLH" <?php echo($sort== "rLH"?'selected="selected"': '');    ?>>Rating: Low to High</option>


	</select>	
	
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">

<?php if(!isset($_POST["query"])): ?>
<label for="pleasesignin" style="font-size: 1.2em;color: #3465b6; font-weight: 400; margin-top:15px">Popular Products.</label>
<?php endif; ?>

    <?php if (count($results) > 0): ?>



        <div class="list-group">






<?php
    $i = 0;
?>


<div class="row">
<?php foreach ($results as $r): ?>
<?php $i++; ?>



<div class='col-lg-2'>
<div class='item'>

                <div class="list-group-item">

                    <div>

			    <?php
			    $average_rating = $r["average_rating"];			
                            if(round($average_rating) == 1){$average_rating = "★☆☆☆☆";}
                            elseif(round($average_rating) == 2){$average_rating = "★★☆☆☆";}
                            elseif(round($average_rating) == 3){$average_rating = "★★★☆☆";}
                            elseif(round($average_rating) == 4){$average_rating = "★★★★☆";}
                            elseif(round($average_rating) == 5){$average_rating = "★★★★★";}
			    ?>

                        <div style="color:#ff7d00;"><?php safer_echo($average_rating); ?></div>
                    </div>




                    <div>
                       <!-- <div>Name:</div>-->
                        <div><?php safer_echo($r["name"]); ?></div>
                    </div>

			<div>
				<img aria-hidden="true"  src="<?php echo $r["checkout_img"]?>" width="200" height="240" alt="" class="ir">
			</div>


                    <div>
                       <!-- <div>Price:</div>-->
                        <div><?php safer_echo("$".$r["price"]); ?></div>
                    </div>
                    <div>
                       <!-- <div>Description:</div>-->
          <!--              <div><?php safer_echo($r["description"]); ?></div>  -->
                    </div>   

                    <div>

			<?php if (has_role("Admin")): ?>
                        	<a type="button" href="test_edit_product.php?id=<?php safer_echo($r['id']); ?>">Edit</a>|
			<?php endif; ?>

                        <a type="button" href="test_view_products.php?id=<?php safer_echo($r['id']); ?>">View</a>|
			<a type="button" href="add_to_cart.php?id=<?php safer_echo($r['id']); ?>">Add to bag</a>
                    </div>
                </div>

</div>
</div>


<?php endforeach; ?>

        </div>


<!-- for pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>




    <?php else: ?>
        <p class="no_results">No results</p>
    <?php endif; ?>
</div>

<label style="margin-top:300px;"></label>
