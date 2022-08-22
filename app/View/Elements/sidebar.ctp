<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
    <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
        
        <?php if (AuthComponent::user('id')): ?>


    
        <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5 d-none d-sm-inline">
                <span class="navbar-text">
                    Hi <?= AuthComponent::user('username') ?>! 
                </span>
            
            </span>
        </a>
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
                <a href="/marvelCharacters" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Characters List</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Create new list</span>
                </a>


                <form class="row g-3" action="/favouritesLists/add" id="addNewListForm" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="inputNewList" name="FavouriteList[name]" placeholder="My hero!" aria-label="My strongest heroes" aria-describedby="basic-addon2" required>
                        <input type="hidden" value="<?= AuthComponent::user('id') ?>" name="FavouriteList[user_id]" />         
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" id="submitNewList" type="submit">Create</button>
                        </div>
                    </div>

                </form>

            </li>
            <li>
                <a href="#submenuFavouritesLists" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">Your Lists</span> </a>
                    <ul class="collapse show nav flex-column ms-1" id="submenuFavouritesLists" data-bs-parent="#menu">

                        <?php // LIST OF USER FAVOURITE LISTS ?> 
                    </ul>
            </li>
            <li><a class="navbar-brand" href="/users/logout">Logout</a></li>								
        </ul>
        <hr>
        <?php endif; ?>     
    </div>
</div>



<script>


$(document).ready(function(){      
    fetchUserLists();

});

function fetchUserLists() {
    $.get( "/favouritesLists/list/<?= AuthComponent::user('id') ?>", function() {
    })
    .done(function(data, status) {
        
        $('#submenuFavouritesLists').empty();
        if(status == 'success' && Object.keys(data).length > 0) {
            Object.entries(data).forEach(([key, value]) => {
                $("#submenuFavouritesLists").append(
                    "<li><a href='#' class='nav-link px-0'><span class='d-none d-sm-inline favourite-list' data-list-id='"+key+"'>"+value+"</span></a></td>"
                );
			});            
        } 
    })
    .fail(function() {
        
    })
    .always(function() {
        bindEvents();
    });
}

function bindEvents() {
    $('.favourite-list').click(function() {        
        getListCharacters($(this).data('list-id'));
    });
}
    		
function getListCharacters(listId) {    
    $.get( "/favouritesLists/detail/" + listId, function() {
        $('.spinner-border').removeClass('d-none');
        $('table.table').addClass('d-none');
    })
    .done(function(data, status) {
        $('#charactersTBody').empty();
        if(status == 'success' && Object.keys(data).length > 0) {
            
            Object.entries(data).forEach(([id, value]) => {
                $("table.table").append(
                    "<tr><td>" + id + "</td>" + 
                    "<td>" + value.name + "</td>" +
                    "<td><img src='" + value.thumbnail + "' width='100'></td>" +
                    "<td><a href="+value.link_info+" target='_blank'>More info</a></td>" +
                    "<td></td>"  +
                    "</tr>"
                );

            });
            $('table.table').removeClass('d-none');
        } 
    })
    .fail(function() {
        
    })
    .always(function() {
        $('.spinner-border').addClass('d-none');
    });

}

$("#submitNewList").click(function(event) {
    event.preventDefault();
    var form = $("#addNewListForm");
    if($('#inputNewList').val() == '') {
        form.addClass('was-validated');
    } else {
        form.removeClass('was-validated');
        jQuery.post(form.attr('action'), form.serialize() , function (data, status) {
            if(status == 'success' && Object.keys(data).length > 0) {  
                $('#inputNewList').val('');              
                fetchUserLists();
            }
        }, 'json');
    }
});

</script>