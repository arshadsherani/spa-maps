<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Map;
use View;
use DB;


class MapsController extends Controller
{

    protected $rules =
    [
        'address' => 'required',
    ];

    public function index()
    {
        $maps = Map::orderBy('id', 'desc')->get();
        return view('maps.index', ['maps' => $maps]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        } else {
			  $address = $request->address;
			  $prepAddr = str_replace(' ','+',$address);
			  $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyAnL8rQZSd9olvjgOzC4vhijEqcEsbMd-I');
			  $output= json_decode($geocode);
			  $lat = $output->results[0]->geometry->location->lat;
			  $lng = $output->results[0]->geometry->location->lng;
		  
            $map = new Map();
            $map->address = $request->address;
            $map->lat = $lat;
			$map->lng = $lng;
            $map->save();
            return response()->json($map);
        }
    }

    public function show($id)
    {
		//
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        } else {
			  $address = $request->address;
			  $prepAddr = str_replace(' ','+',$address);
			  $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyAnL8rQZSd9olvjgOzC4vhijEqcEsbMd-I');
			  $output= json_decode($geocode);
			  $lat = $output->results[0]->geometry->location->lat;
			  $lng = $output->results[0]->geometry->location->lng;
			  
            $map = Map::findOrFail($id);
            $map->address = $request->address;
            $map->lat = $lat;
			$map->lng = $lng;
            $map->save();
            return response()->json($map);
        }
    }
    
    public function destroy($id)
    {
        $map = Map::findOrFail($id);
        $map->delete();

        return response()->json($map);
    }
   
    public function changeStatus(Request $request) 
    {
        $id = Input::get('id');
		
        $map = Map::findOrFail($id);
		$map->status = $request->status;
		$map->save();
		return response()->json($map);
    }
	
	public function show_all_adddress(){
		$address  = Input::get('address');
		$maps = DB::table('maps')->select('address', 'lat','lng')->where('status', 1)
		->when (!empty($address) , function ($maps) use($address){
				return $maps->where('address',$address);
				})->get();
        return response()->json($maps);
		
	}
	
	public function get_all_adddress(){
		$maps = DB::table('maps')->select('address')->where('status', 1)->orderBy('id', 'desc')->get();
        return response()->json($maps);
		
	}
	
	 public function ajaxData(Request $request){

		$term = Input::get('term');
		$results = array();
	
		$queries = DB::table('maps')->where('address', 'LIKE', '%'.$term.'%')->where('status', 1)->get();
	
			foreach ($queries as $query){
				$results[] = [ 'id' => $query->id, 'value' => $query->address ];
			}
		return Response::json($results);

	}
}