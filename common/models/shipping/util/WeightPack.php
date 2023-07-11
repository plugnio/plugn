<?php

namespace common\models\shipping\util;

class WeightPack
{
    private $package_requests;
    private $pack_obj;

    public function __construct( $strategy, $options=array()) {
        $this->pack_obj	=	new WeightPackDescend();
    }

    public function set_max_weight($max_weight){
        $this->pack_obj->set_max_weight($max_weight);
    }

    public function add_item($item_weight,	$item_data,	$quantity){
        $this->pack_obj->add_item($item_weight,	$item_data,	$quantity);
    }

    public function pack_items(){
        $this->pack_obj->pack_items();
        return $this->get_result();
    }

    public function get_packable_items(){
        return $this->pack_obj->get_packable_items();
    }

    public function get_result(){
        return $this->pack_obj->get_result();
    }
}
