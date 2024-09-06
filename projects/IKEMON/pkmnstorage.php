<?php 
include("storage2.php");

class PkmnStorage extends Storage2 {
    public function __construct() {
        parent::__construct(new JsonIO2('cards.json'));
    }
}
?>