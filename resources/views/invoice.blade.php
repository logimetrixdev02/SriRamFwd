<ENVELOPE>
 <HEADER>
  <TALLYREQUEST>Import Data</TALLYREQUEST>
</HEADER>
<BODY>
  <IMPORTDATA>
   <REQUESTDESC>
    <REPORTNAME>Vouchers</REPORTNAME>
    <STATICVARIABLES>
      <SVCURRENTCOMPANY>{{$invoice->company->name}}</SVCURRENTCOMPANY>
    </STATICVARIABLES>
  </REQUESTDESC>
  <REQUESTDATA>
    <TALLYMESSAGE xmlns:UDF="TallyUDF">
      <VOUCHER REMOTEID="11111bfd-9f57-4d72-b602-c066c1e6da56-000011fb" VCHKEY="11111bfd-9f57-4d72-b602-c066c1e6da56-0000aa98:00000068" VCHTYPE="GST Invoice" ACTION="Create" OBJVIEW="Invoice Voucher View">
        <OLDAUDITENTRYIDS.LIST TYPE="Number">
        <OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
      </OLDAUDITENTRYIDS.LIST>
      <DATE>{{date('Ymd',strtotime($invoice->created_at))}}</DATE>
      <GUID></GUID>
      <STATENAME>Uttar Pradesh</STATENAME>
      <COUNTRYOFRESIDENCE>India</COUNTRYOFRESIDENCE>
      <PARTYGSTIN>{{$invoice->retailer->gst_number}}</PARTYGSTIN>
      <PARTYNAME>{{$invoice->retailer_name}}</PARTYNAME>
      <VOUCHERTYPENAME>GST Invoice</VOUCHERTYPENAME>
      <VOUCHERNUMBER>{{$invoice->invoice_number}}</VOUCHERNUMBER>
      <PARTYLEDGERNAME>{{$invoice->retailer_name}}</PARTYLEDGERNAME>
      <BASICBASEPARTYNAME>{{$invoice->retailer_name}}</BASICBASEPARTYNAME>
      <CSTFORMISSUETYPE/>
      <CSTFORMRECVTYPE/>
      <FBTPAYMENTTYPE>Default</FBTPAYMENTTYPE>
      <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
      <PLACEOFSUPPLY>Uttar Pradesh</PLACEOFSUPPLY>
      <CONSIGNEEGSTIN>{{$invoice->retailer->gst_number}}</CONSIGNEEGSTIN>
      <BASICBUYERNAME>{{$invoice->retailer_name}}</BASICBUYERNAME>
      <BASICDATETIMEOFINVOICE>{{date('d-M-Y H:i',strtotime($invoice->created_at))}}</BASICDATETIMEOFINVOICE>
      <BASICDATETIMEOFREMOVAL>{{date('d-M-Y H:i',strtotime($invoice->created_at))}}</BASICDATETIMEOFREMOVAL>
      <VCHGSTCLASS/>
      <CONSIGNEESTATENAME>Uttar Pradesh</CONSIGNEESTATENAME>
      <DIFFACTUALQTY>No</DIFFACTUALQTY>
      <ISMSTFROMSYNC>No</ISMSTFROMSYNC>
      <ASORIGINAL>No</ASORIGINAL>
      <AUDITED>No</AUDITED>
      <FORJOBCOSTING>No</FORJOBCOSTING>
      <ISOPTIONAL>No</ISOPTIONAL>
      <EFFECTIVEDATE>{{date('Ymd',strtotime($invoice->created_at))}}</EFFECTIVEDATE>
      <USEFOREXCISE>No</USEFOREXCISE>
      <ISFORJOBWORKIN>No</ISFORJOBWORKIN>
      <ALLOWCONSUMPTION>No</ALLOWCONSUMPTION>
      <USEFORINTEREST>No</USEFORINTEREST>
      <USEFORGAINLOSS>No</USEFORGAINLOSS>
      <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
      <USEFORCOMPOUND>No</USEFORCOMPOUND>
      <USEFORSERVICETAX>No</USEFORSERVICETAX>
      <ISEXCISEVOUCHER>No</ISEXCISEVOUCHER>
      <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
      <USEFORTAXUNITTRANSFER>No</USEFORTAXUNITTRANSFER>
      <IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>
      <EXCISEOPENING>No</EXCISEOPENING>
      <USEFORFINALPRODUCTION>No</USEFORFINALPRODUCTION>
      <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
      <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
      <ISTDSTCSCASHVCH>No</ISTDSTCSCASHVCH>
      <INCLUDEADVPYMTVCH>No</INCLUDEADVPYMTVCH>
      <ISSUBWORKSCONTRACT>No</ISSUBWORKSCONTRACT>
      <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
      <IGNOREORIGVCHDATE>No</IGNOREORIGVCHDATE>
      <ISVATPAIDATCUSTOMS>No</ISVATPAIDATCUSTOMS>
      <ISDECLAREDTOCUSTOMS>No</ISDECLAREDTOCUSTOMS>
      <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
      <ISISDVOUCHER>No</ISISDVOUCHER>
      <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
      <ISEXCISESUPPLYVCH>No</ISEXCISESUPPLYVCH>
      <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
      <GSTNOTEXPORTED>No</GSTNOTEXPORTED>
      <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
      <ISVATPRINCIPALACCOUNT>No</ISVATPRINCIPALACCOUNT>
      <ISBOENOTAPPLICABLE>No</ISBOENOTAPPLICABLE>
      <ISSHIPPINGWITHINSTATE>No</ISSHIPPINGWITHINSTATE>
      <ISOVERSEASTOURISTTRANS>No</ISOVERSEASTOURISTTRANS>
      <ISDESIGNATEDZONEPARTY>No</ISDESIGNATEDZONEPARTY>
      <ISCANCELLED>No</ISCANCELLED>
      <HASCASHFLOW>No</HASCASHFLOW>
      <ISPOSTDATED>No</ISPOSTDATED>
      <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
      <ISINVOICE>Yes</ISINVOICE>
      <MFGJOURNAL>No</MFGJOURNAL>
      <HASDISCOUNTS>No</HASDISCOUNTS>
      <ASPAYSLIP>No</ASPAYSLIP>
      <ISCOSTCENTRE>No</ISCOSTCENTRE>
      <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
      <ISEXCISEMANUFACTURERON>No</ISEXCISEMANUFACTURERON>
      <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
      <ISVOID>No</ISVOID>
      <ISONHOLD>No</ISONHOLD>
      <ORDERLINESTATUS>No</ORDERLINESTATUS>
      <VATISAGNSTCANCSALES>No</VATISAGNSTCANCSALES>
      <VATISPURCEXEMPTED>No</VATISPURCEXEMPTED>
      <ISVATRESTAXINVOICE>No</ISVATRESTAXINVOICE>
      <VATISASSESABLECALCVCH>No</VATISASSESABLECALCVCH>
      <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
      <ISDELIVERYSAMEASCONSIGNEE>No</ISDELIVERYSAMEASCONSIGNEE>
      <ISDISPATCHSAMEASCONSIGNOR>No</ISDISPATCHSAMEASCONSIGNOR>
      <ISDELETED>No</ISDELETED>
      <CHANGEVCHMODE>No</CHANGEVCHMODE>
      <ALTERID> 6921</ALTERID>
      <MASTERID> 4603</MASTERID>
      <VOUCHERKEY>187569811751016</VOUCHERKEY>
      <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
      <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
      <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
      <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
      <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>

      @if(!is_null($invoice->eway_bill_number))

      <EWAYBILLDETAILS.LIST>
      <CONSIGNORADDRESS.LIST TYPE="String">
      <CONSIGNORADDRESS>{{$invoice->company->address1}}</CONSIGNORADDRESS>
    </CONSIGNORADDRESS.LIST>
    <BILLDATE>{{date('Ymd',strtotime($invoice->created_at))}}</BILLDATE>
    <DOCUMENTTYPE>Tax Invoice</DOCUMENTTYPE>
    <CONSIGNEEGSTIN>{{$invoice->retailer->gst_number}}</CONSIGNEEGSTIN>
    <CONSIGNEESTATENAME>Uttar Pradesh</CONSIGNEESTATENAME>
    <CONSIGNEEPINCODE>261207</CONSIGNEEPINCODE>
    <BILLNUMBER>{{$invoice->eway_bill_number}}</BILLNUMBER>
    <SUBTYPE>Supply</SUBTYPE>
    <BILLSTATUS>Generated by me</BILLSTATUS>
    <CONSIGNORNAME>{{$invoice->company->name}}</CONSIGNORNAME>
    <CONSIGNORPINCODE>261001</CONSIGNORPINCODE>
    <CONSIGNORGSTIN>09AABCG7302H1ZS</CONSIGNORGSTIN>
    <CONSIGNORSTATENAME>Uttar Pradesh</CONSIGNORSTATENAME>
    <CONSIGNEENAME>{{$invoice->retailer_name}}</CONSIGNEENAME>
    <SHIPPEDFROMSTATE>Uttar Pradesh</SHIPPEDFROMSTATE>
    <SHIPPEDTOSTATE>Uttar Pradesh</SHIPPEDTOSTATE>
    <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
    <TRANSPORTDETAILS.LIST>
    <DOCUMENTDATE>{{date('Ymd',strtotime($invoice->created_at))}}</DOCUMENTDATE>
    <TRANSPORTERNAME>{{$invoice->despatched_through}}</TRANSPORTERNAME>
    <TRANSPORTMODE>Road</TRANSPORTMODE>
    <VEHICLENUMBER></VEHICLENUMBER>
    <DOCUMENTNUMBER>2525</DOCUMENTNUMBER>
    <VEHICLETYPE>Regular</VEHICLETYPE>
    <IGNOREVEHICLENOVALIDATION>No</IGNOREVEHICLENOVALIDATION>
    <DISTANCE> </DISTANCE>
  </TRANSPORTDETAILS.LIST>
</EWAYBILLDETAILS.LIST>
@endif


<INVOICEDELNOTES.LIST>      </INVOICEDELNOTES.LIST>
<INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
<INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
<ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
<ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
<INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
<LEDGERENTRIES.LIST>
<OLDAUDITENTRYIDS.LIST TYPE="Number">
<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
</OLDAUDITENTRYIDS.LIST>
<LEDGERNAME>{{$invoice->retailer->name}}</LEDGERNAME>
<GSTCLASS/>
<ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
<LEDGERFROMITEM>No</LEDGERFROMITEM>
<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
<ISPARTYLEDGER>Yes</ISPARTYLEDGER>
<ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
<AMOUNT>-{{$invoice->total}}</AMOUNT>
<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
<BILLALLOCATIONS.LIST>
<NAME>{{$invoice->invoice_number}}</NAME>
<BILLTYPE>New Ref</BILLTYPE>
<TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
<AMOUNT>-{{$invoice->total}}</AMOUNT>
<INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
<STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
</BILLALLOCATIONS.LIST>
<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
<RATEDETAILS.LIST>       </RATEDETAILS.LIST>
<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
</LEDGERENTRIES.LIST>
<LEDGERENTRIES.LIST>
<OLDAUDITENTRYIDS.LIST TYPE="Number">
<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
</OLDAUDITENTRYIDS.LIST>
<ROUNDTYPE/>

@php

$new_product_rate = $invoice->rate - $invoice->freight_discount;
$new_product_rate = $new_product_rate * 100 / (100 + $invoice->product_details->cgst + $invoice->product_details->sgst);
$total_amount = $new_product_rate * $invoice->quantity;


$cgst_percentage = $invoice->product_details->cgst;
$sgst_percentage = $invoice->product_details->sgst;
$cgst_amount = ($cgst_percentage / 100) * $total_amount;
$sgst_amount = ($sgst_percentage / 100) * $total_amount;

@endphp
<LEDGERNAME>CGST</LEDGERNAME>
<GSTCLASS/>
<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
<LEDGERFROMITEM>No</LEDGERFROMITEM>
<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
<ISPARTYLEDGER>No</ISPARTYLEDGER>
<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
<AMOUNT>{{$sgst_amount}}</AMOUNT>
<VATEXPAMOUNT>{{$sgst_amount}}</VATEXPAMOUNT>
<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
<BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
<RATEDETAILS.LIST>       </RATEDETAILS.LIST>
<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
</LEDGERENTRIES.LIST>
<LEDGERENTRIES.LIST>
<OLDAUDITENTRYIDS.LIST TYPE="Number">
<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
</OLDAUDITENTRYIDS.LIST>
<ROUNDTYPE/>
<LEDGERNAME>SGST</LEDGERNAME>
<GSTCLASS/>
<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
<LEDGERFROMITEM>No</LEDGERFROMITEM>
<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
<ISPARTYLEDGER>No</ISPARTYLEDGER>
<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
<AMOUNT>{{$cgst_amount}}</AMOUNT>
<VATEXPAMOUNT>{{$cgst_amount}}</VATEXPAMOUNT>
<SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
<BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
<BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
<INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
<OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
<ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
<AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
<INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
<EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
<RATEDETAILS.LIST>       </RATEDETAILS.LIST>
<SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
<STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
<EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
<TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
<TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
<VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
<COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
<INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
<VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
<ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
</LEDGERENTRIES.LIST>
<ALLINVENTORYENTRIES.LIST>
<STOCKITEMNAME>{{$invoice->product}}</STOCKITEMNAME>
<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
<ISAUTONEGATE>No</ISAUTONEGATE>
<ISCUSTOMSCLEARANCE>No</ISCUSTOMSCLEARANCE>
<ISTRACKCOMPONENT>No</ISTRACKCOMPONENT>
<ISTRACKPRODUCTION>No</ISTRACKPRODUCTION>
<ISPRIMARYITEM>No</ISPRIMARYITEM>
<ISSCRAP>No</ISSCRAP>

<RATE>{{$new_product_rate}}/{{$invoice->unit}}</RATE>
<AMOUNT>{{$total_amount}}</AMOUNT>
<ACTUALQTY> {{$invoice->quantity}} {{$invoice->unit}}</ACTUALQTY>
<BILLEDQTY> {{$invoice->quantity}} {{$invoice->unit}}</BILLEDQTY>
<INCLVATRATE>{{$new_product_rate}}/{{$invoice->unit}}</INCLVATRATE>
<BATCHALLOCATIONS.LIST>
<GODOWNNAME>Main Location</GODOWNNAME>
<BATCHNAME>Primary Batch</BATCHNAME>
<DESTINATIONGODOWNNAME>Main Location</DESTINATIONGODOWNNAME>
<INDENTNO/>
<ORDERNO/>
<TRACKINGNUMBER/>
<DYNAMICCSTISCLEARED>No</DYNAMICCSTISCLEARED>
<AMOUNT>{{$total_amount}}</AMOUNT>
<ACTUALQTY> {{$invoice->quantity}} {{$invoice->unit}}</ACTUALQTY>
<BILLEDQTY> {{$invoice->quantity}} {{$invoice->unit}}</BILLEDQTY>
<INCLVATRATE>{{$new_product_rate}}/{{$invoice->unit}}</INCLVATRATE>
<ADDITIONALDETAILS.LIST>        </ADDITIONALDETAILS.LIST>
<VOUCHERCOMPONENTLIST.LIST>        </VOUCHERCOMPONENTLIST.LIST>
</BATCHALLOCATIONS.LIST>
<ACCOUNTINGALLOCATIONS.LIST>
<OLDAUDITENTRYIDS.LIST TYPE="Number">
<OLDAUDITENTRYIDS>-1</OLDAUDITENTRYIDS>
</OLDAUDITENTRYIDS.LIST>
@php
$gst = '@'. ($invoice->product_details->cgst + $invoice->product_details->sgst);
@endphp
<LEDGERNAME>Sales {{$gst}}% (GST)</LEDGERNAME>
<GSTCLASS/>
<ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
<LEDGERFROMITEM>No</LEDGERFROMITEM>
<REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
<ISPARTYLEDGER>No</ISPARTYLEDGER>
<ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
<ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
<ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
<AMOUNT>{{$total_amount}}</AMOUNT>
<SERVICETAXDETAILS.LIST>        </SERVICETAXDETAILS.LIST>
<BANKALLOCATIONS.LIST>        </BANKALLOCATIONS.LIST>
<BILLALLOCATIONS.LIST>        </BILLALLOCATIONS.LIST>
<INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
<OLDAUDITENTRIES.LIST>        </OLDAUDITENTRIES.LIST>
<ACCOUNTAUDITENTRIES.LIST>        </ACCOUNTAUDITENTRIES.LIST>
<AUDITENTRIES.LIST>        </AUDITENTRIES.LIST>
<INPUTCRALLOCS.LIST>        </INPUTCRALLOCS.LIST>
<DUTYHEADDETAILS.LIST>        </DUTYHEADDETAILS.LIST>
<EXCISEDUTYHEADDETAILS.LIST>        </EXCISEDUTYHEADDETAILS.LIST>
<RATEDETAILS.LIST>        </RATEDETAILS.LIST>
<SUMMARYALLOCS.LIST>        </SUMMARYALLOCS.LIST>
<STPYMTDETAILS.LIST>        </STPYMTDETAILS.LIST>
<EXCISEPAYMENTALLOCATIONS.LIST>        </EXCISEPAYMENTALLOCATIONS.LIST>
<TAXBILLALLOCATIONS.LIST>        </TAXBILLALLOCATIONS.LIST>
<TAXOBJECTALLOCATIONS.LIST>        </TAXOBJECTALLOCATIONS.LIST>
<TDSEXPENSEALLOCATIONS.LIST>        </TDSEXPENSEALLOCATIONS.LIST>
<VATSTATUTORYDETAILS.LIST>        </VATSTATUTORYDETAILS.LIST>
<COSTTRACKALLOCATIONS.LIST>        </COSTTRACKALLOCATIONS.LIST>
<REFVOUCHERDETAILS.LIST>        </REFVOUCHERDETAILS.LIST>
<INVOICEWISEDETAILS.LIST>        </INVOICEWISEDETAILS.LIST>
<VATITCDETAILS.LIST>        </VATITCDETAILS.LIST>
<ADVANCETAXDETAILS.LIST>        </ADVANCETAXDETAILS.LIST>
</ACCOUNTINGALLOCATIONS.LIST>
<DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
<SUPPLEMENTARYDUTYHEADDETAILS.LIST>       </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
<TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
<REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
<EXCISEALLOCATIONS.LIST>       </EXCISEALLOCATIONS.LIST>
<EXPENSEALLOCATIONS.LIST>       </EXPENSEALLOCATIONS.LIST>
</ALLINVENTORYENTRIES.LIST>
<PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
<ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
<GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>
<GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>
<TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>
</VOUCHER>
</TALLYMESSAGE>
<TALLYMESSAGE xmlns:UDF="TallyUDF">
 <COMPANY>
  <REMOTECMPINFO.LIST MERGE="Yes">
  <NAME>11111bfd-9f57-4d72-b602-c066c1e6da56</NAME>
  <REMOTECMPNAME>{{$invoice->company->name}}</REMOTECMPNAME>
  <REMOTECMPSTATE>Uttar Pradesh</REMOTECMPSTATE>
</REMOTECMPINFO.LIST>
</COMPANY>
</TALLYMESSAGE>
<TALLYMESSAGE xmlns:UDF="TallyUDF">
 <COMPANY>
  <REMOTECMPINFO.LIST MERGE="Yes">
  <NAME>11111bfd-9f57-4d72-b602-c066c1e6da56</NAME>
  <REMOTECMPNAME>{{$invoice->company->name}}</REMOTECMPNAME>
  <REMOTECMPSTATE>Uttar Pradesh</REMOTECMPSTATE>
</REMOTECMPINFO.LIST>
</COMPANY>
</TALLYMESSAGE>
</REQUESTDATA>
</IMPORTDATA>
</BODY>
</ENVELOPE>