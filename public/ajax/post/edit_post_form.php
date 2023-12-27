<?php 
 require_once '../../../database/connection.php';
 $sql = "SELECT post.*, categories.name FROM post
         INNER JOIN categories ON post.category_id = categories.id
         WHERE post.id = :id";
 $statement = $pdo->prepare($sql);
 $statement->bindParam(":id", $_GET['postid'], PDO::PARAM_INT);
 $statement->execute();
 $post = $statement->fetch(PDO::FETCH_NAMED);

 $sql = "SELECT * FROM categories";
 $statement = $pdo->query($sql);

 $category_result = $statement->fetchAll();
 ?>
 <div class="modal-header">
     <h5 class="modal-title" id="modalTitleId">Edit Post</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <form id="postForm">
     <div class="modal-body">
         <input type="hidden" name="postid" value="<?php echo $post['id']?>">
         <input type="hidden" name="current_image_name" value="<?php echo $post['image_name']?>">
         <div class="input-con my-1">
             <label for="categories">Select Category</label>
             <select name="categories" class="form-select rounded-0">
                <option value="<?php echo $post['category_id']?>"><?php echo $post['name']?></option>
                 <?php foreach($category_result as $categories):?>
                    <?php if($categories['id'] != $post['category_id']):?>
                        <option value="<?php echo $categories['id']?>"><?php echo $categories['name']?></option>
                    <?php endif;?>
                 <?php endforeach;?>
             </select>
         </div>
         <div class="input-con my-1">
           <label for="categories">Title</label>
           <input type="text" name="title" class=" form-control rounded-0" placeholder="Title of your post.." value="<?php echo $post['title']?>">
        </div>
         <div class="input-con my-1">
             <label for="image">Change Image</label>
             <input type="file" name="image" class=" form-control rounded-0">
         </div>
         <div class="input-con my-3">
             <textarea name="content" cols="30" rows="5" class="form-control rounded-0" placeholder="Start discussion"><?php echo $post['content']?></textarea>
         </div>
     </div>
     <div class="modal-footer">
         <button type="submit" class="btn btn-primary">Save</button>
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
                 url: "ajax/post/edit_post.php",
                 enctype: 'multipart/form-data',
                 data: formData, 
                 processData: false, 
                 contentType: false,
                 timeout: 10000,
                 success: function(response) {  
                     const postDetails = JSON.parse(response);
                     if (postDetails.image_name) {
                        $('.content-img').attr('src', `uploaded_images/${postDetails.image_name}`)
                     }
                     $('.title-post').text(postDetails.title);
                     $('.category-item').text(postDetails.category);
                     $('.post-content-text').text(postDetails.content);
                     
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