<?php

namespace App\Http\Controllers;

use App\Models\TransaksiApotek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TransaksiApotekController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(Request $request){
        $acceptHeader = $request->header('Accept');
        $authorization = $request->header('Authorization');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            if($authorization){
                $transaksi = TransaksiApotek::with([
                    'user' => function($query){
                        $query->select('id','name');
                    },
                    'obat' => function($query){
                        $query->select('id','nama_obat');
                    }
                ])->Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
            }else{
                $transaksi = TransaksiApotek::OrderBy("id", "DESC")->paginate(2)->toArray();
            }
            $response = [
                "total_count" => $transaksi["total"],
                "limit" => $transaksi["per_page"],
                "pagination" => [
                    "next_page" => $transaksi["next_page_url"],
                    "current_page" => $transaksi["current_page"]
                ],
                "data" => $transaksi["data"],
            ];

            if ($acceptHeader === 'application/json') {
                return response()->json($response, 200);
            } else {
                $transaksi1 = TransaksiApotek::OrderBy("id", "DESC")->paginate(2);
                $xml = new \SimpleXMLElement('<transaksi/>');
                foreach ($transaksi1->items('data') as $item) {
                    $xmlItem = $xml->addChild('transaksi');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('obat_id', $item->obat_id);
                    $xmlItem->addChild('jumlah_obat', $item->jumlah_obat);
                    $xmlItem->addChild('total_harga', $item->total_harga);
                }
                return $xml->asXML();
            }

            $outPut = [
                "message" => "Transaksi",
                "result" => $transaksi
            ];

            
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function getall(Request $request){
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $transaksi = TransaksiApotek::OrderBy("id", "DESC")->paginate(2)->toArray();
            $response = [
                "total_count" => $transaksi["total"],
                "limit" => $transaksi["per_page"],
                "pagination" => [
                    "next_page" => $transaksi["next_page_url"],
                    "current_page" => $transaksi["current_page"]
                ],
                "data" => $transaksi["data"],
            ];

            if ($acceptHeader === 'application/json') {
                return response()->json($response, 200);
            } else {
                $xml = new \SimpleXMLElement('<transaksi/>');
                foreach ($transaksi->items('data') as $item) {
                    $xmlItem = $xml->addChild('transaksi');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('obat_id', $item->obat_id);
                    $xmlItem->addChild('jumlah_obat', $item->jumlah_obat);
                    $xmlItem->addChild('total_harga', $item->total_harga);
                }
                return $xml->asXML();
            }

            $outPut = [
                "message" => "Transaksi",
                "result" => $transaksi
            ];

            
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function store(Request $request){
        $acceptHeader = $request->header('Accept');
        $contentTypeHeader = $request->header('Content-Type');

        if ($acceptHeader === 'application/json') {
            $input = $request->all();
            $validationRules = [
                'user_id' => 'required|exists:users,id',
                'obat_id' => 'required',
                'jumlah_obat' => 'required',
                'total_harga' => 'required'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $transaksi = TransaksiApotek::create($input);
            return response()->json($transaksi, 200);

        }else{
            return response('Unsupported Media Type', 415);
        }
    }

    public function show($id, Request $request){
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $transaksi = TransaksiApotek::with([
                'user' => function($query){
                    $query->select('id','name');
                },
                'obat' => function($query){
                    $query->select('id','nama_obat');
                }
            ])->where('id',$id)->get();

            if ($acceptHeader === 'application/json') {
                return response()->json($transaksi, 200);
            } else {
                $xml = new \SimpleXMLElement('<transaksi/>');
                foreach ($transaksi as $item) {
                    $xmlItem = $xml->addChild('transaksi');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('obat_id', $item->obat_id);
                    $xmlItem->addChild('jumlah_obat', $item->jumlah_obat);
                    $xmlItem->addChild('total_harga', $item->total_harga);
                }
                return $xml->asXML();
            }

            if(!$transaksi) {
                abort(404);
            }
   
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function update(Request $request, $id){
        $acceptHeader = $request->header('Accept');
        $contentTypeHeader = $request->header('Content-Type');

        if ($acceptHeader === 'application/json') {
            $input = $request->all();
            $transaksi = TransaksiApotek::find($id);

            if(!$transaksi) {
                abort(404);
            }

            //validation
            $validationRules = [
                'user_id' => 'required|exists:users,id',
                'obat_id' => 'required',
                'jumlah_obat' => 'required',
                'total_harga' => 'required'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $transaksi->fill($input);
            $transaksi->save();

            return response()->json($transaksi, 200);
        }
        else{
            return response('Unsupported Media Type', 415);
        }
    }

    public function destroy($id, Request $request){
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $transaksi = TransaksiApotek::find($id);

            if(!$transaksi) {
                abort(404);
            }

            $transaksi->delete();
            $message = ['message' => 'deleted successfully', 'transaksi_id' => $id];
            return response()->json($message, 200);
   
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
