<style>
    .lbl-create-1 {
        font-size: 1rem;
    }

    .lbl-create-2{
        font-size: 0.7rem;
    }

    @media (min-width: 640px) {
        .lbl-create-1 {
            font-size: 1.1rem;
        }
        .lbl-create-2 {
            font-size: 0.9rem;
        } 
    }
    @media (min-width: 768px) {
        .lbl-create-1 {
            font-size: 1.2rem;
        }
        .lbl-create-2 {
            font-size: 1rem;
        }
    }

    @media (min-width: 1024px) {}
</style>
<div class="modal fade" id="postProfileModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <div class="modal-header p-0 m-0 border-0">
                <h5 class=" lbl-create-1 w-100 text-center fw-semibold py-3">Change Profile Photo</h5>
            </div>
            <div class="modal-body p-0 m-0">
                <form id="profileForm" class=" d-none">
                  <input type="file" name="newPhoto" id="uploadPhotoInput" accept="image/*">
                </form>
                <button type="button" id="btnUploadPhoto" class="lbl-create-2 w-100 btn border-0 border-bottom border-top text-center py-3 fw-bold" style="color: #6d28d9">Upload Photo</button>
                <?php if($user['profile_photo'] != 'default_profile.png'):?>
                  <button type="button" id="btnRemovePhoto" class="lbl-create-2 w-100 btn border-0 border-bottom text-center text-danger py-3 fw-bold">Remove Photo</button>
                <?php endif;?>
                <button type="button" class="lbl-create-2 w-100 btn border-0 text-center py-3 fw-semibold" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>

 <script>
    $(document).ready(function () {
        $('#btnUploadPhoto').click(function(e) {
            $('#uploadPhotoInput').click();
        });

        $('#uploadPhotoInput').change(function(e) {
            let photo = new FormData(document.querySelector('#profileForm')) 
            $.ajax({
                type: "POST",
                url: "ajax/profile/upload_profile.php",
                enctype: 'multipart/form-data',
                data: photo,
                processData: false,
                contentType: false,
                dataType: "text", 
                success: function (response) { 
                    $('.user-image').attr('src', 'uploaded_images/' + response);
                    $('.nav-image-profile').attr('src', 'uploaded_images/' + response);
                    $('#postProfileModal').modal('hide');
                }
            });
        });

        $('#btnRemovePhoto').click(function(){
            $.ajax({
                type: "POST",
                url: "ajax/profile/remove_profile.php", 
                dataType: "text", 
                success: function (response) {
                    $('.user-image').attr('src', 'uploaded_images/' + response);
                    $('.nav-image-profile').attr('src', 'uploaded_images/' + response);
                    $('#postProfileModal').modal('hide');
                }
            });
        })
    });
 </script>