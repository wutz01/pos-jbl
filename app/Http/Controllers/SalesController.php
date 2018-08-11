<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Orders;
use App\OrderItems;
use App\Senior;
use App\InventoryTrail;
use DataTables;
use Auth;
use Carbon\Carbon;

class SalesController extends Controller
{
  public function __construct () {
    date_default_timezone_set("Asia/Manila");
  }

  public function index () {
    return view('sales.index');
  }

  public function loadCart () {
    $order = Orders::where('serverId', Auth::user()->id)->where('status', 'CURRENT', 'AND')->first();
    $items = [];
    if (count($order)) {
      $items = $order->items()->info()->get();
    }
    return view('sales.orders', compact('order', 'items'));
  }

  public function loadInventory (Request $request) {
    $inventory = Inventory::where('medicineName', 'LIKE', '%' . $request->input('query') . '%')->orWhere('medicineType', 'LIKE', '%' . $request->input('query') . '%')->get();
    $stocks = [];
    foreach ($inventory as $key => $value) {
      $object = (object)[];
      $object->value = $value->medicineName;
      $object->data = $value;
      $stocks[] = $object;
    }
    $json['query'] = $request->input('query');
    $json['suggestions'] = $stocks;
    return response()->json($json, 200);
  }

  public function loadSeniorCitizen (Request $request) {
    $sc = Senior::where('seniorCitizen', 'LIKE', '%' . $request->input('query') . '%')->get();
    $list = [];
    foreach ($sc as $key => $value) {
      $object = (object)[];
      $object->value = $value->seniorCitizen;
      $object->data = $value;
      $list[] = $object;
    }
    $json['query'] = $request->input('query');
    $json['suggestions'] = $list;
    return response()->json($json, 200);
  }

  public function addToCart (Request $request) {
    $order = Orders::where('serverId', Auth::user()->id)->where('status', 'CURRENT', 'AND')->first();
    if (!$order) {
      $order                  = new Orders;
      $order->serverId        = Auth::user()->id;
      $order->totalQuantity   = 0;
      $order->globalDiscount  = 0;
      $order->grossPrice      = 0;
      $order->netPrice        = 0;
      $order->save();
    }
    $stock = Inventory::findOrFail($request->input('productId'));
    if (!$stock) {
      $json['is_successful'] = false;
      $json['message'] = "Product not found";
      return response()->json($json, 404);
    }

    if ((int) $request->input('quantity') > $stock->stockQty) {
      $json['is_successful'] = false;
      $json['message'] = "{$stock->medicineName} is low on stock.";
      return response()->json($json, 200);
    }
    // less stock
    $stock->stockQty -= (int) $request->input('quantity');
    if ($stock->stockQty < 0) {
      // abort stock less
      $json['is_successful'] = false;
      $json['message'] = "{$stock->medicineName} has no stock.";
      return response()->json($json, 200);
    }
    $stock->save();

    $item = new OrderItems;
    $item->orderId        = $order->id;
    $item->productId      = $request->input('productId');
    $item->quantity       = (int) $request->input('quantity');
    $item->isBulk         = $request->input('useBulk') == "true" ? true : false;
    $item->pricePerPiece  = $stock->pricePerPiece;
    $item->bulkPrice      = $stock->bulkPrice;
    $item->discount       = 0;
    $item->totalPrice     = ($request->input('useBulk') == "true" ? $stock->bulkPrice : $stock->pricePerPiece) * (int) $request->input('quantity');
    $item->save();

    $trail = new InventoryTrail;
    $trail->productId = $request->input('productId');
    $trail->type      = "ORDERED";
    $trail->message   = "has sold {$item->quantity} pcs.";
    $trail->updatedBy = Auth::user()->id;
    $trail->save();

    $json['stock']         = $stock;
    $json['is_successful'] = true;
    $json['message']       = "{$stock->medicineName} has been added to our orders";
    return response()->json($json, 200);
  }

  public function updateCart (Request $request) {
    $item = OrderItems::findOrFail($request->input('itemId'));
    $now = Carbon::now();
    $stock = $item->inventory;
    if ($now->diffInMinutes($item->created_at) < 5) {
      // $item->quantity   = (int) $request->input('quantity');
      $item->discount   = (int) $request->input('discount');
      $item->totalPrice = (float) $request->input('totalPrice');
      $item->save();

      $json['is_successful'] = true;
      $json['message']       = "Order: {$stock->medicineName} has been updated";
    } else {
      $json['is_successful'] = false;
      $json['item']          = $item;
      $json['message']       = "Order: {$stock->medicineName} can't receive an update. Item already past 5minutes.";
    }

    return response()->json($json, 200);
  }

  public function updateOrder (Request $request) {
    $order = Orders::where('serverId', Auth::user()->id)->where('status', 'CURRENT', 'AND')->first();
    if ($order) {
      $order->totalQuantity   = (int) $request->input('totalQuantity');
      $order->globalDiscount  = (int) $request->input('globalDiscount');
      $order->grossPrice      = (float) $request->input('grossPrice');
      $order->netPrice        = (float) $request->input('netPrice');
      $order->save();
      $json['is_successful'] = true;
      $json['message']       = "Order has been updated";
    } else {
      $json['is_successful'] = false;
      $json['message']       = "No Order has received an update";
    }
    return response()->json($json, 200);
  }

  public function removeCart (Request $request) {
    $item = OrderItems::findOrFail($request->input('id'));
    $now = Carbon::now();
    $stock = $item->inventory;
    if ($now->diffInMinutes($item->created_at) < 5) {
      $stock->stockQty += (int) $item->quantity;
      $stock->save();

      $trail = new InventoryTrail;
      $trail->productId = $stock->id;
      $trail->type      = "RETURNED";
      $trail->message   = "has returned {$item->quantity} pcs.";
      $trail->updatedBy = Auth::user()->id;
      $trail->save();

      $item->delete();
      $json['is_successful'] = true;
      $json['message']       = "Order: {$stock->medicineName} has been removed";
    } else {
      $json['is_successful'] = false;
      $json['message']       = "Order: {$stock->medicineName} can't be remove. Item already past 5minutes.";
    }
    return response()->json($json, 200);
  }

  public function finalizeOrder (Request $request) {
    $order = Orders::where('serverId', Auth::user()->id)->where('status', 'CURRENT', 'AND')->first();
    $sc    = $request->input('seniorCitizen');
    if ($sc !== '' || !isEmpty($sc)) {
      $senior = Senior::where('seniorCitizen', '=', $sc)->first();
      if (!$senior) {
        $senior = new Senior;
        $senior->seniorCitizen = $sc;
        $senior->save();
      }
      $order->scId   = $senior->id;
    }

    $order->status = "SALE";
    $order->save();
    $json['is_successful'] = true;
    $json['message']       = "Order has been saved. You can now create new order.";

    return response()->json($json, 200);
  }
}
