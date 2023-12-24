 <?php 
 require_once '../../../database/connection.php';
 
 $sql = "SELECT * FROM categories";
 $statement = $pdo->query($sql);

 $result = $statement->fetchAll();
 
 ?>
 <div class="modal-header">
     <h5 class="modal-title" id="modalTitleId">Create Post</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <form id="postForm">
     <div class="modal-body">
         <div class="input-con my-1">
             <label for="categories">Select Category</label>
             <select name="categories" class="form-select rounded-0">
                 <?php foreach($result as $categories):?>
                    <option value="<?php echo $categories['id']?>"><?php echo $categories['name']?></option>
                 <?php endforeach;?>
             </select>
         </div>
         <div class="input-con my-1">
           <label for="categories">Title</label>
           <input type="text" name="title" class=" form-control rounded-0" placeholder="Title of your post..">
        </div>
         <div class="input-con my-1">
             <label for="image">Upload Image</label>
             <input type="file" name="image" class=" form-control rounded-0">
         </div>
         <div class="input-con my-3">
             <textarea name="content" cols="30" rows="5" class="form-control rounded-0" placeholder="Start discussion"></textarea>
         </div>
     </div>
     <div class="modal-footer">
         <button type="submit" class="btn btn-primary">Post</button>
     </div>
 </form>

 <script>
     //    $('#postModal').modal('toggle');
     $(document).ready(function() {
         $('#postForm').submit(function(e) {
             e.preventDefault();
             let formData = new FormData(this);
             $('.modal-content').html(`
               <div class="modal-spinner py-5 text-center bg-white">
                 <span class="spinner-border spinner-border-lg"></span>
                 <p class="my-2 text-center fs-5 fw-semibold">Posting</p>
               </div>
             `) 
             $.ajax({
                 type: "POST",
                 url: "ajax/index/post_forum.php",
                 enctype: 'multipart/form-data',
                 data: formData,
                 dataType: 'text',
                 processData: false, 
                 contentType: false,
                 timeout: 10000,
                 success: function(response) {
                     $('.post-list').prepend(response)
                     $('#postModal').modal('toggle');
                 },
                 error: function(jqXHR, textStatus, errorThrown) {
                     if (textStatus == 'timeout') {
                        $('.modal-content').html(`
                           <div class="modal-spinner py-5 text-center bg-white"> 
                             <p class="my-2 text-center fs-5 fw-semibold">Please check your network</p>
                           </div>
                        `)
                     }
                 }
             });


         });
     });
 </script>