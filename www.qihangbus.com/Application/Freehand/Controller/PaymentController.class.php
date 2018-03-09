<?php
namespace Freehand\Controller;
use Think\Controller;
class PaymentController extends CommonController {
    public function index(){
        $this->display("payment/index");
    }
}