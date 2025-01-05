<?php

namespace App\Rules\Hardware\SparePart;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Hardware\SparePart\HWBin;

class BinQuantityValidation implements Rule {

	protected $bin_id = NULL;
	protected $spare_part_id = NULL;
	protected $spare_part_quantity = 0;

    public function __construct($bin_id, $spare_part_id){

		$this->bin_id = $bin_id;
		$this->spare_part_id = $spare_part_id;
    }

 
    public function passes($attribute, $value){

		$objHWBin = new HWBin();
		$this->spare_part_quantity = $objHWBin->get_bin_spare_part_quantity_total($this->bin_id, $this->spare_part_id);

		if($this->spare_part_quantity < $value){

			return FALSE;
			
		}else{

			return TRUE;
		}
		
    }

    
    public function message(){

        return 'Can not release this quantity because this Bin has only';
    }
}
