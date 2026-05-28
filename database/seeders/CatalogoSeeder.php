<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;
use App\Models\CategoriaPadre;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;

class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();

        // Proveedores
        $provEcoCarton = Proveedor::firstOrCreate(['cif' => 'B12345678'], ['nombre' => 'EcoCartón Ibérica', 'email' => 'distribucion@ecocarton.es', 'telefono' => '910111222', 'direccion' => 'Polígono Industrial Sur, Madrid']);
        $provTetraPack = Proveedor::firstOrCreate(['cif' => 'B87654321'], ['nombre' => 'Sostenibles Tetra SL', 'email' => 'ventas@sosteniblestetra.com', 'telefono' => '930112233', 'direccion' => 'Parque Tecnológico, Barcelona']);
        $provBioEmbalajes = Proveedor::firstOrCreate(['cif' => 'B11223344'], ['nombre' => 'BioEmbalajes Europe', 'email' => 'info@bioembalajes.eu', 'telefono' => '960334455', 'direccion' => 'Zona Franca, Valencia']);

        // Categorías
        $catPadreEnvases = CategoriaPadre::firstOrCreate(['nombre' => 'Envases TetraBrik']);
        $catPadreComplementos = CategoriaPadre::firstOrCreate(['nombre' => 'Complementos de Cartón']);

        $catBebidas = Categoria::firstOrCreate(['nombre' => 'Bebidas y Zumos'], ['categoria_padre_id' => $catPadreEnvases->id, 'descripcion' => 'Envases asépticos ideales para líquidos.']);
        $catAlimentacion = Categoria::firstOrCreate(['nombre' => 'Caldos y Sopas'], ['categoria_padre_id' => $catPadreEnvases->id, 'descripcion' => 'Formatos resistentes a la temperatura.']);
        $catPajitas = Categoria::firstOrCreate(['nombre' => 'Pajitas Ecológicas'], ['categoria_padre_id' => $catPadreComplementos->id, 'descripcion' => 'Pajitas de cartón biodegradable.']);
        $catEmbalaje = Categoria::firstOrCreate(['nombre' => 'Embalaje de Envío'], ['categoria_padre_id' => $catPadreComplementos->id, 'descripcion' => 'Cajas de cartón reciclado adaptadas.']);

        // Productos
        $productos = [
            ['proveedor_id' => $provTetraPack->id, 'user_id' => $admin->id, 'nombre' => 'Envase TetraBrik 1L (Pack 1000 ud)', 'descripcion' => 'El formato clásico de 1 litro. Fabricado con un 75% de cartón procedente de bosques certificados FSC.', 'precio' => 120.50, 'stock' => 50, 'imagen_url' => 'productos/brik_1litro.jpg', 'categorias' => [$catBebidas->id]],
            ['proveedor_id' => $provTetraPack->id, 'user_id' => $admin->id, 'nombre' => 'TetraBrik Caldos 500ml (Pack 500 ud)', 'descripcion' => 'Envase compacto diseñado específicamente para caldos y sopas. Interior reforzado.', 'precio' => 85.00, 'stock' => 120, 'imagen_url' => 'productos/brik_caldo500.jpg', 'categorias' => [$catAlimentacion->id]],
            ['proveedor_id' => $provEcoCarton->id, 'user_id' => $admin->id, 'nombre' => 'Pajitas de Cartón Bio (Caja 5000 ud)', 'descripcion' => 'Pajitas de papel kraft natural, resistentes a los líquidos. Biodegradables y compostables.', 'precio' => 35.90, 'stock' => 200, 'imagen_url' => 'productos/pajitas_carton.jpg', 'categorias' => [$catPajitas->id]],
            ['proveedor_id' => $provBioEmbalajes->id, 'user_id' => $admin->id, 'nombre' => 'Caja Transporte para Briks 1L (Pack 50 ud)', 'descripcion' => 'Caja de cartón ondulado reciclado diseñada para almacenar hasta 12 TetraBriks.', 'precio' => 42.00, 'stock' => 80, 'imagen_url' => 'productos/caja_transporte.jpg', 'categorias' => [$catEmbalaje->id]],
        ];

        foreach ($productos as $prodData) {
            $categoriasArr = $prodData['categorias'];
            unset($prodData['categorias']);

            // Usamos firstOrCreate para evitar duplicados si lanzas el seeder varias veces
            $producto = Producto::firstOrCreate(['nombre' => $prodData['nombre']], $prodData);
            $producto->categorias()->sync($categoriasArr);
        }
    }
}
