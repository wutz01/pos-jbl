<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\InventoryTrail;
use DataTables, Validator, Auth;

class InventoryController extends Controller
{
  public function __construct () {
  }

  public function index () {
    return view('inventory.index');
  }

  public function loadData () {
    $inventory = Inventory::where('status', 'ACTIVE')->get();
    return DataTables::of($inventory)
      ->editColumn('pricePerPiece', function($inventory){
        return "PHP " . number_format($inventory->pricePerPiece,2,'.',',');
      })
      ->editColumn('bulkPrice', function($inventory){
        return "PHP " . number_format($inventory->bulkPrice,2,'.',',');
      })
      ->editColumn('supplierPrice', function($inventory){
        return "PHP " . number_format($inventory->supplierPrice,2,'.',',');
      })
      ->editColumn('stockQty', function($inventory){
        $pcs = 'piece';
        if ($inventory->stockQty > 1) {
          $pcs = 'pcs';
        }
        return $inventory->stockQty . ' ' . $pcs;
      })
      ->addColumn('viewUrl', function($inventory){
        return route('inventory.view', $inventory->id);
      })
      ->make();
  }

  public function create () {
    return view('inventory.new');
  }

  public function view ($id) {
    if (!$id) return redirect()->route('inventory.index');
    $stock = Inventory::findOrFail($id);
    return view('inventory.view', compact('stock'));
  }

  public function loadTrail ($id) {
    $stock = Inventory::findOrFail($id)->trails()->with('userAttached')->orderBy('id', 'DESC')->take(9)->get();
    return view('inventory.trail', compact('stock'));
  }

  public function save (Request $request) {
    $message = [
        'medicineName.required'  => 'Medicine Name is required.',
        'pricePerPiece.required' => 'Price is required.',
        'bulkPrice.required'     => 'Bulk Price is required.',
        'supplierPrice.required' => 'Supplier`s Price is required.',
        'quantityStock.required' => 'Quantity is required.',
        'pricePerPiece.numeric'  => 'Price must be valid number.',
        'bulkPrice.numeric'      => 'Bulk Price must be valid number.',
        'supplierPrice.numeric'  => 'Supplier`s Price must be valid number.',
        'quantityStock.numeric'  => 'Quantity must be valid number.',
    ];

    $rules = [
      'medicineName'  => 'required',
      'pricePerPiece' => 'required|numeric',
      'bulkPrice'     => 'required|numeric',
      'supplierPrice' => 'required|numeric',
      'quantityStock' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      $json['validator_error'] = $validator->messages();
      $json['is_successful'] = false;
    } else {
      $inv = new Inventory;
      $inv->medicineName  = $request->input('medicineName');
      $inv->medicineType  = $request->input('productType');
      $inv->pricePerPiece = str_replace(',', '', $request->input('pricePerPiece'));
      $inv->bulkPrice     = str_replace(',', '', $request->input('bulkPrice'));
      $inv->supplierName  = $request->has('supplierName') ? $request->input('supplierName') : '-';
      $inv->supplierPrice = str_replace(',', '', $request->input('supplierPrice'));
      $inv->stockQty      = $request->input('quantityStock');
      $inv->description   = $request->has('description') ? $request->input('description') : '-';
      $inv->save();
      $pcs = 'piece';
      if ($inv->stockQty > 1) {
        $pcs = 'pcs';
      }
      $json['message'] = "{$inv->medicineName} [{$inv->stockQty} {$pcs}] has been added in our inventory.";
      $json['is_successful'] = true;
    }

    return response()->json($json);
  }

  public function update ($id, Request $request) {
    $message = [
        'medicineName.required'  => 'Medicine Name is required.',
        'pricePerPiece.required' => 'Price is required.',
        'bulkPrice.required'     => 'Bulk Price is required.',
        'supplierPrice.required' => 'Supplier`s Price is required.',
        'quantityStock.required' => 'Quantity is required.',
        'pricePerPiece.numeric'  => 'Price must be valid number.',
        'bulkPrice.numeric'      => 'Bulk Price must be valid number.',
        'supplierPrice.numeric'  => 'Supplier`s Price must be valid number.',
        'quantityStock.numeric'  => 'Quantity must be valid number.',
    ];

    $rules = [
        'medicineName'  => 'required',
        'pricePerPiece' => 'required|numeric',
        'bulkPrice'     => 'required|numeric',
        'supplierPrice' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      $json['validator_error'] = $validator->messages();
      $json['is_successful'] = false;
    } else {
      $inv = Inventory::findOrFail($id);
      // TRAIL PRICE UPDATE
      if (floatval($inv->pricePerPiece) != floatval(str_replace(',', '', $request->input('pricePerPiece'))) || floatval($inv->supplierPrice) != floatval(str_replace(',', '', $request->input('supplierPrice'))) || floatval($inv->bulkPrice) != floatval(str_replace(',', '', $request->input('bulkPrice')))) {
        $msg = 'has updated';
        if (floatval($inv->pricePerPiece) != floatval(str_replace(',', '', $request->input('pricePerPiece')))) {
          $price = floatval(str_replace(',', '', $request->input('pricePerPiece')));
          $msg .= " Price per piece from {$inv->pricePerPiece} to {$price}.";
        }
        if (floatval($inv->bulkPrice) != floatval(str_replace(',', '', $request->input('bulkPrice')))) {
          $price = floatval(str_replace(',', '', $request->input('bulkPrice')));
          $msg .= " Bulk Price from {$inv->bulkPrice} to {$price}.";
        }
        if (floatval($inv->supplierPrice) != floatval(str_replace(',', '', $request->input('supplierPrice')))) {
          $price = floatval(str_replace(',', '', $request->input('supplierPrice')));
          $msg .= " Supplier Price from {$inv->supplierPrice} to {$price}.";
        }
        $trail = new InventoryTrail;
        $trail->productId = $id;
        $trail->type      = "UPDATE";
        $trail->message   = $msg;
        $trail->updatedBy = Auth::user()->id;
        $trail->save();
      } else {
        // TRAILS
        $trail = new InventoryTrail;
        $trail->productId = $id;
        $trail->type      = "UPDATE";
        $trail->message   = "has updated our inventory.";
        $trail->updatedBy = Auth::user()->id;
        $trail->save();
      }

      $inv->medicineName  = $request->input('medicineName');
      $inv->medicineType  = $request->input('productType');
      $inv->pricePerPiece = str_replace(',', '', $request->input('pricePerPiece'));
      $inv->bulkPrice     = str_replace(',', '', $request->input('bulkPrice'));
      $inv->supplierName  = $request->has('supplierName') ? $request->input('supplierName') : '-';
      $inv->supplierPrice = str_replace(',', '', $request->input('supplierPrice'));
      $inv->description   = $request->has('description') ? $request->input('description') : '-';
      $inv->save();
      $pcs = 'piece';
      if ($inv->stockQty > 1) {
        $pcs = 'pcs';
      }
      $json['message'] = "{$inv->medicineName} has been updated.";
      $json['stock'] = $inv;
      $json['is_successful'] = true;
    }
    return response()->json($json);
  }

  public function updateQuantity ($id, Request $request) {
    $inv = Inventory::findOrFail($id);
    try {
      $qty = $request->input('quantity');
      $type = $request->input('type') == "ADD" ? "added" : "removed";
      $pcs = (int) $qty > 1 ? 'pcs' : 'pc';
      if ($request->input('type') == "ADD") {
        $inv->stockQty += (int) $qty;
      } else {
        $inv->stockQty -= (int) $qty;
      }
      $inv->save();
      // TRAILS
      $trail = new InventoryTrail;
      $trail->productId = $id;
      $trail->type      = strtoupper($request->input('type'));
      $trail->message   = "has {$type} {$qty} {$pcs} in our inventory.";
      $trail->updatedBy = Auth::user()->id;
      $trail->save();

      $json['is_successful'] = true;
      $json['message'] = "{$qty} {$pcs} has been {$type} to our inventory.";
      $json['stock'] = $inv;
    } catch (\Exception $e) {
      $json['is_successful'] = false;
    }
    return response()->json($json);
  }

  public function archiveInventory ($id) {
    $inv = Inventory::findOrFail($id);

    try {
      $inv->status = "INACTIVE";
      $inv->save();

      $trail = new InventoryTrail;
      $trail->productId = $id;
      $trail->type      = "ARCHIVE";
      $trail->message   = "has archive this product.";
      $trail->updatedBy = Auth::user()->id;
      $trail->save();

      $json['is_successful'] = true;
      $json['message'] = "{$inv->medicineName} has been archived.";
    } catch (\Exception $e) {
      $json['is_successful'] = false;
    }

    return response()->json($json, 200);
  }
}
