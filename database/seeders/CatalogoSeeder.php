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

        // --- 1. PROVEEDORES (3 Existentes + 3 Nuevos) ---
        $provEcoCarton = Proveedor::firstOrCreate(['cif' => 'B12345678'], ['nombre' => 'EcoCartón Ibérica', 'email' => 'distribucion@ecocarton.es', 'telefono' => '910111222', 'direccion' => 'Polígono Industrial Sur, Madrid']);
        $provTetraPack = Proveedor::firstOrCreate(['cif' => 'B87654321'], ['nombre' => 'Sostenibles Tetra SL', 'email' => 'ventas@sosteniblestetra.com', 'telefono' => '930112233', 'direccion' => 'Parque Tecnológico, Barcelona']);
        $provBioEmbalajes = Proveedor::firstOrCreate(['cif' => 'B11223344'], ['nombre' => 'BioEmbalajes Europe', 'email' => 'info@bioembalajes.eu', 'telefono' => '960334455', 'direccion' => 'Zona Franca, Valencia']);

        // Nuevos
        $provCartonajes = Proveedor::firstOrCreate(['cif' => 'A55667788'], ['nombre' => 'Cartonajes Sostenibles S.A.', 'email' => 'contacto@cartonajessostenibles.es', 'telefono' => '910555666', 'direccion' => 'Polígono Norte, Sevilla']);
        $provGreenPack = Proveedor::firstOrCreate(['cif' => 'B99887766'], ['nombre' => 'GreenPack Solutions', 'email' => 'sales@greenpack.com', 'telefono' => '930888777', 'direccion' => 'Bilbao, Vizcaya']);
        $provNaturaBrik = Proveedor::firstOrCreate(['cif' => 'B11122233'], ['nombre' => 'NaturaBrik Global', 'email' => 'hello@naturabrik.com', 'telefono' => '960111222', 'direccion' => 'Málaga, Andalucía']);

        // --- 2. CATEGORÍAS PADRE (2 Existentes + 2 Nuevas) ---
        $catPadreEnvases = CategoriaPadre::firstOrCreate(['nombre' => 'Envases TetraBrik']);
        $catPadreComplementos = CategoriaPadre::firstOrCreate(['nombre' => 'Complementos de Cartón']);
        $catPadreVajilla = CategoriaPadre::firstOrCreate(['nombre' => 'Vajilla Ecológica']);
        $catPadreOficina = CategoriaPadre::firstOrCreate(['nombre' => 'Oficina y Diseño']);

        // --- 3. SUBCATEGORÍAS ---
        $catBebidas = Categoria::firstOrCreate(['nombre' => 'Bebidas y Zumos'], ['categoria_padre_id' => $catPadreEnvases->id, 'descripcion' => 'Envases asépticos ideales para líquidos.']);
        $catAlimentacion = Categoria::firstOrCreate(['nombre' => 'Caldos y Sopas'], ['categoria_padre_id' => $catPadreEnvases->id, 'descripcion' => 'Formatos resistentes a la temperatura.']);

        $catPajitas = Categoria::firstOrCreate(['nombre' => 'Pajitas Ecológicas'], ['categoria_padre_id' => $catPadreComplementos->id, 'descripcion' => 'Pajitas de cartón biodegradable.']);
        $catEmbalaje = Categoria::firstOrCreate(['nombre' => 'Embalaje de Envío'], ['categoria_padre_id' => $catPadreComplementos->id, 'descripcion' => 'Cajas de cartón reciclado adaptadas.']);
        $catTapones = Categoria::firstOrCreate(['nombre' => 'Tapones y Cierres'], ['categoria_padre_id' => $catPadreComplementos->id, 'descripcion' => 'Cierres de bioplástico vegetal.']);

        $catVasos = Categoria::firstOrCreate(['nombre' => 'Vasos de Cartón'], ['categoria_padre_id' => $catPadreVajilla->id, 'descripcion' => 'Vasos térmicos y normales biodegradables.']);
        $catBandejas = Categoria::firstOrCreate(['nombre' => 'Bandejas y Platos'], ['categoria_padre_id' => $catPadreVajilla->id, 'descripcion' => 'Vajilla desechable 100% libre de plásticos.']);

        $catLibretas = Categoria::firstOrCreate(['nombre' => 'Cuadernos Reciclados'], ['categoria_padre_id' => $catPadreOficina->id, 'descripcion' => 'Papel y tapas procedentes de briks reciclados.']);
        $catOrganizadores = Categoria::firstOrCreate(['nombre' => 'Organizadores Automontables'], ['categoria_padre_id' => $catPadreOficina->id, 'descripcion' => 'Mobiliario de escritorio hecho en cartón rígido.']);

        // --- 4. PRODUCTOS (15 Artículos con chicha) ---
        $productos = [
            // Los 4 originales
            ['proveedor_id' => $provTetraPack->id, 'user_id' => $admin->id, 'nombre' => 'Envase TetraBrik 1L (Pack 1000 ud)', 'descripcion' => 'El formato clásico de 1 litro. Fabricado con un 75% de cartón procedente de bosques certificados FSC.', 'precio' => 120.50, 'stock' => 50, 'imagen_url' => 'productos/brik_1litro.jpg', 'categorias' => [$catBebidas->id]],
            ['proveedor_id' => $provTetraPack->id, 'user_id' => $admin->id, 'nombre' => 'TetraBrik Caldos 500ml (Pack 500 ud)', 'descripcion' => 'Envase compacto diseñado específicamente para caldos y sopas. Interior reforzado.', 'precio' => 85.00, 'stock' => 120, 'imagen_url' => 'productos/brik_caldo500.jpg', 'categorias' => [$catAlimentacion->id]],
            ['proveedor_id' => $provEcoCarton->id, 'user_id' => $admin->id, 'nombre' => 'Pajitas de Cartón Bio (Caja 5000 ud)', 'descripcion' => 'Pajitas de papel kraft natural, resistentes a los líquidos. Biodegradables y compostables.', 'precio' => 35.90, 'stock' => 200, 'imagen_url' => 'productos/pajitas_carton.jpg', 'categorias' => [$catPajitas->id]],
            ['proveedor_id' => $provBioEmbalajes->id, 'user_id' => $admin->id, 'nombre' => 'Caja Transporte para Briks 1L (Pack 50 ud)', 'descripcion' => 'Caja de cartón ondulado reciclado diseñada para almacenar hasta 12 TetraBriks.', 'precio' => 42.00, 'stock' => 80, 'imagen_url' => 'productos/caja_transporte.jpg', 'categorias' => [$catEmbalaje->id]],

            // 11 Nuevos productos
            ['proveedor_id' => $provGreenPack->id, 'user_id' => $admin->id, 'nombre' => 'Vaso Cartón Café 250ml (Caja 1000 ud)', 'descripcion' => 'Vasos de cartón de pared simple ideales para café espresso y cortado. No queman al tacto.', 'precio' => 24.50, 'stock' => 300, 'imagen_url' => 'productos/vaso_cafe.jpg', 'categorias' => [$catVasos->id]],
            ['proveedor_id' => $provGreenPack->id, 'user_id' => $admin->id, 'nombre' => 'Vaso Doble Pared Té 400ml (Caja 500 ud)', 'descripcion' => 'Vasos térmicos de doble pared de cartón ondulado. Mantienen el calor del té y evitan quemaduras.', 'precio' => 32.00, 'stock' => 150, 'imagen_url' => 'productos/vaso_te.jpg', 'categorias' => [$catVasos->id]],
            ['proveedor_id' => $provCartonajes->id, 'user_id' => $admin->id, 'nombre' => 'Platos Biodegradables 20cm (Pack 500 ud)', 'descripcion' => 'Platos llanos de pulpa de cartón reciclado, muy rígidos y resistentes a salsas y cortes.', 'precio' => 45.90, 'stock' => 100, 'imagen_url' => 'productos/plato_carton.jpg', 'categorias' => [$catBandejas->id]],
            ['proveedor_id' => $provCartonajes->id, 'user_id' => $admin->id, 'nombre' => 'Bandeja Menú Compartimentada (Pack 200 ud)', 'descripcion' => 'Bandeja de cartón con 3 compartimentos, ideal para comedores escolares ecológicos y catering.', 'precio' => 38.50, 'stock' => 60, 'imagen_url' => 'productos/bandeja_menu.jpg', 'categorias' => [$catBandejas->id]],
            ['proveedor_id' => $provNaturaBrik->id, 'user_id' => $admin->id, 'nombre' => 'Libreta A4 Tapas Recicladas TetraBrik', 'descripcion' => 'Cuaderno ecológico cuyas tapas están fabricadas al 100% con envases TetraBrik reciclados prensados. 80 hojas.', 'precio' => 4.50, 'stock' => 500, 'imagen_url' => 'productos/libreta_tetrabrik.jpg', 'categorias' => [$catLibretas->id]],
            ['proveedor_id' => $provNaturaBrik->id, 'user_id' => $admin->id, 'nombre' => 'Libreta de Bolsillo Kraft (Pack 5 ud)', 'descripcion' => 'Set de 5 libretas tamaño A6 con encuadernación rústica y papel reciclado no blanqueado.', 'precio' => 8.90, 'stock' => 150, 'imagen_url' => 'productos/libreta_kraft.jpg', 'categorias' => [$catLibretas->id]],
            ['proveedor_id' => $provEcoCarton->id, 'user_id' => $admin->id, 'nombre' => 'Organizador de Escritorio Automontable', 'descripcion' => 'Módulo de cartón rígido con 4 cajones y lapicero. Se monta mediante pliegues sin necesidad de pegamento.', 'precio' => 12.00, 'stock' => 90, 'imagen_url' => 'productos/organizador_escritorio.jpg', 'categorias' => [$catOrganizadores->id]],
            ['proveedor_id' => $provTetraPack->id, 'user_id' => $admin->id, 'nombre' => 'Mini Brik Zumo 200ml (Pack 1500 ud)', 'descripcion' => 'Envase individual perfecto para almuerzos infantiles. Incluye orificio pre-troquelado para pajita.', 'precio' => 95.00, 'stock' => 40, 'imagen_url' => 'productos/minibrik_zumo.jpg', 'categorias' => [$catBebidas->id]],
            ['proveedor_id' => $provNaturaBrik->id, 'user_id' => $admin->id, 'nombre' => 'Brik Vino/Mosto 1L Oscuro (Pack 1000 ud)', 'descripcion' => 'Envase TetraBrik con interior metalizado reforzado especial para conservar las propiedades del vino y mosto.', 'precio' => 135.00, 'stock' => 70, 'imagen_url' => 'productos/brik_vino.jpg', 'categorias' => [$catBebidas->id]],
            ['proveedor_id' => $provBioEmbalajes->id, 'user_id' => $admin->id, 'nombre' => 'Tapón de Rosca Bioplástico (Bolsa 5000 ud)', 'descripcion' => 'Cierres de rosca compatibles con briks estándar, fabricados a partir de polímeros vegetales de caña de azúcar.', 'precio' => 65.00, 'stock' => 180, 'imagen_url' => 'productos/tapon_rosca.jpg', 'categorias' => [$catTapones->id]],
            ['proveedor_id' => $provCartonajes->id, 'user_id' => $admin->id, 'nombre' => 'Separador Interior Botellas (Caja 300 ud)', 'descripcion' => 'Celdillas de cartón para cajas de envío, protege hasta 6 botellas o envases de cristal de impactos.', 'precio' => 22.50, 'stock' => 110, 'imagen_url' => 'productos/separador_botellas.jpg', 'categorias' => [$catEmbalaje->id]],
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
