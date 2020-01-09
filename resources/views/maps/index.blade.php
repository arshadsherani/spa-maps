<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.jpg') }}">

    <!-- CSFR token for ajax call -->
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>Manage Address</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    {{-- <link rel="styleeheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}

    <!-- icheck checkboxes -->

    <!-- toastr notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}"> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .panel-heading {
            padding: 0;
        }
        .panel-heading ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .panel-heading li {
            float: left;
            border-right:1px solid #bbb;
            display: block;
            padding: 14px 16px;
            text-align: center;
        }
        .panel-heading li:last-child:hover {
            background-color: #ccc;
        }
        .panel-heading li:last-child {
            border-right: none;
        }
        .panel-heading li a:hover {
            text-decoration: none;
        }

        .table.table-bordered tbody td {
            vertical-align: baseline;
        }
    </style>

</head>

<body>
    <div class="col-md-10 col-md-offset-1">
	<h2 class="text-center">Maps Management</h2>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul>
					 <button class="btn btn-primary add-modal"><span class="glyphicon glyphicon-plus"></span> Add Address</button>
                </ul>
            </div>
        
            <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                            <tr>
                                <th valign="middle">#</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            {{ csrf_field() }}
                        </thead>
                        <tbody>
                            @foreach($maps as $indexKey => $map)
                                <tr class="item{{$map->id}}">
                                    <td class="col1">{{ $indexKey+1 }}</td>
                                    <td id="edit_maps{{$map->id}}">{{$map->address}}</td>
									<td>
									<select onChange="chang_status(this.value,{{$map->id}})">
										<option value="1" @if($map->status == 1) selected="selected" @endif > Enable</option>
										<option value="0" @if($map->status == 0) selected="selected" @endif > Disable </option>
									</select> </td>
                                   
                                    <td id="edit_action_maps{{$map->id}}">
                                        <button class="edit-modal btn btn-info" data-id="{{$map->id}}" data-title="{{$map->address}}">
                                        <span class="glyphicon glyphicon-edit"></span> Edit</button> 
                                        <button class="delete-modal btn btn-danger" data-id="{{$map->id}}" data-title="{{$map->address}}">
                                        <span class="glyphicon glyphicon-trash"></span> Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div><!-- /.panel-body -->
        </div><!-- /.panel panel-default -->
    </div><!-- /.col-md-8 -->
	
	
	<div class="col-md-3 col-md-offset-1">
	<h2 class="text-center">List of all Address</h2>
        <br />
            <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="show_address">
                        <thead>
                            <tr>
                                <th> All Address</th>
                            </tr>
                        </thead>
                        <tbody id="apend_adres">
                           <!-- @foreach($maps as $map)
                                <tr class="item{{$map->id}}">
                                    <td id="all_adrs_edit{{$map->id}}">{{$map->address}}</td>
                                </tr>
                            @endforeach-->
							
                        </tbody>
                    </table>
            </div><!-- /.panel-body -->
    </div>
	
	
	<div class="col-md-7">
	<h2 class="text-center">Search on Maps</h2>
        <br />
		<div class="panel panel-default"><br />
		 <div class="form-group col-sm-6">
		<input type="text" id="search_text" class="form-control" placeholder="Search Address">
		</div>
		 <button onClick="showalladdress()" class="btn btn-success"><i class="glyphicon glyphicon-eye-open"></i> Show all Address</button>
            <div class="panel-body ">
                   <div id="map" style="width: 730px; height: 500px;"></div>
            </div><!-- /.panel-body -->
			</div>
    </div>

    <!-- Modal form to add a post -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="address">Enter Address:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="address_add" autofocus>
                                <p class="errorTitle text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Add Address
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to edit a form -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <input type="hidden" class="form-control" id="id_edit">
                           
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="title">Address:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="address_edit" autofocus>
                                <p class="errorTitle text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal form to delete a form -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Are you sure you want to delete the following maps?</h3>
                    <br />
                    <form class="form-horizontal" role="form">
                       <input type="hidden" class="form-control" id="id_delete" disabled>
                           
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="title">Address:</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" id="address_delete" disabled>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>
    <!-- toastr notifications -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src='https://cdn.rawgit.com/pguso/jquery-plugin-circliful/master/js/jquery.circliful.min.js'></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>

  <script type="text/javascript" src="{{ asset('public/js/custom.js') }}"></script>
		
		<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd0wn0LFKCgZ-vk_1OdAEp4TtRjPdeUic&callback=initMap">  
    </script>
<script>
//   Auco Search 
$(function() {
    $( "#search_text" ).autocomplete({
      source: "{{ route('autocomplete.ajax') }}",
	  minLength: 1,
    });
  });
  
  $(document).ready(function () {
    $('#search_text').on('autocompleteselect', function (e, ui) {
        showalladdress(ui.item.value);
    });
});
		 
</script>
</body>
</html>
