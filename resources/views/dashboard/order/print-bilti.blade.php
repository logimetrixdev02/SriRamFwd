<style type="text/css">

    table#table {

        border: 1px solid #b1b1b1;

        width: 100%;

        padding: 10px;

    }



    table#table1 {

        border-left: 1px solid #b1b1b1;

        width: 100%;

        border-right: 1px solid #b1b1b1;

        border-bottom: 1px solid #b1b1b1;

    }



    table#tablelist {

        border-left: 1px solid #b1b1b1;

        width: 100%;

        border-right: 1px solid #b1b1b1;

        border-bottom: 1px solid #b1b1b1;

    }



    table#tablelist th {

        border: 1px solid #b1b1b1;

        text-align: center;

    }



    table#tablelist td {

        border: 1px solid #b1b1b1;     

    }



    td.logo>img {

        width: 100px;

    }



    td.logo {

        width: 5%;

    }



    td.center {

        width: 75%;

        text-align: center;

    }



    td.center>h2 {

        padding: 0;

        margin: 0;

    }



    td.center>p {

        margin: 0;

        padding: 5px 0;

    }



    td.address-left {

        border-right: 1px solid #b1b1b1;

    }



    td.address-right>table {

        width: 100%;

    }



    td.rowb {

        width: 100%;

        border-bottom: 1px solid #929292;

    }



    td.address-left>table {

        width: 100%;

    }



    #tablelist>tbody>tr.noboder>td:nth-child(1) {

        border-bottom: none;

        text-align: right;

    }



    table#footer {

        border: 1px solid #ccc;

        width: 100%;

    }



    table#footer td.footer-left {

        width: 50%;

        padding: 6px 10px;

        border-right: 1px solid #ccc;

        text-align: center;

    }



    table#footer td.footer-right {

        width: 50%;

        padding: 6px 10px;

        text-align: center;

    }



    td.footer-left>h3 {

        margin-bottom: 100px;

    }



    td.rowt {

        border-top: 1px solid #ccc;

        padding: 10px 0;

    }



    td.info-to {

        padding: 10px 0px;

    }



    p.signatory {

        margin-top: 70px;

    }



    tr.noboderleft>td {

        text-align: left !important;

        padding: 5px 5px;

    }



    #printInvoice {

        float: right;

    }



    #table1 P {

        padding: 8px;

    }

</style>



<button onclick="window.print()" id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>

<div id="print-body">

    <table id="table">

        <tr>

            <td colspan="2" align="center">

                <h2>SRI RAM</h2>

                <h3>Forwarding Agency(Regd.)</h3>

                <p>73/10-B, MAHATMA SADAN COOPERGANJ, KANPUR</p>

                <p>Mobile: 9335176123, 9415154434</p>

            </td>

        </tr>

        <tr>

            <td colspan="2">

                <p>PAN NO. ABAFS5831N</p>

            </td>

        </tr>

        <tr>

            <td>

                <p>GSTIN :. 09ABAFS5831N1ZQ</p>

            </td>

            <td align="center">

                <p display="inline">E-WAY BILL NO ............................</p>

            </td>

        </tr>

    </table>



    <table id="table1">

        <tr>

            <td>

                <p> माल भेजने वाले : {{$company->brand_name}} </p>

            </td>

            <td>

                <p> बिल्टी नं : {{$bilti->bilti_no}} </p>

            </td>

        </tr>

        <tr>

            <td>

                <p> माल पाने वाले : {{$dealer->name}} </p>

            </td>

            <td>

                <p> दिनांक : {{date('d/m/Y',strtotime($bilti->created_at))}}</p>

            </td>

        </tr>

        <tr>

            <td>

                <p> ट्रक मालिक का नाम श्री : {{$driverName->name}} </p>

            </td>

            <td>

                <p> डिलीवरी स्थान : {{$order->retailer_address}} </p>

            </td>

        </tr>

        <tr>

            <td>

                <p> ट्रक ड्राइवर का नाम/मोबाइल न श्री : {{$loadingSlip->driver_no}} </p>

            </td>

            <td>

                <p> ट्रक नंबर : {{$loadingSlip->vehicle_no}} </p>

            </td>

        </tr>

    </table>



    <table id="tablelist">

        <thead>

            <tr>

                <th>

                    <p> विवरण </p>

                </th>

                <th>

                    <p> बोरी </p>

                </th>

                <th>

                    <p> वजन </p>

                </th>

                <th>

                    <p> दर </p>

                </th>

                <th>

                    <p> एडवांस </p>

                </th>

                <th colspan="2">

                    <p> बकाया भाड़ा </p>

                </th>

                <th>

                    <p> रिमार्क </p>

                </th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td rowspan="2">

                    <p> {{$product->name}}</p>

                </td>

                <td rowspan="2">

                    <p> {{$order->quantity}} </p>

                </td>

                <td rowspan="2">

                    <p> .......... </p>

                </td>

                <td rowspan="2">

                    <p> .......... </p>

                </td>

                <td rowspan="2">

                    <p> .......... </p>

                </td>

                <td>

                    <p>......</p>

                </td>

                <td>

                    <p>......</p>

                </td>

                <td rowspan="3">

                    <p>............................</p>

                </td>

            </tr>

            <tr>

                <td>

                    <p>........</p>

                </td>

                <td>

                    <p>........</p>

                </td>

            </tr>

            <tr>

                <td rowspan="2" colspan="5">

                    <p>Liability of GST:</p>

                </td>

                <td>

                    <p> 31</p>

                </td>

                <td>

                    <p> 32</p>

                </td>

            </tr>           

            <tr>

                <td rowspan="4" colspan="3">

                    <p>33</p>

                </td>               

            </tr>

            <tr>

                <td colspan="5">

                    <p>Sales Order No.............</p>

                </td>

            </tr>

            <tr>

                <td colspan="5">

                    <p>Delivery No..............</p>

                </td>            

            </tr>

            <tr>

                <td colspan="5">

                    <p>Gate Pass No..............</p> 

                </td>                

            </tr>

        </tbody>

    </table>

    <table id="footer">

        <tr>

            <td>

                <p>

                    मैं उपरोक्त सामान के सामने मै घोषित करता हूं/करते हैं कि इनमें कानून के खिलाफ वस्तु नहीं है|

                </p>

            </td>

            <td text-align="right">

                <p>

                    वास्ते श्रीराम फॉरवर्डिंग एजेंसी

                </p>

            </td>

        </tr>

        <tr height="80px">

            <td ></td>

        </tr>

        <tr>

            <td>

                <p>हस्ताक्षर: ट्रक मालिक/ड्राइवर</p>

            </td>

            <td text-align="center">

                <p>हस्ताक्षर: माल भेजने वाले के</p>

            </td>

            <td text-align="right">

                <p>हस्ताक्षर: बुकिंग क्लर्क</p>

            </td>

        </tr>





    </table>

    



</div>