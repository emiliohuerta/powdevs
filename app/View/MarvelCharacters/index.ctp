<div class="spinner-border d-none" role="status" >    
</div>

<div class="error-message d-none" >
    An error ocurred while fetching the data, please try again 
</div>

<div class="input-group mb-3">
    <input type="text" class="form-control" name="search" id="searchText" placeholder="Batman" aria-label="search-superhero" aria-describedby="search-superhero">
    
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" id="searchSuperhero" type="button">Search</button>
    </div>
</div>

<table class="table table-striped d-none">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Img</th>
	  <th scope="col">Links</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody id="charactersTBody">
     
  </tbody>
</table>


<!-- Modal -->
<div class="modal fade" id="favouritesModal" tabindex="-1" aria-labelledby="favouritesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="favouritesModalLabel">Your Lists</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
			<?php 

				if(!empty($userFavouriteLists)) {

					foreach($userFavouriteLists as $id => $name) {  ?>

						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="<?= $id ?>" name="favLists[]" >
							<label class="form-check-label" for="flexCheckDefault">
							<?= $name ?>
							</label>
						</div>
						<input type="hidden" value="" id="character-id-fav" />
					<?php
					}

				} else {
					echo "You have no lists created! Go to <a href='/favouritesLists'>this link</a> and create a new list!";
				}


			?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addToFavourites">Add!</button>
      </div>
    </div>
  </div>
</div>

<script>



$(document).ready(function(){
	fetchCharacters();
    bindEventsSearch();    
});

function fetchCharacters(search = '') {
	$('.spinner-border').removeClass('d-none');
    $('table.table').addClass('d-none');
	$('#charactersTBody').empty();

	$.get( "/marvelCharacters/list?search=" + search, function() {       
    })
    .done(function(data, status) {        
        if(status == 'success' && Object.keys(data).length > 0) {            
            
			Object.entries(data).forEach(([key, value]) => {
				appendRow(key, value);
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

function bindEventsSearch() {
	$('#searchSuperhero').click(function() {
		fetchCharacters($('#searchText').val());
	});

	$('#searchText').keyup(function(){
		fetchCharacters($('#searchText').val())
	});

	$('#addToFavourites').click(function(){
		addToFavourites();
	});
}

function appendRow(id, data) {

	$("table.table").append(
		"<tr><td>" + id + "</td>" + 
		"<td>" + data.name + "</td>" +
		"<td><img src='" + data.thumbnail + "' width='100'></td>" +
		"<td><a href="+data.link_info+" target='_blank'>More info</a></td>" +
		"<td>" +
			"<button type='button' class='btn btn-primary open-modal' data-bs-toggle='modal' data-bs-target='#favouritesModal' data-character-id='"+id + "'>" +
				"Add to favourites" +
			"</button>" +
		"</td>"  +
		"</tr>"
	);
}

function addToFavourites() {
	
	var listsIdsArr=[];
	$("input:checkbox[name*=favLists]:checked").each(function(){
		listsIdsArr.push($(this).val());
	});
    
	$.ajax({
        url:'/favouritesLists/addCharacterToFavourites',
        type:'post',
        dataType:'text',
        data:{listsIds:listsIdsArr, characterId:$('#character-id-fav').val()},
        success:function(data){
            $('#favouritesModal').modal('toggle');
        },
        error:function(xx){
            
        }
    });	
}

$('#favouritesModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
	$('#character-id-fav').val(button.data('character-id'));
	$('input[type=checkbox]').prop('checked',false);

})

</script>