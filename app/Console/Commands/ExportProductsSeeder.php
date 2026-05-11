<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ExportProductsSeeder extends Command
{
    protected $signature = 'products:export-seeder {--class=SharedProductsSeeder : Seeder class name to generate}';

    protected $description = 'Export the current products and product options into a shareable Laravel seeder.';

    public function handle(): int
    {
        $class = Str::studly((string) $this->option('class')) ?: 'SharedProductsSeeder';
        $path = database_path("seeders/{$class}.php");

        if (!Schema::hasTable('products')) {
            $this->error('The products table does not exist. Run migrations first.');

            return self::FAILURE;
        }

        $productColumns = Schema::getColumnListing('products');
        $flavorColumns = Schema::hasTable('product_flavors')
            ? Schema::getColumnListing('product_flavors')
            : [];

        $products = Product::query()
            ->with(Schema::hasTable('product_flavors') ? ['flavors'] : [])
            ->orderBy('id')
            ->get()
            ->map(function (Product $product) use ($productColumns, $flavorColumns): array {
                $data = collect($product->getAttributes())
                    ->except(['id', 'category_id', 'created_at', 'updated_at', 'deleted_at'])
                    ->only($productColumns)
                    ->all();

                if (in_array('tags', $productColumns, true)) {
                    $data['tags'] = $product->tags;
                }

                if (in_array('nicotine_strengths', $productColumns, true)) {
                    $data['nicotine_strengths'] = $product->nicotine_strengths;
                }

                return [
                    'attributes' => ['name' => $product->name],
                    'data' => $data,
                    'flavors' => Schema::hasTable('product_flavors')
                        ? $product->flavors->map(function ($flavor) use ($flavorColumns): array {
                            return collect($flavor->getAttributes())
                                ->except(['id', 'product_id', 'created_at', 'updated_at', 'deleted_at'])
                                ->only($flavorColumns)
                                ->all();
                        })->values()->all()
                        : [],
                ];
            })
            ->values()
            ->all();

        $contents = $this->buildSeeder($class, $products);

        File::put($path, $contents);

        $this->info("Exported " . count($products) . " products to database/seeders/{$class}.php");
        $this->line("Commit that seeder file, then your groupmates can run: php artisan db:seed --class={$class}");

        return self::SUCCESS;
    }

    private function buildSeeder(string $class, array $products): string
    {
        $productsExport = $this->shortArrayExport($products);

        return <<<PHP
<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class {$class} extends Seeder
{
    public function run(): void
    {
        foreach (\$this->products() as \$item) {
            \$data = \$this->onlyExistingColumns('products', \$item['data']);

            if (Schema::hasTable('categories') && Schema::hasColumn('products', 'category_id')) {
                \$categoryName = \$data['category'] ?? null;

                if (\$categoryName) {
                    \$category = Category::firstOrCreate(
                        ['slug' => Str::slug(\$categoryName)],
                        ['name' => \$categoryName, 'is_active' => true]
                    );

                    \$data['category_id'] = \$category->id;
                }
            }

            \$product = Product::updateOrCreate(\$item['attributes'], \$data);

            if (Schema::hasTable('product_flavors')) {
                \$names = [];

                foreach (\$item['flavors'] as \$flavor) {
                    \$flavor = \$this->onlyExistingColumns('product_flavors', \$flavor);

                    if (empty(\$flavor['name'])) {
                        continue;
                    }

                    \$names[] = \$flavor['name'];
                    \$product->flavors()->updateOrCreate(
                        ['name' => \$flavor['name']],
                        \$flavor
                    );
                }

                if (\$names !== []) {
                    \$product->flavors()->whereNotIn('name', \$names)->delete();
                    \$product->syncStockFromFlavors();
                }
            }
        }
    }

    private function onlyExistingColumns(string \$table, array \$data): array
    {
        return collect(\$data)
            ->filter(fn (\$value, string \$column) => Schema::hasColumn(\$table, \$column))
            ->all();
    }

    private function products(): array
    {
        return {$productsExport};
    }
}
PHP;
    }

    private function shortArrayExport(array $data): string
    {
        $export = var_export($data, true);
        $export = preg_replace('/^([ ]*)array \\(/m', '$1[', $export);
        $export = preg_replace('/\\)(,?)$/m', ']$1', $export);

        return $export;
    }
}
