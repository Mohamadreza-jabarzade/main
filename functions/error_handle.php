<?php
class handle
{
    protected $errors = [];

    public function set_empty_err($error,$err_text)
    {
        $this->errors[$error][] = $err_text;
    }
    public function set_validity_err($error,$err_text){
        $this->errors[$error][] = $err_text;
    }

    public function count_errors(){
        return count($this->errors);
    }
    public function has($error){
        return isset($this->errors[$error]);
    }
    public function get($error)
    {
        if($this->has($error)){
            return $this->errors[$error][0];
        }
        return null;
    }

}
?>