<?php
require_once '../helper/functions.php';
require_once '../database/connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = htmlspecialchars($_SESSION['user_id']);

// get user info
$sql = "SELECT * FROM users WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_NAMED);
?>

<style>
    .alert-msg {
        border: 1px solid #dcfce7;
        border-left: 2px solid #16a34a;
        background: #dcfce7;
    }
    .user-info-con,
    .input {
        width: 97% !important;
    }

    .input,
    .btn-change {
        font-size: 0.8rem !important;
    }

    .input:read-only {
        background: white !important;
        border: none !important;
    }

    label {
        font-size: 0.9rem;
    }

    @media (min-width: 640px) {

        .user-info-con,
        .input {
            width: 65% !important;
        }

        .input,
        .btn-change {
            font-size: 0.9rem !important;
        }
    }


    @media (min-width: 1024px) {

        .user-info-con,
        .input {
            width: 50% !important;
        }

        .input,
        .btn-change {
            font-size: 1rem !important;
        }
    }
</style>

<?php view('header.php', 'Home') ?>


<?php include_once '../components/navigation.php' ?>
 

<div class=" main-container mb-5 d-flex justify-content-center" style="margin-top:80px;">
    <div class="user-info-con border py-2 px-3 bg-white">
        <div class="alert-con d-none">
            <div class="alert-msg my-2 d-flex align-items-center py-2">
                <i class="bi bi-exclamation-circle-fill px-3" style="font-size: 1.3rem;color: #16a34a;"></i>
                <span class="note m-0 p-0 " style="color: #16a34a;">Saved</span>
            </div>
        </div>
        <form id="editUserDetailsForm">
            <input type="hidden" name="userid" value="<?php echo $_SESSION['user_id'] ?>">
            <div class="user-info d-flex justify-content-between align-items-center">
                <input current-val="<?php echo $user['firstname'] ?>" type="text" name="firstname" id="firstname" value="<?php echo $user['firstname'] ?>" class="fw-semibold input form-control" readonly>
                <button type="button" class="btn-change btn border rounded-pill">Change</button>
            </div>
            <small class="firstname ms-2 text-danger"></small>

            <div class="user-info d-flex justify-content-between align-items-center mt-3">
                <input current-val="<?php echo $user['lastname'] ?>" type="text" name="lastname" id="lastname" value="<?php echo $user['lastname'] ?>" class="fw-semibold input form-control" readonly>
                <button type="button" class="btn-change btn border rounded-pill">Change</button>
            </div>
            <small class="lastname ms-2 text-danger"></small>

            <div class="user-info d-flex justify-content-between align-items-center mt-3">
                <input current-val="<?php echo $user['email'] ?>" type="text" name="email" id="email" value="<?php echo $user['email'] ?>" class="fw-semibold input form-control" readonly>
                <button type="button" class="btn-change btn border rounded-pill">Change</button>
            </div>
            <small class="email ms-2 text-danger"></small>

            <div class="change-pass-con px-2 mt-3">
                <h6>Change Password</h6>
                <div class=" input-con">
                    <label for="password">Current Password</label>
                    <input type="password" name="password" class="w-100 input form-control rounded-0">
                    <small class="password ms-2 text-danger"></small>
                </div>
                <div class=" input-con">
                    <label for="password">New Password</label>
                    <input type="password" name="newpass" class="w-100 input form-control rounded-0">
                    <small class="newpass ms-2 text-danger"></small>
                </div>
            </div>
            <div class="btn-submit-con my-2 px-2 text-end">
                <button type="submit" class=" fw-semibold btn btn-primary rounded-0">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".btn-change").click(function(e) {
            let input = $(this).prev('input');
            if (input.attr('readonly')) {
                input.removeAttr('readonly');
                $(this).text("Cancel");
            } else {
                input.val(input.attr('current-val'))
                input.attr('readonly', '');
                $(this).text("Change");
            }

        });

        $('#editUserDetailsForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            // console.log(formData)
            $.ajax({
                type: "POST",
                url: "ajax/settings/edit_user.php",
                data: formData,
                dataType: "text",
                success: function(response) {
                    $('small').text('');

                    if (response == 'success') {
                      $('.alert-con').removeClass('d-none')
                      setTimeout(()=>{
                        $('.alert-con').addClass('d-none')
                      }, 2000)
                    } else {
                        let errors = JSON.parse(response);
                        for (const error in errors) {
                            $(`.${error}`).text(errors[error])
                        }
                    }
                }
            });
        });


    });
</script>

<?php view('footer.php') ?>