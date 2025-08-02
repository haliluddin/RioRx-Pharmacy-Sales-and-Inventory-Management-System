<style>
   
</style>

<div class="container-fluid">
	<div class="row mt-4 ml-3 mr-3">
		<div class="col-lg-12">
			<div class="card mb-4">
				<div class="card-body">
					<h5 class="m-0"><b><?php echo "Welcome back ".$_SESSION['login_name']."!"  ?></b></h5>	
				</div>
			</div>
			<div class="row mb-4">
				<div class="col">
					<div class="card bg-white">
						<div class="card-body text-dark">
							<p><b><large>Total Sales Today</large></b></p>
							<hr>
							<p class="text-right" style="font-size: 30px;"><b><large><?php 
							include 'db_connect.php';
							$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where date(date_updated)= '".date('Y-m-d')."'");
							echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";?></large></b></p>
						</div>
					</div>
				</div>	
				<div class="col">
					<div class="card bg-white">
						<div class="card-body text-dark">
							<p><b><large>Total Count of Transaction Today</large></b></p>
							<hr>
							<p class="text-right" style="font-size: 30px;"><b><large><?php 
							include 'db_connect.php';
							$sales = $conn->query("SELECT * FROM sales_list where date(date_updated)= '".date('Y-m-d')."'");
							echo $sales->num_rows > 0 ? number_format($sales->num_rows) : "0";?></large></b></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row mb-4">	
				<div class="col-md-12">
					<div class="card totalst">
						<div class="card-header py-3"><b>User Activity Summary Today</b></div>
						<div class="card-body">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-center">Role</th>
										<th class="text-center">Name</th>
										<th class="text-center">Total Transactions Today</th>
										<th class="text-center">Total Sales Today</th>
									</tr>
								</thead>
								<tbody>
									<?php
									include 'db_connect.php';

									$today = date('Y-m-d');

									$users = $conn->query("
										SELECT 
											u.id, 
											u.name, 
											u.type, 
											COUNT(s.id) AS total_transactions, 
											COALESCE(SUM(s.total_amount), 0) AS total_sales 
										FROM 
											users u
										LEFT JOIN 
											sales_list s ON s.user_id = u.id 
											AND DATE(s.date_updated) = '$today'
										GROUP BY 
											u.id
										ORDER BY 
											u.name ASC
									");

									while ($row = $users->fetch_assoc()):
									?>
									<tr>
										<td class="text-center">
											<?php 
											// Convert type to role name
											echo ($row['type'] == 1) ? 'Admin' : (($row['type'] == 2) ? 'Cashier' : 'Unknown'); 
											?>
										</td>
										<td class="text-center">
											<?php echo $row['name']; ?>
										</td>
										<td class="text-center">
											<?php echo $row['total_transactions']; ?>
										</td>
										<td class="text-center">
											<?php echo number_format($row['total_sales'], 2); ?>
										</td>
									</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
					<?php if($_SESSION['login_type'] != 1): ?>
						<style>
							.card.totalst{
								display: none!important;
							}
						</style>
					<?php endif ?>
				</div>	
			</div>
			<div class="row mb-4">
				<div class="col">
					<div class="card">
						<div class="card-header"><b>Expired Product</b></div>
						<div class="card-body">
							<ul class="list-unstyled">
								<?php 
									$ex = $conn->query("SELECT i.*,p.name,p.measurement,p.sku FROM inventory i inner join product_list p on p.id = i.product_id where date(i.expiry_date) <= '".date('Y-m-d')."' and i.expired_confirmed = 0 ");
									while($row= $ex->fetch_array()):
								?>
								<li class="pb-3 mb-3 border-bottom">
									<div class="row">
										<div class="col">
											<?php echo $row['name'] ?> <sup><?php echo $row['measurement'] ?></sup>
											<br>
											<small>Expiry Date: <b><?php echo date('F d, Y', strtotime($row['expiry_date'])); ?></b></small>
										</div>
										<div class="col">
											<a href="index.php?page=manage_expired&iid=<?php echo $row['id'] ?>" class="badge badge-danger float-right p-2">Confirm</a>
										</div>
									</div>								
								</li>
								<?php endwhile; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card">
						<div class="card-header"><b>Nearing Expiration Product</b></div>
						<div class="card-body">
							<ul class="list-unstyled">
								<?php 
								include 'db_connect.php';

								if (isset($_GET['remove_id'])) {
									$remove_id = intval($_GET['remove_id']);
									$conn->query("DELETE FROM inventory WHERE id = $remove_id");
								}

								$threshold_date = date('Y-m-d', strtotime('+30 days'));
								$nearing_expiration = $conn->query("
									SELECT 
										i.*, 
										p.name, 
										p.measurement, 
										p.sku 
									FROM 
										inventory i 
									INNER JOIN 
										product_list p ON p.id = i.product_id 
									WHERE 
										date(i.expiry_date) <= '$threshold_date' 
										AND date(i.expiry_date) > '".date('Y-m-d')."' 
								");

								while ($row = $nearing_expiration->fetch_array()):
								?>
								<li class="pb-3 mb-3 border-bottom">
									<div class="row">
										<div class="col">
											<?php echo $row['name']; ?> <sup><?php echo $row['measurement']; ?></sup>
											<br>
											<small>Expiry Date: <b><?php echo date('F d, Y', strtotime($row['expiry_date'])); ?></b></small>
										</div>
										<div class="col">
											<a href="index.php?page=home&remove_id=<?php echo $row['id']; ?>" class="badge badge-warning float-right p-2">Confirm</a>
										</div>
									</div>
								</li>
								<?php endwhile; ?>
							</ul>
							        
						</div>
					</div>
				</div>
					
			</div>
		</div>	
	</div>
</div>
