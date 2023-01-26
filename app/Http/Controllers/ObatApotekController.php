<?php

namespace App\Http\Controllers;

use App\Models\ObatApotek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ObatApotekController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(Request $request){

        if(Gate::denies('read-obat')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }

        $acceptHeader = $request->header('Accept');
            if ($acceptHeader === 'application/json') {
                $obat = ObatApotek::with([
                    'supplier' => function($query){
                        $query->select('id','nama_supplier');
                    }
                ])->OrderBy("id", "DESC")->paginate(10)->toArray();
                $response = [
                    "total_count" => $obat["total"],
                    "limit" => $obat["per_page"],
                    "pagination" => [
                        "next_page" => $obat["next_page_url"],
                        "current_page" => $obat["current_page"]
                    ],
                    "data" => $obat["data"],
                ];
                return response()->json($response, 200);
            } else {
                $obat = ObatApotek::OrderBy("id", "DESC")->paginate(2);
                $xml = new \SimpleXMLElement('<obat/>');
                foreach ($obat->items('data') as $item) {
                    $xmlItem = $xml->addChild('obat');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_obat', $item->nama_obat);
                    $xmlItem->addChild('stok_obat', $item->stok_obat);
                    $xmlItem->addChild('harga_obat', $item->harga_obat);
                    $xmlItem->addChild('deskripsi_obat', $item->deskripsi_obat);
                    $xmlItem->addChild('komposisi_obat', $item->komposisi_obat);
                    $xmlItem->addChild('dosis_obat', $item->dosis_obat);
                    $xmlItem->addChild('penyajian_obat', $item->penyajian_obat);
                    $xmlItem->addChild('golongan_obat', $item->golongan_obat);
                    $xmlItem->addChild('efek_samping_obat', $item->efek_samping_obat);
                }
                return $xml->asXML();
            }
    }


    public function getall(Request $request){

        if(Gate::denies('read-obat')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            if ($acceptHeader === 'application/json') {
                $obat = ObatApotek::OrderBy("id", "DESC")->paginate(2)->toArray();
                $response = [
                    "total_count" => $obat["total"],
                    "limit" => $obat["per_page"],
                    "pagination" => [
                        "next_page" => $obat["next_page_url"],
                        "current_page" => $obat["current_page"]
                    ],
                    "data" => $obat["data"],
                ];
                return response()->json($response, 200);
            } else {
                $obat = ObatApotek::OrderBy("id", "DESC")->paginate(2);
                $xml = new \SimpleXMLElement('<obat/>');
                foreach ($obat->items('data') as $item) {
                    $xmlItem = $xml->addChild('obat');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_obat', $item->nama_obat);
                    $xmlItem->addChild('stok_obat', $item->stok_obat);
                    $xmlItem->addChild('harga_obat', $item->harga_obat);
                    $xmlItem->addChild('deskripsi_obat', $item->deskripsi_obat);
                    $xmlItem->addChild('komposisi_obat', $item->komposisi_obat);
                    $xmlItem->addChild('dosis_obat', $item->dosis_obat);
                    $xmlItem->addChild('penyajian_obat', $item->penyajian_obat);
                    $xmlItem->addChild('golongan_obat', $item->golongan_obat);
                    $xmlItem->addChild('efek_samping_obat', $item->efek_samping_obat);
                }
                return $xml->asXML();
            }
            
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function store(Request $request){
        if(Gate::denies('create-obat')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }
        $acceptHeader = $request->header('Accept');
        $contentTypeHeader = $request->header('Content-Type');

        if ($acceptHeader === 'application/json') {
            $input = $request->all();
            $validationRules = [
                'nama_obat' => 'required',
                'stok_obat' => 'required',
                'harga_obat' => 'required',
                'deskripsi_obat' => 'required',
                'komposisi_obat' => 'required',
                'dosis_obat' => 'required',
                'penyajian_obat' => 'required',
                'golongan_obat' => 'required',
                'deskripsi_obat' => 'required',
                'efek_samping_obat' => 'required',
                'supplier_id' => 'required|exists:supplier,id'
            ];
            

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $obat = ObatApotek::create($input);
            return response()->json($obat, 200);

        }else{
            return response('Unsupported Media Type', 415);
        }
    }

    public function show($id_obat, Request $request){
        $acceptHeader = $request->header('Accept');

        if(Gate::denies('read-obat')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $obat = ObatApotek::with([
                'supplier' => function($query){
                    $query->select('id','nama_supplier');
                }
            ])->where('id',$id_obat)->get();

            if ($acceptHeader === 'application/json') {
                return response()->json($obat, 200);
            } else {
                $xml = new \SimpleXMLElement('<obat/>');
                foreach ($obat as $item) {
                    $xmlItem = $xml->addChild('obat');

                    $xmlItem->addChild('nama_obat', $item->nama_obat);
                    $xmlItem->addChild('stok_obat', $item->stok_obat);
                    $xmlItem->addChild('harga_obat', $item->harga_obat);
                    $xmlItem->addChild('deskripsi_obat', $item->deskripsi_obat);
                    $xmlItem->addChild('komposisi_obat', $item->komposisi_obat);
                    $xmlItem->addChild('dosis_obat', $item->dosis_obat);
                    $xmlItem->addChild('penyajian_obat', $item->penyajian_obat);
                    $xmlItem->addChild('golongan_obat', $item->golongan_obat);
                    $xmlItem->addChild('efek_samping_obat', $item->efek_samping_obat);
                    $xmlItem->addChild('supplier_id', $item->supplier_id);
                }
                return $xml->asXML();
            }

            if(!$obat) {
                abort(404);
            }
   
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function update(Request $request, $id_obat){
        $acceptHeader = $request->header('Accept');
        $contentTypeHeader = $request->header('Content-Type');

        if ($acceptHeader === 'application/json') {
            $input = $request->all();
            $obat = ObatApotek::find($id_obat);

            if(!$obat) {
                abort(404);
            }

            if(Gate::denies('update-obat')){
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'You are unauthorized' 
                ], 403);
            }

            //validation
            $validationRules = [
                'nama_obat' => 'required',
                'stok_obat' => 'required',
                'harga_obat' => 'required',
                'deskripsi_obat' => 'required',
                'komposisi_obat' => 'required',
                'dosis_obat' => 'required',
                'penyajian_obat' => 'required',
                'golongan_obat' => 'required',
                'deskripsi_obat' => 'required',
                'efek_samping_obat' => 'required',
                'supplier_id' => 'required|exists:supplier,id'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $obat->fill($input);
            $obat->save();

            return response()->json($obat, 200);
        }
        else{
            return response('Unsupported Media Type', 415);
        }
    }

    public function destroy($id_obat, Request $request){
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $obat = ObatApotek::find($id_obat);
            if(Gate::denies('destroy-obat')){
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'You are unauthorized' 
                ], 403);
            }
            if(!$obat) {
                abort(404);
            }

            $obat->delete();
            $message = ['message' => 'deleted successfully', 'obat_id' => $id_obat];
            return response()->json($message, 200);
   
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
