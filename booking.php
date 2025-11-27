<?php 

// booking.php- make reservtion page
session_start();

// check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// get user's name from session
$user_name= $_SESSION['name'];
$user_id=$_SESSION['user_id'];

// fetch available tables
$tables_query="Select * from tables where status= 'available' order by capacity";
$tables_result=$conn->query($tables_query);

// fetch available time slots 
$time_slots= [
    "12:00 PM","12:30 PM","1:00 PM","1:30 PM","2:00 PM",
    "5:00 PM", "5:30 PM","6:00 PM","6:30 PM", "7:00 PM",
    "7:30 PM", "8:00 PM","8:30 PM","9:00 PM","9:30 PM"
];

// handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $table_id= $_POST['table_id'];
    $date=$_POST['date'];
    $time=$_POST['party_size'];
    $special_requests=$_POST['special_request'];

    // validate date(cannot book past dates)
    $today= date('Y-m-d');
    if($date < $today){
        $error="cannot book for past dates! ";
    } else{
        // check if table is laready booked for that date and time
        $check_stmt=$conn->prepare("select v* from reservations where table_id=? and reservation_date=? and reservation_time=?  and status !='cancelled'");
        $check_stmt->bind_param("iss", $table_id, $date, $time);
        $check_stmt->execute();
        $check_result=$check_stmt->get_result();

        if($check_result->num_rows > 0){
            $error= "This table is already booked at this time. Please select another date or time.";
    } else{
         $error="Failed to create reservation. Please try again!";
    }
        $stmt-> close();
}
  $check_stmt->close();
}

// include header
include('header.php');
?>
<h2 class="app-section-title"> Make A reservation</h2>
<p class="app-section-subtitle">
    Welcome! <strong><?php echo htmlspecialchars($user_name); ?></strong> Fill out the form below to book your table.
</p>

<?php if(isset($success)): ?>
    <div class="card" style=" border-left: 4px solid var(--success-color); background-color: rgba(39, 174, 96,0.05);">
        <h3 class="card-title" style="color: var(--danger-color);"> Error</h3>
        <p class="card-text" style="color:var(--danger-color);">
            <?php echo $error?>
        </p>
    </div>
<?php endif; ?>
<div class="card">
    <h3 class="card-title"> Reservation Details</h3>
    <form method="POST" action="" style="margin-top: 16px">
        <div style="margin-bottom: 16px">
            <label for= "table_id" style="display:block; font-weight: 600; margin-bottom: 6px; color: var(--textcolor);">
                Select table:
            </label>
            <select name="table_id" id="table_id" required
            style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius:var(--radius-md); font-size:0.9 rem;">
              <option value="">-- Choose a table--</option>
              <?php while ($table= $tables_result->fetch_assoc()) :?>
                <option value="<?php echo $table['table_id']; ?>">
                    Table<?php echo $table['table_number']; ?>
                    (Capacity: <?php echo $table['capacity']; ?>people)
                </option>
                <?php endwhile; ?>
        </select>
        </div>
        <div style="margin-bottom: 16px;">
            <label for="date" style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-color);">
                Reservation Date:
            </label>
            <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required
                   style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 0.9rem;">
        </div>
        
        <div style="margin-bottom: 16px;">
            <label for="time" style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-color);">
                Reservation Time:
            </label>
            <select name="time" id="time" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 0.9rem;">
                <option value="">-- Select time --</option>
                <?php foreach ($time_slots as $slot): ?>
                    <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

         <div style="margin-bottom: 16px;">
            <label for="party_size" style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-color);">
                Party Size (Number of guests):
            </label>
            <input type="number" name="party_size" id="party_size" min="1" max="20" required
                   style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 0.9rem;">
        </div>

        <div style="margin-bottom: 16px;">
            <label for="special_requests" style="display: block; font-weight: 600; margin-bottom: 6px; color: var(--text-color);">
                Special requests (Optional):
            </label>
            <textarea name="special_requests" id="special_requests" rows="4"
            placeholder="Eg. Window Seat, birthday celebration requirements..."
                   style="width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 0.9rem; resize; vertical">
                </textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding:12px;">Book Table</button>
    </form>
</div>

<?php include('footer.php'); ?>
