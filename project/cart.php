<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//if (!has_role("Admin")) {
//    //this will redirect to login and kill the rest of this script (prevent it from executing)
 //   flash("You don't have permission to access this page");
 //   die(header("Location: login.php"));
//}
?>
<?php

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("Please login to access bag.");
    die(header("Location: login.php"));
}

//$query = "";
$results = [];
//if (isset($_POST["query"])) {
 //   $query = $_POST["query"];
//}
//if (isset($_POST["search"]) && !empty($query)) {
    $user = get_user_id();
    $db = getDB();

    $stmt = $db->prepare("SELECT  prod.id, prod.price, prod.name, cart.product_id, cart.quantity, prod.name FROM `Products` as prod JOIN `Cart` as cart ON prod.id = cart.product_id AND cart.user_id = $user;");
    $r = $stmt->execute();
    if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
    flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
//}
?>
<label for= "pleasesignin">Bag.</label>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Product Name:</div>
                        <div><?php safer_echo($r["name"]); ?></div>
                    </div>
                    <div>
                        <div>Price:</div>
                        <div><?php safer_echo($r["price"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_cart.php?id=<?php safer_echo($r['id']); ?>">+</a>
                        <a type="button" href="test_view_cart.php?id=<?php safer_echo($r['id']); ?>">-</a>
			<a type="button" href="test_view_cart.php?id=<?php safer_echo($r['id']); ?>">Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div> 
