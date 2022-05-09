<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MarketingManagerController extends Controller
{
	public function dashboard(){
		return view('marketing-manager.dashboard');
	}
	public function rake(){
		$data = array();
		$data['master_rakes'] = \App\MasterRake::where('is_active',1)->get();
		return view('marketing-manager.rake',$data);
	}  
	public function rake_tokens(){
		return view('marketing-manager.rake-token');
	} 
	public function rake_loadings(){
		return view('marketing-manager.rake-loadings');
	} 
	public function warehouse(){
		return view('marketing-manager.warehouse');
	}
	public function warehouse_tokens(){
		return view('marketing-manager.warehouse-tokens');
	} 
	public function warehouse_loadings(){
		return view('marketing-manager.warehouse-loadings');
	} 
}
