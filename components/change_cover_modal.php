php
<style>
    .lbl-create-1 {
        font-size: 1rem;
    }

    .lbl-create-2 {
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
    .background-item{
        height: 80px;
        cursor: pointer;
    }
    .background-item:hover{
        border: 1px solid #121212
    }
</style>
 
<div class="modal fade" id="coverBackgroundModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-0">
            <div class="modal-header p-0 m-0 border-bottom">
                <h5 class=" lbl-create-1 w-100 text-center fw-semibold py-3">Choose Background</h5>
            </div>
            <div class="modal-body overflow-hidden">
                <div class="row ">
                  <?php foreach($pdo->query('SELECT * FROM covers')->fetchAll() as $cover_background) : ?>
                    <div class="col-4 p-1">
                        <div class="background-item" id="<?php echo $cover_background['id']?>" style="background-image: linear-gradient(<?php echo $cover_background['linear_gradient']?>);"></div>
                    </div> 
                  <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        $('.background-item').click(function (e) {  
            let id = $(this).prop('id') 
            $.ajax({
                type: "GET",
                url: "ajax/profile/change_cover.php",
                data: {id},
                dataType: "text",
                success: function (response) {
                   if ($.trim(response) != '') {
                     $('.cover-photo').css('background-image', `linear-gradient(${response})`)
                     $('#coverBackgroundModal').modal('hide');
                   }
                }
            });
        });
    });
</script>