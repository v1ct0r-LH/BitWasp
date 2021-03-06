        <div class="span9 mainContent" id="view-account">
          <div class="container-fluid">
			  
			<?php if(isset($returnMessage)) echo '<div class="alert">'.$returnMessage.'</div>'; ?>
			
            <div class="row-fluid">
              <div class="span9 btn-group">
				<h2><?php 
				if($logged_in == TRUE) {
					echo anchor('message/send/'.$user['user_hash'],'Message', 'class="btn"')." "; 
					if($user_role == "Admin" && $user['user_role'] !== "Admin") {
						$txt = ($user['banned'] == '0') ? 'Ban User' : 'Unban User';
						echo anchor('admin/ban_user/'.$user['user_hash'], $txt, 'class="btn"'); 
					}
				} ?>
					<?php echo $user['user_name']; ?></h2>
              </div>
            </div>

			<div class="row-fluid">
			  <div class="span2"><strong>Location</strong></div>
			  <div class="span7"><?php echo $user['location_f']; ?></div>
			</div>

            <div class="row-fluid">
              <div class="span2"><strong>Registered</strong></div>
              <div class="span7"><?php echo $user['register_time_f']; ?></div>
            </div>

<?php if($user['display_login_time'] == '1') { ?>
  	        <div class="row-fluid">
	          <div class="span2"><strong>Last Activity</strong></div>
	          <div class="span7"><?php echo $user['login_time_f']; ?></div>
	        </div>
<?php } ?>

			<div class="row-fluid">
			  <div class="span2"><strong>Average Rating</strong></div>
			  <div class="span7"><?php echo $average_rating; ?></div>
			</div>

			<div class="row-fluid">
				<div class='span2'><strong>Completed Orders</strong></div>
				<div class='span7'><?php echo $user['completed_order_count']; ?></div>
			</div>

<?php if($reviews !== FALSE) { ?>
			<div class='row-fluid'>
				<div class='well'><strong>Recent Reviews</strong><br />
				<?php echo anchor('reviews/view/user/'.$user['user_hash'], "[All Reviews ({$review_count['all']})]"); ?> <?php echo anchor('reviews/view/user/'.$user['user_hash'].'/0', "[Positive Reviews ({$review_count['positive']})]"); ?> <?php echo anchor('reviews/view/user/'.$user['user_hash'].'/1', "[Disputed Orders {$review_count['disputed']}]"); ?>
				<?php	foreach($reviews as $review) { ?>
					<br /><div class='row-fluid'>
						<div class='span3'><?php foreach($review['rating'] as $rating_name => $rating){ echo ucfirst($rating_name) ." - $rating/5<br />"; } ?>Average: <?php echo $review['average_rating']; ?></div>
						<div class='span2'>Comments:</div>
						<div class='span6'><?php echo $review['comments']; ?></div>
					</div><?php	} ?>
				</div>
			</div>
<?php } ?>

<?php if(isset($items) && $items !== FALSE && count($items) > 0) { ?>
			<div class="row-fluid">
<div class='well'><strong>Vendor Items</strong><br />
<?php $c = 0; 
	foreach($items as $item) { 
			$cal = $c++%4;
			if($cal == 0) { // even, then create the row
				echo "<div class='row-fluid'>\n";
				echo "<div class='span3'>".anchor('item/'.$item['hash'], "{$item['name']}")."</div>\n";
			} else if($cal == '3') {
				echo "<div class='span3'>".anchor('item/'.$item['hash'], "{$item['name']}")."</div>\n";
				echo "</div>\n";
			} else {
				echo "<div class='span3'>".anchor('item/'.$item['hash'], "{$item['name']}")."</div>\n";
			}
	} ?></div>
			</div>
<?php } ?>

<?php if(isset($user['pgp']['public_key'])) { ?>
			<div class="row-fluid">
			  <div class="span2"><strong>PGP Fingerprint</strong></div>
			  <div class="span7"><?php echo $user['pgp']['fingerprint_f']; ?></div>
			</div>

            <div class="row-fluid">
              <div class="span2"><strong>PGP Public Key</strong></div>
              <pre id="publicKeyBox" class="span9 well"><?php echo $user['pgp']['public_key']; ?>
			  </pre>
            </div>
<?php } ?>

	
          </div>
        </div>
