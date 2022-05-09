<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintController extends Controller
{
	public function printToken($id){
		$data = array();
		$acting_company = Session::get('acting_company');
		$token =  \App\Token::where('id',$id)->where('is_active',1)->with('company')->first();
		if(is_null($token)){
			return redirect('user/generated-token')->with('error','Token Not found');
		}else{
			try {

				$dealer = "";
				if(!is_null(getModelById('Dealer',$token->dealer_id))){
					$dealer = getModelById('Dealer',$token->dealer_id)->name;
				} 
				$warehouse = "";
				if(!is_null(getModelById('Warehouse',$token->warehouse_id))){
					$warehouse = getModelById('Warehouse',$token->warehouse_id)->name;
				} 
				
				$product = getModelById('Product',$token->product_id)->name;
				$quantity = $token->quantity;
				$unit = getModelById('Unit',$token->unit_id)->unit;

				$transporter = "";
				if(!is_null(getModelById('Transporter',$token->transporter_id))){
					$transporter = getModelById('Transporter',$token->transporter_id)->name;
				} 
				$warehouse_keeper = "";
				if(!is_null(getModelById('User',$token->warehouse_keeper_id))){
					$warehouse_keeper = getModelById('User',$token->warehouse_keeper_id)->name;
				} 


				$connector = null;
				$connector = new WindowsPrintConnector("MyPrinter");
				$printer = new Printer($connector);

				$logo = EscposImage::load(public_path()."/assets/images/logo.png", false);
				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				$printer -> graphics($logo);
				$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
				$printer -> text($token->company->name."\n");
				$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
				$printer -> text($token->company->city."\n");

				$qrCodeInfo = $token->id.",".$token->master_rake_id.",".$token->company_id;

				$printer -> qrCode($qrCodeInfo, Printer::QR_ECLEVEL_L,10);

				$printer -> feed();
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
				$printer -> text("Token: ".$token->id." \n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);


				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text("To, ");
				$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
				$printer -> text($dealer. "\n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);

				


				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text("Godown, ");
				$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
				$printer -> text($warehouse. "\n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);

				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text("Name Of Goods, ");
				$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
				$printer -> text($product. "\n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);

				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text($unit.", ");
				$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
				$printer -> text($quantity. "\n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);

				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text("Godown Keeper, ");
				$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
				$printer -> text($warehouse_keeper. "\n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);

				$printer ->setPrintLeftMargin(64);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text("Transporter, ");
				$printer -> selectPrintMode(Printer::MODE_UNDERLINE);
				$printer -> text($transporter. "\n");
				$printer -> selectPrintMode(0);
				$printer ->setPrintLeftMargin(0);


				/* Footer */
				$printer -> feed(3);

				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				$printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
				$printer -> text("FOR : Krishna C&F Agency" . "\n");


				/* Cut the receipt and open the cash drawer */
				$printer -> cut();
	// $printer -> pulse();

				/* Close printer */
				$printer -> close();
			} catch (Exception $e) {
				echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
			}
			$data['token'] = $token;
			return view('dashboard.token.print-token',$data);
			
		} 
	}

}
