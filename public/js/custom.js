// Load Maps

$(document).ready(function(){ 
	showalladdress();
});

$(document).ready(function(){ 
	getalladdress();
});
function getalladdress(){
	var html;
	$.ajax({
		type: "GET",
		url: "get-all-adddress",
		success: function(data){ 
			 for (var i = 0; i < data.length; i++) {  
				html+="<tr><td>"+data[i]['address']+"</td></tr>";
    		}
			$("#apend_adres").html(html);
		}
	});
}

function showalladdress(address){
	if(!address){
		address='';
	}
	$.ajax({
		type: "GET",
		url: "show-all-adddress?address="+address,
		success: function(data){ 

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(-37.92, 151.25),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
      panControl: false,
      zoomControlOptions: {
         position: google.maps.ControlPosition.LEFT_BOTTOM
      }
    });

    var infowindow = new google.maps.InfoWindow({
      maxWidth: 160
    });

    var markers = new Array();
    // Add the markers and infowindows to the map
    for (var i = 0; i < data.length; i++) {  
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(data[i]['lat'], data[i]['lng']),
        map: map,
        icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
      });

      markers.push(marker);

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(data[i]['address']);
          infowindow.open(map, marker);
        }
      })(marker, i));
      
    }

    function autoCenter() {
      //  Create a new viewpoint bound
      var bounds = new google.maps.LatLngBounds();
      //  Go through each...
      for (var i = 0; i < markers.length; i++) {  
				bounds.extend(markers[i].position);
      }
      //  Fit these bounds to the map
      map.fitBounds(bounds);
    }
    autoCenter();
	   }
	}); //Ajax End
}

 <!-- AJAX CRUD operations -->
        // add a new post
        $(document).on('click', '.add-modal', function() {
			$('.errorTitle').addClass('hidden');
            $('.modal-title').text('Add');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function() {
            $.ajax({
                type: 'POST',
                url: 'maps',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'address': $('#address_add').val(),
                },
                success: function(data) {
                    $('.errorTitle').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.address) {
                            $('.errorTitle').removeClass('hidden');
                            $('.errorTitle').text(data.errors.address);
                        }
                       
                    } else {
                        toastr.success('Successfully added Address!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class='item" + data.id + "'><td class='col1'>" + data.id + "</td><td id='edit_maps"+data.id+"'>" + data.address + "</td><td><select><option value='1'> Enable</option><option value='0'>Disable</option></select></td><td id='edit_action_maps"+data.id+"'><button class='edit-modal btn btn-info' data-id='" + data.id + "' data-title='" + data.address + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-title='" + data.address + "'><span class='glyphicon glyphicon-trash'></span> Delete</button></td></tr>");
                       
					   $("#show_address").prepend("<tr class='item" + data.id + "'><td id='all_adrs_edit"+data.id+"'>" + data.address + "</td></tr>");
					   $("#address_add").val("");
					   $('.col1').each(function (index) {
                        $(this).html(index+1);
                    });
					    showalladdress();
						getalladdress();
                    }
                },
            });
        });

        
// Edit a post
        $(document).on('click', '.edit-modal', function() {
			$('.errorTitle').addClass('hidden');									
            $('.modal-title').text('Edit');
            $('#id_edit').val($(this).data('id'));
            $('#address_edit').val($(this).data('title'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function() {
            $.ajax({
                type: 'PUT',
                url: 'maps/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'address': $('#address_edit').val(),
                },
                success: function(data) {
                    $('.errorTitle').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('Validation error!', 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.address) {
                            $('.errorTitle').removeClass('hidden');
                            $('.errorTitle').text(data.errors.address);
                        }
                       
                    } else { $("#edit_maps"+ data.id).html(data.address); 
							$("#edit_action_maps"+ data.id).html("<button class='edit-modal btn btn-info' data-id='" + data.id + "' data-title='" + data.address + "'><span class='glyphicon glyphicon-edit'></span> Edit</button> <button class='delete-modal btn btn-danger' data-id='" + data.id + "' data-title='" + data.address + "'><span class='glyphicon glyphicon-trash'></span> Delete</button>");
							
							$("#all_adrs_edit"+ data.id).html(data.address);
								
                        toastr.success('Successfully updated Address!', 'Success Alert', {timeOut: 5000});
                       showalladdress();
					   getalladdress();
                    }
                }
            });
        });
        
		
	$(document).on('click', '.change-modal', function() { 
			$('.errorTitle').addClass('hidden');									
            $('.modal-title').text('Edit');
            $('#id_edit').val($(this).data('id'));
            $('#address_edit').val($(this).data('title'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
	
		
        // delete a post
        $(document).on('click', '.delete-modal', function() {
            $('.modal-title').text('Delete');
            $('#id_delete').val($(this).data('id'));
            $('#address_delete').val($(this).data('title'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function() {
            $.ajax({
                type: 'DELETE',
                url: 'maps/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function(data) {
                    toastr.success('Successfully deleted Address!', 'Success Alert', {timeOut: 5000});
                    $('.item' + data['id']).remove();
                    $('.col1').each(function (index) {
                        $(this).html(index+1);
                    });
					showalladdress();
					getalladdress();
                }
            });
        });
		
		function chang_status(status, id) { 
            $.ajax({
                 	type: 'POST',
					url: 'changestatus',
					data: {
						'_token': $('input[name=_token]').val(),
						'status': status,
						'id': id,
					},
                success: function() {
					 toastr.success('Successfully Changed Status!', 'Success Alert', {timeOut: 5000});
					 showalladdress();
					 getalladdress();
                },
            });
        }
