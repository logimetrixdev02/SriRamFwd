<?php

namespace App\Exports;
use Exportable;
use App\Report;
use App\Order;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ReportExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $year;
    protected $month;

 function __construct($year,$month) {
        $this->year = $year;
        $this->month = $month;
 }
    public function collection()
    {
       // $dt=array();
        //return Order::all();
        ini_set('max_execution_time', 300);
        $response=DB::select(DB::raw("select dealers.name as dealer_name,retailers.name as retailer_name,products.name as product_name,orders.quantity as qty,orders.id,DATE_FORMAT(orders.created_at,'%d/%m/%y') as date,orders.order_from,orders.rake_point ,orders.from_warehouse_id  from orders  
        JOIN dealers ON dealers.unique_id = orders.dealer_id 
        JOIN retailers ON retailers.unique_code = orders.retailer_id 
        JOIN products ON products.id = orders.product_id 
        
        where MONTH(orders.created_at)=$this->month and YEAR(orders.created_at)=$this->year and orders.is_active=1 and orders.invoice_status=1 GROUP BY orders.product_id,orders.retailer_id,orders.created_at"));
        //return $response;
        //return collect($response);

      //   $orders = \App\Order::where('is_active',1)->whereBetween('created_at',[$from,$to])->orderBy('created_at','desc')->where('dealer_id',$dealer_id)->where('invoice_status',1)->groupBy(['product_id','retailer_id','created_at'])->select('id','retailer_id','product_id',\DB::raw('(DATE_FORMAT(created_at,"%d/%m/%y")) as date'),\DB::raw("SUM(quantity) as qty"))->get();
      //   dd($response);
        //$tn=[];
        // foreach($truck_numbers as $k => $v){
        // array_push($tn,$v);	
        // }
        $n=0;
        $array=[];
       foreach($response as $key=>$val){
        $truck_numbers=\App\LoadingSlip::where('order_id',$val->id)->pluck('vehicle_no');
        $tn=[];
        foreach($truck_numbers as $k => $v){
          array_push($tn,$v);	
        }
        $btn=implode(',',$tn);

            if($val->order_from==1){
               $rake_wharehouse='Rake ( '.getModelById('RakePoint',$val->rake_point)->rake_point.' )';
               

            }else{
               $rake_wharehouse='Godown ( '.getModelById('Warehouse',$val->from_warehouse_id)->name.' )';
            }


        //dd($btn);
        $array[$n]['order_id']=$val->id;

       
        $array[$n]['rake_wharehouse']=$rake_wharehouse ;
        $array[$n]['dealer_name']=$val->dealer_name;
        $array[$n]['retailer_name']=$val->retailer_name;
        $array[$n]['truck_numbers']=$btn;
        $array[$n]['product_name']=$val->product_name;
        $array[$n]['qty']=$val->qty;
        $array[$n]['date']=$val->date;
       
       
        //$id=($r->id);
        //$tn=DB::select(DB::raw("select loading_slips.vehicle_no from loading_slips where order_id=$r->id"));
        $n++;
    }
       
         // dd($array);
         //return $data;
        return collect($array);
       // return Order::getCustom();
    }

    public function headings(): array
    { 
       
       return [
           'Order Id',
           'Rake/Godown',
           'Dealer',
           'Retailer',
           'Truck No.',
           'Product Name',
           'Quantity',
           'Date'
          
       ];
}
}
