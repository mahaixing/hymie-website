<?php

echo '<h1>before</h1>';
echo '<pre>';
print_r(apcu_cache_info());
apcu_clear_cache();

echo '<h1>after</h1>';
print_r(apcu_cache_info());
