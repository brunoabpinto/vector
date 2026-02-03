<?php

namespace Vector;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class VectorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('vector', function () {
            return '<?php $__vectorId = "vector-" . uniqid(); ob_start(); ?>';
        });

        Blade::directive('endvector', function () {
            return <<<'HTML'
            <?php
            $__vectorContent = ob_get_clean();
            echo \Vector\Vector::render($__vectorId, $__vectorContent);
            ?>
            HTML;
        });
    }
}
