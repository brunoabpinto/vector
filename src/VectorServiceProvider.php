<?php

namespace Vector;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class VectorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->publishAssets();

        // Register precompiler to transform <script vector> blocks
        Blade::precompiler(function (string $string) {
            return Vector::transform($string);
        });

        // Register @vectorJs directive for including runtime scripts
        Blade::directive('vectorJs', function () {
            return "<?php echo \Vector\Vector::runtime(); ?>";
        });
    }

    protected function publishAssets(): void
    {
        $source = __DIR__.'/../resources/js/vector.js';
        $destination = resource_path('js/vendor/vector.js');

        // Auto-publish if the file doesn't exist
        if (! File::exists($destination)) {
            File::ensureDirectoryExists(dirname($destination));
            File::copy($source, $destination);
        }

        // Still register for manual publishing/updating
        $this->publishes([
            $source => $destination,
        ], 'vector-assets');
    }
}
