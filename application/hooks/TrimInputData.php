<?php
class TrimInputData {
    public function autoTrim() {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = $this->recursiveTrim($_POST);
        }
        
    }

    private function recursiveTrim($data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->recursiveTrim($value);
            } elseif (is_string($value)) {
                $data[$key] = trim($value);
            }
        }
        return $data;
    }
}
