<?php

namespace mock;

function readline(...$args) {
    echo "readline" . PHP_EOL;
    return \readline(...$args);
}

function writeln($str){
    echo $str;
}
