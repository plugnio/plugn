<?php

namespace common\models\shipping\util;

abstract class WeightPackStrategy
{
    private $packable_items	=	array();
    private $max_weight;
    public 	$pack_util;
    private $result;

    public function __construct(){
        $this->pack_util	=	new WeightPacketUtil();
    }

    public function set_max_weight($max_weight){
        $this->max_weight	=	$max_weight;
    }

    public function get_max_weight(){
        return $this->max_weight;
    }

    public function set_result($result){
        $this->result	=	$result;
    }

    public function get_result(){
        return $this->result;
    }

    public function get_packable_items(){
        return $this->packable_items;
    }

    public function add_item($item_weight, $item_data, $quantity=1){
        for($i=0;$i<$quantity;$i++){
            $this->packable_items[]=array(
                'weight'	=>	$item_weight,
                'data'		=>	$item_data
            );
        }
    }

    abstract public function pack_items();
}