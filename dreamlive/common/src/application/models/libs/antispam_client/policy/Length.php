<?php
class Length extends Policy
{
    public function isDirty($content)
    {
        $options = $this->getOptions();
        
        $min = isset($options["min"]) ? $options["min"] : 0;
        $max = isset($options["max"]) ? $options["max"] : 200;
        $len = strlen($content);
        
        return $len > $min && $len < $max ? false : true;
    }
}
?>