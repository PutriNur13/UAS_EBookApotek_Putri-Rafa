<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(Request $request){
        if(Gate::denies('read-supplier')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }

        $acceptHeader = $request->header('Accept');
        $authorization = $request->header('Authorization');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $supplier = Supplier::OrderBy("id", "DESC")->paginate(2)->toArray();
            $response = [
                "total_count" => $supplier["total"],
                "limit" => $supplier["per_page"],
                "pagination" => [
                    "next_page" => $supplier["next_page_url"],
                    "current_page" => $supplier["current_page"]
                ],
                "data" => $supplier["data"],
            ];

            if ($acceptHeader === 'application/json') {
                return response()->json($response, 200);
            } else {
                $xml = new \SimpleXMLElement('<supplier/>');
                foreach ($supplier->items('data') as $item) {
                    $xmlItem = $xml->addChild('supplier');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_supplier', $item->nama_supplier);
                    $xmlItem->addChild('alamat_supplier', $item->alamat_supplier);
                    $xmlItem->addChild('no_telp', $item->no_telp);
                }
                return $xml->asXML();
            }

            $outPut = [
                "message" => "Supplier",
                "result" => $supplier
            ];

            
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function getall(Request $request){
        if(Gate::denies('read-supplier')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $supplier = Supplier::OrderBy("id", "DESC")->paginate(2)->toArray();
            $response = [
                "total_count" => $supplier["total"],
                "limit" => $supplier["per_page"],
                "pagination" => [
                    "next_page" => $supplier["next_page_url"],
                    "current_page" => $supplier["current_page"]
                ],
                "data" => $supplier["data"],
            ];

            if ($acceptHeader === 'application/json') {
                return response()->json($response, 200);
            } else {
                $xml = new \SimpleXMLElement('<supplier/>');
                foreach ($supplier->items('data') as $item) {
                    $xmlItem = $xml->addChild('supplier');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_supplier', $item->nama_supplier);
                    $xmlItem->addChild('alamat_supplier', $item->alamat_supplier);
                    $xmlItem->addChild('no_telp', $item->no_telp);
                }
                return $xml->asXML();
            }

            $outPut = [
                "message" => "Supplier",
                "result" => $supplier
            ];

            
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function store(Request $request){
        if(Gate::denies('create-supplier')){
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
                'nama_supplier' => 'required',
                'alamat_supplier' => 'required',
                'no_telp' => 'required'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $supplier = Supplier::create($input);
            return response()->json($supplier, 200);

        }else{
            return response('Unsupported Media Type', 415);
        }
    }

    public function show($id, Request $request){
        if(Gate::denies('read-supplier')){
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized' 
            ], 403);
        }
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $supplier = Supplier::where('id',$id)->get();

            if ($acceptHeader === 'application/json') {
                return response()->json($supplier, 200);
            } else {
                $xml = new \SimpleXMLElement('<supplier/>');
                foreach ($supplier as $item) {
                    $xmlItem = $xml->addChild('supplier');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('nama_supplier', $item->nama_supplier);
                    $xmlItem->addChild('alamat_supplier', $item->alamat_supplier);
                    $xmlItem->addChild('no_telp', $item->no_telp);
                }
                return $xml->asXML();
            }

            if(!$supplier) {
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
            $supplier = Supplier::find($id);

            if(!$supplier) {
                abort(404);
            }

            if(Gate::denies('update-supplier')){
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'You are unauthorized' 
                ], 403);
            }

            //validation
            $validationRules = [
                'nama_supplier' => 'required',
                'alamat_supplier' => 'required',
                'no_telp' => 'required'
            ];

            $validator = Validator::make($input, $validationRules);

            if ($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $supplier->fill($input);
            $supplier->save();

            return response()->json($supplier, 200);
        }
        else{
            return response('Unsupported Media Type', 415);
        }
    }

    public function destroy($id, Request $request){
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $supplier = Supplier::find($id);

            if(!$supplier) {
                abort(404);
            }
            if(Gate::denies('destroy-supplier')){
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'You are unauthorized' 
                ], 403);
            }

            $supplier->delete();
            $message = ['message' => 'deleted successfully', 'supplier_id' => $id];
            return response()->json($message, 200);
   
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
