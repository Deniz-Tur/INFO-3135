<?php 

// booking.php- make reservtion page
session_start();

// check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$activeTab='reservations';
require 'db.php';
require 'header.php';

$errors=[];
$successMessage='';

$tables=[];

// fetch available tables
$tablesStmt=$pdo->query("Select * from tables where status= 'available' order by capacity");
//$tables= $tablesStmt -> fetchAll();

// fetch available time slots 
$time_slots= [
    "12:00 PM","12:30 PM","1:00 PM","1:30 PM","2:00 PM",
    "5:00 PM", "5:30 PM","6:00 PM","6:30 PM", "7:00 PM",
    "7:30 PM", "8:00 PM","8:30 PM","9:00 PM","9:30 PM"
];

// handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $tableId= $_POST['table_id'];
    $date=$_POST['reservation_date'];
    $time=$_POST['reservation_time'];
    $partySize= $POST['party_size'];
    $special_requests=$_POST['special_request'];

//validation
if($tableId===''){
    $errors[]='Please select a table. ';
}
if($date==='')
{
    $errors[]= 'Reservation date is required';
}
if($time===''){
    $errors[]='Reservation time is required';
}
if($partySize=== '' || $partySize<1){
    $errors[]= 'Number of guest sis required.';
}
// validate date(cannot book past dates)
    $today= date('Y-m-d');
 if($date < $today){
        $errors="cannot book for past dates! ";
    }
if(empty($errors)){
    // check if table is already booked at that time and date
    $checkStmt = $pdo->prepare('
    Select * from reservation
    where table_id=?
    and reservation_date=?
    and reservation_time=?
    and status != "Cancelled"');
    $checkStmt->execute([$tableId, $date, $time]);

    if($checkStmt->fetch()){
        $errors[]='This table is already booked at this time. Please select another table or time. ';
    } else{
        // insert reservations
        $isnertStmt= $pdo->prepare('Insert into reservations
        (user_id, table_id, reservation_date, reservation_time, party_size, special_requests, status, created_at
        values(?,?,?,?,?,?,"pending",NOW()');
        $insertStmt->execute([$_SESSIONS['user_id'],
              $tableId,
              $date,
              $time,
              $partySize,
              $specialReq
            ]);
            $successMessage= 'Reservation created successfully! Waiting for admin approval.';
    }
}


}
   

?>
<?php if (!empty($errors)): ?>
    <div class="card" style="border-left: 4px solid #c0392b;">
        <h3 class="card-title">There were some problems:</h3>
       <ul class="card-text" style="margin-left: 18px; list-style: disc;">
           <?php foreach ($errors as $err): ?>
               <li><?php echo htmlspecialchars($err); ?></li>
           <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($successMessage): ?>
    <div class="card" style="border-left: 4px solid #27ae60;">
        <p class="card-text">
           <?php echo htmlspecialchars($successMessage); ?>
           <a href="my_reservations.php" class="btn btn-primary" style="margin-left:10px;">View My Reservations</a>
        </p>
    </div>
<?php endif; ?>

<div class="card">
    <h3 class="card-title">Available Tables</h3>
    <?php if (empty($tables)): ?>
        <p class="card-text">No tables available at the moment.</p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php foreach ($tables as $table): ?>
                <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: #f9f9f9; text-align: center;">
                   <h4 style="margin-top: 0; color: #d4a024;">Table <?php echo htmlspecialchars($table['table_number']); ?></h4>
                    <p style="margin: 5px 0; font-size: 14px;">
                        <strong>Capacity:</strong> <?php echo htmlspecialchars($table['capacity']); ?> people
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <h3 class="card-title">Book Your Table</h3>
    <form action="booking.php" method="post" style="margin-top: 12px;">
        <div style="margin-bottom: 10px;">
            <label>Select Table</label><br>
            <select name="table_id" required
                    style="padding: 6px 10px; width: 100%; max-width: 350px; border-radius: 6px; border: 1px solid #ccc;">
                <option value="">-- Choose a table --</option>
                <?php foreach ($tables as $table): ?>
                    <option value="<?php echo $table['id']; ?>">
                        Table <?php echo htmlspecialchars($table['table_number']) . ' (Capacity: ' . htmlspecialchars($table['capacity']) . ' people)'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="margin-bottom: 10px;">
           <label>Reservation Date</label><br>
            <input type="date" name="reservation_date" 
                   min="<?php echo date('Y-m-d'); ?>"
                  value="<?php echo isset($date) ? htmlspecialchars($date) : ''; ?>"
                  required
                   style="padding: 6px 10px; width: 100%; max-width: 250px; border-radius: 6px; border: 1px solid #ccc;">
        </div>
        
        <div style="margin-bottom: 10px;">
            <label>Reservation Time</label><br>
           <select name="reservation_time" required
                   style="padding: 6px 10px; width: 100%; max-width: 250px; border-radius: 6px; border: 1px solid #ccc;">
                <option value="">-- Choose a time --</option>
                <?php foreach ($timeSlots as $slot): ?>
                    <option value="<?php echo $slot; ?>" <?php echo (isset($time) && $time == $slot) ? 'selected' : ''; ?>>
                        <?php echo $slot; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="margin-bottom: 10px;">
            <label>Party Size (Number of Guests)</label><br>
            <input type="number" name="party_size" min="1" max="20"
                   value="<?php echo isset($partySize) ? htmlspecialchars($partySize) : '2'; ?>"
                   required
                   style="padding: 6px 10px; width: 100%; max-width: 150px; border-radius: 6px; border: 1px solid #ccc;">
        </div>
        
        <div style="margin-bottom: 10px;">
            <label>Special Requests (Optional)</label><br>
            <textarea name="special_requests" rows="3"
                      style="padding: 6px 10px; width: 100%; max-width: 450px; border-radius: 6px; border: 1px solid #ccc;"
                      placeholder="Any dietary restrictions, celebrations, or special requirements..."><?php echo isset($specialReq) ? htmlspecialchars($specialReq) : ''; ?></textarea>
        </div>
        
       <button type="submit" class="btn btn-primary">Book Table</button>
    </form>
</div>

<?php
include 'footer.php';
193?>