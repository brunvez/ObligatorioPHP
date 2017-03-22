<?php

namespace Models;

require_once 'BaseModel.php';

class DismissReason extends BaseModel {

    public           $description;
    protected static $table_name = 'dismiss_reasons';
}