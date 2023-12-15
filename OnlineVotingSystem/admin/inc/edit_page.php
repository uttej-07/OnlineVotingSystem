<!-- changes are made from 
21 to 60 -->
<?php 

    $c_id = $_GET['edit_page'];
  
    //candidates
    $fetchingcandidate=mysqli_query($db,"SELECT * FROM candidate_details WHERE id='". $c_id ."'") OR die(mysqli_error($db));
    $row1= mysqli_fetch_assoc($fetchingcandidate);
    $candidate_id=$row1['id'];
    $election_id=$row1['election_id'];
    $e_id=$election_id;
    $candidate_name=$row1['candidate_name'];
    $candidate_details=$row1['candidate_details'];

    //elections
    $fetchingElections = mysqli_query($db, "SELECT * FROM elections WHERE id='".$election_id."'") OR die(mysqli_error($db));
    $row2= mysqli_fetch_assoc($fetchingElections);
    $election_name=$row2['election_topic'];
?>


<?php 
    if(isset($_GET['updated']))
    {
?>
        <div class="alert alert-success my-3" role="alert">
            Candidate's details has been updated successfully.
        </div>
<?php 
    }else if(isset($_GET['largeFile'])) {
?>
        <div class="alert alert-danger my-3" role="alert">
            Candidate image is too large, please upload small file (you can upload any image upto 2mbs.).
        </div>
<?php
    }else if(isset($_GET['invalidFile']))
    {
?>
        <div class="alert alert-danger my-3" role="alert">
            Invalid image type (Only .jpg, .png files are allowed) .
        </div>
<?php
    }else if(isset($_GET['failed']))
    {
?>
        <div class="alert alert-danger my-3" role="alert">
            Image uploading failed, please try again.
        </div>
<?php
    }else if(isset($_GET['delete_id']))
    {
        $d_id = $_GET['delete_id'];
        mysqli_query($db, "DELETE FROM candidate_details WHERE id = '". $d_id ."'") OR die(mysqli_error($db));
?>
       <div class="alert alert-danger my-3" role="alert">
            candidate has been deleted successfully!
        </div>
<?php

    }
?>


<div class="row my-3">
    <div class="col-12" >
        <h3>Update the details of <?php echo $candidate_name ;?> </h3>
        <form method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <input type="text" name="election_id" placeholder="Election_Name" value=" <?php echo $election_name ;?>" class="form-control" readonly/>
               
            </div>
            <div class="form-group">
                <input type="text" name="candidate_name" placeholder="Candidate Name" value=" <?php echo $candidate_name ;?>" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="file" name="candidate_photo" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="text" name="candidate_details" placeholder="Candidate Details" value=" <?php echo $candidate_details ;?>" class="form-control" required />
            </div>
            <input type="submit" value="Update Candidate" name="editCandidateBtn" class="btn btn-success" />
        </form>
    </div>
   
</div>


<?php 

    if(isset($_POST['editCandidateBtn']))
    {
        // $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
        $election_id = mysqli_real_escape_string($db, $e_id);
        $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
        $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);
        $inserted_by = $_SESSION['username'];
        $inserted_on = date("Y-m-d");

        // Photograph Logic Starts
        $targetted_folder = "../assets/images/candidate_photos/";
        $candidate_photo = $targetted_folder . rand(111111111, 99999999999) . "_" . rand(111111111, 99999999999) . $_FILES['candidate_photo']['name'];
        $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
        $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "png", "jpeg");        
        $image_size = $_FILES['candidate_photo']['size'];

        if($image_size < 2000000) // 2 MB
        {
            if(in_array($candidate_photo_type, $allowed_types))
            {
                if(move_uploaded_file($candidate_photo_tmp_name, $candidate_photo))
                {
                    // inserting into db
                    // mysqli_query($db, "INSERT INTO candidate_details(election_id, candidate_name, candidate_details, candidate_photo, inserted_by, inserted_on) VALUES('". $election_id ."', '". $candidate_name ."', '". $candidate_details ."', '". $candidate_photo ."', '". $inserted_by ."', '". $inserted_on ."')") or die(mysqli_error($db));
                    mysqli_query($db,"UPDATE candidate_details SET election_id='".$election_id."', candidate_name="."'".$candidate_name."',candidate_details="."'".$candidate_details."',candidate_photo="."'".$candidate_photo."',inserted_by="."'".$inserted_by."',inserted_on="."'".$inserted_on."' WHERE id="."'".$c_id."'") or die(mysqli_error($db));
                    echo "<script> location.assign('index.php?addCandidatePage=1&updated=1'); </script>";


                }else {
                    echo "<script> location.assign('index.php?addCandidatePage=1&failed=1'); </script>";                    
                }
            }else {
                echo "<script> location.assign('index.php?addCandidatePage=1&invalidFile=1'); </script>";
            }
        }else {
            echo "<script> location.assign('index.php?addCandidatePage=1&largeFile=1'); </script>";
        }
        // Photograph Logic Ends
    ?>
      <?php
    }
?>