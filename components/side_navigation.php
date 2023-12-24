<?php 

$sql = "SELECT * FROM categories";
$statement = $pdo->query($sql);

$rows = $statement->fetchAll();
 
?>
<style>
  .side-nav{
    margin-top:70px;
  }
  .start-discussion .btn-open-modal-post{
      width: 95%;
      font-size: 0.7rem;
  }
  .categories-text{
    font-size: 0.8rem;
  }
  .topic-list a{
    font-size: 0.6rem;
    padding: 0.3rem 0.8rem 0.3rem 0.8rem;
  }
  @media (min-width: 640px) {
    .start-discussion .btn-open-modal-post{
       width: 70%;  
     }
  }
  @media (min-width: 768px) {
    .start-discussion .btn-open-modal-post{
      width: 60%;
    }
    .start-discussion .btn-open-modal-post{ 
      font-size: 0.9rem;
    }
  }
  @media (min-width: 1024px) {
     .side-nav{
       width: 350px;
       height: calc(100% - 60px); 
       position: fixed;
       top: 0;
       left: 0;
       margin-top:60px;
     }    
     .start-discussion .btn-open-modal-post{
       width: 90%; 
       font-size: 1rem;
     }
     .categories-text{
       font-size: 1.2rem;
     }
     .topic-list a{
        font-size: 0.8rem;
        padding: 0.5rem 1rem 0.5rem 1rem;
     }
  }
</style>
<div class="side-nav bg-light border overflow-auto">
 <div class="start-discussion px-5 my-2 d-flex justify-content-center">
  <button type="button" class="<?php echo isset($_SESSION['user_id']) ? 'btn-post':''?> btn-open-modal-post nav-link d-block py-2 text-white text-center rounded-3 fw-semibold" style="background:#6d28d9;" data-bs-toggle="modal" data-bs-target="#postModal">Start Discussion</button>
 </div>
 <div class="topic-container py-2 px-3 mt-1">
   <h5 class="py-1 categories-text fw-bold">Categories</h5>
   <div class="topic-list d-flex flex-wrap">
    <?php foreach($rows as $category):?>
      <a href="#" class=" nav-link m-1 fw-semibold" style="background: #93c5fd; color: #1d4ed8"><?php echo $category['name']?></a>
    <?php endforeach;?>
   </div>
 </div>
</div>
 

<script>
  $('.btn-post').click(function (e) { 
     $.ajax({
      type: "POST",
      url: "ajax/index/post_forum_form.php",
      success: function (response) {
        $('.modal-content').html(response)
      }
     });
  });
</script>