<?php
foreach (glob("../*/*.php") as $file_name) {
    if (!str_contains($file_name, '../php/'))
        include $file_name;
}
