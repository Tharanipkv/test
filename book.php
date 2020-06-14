<?php
if(isset($_GET['date'])){
	$date= $_GET['date'];
}
if(isset($_POST['submit'])){
	$name=$_POST['name'];
	$email=$_POST['email'];
	$timeslot=$_POST['timeslot'];
	$mysqli=new mysqli('localhost','root','','bookingcalendar');
	$stmt= $mysqli->prepare("INSERT INTO bookings (name, timeslot, email,date) VALUES (?,?,?,?)");
	$stmt->bind_param('ssss',$name,$timeslot,$email,$date);
	$stmt->execute();
	$msg="<div class='alert alert-success'>booking successful</div>";
	echo $msg;
	$stmt->close();
	
	$mysqli->close();
}
$duration= 10;
$cleanup=0;
$start="09.00";
$end="15.00";


function timeslots($duration, $cleanup, $start, $end){
	$start= new DateTime($start);
	$end = new DateTime($end);
	$interval= new DateInterval("PT".$duration."M");
	$cleanupInterval= new DateInterval("PT".$cleanup."M");
	$slots=array();
	for($intStart=$start; $intStart<$end; $intStart->add($interval)->add($cleanupInterval)){
		$endPeriod=clone $intStart;
		$endPeriod->add($interval);
		if($endPeriod>$end){
			break;
		}
		$slots[]=$intStart->format("H:iA")."-".$endPeriod->format("H:iA");
	}
	
	return $slots;
}

?>
<!doctype html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, intial-scale=1.0">
		
		<title>
		</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-
		BVYiiSlFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="/css/main.css">
	</head>
	<body>
	<div class="container">
		
		<h1 class="text-center"> Book for Date : <?php echo date('m/d/Y', strtotime($date)); ?></h1><hr>
		<div class ="row">
			<?php $timeslots=timeslots($duration, $cleanup, $start, $end);
				foreach($timeslots as $ts){
			?>
			<div class="col-md-2">
				<div class= "form-group">
					<button class="btn btn-success book" data-timeslot="<?php echo $ts;?>"><?php echo $ts;?></button>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Booking :<span id ="slot"></span></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<form action ="" method="post">
									<div class="form-group">
										<label for="">Timeslot</label>
										<input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
									</div>
									<div class="form-group">
										<label for="">Name</label>
										<input required type="text" name="name" class="form-control">
									</div>
									<div class="form-group">
										<label for="">Email</label>
										<input required type="text" name="email" class="form-control">
									</div>
									<div class="form-group pull-right">
										<button class="btn btn-primary" type="submit" name="submit">Submit</button>
									</div>
									
								</form>
							</div>
						</div>
					</div>
					
				</div>

			</div>
		</div>
	
	
	
	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" 
	integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
	<script>
	$(".book").click(function(){
		var timeslot=$(this).attr('data-timeslot');
		$("#slot").html(timeslot);
		$("#timeslot").val(timeslot);
		$("#myModal").modal("show");
	})
	</script>
</body>
</html>
	