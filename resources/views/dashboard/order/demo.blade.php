<tbody>
    @foreach($orders as $order)
    <tr id="tr_{{$order->id}}">
        <td>{{$order->id}}</td>
        <td>{{date('d/m/Y',strtotime($order->created_at))}}</td>
        <td>
            <input type="hidden" name="id" id="id_{{ $order->id }}" value="{{ $order->id }}">
            {{$order->order_from == 1 ? 'Rake' : 'Godown' }}
        </td>
        <td>{{getdealer($order->dealer_id)->name}}</td>
        <td>{{getretailer($order->retailer_id)->name}}</td>
        
        <td>{{getModelById('Product',$order->product_id)->name}}</td>
        <td>{{$order->quantity}}</td>
        <td>{{$order->remaining_qty}}</td>
        <td>{{getModelById('Unit',$order->unit_id)->unit}}</td>
        
    
        
        <td id="status_{{$order->id}}">
        @if($role_id != 1)
        
        @if($order->order_status == 'approved')
                <span class="badge badge-success">Approved</span>

                @else
                <span class="badge badge-danger">No Approve</span>

            @endif
        @else
        @if($order->order_status == 'requested')<a class="btn btn-sm btn-warning" onclick="approved_order_now('{{$order->id}}')">Approve</a>@endif  
            @if($order->order_status == 'approved')
                <span class="badge badge-success">Approved</span>
            @endif
        @endif
        
            
            
        </td>
    
        
        <td id="status_{{$order->id}}">
            
            @if($order->order_status == 'approved'  && $order->remaining_qty>0 )
            <a href="javascript:void(0);" onclick="get_add_loading_slip_form({{ $order->id }})">
                <span class="badge badge-info">Generate Loading Slip</span>
            </a>
            
            @endif

            @if($order->order_status == 'approved' &&  $order->remaining_qty==0 )
            <span class="badge badge-success">Genrated</span>
            @endif
            
            
            
            
        </td>
        <td id="status_{{$order->id}}">
        
        @if($order->order_status == 'approved' && $order->remaining_qty == 0 && $order->loading_status==1 )
            <a href="javascript:void(0);" onclick="get_loading_slips({{ $order->id }})">
                <span class="badge badge-warning">Genrate Invoice</span>
            </a>
            @endif

            
        
            @if($order->order_status == 'approved' && $order->remaining_qty==0 && $order->invoice_status==1)
            <a href="javascript:void(0);" onclick="get_loading_slips({{ $order->id }})">
                <span class="badge badge-success">Invoice Generated</span>
            </a>
            @endif
        </td>
        
        <td>
            
            @if($order->order_status == 'requested')
            <a  onclick="get_edit_order_form('{{$order->id}}')" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-pencil bigger-120"></i>
            </a>
            @endif

                
            @if($role_id==1 && $order->order_status == 'approved')
            <a  onclick="get_edit_order_form('{{$order->id}}')" class="btn btn-xs btn-info">
                <i class="ace-icon fa fa-pencil bigger-120"></i>
            </a>
            @endif
            
            @if($order->order_status == 'approved')
            <a href="/user/print-order-token/{{$order->id}}" class="btn btn-xs btn-info" >
                <i class="ace-icon fa fa-print bigger-120"></i>
            </a>
            @endif
        </td>
    </tr>
@endforeach
</tbody>




->addColumn('action', function($row){
    $btn ='<td>';
                                    
    if($orders->order_status == 'requested'){

    $btn=$btn.	'<a  onclick="get_edit_order_form('.$orders->id.')" class="btn btn-xs btn-info">
        <i class="ace-icon fa fa-pencil bigger-120"></i>
    </a>';
    }


        
    if(Auth::user()->role_id==1 && $orders->order_status == 'approved'){
    $btn=$btn.'<a  onclick="get_edit_order_form('.$orders->id.')" class="btn btn-xs btn-info">
        <i class="ace-icon fa fa-pencil bigger-120"></i>
    </a>';
    }	
    if($orders->order_status == 'approved'){
    $btn=$btn.'<a href="/user/print-order-token/'.$orders->id.'" class="btn btn-xs btn-info" >
        <i class="ace-icon fa fa-print bigger-120"></i>
    </a>';
    }
$btn=$btn.'</td>';

        return $btn;
})

->rawColumns(['action'])